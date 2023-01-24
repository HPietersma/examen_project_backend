<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\Role;

class AuthController extends Controller
{
    public function register(Request $request) {

        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed',
            'role_id' => 'required|int',
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
            'role_id' => $fields['role_id'],
        ]);


        if ($user->role_id == 1) {
            $token = $user->createToken('token', ['directie', 'magazijnmedewerker', 'vrijwilliger'])->plainTextToken;
        }

        if ($user->role_id == 2) {
            $token = $user->createToken('token', ['magazijnmedewerker'])->plainTextToken;
        }

        if ($user->role_id == 3) {
            $token = $user->createToken('token', ['vrijwilliger'])->plainTextToken;
        }

        $role = Role::where('id', $user->role_id)->get('role_name')->first();
        $user['role'] = $role->role_name;

        $response = [
            'user' => $user->name,
            'email' => $user->email,
            'role_id' => $user->role_id,
            'role' => $user->role,
            'token' => $token
        ];

        return response($response, 201);
    }


    public function login(Request $request) {

        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = User::Where('email', $fields['email'])->first();
        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'inloggegevens komen niet overeen'
            ], 401);
        }

        if ($user->role_id == 1) {
            $token = $user->createToken('token', ['directie', 'magazijnmedewerker', 'vrijwilliger'])->plainTextToken;
        }

        if ($user->role_id == 2) {
            $token = $user->createToken('token', ['magazijnmedewerker', 'vrijwilliger'])->plainTextToken;
        }

        if ($user->role_id == 3) {
            $token = $user->createToken('token', ['vrijwilliger'])->plainTextToken;
        }


        $role = Role::where('id', $user->role_id)->get('role_name')->first();
        $user['role'] = $role->role_name;

        $response = [
            'user' => $user->name,
            'email' => $user->email,
            'role_id' => $user->role_id,
            'role' => $user->role,
            'token' => $token
        ];

        return response($response, 200);

    }


    public function logout(Request $request) {
        $request->user()->tokens()->delete();

        return response([
            'message'  => 'u bent uitgelogd'
        ], 200);
    }


    public function authToken(Request $request) {
        $user =  $request->user();

        $role = Role::where('id', $user->role_id)->get('role_name')->first();
        $user['role'] = $role->role_name;

        return response([
            'user' => $user
        ], 200);
    }
}
