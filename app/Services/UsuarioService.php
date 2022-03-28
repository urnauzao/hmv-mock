<?php
namespace App\Services;

use App\Models\Usuario;
use Faker;

class UsuarioService{
    
    public static function mock(){
        $faker = Faker\Factory::create("pt_BR");

        $usuario =  new Usuario();
        $usuario->nome = $faker->name();
        $usuario->email = $faker->unique()->safeEmail();
        $usuario->password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'; // password
        if(rand(1,4) == 4){
            $usuario->doc_tipo = 'cnpj';
            $usuario->doc_numero = $faker->cnpj();
        }else{
            $usuario->doc_tipo = 'cpf';
            $usuario->doc_numero = $faker->cpf();
        }
        $usuario->ativo = rand(1,100) > 2 ? true : false;
        $usuario->nome = $faker->name();
        $usuario->foto = "https://picsum.photos/id/".rand(1,1050)."/200";
        $usuario->save();
        return $usuario;
    }

    public static function getAll(bool $json = true){
        $results = Usuario::query()->latest()->get();
        if($json){
            return response()->json($results->toArray());
        }else{
            return $results;
        }
    }

    public static function findJson(int $id){
        try {
            $result = Usuario::query()->find($id);
            if($result){
                return response()->json($result->toArray());
            }else{
                return response()->json(["mensagem" => "Não encontrado"], 404);
            }
        } catch (\Throwable $th) {
            return response()->json(["mensagem" => "Id inválido"], 400);
        }
    }


}
