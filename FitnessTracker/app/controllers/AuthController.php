<?php

class AuthController {

    // 1. Zobrazení registračního formuláře
    public function register() {
        require_once '../app/views/auth/register.php';
    }

    // 2. Zpracování dat z registrace
    public function storeUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $username = htmlspecialchars($_POST['username'] ?? '');
            $email = htmlspecialchars($_POST['email'] ?? '');
            $firstName = htmlspecialchars($_POST['first_name'] ?? '');
            $lastName = htmlspecialchars($_POST['last_name'] ?? '');
            $nickname = htmlspecialchars($_POST['nickname'] ?? '');
            
            $password = $_POST['password'] ?? '';
            $passwordConfirm = $_POST['password_confirm'] ?? '';

            if (empty($username) || empty($email) || empty($password)) {
                $this->addErrorMessage('Vyplňte prosím všechna povinná pole.');
                header('Location: /WA-2026-Kredba-Filip-Patrik/FitnessTracker/public/index.php?url=auth/register');
                exit;
            }

            if ($password !== $passwordConfirm) {
                $this->addErrorMessage('Zadaná hesla se neshodují.');
                header('Location: /WA-2026-Kredba-Filip-Patrik/FitnessTracker/public/index.php?url=auth/register');
                exit;
            }

            // Požadavek na silné heslo: min. 8 znaků, alespoň jedno písmeno a jedna číslice
            if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d).{8,}$/', $password)) {
                $this->addErrorMessage('Heslo musí mít alespoň 8 znaků a obsahovat písmeno i číslici.');
                header('Location: /WA-2026-Kredba-Filip-Patrik/FitnessTracker/public/index.php?url=auth/register');
                exit;
            }

            // PŮVODNÍ STRUKTURA PŘIPOJENÍ
            require_once '../app/models/Database.php';
            require_once '../app/models/User.php';
            
            $db = (new Database())->getConnection();
            $userModel = new User($db);

            // Kontrola uživatelského jména navíc
            if ($userModel->getByUsername($username)) {
                $this->addErrorMessage('Toto uživatelské jméno je již obsazené.');
                header('Location: /WA-2026-Kredba-Filip-Patrik/FitnessTracker/public/index.php?url=auth/register');
                exit;
            }

            if ($userModel->register($username, $email, $password, $firstName, $lastName, $nickname)) {
                $this->addSuccessMessage('Registrace byla úspěšná. Nyní se můžete přihlásit.');
                header('Location: /WA-2026-Kredba-Filip-Patrik/FitnessTracker/public/index.php?url=auth/login');
                exit;
            } else {
                $this->addErrorMessage('Uživatel s tímto e-mailem již existuje.');
                header('Location: /WA-2026-Kredba-Filip-Patrik/FitnessTracker/public/index.php?url=auth/register');
                exit;
            }
        }
    }

    // 3. Zobrazení přihlašovacího formuláře
    public function login() {
        require_once '../app/views/auth/login.php';
    }

    // 4. Zpracování přihlášení
    public function authenticate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = htmlspecialchars($_POST['username'] ?? ''); 
            $password = $_POST['password'] ?? '';

            // PŮVODNÍ STRUKTURA PŘIPOJENÍ
            require_once '../app/models/Database.php';
            require_once '../app/models/User.php';
            
            $db = (new Database())->getConnection();
            $userModel = new User($db);
            
            $user = $userModel->getByUsername($username); 

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['username'];
                $_SESSION['is_admin'] = $user['is_admin'] ?? 0;
                
                $this->addSuccessMessage('Vítej zpět, ' . $user['username'] . '!');
                header('Location: /WA-2026-Kredba-Filip-Patrik/FitnessTracker/public/index.php');
                exit;
            } else {
                $this->addErrorMessage('Nesprávné jméno nebo heslo.');
                header('Location: /WA-2026-Kredba-Filip-Patrik/FitnessTracker/public/index.php?url=auth/login');
                exit;
            }
        }
    }

    // 5. Odhlášení uživatele
    public function logout() {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_name']);
        unset($_SESSION['is_admin']);
        
        $this->addSuccessMessage('Byli jste úspěšně odhlášeni.');
        header('Location: /WA-2026-Kredba-Filip-Patrik/FitnessTracker/public/index.php');
        exit;
    }

    // --- Pomocné metody pro notifikace (Tvé původní) ---
    protected function addSuccessMessage($message) {
        $_SESSION['messages']['success'][] = $message;
    }

    protected function addNoticeMessage($message) {
        $_SESSION['messages']['notice'][] = $message;
    }

    protected function addErrorMessage($message) {
        $_SESSION['messages']['error'][] = $message;
    }
}