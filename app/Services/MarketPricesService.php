<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class MarketPricesService
{
    // Cache lifetimes in seconds
    private const CACHE_TTL = [
        'PRICE_DATA' => 300,       // 5 minutes for real-time price data
        'CONVERSION_RATES' => 120, // 2 minutes for currency conversion data
        'MARKET_DATA' => 2592000,      // 1 month for market stats
    ];

    // API configuration
    private const API_CONFIG = [
        'coin_gecko' => [
            'base_url' => 'https://api.coingecko.com/api/v3/',
            'max_retries' => 2,
            'timeout' => 5,
        ],
        'coin_market_cap' => [
            'base_url' => 'https://pro-api.coinmarketcap.com/v1/',
            'max_retries' => 1, // Fewer retries for fallback
            'timeout' => 5,
        ]
    ];

    /**
     * Retrieve gateway currencies from the configuration.
     */
    public function getGateways(): array
    {
        try {
            $wallets = config('gateways.wallet_addresses');
            return is_array($wallets)
                ? collect($wallets)
                    ->filter(fn($wallet) => $wallet['status'] == 1)
                    ->sortBy('status')
                    ->values()
                    ->toArray()
                : [];
        } catch (Throwable $e) {
            Log::error('MarketPricesService getGateways error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get payment gateway details by method code.
     *
     * @param string $payment_method
     * @return array|null
     */
    public function getSingleGateway(string $payment_method): ?array
    {
        try {
            $wallets = config('gateways.wallet_addresses', []);

            return collect($wallets)
                ->firstWhere('method_code', $payment_method);

        } catch (Exception $e) {
            Log::error('Failed to retrieve payment gateway', [
                'method' => $payment_method,
                'error' => $e->getMessage()
            ]);

            return null;
        }
    }

    /**
     * Convert currency with caching and fallback APIs
     */
    public function convertCurrency(float $amount, string $targetCurrency, string $sourceCurrency): array
    {
        $cacheKey = "conversion_{$amount}_{$sourceCurrency}_$targetCurrency";

        return Cache::remember($cacheKey, self::CACHE_TTL['CONVERSION_RATES'], function()
        use ($amount, $targetCurrency, $sourceCurrency) {

            // Try CoinGecko first
            $result = $this->tryCoinGeckoConversion($amount, $targetCurrency, $sourceCurrency);

            // Fallback to CoinMarketCap if needed
            if ($result['status'] !== 'success') {
                $result = $this->tryCoinMarketCapConversion($amount, $targetCurrency, $sourceCurrency);
            }

            return $result;
        });
    }

    /**
     * Attempt conversion using CoinGecko API
     */
    private function tryCoinGeckoConversion(float $amount, string $targetCurrency, string $sourceCurrency): array
    {
        $config = self::API_CONFIG['coin_gecko'];
        $apiUrl = $config['base_url'] . 'simple/price';

        $params = [
            'ids' => $this->getCoinGeckoId($sourceCurrency),
            'vs_currencies' => strtolower($targetCurrency),
            'precision' => 6,
        ];

        return $this->makeApiRequest(
            'coin_gecko',
            $apiUrl,
            $params,
            function($response) use ($amount, $targetCurrency) {
                $data = $response->json();
                $targetCurrencyLower = strtolower($targetCurrency);

                if (empty($data)) {
                    return ['status' => 'error', 'message' => 'Empty response from CoinGecko'];
                }

                $firstKey = array_key_first($data);
                if (isset($data[$firstKey][$targetCurrencyLower])) {
                    $rate = $data[$firstKey][$targetCurrencyLower];
                    $converted = $amount > 0 ? $amount * $rate : 0;
                    return [
                        'status' => 'success',
                        'converted' => number_format($converted, 6),
                        'source' => 'CoinGecko'
                    ];
                }

                return ['status' => 'error', 'message' => 'Currency not found in response'];
            }
        );
    }

    /**
     * Attempt conversion using CoinMarketCap API
     */
    private function tryCoinMarketCapConversion(float $amount, string $targetCurrency, string $sourceCurrency): array
    {
        $config = self::API_CONFIG['coin_market_cap'];
        $apiUrl = $config['base_url'] . 'tools/price-conversion';

        $params = [
            'amount' => 1,
            'symbol' => strtoupper($sourceCurrency),
            'convert' => strtoupper($targetCurrency)
        ];

        return $this->makeApiRequest(
            'coin_market_cap',
            $apiUrl,
            $params,
            function($response) use ($amount, $targetCurrency) {
                $data = $response->json();
                $targetCurrencyUpper = strtoupper($targetCurrency);

                if (isset($data['data']['quote'][$targetCurrencyUpper]['price'])) {
                    $rate = $data['data']['quote'][$targetCurrencyUpper]['price'];
                    $converted = $amount > 0 ? $amount * $rate : 0;
                    return [
                        'status' => 'success',
                        'converted' => number_format($converted, 6),
                        'source' => 'CoinMarketCap'
                    ];
                }

                return ['status' => 'error', 'message' => 'Invalid response format'];
            },
            ['X-CMC_PRO_API_KEY' => config('services.coinmarketcap.key')]
        );
    }

    /**
     * Generic API request handler with retry logic
     */
    private function makeApiRequest(
        string $apiName,
        string $url,
        array $params,
        callable $successHandler,
        array $headers = []
    ): array {
        $config = self::API_CONFIG[$apiName];
        $lastError = null;

        for ($attempt = 1; $attempt <= $config['max_retries']; $attempt++) {
            try {
                $response = Http::withHeaders($headers)
                    ->timeout($config['timeout'])
                    ->get($url, $params);

                if ($response->successful()) {
                    return $successHandler($response);
                }

                $status = $response->status();
                $lastError = "API request failed with status: $status";

                // Don't retry on client errors
                if ($status >= 400 && $status < 500 && $status !== 429) {
                    break;
                }

            } catch (Exception $e) {
                $lastError = $e->getMessage();
            }

            // Exponential backoff
            if ($attempt < $config['max_retries']) {
                usleep(1000000 * (2 ** ($attempt - 1))); // 1s, 2s, etc.
            }
        }

        Log::warning("$apiName API failed after retries", [
            'error' => $lastError,
            'url' => $url,
            'params' => $params
        ]);

        return [
            'status' => 'error',
            'message' => 'Service temporarily unavailable',
            'detail' => $lastError
        ];
    }

    /**
     * Map currency symbols to CoinGecko IDs
     */
    private function getCoinGeckoId(string $currency): string
    {
        $mapping = [
            'BTC' => 'bitcoin',
            'ETH' => 'ethereum',
            'USDT' => 'tether',
            'BNB' => 'binancecoin',
            'XRP' => 'ripple',
            // Add more mappings as needed
        ];

        return $mapping[strtoupper($currency)] ?? strtolower($currency);
    }

    /**
     * Fetch CoinGecko coin list with caching
     */
    public function fetchCoinGeckoCoinList(): array
    {
        $cacheKey = 'coinGecko_coin_list';

        return Cache::remember($cacheKey, self::CACHE_TTL['MARKET_DATA'], function() {
            $config = self::API_CONFIG['coin_gecko'];
            $apiUrl = $config['base_url'] . 'coins/markets';

            $params = [
                'vs_currency' => 'usd',
                'order' => 'market_cap_desc',
                'sparkline' => 'false',
                'per_page' => 9,
                'page' => 1
            ];

            $result = $this->makeApiRequest(
                'coin_gecko',
                $apiUrl,
                $params,
                function($response) {
                    $data = $response->json();

                    if (is_array($data) && !empty($data)) {
                        return [
                            'status' => 'success',
                            'data' => $data,
                            'source' => 'CoinGecko'
                        ];
                    }

                    return ['status' => 'error', 'message' => 'Empty or invalid response'];
                }
            );

            // Return the data array or empty array on error
            return $result['status'] === 'success' ? $result['data'] : [];
        });
    }
}
