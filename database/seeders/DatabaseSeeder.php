<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (
            $this->command->confirm(
                'Do you wish to refresh migration before seeding, Make sure it will clear all old data?',
                true
            )
        ) {
            $this->command->call('migrate:fresh');
            $this->command->warn(
                PHP_EOL . 'Data deleted, starting from fresh database !!!'
            );

            //disable foreign key check for this connection before running seeders
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            $this->call(UsersSeeder::class);
            $this->call(LoanDetailsSeeder::class);

            $this->command->warn(PHP_EOL);

            //enable foreign key check for this connection before running seeders
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        } else {
            $this->command->warn('Migration stopped!!!' . PHP_EOL);
        }

    }
}
