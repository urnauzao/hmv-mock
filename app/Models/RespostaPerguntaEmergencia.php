<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @param questionario_emergencia_id
 * @param pergunta_emergencia_id
 * @param resposta
 * @param resposta_texto
 */
class RespostaPerguntaEmergencia extends Model
{
    use HasFactory;
    protected $table = 'respostas_perguntas_emergencia';
    protected $fillable = [
        'questionario_emergencia_id',
        'pergunta_emergencia_id',
        'resposta',
        'resposta_texto'
    ];
    protected $casts = [
        'resposta' => 'array'
    ];
}
