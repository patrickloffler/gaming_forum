<?php
require_once '../app/controllers/BaseController.php';

class PostController extends BaseController {

    private function loadDeps(): array {
        require_once '../app/models/Post.php';
        require_once '../app/models/Category.php';
        $db = $this->db();
        return [new Post($db), new Category($db), $db];
    }

    // Seznam příspěvků (homepage)
    public function index() {
        [$postModel, $categoryModel] = $this->loadDeps();
        $categoryId = isset($_GET['cat']) ? (int)$_GET['cat'] : null;
        $posts      = $postModel->getAll($categoryId);
        $categories = $categoryModel->getAll();
        $activeCategory = $categoryId ? $categoryModel->getById($categoryId) : null;
        require_once '../app/views/posts/posts_list.php';
    }

    // Detail příspěvku + komentáře
    public function show($id = null) {
        if (!$id) { header('Location: ' . BASE_URL . '/index.php'); exit; }
        require_once '../app/models/Post.php';
        require_once '../app/models/Comment.php';
        $db          = $this->db();
        $postModel   = new Post($db);
        $commentModel = new Comment($db);
        $post     = $postModel->getById((int)$id);
        if (!$post) { $this->addErrorMessage('Příspěvek nebyl nalezen.'); header('Location: ' . BASE_URL . '/index.php'); exit; }
        $comments = $commentModel->getByPost((int)$id);
        require_once '../app/views/posts/post_detail.php';
    }

    // Formulář pro vytvoření
    public function create() {
        $this->requireLogin();
        [, $categoryModel] = $this->loadDeps();
        $categories = $categoryModel->getAll();
        require_once '../app/views/posts/post_create.php';
    }

    // Uložení nového příspěvku
    public function store() {
        $this->requireLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: ' . BASE_URL . '/index.php?url=post/create'); exit; }

        $title      = htmlspecialchars($_POST['title'] ?? '');
        $content    = htmlspecialchars($_POST['content'] ?? '');
        $gameName   = htmlspecialchars($_POST['game_name'] ?? '');
        $platform   = htmlspecialchars($_POST['platform'] ?? 'PC');
        $categoryId = (int)($_POST['category_id'] ?? 0);

        if (empty($title) || empty($content)) {
            $this->addErrorMessage('Název a obsah příspěvku jsou povinné.');
            header('Location: ' . BASE_URL . '/index.php?url=post/create'); exit;
        }

        $images = $this->processImageUploads();
        require_once '../app/models/Post.php';
        $postModel = new Post($this->db());

        if ($postModel->create($title, $content, $gameName, $platform, $categoryId, $images, $_SESSION['user_id'])) {
            $this->addSuccessMessage('Příspěvek byl úspěšně přidán! 🎮');
            header('Location: ' . BASE_URL . '/index.php');
        } else {
            $this->addErrorMessage('Příspěvek se nepodařilo uložit.');
            header('Location: ' . BASE_URL . '/index.php?url=post/create');
        }
        exit;
    }

    // Formulář pro editaci
    public function edit($id = null) {
        $this->requireLogin();
        if (!$id) { header('Location: ' . BASE_URL . '/index.php'); exit; }

        [$postModel, $categoryModel] = $this->loadDeps();
        $post = $postModel->getById((int)$id);

        if (!$post) { $this->addErrorMessage('Příspěvek nebyl nalezen.'); header('Location: ' . BASE_URL . '/index.php'); exit; }
        if ($post['created_by'] !== $_SESSION['user_id'] && !$this->isModerator()) {
            $this->addErrorMessage('Nemáš oprávnění upravit tento příspěvek.');
            header('Location: ' . BASE_URL . '/index.php'); exit;
        }

        $categories = $categoryModel->getAll();
        require_once '../app/views/posts/post_edit.php';
    }

    // Uložení změn
    public function update($id = null) {
        $this->requireLogin();
        if (!$id || $_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: ' . BASE_URL . '/index.php'); exit; }

        require_once '../app/models/Post.php';
        $postModel = new Post($this->db());
        $post = $postModel->getById((int)$id);

        if (!$post || ($post['created_by'] !== $_SESSION['user_id'] && !$this->isModerator())) {
            $this->addErrorMessage('Nemáš oprávnění upravit tento příspěvek.');
            header('Location: ' . BASE_URL . '/index.php'); exit;
        }

        $title      = htmlspecialchars($_POST['title'] ?? '');
        $content    = htmlspecialchars($_POST['content'] ?? '');
        $gameName   = htmlspecialchars($_POST['game_name'] ?? '');
        $platform   = htmlspecialchars($_POST['platform'] ?? 'PC');
        $categoryId = (int)($_POST['category_id'] ?? 0);
        $images     = $this->processImageUploads();

        // Zachovat stávající obrázky pokud žádné nové nenahrány
        if (empty($images)) {
            $images = json_decode($post['images'] ?? '[]', true) ?: [];
        }

        if ($postModel->update((int)$id, $title, $content, $gameName, $platform, $categoryId, $images)) {
            $this->addSuccessMessage('Příspěvek byl úspěšně upraven.');
            header('Location: ' . BASE_URL . '/index.php?url=post/show/' . $id);
        } else {
            $this->addErrorMessage('Příspěvek se nepodařilo upravit.');
            header('Location: ' . BASE_URL . '/index.php?url=post/edit/' . $id);
        }
        exit;
    }

    // Smazání příspěvku
    public function delete($id = null) {
        $this->requireLogin();
        if (!$id) { header('Location: ' . BASE_URL . '/index.php'); exit; }

        require_once '../app/models/Post.php';
        $postModel = new Post($this->db());
        $post = $postModel->getById((int)$id);

        if (!$post) { $this->addErrorMessage('Příspěvek nebyl nalezen.'); header('Location: ' . BASE_URL . '/index.php'); exit; }
        if ($post['created_by'] !== $_SESSION['user_id'] && !$this->isModerator()) {
            $this->addErrorMessage('Nemáš oprávnění smazat tento příspěvek.');
            header('Location: ' . BASE_URL . '/index.php'); exit;
        }

        if ($postModel->delete((int)$id)) {
            $this->addSuccessMessage('Příspěvek byl smazán.');
        } else {
            $this->addErrorMessage('Příspěvek se nepodařilo smazat.');
        }
        header('Location: ' . BASE_URL . '/index.php');
        exit;
    }
}