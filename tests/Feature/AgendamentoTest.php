<?php

namespace Tests\Feature;

use App\Models\Agendamento;
use Tests\TestCase;
use App\Services\AgendamentoService;

class AgendamentoTest extends TestCase
{
    # php artisan test --filter AgendamentoTest::test_create_agendamento
    public function test_create_agendamento()
    {
        $total_agendamentos_antes = Agendamento::query()->count();
        $mocks = AgendamentoService::mock(1);
        $total_agendamentos_depois = Agendamento::query()->count();

        $this->assertGreaterThan($total_agendamentos_antes, $total_agendamentos_depois);
        
    }
}
