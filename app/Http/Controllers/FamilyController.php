<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Family;

class FamilyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Family::all();
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
            'familyname' => 'required|string',
            'address' => 'required|string',
            'homenr' => 'required|string',
            'zipcode' => 'required|string',
            'city' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|string',
            'amountAdults' => 'required|string',
            'amountChildren' => 'required|string',
            'amountBabies' => 'required|string'
        ]);

        $family = Family::create([
            'familyname' => $fields['familyname'],
            'address' => $fields['address'],
            'homenr' => $fields['homenr'],
            'zipcode' => $fields['zipcode'],
            'city' => $fields['city'],
            'phone' => $fields['phone'],
            'email' => $fields['email'],
            'amountAdults' => $fields['amountAdults'],
            'amountChildren' => $fields['amountChildren'],
            'amountBabies' => $fields['amountBabies']
        ]);

        return response($family, 201);
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $fields = $request->validate([
            'familyname' => 'required|string',
            'address' => 'required|string',
            'homenr' => 'required|string',
            'zipcode' => 'required|string',
            'city' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|string',
            'amountAdults' => 'required|string',
            'amountChildren' => 'required|string',
            'amountBabies' => 'required|string'
        ]);

        $record = Family::find($id);

        if ($record) {
            $record->update($request->all());
            return response($record, 200);
        }
        else {
            return response([
                'message'=>'record not found'
            ], 404);
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
        $record = Family::find($id);

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
}
