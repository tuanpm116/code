<?php require_once __DIR__ . '/../../../layout/header.php'; ?>

<div class="page-header">
    <h1><i class="fas fa-users"></i> <?php echo $title; ?></h1>
    <a href="?module=accounts&action=add" class="btn btn-primary">
        <i class="fas fa-plus"></i> Thêm Tài Khoản
    </a>
</div>

<?php if ($flash): ?>
    <div class="alert alert-<?php echo $flash['type']; ?>">
        <i class="fas fa-<?php echo $flash['type'] == 'success' ? 'check-circle' : 'exclamation-circle'; ?>"></i>
        <?php echo $flash['message']; ?>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-list"></i> Danh Sách Tài Khoản</h3>
        <div class="card-tools">
            <span class="badge">Tổng: <?php echo count($accounts); ?> tài khoản</span>
        </div>
    </div>
    
    <div class="card-body">
        <?php if (empty($accounts)): ?>
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <p>Chưa có tài khoản nào</p>
                <a href="?module=accounts&action=add" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Thêm Tài Khoản Đầu Tiên
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên Đăng Nhập</th>
                            <th>Số Điện Thoại</th>
                            <th>Số Dư</th>
                            <th>Trạng Thái</th>
                            <th>Ngày Tạo</th>
                            <th>Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($accounts as $account): ?>
                            <tr>
                                <td><?php echo $account['id']; ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($account['username']); ?></strong>
                                </td>
                                <td><?php echo htmlspecialchars($account['phone']); ?></td>
                                <td class="text-success">
                                    <strong><?php echo number_format($account['balance'], 0, ',', '.'); ?>đ</strong>
                                </td>
                                <td>
                                    <?php if ($account['status'] == 1): ?>
                                        <span class="badge badge-success">
                                            <i class="fas fa-check"></i> Hoạt động
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">
                                            <i class="fas fa-times"></i> Ngưng
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo date('d/m/Y H:i', strtotime($account['created_at'])); ?></td>
                                <td class="actions">
                                    <a href="?module=accounts&action=edit&id=<?php echo $account['id']; ?>" 
                                       class="btn btn-sm btn-warning" title="Sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="?module=accounts&action=delete&id=<?php echo $account['id']; ?>" 
                                       class="btn btn-sm btn-danger btn-delete" 
                                       title="Xóa"
                                       onclick="return confirm('Bạn có chắc chắn muốn xóa tài khoản <?php echo htmlspecialchars($account['username']); ?>?');">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../../../layout/footer.php'; ?>
