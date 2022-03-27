<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Estabelecimento;
use App\Services\AgendamentoService;
use App\Services\PerfilService;
use App\Services\UsuarioService;
use App\Services\PerguntaEmergenciaService;
use App\Services\AssociativaEnderecoService;
use App\Services\RespostaPerguntaEmergenciaService;

class ConstrutorMockTest extends TestCase
{

    # php artisan test --filter ConstrutorMockTest::test_construindo_mocks
    public function test_construindo_mocks()
    {
        $usuarios = 50;
        $estabelecimentos = 0;
        
        if($usuarios > 0)
        for ($i=0; $i < $usuarios ; $i++) { 
            UsuarioService::mock();
        }
        if($estabelecimentos > 0)
        for ($i=0; $i < $estabelecimentos ; $i++) { 
            Estabelecimento::mock();
        }

        PerfilService::mock();
        
        AssociativaEnderecoService::mock();

        PerguntaEmergenciaService::mock();

        RespostaPerguntaEmergenciaService::mock();

        AgendamentoService::mock(rand(15,70));
        
        $this->assertTrue(true);
    }
}
