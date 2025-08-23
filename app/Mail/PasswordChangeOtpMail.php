<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordChangeOtpMail extends Mailable
{
    use Queueable, SerializesModels;

        public string $name;
    public string $otp;

    public function __construct(string $name, string $otp)
    {
        $this->name = $name;
        $this->otp  = $otp;
    }

    public function build()
    {
        return $this->subject('Your Password Change OTP')
                    ->markdown('emails.password_change_otp');
    }




}
