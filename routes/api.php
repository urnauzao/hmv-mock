<?php

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
    Route::get('/teste', function(Request $request){
        //auth()->user();
        return UsuarioService::getAll(true);
    });

    Route::get('/usuarios', function(Request $request){
        return UsuarioService::getAll(true);
    });
    Route::prefix('usuario')->group(function () {
        Route::get('/dados', function(){
            try {
                return response()->json(auth()->user()?->toArray());           
            } catch (\Throwable $th) {
                return response()->json(['success' => false, 'msg' => 'nao encontrado'], 404);
            }
        });

        Route::get('/{id}', function($id){
            return UsuarioService::findJson($id??0);
        });
    });
});





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
