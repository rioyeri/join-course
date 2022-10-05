<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Models\ForgotPassword;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($url, $email)
    {
        $this->email = $email;
        $this->url = $url;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = ForgotPassword::where('email', $this->email)->first();
        $uri = $this->url."/resetpassword/".$this->email."/".$data->token;
        return $this->from('admin@flashacademia.nadasederhana.com', 'Admin Flash Academia')->markdown('emails.resetpassword',compact('uri'));
    }
}
