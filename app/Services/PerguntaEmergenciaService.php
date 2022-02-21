<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\PerguntaEmergencia;

class PerguntaEmergenciaService
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
            self::construtor_pergunta('Apresenta sinais de pulsação, respiração ou batimentos cardíacos?',10),
            self::construtor_pergunta('O paciente está consciente?', 10),
            self::construtor_pergunta('Apresenta sangramento?',9),
            self::construtor_pergunta('Apresenta fatura exposta?',9),
            self::construtor_pergunta('Apresenta sinal de palidez?',8),
            self::construtor_pergunta('Apresenta febra alta?',6),
            self::construtor_pergunta('Apresenta falta de ar?',6),
            self::construtor_pergunta('Apresenta sinais de hipotermia?',6),
            self::construtor_pergunta('Apresenta dor intensa em alguma parte do corpo? Qual ou quais',2),
            self::construtor_pergunta('Apresenta sinal de desorientação?', 2),
            self::construtor_pergunta('Apresenta sinais de embriagues ou de uso de entorpecentes?', 2),
            self::construtor_pergunta('Apresenta sinais de alergia?', 8),
            self::construtor_pergunta('Paciente consegue se comunicar?', 7)
        ];
    }
    public static function mock(){
        $perguntas = self::perguntas();
        PerguntaEmergencia::query()->insert($perguntas);
    }

    public static function getAll(bool $json = true){
        $result = PerguntaEmergencia::query()->latest()->get();
        if($json){
            return response()->json($result->toArray());
        }else{
            return $result;
        }
    }

    public static function findJson(int $id){
        try {
            $result = PerguntaEmergencia::query()->find($id);
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
