<?php
namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\UserRequest;
use App\Http\Resources\Tenant\UserResource;
use App\Models\Tenant\User;
use App\Http\Resources\Tenant\UserCollection;

class UserController extends Controller
{
    public function record($id)
    {
        $record = new UserResource(User::findOrFail($id));

        return $record;
    }

    public function store(UserRequest $request)
    {
        $id = $request->input('id');
        $user = User::firstOrNew(['id' => $id]);
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        if (!$id) {
            $user->api_token = str_random(50);
            $user->password = bcrypt($request->input('password'));
        }
        elseif ($request->input('password') !== '') {
            if (config('tenant.password_change')) {
                $user->password = bcrypt($request->input('password'));
            }
        }
        $user->save();

        return [
            'success' => true,
            'message' => ($id)?'Usuario actualizado':'Usuario registrado'
        ];
    }

    public function records()
    {
        $records = User::all();

        return new UserCollection($records);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return [
            'success' => true,
            'message' => 'Usuario eliminado con éxito'
        ];
    }
}