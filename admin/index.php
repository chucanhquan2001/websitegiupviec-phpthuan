<?php

session_start();
require_once('../dao/pdo.php');
$tong_user = dem("SELECT COUNT(*) FROM users");
$tong_cong_viec = dem("SELECT COUNT(*) FROM tuyen_dung");
$tong_nguoi_tim_viec = dem("SELECT COUNT(*) FROM nhan_vien");
$tong_comment = dem("SELECT COUNT(*) FROM binh_luan");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php include('../link/style.php') ?>
    <link rel="stylesheet" href="../content/css.css">
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
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load("current", {
            packages: ["corechart"]
        });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Dich vụ', 'Số Lượng'],
                <?php
                foreach (selectAll("SELECT dich_vu.id, dich_vu.ten_dich_vu as 'tenLoai', COUNT(*) so_luong FROM nhan_vien JOIN dich_vu ON dich_vu.id = nhan_vien.id_dichvu GROUP BY dich_vu.id, dich_vu.ten_dich_vu") as $row) {
                    echo "['$row[tenLoai]',     $row[so_luong]],";
                }
                ?>
            ]);
            var options = {
                title: 'Tỷ lệ lựa chọn dich vụ của người tìm việc',
                is3D: true,
            };
            var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
            chart.draw(data, options);
        }
    </script>
    <script type="text/javascript">
        google.charts.load("current", {
            packages: ["corechart"]
        });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Dich vụ', 'Số Lượng'],
                <?php
                foreach (selectAll("SELECT dich_vu.id, dich_vu.ten_dich_vu as 'tenLoai', COUNT(*) so_luong FROM tuyen_dung JOIN dich_vu ON dich_vu.id = tuyen_dung.id_dichvu GROUP BY dich_vu.id, dich_vu.ten_dich_vu") as $row) {
                    echo "['$row[tenLoai]',     $row[so_luong]],";
                }
                ?>
            ]);
            var options = {
                title: 'Tỷ lệ lựa chọn dich vụ của người tuyển dụng',
                is3D: true,
            };
            var chart = new google.visualization.PieChart(document.getElementById('piechart_4d'));
            chart.draw(data, options);
        }
    </script>
</head>

<body>
    <?php include('./layout/header.php'); ?>
    <div class="container-fluid">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <?php include('./layout/menu.php'); ?>
                </div>
                <div class="col-md-9" style="margin-top: 40px;">
                    <div class="content_admin" style="width: 100%;background-color: #fdf7f7;border-radius: 10px;">
                        <div class="tieu_de" style="padding: 20px 0 0 10px;">
                            <h5>DASHBOARD</h5>
                            <br><br>
                        </div>
                        <div class="row">
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-primary shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                    user</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $tong_user ?></div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-user fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- category s -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-success shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                    work</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $tong_cong_viec ?></div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- product -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-info shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">job search
                                                </div>
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col-auto">
                                                        <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?= $tong_nguoi_tim_viec ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- comment -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-warning shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                    new comment</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $tong_comment ?></div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-comments fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div id="piechart_3d" style="width: 450px; height: 450px;"></div>
                            </div>
                            <div class="col-md-6">
                                <div id="piechart_4d" style="width: 450px; height: 450px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include('./layout/footer.php'); ?>

    <a id="button"></a>
    <?php include('../link/js.php') ?>
    <script src="../content/js.js"></script>
    <script src="../content/jquery.js"></script>
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