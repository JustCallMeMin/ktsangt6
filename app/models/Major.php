<?php
class Major {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Get all majors
    public function getMajors() {
        $this->db->query('SELECT * FROM NganhHoc ORDER BY TenNganh');
        return $this->db->resultSet();
    }

    // Get major by ID
    public function getMajorById($id) {
        $this->db->query('SELECT * FROM NganhHoc WHERE MaNganh = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
} 