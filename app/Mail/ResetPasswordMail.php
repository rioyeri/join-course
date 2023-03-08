<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

use App\Models\ForgotPassword;

class ResetPasswordMail extends Mailable
{
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
        return $this->from('admin@flashacademia.com', 'Admin Flash Academia')->subject('[NO REPLY] Reset Your Password in Flash Academia')->markdown('emails.resetpassword',compact('uri'));
    }
}
