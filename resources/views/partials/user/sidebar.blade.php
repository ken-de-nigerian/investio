<div class="adminuiux-sidebar">
    <div class="adminuiux-sidebar-inner">
        <!-- Profile -->
        <div class="px-3 not-iconic mt-3">
            <div class="row">
                <div class="col align-self-center">
                    <h6 class="fw-medium">Main Menu</h6>
                </div>

                <div class="col-auto">
                    <a class="btn btn-link btn-square" data-bs-toggle="collapse" data-bs-target="#usersidebarprofile" aria-expanded="false" role="button" aria-controls="usersidebarprofile">
                        <i data-feather="chevron-down"></i>
                    </a>
                </div>
            </div>

            <div class="text-center collapse" id="usersidebarprofile">
                <figure class="avatar avatar-100 rounded-circle coverimg my-3">
                    <img src="{{ $avatar }}" alt="User Avatar" loading="lazy"/>
                </figure>

                <h5 class="mb-1 fw-medium">{{ $auth['user']['first_name'] }} {{ $auth['user']['last_name'] }}</h5>
                <p class="small">The Investment UI Kit</p>
            </div>
        </div>

        <ul class="nav flex-column menu-active-line p-4">
            <!-- Dashboard -->
            <li class="nav-item">
                <a href="{{ route('user.dashboard') }}" class="nav-link {{ isActive('user.dashboard') }} rounded-4">
                    <i class="menu-icon bi bi-house-door"></i>
                    <span class="menu-name">Dashboard</span>
                </a>
            </li>

            <!-- Wallet -->
            <li class="nav-item">
                <a href="{{ route('user.wallet') }}" class="nav-link {{ isActive(['user.wallet.*', 'user.domestic.transfer.*', 'user.wire.transfer.*']) }}">
                    <i class="menu-icon bi bi-wallet2"></i>
                    <span class="menu-name">Wallet</span>
                </a>
            </li>

            <!-- My Goals -->
            <li class="nav-item">
                <a href="{{ route('user.goal') }}" class="nav-link {{ isActive('user.goal.*') }}">
                    <i class="menu-icon bi bi-tags"></i>
                    <span class="menu-name">My Goals</span>
                </a>
            </li>

            <!-- My Loans -->
            <li class="nav-item">
                <a href="{{ route('user.loan') }}" class="nav-link {{ isActive('user.loan.*') }}">
                    <i class="menu-icon bi bi-cash-stack"></i>
                    <span class="menu-name">My Loans</span>
                </a>
            </li>

            <!-- Investment Dropdown -->
            <li class="nav-item dropdown">
                <a href="javascript:void(0)" class="nav-link dropdown-toggle {{ isActive('user.investment.*') }}" data-bs-toggle="dropdown">
                    <i class="menu-icon bi bi-piggy-bank-fill"></i>
                    <span class="menu-name">Investment</span>
                </a>
                <div class="dropdown-menu">
                    <div class="nav-item">
                        <a href="{{ route('user.investment') }}" class="nav-link">
                            <i class="menu-icon bi bi-graph-up"></i>
                            <span class="menu-name">Overview</span>
                        </a>
                    </div>

                    <div class="nav-item">
                        <a href="{{ route('user.investment.plans') }}" class="nav-link">
                            <i class="menu-icon bi bi-coin"></i>
                            <span class="menu-name">Investment Plans</span>
                        </a>
                    </div>

                    <div class="nav-item">
                        <a href="{{ route('user.investment.list') }}" class="nav-link">
                            <i class="menu-icon bi bi-briefcase"></i>
                            <span class="menu-name">My Portfolio</span>
                        </a>
                    </div>
                </div>
            </li>

            <!-- Statistics -->
            <li class="nav-item">
                <a href="{{ route('user.statistics') }}" class="nav-link {{ isActive('user.statistics') }}">
                    <i class="menu-icon bi bi-bar-chart"></i>
                    <span class="menu-name">Statistics</span>
                </a>
            </li>

            <!-- Calculators -->
            <li class="nav-item">
                <a href="{{ route('user.calculator') }}" class="nav-link" @if(Route::is('user.calculator')) style="background-color: rgba(var(--adminuiux-theme-1-rgb), 1); color: #fff;" @endif>
                    <i class="menu-icon bi bi-calculator-fill"></i>
                    <span class="menu-name">Calculators</span>
                </a>
            </li>

            <!-- My Transactions -->
            <li class="nav-item">
                <a href="{{ route('user.transactions') }}" class="nav-link {{ isActive('user.transactions') }}">
                    <i class="menu-icon bi bi-receipt"></i>
                    <span class="menu-name">My Transactions</span>
                </a>
            </li>

            <!-- Help Center -->
            <li class="nav-item">
                <a href="{{ route('user.contact.us') }}" class="nav-link {{ isActive('user.contact.us') }}">
                    <i class="menu-icon bi bi-telephone-inbound"></i>
                    <span class="menu-name">Contact Us</span>
                </a>
            </li>
        </ul>

        <!-- Quick Links -->
        <div class="px-3 mb-3 not-iconic">
            <h6 class="mb-3 fw-medium">Quick Links</h6>
            <div class="card adminuiux-card">
                <div class="card-body p-2">
                    <div class="row gx-2">
                        <div class="col-12 d-flex justify-content-between">
                            <a href="{{ route('user.loan') }}" class="btn btn-square btn-link theme-red" data-bs-toggle="tooltip" data-bs-original-title="Loan">
                                <i class="bi bi-cash-stack"></i>
                            </a>

                            <a href="{{ route('user.goal') }}" class="btn btn-square btn-link" data-bs-toggle="tooltip" data-bs-original-title="Goal">
                                <i class="bi bi-tags"></i>
                            </a>

                            <a href="{{ route('user.wallet') }}" class="btn btn-square btn-link" data-bs-toggle="tooltip" data-bs-original-title="Wallet">
                                <i class="bi bi-wallet2"></i>
                            </a>

                            <a href="{{ route('user.investment') }}" class="btn btn-square btn-link" data-bs-toggle="tooltip" data-bs-original-title="Investment">
                                <i class="bi bi-piggy-bank"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Account -->
        <ul class="nav flex-column menu-active-line">
            <li class="nav-item">
                <a href="{{ route('user.kyc') }}" class="nav-link {{ isActive('user.kyc.*') }}">
                    <i class="menu-icon bi bi-file-earmark-check"></i>
                    <span class="menu-name">KYC</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('user.referrals') }}" class="nav-link {{ isActive('user.referrals') }}">
                    <i class="menu-icon bi bi-person-plus"></i>
                    <span class="menu-name">Referrals</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('user.profile') }}" class="nav-link {{ isActive('user.profile') }}">
                    <i class="menu-icon bi bi-gear"></i>
                    <span class="menu-name">Settings</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link theme-red" href="javascript:void(0)" onclick="document.getElementById('sidebar-logout-form').submit()">
                    <i class="menu-icon bi bi-box-arrow-right"></i>
                    <span class="menu-name">Logout</span>
                </a>

                <form action="{{ route('logout') }}" method="POST" id="sidebar-logout-form" class="d-none">
                    @csrf
                </form>
            </li>
        </ul>
    </div>
</div>
