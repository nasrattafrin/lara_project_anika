@extends('layouts.master')

@section('content')

    <div class="page-title">
        <div class="title_left"><h3>My Unit</h3></div>
    </div>
    <div class="clearfix"></div>

    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>{{ $unit->name }}</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table class="table table-bordered">
                        <tr><th style="width:200px;">Name</th><td>{{ $unit->name }}</td></tr>
                        <tr><th>Description</th><td>{{ $unit->description ?: '—' }}</td></tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <span class="label label-{{ $unit->status === 'active' ? 'success' : ($unit->status === 'maintenance' ? 'warning' : 'default') }}">
                                    {{ ucfirst($unit->status) }}
                                </span>
                            </td>
                        </tr>
                        <tr><th>Created</th><td>{{ $unit->created_at->format('d M Y') }}</td></tr>
                    </table>

                    <hr>
                    <h4>Quick Links</h4>
                    <a href="#" class="btn btn-info"><i class="fa fa-tasks"></i> Assigned Jobs</a>
                    <a href="#" class="btn btn-warning"><i class="fa fa-exchange"></i> Transfer Requests</a>
                    <a href="#" class="btn btn-success"><i class="fa fa-users"></i> My Staff</a>
                </div>
            </div>
        </div>
    </div>

@endsection