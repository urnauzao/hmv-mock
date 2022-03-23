<?php

namespace App\Http\Controllers;

use App\Models\Endereco;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EnderecoController extends Controller
{
    public function myEnderecos(Request $request):JsonResponse{
        $usuario = auth()->user();
        $enderecos = Endereco::query()->join('associativa_enderecos', 'enderecos.id', 'associativa_enderecos.endereco_id')
        ->where('associativa_enderecos.usuario_id', $usuario->id)
        ->select('enderecos.*')
        ->get();
        return response()->json($enderecos->toArray());
    }
}
