@extends('layouts.master')

@section('content')
    <div class="page-title"><div class="title_left"><h3>Edit Machine</h3></div></div>
    <div class="clearfix"></div>

    <div class="row">
        <div class="col-md-8">
            <div class="x_panel">
                <div class="x_content">
                    <form action="{{ route('machines.update', $machine) }}" method="POST">
                        @csrf @method('PUT')
                        @include('admin.machines._form')
                        <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Update</button>
                        <a href="{{ route('machines.index') }}" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection