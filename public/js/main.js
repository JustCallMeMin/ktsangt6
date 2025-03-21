// Xử lý lỗi khi ảnh không tải được
document.addEventListener('DOMContentLoaded', function() {
    // Lấy tất cả các ảnh
    const images = document.querySelectorAll('img');
    
    // Thêm sự kiện error cho mỗi ảnh
    images.forEach(function(img) {
        img.addEventListener('error', function() {
            // Thay thế src với ảnh mặc định hoặc thêm class broken-image
            img.classList.add('broken-image');
            img.src = ''; // Xóa src để tránh liên tục request
            img.alt = 'Ảnh không tải được';
        });
    });

    // Các chức năng khác của trang web có thể thêm vào đây
}); 