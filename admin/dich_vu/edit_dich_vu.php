<?php
session_start();
require_once('../../dao/pdo.php');
$id = isset($_GET['id']) ? $_GET['id'] : "";
$select = (selectall("select * from dich_vu where id = $id"));

if (isset($_POST['them'])) {
    $ten_dich_vu = $mo_ta = $hinh_anh = "";
    $ten_dich_vu_Err = $mo_ta_Err = $hinh_anh_Err = "";
    if (empty($_POST['ten_dich_vu'])) {
        $ten_dich_vu_Err = "Xin mời bạn nhập tên dịch vụ";
    } else {
        $ten_dich_vu = $_POST['ten_dich_vu'];
    }

    if (empty($_POST['mo_ta'])) {
        $mo_ta_Err = "Xin mời bạn nhập mô tả";
    } else {
        $mo_ta = $_POST['mo_ta'];
    }

    $logo = $_FILES['hinh_anh'];
    $sizeanh = 1500000;
    $path = "";
    if ($_FILES['hinh_anh']['size'] <= 0) {
        foreach ($select as $value) {
            $path = $value['hinh_anh'];
        }
    } elseif (getimagesize($logo['tmp_name']) == false) {
        $hinh_anh_Err = "xin mời bạn nhập file ảnh";
    } elseif ($_FILES['hinh_anh']['size'] >= $sizeanh) {
        $hinh_anh_Err = "xin mời bạn nhập ảnh size nhỏ hơn 1.5mb";
    } else {
        $dir = "../../content/images_dich_vu/";
        $target_file = $dir . basename($logo['name']);
        $filename = "";


        $typeanh = ['jpg', 'png', 'bmp'];
        $kieu = pathinfo($target_file, PATHINFO_EXTENSION);
        if (!in_array($kieu, $typeanh)) {
            $hinh_anh_Err = "Chỉ được upload các định dạng JPG, PNG, JPEG";
        } elseif ($logo['size'] > 0 || $logo['size'] < $sizeanh) {
            $filename = uniqid() . "_" . $logo['name'];
            move_uploaded_file($logo['tmp_name'], "../../content/images_dich_vu/" . $filename);
            $path = "content/images_dich_vu/" . $filename;
        } else {
            $hinh_anh_Err = "";
        }
    }



    if ($ten_dich_vu_Err . $mo_ta_Err . $hinh_anh_Err == "") {
        insert_delete_update("update dich_vu set ten_dich_vu ='$ten_dich_vu' , mo_ta = '$mo_ta' , hinh_anh ='$path' where id = $id");
        header("Location:" . BASE_URL . "admin/dich_vu/index.php?msg=Cập nhập dịch vụ thành công");
        die;
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
    <link rel="stylesheet" href="../content/css.css">
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
                <div class="col-md-9" style="margin-top: 40px;padding-left:10px;">
                    <div class="content_admin" style="width: 100%;background-color: #fdf7f7;border-radius: 10px;padding-left:10px;padding-bottom:20px;">
                        <div class="tieu_de" style="padding: 20px 0 0 10px;">
                            <h5>Cập nhập dịch vụ</h5>
                        </div>
                        <br>
                        <?php foreach (selectall("select * from dich_vu where id=$id") as $item) { ?>
                            <form action="<?= BASE_URL . "admin/dich_vu/edit_dich_vu.php?id=" . $item['id'] ?>" method="POST" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="">Tên dịch vụ *</label>
                                    <input type="text" name="ten_dich_vu" class="form-control" value="<?php echo $item['ten_dich_vu'] ?>">
                                    <?php if (isset($ten_dich_vu_Err)) { ?>
                                        <span style="color:red"><?php echo $ten_dich_vu_Err; ?></span>
                                    <?php } ?>
                                </div>
                                <div class="form-group">
                                    <label for="">Mô tả *</label>
                                    <textarea class="form-control" name="mo_ta" rows="10"><?php echo $item['mo_ta'] ?></textarea>
                                    <?php if (isset($mo_ta_Err)) { ?>
                                        <span style="color:red"><?php echo $mo_ta_Err; ?></span>
                                    <?php } ?>
                                </div>
                                <div class="form-group">
                                    <label for="">Ảnh minh họa dịch vụ *</label>
                                    <img src="<?= BASE_URL . $item['hinh_anh'] ?>" width="150px" alt="">
                                    <input type="file" name="hinh_anh" class="form-control">
                                    <?php if (isset($hinh_anh_Err)) { ?>
                                        <span style="color:red"><?php echo $hinh_anh_Err; ?></span>
                                    <?php } ?>
                                </div>
                                <div class="form-group">
                                    <input type="submit" value="Cập nhập" name="them" class="btn btn-secondary">
                                    <a href="<?= BASE_URL ?>admin/web_setting/index.php" class="btn btn-danger" style="font-size: 12px">Quay Lại</a>
                                </div>
                            </form>
                        <?php } ?>
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