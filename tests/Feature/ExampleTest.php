<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function testMainPage()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertviewIs('index');
        $response->assertSee('Список пользователей');
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testRegisterPage()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertSee('Регистрация');
    }

     /**
     * @test
     */
    public function testUserRegister()
    {
        $response = $this->post(route('user.register'), [
            'email' => 'test@gmail.com',
            'password' => '12345678'
        ]);

        $response->assertRedirect(route('login'));
    }

    public function testLoginPage()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }
}
