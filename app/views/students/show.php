<?php require APPROOT . '/views/inc/header.php'; ?>
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card card-body bg-light mt-5">
                <h2>Thông tin chi tiết</h2>
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <img src="<?php echo URLROOT . '/' . $data['student']->Hinh; ?>" 
                                 alt="<?php echo $data['student']->HoTen; ?>"
                                 class="student-detail-img"
                                 onerror="this.classList.add('broken-image'); this.src=''; this.alt='Ảnh không tải được';">
                        </div>
                        <h5 class="card-title"><?php echo $data['student']->HoTen; ?></h5>
                        <p class="card-text">
                            <strong>Mã SV:</strong> <?php echo $data['student']->MaSV; ?><br>
                            <strong>Giới Tính:</strong> <?php echo $data['student']->GioiTinh; ?><br>
                            <strong>Ngày Sinh:</strong> <?php echo date('d/m/Y', strtotime($data['student']->NgaySinh)); ?><br>
                            <strong>Ngành:</strong> <?php echo $data['student']->MaNganh; ?>
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <a href="<?php echo URLROOT; ?>/students/edit/<?php echo $data['student']->MaSV; ?>" 
                           class="btn btn-warning btn-block">Sửa</a>
                    </div>
                    <div class="col">
                        <button type="button" class="btn btn-danger btn-block" data-bs-toggle="modal" data-bs-target="#deleteStudentModal">
                            Xóa
                        </button>
                    </div>
                    <div class="col">
                        <a href="<?php echo URLROOT; ?>/students" class="btn btn-light btn-block">Quay lại</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal xác nhận xóa sinh viên -->
    <div class="modal fade" id="deleteStudentModal" tabindex="-1" aria-labelledby="deleteStudentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteStudentModalLabel">Xác nhận xóa sinh viên</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn xóa sinh viên <strong><?php echo $data['student']->HoTen; ?></strong>?</p>
                    <p class="text-danger"><small>Lưu ý: Tất cả thông tin đăng ký học phần của sinh viên này cũng sẽ bị xóa.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <form action="<?php echo URLROOT; ?>/students/delete/<?php echo $data['student']->MaSV; ?>" method="post">
                        <button type="submit" class="btn btn-danger">Xác nhận xóa</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php require APPROOT . '/views/inc/footer.php'; ?> 