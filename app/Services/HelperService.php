<?php

namespace App\Services;
use Illuminate\Http\JsonResponse;

class HelperService
{
    public static function defaultResponseJson($msg, $code = 200, $status = true, $detalhes = []):JsonResponse{
        return response()->json(['mensagem' => $msg, 'status' => $status, 'detalhes' => $detalhes], $code);
    }
}
