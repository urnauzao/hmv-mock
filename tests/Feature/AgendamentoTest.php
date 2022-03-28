<?php

namespace Tests\Feature;

use App\Models\Agendamento;
use Tests\TestCase;
use App\Services\AgendamentoService;

class AgendamentoTest extends TestCase
{
    public $endpointAgendamento = "/api/atendente/agendamento/novo/";
    
    public $token = "304|58WOeMzXwnECicBVY24Tl57aJcjEs2bwbRDATtZw";

    # php artisan test --filter AgendamentoTest::test_create_agendamento
    public function test_create_agendamento()
    {
        $total_agendamentos_antes = Agendamento::query()->count();
        $mocks = AgendamentoService::mock(1);
        $total_agendamentos_depois = Agendamento::query()->count();

        $this->assertGreaterThan($total_agendamentos_antes, $total_agendamentos_depois);
        
    }

    # php artisan test --filter AgendamentoTest::test_realizar_agendamento_com_sucesso
    public function test_realizar_agendamento_com_sucesso()
    {
        $data  = [
            'data' => '2022-04-01',
            'observacoes' => 'Retorno de outro atendimento.',
            'estabelecimento_id'  => '1',
            'exame'  => 'Retorno - Ultrassom'
        ];
        $id = '12';
        $rota = $this->endpointAgendamento.$id;

        $response = $this
        ->withToken($this->token )
        ->post($rota, $data);

        $response->assertOk();
    }

}
