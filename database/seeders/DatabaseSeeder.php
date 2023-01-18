<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        // $this->call(FamiliesTableSeeder::class);
        // $this->call(ParcelsTableSeeder::class);
        // $this->call(ProductsTableSeeder::class);
        // $this->call(ProductParcelTableSeeder::class);
        // $this->call(UsersTableSeeder::class);
        DB::unprepared(File::get(base_path('database/seeders/voedselbank_insertONLY_aangepastRoles.sql')));
    }
}
