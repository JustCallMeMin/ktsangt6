<?php
class Router {
    private $projectRoot;
    private $publicPath;
    private $staticExtensions = ['css', 'js', 'jpg', 'jpeg', 'png', 'gif', 'ico', 'svg'];

    public function __construct() {
        try {
            error_log('Router constructor started');
            $this->projectRoot = dirname(dirname(__DIR__));
            $this->publicPath = $this->projectRoot . '/public';
            
            error_log('Project root: ' . $this->projectRoot);
            error_log('Public path: ' . $this->publicPath);
            
            // Change working directory to project root
            chdir($this->projectRoot);
            error_log('Changed directory to: ' . getcwd());

            // Load config
            require_once 'app/config/config.php';
            error_log('Config loaded');
        } catch (Exception $e) {
            error_log('Router construction error: ' . $e->getMessage());
            $this->displayError($e->getMessage());
        }
    }

    public function route() {
        try {
            error_log('Router route method started');
            $uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
            error_log('Decoded URI: ' . $uri);

            // Get file extension if any
            $extension = pathinfo($uri, PATHINFO_EXTENSION);
            error_log('File extension: ' . $extension);

            // If requesting a static file with known extension
            if ($extension && in_array(strtolower($extension), $this->staticExtensions)) {
                $filePath = $this->publicPath . $uri;
                error_log('Static file path: ' . $filePath);
                
                if (file_exists($filePath)) {
                    error_log('Static file found, serving directly');
                    // Set appropriate content type
                    switch (strtolower($extension)) {
                        case 'css':
                            header('Content-Type: text/css');
                            break;
                        case 'js':
                            header('Content-Type: application/javascript');
                            break;
                        case 'jpg':
                        case 'jpeg':
                            header('Content-Type: image/jpeg');
                            break;
                        case 'png':
                            header('Content-Type: image/png');
                            break;
                        case 'gif':
                            header('Content-Type: image/gif');
                            break;
                        case 'svg':
                            header('Content-Type: image/svg+xml');
                            break;
                    }
                    
                    // Output the file and exit
                    readfile($filePath);
                    exit;
                }
            }
            
            // Handle uploads directory separately
            if (strpos($uri, '/uploads/') === 0) {
                $filePath = $this->publicPath . $uri;
                error_log('Uploads file path: ' . $filePath);
                
                if (file_exists($filePath)) {
                    error_log('Upload file found, serving directly');
                    // Set appropriate content type based on extension
                    $uploadExt = pathinfo($filePath, PATHINFO_EXTENSION);
                    switch (strtolower($uploadExt)) {
                        case 'jpg':
                        case 'jpeg':
                            header('Content-Type: image/jpeg');
                            break;
                        case 'png':
                            header('Content-Type: image/png');
                            break;
                        case 'gif':
                            header('Content-Type: image/gif');
                            break;
                    }
                    
                    // Output the file and exit
                    readfile($filePath);
                    exit;
                }
            }
            
            // Check if it's the root URL or other route
            if ($uri == '/' || $uri == '') {
                error_log('Root URI detected');
                $_GET['url'] = '';
            } else {
                // If requesting any other route
                $_GET['url'] = trim($uri, '/');
            }
            
            error_log('Set $_GET[url] to: ' . $_GET['url']);
            
            // Load core libraries
            require_once 'app/core/Core.php';
            require_once 'app/core/Controller.php';
            require_once 'app/core/Database.php';
            error_log('Core libraries loaded');
            
            // Load helpers
            require_once 'app/helpers/url_helper.php';
            require_once 'app/helpers/session_helper.php';
            error_log('Helpers loaded');

            // Change to public directory and include index.php
            chdir($this->publicPath);
            error_log('Changed directory to: ' . getcwd());
            error_log('About to require index.php');
            require 'index.php';
            error_log('index.php loaded successfully');
        } catch (Exception $e) {
            error_log('Routing error: ' . $e->getMessage());
            $this->displayError($e->getMessage());
        }
    }
    
    private function displayError($message = '') {
        // Log the error
        error_log('Application error: ' . $message);
        
        // Set HTTP response code
        http_response_code(500);
        
        // Change to project root to ensure proper path resolution
        chdir($this->projectRoot);
        
        // Define APPROOT if not already defined (needed for includes in error page)
        if (!defined('APPROOT')) {
            define('APPROOT', $this->projectRoot . '/app');
        }
        
        // Define URLROOT if not already defined (needed for links in error page)
        if (!defined('URLROOT')) {
            // Try to get it from config if possible
            if (file_exists($this->projectRoot . '/app/config/config.php')) {
                include_once $this->projectRoot . '/app/config/config.php';
            } else {
                // Fallback
                define('URLROOT', 'http://localhost:8080');
            }
        }
        
        // Include the error page
        include $this->projectRoot . '/app/views/pages/error.php';
        exit;
    }
} 