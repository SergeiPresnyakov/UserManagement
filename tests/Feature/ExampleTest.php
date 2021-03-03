<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ExampleTest extends TestCase
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

    public function test_register_with_correct_credentials_is_successful()
    {
        $response = $this->post(route('user.register'), [
            'email' => 'testemail@mail.com',
            'password' => '12345678'
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('users', ['email' => 'testemail@mail.com']);
        $response->assertRedirect(route('login'));
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
        $response->assertDontSee('Добавить');
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
        $user->admin = 1;
        $user->save();

        $response = $this->post(route('user.login'), [
            'email' => 'testemail@mail.com',
            'password' => '12345678'
        ]);

        $response->assertRedirect(route('index'));
        $response = $this->actingAs($user)->get(route('index'));
        $response->assertSee('Добавить');
    }

    public function test_delete_test_users_after_tests()
    {
        $this->assertDatabaseHas('users', ['email' => 'testemail@mail.com']);
        DB::delete('DELETE FROM users');
        $this->assertDatabaseMissing('users', ['email' => 'testemail@mail.com']);
    }
}
