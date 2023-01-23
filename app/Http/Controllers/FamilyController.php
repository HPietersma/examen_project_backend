<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\Family;
use App\Models\Parcel;

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
            'phone' => 'required|string|unique:families,phone',
            'email' => 'required|string|unique:families,email',
            'amountAdults' => 'required|int',
            'amountChildren' => 'required|int',
            'amountBabies' => 'required|int'
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
    public function show(Request $request)
    {
        $family = array();

        $id = $request->family_id;

        $family_info = Family::findOr($id, fn () => response([
            'record not found'
        ], 404));

        $parcel = DB::table('parcels')->where('family_id', $id)->get()->sortbydesc('created_at');

        array_push($family, $family_info, $parcel);

        return $family;
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
            'amountAdults' => 'required|int',
            'amountChildren' => 'required|int',
            'amountBabies' => 'required|int'
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

    public function restore($id) {
        $record = Family::where('id', $id)->withTrashed();

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

    public function familiesWithoutParcel() {
        // return Family::with('parcels')
        // ->whereHas('parcels', function($q) {
        //     $q->where('id', '=', 2);
        // })
        // ->get();

        return Family::doesntHave('parcels')->orWhereHas('parcels', function($q) {
            $q->where('created_at', '<', Carbon::now()->subDays(5));
        })
        ->get();
    }
}
