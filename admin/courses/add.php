<?php
require_once '../../config/database.php';
spl_autoload_register(function ($classname) {
    require_once "../../app/models/$classname.php";
});
$instructorModel = new Instructor();
$instructors = $instructorModel->getAllInstructors();

$userModel = new User();
$_SESSION['notification'] = "Them thanh cong";
if (isset($_POST['instructor_id'])) {
    $_SESSION['notification'] = "Vui lòng chọn giáo viên";
    echo $_SESSION['notification'];
}
if (!empty($_POST['title']) && !empty($_POST['description']) && !empty($_POST['price']) && !empty($_POST['instructor_id'])) {
    $courseModel = new Course();

    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $instructor_id = $_POST['instructor_id'];
    if ($courseModel->add($title,  $price, $description, $instructor_id)) {
        echo $_SESSION['notification'];
        header('Location: course.php');
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm khóa học mới</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        <h2>Thêm khóa học mới</h2>
        <form action="add.php" method="POST">
            <div class="mb-3">
                <label class="form-label">Tên khóa học</label>
                <input type="text" class="form-control" name="title">
            </div>
            <div class="mb-3">
                <label class="form-label">Mô tả</label>
                <textarea class="form-control" name="description" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Giá</label>
                <input type="number" class="form-control" name="price">
            </div>
            <div class="mb-3">
                <label class="form-label">Giảng viên</label>
                <select class="form-control" name="instructor_id">
                    <option value="">Chọn giảng viên</option>
                    <?php foreach ($instructors as $instructor): ?>
                        <option value="<?php echo $instructor['id'] ?>">
                            <?php echo ($userModel->getUserById($instructor['user_id']))['name'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Thêm khóa học</button>
            <a href="index.php" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
</body>

</html>