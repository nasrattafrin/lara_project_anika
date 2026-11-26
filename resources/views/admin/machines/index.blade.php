@extends('layouts.master')

@section('title', 'Machines')

@section('content')
    <div class="page-title">
        <div class="title_left"><h3>Machines</h3></div>
        <div class="title_right">
            <a href="{{ route('machines.create') }}" class="btn btn-primary pull-right">
                <i class="fa fa-plus"></i> Add Machine
            </a>
        </div>
    </div>
    <div class="clearfix"></div>

    <div class="x_panel">
        <div class="x_content">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Unit</th><th>Machine</th><th>Type</th>
                        <th>Capacity / day</th><th>Cost / piece</th><th>Status</th><th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($machines as $m)
                        <tr>
                            <td>{{ $m->unit->name ?? '—' }}</td>
                            <td>{{ $m->name }}</td>
                            <td>{{ $m->typeLabel() }}</td>
                            <td>{{ number_format($m->capacity_per_day) }}</td>
                            <td>৳{{ rtrim(rtrim(number_format($m->cost_per_unit, 4), '0'), '.') }}</td>
                            <td>
                                <span class="label label-{{ $m->status === 'active' ? 'success' : ($m->status === 'maintenance' ? 'warning' : 'default') }}">
                                    {{ ucfirst($m->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('machines.edit', $m) }}" class="btn btn-xs btn-info">Edit</a>
                                <form action="{{ route('machines.destroy', $m) }}" method="POST" style="display:inline;"
                                      onsubmit="return confirm('Delete this machine?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-xs btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted">There are no machines.Add a machine first,otherwise no unit will be eligible for allocation</td></tr>
                    @endforelse
                </tbody>
            </table>

            {{ $machines->links() }}
        </div>
    </div>
@endsection