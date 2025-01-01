<?php
session_start();
require_once 'config/database.php';
spl_autoload_register(function ($classname) {
    require_once "app/models/$classname.php";
});
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
}

$course = new Course();
$courses = $course->getAllCourses();
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách khóa học</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">E-Learning</a>
            <div class="navbar-nav ms-auto">
                <span class="nav-item nav-link text-light">
                    Xin chào, <?php echo $_SESSION['user']['name']; ?>
                </span>
                <a class="nav-item nav-link" href="logout.php">Đăng xuất</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Danh sách khóa học</h2>
        <div class="row">
            <?php foreach ($courses as $course): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $course['title']; ?></h5>
                            <p class="card-text"><?php echo $course['description']; ?></p>
                            <p class="card-text"><strong>Giá: </strong><?php echo number_format($course['price']); ?> VNĐ</p>
                            <a href="detail.php?id=<?php echo $course['id']; ?>" class="btn btn-primary">Xem chi tiết</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>