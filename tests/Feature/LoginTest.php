<?php

namespace Tests\Feature;

use App\Models\Perfil;
use App\Models\Usuario;
use Tests\TestCase;

class LoginTest extends TestCase
{
    public $endpoint = "/api/sanctum/token";
    
    # php artisan test --filter LoginTest::test_login_perfil_paciente_com_sucesso
    public function test_login_perfil_paciente_com_sucesso()
    {
        $user = $this->getUsuario('paciente');

        $data  = $this->getBodyLogin($user->email, "password");
        
        $response = $this
                    ->post($this -> endpoint, $data );

        $response->assertOk();
        $this->assertNotEmpty($response->getContent(), "Validar token no body");
        
    }

    # php artisan test --filter LoginTest::test_login_perfil_medico_com_sucesso
    public function test_login_perfil_medico_com_sucesso()
    {
        $user = $this->getUsuario('medico');

        $data  = $this->getBodyLogin($user->email, "password");
        
        $response = $this
                    ->post($this -> endpoint, $data );

        $response->assertOk();
        $this->assertNotEmpty($response->getContent(), "Validar token no body");
        
    }

    # php artisan test --filter LoginTest::test_login_com_senha_invalida
    public function test_login_com_senha_invalida()
    {
        $user = $this->getUsuario('paciente');

        $data = $this->getBodyLogin($user->email, "passwords");

        $response = $this->post($this -> endpoint, $data );

        $response->assertUnauthorized();
        $response->assertJson(['mensagem' => 'Não autorizado.']);
        
    }

    # php artisan test --filter LoginTest::test_login_com_usuario_inexistente
    public function test_login_com_usuario_inexistente()
    {
        $data = $this->getBodyLogin("test@gmail.com");

        $response = $this->post($this -> endpoint, $data );

        $response->assertUnauthorized();
        $response->assertJson(['mensagem' => 'Não autorizado.']);
        
    }

    private function getUsuario(String $tipo) {
        $perfil = Perfil::query()
                    ->where('tipo', $tipo)
                    ->first();

        $this->assertNotEmpty($perfil, "Perfil não encontrado");
        
        $user = Usuario::query()
                    ->where('id', $perfil->usuario_id)
                    ->first();

        $this->assertNotEmpty($user, "Usuario não encontrado");

        return $user;
    }

    private function getBodyLogin(String $email, String $password = 'password') {
        
        $data  = [
            'email' => $email,
            'password' => $password,
            'device_name' => "test_device"
        ];

        return $data;
    }

}
