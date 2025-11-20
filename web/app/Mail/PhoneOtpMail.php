<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PhoneOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $code;

    /**
     * Create a new message instance.
     */
    public function __construct(string $code)
    {
        $this->code = $code;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Mã xác thực thay đổi số điện thoại')
                    ->view('emails.phone_otp')
                    ->with(['code' => $this->code]);
    }
}
