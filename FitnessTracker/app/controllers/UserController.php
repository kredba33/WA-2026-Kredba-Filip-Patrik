<?php

class UserController {

    private $userModel;

    public function __construct() {
        require_once __DIR__ . '/../models/Database.php';
        require_once __DIR__ . '/../models/User.php';
        $db = (new Database())->getConnection();
        $this->userModel = new User($db);
    }

    private function redirect(string $url) {
        header('Location: /WA-2026-Kredba-Filip-Patrik/FitnessTracker/public/index.php' . $url);
        exit;
    }

    private function isAdmin(): bool {
        return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
    }

    // Vlastní profil – zobrazení a formulář pro úpravu
    public function profile() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['messages']['error'][] = 'Pro zobrazení profilu se musíte přihlásit.';
            $this->redirect('?url=auth/login');
        }
        $user = $this->userModel->findById((int)$_SESSION['user_id']);
        require_once __DIR__ . '/../views/users/profile.php';
    }

    // Uložení změn profilu (POST)
    public function update() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('?url=auth/login');
        }
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?url=user/profile');
        }

        $userId = (int)$_SESSION['user_id'];

        $newEmail = htmlspecialchars($_POST['email'] ?? '');

        // Kontrola unikátnosti emailu (pokud se mění)
        $existing = $this->userModel->findByEmail($newEmail);
        if ($existing && (int)$existing['id'] !== $userId) {
            $_SESSION['messages']['error'][] = 'Tento e-mail už používá jiný uživatel.';
            $this->redirect('?url=user/profile');
        }

        $data = [
            'email'      => $newEmail,
            'first_name' => htmlspecialchars($_POST['first_name'] ?? ''),
            'last_name'  => htmlspecialchars($_POST['last_name'] ?? ''),
            'nickname'   => htmlspecialchars($_POST['nickname'] ?? ''),
            'height_cm'  => !empty($_POST['height_cm']) ? (int)$_POST['height_cm'] : null,
            'weight_kg'  => !empty($_POST['weight_kg']) ? (float)$_POST['weight_kg'] : null,
        ];
        $this->userModel->update($userId, $data);

        // Volitelná změna hesla
        if (!empty($_POST['new_password'])) {
            $newPwd = $_POST['new_password'];
            $confirm = $_POST['new_password_confirm'] ?? '';

            if ($newPwd !== $confirm) {
                $_SESSION['messages']['error'][] = 'Nová hesla se neshodují.';
            } elseif (!preg_match('/^(?=.*[A-Za-z])(?=.*\d).{8,}$/', $newPwd)) {
                $_SESSION['messages']['error'][] = 'Heslo musí mít alespoň 8 znaků a obsahovat písmeno i číslici.';
            } else {
                $hash = password_hash($newPwd, PASSWORD_DEFAULT);
                $this->userModel->updatePassword($userId, $hash);
                $_SESSION['messages']['success'][] = 'Heslo bylo změněno.';
            }
        }

        $_SESSION['messages']['success'][] = 'Profil byl uložen.';
        $this->redirect('?url=user/profile');
    }

    // Administrace: seznam všech uživatelů
    public function index() {
        if (!isset($_SESSION['user_id']) || !$this->isAdmin()) {
            $_SESSION['messages']['error'][] = 'Tato sekce je dostupná jen administrátorovi.';
            $this->redirect('');
        }
        $users = $this->userModel->getAll();
        require_once __DIR__ . '/../views/users/list.php';
    }

    // Administrace: smazání uživatele
    public function delete($id) {
        if (!isset($_SESSION['user_id']) || !$this->isAdmin()) {
            $_SESSION['messages']['error'][] = 'Smazat uživatele může pouze administrátor.';
            $this->redirect('');
        }
        $id = (int)$id;
        if ($id === (int)$_SESSION['user_id']) {
            $_SESSION['messages']['error'][] = 'Sám sebe smazat nemůžeš.';
            $this->redirect('?url=user/index');
        }
        $this->userModel->delete($id);
        $_SESSION['messages']['success'][] = 'Uživatel byl smazán (včetně jeho tréninků).';
        $this->redirect('?url=user/index');
    }
}
