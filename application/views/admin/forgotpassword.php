<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#424242" />
    <title><?php echo $title_name ?? "Hospital Name Title"; ?></title>
    <link href="<?php echo base_url(); ?>backend/images/s-favican.png" rel="shortcut icon" type="image/x-icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,100,300,500">
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/usertemplate/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/usertemplate/assets/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/usertemplate/assets/css/form-elements.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/usertemplate/assets/css/style.css">
    <style type="text/css">
        .bgwhite {
            background: #e4e5e7;
            box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.5);
            overflow: auto;
            border-radius: 6px;
        }

        .llpb20 {
            padding-bottom: 20px;
        }

        .around40 {
            padding: 40px;
        }

        .formbottom2 {
            text-align: left;
            border: 1px solid #e4e4e4;
        }

        button.btn:hover {
            opacity: 100 !important;
            color: #fff;
            background: #424242;
        }

        .form-top2 {
            text-align: left;
        }

        .img2 {
            width: 100%;
        }

        .spacingmb30 {
            margin-bottom: 30px;
        }

        .borderR {
            border-right: 1px solid rgba(66, 66, 66, 0.16);
            padding: 0px 40px;
        }

        input[type="text"],
        input[type="password"],
        textarea,
        textarea.form-control {
            height: 40px;
            border: 1px solid #999;
        }

        input[type="text"]:focus,
        input[type="password"]:focus,
        textarea:focus,
        textarea.form-control:focus {
            border: 1px solid #424242;
        }

        button.btn {
            height: 40px;
            line-height: 40px;
            margin: 0;
            padding: 0 20px;
            vertical-align: middle;
            background: #ff9800;
            border: 0;
            font-family: 'Roboto', sans-serif;
            font-size: 16px;
            font-weight: 400;
            color: #fff;
            border-radius: 4px;
            transition: all .3s;
        }

        .form-top {
            background: #39f;
            box-shadow: 0px 7px 12px rgba(0, 0, 0, 0.29);
            border-bottom: 1px solid rgba(255, 255, 255, 0.19);
        }

        .form-bottom {
            background: #39f;
            box-shadow: 0px 7px 12px rgba(0, 0, 0, 0.29);
            padding: 25px 25px 15px 25px;
        }

        .font-white {
            color: #fff;
        }

        a.forgot {
            padding-top: 15px;
            color: #b0de37;
        }

        a:hover.forgot {
            color: #fff;
            text-decoration: underline;
        }

        .text-danger {
            font-size: 13px;
        }

        .text-danger p {
            margin-bottom: 0;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="top-content">
        <div class="inner-bg">
            <div class="container">
                <div class="row">
                    <div class="col-sm-8 col-sm-offset-2 text">
                        <div>
                            <?php
                            $logoresult = $this->customlib->getLogoImage();
                            if ($logoresult["image"]) {
                                $userdata = $this->session->userdata('hospitaladmin');
                                $accessToken = $userdata['accessToken'] ?? '';
                                $url = "https://phr-api.plenome.com/file_upload/getDocs";
                                $client = curl_init($url);
                                curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($client, CURLOPT_POST, true);
                                curl_setopt($client, CURLOPT_POSTFIELDS, json_encode(['value' => $logoresult["image"]]));
                                curl_setopt($client, CURLOPT_HTTPHEADER, [
                                    'Content-Type: application/json',
                                    'Authorization: ' . $accessToken
                                ]);
                                $response = curl_exec($client);
                                curl_close($client);
                                $logo_image = ($response !== false && strpos($response, '"NoSuchKey"') === false) ? "data:image/png;base64," . trim($response) : base_url() . "uploads/staff_images/no_image.png";
                            } else {
                                $logo_image = base_url() . "uploads/staff_images/no_image.png";
                            }
                            ?>
                            <img src="<?php echo $logo_image ?? base_url() . 'uploads/staff_images/no_image.png'; ?>" class="logowidth">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 col-sm-offset-3 form-box">
                        <div class="form-top">
                            <div class="form-top-left">
                                <h3 class="font-white"><?php echo $this->lang->line('forgot_password'); ?></h3>
                            </div>
                            <div class="form-top-right">
                                <i class="fa fa-key"></i>
                            </div>
                        </div>
                        <div class="form-bottom">
                        <div class="alert alert-danger" id="errormessage" style="display: none;"></div>
                            <form id="forgetpassword" method="post">
                                <?php echo $this->customlib->getCSRF(); ?>
                                <div class="form-group">
                                    <input type="text" name="email" placeholder="<?php echo $this->lang->line('email'); ?>" class="form-username form-control" id="form-username">
                                    <span class="text-danger"><?php echo form_error('email'); ?></span>
                                </div>
                                <button type="submit" class="btn"><?php echo $this->lang->line('submit'); ?></button>
                            </form>
                            <a href="<?php echo site_url('site/login'); ?>" class="forgot"><i class="fa fa-key"></i> <?php echo $this->lang->line('admin_login'); ?></a>
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
    $(document).ready(function() {
        const base_url = '<?php echo base_url(); ?>';
        const api_base_url = '<?php echo $api_base_url; ?>';

        $.backstretch([`${base_url}backend/usertemplate/assets/img/backgrounds/11.jpg`], {
            duration: 3000,
            fade: 750
        });

        $('#forgetpassword').on('submit', function(e) {
            e.preventDefault();

            const username = $('#form-username').val();
            const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;

            if (!emailPattern.test(username)) {
                $('#errormessage').text('Please enter a valid email address.').show();
                return;
            }
            const formData = { "username": username };
            $.ajax({
                url: `${api_base_url}emr-new-login/forgotPassword`,
                type: 'POST',
                data: JSON.stringify(formData),
                contentType: 'application/json',
                success: function(response) {
                    if (response?.status === 'success') {
                        window.location.href = "<?php echo site_url('site/login'); ?>?login=forgetpassword";
                    } else {
                        $('#errormessage').text(response?.message || 'An error occurred. Please try again.').show();
                    }
                },
                error: function(xhr) {
                    try {
                        const jsonResponse = JSON.parse(xhr.responseText);
                        $('#errormessage').text(jsonResponse?.message || 'An error occurred. Please try again.').show();
                    } catch {
                        $('#errormessage').text('An error occurred. Unable to parse the response.').show();
                    }
                }
            });
        });
    });
</script>

</body>

</html>