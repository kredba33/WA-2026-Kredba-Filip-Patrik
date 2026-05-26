<?php

class CommentController {

    private $commentModel;

    public function __construct() {
        require_once __DIR__ . '/../models/Comment.php';
        $this->commentModel = new Comment();
    }

    private function redirect(string $url) {
        header('Location: /WA-2026-Kredba-Filip-Patrik/FitnessTracker/public/index.php' . $url);
        exit;
    }

    private function isAdmin(): bool {
        return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
    }

    // Vložení komentáře k tréninku (POST)
    public function store($workoutId) {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['messages']['error'][] = 'Pro přidání komentáře se musíš přihlásit.';
            $this->redirect('?url=auth/login');
        }
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?url=workout/show/' . (int)$workoutId);
        }

        $content = trim(htmlspecialchars($_POST['content'] ?? ''));
        if ($content === '') {
            $_SESSION['messages']['error'][] = 'Komentář nemůže být prázdný.';
            $this->redirect('?url=workout/show/' . (int)$workoutId);
        }

        $this->commentModel->create((int)$workoutId, (int)$_SESSION['user_id'], $content);
        $_SESSION['messages']['success'][] = 'Komentář byl přidán.';
        $this->redirect('?url=workout/show/' . (int)$workoutId);
    }

    // Formulář pro úpravu (jen autor)
    public function edit($id) {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('?url=auth/login');
        }
        $comment = $this->commentModel->getById((int)$id);
        if (!$comment) {
            $_SESSION['messages']['error'][] = 'Komentář nenalezen.';
            $this->redirect('');
        }
        if ((int)$comment['user_id'] !== (int)$_SESSION['user_id']) {
            $_SESSION['messages']['error'][] = 'Upravovat smí jen autor komentáře.';
            $this->redirect('?url=workout/show/' . (int)$comment['workout_id']);
        }
        require_once __DIR__ . '/../views/comments/comment_edit.php';
    }

    // Uložení úpravy (POST)
    public function update($id) {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('?url=auth/login');
        }
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?url=comment/edit/' . (int)$id);
        }
        $comment = $this->commentModel->getById((int)$id);
        if (!$comment) {
            $_SESSION['messages']['error'][] = 'Komentář nenalezen.';
            $this->redirect('');
        }
        if ((int)$comment['user_id'] !== (int)$_SESSION['user_id']) {
            $_SESSION['messages']['error'][] = 'Upravovat smí jen autor komentáře.';
            $this->redirect('?url=workout/show/' . (int)$comment['workout_id']);
        }

        $content = trim(htmlspecialchars($_POST['content'] ?? ''));
        if ($content === '') {
            $_SESSION['messages']['error'][] = 'Komentář nemůže být prázdný.';
            $this->redirect('?url=comment/edit/' . (int)$id);
        }

        $this->commentModel->update((int)$id, $content);
        $_SESSION['messages']['success'][] = 'Komentář byl upraven.';
        $this->redirect('?url=workout/show/' . (int)$comment['workout_id']);
    }

    // Smazání (autor nebo admin)
    public function delete($id) {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('?url=auth/login');
        }
        $comment = $this->commentModel->getById((int)$id);
        if (!$comment) {
            $_SESSION['messages']['error'][] = 'Komentář nenalezen.';
            $this->redirect('');
        }
        if ((int)$comment['user_id'] !== (int)$_SESSION['user_id'] && !$this->isAdmin()) {
            $_SESSION['messages']['error'][] = 'Smazat může jen autor komentáře nebo administrátor.';
            $this->redirect('?url=workout/show/' . (int)$comment['workout_id']);
        }
        $this->commentModel->delete((int)$id);
        $_SESSION['messages']['success'][] = 'Komentář byl smazán.';
        $this->redirect('?url=workout/show/' . (int)$comment['workout_id']);
    }
}
