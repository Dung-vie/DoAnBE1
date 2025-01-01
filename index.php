<?php
session_start();
require_once 'config/database.php';
spl_autoload_register(function ($classname) {
    require_once "app/models/$classname.php";
});

$courseModel = new Course();
$courses = $courseModel->getAllCourses();


$isLoggedIn = isset($_SESSION['user']);
// var_dump($_SESSION['user']);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nền tảng học trực tuyến</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="public/css/style.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">E-Learning</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="courses.php">Khóa học</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="instructor.php">Giảng viên</a>
                    </li>
                    <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] == 'student'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="mycourse.php">Khóa học của tôi</a>
                        </li>
                    <?php
                    elseif (isset($_SESSION['user']) && $_SESSION['user']['role'] == 'teacher'):
                        $user_id = $_SESSION['user']['id'];
                        $instructorModel = new Instructor();
                        $instructor = $instructorModel->getInstructorIdByUserId($user_id);
                    ?>
                        <li class="nav-item me-2">
                            <a href="teacher/addlesson.php?id=<?php echo $instructor['id'] ?>" class="nav-link">Thêm bài giảng</a>
                        </li>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] == 'admin'): ?>
                        <li class="nav-item">
                            <a href="admin/courses/" class="btn btn-outline-primary">Quản trị</a>
                        </li>
                    <?php endif; ?>
                </ul>

                <form class="d-flex me-3" action="search.php" method="GET">
                    <input class="form-control me-2" type="search" name="q" placeholder="Tìm khóa học...">
                    <button class="btn btn-outline-light" type="submit">Tìm</button>
                </form>

                <?php
                if (isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn'] === true && isset($_SESSION['user'])) :
                ?>
                    <div class="dropdown">
                        <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown">
                            <?php echo "Xin chào " . $_SESSION['user']['name']; ?>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="my-courses.php">Khóa học của tôi</a></li>
                            <li><a class="dropdown-item" href="profile.php?id=<?php echo $_SESSION['user']['id']  ?>"> Thông tin cá nhân</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="logout.php">Đăng xuất</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <div class="navbar-nav">
                        <a class="btn btn-outline-light me-2" href="login.php">Đăng nhập</a>
                        <a class="btn btn-light" href="register.php">Đăng ký</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="position-relative text-center">
        <img src="public/image/bg_header.jpg" alt="Background Image" class="img-fluid w-100 opacity-75">
        <div class="position-absolute top-50 start-50 translate-middle text-white p-4 text-shadow">
            <h1 class="display-4 mb-4">Học trực tuyến cùng chuyên gia</h1>
            <p class="lead mb-4">Khám phá hàng nghìn khóa học chất lượng cao với giảng viên hàng đầu</p>
            <a href="courses.php" class="btn btn-primary btn-lg">Khám phá ngay</a>
        </div>
    </div>


    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-4">Khóa học nổi bật</h2>
            <div class="row">
                <?php
                $featuredCourses = array_slice($courses, 0, 3);
                foreach ($featuredCourses as $course):
                ?>
                    <div class="col-md-4 mb-4">
                        <div class="card course-card h-100">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $course['title']; ?></h5>
                                <p class="card-text"><?php echo substr($course['description'], 0, 100); ?>...</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-primary font-weight-bold">
                                        <?php echo number_format($course['price'], 0, ',', '.'); ?>đ
                                    </span>
                                    <div>
                                        <p class="text-muted">
                                            <i class="fas fa-users"></i>
                                            <?php echo $course['student_count'] ?? 0; ?> học viên
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-white border-top-0">
                                <a href="detail.php?id=<?php echo $course['id']; ?>"
                                    class="btn btn-outline-primary w-100">Xem chi tiết</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="courses.php" class="btn btn-outline-primary">Xem tất cả khóa học</a>
        </div>
        </div>
    </section>

    <?php if (! $isLoggedIn) : ?>
        <section class="py-5 ">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 text-center mb-6">
                        <img src="public/image/faces_collage_2x.png" width="100%" height="auto">
                    </div>
                    <div class="col-md-6  mb-6">
                        <span>GIÁO VIÊN</span><br>
                        <h1>Hiểu rõ hơn sự khác biệt giữa các học sinh trong lớp của bạn, từ đó thu hút sự tham gia của mọi học sinh.</h1> <br>
                        <p>Chúng tôi giúp các giáo viên quan tâm đến tất cả mọi học sinh trong lớp. 90% giáo viên đã từng sử dụng E-Learning đều cảm thấy E-Learning hiệu quả.</p><br>
                        <a href="register?role=teacher" class="btn btn-outline-primary">Nếu bạn là giáo viên, bắt đầu từ đây</a>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-5 ">
            <div class="container">
                <div class="row">
                    <div class="col-md-5 ">
                        <span>NGƯỜI HỌC VÀ HỌC SINH</span> <br> <br>
                        <h1>Bạn có thể học bất cứ điều gì.</h1> <br>
                        <p>Xây dựng nền tảng kiến thức vững chắc về toán học, khoa học và nhiều bộ môn khác.</p><br>
                        <a href="register" class="btn btn-outline-primary">Nếu bạn là người học, bắt đầu từ đây</a>
                    </div>
                    <div class="col-md-7 text-center">
                        <img src="public/image/hero_student_collage_US_2x.png" width="80%" height="auto">
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Tại sao chọn chúng tôi?</h2>
            <div class="row">
                <div class="col-md-4 text-center mb-4">
                    <i class="fas fa-chalkboard-teacher fa-3x text-primary mb-3"></i>
                    <h4>Giảng viên chất lượng</h4>
                    <p>Đội ngũ giảng viên giàu kinh nghiệm và tận tâm</p>
                </div>
                <div class="col-md-4 text-center mb-4">
                    <i class="fas fa-laptop-code fa-3x text-primary mb-3"></i>
                    <h4>Học mọi lúc mọi nơi</h4>
                    <p>Truy cập và học tập mọi lúc trên mọi thiết bị</p>
                </div>
                <div class="col-md-4 text-center mb-4">
                    <i class="fas fa-certificate fa-3x text-primary mb-3"></i>
                    <h4>Chứng chỉ sau khóa học</h4>
                    <p>Nhận chứng chỉ có giá trị sau khi hoàn thành khóa học</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="row align-items-center">
                            <div class="col-md-4 mb-4">
                                <img src="public/image/hs1" class="img-fluid rounded-circle">
                            </div>

                            <div class="col-md-8" style="color: orangered;">
                                <h3>Anh Nguyễn Văn Trường - Trưởng BTC Trường CĐCN Thủ Đức</h3>
                                <p class="font-italic">Senior Project Manager tại Creative Point</p>
                            </div>
                        </div>
                        <h1>“</h1>
                        <h5>Ứng dụng học tập này thực sự thay đổi cách tôi tiếp cận kiến thức – mọi thứ đều dễ dàng và tiện lợi hơn rất nhiều.Chất lượng giảng dạy và sự hỗ trợ tận tình từ đội ngũ giảng viên khiến tôi cảm thấy đây là lựa chọn hoàn hảo. </h5>
                        <h1 style="text-align: end;">”</h1>
                    </div>

                    <div class="carousel-item">
                        <div class="row align-items-center">
                            <div class="col-md-4 mb-4">
                                <img src="public/image/hs2.png" alt="Nguyệt Quế Đỏ II" class="img-fluid rounded-circle">
                            </div>

                            <div class="col-md-8" style="color: orangered;">
                                <h3>Chị Hồ Thị Hải Yến - Trưởng BTC Nguyệt Quế Đỏ II</h3>
                                <p class="font-italic">Senior Project Manager tại Creative Point</p>
                            </div>
                        </div>
                        <h1>“</h1>
                        <h5>Đến bây giờ chị vẫn còn lâng lâng cảm giác cùng mọi người tạo ra một sân chơi trí tuệ thú vị, kịch tính với sứ mệnh đem vinh quang về cho Quốc Học - Huế. Nguyệt Quế Đỏ là nơi chị học, chị trải nghiệm, chị sửa sai và giúp chị trưởng thành hơn. Dù ở vị trí nào, trở thành một phần của Quế là điều mà chị sẽ không bao giờ hối tiếc.</h5>
                        <h1 style="text-align: end;">”</h1>
                    </div>

                    <div class="carousel-item">
                        <div class="row align-items-center">
                            <div class="col-md-4 mb-4">
                                <img src="public/image/hs3.png" alt="Nguyệt Quế Đỏ II" class="img-fluid rounded-circle">
                            </div>

                            <div class="col-md-8" style="color: orangered;">
                                <h3>Anh Trương Gia Huy - Học sinh bộ môn Java</h3>
                                <p class="font-italic">Senior Project Manager tại Creative Point</p>
                            </div>
                        </div>
                        <h1>“</h1>
                        <h5>Tôi yêu thích cách ứng dụng này cá nhân hóa trải nghiệm học tập – giúp tôi học đúng thứ mình cần, đúng cách mình muốn</h5>
                        <h1 style="text-align: end;">”</h1>
                    </div>
                </div>

                <div class="carousel-indicators">
                    <button type="button " style="background-color: orange;" data-bs-target="#testimonialCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" style="background-color: orange;" data-bs-target="#testimonialCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                    <button type="button" style="background-color: orange;" data-bs-target="#testimonialCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-dark text-light py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5>Về chúng tôi</h5>
                    <p>Nền tảng học trực tuyến hàng đầu với các khóa học chất lượng cao từ các chuyên gia.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Liên kết nhanh</h5>
                    <ul class="list-unstyled">
                        <li><a href="courses.php" class="text-light">Khóa học</a></li>
                        <li><a href="instructors.php" class="text-light">Giảng viên</a></li>
                        <li><a href="#" class="text-light">Về chúng tôi</a></li>
                        <li><a href="#" class="text-light">Liên hệ</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Liên hệ</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-phone me-2"></i> 0123 456 789</li>
                        <li><i class="fas fa-envelope me-2"></i> info@example.com</li>
                        <li><i class="fas fa-map-marker-alt me-2"></i> 123 Đường ABC, TP.HCM</li>
                    </ul>
                </div>
            </div>
            <hr class="bg-light">
            <div class="text-center">
                <p class="mb-0">&copy; 2024 E-Learning. All rights reserved.</p>
            </div>
        </div>
    </footer>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</html>