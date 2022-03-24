<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * @param Perfil paciente_perfil_id
 * @param Perfil medico_perfil_id
 * @param Perfil socorrista_perfil_id
 * @param Perfil atendente_perfil_id
 * @param Datetime data
 * @param Enum situacao : '0 -> Agendado, 1 -> Na espera, 2 -> Em realização, 3 -> Realizado, 4 -> Não realizado', 5 -> 'Emergencia'
 * @param String observacoes
 * @param Estabelecimento estabelecimento_id 
 * @param string exame
 * @param Endereco paciente_endereco_id
 */
class Agendamento extends Model
{
    protected $table = 'agendamentos';
    use HasFactory;
    protected $dates = ['created_at', 'updated_at', 'data'];

    protected $fillable = [
        'socorrista_perfil_id',
        'atendente_perfil_id',
        'paciente_perfil_id',
        'medico_perfil_id',
        'data',
        'situacao',
        'observacoes',
        'estabelecimento_id',
        'exame',
        'paciente_endereco_id'
    ];

    protected $with = ['estabelecimento', 'perfil'];

    public function estabelecimento()
    {
        return $this->belongsTo(Estabelecimento::class);
    }

    public function perfil()
    {
        return $this->belongsTo(Perfil::class,  'paciente_perfil_id', 'id');
    }
}
