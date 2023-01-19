<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

         //Voorbeeld hoe de array er uit moet zien

        $products = array(
        array(
           ['id' => '2', 'quantity' => '3'],
           ['id' => '5', 'quantity' => '2']
            ),
            'family_id' => '6',
            'user_id' => '27'
        );

        $parcel_id = [];
        // $producten = $request->productenlijst;

        foreach($products['0'] as $product){
        $productname = DB::table('products')
        ->where('id', $product['id'])
        ->get('name');

        $quantity = DB::table('products')->where('id', $product['id'])->get('quantity_stock');

            if($product['quantity'] > $quantity)
            {
                return response([
                    'message'=>'Er is niet genoeg voorraad van' ($productname)
                ], 500);
            }
            else(
                DB::table('products')->where('id', $product['id'])->decrement('quantity_stock', $product['quantity'])
            );
        }
            $parcel_id = DB::table('parcels')->insertGetId
            (
                ['family_id' => $products['family_id'], 'user_id' => $products['user_id']]
            );
            foreach($products['0'] as $product){
                DB::table('product_parcel')->insert([
                    'amount' => $product['quantity'],
                    'parcel_id' => $parcel_id,
                    'product_id' => $product['id']
                ]);
            }
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
