<?php
session_start();
require_once '../../dao/pdo.php';
$select = selectAll('select * from setting');
if (isset($_POST['gui'])) {
    $ho_ten_err = $phone_err = $tieu_de_err = $email_err = $noi_dung_err = $phone_err = "";
    if (empty($_POST['ho_ten'])) {
        $ho_ten_err = " Xin mời bạn nhập họ và tên";
    } else {
        $ho_ten = $_POST['ho_ten'];
        if (!preg_match("/^[a-zA-Z-'(àáạảãâầấậẩẫăằắặẳẵèéẹẻẽêềếệểễìíịỉĩòóọỏõôồốộổỗơờớợởỡùúụủũưừứựửữỳýỵỷỹđÀÁẠẢÃÂẦẤẬẨẪĂẰẮẶẲẴÈÉẸẺẼÊỀẾỆỂỄÌÍỊỈĨÒÓỌỎÕÔỒỐỘỔỖƠỜỚỢỞỠÙÚỤỦŨƯỪỨỰỬỮỲÝỴỶỸĐD) ]*$/", $ho_ten)) {
            $ho_ten_err = "Vui lòng nhập họ tên chỉ chứa chữ cái và khoảng trắng !";
        }
    }


    if (empty($_POST['email'])) {
        $email_err  = "Xin mời bạn nhập email";
    } else {
        $email = $_POST['email'];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email_err = "Vui lòng nhập đúng định dạng email";
        }
    }


    if (empty($_POST['phone'])) {
        $phone_err = " Xin mời bạn nhập số điện thoại";
    } else {
        $phone = $_POST['phone'];
        $checkSdt = '/^0+(32|33|34|35|36|37|38|39|56|58|59|70|76|77|78|79|81|82|83|84|85|86|88|89|90|91|92|93|94|96|97|98|99)+[0-9]{7}$/';
        if (!preg_match($checkSdt, $phone)) {
            $phone_err = "Xin mời nhập số điện thoại đúng định dạng";
        }
    }

    if (empty($_POST['noi_dung'])) {
        $noi_dung_err = "Xin mời bạn nhập nội dung ";
    } else {
        $noi_dung = $_POST['noi_dung'];
    }

    if (empty($_POST['tieu_de'])) {
        $tieu_de_err = " Xin mời bạn nhập tiêu đề";
    } else {
        $tieu_de = $_POST['tieu_de'];
    }

    if ($ho_ten_err . $email_err . $phone_err . $noi_dung_err . $tieu_de_err == "") {

        $SENDGRID_API_KEY = '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';
        require './vendor/autoload.php';
        $obj_email = new \SendGrid\Mail\Mail();
        ///------- bạn chỉnh sửa các thông tin dưới đây cho phù hợp với mục đích cá nhân
        // Thông tin người gửi
        $obj_email->setFrom("chucanhquan2001@gmail.com", "Thư từ khách hàng");
        // Tiêu đề thư
        $obj_email->setSubject("$tieu_de");
        // Thông tin người nhận
        $obj_email->addTo('chucquann2011@gmail.com', 'Chúc Anh Quân');
        // Soạn nội dung cho thư
        // $email->addContent("text/plain", "Nội dung text thuần không có thẻ html");
        $obj_email->addContent(
            "text/html",
            "<p>Họ tên : {$ho_ten} </p>
            <p>Email : {$email}</p>
            <p>Số điện thoại : {$phone}</p>
            Nội dung thư : $noi_dung "
        );

        // tiến hành gửi thư
        $sendgrid = new \SendGrid($SENDGRID_API_KEY);
        try {
            $response = $sendgrid->send($obj_email);
            insert_delete_update("insert into lien_he(ho_ten,email,phone,tieu_de,noi_dung) values ('$ho_ten','$email','$phone','$tieu_de','$noi_dung') ");
            header("Location:" . BASE_URL . "site/lien_he/index.php?msg=Thành công");
            //  print $response->statusCode() . "\n";
            //  print_r($response->headers());
            // print $response->body() . "\n";
            die;
            // die;
        } catch (Exception $e) {
            echo 'Caught exception: ' . $e->getMessage() . "\n";
        }
    }
}
if (isset($_GET['msg'])) {
    echo '<script language="javascript">';
    echo 'alert("Gửi Thông Tin Thành Công")';  //not showing an alert box.
    echo '</script>';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include('../../link/style.php') ?>
    <link rel="stylesheet" href="../../content/css.css">
    <link rel="stylesheet" href="../../content/lien_he.css">
    <title>Liên Hệ</title>
</head>

<body>
    <?php include('../../layout/header.php'); ?>
    <div class="container-fluid hang1">
        <div class="row " style="margin-top:20px;margin-bottom:20px;">
            <h3 class="header_title">Liên Hệ</h3>
        </div>
    </div>
    <div class="container-fluid">
        <div class="container">
            <div class="row hang2">
                <?php foreach ($select as $item) { ?>
                    <div class="col-md-3">
                        <div class="hop">
                            <i class="icon fas fa-map-marker-alt" style="font-size:40px;"></i>
                            <p class="hang2_title"><strong>Address : </strong><?= $item['dia_chi'] ?></p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <i class="icon fas fa-phone" style="font-size:40px;"></i>
                        <p class="hang2_title_phone"><strong>Phone : </strong><?= $item['phone'] ?></p>
                    </div>
                    <div class="col-md-3">
                        <i class="icon fas fa-envelope" style="font-size:40px;"></i>
                        <p class="hang2_title" style="margin-left: -40px;"><strong>Email : </strong><?= $item['email'] ?></p>
                    </div>
                    <div class="col-md-3">
                        <i class="icon fas fa-globe" style="font-size:40px;"></i>
                        <p class="hang2_title"><strong>Tên công ty : </strong><?= $item['ten_cong_ty'] ?></p>
                    </div>
                <?php } ?>
            </div>
            <div class="row hang3">
                <div class="col-md-6">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d14873.590928718984!2d105.35532072521845!3d21.255718272903316!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31348c8966c3d8ab%3A0x8d711c9bdb5a62c3!2zUGhvbmcgVsOibiwgQmEgVsOsLCBIw6AgTuG7mWksIFZp4buHdCBOYW0!5e0!3m2!1svi!2s!4v1606490336610!5m2!1svi!2s" width="100%" height="450px" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
                </div>
                <div class="col-md-6">
                    <h4 class="contentform">Liên Hệ</h4>
                    <br>
                    <form action="" method="post">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class='tentitle' for="">Họ tên *</label>
                                    <input type="text" name='ho_ten' placeholder="Họ và tên" class='form-control contentinput'>
                                    <?php if (isset($ho_ten_err)) { ?>
                                        <span style="color:red;"><?= $ho_ten_err; ?></span>
                                    <?php } ?>
                                </div>
                                <br>
                                <div class="form-group">
                                    <label class='tentitle' for="">Email *</label>
                                    <input type="text" name='email' placeholder="Email" class='form-control contentinput'>
                                    <?php if (isset($email_err)) { ?>
                                        <span style="color:red;"><?= $email_err; ?></span>
                                    <?php } ?>
                                </div>
                                <br>
                                <div class="form-group">
                                    <label class='tentitle' for="">Số điện thoại *</label>
                                    <input type="text" name='phone' placeholder="Số điện thoại" class='form-control contentinput'>
                                    <?php if (isset($phone_err)) { ?>
                                        <span style="color:red;"><?= $phone_err; ?></span>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class='tentitle' for="">Tiêu đề *</label>
                                    <input type="text" name='tieu_de' placeholder="Tiêu đề" class='form-control contentinput'>
                                    <?php if (isset($tieu_de_err)) { ?>
                                        <span style="color:red;"><?= $tieu_de_err; ?></span>
                                    <?php } ?>
                                </div>
                                <br>
                                <div class="form-group">
                                    <label class='tentitle' for="">Nội dung *</label>
                                    <input type="text" name='noi_dung' placeholder="Nội dung" class='form-control contentinput'>
                                    <?php if (isset($noi_dung_err)) { ?>
                                        <span style="color:red;"><?= $noi_dung_err; ?></span>
                                    <?php } ?>
                                </div>
                                <br>
                                <br>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success" name="gui">Gửi tin nhắn</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row hang4">
                <div class="col-xl-6">
                    <h3></h3>
                </div>
                <div class="col-xl-6">

                </div>
            </div>
        </div>
    </div>

    <?php include('../../layout/footer.php'); ?>
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