<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Perfil;

class PacienteTest extends TestCase
{
    public $endpoint = "/api/paciente/metricas/";
    public $endpointChamadoEmergencia = "/api/paciente/chamado_emergencia/";

    public $token = "290|6BU6bCHAWlRl8F7w2bLn3zn49wdTuiLJhOLVgbnw";
    public $tokenOutroPerfil = "273|ofInpl6dbivW109HpxC8YWJwlH3FDWQVxmtIuTom";

    # php artisan test --filter PacienteTest::test_consulta_metricas_paciente_com_sucesso
    public function test_consulta_metricas_paciente_com_sucesso()
    {
        $paciente = $this->getUsuario('paciente');
        $rota = $this->endpoint.$paciente->id;
        $response = $this
        ->withToken($this->token )
        ->get($rota );

        $response->assertOk();
        $response->assertValid();
    }

    # php artisan test --filter PacienteTest::test_consulta_metricas_paciente_usuario_sem_permissao
    public function test_consulta_metricas_paciente_usuario_sem_permissao()
    {
        $paciente = $this->getUsuario('paciente');
        $rota = $this->endpoint.$paciente->id;
        $response = $this
        ->withToken($this->tokenOutroPerfil )
        ->get($rota );

        $response->assertForbidden();
        $response->assertJson(['status' => false]);
        $response->assertJson(['mensagem' => "Usuário não tem acesso a este perfil."]);
        $response->assertJson(['detalhes' => []]);
    }

    # php artisan test --filter PacienteTest::test_abrir_chamado_emergencia
    public function test_abrir_chamado_emergencia()
    {
        $data  = [
            'id' => 51,
            'nome' => 'Corona Ltda.',
            'tipo' => 'rua',
            'logradouro' => 'Rua Camilo',
            'cep' => '66085-767',
            'numero' => '21872',
            'cidade' => 'Eduardo do Norte',
            'estado' => 'MA',
            'pais' => 'Brasil',
            'complemento' => null,
            'deleted_at' => null,
            'created_at' => '2022-02-21T02 =>08 =>32.000000Z',
            'updated_at' => '2022-02-21T02 =>08 =>32.000000Z',
            'name' => 'Corona Ltda. | Rua Camilo, 21872',
            'code' => 51
        ];
        $paciente = '12';
        $rota = $this->endpointChamadoEmergencia.$paciente;

        $response = $this
        ->withToken($this->token )
        ->post($rota, $data);

        $response->assertOk();
    }

    private function getUsuario(String $tipo) {
        $perfil = Perfil::query()
                    ->where('tipo', $tipo)
                    ->first();

        $this->assertNotEmpty($perfil, "Perfil não encontrado");

        return $perfil;
    }

    
}
