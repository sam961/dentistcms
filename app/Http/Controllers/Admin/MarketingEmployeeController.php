<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMarketingEmployeeRequest;
use App\Http\Requests\UpdateMarketingEmployeeRequest;
use App\Models\MarketingEmployee;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MarketingEmployeeController extends Controller
{
    /**
     * Display a listing of marketing employees with stats
     */
    public function index(): View
    {
        $employees = MarketingEmployee::withCount(['tenants as total_clients'])
            ->with('tenants')
            ->latest()
            ->get();

        // Calculate totals
        $totalEmployees = $employees->count();
        $activeEmployees = $employees->where('status', 'active')->count();
        $totalClients = $employees->sum('total_clients');
        $totalRevenue = $employees->sum(function ($employee) {
            return $employee->total_revenue;
        });

        return view('admin.marketing-employees.index', compact(
            'employees',
            'totalEmployees',
            'activeEmployees',
            'totalClients',
            'totalRevenue'
        ));
    }

    /**
     * Show the form for creating a new marketing employee
     */
    public function create(): View
    {
        return view('admin.marketing-employees.create');
    }

    /**
     * Store a newly created marketing employee
     */
    public function store(StoreMarketingEmployeeRequest $request): RedirectResponse
    {
        MarketingEmployee::create($request->validated());

        return redirect()
            ->route('admin.marketing-employees.index')
            ->with('success', 'Marketing employee created successfully!');
    }

    /**
     * Display the specified marketing employee with analytics
     */
    public function show(MarketingEmployee $marketingEmployee): View
    {
        $marketingEmployee->load(['tenants' => function ($query) {
            $query->with(['users'])->latest();
        }]);

        // Get analytics
        $totalClients = $marketingEmployee->tenants()->count();
        $activeClients = $marketingEmployee->tenants()->where('status', 'active')->count();
        $inactiveClients = $marketingEmployee->tenants()->where('status', '!=', 'active')->count();

        // Revenue and commission calculations
        $totalRevenue = $marketingEmployee->total_revenue;
        $totalCommissions = $marketingEmployee->total_commissions;

        // Monthly acquisitions (last 6 months)
        $monthlyData = $marketingEmployee->tenants()
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('count', 'month');

        return view('admin.marketing-employees.show', compact(
            'marketingEmployee',
            'totalClients',
            'activeClients',
            'inactiveClients',
            'totalRevenue',
            'totalCommissions',
            'monthlyData'
        ));
    }

    /**
     * Show the form for editing the specified marketing employee
     */
    public function edit(MarketingEmployee $marketingEmployee): View
    {
        return view('admin.marketing-employees.edit', compact('marketingEmployee'));
    }

    /**
     * Update the specified marketing employee
     */
    public function update(UpdateMarketingEmployeeRequest $request, MarketingEmployee $marketingEmployee): RedirectResponse
    {
        $marketingEmployee->update($request->validated());

        return redirect()
            ->route('admin.marketing-employees.show', $marketingEmployee)
            ->with('success', 'Marketing employee updated successfully!');
    }

    /**
     * Remove the specified marketing employee
     */
    public function destroy(MarketingEmployee $marketingEmployee): RedirectResponse
    {
        // Check if employee has any clients
        if ($marketingEmployee->tenants()->count() > 0) {
            return redirect()
                ->route('admin.marketing-employees.index')
                ->with('error', 'Cannot delete employee with assigned clients. Please reassign clients first.');
        }

        $marketingEmployee->delete();

        return redirect()
            ->route('admin.marketing-employees.index')
            ->with('success', 'Marketing employee deleted successfully!');
    }
}
