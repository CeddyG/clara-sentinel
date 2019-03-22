<?php

namespace CeddyG\ClaraSentinel\Http\Controllers\Admin;

use Excel;
use Sentinel;
use Illuminate\Http\Request;
use CeddyG\ClaraSentinel\Models\Role;
use CeddyG\ClaraSentinel\Models\User;
use CeddyG\ClaraSentinel\Http\Requests\UserRequest;
use Facades\CeddyG\ClaraSentinel\Repositories\UserRepository;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    protected $sPath            = 'clara-sentinel::admin.user';
    protected $sPathRedirect    = 'admin/user';
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sPageTitle = 'Sentinel';
        $oItems     = User::All();

        return view($this->sPath.'/index', compact('oItems', 'sPageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sPageTitle     = "Ajout User";
        $aRoles         = Role::all()->pluck('name', 'id');
        
        return view($this->sPath.'/form', compact('aRoles', 'sPageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\UserRequest $oRequest
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $oRequest)
    {
        UserRepository::store($oRequest->all());
        
        return redirect($this->sPathRedirect);
    }
    
    public function import(Request $oRequest, UserRepository $oRepository)
    {
        if($oRequest->hasFile('file'))
        {
            Excel::load($oRequest->file('file')->getRealPath(), function ($oReader) use ($oRepository) 
            {
                foreach ($oReader->toArray() as $iKey => $aRow) 
                {
                    $oUser = Sentinel::findByCredentials(['email' => $aRow['e_mail']]);
                    
                    if ($oUser === null)
                    {
                        $aInsert = [
                            'email'         => $aRow['e_mail'],
                            'first_name'    => $aRow['prenom'],
                            'last_name'     => $aRow['nom']
                        ];
                        
                        $aInsert['password'] = isset($aRow['mot_de_passe']) && $aRow['mot_de_passe'] != ''
                            ? $aRow['mot_de_passe']
                            : str_random();
                        
                        Sentinel::register($aInsert, true);
                    }
                    else
                    {
                        $aUpdate = [
                            'first_name'    => $aRow['prenom'],
                            'last_name'     => $aRow['nom']
                        ];
                        
                        if (isset($aRow['mot_de_passe']) && $aRow['mot_de_passe'] != '')
                        {
                            $aUpdate['password'] = $aRow['mot_de_passe'];
                        }
                        
                        Sentinel::update($oUser, $aUpdate);
                    }
                }
                
                return redirect($this->sPathRedirect)->withOk('Import effectué avec succès.');
            });
            
            return redirect($this->sPathRedirect)->withKo('L\'import a échoué.');
        }
        
        return redirect($this->sPathRedirect)->withKo('Aucun fichier trouvé.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $sPageTitle     = "Modification";
        $oItem          = Sentinel::findById($id)->load('roles');
        $aRoles         = Role::all()->pluck('name', 'id');
        
        return view($this->sPath.'/form',  compact('oItem', 'aRoles', 'sPageTitle'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UserRequest $oRequest
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $oRequest, $id)
    {
        UserRepository::update($id, $oRequest->all());

        return redirect($this->sPathRedirect)->withOk("L'objet a été modifié.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $oUser = Sentinel::findById($id);
        $oUser->roles()->detach();
        $oUser->delete();
        
        return redirect($this->sPathRedirect)->withOk("L'objet a été supprimé.");
    }
    
    //Affiche le formulaire du login
    public function login()
    {
        $sPageTitle = 'Login';
        
        //Si on est pas connecté, on affiche le formuaire, sinon on redirige vers l'accueil
        if (!Sentinel::check())
        {
            return view($this->sPath . '/login', compact('sPageTitle'));
        }
        else
        {
            return redirect('admin');
        }
    }
    
    public function authenticate(Request $oRequest)
    {
        $aInputs    = $oRequest->all();
        $bRemember  = array_key_exists('remember', $aInputs);
        
        if(Sentinel::authenticate($aInputs, $bRemember))
        {
            return redirect('admin');
        }
        else
        {
            return redirect('login')
            ->withInput($oRequest->only('email', 'remember'))
            ->withErrors([
                'fail' => trans('auth.failed'),
            ]);
        }
    }
    
    public function logout()
    {
        Sentinel::logout();
        return redirect('/');
    }
}
