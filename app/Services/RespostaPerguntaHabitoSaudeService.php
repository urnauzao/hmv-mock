<?php
namespace App\Services;

use App\Models\HabitoSaude;
use App\Models\Perfil;
use App\Models\PerguntaEmergencia;
use App\Models\PerguntaHabitoSaude;
use App\Models\RespostaPerguntaEmergencia;
use App\Models\RespostaPerguntaHabitoSaude;

class RespostaPerguntaHabitoSaudeService{

    public static function perfis_sem_habito_saude(){
        return Perfil::query()
        ->leftJoin('habitos_saude', 'habitos_saude.perfil_id', 'perfis.id')
        ->where('perfis.tipo', 'paciente')
        ->whereNull('habitos_saude.id')
        ->select('perfis.*')
        ->get();
    }

    public static function habito_saude_sem_respostas(){
        return HabitoSaude::query()
        ->leftJoin('respostas_perguntas_habitos_saude', 'respostas_perguntas_habitos_saude.habito_saude_id', 'habitos_saude.id')
        ->whereNull('respostas_perguntas_habitos_saude.id')
        ->select('habitos_saude.*')
        ->get();
    }

    public static function mock(){
        $perfis_sem_habito_saude = self::perfis_sem_habito_saude();
        if($perfis_sem_habito_saude->count() === 0)
            return;
        
        foreach($perfis_sem_habito_saude as $perfil){
            $habito_saude = new HabitoSaude();
            $habito_saude->perfil_id = $perfil->id;
            $habito_saude->situacao = 'incompleto';
            $habito_saude->save();
            self::gerar_respostas($habito_saude->id);
            $habito_saude->situacao = 'completo';
            $habito_saude->save();
        }
    }

    public static function gerar_respostas($habito_saude_id){
        $perguntas = PerguntaHabitoSaude::query()->where('ativo', true)->limit(rand(1, 7))->get();
        foreach($perguntas as $pergunta){
            $resposta = new RespostaPerguntaHabitoSaude();
            $resposta->habito_saude_id = $habito_saude_id;
            $resposta->pergunta_habito_saude_id = $pergunta->id;
            $resposta->resposta_texto = rand(0,1) ? 'Sim' : 'Não';
            $resposta->save();
        }
    }

}

