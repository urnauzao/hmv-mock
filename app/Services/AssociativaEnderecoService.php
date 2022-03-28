<?php

namespace App\Services;

use App\Models\AssociativaEndereco;
use App\Models\Endereco;
use App\Models\Estabelecimento;
use App\Models\Usuario;

class AssociativaEnderecoService
{
    public static function usuarios_sem_endereco(){
        return Usuario::query()
        ->leftJoin('associativa_enderecos', 'associativa_enderecos.usuario_id', 'usuarios.id')
        ->whereNull('associativa_enderecos.id')
        ->whereNull('associativa_enderecos.estabelecimento_id')
        ->select('usuarios.*')
        ->get();
    }

    public static function estabelecimentos_sem_endereco(){
        return Estabelecimento::query()
        ->leftJoin('associativa_enderecos', 'associativa_enderecos.estabelecimento_id', 'estabelecimentos.id')
        ->whereNull('associativa_enderecos.id')
        ->whereNull('associativa_enderecos.usuario_id')
        ->select('estabelecimentos.*')
        ->get();
    }

    public static function enderecos_sem_associacao(){
        return Endereco::query()
        ->leftJoin('associativa_enderecos', 'associativa_enderecos.endereco_id', 'enderecos.id')
        ->whereNull('associativa_enderecos.id')
        ->select('enderecos.*')
        ->get();
    }

    public static function mock(){
        $usuarios_sem_endereco = self::usuarios_sem_endereco();
        $estabelecimentos_sem_endereco = self::estabelecimentos_sem_endereco();
        $enderecos_sem_associacao = self::enderecos_sem_associacao();

        if($enderecos_sem_associacao->count() === 0){
            foreach($estabelecimentos_sem_endereco as $estabelecimento){
                EnderecoService::mock();
            }
            foreach($usuarios_sem_endereco as $usuarios){
                EnderecoService::mock();
            }
            $enderecos_sem_associacao = self::enderecos_sem_associacao();
        }

        if($usuarios_sem_endereco)
        foreach($usuarios_sem_endereco as $usuario){
            $endereco = $enderecos_sem_associacao->first();
            if(!$endereco){
                dd($endereco, $usuarios_sem_endereco->count());
            }
            $associativa = new AssociativaEndereco();
            $associativa->usuario_id = $usuario->id;
            $associativa->endereco_id = $endereco->id;
            $associativa->save();

            $enderecos_sem_associacao = $enderecos_sem_associacao->whereNotIn('id', $endereco->id);
        }

        if($estabelecimentos_sem_endereco)
        foreach($estabelecimentos_sem_endereco as $estabelecimento){
            $endereco = $enderecos_sem_associacao->first();
            if(!$endereco){
                dd($endereco, $estabelecimentos_sem_endereco->count());
            }
            $associativa = new AssociativaEndereco();
            $associativa->estabelecimento_id = $estabelecimento->id;
            $associativa->endereco_id = $endereco->id;
            $associativa->save();

            $enderecos_sem_associacao = $enderecos_sem_associacao->whereNotIn('id', $endereco->id);
        }

    }    

    public static function getAll(bool $json = true){
        $result = AssociativaEndereco::query()->latest()->get();
        if($json){
            return response()->json($result->toArray());
        }else{
            return $result;
        }
    }

    public static function findJson(int $id){
        try {
            $result = AssociativaEndereco::query()->find($id);
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
