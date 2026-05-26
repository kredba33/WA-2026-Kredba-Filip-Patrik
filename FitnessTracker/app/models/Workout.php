<?php

require_once __DIR__ . '/../../core/Model.php';
require_once __DIR__ . '/../dto/WorkoutDTO.php';

class Workout extends Model {

    public function __construct() {
        parent::__construct(); 
    }

    // Volitelné filtry: kategorie a hledání podle názvu
    public function getAllWorkouts($category = null, $search = null) {
        $sql = "SELECT workouts.*, categories.name AS category_name, subcategories.name AS subcategory_name,
                       author.username AS author_name
                FROM workouts
                LEFT JOIN categories ON workouts.category = categories.id
                LEFT JOIN subcategories ON workouts.subcategory = subcategories.id
                LEFT JOIN users AS author ON workouts.created_by = author.id
                WHERE 1=1";

        $params = [];
        if (!empty($category)) {
            $sql .= " AND workouts.category = :category";
            $params[':category'] = $category;
        }
        if (!empty($search)) {
            $sql .= " AND workouts.title LIKE :search";
            $params[':search'] = '%' . $search . '%';
        }
        $sql .= " ORDER BY workouts.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Vývoj tréninků v čase (pro spojnicový graf) – seřazeno chronologicky
    public function getProgress($userId) {
        $sql = "SELECT workout_date, duration_min, calories_burned, rpe, exercises
                FROM workouts
                WHERE created_by = :user_id
                ORDER BY workout_date ASC, id ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserStats($userId) {
        $sql = "SELECT
                    COUNT(id) as total_workouts,
                    SUM(duration_min) as total_minutes,
                    SUM(calories_burned) as total_calories,
                    AVG(rpe) as avg_rpe
                FROM workouts
                WHERE created_by = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return [
            'total_workouts' => $result['total_workouts'] ?? 0,
            'total_minutes' => $result['total_minutes'] ?? 0,
            'total_calories' => $result['total_calories'] ?? 0,
            'avg_rpe' => $result['avg_rpe'] !== null ? round($result['avg_rpe'], 1) : null
        ];
    }

    // Vytvoření tréninku přes DTO (místo 12 jednotlivých parametrů).
    public function create(WorkoutDTO $dto, int $userId): bool {

        $sql = "INSERT INTO workouts (title, location, category, subcategory, workout_date, duration_min, calories_burned, notes, link, rpe, images, exercises, created_by)
                VALUES (:title, :location, :category, :subcategory, :workout_date, :duration_min, :calories_burned, :notes, :link, :rpe, :images, :exercises, :created_by)";

        $stmt = $this->db->prepare($sql);
        $imagesData = is_array($dto->images) ? json_encode($dto->images) : $dto->images;

        return $stmt->execute([
            ':title' => $dto->title,
            ':location' => $dto->location,
            ':category' => $dto->category,
            ':subcategory' => $dto->subcategory,
            ':workout_date' => $dto->workout_date,
            ':duration_min' => (int)$dto->duration_min,
            ':calories_burned' => $dto->calories_burned,
            ':notes' => $dto->notes,
            ':link' => $dto->link,
            ':rpe' => $dto->rpe,
            ':images' => $imagesData,
            ':exercises' => json_encode($dto->exercises),
            ':created_by' => $userId
        ]);
    }

    public function getById($id) {
        $sql = "SELECT workouts.*, categories.name AS category_name, subcategories.name AS subcategory_name,
                       author.username AS author_name,
                       editor.username AS editor_name
                FROM workouts
                LEFT JOIN categories ON workouts.category = categories.id
                LEFT JOIN subcategories ON workouts.subcategory = subcategories.id
                LEFT JOIN users AS author ON workouts.created_by = author.id
                LEFT JOIN users AS editor ON workouts.updated_by = editor.id
                WHERE workouts.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Aktualizace tréninku přes DTO.
    public function update($id, WorkoutDTO $dto, $updatedBy = null) {
        $sql = "UPDATE workouts
                SET title = :title,
                    location = :location,
                    category = :category,
                    subcategory = :subcategory,
                    workout_date = :workout_date,
                    duration_min = :duration_min,
                    calories_burned = :calories_burned,
                    notes = :notes,
                    link = :link,
                    rpe = :rpe,
                    images = :images,
                    exercises = :exercises,
                    updated_by = :updated_by
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $imagesData = is_array($dto->images) ? json_encode($dto->images) : $dto->images;
        $exercisesData = is_array($dto->exercises) ? json_encode($dto->exercises) : $dto->exercises;

        return $stmt->execute([
            ':id' => $id,
            ':title' => $dto->title,
            ':location' => $dto->location,
            ':category' => $dto->category,
            ':subcategory' => $dto->subcategory,
            ':workout_date' => $dto->workout_date,
            ':duration_min' => (int)$dto->duration_min,
            ':calories_burned' => $dto->calories_burned,
            ':notes' => $dto->notes,
            ':link' => $dto->link,
            ':rpe' => $dto->rpe,
            ':images' => $imagesData,
            ':exercises' => $exercisesData,
            ':updated_by' => $updatedBy
        ]);
    }

    public function delete($id) {
        $sql = "DELETE FROM workouts WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}