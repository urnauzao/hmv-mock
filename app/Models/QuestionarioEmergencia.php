<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\RespostaPerguntaEmergencia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @param situacao
 * @param perfil_id
 */
class QuestionarioEmergencia extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'questionarios_emergencia';
    protected $fillable = [
        'situacao',
        'perfil_id'
    ];

    
    public function respostas(){
        return $this->hasMany(RespostaPerguntaEmergencia::class);
    }

    protected $cast = [
        'updated_at' => 'datetime',
        'created_at' => 'datetime'
    ];

}
