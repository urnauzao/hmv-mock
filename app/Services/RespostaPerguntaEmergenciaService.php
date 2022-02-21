<?php
namespace App\Services;

use App\Models\Perfil;
use App\Models\PerguntaEmergencia;
use App\Models\QuestionarioEmergencia;
use App\Models\RespostaPerguntaEmergencia;

class RespostaPerguntaEmergenciaService{

    public static function perfis_sem_questionario_emergencia(){
        return Perfil::query()
        ->leftJoin('questionarios_emergencia', 'questionarios_emergencia.perfil_id', 'perfis.id')
        ->where('perfis.tipo', 'paciente')
        ->whereNull('questionarios_emergencia.id')
        ->select('perfis.*')
        ->get();
    }

    public static function questionarios_sem_respostas(){
        return QuestionarioEmergencia::query()
        ->leftJoin('respostas_perguntas_emergencia', 'respostas_perguntas_emergencia.questionario_emergencia_id', 'questionarios_emergencia.id')
        ->whereNull('respostas_perguntas_emergencia.id')
        ->select('questionarios_emergencia.*')
        ->get();
    }

    public static function mock(){
        $perfis_sem_questionario_emergencia = self::perfis_sem_questionario_emergencia();
        if($perfis_sem_questionario_emergencia->count() === 0)
            return;
        
        foreach($perfis_sem_questionario_emergencia as $perfil){
            $questionario_emergencia = new QuestionarioEmergencia();
            $questionario_emergencia->perfil_id = $perfil->id;
            $questionario_emergencia->situacao = 'incompleto';
            $questionario_emergencia->save();
            self::gerar_respostas($questionario_emergencia->id);
            $questionario_emergencia->situacao = 'completo';
            $questionario_emergencia->save();
        }
    }

    public static function gerar_respostas($questionario_emergencia_id){
        $perguntas = PerguntaEmergencia::query()->where('ativo', true)->limit(rand(1, 7))->get();
        foreach($perguntas as $pergunta){
            $resposta = new RespostaPerguntaEmergencia();
            $resposta->questionario_emergencia_id = $questionario_emergencia_id;
            $resposta->pergunta_emergencia_id = $pergunta->id;
            $resposta->resposta_texto = rand(0,1) ? 'Sim' : 'Não';
            $resposta->save();
        }
    }

}

