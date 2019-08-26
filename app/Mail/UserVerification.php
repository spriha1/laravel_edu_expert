<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;


class UserVerification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Request $request, $hash)
    {
        $this->username = $request->input('username');
        $this->password = $request->input('password');
        $this->code = base64_encode($hash);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.user_verification')->with([
                        'username' => $this->username,
                        'password' => $this->password,
                        'code' => $this->code,
                    ]);
    }
}
