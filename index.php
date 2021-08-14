<?php

session_start();
require_once('./dao/pdo.php');
$active = 'active';
$stt = 0;

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ</title>
    <?php include('./link/style.php') ?>
    <link rel="stylesheet" href="./content/css.css">
    <link rel="stylesheet" href="./link/trangchu.css">
    <style>
        .txt {
            position: relative;
            overflow: hidden;
            height: 140px;
        }

        .chay {
            position: absolute;
            top: 68%;
            left: 22%;
            transition: .5s;
        }

        .txt:hover .chay {
            top: 30%;
            background-color: white;
        }
    </style>
</head>

<body>

    <?php include('./layout/header.php'); ?>
    <div class="container-fluid" style="height: 500px;">
        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
                <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
            </ol>
            <div class="carousel-inner">
                <?php foreach (selectAll("SELECT * FROM slide") as $key) { ?>
                    <?php if ($active == 'active') { ?>
                        <div class="carousel-item active">
                            <img src="<?= BASE_URL ?>content/images_slide/<?= $key['hinh_anh'] ?>" class="d-block w-100" alt="..." height="500px">
                        </div>
                    <?php $active = '';
                    } else { ?>
                        <div class="carousel-item">
                            <img src="<?php BASE_URL ?>content/images_slide/<?= $key['hinh_anh'] ?>" class="d-block w-100" alt="..." height="500px">
                        </div>
                <?php }
                } ?>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>
    <div class="container-fluid">
        <div class="container">
            <div class="row" style="margin-top: 70px">
                <?php foreach (selectAll("SELECT * FROM dich_vu LIMIT 8") as $item) { ?>
                    <div class="col-md-3" style="text-align: center;margin-bottom: 50px;">
                        <div style="color: black;" class="txt">
                            <img src="<?= BASE_URL . $item['hinh_anh'] ?>" alt="" width="100px" height="100px">
                            <br>
                            <div class="chay">
                                <br>
                                <h6><?= $item['ten_dich_vu'] ?></h6>
                                <a href="<?= BASE_URL ?>site/dich_vu/nhan_vien.php?id=<?= $item['id'] ?>">Tìm người giúp việc</a><br>
                                <a href="<?= BASE_URL ?>site/dich_vu/tuyen_dung.php?id=<?= $item['id'] ?>">Tìm việc làm</a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="container">
            <div class="row" style="margin-top: 70px">
                <div class="col-md-6">
                    <h4 style="color: red">TÌM VIỆC MỚI</h4>
                    <hr>
                    <div class="row">
                        <?php foreach (selectAll("SELECT * FROM nhan_vien WHERE trang_thai = 0  ORDER BY id DESC LIMIT 10") as $item) { ?>
                            <?php foreach (selectAll("SELECT * FROM users WHERE id = " . $item['id_user']) as $key) { ?>
                                <?php foreach (selectAll("SELECT * FROM dich_vu WHERE id = " . $item['id_dichvu']) as $next) { ?>
                                    <div class="col-md-4">
                                        <div class="card" style="border: 1px solid #9e7171;padding: 3px;">
                                            <a href="<?= BASE_URL ?>site/nhan_vien/chi_tiet_nhan_vien.php?id=<?= $item['id'] ?>&id_dv=<?= $item['id_dichvu'] ?>"><img class="card-img-top" src="<?= BASE_URL ?>content/images_user/<?= $key['hinh_anh'] ?>" alt="Card image cap" height="250px"></a>
                                            <div class="card-body">
                                                <p class="card-text" style="font-size: 12px;">Họ tên : <?= $key['ho_ten'] ?></p>
                                                <p class="card-text" style="font-size: 12px;">Quê quán : <?= $item['dia_chi'] ?></p>
                                                <p class="card-text" style="font-size: 12px;">Dịch vụ : <?= $next['ten_dich_vu'] ?></p>
                                            </div>
                                        </div>
                                        <br>
                                    </div>
                        <?php }
                            }
                        } ?>
                        <br>
                    </div>
                    <a href="<?= BASE_URL ?>site/nhan_vien/dang_tin_tim_viec.php" class="btn btn-info">Đăng tin tìm việc</a>
                </div>
                <div class="col-md-6">
                    <h4 style="color: red">TUYỂN DỤNG MỚI</h4>
                    <hr>
                    <?php foreach (selectAll("SELECT * FROM tuyen_dung WHERE trang_thai = 0 AND trang_thai_duyet = 0 ORDER BY id DESC LIMIT 10") as $item) { ?>
                        <a href="<?= BASE_URL ?>site/tuyen_dung/chi_tiet_tuyen_dung.php?id=<?= $item['id'] ?>&id_dichvu=<?= $item['id_dichvu'] ?>" style="color: green;"><b><?= $stt += 1 ?> . <?= $item['tom_tat'] ?></b></a>
                        <p><?= $item['mo_ta'] ?></p>
                    <?php } ?>
                    <br>
                    <a href="<?= BASE_URL ?>site/tuyen_dung/dang_tin_tuyen_dung.php" class="btn btn-info">Đăng tin tuyển dụng</a>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="container">
            <div class="row" style="margin-top: 70px">
                <div class="col-md-12">
                    <h4 style="color: red">TIN TỨC MỚI NHẤT</h4>
                    <hr>
                    <?php foreach (selectAll("SELECT * FROM tin_tuc ORDER BY id DESC LIMIT 6") as $item) {
                        foreach (selectAll("SELECT * FROM users WHERE id = " . $item['id_user']) as $value) {
                            $tong = dem("SELECT COUNT(*) FROM binh_luan WHERE id_tin_tuc = " . $item['id']); ?>
                            <div class="row" style="color: #626262;">
                                <a href="<?= BASE_URL ?>site/tin_tuc/chi_tiet.php?id=<?= $item["id"] ?>" style="color: #626262;">
                                    <p style="font-size: 18px;margin-left: 15px;"><?= $item["tieu_de"] ?></p>
                                </a>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <a href="<?= BASE_URL ?>site/tin_tuc/chi_tiet.php?id=<?= $item["id"] ?>"><img src="<?= BASE_URL ?>content/images_tin_tuc/<?= $item["hinh_anh"] ?>" alt="" width="100%" height="200px" style="border: 1px solid #9e7171;padding: 3px;"></a>
                                </div>
                                <div class="col-md-9">
                                    <p style="font-size: 12px;color: gray;"><i class="fas fa-stopwatch"></i> &nbsp;<?= $item["thoi_gian"] ?></p>
                                    <a href="<?= BASE_URL ?>site/tin_tuc/chi_tiet.php?id=<?= $item["id"] ?>" style="color: #626262;">
                                        <p><?= $item["tom_tat"] ?>...</p>
                                    </a>
                                    <p style="font-size: 12px;color: gray;"> <i class="fas fa-eye"></i> &nbsp;<?= $item["luot_xem"] ?> &nbsp; &nbsp;<i class="fas fa-comment"></i> <?= $tong ?> &nbsp; &nbsp; <i class="fas fa-user"></i> &nbsp;<?= $value["ho_ten"] ?> </p>
                                </div>
                            </div>
                            <br><br>
                            <hr>
                    <?php }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php include('./layout/footer.php'); ?>

    <a id="button"></a>
    <?php include('./link/js.php') ?>
    <script src="./content/js.js"></script>
    <script src="./content/jquery.js"></script>
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