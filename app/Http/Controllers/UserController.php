<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::all();
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
        return User::findOr($id, fn () => response([
            'record not found'
        ], 404));
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

            $fields = $request->validate([
                'name' => 'required|string',
                'email' => 'required|string',
                'password' => 'required|string',
                'role_id' => 'required|int',
            ]);

            if (Hash::needsRehash($request->password)) {
                $password = Hash::make($request->password);
            }
            else {
                $password = $request->password;
            }

            $record = User::find($id);

            if ($record) {
                $record->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => $password,
                    'role_id' => $request->role_id

                ]);
                return response($record, 200);
            }
            else {
                return response([
                    'message'=>'record not found'
                ], 404);
            }
    }

    public function updatepassword(Request $request)
    {
        // haalt data van ingelogde gebruiker op
       $user = Auth::user();
        // aan te passen data ophalen
        $update = $request->validate([
            'email' => 'required|string',
            'old_password' => 'required|string',
            'new_password' => 'required|string|different:password',
        ]);
        //oud wachtwoord matchen
            if (Hash::check($update['old_password'], $user['password'])) {
                $password = Hash::make($update['new_password']);
            }

            else{
                return response([
                    'message'=>'Passwords do not match or are identical'
                ], 400);
            }

            $record = User::find($user['id']);
            //opgegeven records wijzigen
            if ($record) {
                $record->update([
                    'email' => $update['email'],
                    'password' => $password,
                ]);
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
