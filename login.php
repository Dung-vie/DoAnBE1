<?php
session_start();
require_once 'config/database.php';
spl_autoload_register(function ($classname) {
    require_once "app/models/$classname.php";
});

$userModel = new User();
if (isset($_POST['email']) && isset($_POST['password'])) {
    $user = $userModel->login($_POST['email'], $_POST['password']);
    if ($user) {
        $_SESSION['isLoggedIn'] = true;
        $_SESSION['user'] = $user;
        // var_dump($_SESSION['user']);
        header('Location: index.php');
    } else {
        $_SESSION['notification'] = 'Sai email hoặc passsword';
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Đăng nhập</h3>
                    </div>
                    <div class="card-body">
                        <?php
                        if (!empty($_SESSION['notification'])) :
                        ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $_SESSION['notification'];
                                $_SESSION = ''; ?>
                            </div>
                        <?php
                        endif;
                        ?>
                        <form action="login.php" method="POST">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email">
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Mật khẩu</label>
                                <input type="password" class="form-control" id="password" name="password">
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Đăng nhập</button>
                        </form>
                        <div class="mt-3" style="text-align: center;">
                            <a href="forgotpass.php">Bạn đã quên mật khẩu?</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>