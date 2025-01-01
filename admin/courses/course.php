<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['isLoggedIn'] === false || $_SESSION['user']['role'] != 'admin') {
    header('Location: ../../index.php');
}
require_once '../../config/database.php';
spl_autoload_register(function ($classname) {
    require_once "../../app/models/$classname.php";
});

$courseModel = new Course();

if (isset($_POST['course-id'])) {
    $courseModel->bin($_POST['course-id']);
}

$courses = $courseModel->getAllCourses();
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
    <div class="container mt-4">
        <div class="container mb-3" style="box-sizing: border-box;">
            <a href="index.php" class="btn btn-primary">
                <h5>Quản lý người dùng</h5>
            </a>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Danh sách khóa học</h2>
            <a href="add.php" class="btn btn-success">Thêm khóa học</a>
            <a href="bin.php" class="btn btn-danger">BIN</a>
        </div>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên khóa học</th>
                        <th>Mô tả</th>
                        <th>Giá</th>
                        <th>Giảng viên</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($courses as $course): ?>

                        <tr>
                            <td><?php echo $course['id'] ?></td>
                            <td><?php echo $course['title'] ?></td>
                            <td><?php echo substr($course['description'], 0, 50) ?>...</td>
                            <td><?php echo $course['price'] ?></td>
                            <td><?php echo ($courseModel->getName($course['id']))['name'] ?></td>
                            <td>
                                <a href="edit.php?id=<?php echo $course['id'] ?>" class="btn btn-outline-primary">Edit</a>
                                <form action="course.php" method="post" onsubmit="return confirm('Bạn có muốn xóa không?')">
                                    <input type="hidden" name="course-id" value="<?php echo $course['id'] ?>">
                                    <button type="submit" class="btn btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>