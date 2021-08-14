<?php
session_start();
require_once "../../dao/pdo.php";

if (isset($_POST['add_tai_khoan'])) {
    $dia_chi_err = $phone_err = $hon_nhan_err = $kinh_nghiem_err = $dich_vu_err = $user_err = $hinh_thuc_lam_viec_err = $muc_luong_err = $nguyen_vong_err = $trang_thai_err = "";
    if (isset($_SESSION['user']) || isset($_SESSION['admin'])) {
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
        if (empty($_POST['kinh_nghiem'])) {
            $kinh_nghiem_err = " Xin mời bạn chọn kinh nghiệm của mình";
        } else {
            $kinh_nghiem = $_POST['kinh_nghiem'];
        }

        if (empty($_POST['dich_vu'])) {
            $dich_vu_err = " Xin mời bạn chọn dịch vụ";
        } else {
            $dich_vu = $_POST['dich_vu'];
        }

        if (isset($_SESSION['user'])) {
            $user = $_SESSION['user']['id'];
        } else {
            $user = $_SESSION['admin']['id'];
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
        if (empty($_POST['nguyen_vong'])) {
            $nguyen_vong_err = " Xin mời bạn nhập nguyện vọng vủa mình vào";
        } else {
            $nguyen_vong = $_POST['nguyen_vong'];
        }

        $trang_thai = 0;
        $hon_nhan = $_POST['hon_nhan'];

        // echo "trạng thái" . $trang_thai . "<br>";
        // echo "hon_nhan" . $hon_nhan . "<br>";
        // echo "nguyen_vong" . $nguyen_vong . "<br>";
        // echo "muc_luong" . $muc_luong . "<br>";
        // echo "hinh_thuc_lam_viec" . $hinh_thuc_lam_viec . "<br>";
        // echo "user" . $user . "<br>";
        // echo "dich_vu" . $dich_vu . "<br>";

        if ($dia_chi_err . $phone_err . $hon_nhan_err . $kinh_nghiem_err . $dich_vu_err . $hinh_thuc_lam_viec_err . $muc_luong_err . $nguyen_vong_err == "") {
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
            $them = ("INSERT INTO `nhan_vien` (`id`, `dia_chi`, `phone`, `status`, `kinh_nghiem`, `id_dichvu`, `id_user`, `hinh_thuc_lam_viec`, `muc_luong`, `nguyen_vong`, `trang_thai`) VALUES (NULL, '$dia_chi', '$phone', $hon_nhan, '$kinh_nghiem', '$dich_vu', '$user', '$hinh_thuc_lam_viec', '$muc_luong', '$nguyen_vong', $trang_thai)");
            $connect = connect();
            $stmt = $connect->prepare($them);
            $stmt->execute();
            header("location:" . BASE_URL . "site/nhan_vien/dang_tin_tim_viec.php?msg=Đăng tin thành công");
            die;
        }
    } else {
        header("location:" . BASE_URL . "site/tai_khoan/login.php?msg=Bạn chưa đăng nhập !");
    }
}




?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nhân viên</title>
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
                <h3 class="header_title">Đăng Tin Tìm Việc</h3>
            </div>
        </div>
    </div>
    <br>
    <div class="container-fluid">
        <div class="container">
            <div class="row">
                <div class="col-md-9">
                    <?php if (isset($_GET['msg'])) { ?>
                        <p style="color: red;margin-left: 10px;"><?= $_GET['msg']; ?></p>
                    <?php } ?>
                    <div class="content_admin" style="width: 100%;background-color: #fdf7f7;border-radius: 10px;">
                        <form action="" method="post" enctype="multipart/form-data" class="form_user">
                            <div class="row">
                                <div class="col-md-6" style="padding-left: 25px;">
                                    <div class="form-group">
                                        <label for="">Địa chỉ *</label>
                                        <input type="text" class="form-control" name="dia_chi" placeholder="Địa chỉ của bạn" style="background-color: #fdf7f7;">
                                        <?php if (isset($dia_chi_err)) { ?>
                                            <span style="color:red;"><?php echo $dia_chi_err ?></span>
                                        <?php } ?>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Số điện thoại *</label>
                                        <input type="text" class="form-control" name="phone" placeholder="Số điện thoại của bạn" style="background-color: #fdf7f7;">
                                        <?php if (isset($phone_err)) { ?>
                                            <span style="color:red;"><?php echo $phone_err ?></span>
                                        <?php } ?>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Tình trạng hôn nhân *</label>
                                        <div class="form-control" style="background-color: #fdf7f7;">
                                            <input type="radio" name="hon_nhan" value="0" checked> Đã kết hôn
                                            <input type="radio" name="hon_nhan" value="1"> Chưa kết hôn
                                        </div>
                                        <?php if (isset($hon_nhan_err)) { ?>
                                            <span style="color:red;"><?php echo $hon_nhan_err ?></span>
                                        <?php } ?>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Kinh nghiệm làm việc *</label>
                                        <select name="kinh_nghiem" class="form-control">
                                            <option value="">--- Xin mời bạn chọn ---</option>
                                            <option value="Chưa có kinh nghiệm">Chưa có kinh nghiệm</option>
                                            <option value="Kinh nghiệm dưới 1 năm">Kinh nghiệm dưới 1 năm</option>
                                            <option value="Kinh nghiệm 1 năm">Kinh nghiệm 1 năm</option>
                                            <option value="Kinh nghiệm 2 năm">Kinh nghiệm 2 năm</option>
                                            <option value="Kinh nghiệm 3 năm">Kinh nghiệm 3 năm</option>
                                            <option value="Kinh nghiệm trên 3 năm">Kinh nghiệm trên 3 năm</option>
                                        </select>
                                        <?php if (isset($kinh_nghiem_err)) { ?>
                                            <span style="color:red;"><?php echo $kinh_nghiem_err ?></span>
                                        <?php } ?>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Dịch Vụ *</label>
                                        <select name="dich_vu" class="form-control">
                                            <option value="">--- Xin mời bạn chọn ---</option>
                                            <?php foreach (selectall("select * from dich_vu") as $value) { ?>
                                                <option value="<?= $value['id'] ?>"><?= $value['ten_dich_vu'] ?></option>
                                            <?php } ?>
                                        </select>
                                        <?php if (isset($dich_vu_err)) { ?>
                                            <span style="color:red;"><?php echo $dich_vu_err ?></span>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="col-md-6" style="padding-right: 25px;">
                                    <div class="form-group">
                                        <label for="">Hình thức làm việc *</label>
                                        <select name="hinh_thuc_lam_viec" class="form-control">
                                            <option value="">Xin mời bạn chọn</option>
                                            <option value="Toàn thời gian">Toàn thời gian</option>
                                            <option value="Bán thời gian">Bán thời gian</option>
                                            <option value="Tính theo giờ">Tính theo giờ</option>
                                            <option value="Theo hợp đồng">Theo hợp đồng</option>
                                            <option value="Thực tập">Thực tập</option>
                                            <option value="Khác">Khác</option>
                                        </select>

                                        <?php if (isset($hinh_thuc_lam_viec_err)) { ?>
                                            <span style="color:red;"><?php echo $hinh_thuc_lam_viec_err ?></span>
                                        <?php } ?>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Mức lương mong muốn *</label>
                                        <input type="number" class="form-control" name="muc_luong" placeholder="Mức lương mong muốn" style="background-color: #fdf7f7;">
                                        <?php if (isset($muc_luong_err)) { ?>
                                            <span style="color:red;"><?php echo $muc_luong_err ?></span>
                                        <?php } ?>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Nguyện vọng *</label>
                                        <input type="text" name="nguyen_vong" placeholder="Nguyện vọng của bạn" style="background-color: #fdf7f7;" class="form-control">
                                        <?php if (isset($nguyen_vong_err)) { ?>
                                            <span style="color:red;"><?php echo $nguyen_vong_err ?></span>
                                        <?php } ?>
                                    </div>
                                    <br>
                                    <button type="submit" class="btn btn-secondary" name="add_tai_khoan">Đăng</button>
                                    <a href="<?= BASE_URL ?>" type="submit" class="btn btn-danger">Quay lại</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-3">
                    <?php include_once('../../layout/content_right.php') ?>
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