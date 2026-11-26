<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'PrintNexus')</title>

    <!-- Bootstrap -->
    <link href="{{ asset('assets/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <!-- NProgress -->
    <link href="{{ asset('assets/vendors/nprogress/nprogress.css') }}" rel="stylesheet">
    <!-- iCheck -->
    <link href="{{ asset('assets/vendors/iCheck/skins/flat/green.css') }}" rel="stylesheet">
    <!-- bootstrap-progressbar -->
    <link href="{{ asset('assets/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css') }}" rel="stylesheet">
    <!-- JQVMap -->
    <link href="{{ asset('assets/vendors/jqvmap/dist/jqvmap.min.css') }}" rel="stylesheet"/>
    <!-- bootstrap-daterangepicker -->
    <link href="{{ asset('assets/vendors/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="{{ asset('assets/build/css/custom.min.css') }}" rel="stylesheet">

    @stack('styles')
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a href="{{ route('dashboard') }}" class="site_title">
                <i class="fa fa-paw"></i> <span>PrintNexus</span>
              </a>
            </div>

            <div class="clearfix"></div>

            <!-- menu profile quick info -->
            <div class="profile clearfix">
              <div class="profile_pic">
                <img src="{{ asset('assets/images/img.jpg') }}" alt="..." class="img-circle profile_img">
              </div>
              <div class="profile_info">
                <span>Welcome,</span>
                <h2>{{ auth()->user()->name }}</h2>
                <small>{{ auth()->user()->getRoleNames()->first() }}</small>
              </div>
            </div>
            <!-- /menu profile quick info -->

            <br />

            <!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              <div class="menu_section">
                <h3>General</h3>
                <ul class="nav side-menu">

                  {{-- Dashboard  --}}
                  <li>
                    <a href="{{ route('dashboard') }}">
                      <i class="fa fa-home"></i> Dashboard
                    </a>
                  </li>

                  @role('platform_admin')

                    <li>
                      <a><i class="fa fa-building"></i> Tenants <span class="fa fa-chevron-down"></span></a>
                      <ul class="nav child_menu">
                        <li><a href="{{ route('tenants.index') }}">All Tenants</a></li>
                        <li><a href="{{ route('tenants.create') }}">Add New Tenant</a></li>
                      </ul>
                    </li>
                    <li>
                      <a href="{{ route('plans.index') }}"><i class="fa fa-list"></i> Plans</a>
                    </li>
                    <li>
                        <a href="{{ route('subscriptions.index') }}"><i class="fa fa-credit-card"></i> Subscriptions</a>
                    </li>
                    <li>
                        <a href="{{ route('invoices.index') }}"><i class="fa fa-file-text"></i> Invoices</a>
                    </li>
                    <li>
                        <a href="{{ route('payments.index') }}"><i class="fa fa-money"></i> Payments</a>
                    </li>
                   
                    <li>
                      <a><i class="fa fa-shield"></i> Platform <span class="fa fa-chevron-down"></span></a>
                      <ul class="nav child_menu">
                        <li><a href="#">Admins</a></li>
                        <li><a href="#">Audit Logs</a></li>
                      </ul>
                    </li>
                  @endrole

                  
                  @role('press_responsible')
                    <li>
                      <a href="{{ route('units.index') }}">
                          <i class="fa fa-building"></i> Units
                      </a>
                    </li>
                    <li>
                      <a href="{{ route('machines.index') }}">
                          <i class="fa fa-cogs"></i> Machines
                      </a>
                    </li>
                    <li>
                      <a><i class="fa fa-shopping-cart"></i> Orders <span class="fa fa-chevron-down"></span></a>
                      <ul class="nav child_menu">
                        <li><a href="{{ route('orders.index') }}">All Orders</a></li>
                        <li><a href="{{ route('orders.create') }}">New Order</a></li>
                      </ul>
                    </li>
                    <li>
                      <a><i class="fa fa-cogs"></i> Production <span class="fa fa-chevron-down"></span></a>
                      <ul class="nav child_menu">
                        <li><a href="{{ route('jobs.index') }}">Jobs</a></li>
                        <li><a href="#">Transfers</a></li>
                      </ul>
                    </li>
                    <li>
                      <a href="#"><i class="fa fa-cubes"></i> Inventory</a>
                    </li>
                    <li>
                      <a href="#"><i class="fa fa-bar-chart"></i> Reports</a>
                    </li>
                  @endrole

                
                  @role('unit_manager')
                    <li>
                        <a href="{{ route('unit.my-unit') }}">
                            <i class="fa fa-building"></i> My Unit
                        </a>
                    </li>
                    <li>
                      <a href="{{ route('jobs.index') }}"><i class="fa fa-tasks"></i> Assigned Jobs</a>
                    </li>
                    <li>
                      <a href="#"><i class="fa fa-exchange"></i> Transfer Requests</a>
                    </li>
                    <li>
                      <a href="#"><i class="fa fa-users"></i> Staff</a>
                    </li>
                    <li>
                      <a href="#"><i class="fa fa-cogs"></i> Machines</a>
                    </li>
                  @endrole

                
                  @role('accountant')
                    <li>
                      <a><i class="fa fa-file-text"></i> Invoices <span class="fa fa-chevron-down"></span></a>
                      <ul class="nav child_menu">
                        <li><a href="#">All Invoices</a></li>
                        <li><a href="#">Create Invoice</a></li>
                        <li><a href="#">Receivables</a></li>
                      </ul>
                    </li>
                    <li>
                      <a><i class="fa fa-money"></i> Payments <span class="fa fa-chevron-down"></span></a>
                      <ul class="nav child_menu">
                        <li><a href="#">All Payments</a></li>
                        <li><a href="#">Record Payment</a></li>
                      </ul>
                    </li>
                    <li>
                      <a href="#"><i class="fa fa-credit-card"></i> Expenses</a>
                    </li>
                    <li>
                      <a href="#"><i class="fa fa-users"></i> Payroll</a>
                    </li>
                    <li>
                      <a href="#"><i class="fa fa-bar-chart"></i> Reports</a>
                    </li>
                  @endrole

                  
                  @role('operator')
                    <li>
                      <a href="{{ route('jobs.index') }}"><i class="fa fa-tasks"></i> My Jobs</a>
                    </li>
                    <li>
                      <a href="#"><i class="fa fa-qrcode"></i> Scan QR</a>
                    </li>
                    <li>
                      <a href="#"><i class="fa fa-check-circle"></i> Quality Check</a>
                    </li>
                  @endrole

                  
                  @role('customer')
                    <li>
                      <a><i class="fa fa-shopping-cart"></i> My Orders <span class="fa fa-chevron-down"></span></a>
                      <ul class="nav child_menu">
                        <li><a href="#">All Orders</a></li>
                        <li><a href="#">New Order</a></li>
                      </ul>
                    </li>
                    <li>
                      <a href="#"><i class="fa fa-file-text"></i> My Invoices</a>
                    </li>
                    <li>
                      <a href="#"><i class="fa fa-user"></i> My Profile</a>
                    </li>
                  @endrole

                </ul>
              </div>
            </div>
            <!-- /sidebar menu -->

            <!-- /menu footer buttons -->
            <div class="sidebar-footer hidden-small">
              <a data-toggle="tooltip" data-placement="top" title="Settings">
                <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="Lock">
                <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
              </a>
              <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" data-toggle="tooltip" data-placement="top" title="Logout" style="background: transparent; border: 0; color: inherit;">
                  <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                </button>
              </form>
            </div>
            <!-- /menu footer buttons -->
          </div>
        </div>

        <!-- top navigation -->
        <div class="top_nav">
          <div class="nav_menu">
            <nav>
              <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
              </div>

              <ul class="nav navbar-nav navbar-right">
                <li class="">
                  <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <img src="{{ asset('assets/images/img.jpg') }}" alt="">{{ auth()->user()->name }}
                    <span class=" fa fa-angle-down"></span>
                  </a>
                  <ul class="dropdown-menu dropdown-usermenu pull-right">
                    <li>
                      <span class="label label-info" style="margin: 5px 20px; display: inline-block;">
                        Role: {{ auth()->user()->getRoleNames()->first() }}
                      </span>
                    </li>
                    <li><a href="javascript:;"> Profile</a></li>
                    <li><a href="javascript:;"> Help</a></li>
                    <li>
                      <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" style="background: transparent; border: 0; width: 100%; text-align: left; padding: 5px 20px; color: #5A738E;">
                          <i class="fa fa-sign-out pull-right"></i> Log Out
                        </button>
                      </form>
                    </li>
                  </ul>
                </li>
              </ul>
            </nav>
          </div>
        </div>
        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main">

          {{-- Flash messages --}}
          @if(session('success'))
            <div class="alert alert-success alert-dismissible fade in" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
              <strong> Success!</strong> {{ session('success') }}
            </div>
          @endif

          @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade in" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
              <strong> Error!</strong> {{ session('error') }}
            </div>
          @endif

          @yield('content')
        </div>
        <!-- /page content -->

        <!-- footer content -->
        <footer>
          <div class="pull-right">
            PrintNexus - Multi-Tenant Printing Press Management System
          </div>
          <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
      </div>
    </div>

    <!-- jQuery -->
    <script src="{{ asset('assets/vendors/jquery/dist/jquery.min.js') }}"></script>
    <!-- Bootstrap -->
    <script src="{{ asset('assets/vendors/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <!-- FastClick -->
    <script src="{{ asset('assets/vendors/fastclick/lib/fastclick.js') }}"></script>
    <!-- NProgress -->
    <script src="{{ asset('assets/vendors/nprogress/nprogress.js') }}"></script>
    <!-- Chart.js -->
    <script src="{{ asset('assets/vendors/Chart.js/dist/Chart.min.js') }}"></script>
    <!-- gauge.js -->
    <script src="{{ asset('assets/vendors/gauge.js/dist/gauge.min.js') }}"></script>
    <!-- bootstrap-progressbar -->
    <script src="{{ asset('assets/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js') }}"></script>
    <!-- iCheck -->
    <script src="{{ asset('assets/vendors/iCheck/icheck.min.js') }}"></script>
    <!-- Skycons -->
    <script src="{{ asset('assets/vendors/skycons/skycons.js') }}"></script>
    <!-- Flot -->
    <script src="{{ asset('assets/vendors/Flot/jquery.flot.js') }}"></script>
    <script src="{{ asset('assets/vendors/Flot/jquery.flot.pie.js') }}"></script>
    <script src="{{ asset('assets/vendors/Flot/jquery.flot.time.js') }}"></script>
    <script src="{{ asset('assets/vendors/Flot/jquery.flot.stack.js') }}"></script>
    <script src="{{ asset('assets/vendors/Flot/jquery.flot.resize.js') }}"></script>
    <!-- Flot plugins -->
    <script src="{{ asset('assets/vendors/flot.orderbars/js/jquery.flot.orderBars.js') }}"></script>
    <script src="{{ asset('assets/vendors/flot-spline/js/jquery.flot.spline.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/flot.curvedlines/curvedLines.js') }}"></script>
    <!-- DateJS -->
    <script src="{{ asset('assets/vendors/DateJS/build/date.js') }}"></script>
    <!-- JQVMap -->
    <script src="{{ asset('assets/vendors/jqvmap/dist/jquery.vmap.js') }}"></script>
    <script src="{{ asset('assets/vendors/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>
    <script src="{{ asset('assets/vendors/jqvmap/examples/js/jquery.vmap.sampledata.js') }}"></script>
    <!-- bootstrap-daterangepicker -->
    <script src="{{ asset('assets/vendors/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/bootstrap-daterangepicker/daterangepicker.js') }}"></script>

    <!-- Custom Theme Scripts -->
    <script src="{{ asset('assets/build/js/custom.min.js') }}"></script>

    @stack('scripts')
  </body>
</html>