<?php
// Chỉ khai báo các hàm không trùng với session_helper.php

// Get current URL
function getCurrentUrl() {
    if(isset($_GET['url'])) {
        return rtrim($_GET['url'], '/');
    }
    return '';
}

// Check if current page matches given page
function isCurrentPage($page) {
    return getCurrentUrl() === $page;
} 