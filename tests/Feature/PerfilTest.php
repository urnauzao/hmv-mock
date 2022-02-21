<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Usuario;
use App\Services\PerfilService;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PerfilTest extends TestCase
{
    # php artisan test --filter PerfilTest::test_create_perfil
    public function test_create_perfil()
    {
        PerfilService::mock();
        $usuarios_sem_perfil = Usuario::query()
        ->leftJoin('perfis', 'perfis.usuario_id', 'usuarios.id')
        ->whereNull('perfis.id')->select('usuarios.*')->get();
        $this->assertEmpty($usuarios_sem_perfil->toArray());
    }
}
