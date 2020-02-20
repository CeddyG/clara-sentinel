<?php

namespace CeddyG\ClaraSentinel\Http\Controllers;

use Sentinel;
use Reminder;
use Illuminate\Http\Request;
use CeddyG\ClaraSentinel\Events\User\ReminderEvent;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    protected $sPath = 'clara-sentinel::admin.user';
    
    //Affiche le formulaire du login
    public function login()
    {
        $sPageTitle = 'Login';
        
        //Si on est pas connecté, on affiche le formuaire, sinon on redirige vers l'accueil
        if (!Sentinel::check())
        {
            return view($this->sPath.'/login', compact('sPageTitle'));
        }
        else
        {
            return redirect('/');
        }
    }
    
    public function authenticate(Request $oRequest)
    {
        $aInputs    = $oRequest->all();
        $bRemember  = array_key_exists('remember', $aInputs);
        
        if(Sentinel::authenticate($aInputs, $bRemember))
        {
            if (session('asked-uri') != null)
            {
                $sUri = session('asked-uri');
                
                $oRequest->session()->forget('asked-uri');
                
                return redirect($sUri);                
            }
            else
            {
                return redirect('/');
            }
        }
        else
        {
            return redirect('login')
                ->withInput($oRequest->only('email', 'remember'))
                ->withErrors([
                    'fail' => __('auth.failed'),
                ]);
        }
    }
    
    public function logout()
    {
        Sentinel::logout();
        return redirect('/');
    }
    
    public function createReminder() 
    {
        return view($this->sPath.'.reminder-form');
    }

    public function storeReminder(Request $oRequest) 
    {
        $oUser = Sentinel::findByCredentials(['email' => $oRequest->input('email')]);

        if ($oUser) 
        {
            if (!$oReminder = Reminder::get($oUser))
            {
                $oReminder = Reminder::create($oUser);
            }
            
            event(new ReminderEvent($oUser, $oReminder));
            
            return redirect('login')->withStatus(__('passwords.sent'));
        }
        else
        {
            return redirect(route('password.request'))->withErrors(['email' => __('passwords.user')]);
        }
    }
    
    public function getReminder($sCode)
    {
        $oReminderModel = Reminder::createModel();
        return $oReminderModel->where('code', $sCode)->first(['user_id']);
    }
    
    public function editPassword($sCode) 
    {
        $oReminder = $this->getReminder($sCode);
        
        if ($oReminder) 
        {
            $oUser = Sentinel::findById($oReminder->user_id);
            
            if ($oUser && Reminder::exists($oUser, $sCode))
            {
                return view($this->sPath.'.reset-password-form', ['sCode' => $sCode]);                
            }
            else
            {
                return redirect('/');
            }
        }
        else 
        {
            //incorrect info was passed
            return redirect('/');
        }
    }

    public function updatePassword(Request $oRequest) 
    {
        $sCode      = $oRequest->input('code');
        $oReminder  = $this->getReminder($sCode);

        $oUser = Sentinel::findById($oReminder->user_id);

        //incorrect info was passed.
        if (!Reminder::exists($oUser, $sCode)) 
        {
            return redirect('/');
        }

        if ($oRequest->input('password') != $oRequest->input('password_confirmation')) 
        {
            return redirect(route('password.reset', ['token' => $sCode]))->withErrors(['password' => __('passwords.password')]);
        }

        Reminder::complete($oUser, $sCode, $oRequest->input('password'));

        return redirect('/');
    }
}
