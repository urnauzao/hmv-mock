<?php

namespace Tests\Feature;

use Tests\TestCase;

class AtendenteTest extends TestCase
{
    public $endpoint = "/api/atendente/metricas";
    public $endpointAgendamento = "/api/atendente/agendamento/novo/";
    
    public $token = "304|58WOeMzXwnECicBVY24Tl57aJcjEs2bwbRDATtZw";
    public $tokenOutroPerfil = "301|BMwvc41fuEY8vpvJXcnpFcke8sAMVKVlYer6TPIA";

    # php artisan test --filter AtendenteTest::test_consulta_metricas_atendente_com_sucesso
    public function test_consulta_metricas_atendente_com_sucesso()
    {

        $response = $this
        ->withToken($this->token )
        ->get($this -> endpoint );

        $response->assertOk();
        $response->assertValid();
    }

    # php artisan test --filter AtendenteTest::test_consulta_metricas_atendente_usuario_sem_permissao
    public function test_consulta_metricas_atendente_usuario_sem_permissao()
    {
        $response = $this
        ->withToken($this->tokenOutroPerfil )
        ->get($this -> endpoint );

        $response->assertForbidden();
        $response->assertJson(['status' => false]);
        $response->assertJson(['mensagem' => "Usuário não tem permissão de acesso a esta consulta."]);
        $response->assertJson(['detalhes' => []]);
    }


}
