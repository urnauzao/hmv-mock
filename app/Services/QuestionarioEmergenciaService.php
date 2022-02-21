<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\QuestionarioEmergencia;

class QuestionarioEmergenciaService
{
    public static function getAll(bool $json = true){
        $results = QuestionarioEmergencia::query()->with(['respostas'])->latest()->get();
        if($json){
            return response()->json($results->toArray());
        }else{
            return $results;
        }
    }

    public static function findJson(int $id){
        try {
            $result = QuestionarioEmergencia::query()->with(['respostas'])->find($id);
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
