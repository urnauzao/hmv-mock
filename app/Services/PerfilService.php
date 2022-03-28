<?php

namespace App\Services;

use App\Models\Perfil;
use App\Models\Usuario;

class PerfilService
{
    public static function mock(){
        $usuarios_sem_perfil = Usuario::query()
        ->leftJoin('perfis', 'perfis.usuario_id', 'usuarios.id')
        ->whereNull('perfis.id')->select('usuarios.*')->get();

        foreach($usuarios_sem_perfil as $usuario){
            $total_perfis = rand(1, 6) == 6 ? 2 : 1;
            $perfis_criar = ['paciente'];
            if($total_perfis == 2){
                $outros_perfis = ['medico', 'atendente', 'socorrista', 'admin'];
                $perfis_criar[] = $outros_perfis[rand(0, (count($outros_perfis)-1))];
            }
            foreach($perfis_criar as $perfil_criar){
                $perfil = new Perfil();
                $perfil->tipo = $perfil_criar;
                $perfil->usuario_id = $usuario->id;
                $perfil->save();
            }
        }
    }
    
    public static function getAll(bool $json = true){
        $result = Perfil::query()->latest()->get();
        if($json){
            return response()->json($result->toArray());
        }else{
            return $result;
        }
    }

    public static function findJson(int $id){
        try {
            $result = Perfil::query()->find($id);
            if($result){
                return response()->json($result->toArray());
            }else{
                return response()->json(["mensagem" => "Não encontrado"], 404);
            }
        } catch (\Throwable $th) {
            return response()->json(["mensagem" => "Id inválido"], 400);
        }
    }

    public static function findByUsuario(int $usuario_id):array{
        $result = Perfil::query()->where(['usuario_id' => $usuario_id])->get();
        return $result->toArray();
    }

    public static function perfilEhMedico(int $perfil_id):bool{
        return Perfil::where('usuario_id', $perfil_id)->where('tipo', 'medico')->first() ? true : false;
    }
}
