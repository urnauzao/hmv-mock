<?php

namespace Tests\Feature;

use App\Models\RespostaPerguntaEmergencia;
use App\Services\RespostaPerguntaEmergenciaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RespostaPerguntaEmergenciaTest extends TestCase
{
    # php artisan test --filter RespostaPerguntaEmergenciaTest::test_create_resposta_pergunta_emergencia
    public function test_create_resposta_pergunta_emergencia()
    {
        RespostaPerguntaEmergenciaService::mock();
        $perfis_sem_questionario_emergencia = RespostaPerguntaEmergenciaService::perfis_sem_questionario_emergencia();
        $questionarios_sem_respostas = RespostaPerguntaEmergenciaService::questionarios_sem_respostas();
        $this->assertEmpty($perfis_sem_questionario_emergencia->toArray());
        $this->assertEmpty($questionarios_sem_respostas->toArray());
    }
}
