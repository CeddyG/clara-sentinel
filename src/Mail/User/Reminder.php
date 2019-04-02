<?php

namespace CeddyG\ClaraSentinel\Mail\User;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Reminder extends Mailable
{
    use Queueable, SerializesModels;

    public $oEvent;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($oEvent)
    {
        $this->oEvent = $oEvent;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('clara-sentinel::emails.user.reminder')
            ->subject(__('passwords.reset_password'));
    }
}
