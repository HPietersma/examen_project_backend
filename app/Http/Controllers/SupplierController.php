<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Supplier;
use Carbon\Carbon;

class SupplierController extends Controller
{
    public function suppliersWithProducts() {
        return Supplier::with('Supplier_Product')->get();
    }

    public function supplierWithProducts($id) {
        return Supplier::where('id', $id)->with('Supplier_Product')->get();
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Supplier::all();
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
            'supplier' => 'required|string',
            'last_delivery' => 'string|nullable|date_format:Y-m-d',
            'next_delivery' => 'string|nullable|date_format:Y-m-d',
        ]);

        $supplier = DB::table('suppliers')->where('supplier', $fields['supplier'])->first();
        if(!empty($supplier)) {
            return response([
                'message'=>'supplier already exists'
            ], 400);
        }

        $last_date = Carbon::create($fields['last_delivery']);
        $next_date = Carbon::create($fields['next_delivery']);

        if($next_date < Carbon::now()) {
            return response([
                'message'=>'next delivery date cannot be in the past'
            ], 400);
        }

        if($last_date > $next_date) {
            return response([
                'message'=>'last delivery date cannot be greater than next delivery date'
            ], 400);
        }

        $product = Supplier::create([
            'supplier' => $fields['supplier'],
            'last_delivery' =>  $fields['last_delivery'],
            'next_delivery' => $fields['next_delivery'],
        ]);

        return response($product, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Supplier::findOr($id, fn () => response([
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
            'supplier' => 'required|string',
            'last_delivery' => 'string|nullable|date_format:Y-m-d',
            'next_delivery' => 'string|nullable|date_format:Y-m-d',
        ]);

        $record = Supplier::find($id);

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
        $record = Supplier::find($id);

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
