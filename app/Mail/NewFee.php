<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewFee extends Mailable
{
    use Queueable, SerializesModels;
    public $fee;
    public $date;
    public $student_name;
    /**
     * Create a new message instance.
     */
    public function __construct($fee,$date,$student_name)
    {
        $this->fee =$fee;
        $this->date =$date;
        $this->student_name =$student_name;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Fee',
        );
    }

    public function build()
    {
     return $this->subject('EduSphere')->view('emails.NewFee');
    }
    /**
     * Get the message content definition.
     *//*
    public function content(): Content
    {
        return new Content(
            view: 'view.name',
        );
    }
*/
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
