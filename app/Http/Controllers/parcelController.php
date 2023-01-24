<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\Parcel;


class ParcelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $parcels = DB::table('parcels')->get();

        foreach($parcels as $parcel) { // foreach parcel
            $parcel_items = DB::table('product_parcel')->where('parcel_id', $parcel->id)->get();
            foreach($parcel_items as $parcel_item) {
                $product = DB::table('products')->where('id', $parcel_item->product_id)->first();
                $parcel_item->category = DB::table('categories')->where('id', $product->category_id)->first()->category;
                $parcel_item->name = $product->name;
            }
            $parcel->created_at = date('d-m-Y H:i', strtotime($parcel->created_at));
            $parcel->user_name = DB::table('users')->where('id', $parcel->user_id)->first()->name;
            $parcel->family_name = DB::table('families')->where('id', $parcel->family_id)->first()->familyname;
            $parcel->products = $parcel_items;
            unset($parcel->updated_at);
        }

        return $parcels;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // check if family exists
        if(DB::table('families')->where('id', $request->input('family_id'))->doesntExist())
        {
            return response([
                'message'=>'Familie bestaat niet'
            ], 400);
        }

        $parcels = Parcel::where([
            ['family_id', '=', $request->input('family_id')],
        ])->get();

        if($parcels->count() > 0) {
            foreach($parcels as $parcel) {
                if($parcel->created_at === null) { continue; }
                $date = Carbon::parse($parcel->created_at);
                if($date > Carbon::now()->subDays(6)) {
                    return response([
                        'message' => 'Deze klant heeft al een pakket ontvangen in de afgelopen 6 dagen.'
                    ], 400);
                }
            }
        }

        $parcel_id = [];
        // check if product can be added to parcel
        foreach($request->input('products') as $product){
            $productname = DB::table('products')
            ->where('id', $product['id'])
            ->first('name');

            $quantity = DB::table('products')->where('id', $product['id'])->first();
            if($product['quantity'] > $quantity->quantity_stock)
            {
                return response([
                    'message'=>'Er is niet genoeg voorraad van: ' . $productname->name
                ], 400);
            }
            else(
                DB::table('products')->where('id', $product['id'])->decrement('quantity_stock', $product['quantity'])
            );
        }

        // Create new parcel so products van de added (returns id)
        $parcel_id = DB::table('parcels')->insertGetId([
            'family_id' => $request->input('family_id'),
            'user_id' => $request->user()->id
        ]);

        // add products to parcel.
        foreach($request->input('products') as $product){
            $bool = DB::table('product_parcel')->insert([
                'amount' => $product['quantity'],
                'parcel_id' => $parcel_id,
                'product_id' => $product['id'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
            if(!$bool) {
                return response([
                    'message'=>'Kan product met id: '. $product['id'] . ' niet toevoegen aan pakket'
                ], 500);
            }
        }
        
        return response ([
            'message' => 'Pakket is aangemaakt'
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $parcel = Parcel::find($id);
        if($parcel === null) { // if parcel doesn't exist
            return response([
                'message'=>'Pakket bestaat niet'
            ], 404);
        } else {
            return response()->json($parcel);
        }
        
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}