@extends('layouts.master')

@section('content')

    {{-- WELCOME BANNER --}}
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Welcome back, {{ auth()->user()->name }}!</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <p>
                        <strong>Email:</strong> {{ auth()->user()->email }} |
                        <strong>Role:</strong> {{ auth()->user()->getRoleNames()->first() }} |
                        <strong>Status:</strong>
                        <span class="label label-success">{{ auth()->user()->status }}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- TOP TILES --}}
    <div class="row tile_count">

        {{-- PLATFORM ADMIN --}}
        @role('platform_admin')
            <div class="col-md-3 col-sm-6 col-xs-12 tile_stats_count">
                <span class="count_top"><i class="fa fa-building"></i> Total Tenants</span>
                <div class="count">{{ $totalTenants ?? 0 }}</div>
                <span class="count_bottom">Press accounts</span>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12 tile_stats_count">
                <span class="count_top"><i class="fa fa-list"></i> Total Plans</span>
                <div class="count">{{ $totalPlans ?? 0 }}</div>
                <span class="count_bottom">Subscription plans</span>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12 tile_stats_count">
                <span class="count_top"><i class="fa fa-users"></i> Total Users</span>
                <div class="count">{{ $totalUsers ?? 0 }}</div>
                <span class="count_bottom">Registered users</span>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12 tile_stats_count">
                <span class="count_top"><i class="fa fa-money"></i> Monthly Revenue</span>
                <div class="count green">৳{{ number_format($revenue ?? 0, 0) }}</div>
                <span class="count_bottom">Plan prices</span>
            </div>
        @endrole

        {{-- PRESS RESPONSIBLE --}}
        @role('press_responsible')
            <div class="col-md-3 col-sm-6 col-xs-12 tile_stats_count">
                <span class="count_top"><i class="fa fa-building"></i> Total Units</span>
                <div class="count">{{ $totalUnits ?? 0 }}</div>
                <span class="count_bottom">Press units</span>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12 tile_stats_count">
                <span class="count_top"><i class="fa fa-shopping-cart"></i> Active Orders</span>
                <div class="count">{{ $activeOrders ?? 0 }}</div>
                <span class="count_bottom">In progress</span>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12 tile_stats_count">
                <span class="count_top"><i class="fa fa-cogs"></i> Running Jobs</span>
                <div class="count">{{ $runningJobs ?? 0 }}</div>
                <span class="count_bottom">On production floor</span>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12 tile_stats_count">
                <span class="count_top"><i class="fa fa-clock-o"></i> On-Time Delivery</span>
                <div class="count green">{{ $onTimeRate ?? 0 }}%</div>
                <span class="count_bottom">Last 30 days</span>
            </div>
        @endrole

        {{-- UNIT MANAGER --}}
        @role('unit_manager')
            <div class="col-md-3 col-sm-6 col-xs-12 tile_stats_count">
                <span class="count_top"><i class="fa fa-building"></i> My Unit</span>
                <div class="count" style="font-size: 20px;">
                    {{ auth()->user()->unit?->name ?? '—' }}
                </div>
                <span class="count_bottom">
                    @if(auth()->user()->unit)
                        Assigned unit
                    @else
                        <span class="text-danger">No unit assigned</span>
                    @endif
                </span>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12 tile_stats_count">
                <span class="count_top"><i class="fa fa-tasks"></i> Assigned Jobs</span>
                <div class="count">{{ $assignedJobs ?? 0 }}</div>
                <span class="count_bottom">Pending</span>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12 tile_stats_count">
                <span class="count_top"><i class="fa fa-exchange"></i> Transfer Requests</span>
                <div class="count">{{ $transferRequests ?? 0 }}</div>
                <span class="count_bottom">Pending approval</span>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12 tile_stats_count">
                <span class="count_top"><i class="fa fa-users"></i> My Staff</span>
                <div class="count">{{ $staffCount ?? 0 }}</div>
                <span class="count_bottom">Team members</span>
            </div>
        @endrole

        {{-- ACCOUNTANT --}}
        @role('accountant')
            <div class="col-md-3 col-sm-6 col-xs-12 tile_stats_count">
                <span class="count_top"><i class="fa fa-file-text"></i> Total Invoices</span>
                <div class="count">{{ $totalInvoices ?? 0 }}</div>
                <span class="count_bottom">All time</span>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12 tile_stats_count">
                <span class="count_top"><i class="fa fa-money"></i> Collected</span>
                <div class="count green">৳{{ number_format($collected ?? 0, 0) }}</div>
                <span class="count_bottom">This month</span>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12 tile_stats_count">
                <span class="count_top"><i class="fa fa-exclamation-circle"></i> Pending Dues</span>
                <div class="count red">৳{{ number_format($pendingDues ?? 0, 0) }}</div>
                <span class="count_bottom">Receivables</span>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12 tile_stats_count">
                <span class="count_top"><i class="fa fa-line-chart"></i> Gross Margin</span>
                <div class="count">{{ $grossMargin ?? 0 }}%</div>
                <span class="count_bottom">Revenue - costs</span>
            </div>
        @endrole

        {{-- OPERATOR --}}
        @role('operator')
            <div class="col-md-4 col-sm-6 col-xs-12 tile_stats_count">
                <span class="count_top"><i class="fa fa-tasks"></i> Assigned Jobs</span>
                <div class="count">{{ $assignedJobs ?? 0 }}</div>
                <span class="count_bottom">Pending</span>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12 tile_stats_count">
                <span class="count_top"><i class="fa fa-check-circle"></i> Completed Today</span>
                <div class="count green">{{ $completedToday ?? 0 }}</div>
                <span class="count_bottom">Jobs</span>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12 tile_stats_count">
                <span class="count_top"><i class="fa fa-refresh"></i> In Progress</span>
                <div class="count">{{ $inProgress ?? 0 }}</div>
                <span class="count_bottom">Running</span>
            </div>
        @endrole

        {{-- CUSTOMER --}}
        @role('customer')
            <div class="col-md-3 col-sm-6 col-xs-12 tile_stats_count">
                <span class="count_top"><i class="fa fa-shopping-cart"></i> My Orders</span>
                <div class="count">{{ $myOrders ?? 0 }}</div>
                <span class="count_bottom">All time</span>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12 tile_stats_count">
                <span class="count_top"><i class="fa fa-hourglass-half"></i> In Progress</span>
                <div class="count">{{ $inProgress ?? 0 }}</div>
                <span class="count_bottom">Being produced</span>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12 tile_stats_count">
                <span class="count_top"><i class="fa fa-check"></i> Delivered</span>
                <div class="count green">{{ $delivered ?? 0 }}</div>
                <span class="count_bottom">Completed</span>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12 tile_stats_count">
                <span class="count_top"><i class="fa fa-file-text"></i> Pending Invoices</span>
                <div class="count red">{{ $pendingInvoices ?? 0 }}</div>
                <span class="count_bottom">Unpaid</span>
            </div>
        @endrole

    </div>
    {{-- /top tiles --}}

    {{-- QUICK ACTIONS --}}
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Quick Actions</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    @role('platform_admin')
                        <a href="{{ route('plans.index') }}" class="btn btn-primary"><i class="fa fa-list"></i> Manage Plans</a>
                        <a href="{{ route('tenants.index') }}" class="btn btn-info"><i class="fa fa-building"></i> Manage Tenants</a>
                    @endrole

                    @role('press_responsible')
                        <a href="{{ route('units.index') }}" class="btn btn-primary"><i class="fa fa-building"></i> Manage Units</a>
                        <a href="#" class="btn btn-info"><i class="fa fa-cogs"></i> View Jobs</a>
                        <a href="#" class="btn btn-success"><i class="fa fa-bar-chart"></i> Reports</a>
                    @endrole

                    @role('unit_manager')
                        <a href="{{ route('units.index') }}" class="btn btn-primary"><i class="fa fa-building"></i> My Unit</a>
                        <a href="#" class="btn btn-info"><i class="fa fa-tasks"></i> Assigned Jobs</a>
                        <a href="#" class="btn btn-warning"><i class="fa fa-exchange"></i> Transfer Requests</a>
                    @endrole

                    @role('accountant')
                        <a href="#" class="btn btn-primary"><i class="fa fa-file-text"></i> Create Invoice</a>
                        <a href="#" class="btn btn-success"><i class="fa fa-money"></i> Record Payment</a>
                        <a href="#" class="btn btn-info"><i class="fa fa-bar-chart"></i> Reports</a>
                    @endrole

                    @role('operator')
                        <a href="#" class="btn btn-primary"><i class="fa fa-tasks"></i> My Jobs</a>
                        <a href="#" class="btn btn-info"><i class="fa fa-qrcode"></i> Scan QR</a>
                    @endrole

                    @role('customer')
                        <a href="#" class="btn btn-primary"><i class="fa fa-plus"></i> Place New Order</a>
                        <a href="#" class="btn btn-info"><i class="fa fa-list"></i> My Orders</a>
                        <a href="#" class="btn btn-success"><i class="fa fa-file-text"></i> My Invoices</a>
                    @endrole
                </div>
            </div>
        </div>
    </div>

    {{-- RECENT ACTIVITY + PERMISSIONS --}}
    <div class="row">
        <div class="col-md-8 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Recent Activity <small>Latest updates</small></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <ul class="list-unstyled timeline widget">
                        <li>
                            <div class="block">
                                <div class="block_content">
                                    <h2 class="title"><a>Welcome to PrintNexus</a></h2>
                                    <div class="byline">
                                        <span>Just now</span> by <a>System</a>
                                    </div>
                                    <p class="excerpt">
                                        Your multi-tenant printing press management system is ready.
                                    </p>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>My Permissions</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    @forelse(auth()->user()->getAllPermissions() as $permission)
                        <span class="label label-default" style="display:inline-block; margin: 3px;">
                            {{ $permission->name }}
                        </span>
                    @empty
                        <p class="text-muted">No permissions assigned.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

@endsection