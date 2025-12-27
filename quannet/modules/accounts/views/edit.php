<?php require_once __DIR__ . '/../../../layout/header.php'; ?>

<div class="page-header">
    <h1><i class="fas fa-edit"></i> <?php echo $title; ?></h1>
    <a href="?module=accounts&action=index" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Quay Lại
    </a>
</div>

<?php if (!empty($errors)): ?>
    <div class="alert alert-error">
        <i class="fas fa-exclamation-circle"></i>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo $error; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-user-edit"></i> Chỉnh Sửa Thông Tin</h3>
    </div>
    
    <div class="card-body">
        <form method="POST" action="" class="form">
            <div class="form-row">
                <div class="form-group">
                    <label for="username">
                        <i class="fas fa-user"></i> Tên Đăng Nhập <span class="required">*</span>
                    </label>
                    <input type="text" 
                           id="username" 
                           name="username" 
                           class="form-control" 
                           value="<?php echo htmlspecialchars($old['username'] ?? $account['username']); ?>"
                           placeholder="Nhập tên đăng nhập"
                           required>
                </div>
                
                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-lock"></i> Mật Khẩu Mới
                    </label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="form-control" 
                           placeholder="Để trống nếu không đổi mật khẩu">
                    <small class="form-text">Chỉ nhập nếu muốn thay đổi mật khẩu (tối thiểu 6 ký tự)</small>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="phone">
                        <i class="fas fa-phone"></i> Số Điện Thoại <span class="required">*</span>
                    </label>
                    <input type="text" 
                           id="phone" 
                           name="phone" 
                           class="form-control" 
                           value="<?php echo htmlspecialchars($old['phone'] ?? $account['phone']); ?>"
                           placeholder="Nhập số điện thoại"
                           required>
                </div>
                
                <div class="form-group">
                    <label for="balance">
                        <i class="fas fa-wallet"></i> Số Dư (VNĐ)
                    </label>
                    <input type="number" 
                           id="balance" 
                           name="balance" 
                           class="form-control" 
                           value="<?php echo htmlspecialchars($old['balance'] ?? $account['balance']); ?>"
                           placeholder="Nhập số dư"
                           min="0"
                           step="1000">
                </div>
            </div>
            
            <div class="form-group">
                <label class="checkbox-label">
                    <input type="checkbox" 
                           name="status" 
                           <?php 
                           $checked = isset($old['status']) ? $old['status'] : $account['status'];
                           echo $checked ? 'checked' : ''; 
                           ?>>
                    <span><i class="fas fa-check-circle"></i> Kích hoạt tài khoản</span>
                </label>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Cập Nhật
                </button>
                <a href="?module=accounts&action=index" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Hủy Bỏ
                </a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../../../layout/footer.php'; ?>
