<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssociativaEndereco extends Model
{
    protected $table = 'associativa_enderecos';
    use HasFactory;
    protected $fillable = [
        'estabelecimento_id',
        'usuario_id',
        'endereco_id'
    ];
}
