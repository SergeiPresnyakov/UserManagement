<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\UserInfo;
use App\Services\ImageService;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Illuminate\Support\Str;

class UserModelTest extends TestCase
{  
    public function test_create_user()
    {
        $id = rand(1, 10);
        $name = 'John Doe';
        $avatar = ImageService::DEFAULT_AVATAR;
        $email = Str::random(10) . '@mail.com';

        $job = 'Programmer';
        $phone = '+7 999 999 99 99';
        $address = 'Moscow, Russia';
        $vk = Str::random(15);
        $telegram = Str::random(15);
        $instagram = Str::random(15);
        
 
        $user = User::factory()->create([
            'name' => $name,
            'id' => $id,
            'avatar' => $avatar,
            'email' => $email,
        ]);

        $info = UserInfo::create([
            'user_id' => $id,
            'job' => $job,
            'phone' => $phone,
            'address' => $address,
            'vk' => $vk,
            'telegram' => $telegram,
            'instagram' => $instagram
        ]);

        $this->assertDatabaseHas('users', ['name' => 'John Doe']);
        $this->assertDatabaseHas('users', ['email' => $email]);
        $this->assertDatabaseHas('users', ['avatar' => $avatar]);

        $this->assertDatabaseHas('users_info', ['user_id' => $id]);
        $this->assertDatabaseHas('users_info', ['job' => $job]);
        $this->assertDatabaseHas('users_info', ['phone' => $phone]);
        $this->assertDatabaseHas('users_info', ['address' => $address]);
        $this->assertDatabaseHas('users_info', ['vk' => $vk]);
        $this->assertDatabaseHas('users_info', ['telegram' => $telegram]);
        $this->assertDatabaseHas('users_info', ['instagram' => $instagram]);

        $users = User::all();
        $this->assertCount(1, $users);     
    }
    
    public function test_get_models_from_database()
    {
        $users = User::all();
        $this->assertCount(1, $users);
        
        $user = User::first();
        $this->assertEquals('John Doe', $user->name);
        $this->assertEquals('Programmer', $user->info->job);
        $this->assertEquals('Moscow, Russia', $user->info->address);
    }

    public function test_models_update_properly()
    {
        $user = User::first();
        $user->update(['name' => 'Jane Coe']);
        $user->info->update(['address' => 'New York, USA']);
        
        $this->assertEquals('Jane Coe', $user->name);
        $this->assertEquals('New York, USA', $user->info->address);
    }
    
    public function test_models_deleting()
    {
        $user = User::first();
        $id = $user->id;
        $info = UserInfo::where('user_id', $id)->first();
        
        $info->destroy($info->id);
        $user->destroy($id);
        
        $this->assertDatabaseMissing('users', ['name' => 'Jane Coe']);
        $this->assertDatabaseMissing('users_info', ['address' => 'New York, USA']);
    }

    public function test_clear_the_database()
    {
        DB::delete('DELETE FROM users');
        DB::delete('DELETE FROM users_info');

        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('users_info', 0);
    }
}
