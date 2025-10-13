<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdatePastAppointments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointments:update-past';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update past confirmed appointments to completed status';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Checking for past confirmed appointments...');

        // Get current date and time
        $now = Carbon::now();

        // Find all confirmed appointments where the appointment date + time + duration has passed
        $appointments = Appointment::where('status', 'confirmed')
            ->get()
            ->filter(function ($appointment) {
                // Parse appointment date and time
                $appointmentDateTime = Carbon::parse(
                    $appointment->appointment_date->format('Y-m-d').' '.$appointment->appointment_time
                );

                // Add duration to get end time
                $appointmentEndTime = $appointmentDateTime->copy()->addMinutes($appointment->duration);

                // Check if appointment has ended
                return $appointmentEndTime->isPast();
            });

        if ($appointments->isEmpty()) {
            $this->info('No past confirmed appointments found.');

            return Command::SUCCESS;
        }

        // Update appointments to completed
        $count = 0;
        foreach ($appointments as $appointment) {
            $appointment->update(['status' => 'completed']);
            $count++;
        }

        $this->info("Updated {$count} past confirmed appointment(s) to completed status.");

        return Command::SUCCESS;
    }
}
