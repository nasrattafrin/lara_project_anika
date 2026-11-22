@extends('layouts.master')

@section('content')
    <div class="page-title"><div class="title_left"><h3>New Subscription</h3></div></div>
    <div class="clearfix"></div>

    <div class="row">
        <div class="col-md-8">
            <div class="x_panel">
                <div class="x_content">
                    @if ($errors->any())
                        <div class="alert alert-danger"><ul>@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
                    @endif

                    <form action="{{ route('subscriptions.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Tenant <span class="text-danger">*</span></label>
                            <select name="tenant_id" class="form-control" required>
                                <option value="">— Select Tenant —</option>
                                @foreach($tenants as $tenant)
                                    <option value="{{ $tenant->id }}" {{ old('tenant_id', $selectedTenant) == $tenant->id ? 'selected' : '' }}>{{ $tenant->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Plan <span class="text-danger">*</span></label>
                            <select name="plan_id" class="form-control" required>
                                <option value="">— Select Plan —</option>
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}">{{ $plan->name }} ({{ $plan->currency }} {{ number_format($plan->price) }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Billing Cycle <span class="text-danger">*</span></label>
                            <select name="billing_cycle" class="form-control" required>
                                <option value="monthly">Monthly</option>
                                <option value="yearly">Yearly</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label><input type="checkbox" name="auto_renew" value="1" checked> Auto Renew</label>
                        </div>
                        <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Create</button>
                        <a href="{{ route('subscriptions.index') }}" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection