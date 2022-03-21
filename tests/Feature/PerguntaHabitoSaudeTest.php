<?php

namespace Tests\Feature;

use App\Models\PerguntaHabitoSaude;
use App\Services\PerguntaHabitoSaudeService;
use Tests\TestCase;

class PerguntaHabitoSaudeTest extends TestCase
{
    # php artisan test --filter PerguntaHabitoSaudeTest::test_create_pergunta_emergencia
    public function test_create_pergunta_emergencia()
    {
        $antes = PerguntaHabitoSaude::query()->count();
        PerguntaHabitoSaudeService::mock();
        $depois = PerguntaHabitoSaude::query()->count();
        $this->assertGreaterThan($antes, $depois);
    }
}
