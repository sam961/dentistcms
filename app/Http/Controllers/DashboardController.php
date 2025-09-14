<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Dentist;
use App\Models\Appointment;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Carbon\Carbon;

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

        $stats = [
            'total_patients' => Patient::count(),
            'total_dentists' => Dentist::where('status', 'active')->count(),
            'today_appointments' => $todayAppointments->count(),
            'completed_today' => $todayAppointments->where('status', 'completed')->count(),
            'pending_invoices' => Invoice::whereIn('status', ['sent', 'partially_paid', 'overdue'])->count(),
            'pending_amount' => Invoice::whereIn('status', ['sent', 'partially_paid', 'overdue'])->sum('total_amount'),
            'monthly_revenue' => Invoice::whereMonth('invoice_date', Carbon::now()->month)
                ->whereYear('invoice_date', Carbon::now()->year)
                ->where('status', 'paid')
                ->sum('total_amount'),
            'scheduled_appointments' => Appointment::where('status', 'scheduled')->count(),
            'confirmed_appointments' => Appointment::where('status', 'confirmed')->count(),
            'completed_appointments' => Appointment::where('status', 'completed')->count(),
            'cancelled_appointments' => Appointment::where('status', 'cancelled')->count(),
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

        return view('dashboard-modern', compact(
            'todayAppointments',
            'upcomingAppointments',
            'stats',
            'recentPatients',
            'topDentists'
        ));
    }
}
