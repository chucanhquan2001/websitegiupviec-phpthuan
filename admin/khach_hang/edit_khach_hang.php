<?php
session_start();
require_once('../../dao/pdo.php');
$id = $_GET['id'];
if (isset($_POST['add_tai_khoan'])) {
    $dia_chi_err = $phone_err = $hon_nhan_err = $mo_ta_err = $dich_vu_err = $user_err = $hinh_thuc_lam_viec_err = $muc_luong_err = $yeu_cau_err = $trang_thai_err = $tom_tat_err = "";


    if (empty($_POST['dia_chi'])) {
        $dia_chi_err = " Xin mời bạn nhập địa chỉ của mình vào";
    } else {
        $dia_chi = $_POST['dia_chi'];
    }


    if (empty($_POST['phone'])) {
        $phone_err = " Xin mời bạn số điện thoại của mình vào";
    } else {
        $phone = $_POST['phone'];
        $checkSdt = '/^0+(32|33|34|35|36|37|38|39|56|58|59|70|76|77|78|79|81|82|83|84|85|86|88|89|90|91|92|93|94|96|97|98|99)+[0-9]{7}$/';
        if (!preg_match($checkSdt, $phone)) {
            $phone_err = "Xin mời nhập số điện thoại đúng định dạng";
        }
    }


    if (empty($_POST['mo_ta'])) {
        $mo_ta_err = " Xin mời bạn nhập mô tả công việc của mình vào";
    } else {
        $mo_ta = $_POST['mo_ta'];
    }

    if (empty($_POST['tom_tat'])) {
        $tom_tat_err = " Xin mời bạn nhập tóm tắt công việc";
    } else {
        $tom_tat = $_POST['tom_tat'];
    }

    if (empty($_POST['dich_vu'])) {
        $dich_vu_err = " Xin mời bạn chọn dịch vụ";
    } else {
        $dich_vu = $_POST['dich_vu'];
    }

    if (empty($_POST['user'])) {
        $user_err = " Xin mời bạn chọn dịch vụ";
    } else {
        $user = $_POST['user'];
    }

    if (empty($_POST['hinh_thuc_lam_viec'])) {
        $hinh_thuc_lam_viec_err = " Xin mời hình thức làm việc";
    } else {
        $hinh_thuc_lam_viec = $_POST['hinh_thuc_lam_viec'];
    }

    if (empty($_POST['muc_luong'])) {
        $muc_luong_err = " Xin mời nhập mức lương mong muốn";
    } else {
        $muc_luong = $_POST['muc_luong'];
        $check_luong = '/^[1-9]+[0-9]+$/';
        if (!preg_match($check_luong, $muc_luong)) {
            $muc_luong_err = " Xin mời nhập mức lương đúng ";
        }
    }

    if (empty($_POST['yeu_cau'])) {
        $yeu_cau_err = " Xin mời bạn nhập yêu cầu vủa mình vào";
    } else {
        $yeu_cau = $_POST['yeu_cau'];
    }

    $trang_thai = $_POST['trang_thai'];
    $trang_thai_duyet = $_POST['trang_thai_duyet'];
    $hon_nhan = $_POST['hon_nhan'];



    // echo "trạng thái" . $trang_thai . "<br>";
    // echo "hon_nhan" . $hon_nhan . "<br>";
    // echo "nguyen_vong" . $nguyen_vong . "<br>";
    // echo "muc_luong" . $muc_luong . "<br>";
    // echo "hinh_thuc_lam_viec" . $hinh_thuc_lam_viec . "<br>";
    // echo "user" . $user . "<br>";
    // echo "dich_vu" . $dich_vu . "<br>";


    if ($dia_chi_err . $phone_err . $hon_nhan_err . $yeu_cau_err . $dich_vu_err . $user_err . $hinh_thuc_lam_viec_err . $muc_luong_err . $yeu_cau_err . $trang_thai_err . $tom_tat_err == "") {
        // $insert = "insert into nhan_vien set dia_chi =:dia_chi ,phone=:phone , status=:hon_nhan,kinh_nghiem =:kinh_nghiem,id_dich_vu':dich_vu,id_user=:user,hinh_thuc_lam_viec=:hinh_thuc_lam_viec,muc_luong=:muc_luong,nguyen_vong=:nguyen_vong,trang_thai=:trang_thai ";
        // $connect = connect();
        // $stmt = $connect->prepare($insert);
        // $stmt->bindParam(':dia_chi', $dia_chi);
        // $stmt->bindParam(':phone', $phone);
        // $stmt->bindParam(':hon_nhan', $hon_nhan);   
        // $stmt->bindParam(':kinh_nghiem', $kinh_nghiem);
        // $stmt->bindParam(':dich_vu', $dich_vu);
        // $stmt->bindParam(':user', $user);
        // $stmt->bindParam(':hinh_thuc_lam_viec', $hinh_thuc_lam_viec);
        // $stmt->bindParam(':muc_luong', $muc_luong);
        // $stmt->bindParam(':nguyen_vong', $nguyen_vong);
        // $stmt->bindParam(':trang_thai', $trang_thai);
        // $stmt->execute();
        // (insert_delete_update("INSERT INTO `nhan_vien`(`dia_chi`, `phone`, `status`, `kinh_nghiem`, `id_dichvu`, `id_user`, `hinh_thuc_lam_viec`, `muc_luong`, `nguyen_vong`, `trang_thai`) values('$dia_chi','$phone',$hon_nhan,'$kinh_nghiem','$dich_vu','$user','$hinh_thuc_lam_viec',$muc_luong,'$nguyen_vong',$trang_thai)"));
        // 
        // $them = ("UPDATE `tuyen_dung` `id_user`='$user',`id_dichvu`='$dich_vu',`yeu_cau`='$yeu_cau',`hinh_thuc_lam_viec`='$hinh_thuc_lam_viec',`noi_lam_viec`='$dia_chi',`muc_luong`='$muc_luong',`mo_ta`='$mo_ta',`status`=$hon_nhan,`phone`='$phone',`trang_thai`=0 WHERE id ='2'");

        //    , `id_dichvu`, `yeu_cau`, `hinh_thuc_lam_viec`, `noi_lam_viec`, `muc_luong`, `mo_ta`, `status`, `phone`, `trang_thai`
        insert_delete_update("UPDATE `tuyen_dung` SET `id_user` = '$user', `id_dichvu` = '$dich_vu', `yeu_cau` = '$yeu_cau', `hinh_thuc_lam_viec` = '$hinh_thuc_lam_viec', `noi_lam_viec` = '$dia_chi', `muc_luong` = '$muc_luong', `mo_ta` = '$mo_ta', `tom_tat` = '$tom_tat',`status` = $hon_nhan, `phone` = '$phone', `trang_thai` = $trang_thai, `trang_thai_duyet` = $trang_thai_duyet WHERE `tuyen_dung`.`id` = '$id';");
        header("location:" . BASE_URL . "admin/khach_hang/index.php?msg=Cập nhập người tuyển dụng thành công");
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
                            <h5>CẬP NHẬP CÔNG VIỆC</h5>
                        </div>
                        <br>
                        <form action="" method="post" enctype="multipart/form-data" class="form_user">
                            <div class="row">
                                <?php foreach (selectall("select * from tuyen_dung where id =$id") as $item) { ?>
                                    <div class="col-md-6" style="padding-left: 25px;">
                                        <div class="form-group">
                                            <label for="">Địa chỉ *</label>
                                            <input type="text" class="form-control" name="dia_chi" value="<?php echo $item['noi_lam_viec'] ?>" style="background-color: #fdf7f7;">
                                            <?php if (isset($dia_chi_err)) { ?>
                                                <span style="color:red;"><?php echo $dia_chi_err ?></span>
                                            <?php } ?>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Số điện thoại *</label>
                                            <input type="text" class="form-control" name="phone" value="<?php echo $item['phone'] ?>" style="background-color: #fdf7f7;">
                                            <?php if (isset($phone_err)) { ?>
                                                <span style="color:red;"><?php echo $phone_err ?></span>
                                            <?php } ?>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Tình trạng hôn nhân *</label>
                                            <div class="form-control" style="background-color: #fdf7f7;">
                                                <input type="radio" name="hon_nhan" value="0" <?php echo $item['status'] == 0 ? 'checked' : '' ?>> Đã kết hôn
                                                <input type="radio" name="hon_nhan" value="1" <?php echo $item['status'] == 1 ? 'checked' : '' ?>> Chưa kết hôn
                                            </div>
                                            <?php if (isset($hon_nhan_err)) { ?>
                                                <span style="color:red;"><?php echo $hon_nhan_err ?></span>
                                            <?php } ?>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Mô Tả *</label>
                                            <input type="text" class="form-control" name="mo_ta" value="<?= $item['mo_ta'] ?>" style="background-color: #fdf7f7;">
                                            <?php if (isset($mo_ta_err)) { ?>
                                                <span style="color:red;"><?php echo $mo_ta_err ?></span>
                                            <?php } ?>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Tóm tắt công việc *</label>
                                            <input type="text" name="tom_tat" placeholder="Tóm tắt công việc" value="<?= $item['tom_tat'] ?>" style="background-color: #fdf7f7;" class="form-control">
                                            <?php if (isset($tom_tat_err)) { ?>
                                                <span style="color:red;"><?php echo $tom_tat_err ?></span>
                                            <?php } ?>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Dịch Vụ *</label>
                                            <select name="dich_vu" class="form-control">
                                                <option value="">--- Xin mời bạn chọn ---</option>
                                                <?php foreach (selectall("select * from dich_vu") as $value) { ?>
                                                    <option value="<?= $value['id'] ?>" <?php if ($value['id'] == $item['id_dichvu']) {
                                                                                            echo "selected";
                                                                                        } ?>><?= $value['ten_dich_vu'] ?></option>
                                                <?php } ?>
                                            </select>
                                            <?php if (isset($dich_vu_err)) { ?>
                                                <span style="color:red;"><?php echo $dich_vu_err ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6" style="padding-right: 25px;">
                                        <div class="form-group">
                                            <label for="">Người tuyển dụng *</label>
                                            <select name="user" class="form-control">
                                                <option value="">--- Xin mời bạn chọn ---</option>
                                                <?php foreach (selectall("select * from users") as $value) { ?>
                                                    <option value="<?= $value['id'] ?>" <?php if ($value['id'] == $item['id_user']) {
                                                                                            echo "selected";
                                                                                        } ?>><?= $value['ho_ten'] ?></option>
                                                <?php } ?>
                                            </select>
                                            <?php if (isset($user_err)) { ?>
                                                <span style="color:red;"><?php echo $user_err ?></span>
                                            <?php } ?>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Hình thức làm việc *</label>
                                            <select name="hinh_thuc_lam_viec" class="form-control">
                                                <option>Xin mời bạn chọn</option>
                                                <option value="Toàn thời gian" <?php if ($item['hinh_thuc_lam_viec'] == "Toàn thời gian") {
                                                                                    echo "selected";
                                                                                } ?>>Toàn thời gian</option>
                                                <option value="Bán thời gian" <?php if ($item['hinh_thuc_lam_viec'] == "Bán thời gian") {
                                                                                    echo "selected";
                                                                                } ?>>Bán thời gian</option>
                                                <option value="Tính theo giờ" <?php if ($item['hinh_thuc_lam_viec'] == "Tính theo giờ") {
                                                                                    echo "selected";
                                                                                } ?>>Tính theo giờ</option>
                                                <option value="Theo hợp đồng" <?php if ($item['hinh_thuc_lam_viec'] == "Theo hợp đồng") {
                                                                                    echo "selected";
                                                                                } ?>>Theo hợp đồng</option>
                                                <option value="Thực tập" <?php if ($item['hinh_thuc_lam_viec'] == "Thực tập") {
                                                                                echo "selected";
                                                                            } ?>>Thực tập</option>
                                                <option value="Khác" <?php if ($item['hinh_thuc_lam_viec'] == "Khác") {
                                                                            echo "selected";
                                                                        } ?>>Khác</option>
                                            </select>

                                            <?php if (isset($hinh_thuc_lam_viec_err)) { ?>
                                                <span style="color:red;"><?php echo $hinh_thuc_lam_viec_err ?></span>
                                            <?php } ?>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Mức lương mong muốn *</label>
                                            <input type="number" class="form-control" name="muc_luong" value="<?php echo $item['muc_luong'] ?>" style="background-color: #fdf7f7;">
                                            <?php if (isset($muc_luong_err)) { ?>
                                                <span style="color:red;"><?php echo $muc_luong_err ?></span>
                                            <?php } ?>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Yêu cầu kinh nghiệm *</label>
                                            <select name="yeu_cau" class="form-control">
                                                <option>Xin mời bạn chọn</option>
                                                <option value="Chưa có kinh nghiệm" <?php if ($item['yeu_cau'] == "Chưa có kinh nghiệm") {
                                                                                        echo "selected";
                                                                                    } ?>>Chưa có kinh nghiệm</option>
                                                <option value="Kinh nghiệm dưới 1 năm" <?php if ($item['yeu_cau'] == "Kinh nghiệm dưới 1 năm") {
                                                                                            echo "selected";
                                                                                        } ?>>Kinh nghiệm dưới 1 năm</option>
                                                <option value="Kinh nghiệm 1 năm" <?php if ($item['yeu_cau'] == "Kinh nghiệm 1 năm") {
                                                                                        echo "selected";
                                                                                    } ?>>Kinh nghiệm 1 năm</option>
                                                <option value="Kinh nghiệm 2 năm" <?php if ($item['yeu_cau'] == "Kinh nghiệm 2 năm") {
                                                                                        echo "selected";
                                                                                    } ?>>Kinh nghiệm 2 năm</option>
                                                <option value="Kinh nghiệm 3 năm" <?php if ($item['yeu_cau'] == "Kinh nghiệm 3 năm") {
                                                                                        echo "selected";
                                                                                    } ?>>Kinh nghiệm 3 năm</option>
                                                <option value="Kinh nghiệm trên 3 năm" <?php if ($item['yeu_cau'] == "Kinh nghiệm trên 3 năm") {
                                                                                            echo "selected";
                                                                                        } ?>> Kinh nghiệm trên 3 năm</option>
                                            </select>
                                            <?php if (isset($yeu_cau_err)) { ?>
                                                <span style="color:red;"><?php echo $yeu_cau_err ?></span>
                                            <?php } ?>
                                        </div>

                                        <div class="form-group">
                                            <label for="">Tình trạng ứng tuyển *</label>
                                            <div class="form-control" style="background-color: #fdf7f7;">
                                                <label><input name="trang_thai" value="0" type="radio" <?php echo $item['trang_thai'] == 0 ? 'checked' : '' ?>> Chưa có ứng tuyển</label>&nbsp;&nbsp;
                                                <label><input name="trang_thai" value="1" type="radio" <?php echo $item['trang_thai'] == 1 ? 'checked' : '' ?>> Đã có người ứng tuyển</label>
                                            </div>
                                            <?php if (isset($trang_thai_err)) { ?>
                                                <span style="color:red;"><?php echo $trang_thai_err ?></span>
                                            <?php } ?>
                                        </div>
                                        <br>
                                        <div class="form-group">
                                            <label for="">Trạng thái duyệt *</label>
                                            <div class="form-control" style="background-color: #fdf7f7;">
                                                <label><input name="trang_thai_duyet" value="0" type="radio" <?php echo $item['trang_thai_duyet'] == 0 ? 'checked' : '' ?>> Đã được duyệt</label>&nbsp;&nbsp;
                                                <label><input name="trang_thai_duyet" value="1" type="radio" <?php echo $item['trang_thai_duyet'] == 1 ? 'checked' : '' ?>> Chưa được duyệt</label>
                                            </div>

                                        </div>
                                        <br>
                                        <button type="submit" class="btn btn-secondary" name="add_tai_khoan">Cập nhập</button>
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