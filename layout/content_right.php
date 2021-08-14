<div class="col-md-12">
    <a href="<?= BASE_URL ?>site/nhan_vien/dang_tin_tim_viec.php">
        <h3 class="titletimviec" style=" background-color: #009900;
    height: 40px;
    padding: 0 5px;
    margin-right: 5px;
    border-radius: 6px;
    margin-bottom: 10px;
    line-height: 40px;
    color: white;
    font-size: 13px;
    font-weight: 600;
    text-transform: uppercase;">Đăng tin tìm việc <i class="fas fa-arrow-circle-right" style="margin-left:65px;font-size:20px;"></i></h3>
    </a>
    <a href="<?= BASE_URL ?>site/tuyen_dung/dang_tin_tuyen_dung.php">
        <h3 class="titletimviec" style="  background-color: #009900;

    height: 40px;
    padding: 0 5px;
    margin-right: 5px;
    border-radius: 6px;
    margin-bottom: 10px;
    line-height: 40px;color:white;
    font-size: 13px;
    font-weight: 600;
    text-transform: uppercase;">Đăng tin tuyển dụng <i class="fas fa-arrow-circle-right" style="margin-left:36px;font-size:20px;"></i></h3>
    </a>
    <h2 style="background: #009900;
    font-size: 9pt;
    font-weight: 700;
    color: #fff;
    border: none;
    margin: 0;
    padding: 10px;
    text-transform: uppercase;
    border-radius: 6px 6px 0 0;">Dành cho nhà tuyển dụng</h2>

    <ul style="list-style:none;margin-left:-40px;">
        <?php foreach (selectAll("select * from dich_vu") as $item) { ?>
            <li style="padding:5px 0px;border-bottom: 1px solid #d6bfbf;">
                <a href="<?= BASE_URL ?>site/dich_vu/nhan_vien.php?id=<?= $item['id'] ?>" style="color:#147f3b;padding: 0px 8px;"><?= $item['ten_dich_vu'] ?></a></li>
        <?php } ?>
    </ul>
    <h2 style="background: #009900;
    font-size: 9pt;
    font-weight: 700;
    color: #fff;
    border: none;
    margin: 0;
    padding: 10px;
    text-transform: uppercase;
    border-radius: 6px 6px 0 0;">Dành cho người tìm việc</h2>
    <ul style="list-style:none;margin-left:-40px;">
        <?php foreach (selectAll("select * from dich_vu") as $item) { ?>
            <li style="padding:5px 0px;border-bottom: 1px solid #d6bfbf;">
                <a href="<?= BASE_URL ?>site/dich_vu/tuyen_dung.php?id=<?= $item['id'] ?>" style="color:#147f3b;padding: 0px 8px;"><?= $item['ten_dich_vu'] ?></a></li>
        <?php } ?>
    </ul>

</div>