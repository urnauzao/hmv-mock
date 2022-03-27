<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Usuario;
use App\Services\UsuarioService;

class UsuarioTest extends TestCase
{

    public $token = "216|KNy2k7EQFyFUs8XJm0MtuK223TKJUAHan0qBMhuk";

    # php artisan test --filter UsuarioTest::test_create_usuario
    public function test_create_usuario()
    {
        $antes = Usuario::query()->count();
        UsuarioService::mock();
        $depois = Usuario::query()->count();
        $this->assertGreaterThan($antes, $depois);
    }

    # php artisan test --filter UsuarioTest::test_consulta_usuario
    public function test_consulta_usuario()
    {
        $response = $this
        ->withToken($this->token )
        ->get('/api/usuario/dados');

        $response->assertOk();
        $response->assertValid();
    }
    
}
