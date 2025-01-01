<?php
session_start();
require_once 'config/database.php';
spl_autoload_register(function ($classname) {
    require_once "app/models/$classname.php";
});

// Kiểm tra nếu chưa đăng nhập thì chuyển hướng đến trang login
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

// Kiểm tra nếu `course_id` tồn tại trong `$_POST`
if (!isset($_POST['course_id'])) {
    echo "Không tìm thấy thông tin khóa học.";
    exit;
}

$courseId = $_POST['course_id'];

// Lấy thông tin khóa học từ cơ sở dữ liệu
$courseModel = new Course();
$course = $courseModel->findById($courseId);

// Kiểm tra nếu không tìm thấy khóa học
if (!$course) {
    echo "Khóa học không tồn tại.";
    exit;
}

// Xử lý đăng ký khóa học
$userId = $_SESSION['user']['id']; // ID của người dùng đang đăng nhập
if ($courseModel->enrollCourse($userId, $courseId)) {
    echo "Đăng ký khóa học thành công!";
} else {
    echo "Có lỗi xảy ra trong quá trình đăng ký.";
}
?>


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form method="POST" action="confirm_enroll.php">
        <input type="hidden" name="course_id" value="<?php $course['id'] ?>">
        <button type="submit" class="btn btn-success" <?php $course['statusCourse'] ? 'disabled' : '' ?>>
            Xác nhận đăng ký
        </button>
    </form>
</body>

</html>