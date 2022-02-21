<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoricoAtendimento extends Model
{
    use HasFactory;
    protected $table = 'historicos_atendimento';
    protected $fillable = [
        'relatorio',
        'data',
        'agendamento_id',
        'paciente_perfil_id',
        'medico_perfil_id'
    ];
}
