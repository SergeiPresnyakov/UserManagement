<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\ImageService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ExampleTest extends TestCase
{  
    /**
     * @test
     */
    public function testCreatedUserDataInAllTables()
    {
        $data = [
            'email' => 'test2@gmail.com',
            'password' => '12345678'
        ];

        $this->post(route('user.register'), [
            'email' => $data['email'],
            'password' => $data['password']
        ]);

        $user = DB::table('users')->where('email', $data['email'])->first();

        $this->assertDatabaseHas('users', [
            'email' => $data['email'],
            'status' => 'online'
        ]);

        $this->assertDatabaseHas('users_info', [
            'user_id' => $user->id
        ]);
    }
}
