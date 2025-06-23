<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Reward;
use App\Models\Family;

class RewardClaimEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $child;
    public $reward;
    public $family;

    public function __construct(User $child, Reward $reward, Family $family)
    {
        $this->child = $child;
        $this->reward = $reward;
        $this->family = $family;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->child->name . ' redeemed reward: ' . $this->reward->reward,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reward-claim',
            with: [
                'child' => $this->child,
                'reward' => $this->reward,
                'family' => $this->family,
            ],
        );
    }
}