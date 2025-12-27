<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Quản Lý Quán Net'; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <?php require_once __DIR__ . '/sidebar.php'; ?>
        
        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <header class="header">
                <h2><?php echo $pageTitle ?? 'Dashboard'; ?></h2>
                <div class="user-info">
                    <i class="fas fa-user-circle"></i>
                    <span>Xin chào, <strong><?php echo Auth::user()['fullname'] ?? 'Admin'; ?></strong></span>
                    <a href="?module=auth&action=logout" class="btn-logout" title="Đăng xuất">
                        <i class="fas fa-sign-out-alt"></i>
                        Đăng xuất
                    </a>
                </div>
            </header>
            
            <!-- Page Content -->
            <div class="page-content">
