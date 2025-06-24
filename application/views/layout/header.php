<?php
$data = $this->session->userdata('hospitaladmin');
$api_base_url = $this->config->item('api_base_url');
$api_base_url_casesheet = $this->config->item('s3key');
$hospitalname = $this->customlib->gethospitalname();
$hospital_logo = isset($hospitalname[0]['mini_logo']) ? $hospitalname[0]['mini_logo'] : '';
$apiEndpoint = $api_base_url_casesheet . 'upload-doc-previw/get-signed-url';
$requestPayload = json_encode(["value" => $hospital_logo]);
$accessToken = isset($data['accessToken']) ? $data['accessToken'] : '';
$requestOptions = [
    'http' => [
        'header'  => "Content-Type: application/json\r\n" .
            "Authorization: Bearer " . $accessToken . "\r\n",
        'method'  => 'POST',
        'content' => $requestPayload,
        'ignore_errors' => true
    ]
];
$httpContext = stream_context_create($requestOptions);
$apiResponse = @file_get_contents($apiEndpoint, false, $httpContext);
if ($apiResponse === FALSE) {
    error_log("Failed to fetch the signed URL. API endpoint might be incorrect or unavailable.");
    $imageSignedUrl = '';
} else {
    $imageSignedUrl = json_decode($apiResponse, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log("Failed to decode API response: " . json_last_error_msg());
        $imageSignedUrl = '';
    }
}
$currency_symbol = $this->customlib->getHospitalCurrencyFormat();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title> <?php echo $hospitalname[0]['name']; ?></title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="theme-color" content="#5190fd" />
    <link href="<?= isset($imageSignedUrl['imageURL']) ? $imageSignedUrl['imageURL'] : ""  ?>" rel="shortcut icon"
        type="image/x-icon">
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/style-main.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/jquery.mCustomScrollbar.min.css">
    <script src="<?php echo base_url(); ?>backend/js/pagenation.js"></script>
    <script src="<?php echo base_url(); ?>backend/js/tour.js"></script>
    <style>
        #pageloader {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 9999;
            background: rgba(248, 251, 255, 0.96);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            border: 1px solid rgba(59, 130, 246, 0.1);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Inter', sans-serif;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
        }

        #pageloader::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at center, rgba(59, 130, 246, 0.05) 0%, transparent 70%);
        }

        .pageloader-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            color: #1e40af;
        }

        .pageloader-spinner {
            width: 60px;
            height: 60px;
            margin: 0 auto 24px;
            position: relative;
        }

        .pageloader-ring {
            width: 100%;
            height: 100%;
            border: 2px solid rgba(59, 130, 246, 0.2);
            border-radius: 50%;
            animation: hospitalPulse 2s ease-in-out infinite;
            position: absolute;
        }

        .pageloader-cross {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 32px;
            height: 32px;
            transform: translate(-50%, -50%);
        }

        .pageloader-bar-vertical,
        .pageloader-bar-horizontal {
            position: absolute;
            background: linear-gradient(45deg, #3b82f6, #1d4ed8);
            border-radius: 3px;
            animation: crossGlow 1.5s ease-in-out infinite alternate;
        }

        .pageloader-bar-vertical {
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 6px;
            height: 100%;
        }

        .pageloader-bar-horizontal {
            top: 50%;
            left: 0;
            transform: translateY(-50%);
            width: 100%;
            height: 6px;
        }

        .pageloader-heartbeat {
            width: 80%;
            height: 80%;
            position: absolute;
            top: 10%;
            left: 10%;
            border: 2px solid transparent;
            border-top: 2px solid #10b981;
            border-right: 2px solid #10b981;
            border-radius: 50%;
            animation: medicalSpin 3s linear infinite;
        }

        .pageloader-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
            animation: textPulse 2s ease-in-out infinite;
        }

        .pageloader-subtitle {
            font-size: 13px;
            color: #64748b;
            font-weight: 500;
            animation: textPulse 2s ease-in-out infinite 0.5s;
        }

        .pageloader-dots {
            margin-top: 16px;
            display: flex;
            justify-content: center;
            gap: 6px;
        }

        .pageloader-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            animation: dotBounce 1.4s ease-in-out infinite;
        }

        .pageloader-dot.blue {
            background: #3b82f6;
        }

        .pageloader-dot.green {
            background: #10b981;
            animation-delay: 0.2s;
        }

        .pageloader-dot.purple {
            background: #8b5cf6;
            animation-delay: 0.4s;
        }

        /* Animations */
        @keyframes hospitalPulse {

            0%,
            100% {
                transform: scale(1);
                opacity: 0.8;
            }

            50% {
                transform: scale(1.15);
                opacity: 0.4;
            }
        }

        @keyframes crossGlow {
            0% {
                box-shadow: 0 0 5px rgba(59, 130, 246, 0.3);
                filter: brightness(1);
            }

            100% {
                box-shadow: 0 0 15px rgba(59, 130, 246, 0.6);
                filter: brightness(1.2);
            }
        }

        @keyframes medicalSpin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes textPulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }
        }

        @keyframes dotBounce {

            0%,
            80%,
            100% {
                transform: scale(0.8);
                opacity: 0.5;
            }

            40% {
                transform: scale(1.2);
                opacity: 1;
            }
        }
    </style>
    <style>
        #ajaxlist {
            width: 100%;
            border-collapse: collapse;
        }

        #ajaxlist th,
        #ajaxlist td {
            text-align: left;
            vertical-align: middle;
            padding: 8px;
            font-size: 14px;
            overflow: hidden;
            max-width: 150px;
        }

        #ajaxlist th:first-child,
        #ajaxlist td:first-child {
            text-align: left;
        }

        #ajaxlist th:last-child,
        #ajaxlist td:last-child {
            text-align: left;
            white-space: nowrap;
        }

        .dataTables_wrapper .btn {
            padding: 4px 8px;
            font-size: 12px;
        }

        .dataTables_wrapper .badge {
            font-size: 11px;
            padding: 4px 8px;
        }

        #ajaxlist tbody tr:hover {
            background-color: #f9f9f9;
        }

        .ipdcharged {
            width: 100%;
            border-collapse: collapse;
        }

        .ipdcharged th,
        .ipdcharged td {
            text-align: left;
            vertical-align: middle;
            padding: 8px;
            font-size: 14px;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 150px;
        }

        .ipdcharged th:first-child,
        .ipdcharged td:first-child {
            text-align: left;
        }

        .dataTables_wrapper .btn {
            padding: 4px 8px;
            font-size: 12px;
        }

        .dataTables_wrapper .badge {
            font-size: 11px;
            padding: 4px 8px;
        }

        .ipdcharged tbody tr:hover {
            background-color: #f9f9f9;
        }

        .ipdcharged th:last-child,
        .ipdcharged td:last-child {
            text-align: left;
            white-space: nowrap;
        }

        #payment-summary-tags {
            margin-bottom: 15px;
        }

        #payment-summary-tags .tag {
            display: inline-block;
            padding: 5px 10px;
            margin-right: 5px;
            margin-bottom: 5px;
            border-radius: 3px;
            background-color: var(--primary-color);
            color: white;
            font-size: 12px;
        }

        :root {
            --primary-color: #3c8dbc;
            --primary-dark: #367fa9;
            --accent-color: #00c0ef;
            --success-color: #00a65a;
            --warning-color: #f39c12;
            --danger-color: #dd4b39;
            --dark-gray: #444;
            --text-muted: #777;
        }
    </style>

    <?php
    $this->load->view('layout/theme');
    ?>
    <?php
    if ($this->customlib->getRTL() == "yes") {
    ?>
        <!-- Bootstrap 3.3.5 RTL -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/rtl/bootstrap-rtl/css/bootstrap-rtl.min.css" />
        <!-- Theme RTL style -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/rtl/dist/css/AdminLTE-rtl.min.css" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/rtl/dist/css/ss-rtlmain.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/rtl/dist/css/skins/_all-skins-rtl.min.css" />
    <?php } ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/all.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/ionicons.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/iCheck/flat/blue.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/morris/morris.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/datepicker/datepicker3.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/colorpicker/bootstrap-colorpicker.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/daterangepicker/daterangepicker-bs3.css">
    <link rel="stylesheet"
        href="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/custom_style.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/datepicker/css/bootstrap-datetimepicker.css">
    <!--file dropify-->
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/dropify.min.css">
    <!--file nprogress-->
    <link href="<?php echo base_url(); ?>backend/dist/css/nprogress.css" rel="stylesheet">
    <!--print table-->
    <link href="<?php echo base_url(); ?>backend/dist/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>backend/dist/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>backend/dist/datatables/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <!--print table mobile support-->
    <link href="<?php echo base_url(); ?>backend/dist/datatables/css/responsive.dataTables.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>backend/dist/datatables/css/rowReorder.dataTables.min.css" rel="stylesheet">

    <script src="<?php echo base_url(); ?>backend/custom/jquery.min.js"></script>
    <script src="<?php echo base_url(); ?>backend/plugins/colorpicker/bootstrap-colorpicker.js"></script>
    <script src="<?php echo base_url(); ?>backend/datepicker/date.js"></script>
    <script src="<?php echo base_url(); ?>backend/dist/js/jquery-ui.min.js"></script>
    <script src="<?php echo base_url(); ?>backend/js/school-custom.js"></script>
    <!-- fullCalendar -->
    <link rel="stylesheet" href="<?php echo base_url() ?>backend/fullcalendar/dist/fullcalendar.min.css">
    <link rel="stylesheet" href="<?php echo base_url() ?>backend/fullcalendar/dist/fullcalendar.print.min.css"
        media="print">
    <link rel="stylesheet" href="<?php echo base_url() ?>backend/plugins/select2/select2.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/0.8.2/css/flag-icon.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>/backend/dist/css/bootstrap-select.min.css">
