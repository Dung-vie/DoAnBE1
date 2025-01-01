<?php
session_start();
require_once 'config/database.php';
spl_autoload_register(function ($classname) {
    require_once "app/models/$classname.php";
});

$userModel = new User();

if (isset($_POST['new_password'])) {
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Kiểm tra xem mật khẩu mới và mật khẩu xác nhận có khớp không
    if ($newPassword === $confirmPassword) {
        // Lấy email từ session
        $email = $_SESSION['verification_email'];

        // Cập nhật mật khẩu mới
        if ($userModel->updatePassword($email, $newPassword)) {
            $_SESSION['notification'] = 'Mật khẩu đã được thay đổi thành công.';
            header('Location: login.php');  // Điều hướng người dùng về trang đăng nhập
            exit;
        } else {
            $_SESSION['notification'] = 'Đã có lỗi xảy ra khi cập nhật mật khẩu.';
        }
    } else {
        $_SESSION['notification'] = 'Mật khẩu mới và xác nhận mật khẩu không khớp.';
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt lại mật khẩu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h3>Đặt lại mật khẩu</h3>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($_SESSION['notification'])): ?>
                            <div class="alert alert-danger">
                                <?php
                                echo $_SESSION['notification'];
                                unset($_SESSION['notification']);
                                ?>
                            </div>
                        <?php endif; ?>
                        <form action="resetpassword.php" method="POST">
                            <div class="mb-3">
                                <label for="new_password" class="form-label">Mật khẩu mới</label>
                                <input type="password" name="new_password" class="form-control" id="new_password" required>
                            </div>
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Xác nhận mật khẩu mới</label>
                                <input type="password" name="confirm_password" class="form-control" id="confirm_password" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Đặt lại mật khẩu</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>