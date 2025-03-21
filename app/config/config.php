<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'Tnhminh33');
define('DB_NAME', 'Test1');

// App Root
define('APPROOT', dirname(dirname(__FILE__)));
// URL Root
define('URLROOT', 'http://localhost:8080');
// Site Name
define('SITENAME', 'Hệ thống quản lý sinh viên');

// Load Helpers
require_once APPROOT . '/helpers/session_helper.php'; 