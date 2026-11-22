@extends('layouts.master')

@section('content')

    <div class="page-title">
        <div class="title_left">
            <h3>Plan: {{ $plan->name }}</h3>
        </div>
        <div class="title_right">
            <a href="{{ route('plans.index') }}" class="btn btn-default pull-right">
                <i class="fa fa-arrow-left"></i> Back
            </a>
        </div>
    </div>
    <div class="clearfix"></div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Plan Details</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">

                    <table class="table table-bordered">
                        <tr><th style="width:200px;">Code</th><td>{{ $plan->code }}</td></tr>
                        <tr><th>Name</th><td>{{ $plan->name }}</td></tr>
                        <tr><th>Description</th><td>{{ $plan->description ?: '—' }}</td></tr>
                        <tr><th>Price</th><td>{{ $plan->currency }} {{ number_format($plan->price, 2) }}</td></tr>
                        <tr><th>Billing Cycle</th><td>{{ ucfirst($plan->billing_cycle) }}</td></tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if($plan->is_active)
                                    <span class="label label-success">Active</span>
                                @else
                                    <span class="label label-default">Inactive</span>
                                @endif
                            </td>
                        </tr>
                        <tr><th>Trial Days</th><td>{{ $plan->trial_days }} days</td></tr>
                        <tr>
                            <th>Limits</th>
                            <td>
                                <strong>Units:</strong> {{ $plan->getLimit('units', '—') }}<br>
                                <strong>Users:</strong> {{ $plan->getLimit('users', '—') }}<br>
                                <strong>Storage:</strong> {{ $plan->getLimit('storage_gb', '—') }} GB<br>
                                <strong>Orders/Month:</strong> {{ $plan->getLimit('orders_per_month', '—') }}
                            </td>
                        </tr>
                        <tr>
                            <th>Features</th>
                            <td>
                                @foreach(['reports', 'api_access', 'custom_domain', 'priority_support'] as $feature)
                                    {!! $plan->hasFeature($feature) ? 'TRUE ' : 'FALSE ' !!}{{ ucwords(str_replace('_', ' ', $feature)) }}<br>
                                @endforeach
                            </td>
                        </tr>
                    </table>

                    <a href="{{ route('plans.edit', $plan) }}" class="btn btn-warning">
                        <i class="fa fa-pencil"></i> Edit
                    </a>
                    <form action="{{ route('plans.destroy', $plan) }}" method="POST"
                          style="display:inline;"
                          onsubmit="return confirm('Delete this plan?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fa fa-trash"></i> Delete
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>

@endsection