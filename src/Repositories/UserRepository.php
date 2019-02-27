<?php

namespace CeddyG\ClaraSentinel\Repositories;

use CeddyG\QueryBuilderRepository\QueryBuilderRepository;

use Sentinel;

class UserRepository extends QueryBuilderRepository
{
    public static function store($aInputs)
    {
        $oUser              = Sentinel::register($aInputs, true);
        $oUser->permissions = self::attachPermissions($aInputs);
        $oUser->save();
        
        self::attachRoles($oUser, $aInputs);
    }
    
    public function update($id, array $aInputs)
    {
        $oUser = Sentinel::findById($id);
        
        if (isset($aInputs['password']) && $aInputs['password'] == '')
        {
            unset($aInputs['password']);
        }

        $oUser = Sentinel::update($oUser, $aInputs);
        
        $oUser->permissions = self::attachPermissions($aInputs);
        
        $oUser->save();
        
        self::attachRoles($oUser, $aInputs);
    }
    
    private static function attachPermissions($aInputs)
    {
        $aPermissions = [];
        
        if (isset($aInputs['permissions']) && $aInputs['permissions'] !== null)
        {
            foreach ($aInputs['permissions'] as $perm)
            {
                $aPermissions[$perm] = true;
            }
        }
        
        return $aPermissions;
    }
    
    private static function attachRoles($oUser, $aInputs)
    {
        if (isset($aInputs['roles']) && $aInputs['roles'] !== null) 
        {
            $aRoles = is_array($aInputs['roles']) ? $aInputs['roles'] : [$aInputs['roles']];
            
            $oUser->roles()->sync($aRoles);
        } 
        else 
        {
            $oUser->roles()->detach();
        }
    }
}
