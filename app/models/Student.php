<?php
class Student {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Get all students
    public function getStudents() {
        $this->db->query('SELECT * FROM SinhVien');
        return $this->db->resultSet();
    }

    // Get student by ID
    public function getStudentById($id) {
        $this->db->query('SELECT * FROM SinhVien WHERE MaSV = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Add student
    public function addStudent($data) {
        $this->db->query('INSERT INTO SinhVien (MaSV, HoTen, GioiTinh, NgaySinh, Hinh, MaNganh) 
                         VALUES (:masv, :hoten, :gioitinh, :ngaysinh, :hinh, :manganh)');
        
        // Bind values
        $this->db->bind(':masv', $data['masv']);
        $this->db->bind(':hoten', $data['hoten']);
        $this->db->bind(':gioitinh', $data['gioitinh']);
        $this->db->bind(':ngaysinh', $data['ngaysinh']);
        $this->db->bind(':hinh', $data['hinh']);
        $this->db->bind(':manganh', $data['manganh']);

        // Execute
        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Update student
    public function updateStudent($data) {
        $this->db->query('UPDATE SinhVien 
                         SET HoTen = :hoten, GioiTinh = :gioitinh, NgaySinh = :ngaysinh, 
                             Hinh = :hinh, MaNganh = :manganh 
                         WHERE MaSV = :masv');
        
        // Bind values
        $this->db->bind(':masv', $data['masv']);
        $this->db->bind(':hoten', $data['hoten']);
        $this->db->bind(':gioitinh', $data['gioitinh']);
        $this->db->bind(':ngaysinh', $data['ngaysinh']);
        $this->db->bind(':hinh', $data['hinh']);
        $this->db->bind(':manganh', $data['manganh']);

        // Execute
        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Delete student
    public function deleteStudent($id) {
        $this->db->query('DELETE FROM SinhVien WHERE MaSV = :id');
        // Bind values
        $this->db->bind(':id', $id);

        // Execute
        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
} 