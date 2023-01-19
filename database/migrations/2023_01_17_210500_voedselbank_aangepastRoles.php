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

         /**
         * Table: rollen
         */
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('role_name');
        });

        /**
         * Table: users
         */
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('email', 255)->unique();
            $table->string('password', 255);

            $table->unsignedBigInteger('role_id')->default(1);

            $table->timestamp('email_verified_at')->default(NULL)->nullable();
            $table->rememberToken();

            $table->timestamps();
            $table->softDeletes();

            // extra toegevoegd: foreign key
            $table->foreign('role_id')->references('id')->on('roles');
        });


        /**
         * Table: families
         */
        Schema::create('families', function (Blueprint $table) {
            $table->id();

            $table->string('familyname', 255)->default(NULL)->nullable();
            $table->string('address', 255)->default(NULL)->nullable();
            $table->string('homenr', 255)->default(NULL)->nullable();
            $table->string('zipcode', 255)->default(NULL)->nullable();
            $table->string('city', 255)->default(NULL)->nullable();
            $table->string('phone', 255)->default(NULL)->nullable();
            $table->string('email', 255)->default(NULL)->nullable();

            $table->integer('amountAdults')->default(NULL)->nullable();
            $table->integer('amountChildren')->default(NULL)->nullable();
            $table->integer('amountBabies')->default(NULL)->nullable();

            $table->softDeletes()->default(NULL);
            $table->timestamps();
        });

        /**
         * Table: parcels
         */
        Schema::create('parcels', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('family_id')->default(NULL)->nullable();
            $table->unsignedBigInteger('user_id')->default(NULL)->nullable();
            $table->date('collected_at')->default(NULL)->nullable();

            $table->timestamps();

            $table->foreign('collected_at')->references('collected_at')->on('families');
            $table->foreign('family_id')->references('id')->on('families');
            $table->foreign('user_id')->references('id')->on('users');
        });

        /**
         * Table: products
         */
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->string('name', 255)->default(NULL)->nullable();
            $table->string('description', 255)->default(NULL)->nullable();
            $table->string('category_id', 255)->default(NULL)->nullable();

            $table->integer('quantity_stock')->default(NULL)->nullable();

            $table->softDeletes()->default(NULL);
            $table->timestamps();
        });

        /**
         * Table: product_parcel
         */
        Schema::create('product_parcel', function (Blueprint $table) {
            $table->id();

            $table->integer('amount');

            $table->unsignedBigInteger('parcel_id');
            $table->unsignedBigInteger('product_id');

            $table->timestamps();

            $table->foreign('parcel_id')->references('id')->on('parcels');
            $table->foreign('product_id')->references('id')->on('products');
        });


        Schema::create('categories', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('content');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
