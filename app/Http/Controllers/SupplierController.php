<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Supplier;


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
            'last_delivery' => 'string|nullable',
            'next_delivery' => 'string|nullable',
        ]);

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
            'last_delivery' => 'string|nullable',
            'next_delivery' => 'string|nullable',
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
