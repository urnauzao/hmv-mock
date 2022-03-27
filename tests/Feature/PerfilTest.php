<?php

namespace Tests\Feature;

use App\Models\Perfil;
use Tests\TestCase;
use App\Models\Usuario;
use App\Services\PerfilService;
use App\Services\UsuarioService;

class PerfilTest extends TestCase
{
    public $usuario_id = 0;
    public $perfis = ['paciente', 'medico', 'atendente', 'socorrista', 'admin'];

    # php artisan test --filter PerfilTest::test_create_perfil
    public function test_create_perfil()
    {
        PerfilService::mock();
        $usuarios_sem_perfil = Usuario::query()
        ->leftJoin('perfis', 'perfis.usuario_id', 'usuarios.id')
        ->whereNull('perfis.id')->select('usuarios.*')->get();
        $this->assertEmpty($usuarios_sem_perfil->toArray());

    }

    # php artisan test --filter PerfilTest::test_add_perfil_medico
    public function test_add_perfil_medico(){
        $usuario_id = UsuarioService::mock()->id;
        $this->assertEmpty(Perfil::query()->where('usuario_id', $usuario_id)->where('tipo', $this->perfis[1])->get());
        Perfil::query()->insert(['usuario_id' => $usuario_id, 'tipo'=> $this->perfis[1]]);
        $this->assertNotEmpty(Perfil::query()->where('usuario_id', $usuario_id)->where('tipo', $this->perfis[1])->first());
    }

    # php artisan test --filter PerfilTest::test_add_perfil_atendente
    public function test_add_perfil_atendente(){
        $usuario_id = UsuarioService::mock()->id;
        $this->assertEmpty(Perfil::query()->where('usuario_id', $usuario_id)->where('tipo', $this->perfis[2])->get()->toArray());
        Perfil::query()->insert(['usuario_id' => $usuario_id, 'tipo'=> $this->perfis[2]]);
        $this->assertNotEmpty(Perfil::query()->where('usuario_id', $usuario_id)->where('tipo', $this->perfis[2])->get()->toArray());
    }

    # php artisan test --filter PerfilTest::test_add_perfil_socorrista
    public function test_add_perfil_socorrista(){
        $usuario_id = UsuarioService::mock()->id;
        $this->assertEmpty(Perfil::query()->where('usuario_id', $usuario_id)->where('tipo', $this->perfis[3])->get()->toArray());
        Perfil::query()->insert(['usuario_id' => $usuario_id, 'tipo'=> $this->perfis[3]]);
        $this->assertNotEmpty(Perfil::query()->where('usuario_id', $usuario_id)->where('tipo', $this->perfis[3])->get()->toArray());
    }
    
    # php artisan test --filter PerfilTest::test_add_perfil_admin
    public function test_add_perfil_admin(){
        $usuario_id = UsuarioService::mock()->id;
        $this->assertEmpty(Perfil::query()->where('usuario_id', $usuario_id)->where('tipo', $this->perfis[4])->get()->toArray());
        Perfil::query()->insert(['usuario_id' => $usuario_id, 'tipo'=> $this->perfis[4]]);
        $this->assertNotEmpty(Perfil::query()->where('usuario_id', $usuario_id)->where('tipo', $this->perfis[4])->get()->toArray());
    }
}
