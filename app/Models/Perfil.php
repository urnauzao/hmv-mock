<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * @param int id
 * @param int usuario_id
 * @param enum tipo ['medico', 'paciente', 'atendente', 'socorrista', 'admin']
 */
class Perfil extends Model
{
    use HasFactory;
    protected $table = 'perfis';
    // ['medico', 'paciente', 'atendente', 'socorrista', 'admin']
    protected $fillable = [
        'tipo',
        'usuario_id'
    ];
}
