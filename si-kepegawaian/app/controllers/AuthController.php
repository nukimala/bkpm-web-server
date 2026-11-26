<?php 
class AuthController {
    private $userModel;
    public function __construct() {
        $this->userModel = new User();
    }
    private function getInputData() {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        if (stripos($contentType, 'application/json') !== false) {
            $raw = file_get_contents('php://input');
            return json_decode($raw, true) ?? [];
        }
        return $_POST;
    }
    public function login() {
        $data = $this->getInputData();
        if (empty($data['username']) || empty($data['password'])) {
            Response::error('username dan password harus diisi', 400);
        }
        $user = $this->userModel->getByUsername($data['username']);
        if (!$user || !password_verify($data['password'], $user['password'])) {
            Response::error('username atau password salah', 401);
        }
        $token = Auth::generateToken($user);
        $profile = $this->userModel->getProfile($user['id']);
        Response::success('Login berhasil', ['token' => $token, 'profile' => $profile], 200);
    }
    public function me() {
        $userId = Auth::$user['id'];
        $profile = $this->userModel->getProfile($userId);
        response::success('Berhasil mengambil data profil', $profile, 200);
    }
}



        