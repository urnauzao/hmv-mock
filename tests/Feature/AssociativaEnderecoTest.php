<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Usuario;
use App\Services\AssociativaEnderecoService;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AssociativaEnderecoTest extends TestCase
{
    # php artisan test --filter AssociativaEnderecoTest::test_create_associativa_endereco
    public function test_create_associativa_endereco()
    {
        AssociativaEnderecoService::mock();
        $usuarios_sem_endereco = AssociativaEnderecoService::usuarios_sem_endereco();
        $this->assertEmpty($usuarios_sem_endereco->toArray());

        $estabelecimentos_sem_endereco = AssociativaEnderecoService::estabelecimentos_sem_endereco();
        $this->assertEmpty($estabelecimentos_sem_endereco->toArray());

        $enderecos_sem_associacao = AssociativaEnderecoService::enderecos_sem_associacao();
        $this->assertEmpty($enderecos_sem_associacao->toArray());
    }
}
