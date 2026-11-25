@extends('layouts.master')

@section('content')
    <div class="page-title">
        <div class="title_left"><h3>Allocation Details</h3></div>
        <div class="title_right">
            <a href="{{ url()->previous() }}" class="btn btn-default pull-right"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
    </div>
    <div class="clearfix"></div>

    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title"><h2>{{ $allocation->unit->name }} — Score: {{ $allocation->score }}/100</h2><div class="clearfix"></div></div>
                <div class="x_content">
                    <table class="table table-bordered">
                        <tr><th>Order</th><td>{{ $allocation->order->order_number }}</td></tr>
                        <tr><th>Unit</th><td>{{ $allocation->unit->name }}</td></tr>
                        <tr><th>Status</th><td><span class="label label-info">{{ ucfirst($allocation->status) }}</span></td></tr>
                        <tr><th>Total Score</th><td><strong>{{ $allocation->score }}/100</strong></td></tr>
                        @if($allocation->approvedBy)
                            <tr><th>Approved By</th><td>{{ $allocation->approvedBy->name }}</td></tr>
                            <tr><th>Approved At</th><td>{{ $allocation->approved_at->format('d M Y H:i') }}</td></tr>
                            @if($allocation->override_reason)
                                <tr><th>Override Reason</th><td>{{ $allocation->override_reason }}</td></tr>
                            @endif
                        @endif
                    </table>

                    <h4>Score Breakdown</h4>
                    <table class="table table-striped">
                        <thead>
                            <tr><th>Factor</th><th>Weight</th><th>Raw Score</th><th>Weighted Score</th></tr>
                        </thead>
                        <tbody>
                            @foreach($allocation->scores as $score)
                                <tr>
                                    <td>{{ ucwords(str_replace('_', ' ', $score->factor)) }}</td>
                                    <td>{{ $score->weight }}%</td>
                                    <td>{{ $score->raw_score }}/100</td>
                                    <td><strong>{{ $score->weighted_score }}</strong></td>
                                </tr>
                            @endforeach
                            <tr style="background: #f5f5f5;">
                                <td colspan="3"><strong>Total</strong></td>
                                <td><strong>{{ $allocation->score }}/100</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection