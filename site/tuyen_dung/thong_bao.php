<?php
session_start();
require_once "../../dao/pdo.php";

if (isset($_SESSION['user']) || isset($_SESSION['admin'])) {
    if (isset($_GET['id'])) {
        $id_tuyendung = $_GET['id'];
    }
    if (isset($_SESSION['user'])) {
        $id_user = $_SESSION['user']['id'];
    } else {
        $id_user = $_SESSION['admin']['id'];
    }
    // lấy email của người giúp việc
    $conn = connect();
    $sql = "SELECT * FROM tuyen_dung WHERE id = $id_tuyendung";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $tuyen_dung = $stmt->fetch();

    $sql = "SELECT * FROM users WHERE id = " . $tuyen_dung['id_user'];
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $user = $stmt->fetch();

    $email = $user['email'];
    $ho_ten = $user['ho_ten'];
    // lấy thông tin của người tuyển dụng qua session
    $sql = "SELECT * FROM users WHERE id = $id_user";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $tai_khoan = $stmt->fetch();
    $ho_ten_kh = $tai_khoan['ho_ten'];
    $email_kh = $tai_khoan['email'];


    $SENDGRID_API_KEY = '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';
    require './vendor/autoload.php';
    $obj_email = new \SendGrid\Mail\Mail();
    ///------- bạn chỉnh sửa các thông tin dưới đây cho phù hợp với mục đích cá nhân
    // Thông tin người gửi
    $obj_email->setFrom("chucanhquan2001@gmail.com", "Thư thông báo từ website giúp việc Hồng Nguyên");
    // Tiêu đề thư
    $obj_email->setSubject("Thông báo có nhân viên muốn ứng tuyển !");
    // Thông tin người nhận
    $obj_email->addTo($email, $ho_ten);
    // Soạn nội dung cho thư
    // $email->addContent("text/plain", "Nội dung text thuần không có thẻ html");
    $obj_email->addContent(
        "text/html",
        "<b>Trân trọng, $ho_ten !</b><br><b>Thông báo đã có nhân viên muốn ứng tuyển công việc của bạn, vui lòng chủ động liên hệ để làm việc !</b><p>Thông tin nhân viên : </p><p>Họ tên nhân viên : $ho_ten_kh</p><p>Email nhân viên : $email_kh</p>"
    );

    // tiến hành gửi thư
    $sendgrid = new \SendGrid($SENDGRID_API_KEY);
    try {
        $response = $sendgrid->send($obj_email);
        $msg = "Thông báo : Người tuyển dụng sẽ nhận được thông tin của bạn qua email, vui lòng chủ động liên hệ hoặc để ý liên lạc để làm việc với người tuyển dụng !";
        insert_delete_update("UPDATE tuyen_dung SET trang_thai = 1 WHERE id = $id_tuyendung");
        //  print $response->statusCode() . "\n";
        //  print_r($response->headers());
        // print $response->body() . "\n";
        // die;
    } catch (Exception $e) {
        echo 'Caught exception: ' . $e->getMessage() . "\n";
    }
} else {
    header("location:" . BASE_URL . "site/tai_khoan/login.php?msg=Vui lòng đăng nhập !");
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nhân viên</title>
    <?php include('../../link/style.php') ?>
    <link rel="stylesheet" href="../../content/css.css">
    <style>
        .header_title {
            background-color: #64cc7b;
            color: white;
            text-align: center;
            width: 100%;
            height: 80px;
            line-height: 80px;
            font-family: 'Ubuntu', sans-serif;
        }
    </style>
</head>

<body>
    <?php include('../../layout/header.php') ?>
    <br><br>
    <div class="container">
        <div class="row">
            <?= $msg ?><a href="<?= BASE_URL ?>">Quay lại</a>
        </div>
    </div>

    <?php include('../../layout/footer.php') ?>
    <a id="button"></a>
    <?php include('../../link/js.php') ?>
    <script src="../../content/js.js"></script>
    <script src="../../content/jquery.js"></script>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            var $filter = $('.head_nav');
            var $filterSpacer = $('<div />', {
                "class": "vnkings-spacer",
                "height": $filter.outerHeight()
            });
            if ($filter.size()) {
                $(window).scroll(function() {
                    if (!$filter.hasClass('fix') && $(window).scrollTop() > $filter.offset().top) {
                        $filter.before($filterSpacer);
                        $filter.addClass("fix");
                    } else if ($filter.hasClass('fix') && $(window).scrollTop() < $filterSpacer.offset().top) {
                        $filter.removeClass("fix");
                        $filterSpacer.remove();
                    }
                });
            }

        });
    </script>
</body>

</html>