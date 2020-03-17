<?php

namespace CeddyG\ClaraSentinel\Http\Middleware;

use Closure;
use Sentinel;
use Illuminate\Support\Facades\Route;
use Facades\CeddyG\ClaraSentinel\Repositories\UserRepository;

class SentinelAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $oRequest
     * @param  \Closure  $oNext
     * @return mixed
     */
    public function handle($oRequest, Closure $oNext, $sType = 'web')
    {
        if (!is_null($oRequest->bearerToken()))
        {
            $oUser = UserRepository::findByField(
                'api_token', 
                $oRequest->bearerToken(), 
                ['id']
            );
            
            if (!is_null($oUser))
            {
                Sentinel::authenticate(Sentinel::findById($oUser->first()->id));
            }
        }
        
        if (Sentinel::check())
        {
            // User is logged in and assigned to the `$user` variable.
            $sAction = Route::getCurrentRoute()->getName();
            
            if (Sentinel::hasAccess($sAction))
            {
                return $oNext($oRequest);
            }
            else 
            {
                if ($sType == 'api')
                {
                    return response()->json([
                        'status'    => 403,
                        'message'   => 'Access denied'
                    ]);
                }
                else
                {
                    return response('Access denied');
                }
            }
        }
        else
        {
            // User is not logged in
            if ($sType == 'api')
            {
                return response()->json([
                    'status'    => 401,
                    'message'   => 'Vous devez être connecté et avoir les droits'
                ]);
            }
            else
            {
                session(['asked-uri' => $oRequest->path()]);
                
                return redirect('login');
            }
        }
        
    }
}
