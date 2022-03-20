<?php

namespace App\Http\Controllers;

use App\Models\Perfil;
use App\Models\Agendamento;
use App\Models\HabitoSaude;
use App\Services\HelperService;
use Illuminate\Routing\Controller;
use App\Models\HistoricoAtendimento;
use App\Models\QuestionarioEmergencia;
use Illuminate\Http\JsonResponse;

class PacienteController extends Controller
{
    public function metricas(Perfil $perfil):JsonResponse{
        if($perfil->usuario_id !== auth()->user()->id){
            return HelperService::defaultResponseJson("Usuário não tem acesso a este perfil.", 403, false);
        }
        $questionarios = QuestionarioEmergencia::query()->select('created_at', 'id')->where('perfil_id', $perfil->id)->latest()->get();
        $habitos = HabitoSaude::query()->select('created_at', 'id')->where('perfil_id', $perfil->id)->latest()->get();
        $historicos = HistoricoAtendimento::query()->select('created_at', 'id')->where('paciente_perfil_id', $perfil->id)->latest()->get();
        $agendamentos = Agendamento::query()->with('estabelecimento')->where('paciente_perfil_id', $perfil->id)->latest()->get();
        $response = [
            'questionarios_emergencia' => ['total'=> $questionarios?->count() ?? 0 ],
            'habitos' => [ 'total'=> $habitos?->count() ?? 0 ],
            'historicos' => [ 'total'=> $historicos?->count() ?? 0 ],
            'agendamentos' => [ 'total'=> $agendamentos?->count() ?? 0 ],
        ];

        if($response['questionarios_emergencia']['total']){
            $response['questionarios_emergencia']['ultimo'] = $questionarios->first()->created_at->toDateString();
        }else{
            $response['questionarios_emergencia']['ultimo'] = null;
        }
        if($response['habitos']['total']){
            $response['habitos']['ultimo'] = $habitos->first()->created_at->toDateString();
        }else{
            $response['habitos']['ultimo'] = null;
        }
        if($response['historicos']['total']){
            $response['historicos']['ultimo'] = $historicos->first()->created_at->toDateString();
        }else{
            $response['historicos']['ultimo'] = null;
        }
        $response['agendamentos']['lista'] = $agendamentos?->take(21)?->toArray() ?? [];

        return response()->json($response, 200);
    }
}
