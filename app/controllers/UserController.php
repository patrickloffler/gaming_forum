<?php
require_once '../app/controllers/BaseController.php';

class UserController extends BaseController {

    // Zobrazení profilu
    public function profile($id = null) {
        require_once '../app/models/User.php';
        require_once '../app/models/Post.php';
        $db = $this->db();
        $userId = $id ? (int)$id : ($_SESSION['user_id'] ?? null);
        if (!$userId) { header('Location: ' . BASE_URL . '/index.php?url=auth/login'); exit; }
        $userModel = new User($db);
        $postModel = new Post($db);
        $user  = $userModel->findById($userId);
        if (!$user) { $this->addErrorMessage('Uživatel nebyl nalezen.'); header('Location: ' . BASE_URL . '/index.php'); exit; }
        $posts = $postModel->getByUser($userId);
        require_once '../app/views/users/profile.php';
    }

    // Formulář pro editaci profilu
    public function edit($id = null) {
        $this->requireLogin();
        $userId = $id ? (int)$id : $_SESSION['user_id'];
        // Pouze sám uživatel nebo admin může editovat
        if ($userId !== $_SESSION['user_id'] && !$this->isAdmin()) {
            $this->addErrorMessage('Nemáš oprávnění upravit tento profil.'); header('Location: ' . BASE_URL . '/index.php'); exit;
        }
        require_once '../app/models/User.php';
        $userModel = new User($this->db());
        $user = $userModel->findById($userId);
        if (!$user) { $this->addErrorMessage('Uživatel nebyl nalezen.'); header('Location: ' . BASE_URL . '/index.php'); exit; }
        require_once '../app/views/users/edit.php';
    }

    // Uložení změn profilu
    public function update($id = null) {
        $this->requireLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: ' . BASE_URL . '/index.php'); exit; }
        $userId = $id ? (int)$id : $_SESSION['user_id'];
        if ($userId !== $_SESSION['user_id'] && !$this->isAdmin()) {
            $this->addErrorMessage('Nemáš oprávnění upravit tento profil.'); header('Location: ' . BASE_URL . '/index.php'); exit;
        }

        require_once '../app/models/User.php';
        $db = $this->db();
        $userModel = new User($db);
        $user = $userModel->findById($userId);
        if (!$user) { $this->addErrorMessage('Uživatel nebyl nalezen.'); header('Location: ' . BASE_URL . '/index.php'); exit; }

        $username  = htmlspecialchars($_POST['username'] ?? '');
        $email     = htmlspecialchars($_POST['email'] ?? '');
        $firstName = htmlspecialchars($_POST['first_name'] ?? '');
        $lastName  = htmlspecialchars($_POST['last_name'] ?? '');
        $nickname  = htmlspecialchars($_POST['nickname'] ?? '');
        $bio       = htmlspecialchars($_POST['bio'] ?? '');

        // Avatar upload
        $avatar = $user['avatar'];
        $newAvatar = $this->processAvatarUpload();
        if ($newAvatar) $avatar = $newAvatar;

        $userModel->update($userId, $username, $email, $firstName, $lastName, $nickname, $bio, $avatar);

        // Změna hesla (volitelné)
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        if (!empty($newPassword)) {
            if ($newPassword !== $confirmPassword) {
                $this->addErrorMessage('Hesla se neshodují.');
                header('Location: ' . BASE_URL . '/index.php?url=user/edit/' . $userId); exit;
            }
            if (strlen($newPassword) < 6) {
                $this->addErrorMessage('Heslo musí mít alespoň 6 znaků.');
                header('Location: ' . BASE_URL . '/index.php?url=user/edit/' . $userId); exit;
            }
            $userModel->updatePassword($userId, $newPassword);
        }

        // Admin může měnit roli
        if ($this->isAdmin() && isset($_POST['role'])) {
            $role = $_POST['role'];
            if (in_array($role, ['user','moderator','admin'])) {
                $userModel->updateRole($userId, $role);
            }
        }

        // Aktualizace session pokud upravujeme sebe
        if ($userId === $_SESSION['user_id']) {
            $_SESSION['user_name'] = !empty($nickname) ? $nickname : $username;
        }

        $this->addSuccessMessage('Profil byl úspěšně aktualizován.');
        header('Location: ' . BASE_URL . '/index.php?url=user/profile/' . $userId);
        exit;
    }
    

    // Smazání uživatele — pouze admin
    public function delete($id = null) {
        $this->requireAdmin();
        if (!$id) { header('Location: ' . BASE_URL . '/index.php?url=user/list'); exit; }

        $userId = (int)$id;
        // Admin nemůže smazat sám sebe 
        if ($userId === $_SESSION['user_id']) {
            $this->addErrorMessage('Nemůžeš smazat vlastní účet přes správu uživatelů.');
            header('Location: ' . BASE_URL . '/index.php?url=user/list'); exit;
        }

        require_once '../app/models/User.php';
        $userModel = new User($this->db());
        if ($userModel->delete($userId)) {
            $this->addSuccessMessage('Uživatel byl smazán.');
        } else {
            $this->addErrorMessage('Uživatele se nepodařilo smazat.');
        }
        header('Location: ' . BASE_URL . '/index.php?url=user/list');
        exit;
    }

    public function removeAvatar($id = null) {
    $this->requireLogin();
    $userId = $id ? (int)$id : $_SESSION['user_id'];
    if ($userId !== $_SESSION['user_id'] && !$this->isAdmin()) {
        $this->addErrorMessage('Nemáš oprávnění upravit tento profil.');
        header('Location: ' . BASE_URL . '/index.php'); exit;
    }

    require_once '../app/models/User.php';
    $userModel = new User($this->db());
    $user = $userModel->findById($userId);

    if ($user && $user['avatar']) {
        $filePath = __DIR__ . '/../../public/uploads/' . $user['avatar'];
        if (file_exists($filePath)) {
            unlink($filePath); // smaže fyzický soubor
        }
        $userModel->update($userId, $user['username'], $user['email'], $user['first_name'], $user['last_name'], $user['nickname'], $user['bio'], null);
        $this->addSuccessMessage('Avatar byl odstraněn.');
    }

    header('Location: ' . BASE_URL . '/index.php?url=user/edit/' . $userId);
    exit;
}

    // Seznam uživatelů — pouze admin
    public function list() {
        $this->requireAdmin();
        require_once '../app/models/User.php';
        $userModel = new User($this->db());
        $users = $userModel->getAll();
        require_once '../app/views/users/list.php';
    }
}