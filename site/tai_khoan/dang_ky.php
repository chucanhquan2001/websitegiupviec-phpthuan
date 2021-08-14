<?php
session_start();
require_once "../../dao/pdo.php";
if (isset($_POST['dang_ky'])) {
    $ho_ten_err = $email_err = $mat_khau_err = $password_old_err = $ngay_sinh_err = $user_err = $hinh_anh_err = "";

    if (empty($_POST['ho_ten'])) {
        $ho_ten_err = "Vui lòng nhập họ tên !";
    } else {
        $ho_ten = $_POST['ho_ten'];
        if (!preg_match("/^[a-zA-Z-'(àáạảãâầấậẩẫăằắặẳẵèéẹẻẽêềếệểễìíịỉĩòóọỏõôồốộổỗơờớợởỡùúụủũưừứựửữỳýỵỷỹđÀÁẠẢÃÂẦẤẬẨẪĂẰẮẶẲẴÈÉẸẺẼÊỀẾỆỂỄÌÍỊỈĨÒÓỌỎÕÔỒỐỘỔỖƠỜỚỢỞỠÙÚỤỦŨƯỪỨỰỬỮỲÝỴỶỸĐD) ]*$/", $ho_ten)) {
            $ho_ten_err = "Vui lòng nhập họ tên chỉ chứa chữ cái và khoảng trắng !";
        }
    }
    if (empty($_POST['user'])) {
        $user_err = "Vui lòng nhập tên đăng nhập !";
    } else {
        $ten_dang_nhap = $_POST['user'];
        $check_id = "SELECT * FROM users WHERE ten_dang_nhap = '$ten_dang_nhap'";
        $conn = connect();
        $cout_id = $conn->prepare($check_id);
        $cout_id->execute();
        if ($cout_id->rowCount() > 0) {
            $user_err = "Tên đăng nhập này đã có người sử dụng. Vui lòng chọn tên khác ! ";
        } else {
            if (!preg_match("/^[a-zA-Z0-9_]{5,30}$/", $ten_dang_nhap)) {
                $user_err = "Vui lòng nhập tên đăng chỉ bao gồm các ký tự a-z A-Z 0-9 và gạch dưới, tối thiểu 5 ký tự, tối đa 30 ký tự !";
            }
        }
    }
    if (empty($_POST['password'])) {
        $mat_khau_err = "Vui lòng nhập mật khẩu !";
    } else {
        $password = $_POST['password'];
        $removeWhiteSpacePassword = str_replace(" ", "", $password);
        if (strlen($password) < 6 || (strlen($removeWhiteSpacePassword) != strlen($password))) {
            $mat_khau_err = "Mật khẩu không thỏa mãn điều kiện (ít nhất 6 ký tự và không chứa khoảng trắng)";
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
    if (empty($_POST['ngay_sinh'])) {
        $ngay_sinh_err = "Vui lòng nhập ngày sinh !";
    } else {
        $ngay_sinh = $_POST['ngay_sinh'];
    }
    $gioi_tinh = $_POST['gioi_tinh'];

    if (!file_exists($_FILES['hinh_anh']["tmp_name"])) {
        $hinh_anh_err = "Vui lòng tải ảnh !";
    } else {
        $img = $_FILES['hinh_anh'];
        $maxSize = 1500000;
        $upload = true;
        $dir = "../../content/images_user/";
        $target_file = $dir . basename($img['name']);
        $type = pathinfo($target_file, PATHINFO_EXTENSION);
        $allowtypes    = array('jpg', 'png', 'jpeg', 'bmp');
        if ($img["size"] > $maxSize) {
            $hinh_anh_err = "File ảnh quá lớn. Vui lòng chọn ảnh khác";
            $upload = false;
        } elseif (!in_array($type, $allowtypes)) {
            $hinh_anh_err = "Chỉ được upload các định dạng JPG, PNG, JPEG, BMP";
            $upload = false;
        } elseif (in_array($type, $allowtypes) && $img["size"] <= 0) {
            $hinh_anh_err = "Vui lòng tải ảnh hợp lệ !";
            $upload = false;
        } elseif (in_array($type, $allowtypes) && $img["size"] > 0) {
            $check = getimagesize($img["tmp_name"]);
            if ($check === false) {
                $hinh_anh_err = "Vui lòng tải ảnh hợp lệ !";
                $upload = false;
            } else {
                $imgname = uniqid() . "-" . $img['name'];
                if (move_uploaded_file($img['tmp_name'], $dir . $imgname)) {
                }
            }
        }
    }
    if (empty($_POST['password_old'])) {
        $password_old_err = "Vui lòng nhập mật khẩu !";
    } else {
        $nhap_lai_mat_khau = $_POST['password_old'];
    }
    if (isset($password) && isset($nhap_lai_mat_khau)) {
        if ($password != $nhap_lai_mat_khau) {
            $password_old_err = "Xác nhận mật khẩu không chính xác !";
        }
    }
    // echo "ho_ten".$ho_ten."<br>";
    // echo "ten_dang_nhap".$ten_dang_nhap."<br>";
    // echo "password".$password."<br>";
    // echo "email".$email."<br>";
    // echo "ngay_sinh".$ngay_sinh."<br>";
    // echo "gioi_tinh".$gioi_tinh."<br>";
    // echo "img".$img['name']."<br>";
    // echo "nhap_lai_mat_khau".$nhap_lai_mat_khau."<br>";

    if ($ho_ten_err . $email_err . $mat_khau_err . $password_old_err . $ngay_sinh_err . $user_err . $hinh_anh_err == "") {
        $pass = md5($password);
        insert_delete_update("INSERT INTO `users`(`ho_ten`, `ten_dang_nhap`, `mat_khau`, `email`, `ngay_sinh`, `gioi_tinh`, `hinh_anh`, `vai_tro`, `trang_thai`)
         VALUES ('$ho_ten','$ten_dang_nhap','$pass','$email','$ngay_sinh',$gioi_tinh,'$imgname',0, 0)");
        header("Location: " . BASE_URL . "index.php?msg=Thêm tài khoản thành công !");
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký tài khoản</title>
    <?php include('../../link/style.php') ?>
    <link rel="stylesheet" href="../../content/css.css">
    <link rel="stylesheet" href="../../content/lien_he.css">
</head>

<body>
    <?php include('../../layout/header.php') ?>
    <div class="container-fluid">
        <div class="row" style="margin-top:20px;margin-bottom:20px;">
            <h3 class="header_title">Đăng Ký</h3>
        </div>
        <div class="container">
            <form action="" method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Họ tên *</label>
                                    <input type="text" name='ho_ten' placeholder="Họ và tên" class='form-control contentinput'>
                                    <?php if (isset($ho_ten_err)) { ?>
                                        <span style="color:red;"><?= $ho_ten_err; ?></span>
                                    <?php } ?>
                                </div>
                                <div class="form-group">
                                    <label for="">Email *</label>
                                    <input type="text" name='email' placeholder="Email" class='form-control contentinput'>
                                    <?php if (isset($email_err)) { ?>
                                        <span style="color:red;"><?= $email_err; ?></span>
                                    <?php } ?>
                                </div>
                                <div class="form-group">
                                    <label for="">Mật Khẩu *</label>
                                    <input type="password" name='password' placeholder="Mật khẩu" class='form-control contentinput'>
                                    <?php if (isset($mat_khau_err)) { ?>
                                        <span style="color:red;"><?= $mat_khau_err; ?></span>
                                    <?php } ?>
                                </div>
                                <div class="form-group">
                                    <label for="">Nhập Lại Mật Khẩu *</label>
                                    <input type="password" name='password_old' placeholder="Nhâp lại mật khẩu" class='form-control contentinput'>
                                    <?php if (isset($password_old_err)) { ?>
                                        <span style="color:red;"><?= $password_old_err; ?></span>
                                    <?php } ?>
                                </div>
                                <div class="form-group">
                                    <label for="">Ngày sinh *</label>
                                    <input type="date" class="form-control" name="ngay_sinh" placeholder="Ngày sinh của bạn" style="background-color: #fdf7f7;">
                                    <?php if (isset($ngay_sinh_err)) { ?>
                                        <span style="color:red;">
                                            <?php echo $ngay_sinh_err . "<br>" ?></span>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">UserName *</label>
                                    <input type="text" name='user' placeholder="Username" class='form-control contentinput'>
                                    <?php if (isset($user_err)) { ?>
                                        <span style="color:red;"><?= $user_err; ?></span>
                                    <?php } ?>
                                </div>
                                <div class="form-group">
                                    <label for="">Giới tính *</label>
                                    <div class="form-control" style="background-color: #fdf7f7;">
                                        <label><input name="gioi_tinh" value="0" type="radio" checked> Nam</label>&nbsp;&nbsp;
                                        <label><input name="gioi_tinh" value="1" type="radio"> Nữ</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="">Hình ảnh *</label>
                                    <input type="file" class="form-control" name="hinh_anh" style="background-color: #fdf7f7;">
                                    <?php if (isset($hinh_anh_err)) { ?>
                                        <span style="color:red;">
                                            <?php echo $hinh_anh_err . "<br>" ?></span>
                                    <?php } ?>
                                </div>
                                <br>
                                <button type="submit" class="btn btn-success" name="dang_ky">Đăng Ký</button>
                                <a href="<?= BASE_URL ?>index.php" type="submit" class="btn btn-danger">Quay lại</a>
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