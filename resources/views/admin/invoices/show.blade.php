@extends('layouts.master')

@section('content')
    <div class="page-title">
        <div class="title_left"><h3>Invoice {{ $invoice->invoice_number }}</h3></div>
        <div class="title_right">
            <a href="{{ route('invoices.index') }}" class="btn btn-default pull-right"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
    </div>
    <div class="clearfix"></div>

    <div class="row">
        <div class="col-md-6">
            <div class="x_panel">
                <div class="x_title"><h2>Invoice Details</h2><div class="clearfix"></div></div>
                <div class="x_content">
                    <table class="table table-bordered">
                        <tr><th>Tenant</th><td>{{ $invoice->tenant->name ?? '—' }}</td></tr>
                        <tr><th>Subscription</th><td>#{{ $invoice->subscription_id }}</td></tr>
                        <tr><th>Subtotal</th><td>{{ $invoice->currency }} {{ number_format($invoice->subtotal, 2) }}</td></tr>
                        <tr><th>Tax</th><td>{{ $invoice->currency }} {{ number_format($invoice->tax, 2) }}</td></tr>
                        <tr><th>Discount</th><td>{{ $invoice->currency }} {{ number_format($invoice->discount, 2) }}</td></tr>
                        <tr><th>Total</th><td><strong>{{ $invoice->currency }} {{ number_format($invoice->amount, 2) }}</strong></td></tr>
                        <tr><th>Status</th><td><span class="label label-info">{{ ucfirst($invoice->status) }}</span></td></tr>
                        <tr><th>Due</th><td>{{ $invoice->due_date->format('d M Y') }}</td></tr>
                        @if($invoice->paid_at)
                            <tr><th>Paid</th><td>{{ $invoice->paid_at->format('d M Y H:i') }}</td></tr>
                        @endif
                    </table>

                    @if($invoice->status !== 'paid')
                        <a href="{{ route('payments.create', ['invoice_id' => $invoice->id]) }}" class="btn btn-success">
                            <i class="fa fa-money"></i> Record Payment
                        </a>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="x_panel">
                <div class="x_title"><h2>Payments</h2><div class="clearfix"></div></div>
                <div class="x_content">
                    <table class="table table-striped">
                        <thead><tr><th>Method</th><th>Ref</th><th>Amount</th><th>Status</th></tr></thead>
                        <tbody>
                            @forelse($invoice->payments as $p)
                                <tr>
                                    <td>{{ $p->payment_method }}</td>
                                    <td><code>{{ $p->payment_reference }}</code></td>
                                    <td>{{ $p->currency }} {{ number_format($p->amount, 2) }}</td>
                                    <td><span class="label label-{{ $p->status === 'completed' ? 'success' : 'default' }}">{{ ucfirst($p->status) }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="4">No payments yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection