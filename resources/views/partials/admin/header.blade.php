<header class="adminuiux-header">
    <!-- Fixed navbar -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container-fluid">
            <!-- main sidebar toggle -->
            <button class="btn btn-link btn-square sidebar-toggler" type="button" onclick="initSidebar()">
                <i class="sidebar-svg" data-feather="menu"></i>
            </button>

            <!-- logo -->
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                <img data-bs-img="light" src="{{ asset('assets/img/logo-light.svg') }}" alt="" />
                <img data-bs-img="dark" src="{{ asset('assets/img/logo.svg') }}" alt="" />
                <div class="">
                    <span class="h4">Investment<b>UX</b></span>
                    <p class="company-tagline">AdminUIUX HTML template</p>
                </div>
            </a>

            <!-- right icons button -->
            <div class="ms-auto d-flex align-items-center">
                <!-- Dark mode -->
                <button class="btn btn-link btn-square btnsunmoon rounded-pill btn-link-header me-2" id="btn-layout-modes-dark-page">
                    <i class="sun mx-auto" data-feather="sun"></i>
                    <i class="moon mx-auto" data-feather="moon"></i>
                </button>

                <!-- profile dropdown -->
                <div class="dropdown d-inline-block ms-2">
                    <a class="dropdown-toggle style-none no-caret px-0" id="userprofiledd" data-bs-toggle="dropdown" aria-expanded="false" role="button">
                        <div class="d-flex align-items-center">
                            <figure class="avatar avatar-40 rounded-circle coverimg me-2">
                                <img src="{{ $avatar }}" alt="User Avatar" id="userphotoonboarding2" />
                            </figure>
                            <p class="mb-0 f-14 d-none d-sm-block">
                                {{ $auth['user'] ? $auth['user']->first_name . ' ' . ($auth['user']->last_name ?? '') : 'Guest' }}
                            </p>
                        </div>
                    </a>


                    <div class="dropdown-menu dropdown-menu-end width-300 pt-0 px-0 sm-mi-45px" aria-labelledby="userprofiledd">
                        <div class="bg-theme-1-space rounded py-3 mb-3 dropdown-dontclose">
                            <div class="row gx-0">
                                <div class="col-auto px-3">
                                    <figure class="avatar avatar-50 rounded-circle coverimg align-middle">
                                        <img src="{{ $avatar }}" alt="" />
                                    </figure>
                                </div>

                                <div class="col align-self-center">
                                    <p class="mb-1"><span>{{ $auth['user']['first_name'] }} {{ $auth['user']['last_name'] }}</span></p>
                                    <p><i class="bi bi-wallet2 me-2"></i> ${{ number_format($auth['user']['balance'], 2) }} <small class="opacity-50">Balance</small></p>
                                </div>
                            </div>
                        </div>

                        <div class="px-2">
                            <div>
                                <a class="dropdown-item {{ isActive('admin.dashboard') }}" href="{{ route('admin.dashboard') }}">
                                    <i data-feather="layout" class="avatar avatar-18 me-1"></i> Dashboard
                                </a>
                            </div>

                            <div>
                                <a class="dropdown-item {{ isActive('admin.profile') }}" href="{{ route('admin.profile') }}"> <i data-feather="settings" class="avatar avatar-18 me-1"></i> Account Settings </a>
                            </div>

                            <div>
                                <a class="dropdown-item theme-red" href="javascript:void(0)" onclick="document.getElementById('header-logout-form').submit()">
                                    <i data-feather="power" class="avatar avatar-18 me-1"></i> Logout
                                </a>
                                <form action="{{ route('logout') }}" method="POST" id="header-logout-form" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>
