<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Plan;
use App\Models\ProductionJob;
use App\Models\Tenant;
use App\Models\Unit;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $tenant = $user->tenant;

        return match (true) {
            $user->hasRole('platform_admin')    => $this->platformDashboard(),
            $user->hasRole('press_responsible') => $this->pressDashboard($user, $tenant),
            $user->hasRole('unit_manager')      => $this->unitManagerDashboard($user, $tenant),
            $user->hasRole('accountant')        => $this->accountantDashboard($tenant),
            $user->hasRole('operator')          => $this->operatorDashboard($user),
            $user->hasRole('customer')          => $this->customerDashboard($user),
            default                             => view('dashboard.index'),
        };
    }

    private function platformDashboard()
    {
        return view('dashboard.index', [
            'totalTenants' => Tenant::count(),
            'totalPlans'   => Plan::count(),
            'totalUsers'   => User::count(),
            'revenue'      => Plan::sum('price'),
        ]);
    }

    private function pressDashboard($user, $tenant)
    {
        if (!$tenant) {
            return view('dashboard.index', ['error' => 'No tenant assigned']);
        }

        
        $pendingOrders = Order::where('tenant_id', $tenant->id)
            ->whereIn('status', ['confirmed', 'quoted'])
            ->whereDoesntHave('allocations', function ($q) {
                $q->where('status', 'approved');
            })
            ->latest()
            ->take(5)
            ->get();

       
        $tenantJobs = ProductionJob::whereIn('unit_id', Unit::select('id'));

        $runningJobs = (clone $tenantJobs)
            ->whereIn('status', ['queued', 'printing', 'qc'])
            ->count();

        // On-time rate = deadline er moddhe delivered job / total delivered job
        $deliveredTotal = (clone $tenantJobs)->where('status', 'delivered')->count();
        $deliveredOnTime = (clone $tenantJobs)
            ->where('status', 'delivered')
            ->whereNotNull('completed_at')
            ->whereNotNull('deadline')
            ->whereRaw('DATE(completed_at) <= deadline')
            ->count();

        $onTimeRate = $deliveredTotal > 0
            ? round(($deliveredOnTime / $deliveredTotal) * 100)
            : 0;

        return view('dashboard.index', [
            'tenant'        => $tenant,
            'totalUnits'    => Unit::where('tenant_id', $tenant->id)->count(),
            'activeOrders'  => Order::where('tenant_id', $tenant->id)
                ->whereIn('status', ['confirmed', 'allocated', 'in_production', 'qc', 'ready'])
                ->count(),
            'runningJobs'   => $runningJobs,
            'onTimeRate'    => $onTimeRate,
            'pendingOrders' => $pendingOrders,
        ]);
    }

    private function unitManagerDashboard($user, $tenant)
    {
        $assignedJobs = $user->unit_id
            ? ProductionJob::where('unit_id', $user->unit_id)
                ->whereIn('status', ['queued', 'printing', 'qc'])
                ->count()
            : 0;

        return view('dashboard.index', [
            'tenant'           => $tenant,
            'myUnit'           => $user->unit,
            'assignedJobs'     => $assignedJobs,
            'transferRequests' => 0, // transfer feature not implemented yet
            'staffCount'       => $user->unit_id
                ? User::where('unit_id', $user->unit_id)->count()
                : 0,
        ]);
    }

    private function accountantDashboard($tenant)
    {
        
        return view('dashboard.index', [
            'totalInvoices' => 0,
            'collected'     => 0,
            'pendingDues'   => 0,
            'grossMargin'   => 0,
        ]);
    }

    private function operatorDashboard($user)
    {
        $base = ProductionJob::where('unit_id', $user->unit_id);

        return view('dashboard.index', [
            'assignedJobs'   => $user->unit_id
                ? (clone $base)->whereIn('status', ['queued', 'printing', 'qc'])->count()
                : 0,
            'completedToday' => $user->unit_id
                ? (clone $base)->whereDate('completed_at', today())->count()
                : 0,
            'inProgress'     => $user->unit_id
                ? (clone $base)->where('status', 'printing')->count()
                : 0,
        ]);
    }

    private function customerDashboard($user)
    {
        $orders = Order::where('customer_id', $user->id);

        return view('dashboard.index', [
            'myOrders'        => (clone $orders)->count(),
            'inProgress'      => (clone $orders)
                ->whereIn('status', ['allocated', 'in_production', 'qc', 'ready'])->count(),
            'delivered'       => (clone $orders)->where('status', 'delivered')->count(),
            'pendingInvoices' => 0, 
        ]); 
    }
}