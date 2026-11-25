@extends('layouts.master')

@section('content')
    <div class="page-title">
        <div class="title_left"><h3>Orders</h3></div>
        <div class="title_right">
            <a href="{{ route('orders.create') }}" class="btn btn-primary pull-right">
                <i class="fa fa-plus"></i> New Order
            </a>
        </div>
    </div>
    <div class="clearfix"></div>

    @if(session('success')) <div class="alert alert-success">✅ {{ session('success') }}</div> @endif

    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_content">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Deadline</th>
                                <th>Status</th>
                                <th>Amount</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <td><strong>{{ $order->order_number }}</strong></td>
                                    <td>{{ $order->deadline?->format('d M Y') ?? '—' }}</td>
                                    <td><span class="label label-info">{{ ucfirst($order->status) }}</span></td>
                                    <td>৳{{ number_format($order->total_amount, 2) }}</td>
                                    <td>
                                        <a href="{{ route('orders.show', $order) }}" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
                                        @if($order->status === 'confirmed')
                                            <a href="{{ route('allocations.scorecard', $order) }}" class="btn btn-primary btn-xs">
                                                <i class="fa fa-calculator"></i> Scorecard
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center">No orders found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection