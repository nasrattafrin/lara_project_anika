@extends('layouts.master')

@section('content')

    <div class="page-title">
        <div class="title_left">
            <h3>Plans</h3>
        </div>
        <div class="title_right">
            <a href="{{ route('plans.create') }}" class="btn btn-primary pull-right">
                <i class="fa fa-plus"></i> Create New Plan
            </a>
        </div>
    </div>
    <div class="clearfix"></div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade in" role="alert">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
             {{ session('success') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>All Plans <small>Subscription plans</small></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Billing</th>
                                <th>Units</th>
                                <th>Users</th>
                                <th>Status</th>
                                <th style="width:160px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($plans as $plan)
                                <tr>
                                    <td><strong>{{ $plan->code }}</strong></td>
                                    <td>{{ $plan->name }}</td>
                                    <td>{{ $plan->currency }} {{ number_format($plan->price, 2) }}</td>
                                    <td>{{ ucfirst($plan->billing_cycle) }}</td>
                                    <td>{{ $plan->getLimit('units', '—') }}</td>
                                    <td>{{ $plan->getLimit('users', '—') }}</td>
                                    <td>
                                        @if($plan->is_active)
                                            <span class="label label-success">Active</span>
                                        @else
                                            <span class="label label-default">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('plans.show', $plan) }}" class="btn btn-info btn-xs">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a href="{{ route('plans.edit', $plan) }}" class="btn btn-warning btn-xs">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                        <form action="{{ route('plans.destroy', $plan) }}" method="POST"
                                              style="display:inline;"
                                              onsubmit="return confirm('Delete this plan?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-xs">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No plans found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="text-center">
                        {{ $plans->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection