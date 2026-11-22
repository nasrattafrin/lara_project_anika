@extends('layouts.master')

@section('content')
    <div class="page-title">
        <div class="title_left"><h3>Units</h3></div>
        <div class="title_right">
            @if($limitCheck['allowed'])
                <a href="{{ route('units.create') }}" class="btn btn-primary pull-right">
                    <i class="fa fa-plus"></i> Create Unit
                    <small>({{ $limitCheck['current'] }}/{{ $limitCheck['limit'] }})</small>
                </a>
            @else
                <a href="{{ route('plans.index') }}" class="btn btn-warning pull-right" style="margin-right:5px;">
                    <i class="fa fa-arrow-up"></i> Upgrade Plan
                </a>
                <button class="btn btn-default pull-right" disabled>
                    <i class="fa fa-lock"></i> Limit Reached ({{ $limitCheck['current'] }}/{{ $limitCheck['limit'] }})
                </button>
            @endif
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
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>All Units <small>Printing units</small></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th style="width:160px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($units as $unit)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><strong>{{ $unit->name }}</strong></td>
                                    <td>{{ $unit->description ?? '—' }}</td>
                                    <td>
                                        @php
                                            $badge = match($unit->status) {
                                                'active' => 'label-success',
                                                'inactive' => 'label-default',
                                                'maintenance' => 'label-warning',
                                                default => 'label-default',
                                            };
                                        @endphp
                                        <span class="label {{ $badge }}">{{ ucfirst($unit->status) }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('units.show', $unit) }}" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
                                        <a href="{{ route('units.edit', $unit) }}" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a>
                                        <form action="{{ route('units.destroy', $unit) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this unit?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center">No units found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="text-center">{{ $units->links() }}</div>
                </div>
            </div>
        </div>
    </div>
@endsection