<?php
class Response {
    public static function json($status, $message, $data = null, $statusCode = 200) {
        if (ob_get_length()) {
            ob_clean();
        }

        header('Content-Type: application/json; charshet=UTF-8');
        http_response_code($statusCode);

        $response = [
            'status' => $status,
            'message' => $message,
        ];
         if ($data !== null) {
            $response['data'] = $data;
         }
         echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
         exit;
    }

    public static function success($message, $data = null, $statusCode = 200) {
        self::json('success',$message, $data, $statusCode );
    }
     public static function error($message, $statusCode = 400, $data = null) {
        self::json('error', $message, $data, $statusCode);
    }
}