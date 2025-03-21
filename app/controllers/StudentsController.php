<?php
class StudentsController extends Controller {
    private $studentModel;
    private $majorModel;

    public function __construct() {
        try {
            $this->studentModel = $this->model('Student');
            $this->majorModel = $this->model('Major');
        } catch (Exception $e) {
            die('Không thể kết nối đến dữ liệu: ' . $e->getMessage());
        }
    }

    // List all students
    public function index() {
        try {
            $students = $this->studentModel->getStudents();
            $data = [
                'students' => $students
            ];
            $this->view('students/index', $data);
        } catch (Exception $e) {
            error_log('Lỗi lấy danh sách sinh viên: ' . $e->getMessage());
            flash('student_error', 'Không thể tải danh sách sinh viên: ' . $e->getMessage(), 'alert alert-danger');
            redirect('');
        }
    }

    // Show add form
    public function add() {
        try {
            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Process form
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                
                // Kiểm tra ngày sinh không nằm trong tương lai
                $ngaysinh = trim($_POST['ngaysinh']);
                if (strtotime($ngaysinh) > time()) {
                    throw new Exception("Ngày sinh không được nằm trong tương lai");
                }

                // Upload image if provided
                $targetDir = "uploads/";
                $fileName = basename($_FILES["hinh"]["name"]);
                $targetFilePath = $targetDir . $fileName;
                $fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);

                if(!empty($_FILES["hinh"]["name"])){
                    // Allow certain file formats
                    $allowTypes = array('jpg','png','jpeg','gif');
                    if(in_array($fileType, $allowTypes)){
                        // Upload file to server
                        if(move_uploaded_file($_FILES["hinh"]["tmp_name"], $targetFilePath)){
                            $data = [
                                'masv' => trim($_POST['masv']),
                                'hoten' => trim($_POST['hoten']),
                                'gioitinh' => trim($_POST['gioitinh']),
                                'ngaysinh' => $ngaysinh,
                                'hinh' => $targetFilePath,
                                'manganh' => trim($_POST['manganh'])
                            ];

                            if($this->studentModel->addStudent($data)) {
                                flash('student_message', 'Thêm sinh viên thành công', 'alert alert-success');
                                redirect('students');
                            } else {
                                throw new Exception('Không thể thêm sinh viên');
                            }
                        } else {
                            throw new Exception('Không thể tải lên hình ảnh');
                        }
                    } else {
                        throw new Exception('Chỉ chấp nhận file hình ảnh (JPG, JPEG, PNG, GIF)');
                    }
                } else {
                    throw new Exception('Vui lòng chọn hình ảnh');
                }
            } else {
                // Get list of majors for dropdown
                $majors = $this->majorModel->getMajors();
                
                $data = [
                    'masv' => '',
                    'hoten' => '',
                    'gioitinh' => '',
                    'ngaysinh' => '',
                    'hinh' => '',
                    'manganh' => '',
                    'majors' => $majors
                ];

                $this->view('students/add', $data);
            }
        } catch (Exception $e) {
            error_log('Lỗi thêm sinh viên: ' . $e->getMessage());
            flash('student_error', $e->getMessage(), 'alert alert-danger');
            
            // Get list of majors for dropdown để hiển thị lại form
            $majors = $this->majorModel->getMajors();
            
            $data = [
                'masv' => isset($_POST['masv']) ? trim($_POST['masv']) : '',
                'hoten' => isset($_POST['hoten']) ? trim($_POST['hoten']) : '',
                'gioitinh' => isset($_POST['gioitinh']) ? trim($_POST['gioitinh']) : '',
                'ngaysinh' => isset($_POST['ngaysinh']) ? trim($_POST['ngaysinh']) : '',
                'hinh' => '',
                'manganh' => isset($_POST['manganh']) ? trim($_POST['manganh']) : '',
                'majors' => $majors
            ];
            
            $this->view('students/add', $data);
        }
    }

    // Show edit form and process form submission
    public function edit($id) {
        try {
            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Process form
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                
                // Kiểm tra ngày sinh không nằm trong tương lai
                $ngaysinh = trim($_POST['ngaysinh']);
                if (strtotime($ngaysinh) > time()) {
                    throw new Exception("Ngày sinh không được nằm trong tương lai");
                }

                // Check if new image was uploaded
                if(!empty($_FILES["hinh"]["name"])){
                    $targetDir = "uploads/";
                    $fileName = basename($_FILES["hinh"]["name"]);
                    $targetFilePath = $targetDir . $fileName;
                    $fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);
                    
                    // Allow certain file formats
                    $allowTypes = array('jpg','png','jpeg','gif');
                    if(in_array($fileType, $allowTypes)){
                        // Upload file to server
                        if(move_uploaded_file($_FILES["hinh"]["tmp_name"], $targetFilePath)){
                            $hinh = $targetFilePath;
                        } else {
                            throw new Exception('Không thể tải lên hình ảnh');
                        }
                    } else {
                        throw new Exception('Chỉ chấp nhận file hình ảnh (JPG, JPEG, PNG, GIF)');
                    }
                } else {
                    // Keep existing image
                    $hinh = $this->studentModel->getStudentById($id)->Hinh;
                }

                $data = [
                    'masv' => $id,
                    'hoten' => trim($_POST['hoten']),
                    'gioitinh' => trim($_POST['gioitinh']),
                    'ngaysinh' => $ngaysinh,
                    'hinh' => $hinh,
                    'manganh' => trim($_POST['manganh'])
                ];

                // Update student
                if($this->studentModel->updateStudent($data)) {
                    flash('student_message', 'Cập nhật sinh viên thành công', 'alert alert-success');
                    redirect('students');
                } else {
                    throw new Exception('Không thể cập nhật sinh viên');
                }
            } else {
                // Get existing student data
                $student = $this->studentModel->getStudentById($id);
                
                // Get list of majors for dropdown
                $majors = $this->majorModel->getMajors();

                $data = [
                    'student' => $student,
                    'majors' => $majors
                ];

                $this->view('students/edit', $data);
            }
        } catch (Exception $e) {
            error_log('Lỗi cập nhật sinh viên: ' . $e->getMessage());
            flash('student_error', $e->getMessage(), 'alert alert-danger');
            
            // Chuyển về trang chỉnh sửa
            redirect('students/edit/' . $id);
        }
    }

    // Delete student
    public function delete($id) {
        try {
            // Check if logged in
            if(!isset($_SESSION['user_id'])) {
                flash('student_message', 'Vui lòng đăng nhập để xóa sinh viên', 'alert alert-danger');
                redirect('auth/login');
                return;
            }
            
            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Get student info to delete image file
                $student = $this->studentModel->getStudentById($id);
                
                // Delete student from database
                if($this->studentModel->deleteStudent($id)) {
                    // Delete image file if exists
                    if($student && !empty($student->Hinh) && file_exists($student->Hinh)) {
                        unlink($student->Hinh);
                    }
                    
                    flash('student_message', 'Xóa sinh viên thành công', 'alert alert-success');
                    redirect('students');
                } else {
                    throw new Exception("Không thể xóa sinh viên");
                }
            } else {
                $student = $this->studentModel->getStudentById($id);
                if(!$student) {
                    throw new Exception("Không tìm thấy sinh viên");
                }
                
                $data = [
                    'student' => $student
                ];
                
                $this->view('students/delete', $data);
            }
        } catch (Exception $e) {
            error_log('Lỗi xóa sinh viên: ' . $e->getMessage());
            flash('student_message', 'Không thể xóa sinh viên: ' . $e->getMessage(), 'alert alert-danger');
            redirect('students');
        }
    }

    // Show student details
    public function show($id) {
        try {
            $student = $this->studentModel->getStudentById($id);
            
            if(!$student) {
                throw new Exception("Không tìm thấy sinh viên");
            }
            
            $data = [
                'student' => $student
            ];
            
            $this->view('students/show', $data);
        } catch (Exception $e) {
            error_log('Lỗi hiển thị thông tin sinh viên: ' . $e->getMessage());
            flash('student_message', 'Không thể hiển thị thông tin sinh viên: ' . $e->getMessage(), 'alert alert-danger');
            redirect('students');
        }
    }
} 