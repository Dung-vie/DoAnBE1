<?php
session_start();
require_once 'config/database.php';
spl_autoload_register(function ($classname) {
    require_once "app/models/$classname.php";
});

$courseModel = new Course();
$enrolledCourses = $courseModel->getUserEnrollments($_SESSION['user_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];
    $courseId = $_POST['course_id'];

    if ($courseModel->confirmEnrollment($userId, $courseId)) {
        echo "Đăng ký đã được xác nhận!";
    } else {
        echo "Có lỗi xảy ra, vui lòng thử lại.";
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h2>Khóa học chưa xác nhận</h2>
    <form method="POST" action="confirm_enrollment.php">
        <input type="hidden" name="course_id" value="<?php $course['id'] ?>">
        <button type="submit" class="btn btn-success" <?php $course['statusCourse'] ? 'disabled' : '' ?>>
            Xác nhận đăng ký
        </button>
    </form>

    <h3>Các khóa học đã đăng ký:</h3>
    <ul>
        <?php foreach ($enrolledCourses as $course): ?>
            <li>
                <?= $course['name'] ?> - <?= $course['description'] ?>
                (Trạng thái: <?= $course['statusCourse'] ? 'Đã đăng ký' : 'Chưa đăng ký' ?>)
                <small>Thời gian: <?= $course['enrolled_at'] ?></small>
            </li>
        <?php endforeach; ?>
    </ul>

</body>

</html>