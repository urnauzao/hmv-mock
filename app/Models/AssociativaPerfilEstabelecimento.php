<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @param int perfil_id
 * @param int estabelecimento_id
 */
class AssociativaPerfilEstabelecimento extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'associativa_perfil_estabelecimento';
    protected $fillable = [
        'perfil_id',
        'estabelecimento_id'
    ];

}
