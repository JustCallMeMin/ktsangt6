<?php require APPROOT . '/views/inc/header.php'; ?>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="mb-4">Đăng Ký Học Phần</h1>
            </div>
        </div>

        <?php flash('register_message'); ?>

        <div class="card mb-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">Thông tin Đăng ký</h5>
            </div>
            <div class="card-body">
                <form action="<?php echo URLROOT; ?>/courses/saveRegistration" method="post">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="student_id" class="form-label">Mã SV:</label>
                            <input type="text" class="form-control" id="student_id" name="student_id" value="<?php echo $data['student']->MaSV; ?>" readonly>
                        </div>
                        <div class="col-md-4">
                            <label for="student_name" class="form-label">Tên Sinh Viên:</label>
                            <input type="text" class="form-control" id="student_name" value="<?php echo $data['student']->HoTen; ?>" readonly>
                        </div>
                        <div class="col-md-4">
                            <label for="registration_date" class="form-label">Ngày Đăng Ký:</label>
                            <input type="text" class="form-control" id="registration_date" value="<?php echo date('d/m/Y H:i:s A'); ?>" readonly>
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <strong>Hướng dẫn:</strong> Vui lòng chọn các học phần bạn muốn đăng ký bằng cách đánh dấu vào ô checkbox tương ứng. 
                        Các học phần đã đăng ký trước đó hoặc đã đủ số lượng sẽ được hiển thị trạng thái tương ứng.
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label">Chọn Học Phần:</label>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead class="table-dark">
                                        <tr>
                                            <th style="width: 50px">#</th>
                                            <th>Mã HP</th>
                                            <th>Tên Học Phần</th>
                                            <th>Số Tín Chỉ</th>
                                            <th>Số lượng đăng ký</th>
                                            <th style="width: 80px">Chọn</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($data['courses'] as $index => $course): ?>
                                        <tr>
                                            <td><?php echo $index + 1; ?></td>
                                            <td><?php echo $course->MaHP; ?></td>
                                            <td><?php echo $course->TenHP; ?></td>
                                            <td><?php echo $course->SoTinChi; ?></td>
                                            <td>
                                                <?php echo $course->registrationCount . '/' . $course->SoLuong; ?>
                                            </td>
                                            <td class="text-center">
                                                <div class="form-check d-flex justify-content-center">
                                                    <?php 
                                                    // Kiểm tra xem học phần đã được đăng ký chưa
                                                    $isRegistered = in_array($course->MaHP, $data['registeredCourseIds'] ?? []);
                                                    
                                                    // Kiểm tra xem học phần đã đầy chưa
                                                    $isFull = false;
                                                    if($course->SoLuong <= 0) {
                                                        $isFull = true;
                                                    }
                                                    
                                                    if($isRegistered) {
                                                        echo '<div class="text-success"><i class="bi bi-check-circle-fill"></i> Đã đăng ký</div>';
                                                    } else if($isFull) {
                                                        echo '<div class="text-danger"><i class="bi bi-x-circle-fill"></i> Đã đầy</div>';
                                                    } else {
                                                    ?>
                                                    <input class="form-check-input" type="checkbox" name="course_ids[]" value="<?php echo $course->MaHP; ?>" id="course_<?php echo $course->MaHP; ?>">
                                                    <?php } ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr class="table-secondary">
                                            <td colspan="5" class="text-end fw-bold">Số lượng học phần:</td>
                                            <td class="text-center fw-bold"><span id="selectedCourseCount">0</span></td>
                                        </tr>
                                        <tr class="table-secondary">
                                            <td colspan="5" class="text-end fw-bold">Tổng số tín chỉ:</td>
                                            <td class="text-center fw-bold"><span id="totalCredits">0</span></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn-primary">Lưu Đăng Ký</button>
                            <a href="<?php echo URLROOT; ?>/courses" class="btn btn-secondary ms-2">Hủy</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // JavaScript to update the count and total credits when checkboxes are checked/unchecked
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('input[name="course_ids[]"]');
            const selectedCourseCount = document.getElementById('selectedCourseCount');
            const totalCredits = document.getElementById('totalCredits');
            
            // Function to update the counts
            function updateCounts() {
                let count = 0;
                let credits = 0;
                
                checkboxes.forEach(function(checkbox) {
                    if (checkbox.checked) {
                        count++;
                        // Get the credits from the 4th column (index 3) of the current row
                        const row = checkbox.closest('tr');
                        const creditCell = row.cells[3];
                        const creditValue = parseInt(creditCell.textContent);
                        credits += creditValue;
                    }
                });
                
                selectedCourseCount.textContent = count;
                totalCredits.textContent = credits;
            }
            
            // Add event listeners to all checkboxes
            checkboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', updateCounts);
            });
            
            // Initialize counts
            updateCounts();
        });
    </script>
<?php require APPROOT . '/views/inc/footer.php'; ?> 