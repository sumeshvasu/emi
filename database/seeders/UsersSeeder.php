<?php
/**
 * Seeder : UsersSeeder.
 *
 * This file used to seed Users
 *
 * @author Sumesh K V <sumeshvasu@gmail.com>
 *
 * @version 1.0
 */

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //DB::table('users')->truncate();

        User::create([
            'name' => 'developer',
            'email' => 'developer@emi.com',
            'password' => Hash::make('Test@Tuna123#'),
            'email_verified_at' => Carbon::now(),
        ]);
    }
}
