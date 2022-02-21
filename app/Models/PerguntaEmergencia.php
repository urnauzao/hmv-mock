<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * @param pergunta
 * @param tipo
 * @param opcoes
 * @param prioridade
 * @param obrigatoria
 * @param ativo
 */
class PerguntaEmergencia extends Model
{
    use HasFactory;
    protected $table = 'perguntas_emergencia';
    protected $fillable = [
        'pergunta',
        'tipo',
        'opcoes',
        'prioridade',
        'obrigatoria',
        'ativo'
    ];
}
