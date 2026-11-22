@php
    $isEdit = isset($tenant) && $tenant !== null;
@endphp

<div class="row">
    <div class="col-md-6">
        <h4>Basic Info</h4>
        <div class="form-group">
            <label>Slug <span class="text-danger">*</span></label>
            <input type="text" name="slug" class="form-control"
                   value="{{ old('slug', $tenant->slug ?? '') }}" placeholder="press1" required>
            <small class="text-muted">Subdomain er jonno — shudhu letters, numbers, hyphen</small>
        </div>
        <div class="form-group">
            <label>Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control"
                   value="{{ old('name', $tenant->name ?? '') }}" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control"
                   value="{{ old('email', $tenant->email ?? '') }}">
        </div>
        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control"
                   value="{{ old('phone', $tenant->phone ?? '') }}">
        </div>
    </div>

    <div class="col-md-6">
        <h4>Subscription</h4>
        <div class="form-group">
            <label>Plan</label>
            <select name="plan_id" class="form-control">
                <option value="">— No Plan —</option>
                @foreach($plans as $plan)
                    <option value="{{ $plan->id }}"
                        {{ old('plan_id', $tenant->plan_id ?? '') == $plan->id ? 'selected' : '' }}>
                        {{ $plan->name }} — {{ $plan->currency }} {{ number_format($plan->price) }}/{{ $plan->billing_cycle }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Status <span class="text-danger">*</span></label>
            <select name="status" class="form-control" required>
                @foreach(['trial', 'active', 'suspended', 'cancelled', 'expired'] as $status)
                    <option value="{{ $status }}"
                        {{ old('status', $tenant->status ?? 'trial') === $status ? 'selected' : '' }}>
                        {{ ucfirst($status) }}
                    </option>
                @endforeach
            </select>
        </div>
        @if(!$isEdit)
            <div class="form-group">
                <label>Trial Days</label>
                <input type="number" name="trial_days" class="form-control"
                       value="{{ old('trial_days', 14) }}" min="0" max="365">
                <small class="text-muted">Status = "trial" hole trial_ends_at set hobe</small>
            </div>
        @else
            @if($tenant->trial_ends_at)
                <div class="form-group">
                    <label>Trial Ends</label>
                    <input type="text" class="form-control" value="{{ $tenant->trial_ends_at->format('d M Y') }}" disabled>
                </div>
            @endif
            <div class="form-group">
                <label>Suspension Reason</label>
                <textarea name="suspension_reason" class="form-control" rows="2">{{ old('suspension_reason', $tenant->suspension_reason ?? '') }}</textarea>
                <small class="text-muted">Status = "suspended" hole ei reason save hobe</small>
            </div>
        @endif
    </div>
</div>