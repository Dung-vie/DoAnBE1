<?php
session_start();
require_once 'config/database.php';
spl_autoload_register(function ($classname) {
    require_once "app/models/$classname.php";
});
$userModel = new User();

if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['role'])) {
    if ($_POST['password'] !== $_POST['confirm_password']) {
        $error = "Mật khẩu xác nhận không khớp!";
    } else {
        $user = $userModel->register($_POST['name'], $_POST['email'], $_POST['password'], $_POST['role']);
        if ($user) {
            $_SESSION['success'] = "Đăng ký thành công! Vui lòng đăng nhập.";
            $success = "Đăng ký thành công! Vui lòng đăng nhập.";
        } else {
            $error = "Có lỗi xảy ra, vui lòng thử lại!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Đăng ký tài khoản</h3>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        <?php if (isset($success)): ?>
                            <div class="alert alert-success"><?php echo $success; ?></div>
                        <?php endif; ?>
                        <form method="POST" action="register.php">
                            <div class="form-group">
                                <label class="form-label">Vai trò:</label> <br>
                                <?php if (isset($_GET['role']) && $_GET['role'] == 'teacher') : ?>
                                    <div class="btn-control" role="group">
                                        <input type="radio" class="btn-check" name="role" id="student" value="student">
                                        <label class="btn btn-outline-primary" for="student">Học sinh</label>

                                        <input type="radio" class="btn-check" name="role" id="teacher" value="teacher" checked>
                                        <label class="btn btn-outline-primary" for="teacher">Giáo viên</label>
                                    </div>
                                <?php else : ?>
                                    <div class="btn-control" role="group">
                                        <input type="radio" class="btn-check" name="role" id="student" value="student" checked>
                                        <label class="btn btn-outline-primary" for="student">Học sinh</label>

                                        <input type="radio" class="btn-check" name="role" id="teacher" value="teacher">
                                        <label class="btn btn-outline-primary" for="teacher">Giáo viên</label>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3">
                                <label for="name" class="form-label">Họ và tên</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Mật khẩu</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Xác nhận mật khẩu</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Đăng ký</button>
                            <div class="text-center mt-3">
                                <p>Đã có tài khoản? <a href="login.php">Đăng nhập ngay</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>