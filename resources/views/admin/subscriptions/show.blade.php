@extends('layouts.master')

@section('content')
    <div class="page-title">
        <div class="title_left"><h3>Subscription #{{ $subscription->id }}</h3></div>
        <div class="title_right">
            <a href="{{ route('subscriptions.index') }}" class="btn btn-default pull-right"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
    </div>
    <div class="clearfix"></div>

    <div class="row">
        <div class="col-md-6">
            <div class="x_panel">
                <div class="x_title"><h2>Details</h2><div class="clearfix"></div></div>
                <div class="x_content">
                    <table class="table table-bordered">
                        <tr><th>Tenant</th><td>{{ $subscription->tenant->name ?? '—' }}</td></tr>
                        <tr><th>Plan</th><td>{{ $subscription->plan->name ?? '—' }}</td></tr>
                        <tr><th>Amount</th><td>{{ $subscription->currency }} {{ number_format($subscription->amount, 2) }}</td></tr>
                        <tr><th>Status</th><td><span class="label label-info">{{ ucfirst($subscription->status) }}</span></td></tr>
                        <tr><th>Starts</th><td>{{ $subscription->starts_at?->format('d M Y') }}</td></tr>
                        <tr><th>Ends</th><td>{{ $subscription->ends_at?->format('d M Y') }}</td></tr>
                        <tr><th>Auto Renew</th><td>{{ $subscription->auto_renew ? 'Yes' : 'No' }}</td></tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="x_panel">
                <div class="x_title"><h2>Invoices</h2><div class="clearfix"></div></div>
                <div class="x_content">
                    <table class="table table-striped">
                        <thead><tr><th>#</th><th>Amount</th><th>Status</th><th>Due</th></tr></thead>
                        <tbody>
                            @forelse($subscription->invoices as $inv)
                                <tr>
                                    <td><a href="{{ route('invoices.show', $inv) }}">{{ $inv->invoice_number }}</a></td>
                                    <td>{{ $inv->currency }} {{ number_format($inv->amount, 2) }}</td>
                                    <td><span class="label label-{{ $inv->status === 'paid' ? 'success' : 'warning' }}">{{ ucfirst($inv->status) }}</span></td>
                                    <td>{{ $inv->due_date->format('d M Y') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4">No invoices yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    <a href="{{ route('invoices.create', ['subscription_id' => $subscription->id]) }}" class="btn btn-primary btn-sm">
                        <i class="fa fa-plus"></i> Generate Invoice
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection