<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotifyHours extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $totalHours;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $totalHours)
    {
        $this->user = $user;
        $this->totalHours = $totalHours;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Previous month notification hours',
        );
    }

    public function build()
    {
        return $this->view('emails.monthly_work_summary')
            ->with([
                'name' => $this->user->name,
                'totalHours' => $this->totalHours,
            ]);
    }
}
