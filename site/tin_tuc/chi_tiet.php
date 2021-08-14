<?php
session_start();
require_once "../../dao/pdo.php";

$id = null;
$id_tin_tuc = null;
$so_luot_xem = 1;
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM tin_tuc WHERE id = '$id'";
    $connect = connect();
    $stmt = $connect->prepare($sql);
    $stmt->execute();
    $tin_tuc = $stmt->fetch();
    foreach (selectAll("SELECT * FROM tin_tuc WHERE id = '$id'") as $row) {
        $id_tin_tuc  = $row['id'];
        $so_luot_xem += $row['luot_xem'];
        insert_delete_update("UPDATE tin_tuc SET luot_xem = '$so_luot_xem' WHERE id = '$id'");
    }
} else {
    header("location:" . BASE_URL);
}
if (isset($_GET['id_comment'])) {
    $id_cmt = $_GET['id_comment'];
    $check_id = "SELECT * FROM binh_luan WHERE id = '$id_cmt'";
    $conn = connect();
    $cout_id = $conn->prepare($check_id);
    $cout_id->execute();
    if ($cout_id->rowCount() > 0) {
        insert_delete_update("DELETE FROM binh_luan WHERE id = '$id_cmt'");
    } else {
        header("location:" . BASE_URL);
    }
}
if (isset($_SESSION['user']) && isset($_POST['comment'])) {
    $date = date("Y/m/d");
    $ten_dang_nhap = $_SESSION['user']['ten_dang_nhap'];
    $content = $_POST['comment_tintuc'];
    foreach (selectAll("SELECT * FROM users WHERE ten_dang_nhap = '$ten_dang_nhap'") as $row) {
        $id_user = $row['id'];
    }
    if (($_POST['comment_tintuc']) == "") {
        echo "<script>alert('Vui lòng nhập bình luận !')</script>";
        // header("Location:" . $_SERVER['REQUEST_URI']);
    } else {
        insert_delete_update("INSERT INTO binh_luan (noi_dung,ngay_bl,id_user,id_tin_tuc) VALUES ('$content','$date','$id_user','$id_tin_tuc')");
        header("Location:" . $_SERVER['REQUEST_URI']);
    }
} elseif (isset($_SESSION['admin']) && isset($_POST['comment'])) {
    $date = date("Y/m/d");
    $ten_dang_nhap = $_SESSION['admin']['ten_dang_nhap'];
    $content = $_POST['comment_tintuc'];
    foreach (selectAll("SELECT * FROM users WHERE ten_dang_nhap = '$ten_dang_nhap'") as $row) {
        $id_user = $row['id'];
    }
    if (($_POST['comment_tintuc']) == "") {
        echo "<script>alert('Vui lòng nhập bình luận !');</script>";
        // header("Location:" . $_SERVER['REQUEST_URI']);
    } else {
        insert_delete_update("INSERT INTO binh_luan (noi_dung,ngay_bl,id_user,id_tin_tuc) VALUES ('$content','$date','$id_user','$id_tin_tuc')");
        header("Location:" . $_SERVER['REQUEST_URI']);
    }
} elseif (!isset($_SESSION['user']) && !isset($_SESSION['admin']) && isset($_POST['comment'])) {
    header("location:" . BASE_URL . "site/tai_khoan/login?msg=Vui lòng đăng nhập để bình luận !");
}



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết tin tức</title>
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
    <div class="container-fluid" style="background-color: #64cc7b;">
        <div class="container">
            <div class="row" style="margin-top:20px;margin-bottom:20px;">
                <h3 class="header_title">Bài Viết Của Bạn</h3>
            </div>
        </div>
    </div>
    <br>
    <div class="container-fluid">
        <div class="container">
            <div class="row">
                <div class="col-md-9">
                    <h3 style="color: #147f3b"><?= $tin_tuc['tieu_de'] ?></h3>
                    <br>
                    <p style="color: #60656180; font-size: 12px;"><i class="fas fa-stopwatch"></i> <?= $tin_tuc['thoi_gian'] ?></p>
                    <h2 style="font-size: 20px; color: #147f3b"><?= $tin_tuc['tom_tat'] ?>
                    </h2>
                    <p style="color: #626262;">
                        <?= $tin_tuc['noi_dung'] ?>
                    </p>
                </div>
                <div class="col-md-3">
                    <?php include_once('../../layout/content_right.php') ?>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="container">

            <div class="row" style="margin-top:30px">
                <div class="col-md-12">
                    <h4>Bình luận</h4>
                    <hr>
                    <?php if (isset($_SESSION['user']) || isset($_SESSION['admin'])) { ?>
                        <div class="row">
                            <div class="col-md-1">
                                <?php if (isset($_SESSION['admin'])) { ?>
                                    <img src="<?= BASE_URL ?>content/images_user/<?= $_SESSION['admin']['hinh_anh'] ?>" alt="" height="50px" ; width="50px" ;>
                                <?php } ?>
                                <?php if (isset($_SESSION['user'])) { ?>
                                    <img src="<?= BASE_URL ?>content/images_user/<?= $_SESSION['user']['hinh_anh'] ?>" alt="" height="50px" ; width="50px" ;>
                                <?php } ?>
                            </div>
                            <div class="col-md-11">
                                <form action="" method="post">
                                    <textarea name="comment_tintuc" id="inputcommentPro" placeholder="Thêm bình luận ..." cols="135" style="border: 1px solid #D3D6DB;border-radius: 4px;"></textarea> <br>
                                    <button type="submit" name="comment" class="btn btn-outline-dark" style="border: 1px solid #D3D6DB;">Bình luận</button>
                                </form>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <?php foreach (selectAll("SELECT * FROM binh_luan WHERE id_tin_tuc = '$id' ORDER BY id DESC ") as $row) {
                $id_user = $row['id_user'];
                foreach (selectAll("SELECT * FROM users WHERE id= '$id_user'") as $tow) { ?>
                    <div class="row" style="margin-top: 30px">
                        <div class="col-md-1">
                            <img src="<?= BASE_URL ?>content/images_user/<?= $tow['hinh_anh'] ?>" alt="" height="50px" ; width="50px">
                        </div>
                        <div class="col-md-11">
                            <a href="#">
                                <p style="font-weight: 600;color: #288ad6;"><?= $tow['ho_ten'] ?>
                                </p>
                            </a>
                            <p><?= $row['noi_dung'] ?></p>
                            <a href="#" style="font-size: 12px;color: #4267b2;"><span>Thích</span></a>
                            <span style="font-size: 10px;"> . </span>
                            <a href="#" style="font-size: 12px;color: #4267b2;"><span>Phản hồi</span></a>
                            <span style="font-size: 10px;"> . </span>
                            <?php
                            if (isset($_SESSION['user'])) {
                                if ($tow['ten_dang_nhap'] == $_SESSION['user']['ten_dang_nhap']) { ?>
                                    <a href="chi_tiet.php?id=<?= $id ?>&id_comment=<?= $row['id'] ?>" style="font-size: 12px;color: #4267b2;" onclick="return confirm('Bạn muốn xóa bình luận thật chứ?')">Xóa</a>
                                    <span style='font-size: 10px;'> . </span>
                                <?php

                                }
                            } else if (isset($_SESSION['admin']))
                                if ($tow['ten_dang_nhap'] == $_SESSION['admin']['ten_dang_nhap']) { ?>
                                <a href="chi_tiet.php?id=<?= $id ?>&id_comment=<?= $row['id'] ?>" style="font-size: 12px;color: #4267b2;" onclick="return confirm('Bạn muốn xóa bình luận thật chứ?')">Xóa</a>
                                <span style='font-size: 10px;'> . </span>

                            <?php

                                }
                            ?>

                            <span style="color: #90949c; font-size: 11px"><?= $row['ngay_bl'] ?></span>

                        </div>
                    </div>
            <?php }
            } ?>
        </div>
    </div>
    <div class="container-fluid">
        <div class="container">
            <div class="row" style="margin-top: 70px">
                <div class="col-md-12">
                    <h4 style="color: red">CÓ THỂ BẠN QUAN TÂM</h4>
                    <hr>
                    <?php foreach (selectAll("SELECT * FROM tin_tuc ORDER BY id DESC LIMIT 3") as $item) { ?>
                        <?php foreach (selectAll("SELECT * FROM users WHERE id = " . $item['id_user']) as $value) {
                            $tong = dem("SELECT COUNT(*) FROM binh_luan WHERE id_tin_tuc = " . $item['id']); ?>
                            <div class="row" style="color: #626262;">
                                <a href="<?= BASE_URL ?>site/tin_tuc/chi_tiet.php?id=<?= $item["id"] ?>" style="color: #626262;">
                                    <p style="font-size: 18px;margin-left: 15px;"><?= $item["tieu_de"] ?></p>
                                </a>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <a href="<?= BASE_URL ?>site/tin_tuc/chi_tiet.php?id=<?= $item["id"] ?>"><img src="<?= BASE_URL ?>content/images_tin_tuc/<?= $item["hinh_anh"] ?>" alt="" width="100%" height="200px" style="border: 1px solid #9e7171;padding: 3px;"></a>
                                </div>
                                <div class="col-md-9">
                                    <p style="font-size: 12px;color: gray;"><i class="fas fa-stopwatch"></i> &nbsp;<?= $item["thoi_gian"] ?></p>
                                    <a href="<?= BASE_URL ?>site/tin_tuc/chi_tiet.php?id=<?= $item["id"] ?>" style="color: #626262;">
                                        <p><?= $item["tom_tat"] ?>...</p>
                                    </a>
                                    <p style="font-size: 12px;color: gray;"> <i class="fas fa-eye"></i> &nbsp;<?= $item["luot_xem"] ?> &nbsp; &nbsp;<i class="fas fa-comment"></i> <?= $tong ?> &nbsp; &nbsp; <i class="fas fa-user"></i> &nbsp;<?= $value["ho_ten"] ?> </p>
                                </div>
                            </div>
                            <br><br>
                            <hr>
                    <?php }
                    } ?>
                </div>
            </div>
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