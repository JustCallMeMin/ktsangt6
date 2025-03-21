<?php
class CoursesController extends Controller {
    private $courseModel;

    public function __construct() {
        try {
            $this->courseModel = $this->model('Course');
        } catch (Exception $e) {
            die('Không thể kết nối đến dữ liệu: ' . $e->getMessage());
        }
    }

    // List all courses
    public function index() {
        try {
            $studentInfo = null;
            $registeredCourseIds = [];
            
            // Kiểm tra nếu sinh viên đã đăng nhập
            if (isset($_SESSION['user_id'])) {
                // Lấy thông tin sinh viên đang đăng nhập
                $studentModel = $this->model('Student');
                $student = $studentModel->getStudentById($_SESSION['user_id']);
                
                if ($student) {
                    // Lưu thông tin sinh viên để hiển thị
                    $studentInfo = $student;
                    
                    // Lấy danh sách học phần đã đăng ký
                    $registeredCourses = $this->courseModel->getStudentCourses($_SESSION['user_id']);
                    foreach($registeredCourses as $course) {
                        $registeredCourseIds[] = $course->MaHP;
                    }
                }
            }
            
            // Lấy tất cả học phần không lọc theo ngành
            $courses = $this->courseModel->getCourses();
            
            // Lấy số lượng đăng ký cho mỗi khóa học
            $coursesWithRegCount = [];
            foreach ($courses as $course) {
                $course->registrationCount = $this->courseModel->getRegistrationCount($course->MaHP);
                $coursesWithRegCount[] = $course;
            }
            
            $data = [
                'courses' => $coursesWithRegCount,
                'student' => $studentInfo,
                'registeredCourseIds' => $registeredCourseIds
            ];
            
            $this->view('courses/index', $data);
        } catch (Exception $e) {
            error_log('Lỗi lấy danh sách học phần: ' . $e->getMessage());
            flash('course_error', 'Không thể tải danh sách học phần: ' . $e->getMessage(), 'alert alert-danger');
            redirect('');
        }
    }

    // Show course registration form
    public function register($courseId = null) {
        try {
            // Kiểm tra đăng nhập
            if(!isset($_SESSION['user_id'])) {
                flash('register_message', 'Vui lòng đăng nhập để đăng ký học phần', 'alert alert-danger');
                redirect('auth/login');
                return;
            }
            
            // Chuyển hướng đến trang đăng ký nhiều học phần
            redirect('courses/saveRegistration');
            
        } catch (Exception $e) {
            error_log('Lỗi đăng ký học phần: ' . $e->getMessage());
            flash('register_message', 'Không thể đăng ký học phần: ' . $e->getMessage(), 'alert alert-danger');
            redirect('courses');
        }
    }

    // Show registered courses for a student
    public function myRegistrations($studentId = null) {
        try {
            // Kiểm tra đăng nhập
            if(!isset($_SESSION['user_id'])) {
                flash('register_message', 'Vui lòng đăng nhập để xem học phần đã đăng ký', 'alert alert-danger');
                redirect('auth/login');
                return;
            }
            
            // Nếu không truyền studentId, sử dụng id của sinh viên đang đăng nhập
            if(!$studentId) {
                $studentId = $_SESSION['user_id'];
            } else if($studentId != $_SESSION['user_id']) {
                // Không cho phép xem học phần của sinh viên khác
                flash('register_message', 'Không thể xem học phần của sinh viên khác', 'alert alert-danger');
                redirect('courses');
                return;
            }
            
            $courses = $this->courseModel->getStudentCourses($studentId);
            $data = [
                'courses' => $courses,
                'student_id' => $studentId
            ];
            $this->view('courses/my_registrations', $data);
        } catch (Exception $e) {
            error_log('Lỗi lấy danh sách học phần đã đăng ký: ' . $e->getMessage());
            flash('register_message', 'Không thể tải danh sách học phần đã đăng ký: ' . $e->getMessage(), 'alert alert-danger');
            redirect('courses');
        }
    }

