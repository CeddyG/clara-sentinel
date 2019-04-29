<?php

namespace CeddyG\ClaraSentinel\Http\Controllers\Admin;

use Sentinel;
use CeddyG\ClaraSentinel\Models\User;
use CeddyG\ClaraSentinel\Models\Role;
use CeddyG\ClaraSentinel\Http\Requests\RoleRequest;
use CeddyG\ClaraSentinel\Repositories\RoleRepository;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    protected $sPath            = 'clara-sentinel::admin.group';
    protected $sPathRedirect    = 'admin/group';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $oItems     = Role::All();
        $sPageTitle = 'Groupes admin';

        return view($this->sPath.'/index', compact('oItems', 'sPageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sPageTitle     = 'Création d\'un groupe';
        $aUsers         = User::all()->sortBy('full_name')->pluck('full_name', 'id');
        
        return view($this->sPath.'/form', compact('sPageTitle', 'aUsers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\RoleRequest  $oRequest
     * 
     * @return \Illuminate\Http\Response
     */
    public function store(RoleRequest $oRequest)
    {
        RoleRepository::store($oRequest->all());
        
        return redirect($this->sPathRedirect);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * 
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $sPageTitle     = "Sentinel Role";
        $oItem          = Sentinel::findRoleById($id)->load('users');
        $aUsers         = User::all()->pluck('full_name', 'id')->toArray();
        
        return view($this->sPath.'/form',  compact('oItem', 'aUsers', 'sPageTitle'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\RoleRequest  $oRequest
     * @param  int  $id
     * 
     * @return \Illuminate\Http\Response
     */
    public function update(RoleRequest $oRequest, $id)
    {
        RoleRepository::update($id, $oRequest->all());
        
        return redirect($this->sPathRedirect)->withOk("L'objet a été modifié.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * 
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $oRole = Sentinel::findRoleById($id);
        $oRole->users()->detach();
        $oRole->delete();
        
        return redirect($this->sPathRedirect)->withOk("L'objet a été supprimé.");
    }
}
