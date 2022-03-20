<?php

namespace App\Services;
use Illuminate\Http\JsonResponse;

class HelperService
{
    public static function defaultResponseJson($msg, $code = 200, $success = true, $datas = []):JsonResponse{
        return response()->json(['msg' => $msg, 'success' => $success, 'datas' => $datas], $code);
    }
}
