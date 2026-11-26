<?php 
class Auth {
    private static $secretKey = 'kunci keamanan';
    public static $user = null;
    private static function base64UrlEncode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
    private static function base64UrlDecode($data) {
        return base64_decode(strtr($data, '-_', '+/'));
    }
    public static function generateToken($userData) {
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        $payload = json_encode([
            'id' => $userData['id'],
            'username' => $userData['username'],
            'role' => $userData['role'],
            'pegawai_id' => $userData['pegawai_id'],
            'iat' => time(),
            'exp' => time() + (6 * 3600)
        ]);
        $base64UrlHeader = self::base64UrlEncode($header);
        $base64UrlPayload = self::base64UrlEncode($payload);
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, self::$secretKey, true);
        $base64UrlSignature = self::base64UrlEncode($signature);
        return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }
    public static function validateToken($jwt) {
        $tokenParts = explode('.', $jwt);
        if (count($tokenParts) !== 3) {
            return false;
        }
        list($header64, $payload64, $signature64) = $tokenParts;
        $signature = self::base64UrlDecode($signature64);
        $expectedSignature = hash_hmac('sha256', $header64 . "." . $payload64, self::$secretKey, true);
        if (!hash_equals($signature, $expectedSignature)) {
            return false;
        }
        $payload = json_decode(self::base64UrlDecode($payload64), true);
        if (!$payload || !isset($payload['exp']) || $payload['exp'] < time()) {
            return false;
        }
        return $payload;
    }
    public static function getBearerToken() {
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER['Authorization']);
        } elseif (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $headers = trim($_SERVER['HTTP_AUTHORIZATION']);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        return null;
        }
    }
    public static function requireAuth() {
        $token = self::getBearerToken();
        if (!$token) {
            Response::error('Token tidak ditemukan', 401);
        }
        $payload = self::validateToken($token);
        if (!$payload) {
            Response::error('Token tidak valid atau sudah kadaluarsa', 401);
        }
        self::$user = $payload;
    }
    public static function requireAdmin() {
        self::requireAuth();
        if (self::$user['role'] !== 'admin_hr') {
            Response::error('Akses ditolak. Hanya admin yang dapat mengakses endpoint ini.', 403);
        }
    }

    }