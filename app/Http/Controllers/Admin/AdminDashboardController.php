<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $tenants = Tenant::withCount(['users', 'patients', 'dentists', 'appointments'])
            ->orderBy('created_at', 'desc')
            ->get();

        $stats = [
            'total_clients' => Tenant::count(),
            'active_clients' => Tenant::where('status', 'active')->count(),
            'inactive_clients' => Tenant::where('status', 'inactive')->count(),
            'suspended_clients' => Tenant::where('status', 'suspended')->count(),
        ];

        return view('admin.dashboard', compact('tenants', 'stats'));
    }
}
