<?php require APPROOT . '/views/inc/header.php'; ?>
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="alert alert-success">
                    <i class="bi bi-check-circle-fill me-2"></i> Đăng ký học phần thành công!
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">Thông Tin Học Phần Đã Lưu</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Mã đăng ký:</th>
                                        <td><?php echo $data['registration']->MaDK; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Mã SV:</th>
                                        <td><?php echo $data['student']->MaSV; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Ngày đăng ký:</th>
                                        <td><?php echo date('d/m/Y H:i:s', strtotime($data['registration']->NgayDK)); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Họ tên:</th>
                                        <td><?php echo $data['student']->HoTen; ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                        <h5 class="mb-3">Kết quả sau khi đăng ký học phần:</h5>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-striped table-bordered">
                                    <thead class="table-dark">
                                        <tr>
                                            <th style="width: 50px">#</th>
                                            <th>Mã ĐK</th>
                                            <th>Mã HP</th>
                                            <th>Tên Học Phần</th>
                                            <th>Số Tín Chỉ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(empty($data['registeredCourses'])): ?>
                                        <tr>
                                            <td colspan="5" class="text-center">Không có học phần nào được đăng ký</td>
                                        </tr>
                                        <?php else: ?>
                                            <?php foreach($data['registeredCourses'] as $index => $course): ?>
                                            <tr>
                                                <td><?php echo $index + 1; ?></td>
                                                <td><?php echo $course->MaDK; ?></td>
                                                <td><?php echo $course->MaHP; ?></td>
                                                <td><?php echo $course->TenHP; ?></td>
                                                <td><?php echo $course->SoTinChi; ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                    <?php 
                                    // Calculate summary statistics
                                    $courseCount = count($data['registeredCourses']);
                                    $totalCredits = 0;
                                    foreach($data['registeredCourses'] as $course) {
                                        $totalCredits += $course->SoTinChi;
                                    }
                                    ?>
                                    <tfoot>
                                        <tr class="table-secondary">
                                            <td colspan="4" class="text-end fw-bold">Số lượng học phần:</td>
                                            <td class="text-center fw-bold"><?php echo $courseCount; ?></td>
                                        </tr>
                                        <tr class="table-secondary">
                                            <td colspan="4" class="text-end fw-bold">Tổng số tín chỉ:</td>
                                            <td class="text-center fw-bold"><?php echo $totalCredits; ?></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-md-12 text-center">
                                <a href="<?php echo URLROOT; ?>/courses/myRegistrations" class="btn btn-primary">Xem Tất Cả Học Phần Đã Đăng Ký</a>
                                <a href="<?php echo URLROOT; ?>/courses" class="btn btn-secondary ms-2">Quay Lại Danh Sách Học Phần</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php require APPROOT . '/views/inc/footer.php'; ?> 