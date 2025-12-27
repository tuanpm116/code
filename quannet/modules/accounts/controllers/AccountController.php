<?php

/**
 * Class AccountController
 * Xử lý các request liên quan đến tài khoản
 */
class AccountController extends Controller {
    private $accountModel;
    
    public function __construct() {
        $this->accountModel = $this->model('accounts');
    }
    
    /**
     * Hiển thị danh sách tài khoản
     */
    public function index() {
        $accounts = $this->accountModel->getAll();
        $flash = $this->getFlash();
        
        $data = [
            'title' => 'Danh Sách Tài Khoản',
            'accounts' => $accounts,
            'flash' => $flash
        ];
        
        $this->view('accounts/views/list', $data);
    }
    
    /**
     * Hiển thị form thêm & xử lý thêm tài khoản
     */
    public function add() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validate dữ liệu
            $errors = [];
            
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $balance = trim($_POST['balance'] ?? 0);
            $status = isset($_POST['status']) ? 1 : 0;
            
            // Validation
            if (empty($username)) {
                $errors[] = 'Vui lòng nhập tên đăng nhập';
            } elseif (strlen($username) < 3 || strlen($username) > 20) {
                $errors[] = 'Tên đăng nhập phải từ 3-20 ký tự';
            } elseif ($this->accountModel->checkUsernameExists($username)) {
                $errors[] = 'Tên đăng nhập đã tồn tại';
            }
            
            if (empty($password)) {
                $errors[] = 'Vui lòng nhập mật khẩu';
            } elseif (strlen($password) < 6) {
                $errors[] = 'Mật khẩu phải có ít nhất 6 ký tự';
            }
            
            if (empty($phone)) {
                $errors[] = 'Vui lòng nhập số điện thoại';
            } elseif (!preg_match('/^0[0-9]{9,10}$/', $phone)) {
                $errors[] = 'Số điện thoại không đúng định dạng (10-11 số, bắt đầu bằng 0)';
            } elseif ($this->accountModel->checkPhoneExists($phone)) {
                $errors[] = 'Số điện thoại đã tồn tại';
            }
            
            if (!is_numeric($balance) || $balance < 0) {
                $errors[] = 'Số dư không hợp lệ';
            }
            
            // Nếu không có lỗi thì thêm vào database
            if (empty($errors)) {
                $data = [
                    'username' => $username,
                    'password' => $password,
                    'phone' => $phone,
                    'balance' => $balance,
                    'status' => $status
                ];
                
                if ($this->accountModel->create($data)) {
                    $this->setFlash('success', 'Thêm tài khoản thành công!');
                    $this->redirect('?module=accounts&action=index');
                } else {
                    $errors[] = 'Có lỗi xảy ra khi thêm tài khoản';
                }
            }
            
            // Có lỗi thì hiển thị lại form
            $data = [
                'title' => 'Thêm Tài Khoản',
                'errors' => $errors,
                'old' => $_POST
            ];
            
            $this->view('accounts/views/add', $data);
        } else {
            // Hiển thị form
            $data = [
                'title' => 'Thêm Tài Khoản',
                'errors' => [],
                'old' => []
            ];
            
            $this->view('accounts/views/add', $data);
        }
    }
    
    /**
     * Hiển thị form sửa & xử lý cập nhật tài khoản
     */
    public function edit() {
        $id = $_GET['id'] ?? 0;
        
        // Kiểm tra tài khoản có tồn tại không
        $account = $this->accountModel->getById($id);
        if (!$account) {
            $this->setFlash('error', 'Tài khoản không tồn tại!');
            $this->redirect('?module=accounts&action=index');
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validate dữ liệu
            $errors = [];
            
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $balance = trim($_POST['balance'] ?? 0);
            $status = isset($_POST['status']) ? 1 : 0;
            
            // Validation
            if (empty($username)) {
                $errors[] = 'Vui lòng nhập tên đăng nhập';
            } elseif (strlen($username) < 3 || strlen($username) > 20) {
                $errors[] = 'Tên đăng nhập phải từ 3-20 ký tự';
            } elseif ($this->accountModel->checkUsernameExists($username, $id)) {
                $errors[] = 'Tên đăng nhập đã tồn tại';
            }
            
            if (!empty($password) && strlen($password) < 6) {
                $errors[] = 'Mật khẩu phải có ít nhất 6 ký tự';
            }
            
            if (empty($phone)) {
                $errors[] = 'Vui lòng nhập số điện thoại';
            } elseif (!preg_match('/^0[0-9]{9,10}$/', $phone)) {
                $errors[] = 'Số điện thoại không đúng định dạng (10-11 số, bắt đầu bằng 0)';
            } elseif ($this->accountModel->checkPhoneExists($phone, $id)) {
                $errors[] = 'Số điện thoại đã tồn tại';
            }
            
            if (!is_numeric($balance) || $balance < 0) {
                $errors[] = 'Số dư không hợp lệ';
            }
            
            // Nếu không có lỗi thì cập nhật
            if (empty($errors)) {
                $data = [
                    'username' => $username,
                    'password' => $password,
                    'phone' => $phone,
                    'balance' => $balance,
                    'status' => $status
                ];
                
                if ($this->accountModel->update($id, $data)) {
                    $this->setFlash('success', 'Cập nhật tài khoản thành công!');
                    $this->redirect('?module=accounts&action=index');
                } else {
                    $errors[] = 'Có lỗi xảy ra khi cập nhật tài khoản';
                }
            }
            
            // Có lỗi thì hiển thị lại form
            $data = [
                'title' => 'Sửa Tài Khoản',
                'errors' => $errors,
                'account' => $account,
                'old' => $_POST
            ];
            
            $this->view('accounts/views/edit', $data);
        } else {
            // Hiển thị form
            $data = [
                'title' => 'Sửa Tài Khoản',
                'errors' => [],
                'account' => $account,
                'old' => []
            ];
            
            $this->view('accounts/views/edit', $data);
        }
    }
    
    /**
     * Xóa tài khoản
     */
    public function delete() {
        $id = $_GET['id'] ?? 0;
        
        // Kiểm tra tài khoản có tồn tại không
        $account = $this->accountModel->getById($id);
        if (!$account) {
            $this->setFlash('error', 'Tài khoản không tồn tại!');
            $this->redirect('?module=accounts&action=index');
        }
        
        if ($this->accountModel->delete($id)) {
            $this->setFlash('success', 'Xóa tài khoản thành công!');
        } else {
            $this->setFlash('error', 'Có lỗi xảy ra khi xóa tài khoản!');
        }
        
        $this->redirect('?module=accounts&action=index');
    }
}
