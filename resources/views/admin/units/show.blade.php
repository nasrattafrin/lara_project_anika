@extends('layouts.master')

@section('content')
    <div class="page-title">
        <div class="title_left"><h3>Unit: {{ $unit->name }}</h3></div>
        <div class="title_right">
            <a href="{{ route('units.index') }}" class="btn btn-default pull-right"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
    </div>
    <div class="clearfix"></div>

    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_content">
                    <table class="table table-bordered">
                        <tr><th style="width:200px;">Name</th><td>{{ $unit->name }}</td></tr>
                        <tr><th>Description</th><td>{{ $unit->description ?: '—' }}</td></tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <span class="label label-{{ $unit->status === 'active' ? 'success' : ($unit->status === 'maintenance' ? 'warning' : 'default') }}">
                                    {{ ucfirst($unit->status) }}
                                </span>
                            </td>
                        </tr>
                        <tr><th>Created</th><td>{{ $unit->created_at->format('d M Y') }}</td></tr>
                    </table>

                    <a href="{{ route('units.edit', $unit) }}" class="btn btn-warning"><i class="fa fa-pencil"></i> Edit</a>
                    <form action="{{ route('units.destroy', $unit) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this unit?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i> Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection