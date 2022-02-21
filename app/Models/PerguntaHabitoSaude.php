<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerguntaHabitoSaude extends Model
{
    use HasFactory;
    protected $table = 'perguntas_habitos_saude';
    protected $fillable = [
        'pergunta',
        'tipo',
        'opcoes',
        'prioridade',
        'obrigatoria',
        'ativo'
    ];
}
