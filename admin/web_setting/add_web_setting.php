<?php
session_start();
require_once '../../dao/pdo.php';

if (isset($_POST['them'])) {
    $ten_cong_ty_Err = $phone_Err = $dia_chi_Err = $phone_hotline_Err = $youtobe_url_Err = $instagram_url_Err = $facebook_url_Err = $zalo_url_Err = $logo_Err = $email_Err = "";
    $ten_cong_ty = $phone = $dia_chi = $phone_hotline = $youtobe_url = $instagram_url = $facebook_url = $zalo_url = $email = "";
    if (empty($_POST['ten_cong_ty'])) {
        $ten_cong_ty_Err = "Xin mời nhập tên công ty";
    } else {
        $ten_cong_ty = $_POST['ten_cong_ty'];
    }

    if (empty($_POST['phone'])) {
        $phone_Err = "Xin mời nhập số điện thoại";
    } else {
        $phone = $_POST['phone'];
        $checkSdt = '/^0+(32|33|34|35|36|37|38|39|56|58|59|70|76|77|78|79|81|82|83|84|85|86|88|89|90|91|92|93|94|96|97|98|99)+[0-9]{7}$/';
        if (!preg_match($checkSdt, $phone)) {
            $phone_Err = "Xin mời nhập số điện thoại đúng định dạng";
        }
    }

    if (empty($_POST['email'])) {
        $email_Err = "Xin mời nhập email";
    } else {
        $email = $_POST['email'];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email_Err = " Email không hợp kệ";
        }
    }


    if (empty($_POST['dia_chi'])) {
        $dia_chi_Err = "Xin mời nhập địa chỉ của bạn";
    } else {
        $dia_chi = $_POST['dia_chi'];
    }


    if (empty($_POST['phone_hotline'])) {
        $phone_hotline_Err = "Xin mời nhập số máy bàn";
    } else {
        $phone_hotline = $_POST['phone_hotline'];
    }

    if (empty($_POST['youtobe_url'])) {
        $youtobe_url_Err = "Xin mời nhập link youtube";
    } else {
        $youtobe_url = $_POST['youtobe_url'];
        if (!preg_match('/(?:https:\/\/www\.)?youtube\.com\/watch\?v=[\w_-]{1,11}/', $youtobe_url)) {
            $youtobe_url_Err = "Xin mời nhập link youtube đúng định dạng";
        }
    }
    if (empty($_POST['instagram_url'])) {
        $instagram_url_Err = "Xin mời nhập link Instagram";
    } else {
        $instagram_url = $_POST['instagram_url'];
        if (!preg_match('/(https?:\/\/www\.)?instagram\.com(\/\w+\/?)/', $instagram_url)) {
            $instagram_url_Err = "Xin mời nhập link Instagram đúng định dạng";
        }
    }

    if (empty($_POST['facebook_url'])) {
        $facebook_url_Err = "Xin mời nhập link facebook";
    } else {
        $facebook_url = $_POST['facebook_url'];
        if (!preg_match('/^(https?:\/\/)?(www\.)?facebook.com\/[a-zA-Z0-9(\.\?)?]/', $facebook_url)) {
            $facebook_url_Err = "Xin mời nhập link Facebook đúng định dạng";
        }
    }

    if (empty($_POST['zalo_url'])) {
        $zalo_url_Err = "Xin mời nhập link zalo";
    } else {
        $zalo_url = $_POST['zalo_url'];

        if (!preg_match('/^https:\/\/chat\.zalo\.me\//', $zalo_url)) {
            $zalo_url_Err = "Xin mời nhập link Zalo đúng định dạng";
        }
    }


    $logo = $_FILES['logo'];
    $sizeanh = 1500000;

    if ($_FILES['logo']['size'] <= 0) {
        $logo_Err = "Xin mời bạn nhập ảnh logo";
    } elseif (getimagesize($logo['tmp_name']) == false) {
        $anhErr = "xin mời bạn nhập file ảnh";
    } elseif ($_FILES['logo']['size'] >= $sizeanh) {
        $logo_Err = "xin mời bạn nhập ảnh size nhỏ hơn 1.5mb";
    } else {
        $dir = "../../content/images_logo/";
        $target_file = $dir . basename($logo['name']);
        $filename = "";
        $path = "";

        $typeanh = ['jpg', 'png', 'bmp'];
        $kieu = pathinfo($target_file, PATHINFO_EXTENSION);
        if (!in_array($kieu, $typeanh)) {
            $logo_Err = "Chỉ được upload các định dạng JPG, PNG, JPEG";
        } elseif ($logo['size'] > 0 || $logo['size'] < $sizeanh) {
            $filename = uniqid() . "_" . $logo['name'];
            move_uploaded_file($logo['tmp_name'], "../../content/images_logo/" . $filename);
            $path = "content/images_logo/" . $filename;
        } else {
            $logo_Err = "";
        }
    }
    if ($ten_cong_ty_Err . $phone_Err . $dia_chi_Err . $phone_hotline_Err . $youtobe_url_Err . $instagram_url_Err . $facebook_url_Err . $zalo_url_Err . $logo_Err == "") {
        insert_delete_update("INSERT INTO `setting` ( `ten_cong_ty`, `phone`, `dia_chi`, `email`, `logo`, `phone_hotline`, `youtobe_url`, `instagram_url`, `facebook_url`, `zalo_url`) VALUES ( '$ten_cong_ty', '$phone', '$dia_chi', '$email', '$path', ' $phone_hotline ', '$youtobe_url', ' $instagram_url', '$facebook_url', '$zalo_url');");
        header("location:" . BASE_URL . 'admin/web_setting/index.php?msg=Thêm thông tin thành công !');
        die;
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm thông tin setting</title>
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
    <div class="container-fluid">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <?php include('../layout/menu.php'); ?>
                </div>
                <div class="col-md-9" style="margin-top: 40px;">
                    <form action="<?= BASE_URL ?>admin/web_setting/add_web_setting.php" method="post" enctype="multipart/form-data">
                        <div class="content_admin" style="width: 100%;background-color: #fdf7f7;border-radius: 10px;height:550px;">
                            <div class="tieu_de" style="padding: 20px 0 0 10px;margin-bottom:20px;">
                                <h5>THÊM THÔNG TIN SETTING</h5>
                            </div>
                            <div class="khoi1" style="float:left;width:400px;margin-right:20px;padding-left:10px;">
                                <div class="form-group">
                                    <label for="">Tên công ty *</label>
                                    <input type="text" class="form-control" placeholder="Tên công ty" name="ten_cong_ty" style="background-color:#fdf7f7;">
                                    <?php if (isset($ten_cong_ty_Err)) { ?>
                                        <span style="color:red"><?php echo $ten_cong_ty_Err; ?></span>
                                    <?php } ?>
                                </div>
                                <div class="form-group">
                                    <label for="">Phone *</label>
                                    <input type="text" class="form-control" placeholder="Phone" name="phone" style="background-color:#fdf7f7;">
                                    <?php if (isset($phone_Err)) { ?>
                                        <span style="color:red"><?php echo $phone_Err; ?></span>
                                    <?php } ?>
                                </div>
                                <div class="form-group">
                                    <label for="">Địa Chỉ *</label>
                                    <input type="text" class="form-control" placeholder="Đại chỉ" name="dia_chi" style="background-color:#fdf7f7;">
                                    <?php if (isset($dia_chi_Err)) { ?>
                                        <span style="color:red"><?php echo $dia_chi_Err; ?></span>
                                    <?php } ?>
                                </div>
                                <div class="form-group">
                                    <label for="">Email *</label>
                                    <input type="text" class="form-control" placeholder="Email" name="email" style="background-color:#fdf7f7;">
                                    <?php if (isset($email_Err)) { ?>
                                        <span style="color:red"><?php echo $email_Err; ?></span>
                                    <?php } ?>
                                </div>
                                <div class="form-group">
                                    <label for="">Ảnh Logo *</label>
                                    <input type="file" class="form-control" placeholder="Logo" name="logo" style="background-color:#fdf7f7;">
                                    <?php if (isset($logo_Err)) { ?>
                                        <span style="color:red"><?php echo $logo_Err; ?></span>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="khoi2" style="float:left;width:400px;">
                                <div class="form-group">
                                    <label for="">Số máy bàn *</label>
                                    <input type="text" class="form-control" placeholder="Số máy bàn" name="phone_hotline" style="background-color:#fdf7f7;">
                                    <?php if (isset($phone_hotline_Err)) { ?>
                                        <span style="color:red"><?php echo $phone_hotline_Err; ?></span>
                                    <?php } ?>
                                </div>
                                <div class="form-group">
                                    <label for="">Link Youtube *</label>
                                    <input type="text" class="form-control" placeholder="Link Youtube" name="youtobe_url" style="background-color:#fdf7f7;">
                                    <?php if (isset($youtobe_url_Err)) { ?>
                                        <span style="color:red"><?php echo $youtobe_url_Err; ?></span>
                                    <?php } ?>
                                </div>
                                <div class="form-group">
                                    <label for="">Link Instagram *</label>
                                    <input type="text" class="form-control" placeholder="Link Instagram" name="instagram_url" style="background-color:#fdf7f7;">
                                    <?php if (isset($instagram_url_Err)) { ?>
                                        <span style="color:red"><?php echo $instagram_url_Err; ?></span>
                                    <?php } ?>
                                </div>
                                <div class="form-group">
                                    <label for="">Link Facebook *</label>
                                    <input type="text" class="form-control" placeholder="Link Facebook" name="facebook_url" style="background-color:#fdf7f7;">
                                    <?php if (isset($facebook_url_Err)) { ?>
                                        <span style="color:red"><?php echo $facebook_url_Err; ?></span>
                                    <?php } ?>
                                </div>
                                <div class="form-group">
                                    <label for="">Link Zalo*</label>
                                    <input type="text" class="form-control" placeholder="Link Zalo" name="zalo_url" style="background-color:#fdf7f7;">
                                    <?php if (isset($zalo_url_Err)) { ?>
                                        <span style="color:red"><?php echo $zalo_url_Err; ?></span>
                                    <?php } ?>
                                </div>
                                <div class="form-group">
                                    <input type="submit" value="Thêm" name="them" class="btn btn-secondary">
                                    <a href="<?= BASE_URL ?>admin/web_setting/index.php" class="btn btn-danger" style="font-size: 12px">Quay Lại</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php include('../layout/footer.php'); ?>
</body>

</html>