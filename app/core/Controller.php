<?php
class Controller {
    // Load model
    public function model($model) {
        try {
            // Require model file
            $modelPath = 'app/models/' . $model . '.php';
            
            // Kiểm tra nếu đường dẫn không tồn tại, thử đường dẫn tương đối khác
            if(!file_exists($modelPath)) {
                $modelPath = dirname(dirname(__FILE__)) . '/models/' . $model . '.php';
            }
            
            if(file_exists($modelPath)) {
                require_once $modelPath;
                // Instantiate model
                return new $model();
            } else {
                throw new Exception('Model ' . $model . ' not found');
            }
        } catch (Exception $e) {
            error_log('Error loading model: ' . $e->getMessage());
            throw $e; // Re-throw the exception to be caught by the controller
        }
    }

    // Load view
    public function view($view, $data = []) {
        try {
            // Check for view file
            $viewPath = 'app/views/' . $view . '.php';
            
            // Kiểm tra nếu đường dẫn không tồn tại, thử đường dẫn tương đối khác
            if(!file_exists($viewPath)) {
                $viewPath = dirname(dirname(__FILE__)) . '/views/' . $view . '.php';
            }
            
            if(file_exists($viewPath)) {
                require_once $viewPath;
            } else {
                // View does not exist
                throw new Exception('View does not exist: ' . $viewPath);
            }
        } catch (Exception $e) {
            error_log('Error loading view: ' . $e->getMessage());
            throw $e; // Re-throw the exception to be caught by the controller
        }
    }
} 