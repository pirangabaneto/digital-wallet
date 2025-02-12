<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /** @test 
     * Criar um usuário com dados válidos
    */
    public function it_creates_a_user_with_valid_data()
    {
        $userData = [
            'name' => 'José Pirangaba',
            'email' => 'jose@example.com',
            'cpf' => '00000000000',
            'password' => bcrypt('password123'),
        ];

        $user = User::create($userData);
        
        $this->assertDatabaseHas('users', [
            'email' => 'jose@example.com',
        ]);
        
        $this->assertEquals('José Pirangaba', $user->name);
    }

    /** @test 
     * Autenticar usuário com credenciais corretas
    */
    public function it_authenticates_a_user_with_valid_credentials()
    {
        $user = User::factory()->create([
            'name' => 'José Pirangaba',
            'email' => 'jose@example.com',
            'cpf' => '00000000000',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'jose@example.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect('/dashboard');
    }

    /** @test 
     * Falha ao criar um usuário sem um e-mail válido
    */
    public function it_fails_to_create_user_without_valid_email()
    {
        // Dados inválidos para o e-mail
        $userData = [
            'name' => 'José Pirangaba',
            'email' => 'invalid-email', // E-mail inválido
            'cpf' => '11036013421',
            'password' => bcrypt('password123'),
        ];

        // Enviar requisição para criar o usuário
        $response = $this->post(route('register'), $userData);

        // Verificar se a validação falhou e se a resposta contém erros
        $response->assertSessionHasErrors('email');
    }
}
