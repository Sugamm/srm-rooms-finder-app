<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class feedbackMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $u_email;
    public $u_reg;
    public $subject;
    public $type;
    public $msg;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $u_email, $u_reg, $subject, $type, $msg)
    {
        $this->name = $name;
        $this->u_email = $u_email;
        $this->u_reg = $u_reg;
        $this->subject = $subject;
        $this->type = $type;
        $this->msg = $msg;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('sugam0030@gmail.com')->view('email.feedbackEmail');
    }
}
