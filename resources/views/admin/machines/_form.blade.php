@php $m = $machine ?? null; @endphp

@if ($errors->any())
    <div class="alert alert-danger"><ul>@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif

<div class="form-group">
    <label>Unit <span class="text-danger">*</span></label>
    <select name="unit_id" class="form-control" required>
        @foreach($units as $u)
            <option value="{{ $u->id }}" @selected(old('unit_id', $m?->unit_id) == $u->id)>{{ $u->name }}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label>Machine name <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $m?->name) }}" required>
</div>

<div class="form-group">
    <label>Printing type <span class="text-danger">*</span></label>
    <select name="process_type" class="form-control" required>
        @foreach(\App\Models\Machine::TYPES as $key => $label)
            <option value="{{ $key }}" @selected(old('process_type', $m?->process_type) == $key)>{{ $label }}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label>Capacity per day (pieces) <span class="text-danger">*</span></label>
    <input type="number" name="capacity_per_day" class="form-control" min="1"
           value="{{ old('capacity_per_day', $m?->capacity_per_day ?? 5000) }}" required>
</div>

<div class="form-group">
    <label>Cost per piece (৳) <span class="text-danger">*</span></label>
    <input type="number" step="0.0001" name="cost_per_unit" class="form-control" min="0"
           value="{{ old('cost_per_unit', $m?->cost_per_unit ?? 0.5) }}" required>
</div>

<div class="form-group">
    <label>Status <span class="text-danger">*</span></label>
    <select name="status" class="form-control" required>
        @foreach(['active', 'maintenance', 'inactive'] as $s)
            <option value="{{ $s }}" @selected(old('status', $m?->status ?? 'active') == $s)>{{ ucfirst($s) }}</option>
        @endforeach
    </select>
</div>