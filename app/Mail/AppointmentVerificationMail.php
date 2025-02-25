<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $verificationCode;
    public $userName;

    /**
     * Create a new message instance.
     */
    public function __construct($verificationCode, $userName)
    {
        $this->verificationCode = $verificationCode;
        $this->userName = $userName;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('🔑 Vérifiez votre rendez-vous FIXI – Code de confirmation')
            ->view('emails.appointment_verification')
            ->with([
                'code' => $this->verificationCode,
                'userName' => $this->userName,
            ]);
    }
}