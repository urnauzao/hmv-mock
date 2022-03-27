<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Endereco;
use App\Services\EnderecoService;

class EnderecoTest extends TestCase
{
    public $token = "301|BMwvc41fuEY8vpvJXcnpFcke8sAMVKVlYer6TPIA";

    # php artisan test --filter EnderecoTest::test_consulta_endereco_com_sucesso
    public function test_consulta_endereco_com_sucesso()
    {
        $response = $this
        ->withToken($this->token )
        ->get("/api/endereco/meus" );

        $response->assertOk();
        $response->assertValid();
    }

    # php artisan test --filter EnderecoTest::test_create_endereco
    public function test_create_endereco()
    {
        $antes = Endereco::query()->count();
        EnderecoService::mock();
        $depois = Endereco::query()->count();
        $this->assertGreaterThan($antes, $depois);
    }
}
