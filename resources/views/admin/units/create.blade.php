@extends('layouts.master')

@section('content')
    <div class="page-title">
        <div class="title_left"><h3>Create Unit</h3></div>
        <div class="title_right">
            <a href="{{ route('units.index') }}" class="btn btn-default pull-right"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
    </div>
    <div class="clearfix"></div>

    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title"><h2>Unit Details</h2><div class="clearfix"></div></div>
                <div class="x_content">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                        </div>
                    @endif

                    <form action="{{ route('units.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label>Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-control" required>
                                @foreach(['active', 'inactive', 'maintenance'] as $status)
                                    <option value="{{ $status }}" {{ old('status', 'active') === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="ln_solid"></div>
                        <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save Unit</button>
                        <a href="{{ route('units.index') }}" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection