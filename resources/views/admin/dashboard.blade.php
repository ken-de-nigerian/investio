@extends('layouts.admin')
@section('content')
    <!-- content -->
    <div class="container mt-4 mb-5" id="main-content">
        <!-- summary -->
        <div class="row gx-3">
            <!-- Total investment -->
            <div class="col-12 col-sm-6 col-xxl-3 mb-3">
                <div class="card adminuiux-card overflow-hidden">
                    <div class="card-body theme-blue">
                        <div class="row gx-3 align-items-center mb-1">
                            <div class="col-auto">
                                <div class="avatar avatar-50 text-center rounded bg-theme-1 text-white">
                                    <i class="bi bi-cash-stack h4"></i>
                                </div>
                            </div>
                            <div class="col">
                                <h2 class="fw-medium mb-0">$ 15.41L</h2>
                                <p class="text-secondary small">Investment</p>
                            </div>
                        </div>
                    </div>
                    <div class="summarychart height-40">
                        <canvas id="areachartblue1"></canvas>
                    </div>
                </div>
            </div>
            <!-- Total released profit -->
            <div class="col-12 col-sm-6 col-xxl-3 mb-3">
                <div class="card adminuiux-card overflow-hidden">
                    <div class="card-body theme-green">
                        <div class="row gx-3 align-items-center mb-1">
                            <div class="col-auto">
                                <div class="avatar avatar-50 text-center rounded bg-theme-1 text-white">
                                    <i class="bi bi-bank h4"></i>
                                </div>
                            </div>
                            <div class="col">
                                <div class="row gx-3 align-items-center">
                                    <div class="col">
                                        <h2 class="fw-medium mb-0">$ 1.50k</h2>
                                        <p class="text-secondary small">Released - P&L</p>
                                    </div>
                                    <div class="col-auto text-end">
                                        <h4 class="fw-medium mb-1"><small class="text-success">+2.2%</small></h4>
                                        <p class="text-secondary small">2 days ago</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="summarychart height-40">
                        <canvas id="areachartgreen1"></canvas>
                    </div>
                </div>
            </div>
            <!-- Total unreleased profit loss -->
            <div class="col-12 col-sm-6 col-xxl-3 mb-3">
                <div class="card adminuiux-card overflow-hidden">
                    <div class="card-body theme-orange">
                        <div class="row gx-3 align-items-center mb-1">
                            <div class="col-auto">
                                <div class="avatar avatar-50 text-center rounded bg-theme-1 text-white">
                                    <i class="bi bi-percent h4"></i>
                                </div>
                            </div>
                            <div class="col">
                                <div class="row gx-3 align-items-center">
                                    <div class="col">
                                        <h2 class="fw-medium mb-0">- $ 5.41k</h2>
                                        <p class="text-secondary small">Unreleased P&L</p>
                                    </div>
                                    <div class="col-auto text-end">
                                        <h4 class="fw-medium mb-1"><small class="text-danger">-1.04%</small></h4>
                                        <p class="text-secondary small">15min </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="summarychart height-40">
                        <canvas id="areachartyellow1"></canvas>
                    </div>
                </div>
            </div>
            <!-- Total expected return-->
            <div class="col-12 col-sm-6 col-xxl-3 mb-3">
                <div class="card adminuiux-card overflow-hidden">
                    <div class="card-body theme-red">
                        <div class="row gx-3 mb-1">
                            <div class="col-auto">
                                <div class="avatar avatar-50 text-center rounded bg-theme-1 text-white">
                                    <i class="bi bi-x-diamond h4"></i>
                                </div>
                            </div>
                            <div class="col">
                                <div class="row gx-3 align-items-center">
                                    <div class="col">
                                        <h2 class="fw-medium mb-0">$ 1.34k</h2>
                                        <p class="text-secondary small">Expected P&L</p>
                                    </div>
                                    <div class="col-auto text-end">
                                        <h4 class="fw-medium mb-1"><small class="text-success">+1.03%</small></h4>
                                        <p class="text-secondary small">in 3 days</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="summarychart height-40">
                        <canvas id="areachartred1"></canvas>
                    </div>
                </div>
            </div>

            <!-- visit chart -->
            <div class="col-12 col-lg-6 col-xl-8 mb-3">
                <div class="card adminuiux-card">
                    <div class="card-body">
                        <div class="row gx-3 align-items-center mb-3">
                            <div class="col">
                                <h6>Profit/Expense</h6>
                            </div>
                            <div class="col-auto">
                                <select class="form-select form-select-sm">
                                    <option>USD</option>
                                    <option>CAD</option>
                                    <option>AUD</option>
                                </select>
                            </div>
                            <div class="col-auto">
                                <button class="btn btn-sm btn-square btn-link"><i class="bi bi-arrow-clockwise"></i></button>
                            </div>
                        </div>

                        <div class="w-100 height-200 mb-3">
                            <canvas id="summarychart"></canvas>
                        </div>

                        <div class="row gx-3 align-items-center">
                            <div class="col-6 col-md-4 col-lg-3">
                                <div class="card adminuiux-card bg-theme-1">
                                    <div class="card-body z-index-1">
                                        <h4 class="fw-medium text">$5560.50</h4>
                                        <p class="opacity-75 small">Income <span class="fs-14"><i class="bi bi-arrow-up"></i> 11.5%</span></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-4 col-lg-3">
                                <div class="card adminuiux-card bg-theme-1-subtle">
                                    <div class="card-body z-index-1">
                                        <h4 class="fw-medium">$5560.50</h4>
                                        <p class="text-secondary">Expense <span class="text-success fs-14"><i class="bi bi-arrow-up"></i> 11.5%</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--  Ages distribution -->
            <div class="col-12 col-lg-6 col-xl-4 mb-3">
                <div class="card adminuiux-card h-100">
                    <div class="card-body pb-0">
                        <h6 class="mb-3">Ages in Years</h6>
                        <canvas id="doughnutchart" class="mx-auto mb-3 height-180"></canvas>
                        <p class="text-secondary small">It looks like a children age between 0-15 are have more cases than
                            expected.</p>
                        <div class="row gx-3">
                            <div class="col-auto mb-3">
                                <p class="h4 mb-1">40</p>
                                <p class="text-secondary small"><span class="me-1 avatar avatar-10 rounded bg-green"></span>0-15
                                </p>
                            </div>
                            <div class="col-auto mb-3">
                                <p class="h4 mb-1">10</p>
                                <p class="text-secondary small"><span class="me-1 avatar avatar-10 rounded bg-yellow"></span>16-30
                                </p>
                            </div>
                            <div class="col-auto mb-3">
                                <p class="h4 mb-1">15</p>
                                <p class="text-secondary small"><span class="me-1 avatar avatar-10 rounded bg-orange"></span>31-45
                                </p>
                            </div>
                            <div class="col-auto mb-3">
                                <p class="h4 mb-1">25</p>
                                <p class="text-secondary small"><span class="me-1 avatar avatar-10 rounded bg-purple"></span>46-60
                                </p>
                            </div>
                            <div class="col-auto mb-3">
                                <p class="h4 mb-1">10</p>
                                <p class="text-secondary small"><span class="me-1 avatar avatar-10 rounded bg-secondary"></span>60+
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Expense quota-->
            <div class="col-12 col-lg-6 col-xl-4 mb-3">
                <div class="card adminuiux-card h-100">
                    <div class="card-body">
                        <h6 class="mb-3">Top Investment Categories</h6>

                        <div class="semidoughnutchart">
                            <div class="expensedatasemidoughnut">
                                <p class="h4 mb-0">$ 15.50k</p>
                                <p class="text-secondary small">Profit</p>
                            </div>
                            <canvas id="semidoughnutchart" class="mx-auto width-260 height-260"></canvas>
                        </div>

                        <div class="row ">
                            <div class="col-6 col-md-6 mb-3">
                                <p class="text-secondary small mb-2">
                                    <span class="me-1 avatar avatar-10 rounded bg-green"></span>
                                    Share holdings
                                </p>
                                <h4 class="ps-3 fw-medium">$ 165.52k<br> <span class="text-success fs-14 fw-normal"><i class="bi bi-caret-up-fill me-1 fs-14"></i> 25.30%</span></h4>

                            </div>
                            <div class="col-6 col-md-6 mb-3">
                                <p class="text-secondary small mb-2">
                                    <span class="me-1 avatar avatar-10 rounded bg-yellow"></span>
                                    Mutual Funds
                                </p>
                                <h4 class="ps-3 fw-medium">$ 265.85k<br> <span class="text-success fs-14 fw-normal"><i class="bi bi-caret-up-fill me-1"></i> 21.42%</span></h4>
                            </div>
                            <div class="col-6 col-md-6 mb-3">
                                <p class="text-secondary small mb-2">
                                    <span class="me-1 avatar avatar-10 rounded bg-orange"></span>
                                    Bank Bonds
                                </p>
                                <h4 class="ps-3 fw-medium">$ 356.26k<br> <span class="text-success fs-14 fw-normal"><i class="bi bi-caret-up-fill me-1"></i> 20.18%</span></h4>
                            </div>
                            <div class="col-6 col-md-6 mb-3">
                                <p class="text-secondary small mb-2">
                                    <span class="me-1 avatar avatar-10 rounded bg-purple"></span>
                                    Gov. Securities
                                </p>
                                <h4 class="ps-3 fw-medium">$ 185.65<br> <span class="text-success fs-14 fw-normal"><i class="bi bi-caret-up-fill me-1"></i> 15.65%</span></h4>
                            </div>
                            <div class="col-6 col-md-6">
                                <p class="text-secondary small mb-2">
                                    <span class="me-1 avatar avatar-10 rounded bg-secondary"></span>
                                    Fixed Deposit
                                </p>
                                <h4 class="ps-3 fw-medium">$ 190.45k<br> <span class="text-success fs-14 fw-normal"><i class="bi bi-caret-up-fill me-1"></i> 18.50%</span></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- list table -->
            <div class="col-12 col-lg-12 col-xl-8">
                <div class="card adminuiux-card mb-3">
                    <div class="card-body pb-0">
                        <h6>My Holdings Performance</h6>

                        <!-- Assets funds and shares -->
                        <table class="table nowrap mb-0" id="dataTable">
                            <thead>
                            <tr>
                                <th data-priority="1">Company</th>
                                <th data-priority="1">Price</th>
                                <th>Holding</th>
                                <th data-priority="2">Profit/Loss</th>
                                <th data-priority="3">Today's Trend</th>
                                <th data-priority="4">% Change</th>
                                <th data-priority="1">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>
                                    <div class="d-inline-block align-middle">
                                        <p class="mb-0">Jintudal</p>
                                        <p class="small text-theme-1"><i class="bi bi-award-fill"></i> Event</p>
                                    </div>
                                </td>
                                <td>
                                    <p class="mb-0">$ 100.45</p>
                                    <p class="small"><span class="text-secondary" data-bs-toggle="tooltip" title="Last top price">LTP:</span> 152</p>
                                </td>
                                <td>
                                    <p class="mb-0">102 units </p>
                                    <p class="small"><span class="text-secondary">Invested:</span> $ 1400.45 </p>
                                </td>
                                <td>
                                    <p class="mb-0 text-success"><i class="bi bi-caret-up-fill"></i> 25.30%</p>
                                    <p class="small"><span class="text-secondary">Profit:</span> $ 305.5 </p>
                                </td>
                                <td>
                                    <p class="mb-0 text-success"><i class="bi bi-graph-up-arrow"></i> Bullish</p>
                                </td>
                                <td>
                                    <p class="mb-0 text-success"><i class="bi bi-caret-up-fill"></i> 1.24%</p>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-success">Invest</button>
                                    <button class="btn btn-sm btn-outline-danger">Sell</button>
                                    <div class="dropdown d-inline-block">
                                        <a class="btn btn-link btn-square no-caret" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots"></i>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="javascript:void(0)">Favorite</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0)">View Chart</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0)">Company Events</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-inline-block align-middle">
                                        <p class="mb-0">Ciplasc</p>
                                    </div>
                                </td>
                                <td>
                                    <p class="mb-0">$ 520.45</p>
                                    <p class="small"><span class="text-secondary" data-bs-toggle="tooltip" title="Last top price">LTP:</span> 521.05</p>
                                </td>
                                <td>
                                    <p class="mb-0">50 units </p>
                                    <p class="small"><span class="text-secondary">Invested:</span> $ 1520.45 </p>
                                </td>
                                <td>
                                    <p class="mb-0 text-success"><i class="bi bi-caret-up-fill"></i> 15.40%</p>
                                    <p class="small"><span class="text-secondary">Profit:</span> $ 408.65 </p>
                                </td>
                                <td>
                                    <p class="mb-0 text-danger"><i class="bi bi-graph-down-arrow"></i> Bearish</p>
                                </td>
                                <td>
                                    <p class="mb-0 text-danger"><i class="bi bi-caret-down-fill"></i> 0.85%</p>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-success">Invest</button>
                                    <button class="btn btn-sm btn-outline-danger">Sell</button>
                                    <div class="dropdown d-inline-block">
                                        <a class="btn btn-link btn-square no-caret" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots"></i>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="javascript:void(0)">Favorite</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0)">View Chart</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0)">Company Events</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-inline-block align-middle">
                                        <p class="mb-0">Trisha LLC</p>
                                    </div>
                                </td>
                                <td>
                                    <p class="mb-0">$ 856.45</p>
                                    <p class="small"><span class="text-secondary" data-bs-toggle="tooltip" title="Last top price">LTP:</span> 856.55</p>
                                </td>
                                <td>
                                    <p class="mb-0">20 units </p>
                                    <p class="small"><span class="text-secondary">Invested:</span> $ 2050.00 </p>
                                </td>
                                <td>
                                    <p class="mb-0 text-success"><i class="bi bi-caret-up-fill"></i> 35.15%</p>
                                    <p class="small"><span class="text-secondary">Profit:</span> $ 685.00 </p>
                                </td>
                                <td>
                                    <p class="mb-0 text-success"><i class="bi bi-graph-up-arrow"></i> Bullish</p>
                                </td>
                                <td>
                                    <p class="mb-0 text-success"><i class="bi bi-caret-up-fill"></i> 1.03%</p>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-success">Invest</button>
                                    <button class="btn btn-sm btn-outline-danger">Sell</button>
                                    <div class="dropdown d-inline-block">
                                        <a class="btn btn-link btn-square no-caret" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots"></i>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="javascript:void(0)">Favorite</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0)">View Chart</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0)">Company Events</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-inline-block align-middle">
                                        <p class="mb-0">Cyborgous</p>
                                    </div>
                                </td>
                                <td>
                                    <p class="mb-0">$ 150.00</p>
                                    <p class="small"><span class="text-secondary" data-bs-toggle="tooltip" title="Last top price">LTP:</span> 153.00</p>
                                </td>
                                <td>
                                    <p class="mb-0">3 units </p>
                                    <p class="small"><span class="text-secondary">Invested:</span> $ 450.00 </p>
                                </td>
                                <td>
                                    <p class="mb-0 text-success"><i class="bi bi-caret-up-fill"></i> 10.00%</p>
                                    <p class="small"><span class="text-secondary">Profit:</span> $ 45.0 </p>
                                </td>
                                <td>
                                    <p class="mb-0 text-danger"><i class="bi bi-graph-down-arrow"></i> Bearish</p>
                                </td>
                                <td>
                                    <p class="mb-0 text-danger"><i class="bi bi-caret-down-fill"></i> 0.65%</p>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-success">Invest</button>
                                    <button class="btn btn-sm btn-outline-danger">Sell</button>
                                    <div class="dropdown d-inline-block">
                                        <a class="btn btn-link btn-square no-caret" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots"></i>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="javascript:void(0)">Favorite</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0)">View Chart</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0)">Company Events</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-inline-block align-middle">
                                        <p class="mb-0">Spanishwebs</p>
                                        <p class="small text-theme-1"><i class="bi bi-award-fill"></i> Event</p>
                                    </div>
                                </td>
                                <td>
                                    <p class="mb-0">$ 130.45</p>
                                    <p class="small"><span class="text-secondary" data-bs-toggle="tooltip" title="Last top price">LTP:</span> 135.52</p>
                                </td>
                                <td>
                                    <p class="mb-0">100 units </p>
                                    <p class="small"><span class="text-secondary">Invested:</span> $ 150.45 </p>
                                </td>
                                <td>
                                    <p class="mb-0 text-success"><i class="bi bi-caret-up-fill"></i> 5.52%</p>
                                    <p class="small"><span class="text-secondary">Profit:</span> $ 10.15 </p>
                                </td>
                                <td>
                                    <p class="mb-0 text-success"><i class="bi bi-graph-up-arrow"></i> Bullish</p>
                                </td>
                                <td>
                                    <p class="mb-0 text-success"><i class="bi bi-caret-up-fill"></i> 1.24%</p>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-success">Invest</button>
                                    <button class="btn btn-sm btn-outline-danger">Sell</button>
                                    <div class="dropdown d-inline-block">
                                        <a class="btn btn-link btn-square no-caret" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots"></i>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="javascript:void(0)">Favorite</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0)">View Chart</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0)">Company Events</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-inline-block align-middle">
                                        <p class="mb-0">Jintudal</p>
                                        <p class="small text-theme-1"><i class="bi bi-award-fill"></i> Event</p>
                                    </div>
                                </td>
                                <td>
                                    <p class="mb-0">$ 100.45</p>
                                    <p class="small"><span class="text-secondary" data-bs-toggle="tooltip" title="Last top price">LTP:</span> 152</p>
                                </td>
                                <td>
                                    <p class="mb-0">102 units </p>
                                    <p class="small"><span class="text-secondary">Invested:</span> $ 1400.45 </p>
                                </td>
                                <td>
                                    <p class="mb-0 text-success"><i class="bi bi-caret-up-fill"></i> 25.30%</p>
                                    <p class="small"><span class="text-secondary">Profit:</span> $ 305.5 </p>
                                </td>
                                <td>
                                    <p class="mb-0 text-success"><i class="bi bi-graph-up-arrow"></i> Bullish</p>
                                </td>
                                <td>
                                    <p class="mb-0 text-success"><i class="bi bi-caret-up-fill"></i> 1.24%</p>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-success">Invest</button>
                                    <button class="btn btn-sm btn-outline-danger">Sell</button>
                                    <div class="dropdown d-inline-block">
                                        <a class="btn btn-link btn-square no-caret" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots"></i>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="javascript:void(0)">Favorite</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0)">View Chart</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0)">Company Events</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-inline-block align-middle">
                                        <p class="mb-0">Jintudal</p>
                                        <p class="small text-theme-1"><i class="bi bi-award-fill"></i> Event</p>
                                    </div>
                                </td>
                                <td>
                                    <p class="mb-0">$ 100.45</p>
                                    <p class="small"><span class="text-secondary" data-bs-toggle="tooltip" title="Last top price">LTP:</span> 152</p>
                                </td>
                                <td>
                                    <p class="mb-0">102 units </p>
                                    <p class="small"><span class="text-secondary">Invested:</span> $ 1400.45 </p>
                                </td>
                                <td>
                                    <p class="mb-0 text-success"><i class="bi bi-caret-up-fill"></i> 25.30%</p>
                                    <p class="small"><span class="text-secondary">Profit:</span> $ 305.5 </p>
                                </td>
                                <td>
                                    <p class="mb-0 text-success"><i class="bi bi-graph-up-arrow"></i> Bullish</p>
                                </td>
                                <td>
                                    <p class="mb-0 text-success"><i class="bi bi-caret-up-fill"></i> 1.24%</p>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-success">Invest</button>
                                    <button class="btn btn-sm btn-outline-danger">Sell</button>
                                    <div class="dropdown d-inline-block">
                                        <a class="btn btn-link btn-square no-caret" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots"></i>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="javascript:void(0)">Favorite</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0)">View Chart</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0)">Company Events</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-inline-block align-middle">
                                        <p class="mb-0">Jintudal</p>
                                        <p class="small text-theme-1"><i class="bi bi-award-fill"></i> Event</p>
                                    </div>
                                </td>
                                <td>
                                    <p class="mb-0">$ 100.45</p>
                                    <p class="small"><span class="text-secondary" data-bs-toggle="tooltip" title="Last top price">LTP:</span> 152</p>
                                </td>
                                <td>
                                    <p class="mb-0">102 units </p>
                                    <p class="small"><span class="text-secondary">Invested:</span> $ 1400.45 </p>
                                </td>
                                <td>
                                    <p class="mb-0 text-success"><i class="bi bi-caret-up-fill"></i> 25.30%</p>
                                    <p class="small"><span class="text-secondary">Profit:</span> $ 305.5 </p>
                                </td>
                                <td>
                                    <p class="mb-0 text-success"><i class="bi bi-graph-up-arrow"></i> Bullish</p>
                                </td>
                                <td>
                                    <p class="mb-0 text-success"><i class="bi bi-caret-up-fill"></i> 1.24%</p>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-success">Invest</button>
                                    <button class="btn btn-sm btn-outline-danger">Sell</button>
                                    <div class="dropdown d-inline-block">
                                        <a class="btn btn-link btn-square no-caret" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots"></i>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="javascript:void(0)">Favorite</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0)">View Chart</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0)">Company Events</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-inline-block align-middle">
                                        <p class="mb-0">Jintudal</p>
                                        <p class="small text-theme-1"><i class="bi bi-award-fill"></i> Event</p>
                                    </div>
                                </td>
                                <td>
                                    <p class="mb-0">$ 100.45</p>
                                    <p class="small"><span class="text-secondary" data-bs-toggle="tooltip" title="Last top price">LTP:</span> 152</p>
                                </td>
                                <td>
                                    <p class="mb-0">102 units </p>
                                    <p class="small"><span class="text-secondary">Invested:</span> $ 1400.45 </p>
                                </td>
                                <td>
                                    <p class="mb-0 text-success"><i class="bi bi-caret-up-fill"></i> 25.30%</p>
                                    <p class="small"><span class="text-secondary">Profit:</span> $ 305.5 </p>
                                </td>
                                <td>
                                    <p class="mb-0 text-success"><i class="bi bi-graph-up-arrow"></i> Bullish</p>
                                </td>
                                <td>
                                    <p class="mb-0 text-success"><i class="bi bi-caret-up-fill"></i> 1.24%</p>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-success">Invest</button>
                                    <button class="btn btn-sm btn-outline-danger">Sell</button>
                                    <div class="dropdown d-inline-block">
                                        <a class="btn btn-link btn-square no-caret" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots"></i>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="javascript:void(0)">Favorite</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0)">View Chart</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0)">Company Events</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>

                            </tbody>
                        </table>

                    </div>
                </div>
            </div>

            <!-- top gainer -->
            <div class="col-12 col-lg-6 col-xl-4 mb-3">
                <div class="card adminuiux-card">
                    <div class="card-header">
                        <h6>Top Gainer</h6>
                    </div>
                    <ul class="list-group list-group-flush border-top bg-none">
                        <li class="list-group-item">
                            <div class="row gx-3 align-items-center">
                                <div class="col-auto">
                                    <i class="bi bi-building-lock avatar avatar-40 h5 rounded-circle border"></i>
                                </div>
                                <div class="col">
                                    <p class="mb-1 fw-medium">Jindaabanda</p>
                                    <p class="small text-secondary">$ 2250.00, 15 Units</p>
                                </div>
                                <div class="col-auto text-end">
                                    <h6>$ 4500.00</h6>
                                    <p class="small text-success"><i class="bi bi-caret-up-fill"></i> 25.30%</p>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="row gx-3 align-items-center">
                                <div class="col-auto">
                                    <span class="avatar avatar-40 rounded-circle border fw-bold small">SIP</span>
                                </div>
                                <div class="col">
                                    <p class="mb-1 fw-medium">Guaranteed Return</p>
                                    <p class="small text-secondary">$ 1500.00, $ 200.00/mo.</p>
                                </div>
                                <div class="col-auto text-end">
                                    <h6>$ 1800.00</h6>
                                    <p class="small text-success"><i class="bi bi-caret-up-fill"></i> 24.15%</p>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="row gx-3 align-items-center">
                                <div class="col-auto">
                                    <i class="bi bi-percent avatar avatar-40 h5 rounded-circle border"></i>
                                </div>
                                <div class="col">
                                    <p class="mb-1 fw-medium">Fixed Deposit @7.25%</p>
                                    <p class="small text-secondary">$ 4000.00</p>
                                </div>
                                <div class="col-auto text-end">
                                    <h6>$ 5280.00</h6>
                                    <p class="small text-success"><i class="bi bi-caret-up-fill"></i> 22.65%</p>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="row gx-3 align-items-center">
                                <div class="col-auto">
                                    <i class="bi bi-percent avatar avatar-40 h5 rounded-circle border"></i>
                                </div>
                                <div class="col">
                                    <p class="mb-1 fw-medium">Fixed Deposit @7.00%</p>
                                    <p class="small text-secondary">$ 4500.00, 24/45 mo.</p>
                                </div>
                                <div class="col-auto text-end">
                                    <h6>$ 5600.00</h6>
                                    <p class="small text-success"><i class="bi bi-caret-up-fill"></i> 18.15%</p>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="row gx-3 align-items-center">
                                <div class="col-auto">
                                    <span class="avatar avatar-40 rounded-circle border fw-bold small">SIP</span>
                                </div>
                                <div class="col">
                                    <p class="mb-1 fw-medium">Real Estate Fund</p>
                                    <p class="small text-secondary">$ 1500.00</p>
                                </div>
                                <div class="col-auto text-end">
                                    <h6>$ 1850.00</h6>
                                    <p class="small text-success"><i class="bi bi-caret-up-fill"></i> 17.65%</p>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- top loser -->
            <div class="col-12 col-lg-6 col-xl-4 mb-3">
                <div class="card adminuiux-card">
                    <div class="card-header">
                        <h6>Top Loser</h6>
                    </div>
                    <ul class="list-group list-group-flush border-top bg-none">
                        <li class="list-group-item">
                            <div class="row gx-3 align-items-center">
                                <div class="col">
                                    <p class="mb-1 fw-medium">Infragistic LLP</p>
                                    <p class="small text-secondary">$ 2250.00, 10 units</p>
                                </div>
                                <div class="col-auto text-end">
                                    <h6>$ 4500.00</h6>
                                    <p class="small text-danger"><i class="bi bi-caret-down-fill"></i> - 25.30%</p>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="row gx-3 align-items-center">
                                <div class="col">
                                    <p class="mb-1 fw-medium">Reliables</p>
                                    <p class="small text-secondary">$ 1200.00, 15 units</p>
                                </div>
                                <div class="col-auto text-end">
                                    <h6>$ 3150.00</h6>
                                    <p class="small text-danger"><i class="bi bi-caret-down-fill"></i> - 22.65%</p>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="row gx-3 align-items-center">
                                <div class="col">
                                    <p class="mb-1 fw-medium">Alsometan</p>
                                    <p class="small text-secondary">$ 2560.00, 11 units</p>
                                </div>
                                <div class="col-auto text-end">
                                    <h6>$ 4280.00</h6>
                                    <p class="small text-danger"><i class="bi bi-caret-down-fill"></i> - 21.15%</p>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="row gx-3 align-items-center">
                                <div class="col">
                                    <p class="mb-1 fw-medium">LinkedJosh</p>
                                    <p class="small text-secondary">$ 850.00, 25 units</p>
                                </div>
                                <div class="col-auto text-end">
                                    <h6>$ 4280.00</h6>
                                    <p class="small text-danger"><i class="bi bi-caret-down-fill"></i> - 21.05%</p>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="row gx-3 align-items-center">
                                <div class="col">
                                    <p class="mb-1 fw-medium">Real Estate Card</p>
                                    <p class="small text-secondary">$ 1325.00, 13 units</p>
                                </div>
                                <div class="col-auto text-end">
                                    <h6>$ 1850.00</h6>
                                    <p class="small text-danger"><i class="bi bi-caret-down-fill"></i> - 20.75%</p>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- risk-o-meter -->
            <div class="col-12 col-lg-6 col-xl-4 mb-3">
                <div class="card adminuiux-card">
                    <div class="card-header">
                        <h6>Riskometer</h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <h5>Moderate</h5>
                            <p class="text-secondary small">Your portfolio in good situation</p>
                        </div>
                        <div class="riskometer moderate mb-3">
                            <!-- low low-moderate moderate moderate-high very-high high-->
                            <div class="indicator"></div>
                        </div>
                        <br>
                        <div class="text-center">
                            <h4 class="fw-medium">$ 15.51k <span class="text-success fs-14"><i class="bi bi-arrow-up-short me-1"></i> 11.5%</span></h4>
                            <p class="text-secondary">Profit Revenue</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
