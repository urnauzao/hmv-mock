<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/**
 * @param telefone 
 * @param email 
 * @param cnpj 
 * @param imagem 
 * @param site 
 * @param tipo  //0 -> Hospital, 1 -> Pronto Socorro, 2 -> Clínica, 3 -> Outros
 * @param deleted_at  
 * @param nome
*/
class Estabelecimento extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'estabelecimentos';
    protected $fillable = [
        'nome',
        'telefone',
        'email',
        'cnpj',
        'imagem',
        'site',
        'tipo', //0 -> Hospital, 1 -> Pronto Socorro, 2 -> Clínica, 3 -> Outros
        'deleted_at'
    ];
}
