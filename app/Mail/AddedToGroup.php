<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AddedToGroup extends Mailable
{
    use Queueable, SerializesModels;

    public $groupName;
    public $adminName;
    public $memberName;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($groupName,$adminName,$memberName)
    {
        $this->groupName = $groupName;
        $this->adminName = $adminName;
        $this->memberName = $memberName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('sugam0030@gmail.com')->replyTo("sugam0030@gmail.com")->subject("You added in announcement group")->view('email.addedtogroup');
    }
}
