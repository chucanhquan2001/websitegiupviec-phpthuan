<?php
session_start();
require_once('../../dao/pdo.php');
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM lien_he WHERE id = '$id'";
    $conne = connect();
    $stml = $conne->prepare($sql);
    $stml->execute();
    $select = $stml->fetch();
}

if (isset($_POST['gui'])) {

    $err_tieu_de = $err_noi_dung = "";
    if (empty($_POST['tieu_de'])) {
        $err_tieu_de = "Vui lòng nhập tiêu đề !";
    } else {
        $tieu_de = $_POST['tieu_de'];
    }
    if (empty($_POST['noi_dung'])) {
        $err_noi_dung = "Vui lòng nhập nội dung !";
    } else {
        $noi_dung = $_POST['noi_dung'];
    }
    if ($err_tieu_de . $err_noi_dung  == "") {
        //1. Key dưới đây chỉ dùng tạm, khi chạy dịch vụ chính thức bạn cần đăng ký tài khoản của sendgrid.com
        // website nhỏ thì dùng tài khoản miễn phí ok
        // tham khảo cách đăng ký để lấy key https://saophaixoan.net/search-tut?q=sendgrid
        // trong code này chỉ cần lấy key là ok, sau khi gửi thử xong thì verify là ok.
        $SENDGRID_API_KEY = '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';

        require './vendor/autoload.php';
        $obj_email = new \SendGrid\Mail\Mail();
        ///------- bạn chỉnh sửa các thông tin dưới đây cho phù hợp với mục đích cá nhân
        // Thông tin người gửi
        $obj_email->setFrom("chucanhquan2001@gmail.com", "Thư từ admin website giúp việc");
        // Tiêu đề thư
        $obj_email->setSubject("$tieu_de");
        // Thông tin người nhận
        $obj_email->addTo($select['email'], $select['ho_ten']);
        // Soạn nội dung cho thư
        // $email->addContent("text/plain", "Nội dung text thuần không có thẻ html");
        $obj_email->addContent(
            "text/html",
            "Nội dung thư : <h2 style='color: red'> $noi_dung </h2>"
        );

        // tiến hành gửi thư
        $sendgrid = new \SendGrid($SENDGRID_API_KEY);
        try {
            $response = $sendgrid->send($obj_email);
            insert_delete_update("UPDATE lien_he SET tra_loi = 1 WHERE id = '$id'");
            header("Location:" . BASE_URL . "admin/lien_he/tra_loi.php?msg=Gửi thành công !&id=$id ");
            // print $response->statusCode() . "\n";
            // print_r($response->headers());
            // print $response->body() . "\n";
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
    <title>Document</title>
    <?php include('../../link/style.php') ?>
    <link rel="stylesheet" href="../../content/css.css">
    <style>
        .vertical-menu {
            margin-top: 40px;
            width: 205px;
            border-radius: 10px;
            box-shadow: 0 0 10px 0 #d7bfbf;


        }

        .vertical-menu a {
            background-color: #fdf7f7;
            color: black;
            display: block;
            padding: 12px;
            text-decoration: none;
        }

        .vertical-menu a:hover {
            background-color: #ccc;
        }

        .vertical-menu a.active {
            border-radius: 10px 10px 0 0;
            background-color: #c0b6b6;
            color: white;
        }
    </style>
</head>

<body>
    <?php include('../layout/header.php'); ?>
    <div class="container-fluid" style="margin-bottom:80px;">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <?php include('../layout/menu.php'); ?>
                </div>
                <div class="col-md-9" style="margin-top: 40px;">
                    <div class="content_admin" style="width: 100%;background-color: #fdf7f7;border-radius: 10px;">
                        <div class="tieu_de" style="padding: 20px 0 0 10px;">
                            <h5>TRẢ LỜI LIÊN HỆ</h5>
                            <?php if (isset($_GET['msg'])) { ?>
                                <span style="color:red;">
                                    <?php echo $_GET['msg'] ?></span>
                            <?php } ?>
                        </div>
                        <br>

                        <form action="" method="post">
                            <label for="">Tiêu đề</label>
                            <input type="text" name="tieu_de" class="form-control" placeholder="Tiêu đề">
                            <br>
                            <label for="">Nội dung trả lời</label>
                            <textarea class="form-control" name="noi_dung" id="" placeholder="Nội dung" style="width:100%; height: 200px;"></textarea>
                            <br>
                            <button class="btn btn-danger" type="submit" name="gui">Gửi</button>
                            <a href="index.php" class="btn btn-danger">Quay lại</a>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <?php include('../layout/footer.php'); ?>

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