</head>
<script type="text/javascript">
    var baseurl = "<?php echo base_url(); ?>";
    var chk_validate = "<?php echo $this->config->item('SHLK') ?>";

    function formatDatebydb(dateString) {
        const d = new Date(dateString);
        return `${String(d.getDate()).padStart(2, '0')}-${String(d.getMonth() + 1).padStart(2, '0')}-${d.getFullYear()}`;
    }
    let accesstoken = '<?= $this->session->userdata('hospitaladmin')["accessToken"] ?>';

    function showPageLoader() {
        document.getElementById('pageloader').style.display = 'block';
    }

    function hidePageLoader() {
        document.getElementById('pageloader').style.display = 'none';
    }
</script>

<body class="hold-transition skin-blue fixed sidebar-mini">
    <?php
    if ($this->config->item('SHLK') == "") {
    ?>
        <!-- <div class="topaleart">
                <div class="slidealert">
                    <div class="alert alert-dismissible topaleart-inside"> -->
        <p class="purchasemodal"></p>
        <!-- </div>
                </div>
            </div> -->
    <?php
    }
    ?>
    <script type="text/javascript">
        function collapseSidebar() {
            if (Boolean(sessionStorage.getItem('sidebar-toggle-collapsed'))) {
                sessionStorage.setItem('sidebar-toggle-collapsed', '');
            } else {
                sessionStorage.setItem('sidebar-toggle-collapsed', '1');
            }
        }

        function checksidebar() {
            if (Boolean(sessionStorage.getItem('sidebar-toggle-collapsed'))) {
                var body = document.getElementsByTagName('body')[0];
                body.className = body.className + ' sidebar-collapse';
            }
        }
        checksidebar();

        function capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }
    </script>
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
        curl_setopt($client, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Authorization: ' . $accessToken]);
        $response = curl_exec($client);
        curl_close($client);

        if ($response !== false && strpos($response, '"NoSuchKey"') === false) {
            $logo_image = "data:image/png;base64," . trim($response);
        } else {
            $logo_image = base_url() . "uploads/staff_images/no_image.png";
        }
    } else {
        $logo_image = base_url() . "uploads/staff_images/no_image.png";
    }
    if ($logoresult["mini_logo"]) {
        $userdata = $this->session->userdata('hospitaladmin');
        $accessToken = $userdata['accessToken'] ?? '';
        $url = "https://phr-api.plenome.com/file_upload/getDocs";
        $client = curl_init($url);
        curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($client, CURLOPT_POST, true);
        curl_setopt($client, CURLOPT_POSTFIELDS, json_encode(['value' => $logoresult["mini_logo"]]));
        curl_setopt($client, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Authorization: ' . $accessToken]);
        $response = curl_exec($client);
        curl_close($client);

        if ($response !== false && strpos($response, '"NoSuchKey"') === false) {
            $mini_logo = "data:image/png;base64," . trim($response);
        } else {
            $mini_logo = base_url() . "uploads/staff_images/no_image.png";
        }
    } else {
        $mini_logo = base_url() . "uploads/staff_images/no_image.png";
    }
    ?>
    <div class="wrapper">
        <header class="main-header" id="alert">
            <a href="<?php echo base_url(); ?>admin/admin/dashboard" class="logo">
                <span class="logo-mini">
                    <img width="31" height="19" src="<?php echo $mini_logo ?>"
                        alt="<?php echo $this->customlib->getAppName() ?>" />
                </span>
                <span class="logo-lg">
                    <img src="<?php echo $logo_image ?>" alt="<?php echo $this->customlib->getAppName() ?>" />
                </span>
            </a>
            <nav class="navbar navbar-static-top" role="navigation">
                <a href="#" onclick="collapseSidebar()" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3">
                    <span href="#" class="sidebar-session">
                        <?php echo $this->setting_model->getCurrentHospitalName(); ?>
                    </span>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-9 col-xs-9">
                    <div class="pull-right">
                        <?php if (($this->rbac->hasPrivilege('patient', 'can_view'))) { ?>
                            <form class="navbar-form navbar-left search-form" role="search"
                                action="<?php echo site_url('admin/admin/search'); ?>" method="POST">
                                <?php echo $this->customlib->getCSRF(); ?>
                                <div class="input-group" style="padding-top:3px;">
                                    <input type="text" name="search_text" class="form-control search-form search-form3"
                                        placeholder="<?php echo $this->lang->line('search_by_name'); ?>">
                                    <span class="input-group-btn">
                                        <button type="submit" name="search" id="search-btn"
                                            style="padding: 3px 12px !important;border-radius: 0px 30px 30px 0px; background: #fff;"
                                            class="btn btn-flat"><i class="fa fa-search"></i></button>
                                    </span>
                                </div>
                            </form>
                        <?php } ?>
                        <div class="navbar-custom-menu">
                            <?php if ($this->rbac->hasPrivilege('language_switcher', 'can_view')) {
                            ?>
                                <div class="langdiv"><select class="languageselectpicker"
                                        onchange="set_languages(this.value)" type="text" id="languageSwitcher">

                                        <?php $this->load->view('admin/language/languageSwitcher') ?>

                                    </select></div>
                            <?php
                            } ?>
                            <ul class="nav navbar-nav headertopmenu">
                                <?php
                                if ($this->rbac->hasPrivilege('notification_center', 'can_view')) {
                                    $systemnotifications = $this->notification_model->getCountUnreadNotification();

                                ?>
                                    <!-- <li class="cal15">

                                        <a href="<?php echo base_url() . "admin/systemnotification" ?>">
                                            <i class="fa fa-bell-o"></i>
                                            <?php

                                            echo ($systemnotifications->count > 0) ? "<span class='label label-warning'>" . $systemnotifications->count . "</span>" : "";

                                            ?>
                                        </a>
                                    </li> -->
                                <?php
                                }
                                ?>

                                <?php if ($this->rbac->hasPrivilege('bed_status', 'can_view')) { ?>
                                    <li class="">
                                        <a data-target="modal" href="#" id='beddata'
                                            data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('loading'); ?>"
                                            onclick="getbedstatus()">
                                            <i class="fas fa-bed cal15"></i>
                                            <span class="spanDM"><?php echo $this->lang->line('bed_status'); ?></span>
                                        </a>
                                    </li>
                                <?php }
                                if ($this->module_lib->hasActive('chat')) { ?>
                                    <li class="cal15">
                                        <a data-placement="bottom" data-toggle="tooltip" title=""
                                            href="<?php echo site_url('admin/chat') ?>"
                                            data-original-title="<?php echo $this->lang->line('chat'); ?>"
                                            class="todoicon"><i class="fa fa-whatsapp"></i>
                                            <?php echo chat_couter() > 0 ? "<span class='label label-warning'>" . chat_couter() . "</span>" : "" ?></a>
                                    </li>
                                    <?php
                                }

                                if ($this->module_lib->hasActive('calendar_to_do_list')) {
                                    if ($this->rbac->hasPrivilege('calendar_to_do_list', 'can_view')) {
                                    ?>
                                        <li class="cal15"><a href="<?php echo base_url() ?>admin/calendar/events"
                                                title="<?php echo $this->lang->line('calendar') ?>"><i
                                                    class="fa fa fa-calendar"></i></a></li>
                                <?php
                                    }
                                }
                                ?>
                                <?php
                                if ($this->module_lib->hasActive('calendar_to_do_list')) {
                                    if ($this->rbac->hasPrivilege('calendar_to_do_list', 'can_view')) {
                                ?>
                                        <li class="dropdown">
                                            <a href="#" title="<?php echo $this->lang->line('task') ?>"
                                                class="dropdown-toggle todoicon" data-toggle="dropdown">
                                                <i class="fa fa-check-square-o"></i>
                                                <?php
                                                $userdata = $this->customlib->getUserData();

                                                $count = $this->customlib->countincompleteTask($userdata["id"]);
                                                if ($count > 0) {
                                                ?>

                                                    <span class="todo-indicator"><?php echo $count ?></span>
                                                <?php } ?>
                                            </a>
                                            <ul class="dropdown-menu menuboxshadow widthMo250">

                                                <li class="todoview plr10 ssnoti">
                                                    <?php echo $this->lang->line('today_you_have'); ?> <?php echo $count; ?>
                                                    <?php echo $this->lang->line('pending_task'); ?><a
                                                        href="<?php echo base_url() ?>admin/calendar/events"
                                                        class="pull-right pt0"><?php echo $this->lang->line('view_all'); ?></a>
                                                </li>
                                                <li>
                                                    <ul class="todolist">
                                                        <?php
                                                        $tasklist = $this->customlib->getincompleteTask($userdata["id"]);
                                                        foreach ($tasklist as $key => $value) {
                                                        ?>
                                                            <li>
                                                                <div class="checkbox">
                                                                    <label><input type="checkbox"
                                                                            id="newcheck<?php echo $value["id"] ?>"
                                                                            onclick="markc('<?php echo $value["id"] ?>')"
                                                                            name="eventcheck"
                                                                            value="<?php echo $value["id"]; ?>"><?php echo $value["event_title"] ?></label>
                                                                </div>
                                                            </li>
                                                        <?php } ?>

                                                    </ul>
                                                </li>
                                            </ul>
                                        </li>
                                <?php
                                    }
                                }
                                ?>

                                <?php
                                $file   = "";
                                $result = $this->customlib->getUserData();
                                $image = $result["image"];
                                $role  = $result["user_type"];
                                $id    = $result["id"];
                                if (!empty($image)) {
                                    $file = $image;
                                } else {
                                    $file = "uploads/staff_images/no_image.png";
                                }
                                ?>
                                <li class="dropdown user-menu">
                                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                                        <?php
                                        $image = $result['image'];
                                        $file = !empty($image) ? $result['image'] : "no_image.png";
                                        $child_pic = $file;
                                        $base64Image = '';
                                        if ($child_pic) {
                                            $userdata = $this->session->userdata('hospitaladmin');
                                            $accessToken = $userdata['accessToken'] ?? '';
                                            $url = "https://phr-api.plenome.com/file_upload/getDocs";
                                            $client = curl_init($url);
                                            curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
                                            curl_setopt($client, CURLOPT_POST, true);
                                            curl_setopt($client, CURLOPT_POSTFIELDS, json_encode(['value' => $child_pic]));
                                            curl_setopt($client, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Authorization: ' . $accessToken]);
                                            $response = curl_exec($client);
                                            curl_close($client);

                                            if ($response !== false && strpos($response, '"NoSuchKey"') === false) {
                                                $base64Image = "data:image/png;base64," . trim($response);
                                            } else {
                                                $base64Image = base_url() . "uploads/staff_images/no_image.png";
                                            }
                                        } else {
                                            $base64Image = base_url() . "uploads/staff_images/no_image.png";
                                        }
                                        ?>

                                        <img src="<?php echo htmlspecialchars($base64Image); ?>" class="topuser-image"
                                            alt="User Image">


                                    </a>
                                    <ul class="dropdown-menu dropdown-user menuboxshadow">
                                        <li>
                                            <div class="sstopuser">
                                                <div class="ssuserleft">
                                                    <a href="<?php echo base_url() . "admin/staff/profile/" . $id ?>">
                                                        <img src="<?php echo htmlspecialchars($base64Image); ?>"
                                                            alt="User Image"></a>
                                                </div>

                                                <div class="sstopuser-test">
                                                    <h4 style="text-transform: capitalize;">
                                                        <?php echo $this->customlib->getAdminSessionUserName(); ?></h4>
                                                    <h5><?php echo $role; ?></h5>
                                                </div>
                                                <div class="divider"></div>
                                                <div class="sspass">
                                                    <a href="<?php echo base_url() . "admin/staff/profile/" . base64_encode($id) ?>"
                                                        data-toggle="tooltip" title=""
                                                        data-original-title="<?php echo $this->lang->line('my_profile'); ?>"><i
                                                            class="fa fa-user"></i><?php echo $this->lang->line('profile'); ?></a>
                                                    <a class="pl25"
                                                        href="<?php echo base_url(); ?>admin/admin/changepass"
                                                        data-toggle="tooltip" title=""
                                                        data-original-title="<?php echo $this->lang->line('change_password') ?>"><i
                                                            class="fa fa-key"></i><?php echo $this->lang->line('password'); ?></a>
                                                    <a class="pull-right" href="<?php echo base_url(); ?>site/logout"><i
                                                            class="fa fa-sign-out fa-fw"></i><?php echo $this->lang->line('logout'); ?></a>
                                                </div>
                                            </div>
                                            <!--./sstopuser-->
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
        </header>
        <div class="modal fade" id="AppoitmentviewModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content modal-media-content">
                    <div class="modal-header modal-media-header">
                        <button type="button" class="close" data-toggle="tooltip"
                            data-original-title="<?php echo $this->lang->line('close'); ?>"
                            data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"><?php echo $this->lang->line('appointment_details'); ?></h4>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <tbody>
                                    <tr>
                                        <th><?php echo $this->lang->line('patient_name'); ?></th>
                                        <td><span id='patient_names'></span></td>
                                        <th><?php echo $this->lang->line('appointment_no'); ?></th>
                                        <td><span id="appointmentno"></span></td>
                                        <th id="module_name"></th>
                                        <td colspan="5" id="opd_id_appointent"></td>
                                    </tr>
                                    <tr>
                                        <th><?php echo $this->lang->line('email'); ?></th>
                                        <td><span id='emails'></span></td>
                                        <th><?php echo $this->lang->line('appointment_date'); ?></th>
                                        <td><span id='dating'></span></td>
                                        <th><?php echo $this->lang->line('phone'); ?></th>
                                        <td><span id="phones"></span></td>
                                    </tr>
                                    <tr>
                                        <th><?php echo $this->lang->line('gender'); ?></th>
                                        <td><span id="view_genders"></span></td>
                                        <th><?php echo $this->lang->line('shift'); ?></th>
                                        <td><span id="global_shift_view"></span></td>
                                        <th><?php echo $this->lang->line('doctor'); ?></th>
                                        <td><span id='doctors'></span></td>
                                    </tr>
                                    <tr>
                                        <th><?php echo $this->lang->line('slot'); ?></th>
                                        <td><span id='doctor_shift_view'></span></td>
                                        <th><?php echo $this->lang->line('department'); ?></th>
                                        <td><span id="department_name"></span></td>
                                        <th><?php echo $this->lang->line('appointment_priority'); ?></th>
                                        <td><span id='appointpriority'></span></td>
                                    </tr>
                                    <tr>
                                        <th><?php echo $this->lang->line('fees'); ?></th>
                                        <td><span id='fees'></span></td>
                                        <th>Additional Charges</th>
                                        <td><span id='additional_charges'></span></td>
                                        <th><?php echo $this->lang->line('discount'); ?> (<span
                                                id="discount_percentage"></span>%)</th>
                                        <td><span id='discount'></span></td>
                                    </tr>
                                    <tr>
                                        <th>Sub Total</th>
                                        <td><span id='sub_total'></span></td>
                                        <th><?php echo $this->lang->line('tax'); ?>(<span id="tax_percentage"></span>%)
                                        </th>
                                        <td><span id='tax'></span></td>
                                        <th><?php echo $this->lang->line('net_amount'); ?></th>
                                        <td><span id='net_amount'></span></td>
                                    </tr>
                                    <tr>
                                        <th><?php echo $this->lang->line('paid_amount'); ?></th>
                                        <td><span id='pay_amount'></span></td>
                                        <th><?php echo $this->lang->line('due_amount'); ?></th>
                                        <td><span id='due_amount'></span></td>
                                        <th><?php echo $this->lang->line('payment_mode'); ?></th>
                                        <td><span id="payment_mode"></span></td>
                                    </tr>
                                    <tr>
                                        <th><?php echo $this->lang->line('transaction_id'); ?></th>
                                        <td><span id="trans_id"></span></td>
                                        <th><?php echo $this->lang->line('status'); ?></th>
                                        <td><span id='status'></span></td>
                                        <th><?php echo "Notes"; ?></th>
                                        <td><span id="messages"></span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="pageloader">
            <div class="pageloader-content">
                <div class="pageloader-spinner">
                    <div class="pageloader-ring"></div>
                    <div class="pageloader-cross">
                        <div class="pageloader-bar-vertical"></div>
                        <div class="pageloader-bar-horizontal"></div>
                    </div>
                    <div class="pageloader-heartbeat"></div>
                </div>

                <div class="pageloader-title">Processing Medical Data...</div>
                <div class="pageloader-subtitle">📋 Retrieving records securely</div>

                <div class="pageloader-dots">
                    <div class="pageloader-dot blue"></div>
                    <div class="pageloader-dot green"></div>
                    <div class="pageloader-dot purple"></div>
                </div>
            </div>
        </div>
        <script>
            document.getElementById('pageloader').style.display = 'none';

            function defoult(id) {
                var defoult = $('#languageSwitcher').val();
                $.ajax({
                    type: "POST",
                    url: base_url + "admin/language/defoult_language/" + id,
                    data: {},
                    success: function(data) {
                        successMsg("<?php echo $this->lang->line('status_change_successfully'); ?>");
                        $('#languageSwitcher').html(data);

                    }
                });
                window.location.reload('true');
            }

            function set_languages(lang_id) {
                $.ajax({
                    type: "POST",
                    url: base_url + "admin/language/user_language/" + lang_id,
                    data: {},
                    success: function(data) {
                        successMsg("<?php echo $this->lang->line('status_change_successfully'); ?>");
                        window.location.reload('true');

                    }
                });
            }
        </script>
        <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
        <script>
            function handlePayment(jsonData, printAfterSave = false) {
                return new Promise((resolve) => {
                    $.ajax({
                        url: base_url + 'admin/appointment/getpatientdetail',
                        type: "GET",
                        data: {
                            'patientid': jsonData["patient_id"]
                        },
                        dataType: 'json',
                        success: function(data) {
                            let patientData = {
                                name: data.fullname || "Guest",
                                mobile: data.mobilenumber || "",
                                email: data.email || ""
                            };
                            initiateRazorpay(jsonData, patientData, printAfterSave, resolve);
                        },
                        error: function() {
                            errorMsg(
                                "An error occurred while fetching patient details. Please try again."
                            );
                            resolve(false);
                        }
                    });
                });
            }

            function initiateRazorpay(jsonData, patientData, printAfterSave, resolve) {
                let razorpayKey = "<?= getenv('RAZORPAY_LIVE_KEY') ?: 'rzp_live_E1TEgAPfjZ0OIQ' ?>";
                let options = {
                    "key": razorpayKey,
                    "amount": (Math.max(parseFloat(jsonData["amount"]) * 100, 2)).toFixed(2),
                    "currency": "INR",
                    "name": "<?= htmlspecialchars($data['sch_name']) ?>",
                    "description": "Appointment Fees",
                    "handler": function(response) {
                        jsonData["razorpay_payment_id"] = response.razorpay_payment_id;
                        processPayment(jsonData, printAfterSave, resolve);
                    },
                    "prefill": {
                        "name": patientData.name,
                        "email": patientData.email,
                        "contact": patientData.mobile
                    },
                    "theme": {
                        "color": "#3399cc"
                    }
                };
                let rzp = new Razorpay(options);
                rzp.on('payment.failed', function(response) {
                    errorMsg("Payment failed. Reason: " + (response.error.description || "Unknown error"));
                    resolve(false);
                });

                rzp.open();
            }

            function processPayment(jsonData, printAfterSave, resolve) {
                $.ajax({
                    url: base_url + 'admin/appointment/processPayment',
                    type: "POST",
                    contentType: "application/json",
                    data: JSON.stringify({
                        'paymentId': jsonData["razorpay_payment_id"]
                    }),
                    dataType: 'json',
                    success: function(data) {
                        if (data.status == true) {
                            resolve({
                                status: data.status,
                                payment_id: data.payment_id,
                                reference_id: data.reference_id
                            });
                        } else {
                            errorMsg("Payment verification failed. Please contact support.");
                            resolve(false);
                        }
                    },
                    error: function() {
                        errorMsg("An error occurred while processing payment. Please try again.");
                        resolve(false);
                    }
                });
            }
        </script>
        <script>
            function viewDetail(id) {
                $("#pageloader").fadeIn();
                $("#AppoitmentviewModal").modal('hide');
                $("#field_data").html('<tr><td colspan="2">Loading...</td></tr>');
                function sanitizeValue(value) {
                    if (value === null || value === undefined || value === '' || (typeof value === 'number' && isNaN(
                            value))) {
                        return "-";
                    }
                    return value;
                }

                function fetchAppointmentDetails(id, retries = 3) {
                    $.ajax({
                        url: baseurl + 'admin/appointment/getappoitmentinfo',
                        type: "GET",
                        data: {
                            appointment_id: id
                        },
                        dataType: 'json',
                        success: function(data) {
                            $("#pageloader").fadeOut();
                            if (data.status == false) {
                                errorMsg(data.message);
                                return;
                            }

                            if (!data || typeof data !== "object") {
                                if (retries > 0) setTimeout(() => fetchAppointmentDetails(id, retries - 1),
                                    1000);
                                return;
                            }

                            let table_html = Array.isArray(data.field_data) ? data.field_data
                                .filter(obj => obj.visible_on_patient_panel == 1)
                                .map(obj => `
                                    <tr>
                                        <th width="15%">${sanitizeValue(capitalizeFirstLetter($.trim(obj.name)))}</th>
                                        <td width="85%">${sanitizeValue($.trim(obj.field_value))}</td>
                                    </tr>
                                `).join("") : "";

                            $("#field_data").html(table_html);
                            $("#appointmentno").html(sanitizeValue($.trim(data.id)));
                            $("#appointmentsno").html(sanitizeValue($.trim(data.appointment_serial_no)));
                            $("#dating").html(data.date ? new Date($.trim(data.date)).toLocaleDateString(
                                'en-GB') : "-");
                            $("#patient_names").html(sanitizeValue($.trim(data.patients_name)));
                            $("#view_genders").html(sanitizeValue($.trim(data.patients_gender)));
                            $("#emails").html(sanitizeValue($.trim(data.patient_email)));
                            $("#appointpriority").html(sanitizeValue($.trim(data.appoint_priority)));
                            $("#phones").html(sanitizeValue($.trim(data.patient_mobileno)));
                            $("#doctors").text(sanitizeValue($.trim(data.doctors_name)));
                            $("#department_name").html(sanitizeValue($.trim(data.department_name)));
                            $("#messages").html(sanitizeValue($.trim(data.additional_charge_note) || data
                                .message));
                            $("#liveconsult").html(sanitizeValue($.trim(data.edit_live_consult)));
                            $("#global_shift_view").html(sanitizeValue($.trim(data.global_shift_name)));
                            $("#doctor_shift_view").text(sanitizeValue($.trim(data.doctor_shift_name) || $.trim(
                                data.time)));
                            $("#source").html(sanitizeValue($.trim(data.source)));
                            $("#payment_note").html(sanitizeValue($.trim(data.payment_note)));
                            $("#patient_age").html(sanitizeValue($.trim(data.patient_age)));

                            let sc = parseFloat(data.consultFees) || 0,
                                ac = parseFloat(data.additional_charge) || 0,
                                dc = parseFloat(data.discount_amount) || 0,
                                st = (sc + ac - dc).toFixed(2),
                                ta = parseFloat(data.taxAmount) || 0,
                                na = (parseFloat(st) + ta).toFixed(2),
                                da = (parseFloat(na) - (parseFloat(data.paid_amount) || 0)).toFixed(2);

                            $("#fees").html(`<?php echo $currency_symbol; ?> ${sanitizeValue(sc.toFixed(2))}`);
                            $("#additional_charges").html(
                                `<?php echo $currency_symbol; ?> ${sanitizeValue(ac.toFixed(2))}`);
                            $("#discount").html(
                                `<?php echo $currency_symbol; ?> ${sanitizeValue(dc.toFixed(2))}`);
                            $("#sub_total").html(`<?php echo $currency_symbol; ?> ${sanitizeValue(st)}`);
                            $("#tax").html(`<?php echo $currency_symbol; ?> ${sanitizeValue(ta.toFixed(2))}`);
                            $("#tax_appointment").html(
                                `<?php echo $currency_symbol; ?> ${sanitizeValue(ta.toFixed(2))}`);
                            $("#tax_percentage").html(`${sanitizeValue(data.taxPercentage)}`);
                            $("#discount_percentage").html(sanitizeValue(data.discount_percentage));
                            $("#net_amount").html(`<?php echo $currency_symbol; ?> ${sanitizeValue(na)}`);
                            $("#due_amount").html(`<?php echo $currency_symbol; ?> ${sanitizeValue(da)}`);
                            let links = {
                                OPD: sanitizeValue($.trim(data.redirect_link_opd)),
                                IPD: sanitizeValue($.trim(data.redirect_link_ipd)),
                                APPOINTMENT: sanitizeValue($.trim(data.redirect_link_opd))
                            };
                            let ids = {
                                OPD: "OPD NO",
                                IPD: "IPD NO",
                                APPOINTMENT: "OPD NO"
                            };

                            if (data.module in links) {
                                if (
                                    (data.module == "OPD" || data.module == "APPOINTMENT") &&
                                    (data.appointment_status == "InProcess" || data.appointment_status ==
                                        "Completed")
                                ) {
                                    $("#opd_id_appointent").html(
                                        `<a href="${links[data.module]}">${sanitizeValue($.trim(data.opd_id) || "1")}</a>`
                                    );
                                } else if (data.module == "IPD") {
                                    $("#opd_id_appointent").html(
                                        `<a href="${links[data.module]}">${sanitizeValue($.trim(data.ipd_id) || "2")}</a>`
                                    );
                                } else {
                                    $("#opd_id_appointent").html("-");
                                }
                                $("#module_name").html(ids[data.module]);
                            } else {
                                $("#opd_id, #opd_id_appointent").html("-");
                                $("#module_name").html("-");
                            }

                            if (data.payment_mode === "Cheque") {
                                $("#payrow, #paydocrow").show();
                                $("#spn_chequeno").html(sanitizeValue($.trim(data.cheque_no)));
                                $("#spn_chequedate").html(sanitizeValue($.trim(data.cheque_date)));
                                $("#spn_doc").html(sanitizeValue($.trim(data.doc)));
                            } else {
                                $("#payrow, #paydocrow").hide();
                                $("#spn_chequeno, #spn_chequedate, #spn_doc").html("-");
                            }

                            $("#pay_amount").html(
                                `<?php echo $currency_symbol; ?> ${(parseFloat(data.paid_amount) || 0).toFixed(2)}`
                            );
                            $("#payment_mode").html(sanitizeValue($.trim(data.payment_mode)));
                            $("#trans_id").html(sanitizeValue($.trim(data.transaction_id)));

                            const colors = {
                                "Requested": "#F59E0B",
                                "Reserved": "#FFC52F",
                                "Approved": "#00D65B",
                                "Cancelled": "#FF0600",
                                "InProcess": "#6070FF",
                                "Completed": "#00D65B"
                            };

                            $("#status").html(`
                                <small style="display:inline-block;min-width:80px;padding:4px 8px;border-radius:4px;background-color:${colors[data.appointment_status] || "#000"};color:#fff;font-size:0.8em;text-align:center;">
                                    ${sanitizeValue($.trim(data.appointment_status))}
                                </small>
                            `);
                            $("#edit_delete").html(
                                `<a href="#" onclick="printAppointment(${id})"><i class="fa fa-print"></i></a>`
                            );
                            setTimeout(() => {
                                $("#AppoitmentviewModal").modal({
                                    backdrop: 'static',
                                    keyboard: false
                                }).modal('show');
                            }, 500);
                        },
                        error: function() {
                            if (retries > 0) setTimeout(() => fetchAppointmentDetails(id, retries - 1), 1000);
                            else $("#pageloader").fadeOut();
                        }
                    });
                }
                fetchAppointmentDetails(id);
            }
        </script>
        <!-- setup datatable function --->
        <?php $this->load->view('layout/sidebar'); ?>