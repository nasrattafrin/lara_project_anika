@extends('layouts.master')

@section('content')
    <div class="page-title"><div class="title_left"><h3>New Invoice</h3></div></div>
    <div class="clearfix"></div>

    <div class="row">
        <div class="col-md-8">
            <div class="x_panel">
                <div class="x_content">
                    @if ($errors->any())
                        <div class="alert alert-danger"><ul>@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
                    @endif

                    <form action="{{ route('invoices.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Subscription <span class="text-danger">*</span></label>
                            <select name="subscription_id" class="form-control" required>
                                <option value="">— Select —</option>
                                @foreach($subscriptions as $sub)
                                    <option value="{{ $sub->id }}" {{ old('subscription_id', $selectedSubscription) == $sub->id ? 'selected' : '' }}>
                                        {{ $sub->tenant->name }} — {{ $sub->plan->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Subtotal *</label>
                                    <input type="number" name="subtotal" step="0.01" class="form-control" value="{{ old('subtotal', 0) }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tax</label>
                                    <input type="number" name="tax" step="0.01" class="form-control" value="{{ old('tax', 0) }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Discount</label>
                                    <input type="number" name="discount" step="0.01" class="form-control" value="{{ old('discount', 0) }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Due Date <span class="text-danger">*</span></label>
                            <input type="date" name="due_date" class="form-control" value="{{ old('due_date', now()->addDays(7)->format('Y-m-d')) }}" required>
                        </div>
                        <div class="form-group">
                            <label>Notes</label>
                            <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Create Invoice</button>
                        <a href="{{ route('invoices.index') }}" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection