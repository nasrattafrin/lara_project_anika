@extends('layouts.master')

@section('content')
    <div class="page-title">
        <div class="title_left"><h3>Subscriptions</h3></div>
        <div class="title_right">
            <a href="{{ route('subscriptions.create') }}" class="btn btn-primary pull-right">
                <i class="fa fa-plus"></i> New Subscription
            </a>
        </div>
    </div>
    <div class="clearfix"></div>

    @if(session('success')) <div class="alert alert-success">✅ {{ session('success') }}</div> @endif

    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_content">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Tenant</th><th>Plan</th><th>Amount</th><th>Status</th><th>Ends At</th><th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($subscriptions as $sub)
                                <tr>
                                    <td>{{ $sub->tenant->name ?? '—' }}</td>
                                    <td>{{ $sub->plan->name ?? '—' }}</td>
                                    <td>{{ $sub->currency }} {{ number_format($sub->amount, 2) }}</td>
                                    <td>
                                        <span class="label label-{{ $sub->status === 'active' ? 'success' : ($sub->status === 'trial' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($sub->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $sub->ends_at?->format('d M Y') ?? '—' }}</td>
                                    <td>
                                        <a href="{{ route('subscriptions.show', $sub) }}" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
                                        @if($sub->status === 'active')
                                            <form action="{{ route('subscriptions.cancel', $sub) }}" method="POST" style="display:inline;" onsubmit="return confirm('Cancel?')">
                                                @csrf
                                                <button class="btn btn-danger btn-xs"><i class="fa fa-times"></i></button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center">No subscriptions.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $subscriptions->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection