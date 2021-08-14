<?php
session_start();
require_once('../../dao/pdo.php');
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    if (isset($_POST['edit_slide'])) {
        $err_ten_anh = $error_hinh_anh = "";
        if (isset($_SESSION['admin'])) {
            $id_user = $_SESSION['admin']['id'];
        }
        if (empty($_POST['ten_anh'])) {
            $err_ten_anh = "Vui lòng nhập tên ảnh !";
        } else {
            $ten_anh = $_POST['ten_anh'];
        }

        if (isset($_FILES['hinh_anh']) && $_FILES['hinh_anh']['name']) {
            $img = $_FILES['hinh_anh'];
            $maxSize = 1500000;
            $upload = true;
            $dir = "../../content/images_slide/";
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
                    if ($err_ten_anh . $error_hinh_anh == "") {
                        insert_delete_update("UPDATE slide SET ten = '$ten_anh', hinh_anh = '$imgname', id_user = '$id_user' WHERE id = $id");
                        header("Location:index.php?msg=Cập nhập slide thành công !");
                    }
                }
            }
        } else {
            if ($err_ten_anh . $error_hinh_anh == "") {
                insert_delete_update("UPDATE slide SET ten = '$ten_anh', id_user = '$id_user' WHERE id = $id");
                header("Location:index.php?msg=Cập nhập slide thành công !");
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
                            <h5>THÊM MỚI SLIDE</h5>
                        </div>
                        <br>
                        <form action="" method="post" enctype="multipart/form-data" class="form_user">
                            <div class="row">
                                <div class="col-md-12" style="padding:0 25px 0 25px;">
                                    <?php
                                    foreach (selectAll("SELECT * FROM slide WHERE id = '$id'") as $item) {
                                    ?>
                                        <div class="form-group">
                                            <label for="">Tên ảnh *</label>
                                            <input type="text" value="<?= $item['ten'] ?>" class="form-control" name="ten_anh" placeholder="Tên ảnh" style="background-color: #fdf7f7;">
                                            <?php if (isset($err_ten_anh)) { ?>
                                                <span style="color:red;">
                                                    <?php echo $err_ten_anh . "<br>" ?></span>
                                            <?php } ?>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Hình ảnh *</label>
                                            <input type="file" class="form-control" name="hinh_anh" placeholder="Hình ảnh" style="background-color: #fdf7f7;">
                                            <?php if (isset($error_hinh_anh)) { ?>
                                                <span style="color:red;">
                                                    <?php echo $error_hinh_anh . "<br>" ?></span>
                                            <?php } ?>
                                        </div>
<img src="<?= BASE_URL ?>content/images_slide/<?= $item['hinh_anh'] ?>" width="50%" alt="">
                                        <br><br>
                                        <button type="submit" class="btn btn-secondary" name="edit_slide">Cập nhập</button>
                                        <a href="index.php" type="submit" class="btn btn-danger">Quay lại</a>
                                    <?php } ?>
                                </div>
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