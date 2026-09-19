<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;
    public $in_data;
    public $_subject;
    public $_view;

    /**
     * Create a new message instance.
     */
    public function __construct($in_data)
    {
        $this->in_data = $in_data;
        $this->_subject = config("web.mail.default_subject");
        if (array_key_exists("subject", $in_data)) {
            $this->_subject = !empty($in_data["subject"]) ? $in_data["subject"] : $this->_subject;
        }
        if (!array_key_exists("body_text", $in_data)) {
            $this->in_data["body_text"] = config("web.mail.default_body_text");
        }
        $this->in_data["store"] = config("web.mail");
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->_subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: "mail.index",
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
