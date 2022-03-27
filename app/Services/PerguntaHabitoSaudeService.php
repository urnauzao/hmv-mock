<?php

namespace App\Services;

use App\Models\PerguntaHabitoSaude;

class PerguntaHabitoSaudeService
{
    public static function construtor_pergunta(string $pergunta, int $prioridade){
        return [
            'pergunta' => $pergunta,
            'prioridade' => $prioridade,
            'tipo' => 'string',
            'opcoes' => null, 
            'obrigatoria' => false,
            'ativo' => true
        ];
    }

    public static function perguntas(){
        return [
            self::construtor_pergunta("Você usa óculos/lentes?", 2),
            self::construtor_pergunta("Você tem pressão alta?", 7),
            self::construtor_pergunta("Você usa remédio para pressão?", 7),
            self::construtor_pergunta("Você usa remédios por outros motivos?", 6),
            self::construtor_pergunta("Você já teve Hepatite?", 7),
            self::construtor_pergunta("Você já foi operado?", 5),
            self::construtor_pergunta("Já quebrou algum osso?", 4),
            self::construtor_pergunta("Você Fuma?", 8),
            self::construtor_pergunta("Se não fuma, já fumou alguma vez?", 4),
            self::construtor_pergunta("Você bebe?", 8),
            self::construtor_pergunta("Usa algum tipo de droga ilicita?", 8),
            self::construtor_pergunta("Você come frutas/vegetais?", 3),
            self::construtor_pergunta("Você faz atividades físicas?", 3),
            self::construtor_pergunta("Você tem diabete?", 7),
            self::construtor_pergunta("Possuí algum tipo de alergia?", 8)
        ];


    }
    public static function mock(){
        $perguntas = self::perguntas();
        PerguntaHabitoSaude::query()->insert($perguntas);
    }

    public static function getAll(bool $json = true){
        $result = PerguntaHabitoSaude::query()->latest()->get();
        if($json){
            return response()->json($result->toArray());
        }else{
            return $result;
        }
    }

    public static function findJson(int $id){
        try {
            $result = PerguntaHabitoSaude::query()->find($id);
            if($result){
                return response()->json($result->toArray());
            }else{
                return response()->json(["mensagem" => "Não encontrado"], 404);
            }
        } catch (\Throwable $th) {
            return response()->json(["mensagem" => "Id inválido"], 400);
        }
    }
}
