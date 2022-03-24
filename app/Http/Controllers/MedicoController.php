<?php

namespace App\Http\Controllers;

use App\Enums\AgendamentoSituacaoEnum;
use App\Models\Agendamento;
use App\Models\AssociativaPerfilEstabelecimento;
use App\Models\Estabelecimento;
use App\Models\HabitoSaude;
use App\Models\Perfil;
use App\Models\QuestionarioEmergencia;
use App\Services\HelperService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MedicoController extends Controller
{
    public function metricas(Perfil $perfil):JsonResponse{
        if($perfil->usuario_id !== auth()->user()->id){
            return HelperService::defaultResponseJson("Usuário não tem acesso a este perfil.", 403, false);
        }
        $response = [
            "meus_estabelecimentos" => [],
            "minhas_emergencias" => [],
            "outras_emergencias" => [],
            "agendamentos" => []
        ];

        /** MEUS ESTABELECIMENTOS */
        $meus_estabelecimentos = AssociativaPerfilEstabelecimento::query()
        ->join('estabelecimentos', 'estabelecimentos.id', 'associativa_perfil_estabelecimento.estabelecimento_id')
        ->where('associativa_perfil_estabelecimento.perfil_id', $perfil->id)
        ->select('estabelecimentos.nome','estabelecimentos.id')
        ->get();
        $meus_estabelecimentos = $meus_estabelecimentos->pluck('id', 'nome')->flip()->toArray(); 
        $response["meus_estabelecimentos"] =  $meus_estabelecimentos;

        /** MEUS AGENDAMENTOS E EMERGENCIAS */
        $agendamentos = Agendamento::query()
        ->whereIn('estabelecimento_id', array_keys($meus_estabelecimentos))
        ->where('medico_perfil_id', $perfil->id )
        ->whereNotIn('situacao', [3,4])
        ->where('data', '>', now()->subDay()->toDateString())
        ->where('data', '<', now()->addDays(7))
        ->orWhere(function($query) use ($meus_estabelecimentos) {
                $query->whereIn('estabelecimento_id', array_keys($meus_estabelecimentos))
                    ->whereNull('medico_perfil_id')
                    ->whereNotIn('situacao', [3,4])
                    ->where('data', '>', now()->subDay()->toDateString())
                    ->where('data', '<', now()->addDays(7));
        })->get();

        $agendamentos = $agendamentos->groupBy('medico_perfil_id');
        foreach($agendamentos as $estabId => $agendamentos){ 
            if($estabId == ""){
                $response['outras_emergencias'] = $agendamentos->toArray();
                continue;
            }
            if($estabId){
                $response['minhas_emergencias'] = [];
                $response['agendamentos'] = [];
                foreach($agendamentos as $agendamento){
                    if($agendamento['situacao'] === AgendamentoSituacaoEnum::EMERGENCIA){
                        $response['minhas_emergencias'][] = $agendamento;
                    }else{
                        $response['agendamentos'][] = $agendamento;
                    }
                }
            }
        }
        return response()->json($response, 200);
    }

    public function verQuestionarioEmergencia(Perfil $perfil):JsonResponse{
        if(!$this->usuarioEhMedico()){
            return HelperService::defaultResponseJson("Usuário não tem este tipo de permissão.", 403, false);
        }

        $questionarios = QuestionarioEmergencia::query()
        ->join('respostas_perguntas_emergencia', 'respostas_perguntas_emergencia.questionario_emergencia_id', 'questionarios_emergencia.id' )
        ->join('perguntas_emergencia', 'perguntas_emergencia.id', 'respostas_perguntas_emergencia.pergunta_emergencia_id' )
        ->where('questionarios_emergencia.perfil_id', $perfil->id)
        ->select('questionarios_emergencia.id as questionario_emergencia_id', 'questionarios_emergencia.created_at','questionarios_emergencia.situacao', 'perguntas_emergencia.pergunta', 'respostas_perguntas_emergencia.resposta_texto')
        ->get()->groupBy('questionario_emergencia_id');

        return response()->json($questionarios->toArray());
    }

    public function usuarioEhMedico():bool{
        return Perfil::where('usuario_id', auth()->user()->id)->where('tipo', 'medico')->first() ? true : false;
    }

    public function verHabitoSaude(Perfil $perfil):JsonResponse{
        if(!$this->usuarioEhMedico()){
            return HelperService::defaultResponseJson("Usuário não tem este tipo de permissão.", 403, false);
        }

        $habitos = HabitoSaude::query()
        ->join('respostas_perguntas_habitos_saude', 'respostas_perguntas_habitos_saude.habito_saude_id', 'habitos_saude.id' )
        ->join('perguntas_habitos_saude', 'perguntas_habitos_saude.id', 'respostas_perguntas_habitos_saude.pergunta_habito_saude_id' )
        ->where('habitos_saude.perfil_id', $perfil->id)
        ->select('habitos_saude.id as habito_saude_id', 'habitos_saude.created_at','habitos_saude.situacao', 'perguntas_habitos_saude.pergunta', 'respostas_perguntas_habitos_saude.resposta_texto')
        ->get()->groupBy('habito_saude_id');

        return response()->json($habitos->toArray());
    }
    public function verHistorico(){
        dd('em desenvolvimento');
        // select * from historicos_atendimento ha 
        // inner join perfis p on p.id = ha.medico_perfil_id and p.tipo = 'medico'
        // inner join usuarios u on u.id = p.usuario_id 
        // where 
        // paciente_perfil_id = 19;
    }
    public function concluirAtendimento(){
        dd('em desenvolvimento');
    }
}

