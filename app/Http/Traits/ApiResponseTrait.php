<?php

namespace App\Http\Traits;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Response;

/**
 * Trait ApiResponseTrait
 * @author Ahmed Mohamed
 */
trait ApiResponseTrait
{
    /**

     * @return Response
     */
    public function apiResponse($data , $status , $IDcode, $Statuscode, $message )
    {
        $array = [
            'data'   => $data,
            'status' => $status,
            'identifier_code' => $IDcode ,
            'status_code' => $Statuscode,
            'message' => $message,
        ];
        return Response::json($array, $Statuscode);

    }


    /**
     * This function apiResponseValidation for Validation Request
     * @param $validator
     */
    public function apiResponseValidation($validator)
    {
        $response = [
            'data'   => [],
            'status' => false,
            'identifier_code' => 100003,
            'status_code' => 422,
            'message' => 'validation',
            'validator'=>$validator->errors(),
        ];

        throw new HttpResponseException( Response::json($response, 422));
    }
}
