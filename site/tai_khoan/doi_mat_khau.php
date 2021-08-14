<?php
require_once "../../dao/pdo.php";
session_start();
$id = isset($_GET['id']) ? $_GET['id'] : "";
$select = selectAll("select * from users where id='$id'");

if (isset($_POST['doi_mat_khau'])) {
    $mat_khau_err  = $mat_khau_new_err = $mat_khau_old_err = "";
    if (empty($_POST['mat_khau'])) {
        $mat_khau_err = "Xin mời bạn nhập mật khẩu";
    } else {
        $mat_khau = md5($_POST['mat_khau']);
        $removeWhiteSpacePassword = str_replace(" ", "", $mat_khau);
        foreach ($select as $value) {
            if ($mat_khau != $value['mat_khau']) {
                $mat_khau_err = "Sai mật khẩu ! Xin mời bạn nhập lại mật khẩu";
            }
        }
    }

    if (empty($_POST['mat_khau_new'])) {
        $mat_khau_new_err = "Xin mời bạn nhập mật khẩu";
    } else {
        $mat_khau_new = $_POST['mat_khau_new'];
        $removeWhiteSpacePassword = str_replace(" ", "", $mat_khau_new);
        if (strlen($mat_khau_new) < 6 || (strlen($removeWhiteSpacePassword) != strlen($mat_khau_new))) {
            $mat_khau_new_err = "Mật khẩu không thỏa mãn điều kiện (ít nhất 6 ký tự và không chứa khoảng trắng)";
        }
    }
    if (empty($_POST['mat_khau_old'])) {
        $mat_khau_old_err = "Xin mời bạn nhập mật khẩu";
    } else {
        $mat_khau_old = $_POST['mat_khau_old'];
        if ($mat_khau_new != $mat_khau_old) {
            $mat_khau_old_err = "Sai mật khẩu ! Xin mời nhập lại";
        }
    }
    if ($mat_khau_err . $mat_khau_new_err  . $mat_khau_old_err   == "") {
        $pass = md5($mat_khau_old);
        echo insert_delete_update("UPDATE users SET mat_khau='$pass' where id='$id'");
        unset($_SESSION['user']);
        unset($_SESSION['admin']);
        header("location:" . BASE_URL . "site/tai_khoan/login.php?msg=Bạn đổi thông tin thành công ! Xin mời đăng nhập");
        die;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đổi thông tin</title>
    <?php include('../../link/style.php') ?>
    <link rel="stylesheet" href="../../content/css.css">
    <link rel="stylesheet" href="../../content/lien_he.css">
</head>

<body>
    <?php include('../../layout/header.php'); ?>
    <div class="container-fluid">
        <div class="row " style="margin-top:20px;margin-bottom:20px;">
            <h3 class="header_title">Đổi mật khẩu</h3>
        </div>
        <div class="container">
            <form action="" method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-9">
                        <div class="row">
                            <?php foreach ($select as $item) { ?>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">User Name *</label>
                                        <input type="text" class="form-control" value="<?= $item['ten_dang_nhap'] ?>" style="background-color: #fdf7f7;" disabled>
                                        <?php if (isset($hinh_anh_err)) { ?>
                                            <span style="color:red;">
                                                <?php echo $hinh_anh_err . "<br>" ?></span>
                                        <?php } ?>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Mật khẩu cũ *</label>
                                        <input type="password" class="form-control" name="mat_khau" style="background-color: #fdf7f7;">
                                        <?php if (isset($mat_khau_err)) { ?>
                                            <span style="color:red;">
                                                <?php echo $mat_khau_err . "<br>" ?></span>
                                        <?php } ?>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Mật mới *</label>
                                        <input type="password" class="form-control" name="mat_khau_new" placeholder="Nhập mật khẩu mới" style="background-color: #fdf7f7;">
                                        <?php if (isset($mat_khau_new_err)) { ?>
                                            <span style="color:red;">
                                                <?php echo $mat_khau_new_err . "<br>" ?></span>
                                        <?php } ?>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Nhập lại mật khẩu *</label>
                                        <input type="password" class="form-control" name="mat_khau_old" placeholder="Nhập lại mật khẩu" style="background-color: #fdf7f7;">
                                        <?php if (isset($mat_khau_old_err)) { ?>
                                            <span style="color:red;">
                                                <?php echo $mat_khau_old_err . "<br>" ?></span>
                                        <?php } ?>
                                    </div>
                                    <br>
                                    <button type="submit" class="btn btn-success" name="doi_mat_khau">Đổi mật khẩu</button>
                                    <a href="index.php" type="submit" class="btn btn-danger">Quay lại</a>
                                </div>
                            <?php } ?>
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