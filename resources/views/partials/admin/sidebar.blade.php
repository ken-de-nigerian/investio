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
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ isActive('admin.dashboard') }} rounded-4">
                    <i class="menu-icon bi bi-house-door"></i>
                    <span class="menu-name">Dashboard</span>
                </a>
            </li>

            <!-- Users Dropdown -->
            <li class="nav-item dropdown">
                <a href="javascript:void(0)" class="nav-link dropdown-toggle {{ isActive('admin.users.*') }}" data-bs-toggle="dropdown">
                    <i class="menu-icon bi bi-people"></i>
                    <span class="menu-name">Users</span>
                </a>
                <div class="dropdown-menu">
                    <div class="nav-item">
                        <a href="{{ route('admin.users') }}" class="nav-link {{ isActive('admin.users') }}">
                            <i class="menu-icon bi bi-person"></i>
                            <span class="menu-name">Overview</span>
                        </a>
                    </div>

                    <div class="nav-item">
                        <a href="{{ route('admin.users.create') }}" class="nav-link {{ isActive('admin.users.create') }}">
                            <i class="menu-icon bi bi-person-add"></i>
                            <span class="menu-name">Create Users</span>
                        </a>
                    </div>

                    <div class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="menu-icon bi bi-envelope-at"></i>
                            <span class="menu-name">Email Notifications</span>
                        </a>
                    </div>
                </div>
            </li>

            <!-- Deposits Dropdown -->
            <li class="nav-item dropdown">
                <a href="javascript:void(0)" class="nav-link dropdown-toggle {{ isActive('admin.deposits.*') }}" data-bs-toggle="dropdown">
                    <i class="menu-icon bi bi-cash"></i>
                    <span class="menu-name">Deposits</span>
                </a>

                <div class="dropdown-menu">
                    <div class="nav-item">
                        <a href="{{ route('admin.deposits') }}" class="nav-link {{ isActive('admin.deposits') }}">
                            <i class="menu-icon bi bi-bar-chart"></i>
                            <span class="menu-name">Overview</span>
                        </a>
                    </div>

                    <div class="nav-item">
                        <a href="{{ route('admin.deposits.alert') }}" class="nav-link {{ isActive('admin.deposits.alert') }}">
                            <i class="menu-icon bi bi-currency-exchange"></i>
                            <span class="menu-name">Credit | Debit</span>
                        </a>
                    </div>

                    <div class="nav-item">
                        <a href="{{ route('admin.deposits.methods') }}" class="nav-link {{ isActive('admin.deposits.methods') }}">
                            <i class="menu-icon bi bi-credit-card"></i>
                            <span class="menu-name">Payment Methods</span>
                        </a>
                    </div>
                </div>
            </li>

            <!-- Goals & Savings Dropdown -->
            <li class="nav-item dropdown">
                <a href="javascript:void(0)" class="nav-link dropdown-toggle {{ isActive('admin.goals.*') }}" data-bs-toggle="dropdown">
                    <i class="menu-icon bi bi-arrow-left-right"></i>
                    <span class="menu-name">Goals & Savings</span>
                </a>

                <div class="dropdown-menu">
                    <div class="nav-item">
                        <a href="{{ route('admin.goals') }}" class="nav-link {{ isActive('admin.goals') }}">
                            <i class="menu-icon bi bi-bank"></i>
                            <span class="menu-name">Overview</span>
                        </a>
                    </div>

                    <div class="nav-item">
                        <a href="{{ route('admin.goal.categories') }}" class="nav-link {{ isActive('admin.goal.categories') }}">
                            <i class="menu-icon bi bi-tags"></i>
                            <span class="menu-name">Goal Categories</span>
                        </a>
                    </div>
                </div>
            </li>

            <!-- Loans Financing -->
            <li class="nav-item">
                <a href="{{ route('admin.loans') }}" class="nav-link {{ isActive('admin.loans.*') }}">
                    <i class="menu-icon bi bi-cash-stack"></i>
                    <span class="menu-name">Loans Financing</span>
                </a>
            </li>

            <!-- Transfers Dropdown -->
            <li class="nav-item dropdown">
                <a href="javascript:void(0)" class="nav-link dropdown-toggle {{ isActive(['admin.interbank.*', 'admin.domestic.*', 'admin.wire.*']) }}" data-bs-toggle="dropdown">
                    <i class="menu-icon bi bi-arrow-left-right"></i>
                    <span class="menu-name">Transfers</span>
                </a>

                <div class="dropdown-menu">
                    <div class="nav-item">
                        <a href="{{ route('admin.deposits') }}" class="nav-link {{ isActive('admin.deposits') }}">
                            <i class="menu-icon bi bi-bank"></i>
                            <span class="menu-name">Interbank Transfer</span>
                        </a>
                    </div>

                    <div class="nav-item">
                        <a href="{{ route('admin.deposits.alert') }}" class="nav-link {{ isActive('admin.deposits.alert') }}">
                            <i class="menu-icon bi bi-calendar-event"></i>
                            <span class="menu-name">Domestic Transfer</span>
                        </a>
                    </div>

                    <div class="nav-item">
                        <a href="{{ route('admin.deposits.methods') }}" class="nav-link {{ isActive('admin.deposits.methods') }}">
                            <i class="menu-icon bi bi-globe-americas"></i>
                            <span class="menu-name">Wire Transfer</span>
                        </a>
                    </div>
                </div>
            </li>

            <!-- Investment Dropdown -->
            <li class="nav-item dropdown">
                <a href="javascript:void(0)" class="nav-link dropdown-toggle {{ isActive('admin.investments.*') }}" data-bs-toggle="dropdown">
                    <i class="menu-icon bi bi-piggy-bank-fill"></i>
                    <span class="menu-name">Investment</span>
                </a>
                <div class="dropdown-menu">
                    <div class="nav-item">
                        <a href="{{ route('admin.investments') }}" class="nav-link">
                            <i class="menu-icon bi bi-graph-up"></i>
                            <span class="menu-name">Overview</span>
                        </a>
                    </div>

                    <div class="nav-item">
                        <a href="{{ route('admin.investment.plans') }}" class="nav-link">
                            <i class="menu-icon bi bi-coin"></i>
                            <span class="menu-name">Investment Plans</span>
                        </a>
                    </div>

                    <div class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="menu-icon bi bi-briefcase"></i>
                            <span class="menu-name">My Portfolio</span>
                        </a>
                    </div>
                </div>
            </li>

            <!-- Statistics -->
            <li class="nav-item">
                <a href="" class="nav-link">
                    <i class="menu-icon bi bi-bar-chart"></i>
                    <span class="menu-name">Statistics</span>
                </a>
            </li>

            <!-- My Transactions -->
            <li class="nav-item">
                <a href="{{ route('admin.transactions') }}" class="nav-link {{ isActive('admin.transactions') }}">
                    <i class="menu-icon bi bi-receipt"></i>
                    <span class="menu-name">My Transactions</span>
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
                            <a href="{{ route('admin.loans') }}" class="btn btn-square btn-link theme-red" data-bs-toggle="tooltip" data-bs-original-title="Loan">
                                <i class="bi bi-cash-stack"></i>
                            </a>

                            <a href="{{ route('admin.goals') }}" class="btn btn-square btn-link" data-bs-toggle="tooltip" data-bs-original-title="Goal">
                                <i class="bi bi-tags"></i>
                            </a>

                            <a href="" class="btn btn-square btn-link" data-bs-toggle="tooltip" data-bs-original-title="Wallet">
                                <i class="bi bi-wallet2"></i>
                            </a>

                            <a href="{{ route('admin.investments') }}" class="btn btn-square btn-link" data-bs-toggle="tooltip" data-bs-original-title="Investment">
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
                <a href="{{ route('admin.kyc') }}" class="nav-link {{ isActive('admin.kyc.*') }}">
                    <i class="menu-icon bi bi-file-earmark-check"></i>
                    <span class="menu-name">KYC</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admin.referrals') }}" class="nav-link {{ isActive('admin.referrals') }}">
                    <i class="menu-icon bi bi-person-plus"></i>
                    <span class="menu-name">Referrals</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admin.profile') }}" class="nav-link {{ isActive('admin.profile') }}">
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
