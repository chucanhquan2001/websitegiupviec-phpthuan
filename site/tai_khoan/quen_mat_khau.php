<?php
require_once "../../dao/pdo.php";
session_start();

if (isset($_POST['quyen_mat_khau'])) {
    $username_err  = $email_err = $emaildk_err = $error = $msg = "";
    $value = selectAll("select * from users");

    if (empty($_POST['username'])) {
        $username_err = "Xin mời bạn nhập User Name";
    } else {
        $userName = $_POST['username'];
    }

    if (empty($_POST['emaildk'])) {
        $emaildk_err = "Vui lòng nhập email !";
    } else {
        $emaildk = $_POST['emaildk'];
        if (!filter_var($emaildk, FILTER_VALIDATE_EMAIL)) {
            $emaildk_err = "Vui lòng nhập đúng định dạng email";
        }
    }
    if ($username_err . $email_err == "") {
        $dem = selectAll("select * from users where  email = '$emaildk' and ten_dang_nhap = '$userName'");
        if (count($dem) <= 0) {
            $error = " Email và tên đăng nhập không chính xác !";
        }
    }
    if (empty($_POST['email'])) {
        $email_err = "Vui lòng nhập email !";
    } else {
        $email = $_POST['email'];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email_err = "Vui lòng nhập đúng định dạng email";
        }
    }
    if ($username_err . $email_err . $emaildk_err . $error == "") {
        $SENDGRID_API_KEY = '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';
        $mat_khau = uniqid();
        require '../lien_he/vendor/autoload.php';
        $obj_email = new \SendGrid\Mail\Mail();
        ///------- bạn chỉnh sửa các thông tin dưới đây cho phù hợp với mục đích cá nhân
        // Thông tin người gửi
        $obj_email->setFrom("chucanhquan2001@gmail.com", "Thư từ website giúp việc Hồng Nguyên");
        // Tiêu đề thư
        $obj_email->setSubject("Lấy lại mật khẩu");
        // Thông tin người nhận
        $obj_email->addTo($email, 'Khách Hàng');
        // Soạn nội dung cho thư
        // $email->addContent("text/plain", "Nội dung text thuần không có thẻ html");
        $obj_email->addContent(
            "text/html",
            "
            <p>Mật khẩu mới của bạn : {$mat_khau}</p><p>Vui lòng đổi mật khẩu sớm nhất để đảm bảo tài khoản của bạn được bảo mật tuyệt đối !</p>

             "
        );

        // tiến hành gửi thư
        $sendgrid = new \SendGrid($SENDGRID_API_KEY);
        try {
            $response = $sendgrid->send($obj_email);
            $pass = md5($mat_khau);
            insert_delete_update(" UPDATE users set mat_khau = '$pass' where ten_dang_nhap = '$userName' ");
            $msg = "Gửi thành công ! Vui lòng check email của bạn để lấy lại mật khẩu !";
        } catch (Exception $e) {
            echo 'Caught exception: ' . $e->getMessage() . "\n";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lấy lại mật khẩu</title>
    <?php include('../../link/style.php') ?>
    <link rel="stylesheet" href="../../content/css.css">
    <link rel="stylesheet" href="../../content/lien_he.css">
</head>

<body>
    <?php include('../../layout/header.php'); ?>
    <div class="container-fluid">
        <div class="row " style="margin-top:20px;margin-bottom:20px;">
            <h3 class="header_title">Quên Mật Khẩu</h3>
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <?php if (isset($_GET['msg'])) { ?>
                            <span style="color:red;"><?php echo $_GET['msg'] ?></span>
                        <?php } ?>
                        <?php if (isset($error)) { ?>
                            <span style="color:red;"><?php echo $error ?></span>
                        <?php } ?>
                        <?php if (isset($msg)) { ?>
                            <span style="color:red;"><?php echo $msg ?></span>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <form action="" method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-9">
                        <div class="row">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">User Name *</label>
                                    <input type="text" class="form-control" name="username" placeholder="UserName" style="background-color: #fdf7f7;">
                                    <?php if (isset($username_err)) { ?>
                                        <span style="color:red;">
                                            <?php echo $username_err . "<br>" ?></span>
                                    <?php } ?>
                                </div>
                                <div class="form-group">
                                    <label for="">Email đăng ký *</label>
                                    <input type="text" class="form-control" name="emaildk" placeholder="Email đăng ký" style="background-color: #fdf7f7;">
                                    <?php if (isset($emaildk_err)) { ?>
                                        <span style="color:red;">
                                            <?php echo $emaildk_err . "<br>" ?></span>
                                    <?php } ?>
                                </div>
                                <div class="form-group">
                                    <label for="">Email Nhận *</label>
                                    <input type="text" class="form-control" name="email" placeholder="Email người nhận" style="background-color: #fdf7f7;">
                                    <?php if (isset($email_err)) { ?>
                                        <span style="color:red;">
                                            <?php echo $email_err . "<br>" ?></span>
                                    <?php } ?>
                                </div>
                                <br>
                                <button type="submit" class="btn btn-success" name="quyen_mat_khau">Lấy mật khẩu</button>
                                <a href="index.php" type="submit" class="btn btn-danger">Quay lại</a>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-3">
                        <?php include_once "../../layout/content_right.php" ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php include_once('../../layout/footer.php') ?>
</body>
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

</html>