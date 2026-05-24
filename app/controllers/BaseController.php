<?php

class BaseController {

    protected function addSuccessMessage(string $msg): void {
        $_SESSION['messages']['success'][] = $msg;
    }
    protected function addErrorMessage(string $msg): void {
        $_SESSION['messages']['error'][] = $msg;
    }
    protected function addNoticeMessage(string $msg): void {
        $_SESSION['messages']['notice'][] = $msg;
    }

    protected function requireLogin(): void {
        if (!isset($_SESSION['user_id'])) {
            $this->addErrorMessage('Pro tuto akci se musíte přihlásit.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }
    }

    protected function requireAdmin(): void {
        $this->requireLogin();
        if (($_SESSION['user_role'] ?? '') !== 'admin') {
            $this->addErrorMessage('Tato akce vyžaduje administrátorská oprávnění.');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }
    }

    protected function isModerator(): bool {
        return in_array($_SESSION['user_role'] ?? '', ['admin', 'moderator']);
    }

    protected function isAdmin(): bool {
        return ($_SESSION['user_role'] ?? '') === 'admin';
    }

    protected function db(): PDO {
        require_once '../app/models/Database.php';
        return (new Database())->getConnection();
    }

    protected function processImageUploads(): array {
        $uploadedFiles = [];
        $uploadDir = __DIR__ . '/../../public/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
            $fileCount = count($_FILES['images']['name']);
            for ($i = 0; $i < $fileCount; $i++) {
                if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                    $tmpName  = $_FILES['images']['tmp_name'][$i];
                    $origName = basename($_FILES['images']['name'][$i]);
                    $ext      = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
                    $allowed  = ['jpg','jpeg','png','webp','gif'];
                    if (!in_array($ext, $allowed)) continue;
                    $newName  = 'img_' . uniqid() . '_' . substr(md5(mt_rand()), 0, 4) . '.' . $ext;
                    if (move_uploaded_file($tmpName, $uploadDir . $newName)) {
                        $uploadedFiles[] = $newName;
                    }
                }
            }
        }
        return $uploadedFiles;
    }

    protected function processAvatarUpload(): ?string {
        $uploadDir = __DIR__ . '/../../public/uploads/avatars/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $origName = basename($_FILES['avatar']['name']);
            $ext      = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
            $allowed  = ['jpg','jpeg','png','webp'];
            if (!in_array($ext, $allowed)) return null;
            $newName = 'avatar_' . uniqid() . '.' . $ext;
            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadDir . $newName)) {
                return 'avatars/' . $newName;
            }
        }
        return null;
    }
}