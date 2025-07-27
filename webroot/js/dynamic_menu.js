document.addEventListener('DOMContentLoaded', function() {
    // Xử lý click danh mục
    document.querySelectorAll('.menu-category').forEach(category => {
        category.addEventListener('click', function(e) {
            if (this.classList.contains('add-new')) {
                // Mở modal thêm danh mục mới
                var modal = new bootstrap.Modal(document.getElementById('addCategoryModal'));
                modal.show();
            }
        });
    });

    // Xử lý toggle item group
    document.querySelectorAll('.group-title').forEach(title => {
        title.addEventListener('click', function() {
            this.nextElementSibling.classList.toggle('show');
            this.classList.toggle('active');
        });
    });
});