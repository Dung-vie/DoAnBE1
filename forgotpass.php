<?php
session_start();
require_once 'config/database.php';
require_once 'sendmail.php';
spl_autoload_register(function ($classname) {
    require_once "app/models/$classname.php";
});

$userModel = new User();

if (isset($_POST['email'])) {
    $email = $_POST['email'];

    $user = $userModel->getUserByEmail($email);
    var_dump($email);
    if ($user) {
        $verificationCode = rand(100000, 999999);
        $_SESSION['verification_code'] = $verificationCode;
        $_SESSION['verification_email'] = $email;

        $subject = "Mã xác nhận quên mật khẩu";
        $body = "Mã xác nhận của bạn là: <strong>$verificationCode</strong>";

        if (sendVerificationEmail($email, $subject, $body)) {
            $_SESSION['notification'] = 'Mã xác nhận đã được gửi đến email của bạn.';
            header('Location: verifycode.php');
        } else {
            $_SESSION['notification'] = 'Không thể gửi email. Vui lòng thử lại.';
        }
    } else {
        $_SESSION['notification'] = 'Email không tồn tại trong hệ thống.';
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên mật khẩu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h3>Quên mật khẩu</h3>
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
                        <form action="forgotpass.php" method="POST">
                            <div class="mb-3">
                                <label for="email" class="form-label">Nhập email</label>
                                <input type="email" name="email" class="form-control" id="email" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Gửi mã xác nhận</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>