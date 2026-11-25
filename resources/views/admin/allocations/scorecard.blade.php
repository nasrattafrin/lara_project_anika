@extends('layouts.master')

@section('title', 'Scorecard — ' . $order->order_number)

@push('styles')
<style>
    
    .scorecard-wrapper {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 20px;
        border-radius: 15px;
        margin-bottom: 20px;
    }

    .scorecard-title {
        color: #fff;
        text-align: center;
        margin-bottom: 15px;
        position: relative;
    }
    .scorecard-title h2 {
        color: #fff;
        font-weight: 700;
        margin: 0;
        font-size: 22px;
    }
    .scorecard-title p {
        color: rgba(255,255,255,0.85);
        margin: 3px 0 0;
        font-size: 12px;
    }

    .order-info-bar {
        background: rgba(255,255,255,0.95);
        border-radius: 10px;
        padding: 10px 15px;
        margin-bottom: 15px;
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
    }
    .order-info-item {
        display: flex;
        flex-direction: column;
    }
    .order-info-label {
        font-size: 10px;
        text-transform: uppercase;
        color: #95a5a6;
        font-weight: 600;
        letter-spacing: 0.3px;
    }
    .order-info-value {
        font-size: 13px;
        font-weight: 700;
        color: #2c3e50;
    }

   
    .unit-card {
        background: #fff;
        border-radius: 12px;
        padding: 15px 12px;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        overflow: hidden;
        height: 100%;
        border: 2px solid transparent;
    }

    .unit-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.15);
    }

    .unit-card.recommended {
        border: 2px solid #26B99A;
        box-shadow: 0 0 0 0 rgba(38, 185, 154, 0.7);
        animation: pulse-glow 2s infinite;
        background: linear-gradient(135deg, #ffffff 0%, #f0fdf9 100%);
    }

    @keyframes pulse-glow {
        0%   { box-shadow: 0 0 0 0 rgba(38, 185, 154, 0.7); }
        50%  { box-shadow: 0 0 0 10px rgba(38, 185, 154, 0); }
        100% { box-shadow: 0 0 0 0 rgba(38, 185, 154, 0); }
    }

    .ribbon {
        position: absolute;
        top: 10px;
        right: -35px;
        background: linear-gradient(135deg, #26B99A 0%, #1ABB9C 100%);
        color: #fff;
        padding: 4px 35px;
        transform: rotate(45deg);
        font-size: 9px;
        font-weight: 700;
        letter-spacing: 1px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.2);
    }

    .unit-name {
        font-size: 15px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .unit-rank {
        display: inline-block;
        width: 22px;
        height: 22px;
        background: #3498DB;
        color: #fff;
        border-radius: 50%;
        font-size: 11px;
        line-height: 22px;
        text-align: center;
        font-weight: 700;
    }

    .recommended .unit-rank {
        background: #26B99A;
    }

    /* Gauge — 130px */
    .gauge-container {
        position: relative;
        width: 130px;
        height: 130px;
        margin: 10px auto;
    }

    .gauge-center {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        z-index: 1;
    }

    .gauge-score {
        font-size: 28px;
        font-weight: 800;
        line-height: 1;
        color: #2c3e50;
    }

    .gauge-label {
        font-size: 11px;
        color: #95a5a6;
        margin-top: 2px;
    }

    .recommended .gauge-score {
        color: #26B99A;
    }

    /* Factor List */
    .factor-list {
        margin: 12px 0 10px;
    }

    .factor-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 5px 0;
        border-bottom: 1px dashed #ecf0f1;
        font-size: 12px;
    }

    .factor-item:last-child {
        border-bottom: none;
    }

    .factor-label {
        color: #7f8c8d;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .factor-label i {
        width: 15px;
        text-align: center;
        color: #3498DB;
        font-size: 12px;
    }

    .recommended .factor-label i {
        color: #26B99A;
    }

    .factor-score {
        font-weight: 700;
        color: #2c3e50;
        font-size: 12px;
    }

    .factor-score .total {
        color: #bdc3c7;
        font-weight: 400;
        font-size: 10px;
    }

    .status-icon {
        font-size: 11px;
        margin-left: 3px;
    }
    .status-icon.good { color: #26B99A; }
    .status-icon.warn { color: #F39C12; }
    .status-icon.bad  { color: #E74C3C; }

    /* CTA */
    .btn-approve {
        background: linear-gradient(135deg, #26B99A 0%, #1ABB9C 100%);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 8px 15px;
        font-weight: 600;
        letter-spacing: 0.3px;
        font-size: 12px;
        width: 100%;
        transition: all 0.3s ease;
        box-shadow: 0 3px 10px rgba(38, 185, 154, 0.3);
    }
    .btn-approve:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(38, 185, 154, 0.5);
        color: #fff;
    }

    .btn-override {
        background: #fff;
        color: #7f8c8d;
        border: 2px solid #ecf0f1;
        border-radius: 8px;
        padding: 8px 15px;
        font-weight: 600;
        letter-spacing: 0.3px;
        font-size: 12px;
        width: 100%;
        transition: all 0.3s ease;
    }
    .btn-override:hover {
        background: #f8f9fa;
        border-color: #3498DB;
        color: #3498DB;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .animate-in {
        animation: fadeInUp 0.6s ease forwards;
        opacity: 0;
    }
    .animate-in:nth-child(1) { animation-delay: 0.1s; }
    .animate-in:nth-child(2) { animation-delay: 0.2s; }
    .animate-in:nth-child(3) { animation-delay: 0.3s; }

    .btn-back {
        background: rgba(255,255,255,0.2);
        color: #fff;
        border: 1px solid rgba(255,255,255,0.3);
        border-radius: 6px;
        padding: 6px 12px;
        font-size: 12px;
        transition: all 0.3s ease;
    }
    .btn-back:hover {
        background: rgba(255,255,255,0.3);
        color: #fff;
    }
</style>
@endpush

@section('content')

    <div class="scorecard-wrapper">

        {{-- Title --}}
        <div class="scorecard-title">
            <a href="{{ url()->previous() }}" class="btn btn-back pull-left">
                <i class="fa fa-arrow-left"></i> Back
            </a>
            <h2><i class="fa fa-trophy"></i> Unit Allocation Scorecard</h2>
            <p>Smart recommendation — weighted scoring system</p>
        </div>

        {{-- Order Info --}}
        <div class="order-info-bar">
            <div class="order-info-item">
                <span class="order-info-label">Order #</span>
                <span class="order-info-value">{{ $order->order_number }}</span>
            </div>
            <div class="order-info-item">
                <span class="order-info-label">Deadline</span>
                <span class="order-info-value">{{ $order->deadline?->format('d M Y') ?? '—' }}</span>
            </div>
            <div class="order-info-item">
                <span class="order-info-label">Status</span>
                <span class="order-info-value">
                    <span class="label label-info">{{ ucfirst($order->status) }}</span>
                </span>
            </div>
            <div class="order-info-item">
                <span class="order-info-label">Active Units</span>
                <span class="order-info-value">{{ count($allocations) }}</span>
            </div>
        </div>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if(count($allocations) > 0)
            @php
                $topUnits = array_slice($allocations, 0, 3);
                $factorLabels = [
                    'on_time' => ['On-Time', 'fa-clock-o', 30],
                    'workload' => ['Workload', 'fa-briefcase', 25],
                    'cost' => ['Cost', 'fa-money', 20],
                    'history' => ['History', 'fa-history', 15],
                    'quality' => ['Quality', 'fa-star-half-o', 10],
                ];
            @endphp

            <div class="row">
                @foreach($topUnits as $index => $alloc)
                    @php $isRecommended = $index === 0; @endphp
                    <div class="col-md-4 col-sm-12" style="margin-bottom: 15px;">
                        <div class="unit-card {{ $isRecommended ? 'recommended' : '' }} animate-in">

                            @if($isRecommended)
                                <div class="ribbon">★ RECOMMENDED</div>
                            @endif

                            <div class="unit-name">
                                <span class="unit-rank">#{{ $index + 1 }}</span>
                                {{ $alloc->unit->name }}
                            </div>

                            {{-- Gauge 130×130 --}}
                            <div class="gauge-container">
                                <canvas id="gauge-{{ $alloc->id }}" width="130" height="130"></canvas>
                                <div class="gauge-center">
                                    <div class="gauge-score" data-score="{{ $alloc->score }}">0</div>
                                    <div class="gauge-label">out of 100</div>
                                </div>
                            </div>

                            {{-- Factors --}}
                            <div class="factor-list">
                                @foreach($factorLabels as $factor => $meta)
                                    @php
                                        $scoreItem = $alloc->scores->firstWhere('factor', $factor);
                                        $rawScore = $scoreItem ? (float) $scoreItem->raw_score : 0;
                                        if ($rawScore >= 80) {
                                            $statusClass = 'good'; $statusIcon = 'fa-check-circle';
                                        } elseif ($rawScore >= 50) {
                                            $statusClass = 'warn'; $statusIcon = 'fa-exclamation-circle';
                                        } else {
                                            $statusClass = 'bad';  $statusIcon = 'fa-times-circle';
                                        }
                                    @endphp
                                    <div class="factor-item">
                                        <span class="factor-label">
                                            <i class="fa {{ $meta[1] }}"></i>
                                            {{ $meta[0] }}
                                            <small style="color:#bdc3c7;">({{ $meta[2] }}%)</small>
                                        </span>
                                        <span class="factor-score">
                                            {{ $rawScore }}
                                            <span class="total">/100</span>
                                            <i class="fa {{ $statusIcon }} status-icon {{ $statusClass }}"></i>
                                        </span>
                                    </div>
                                @endforeach
                            </div>

                            @if($isRecommended)
                                <button type="button" class="btn btn-approve" data-toggle="modal" data-target="#approveModal-{{ $alloc->id }}">
                                    <i class="fa fa-check-circle"></i> APPROVE
                                </button>
                            @else
                                <button type="button" class="btn btn-override" data-toggle="modal" data-target="#approveModal-{{ $alloc->id }}">
                                    <i class="fa fa-exchange"></i> OVERRIDE
                                </button>
                            @endif

                            {{-- Modal --}}
                            <div class="modal fade" id="approveModal-{{ $alloc->id }}" tabindex="-1" role="dialog">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <form action="{{ route('allocations.approve', $order) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="allocation_id" value="{{ $alloc->id }}">
                                            <div class="modal-header" style="background: {{ $isRecommended ? '#26B99A' : '#3498DB' }}; color: #fff; border-radius: 5px 5px 0 0;">
                                                <h4 class="modal-title" style="color: #fff;">
                                                    <i class="fa fa-check-circle"></i> Approve {{ $alloc->unit->name }}?
                                                </h4>
                                            </div>
                                            <div class="modal-body">
                                                <p>Score: <strong style="font-size: 20px; color: #26B99A;">{{ $alloc->score }}</strong>/100</p>
                                                @if(!$isRecommended)
                                                    <div class="alert alert-warning">
                                                        <i class="fa fa-exclamation-triangle"></i>
                                                        Ei unit <strong>recommended na</strong>। Override korle reason dite hobe।
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Override Reason <span class="text-danger">*</span></label>
                                                        <textarea name="override_reason" class="form-control" rows="3" required placeholder="e.g. Machine maintenance"></textarea>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Confirm</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Other Units --}}
            @if(count($allocations) > 3)
                <div class="row" style="margin-top: 15px;">
                    <div class="col-md-12">
                        <div style="background: #fff; border-radius: 12px; padding: 15px;">
                            <h4 style="margin-top: 0; font-size: 16px;"><i class="fa fa-list"></i> Other Units</h4>
                            <table class="table table-hover" style="margin-bottom: 0;">
                                <thead>
                                    <tr><th>Rank</th><th>Unit</th><th>Score</th><th class="text-right">Action</th></tr>
                                </thead>
                                <tbody>
                                    @foreach(array_slice($allocations, 3) as $i => $alloc)
                                        <tr>
                                            <td>#{{ $i + 4 }}</td>
                                            <td><strong>{{ $alloc->unit->name }}</strong></td>
                                            <td><span class="label label-default">{{ $alloc->score }}</span></td>
                                            <td class="text-right">
                                                <button type="button" class="btn btn-override btn-sm" data-toggle="modal" data-target="#approveModal-{{ $alloc->id }}" style="width: auto; padding: 5px 15px;">
                                                    <i class="fa fa-exchange"></i> Override
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

        @else
            <div class="alert alert-warning text-center" style="background: #fff;">
                <h4><i class="fa fa-exclamation-triangle"></i> No active units available</h4>
                <p>Unit add koren, tarpor scorecard view koren।</p>
                <a href="{{ route('units.create') }}" class="btn btn-primary">Add Unit</a>
            </div>
        @endif

    </div>

@endsection

@push('scripts')
<script src="{{ asset('assets/vendors/Chart.js/dist/Chart.min.js') }}"></script>
<script>
    @if(count($allocations) > 0)
    @php
        $topUnits = array_slice($allocations, 0, 3);
        $colors = ['#26B99A', '#3498DB', '#F39C12'];
    @endphp

    // Debug
    console.log('=== Scorecard Debug ===');
    console.log('Canvas count:', document.querySelectorAll('canvas[id^="gauge-"]').length);
    console.log('Chart loaded:', typeof Chart !== 'undefined');
    console.log('Chart version:', typeof Chart !== 'undefined' ? Chart.version : 'N/A');

    
    @foreach($topUnits as $index => $alloc)
    (function() {
        var canvas = document.getElementById('gauge-{{ $alloc->id }}');
        if (!canvas) {
            console.warn('Canvas NOT found: gauge-{{ $alloc->id }}');
            return;
        }
        if (typeof Chart === 'undefined') {
            console.error('Chart.js NOT loaded');
            return;
        }

        var ctx = canvas.getContext('2d');
        var score = {{ $alloc->score }};
        var color = '{{ $colors[$index] }}';

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Score', 'Remaining'],
                datasets: [{
                    data: [score, 100 - score],
                    backgroundColor: [color, '#ecf0f1'],
                    borderWidth: 0,
                    borderColor: 'transparent',
                }]
            },
            options: {
                responsive: false,
                maintainAspectRatio: false,
                cutoutPercentage: 82,
                rotation: -Math.PI * 0.5,
                circumference: Math.PI * 2,
                legend: { display: false },
                tooltips: { enabled: false },
                animation: {
                    animateRotate: true,
                    animateScale: false,
                    duration: 1500,
                    easing: 'easeOutQuart',
                },
            }
        });
    })();
    @endforeach

    
    document.querySelectorAll('.gauge-score').forEach(function(el) {
        var target = parseFloat(el.getAttribute('data-score'));
        var current = 0;
        var increment = target / 60;
        var timer = setInterval(function() {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            el.textContent = Math.floor(current);
        }, 25);
    });
    @endif
</script>
@endpush