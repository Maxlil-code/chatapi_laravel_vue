<?php

function httpResponse($data, $message = '', $statusCode = 200){
    $response['data'] = $data;
    $response['message'] = $message;

    return response()->json(
        $response,
        $statusCode
    );
}
