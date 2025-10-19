<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Dentist;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\Treatment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));

        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        // Financial Metrics
        $financialMetrics = $this->getFinancialMetrics($start, $end);

        // Appointment Metrics
        $appointmentMetrics = $this->getAppointmentMetrics($start, $end);

        // Patient Metrics
        $patientMetrics = $this->getPatientMetrics($start, $end);

        // Dentist Performance
        $dentistPerformance = $this->getDentistPerformance($start, $end);

        // Treatment Analytics
        $treatmentAnalytics = $this->getTreatmentAnalytics($start, $end);

        // Monthly Revenue Trend (last 6 months)
        $monthlyRevenue = $this->getMonthlyRevenueTrend();

        // Appointment Status Distribution
        $appointmentStatusDistribution = $this->getAppointmentStatusDistribution($start, $end);

        return view('reports.index', compact(
            'financialMetrics',
            'appointmentMetrics',
            'patientMetrics',
            'dentistPerformance',
            'treatmentAnalytics',
            'monthlyRevenue',
            'appointmentStatusDistribution',
            'startDate',
            'endDate'
        ));
    }

    private function getFinancialMetrics(Carbon $start, Carbon $end): array
    {
        $totalRevenue = Invoice::whereBetween('invoice_date', [$start, $end])
            ->sum('total_amount');

        $paidInvoices = Invoice::whereBetween('invoice_date', [$start, $end])
            ->where('status', 'paid')
            ->sum('total_amount');

        $pendingInvoices = Invoice::whereBetween('invoice_date', [$start, $end])
            ->whereIn('status', ['pending'])
            ->sum('total_amount');

        $overdueInvoices = Invoice::whereBetween('invoice_date', [$start, $end])
            ->where('status', 'overdue')
            ->sum('total_amount');

        $invoiceCount = Invoice::whereBetween('invoice_date', [$start, $end])->count();

        $averageInvoiceValue = $invoiceCount > 0 ? $totalRevenue / $invoiceCount : 0;

        // Payment methods breakdown
        $paymentMethods = Invoice::whereBetween('invoice_date', [$start, $end])
            ->where('status', 'paid')
            ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_amount) as total'))
            ->groupBy('payment_method')
            ->get();

        return [
            'total_revenue' => $totalRevenue,
            'paid_invoices' => $paidInvoices,
            'pending_invoices' => $pendingInvoices,
            'overdue_invoices' => $overdueInvoices,
            'invoice_count' => $invoiceCount,
            'average_invoice_value' => $averageInvoiceValue,
            'payment_methods' => $paymentMethods,
        ];
    }

    private function getAppointmentMetrics(Carbon $start, Carbon $end): array
    {
        $totalAppointments = Appointment::whereBetween('appointment_date', [$start, $end])->count();

        $completedAppointments = Appointment::whereBetween('appointment_date', [$start, $end])
            ->where('status', 'completed')
            ->count();

        $cancelledAppointments = Appointment::whereBetween('appointment_date', [$start, $end])
            ->where('status', 'cancelled')
            ->count();

        $noShowAppointments = Appointment::whereBetween('appointment_date', [$start, $end])
            ->where('status', 'no_show')
            ->count();

        $confirmedAppointments = Appointment::whereBetween('appointment_date', [$start, $end])
            ->where('status', 'confirmed')
            ->count();

        $completionRate = $totalAppointments > 0 ? ($completedAppointments / $totalAppointments) * 100 : 0;
        $cancellationRate = $totalAppointments > 0 ? ($cancelledAppointments / $totalAppointments) * 100 : 0;
        $noShowRate = $totalAppointments > 0 ? ($noShowAppointments / $totalAppointments) * 100 : 0;

        // Average appointments per day
        $daysInPeriod = $start->diffInDays($end) + 1;
        $avgAppointmentsPerDay = $daysInPeriod > 0 ? $totalAppointments / $daysInPeriod : 0;

        return [
            'total_appointments' => $totalAppointments,
            'completed_appointments' => $completedAppointments,
            'cancelled_appointments' => $cancelledAppointments,
            'no_show_appointments' => $noShowAppointments,
            'confirmed_appointments' => $confirmedAppointments,
            'completion_rate' => round($completionRate, 2),
            'cancellation_rate' => round($cancellationRate, 2),
            'no_show_rate' => round($noShowRate, 2),
            'avg_appointments_per_day' => round($avgAppointmentsPerDay, 1),
        ];
    }

    private function getPatientMetrics(Carbon $start, Carbon $end): array
    {
        $totalPatients = Patient::count();

        $newPatients = Patient::whereBetween('created_at', [$start, $end])->count();

        // Active patients (had at least one appointment in the period)
        $activePatients = Appointment::whereBetween('appointment_date', [$start, $end])
            ->distinct('patient_id')
            ->count('patient_id');

        // Patients by age group - SQLite compatible
        $dbDriver = DB::connection()->getDriverName();

        if ($dbDriver === 'sqlite') {
            $ageGroups = Patient::select(
                DB::raw('CASE
                    WHEN (strftime("%Y", "now") - strftime("%Y", date_of_birth)) < 18 THEN "0-17"
                    WHEN (strftime("%Y", "now") - strftime("%Y", date_of_birth)) BETWEEN 18 AND 30 THEN "18-30"
                    WHEN (strftime("%Y", "now") - strftime("%Y", date_of_birth)) BETWEEN 31 AND 50 THEN "31-50"
                    WHEN (strftime("%Y", "now") - strftime("%Y", date_of_birth)) BETWEEN 51 AND 70 THEN "51-70"
                    ELSE "70+"
                END as age_group'),
                DB::raw('COUNT(*) as count')
            )
                ->groupBy('age_group')
                ->get();
        } else {
            // MySQL/PostgreSQL
            $ageGroups = Patient::select(
                DB::raw('CASE
                    WHEN YEAR(CURDATE()) - YEAR(date_of_birth) < 18 THEN "0-17"
                    WHEN YEAR(CURDATE()) - YEAR(date_of_birth) BETWEEN 18 AND 30 THEN "18-30"
                    WHEN YEAR(CURDATE()) - YEAR(date_of_birth) BETWEEN 31 AND 50 THEN "31-50"
                    WHEN YEAR(CURDATE()) - YEAR(date_of_birth) BETWEEN 51 AND 70 THEN "51-70"
                    ELSE "70+"
                END as age_group'),
                DB::raw('COUNT(*) as count')
            )
                ->groupBy('age_group')
                ->get();
        }

        return [
            'total_patients' => $totalPatients,
            'new_patients' => $newPatients,
            'active_patients' => $activePatients,
            'age_groups' => $ageGroups,
        ];
    }

    private function getDentistPerformance(Carbon $start, Carbon $end): array
    {
        $dentists = Dentist::with(['appointments' => function ($query) use ($start, $end) {
            $query->whereBetween('appointment_date', [$start, $end]);
        }])->get();

        $performance = $dentists->map(function ($dentist) use ($start, $end) {
            $appointments = $dentist->appointments;

            $completed = $appointments->where('status', 'completed')->count();
            $total = $appointments->count();

            // Calculate revenue from invoices for this dentist's appointments
            $revenue = Invoice::whereHas('appointment', function ($query) use ($dentist, $start, $end) {
                $query->where('dentist_id', $dentist->id)
                    ->whereBetween('appointment_date', [$start, $end]);
            })->sum('total_amount');

            return [
                'id' => $dentist->id,
                'name' => $dentist->full_name,
                'specialization' => $dentist->specialization,
                'total_appointments' => $total,
                'completed_appointments' => $completed,
                'completion_rate' => $total > 0 ? round(($completed / $total) * 100, 2) : 0,
                'revenue' => $revenue,
            ];
        })->sortByDesc('revenue');

        return $performance->values()->toArray();
    }

    private function getTreatmentAnalytics(Carbon $start, Carbon $end): array
    {
        try {
            // Get appointments with treatments in the date range
            $treatmentStats = DB::table('appointment_treatment')
                ->join('appointments', 'appointment_treatment.appointment_id', '=', 'appointments.id')
                ->join('treatments', 'appointment_treatment.treatment_id', '=', 'treatments.id')
                ->whereBetween('appointments.appointment_date', [$start, $end])
                ->select(
                    'treatments.name',
                    'treatments.price',
                    DB::raw('COUNT(*) as count'),
                    DB::raw('SUM(treatments.price) as total_revenue')
                )
                ->groupBy('treatments.id', 'treatments.name', 'treatments.price')
                ->orderByDesc('count')
                ->get();

            return [
                'top_treatments' => $treatmentStats->take(10),
                'total_treatments_performed' => $treatmentStats->sum('count'),
            ];
        } catch (\Exception $e) {
            // If appointment_treatment table doesn't exist, return empty data
            return [
                'top_treatments' => collect([]),
                'total_treatments_performed' => 0,
            ];
        }
    }

    private function getMonthlyRevenueTrend(): array
    {
        $months = [];
        $revenues = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthStart = $date->copy()->startOfMonth();
            $monthEnd = $date->copy()->endOfMonth();

            $revenue = Invoice::whereBetween('invoice_date', [$monthStart, $monthEnd])
                ->sum('total_amount');

            $months[] = $date->format('M Y');
            $revenues[] = $revenue;
        }

        return [
            'months' => $months,
            'revenues' => $revenues,
        ];
    }

    private function getAppointmentStatusDistribution(Carbon $start, Carbon $end): array
    {
        $distribution = Appointment::whereBetween('appointment_date', [$start, $end])
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        return [
            'labels' => $distribution->pluck('status')->toArray(),
            'data' => $distribution->pluck('count')->toArray(),
        ];
    }
}
