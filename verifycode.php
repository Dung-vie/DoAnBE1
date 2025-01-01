<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputCode = $_POST['code'];
    if ($inputCode == $_SESSION['verification_code']) {
        header('Location: resetpassword.php');
    } else {
        $_SESSION['notification'] = 'Mã xác nhận không chính xác.';
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác minh mã</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h3>Xác minh mã</h3>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($_SESSION['notification'])): ?>
                            <div class="alert alert-danger">
                                <?php
                                echo $_SESSION['notification'];
                                $_SESSION['notification'] = "";
                                ?>
                            </div>
                        <?php endif; ?>
                        <form action="verifycode.php" method="POST">
                            <div class="mb-3">
                                <label for="code" class="form-label">Nhập mã xác nhận</label>
                                <input type="number" name="code" class="form-control" id="code" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Xác nhận</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>