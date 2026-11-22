@php
    $isEdit = isset($plan) && $plan !== null;
    $limits = $isEdit ? $plan->limits : [];
    $features = $isEdit ? $plan->features : [];
@endphp

<div class="row">
    <div class="col-md-6">
        <h4>Basic Info</h4>
        <div class="form-group">
            <label>Code <span class="text-danger">*</span></label>
            <input type="text" name="code" class="form-control"
                   value="{{ old('code', $plan->code ?? '') }}" required>
        </div>
        <div class="form-group">
            <label>Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control"
                   value="{{ old('name', $plan->name ?? '') }}" required>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description', $plan->description ?? '') }}</textarea>
        </div>
    </div>

    <div class="col-md-6">
        <h4>Pricing</h4>
        <div class="form-group">
            <label>Price <span class="text-danger">*</span></label>
            <input type="number" name="price" step="0.01" class="form-control"
                   value="{{ old('price', $plan->price ?? 0) }}" required>
        </div>
        <div class="form-group">
            <label>Currency <span class="text-danger">*</span></label>
            <select name="currency" class="form-control" required>
                <option value="BDT" {{ old('currency', $plan->currency ?? 'BDT') === 'BDT' ? 'selected' : '' }}>BDT</option>
                <option value="USD" {{ old('currency', $plan->currency ?? '') === 'USD' ? 'selected' : '' }}>USD</option>
            </select>
        </div>
        <div class="form-group">
            <label>Billing Cycle <span class="text-danger">*</span></label>
            <select name="billing_cycle" class="form-control" required>
                <option value="monthly" {{ old('billing_cycle', $plan->billing_cycle ?? 'monthly') === 'monthly' ? 'selected' : '' }}>Monthly</option>
                <option value="yearly" {{ old('billing_cycle', $plan->billing_cycle ?? '') === 'yearly' ? 'selected' : '' }}>Yearly</option>
            </select>
        </div>
    </div>
</div>

<hr>

<h4>Limits</h4>
<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label>Units <span class="text-danger">*</span></label>
            <input type="number" name="limits[units]" class="form-control"
                   value="{{ old('limits.units', $limits['units'] ?? 3) }}" min="1" required>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label>Users <span class="text-danger">*</span></label>
            <input type="number" name="limits[users]" class="form-control"
                   value="{{ old('limits.users', $limits['users'] ?? 10) }}" min="1" required>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label>Storage (GB)</label>
            <input type="number" name="limits[storage_gb]" class="form-control"
                   value="{{ old('limits.storage_gb', $limits['storage_gb'] ?? 5) }}" min="0">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label>Orders/Month</label>
            <input type="number" name="limits[orders_per_month]" class="form-control"
                   value="{{ old('limits.orders_per_month', $limits['orders_per_month'] ?? 100) }}" min="0">
        </div>
    </div>
</div>

<hr>

<h4>Features</h4>
<div class="row">
    @foreach(['reports' => 'Reports', 'api_access' => 'API Access', 'custom_domain' => 'Custom Domain', 'priority_support' => 'Priority Support'] as $key => $label)
        <div class="col-md-3">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="features[{{ $key }}]" value="1"
                        {{ old("features.$key", $features[$key] ?? false) ? 'checked' : '' }}>
                    {{ $label }}
                </label>
            </div>
        </div>
    @endforeach
</div>

<hr>

<h4>Other</h4>
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label>Trial Days <span class="text-danger">*</span></label>
            <input type="number" name="trial_days" class="form-control"
                   value="{{ old('trial_days', $plan->trial_days ?? 14) }}" min="0" required>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Sort Order</label>
            <input type="number" name="sort_order" class="form-control"
                   value="{{ old('sort_order', $plan->sort_order ?? 0) }}" min="0">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group"><br>
            <label>
                <input type="checkbox" name="is_active" value="1"
                    {{ old('is_active', $plan->is_active ?? true) ? 'checked' : '' }}>
                Active
            </label>
        </div>
    </div>
</div>