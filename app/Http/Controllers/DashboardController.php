<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Dentist;
use App\Models\Invoice;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $todayAppointments = Appointment::with(['patient', 'dentist', 'treatments'])
            ->whereDate('appointment_date', Carbon::today())
            ->whereNotIn('status', ['cancelled'])
            ->orderBy('appointment_time')
            ->get();

        $upcomingAppointments = Appointment::with(['patient', 'dentist'])
            ->where('appointment_date', '>', Carbon::today())
            ->whereNotIn('status', ['cancelled'])
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->limit(5)
            ->get();

        // Calculate current month patients
        $currentMonthPatients = Patient::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        // Calculate last month patients for percentage comparison
        $lastMonthPatients = Patient::whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->count();

        // Calculate percentage change
        $patientGrowthPercentage = $lastMonthPatients > 0
            ? round((($currentMonthPatients - $lastMonthPatients) / $lastMonthPatients) * 100)
            : 0;

        // Calculate current month revenue
        $currentMonthRevenue = Invoice::whereMonth('invoice_date', Carbon::now()->month)
            ->whereYear('invoice_date', Carbon::now()->year)
            ->where('status', 'paid')
            ->sum('total_amount');

        // Calculate last month revenue for percentage comparison
        $lastMonthRevenue = Invoice::whereMonth('invoice_date', Carbon::now()->subMonth()->month)
            ->whereYear('invoice_date', Carbon::now()->subMonth()->year)
            ->where('status', 'paid')
            ->sum('total_amount');

        // Calculate revenue percentage change
        $revenueGrowthPercentage = $lastMonthRevenue > 0
            ? round((($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100)
            : 0;

        $stats = [
            'total_patients' => Patient::count(),
            'total_dentists' => Dentist::where('status', 'active')->count(),
            'today_appointments' => $todayAppointments->count(),
            'completed_today' => $todayAppointments->where('status', 'completed')->count(),
            'pending_invoices' => Invoice::whereIn('status', ['sent', 'partially_paid', 'overdue'])->count(),
            'pending_amount' => Invoice::whereIn('status', ['sent', 'partially_paid', 'overdue'])->sum('total_amount'),
            'monthly_revenue' => $currentMonthRevenue,
            'confirmed_appointments' => Appointment::where('status', 'confirmed')->count(),
            'completed_appointments' => Appointment::where('status', 'completed')->count(),
            'cancelled_appointments' => Appointment::where('status', 'cancelled')->count(),
            'new_patients_this_month' => $currentMonthPatients,
            'patient_growth_percentage' => $patientGrowthPercentage,
            'revenue_growth_percentage' => $revenueGrowthPercentage,
        ];

        $recentPatients = Patient::latest()->limit(5)->get();

        // Top performing dentists (by appointment count this month)
        $topDentists = Dentist::withCount(['appointments' => function ($query) {
            $query->whereMonth('appointment_date', Carbon::now()->month)
                ->whereYear('appointment_date', Carbon::now()->year);
        }])
            ->orderBy('appointments_count', 'desc')
            ->limit(3)
            ->get();

        // Calculate last 6 months revenue for chart
        $revenueData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $revenue = Invoice::whereMonth('invoice_date', $date->month)
                ->whereYear('invoice_date', $date->year)
                ->where('status', 'paid')
                ->sum('total_amount');
            $revenueData[] = $revenue;
        }

        // Get top 5 treatments by appointment count
        $treatmentStats = \DB::table('appointment_treatments')
            ->join('treatments', 'appointment_treatments.treatment_id', '=', 'treatments.id')
            ->select('treatments.name', \DB::raw('count(*) as count'))
            ->groupBy('treatments.id', 'treatments.name')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get();

        $treatmentNames = $treatmentStats->pluck('name')->toArray();
        $treatmentCounts = $treatmentStats->pluck('count')->toArray();

        // If no treatments, use placeholder
        if (empty($treatmentNames)) {
            $treatmentNames = ['No data'];
            $treatmentCounts = [0];
        }

        // Get recent activities (mix of patients, appointments, and invoices)
        $recentActivities = collect();

        // Recent patients
        $recentPatientsActivity = Patient::latest()->limit(2)->get()->map(function ($patient) {
            return [
                'type' => 'patient',
                'icon' => 'user-plus',
                'color' => 'blue',
                'title' => 'New patient registered',
                'description' => $patient->full_name,
                'time' => $patient->created_at,
            ];
        });

        // Recent completed appointments
        $recentCompletedAppointments = Appointment::with('patient')
            ->where('status', 'completed')
            ->latest('updated_at')
            ->limit(2)
            ->get()
            ->map(function ($appointment) {
                return [
                    'type' => 'appointment',
                    'icon' => 'check',
                    'color' => 'green',
                    'title' => 'Appointment completed',
                    'description' => $appointment->patient->full_name,
                    'time' => $appointment->updated_at,
                ];
            });

        // Recent paid invoices
        $recentPaidInvoices = Invoice::where('status', 'paid')
            ->latest('updated_at')
            ->limit(2)
            ->get()
            ->map(function ($invoice) {
                return [
                    'type' => 'invoice',
                    'icon' => 'dollar-sign',
                    'color' => 'purple',
                    'title' => 'Payment received',
                    'description' => 'Invoice #'.$invoice->invoice_number,
                    'time' => $invoice->updated_at,
                ];
            });

        // Merge and sort by time
        $recentActivities = $recentActivities
            ->merge($recentPatientsActivity)
            ->merge($recentCompletedAppointments)
            ->merge($recentPaidInvoices)
            ->sortByDesc('time')
            ->take(4);

        // Get current tenant and subscription info
        $tenant = app(\App\Services\TenantContext::class)->getTenant();
        $currentSubscription = null;

        if ($tenant && $tenant->subscription_status === 'active' && $tenant->subscription_ends_at) {
            $daysUntil = now()->diffInDays($tenant->subscription_ends_at, false);

            $currentSubscription = (object) [
                'plan_name' => 'Paid Plan',
                'formatted_amount' => '$' . number_format($tenant->subscriptionHistory()->where('starts_at', $tenant->subscription_starts_at)->latest()->first()->amount ?? 0, 2),
                'billing_cycle' => 'custom',
                'renewal_date' => $tenant->subscription_ends_at,
                'days_until_renewal' => $daysUntil
            ];
        }

        return view('dashboard-modern', compact(
            'todayAppointments',
            'upcomingAppointments',
            'stats',
            'recentPatients',
            'topDentists',
            'revenueData',
            'treatmentNames',
            'treatmentCounts',
            'recentActivities',
            'currentSubscription'
        ));
    }
}
