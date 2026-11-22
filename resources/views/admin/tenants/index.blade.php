@extends('layouts.master')

@section('content')
    <div class="page-title">
        <div class="title_left"><h3>Tenants</h3></div>
        <div class="title_right">
            <a href="{{ route('tenants.create') }}" class="btn btn-primary pull-right">
                <i class="fa fa-plus"></i> Create New Tenant
            </a>
        </div>
    </div>
    <div class="clearfix"></div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            ✅ {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            ❌ {{ session('error') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>All Tenants <small>Printing press accounts</small></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Slug</th>
                                <th>Name</th>
                                <th>Plan</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th style="width:160px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tenants as $tenant)
                                <tr>
                                    <td><code>{{ $tenant->slug }}</code></td>
                                    <td><strong>{{ $tenant->name }}</strong></td>
                                    <td>{{ $tenant->plan->name ?? '—' }}</td>
                                    <td>{{ $tenant->email ?? '—' }}</td>
                                    <td>
                                        @php
                                            $badge = match($tenant->status) {
                                                'active' => 'label-success',
                                                'trial' => 'label-warning',
                                                'suspended' => 'label-danger',
                                                'cancelled' => 'label-default',
                                                'expired' => 'label-default',
                                                default => 'label-default',
                                            };
                                        @endphp
                                        <span class="label {{ $badge }}">{{ ucfirst($tenant->status) }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('tenants.show', $tenant) }}" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
                                        <a href="{{ route('tenants.edit', $tenant) }}" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a>
                                        <form action="{{ route('tenants.destroy', $tenant) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this tenant?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center">No tenants found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="text-center">{{ $tenants->links() }}</div>
                </div>
            </div>
        </div>
    </div>
@endsection