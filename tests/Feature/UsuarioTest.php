<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Usuario;
use App\Services\UsuarioService;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsuarioTest extends TestCase
{

    # php artisan test --filter UsuarioTest::test_create_usuario
    public function test_create_usuario()
    {
        $antes = Usuario::query()->count();
        UsuarioService::mock();
        $depois = Usuario::query()->count();
        $this->assertGreaterThan($antes, $depois);
    }
    
}
