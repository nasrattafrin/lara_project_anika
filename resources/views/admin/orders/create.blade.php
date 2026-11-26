@extends('layouts.master')

@section('content')
    <div class="page-title"><div class="title_left"><h3>New Order</h3></div></div>
    <div class="clearfix"></div>

    <div class="row">
        <div class="col-md-8">
            <div class="x_panel">
                <div class="x_content">
                    @if ($errors->any())
                        <div class="alert alert-danger"><ul>@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
                    @endif

                    <form action="{{ route('orders.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Product <span class="text-danger">*</span></label>
                            <input type="text" name="product_name" class="form-control" value="{{ old('product_name') }}" required>
                        </div>
                            <div class="form-group">
                            <label>Printing type 
                                <span class="text-danger">*</span>
                            </label>
                            <select name="process_type" class="form-control" required>
                                @foreach(\App\Models\Machine::TYPES as $key => $label)
                                    <option value="{{ $key }}" @selected(old('process_type') == $key)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Quantity <span class="text-danger">*</span></label>
                            <input type="number" name="quantity" class="form-control" value="{{ old('quantity', 1000) }}" min="1" required>
                        </div>
                        <div class="form-group">
                            <label>Deadline <span class="text-danger">*</span></label>
                            <input type="date" name="deadline" class="form-control" value="{{ old('deadline', now()->addDays(7)->format('Y-m-d')) }}" required>
                        </div>
                        <div class="form-group">
                            <label>Notes</label>
                            <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Create Order</button>
                        <a href="{{ route('orders.index') }}" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection