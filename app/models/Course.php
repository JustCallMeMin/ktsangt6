<?php
class Course {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Get all courses
    public function getCourses() {
        $this->db->query('SELECT * FROM HocPhan');
        return $this->db->resultSet();
    }

    // Get course by ID
    public function getCourseById($id) {
        $this->db->query('SELECT * FROM HocPhan WHERE MaHP = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Get course registration count
    public function getRegistrationCount($courseId) {
        $this->db->query('SELECT COUNT(*) as count FROM ChiTietDangKy WHERE MaHP = :id');
        $this->db->bind(':id', $courseId);
        $result = $this->db->single();
        return $result->count;
    }

    // Check if student has registered for course
    public function isStudentRegistered($studentId, $courseId) {
        $this->db->query('SELECT COUNT(*) as count FROM DangKy dk 
                         JOIN ChiTietDangKy ctdk ON dk.MaDK = ctdk.MaDK 
                         WHERE dk.MaSV = :studentId AND ctdk.MaHP = :courseId');
        $this->db->bind(':studentId', $studentId);
        $this->db->bind(':courseId', $courseId);
        $result = $this->db->single();
        return $result->count > 0;
    }

    // Register student for course
    public function registerCourse($studentId, $courseId) {
        // Start transaction
        $this->db->query('START TRANSACTION');
        $this->db->execute();

        try {
            // Check course capacity
            $course = $this->getCourseById($courseId);
            if($course->SoLuong <= 0) {
                throw new Exception('Course is at full capacity');
            }

            // Create registration
            $this->db->query('INSERT INTO DangKy (NgayDK, MaSV) VALUES (NOW(), :studentId)');
            $this->db->bind(':studentId', $studentId);
            $this->db->execute();

            // Get the last inserted registration ID
            $this->db->query('SELECT LAST_INSERT_ID() as id');
            $result = $this->db->single();
            $registrationId = $result->id;

            // Create registration detail
            $this->db->query('INSERT INTO ChiTietDangKy (MaDK, MaHP) VALUES (:registrationId, :courseId)');
            $this->db->bind(':registrationId', $registrationId);
            $this->db->bind(':courseId', $courseId);
            $this->db->execute();
            
            // Decrease available slots
            $this->decreaseCourseCpapacity($courseId);

            // Commit transaction
            $this->db->query('COMMIT');
            $this->db->execute();

            return true;
        } catch (Exception $e) {
            // Rollback transaction on error
            $this->db->query('ROLLBACK');
            $this->db->execute();
            return false;
        }
    }

    // Unregister student from course
    public function unregisterCourse($studentId, $courseId) {
        // Start transaction
        $this->db->query('START TRANSACTION');
        $this->db->execute();

        try {
            // Check if course exists
            $this->db->query('SELECT * FROM HocPhan WHERE MaHP = :courseId');
            $this->db->bind(':courseId', $courseId);
            $course = $this->db->single();
            
            if(!$course) {
                throw new Exception('Học phần không tồn tại');
            }
            
            // Check if student is registered for this course
            $this->db->query('SELECT dk.MaDK 
                             FROM DangKy dk 
                             JOIN ChiTietDangKy ctdk ON dk.MaDK = ctdk.MaDK 
                             WHERE dk.MaSV = :studentId AND ctdk.MaHP = :courseId');
            $this->db->bind(':studentId', $studentId);
            $this->db->bind(':courseId', $courseId);
            $result = $this->db->single();
            
            // Check if we found a registration
            if(!$result) {
                throw new Exception('Bạn chưa đăng ký học phần này');
            }
            
            $registrationId = $result->MaDK;

            // Delete registration detail
            $this->db->query('DELETE FROM ChiTietDangKy WHERE MaDK = :registrationId AND MaHP = :courseId');
            $this->db->bind(':registrationId', $registrationId);
            $this->db->bind(':courseId', $courseId);
            $deleteResult = $this->db->execute();
            
            // Check if delete was successful
            if($deleteResult === false) {
                throw new Exception('Không thể xóa chi tiết đăng ký');
            }
            
            // Increase course capacity
            $this->increaseCourseCpapacity($courseId);

            // Check if there are any remaining courses for this registration
            $this->db->query('SELECT COUNT(*) as count FROM ChiTietDangKy WHERE MaDK = :registrationId');
            $this->db->bind(':registrationId', $registrationId);
            $result = $this->db->single();

            // If no courses remain, delete the registration
            if($result->count == 0) {
                $this->db->query('DELETE FROM DangKy WHERE MaDK = :registrationId');
                $this->db->bind(':registrationId', $registrationId);
                
                if($this->db->execute() === false) {
                    throw new Exception('Không thể xóa đăng ký');
                }
            }

            // Commit transaction
            $this->db->query('COMMIT');
            $this->db->execute();

            return true;
        } catch (Exception $e) {
            // Rollback transaction on error
            $this->db->query('ROLLBACK');
            $this->db->execute();
            error_log('Error unregistering course: ' . $e->getMessage());
            throw $e; // Re-throw to be caught by the controller
        }
    }

    // Get student's registered courses
    public function getStudentCourses($studentId) {
        $this->db->query('SELECT hp.* FROM HocPhan hp 
                         JOIN ChiTietDangKy ctdk ON hp.MaHP = ctdk.MaHP 
                         JOIN DangKy dk ON ctdk.MaDK = dk.MaDK 
                         WHERE dk.MaSV = :studentId');
        $this->db->bind(':studentId', $studentId);
        return $this->db->resultSet();
    }

    // Get courses by major prefix (first 4 characters of major code)
    public function getCoursesByMajorPrefix($majorPrefix) {
        try {
            $this->db->query('SELECT hp.* FROM HocPhan hp 
                             WHERE LEFT(hp.MaHP, 4) = :majorPrefix');
            $this->db->bind(':majorPrefix', $majorPrefix);
            return $this->db->resultSet();
        } catch (Exception $e) {
            error_log('Error getting courses by major prefix: ' . $e->getMessage());
            throw $e;
        }
    }

    // Register multiple courses at once
    public function registerMultipleCourses($studentId, $courseIds) {
        // Start transaction
        $this->db->query('START TRANSACTION');
        $this->db->execute();
        
        try {
            // Create registration
            $this->db->query('INSERT INTO DangKy (NgayDK, MaSV) VALUES (NOW(), :studentId)');
            $this->db->bind(':studentId', $studentId);
            $this->db->execute();
            
            // Get registration ID
            $this->db->query('SELECT LAST_INSERT_ID() as id');
            $result = $this->db->single();
            $registrationId = $result->id;
            
            // Register each course
            foreach($courseIds as $courseId) {
                // Check if already registered
                if($this->isStudentRegistered($studentId, $courseId)) {
                    continue; // Skip already registered courses
                }
                
                // Check course capacity
                $course = $this->getCourseById($courseId);
                
                if($course->SoLuong <= 0) {
                    continue; // Skip courses with no capacity
                }
                
                // Register course
                $this->db->query('INSERT INTO ChiTietDangKy (MaDK, MaHP) VALUES (:registrationId, :courseId)');
                $this->db->bind(':registrationId', $registrationId);
                $this->db->bind(':courseId', $courseId);
                $this->db->execute();
                
                // Decrease available slots
                $this->decreaseCourseCpapacity($courseId);
            }
            
            // Commit transaction
            $this->db->query('COMMIT');
            $this->db->execute();
            
            return $registrationId;
        } catch (Exception $e) {
            // Rollback transaction on error
            $this->db->query('ROLLBACK');
            $this->db->execute();
            error_log('Error registering courses: ' . $e->getMessage());
            return false;
        }
    }
    
    // Decrease course capacity by 1
    public function decreaseCourseCpapacity($courseId) {
        $this->db->query('UPDATE HocPhan SET SoLuong = SoLuong - 1 WHERE MaHP = :id AND SoLuong > 0');
        $this->db->bind(':id', $courseId);
        return $this->db->execute();
    }
    
    // Increase course capacity by 1 when a student unregisters
    public function increaseCourseCpapacity($courseId) {
        $this->db->query('UPDATE HocPhan SET SoLuong = SoLuong + 1 WHERE MaHP = :id');
        $this->db->bind(':id', $courseId);
        return $this->db->execute();
    }
    
    // Get registration by ID
    public function getRegistrationById($registrationId) {
        try {
            $this->db->query('SELECT * FROM DangKy WHERE MaDK = :id');
            $this->db->bind(':id', $registrationId);
            return $this->db->single();
        } catch (Exception $e) {
            error_log('Error getting registration: ' . $e->getMessage());
            throw $e;
        }
    }
    
    // Get registered courses by registration ID
    public function getRegisteredCoursesByRegistrationId($registrationId) {
        try {
            $this->db->query('SELECT c.MaDK, c.MaHP, h.TenHP, h.SoTinChi 
                             FROM ChiTietDangKy c
                             JOIN HocPhan h ON c.MaHP = h.MaHP
                             WHERE c.MaDK = :registrationId');
            $this->db->bind(':registrationId', $registrationId);
            return $this->db->resultSet();
        } catch (Exception $e) {
            error_log('Error getting registered courses: ' . $e->getMessage());
            throw $e;
        }
    }
} 