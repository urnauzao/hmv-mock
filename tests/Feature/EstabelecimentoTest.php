<?php

namespace Tests\Feature;

use App\Models\Estabelecimento;
use App\Services\EstabelecimentoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EstabelecimentoTest extends TestCase
{
    # php artisan test --filter EstabelecimentoTest::test_create_estabelecimento
    public function test_create_estabelecimento()
    {
        $antes = Estabelecimento::query()->count();
        EstabelecimentoService::mock();
        $depois = Estabelecimento::query()->count();
        $this->assertGreaterThan($antes, $depois);
    }
}
