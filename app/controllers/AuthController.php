<?php
class AuthController extends Controller {
    private $studentModel;

    public function __construct() {
        try {
            $this->studentModel = $this->model('Student');
        } catch (Exception $e) {
            die('Không thể kết nối đến dữ liệu sinh viên: ' . $e->getMessage());
        }
    }

    public function login() {
        try {
            // Kiểm tra nếu đã đăng nhập thì chuyển về trang chủ
            if(isset($_SESSION['user_id'])) {
                redirect('');
            }

            // Kiểm tra form submit
            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Xử lý POST
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                // Dữ liệu form
                $data = [
                    'masv' => trim($_POST['masv']),
                    'masv_err' => ''
                ];

                // Kiểm tra mã sinh viên
                if(empty($data['masv'])) {
                    $data['masv_err'] = 'Vui lòng nhập mã sinh viên';
                } else {
                    // Kiểm tra sinh viên có tồn tại
                    $student = $this->studentModel->getStudentById($data['masv']);
                    if(!$student) {
                        $data['masv_err'] = 'Mã sinh viên không tồn tại';
                    }
                }

                // Đảm bảo không có lỗi
                if(empty($data['masv_err'])) {
                    // Đăng nhập thành công
                    // Lưu session
                    $_SESSION['user_id'] = $student->MaSV;
                    $_SESSION['user_name'] = $student->HoTen;
                    
                    // Redirect to courses page
                    redirect('courses');
                } else {
                    // Load view với lỗi
                    $this->view('auth/login', $data);
                }
            } else {
                // Init data
                $data = [
                    'masv' => '',
                    'masv_err' => ''
                ];

                // Load view
                $this->view('auth/login', $data);
            }
        } catch (Exception $e) {
            error_log('Lỗi đăng nhập: ' . $e->getMessage());
            $data = [
                'masv' => isset($_POST['masv']) ? $_POST['masv'] : '',
                'masv_err' => 'Đã xảy ra lỗi trong quá trình đăng nhập: ' . $e->getMessage()
            ];
            $this->view('auth/login', $data);
        }
    }

    public function logout() {
        try {
            // Xóa các biến session
            unset($_SESSION['user_id']);
            unset($_SESSION['user_name']);
            session_destroy();
            
            // Redirect về trang đăng nhập
            redirect('auth/login');
        } catch (Exception $e) {
            error_log('Lỗi đăng xuất: ' . $e->getMessage());
            flash('logout_error', 'Đã xảy ra lỗi khi đăng xuất: ' . $e->getMessage(), 'alert alert-danger');
            redirect('');
        }
    }
} 