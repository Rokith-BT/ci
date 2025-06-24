<style type="text/css">
    {
        margin: 0;
        padding: 0;
    }

    /*       body{ font-family: 'arial'; margin:0; padding: 0;font-size: 12px; color: #000;}*/
    .tc-container {
        width: 100%;
        position: relative;
        text-align: center;
    }

    .tcmybg {
        background: top center;
        background-size: contain;
        position: absolute;
        left: 0;
        bottom: 10px;
        width: 200px;
        height: 200px;
        margin-left: auto;
        margin-right: auto;
        right: 0;
    }

    /*begin Patients id card*/
    .patienttop img {
        width: 30px;
        vertical-align: initial;
    }

    .patienttop {
        background:
            <?php echo $idcard->header_color; ?>
        ;
        padding: 2px;
        color: #fff;
        overflow: hidden;
        position: relative;
        z-index: 1;
    }

    .sttext1 {
        font-size: 24px;
        font-weight: bold;
        line-height: 30px;
    }

    .stgray {
        background: #efefef;
        padding-top: 5px;
        padding-bottom: 10px;
    }

    .staddress {
        margin-bottom: 0;
        padding-top: 2px;
    }

    .stdivider {
        border-bottom: 2px solid #000;
        margin-top: 5px;
        margin-bottom: 5px;
    }

    .stlist {
        padding: 0;
        margin: 0;
        list-style: none;
    }

    .stlist li {
        text-align: left;
        display: inline-block;
        width: 100%;
        padding: 0px 5px;
    }

    .stlist li span {
        width: 65%;
        float: right;
    }

    .stimg {
        /*margin-top: 5px;*/
        width: 80px;
        height: auto;
        /*margin: 0 auto;*/
    }

    .stimg img {
        width: 100%;
        height: auto;
        border-radius: 2px;
        display: block;
    }

    .staround {
        padding: 3px 10px 3px 0;
        position: relative;
        overflow: hidden;
    }

    .staround2 {
        position: relative;
        z-index: 9;
    }

    .stbottom {
        background: #453278;
        height: 20px;
        width: 100%;
        clear: both;
        margin-bottom: 5px;
    }

    /*.stidcard{margin-top: 0px;
                color: #fff;font-size: 16px; line-height: 16px;
                padding: 2px 0 0; position: relative; z-index: 1;
                background: #453277;
                text-transform: uppercase;}*/
    .principal {
        margin-top: -40px;
        margin-right: 10px;
        float: right;
    }

    .stred {
        color: #000;
    }

    .spanlr {
        padding-left: 5px;
        padding-right: 5px;
    }

    .cardleft {
        width: 20%;
        float: left;
    }

    .cardright {
        width: 77%;
        float: right;
    }
</style>
<?php $dummy_date = "2020-01-01"; ?>
<table cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td valign="top" width="32%" style="padding: 3px;">
            <table cellpadding="0" cellspacing="0" width="100%" class="tc-container" style="background: #f0f8fd;">
                <tr>
                    <td valign="top">
                    <?php
                        function fetchImage($value) {
                            $userdata = $this->session->userdata('hospitaladmin');
                            $accessToken = $userdata['accessToken'] ?? '';
                            $url = "https://phr-api.plenome.com/file_upload/getDocs";
                            $client = curl_init($url);
                            curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($client, CURLOPT_POST, true);
                            curl_setopt($client, CURLOPT_POSTFIELDS, json_encode(['value' => $value]));
                            curl_setopt($client, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Authorization: ' . $accessToken]);
                            $response = curl_exec($client);
                            curl_close($client);
                            return $response !== false ? "data:image/png;base64," . trim($response) : "<i class='fa fa-picture-o fa-3x'></i>";
                        }

                        $backgroundImage = fetchImage($idcard->background);
                    ?>
                        <img src="<?=$backgroundImage?>" class="tcmybg" style="opacity: .1" />
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        <div class="patienttop">
                            <div class="sttext1">
                                <?php $logoImage = fetchImage($idcard->logo); ?>
                                <img src="<?=$logoImage?>" />
                                <?=$idcard->hospital_name?>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td valign="top" align="center" style="padding: 1px 0;">
                        <p><?=$idcard->hospital_address?></p>
                    </td>
                </tr>
                <tr>
                    <td valign="top"
                        style="color: #fff;font-size: 16px; padding: 2px 0 0; position: relative; z-index: 1;background: <?=$idcard->header_color?>;text-transform: uppercase;">
                        <?=$idcard->title?>
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        <div class="staround">
                            <div class="cardleft">
                                <div class="stimg">
                                    <img src="<?=base_url('uploads/patient_images/no_image.png' . img_time())?>" class="img-responsive" />
                                </div>
                            </div>
                            <div class="cardright">
                                <ul class="stlist">
                                    <?php if ($idcard->enable_name) echo "<li>{$this->lang->line('staff_name')}<span>Mohan Patil</span></li>"; ?>
                                    <?php if ($idcard->enable_staff_id) echo "<li>{$this->lang->line('staff_id')}<span>9000</span></li>"; ?>
                                    <?php if ($idcard->enable_designation) echo "<li>{$this->lang->line('designation')}<span>Administrator</span></li>"; ?>
                                    <?php if ($idcard->enable_staff_department) echo "<li>{$this->lang->line('department')}<span>Admin</span></li>"; ?>
                                    <?php if ($idcard->enable_fathers_name) echo "<li>{$this->lang->line('father_name')}<span>Sohan Patil</span></li>"; ?>
                                    <?php if ($idcard->enable_mothers_name) echo "<li>{$this->lang->line('mother_name')}<span>Kirti Patil</span></li>"; ?>
                                    <?php if ($idcard->enable_date_of_joining) echo "<li>{$this->lang->line('date_of_joining')}<span>{$this->customlib->YYYYMMDDTodateFormat($dummy_date)}</span></li>"; ?>
                                    <?php if ($idcard->enable_permanent_address) echo "<li>{$this->lang->line('address')}<span>Near Railway Station Jabalpur</span></li>"; ?>
                                    <?php if ($idcard->enable_staff_phone) echo "<li>{$this->lang->line('phone')}<span>9845624781</span></li>"; ?>
                                    <?php if ($idcard->enable_staff_dob) echo "<li>{$this->lang->line('date_of_birth')}<span>{$this->customlib->YYYYMMDDTodateFormat($dummy_date)}</span></li>"; ?>
                                </ul>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td valign="top" align="right" class="principal">
                        <?php $signImage = fetchImage($idcard->sign_image); ?>
                        <img src="<?=$signImage?>" width="66" height="40" />
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

