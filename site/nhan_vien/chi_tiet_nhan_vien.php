<?php
session_start();
require_once "../../dao/pdo.php";

if (isset($_GET['id']) && isset($_GET['id_dv'])) {
    $id = $_GET['id'];
    $id_dv = $_GET['id_dv'];
    if (isset($_SESSION['admin']) || isset($_SESSION['user'])) {
        if (isset($_SESSION['admin'])) {
            $id_user = $_SESSION['admin']['id'];
        }
        if (isset($_SESSION['user'])) {
            $id_user = $_SESSION['user']['id'];
        }
        if (isset($_POST['guisao'])) {
            $danhgiaErr = "";
            if (empty($_POST['rating'])) {
                $danhgiaErr = "Xin mời bạn chọn số sao đánh giá";
            } else {
                $danhgia = $_POST['rating'];
            }
            if ($danhgiaErr == "") {
                insert_delete_update("INSERT INTO danh_gia(`id_user`, `id_nhan_vien`, `danh_gia`) VALUES ('$id_user','$id','$danhgia')");
                header("Location:" . BASE_URL . "site/nhan_vien/chi_tiet_nhan_vien.php?id=$id&id_dv=$id_dv");
                die;
            }
        }
        if (isset($_GET['xoa'])) {
            $xoa = $_GET['xoa'];
            insert_delete_update("DELETE FROM danh_gia where id = $xoa");
            header("Location:" . BASE_URL . "site/nhan_vien/chi_tiet_nhan_vien.php?id=$id&id_dv=$id_dv");
            die;
        }
        if (isset($_GET['like'])) {
            $id_like = $_GET['like'];
            $like = 1;
            foreach (selectAll("select * from danh_gia where id=$id_like") as $value) {
                $like += $value['so_like'];
                insert_delete_update("UPDATE  danh_gia SET so_like = $like where id = $id_like");
                header("Location:" . BASE_URL . "site/nhan_vien/chi_tiet_nhan_vien.php?id=$id&id_dv=$id_dv");
            }
        }
    }
    $bien = selectAll("SELECT danh_gia.* , users.ho_ten as ho_ten , users.hinh_anh as hinh_anh  FROM danh_gia inner join users on danh_gia.id_user = users.id and danh_gia.id_nhan_vien=$id LIMIT 0,6");
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
    <link rel="stylesheet" href="../../content/sao.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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

        ul>.ds {
            list-style: none;
            margin-bottom: 25px;
        }

        .mauxanh:hover {
            color: blue;
        }

        @import url(//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css);

        /*reset css*/
        div,
        label {
            margin: 0;
            padding: 0;
        }



        h1 {
            font-size: 1.5em;
            margin: 10px;
        }

        /****** Style Star Rating Widget *****/
        #rating {
            border: none;
            float: left;
        }

        #rating>input {
            display: none;
        }


        #rating>label:before {
            margin: 5px;
            font-size: 1.25em;
            font-family: FontAwesome;
            display: inline-block;
            content: "\f005";
        }

        /*1 ngôi sao*/
        #rating>.half:before {
            content: "\f089";
            position: absolute;
        }

        /*0.5 ngôi sao*/
        #rating>label {
            color: #ddd;
            float: right;
        }


        #rating>input:checked~label,
        #rating:not(:checked)>label:hover,
        #rating:not(:checked)>label:hover~label {
            color: #CCA772;
        }


        #rating>input:checked+label:hover,
        #rating>input:checked~label:hover,
        #rating>label:hover~input:checked~label,
        #rating>input:checked~label:hover~label {
            color: #FFED85;
        }
    </style>
</head>

