<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SiteFunctionalityTest extends TestCase
{
    public function test_regiser_page_available()
    {
        $response = $this->get(route('register'));
        $response->assertStatus(200);
        $response->assertSee('Регистрация');
    }

    public function test_index_page_available()
    {
        $response = $this->get(route('index'));
        $response->assertStatus(200);
    }

    public function test_wrong_register_email_cause_validation_error()
    {
        $response = $this->post(route('user.register'), [
            'email' => 'notemail',
            'password' => '12345678'
        ]);

        $response->assertSessionHasErrors('email');
        $response->assertStatus(302);
    }

    public function test_register_with_wrong_password_cause_validation_error()
    {
        $response = $this->post(route('user.register'), [
            'email' => 'mail@mail.com',
            'password' => '2'
        ]);

        $response->assertSessionHasErrors('password');
        $response->assertStatus(302);
    }

    public function test_register_admin_with_correct_credentials_is_successful()
    {
        $response = $this->post(route('user.register'), [
            'email' => 'testemail@mail.com',
            'password' => '12345678'
        ]);

        $user = User::first();
        $user->admin = 1;
        $user->save();
        $user->info->update([
            'job' => 'jobTitle',
            'phone' => '+7 999 999 9999',
            'address' => 'Moscow, Russia',
            'vk' => 'vk-url',
            'telegram' => 'telegram-url',
            'instagram' => 'instagram-url'
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('users', ['email' => 'testemail@mail.com']);
        $response->assertRedirect(route('login'));
    }

    public function test_create_simple_user()
    {
        $response = $this->post(route('user.register'), [
            'email' => 'simpleuser@mail.com',
            'password' => '12345678'
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('users', ['email' => 'simpleuser@mail.com']);
    }

    public function test_login_page_is_available()
    {
        $response = $this->get(route('login'));
        $response->assertStatus(200);
        $response->assertSee('Зарегистрироваться');
    }

    public function test_cant_login_with_wrong_email()
    {
        $response = $this->post(route('user.login'), [
            'email' => 'notemail',
            'password' => '12345678'
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_cant_login_with_wrong_password()
    {
        $response = $this->post(route('user.login'), [
            'email' => 'testemail@mail.com',
            'password' => '2'
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertGuest();
    }

    public function test_can_authorize_with_correct_credentials()
    {
        $response = $this->post(route('user.login'), [
            'email' => 'testemail@mail.com',
            'password' => '12345678'
        ]);
        
        $this->assertDatabaseHas('users', ['email' => 'testemail@mail.com']); 
        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('index'));
        $this->assertAuthenticated();
        $response = $this->actingAs(Auth::user())->get(route('index'));
        $response->assertSee('Выйти');
        $response->assertSee('Добавить');
    }

    public function test_simple_user_can_access_his_own_options()
    {
        $response = $this->post(route('user.login'), [
            'email' => 'simpleuser@mail.com',
            'password' => '12345678'
        ]);
        $id = Auth::user()->id;
        
        $this->call('GET', "/user/{$id}/edit")->assertStatus(200);
        $this->call('GET', "/user/{$id}/status")->assertStatus(200);
        $this->call('GET', "/user/{$id}/media")->assertStatus(200);
        $this->call('GET', "/user/{$id}/security")->assertStatus(200);
        $this->call('GET', "/user/{$id}/contacts")->assertStatus(200);
    }

    public function test_simple_user_cant_access_others_options()
    {
        $this->post(route('user.login'), [
            'email' => 'simpleuser@mail.com',
            'password' => '12345678'
        ]);
        $id = Auth::user()->id;
        $admin_id = DB::table('users')
            ->select('id')
            ->where('id', '!=', $id)
            ->first()
            ->id;
        
        $this->call('GET', "/user/{$admin_id}/edit")->assertStatus(302);
        $this->call('GET', "/user/{$admin_id}/status")->assertStatus(302);
        $this->call('GET', "/user/{$admin_id}/media")->assertStatus(302);
        $this->call('GET', "/user/{$admin_id}/security")->assertStatus(302);
        $this->call('GET', "/user/{$admin_id}/contacts")->assertStatus(302);
    }

    public function test_user_successfully_logout()
    {
        $response = $this->post(route('user.login'), [
            'email' => 'testemail@mail.com',
            'password' => '12345678'
        ]);
        $user = Auth::user();
        $this->assertAuthenticated();
        $response = $this->actingAs($user)->get(route('logout'));
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_login_as_admin()
    {
        $user = User::first();

        $response = $this->post(route('user.login'), [
            'email' => 'testemail@mail.com',
            'password' => '12345678'
        ]);

        $response->assertRedirect(route('index'));
        $response = $this->actingAs($user)->get(route('index'));
        $response->assertSee('Добавить');
    }

    public function test_admin_can_see_anyones_options()
    {
        $user = User::first();
        
        $id = $user->id;
        $second_id = DB::table('users')
            ->select('id')
            ->where('id', '!=', $user->id)
            ->first()
            ->id;
 
        $response = $this->post(route('user.login'), [
            'email' => 'testemail@mail.com',
            'password' => '12345678'
        ]);

        $response->assertRedirect(route('index'));
        $this->actingAs($user)->call('GET', "/user/{$id}/edit")->assertSuccessful();
        $this->actingAs($user)->call('GET', "/user/{$second_id}/edit")->assertSuccessful();
        $this->actingAs($user)->call('GET', "/user/{$id}/status")->assertSuccessful();
        $this->actingAs($user)->call('GET', "/user/{$second_id}/status")->assertSuccessful();
        $this->actingAs($user)->call('GET', "/user/{$id}/media")->assertSuccessful();
        $this->actingAs($user)->call('GET', "/user/{$second_id}/media")->assertSuccessful();
        $this->actingAs($user)->call('GET', "/user/{$id}/security")->assertSuccessful();
        $this->actingAs($user)->call('GET', "/user/{$second_id}/security")->assertSuccessful();
        $this->actingAs($user)->call('GET', "/user/{$id}/contacts")->assertSuccessful();
        $this->actingAs($user)->call('GET', "/user/{$second_id}/contacts")->assertSuccessful();
    }

    public function test_admin_can_delete_other_users()
    {
        $user = User::first();
        $second_id = DB::table('users')
            ->select('id')
            ->where('id', '!=', $user->id)
            ->first()
            ->id;
 
        $this->post(route('user.login'), [
            'email' => 'testemail@mail.com',
            'password' => '12345678'
        ]);

        $this->actingAs($user)->call('DELETE', "user/{$second_id}/delete");
        $this->assertDatabaseMissing('users', ['email' => 'simpleuser@mail.com']);
    }

    public function test_delete_test_users_after_tests()
    {
        $this->assertDatabaseHas('users', ['email' => 'testemail@mail.com']);
        DB::delete('DELETE FROM users');
        DB::delete('DELETE FROM users_info');
        $this->assertDatabaseMissing('users', ['email' => 'testemail@mail.com']);
    }
}