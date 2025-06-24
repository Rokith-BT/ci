<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#424242" />
    <title>Smart Hospital : Hospital Management System</title>
    <link href="<?php echo base_url(); ?>backend/images/s-favican.png" rel="shortcut icon" type="image/x-icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,100,300,500">
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/usertemplate/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/usertemplate/assets/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/usertemplate/assets/css/form-elements.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/usertemplate/assets/css/style.css">
    <style type="text/css">
        .bgwhite{background:#e4e5e7;box-shadow:0px 2px 10px rgba(0,0,0,0.5);overflow:auto;border-radius:6px;}
        .llpb20{padding-bottom:20px;}
        .around40{padding:40px;}
        .formbottom2{text-align:left;border:1px solid #e4e4e4;}
        button.btn:hover{opacity:100!important;color:#fff;background:#424242;}
        .form-top2{text-align:left;}
        .img2{width:100%}
        .spacingmb30{margin-bottom:30px;}
        .borderR{border-right:1px solid rgba(66,66,66,0.16);padding:0px 40px;}
        input[type="text"],input[type="password"],textarea,textarea.form-control{height:40px;border:1px solid #999;}
        input[type="text"]:focus,input[type="password"]:focus,textarea:focus,textarea.form-control:focus{border:1px solid #424242;}
        button.btn{height:40px;line-height:40px;}
        .ispace{padding-right:5px;}
        .form-top{background:#39f;box-shadow:0px 7px 12px rgba(0,0,0,0.29);border-bottom:1px solid rgba(255,255,255,0.19);}
        .form-bottom{background:rgba(0,0,0,0.50);box-shadow:0px 7px 12px rgba(0,0,0,0.29);}
        .font-white{color:#fff;}
        .form-bottom{padding:25px 25px 15px 25px;}
        button.btn{margin:0;padding:0 20px;vertical-align:middle;background:#ff9800;border:0;font-family:'Roboto',sans-serif;font-size:16px;font-weight:400;color:#fff;border-radius:4px;text-shadow:none;box-shadow:none;transition:all .3s;}
        button.btn:hover{background:#fbc02d;}
        a.forgot{padding-top:15px;color:#b0de37;}
        a:hover.forgot{color:#fff;text-decoration:underline;}
    </style>
</head>
<body>
<div class="top-content">
    <div class="inner-bg">
        <div class="container">
            <div class="row">
                <div class="col-sm-8 col-sm-offset-2 text">
                    <?php
                    $logoresult = $this->customlib->getLogoImage();
                    $userdata = $this->session->userdata('hospitaladmin');
                    $accessToken = $userdata['accessToken'] ?? '';
                    $logo_image = base_url() . "uploads/staff_images/no_image.png";
                    if (!empty($logoresult["image"])) {
                        $response = file_get_contents("https://phr-api.plenome.com/file_upload/getDocs", false, stream_context_create([
                            "http" => [
                                "method" => "POST",
                                "header" => "Content-Type: application/json\r\nAuthorization: $accessToken",
                                "content" => json_encode(['value' => $logoresult["image"]])
                            ]
                        ]));
                        if ($response && strpos($response, '"NoSuchKey"') === false) {
                            $logo_image = "data:image/png;base64," . trim($response);
                        }
                    }
                    ?>
                    <img src="<?php echo $logo_image; ?>" style="height: 100px; width: 200px;">
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 col-sm-offset-3 form-box">
                    <div class="form-top">
                        <div class="form-top-left">
                            <h3 class="font-white"><?php echo $this->lang->line('reset_password'); ?></h3>
                        </div>
                        <div class="form-top-right">
                            <i class="fa fa-key"></i>
                        </div>
                    </div>
                    <div class="form-bottom">
                        <div class='alert alert-danger' id="error" style="display:none;"></div>
                        <div class='alert alert-success' id="success" style="display:none;"></div>
                        <form method="post" id="resetPasswordForm" class="login-form">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="form-group">
                                <label class="sr-only"><?php echo $this->lang->line('password'); ?></label>
                                <input type="password" name="password" placeholder="<?php echo $this->lang->line('password'); ?>" class="form-password form-control" id="form-password">
                                <span class="text-danger"><?php echo form_error('password'); ?></span>
                            </div>
                            <div class="form-group">
                                <label class="sr-only"><?php echo $this->lang->line('confirm_password'); ?></label>
                                <input type="password" name="confirm_password" placeholder="<?php echo $this->lang->line('confirm_password'); ?>" class="form-control" id="form-confirm_password">
                                <span class="text-danger"><?php echo form_error('confirm_password'); ?></span>
                            </div>
                            <button type="submit" class="btn"><?php echo $this->lang->line('submit'); ?></button>
                        </form>
                        <a href="<?php echo site_url('site/login') ?>" class="forgot"><i class="fa fa-key"></i> <?php echo $this->lang->line('admin_login'); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url(); ?>backend/usertemplate/assets/js/jquery-1.11.1.min.js"></script>
<script src="<?php echo base_url(); ?>backend/usertemplate/assets/bootstrap/js/bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>backend/usertemplate/assets/js/jquery.backstretch.min.js"></script>
<?php
$data = $this->session->userdata('hospitaladmin');
$api_base_url = $this->config->item('api_base_url');
?>
<script>
    $(document).ready(function () {
        var base_url = '<?php echo base_url(); ?>';
        $.backstretch([base_url + "backend/usertemplate/assets/img/backgrounds/11.jpg"], {duration: 3000, fade: 750});
        $('.login-form input[type="text"], .login-form input[type="password"], .login-form textarea').on('focus', function () {
            $(this).removeClass('input-error');
        });
        $('.login-form').on('submit', function (e) {
            $(this).find('input[type="text"], input[type="password"], textarea').each(function () {
                if ($(this).val() == "") {
                    e.preventDefault();
                    $(this).addClass('input-error');
                } else {
                    $(this).removeClass('input-error');
                }
            });
        });
        $("#resetPasswordForm").on("submit", function (e) {
            e.preventDefault();
            $("#error").hide().text("");
            $("#success").hide().text("");
            var password = $("#form-password").val();
            var confirmPassword = $("#form-confirm_password").val();
            var passwordPattern = /^(?=.*[A-Z])(?=.*\d)[A-Za-z\d@$!%*?&]{8,}$/;
            if (password !== confirmPassword) {
                $("#error").text("Passwords do not match!").show();
                return;
            }
            if (!passwordPattern.test(password)) {
                $("#error").text("Password must be at least 8 characters, include one uppercase letter, and one number.").show();
                return;
            }
            var formData = {
                "username": "<?php echo $data['email']; ?>",
                "Password": password,
                "confirm_password": confirmPassword
            };
            $.ajax({
                url: "<?php echo $api_base_url; ?>emr-new-login/resetPassword",
                type: "POST",
                data: JSON.stringify(formData),
                dataType: "json",
                success: function (response) {
                    if (response.status === 'success') {
                        $("#success").text(response.message).show();
                        setTimeout(() => {
                            window.location.href = "<?php echo site_url('site/login'); ?>";
                        }, 2000);
                    } else {
                        $("#error").text(response.message || "Something went wrong.").show();
                    }
                },
                error: function () {
                    $("#error").text("An error occurred. Please try again.").show();
                }
            });
        });
    });
</script>
</body>
</html>
