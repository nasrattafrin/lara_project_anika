@extends('layouts.master')

@section('content')
    <div class="page-title">
        <div class="title_left"><h3>Invoices</h3></div>
        <div class="title_right">
            <a href="{{ route('invoices.create') }}" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> New Invoice</a>
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
                            <tr><th>Invoice #</th><th>Tenant</th><th>Amount</th><th>Status</th><th>Due</th><th>Actions</th></tr>
                        </thead>
                        <tbody>
                            @forelse($invoices as $inv)
                                <tr>
                                    <td><a href="{{ route('invoices.show', $inv) }}">{{ $inv->invoice_number }}</a></td>
                                    <td>{{ $inv->tenant->name ?? '—' }}</td>
                                    <td>{{ $inv->currency }} {{ number_format($inv->amount, 2) }}</td>
                                    <td>
                                        <span class="label label-{{ $inv->status === 'paid' ? 'success' : ($inv->status === 'overdue' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($inv->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $inv->due_date->format('d M Y') }}</td>
                                    <td>
                                        <a href="{{ route('invoices.show', $inv) }}" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
                                        @if($inv->status !== 'paid')
                                            <a href="{{ route('payments.create', ['invoice_id' => $inv->id]) }}" class="btn btn-success btn-xs">
                                                <i class="fa fa-money"></i> Pay
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center">No invoices.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $invoices->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection