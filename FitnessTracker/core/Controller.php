<?php

class Controller {
    public function model($model) {
        require_once __DIR__ . '/../app/models/' . $model . '.php';
        return new $model();
    }

    public function view($view, $data = []) {
        if (file_exists(__DIR__ . '/../app/views/' . $view . '.php')) {
            require_once __DIR__ . '/../app/views/' . $view . '.php';
        } else {
            die('View does not exist: ' . $view);
        }
    }
}