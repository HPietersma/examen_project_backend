<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Product::with('categories')->get();
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
            'description' => 'string|nullable',
            'category_id' => 'required|int',
            'quantity_stock' => 'required|int'
        ]);

        $product = DB::table('products')->where('name', $fields['name'])->first();
        if(!empty($product)) {
            return response([
                'message'=>'product already exists'
            ], 400);
        }

        $category = DB::table('categories')->where('id', $fields['category_id'])->first();
        if(empty($category)) {
            return response([
                'message'=>'category does not exist'
            ], 400);
        }

        if($fields['quantity_stock'] < 0) {
            return response([
                'message'=>'quantity stock must be greater than 0'
            ], 400);
        }

        $product = Product::create([
            'name' => $fields['name'],
            'description' =>  $fields['description'],
            'category_id' => $fields['category_id'],
            'quantity_stock' => $fields['quantity_stock']
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
        return Product::findOr($id, fn () => response([
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
            'description' => 'string|nullable',
            'category_id' => 'required|int',
            'quantity_stock' => 'required|int'
        ]);
        
        $product = DB::table('products')->where('name', $fields['name'])->whereNot('id', $id)->first();
        if(!empty($product)) {
            return response([
                'message'=>'product already exists'
            ], 400);
        }

        $category = DB::table('categories')->where('id', $fields['category_id'])->first();
        if(empty($category)) {
            return response([
                'message'=>'category does not exist'
            ], 400);
        }

        if($fields['quantity_stock'] < 0) {
            return response([
                'message'=>'quantity stock must be greater than 0'
            ], 400);
        }

        $record = Product::find($id);

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
        $record = Product::find($id);

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
