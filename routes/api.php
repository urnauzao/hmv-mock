<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AtendenteController;
use App\Http\Controllers\EnderecoController;
use App\Http\Controllers\HabitoSaudeController;
use App\Http\Controllers\MedicoController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\QuestionarioEmergenciaController;
use App\Http\Controllers\SocorristaController;
use App\Http\Controllers\UsuarioController;
use App\Models\Perfil;
use App\Models\Usuario;
use Illuminate\Http\Request;
use App\Models\Estabelecimento;
use App\Services\PerfilService;
use App\Services\UsuarioService;
use App\Services\EnderecoService;
use App\Models\AssociativaEndereco;
use App\Services\AgendamentoService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Services\EstabelecimentoService;
use App\Services\PerguntaEmergenciaService;
use App\Services\AssociativaEnderecoService;
use App\Services\QuestionarioEmergenciaService;
use Illuminate\Validation\ValidationException;

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

// /api/

//LOGIN é FEITO AQUI
Route::post('/sanctum/token', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'device_name' => 'required',
    ]);
 
    $user = Usuario::where('email', $request->email)->first();
 
    if (! $user || ! Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }
 
    return $user->createToken($request->device_name)->plainTextToken;
});

Route::middleware('auth:sanctum')->group(function () {

    // Route::get('/usuarios', function(Request $request){
    //     return UsuarioService::getAll(true);
    // });

    Route::prefix('usuario')->group(function () {
        // retorna o usuário e seus perfis
        Route::get('/dados', [UsuarioController::class, 'index']);

        // retorna um usuário específico
        Route::get('/{id}', function($id){
            return UsuarioService::findJson($id??0);
        })->where('id', '[0-9]+');
    });

    Route::prefix('paciente')->group(function () {
        // retorna métricas de um usuário como históricos de agendamento, questionários... 
        Route::get('/metricas/{perfil}', [PacienteController::class, 'metricas']);
        // abre um chamado de emergencia
        Route::post('/chamado_emergencia/{perfil}', [PacienteController::class, 'chamado_emergencia']);
    });

    Route::prefix('questionario_emergencia')->group(function(){
        Route::get('/novo/{perfil}', [QuestionarioEmergenciaController::class, 'index']);
        Route::post('/novo/{perfil}', [QuestionarioEmergenciaController::class, 'save']);
    });

    Route::prefix('habito_saude')->group(function(){
        Route::get('/novo/{perfil}', [HabitoSaudeController::class, 'index']);
        Route::post('/novo/{perfil}', [HabitoSaudeController::class, 'save']);
    });

    Route::prefix('endereco')->group(function(){
        Route::get('/meus', [EnderecoController::class, 'myEnderecos']);
    });

    Route::prefix('medico')->group(function(){
        Route::get('/metricas/{perfil}', [MedicoController::class, 'metricas']);
        Route::get('/questionario_emergencia/{perfil}', [MedicoController::class, 'verQuestionarioEmergencia']);
        Route::get('/habito_saude/{perfil}', [MedicoController::class, 'verHabitoSaude']);
        Route::get('/historico/{perfil}', [MedicoController::class, 'verHistorico']);
        Route::post('/atendimento/{perfil}', [MedicoController::class, 'concluirAtendimento']);
    });

    Route::prefix('socorrista')->group(function(){
        Route::get('/metricas/{perfil}', [SocorristaController::class, 'metricas']);
    });

    Route::prefix('atendente')->group(function(){
        Route::get('/metricas/{perfil}', [AtendenteController::class, 'metricas']);
    });

    Route::prefix('admin')->group(function(){
        Route::get('/metricas/{perfil}', [AdminController::class, 'metricas']);
    });



});



/*** PRA CIMA ESTÁ VALENDO ^^^^ */



// Route::get('/usuarios', function(Request $request){
//     return UsuarioService::getAll(true);
// });
// Route::prefix('usuario')->group(function () {
//     Route::get('/{id}', function($id){
//         return UsuarioService::findJson($id??0);
//     });
// });


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

Route::get('/agendamentos', function(Request $request){
    return AgendamentoService::getAll(true);
});
Route::prefix('agendamento')->group(function () {
    Route::get('/paciente/{perfil}', function(Perfil $perfil){
        return AgendamentoService::getAllByPerfilPaciente($perfil);
    });
    Route::get('/medico/{perfil}', function(Perfil $perfil){
        return AgendamentoService::getAllByPerfilMedico($perfil);
    });
    Route::get('/estabelecimento/{estabelecimento}', function(Estabelecimento $estabelecimento){
        return AgendamentoService::getAllByEstabelecimento($estabelecimento);
    });
});


Route::get('/permissoes-perfis', function(Request $request){
    // return PermissoesPerfisService::getAll(true);
});
