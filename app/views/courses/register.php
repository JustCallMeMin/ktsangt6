<?php require APPROOT . '/views/inc/header.php'; ?>
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card card-body bg-light mt-5">
                <h2>Đăng Ký Học Phần</h2>
                <form action="<?php echo URLROOT; ?>/courses/register" method="post">
                    <div class="form-group mb-3">
                        <label for="student_id">Mã Sinh Viên:</label>
                        <input type="text" name="student_id" class="form-control" value="<?php echo $_SESSION['user_id']; ?>" readonly>
                        <small class="form-text text-muted">Đang đăng ký với tư cách sinh viên: <?php echo $_SESSION['user_name']; ?></small>
                    </div>
                    <div class="form-group mb-3">
                        <label for="course_id">Học Phần:</label>
                        <?php if(isset($data['course'])) : ?>
                            <input type="hidden" name="course_id" value="<?php echo $data['course']->MaHP; ?>">
                            <p class="form-control-static">
                                <?php echo $data['course']->MaHP . ' - ' . $data['course']->TenHP; ?>
                                <br>
                                <small class="text-muted">Số tín chỉ: <?php echo $data['course']->SoTinChi; ?></small>
                            </p>
                        <?php else : ?>
                            <input type="text" name="course_id" class="form-control" required>
                        <?php endif; ?>
                    </div>
                    <div class="row">
                        <div class="col">
                            <input type="submit" value="Đăng Ký" class="btn btn-success btn-block">
                        </div>
                        <div class="col">
                            <a href="<?php echo URLROOT; ?>/courses" class="btn btn-light btn-block">Hủy</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php require APPROOT . '/views/inc/footer.php'; ?> 