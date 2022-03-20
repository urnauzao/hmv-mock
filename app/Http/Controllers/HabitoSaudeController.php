<?php

namespace App\Http\Controllers;

use App\Models\HabitoSaude;
use App\Models\Perfil;
use App\Models\PerguntaHabitoSaude;
use App\Models\RespostaPerguntaHabitoSaude;
use App\Services\HelperService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HabitoSaudeController extends Controller
{
    public function index(Perfil $perfil):JsonResponse{
        if($perfil->usuario_id !== auth()->user()->id){
            return HelperService::defaultResponseJson("Usuário não tem acesso a este perfil.", 403, false);
        }

        $ultimo_habito_saude_em_aberto = HabitoSaude::query()
        ->where(['perfil_id'=>$perfil->id])
        ->where('situacao','!=', 'completo')
        ->latest()
        ->first();

        $respostas_ultimo_habito_saude_em_aberto = null;
        if($ultimo_habito_saude_em_aberto){
            $respostas_ultimo_habito_saude_em_aberto = RespostaPerguntaHabitoSaude::query()
            ->where('habito_saude_id', $ultimo_habito_saude_em_aberto->id)
            ->get();
        }
        
        $perguntas_habito_saude = PerguntaHabitoSaude::query()
        ->where('ativo', true)
        ->orderBy('prioridade', 'desc')
        ->get();

        return response()->json([
            'ultimo_habito_saude_em_aberto' => $ultimo_habito_saude_em_aberto,
            'respostas_ultimo_habito_saude_em_aberto' => $respostas_ultimo_habito_saude_em_aberto,
            'perguntas_habito_saude' => $perguntas_habito_saude
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

        $ultimo_habito_saude_em_aberto = HabitoSaude::query()
        ->where(['perfil_id'=>$perfil->id])
        ->where('situacao','!=', 'completo')
        ->latest()
        ->first();

        $respostas_ultimo_habito_saude_em_aberto = null;
        if($ultimo_habito_saude_em_aberto){
            $respostas_ultimo_habito_saude_em_aberto = RespostaPerguntaHabitoSaude::query()
            ->where('habito_saude_id', $ultimo_habito_saude_em_aberto->id)
            ->get();
        }else{
            $ultimo_habito_saude_em_aberto = new HabitoSaude();
            $ultimo_habito_saude_em_aberto->situacao = 'incompleto';
            $ultimo_habito_saude_em_aberto->perfil_id = $perfil->id;
            $ultimo_habito_saude_em_aberto->save();
        }

        if($respostas_ultimo_habito_saude_em_aberto){
            $respostas_ultimo_habito_saude_em_aberto = $respostas_ultimo_habito_saude_em_aberto->groupBy('pergunta_habito_saude_id')->toArray();
        }else{
            $respostas_ultimo_habito_saude_em_aberto = [];
        }

        $insertsRespostas = [];
        foreach($fields as $pergunta_id => $__resposta){
            $resposta = $respostas_ultimo_habito_saude_em_aberto[$pergunta_id] ?? new RespostaPerguntaHabitoSaude();
            $resposta['habito_saude_id'] = $ultimo_habito_saude_em_aberto->id;
            $resposta['pergunta_habito_saude_id'] = $pergunta_id;
            $resposta['resposta_texto'] = $__resposta;
            $resposta['resposta'] = null;
            $insertsRespostas[] = is_array($resposta)? $resposta : $resposta->toArray();
        }

        try {
            $totalSalvo = RespostaPerguntaHabitoSaude::query()->insert($insertsRespostas);
            if($totalSalvo){
                $ultimo_habito_saude_em_aberto->situacao = 'completo';
                $ultimo_habito_saude_em_aberto->save();
                return HelperService::defaultResponseJson("Suas respostas para o hábito de saúde foram salvas com sucesso.", 200, true, ['habito_saude_id' => $ultimo_habito_saude_em_aberto->id]);
            }else{
                return HelperService::defaultResponseJson("Você foi possível salvar seu hábito de saúdo.", 400, false);
            }
        } catch (\Throwable $th) {
            return HelperService::defaultResponseJson("Algum dos valores informações é inconsistente.", 500, false);
        }
    }
}
