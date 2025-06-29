<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\FundsManagedConfirmation;
use App\Models\Deposit;
use App\Models\Transaction;
use App\Services\MarketPricesService;
use Artisan;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AdminDepositsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $deposits = Deposit::with('user')->latest()->paginate(10)->withQueryString();

        $approved_deposits = Deposit::where('status', 'approved')->sum('amount');
        $pending_deposits = Deposit::where('status', 'pending')->sum('amount');
        $rejected_deposits = Deposit::where('status', 'rejected')->sum('amount');

        return view('admin.deposits.index', [
            'title' => 'Account - Deposits',
            'metrics' => [
                'pending_deposits' => $pending_deposits,
                'approved_deposits' => $approved_deposits,
                'rejected_deposits' => $rejected_deposits,
            ],
            'deposits' => $deposits,
        ]);
    }

    /**
     * Approve a deposit and notify the user.
     *
     * @param Deposit $deposit
     * @return JsonResponse
     */
    public function approve(Deposit $deposit): JsonResponse
    {
        // Prevent re-approving an already approved deposit
        if ($deposit->status === 'approved') {
            return response()->json([
                'status' => 'error',
                'message' => 'Deposit is already approved.'
            ], 400);
        }

        try {
            DB::beginTransaction();

            // Increment user balance
            $deposit->user->increment('balance', $deposit->amount);

            // Update deposit status
            $deposit->update(['status' => 'approved']);

            // Log transaction
            $transactionId = 'DEPOSIT_APPROVED_' . Str::uuid();
            Transaction::create([
                'user_id' => $deposit->user->id,
                'reference_id' => $transactionId,
                'amount' => $deposit->amount,
                'bank_name' => config('app.name'),
                'account_number' => $deposit->user->profile?->account_number ?? 'N/A',
                'trans_type' => 'credit',
                'receiver_name' => $deposit->user->first_name . ' ' . $deposit->user->last_name,
                'description' => 'Deposited funds have been added to your account',
                'acct_type' => $deposit->user->profile?->acct_type ?? 'savings',
                'trans_status' => 'approved'
            ]);

            // Send email notification if enabled
            if (config('settings.email_notification')) {
                Mail::mailer(config('settings.email_provider', 'default'))
                    ->to($deposit->user->email)
                    ->send(new FundsManagedConfirmation($deposit->user, $deposit->amount, 'deposit'));
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Deposit approved successfully'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to approve deposit: ' . $e->getMessage(), ['deposit_id' => $deposit->id]);
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to approve deposit. Please try again.'
            ], 500);
        }
    }

    /**
     * Reject a deposit and notify the user.
     *
     * @param Deposit $deposit
     * @return JsonResponse
     */
    public function reject(Deposit $deposit): JsonResponse
    {
        // Prevent rejecting a non-pending deposit
        if ($deposit->status !== 'pending') {
            return response()->json([
                'status' => 'error',
                'message' => 'Only pending deposits can be rejected.'
            ], 400);
        }

        try {
            DB::beginTransaction();

            // Update deposit status to rejected
            $deposit->update(['status' => 'rejected']);

            // Log transaction
            $transactionId = 'DEPOSIT_REJECTED_' . Str::uuid();
            Transaction::create([
                'user_id' => $deposit->user->id,
                'reference_id' => $transactionId,
                'amount' => $deposit->amount,
                'bank_name' => config('app.name'),
                'account_number' => $deposit->user->profile?->account_number ?? 'N/A',
                'trans_type' => 'credit',
                'receiver_name' => $deposit->user->first_name . ' ' . $deposit->user->last_name,
                'description' => 'Deposit request was rejected',
                'acct_type' => $deposit->user->profile?->acct_type ?? 'savings',
                'trans_status' => 'rejected'
            ]);

            // Send email notification if enabled
            if (config('settings.email_notification')) {
                Mail::mailer(config('settings.email_provider', 'default'))
                    ->to($deposit->user->email)
                    ->send(new FundsManagedConfirmation($deposit->user, $deposit->amount, 'deposit_rejected'));
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Deposit rejected successfully'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to reject deposit: ' . $e->getMessage(), ['deposit_id' => $deposit->id]);
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to reject deposit. Please try again.'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Deposit $deposit)
    {
        return view('admin.deposits.show', [
            'title' => 'Account - Deposits',
            'deposit' => $deposit->load('user'),
        ]);
    }

    /**
     * List all wallets
     *
     * @return Factory|View|Application|object
     */
    public function methods ()
    {
        // Get the WALLET_ADDRESSES array from .env
        $walletAddresses = $this->getWalletAddresses();

        return view('admin.deposits.methods.index', [
            'title' => 'Account - Deposit Methods',
            'wallets' => $walletAddresses,
        ]);
    }

    /**
     * Show the form for creating a new payment method.
     */
    public function addMethods()
    {
        return view('admin.deposits.methods.create', [
            'title' => 'Account - Deposit Methods',
        ]);
    }

    /**
     * Show form to edit the wallet
     * @param string $id
     * @return Factory|View|Application|object
     */
    public function editMethods (string $id)
    {
        $wallet = (new MarketPricesService())->getSingleGateway($id);

        return view('admin.deposits.methods.edit', [
            'title' => 'Account - Deposit Methods',
            'wallet' => $wallet,
        ]);
    }

    /**
     * Store a new payment method in the .env file.
     */
    public function storeMethods(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:2|max:255',
            'abbreviation' => 'required|string|min:2|max:255',
            'status' => 'required|in:0,1',
            'gateway_parameter' => 'required|string',
        ]);

        try {

            // Get the WALLET_ADDRESSES array from .env
            $walletAddresses = $this->getWalletAddresses();

            // Check if abbreviation already exists to ensure uniqueness
            foreach ($walletAddresses as $wallet) {
                if (isset($wallet['abbreviation']) && $wallet['abbreviation'] === $validated['abbreviation']) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Wallet abbreviation already exists.',
                    ], 422);
                }
            }

            // Generate a unique method_code
            $method_code = $this->generateUniqueMethodCode($walletAddresses);

            // Create new wallet
            $newWallet = [
                'method_code' => (string) $method_code,
                'name' => $validated['name'],
                'abbreviation' => $validated['abbreviation'],
                'status' => (int) $validated['status'],
                'gateway_parameter' => $validated['gateway_parameter'],
            ];

            // Append the new wallet to the array
            $walletAddresses[] = $newWallet;

            // Update the .env file
            $this->updateEnvWalletAddresses($walletAddresses);

            return response()->json([
                'success' => true,
                'message' => 'Payment method created successfully!',
                'data' => $newWallet
            ], 201);
        } catch (Exception $e) {
            Log::error('Failed to create payment method in .env: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the payment method.',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], 500);
        }
    }

    /**
     * Update the specified payment method in the .env file.
     */
    public function updateMethods(Request $request, $method_code)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:2|max:255',
            'abbreviation' => 'required|string|min:2|max:255',
            'status' => 'required|in:0,1',
            'gateway_parameter' => 'required|string',
        ]);

        try {

            // Get the WALLET_ADDRESSES array from .env
            $walletAddresses = $this->getWalletAddresses();

            // Check if the new abbreviation already exists (excluding current wallet)
            foreach ($walletAddresses as $wallet) {
                if (isset($wallet['abbreviation']) &&
                    isset($wallet['method_code']) &&
                    $wallet['abbreviation'] === $validated['abbreviation'] &&
                    $wallet['method_code'] !== $method_code) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Wallet abbreviation already exists.',
                    ], 422);
                }
            }

            // Find and update the wallet by method_code
            $found = false;
            $updatedWallet = null;
            foreach ($walletAddresses as &$wallet) {
                if (isset($wallet['method_code']) && $wallet['method_code'] === $method_code) {
                    // Update the wallet data
                    $wallet['name'] = $validated['name'];
                    $wallet['abbreviation'] = $validated['abbreviation'];
                    $wallet['status'] = (int) $validated['status'];
                    $wallet['gateway_parameter'] = $validated['gateway_parameter'];
                    $updatedWallet = $wallet;
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment method not found.',
                ], 404);
            }

            // Update the .env file
            $this->updateEnvWalletAddresses($walletAddresses);

            return response()->json([
                'success' => true,
                'message' => 'Payment method updated successfully!',
                'data' => $updatedWallet
            ]);
        } catch (Exception $e) {
            Log::error('Failed to update payment method in .env: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the payment method.',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], 500);
        }
    }

    /**
     * Helper method to get wallet addresses from .env
     */
    private function getWalletAddresses()
    {
        // Try to get from config first
        $walletAddresses = config('settings.site.wallet_addresses');

        if (is_array($walletAddresses) && !empty($walletAddresses)) {
            return $walletAddresses;
        }

        // Read directly from .env file to handle your specific format
        $envFile = base_path('.env');
        if (!File::exists($envFile)) {
            Log::warning('.env file not found, initializing empty wallet array');
            return [];
        }

        $envContent = File::get($envFile);

        // Extract WALLET_ADDRESSES line using regex
        if (preg_match('/^WALLET_ADDRESSES=(.*)$/m', $envContent, $matches)) {
            $envValue = trim($matches[1]);

            // Remove outer quotes if present
            if ((str_starts_with($envValue, '"') && str_ends_with($envValue, '"')) ||
                (str_starts_with($envValue, "'") && str_ends_with($envValue, "'"))) {
                $envValue = substr($envValue, 1, -1);
            }

            // Unescape the JSON string
            $envValue = str_replace(['\\"', "\\'"], ['"', "'"], $envValue);

            $walletAddresses = json_decode($envValue, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('JSON decode error in WALLET_ADDRESSES: ' . json_last_error_msg());
                Log::error('Raw value: ' . $envValue);
                return [];
            }

            if (!is_array($walletAddresses)) {
                Log::warning('WALLET_ADDRESSES is not an array, initializing empty array');
                return [];
            }

            return $walletAddresses;
        }

        // WALLET_ADDRESSES not found in .env
        Log::info('WALLET_ADDRESSES not found in .env, initializing empty array');
        return [];
    }

    /**
     * Helper method to generate unique method code
     * @throws Exception
     */
    private function generateUniqueMethodCode($walletAddresses)
    {
        $maxAttempts = 10;
        $attempts = 0;

        do {
            $method_code = mt_rand(100000000000, 999999999999);
            $exists = false;

            foreach ($walletAddresses as $wallet) {
                if (isset($wallet['method_code']) && $wallet['method_code'] == $method_code) {
                    $exists = true;
                    break;
                }
            }

            $attempts++;
        } while ($exists && $attempts < $maxAttempts);

        if ($exists) {
            throw new Exception('Unable to generate unique method code after ' . $maxAttempts . ' attempts');
        }

        return $method_code;
    }

    /**
     * Helper method to update WALLET_ADDRESSES in .env file
     * @throws Exception
     */
    private function updateEnvWalletAddresses($walletAddresses)
    {
        $envFile = base_path('.env');

        if (!File::exists($envFile)) {
            throw new Exception('.env file not found');
        }

        $envContent = File::get($envFile);

        // Convert array to JSON string matching your exact format
        $jsonString = json_encode($walletAddresses, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        // Escape quotes for .env format - this creates the exact format you have
        $escapedJson = str_replace('"', '\\"', $jsonString);
        $envValue = '"' . $escapedJson . '"';

        // Check if WALLET_ADDRESSES exists in .env
        if (preg_match('/^WALLET_ADDRESSES=/m', $envContent)) {
            // Replace existing line - handle multiline values if needed
            $newEnvContent = preg_replace(
                '/^WALLET_ADDRESSES=.*$/m',
                "WALLET_ADDRESSES=$envValue",
                $envContent
            );

            // If the regex didn't match properly, try a more comprehensive approach
            if ($newEnvContent === $envContent) {
                $lines = explode("\n", $envContent);
                $newLines = [];
                $found = false;

                foreach ($lines as $line) {
                    if (str_starts_with(trim($line), 'WALLET_ADDRESSES=')) {
                        $newLines[] = "WALLET_ADDRESSES=$envValue";
                        $found = true;
                    } else {
                        $newLines[] = $line;
                    }
                }

                if ($found) {
                    $newEnvContent = implode("\n", $newLines);
                }
            }
        } else {
            // Append new line
            $newEnvContent = rtrim($envContent) . "\nWALLET_ADDRESSES=$envValue\n";
        }

        // Write back to file
        if (File::put($envFile, $newEnvContent) === false) {
            throw new Exception('Failed to write to .env file');
        }

        // Clear config cache to reload .env changes
        try {
            Artisan::call('config:clear');
        } catch (Exception $e) {
            Log::warning('Failed to clear config cache: ' . $e->getMessage());
            // Don't throw here as the .env update was successful
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Deposit $deposit)
    {
        // Delete the deposit
        $deposit->delete();

        // Return success response
        return response()->json([
            'status' => 'success',
            'message' => 'Deposit deleted successfully'
        ]);
    }
}
