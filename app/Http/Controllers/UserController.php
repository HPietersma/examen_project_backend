<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users =  User::with('role')->get();
        foreach($users as $user) {
            unset($user['password']);
            unset($user['email_verified_at']);
            
        }

        return $users;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed',
            'role_id' => 'required|int',
        ]);

        $role = Role::find($request->role_id);

        if (!$role) {
            return response([
                'message'=>'role not found'
            ], 404);
        }

        return User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
            'role_id' => $fields['role_id'],
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response([
                'message' => 'record not found'
            ], 404);
        }

        $role = Role::where('id', $user->role_id)->get('role_name')->first();
        $user['role'] = $role->role_name;

        return $user;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        {
            $fields = $request->validate([
                'name' => 'required|string',
                'email' => 'required|string|email',
                'password' => 'nullable|string',
                'role_id' => 'required|int',
            ]);

            if ($request->password) {
                $password = null;
                if (Hash::needsRehash($request->password)) {
                    $password = Hash::make($request->password);
                }
                else {
                    $password = $request->password;
                }
        
                $record = User::find($id);
                $role = Role::find($request->role_id);
        
                if (!$record) {
                    return response([
                        'message'=>'record not found'
                    ], 404);
                }

                if (!$role) {
                    return response([
                        'message'=>'role not found'
                    ], 404);
                }
                
                $record->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => $password,
                    'role_id' => $request->role_id
                ]);

                return response($record, 200);
            }
            else {
                $record = User::find($id);
                $role = Role::find($request->role_id);
        
                if (!$record) {
                    return response([
                        'message'=>'record not found'
                    ], 404);
                }

                if (!$role) {
                    return response([
                        'message'=>'role not found'
                    ], 404);
                }

                $record->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'role_id' => $request->role_id
                ]);

                return response($record, 200);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $record = User::find($id);

        if ($record) {
            $record->delete();
            return response([
                'message'=>'record deleted'
            ], 200);
        }
        else {
            return response([
                'message'=>'record not found'
            ], 404); 
        }
    }

    
    public function restore($id) {
        $record = User::where('id', $id)->withTrashed()->first();

        if ($record) {
            $record->restore();
            return response([
                'message'=>'record restored'
            ], 200);
        }
        else {
            return response([
                'message'=>'record not found'
            ], 404); 
        }
    }
    
}
