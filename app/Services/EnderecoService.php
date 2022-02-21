<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Endereco;
use Faker;

class EnderecoService
{
    public static function mock(){
        $faker = Faker\Factory::create("pt_BR");
        $array_tipos = ['rua', 'av', 'travessa', 'rota'];
        $tipo = rand(1,3) > 1 ? 'rua': $array_tipos[rand(0, (count($array_tipos)-1))];

        $endereco = new Endereco();
        $endereco->nome = $faker->company();
        $endereco->tipo = $tipo;
        $endereco->logradouro = $faker->streetName();
        $endereco->cep = $faker->postcode();
        $endereco->numero = $faker->buildingNumber();
        $endereco->cidade = $faker->city();
        $endereco->estado = $faker->stateAbbr();
        $endereco->pais = 'Brasil';
        $endereco->complemento = rand(1,9) > 7 ? str_split($faker->address(), 60) : null;
        $endereco->deleted_at = rand(1, 100) == 1 ? now()->toDateString() : null;
        $endereco->save();
    }

    public static function getAll(bool $json = true){
        $result = Endereco::query()->latest()->get();
        if($json){
            return response()->json($result->toArray());
        }else{
            return $result;
        }
    }

    public static function findJson(int $id){
        try {
            $result = Endereco::query()->find($id);
            if($result){
                return response()->json($result->toArray());
            }else{
                return response()->json(["msg" => "Não encontrado"], 404);
            }
        } catch (\Throwable $th) {
            return response()->json(["msg" => "Id inválido"], 400);
        }
    }
}
