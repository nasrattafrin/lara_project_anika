@extends('layouts.master')

@section('content')
    <div class="page-title"><div class="title_left"><h3>Edit Order {{ $order->order_number }}</h3></div></div>
    <div class="clearfix"></div>

    <div class="row">
        <div class="col-md-8">
            <div class="x_panel">
                <div class="x_content">
                    @if ($errors->any())
                        <div class="alert alert-danger"><ul>@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
                    @endif

                    <form action="{{ route('orders.update', $order) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                @foreach(['draft', 'quoted', 'confirmed', 'allocated', 'in_production', 'qc', 'ready', 'delivered', 'cancelled'] as $status)
                                    <option value="{{ $status }}" {{ $order->status === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Deadline</label>
                            <input type="date" name="deadline" class="form-control" value="{{ $order->deadline?->format('Y-m-d') }}">
                        </div>
                        <div class="form-group">
                            <label>Notes</label>
                            <textarea name="notes" class="form-control" rows="2">{{ $order->notes }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Update</button>
                        <a href="{{ route('orders.index') }}" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection