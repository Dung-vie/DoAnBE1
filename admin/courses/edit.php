<!-- edit.php -->
<?php
require_once '../../config/database.php';
spl_autoload_register(function ($classname) {
    require_once "../../app/models/$classname.php";
});

$id = $_GET['id'];
$courseModel = new Course();
$course = $courseModel->getCourseById($id);

$instructorModel = new Instructor();
$instructors = $instructorModel->getAllInstructors();

$userModel = new User();
if (!empty($_POST['title']) && !empty($_POST['description']) && !empty($_POST['price']) && !empty($_POST['instructor_id'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $instructor_id = $_POST['instructor_id'];

    if ($courseModel->update($_GET['id'], $title, $description, $price, $instructor_id)) {
        $_SESSION['notification'] = "Cập nhật thành công";
        echo  $_SESSION['notification'];
        header('Location: course.php');
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa khóa học</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        <h2>Sửa khóa học</h2>
        <form method="POST" action="edit.php?id=<?php echo $course['id'] ?>">
            <div class="mb-3">
                <label class="form-label">Tên khóa học</label>
                <input type="text" class="form-control" name="title"
                    value="<?php echo $course['title'] ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Mô tả</label>
                <textarea class="form-control" name="description" rows="3"><?php echo $course['description'] ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Giá</label>
                <input type="number" class="form-control" name="price"
                    value="<?php echo $course['price'] ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Giảng viên</label>
                <select class="form-control" name="instructor_id">
                    <?php
                    foreach ($instructors as $instructor):
                    ?>
                        <option value="<?php echo $instructor['id'] ?>">
                            <?php echo ($userModel->getUserById($instructor['user_id']))['name'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="index.php" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
</body>

</html>