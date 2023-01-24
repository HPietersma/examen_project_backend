<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        return User::with('role')->get();
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

        $user = User::findOr($id, fn () => response([
            'record not found'
        ], 404));

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
        //Als directie gegevens van alle gebruikers aan kunnen passen
            $fields = $request->validate([
                'name' => 'string',
                'email' => 'string',
                'password' => 'string',
                'role_id' => 'int',
            ]);

            if (Hash::needsRehash($request->password)) {
                $password = Hash::make($request->password);
            }
            else {
                $password = $request->password;
            }

            $record = User::find($id);

            if ($record) {
                if($request->input('name')) { $record->update(['name' => $request->name]); }
                if($request->input('email')) { $record->update(['email' => $request->email]); }
                if($request->input('password')) { $record->update(['password' => $password]); }
                if($request->input('role_id')) { $record->update(['role_id' => $request->role_id]); }
                return response($record, 200);
            }
            else {
                return response([
                    'message'=>'record not found'
                ], 404);
            }
    }

    public function updateuser(Request $request)
    {
        //Als gebruiker gegevens aanpassen

    $user = Auth::user();

        $update = $request->validate([
            'name' => 'string',
            'email' => 'string',
            'old_password' => 'string',
            'new_password' => 'string|different:old_password',
        ]);


        if(isset($update['old_password']) && isset($update['new_password'])) {
            if (Hash::check($update['old_password'], $user['password'])) {
                $password = Hash::make($update['new_password']);
            }
            else{
                return response([
                    'message'=>'Passwords do not match or are identical'
                ], 400);
            }
        }

        $record = User::where('id', $user['id'])->first();

        if ($record) {
            if($request->input('name')) { $record->update(['name' => $request->name]); }
            if($request->input('email')) { $record->update(['email' => $request->email]); }
            if($request->input('new_password')) { $record->update(['password' => $password]); }
            return response($record, 200);
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
        //User verwijderen
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
        //Verwijderde gebruiker herstellen
        $record = User::where('id', $id)->withTrashed();

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
