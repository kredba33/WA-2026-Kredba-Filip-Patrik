<?php

class WorkoutController {

    private $workoutModel;

    public function __construct() {
        require_once __DIR__ . '/../models/Workout.php';
        $this->workoutModel = new Workout();
    }

    public function index() {
        // Filtry z URL (GET)
        $filterCategory = !empty($_GET['category']) ? (int)$_GET['category'] : null;
        $filterSearch = !empty($_GET['q']) ? trim($_GET['q']) : null;

        $workouts = $this->workoutModel->getAllWorkouts($filterCategory, $filterSearch);

        // Kategorie pro filtr (rozbalovací seznam)
        require_once __DIR__ . '/../models/Category.php';
        $categories = (new Category())->getAllCategories();

        $stats = null;
        $progress = null;
        if (isset($_SESSION['user_id'])) {
            $stats = $this->workoutModel->getUserStats($_SESSION['user_id']);
            $progress = $this->workoutModel->getProgress($_SESSION['user_id']);
        }

        $data = [
            'workouts' => $workouts,
            'stats' => $stats,
            'progress' => $progress,
            'categories' => $categories,
            'filterCategory' => $filterCategory,
            'filterSearch' => $filterSearch
        ];

        require_once __DIR__ . '/../views/workouts/workouts_list.php';
    }

