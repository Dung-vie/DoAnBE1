<?php
session_start();
require_once '../../config/database.php';
spl_autoload_register(function ($classname) {
    require_once "../../app/models/$classname.php";
});
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
}

$userModel = new User();
$user = $userModel->getUserById($_GET['id']);

$instructorModel = new Instructor();

$courseModel = new Course();

$enrollmentModel = new Enrollment();
$enrollments = $enrollmentModel->getAllCourseRegister($user['id']);
// var_dump($enrollments);

?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body>
    <div class="container my-5">
        <div class="row">
            <div class="col-md-7">
                <h5>Vai trò: <?php echo $user['role'] ?></h5>
                <h2 class="mb-4">Họ và tên: <?php echo $user['name']; ?></h2>
                <p>Email: <?php echo $user['email'] ?></p>
            </div>
            <div class="col-md-5">
                <div class="card">
                    <div class="card-body">
                        <?php if ($user['role'] == "student") : $count = 0; ?>
                            <h5>Thông tin các khóa học đã đăng kí</h5>
                            <?php foreach ($enrollments as $enrollment) : ?>
                                <?php if ($enrollment['statusCourse'] == 1) :
                                    $count++;
                                    $course = $courseModel->getCourseById($enrollment['course_id']);
                                ?>
                                    <p><?php echo $count . ")" ?></p>
                                    <h4><?php echo $course['title'] ?></h4>
                                    <p>Ngày đăng kí: <?php echo $enrollment['time'] ?></p>
                                    <p><?php echo substr($course['description'], 0, 50) ?>...</p>
                                    <h5><?php echo $course['price'] ?></h5>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            <?php if ($count < 1) : ?>
                                <p class="alert alert-danger">Bạn chưa đăng kí khóa học nào! </p>
                            <?php endif; ?>
                        <?php else :
                            $courses = $courseModel->getCourseByInstructor($user['id']);
                            // var_dump($courses);
                            $count = 0;
                        ?>
                            <h5>Thông tin các khóa học bạn đã dạy</h5>
                            <p>Tiểu sử:<?php echo $courses[0]['bio']; ?></p>
                            <?php foreach ($courses as $course) : ?>
                                <b><?php echo ++$count . ")"; ?></b>
                                <h5>Tên khóa học:<?php echo $course['title']; ?></h5>
                                <p>Chi tiết:<?php echo $course['description']; ?></p>
                                <p>Xếp hạng:<?php echo $course['rating']; ?></p>
                                <p>Gía: <?php echo $course['price']; ?></p>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
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