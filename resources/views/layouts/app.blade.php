<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'PrintNexus')</title>

    <link href="{{ asset('assets/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendors/nprogress/nprogress.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/build/css/custom.min.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body class="nav-md">
<div class="container body">
    <div class="main_container">

        {{-- SIDEBAR --}}
        <div class="col-md-3 left_col">
            <div class="left_col scroll-view">
                <div class="navbar nav_title" style="border: 0;">
                    <a href="{{ route('dashboard') }}" class="site_title">
                        <i class="fa fa-paw"></i> <span>PrintNexus</span>
                    </a>
                </div>
                <div class="clearfix"></div>
                <br />
                <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                    <div class="menu_section">
                        <h3>General</h3>
                        <ul class="nav side-menu">
                            <li><a href="{{ route('dashboard') }}"><i class="fa fa-home"></i> Dashboard</a></li>

                            @role('platform_admin')
                                <li><a href="{{ route('plans.index') }}"><i class="fa fa-list"></i> Plans</a></li>
                            @endrole

                            @can('tenants.view')
                                <li><a href="#"><i class="fa fa-building"></i> Tenants</a></li>
                            @endcan

                            @can('orders.view')
                                <li><a href="#"><i class="fa fa-shopping-cart"></i> Orders</a></li>
                            @endcan

                            @can('invoices.view')
                                <li><a href="#"><i class="fa fa-money"></i> Finance</a></li>
                            @endcan
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- TOP NAV --}}
        <div class="top_nav">
            <div class="nav_menu">
                <nav>
                    <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                    </div>
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-user"></i> {{ auth()->user()->name }}
                                <span class="label label-info">{{ auth()->user()->getRoleNames()->first() }}</span>
                                <span class="fa fa-angle-down"></span>
                            </a>
                            <ul class="dropdown-menu dropdown-usermenu pull-right">
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-link" style="color:#5A738E;">
                                            <i class="fa fa-sign-out pull-right"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

        {{-- CONTENT --}}
        <div class="right_col" role="main">
            {{-- Flash messages --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    ✅ {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    ❌ {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </div>
</div>

<script src="{{ asset('assets/vendors/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('assets/vendors/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/build/js/custom.min.js') }}"></script>
@stack('scripts')
</body>
</html>