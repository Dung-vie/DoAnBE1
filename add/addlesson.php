<?php
session_start();
require_once '../config/database.php';
spl_autoload_register(function ($classname) {
    require_once "../app/models/$classname.php";
});

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] == 'student') {
    header('Location: ../index.php');
}
$courseModel = new Course();
$courses = $courseModel->getAllCourses();

$lessonModel = new Lesson();
$_SESSION['notification'] = "Thêm bài học thành công";

var_dump($_GET['id']);
$lessons = $lessonModel->getLessonsByInstructor($_GET['id']);
                    var_dump($lessons);

if (!empty($_POST['title']) && !empty($_POST['content'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $course_id = $_POST['course_id'];
    $order_number = $_POST['order_number'];

    $video_path = '';
    if (isset($_FILES['video']) && $_FILES['video']['error'] === 0) {
        $video_name = time() . '_' . $_FILES['video']['name'];
        $video_path = 'uploads/videos/' . $video_name;
        move_uploaded_file($_FILES['video']['tmp_name'], $video_path);
    }


    if ($lessonModel->createLesson($course_id, $title, json_encode($content_data), $order_number)) {
        echo $_SESSION['notification'];
        header("Location: course.php?id=$course_id");
    }
}

?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Bài Học</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        <h2>Thêm Bài Học Mới</h2>

        <form action="addlesson.php" method="POST">
            <div class="mb-3">
                <label class="form-label">Tiêu đề bài học</label>
                <input type="text" class="form-control" name="title">
            </div>

            <div class="mb-3">
                <label class="form-label">Nội dung</label>
                <textarea class="form-control" name="content" rows="5"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Video bài giảng</label>
                <input type="file" class="form-control" name="video">
            </div>

            <div class="mb-3">
                <label class="form-label">Chuyên đề khóa học</label>
                <select class="form-control" name="course_id">
                    <?php
                    foreach ($courses as $course):
                    ?>
                        <option value="<?php echo $course['id'] ?>">
                            <?php echo $course['title'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Thêm bài học</button>
            <a href="../index.php" class="btn btn-secondary">Hủy</a>
        </form>

        <?php
        if ($_SESSION['user']['role'] == 'teacher') :
        ?>
            <h3 class="mt-4">Danh sách bài học hiện tại của Giảng Viên <?php echo $_SESSION['user']['name'] ?></h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Khoá học</th>
                        <th>Tiêu đề bài học</th>
                        <th>Số lượng đăng kí học</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($lessons as $lesson) : 
                    ?>
                        <tr>
                            <td><?php echo $lesson['course_title']; ?></td>
                            <td><?php echo $lesson['title']; ?></td>
                            <td><?php echo $lesson['order_number']; ?></td>
                            <td>
                                <a href="deletelesson.php?id=<?php echo $lesson['id']; ?>"
                                    class="btn btn-sm btn-danger"
                                    onclick="return confirm('Bạn có chắc muốn xóa bài học này không?')">Xóa</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>

</html>