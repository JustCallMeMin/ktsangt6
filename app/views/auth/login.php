<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card card-body bg-light mt-5">
            <h2 class="text-center">ĐĂNG NHẬP</h2>
            <p class="text-center mb-3">Vui lòng nhập mã sinh viên để đăng nhập</p>
            <form action="<?php echo URLROOT; ?>/auth/login" method="post">
                <div class="form-group mb-3">
                    <label for="masv">Mã SV</label>
                    <input type="text" name="masv" class="form-control <?php echo (!empty($data['masv_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['masv']; ?>">
                    <span class="invalid-feedback"><?php echo $data['masv_err']; ?></span>
                </div>
                <div class="form-group">
                    <div class="d-grid">
                        <input type="submit" value="Đăng Nhập" class="btn btn-primary">
                    </div>
                </div>
            </form>
            <p class="mt-3">
                <a href="<?php echo URLROOT; ?>" class="text-decoration-none">Back to List</a>
            </p>
        </div>
    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?> 