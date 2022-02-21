<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Endereco;
use App\Services\EnderecoService;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EnderecoTest extends TestCase
{
    # php artisan test --filter EnderecoTest::test_create_endereco
    public function test_create_endereco()
    {
        $antes = Endereco::query()->count();
        EnderecoService::mock();
        $depois = Endereco::query()->count();
        $this->assertGreaterThan($antes, $depois);
    }
}
