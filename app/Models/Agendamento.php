<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agendamento extends Model
{
    protected $table = 'agendamentos';
    use HasFactory;
    protected $dates = ['created_at', 'updated_at', 'data'];

    protected $fillable = [
        'paciente_perfil_id',
        'medico_perfil_id',
        'data',
        'situacao',
        'observacoes',
        'estabelecimento_id'
    ];
}
