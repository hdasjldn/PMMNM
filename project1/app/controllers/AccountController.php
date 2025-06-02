<?php
require_once('app/config/database.php');
require_once('app/models/AccountModel.php');

class AccountController {
    private $accountModel;
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->accountModel = new AccountModel($this->db);
    }

    public function register() {
        include_once 'app/views/account/register.php';
    }

    public function login() {
        include_once 'app/views/account/login.php';
    }

    public function save() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING) ?? '';
            $fullName = filter_input(INPUT_POST, 'fullname', FILTER_SANITIZE_STRING) ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirmpassword'] ?? '';
            $errors = [];
            if (empty($username)) {
                $errors['username'] = "Vui lòng nhập userName!";
            }
            if (empty($fullName)) {
                $errors['fullname'] = "Vui lòng nhập fullName!";
            }
            if (empty($password)) {
                $errors['password'] = "Vui lòng nhập password!";
            }
            if ($password != $confirmPassword) {
                $errors['confirmPass'] = "Mật khẩu và xác nhận chưa đúng";
            }
            $account = $this->accountModel->getAccountByUsername($username);
            if ($account) {
                $errors['account'] = "Tài khoản này đã có người đăng ký!";
            }
            if (count($errors) > 0) {
                include_once 'app/views/account/register.php';
            } else {
                $password = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
                $result = $this->accountModel->save($username, $fullName, $password, 'user');
                if ($result) {
                    header('Location: /project1/account/login');
                    exit;
                } else {
                    $errors['save'] = "Đăng ký thất bại, vui lòng thử lại.";
                    include_once 'app/views/account/register.php';
                }
            }
        }
    }

    public function logout() {
        session_start();
        unset($_SESSION['username']);
        unset($_SESSION['role']);
        header('Location: /project1/product');
        exit;
    }

    public function checkLogin() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            session_start();
            $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING) ?? '';
            $password = $_POST['password'] ?? '';
            if (empty($username) || empty($password)) {
                $_SESSION['ErrorMessage'] = "Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu.";
                error_log("Login failed at " . date('Y-m-d H:i:s') . ": Missing username or password", 3, '/tmp/account_controller.log');
                header('Location: /project1/account/login');
                exit;
            }
            $account = $this->accountModel->getAccountByUsername($username);
            if ($account && property_exists($account, 'password')) {
                $pwd_hashed = $account->password;
                if (password_verify($password, $pwd_hashed)) {
                    $_SESSION['username'] = $account->username;
                    $_SESSION['role'] = $account->role ?? 'user'; // Match account table role
                    error_log("Login successful for $username at " . date('Y-m-d H:i:s') . ", Role: {$_SESSION['role']}", 3, '/tmp/account_controller.log');
                    header('Location: /project1/product');
                    exit;
                } else {
                    $_SESSION['ErrorMessage'] = "Mật khẩu không đúng.";
                    error_log("Login failed for $username at " . date('Y-m-d H:i:s') . ": Incorrect password", 3, '/tmp/account_controller.log');
                    header('Location: /project1/account/login');
                    exit;
                }
            } else {
                $_SESSION['ErrorMessage'] = "Không tìm thấy tài khoản.";
                error_log("Login failed at " . date('Y-m-d H:i:s') . ": Account $username not found", 3, '/tmp/account_controller.log');
                header('Location: /project1/account/login');
                exit;
            }
        } else {
            header('Location: /project1/account/login');
            exit;
        }
    }
}
?>