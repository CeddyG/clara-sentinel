<?php
namespace CeddyG\ClaraSentinel;

use Illuminate\Support\ServiceProvider;

use View;
use Route;

/**
 * Description of EntityServiceProvider
 *
 * @author CeddyG
 */
class SentinelServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishesConfig();
		$this->publishesTranslations();
        $this->loadRoutesFrom(__DIR__.'/routes.php');
		$this->publishesView();
        
        $this->setPermissionsInView();
    }
    
    private function setPermissionsInView()
    {
        View::composer('clara-sentinel::admin.user.form', function($view)
        {            
            $view->with('aPermissions', self::getPermissions());
        });
        
        View::composer('clara-sentinel::admin.group.form', function($view)
        {            
            $view->with('aPermissions', self::getPermissions());
        });
    }
    
    public static function getPermissions()
    {
        $oPerms = Route::getRoutes();
        
        //Liste de permissions possibles à partir des routes existantes
        $aPermissions = [];
        $sCurrentPerm = '';
        
        foreach($oPerms as $oPerm)
        {
            if($oPerm->getName() != ''
            && $oPerm->getName() != 'authenticate'
            && strrpos($oPerm->getName(), 'sentinel') === false
            && strrpos($oPerm->getName(), 'group') === false
            && strrpos($oPerm->getName(), 'debugbar') === false)
            {
                $sName = preg_replace('/admin./', '', $oPerm->getName(), 1);
                
                $sTmpName = explode('.', $sName);
                if($sCurrentPerm != $sTmpName[0] && $sTmpName[0] != 'admin')
                {
                    $aPermissions['admin.'.$sTmpName[0].'.*'] = $sTmpName[0];
                    $sCurrentPerm = $sTmpName[0];
                }
                
                $sName = str_replace('.', ' ', $sName);
                $aPermissions[$oPerm->getName()] = $sName;
            }
            
        }
        
        asort($aPermissions);
        $aPermissions = ['*' => 'all'] + $aPermissions;
        
        return $aPermissions;
    }
    
    /**
	 * Publish config file.
	 * 
	 * @return void
	 */
	private function publishesConfig()
	{
		$sConfigPath = __DIR__ . '/../config';
        if (function_exists('config_path')) 
		{
            $sPublishPath = config_path();
        } 
		else 
		{
            $sPublishPath = base_path();
        }
		
        $this->publishes([$sConfigPath => $sPublishPath], 'clara.sentinel.config');  
	}
	
	private function publishesTranslations()
	{
		$sTransPath = __DIR__.'/../resources/lang';

        $this->publishes([
			$sTransPath => resource_path('lang/vendor/clara-sentinel'),
			'clara.sentinel.trans'
		]);
        
		$this->loadTranslationsFrom($sTransPath, 'clara-sentinel');
    }

	private function publishesView()
	{
        $sResources = __DIR__.'/../resources/views';

        $this->publishes([
            $sResources => resource_path('views/vendor/clara-sentinel'),
        ], 'clara.sentinel.views');
        
        $this->loadViewsFrom($sResources, 'clara-sentinel');
	}

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/clara.sentinel.php', 'clara.sentinel'
        );
        
        $this->mergeConfigFrom(
            __DIR__ . '/../config/clara.group.php', 'clara.group'
        );
    }
}
