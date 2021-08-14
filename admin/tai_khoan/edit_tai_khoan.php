<?php
session_start();
require_once('../../dao/pdo.php');
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    if (isset($_POST['edit_tai_khoan'])) {
        $err_ho_ten = $err_mat_khau = $err_nhap_lai_mat_khau = $err_email = $err_ngay_sinh = $error_hinh_anh = "";
        if (empty($_POST['ho_ten'])) {
            $err_ho_ten = "Vui lòng nhập họ tên !";
        } else {
            $ho_ten = $_POST['ho_ten'];
            if (!preg_match("/^[a-zA-Z-'(àáạảãâầấậẩẫăằắặẳẵèéẹẻẽêềếệểễìíịỉĩòóọỏõôồốộổỗơờớợởỡùúụủũưừứựửữỳýỵỷỹđÀÁẠẢÃÂẦẤẬẨẪĂẰẮẶẲẴÈÉẸẺẼÊỀẾỆỂỄÌÍỊỈĨÒÓỌỎÕÔỒỐỘỔỖƠỜỚỢỞỠÙÚỤỦŨƯỪỨỰỬỮỲÝỴỶỸĐD) ]*$/", $ho_ten)) {
                $err_ho_ten = "Vui lòng nhập họ tên chỉ chứa chữ cái và khoảng trắng !";
            }
        }



        if (empty($_POST['email'])) {
            $err_email = "Vui lòng nhập email !";
        } else {
            $email = $_POST['email'];
        }

        if (empty($_POST['ngay_sinh'])) {
            $err_ngay_sinh = "Vui lòng nhập ngày sinh !";
        } else {
            $ngay_sinh = $_POST['ngay_sinh'];
        }

        $gioi_tinh_chu = $_POST['gioi_tinh'];
        $gioi_tinh = (int)$gioi_tinh_chu;
        $vai_tro_chu = $_POST['vai_tro'];
        $vai_tro = (int)$vai_tro_chu;
        $trang_thai_chu = $_POST['trang_thai'];
        $trang_thai = (int)$trang_thai_chu;

        if (($_POST['mat_khau']) != "") {
            $mat_khau = $_POST['mat_khau'];
            $removeWhiteSpacePassword = str_replace(" ", "", $mat_khau);
            if (strlen($mat_khau) < 6 || (strlen($removeWhiteSpacePassword) != strlen($mat_khau))) {
                $err_mat_khau = "Mật khẩu không thỏa mãn điều kiện (ít nhất 6 ký tự và không chứa khoảng trắng)";
            } else {
                if (($_POST['nhap_lai_mat_khau']) == "") {
                    $err_mat_khau = "Bạn chưa nhập xác nhận mật khẩu !";
                }
            }
        }
        if (($_POST['nhap_lai_mat_khau']) != "" && ($_POST['mat_khau']) == "") {
            $err_nhap_lai_mat_khau = "Bạn chưa nhập mật khẩu !";
        }

        if (($_POST['mat_khau']) != "" && ($_POST['nhap_lai_mat_khau']) != "") {
            $nhap_lai_mat_khau = $_POST['nhap_lai_mat_khau'];
            if ($mat_khau != $nhap_lai_mat_khau) {
                $err_nhap_lai_mat_khau = "Xác nhận mật khẩu không chính xác !";
            }
        }

        if (isset($_FILES['hinh_anh']) && $_FILES['hinh_anh']['name']) {
            $img = $_FILES['hinh_anh'];
            $maxSize = 1500000;
            $upload = true;
            $dir = "../../content/images_user/";
            $target_file = $dir . basename($img['name']);
            $type = pathinfo($target_file, PATHINFO_EXTENSION);
            $allowtypes    = array('jpg', 'png', 'jpeg', 'bmp');
            if ($img["size"] > $maxSize) {
                $error_hinh_anh = "File ảnh quá lớn. Vui lòng chọn ảnh khác";
                $upload = false;
            } elseif (!in_array($type, $allowtypes)) {
                $error_hinh_anh = "Chỉ được upload các định dạng JPG, PNG, JPEG, BMP";
                $upload = false;
            } elseif (in_array($type, $allowtypes) && $img["size"] <= 0) {
                $error_hinh_anh = "Vui lòng tải ảnh hợp lệ !";
                $upload = false;
            } elseif (in_array($type, $allowtypes) && $img["size"] > 0) {
                $check = getimagesize($img["tmp_name"]);
                if ($check === false) {
                    $error_hinh_anh = "Vui lòng tải ảnh hợp lệ !";
                    $upload = false;
                } else {
                    $imgname = uniqid() . "-" . $img['name'];
                    if (move_uploaded_file($img['tmp_name'], $dir . $imgname)) {
                    }
                    if (($_POST['mat_khau']) != "" && ($_POST['nhap_lai_mat_khau']) != "") {
                        if ($err_ho_ten . $err_mat_khau . $err_nhap_lai_mat_khau . $err_email . $err_ngay_sinh . $error_hinh_anh == "") {
                            $pass = md5($mat_khau);
                            insert_delete_update("UPDATE users SET ho_ten='$ho_ten', mat_khau='$pass', email = '$email', ngay_sinh = '$ngay_sinh', gioi_tinh = $gioi_tinh, vai_tro = $vai_tro, trang_thai = $trang_thai, hinh_anh = '$imgname' WHERE id = '$id'");
                            header("Location: " . BASE_URL . "admin/tai_khoan/index.php?msg=Cập nhập tài khoản thành công !");
                        }
                    } else {
                        if ($err_ho_ten . $err_mat_khau . $err_nhap_lai_mat_khau . $err_email . $err_ngay_sinh . $error_hinh_anh == "") {
                            $pass = md5($mat_khau);
                            insert_delete_update("UPDATE users SET ho_ten = '$ho_ten', email = '$email', ngay_sinh = '$ngay_sinh', gioi_tinh = $gioi_tinh, vai_tro = $vai_tro, trang_thai = $trang_thai, hinh_anh = '$imgname' WHERE id = '$id'");
                            header("Location: " . BASE_URL . "admin/tai_khoan/index.php?msg=Cập nhập tài khoản thành công !");
                        }
                    }
                }
            }
        } else {
            if (($_POST['mat_khau']) != "" && ($_POST['nhap_lai_mat_khau']) != "") {
                if ($err_ho_ten . $err_mat_khau . $err_nhap_lai_mat_khau . $err_email . $err_ngay_sinh . $error_hinh_anh == "") {
                    $pass = md5($mat_khau);
                    insert_delete_update("UPDATE users SET ho_ten='$ho_ten', mat_khau='$pass', email = '$email', ngay_sinh = '$ngay_sinh', gioi_tinh = $gioi_tinh, vai_tro = $vai_tro, trang_thai = $trang_thai WHERE id = '$id'");
                    header("Location: " . BASE_URL . "admin/tai_khoan/index.php?msg=Cập nhập tài khoản thành công !");
                }
            } else {
                if ($err_ho_ten . $err_mat_khau . $err_nhap_lai_mat_khau . $err_email . $err_ngay_sinh . $error_hinh_anh == "") {
                    $pass = md5($mat_khau);
                    insert_delete_update("UPDATE users SET ho_ten = '$ho_ten', email = '$email', ngay_sinh = '$ngay_sinh', gioi_tinh = $gioi_tinh, vai_tro = $vai_tro, trang_thai = $trang_thai WHERE id = '$id'");
                    header("Location: " . BASE_URL . "admin/tai_khoan/index.php?msg=Cập nhập tài khoản thành công !");
                }
            }
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
            /* height: 150px;
            overflow-y: auto; */
            /* Set a width if you like */
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
    <div class="container-fluid">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <?php include('../layout/menu.php'); ?>
                </div>
                <div class="col-md-9" style="margin-top: 40px;">
                    <div class="content_admin" style="width: 100%;background-color: #fdf7f7;border-radius: 10px;">
                        <div class="tieu_de" style="padding: 20px 0 0 10px;">
                            <h5>CẬP NHẬP TÀI KHOẢN</h5>
                        </div>
                        <br>
                        <form action="" method="post" enctype="multipart/form-data" class="form_user">
                            <div class="row">
                                <div class="col-md-6" style="padding-left: 25px;">
                                    <?php
                                    foreach (selectAll("SELECT * FROM users WHERE id = '$id'") as $item) {
                                    ?>
                                        <div class="form-group">
                                            <label for="">Họ tên *</label>
                                            <input type="text" value="<?= $item['ho_ten'] ?>" class="form-control" name="ho_ten" placeholder="Họ tên của bạn" style="background-color: #fdf7f7;">
                                            <?php if (isset($err_ho_ten)) { ?>
                                                <span style="color:red;">
                                                    <?php echo $err_ho_ten . "<br>" ?></span>
                                            <?php } ?>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Tên đăng nhập *</label>
                                            <input type="text" value="<?= $item['ten_dang_nhap'] ?>" class="form-control" name="ten_dang_nhap" placeholder="Tên đăng nhập của bạn" style="background-color: #fdf7f7;" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Mật khẩu *</label>
                                            <input type="password" class="form-control" name="mat_khau" placeholder="Mật khẩu của bạn" style="background-color: #fdf7f7;">
                                            <?php if (isset($err_mat_khau)) { ?>
                                                <span style="color:red;">
                                                    <?php echo $err_mat_khau . "<br>" ?></span>
                                            <?php } ?>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Nhập lại mật khẩu *</label>
                                            <input type="password" class="form-control" name="nhap_lai_mat_khau" placeholder="Nhập lại mật khẩu của bạn" style="background-color: #fdf7f7;">
                                            <?php if (isset($err_nhap_lai_mat_khau)) { ?>
                                                <span style="color:red;">
                                                    <?php echo $err_nhap_lai_mat_khau . "<br>" ?></span>
                                            <?php } ?>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Email *</label>
                                            <input type="text" value="<?= $item['email'] ?>" class="form-control" name="email" placeholder="Email của bạn" style="background-color: #fdf7f7;">
                                            <?php if (isset($err_email)) { ?>
                                                <span style="color:red;">
                                                    <?php echo $err_email . "<br>" ?></span>
                                            <?php } ?>
                                        </div>

                                </div>

                                <div class="col-md-6" style="padding-right: 25px;">
                                    <div class="form-group">
                                        <label for="">Ngày sinh *</label>
                                        <input type="date" value="<?= $item['ngay_sinh'] ?>" class="form-control" name="ngay_sinh" placeholder="Ngày sinh của bạn" style="background-color: #fdf7f7;">
                                        <?php if (isset($err_ngay_sinh)) { ?>
                                            <span style="color:red;">
                                                <?php echo $err_ngay_sinh . "<br>" ?></span>
                                        <?php } ?>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Giới tính *</label>
                                        <div class="form-control" style="background-color: #fdf7f7;">
                                            <label><input name="gioi_tinh" value="0" type="radio" <?php if ($item['gioi_tinh'] == 0) {
                                                                                                        echo "checked";
                                                                                                    } ?>> Nam</label>&nbsp;&nbsp;
                                            <label><input name="gioi_tinh" value="1" type="radio" <?php if ($item['gioi_tinh'] == 1) {
                                                                                                        echo "checked";
                                                                                                    } ?>> Nữ</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Vai trò *</label>
                                        <div class="form-control" style="background-color: #fdf7f7;">
                                            <label><input name="vai_tro" value="0" type="radio" <?php if ($item['vai_tro'] == 0) {
                                                                                                    echo "checked";
                                                                                                } ?>> Người dùng</label>&nbsp;&nbsp;
                                            <label><input name="vai_tro" value="1" type="radio" <?php if ($item['vai_tro'] == 1) {
                                                                                                    echo "checked";
                                                                                                } ?>> Admin</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Trạng thái *</label>
                                        <div class="form-control" style="background-color: #fdf7f7;">
                                            <label><input name="trang_thai" value="0" type="radio" <?php if ($item['trang_thai'] == 0) {
                                                                                                        echo "checked";
                                                                                                    } ?>> Bình thường</label>&nbsp;&nbsp;
                                            <label><input name="trang_thai" value="1" type="radio" <?php if ($item['trang_thai'] == 1) {
                                                                                                        echo "checked";
                                                                                                    } ?>> Khóa</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Hình ảnh *</label>
                                        <input type="file" class="form-control" name="hinh_anh" style="background-color: #fdf7f7;">
                                        <?php if (isset($error_hinh_anh)) { ?>
                                            <span style="color:red;">
                                                <?php echo $error_hinh_anh . "<br>" ?></span>
                                        <?php } ?>
                                    </div>

                                    <img src="<?= BASE_URL ?>content/images_user/<?= $item['hinh_anh'] ?>" width="30%" alt="">
                                    <br><br>
                                    <button type="submit" class="btn btn-secondary" name="edit_tai_khoan">Cập nhập</button>
                                    <a href="index.php" type="submit" class="btn btn-danger">Quay lại</a>
                                </div>
                            <?php } ?>
                            </div>
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