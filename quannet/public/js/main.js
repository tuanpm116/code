// Confirm delete
document.addEventListener('DOMContentLoaded', function () {
    // Delete confirmation
    const deleteButtons = document.querySelectorAll('.btn-delete');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            if (!confirm('Bạn có chắc chắn muốn xóa tài khoản này?')) {
                e.preventDefault();
            }
        });
    });

    // Auto hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.remove();
            }, 300);
        }, 5000);
    });

    // Form validation
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function (e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.style.borderColor = 'var(--danger)';
                } else {
                    field.style.borderColor = 'var(--gray-300)';
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('Vui lòng điền đầy đủ thông tin bắt buộc!');
            }
        });
    });
});
