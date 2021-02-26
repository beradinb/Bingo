<?php

namespace Modules\Bingo\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class SeedBingoPermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Model::unguard();

        // $this->call("OthersTableSeeder");
        Auth::loginUsingId(1);

        // Disable foreign key checks!
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');


        \Artisan::call('auth:permission', [
            'name' => 'bingo',
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
