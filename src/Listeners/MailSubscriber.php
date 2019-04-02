<?php

namespace CeddyG\ClaraSentinel\Listeners;

use Mail;
use CeddyG\ClaraSentinel\Mail\User\Reminder;



/**
 * Description of MailSubscriber
 *
 * @author Cedric
 */
class MailSubscriber
{
    public function sendReminderMail($oEvent)
    {
        Mail::to($oEvent->oUser->email)
            ->send(new Reminder($oEvent));
    }
    
    /**
     * Register the listeners for the subscriber.
     *
     * @param  Illuminate\Events\Dispatcher  $oEvent
     */
    public function subscribe($oEvent)
    {
        $oEvent->listen(
            'CeddyG\ClaraSentinel\Events\User\ReminderEvent',
            'CeddyG\ClaraSentinel\Listeners\MailSubscriber@sendReminderMail'
        );
    }
}
