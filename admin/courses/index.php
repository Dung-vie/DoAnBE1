<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['isLoggedIn'] === false || $_SESSION['user']['role'] != 'admin') {
    header('Location: ../../index.php');
}
require_once '../../config/database.php';
spl_autoload_register(function ($classname) {
    require_once "../../app/models/$classname.php";
});


$userModel = new User();

if (isset($_POST['btn-delete'])) {
    $userModel->delete($_POST['btn-delete']);
}
$users = $userModel->all();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý khóa học</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <header class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">E-Learning</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div>
                <button class="btn btn-light " type="button">Admin</button>
            </div>
        </div>
    </header>
    <main>
        <div class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <h2 class="m-2 p-3" style="color: white;">
                    <?php echo  "Chào mừng, " . $_SESSION['user']['name']; ?>
                </h2>
            </div>
        </div>
        <div class="content m-4">

            <div class="container" style="box-sizing: border-box;">
                <a href="course.php" class="btn btn-primary">
                    <h5>Quản lý khóa học</h5>
                </a>
            </div>
            <div class="container">
                <div class="row">
                    <div class="container mt-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h1>Quản lý người dùng</h1>
                            <a href="user-add.php" class="btn btn-success">
                                <i class="fas fa-plus"></i> Thêm người dùng
                            </a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tên</th>
                                        <th>Email</th>
                                        <th>Vai trò</th>
                                        <th>Thao tác</th>
                                        <th>Chi tiết</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $user): ?>
                                        <tr>
                                            <td><?php echo $user['id']; ?></td>
                                            <td><?php echo $user['name']; ?></td>
                                            <td><?php echo $user['email']; ?></td>
                                            <td><?php echo $user['role']; ?></td>
                                            <td>
                                                <form action="index.php" method="post" onsubmit="return confirm('Bạn có muốn xóa không?')">
                                                    <button type="submit" class="btn btn-outline-danger" name="btn-delete" value="<?php echo $user['id'] ?>">Delete</button>
                                                </form>
                                            </td>
                                            <th>
                                                <a href="detail.php?id=<?php echo $user['id']; ?>" class="btn btn-outline-primary">Chi tiết</a>
                                            </th>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>


    <footer class="bg-dark text-light py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5>Về chúng tôi</h5>
                    <p>Nền tảng học trực tuyến hàng đầu với các khóa học chất lượng cao từ các chuyên gia.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Liên kết nhanh</h5>
                    <ul class="list-unstyled">
                        <li><a href="courses.php" class="text-light">Khóa học</a></li>
                        <li><a href="instructors.php" class="text-light">Giảng viên</a></li>
                        <li><a href="#" class="text-light">Về chúng tôi</a></li>
                        <li><a href="#" class="text-light">Liên hệ</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Liên hệ</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-phone me-2"></i> 0123 456 789</li>
                        <li><i class="fas fa-envelope me-2"></i> info@example.com</li>
                        <li><i class="fas fa-map-marker-alt me-2"></i> 123 Đường ABC, TP.HCM</li>
                    </ul>
                </div>
            </div>
            <hr class="bg-light">
            <div class="text-center">
                <p class="mb-0">&copy; 2024 E-Learning. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>

</html>