<?php
session_start();
require_once('../../dao/pdo.php');
if (isset($_POST['add_tin_tuc'])) {
    $err_tieu_de = $err_noi_dung = $error_hinh_anh = $err_tom_tat = "";
    if (isset($_SESSION['admin'])) {
        $id_user = $_SESSION['admin']['id'];
    }
    if (empty($_POST['tieu_de'])) {
        $err_tieu_de = "Vui lòng nhập tiêu đề !";
    } else {
        $tieu_de = $_POST['tieu_de'];
    }

    if (empty($_POST['noi_dung'])) {
        $err_noi_dung = "Vui lòng nhập nội dung !";
    } else {
        $noi_dung = $_POST['noi_dung'];
    }

    if (empty($_POST['tom_tat'])) {
        $err_tom_tat = "Vui lòng nhập nội dung !";
    } else {
        $tom_tat = $_POST['tom_tat'];
    }

    $thoi_gian = date("Y/m/d");
    $luot_xem = 1;

    if (!file_exists($_FILES['hinh_anh']["tmp_name"])) {
        $error_hinh_anh = "Vui lòng tải ảnh !";
    } else {
        $img = $_FILES['hinh_anh'];
        $maxSize = 1500000;
        $upload = true;
        $dir = "../../content/images_tin_tuc/";
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
            }
        }
    }

    if ($err_tieu_de . $err_noi_dung . $error_hinh_anh . $err_tom_tat == "") {
        insert_delete_update("INSERT INTO `tin_tuc`(`tieu_de`, `noi_dung`,`thoi_gian`, `hinh_anh`, `luot_xem`, `tom_tat`, `id_user`) VALUES ('$tieu_de','$noi_dung','$thoi_gian',
        '$imgname', $luot_xem, '$tom_tat', $id_user)");
        header("Location:index.php?msg=Thêm tin tức thành công !");
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
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>

    <style>
        figure.image {
            display: inline-block;
border: 1px solid gray;
            margin: 0 2px 0 1px;
            background: #f5f2f0;
        }

        figure.align-left {
            float: left;
        }

        figure.align-right {
            float: right;
        }

        figure.image img {
            margin: 8px 8px 0 8px;
        }

        figure.image figcaption {
            margin: 6px 8px 6px 8px;
            text-align: center;
        }


        /*
 Alignment using classes rather than inline styles
 check out the "formats" option
*/

        img.align-left {
            float: left;
        }

        img.align-right {
            float: right;
        }

        /* Basic styles for Table of Contents plugin (toc) */
        .mce-toc {
            border: 1px solid gray;
        }

        .mce-toc h2 {
            margin: 4px;
        }

        .mce-toc li {
            list-style-type: none;
        }

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
                            <h5>THÊM MỚI TIN TỨC</h5>
                        </div>
                        <br>
                        <form action="" method="post" enctype="multipart/form-data" class="form_user">
                            <div class="row">
                                <div class="col-md-12" style="padding:0 25px 0 25px;">
                                    <div class="form-group">
                                        <label for="">Tiêu đề *</label>
                                        <input type="text" class="form-control" name="tieu_de" placeholder="Tiêu đề" style="background-color: #fdf7f7;">
                                        <?php if (isset($err_tieu_de)) { ?>
                                            <span style="color:red;">
<?php echo $err_tieu_de . "<br>" ?></span>
                                        <?php } ?>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Nội dung *</label>
                                        <textarea id="full-featured-non-premium" name="noi_dung" class="form-control"></textarea>
                                        <?php if (isset($err_noi_dung)) { ?>
                                            <span style="color:red;">
                                                <?php echo $err_noi_dung . "<br>" ?></span>
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
                                    <div class="form-group">
                                        <label for="">Tóm tắt *</label>
                                        <textarea class="form-control" name="tom_tat" placeholder="Tóm tắt" style="background-color: #fdf7f7;"></textarea>
                                        <?php if (isset($err_tom_tat)) { ?>
                                            <span style="color:red;">
                                                <?php echo $err_tom_tat . "<br>" ?></span>
                                        <?php } ?>
                                    </div>
                                    <button type="submit" class="btn btn-secondary" name="add_tin_tuc">Thêm</button>
                                    <a href="index.php" type="submit" class="btn btn-danger">Quay lại</a>
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
    <script>
        var useDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;

        tinymce.init({
            selector: 'textarea#full-featured-non-premium',
            plugins: 'print preview paste importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons',
            imagetools_cors_hosts: ['picsum.photos'],
            menubar: 'file edit view insert format tools table help',
            toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | ltr rtl',
            toolbar_sticky: true,
            autosave_ask_before_unload: true,
            autosave_interval: '30s',
            autosave_prefix: '{path}{query}-{id}-',
            autosave_restore_when_empty: false,
            autosave_retention: '2m',
            image_advtab: true,
            link_list: [{
                    title: 'My page 1',
                    value: 'https://www.tiny.cloud'
                },
                {
                    title: 'My page 2',
                    value: 'http://www.moxiecode.com'
                }
            ],
            image_list: [{
                    title: 'My page 1',
                    value: 'https://www.tiny.cloud'
                },
                {
                    title: 'My page 2',
                    value: 'http://www.moxiecode.com'
                }
            ],
            image_class_list: [{
                    title: 'None',
                    value: ''
                },
                {
                    title: 'Some class',
                    value: 'class-name'
                }
            ],
            importcss_append: true,
            file_picker_callback: function(callback, value, meta) {
                /* Provide file and text for the link dialog */
                if (meta.filetype === 'file') {
                    callback('https://www.google.com/logos/google.jpg', {
                        text: 'My text'
                    });
                }
/* Provide image and alt text for the image dialog */
                if (meta.filetype === 'image') {
                    callback('https://www.google.com/logos/google.jpg', {
                        alt: 'My alt text'
                    });
                }

                /* Provide alternative source and posted for the media dialog */
                if (meta.filetype === 'media') {
                    callback('movie.mp4', {
                        source2: 'alt.ogg',
                        poster: 'https://www.google.com/logos/google.jpg'
                    });
                }
            },
            templates: [{
                    title: 'New Table',
                    description: 'creates a new table',
                    content: '<div class="mceTmpl"><table width="98%%"  border="0" cellspacing="0" cellpadding="0"><tr><th scope="col"> </th><th scope="col"> </th></tr><tr><td> </td><td> </td></tr></table></div>'
                },
                {
                    title: 'Starting my story',
                    description: 'A cure for writers block',
                    content: 'Once upon a time...'
                },
                {
                    title: 'New list with dates',
                    description: 'New List with dates',
                    content: '<div class="mceTmpl"><span class="cdate">cdate</span><br /><span class="mdate">mdate</span><h2>My List</h2><ul><li></li><li></li></ul></div>'
                }
            ],
            template_cdate_format: '[Date Created (CDATE): %m/%d/%Y : %H:%M:%S]',
            template_mdate_format: '[Date Modified (MDATE): %m/%d/%Y : %H:%M:%S]',
            height: 600,
            image_caption: true,
            quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
            noneditable_noneditable_class: 'mceNonEditable',
            toolbar_mode: 'sliding',
            contextmenu: 'link image imagetools table',
            skin: useDarkMode ? 'oxide-dark' : 'oxide',
            content_css: useDarkMode ? 'dark' : 'default',
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
        });
    </script>
</body>

</html>