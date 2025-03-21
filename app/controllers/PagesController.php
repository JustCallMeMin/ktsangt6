<?php
class PagesController extends Controller {
    public function __construct() {
    }

    public function index() {
        $data = [
            'title' => 'Trang Chủ',
            'description' => 'Hệ thống đăng ký học phần'
        ];
        
        $this->view('pages/index', $data);
    }
} 