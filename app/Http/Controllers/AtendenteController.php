<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use App\Models\AssociativaPerfilEstabelecimento;
use App\Models\Estabelecimento;
use App\Models\Perfil;
use App\Services\HelperService;
use App\Services\PerfilService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AtendenteController extends Controller
{
    public $atendente_perfil_id;

    public function metricas():JsonResponse{
        if(!$this->usuarioEhAtendente()){
            return HelperService::defaultResponseJson("Usuário não tem permissão de acesso a esta consulta.", 403, false);
        }

        $estabelecimento = AssociativaPerfilEstabelecimento::query()
        ->where('perfil_id', $this->atendente_perfil_id)
        ->get();

        $estabelecimentoIds = $estabelecimento->pluck('estabelecimento_id')->toArray();

        $agendamentos = Agendamento::query()
        ->join('perfis', 'perfis.id', 'agendamentos.paciente_perfil_id')
        ->join('usuarios', 'usuarios.id', 'perfis.usuario_id')
        ->join('estabelecimentos', 'estabelecimentos.id', 'agendamentos.estabelecimento_id')
        ->where('agendamentos.data', '<', now()->addDays(2)->toDateString())
        ->whereIn('agendamentos.estabelecimento_id', $estabelecimentoIds)
        ->where('perfis.tipo', 'paciente')
        ->whereNotIn('agendamentos.situacao', ['3', '4'])
        ->without(['estabelecimento', 'perfil'])
        ->select(
            'agendamentos.id as agendamento_id',
            'agendamentos.data as agendamento_data',
            'agendamentos.observacoes as agendamento_observacoes',
            'agendamentos.exame as exame',
            'agendamentos.situacao as situacao',
            'usuarios.nome as paciente_nome',
            'usuarios.foto as paciente_foto',
            'estabelecimentos.id as estabelecimento_id',
            'estabelecimentos.nome as estabelecimento_nome'
        )
        ->orderBy('agendamentos.data', 'desc')
        ->get();
        
        $response = [
            'agendamentos' => array_values($agendamentos->where('situacao', '0')->toArray()??[]),
            'paciente_na_espera' => array_values($agendamentos->where('situacao', '1')->toArray()??[]),
            'paciente_em_procedimento' => array_values($agendamentos->where('situacao', '2')->toArray()??[]),
            'emergencias' => array_values($agendamentos->where('situacao', '5')->toArray()??[])
        ];

        return response()->json($response);
    }

    public function agendamentoDefinirMedico(Agendamento $agendamento, Perfil $perfil):JsonResponse{
        if(!$this->usuarioEhAtendente()){
            return HelperService::defaultResponseJson("Usuário não tem permissão de acesso a esta consulta.", 403, false);
        }

        if(PerfilService::perfilEhMedico($perfil->id)){
            return HelperService::defaultResponseJson("Perfil de médico informado não é válido.", 400, false);
        }

        $agendamento->medico_perfil_id = $perfil->id;
        $agendamento->medico_perfil_id = $this->atendente_perfil_id;
        $agendamento->save();
        return response()->json($agendamento->toArray(), 200);
    }

    public function estabelecimentosParaAgendamento(Perfil $perfil):JsonResponse{
        if(!$this->usuarioEhAtendente()){
            return HelperService::defaultResponseJson("Usuário não tem permissão de acesso a esta consulta.", 401, false);
        }

        $estabelecimentos = AssociativaPerfilEstabelecimento::query()
        ->join('estabelecimentos', 'estabelecimentos.id', 'associativa_perfil_estabelecimento.estabelecimento_id')
        ->where('associativa_perfil_estabelecimento.perfil_id', $perfil->id)
        ->select('estabelecimentos.*')
        ->get();

        return response()->json($estabelecimentos->toArray());
    }
    
    public function novoAgendamento(Request $request, Perfil $perfil):JsonResponse{
        if(!$this->usuarioEhAtendente()){
            return HelperService::defaultResponseJson("Usuário não tem permissão de acesso a esta consulta.", 401, false);
        }
        $request = $request->all();
        $agendamento = new Agendamento();
        $agendamento->paciente_perfil_id = $perfil->id;
        $agendamento->atendente_perfil_id = $this->atendente_perfil_id;
        // $agendamento->medico_perfil_id;
        // $agendamento->socorrista_perfil_id
        $agendamento->situacao = '0'; // '0 -> Agendado, 1 -> Na espera, 2 -> Em realização, 3 -> Realizado, 4 -> Não realizado', 5 -> 'Emergencia'
        $agendamento->data = $request['data'];
        $agendamento->observacoes = $request['observacoes'];
        $agendamento->estabelecimento_id = $request['estabelecimento_id'];
        $agendamento->exame = $request['exame'];
        // $agendamento->paciente_endereco_id;

        $agendamento->save();
        return response()->json($agendamento->toArray());
    }

    public function alterarSituacaoAgendamento(Agendamento $agendamento, int $situacao){
        if(!$this->usuarioEhAtendente()){
            return HelperService::defaultResponseJson("Usuário não tem permissão de acesso a esta consulta.", 401, false);
        }

        $agendamento->situacao = $situacao;
        $agendamento->save();
        return response()->json($agendamento->toArray(), 200);
    }

    public function usuarioEhAtendente():bool{
        $perfil = Perfil::where('usuario_id', auth()->user()->id)->where('tipo', 'atendente')->first();
        $this->atendente_perfil_id = $perfil ? $perfil->id : null; 
        return $perfil ? true : false;
    }
    
}

 
