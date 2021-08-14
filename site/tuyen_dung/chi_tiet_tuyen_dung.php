<?php
session_start();
require_once "../../dao/pdo.php";
$stt = 0;
if (isset($_GET['id']) && isset($_GET['id_dichvu'])) {
    $id = $_GET['id'];
    $id_dv = $_GET['id_dichvu'];
} else {
    header("location:" . BASE_URL);
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
                <h3 class="header_title">Thông Tin Tuyển Dụng</h3>
            </div>
        </div>
    </div>
    <br>
    <div class="container-fluid">
        <div class="container">
            <div class="row">
                <div class="col-md-9">
                    <?php foreach (selectAll("SELECT * FROM tuyen_dung WHERE id = '$id' AND trang_thai = 0 AND trang_thai_duyet = 0") as $item) { ?>
                        <?php foreach (selectAll("SELECT * FROM users WHERE id = " . $item['id_user']) as $key) { ?>
                            <?php foreach (selectAll("SELECT * FROM dich_vu WHERE id = " . $item['id_dichvu']) as $next) { ?>
                                <div class="row">
                                    <div class="card" style="width: 100%">
                                        <div class="card-header">
                                            Thông tin tuyển dụng
                                        </div>
                                        <div class="card-body">
                                            <p><b>Tóm tắt công việc :</b> <?= $item['tom_tat'] ?></p>
                                            <p><b>Nơi làm việc :</b> <?= $item['noi_lam_viec'] ?></p>
                                            <p><b>Hình thức làm việc :</b> <?= $item['hinh_thuc_lam_viec'] ?></p>
                                            <p><b>Yêu cầu kinh nghiệm :</b> <?= $item['yeu_cau'] ?></p>

                                            <p><b>Trạng thái hôn nhân :</b> <?= ($item['status'] == 0) ? 'Đã kết hôn' : 'Chưa kết hôn' ?></p>

                                            <p><b>Mức lương dự kiến :</b> <?= money($item['muc_luong']) ?> vnđ/tháng</p>
                                            <p><b>Mô tả công việc :</b> <?= $item['mo_ta'] ?></p>
                                            <p><b>Dịch vụ làm việc :</b> <?= $next['ten_dich_vu'] ?></p>
                                            <a onclick="return confirm('Thông tin cá nhân của bạn sẽ được gửi đến người tuyển dụng ! Bạn chắc chắn muốn ứng tuyển chứ ?')" href="<?= BASE_URL ?>site/tuyen_dung/thong_bao.php?id=<?= $item['id'] ?>" class="btn btn-success">Ứng tuyển</a>
                                        </div>
                                    </div>
                                </div>
                                <br><br>
                                <div class="row">
                                    <div class="card" style="width: 100%">
                                        <div class="card-header">
                                            Thông tin liên hệ
                                        </div>
                                        <div class="card-body">
                                            <p><b>Số điện thoại :</b> <?= $item['phone'] ?></p>
                                            <p><b>Email :</b> <?= $key['email'] ?></p>
                                        </div>
                                    </div>
                                </div>
                    <?php }
                        }
                    } ?>
                    <br><br>
                    <div class="row">
                        <h5 style="margin-left: 15px">Công Việc Cùng Dịch Vụ</h5>
                    </div>
                    <hr>
                    <div class="row">
                        <?php foreach (selectAll("SELECT * FROM tuyen_dung WHERE trang_thai = 0 AND trang_thai_duyet = 0 AND id_dichvu = $id_dv AND id != $id ORDER BY id DESC LIMIT 10") as $item) { ?>
                            <a href="<?= BASE_URL ?>site/tuyen_dung/chi_tiet_tuyen_dung.php?id=<?= $item['id'] ?>&id_dichvu=<?= $item['id_dichvu'] ?>" style="color: green;"><b><?= $stt += 1 ?> . <?= $item['tom_tat'] ?></b></a>
                            <p><?= $item['mo_ta'] ?></p>
                        <?php } ?>
                    </div>
                </div>
                <div class="col-md-3">
                    <?php include_once('../../layout/content_right.php') ?>
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