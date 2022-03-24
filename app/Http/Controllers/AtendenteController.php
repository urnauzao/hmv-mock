<?php

namespace App\Http\Controllers;

use App\Models\Perfil;
use App\Services\HelperService;
use Illuminate\Http\JsonResponse;

class AtendenteController extends Controller
{
    public function metricas(Perfil $perfil):JsonResponse{
        if($perfil->usuario_id !== auth()->user()->id){
            return HelperService::defaultResponseJson("Usuário não tem acesso a este perfil.", 403, false);
        }
    }
}
