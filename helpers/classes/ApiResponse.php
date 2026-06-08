<?php

declare(strict_types=1);



class ApiResponse {

    public static function send(mixed $data = null, int $statusCode = 200) : void {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        
        $response = ['success' => true];
        if ($data !== null) {
            $response['data'] = $data;
        }
        
        echo json_encode($response);
        exit();
    }

    public static function error(string $message, int $statusCode = 400) : void {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        
        echo json_encode([
            'success' => false,
            'error' => $message
        ]);
        exit();
    }
}

?>