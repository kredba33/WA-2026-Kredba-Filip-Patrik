<?php

require_once __DIR__ . '/../../core/Model.php';

class Comment extends Model {

    public function __construct() {
        parent::__construct();
    }

    // Komentáře k jednomu tréninku, JOIN na uživatele (jméno + nickname)
    public function getByWorkout(int $workoutId): array {
        $sql = "SELECT comments.*,
                       users.username AS author_username,
                       users.nickname AS author_nickname
                FROM comments
                LEFT JOIN users ON comments.user_id = users.id
                WHERE comments.workout_id = :wid
                ORDER BY comments.created_at ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':wid' => $workoutId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Jeden komentář (pro edit/delete oprávnění)
    public function getById(int $id) {
        $stmt = $this->db->prepare("SELECT * FROM comments WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Vložení nového komentáře
    public function create(int $workoutId, int $userId, string $content): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO comments (workout_id, user_id, content) VALUES (:wid, :uid, :c)"
        );
        return $stmt->execute([
            ':wid' => $workoutId,
            ':uid' => $userId,
            ':c'   => $content,
        ]);
    }

    // Úprava obsahu komentáře
    public function update(int $id, string $content): bool {
        $stmt = $this->db->prepare("UPDATE comments SET content = :c WHERE id = :id");
        return $stmt->execute([':c' => $content, ':id' => $id]);
    }

    // Smazání komentáře
    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM comments WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
