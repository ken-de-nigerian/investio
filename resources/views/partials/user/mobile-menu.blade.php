<footer class="adminuiux-mobile-footer style-2">
    <div class="container">
        <ul class="nav nav-pills nav-justified">
            <li class="nav-item">
                <a class="nav-link {{ isActive('user.dashboard') }}" href="{{ route('user.dashboard') }}">
                    <span>
                        <i class="nav-icon bi bi-house-door"></i>
                        <span class="nav-text">Home</span>
                    </span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ isActive(['user.wallet.*', 'user.domestic.transfer.*', 'user.wire.transfer.*']) }}" href="{{ route('user.wallet') }}">
                    <span>
                        <i class="nav-icon bi bi-wallet"></i>
                        <span class="nav-text">Wallet</span>
                    </span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('user.goal') }}" class="nav-link {{ isActive('user.goal.*') }}">
                    <span>
                        <i class="nav-icon bi bi-tags"></i>
                        <span class="nav-text">Goals</span>
                    </span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ isActive('user.statistics') }}" href="{{ route('user.statistics') }}">
                    <span>
                        <i class="nav-icon bi bi bi-bar-chart"></i>
                        <span class="nav-text">Statistic</span>
                    </span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ isActive('user.calculator') }}" href="{{ route('user.calculator') }}">
                    <span>
                        <i class="nav-icon bi bi-calculator"></i>
                        <span class="nav-text">Calc.</span>
                    </span>
                </a>
            </li>
        </ul>
    </div>
</footer>
