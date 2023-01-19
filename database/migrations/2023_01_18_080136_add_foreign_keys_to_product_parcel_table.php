<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_parcel', function (Blueprint $table) {
            $table->foreign(['parcel_id'])->references(['id'])->on('parcels');
            $table->foreign(['product_id'])->references(['id'])->on('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_parcel', function (Blueprint $table) {
            $table->dropForeign('product_parcel_parcel_id_foreign');
            $table->dropForeign('product_parcel_product_id_foreign');
        });
    }
};
