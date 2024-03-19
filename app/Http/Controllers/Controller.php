<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    public function handelResponse($result, $msg)
    {
        $res = [
            'success' => true,
            'data' => $result,
            'message' => $msg
        ];
        return response()->json($res, 200);
    }
    //////////////////////Handel Error//////////////////////////////
    public function handelError($error, $errorMSG = [], $code = 404)
    {
        $res = [
            'succes' => false,
            'message' => $error,
        ];
        if (!empty($errorMSG)) {
            $res['data'] = $errorMSG;
        }
        return response()->json($res, $code);
    }
}
