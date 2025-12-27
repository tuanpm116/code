<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Quản lý Quán Net</title>
    <link rel="stylesheet" href="public/css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="logo">
                    <i class="fas fa-desktop"></i>
                </div>
                <h1>Quản Lý Quán Net</h1>
                <p>Đăng nhập vào hệ thống</p>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php 
                        echo $_SESSION['error']; 
                        unset($_SESSION['error']);
                    ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['errors'])): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <ul>
                        <?php foreach ($_SESSION['errors'] as $error): ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <?php unset($_SESSION['errors']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?php 
                        echo $_SESSION['success']; 
                        unset($_SESSION['success']);
                    ?>
                </div>
            <?php endif; ?>

            <form action="?module=auth&action=doLogin" method="POST" class="login-form">
                <div class="form-group">
                    <label for="username">
                        <i class="fas fa-user"></i>
                        Tên đăng nhập
                    </label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        placeholder="Nhập tên đăng nhập"
                        value="<?php echo $_SESSION['old_username'] ?? ''; ?>"
                        required
                        autofocus
                    >
                    <?php unset($_SESSION['old_username']); ?>
                </div>

                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-lock"></i>
                        Mật khẩu
                    </label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="Nhập mật khẩu"
                        required
                    >
                </div>

                <div class="form-group-checkbox">
                    <label>
                        <input type="checkbox" name="remember" id="remember">
                        <span>Ghi nhớ đăng nhập</span>
                    </label>
                </div>

                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i>
                    Đăng nhập
                </button>
            </form>

            <div class="login-footer">
                <p>&copy; 2025 Quản Lý Quán Net. All rights reserved.</p>
                <p class="demo-info">
                    <i class="fas fa-info-circle"></i>
                    Demo: admin / password123
                </p>
            </div>
        </div>

        <div class="background-animation">
            <div class="circle circle-1"></div>
            <div class="circle circle-2"></div>
            <div class="circle circle-3"></div>
        </div>
    </div>

    <script>
        // Auto hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            });
        }, 5000);
    </script>
</body>
</html>