<body>
    <?php include('../../layout/header.php') ?>
    <div class="container-fluid" style="background-color: #64cc7b;">
        <div class="container">
            <div class="row" style="margin-top:20px;margin-bottom:20px;">
                <h3 class="header_title">Thông Tin Nhân Viên</h3>
            </div>
        </div>
    </div>
    <br>
    <div class="container-fluid">
        <div class="container">
            <div class="row">
                <div class="col-md-9">
                    <div class="row">
                        <?php foreach (selectAll("SELECT * FROM nhan_vien WHERE id = '$id' AND trang_thai = 0") as $item) { ?>
                            <?php foreach (selectAll("SELECT * FROM users WHERE id = " . $item['id_user']) as $key) { ?>
                                <?php foreach (selectAll("SELECT * FROM dich_vu WHERE id = " . $item['id_dichvu']) as $next) { ?>
                                    <div class="col-md-4">
                                        <div class="img">
                                            <img src="<?= BASE_URL ?>content/images_user/<?= $key['hinh_anh'] ?>" alt="" width="100%">
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <p><b>Họ và tên :</b> <?= $key['ho_ten'] ?></p>
                                        <p><b>Quê quán :</b> <?= $item['dia_chi'] ?></p>
                                        <p><b>Kinh nghiệm làm việc :</b> <?= $item['kinh_nghiem'] ?></p>
                                        <p><b>Giới tính :</b> <?= ($key['gioi_tinh'] == 0) ? 'Nam' : 'Nữ' ?></p>
                                        <p><b>Năm sinh :</b> <?= $key['ngay_sinh'] ?></p>
                                        <p><b>Số điện thoại :</b> <?= $item['phone'] ?></p>
                                        <p><b>Trạng thái hôn nhân :</b> <?= ($item['status'] == 0) ? 'Đã kết hôn' : 'Chưa kết hôn' ?></p>

                                        <p><b>Mức lương mong muốn :</b> <?= money($item['muc_luong']) ?> vnđ/tháng</p>
                                        <p><b>Nguyện vọng :</b> <?= $item['nguyen_vong'] ?></p>
                                        <p><b>Dịch vụ làm việc :</b> <?= $next['ten_dich_vu'] ?></p>
                                        <a onclick="return confirm('Thông tin cá nhân của bạn sẽ được gửi đến người giúp việc ! Bạn chắc chắn muốn thuê chứ ?')" href="<?= BASE_URL ?>site/nhan_vien/thong_bao.php?id=<?= $item['id'] ?>" class="btn btn-success">Thuê ngay</a>
                                    </div>
                        <?php }
                            }
                        } ?>
                    </div>
                    <br><br>
                    <div class="row">
                        <div class="col-md-12">
                            <?php if (isset($_SESSION['admin']) || isset($_SESSION['user'])) { ?>
                                <?php foreach (selectAll("select * from users where id =$id_user") as $item) { ?>
                                    <img src="<?= BASE_URL . 'content/images_user/' . $item['hinh_anh'] ?>" style="width:50px;height:50px;border-radius:100px;" alt="ảnh người dùng">
                                    <span style="margin-left: 15px;margin-top:-3px;"><?= $item['ho_ten'] ?></span>
                                <?php } ?>
                                <form id="ratingForm" method="post" action="" style="margin-top: -9px;margin-left: 60px;margin-bottom: 60px;">
                                    <fieldset class="rating">
                                        <input type="radio" id="star5" name="rating" value="Rất tốt" /><label for="star5" title="Rất tốt!">5
                                            stars</label>
                                        <input type="radio" id="star4" name="rating" value="Khá tốt" /><label for="star4" title="Khá tốt">4
                                            stars</label>
                                        <input type="radio" id="star3" name="rating" value="Trung bình" /><label for="star3" title="Trung bình">3
                                            stars</label>
                                        <input type="radio" id="star2" name="rating" value="Tệ" /><label for="star2" title="Tệ">2 stars</label>
                                        <input type="radio" id="star1" name="rating" value="Rất tệ" /><label for="star1" title="Rất tệ">1
                                            star</label>
                                    </fieldset>
                                    <div class="clearfix"></div>
                                    <button name="guisao" class="btn btn-success submit clearfix">Gửi đánh giá</button>
                                </form>
                            <?php } ?>
                        </div>
                        <hr>
                    </div>
                    <h5>Đánh giá mới nhất</h5>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <ul>
                                <?php foreach ($bien as $item) { ?>
                                    <li class="ds">
                                        <img src="<?php echo BASE_URL . "content/images_user/" . $item['hinh_anh'] ?>" style="width:50px;margin-top: 30px; height:50px ; border-radius:50px;" alt="ảnh người từng đánh giá">
                                        <span><?= $item['ho_ten'] ?></span>
                                        <form id="ratingForm" style="margin-left: 58px;margin-top: -35px;">
                                            <fieldset class="rating">
                                                <input type="radio" id="star5" name="rating" <?php if ($item['danh_gia'] == "Rất tốt") {
                                                                                                    echo "checked";
                                                                                                } ?> value="Rất tốt" /><label for="star5" title="Rất tốt!">5
                                                    stars</label>
                                                <input type="radio" id="star4" name="rating" <?php if ($item['danh_gia'] == "Khá tốt") {
                                                                                                    echo "checked";
                                                                                                } ?> value="Khá tốt" /><label for="star4" title="Khá tốt">4
                                                    stars</label>
                                                <input type="radio" id="star3" name="rating" <?php if ($item['danh_gia'] == "Trung bình") {
                                                                                                    echo "checked";
                                                                                                } ?> value="Trung bình" /><label for="star3" title="Trung bình">3
                                                    stars</label>
                                                <input type="radio" id="star2" name="rating" <?php if ($item['danh_gia'] == "Tệ") {
                                                                                                    echo "checked";
                                                                                                } ?> value="Tệ" /><label for="star2" title="Tệ">2 stars</label>
                                                <input type="radio" id="star1" name="rating" <?php if ($item['danh_gia'] == "Rất tệ") {
                                                                                                    echo "checked";
                                                                                                } ?> value="Rất tệ" /><label for="star1" title="Rất tệ">1
                                                    star</label>
                                            </fieldset>
                                        </form>
                                        <br><span style="margin-left: -122px;margin-top: 4px;"><?php echo $item['danh_gia'] ?></span><br>
                                        <?php if (!isset($_SESSION['admin']) && !isset($_SESSION['user'])) { ?>
                                            <i class="fas fa-thumbs-up mauxanh" style="margin-left: 60px;"></i> <?= "   " . $item['so_like'] ?>
                                        <?php } ?>
                                        <?php if (isset($_SESSION['admin'])) { ?>

                                            <a href="<?= BASE_URL . "site/nhan_vien/chi_tiet_nhan_vien.php?like=" . $item['id'] . "&id=$id&id_dv=$id_dv" ?>" style="color:gray;margin-left: 60px;" class="mauxanh">
                                                <i class="fas fa-thumbs-up mauxanh "></i> <?= "   " . $item['so_like'] ?>
                                            </a>
                                            <a class="mauxanh" onclick="return confirm('Bạn có muồn xóa bình luận này không ?')" href="<?= BASE_URL . "site/nhan_vien/chi_tiet_nhan_vien.php?xoa=" . $item['id'] . "&id=$id&id_dv=$id_dv" ?>" style="margin-left:15px;color:gray;">
                                                <i class="fas fa-trash-alt mauxanh"></i>
                                            </a>
                                            <a class="mauxanh" href="" style="margin-left:15px;color:gray;">
                                                <i class="fas fa-edit mauxanh"></i>
                                            </a>
                                        <?php } ?>
                                        <?php if (isset($_SESSION['user'])) { ?>

                                            <a href="<?= BASE_URL . "site/nhan_vien/chi_tiet_nhan_vien.php?like=" . $item['id'] . "&id=$id&id_dv=$id_dv" ?>" style="color:gray;margin-left: 60px;" class="mauxanh">
                                                <i class="fas fa-thumbs-up mauxanh "></i> <?= "   " . $item['so_like'] ?>
                                            </a>
                                            <?php if (($_SESSION['user']['id']) == $item['id_user']) { ?>
                                                <a class="mauxanh" onclick="return confirm('Bạn có muồn xóa bình luận này không ?')" href="<?= BASE_URL . "site/nhan_vien/chi_tiet_nhan_vien.php?xoa=" . $item['id'] . "&id=$id&id_dv=$id_dv" ?>" style="margin-left:15px;color:gray;">
                                                    <i class="fas fa-trash-alt mauxanh"></i>
                                                </a>
                                            <?php } ?>
                                            <a class="mauxanh" href="" style="margin-left:15px;color:gray;">
                                                <i class="fas fa-edit mauxanh"></i>
                                            </a>
                                        <?php } ?>
                                    </li>

                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                    <div class="row">
                        <h5 style="margin-left: 15px">Nhân Viên Cùng Dịch Vụ</h5>
                    </div>
                    <hr>
                    <div class="row">
                        <?php foreach (selectAll("SELECT * FROM nhan_vien WHERE trang_thai = 0 AND id_dichvu = $id_dv AND id != $id ORDER BY id DESC LIMIT 6") as $item) { ?>
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