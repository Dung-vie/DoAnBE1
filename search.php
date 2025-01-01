<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['isLoggedIn'] === false) {
    header('Location: login.php');
}
require_once 'config/database.php';
spl_autoload_register(function ($classname) {
    require_once "app/models/$classname.php";
});

$courseModel = new Course();
if (!empty($_GET['q'])) {
    $q = trim($_GET['q']);
    $courses = $courseModel->findByKeyWord($q);
} else {
    header('location: index.php');
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kết quả tìm kiếm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .course-card:hover {
            transform: translateY(-5px);
            transition: transform 0.3s ease;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <!-- Navbar -->
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
                    <input class="form-control me-2" type="search" name="q" placeholder="Tìm kiếm khóa học..." value="<?php echo $q; ?>">
                    <button class="btn btn-outline-light" type="submit">Tìm</button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Search Results -->
    <div class="container my-5">
        <h2 class="text-center mb-4">Kết quả tìm kiếm</h2>
        <?php if (!empty($q)): ?>
            <p class="text-center">Kết quả cho từ khóa: <strong><?php echo $q; ?></strong></p>
        <?php endif; ?>
        <div class="row">
            <?php if (!empty($courses)): ?>
                <?php foreach ($courses as $course): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card course-card h-100">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo ($course['title']); ?></h5>
                                <p class="card-text"><?php echo substr($course['description'], 0, 100); ?>...</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-primary font-weight-bold"><?php echo number_format($course['price'], 0, ',', '.'); ?> VND</span>
                                </div>
                            </div>
                            <div class="card-footer bg-white border-top-0">
                                <a href="detail.php?id=<?php echo $course['id']; ?>" class="btn btn-outline-primary w-100">Xem chi tiết</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center text-muted">Không tìm thấy khóa học nào phù hợp với từ khóa "<strong><?php echo ($q); ?></strong>".</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4">
        <div class="container text-center">
            <p>&copy; 2024 E-Learning. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>