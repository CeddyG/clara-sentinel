<?php

namespace CeddyG\ClaraSentinel\Events\User;

use Cartalyst\Sentinel\Users\EloquentUser;
use Cartalyst\Sentinel\Reminders\EloquentReminder;

/**
 * Description of ReminderEvent
 *
 * @author Cedric
 */
class ReminderEvent
{
    public $oUser;
    public $oReminder;

    public function __construct(EloquentUser $oUser, EloquentReminder $oReminder)
    {
        $this->oUser     = $oUser;
        $this->oReminder = $oReminder;
    }
}
