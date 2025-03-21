<?php require APPROOT . '/views/inc/header.php'; ?>
    <div class="jumbotron jumbotron-fluid bg-light p-5 mb-4">
        <div class="container">
            <h1 class="display-4"><?php echo $data['title']; ?></h1>
            <p class="lead"><?php echo $data['description']; ?></p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Quản Lý Sinh Viên</h5>
                    <p class="card-text">Xem danh sách, thêm, sửa, xóa thông tin sinh viên.</p>
                    <a href="<?php echo URLROOT; ?>/students" class="btn btn-primary">Quản Lý Sinh Viên</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Đăng Ký Học Phần</h5>
                    <p class="card-text">Xem và đăng ký các học phần có sẵn.</p>
                    <a href="<?php echo URLROOT; ?>/courses" class="btn btn-primary">Đăng Ký Học Phần</a>
                </div>
            </div>
        </div>
    </div>
<?php require APPROOT . '/views/inc/footer.php'; ?> 