@extends('layouts.master')

@section('content')
    <div class="page-title"><div class="title_left"><h3>Record Payment</h3></div></div>
    <div class="clearfix"></div>

    <div class="row">
        <div class="col-md-8">
            <div class="x_panel">
                <div class="x_content">
                    @if ($errors->any())
                        <div class="alert alert-danger"><ul>@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
                    @endif
                    @if(session('error')) <div class="alert alert-danger">❌ {{ session('error') }}</div> @endif

                    <form action="{{ route('payments.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Invoice <span class="text-danger">*</span></label>
                            <select name="invoice_id" class="form-control" required>
                                <option value="">— Select —</option>
                                @foreach($invoices as $inv)
                                    <option value="{{ $inv->id }}" {{ old('invoice_id', $selectedInvoice) == $inv->id ? 'selected' : '' }}>
                                        {{ $inv->invoice_number }} — {{ $inv->tenant->name }} — {{ $inv->currency }} {{ number_format($inv->amount, 2) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Amount <span class="text-danger">*</span></label>
                            <input type="number" name="amount" step="0.01" class="form-control" value="{{ old('amount', 0) }}" required>
                        </div>
                        <div class="form-group">
                            <label>Payment Method <span class="text-danger">*</span></label>
                            <select name="payment_method" class="form-control" required>
                                <option value="bkash">bKash</option>
                                <option value="nagad">Nagad</option>
                                <option value="bank">Bank Transfer</option>
                                <option value="stripe">Stripe</option>
                                <option value="cash">Cash</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Payment Reference <span class="text-danger">*</span></label>
                            <input type="text" name="payment_reference" class="form-control" value="{{ old('payment_reference') }}" placeholder="e.g. BKASH123456" required>
                            <small class="text-muted">Unique transaction ID — double payment prevent</small>
                        </div>
                        <div class="form-group">
                            <label>Notes</label>
                            <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Record Payment</button>
                        <a href="{{ route('payments.index') }}" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection