<?php
session_start();
require_once 'config/database.php';
spl_autoload_register(function ($classname) {
    require_once "app/models/$classname.php";
});
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
}

$instructor = new Instructor();
$instructors = $instructor->getAllInstructors();

$userModel = new User();

?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách giảng viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .instructor-image {
            width: 200px;
            height: 200px;
            object-fit: cover;
            border-radius: 50%;
        }

        .instructor-card {
            transition: transform 0.3s;
        }

        .instructor-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">E-Learning</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="courses.php">Khóa học</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="instructors.php">Giảng viên</a>
                    </li>
                </ul>
                <div class="navbar-nav ms-auto">
                    <span class="nav-item nav-link text-light">
                        Xin chào, <?php echo $_SESSION['user']['name']; ?>
                    </span>
                    <a class="nav-item nav-link" href="logout.php">Đăng xuất</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2 class="text-center mb-4">Đội ngũ giảng viên</h2>
        <div class="row">
            <?php
            foreach ($instructors as $instructor):
                $user = $userModel->getUserById($instructor['user_id'])
            ?>
                <div class="col-md-4 mb-4">
                    <div class="card instructor-card">
                        <div class="card-body text-center">
                            <img src="public/image/<?php echo $user['image']; ?>.png"
                                class="instructor-image mb-3" width="50px">
                            <h5 class="card-title"><?php echo $user['name']; ?></h5>
                            <p class="card-text text-muted">Email: <?php echo $user['email'] ?></p>
                            <h5 class="card-text text-muted">Tiểu sử: <?php echo $instructor['bio']; ?></h5>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>