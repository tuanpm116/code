<?php require_once __DIR__ . '/../../../layout/header.php'; ?>

<div class="page-header">
    <h1><i class="fas fa-plus-circle"></i> <?php echo $title; ?></h1>
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
        <h3><i class="fas fa-user-plus"></i> Thông Tin Tài Khoản</h3>
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
                           value="<?php echo htmlspecialchars($old['username'] ?? ''); ?>"
                           placeholder="Nhập tên đăng nhập"
                           required>
                </div>
                
                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-lock"></i> Mật Khẩu <span class="required">*</span>
                    </label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="form-control" 
                           placeholder="Nhập mật khẩu (tối thiểu 6 ký tự)"
                           required>
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
                           value="<?php echo htmlspecialchars($old['phone'] ?? ''); ?>"
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
                           value="<?php echo htmlspecialchars($old['balance'] ?? '0'); ?>"
                           placeholder="Nhập số dư"
                           min="0"
                           step="1000">
                </div>
            </div>
            
            <div class="form-group">
                <label class="checkbox-label">
                    <input type="checkbox" 
                           name="status" 
                           <?php echo (isset($old['status']) && $old['status']) || !isset($old['status']) ? 'checked' : ''; ?>>
                    <span><i class="fas fa-check-circle"></i> Kích hoạt tài khoản</span>
                </label>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Lưu Tài Khoản
                </button>
                <a href="?module=accounts&action=index" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Hủy Bỏ
                </a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../../../layout/footer.php'; ?>
