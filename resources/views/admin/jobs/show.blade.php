@extends('layouts.master')

@section('title', 'Job ' . $job->job_number)

@section('content')
    @php
        $stages = ['queued', 'printing', 'qc', 'ready', 'delivered'];
        $currentIndex = array_search($job->status, $stages);
        $next = $job->nextStatus();
        $buttonText = [
            'printing'  => 'Start Printing',
            'qc'        => 'Send to QC',
            'ready'     => 'Pass QC (Mark Ready)',
            'delivered' => 'Confirm Delivery',
        ];
        $canAdvance = $next && !($next === 'delivered' && auth()->user()->role === 'operator');
    @endphp

    <div class="page-title">
        <div class="title_left"><h3>Job {{ $job->job_number }}</h3></div>
        <div class="title_right">
            <a href="{{ route('jobs.index') }}" class="btn btn-default pull-right"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
    </div>
    <div class="clearfix"></div>

    {{-- Progress --}}
    <div class="x_panel">
        <div class="x_content text-center">
            @foreach($stages as $i => $stage)
                <span class="label label-{{ $i < $currentIndex ? 'success' : ($i == $currentIndex ? 'primary' : 'default') }}"
                      style="font-size:14px; padding:8px 14px; margin:2px;">
                    {{ ucfirst($stage) }}
                </span>
                @if(!$loop->last) <i class="fa fa-arrow-right text-muted"></i> @endif
            @endforeach
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="x_panel">
                <div class="x_title"><h2>Details</h2><div class="clearfix"></div></div>
                <div class="x_content">
                    <table class="table table-bordered">
                        <tr><th>Order</th><td>{{ $job->order->order_number ?? '—' }}</td></tr>
                        <tr><th>Product</th><td>{{ $job->order->items->first()->product_name ?? '—' }}</td></tr>
                        <tr><th>Unit</th><td>{{ $job->unit->name ?? '—' }}</td></tr>
                        <tr><th>Quantity</th><td>{{ number_format($job->quantity) }}</td></tr>
                        <tr><th>Deadline</th><td>{{ $job->deadline?->format('d M Y') ?? '—' }}</td></tr>
                        <tr><th>Rework count</th><td>{{ $job->rework_count }}</td></tr>
                        <tr><th>Completed at</th><td>{{ $job->completed_at?->format('d M Y H:i') ?? '—' }}</td></tr>
                    </table>

                    @if($canAdvance)
                        <form action="{{ route('jobs.advance', $job->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button class="btn btn-success"><i class="fa fa-check"></i> {{ $buttonText[$next] }}</button>
                        </form>
                    @endif

                    @if($job->status === 'qc')
                        <hr>
                        <form action="{{ route('jobs.rework', $job->id) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>QC fail? Rework er karon likho</label>
                                <input type="text" name="note" class="form-control" placeholder="e.g. Color mismatch" required>
                            </div>
                            <button class="btn btn-danger"><i class="fa fa-undo"></i> Send for Rework</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="x_panel">
                <div class="x_title"><h2>Status History</h2><div class="clearfix"></div></div>
                <div class="x_content">
                    <table class="table table-striped">
                        <thead><tr><th>When</th><th>Change</th><th>By</th><th>Note</th></tr></thead>
                        <tbody>
                            @foreach($job->logs as $log)
                                <tr>
                                    <td>{{ $log->created_at->format('d M H:i') }}</td>
                                    <td>{{ $log->from_status ?? '—' }} → <strong>{{ $log->to_status }}</strong></td>
                                    <td>{{ $log->actor->name ?? '—' }}</td>
                                    <td>{{ $log->note }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection