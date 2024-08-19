<?php

class Controller {
    public function __construct() {
        $this->validation = new Validation();
    }

    public function validate($data, $rules) {
        return $this->validation->validate($data, $rules);
    }

    public function errors() {
        return $this->validation->errors();
    }
    
    // Load and return model instance
    protected function model($model) {
        $modelPath = __DIR__ . '/../models/' . $model . '.php';
        if (file_exists($modelPath)) {
            require_once $modelPath;
            return new $model();
        } else {
            throw new Exception("Model $model not found.");
        }
    }

    // Render the view with the data
    protected function view($view, $data = []) {
        $viewPath = __DIR__ . '/../views/' . $view . '.php';
        if (file_exists($viewPath)) {
            // Make data available as variables in the view
            foreach ($data as $key => $value) {
                ${$key} = $value;
            }
            require_once $viewPath;
        } else {
            throw new Exception("View $view not found.");
        }
    }
}
