<?php

/**
 * Data Transfer Object pro trénink.
 * Místo posílání 12 jednotlivých parametrů do modelu předáme jeden objekt s daty.
 */
class WorkoutDTO {
    public $title;
    public $location;
    public $category;
    public $subcategory;
    public $workout_date;
    public $duration_min;
    public $calories_burned;
    public $link;
    public $notes;
    public $rpe;
    public $images;
    public $exercises;

    // Konstruktor naplní objekt daty hned při jeho vytvoření
    public function __construct($data) {
        $this->title           = $data['title']           ?? '';
        $this->location        = $data['location']        ?? '';
        $this->category        = $data['category']        ?? null;
        $this->subcategory     = $data['subcategory']     ?? null;
        $this->workout_date    = $data['workout_date']    ?? date('Y-m-d');
        $this->duration_min    = $data['duration_min']    ?? 0;
        $this->calories_burned = $data['calories_burned'] ?? null;
        $this->link            = $data['link']            ?? '';
        $this->notes           = $data['notes']           ?? '';
        $this->rpe             = $data['rpe']             ?? null;
        $this->images          = $data['images']          ?? [];
        $this->exercises       = $data['exercises']       ?? [];
    }
}
