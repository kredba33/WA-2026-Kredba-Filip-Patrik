<?php

class User {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;    
    }

    // 1. Registrace nového uživatele
    public function register(
        string $username, 
        string $email, 
        string $password, 
        ?string $firstName = null, 
        ?string $lastName = null, 
        ?string $nickname = null
    ): bool {
        if ($this->findByEmail($email)) {
            return false; // Email už je zabraný
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (username, email, password, first_name, last_name, nickname) 
                VALUES (:username, :email, :password, :first_name, :last_name, :nickname)";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':password' => $hashedPassword,
            ':first_name' => $firstName,
            ':last_name' => $lastName,
            ':nickname' => $nickname
        ]);
    }

    // 2. Nalezení uživatele podle emailu
    public function findByEmail(string $email) {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // 3. Získání uživatele podle ID (vč. výšky/váhy pro profil)
    public function findById(int $id) {
        $sql = "SELECT id, username, email, first_name, last_name, nickname,
                       height_cm, weight_kg, is_admin, created_at
                FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 4. Získání uživatele podle uživatelského jména (pro login)
    public function getByUsername($username) {
        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 5. Úprava profilu (vše kromě hesla a uživatelského jména)
    public function update(int $id, array $data): bool {
        $sql = "UPDATE users SET
                    email = :email,
                    first_name = :first_name,
                    last_name = :last_name,
                    nickname = :nickname,
                    height_cm = :height_cm,
                    weight_kg = :weight_kg
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id'         => $id,
            ':email'      => $data['email']      ?? '',
            ':first_name' => $data['first_name'] ?? null,
            ':last_name'  => $data['last_name']  ?? null,
            ':nickname'   => $data['nickname']   ?? null,
            ':height_cm'  => $data['height_cm']  ?? null,
            ':weight_kg'  => $data['weight_kg']  ?? null,
        ]);
    }

    // 6. Změna hesla (uloží hash, ne plaintext)
    public function updatePassword(int $id, string $hashedPassword): bool {
        $stmt = $this->db->prepare("UPDATE users SET password = :p WHERE id = :id");
        return $stmt->execute([':p' => $hashedPassword, ':id' => $id]);
    }

    // 7. Seznam všech uživatelů (pro administrátora)
    public function getAll(): array {
        $stmt = $this->db->query(
            "SELECT id, username, email, first_name, last_name, nickname, is_admin, created_at
             FROM users ORDER BY id ASC"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 8. Smazání uživatele (pouze admin)
    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}