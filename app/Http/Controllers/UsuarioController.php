<?php

namespace App\Http\Controllers;

use App\Services\PerfilService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
}
