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
            'email' => 'somemail@gmail.com',
            'password' => Hash::make('12345678'),
            'admin' => 1,
            'name' => 'Sergey'
        ]);

        UserInfo::create([
            'user_id' => $user->id,
            'phone' => '+7 890 234 3445',
            'job' => 'Administrator',
            'address' => 'Irkutsk, Russia'
        ]);
    }
}
