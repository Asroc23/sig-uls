<?php

namespace App\Mail;

use App\Models\Graduate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GraduateNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Graduate $graduate,
        public string $subject,
        public string $message,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            subject: $this->subject,
        );
    }

    public function content(): Content
    {
        // Replace variables in message
        $processedMessage = $this->replaceVariables($this->message, $this->graduate);

        return new Content(
            view: 'emails.graduate-notification',
            with: [
                'graduate' => $this->graduate,
                'message' => $processedMessage,
            ],
        );
    }

    private function replaceVariables(string $message, Graduate $graduate): string
    {
        return str_replace(
            ['{name}', '{email}', '{graduation_year}'],
            [$graduate->first_name . ' ' . $graduate->last_name, $graduate->email, $graduate->graduation_year],
            $message,
        );
    }
}
