<?php require APPROOT . '/views/inc/header.php'; ?>
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card card-body bg-light mt-5">
                <h2>Xóa Thông Tin</h2>
                <p>Bạn có chắc chắn muốn xóa sinh viên này?</p>
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $data['student']->HoTen; ?></h5>
                        <p class="card-text">
                            <strong>Mã SV:</strong> <?php echo $data['student']->MaSV; ?><br>
                            <strong>Giới Tính:</strong> <?php echo $data['student']->GioiTinh; ?><br>
                            <strong>Ngày Sinh:</strong> <?php echo date('d/m/Y', strtotime($data['student']->NgaySinh)); ?><br>
                            <strong>Ngành:</strong> <?php echo $data['student']->MaNganh; ?>
                        </p>
                        <img src="<?php echo URLROOT . '/' . $data['student']->Hinh; ?>" 
                             alt="<?php echo $data['student']->HoTen; ?>"
                             class="student-detail-img"
                             onerror="this.classList.add('broken-image'); this.src=''; this.alt='Ảnh không tải được';">
                    </div>
                </div>
                <form action="<?php echo URLROOT; ?>/students/delete/<?php echo $data['student']->MaSV; ?>" method="post">
                    <div class="row">
                        <div class="col">
                            <input type="submit" value="Xóa" class="btn btn-danger btn-block">
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