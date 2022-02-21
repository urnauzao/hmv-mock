<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PermissoesPerfis extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'permissoes_perfis';
    protected $fillable = [
        'perfil_id',
        'usuario_id',
        'deleted_at'
    ];
}
