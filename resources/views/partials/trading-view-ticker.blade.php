<div class="swiper swipernav">
    <div class="swiper-wrapper">
        @foreach($cryptoData as $crypto)
        <div class="swiper-slide width-200">
            <h6 class="mb-0 {{ $crypto['price_change_percentage_24h'] >= 0 ? 'text-success' : 'text-danger' }}">
                ${{ number_format($crypto['current_price'], 2) }}
            </h6>

            <p class="small">
                <span class="text-secondary">{{ strtoupper($crypto['name']) }}:</span>
                <span class="{{ $crypto['price_change_percentage_24h'] >= 0 ? 'text-success' : 'text-danger' }}">
                    <i class="bi {{ $crypto['price_change_percentage_24h'] >= 0 ? 'bi-caret-up-fill' : 'bi-caret-down-fill' }}"></i>
                    {{ abs(round($crypto['price_change_percentage_24h'], 2)) }}%
                </span>
            </p>
        </div>
        @endforeach
    </div>
</div>
