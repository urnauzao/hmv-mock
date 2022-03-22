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
        $insertsRespostasUpdate = [];
        foreach($fields as $pergunta_id => $__resposta){
            $resposta = $respostas_ultimo_habito_saude_em_aberto[$pergunta_id][0] ?? new RespostaPerguntaHabitoSaude();
            $resposta['habito_saude_id'] = $ultimo_habito_saude_em_aberto['id'];
            $resposta['pergunta_habito_saude_id'] = $pergunta_id;
            $resposta['resposta_texto'] = $__resposta;
            $resposta['resposta'] = null;
            if($resposta['id']){
                $insertsRespostasUpdate[] = is_array($resposta)? $resposta : $resposta->toArray();
            }else{
                $insertsRespostas[] = is_array($resposta)? $resposta : $resposta->toArray();
            }
        }

        try {
            $totalSalvo = RespostaPerguntaHabitoSaude::query()->insert($insertsRespostas);
            foreach($insertsRespostasUpdate as $update){
                $up = RespostaPerguntaHabitoSaude::query()->find($update['id']);
                $up['habito_saude_id'] = $update['habito_saude_id'];
                $up['pergunta_habito_saude_id'] = $update['pergunta_habito_saude_id'];
                $up['resposta_texto'] = $update['resposta_texto'];
                $up['resposta'] = $update['resposta'];
                $up->save();
            }
            if($totalSalvo || !empty($insertsRespostasUpdate)){
                $ultimo_habito_saude_em_aberto->situacao = 'completo';
                $ultimo_habito_saude_em_aberto->save();
                return HelperService::defaultResponseJson("Suas respostas para o hábito de saúde foram salvas com sucesso.", 200, true, ['habito_saude_id' => $ultimo_habito_saude_em_aberto->id]);
            }else{
                return HelperService::defaultResponseJson("Não foi possível salvar seu hábito de saúdo.", 400, false);
            }
        } catch (\Throwable $th) {
            dd($th,$insertsRespostas);
            return HelperService::defaultResponseJson("Algum dos valores informações é inconsistente.", 500, false);
        }
    }
}
