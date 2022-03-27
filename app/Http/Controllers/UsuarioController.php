<?php

namespace App\Http\Controllers;

use App\Models\AssociativaEndereco;
use App\Models\AssociativaPerfilEstabelecimento;
use App\Models\Endereco;
use App\Models\Estabelecimento;
use App\Models\Perfil;
use App\Models\Usuario;
use App\Services\HelperService;
use App\Services\PerfilService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function index(Request $request):JsonResponse{
        try {
            $usuario = auth()->user()->toArray();
        } catch (\Throwable $th) {
            return response()->json([], 401);
        }

        $perfis = PerfilService::findByUsuario($usuario['id']);
        $usuario['perfis'] = $perfis;
        return response()->json($usuario);
    }
    
    public function register(Request $request):JsonResponse{
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $request = $request->all();

        $usuario = new Usuario();
        $usuario["email"] = $request["email"];
        $usuario["password"] = Hash::make($request["password"]);
        $usuario["doc_tipo"] = $request["doc_tipo"];
        $usuario["doc_numero"] = $request["doc_numero"];
        $usuario["ativo"] = true;
        $usuario["nome"] = $request["nome"];
        $usuario["foto"] = $request["foto"];

        $perfil = new Perfil();
        $perfil['tipo'] = "paciente";

        $endereco = new Endereco();
        $endereco["nome"] = $request["endereco"]["nome"];
        $endereco["tipo"] = $request["endereco"]["tipo"];
        $endereco["logradouro"] = $request["endereco"]["logradouro"];
        $endereco["cep"] = $request["endereco"]["cep"];
        $endereco["numero"] = $request["endereco"]["numero"];
        $endereco["cidade"] = $request["endereco"]["cidade"];
        $endereco["estado"] = $request["endereco"]["estado"];
        $endereco["pais"] = "Brasil"; //$request["endereco"]["pais"];
        $endereco["complemento"] = $request["endereco"]["complemento"];

        try {
            if($usuario->save()){
                $perfil['usuario_id'] = $usuario->id;
                $perfil->save();
                $endereco->save();
                AssociativaEndereco::insert(['endereco_id' => $endereco->id, 'usuario_id' => $usuario->id, 'created_at' => now()->toDateTimeString()]);
                $estabelecimento = Estabelecimento::query()->find(5);
                AssociativaPerfilEstabelecimento::insert(['perfil_id' => $perfil->id,'estabelecimento_id' => $estabelecimento->id, 'created_at' => now()->toDateTimeString()]);
                return response()->json([], 201);
            }
        } catch (\Throwable $th) {
            return HelperService::defaultResponseJson("Erro ao salvar usuário", 400, false, ['th' => $th]);
        }
    }
}

