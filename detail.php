<?php
session_start();
require_once 'config/database.php';
spl_autoload_register(function ($classname) {
    require_once "app/models/$classname.php";
});

// Kiểm tra nếu chưa đăng nhập thì chuyển hướng đến trang login
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
}

$courseId = isset($_GET['id']) ? $_GET['id'] : null;

$courseModel = new Course();
$course = $courseModel->findById($courseId);

$instructorModel = new Instructor();
$instructor = $instructorModel->findById($course['instructor_id']);

// Xử lý đăng ký khóa học khi người dùng gửi form
if (isset($_POST['course_id']) && isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $courseId = $_POST['course_id'];

    if ($courseModel->enrollCourse($userId, $courseId)) {
        echo "Đăng ký thành công!";
    } else {
        echo "Có lỗi xảy ra, vui lòng thử lại.";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết khóa học</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php"><i class="fas fa-graduation-cap"></i> E-Learning</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="courses.php">Khóa học</a></li>
                    <li class="nav-item"><a class="nav-link" href="instructors.php">Giảng viên</a></li>
                </ul>
                <form class="d-flex me-3" action="search.php" method="GET">
                    <input class="form-control me-2" type="search" name="keyword" placeholder="Tìm kiếm khóa học...">
                    <button class="btn btn-outline-light" type="submit">Tìm</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <div class="row">
            <div class="col-md-8">
                <h1 class="mb-4"><?php echo $course['title']; ?></h1>
                <p><?php echo $course['description']; ?></p>
                <h4 class="mt-4">Giá khóa học:</h4>
                <p class="text-primary fw-bold"><?php echo number_format($course['price'], 0, ',', '.'); ?>đ</p>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5>Thông tin giảng viên</h5>
                        <?php
                        $userModel = new User();
                        $user = $userModel->getUserById($instructor['user_id']);
                        ?>
                        <p><img src="public/image/<?php echo $user['image']; ?>" alt="teacher" width="300px"></p>
                        <p><strong>Tên:</strong> <?php echo $user['name']; ?></p>
                        <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
                        <p><strong>Tiểu sử:</strong> <?php echo $instructor['bio']; ?></p>
                    </div>
                </div>
                <?php if ($_SESSION['user']['role'] == 'student') : ?>
                    <form method="POST" action="confirm_enroll.php">
                        <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                        <button type="submit" class="btn btn-primary">Đăng ký khóa học</button>
                    </form>

                <?php endif; ?>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-light py-4">
        <div class="container text-center">
            <p>&copy; 2024 E-Learning. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>