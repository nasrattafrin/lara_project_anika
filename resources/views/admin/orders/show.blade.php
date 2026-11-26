@extends('layouts.master')

@section('content')
    <div class="page-title">
        <div class="title_left"><h3>Order {{ $order->order_number }}</h3></div>
        <div class="title_right">
            <a href="{{ route('orders.index') }}" class="btn btn-default pull-right"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
    </div>
    <div class="clearfix"></div>

    <div class="row">
        <div class="col-md-6">
            <div class="x_panel">
                <div class="x_title"><h2>Order Details</h2><div class="clearfix"></div></div>
                <div class="x_content">
                    <table class="table table-bordered">
                        <tr><th>Order #</th><td>{{ $order->order_number }}</td></tr>
                        <tr><th>Status</th><td><span class="label label-info">{{ ucfirst($order->status) }}</span></td></tr>
                        <tr><th>Deadline</th><td>{{ $order->deadline?->format('d M Y') ?? '—' }}</td></tr>
                        <tr><th>Total</th><td>৳{{ number_format($order->total_amount, 2) }}</td></tr>
                    </table>

                    @if($order->status === 'confirmed')
                        <a href="{{ route('allocations.scorecard', $order) }}" class="btn btn-primary">
                            <i class="fa fa-calculator"></i> View Allocation Scorecard
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="x_panel">
                <div class="x_title"><h2>Items</h2><div class="clearfix"></div></div>
                <div class="x_content">
                    <table class="table table-striped">
                        <thead><tr><th>Product</th><th>Qty</th><th>Total</th></tr></thead>
                        <tbody>
                            @forelse($order->items as $item)
                                <tr>
                                    <td>{{ $item->product_name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>৳{{ number_format($item->total_price, 2) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3">No items.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @if($order->allocations->count() > 0)
        <div class="row">
            <div class="col-md-12">
                <div class="x_panel">
                    <div class="x_title"><h2>Allocation History</h2><div class="clearfix"></div></div>
                    <div class="x_content">
                        <table class="table table-striped">
                            <thead><tr><th>Unit</th><th>Score</th><th>Status</th><th>Approved By</th></tr></thead>
                            <tbody>
                                @foreach($order->allocations as $alloc)
                                    <tr>
                                        <td>{{ $alloc->unit->name ?? '—' }}</td>
                                        <td>{{ $alloc->score }}</td>
                                        <td><span class="label label-info">{{ ucfirst($alloc->status) }}</span></td>
                                        <td>{{ $alloc->approvedBy?->name ?? '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($order->jobs->count() > 0)
        <div class="row">
            <div class="col-md-12">
                <div class="x_panel">
                    <div class="x_title"><h2>Production Job</h2><div class="clearfix"></div></div>
                    <div class="x_content">
                        <table class="table table-striped">
                            <thead><tr><th>Job #</th><th>Unit</th><th>Qty</th><th>Status</th><th></th></tr></thead>
                            <tbody>
                                @foreach($order->jobs as $job)
                                    <tr>
                                        <td>{{ $job->job_number }}</td>
                                        <td>{{ $job->unit->name ?? '—' }}</td>
                                        <td>{{ number_format($job->quantity) }}</td>
                                        <td><span class="label label-primary">{{ ucfirst($job->status) }}</span></td>
                                        <td><a href="{{ route('jobs.show', $job->id) }}" class="btn btn-xs btn-primary">Open</a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection