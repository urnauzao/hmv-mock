<?php

namespace App\Http\Controllers;

use App\Models\Perfil;
use App\Models\PerguntaEmergencia;
use App\Models\QuestionarioEmergencia;
use App\Models\RespostaPerguntaEmergencia;
use App\Services\HelperService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuestionarioEmergenciaController extends Controller
{
    public function index(Perfil $perfil):JsonResponse{
        if($perfil->usuario_id !== auth()->user()->id){
            return HelperService::defaultResponseJson("Usuário não tem acesso a este perfil.", 403, false);
        }

        $ultimo_questionario_em_aberto = QuestionarioEmergencia::query()
        ->where(['perfil_id'=>$perfil->id])
        ->where('situacao','!=', 'completo')
        ->latest()
        ->first();

        $respostas_ultimo_questionario_em_aberto = null;
        if($ultimo_questionario_em_aberto){
            $respostas_ultimo_questionario_em_aberto = RespostaPerguntaEmergencia::query()
            ->where('questionario_emergencia_id', $ultimo_questionario_em_aberto->id)
            ->get();
        }
        
        $perguntas_questionario = PerguntaEmergencia::query()
        ->where('ativo', true)
        ->orderBy('prioridade', 'desc')
        ->get();

        return response()->json([
            'ultimo_questionario_em_aberto' => $ultimo_questionario_em_aberto,
            'respostas_ultimo_questionario_em_aberto' => $respostas_ultimo_questionario_em_aberto,
            'perguntas_questionario' => $perguntas_questionario
        ]);
    }

    public function save(Perfil $perfil, Request $request):JsonResponse{
        if($perfil->usuario_id !== auth()->user()->id){
            return HelperService::defaultResponseJson("Usuário não tem acesso a este perfil.", 403, false);
        }

        $fields = $request->all();
        if(empty($fields)){
            return HelperService::defaultResponseJson("Você deve responder à alguma pergunta para poder concluir.", 400, false);
        }

        $ultimo_questionario_em_aberto = QuestionarioEmergencia::query()
        ->where(['perfil_id'=>$perfil->id])
        ->where('situacao','!=', 'completo')
        ->latest()
        ->first();

        $respostas_ultimo_questionario_em_aberto = null;
        if($ultimo_questionario_em_aberto){
            $respostas_ultimo_questionario_em_aberto = RespostaPerguntaEmergencia::query()
            ->where('questionario_emergencia_id', $ultimo_questionario_em_aberto->id)
            ->get();
        }else{
            $ultimo_questionario_em_aberto =  new QuestionarioEmergencia();
            $ultimo_questionario_em_aberto->situacao = 'incompleto';
            $ultimo_questionario_em_aberto->perfil_id = $perfil->id;
            $ultimo_questionario_em_aberto->save();
        }

        if($respostas_ultimo_questionario_em_aberto){
            $respostas_ultimo_questionario_em_aberto = $respostas_ultimo_questionario_em_aberto->groupBy('pergunta_emergencia_id');
        }else{
            $respostas_ultimo_questionario_em_aberto = [];
        }

        $insertsRespostas = [];
        $insertsRespostasUpdate = [];
        foreach($fields as $pergunta_id => $__resposta){
            $resposta = $respostas_ultimo_questionario_em_aberto[$pergunta_id][0] ?? new RespostaPerguntaEmergencia();
            $resposta['questionario_emergencia_id'] = $ultimo_questionario_em_aberto['id'];
            $resposta['pergunta_emergencia_id'] = $pergunta_id;
            $resposta['resposta_texto'] = $__resposta;
            $resposta['resposta'] = null;
            if($resposta['id']){
                $insertsRespostasUpdate[] = is_array($resposta)? $resposta : $resposta->toArray();
            }else{
                $insertsRespostas[] = is_array($resposta)? $resposta : $resposta->toArray();
            }
        }
        try {
            $totalSalvo = RespostaPerguntaEmergencia::query()->insert($insertsRespostas);
            foreach($insertsRespostasUpdate as $update){
                $up = RespostaPerguntaEmergencia::query()->find($update['id']);
                $up['questionario_emergencia_id'] = $update['questionario_emergencia_id'];
                $up['pergunta_emergencia_id'] = $update['pergunta_emergencia_id'];
                $up['resposta_texto'] = $update['resposta_texto'];
                $up['resposta'] = $update['resposta'];
                $up->save();
            }
            if($totalSalvo || !empty($insertsRespostasUpdate)){
                $ultimo_questionario_em_aberto->situacao = 'completo';
                $ultimo_questionario_em_aberto->save();
                return HelperService::defaultResponseJson("Suas respostas para o questionário foram salvas com sucesso.", 200, true, ['questionario_id' => $ultimo_questionario_em_aberto->id]);
            }else{
                return HelperService::defaultResponseJson("Não foi possível salvar seu questionário de emergência.", 400, false);
            }
        } catch (\Throwable $th) {
            // dd($th,$insertsRespostas);
            return HelperService::defaultResponseJson("Algum dos valores informações é inconsistente.", 500, false);
        }

    }
}
