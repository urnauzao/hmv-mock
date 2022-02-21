<?php

namespace Tests\Feature;

use App\Models\PerguntaEmergencia;
use App\Services\PerguntaEmergenciaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PerguntaEmergenciaTest extends TestCase
{
    # php artisan test --filter PerguntaEmergenciaTest::test_create_pergunta_emergencia
    public function test_create_pergunta_emergencia()
    {
        $antes = PerguntaEmergencia::query()->count();
        PerguntaEmergenciaService::mock();
        $depois = PerguntaEmergencia::query()->count();
        $this->assertGreaterThan($antes, $depois);
    }
}
