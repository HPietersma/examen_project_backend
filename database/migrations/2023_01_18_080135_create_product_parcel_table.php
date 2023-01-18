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
        Schema::create('product_parcel', function (Blueprint $table) {
            $table->comment('');
            $table->bigIncrements('id');
            $table->integer('amount');
            $table->unsignedBigInteger('parcel_id')->index('product_parcel_parcel_id_foreign');
            $table->unsignedBigInteger('product_id')->index('product_parcel_product_id_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_parcel');
    }
};
