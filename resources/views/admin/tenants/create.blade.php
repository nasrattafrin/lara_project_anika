@extends('layouts.master')

@section('content')
    <div class="page-title">
        <div class="title_left"><h3>Create New Tenant</h3></div>
        <div class="title_right">
            <a href="{{ route('tenants.index') }}" class="btn btn-default pull-right"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
    </div>
    <div class="clearfix"></div>

    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title"><h2>Tenant Details</h2><div class="clearfix"></div></div>
                <div class="x_content">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                        </div>
                    @endif

                    <form action="{{ route('tenants.store') }}" method="POST">
                        @csrf
                        @include('admin.tenants._form', ['tenant' => null])

                        <div class="ln_solid"></div>
                        <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save Tenant</button>
                        <a href="{{ route('tenants.index') }}" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection