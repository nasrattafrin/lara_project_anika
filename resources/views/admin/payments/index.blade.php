@extends('layouts.master')

@section('content')
    <div class="page-title">
        <div class="title_left"><h3>Payments</h3></div>
        <div class="title_right">
            <a href="{{ route('payments.create') }}" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Record Payment</a>
        </div>
    </div>
    <div class="clearfix"></div>

    @if(session('success')) <div class="alert alert-success">✅ {{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert alert-danger">❌ {{ session('error') }}</div> @endif

    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_content">
                    <table class="table table-striped">
                        <thead>
                            <tr><th>Invoice</th><th>Tenant</th><th>Method</th><th>Reference</th><th>Amount</th><th>Status</th></tr>
                        </thead>
                        <tbody>
                            @forelse($payments as $p)
                                <tr>
                                    <td>{{ $p->invoice->invoice_number ?? '—' }}</td>
                                    <td>{{ $p->tenant->name ?? '—' }}</td>
                                    <td>{{ $p->payment_method }}</td>
                                    <td><code>{{ $p->payment_reference }}</code></td>
                                    <td>{{ $p->currency }} {{ number_format($p->amount, 2) }}</td>
                                    <td><span class="label label-{{ $p->status === 'completed' ? 'success' : 'warning' }}">{{ ucfirst($p->status) }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center">No payments.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $payments->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection