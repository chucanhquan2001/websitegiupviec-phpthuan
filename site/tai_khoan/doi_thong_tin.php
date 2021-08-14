<?php
require_once "../../dao/pdo.php";
$id = isset($_GET['id']) ? $_GET['id'] : "";
$select = selectAll("select * from users where id='$id'");

if (isset($_POST['doi_thong_tin'])) {
    $ho_ten_err = $email_err  = $ngay_sinh_err  = $hinh_anh_err = "";

    if (empty($_POST['ho_ten'])) {
        $ho_ten_err = "Vui lòng nhập họ tên !";
    } else {
        $ho_ten = $_POST['ho_ten'];
        if (!preg_match("/^[a-zA-Z-'(àáạảãâầấậẩẫăằắặẳẵèéẹẻẽêềếệểễìíịỉĩòóọỏõôồốộổỗơờớợởỡùúụủũưừứựửữỳýỵỷỹđÀÁẠẢÃÂẦẤẬẨẪĂẰẮẶẲẴÈÉẸẺẼÊỀẾỆỂỄÌÍỊỈĨÒÓỌỎÕÔỒỐỘỔỖƠỜỚỢỞỠÙÚỤỦŨƯỪỨỰỬỮỲÝỴỶỸĐD) ]*$/", $ho_ten)) {
            $ho_ten_err = "Vui lòng nhập họ tên chỉ chứa chữ cái và khoảng trắng !";
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
        foreach ($select as $value) {
            $imgname = $value['hinh_anh'];
        }
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


    if ($ho_ten_err . $email_err  . $ngay_sinh_err  . $hinh_anh_err == "") {
        echo insert_delete_update("UPDATE users SET ho_ten='$ho_ten' ,email='$email' , gioi_tinh=$gioi_tinh,ngay_sinh='$ngay_sinh' where id='$id'");
        header("location:index.php?msg=Bạn đã sửa thông tin thành công");
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
            <h3 class="header_title">Đổi thông tin</h3>
        </div>
        <div class="container">
            <form action="" method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-9">
                        <div class="row">
                            <?php foreach ($select as $item) { ?>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">UserName *</label>
                                        <input type="text" name='user' value="<?= $item['ten_dang_nhap'] ?>" disabled class='form-control contentinput'>
                                        <?php if (isset($user_err)) { ?>
                                            <span style="color:red;"><?= $user_err; ?></span>
                                        <?php } ?>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Họ tên *</label>
                                        <input type="text" name='ho_ten' value="<?= $item['ho_ten'] ?>" class='form-control contentinput'>
                                        <?php if (isset($ho_ten_err)) { ?>
                                            <span style="color:red;"><?= $ho_ten_err; ?></span>
                                        <?php } ?>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Email *</label>
                                        <input type="text" name='email' value="<?= $item['email'] ?>" class='form-control contentinput'>
                                        <?php if (isset($email_err)) { ?>
                                            <span style="color:red;"><?= $email_err; ?></span>
                                        <?php } ?>
                                    </div>

                                    <div class="form-group">
                                        <label for="">Ngày sinh *</label>
                                        <input type="date" class="form-control" name="ngay_sinh" value="<?= $item['ngay_sinh'] ?>" style="background-color: #fdf7f7;">
                                        <?php if (isset($ngay_sinh_err)) { ?>
                                            <span style="color:red;">
                                                <?php echo $ngay_sinh_err . "<br>" ?></span>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="col-md-6">

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
                                        <label for="">Hình ảnh *</label>
                                        <img src="<?= BASE_URL . "content/images_user/" . $item['hinh_anh'] ?>" width="200px" alt="Ảnh tài khoản">
                                        <br><br>
                                        <input type="file" class="form-control" name="hinh_anh" style="background-color: #fdf7f7;">
                                        <?php if (isset($hinh_anh_err)) { ?>
                                            <span style="color:red;">
                                                <?php echo $hinh_anh_err . "<br>" ?></span>
                                        <?php } ?>
                                    </div>
                                    <br>
                                    <button type="submit" class="btn btn-success" name="doi_thong_tin">Đổi thông tin</button>
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