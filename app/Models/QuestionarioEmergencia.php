<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuestionarioEmergencia extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'questionarios_emergencia';
    protected $fillable = [
        'situacao',
        'perfil_id'
    ];
}
