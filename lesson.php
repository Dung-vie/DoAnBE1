<?php
session_start();
require_once 'config/database.php';
spl_autoload_register(function ($classname) {
    require_once "app/models/$classname.php";
});
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
}
$lessonModel = new Lesson();
$lessons = $lessonModel->getLessonsByCourse(1);



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
    <h2>Danh sách bài học</h2>
    <div class="list-group">
        <?php foreach($lessons as $lesson) : ?>
            <div class="list-group-item">
                <a href="#lesson-<?php echo $lesson['id']; ?>" data-bs-toggle="collapse" class="d-block mb-2">
                    <h5 class="card-title"><?php echo $lesson['title']; ?></h5>
                </a>

                <div id="lesson-<?php echo $lesson['id']; ?>" class="collapse">
                    <?php
                    $video_url = $lesson['video'];
                    $video_id = substr(parse_url($video_url, PHP_URL_PATH), 1);
                    ?>
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" 
                                src="https://www.youtube.com/embed/<?php echo $video_id; ?>" 
                                frameborder="0" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                allowfullscreen>
                        </iframe>
                    </div>
                </div>

                <p class="card-text mt-2"><?php echo $lesson['content']; ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</div>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>