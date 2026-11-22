@extends('layouts.master')

@section('content')

    <div class="page-title">
        <div class="title_left">
            <h3>Create New Plan</h3>
        </div>
        <div class="title_right">
            <a href="{{ route('plans.index') }}" class="btn btn-default pull-right">
                <i class="fa fa-arrow-left"></i> Back
            </a>
        </div>
    </div>
    <div class="clearfix"></div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Plan Details</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('plans.store') }}" method="POST">
                        @csrf
                        @include('admin.plans._form', ['plan' => null])

                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-save"></i> Save Plan
                            </button>
                            <a href="{{ route('plans.index') }}" class="btn btn-default">Cancel</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

@endsection