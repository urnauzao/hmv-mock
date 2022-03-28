<?php

namespace Tests\Feature;

use App\Models\Perfil;
use Tests\TestCase;

class MedicoTest extends TestCase
{
    public $endpoint = "/api/medico/metricas/";
    public $endpointChamadoEmergencia = "/api/medico/chamado_emergencia/";
    
    public $token = "301|BMwvc41fuEY8vpvJXcnpFcke8sAMVKVlYer6TPIA";
    public $tokenOutroPerfil = "290|6BU6bCHAWlRl8F7w2bLn3zn49wdTuiLJhOLVgbnw";

    # php artisan test --filter MedicoTest::test_consulta_metricas_medico_com_sucesso
    public function test_consulta_metricas_medico_com_sucesso()
    {
        $medico = $this->getUsuario('medico');
        $rota = $this->endpoint.$medico->id;
        
        $response = $this
        ->withToken($this->token )
        ->get($rota );

        $response->assertOk();
        $response->assertValid();
    }

    # php artisan test --filter MedicoTest::test_consulta_metricas_medico_usuario_sem_permissao
    public function test_consulta_metricas_medico_usuario_sem_permissao()
    {
        $medico = $this->getUsuario('medico');
        $rota = $this->endpoint.$medico->id;
        
        $response = $this
        ->withToken($this->tokenOutroPerfil )
        ->get($rota );

        $response->assertForbidden();
        $response->assertJson(['status' => false]);
        $response->assertJson(['mensagem' => "Usuário não tem acesso a este perfil."]);
        $response->assertJson(['detalhes' => []]);
    }

    private function getUsuario(String $tipo) {
        $perfil = Perfil::query()
                    ->where('tipo', $tipo)
                    ->first();

        $this->assertNotEmpty($perfil, "Perfil não encontrado");

        return $perfil;
    }

}
