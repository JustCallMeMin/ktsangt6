<?php require APPROOT . '/views/inc/header.php'; ?>
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card card-body bg-light mt-5">
                <h2>Hiệu chỉnh thông tin sinh viên</h2>
                <?php flash('student_error'); ?>
                <form action="<?php echo URLROOT; ?>/students/edit/<?php echo $data['student']->MaSV; ?>" method="post" enctype="multipart/form-data">
                    <div class="form-group mb-3">
                        <label for="masv">Mã Sinh Viên:</label>
                        <input type="text" name="masv" class="form-control" value="<?php echo $data['student']->MaSV; ?>" readonly>
                    </div>
                    <div class="form-group mb-3">
                        <label for="hoten">Họ Tên: <sup>*</sup></label>
                        <input type="text" name="hoten" class="form-control" value="<?php echo $data['student']->HoTen; ?>" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="gioitinh">Giới Tính: <sup>*</sup></label>
                        <select name="gioitinh" class="form-control" required>
                            <option value="Nam" <?php echo ($data['student']->GioiTinh == 'Nam') ? 'selected' : ''; ?>>Nam</option>
                            <option value="Nữ" <?php echo ($data['student']->GioiTinh == 'Nữ') ? 'selected' : ''; ?>>Nữ</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="ngaysinh">Ngày Sinh: <sup>*</sup></label>
                        <input type="date" name="ngaysinh" class="form-control" value="<?php echo date('Y-m-d', strtotime($data['student']->NgaySinh)); ?>" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="hinh">Hình hiện tại:</label>
                        <img src="<?php echo URLROOT . '/' . $data['student']->Hinh; ?>" 
                             alt="<?php echo $data['student']->HoTen; ?>"
                             class="student-detail-img mb-3"
                             onerror="this.classList.add('broken-image'); this.src=''; this.alt='Ảnh không tải được';">
                        <label for="hinh">Chọn hình mới (nếu muốn thay đổi):</label>
                        <input type="file" name="hinh" class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label for="manganh">Ngành Học: <sup>*</sup></label>
                        <select name="manganh" class="form-control" required>
                            <?php foreach($data['majors'] as $major) : ?>
                                <option value="<?php echo $major->MaNganh; ?>" <?php echo ($data['student']->MaNganh == $major->MaNganh) ? 'selected' : ''; ?>>
                                    <?php echo $major->TenNganh; ?> (<?php echo $major->MaNganh; ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col">
                            <input type="submit" value="Cập Nhật" class="btn btn-success btn-block">
                        </div>
                        <div class="col">
                            <a href="<?php echo URLROOT; ?>/students" class="btn btn-light btn-block">Hủy</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php require APPROOT . '/views/inc/footer.php'; ?> 