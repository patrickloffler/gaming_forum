<?php
require_once '../app/controllers/BaseController.php';

class CommentController extends BaseController {

    // Přidání komentáře
    public function store($postId = null) {
        $this->requireLogin();
        if (!$postId || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/index.php'); exit;
        }

        $content = trim(htmlspecialchars($_POST['content'] ?? ''));
        if (empty($content)) {
            $this->addErrorMessage('Komentář nemůže být prázdný.');
            header('Location: ' . BASE_URL . '/index.php?url=post/show/' . $postId); exit;
        }

        require_once '../app/models/Comment.php';
        $commentModel = new Comment($this->db());

        if ($commentModel->create((int)$postId, $_SESSION['user_id'], $content)) {
            $this->addSuccessMessage('Komentář byl přidán.');
        } else {
            $this->addErrorMessage('Komentář se nepodařilo uložit.');
        }
        header('Location: ' . BASE_URL . '/index.php?url=post/show/' . $postId . '#comments');
        exit;
    }

    // Formulář pro editaci komentáře
    public function edit($id = null) {
        $this->requireLogin();
        if (!$id) { header('Location: ' . BASE_URL . '/index.php'); exit; }

        require_once '../app/models/Comment.php';
        $commentModel = new Comment($this->db());
        $comment = $commentModel->getById((int)$id);

        if (!$comment) { $this->addErrorMessage('Komentář nebyl nalezen.'); header('Location: ' . BASE_URL . '/index.php'); exit; }
        if ($comment['user_id'] !== $_SESSION['user_id'] && !$this->isModerator()) {
            $this->addErrorMessage('Nemáš oprávnění upravit tento komentář.');
            header('Location: ' . BASE_URL . '/index.php?url=post/show/' . $comment['post_id']); exit;
        }

        require_once '../app/views/comments/comment_edit.php';
    }

    // Uložení upraveného komentáře
    public function update($id = null) {
        $this->requireLogin();
        if (!$id || $_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: ' . BASE_URL . '/index.php'); exit; }

        require_once '../app/models/Comment.php';
        $commentModel = new Comment($this->db());
        $comment = $commentModel->getById((int)$id);

        if (!$comment || ($comment['user_id'] !== $_SESSION['user_id'] && !$this->isModerator())) {
            $this->addErrorMessage('Nemáš oprávnění upravit tento komentář.');
            header('Location: ' . BASE_URL . '/index.php'); exit;
        }

        $content = trim(htmlspecialchars($_POST['content'] ?? ''));
        if (empty($content)) {
            $this->addErrorMessage('Komentář nemůže být prázdný.');
            header('Location: ' . BASE_URL . '/index.php?url=comment/edit/' . $id); exit;
        }

        if ($commentModel->update((int)$id, $content)) {
            $this->addSuccessMessage('Komentář byl upraven.');
        } else {
            $this->addErrorMessage('Komentář se nepodařilo upravit.');
        }
        header('Location: ' . BASE_URL . '/index.php?url=post/show/' . $comment['post_id'] . '#comments');
        exit;
    }

    // Smazání komentáře
    public function delete($id = null) {
        $this->requireLogin();
        if (!$id) { header('Location: ' . BASE_URL . '/index.php'); exit; }

        require_once '../app/models/Comment.php';
        $commentModel = new Comment($this->db());
        $comment = $commentModel->getById((int)$id);

        if (!$comment || ($comment['user_id'] !== $_SESSION['user_id'] && !$this->isModerator())) {
            $this->addErrorMessage('Nemáš oprávnění smazat tento komentář.');
            header('Location: ' . BASE_URL . '/index.php'); exit;
        }

        $postId = $comment['post_id'];
        if ($commentModel->delete((int)$id)) {
            $this->addSuccessMessage('Komentář byl smazán.');
        } else {
            $this->addErrorMessage('Komentář se nepodařilo smazat.');
        }
        header('Location: ' . BASE_URL . '/index.php?url=post/show/' . $postId . '#comments');
        exit;
    }
}