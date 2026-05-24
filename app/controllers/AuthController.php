<?php
require_once '../app/controllers/BaseController.php';

class AuthController extends BaseController {

    public function login() {
        require_once '../app/views/auth/login.php';
    }

    public function authenticate() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: ' . BASE_URL . '/index.php?url=auth/login'); exit; }
        $email    = htmlspecialchars($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        require_once '../app/models/User.php';
        $userModel = new User($this->db());
        $user = $userModel->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_name'] = !empty($user['nickname']) ? $user['nickname'] : $user['username'];
            $_SESSION['user_role'] = $user['role'];
            $this->addSuccessMessage('Vítej zpět, ' . $_SESSION['user_name'] . '! 🎮');
            header('Location: ' . BASE_URL . '/index.php');
        } else {
            $this->addErrorMessage('Nesprávný e-mail nebo heslo.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
        }
        exit;
    }

    public function register() {
        require_once '../app/views/auth/register.php';
    }

    public function storeUser() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: ' . BASE_URL . '/index.php?url=auth/register'); exit; }

        $username        = htmlspecialchars($_POST['username'] ?? '');
        $email           = htmlspecialchars($_POST['email'] ?? '');
        $firstName       = htmlspecialchars($_POST['first_name'] ?? '');
        $lastName        = htmlspecialchars($_POST['last_name'] ?? '');
        $nickname        = htmlspecialchars($_POST['nickname'] ?? '');
        $password        = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';

        if (empty($username) || empty($email) || empty($password)) {
            $this->addErrorMessage('Vyplňte prosím všechna povinná pole.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/register'); exit;
        }
        if ($password !== $passwordConfirm) {
            $this->addErrorMessage('Zadaná hesla se neshodují.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/register'); exit;
        }
        if (strlen($password) < 6) {
            $this->addErrorMessage('Heslo musí mít alespoň 6 znaků.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/register'); exit;
        }

        require_once '../app/models/User.php';
        $userModel = new User($this->db());

        if ($userModel->register($username, $email, $password, $firstName, $lastName, $nickname)) {
            $this->addSuccessMessage('Registrace proběhla úspěšně! Nyní se můžeš přihlásit.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
        } else {
            $this->addErrorMessage('Uživatel s tímto e-mailem nebo jménem již existuje.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/register');
        }
        exit;
    }

    public function logout() {
        unset($_SESSION['user_id'], $_SESSION['user_name'], $_SESSION['user_role']);
        $this->addSuccessMessage('Byl jsi úspěšně odhlášen. Brzy na viděnou! 👋');
        header('Location: ' . BASE_URL . '/index.php');
        exit;
    }
}