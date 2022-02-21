<?php

use App\Models\AssociativaEndereco;
use App\Services\AssociativaEnderecoService;
use App\Services\EnderecoService;
use App\Services\EstabelecimentoService;
use App\Services\PerfilService;
use App\Services\PerguntaEmergenciaService;
use App\Services\QuestionarioEmergenciaService;
use Illuminate\Http\Request;
use App\Services\UsuarioService;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/usuarios', function(Request $request){
    return UsuarioService::getAll(true);
});
Route::prefix('usuario')->group(function () {
    Route::get('/{id}', function($id){
        return UsuarioService::findJson($id??0);
    });
});


Route::get('/associativa-enderecos', function(Request $request){
    return AssociativaEnderecoService::getAll(true);
});
Route::prefix('associativa-endereco')->group(function () {
    Route::get('/{id}', function($id){
        return AssociativaEndereco::findJson($id??0);
    });
});

Route::get('/enderecos', function(Request $request){
    return EnderecoService::getAll(true);
});
Route::prefix('endereco')->group(function () {
    Route::get('/{id}', function($id){
        return EnderecoService::findJson($id??0);
    });
});

Route::get('/estabelecimentos', function(Request $request){
    return EstabelecimentoService::getAll(true);
});
Route::prefix('estabelecimento')->group(function () {
    Route::get('/{id}', function($id){
        return EstabelecimentoService::findJson($id??0);
    });
});

Route::get('/perfis', function(Request $request){
    return PerfilService::getAll(true);
});
Route::prefix('perfil')->group(function () {
    Route::get('/{id}', function($id){
        return PerfilService::findJson($id??0);
    });
});

Route::get('/perguntas-emergencia', function(Request $request){
    return PerguntaEmergenciaService::getAll(true);
});
Route::prefix('pergunta-emergencia')->group(function () {
    Route::get('/{id}', function($id){
        return PerguntaEmergenciaService::findJson($id??0);
    });
});

Route::get('/questionarios-emergencia', function(Request $request){
    return QuestionarioEmergenciaService::getAll(true);
});
Route::prefix('questionario-emergencia')->group(function () {
    Route::get('/{id}', function($id){
        return QuestionarioEmergenciaService::findJson($id??0);
    });
});


Route::get('/permissoes-perfis', function(Request $request){
    // return PermissoesPerfisService::getAll(true);
});
