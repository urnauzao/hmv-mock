<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RespostaPerguntaHabitoSaude extends Model
{
    use HasFactory;
    protected $table = 'respostas_perguntas_habitos_saude';
    protected $fillable = [
        'habito_saude_id',
        'pergunta_habito_saude_id',
        'resposta',
        'resposta_texto'
    ];
    protected $casts = [
        'resposta' => 'array'
    ];
}
