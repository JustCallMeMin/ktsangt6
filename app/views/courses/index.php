<?php require APPROOT . '/views/inc/header.php'; ?>
    <div class="row">
        <div class="col-md-12">
            <h1>Danh Sách Học Phần</h1>
            
            <?php if(isset($_SESSION['user_id'])) : ?>
                <div class="mb-3 d-flex justify-content-end">
                    <a href="<?php echo URLROOT; ?>/courses/saveRegistration" class="btn btn-primary me-2">
                        <i class="bi bi-journal-plus"></i> Đăng Ký Học Phần
                    </a>
                    <a href="<?php echo URLROOT; ?>/courses/myRegistrations" class="btn btn-info">
                        <i class="bi bi-journal-check"></i> Học Phần Đã Đăng Ký
                    </a>
                </div>
            <?php else : ?>
                <div class="mb-3 d-flex justify-content-end">
                    <a href="<?php echo URLROOT; ?>/auth/login" class="btn btn-primary">
                        <i class="bi bi-box-arrow-in-right"></i> Đăng Nhập Để Đăng Ký Học Phần
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php flash('register_message'); ?>
    <?php flash('course_error'); ?>

    <div class="row mt-3">
        <div class="col-md-12">
            <?php if(empty($data['courses'])) : ?>
                <div class="alert alert-warning">
                    Không có học phần nào. Vui lòng liên hệ với quản trị viên.
                </div>
            <?php else : ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Mã HP</th>
                            <th>Tên Học Phần</th>
                            <th>Số Tín Chỉ</th>
                            <th>Số lượng đăng ký</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['courses'] as $course) : ?>
                            <tr>
                                <td><?php echo $course->MaHP; ?></td>
                                <td><?php echo $course->TenHP; ?></td>
                                <td><?php echo $course->SoTinChi; ?></td>
                                <td>
                                    <?php echo $course->registrationCount . '/' . $course->SoLuong; ?>
                                </td>
                                <td>
                                    <?php 
                                    // Kiểm tra trạng thái học phần
                                    $isFull = $course->SoLuong <= 0;
                                    $isRegistered = false;
                                    
                                    if (isset($_SESSION['user_id'])) {
                                        // Kiểm tra nếu đã đăng nhập và học phần đã đăng ký
                                        $isRegistered = isset($data['registeredCourseIds']) && 
                                                       in_array($course->MaHP, $data['registeredCourseIds']);
                                    }
                                    
                                    if ($isRegistered) {
                                        echo '<span class="badge bg-success">Đã đăng ký</span>';
                                    } else if ($isFull) {
                                        echo '<span class="badge bg-danger">Đã đủ số lượng</span>';
                                    } else {
                                        echo '<span class="badge bg-primary">Có thể đăng ký</span>';
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
<?php require APPROOT . '/views/inc/footer.php'; ?> 