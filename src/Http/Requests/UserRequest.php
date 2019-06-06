<?php

namespace CeddyG\ClaraSentinel\Http\Requests;

use Request;
use CeddyG\ClaraSentinel\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
    
    public function all($keys = null)
    {
        $aAttribute = parent::all($keys);
        
        if (($this->method() == 'PUT' || $this->method() == 'PATCH') && $aAttribute['password'] == '')
        {
            unset($aAttribute['password']);
        }
        
        return $aAttribute;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch($this->method())
        {
            case 'POST':
            {
                if (Request::path() != 'install')
                {
                    return [
                        'last_name'     => 'required|max:255|string',
                        'first_name'    => 'required|max:255|string',
                        'email'         => 'required|max:255|email|unique:users',
                        'password'      => 'required|max:255|string'
                    ];
                }
                else
                {
                    return [
                        'last_name'     => 'required|max:255|string',
                        'first_name'    => 'required|max:255|string',
                        'email'         => 'required|max:255|email',
                        'password'      => 'required|max:255|string'
                    ];
                }
            }
            
            case 'PUT':
            case 'PATCH':
            {
                $user = User::find($this->user);
                
                return [
                    'last_name'     => 'required|max:255|string',
                    'first_name'    => 'required|max:255|string',
                    'email'         => 'required|max:255|email|unique:users,email,'.$user->id,
                    'password'      => 'max:255'
                ];
            }
            
            default:break;
        }
    }
}
