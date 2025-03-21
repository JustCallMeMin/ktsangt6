<?php
class Core {
    protected $currentController = 'pages';
    protected $currentMethod = 'index';
    protected $params = [];
    protected $appDirectory;

    public function __construct() {
        try {
            // Add detailed error logging
            error_log('Core constructor started');
            
            // Define the app directory path
            $this->appDirectory = dirname(dirname(__FILE__));
            error_log('App directory: ' . $this->appDirectory);
            
            $url = $this->getUrl();
            
            // Debug URL
            error_log('URL: ' . print_r($url, true));

            // Look in controllers for first value
            if(isset($url[0])) {
                $controllerName = strtolower($url[0]);
                $controllerClass = ucwords($controllerName) . 'Controller';
                $controllerFile = $this->appDirectory . '/controllers/' . $controllerClass . '.php';
                error_log('Looking for controller: ' . $controllerFile);
                
                if(file_exists($controllerFile)) {
                    // If exists, set as controller
                    $this->currentController = $controllerName;
                    // Unset 0 Index
                    unset($url[0]);
                } else {
                    error_log('Controller file not found: ' . $controllerFile);
                }
            }

            // Require the controller
            $controllerClass = ucwords($this->currentController) . 'Controller';
            $controllerFile = $this->appDirectory . '/controllers/' . $controllerClass . '.php';
            error_log('Loading controller: ' . $controllerFile);
            
            if(!file_exists($controllerFile)) {
                throw new Exception('Controller file not found: ' . $controllerFile);
            }
            
            require_once $controllerFile;

            // Instantiate controller class
            $this->currentController = new $controllerClass;

            // Check for second part of url
            if(isset($url[1])) {
                // Check to see if method exists in controller
                if(method_exists($this->currentController, $url[1])) {
                    $this->currentMethod = $url[1];
                    // Unset 1 index
                    unset($url[1]);
                } else {
                    throw new Exception('Method ' . $url[1] . ' not found in controller ' . $controllerClass);
                }
            }

            // Get params
            $this->params = $url ? array_values($url) : [];

            // Debug final routing
            error_log('Controller: ' . get_class($this->currentController));
            error_log('Method: ' . $this->currentMethod);
            error_log('Params: ' . print_r($this->params, true));

            // Call a callback with array of params
            call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
        } catch (Exception $e) {
            error_log('Core error: ' . $e->getMessage());
            // Display a friendly error page
            include $this->appDirectory . '/views/pages/error.php';
        }
    }

    public function getUrl() {
        if(isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
        return [];
    }
} 