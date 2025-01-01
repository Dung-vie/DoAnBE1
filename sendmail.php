<?php
// Include các file PHPMailer
require_once 'mail/PHPMailer/src/PHPMailer.php';
require 'mail/PHPMailer/src/SMTP.php';
require 'mail/PHPMailer/src/Exception.php';

// Sử dụng các lớp PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendVerificationEmail($toEmail, $subject, $body)
{
    $mail = new PHPMailer(true);

    try {
        // Cấu hình SMTP
        $mail->isSMTP();  // Sử dụng SMTP
        $mail->CharSet = 'utf-8';                                      //Send using SMTP
        $mail->Host = 'smtp.gmail.com';                 //Set the SMTP server to send through
        $mail->SMTPAuth   = true;
        $mail->Username = 'dunghoang1122004@gmail.com'; // Thay bằng email của bạn
        $mail->Password = 'yrnx lsjj dlyp umod'; // Thay bằng mật khẩu ứng dụng của Gmail
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Sử dụng STARTTLS
        $mail->Port = 587; // Cổng SMTP của Gmail

        // Thiết lập người gửi và người nhận
        $mail->setFrom('dunghoang1122004@gmail.com', 'E-learning'); // Địa chỉ email và tên người gửi
        $mail->addAddress($toEmail); // Địa chỉ email người nhận

        // Thiết lập nội dung email
        $mail->isHTML(true);  // Cho phép gửi email dưới dạng HTML
        $mail->Subject = $subject; // Tiêu đề email
        $mail->Body    = $body; // Nội dung email

        // Gửi email
        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
