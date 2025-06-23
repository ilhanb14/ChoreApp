<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FamilyInvitationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $inviter;
    public $family;

    public function __construct($inviter, $family)
    {
        $this->inviter = $inviter;
        $this->family = $family;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invitation to join ' . $this->family->name . ' family',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.family-invitation',
            with: [
                'inviter' => $this->inviter,
                'family' => $this->family,
            ],
        );
    }
}