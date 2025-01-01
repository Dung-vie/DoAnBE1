<?php
session_start();
require_once 'config/database.php';
spl_autoload_register(function ($classname) {
    require_once "app/models/$classname.php";
});
$id = $_GET['id'];
$userModel = new User();
$user = $userModel->getUserById($id);
var_dump($user);
if (isset($user['name']) && isset($user['email']) && isset($_FILES['image']['name'])) {
    $name = $user['name'];
    $email = $user['email'];

    $image = basename($_FILES['image']['name']);
    $target_dir = "public/image/";
    $target_file = $target . $image;
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        echo "Hình đã được upload";
    } else {
        echo "Hình chưa được upload";
    }

    if ($userModel->updateProfile($id, $name, $email, $image)) {
        $_SESSION['user'] = $userModel->getUserById($_SESSION['user']['id']);
        $_SESSION['notification'] = 'Cập nhật thông tin thành công';
        // header('Location: index.php');
    } else {
        $_SESSION['notification'] = 'Cập nhật thất bại';
    }
}

?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin cá nhân</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Thông tin cá nhân</h3>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($_SESSION['notification'])): ?>
                            <div class="alert alert-info">
                                <?php
                                echo $_SESSION['notification'];
                                $_SESSION['notification'] = '';
                                ?>
                            </div>
                        <?php endif; ?>

                        <form action="profile.php" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label">Vai trò</label>
                                <input type="text" class="form-control"
                                    value="<?php echo $user['role']; ?>" disabled>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Ảnh đại diện</label>
                                <input type="file" class="form-control" name="image">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Họ tên</label>
                                <input type="text" class="form-control" name="name"
                                    value="<?php echo $user['name']; ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email"
                                    value="<?php echo $user['email']; ?>">
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Cập nhật</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>