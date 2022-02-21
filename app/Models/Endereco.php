<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/**
 *  @param id,
 *  @param nome,
 *  @param tipo,
 *  @param logradouro,
 *  @param cep,
 *  @param numero,
 *  @param cidade,
 *  @param estado,
 *  @param pais,
 *  @param complemento,
 *  @param deleted_at
 */
class Endereco extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'enderecos';

    protected $fillable = [
        'id',
        'nome',
        'tipo',
        'logradouro',
        'cep',
        'numero',
        'cidade',
        'estado',
        'pais',
        'complemento',
        'deleted_at'
    ];
}
