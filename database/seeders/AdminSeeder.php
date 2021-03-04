<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12345678'),
            'admin' => 1,
            'name' => 'Admin'
        ]);

        UserInfo::create([
            'user_id' => $user->id,
            'phone' => '+7 999 999 99 99',
            'job' => 'Administrator',
            'address' => 'Irkutsk, Russia'
        ]);
    }
}
