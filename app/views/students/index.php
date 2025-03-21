<?php require APPROOT . '/views/inc/header.php'; ?>
    <div class="row">
        <div class="col-md-12">
            <h1>Danh Sách Sinh Viên</h1>
            <div class="mb-3 d-flex justify-content-end">
                <a href="<?php echo URLROOT; ?>/students/add" class="btn btn-primary">
                    <i class="bi bi-plus"></i> Thêm Sinh Viên
                </a>
            </div>
            
            <?php flash('student_message'); ?>
            <?php flash('student_error'); ?>
            
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Mã SV</th>
                        <th>Họ Tên</th>
                        <th>Giới Tính</th>
                        <th>Ngày Sinh</th>
                        <th>Hình</th>
                        <th>Ngành</th>
                        <th>Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['students'] as $student) : ?>
                        <tr>
                            <td><?php echo $student->MaSV; ?></td>
                            <td><?php echo $student->HoTen; ?></td>
                            <td><?php echo $student->GioiTinh; ?></td>
                            <td><?php echo date('d/m/Y', strtotime($student->NgaySinh)); ?></td>
                            <td>
                                <img src="<?php echo URLROOT . '/' . $student->Hinh; ?>" 
                                     alt="<?php echo $student->HoTen; ?>" 
                                     class="img-thumbnail" 
                                     onerror="this.classList.add('broken-image'); this.src=''; this.alt='Ảnh không tải được';">
                            </td>
                            <td><?php echo $student->MaNganh; ?></td>
                            <td>
                                <a href="<?php echo URLROOT; ?>/students/show/<?php echo $student->MaSV; ?>" 
                                   class="btn btn-info btn-sm">
                                    Chi Tiết
                                </a>
                                <a href="<?php echo URLROOT; ?>/students/edit/<?php echo $student->MaSV; ?>" 
                                   class="btn btn-warning btn-sm">
                                    Sửa
                                </a>
                                <button type="button" class="btn btn-danger btn-sm" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#deleteStudentModal<?php echo $student->MaSV; ?>">
                                    Xóa
                                </button>

                                <!-- Modal xác nhận xóa sinh viên -->
                                <div class="modal fade" id="deleteStudentModal<?php echo $student->MaSV; ?>" tabindex="-1" 
                                     aria-labelledby="deleteStudentModalLabel<?php echo $student->MaSV; ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteStudentModalLabel<?php echo $student->MaSV; ?>">
                                                    Xác nhận xóa sinh viên
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Bạn có chắc chắn muốn xóa sinh viên <strong><?php echo $student->HoTen; ?></strong>?</p>
                                                <p class="text-danger"><small>Lưu ý: Tất cả thông tin đăng ký học phần của sinh viên này cũng sẽ bị xóa.</small></p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                                <form action="<?php echo URLROOT; ?>/students/delete/<?php echo $student->MaSV; ?>" method="post">
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
            </table>
        </div>
    </div>
<?php require APPROOT . '/views/inc/footer.php'; ?> 