<?php

namespace App\Services;

use App\Models\Agendamento;
use App\Models\Estabelecimento;
use App\Models\Perfil;
use Faker;

class AgendamentoService
{
    public static function mock(int $total = 10){
        $faker = Faker\Factory::create("pt_BR");
        $situacoes = [
            0 => "Agendado",
            1 => "Na espera",
            2 => "Em realização",
            3 => "Realizado",
            4 => "Não realizado"
        ];
        $estabelecimentos = Estabelecimento::query()->select('id')->get()->pluck('id');
        $medicos = Perfil::query()->where('tipo', 'medico')->select('id')->get()->pluck('id');
        $pacientes = Perfil::query()->where('tipo', 'paciente')->select('id')->get()->pluck('id');

        for ($i=0; $i < $total; $i++) { 
            $agora = rand(0,6)>3 ? false : true;
            $agendamento = new Agendamento();
            $agendamento->paciente_perfil_id = $pacientes->random();  
            $agendamento->medico_perfil_id = $medicos->random();
            if($agora){
                $agendamento->data = now()->subDays(rand(0,3))->addHours(rand(1,4));
                if(now()->toDateString() == $agendamento->data->toDateString()){
                    //consulta de hoje
                    $agendamento->situacao = array_keys($situacoes)[rand(0, count($situacoes)-1)];
                }else{
                    //consulta de ontem...
                    $agendamento->situacao = rand(0,7)>1 ? 3 : 4;
                }
            }else{
                $agendamento->data = now()->addDays(rand(0,50))->addHours(rand(1,4));
                $agendamento->situacao = 0;
            }
            $agendamento->observacoes = rand(0,6) > 4 ? "Paciente apresentou dificuldades durante o processo" : null;
            $agendamento->estabelecimento_id =  $estabelecimentos->random();
            $agendamento->save();
        }
    }

    public static function getAll(bool $json = true){
        $result = Agendamento::query()->latest()->get();
        if($json){
            return response()->json($result->toArray());
        }else{
            return $result;
        }
    }

    public static function findJson(int $id){
        try {
            $result = Agendamento::query()->find($id);
            if($result){
                return response()->json($result->toArray());
            }else{
                return response()->json(["msg" => "Não encontrado"], 404);
            }
        } catch (\Throwable $th) {
            return response()->json(["msg" => "Id inválido"], 400);
        }
    }

    public static function getAllByPerfilPaciente(Perfil $perfil){
        try {
            $result = Agendamento::query()
                ->where(['paciente_perfil_id' => $perfil->id])
                ->orderByDesc('data')->get();
            if($result){
                return response()->json($result->toArray());
            }else{
                return response()->json(["msg" => "Não encontrado"], 404);
            }
        } catch (\Throwable $th) {
            return response()->json(["msg" => "Id inválido"], 400);
        }
    }
    public static function getAllByPerfilMedico(Perfil $perfil){
        try {
            $result = Agendamento::query()
                ->where(['medico_perfil_id' => $perfil->id])
                ->orderByDesc('data')->get();
            if($result){
                return response()->json($result->toArray());
            }else{
                return response()->json(["msg" => "Não encontrado"], 404);
            }
        } catch (\Throwable $th) {
            return response()->json(["msg" => "Id inválido"], 400);
        }
    }
    public static function getAllByEstabelecimento(Estabelecimento $estabelecimento){
        try {
            $result = Agendamento::query()
                ->where(['estabelecimento_id' => $estabelecimento->id])
                ->orderByDesc('data')->get();
            if($result){
                return response()->json($result->toArray());
            }else{
                return response()->json(["msg" => "Não encontrado"], 404);
            }
        } catch (\Throwable $th) {
            return response()->json(["msg" => "Id inválido"], 400);
        }
    }
}
