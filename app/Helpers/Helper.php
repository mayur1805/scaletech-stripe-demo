<?php
namespace App\Helpers;

trait Helper
{
    public function successResponse($message, $data = [])
    {
        return [
            "status" => true,
            "data" => $data,
            "message" => $message
        ];
    }

    public function errorResponse($message)
    {
        return [
            "status" => false,
            "message" => $message
        ];
    }
}
