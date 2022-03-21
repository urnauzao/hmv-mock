<?php

namespace Tests\Feature;

use App\Services\RespostaPerguntaHabitoSaudeService;
use Tests\TestCase;

class RespostaPerguntaHabitoSaudeTest extends TestCase
{
    # php artisan test --filter RespostaPerguntaHabitoSaudeTest::test_create_resposta_pergunta_habito_saude
    public function test_create_resposta_pergunta_habito_saude()
    {
        RespostaPerguntaHabitoSaudeService::mock();
        $perfis_sem_questionario_emergencia = RespostaPerguntaHabitoSaudeService::perfis_sem_habito_saude();
        $questionarios_sem_respostas = RespostaPerguntaHabitoSaudeService::habito_saude_sem_respostas();
        $this->assertEmpty($perfis_sem_questionario_emergencia->toArray());
        $this->assertEmpty($questionarios_sem_respostas->toArray());
    }
}
