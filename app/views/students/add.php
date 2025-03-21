<?php require APPROOT . '/views/inc/header.php'; ?>
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card card-body bg-light mt-5">
                <h2>Thêm Sinh Viên</h2>
                <?php flash('student_error'); ?>
                <form action="<?php echo URLROOT; ?>/students/add" method="post" enctype="multipart/form-data">
                    <div class="form-group mb-3">
                        <label for="masv">Mã Sinh Viên: <sup>*</sup></label>
                        <input type="text" name="masv" class="form-control" value="<?php echo $data['masv']; ?>" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="hoten">Họ Tên: <sup>*</sup></label>
                        <input type="text" name="hoten" class="form-control" value="<?php echo $data['hoten']; ?>" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="gioitinh">Giới Tính: <sup>*</sup></label>
                        <select name="gioitinh" class="form-control" required>
                            <option value="Nam" <?php echo ($data['gioitinh'] == 'Nam') ? 'selected' : ''; ?>>Nam</option>
                            <option value="Nữ" <?php echo ($data['gioitinh'] == 'Nữ') ? 'selected' : ''; ?>>Nữ</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="ngaysinh">Ngày Sinh: <sup>*</sup></label>
                        <input type="date" name="ngaysinh" class="form-control" value="<?php echo $data['ngaysinh']; ?>" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="hinh">Hình: <sup>*</sup></label>
                        <input type="file" name="hinh" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="manganh">Ngành Học: <sup>*</sup></label>
                        <select name="manganh" class="form-control" required>
                            <option value="">Chọn ngành học</option>
                            <?php foreach($data['majors'] as $major) : ?>
                                <option value="<?php echo $major->MaNganh; ?>" <?php echo ($data['manganh'] == $major->MaNganh) ? 'selected' : ''; ?>><?php echo $major->TenNganh; ?> (<?php echo $major->MaNganh; ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col">
                            <input type="submit" value="Thêm" class="btn btn-success btn-block">
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