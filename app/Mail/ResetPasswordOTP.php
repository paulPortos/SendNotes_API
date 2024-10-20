<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;

class ResetPasswordOTP extends Mailable
{
    use Queueable, SerializesModels;

    public $resetPasswordToken;

    /**
     * Create a new message instance.
     *
     * @param string $otp
     * @return void
     */
    public function __construct($resetPasswordToken)
    {
        $this->resetPasswordToken = $resetPasswordToken;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Password Reset OTP',
            from: new Address('no-reply@sendnotes.com', 'Send Notes')
        );
    }
    public function content(): Content
    {
        return new Content(
            view: 'OtpNotif'
        );
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    // public function build()
    // {
    //     return $this->from(config('mail.from.address', 'default@example.com'), config('mail.from.name', 'Default Name'))
    //         ->subject('Password Reset OTP')
    //         ->view('OtpNotif')
    //         ->with([
    //             'otp' => $this->resetPasswordToken,
    //         ]);
    // }
}
