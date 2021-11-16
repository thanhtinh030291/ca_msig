<?php
namespace App\Http\Traits;

use App\Permission;
use App\Role;
use Auth;
use Illuminate\Support\Arr;

trait Authorizable {

    private $abilities = [
        'index' => 'view',
        'edit' => 'edit',
        'show' => 'view',
        'update' => 'edit',
        'create' => 'add',
        'store' => 'add',
        'destroy' => 'delete'
    ];

    /**
     * Override of callAction to perform the authorization before
     *
     * @param $method
     * @param $parameters
     * @return mixed
     */
    public function callAction($method, $parameters)
    {
    
        if( $ability = $this->getAbility($method) ) {
            $this->authorize($ability);
        }
        switch ($method) {
            case 'update':
            case 'edit':
                $id = reset($parameters);
                $model = 'App\\' .ucfirst(key($parameters));
                $model = new $model();
                $data = $model::findOrFail($id);
                $user = Auth::user();
                if($user->cannot('update', $data)){
                    return abort(403,__('message.not_have_role'));
                }
                break;
            case 'destroy':
                $id = reset($parameters);
                $model = 'App\\' .ucfirst(key($parameters));
                $model = new $model();
                $data = $model::findOrFail($id);
                $user = Auth::user();
                if($user->cannot('delete', $data)){
                    return abort(403,__('message.not_have_role'));
                }
            break;
            default:
                break;
        }

        return parent::callAction($method, $parameters);
    }

    public function getAbility($method)
    {
        $routeName = explode('.', \Request::route()->getName());
        $action = Arr::get($this->getAbilities(), $method);

        return $action ? $action . '_' . $routeName[0] : null;
    }

    private function getAbilities()
    {
        return $this->abilities;
    }

    public function setAbilities($abilities)
    {
        $this->abilities = $abilities;
    }
}
