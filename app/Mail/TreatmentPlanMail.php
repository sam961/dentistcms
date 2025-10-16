<?php

namespace App\Mail;

use App\Models\TreatmentPlan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TreatmentPlanMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public TreatmentPlan $treatmentPlan
    ) {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Treatment Plan - '.$this->treatmentPlan->title,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.treatment-plan',
            with: [
                'treatmentPlan' => $this->treatmentPlan,
                'patient' => $this->treatmentPlan->patient,
                'dentist' => $this->treatmentPlan->dentist,
                'items' => $this->treatmentPlan->items()->with('treatment')->get(),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