    // Unregister from a course
    public function unregister() {
        try {
            // Kiểm tra đăng nhập
            if(!isset($_SESSION['user_id'])) {
                flash('register_message', 'Vui lòng đăng nhập để hủy đăng ký học phần', 'alert alert-danger');
                redirect('auth/login');
                return;
            }
            
            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Process form
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                $studentId = trim($_POST['student_id']);
                $courseId = trim($_POST['course_id']);
                
                // Kiểm tra xem sinh viên đăng nhập có khớp với form submit không
                if($studentId != $_SESSION['user_id']) {
                    flash('register_message', 'Không thể hủy đăng ký cho sinh viên khác', 'alert alert-danger');
                    redirect('courses/myRegistrations');
                    return;
                }

                // Kiểm tra xem học phần có tồn tại không
                if(!$this->courseModel->getCourseById($courseId)) {
                    flash('register_message', 'Học phần không tồn tại', 'alert alert-danger');
                    redirect('courses/myRegistrations');
                    return;
                }

                // Kiểm tra xem sinh viên có đăng ký học phần này không
                if(!$this->courseModel->isStudentRegistered($studentId, $courseId)) {
                    flash('register_message', 'Bạn chưa đăng ký học phần này', 'alert alert-danger');
                    redirect('courses/myRegistrations');
                    return;
                }

                // Unregister from course
                try {
                    $this->courseModel->unregisterCourse($studentId, $courseId);
                    flash('register_message', 'Hủy đăng ký học phần thành công', 'alert alert-success');
                } catch (Exception $e) {
                    flash('register_message', 'Lỗi: ' . $e->getMessage(), 'alert alert-danger');
                }
                redirect('courses/myRegistrations');
            } else {
                redirect('courses/myRegistrations');
            }
        } catch (Exception $e) {
            error_log('Lỗi hủy đăng ký học phần: ' . $e->getMessage());
            flash('register_message', 'Không thể hủy đăng ký học phần: ' . $e->getMessage(), 'alert alert-danger');
            redirect('courses/myRegistrations');
        }
    }

    // Unregister from all courses
    public function unregisterAll() {
        try {
            // Kiểm tra đăng nhập
            if(!isset($_SESSION['user_id'])) {
                flash('register_message', 'Vui lòng đăng nhập để hủy đăng ký học phần', 'alert alert-danger');
                redirect('auth/login');
                return;
            }
            
            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Process form
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                $studentId = trim($_POST['student_id']);
                
                // Kiểm tra xem sinh viên đăng nhập có khớp với form submit không
                if($studentId != $_SESSION['user_id']) {
                    flash('register_message', 'Không thể hủy đăng ký cho sinh viên khác', 'alert alert-danger');
                    redirect('courses/myRegistrations');
                    return;
                }
                
                $courses = $this->courseModel->getStudentCourses($studentId);
                
                // Nếu không có học phần nào đã đăng ký
                if(empty($courses)) {
                    flash('register_message', 'Bạn chưa đăng ký học phần nào', 'alert alert-warning');
                    redirect('courses/myRegistrations');
                    return;
                }

                $success = true;
                $errorMessage = '';
                foreach($courses as $course) {
                    try {
                        if(!$this->courseModel->unregisterCourse($studentId, $course->MaHP)) {
                            $success = false;
                            $errorMessage = 'Không thể hủy đăng ký học phần: ' . $course->TenHP;
                            break;
                        }
                    } catch (Exception $e) {
                        $success = false;
                        $errorMessage = $e->getMessage();
                        break;
                    }
                }

                if($success) {
                    flash('register_message', 'Hủy đăng ký tất cả học phần thành công', 'alert alert-success');
                } else {
                    flash('register_message', 'Có lỗi xảy ra: ' . $errorMessage, 'alert alert-danger');
                }
                redirect('courses/myRegistrations');
            } else {
                redirect('courses/myRegistrations');
            }
        } catch (Exception $e) {
            error_log('Lỗi hủy đăng ký tất cả học phần: ' . $e->getMessage());
            flash('register_message', 'Không thể hủy đăng ký tất cả học phần: ' . $e->getMessage(), 'alert alert-danger');
            redirect('courses/myRegistrations');
        }
    }

    // Lưu đăng ký học phần
    public function saveRegistration() {
        try {
            // Kiểm tra đăng nhập
            if(!isset($_SESSION['user_id'])) {
                flash('register_message', 'Vui lòng đăng nhập để đăng ký học phần', 'alert alert-danger');
                redirect('auth/login');
                return;
            }
            
            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Process form
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                
                $studentId = trim($_POST['student_id']);
                $courseIds = isset($_POST['course_ids']) ? $_POST['course_ids'] : [];
                
                // Kiểm tra xem sinh viên đăng nhập có khớp với form submit không
                if($studentId != $_SESSION['user_id']) {
                    flash('register_message', 'Không thể đăng ký cho sinh viên khác', 'alert alert-danger');
                    redirect('courses');
                    return;
                }
                
                // Kiểm tra nếu không chọn học phần nào
                if(empty($courseIds)) {
                    flash('register_message', 'Vui lòng chọn ít nhất một học phần để đăng ký', 'alert alert-danger');
                    redirect('courses');
                    return;
                }
                
                // Lọc danh sách học phần, loại bỏ những học phần đã đăng ký
                $validCourseIds = [];
                $skippedCourses = [];
                
                foreach($courseIds as $courseId) {
                    // Kiểm tra nếu học phần đã được đăng ký
                    if($this->courseModel->isStudentRegistered($studentId, $courseId)) {
                        // Lấy thông tin học phần để hiển thị thông báo
                        $course = $this->courseModel->getCourseById($courseId);
                        $skippedCourses[] = $course->TenHP;
                        continue;
                    }
                    
                    // Kiểm tra sĩ số của học phần
                    $course = $this->courseModel->getCourseById($courseId);
                    
                    if($course->SoLuong <= 0) {
                        // Học phần đã đủ số lượng
                        $skippedCourses[] = $course->TenHP . ' (đã đủ số lượng)';
                        continue;
                    }
                    
                    // Học phần hợp lệ, thêm vào danh sách đăng ký
                    $validCourseIds[] = $courseId;
                }
                
                // Kiểm tra nếu không còn học phần nào hợp lệ để đăng ký
                if(empty($validCourseIds)) {
                    $message = 'Không thể đăng ký học phần: ';
                    
                    if(!empty($skippedCourses)) {
                        $message .= 'Tất cả học phần đã chọn hoặc đã được đăng ký trước đó hoặc đã đủ số lượng.';
                    } else {
                        $message .= 'Không có học phần nào được chọn.';
                    }
                    
                    flash('register_message', $message, 'alert alert-warning');
                    redirect('courses');
                    return;
                }
                
                // Đăng ký các học phần hợp lệ
                $registrationId = $this->courseModel->registerMultipleCourses($studentId, $validCourseIds);
                
                if($registrationId) {
                    // Lưu thông tin đăng ký vào session để hiển thị ở trang kết quả
                    $_SESSION['registration_id'] = $registrationId;
                    $_SESSION['registration_date'] = date('Y-m-d H:i:s');
                    
                    $message = 'Đăng ký học phần thành công';
                    
                    // Thêm thông báo về các học phần bị bỏ qua
                    if(!empty($skippedCourses)) {
                        $message .= '. Lưu ý: Một số học phần không được đăng ký do đã đăng ký trước đó hoặc đã đủ số lượng.';
                    }
                    
                    flash('register_message', $message, 'alert alert-success');
                    redirect('courses/showRegistrationResult/' . $registrationId);
                } else {
                    throw new Exception('Không thể đăng ký học phần');
                }
            } else {
                // Lấy danh sách học phần cho form
                $courses = $this->courseModel->getCourses();
                
                // Lấy số lượng đăng ký cho mỗi khóa học
                $coursesWithRegCount = [];
                foreach ($courses as $course) {
                    $course->registrationCount = $this->courseModel->getRegistrationCount($course->MaHP);
                    $coursesWithRegCount[] = $course;
                }
                
                // Lấy thông tin sinh viên
                $studentModel = $this->model('Student');
                $student = $studentModel->getStudentById($_SESSION['user_id']);
                
                // Lấy danh sách học phần đã đăng ký để đánh dấu
                $registeredCourses = $this->courseModel->getStudentCourses($_SESSION['user_id']);
                $registeredCourseIds = [];
                
                foreach($registeredCourses as $course) {
                    $registeredCourseIds[] = $course->MaHP;
                }
                
                $data = [
                    'student' => $student,
                    'courses' => $coursesWithRegCount,
                    'registeredCourseIds' => $registeredCourseIds
                ];
                
                $this->view('courses/register_form', $data);
            }
        } catch (Exception $e) {
            error_log('Lỗi đăng ký học phần: ' . $e->getMessage());
            flash('register_message', 'Lỗi đăng ký học phần: ' . $e->getMessage(), 'alert alert-danger');
            redirect('courses');
        }
    }
    
    // Hiển thị kết quả đăng ký học phần
    public function showRegistrationResult($registrationId = null) {
        try {
            // Kiểm tra đăng nhập
            if(!isset($_SESSION['user_id'])) {
                flash('register_message', 'Vui lòng đăng nhập để xem kết quả đăng ký', 'alert alert-danger');
                redirect('auth/login');
                return;
            }
            
            // Nếu không có registrationId, thử lấy từ session
            if(!$registrationId && isset($_SESSION['registration_id'])) {
                $registrationId = $_SESSION['registration_id'];
            }
            
            if(!$registrationId) {
                flash('register_message', 'Không tìm thấy thông tin đăng ký', 'alert alert-danger');
                redirect('courses');
                return;
            }
            
            // Lấy thông tin đăng ký
            $registration = $this->courseModel->getRegistrationById($registrationId);
            
            // Kiểm tra xem sinh viên đăng nhập có phải là người đăng ký không
            if($registration && $registration->MaSV != $_SESSION['user_id']) {
                flash('register_message', 'Bạn không có quyền xem thông tin đăng ký này', 'alert alert-danger');
                redirect('courses');
                return;
            }
            
            // Lấy danh sách học phần đã đăng ký
            $registeredCourses = $this->courseModel->getRegisteredCoursesByRegistrationId($registrationId);
            
            // Lấy thông tin sinh viên
            $studentModel = $this->model('Student');
            $student = $studentModel->getStudentById($_SESSION['user_id']);
            
            $data = [
                'registration' => $registration,
                'student' => $student,
                'registeredCourses' => $registeredCourses
            ];
            
            $this->view('courses/registration_result', $data);
            
            // Xóa thông tin đăng ký khỏi session sau khi hiển thị
            unset($_SESSION['registration_id']);
            unset($_SESSION['registration_date']);
        } catch (Exception $e) {
            error_log('Lỗi hiển thị kết quả đăng ký: ' . $e->getMessage());
            flash('register_message', 'Lỗi hiển thị kết quả đăng ký: ' . $e->getMessage(), 'alert alert-danger');
            redirect('courses');
        }
    }
} 