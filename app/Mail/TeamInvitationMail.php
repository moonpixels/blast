<?php

namespace App\Mail;

use App\Models\TeamInvitation as TeamInvitationModel;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TeamInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public TeamInvitationModel $invitation)
    {
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('Invitation to join the :team team', ['team' => $this->invitation->team->name])
        );
    }

    /**
     * Get the message content.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.teams.invitation',
        );
    }
}
