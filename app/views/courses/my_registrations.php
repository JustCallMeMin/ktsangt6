<?php require APPROOT . '/views/inc/header.php'; ?>
    <div class="row">
        <div class="col-md-8">
            <h1>Học Phần Đã Đăng Ký</h1>
        </div>
        <div class="col-md-4">
            <button type="button" class="btn btn-danger float-end" data-bs-toggle="modal" data-bs-target="#deleteAllModal">
                Xóa Tất Cả Đăng Ký
            </button>
        </div>
    </div>

    <?php flash('register_message'); ?>

    <div class="row mt-3">
        <div class="col-md-12">
            <?php if(empty($data['courses'])) : ?>
                <p>Chưa có học phần nào được đăng ký.</p>
            <?php else : ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Mã HP</th>
                            <th>Tên Học Phần</th>
                            <th>Số Tín Chỉ</th>
                            <th>Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['courses'] as $course) : ?>
                            <tr>
                                <td><?php echo $course->MaHP; ?></td>
                                <td><?php echo $course->TenHP; ?></td>
                                <td><?php echo $course->SoTinChi; ?></td>
                                <td>
                                    <button type="button" class="btn btn-warning btn-sm" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteCourseModal<?php echo $course->MaHP; ?>">
                                        Hủy Đăng Ký
                                    </button>

                                    <!-- Modal xác nhận xóa học phần -->
                                    <div class="modal fade" id="deleteCourseModal<?php echo $course->MaHP; ?>" tabindex="-1" aria-labelledby="deleteCourseModalLabel<?php echo $course->MaHP; ?>" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteCourseModalLabel<?php echo $course->MaHP; ?>">Xác nhận hủy đăng ký</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Bạn có chắc chắn muốn hủy đăng ký học phần <strong><?php echo $course->TenHP; ?> (<?php echo $course->MaHP; ?>)</strong>?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                                    <form action="<?php echo URLROOT; ?>/courses/unregister" method="post" class="d-inline">
                                                        <input type="hidden" name="student_id" value="<?php echo $data['student_id']; ?>">
                                                        <input type="hidden" name="course_id" value="<?php echo $course->MaHP; ?>">
                                                        <button type="submit" class="btn btn-danger">Xác nhận xóa</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <?php 
                    // Calculate summary statistics
                    $courseCount = count($data['courses']);
                    $totalCredits = 0;
                    foreach($data['courses'] as $course) {
                        $totalCredits += $course->SoTinChi;
                    }
                    ?>
                    <tfoot>
                        <tr class="table-secondary">
                            <td colspan="2" class="text-end fw-bold">Số lượng học phần:</td>
                            <td colspan="2" class="fw-bold"><?php echo $courseCount; ?></td>
                        </tr>
                        <tr class="table-secondary">
                            <td colspan="2" class="text-end fw-bold">Tổng số tín chỉ:</td>
                            <td colspan="2" class="fw-bold"><?php echo $totalCredits; ?></td>
                        </tr>
                    </tfoot>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            <a href="<?php echo URLROOT; ?>/courses" class="btn btn-light">Quay Lại Danh Sách Học Phần</a>
        </div>
    </div>

    <!-- Modal xác nhận xóa tất cả -->
    <div class="modal fade" id="deleteAllModal" tabindex="-1" aria-labelledby="deleteAllModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteAllModalLabel">Xác nhận xóa tất cả đăng ký</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn hủy đăng ký <strong>TẤT CẢ</strong> học phần? Hành động này không thể hoàn tác.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <form action="<?php echo URLROOT; ?>/courses/unregisterAll" method="post">
                        <input type="hidden" name="student_id" value="<?php echo $data['student_id']; ?>">
                        <button type="submit" class="btn btn-danger">Xác nhận xóa tất cả</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php require APPROOT . '/views/inc/footer.php'; ?> 