    public function create() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['messages']['error'][] = 'Pro zapsání tréninku se musíte nejprve přihlásit.';
            header('Location: /WA-2026-Kredba-Filip-Patrik/FitnessTracker/public/index.php?url=auth/login');
            exit;
        }

        require_once __DIR__ . '/../models/Category.php';
        require_once __DIR__ . '/../models/Subcategory.php';
        $categories = (new Category())->getAllCategories();
        $subcategories = (new Subcategory())->getAllSubcategories();

        require_once __DIR__ . '/../views/workouts/workout_create.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_SESSION['user_id'])) {
                header('Location: /WA-2026-Kredba-Filip-Patrik/FitnessTracker/public/index.php?url=auth/login');
                exit;
            }
            $userId = $_SESSION['user_id'];

            // Sestavení DTO z očištěných vstupů (htmlspecialchars + přetypování čísel)
            $dto = new WorkoutDTO([
                'title'           => htmlspecialchars($_POST['title'] ?? ''),
                'location'        => htmlspecialchars($_POST['location'] ?? ''),
                'category'        => !empty($_POST['category']) ? (int)$_POST['category'] : null,
                'subcategory'     => !empty($_POST['subcategory']) ? (int)$_POST['subcategory'] : null,
                'workout_date'    => !empty($_POST['workout_date']) ? htmlspecialchars($_POST['workout_date']) : date('Y-m-d'),
                'duration_min'    => intval($_POST['duration_min'] ?? 0),
                'calories_burned' => !empty($_POST['calories_burned']) ? intval($_POST['calories_burned']) : null,
                'link'            => htmlspecialchars($_POST['link'] ?? ''),
                'notes'           => htmlspecialchars($_POST['notes'] ?? ''),
                'rpe'             => !empty($_POST['rpe']) ? (int)$_POST['rpe'] : null,
                'images'          => $this->processImageUploads(),
                'exercises'       => $this->processExercises(),
            ]);

            $isSaved = $this->workoutModel->create($dto, $userId);

            $_SESSION['messages']['success'][] = 'Trénink byl úspěšně uložen!';
            header('Location: /WA-2026-Kredba-Filip-Patrik/FitnessTracker/public/index.php');
            exit;
        }
    }

    public function show($id) {
        $workout = $this->workoutModel->getById($id);

        // Načti komentáře k tréninku
        require_once __DIR__ . '/../models/Comment.php';
        $comments = (new Comment())->getByWorkout((int)$id);

        require_once __DIR__ . '/../views/workouts/workout_detail.php';
    }

    public function edit($id) {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /WA-2026-Kredba-Filip-Patrik/FitnessTracker/public/index.php?url=auth/login');
            exit;
        }

        $workout = $this->workoutModel->getById($id);
        $isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;

        if ($workout['created_by'] !== $_SESSION['user_id'] && !$isAdmin) {
            $_SESSION['messages']['error'][] = 'Nemáte oprávnění upravovat tento trénink.';
            header('Location: /WA-2026-Kredba-Filip-Patrik/FitnessTracker/public/index.php');
            exit;
        }

        require_once __DIR__ . '/../models/Category.php';
        require_once __DIR__ . '/../models/Subcategory.php';
        $categories = (new Category())->getAllCategories();
        $subcategories = (new Subcategory())->getAllSubcategories();

        require_once __DIR__ . '/../views/workouts/workout_edit.php';
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_SESSION['user_id'])) {
                header('Location: /WA-2026-Kredba-Filip-Patrik/FitnessTracker/public/index.php?url=auth/login');
                exit;
            }

            $userId = $_SESSION['user_id'];
            $workout = $this->workoutModel->getById($id);
            $isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;

            if ($workout['created_by'] !== $_SESSION['user_id'] && !$isAdmin) {
                $_SESSION['messages']['error'][] = 'Nemáte oprávnění ukládat změny u tohoto tréninku.';
                header('Location: /WA-2026-Kredba-Filip-Patrik/FitnessTracker/public/index.php');
                exit;
            }

            $uploadedImages = $this->processImageUploads();
            if (!empty($uploadedImages)) {
                // staré fotky se nahrazují novými -> smaž je z disku
                $this->cleanupImages($workout['images'] ?? '');
                $finalImages = $uploadedImages;
            } else {
                $finalImages = empty(trim($_POST['old_images'] ?? '')) ? '[]' : trim($_POST['old_images']);
            }

            $dto = new WorkoutDTO([
                'title'           => htmlspecialchars($_POST['title'] ?? ''),
                'location'        => htmlspecialchars($_POST['location'] ?? ''),
                'category'        => !empty($_POST['category']) ? (int)$_POST['category'] : null,
                'subcategory'     => !empty($_POST['subcategory']) ? (int)$_POST['subcategory'] : null,
                'workout_date'    => !empty($_POST['workout_date']) ? htmlspecialchars($_POST['workout_date']) : date('Y-m-d'),
                'duration_min'    => intval($_POST['duration_min'] ?? 0),
                'calories_burned' => !empty($_POST['calories_burned']) ? intval($_POST['calories_burned']) : null,
                'link'            => htmlspecialchars($_POST['link'] ?? ''),
                'notes'           => htmlspecialchars($_POST['notes'] ?? ''),
                'rpe'             => !empty($_POST['rpe']) ? (int)$_POST['rpe'] : null,
                'images'          => $finalImages,
                'exercises'       => $this->processExercises(),
            ]);

            $this->workoutModel->update($id, $dto, $userId);

            $_SESSION['messages']['success'][] = 'Trénink byl úspěšně upraven!';
            header('Location: /WA-2026-Kredba-Filip-Patrik/FitnessTracker/public/index.php');
            exit;
        }
    }

    public function delete($id) {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /WA-2026-Kredba-Filip-Patrik/FitnessTracker/public/index.php?url=auth/login');
            exit;
        }

        $workout = $this->workoutModel->getById($id);
        $isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;

        if ($workout['created_by'] !== $_SESSION['user_id'] && !$isAdmin) {
            $_SESSION['messages']['error'][] = 'Nemáte oprávnění smazat tento trénink.';
            header('Location: /WA-2026-Kredba-Filip-Patrik/FitnessTracker/public/index.php');
            exit;
        }

        // smaž osiřelé obrázky z disku PŘED smazáním záznamu
        $this->cleanupImages($workout['images'] ?? '');

        $this->workoutModel->delete($id);
        $_SESSION['messages']['success'][] = 'Trénink byl smazán.';
        header('Location: /WA-2026-Kredba-Filip-Patrik/FitnessTracker/public/index.php');
        exit;
    }

    // Smaže soubory obrázků (uložené v JSON sloupci images) z public/uploads/
    protected function cleanupImages($imagesJson) {
        if (empty($imagesJson)) return;
        $images = is_array($imagesJson) ? $imagesJson : json_decode($imagesJson, true);
        if (!is_array($images)) return;
        $uploadDir = __DIR__ . '/../../public/uploads/';
        foreach ($images as $img) {
            // basename() = ochrana proti path traversal v názvu souboru
            $path = $uploadDir . basename($img);
            if (is_file($path)) {
                @unlink($path);
            }
        }
    }

    protected function processImageUploads() {
        $uploadedFiles = [];
        $uploadDir = __DIR__ . '/../../public/uploads/';
        if (!is_dir($uploadDir)) { @mkdir($uploadDir, 0777, true); }
        if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
            foreach ($_FILES['images']['name'] as $i => $name) {
                if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                    if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
                        $newName = 'workout_' . uniqid() . '_' . substr(md5(mt_rand()), 0, 4) . '.' . $ext;
                        if (@move_uploaded_file($_FILES['images']['tmp_name'][$i], $uploadDir . $newName)) {
                            $uploadedFiles[] = $newName;
                        }
                    }
                }
            }
        }
        return $uploadedFiles;
    }

    // Posbírá cviky z formuláře (paralelní pole) do pole objektů
    protected function processExercises() {
        $result = [];
        if (!empty($_POST['exercise_name']) && is_array($_POST['exercise_name'])) {
            foreach ($_POST['exercise_name'] as $i => $name) {
                $name = trim(htmlspecialchars($name));
                if ($name === '') { continue; } // přeskoč prázdné řádky
                $result[] = [
                    'name'   => $name,
                    'weight' => (isset($_POST['exercise_weight'][$i]) && $_POST['exercise_weight'][$i] !== '') ? (float)$_POST['exercise_weight'][$i] : null,
                    'reps'   => (isset($_POST['exercise_reps'][$i]) && $_POST['exercise_reps'][$i] !== '') ? (int)$_POST['exercise_reps'][$i] : null,
                ];
            }
        }
        return $result;
    }
}