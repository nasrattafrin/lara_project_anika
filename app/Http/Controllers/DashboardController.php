<?php

namespace App\Http\Controllers;

use App\Models\Plan;
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
            default                              => view('dashboard.index'),
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
        return view('dashboard.index', [
            'tenant' => $tenant,
            'totalUnits' => $tenant ? Unit::where('tenant_id', $tenant->id)->count() : 0,
            'activeOrders' => 0,
            'runningJobs' => 0,
            'onTimeRate' => 0,
        ]);
    }

    private function unitManagerDashboard($user, $tenant)
    {
        return view('dashboard.index', [
            'tenant' => $tenant,
            'myUnit' => $user->unit,
            'assignedJobs' => 0,
            'transferRequests' => 0,
            'staffCount' => $user->unit ? User::where('unit_id', $user->unit->id)->count() : 0,
        ]);
    }

    private function accountantDashboard($tenant)
    {
        return view('dashboard.index', [
            'totalInvoices' => 0,
            'collected' => 0,
            'pendingDues' => 0,
            'grossMargin' => 0,
        ]);
    }

    private function operatorDashboard($user)
    {
        return view('dashboard.index', [
            'assignedJobs' => 0,
            'completedToday' => 0,
            'inProgress' => 0,
        ]);
    }

    private function customerDashboard($user)
    {
        return view('dashboard.index', [
            'myOrders' => 0,
            'inProgress' => 0,
            'delivered' => 0,
            'pendingInvoices' => 0,
        ]);
    }
}