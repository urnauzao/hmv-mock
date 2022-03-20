<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @param situacao
 * @param perfil_id
 */
class HabitoSaude extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'habitos_saude';
    protected $fillable = [
        'situacao',
        'perfil_id'
    ];

    protected $cast = [
        'updated_at' => 'datetime',
        'created_at' => 'datetime'
    ];
}
