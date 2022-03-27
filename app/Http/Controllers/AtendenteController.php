<?php

namespace App\Http\Controllers;

use App\Enums\AgendamentoSituacaoEnum;
use App\Models\Agendamento;
use App\Models\AssociativaPerfilEstabelecimento;
use App\Models\Endereco;
use App\Models\Estabelecimento;
use App\Models\Perfil;
use App\Models\Usuario;
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
            'agendamentos.medico_perfil_id as medico_id',
            'usuarios.nome as paciente_nome',
            'usuarios.foto as paciente_foto',
            'estabelecimentos.id as estabelecimento_id',
            'estabelecimentos.nome as estabelecimento_nome'
        )
        ->orderBy('agendamentos.data', 'desc')
        ->get();

        $medicosIds = $agendamentos->pluck('medico_id')->toArray();

        $medicos = Usuario::query()
        ->join('perfis', 'perfis.usuario_id', 'usuarios.id')
        ->where('perfis.tipo', 'medico')
        ->whereIn('perfis.id', $medicosIds)
        ->select('usuarios.nome', 'perfis.id as medico_perfil_id')
        ->get()->pluck('nome', 'medico_perfil_id')->toArray();
        
        $response = [
            'agendamentos' => array_values($agendamentos->where('situacao', AgendamentoSituacaoEnum::AGENDADO)->toArray()??[]),
            'paciente_na_espera' => array_values($agendamentos->where('situacao', AgendamentoSituacaoEnum::NA_ESPERA)->toArray()??[]),
            'paciente_em_procedimento' => array_values($agendamentos->where('situacao', AgendamentoSituacaoEnum::EM_REALIZACAO)->toArray()??[]),
            'emergencias' => array_values($agendamentos->where('situacao', AgendamentoSituacaoEnum::EMERGENCIA)->toArray()??[])
        ];

        
        foreach($response as &$resp){
            foreach($resp as &$__res){
                $__res['medico_nome'] = $medicos[$__res['medico_id']] ?? 'Desconhecido';
            }
        }
    
        return response()->json($response);
    }

    public function pacienteByDoc(Request $request):JsonResponse{
        if(!$this->usuarioEhAtendente()){
            return HelperService::defaultResponseJson("Usuário não tem permissão de acesso a esta consulta.", 403, false);
        }
        $request = $request->all();
        $docTipo = $request['doc_tipo']??null;
        $docNumero = $request['doc_numero']??null;

        if(!$docTipo || !$docNumero){
            return HelperService::defaultResponseJson("Requisição mal formatada.", 406, false);
        }

        $paciente = Perfil::query()
        ->join('usuarios', 'usuarios.id', 'perfis.usuario_id')
        ->where('doc_tipo', $docTipo)
        ->where('doc_numero', $docNumero)
        ->where('ativo', true)
        ->without('usuario')
        ->select(
            'perfis.id as perfil_id',
            'perfis.tipo',
            'usuarios.id as usuario_id',
            'usuarios.email',
            'usuarios.doc_tipo',
            'usuarios.doc_numero',
            'usuarios.nome',
            'usuarios.foto'
        )
        ->first();

        if($paciente){
            $enderecos = Endereco::query()
            ->join('associativa_enderecos', 'associativa_enderecos.endereco_id', 'enderecos.id')
            ->where('associativa_enderecos.usuario_id', $paciente["usuario_id"])
            ->get()->toArray();
            $paciente['enderecos'] = $enderecos;

            $estabelecimentos = AssociativaPerfilEstabelecimento::query()
            ->join('estabelecimentos', 'estabelecimentos.id', 'associativa_perfil_estabelecimento.estabelecimento_id')
            ->where('associativa_perfil_estabelecimento.perfil_id', $paciente["perfil_id"])
            ->select('estabelecimentos.*')
            ->get()->toArray();
            $paciente['estabelecimentos'] = $estabelecimentos;

            return response()->json($paciente);
        }
        return response()->json([], 404);
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
        $agendamento->situacao = AgendamentoSituacaoEnum::EMERGENCIA === $request['situacao'] ? AgendamentoSituacaoEnum::EMERGENCIA : AgendamentoSituacaoEnum::AGENDADO; // '0 -> Agendado, 1 -> Na espera, 2 -> Em realização, 3 -> Realizado, 4 -> Não realizado', 5 -> 'Emergencia'
        $agendamento->data = $request['data']??now()->toDateTimeString();
        $agendamento->observacoes = $request['observacoes'];
        $agendamento->estabelecimento_id = $request['estabelecimento_id'];
        $agendamento->exame = $request['exame'];
        $agendamento->paciente_endereco_id = $request['local_paciente_id'];

        $agendamento->save();
        return response()->json($agendamento->toArray());
    }

    public function alterarSituacaoAgendamento(Agendamento $agendamento, int $situacao){
        if(!$this->usuarioEhAtendente()){
            return HelperService::defaultResponseJson("Usuário não tem permissão de acesso a esta consulta.", 401, false);
        }
        $newSituacao = null;
        switch($situacao){
            case 0:
                $newSituacao = AgendamentoSituacaoEnum::AGENDADO;
            break;
            case 1:
                $newSituacao = AgendamentoSituacaoEnum::NA_ESPERA;
            break;
            case 2:
                $newSituacao = AgendamentoSituacaoEnum::EM_REALIZACAO;
            break;
            case 3:
                $newSituacao = AgendamentoSituacaoEnum::REALIZADO;
            break;
            case 4:
                $newSituacao = AgendamentoSituacaoEnum::NAO_REALIZADO;
            break;
            case 5:
                $newSituacao = AgendamentoSituacaoEnum::EMERGENCIA;
            break;
            default:
                $newSituacao = AgendamentoSituacaoEnum::AGENDADO;
        }

        $agendamento->situacao = $newSituacao;
        $agendamento->save();
        return response()->json($agendamento->toArray(), 200);
    }

    public function usuarioEhAtendente():bool{
        $perfil = Perfil::where('usuario_id', auth()->user()->id)->where('tipo', 'atendente')->first();
        $this->atendente_perfil_id = $perfil ? $perfil->id : null; 
        return $perfil ? true : false;
    }
    
}

 
