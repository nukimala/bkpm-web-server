<?php
// app/Controllers/AuthController.php
require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Core/Middleware/AuthMiddleware.php';
require_once __DIR__ . '/../Core/Logger.php';
require_once __DIR__ . '/../Repositories/UserRepository.php';

class AuthController extends Controller
{
    private UserRepository $users;

    public function __construct(?UserRepository $users = null)
    {
        $this->users = $users ?? new UserRepository(Database::getInstance());
    }

    private function setFlash(string $message): void
    {
        $_SESSION['flash'] = $message;
    }

    public function showLogin(): void
    {
        if (AuthMiddleware::isLoggedIn()) {
            $this->redirect('/dashboard');
        }
        $this->view('auth/login.php');
    }

    public function login(): void
    {
        $this->onlyPost('/login');
        $this->verifyCsrf();

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $logger = new Logger();

        $user = $this->users->findByUsername($username);

        if ($user !== null && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user'] = $user['username'];
            $_SESSION['user_id'] = (int)$user['id'];
            $_SESSION['user_name'] = $user['nama'];

            $logger->info('LOGIN BERHASIL: ' . $username . ' dari IP ' . ($_SERVER['REMOTE_ADDR'] ?? '?'));
            $this->setFlash('Login berhasil. Selamat datang, ' . $user['nama'] . '!');
            $this->redirect('/dashboard');
        }

        $logger->warning('LOGIN GAGAL: username "' . $username . '"');
        $this->setFlash('Username atau password salah.');
        $this->view('auth/login.php');
    }

    public function logout(): void
    {
        $logger = new Logger();
        $logger->info('LOGOUT: ' . ($_SESSION['user'] ?? '?'));
        session_unset();
        session_destroy();
        $this->redirect('/login');
    }
}