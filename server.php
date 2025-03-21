<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Print actual request info for debugging
error_log('REQUEST_URI: ' . $_SERVER['REQUEST_URI']);
error_log('SCRIPT_NAME: ' . $_SERVER['SCRIPT_NAME']);
error_log('DOCUMENT_ROOT: ' . $_SERVER['DOCUMENT_ROOT']);

// Bootstrap file for PHP built-in server
require_once __DIR__ . '/app/core/Router.php';

try {
    $router = new Router();
    $router->route();
} catch (Exception $e) {
    // Log error and show friendly message
    error_log('EXCEPTION: ' . $e->getMessage());
    http_response_code(500);
    echo "Server Error: " . $e->getMessage();
} 