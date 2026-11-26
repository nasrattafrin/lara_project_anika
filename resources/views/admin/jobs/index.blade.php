@extends('layouts.master')

@section('title', 'Production Jobs')

@section('content')
    @php
        $colors = [
            'queued' => 'default', 'printing' => 'primary', 'qc' => 'warning',
            'ready' => 'info', 'delivered' => 'success',
        ];
    @endphp

    <div class="page-title">
        <div class="title_left"><h3>Production Jobs</h3></div>
    </div>
    <div class="clearfix"></div>

    @if($noUnit)
        <div class="alert alert-warning">No unit has been assigned to your account.Please ask the Press Responsible to assign a unit.</div>
    @endif

    <div class="x_panel">
        <div class="x_title">
            <h2>All Jobs</h2>
            <div class="pull-right">
                <a href="{{ route('jobs.index') }}" class="btn btn-xs btn-default">All</a>
                @foreach(['queued','printing','qc','ready','delivered'] as $s)
                    <a href="{{ route('jobs.index', ['status' => $s]) }}" class="btn btn-xs btn-{{ $colors[$s] }}">{{ ucfirst($s) }}</a>
                @endforeach
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Job #</th><th>Order</th><th>Unit</th><th>Qty</th>
                        <th>Deadline</th><th>Status</th><th>Rework</th><th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jobs as $job)
                        <tr>
                            <td>{{ $job->job_number }}</td>
                            <td>{{ $job->order->order_number ?? '—' }}</td>
                            <td>{{ $job->unit->name ?? '—' }}</td>
                            <td>{{ number_format($job->quantity) }}</td>
                            <td>{{ $job->deadline?->format('d M Y') ?? '—' }}</td>
                            <td><span class="label label-{{ $colors[$job->status] ?? 'default' }}">{{ ucfirst($job->status) }}</span></td>
                            <td>{{ $job->rework_count }}</td>
                            <td><a href="{{ route('jobs.show', $job->id) }}" class="btn btn-xs btn-primary">Open</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted">No jobs found.</td></tr>
                    @endforelse
                </tbody>
            </table>

            {{ $jobs->links() }}
        </div>
    </div>
@endsection