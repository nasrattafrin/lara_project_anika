@extends('layouts.master')

@section('content')
    <div class="page-title">
        <div class="title_left"><h3>Tenant: {{ $tenant->name }}</h3></div>
        <div class="title_right">
            <a href="{{ route('tenants.index') }}" class="btn btn-default pull-right"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
    </div>
    <div class="clearfix"></div>

    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title"><h2>Tenant Details</h2><div class="clearfix"></div></div>
                <div class="x_content">
                    <table class="table table-bordered">
                        <tr><th style="width:200px;">Slug</th><td><code>{{ $tenant->slug }}</code></td></tr>
                        <tr><th>Name</th><td>{{ $tenant->name }}</td></tr>
                        <tr><th>Email</th><td>{{ $tenant->email ?: '—' }}</td></tr>
                        <tr><th>Phone</th><td>{{ $tenant->phone ?: '—' }}</td></tr>
                        <tr><th>Plan</th><td>{{ $tenant->plan->name ?? '—' }}</td></tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <span class="label label-{{ $tenant->status === 'active' ? 'success' : ($tenant->status === 'trial' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($tenant->status) }}
                                </span>
                            </td>
                        </tr>
                        @if($tenant->trial_ends_at)
                            <tr><th>Trial Ends</th><td>{{ $tenant->trial_ends_at->format('d M Y') }}</td></tr>
                        @endif
                        @if($tenant->suspended_at)
                            <tr><th>Suspended At</th><td>{{ $tenant->suspended_at->format('d M Y H:i') }}</td></tr>
                            <tr><th>Suspension Reason</th><td>{{ $tenant->suspension_reason ?: '—' }}</td></tr>
                        @endif
                        <tr><th>Users</th><td>{{ $tenant->users->count() }}</td></tr>
                        <tr><th>Created</th><td>{{ $tenant->created_at->format('d M Y') }}</td></tr>
                    </table>

                    <a href="{{ route('tenants.edit', $tenant) }}" class="btn btn-warning"><i class="fa fa-pencil"></i> Edit</a>
                    <form action="{{ route('tenants.destroy', $tenant) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this tenant?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i> Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection