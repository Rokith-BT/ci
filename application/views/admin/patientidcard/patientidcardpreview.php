<style type="text/css">
    { margin:0; padding: 0;}
    .tc-container{width: 100%;position: relative; text-align: center;}
    .tcmybg {
        background: top center;
        background-size: contain;
        position: absolute;
        left: 0;
        bottom: 10px;
        width: 160px;
        height: 160px;
        margin-left: auto;
        margin-right: auto;
        right: 0;
    }
    .patientmain{background: #efefef;width: 100%; margin-bottom: 30px;}
    .patienttop img{height:30px;vertical-align: initial;}
    .patienttop{background: <?php echo $idcard->header_color; ?>;padding:2px;color: #fff;overflow: hidden; position: relative;z-index: 1;}
    .sttext1{font-size: 24px;font-weight: bold;line-height: 30px;}
    .stgray{background: #efefef;padding-top: 5px; padding-bottom: 10px;}
    .staddress{margin-bottom: 0; padding-top: 2px;}
    .stdivider{border-bottom: 2px solid #000;margin-top: 5px; margin-bottom: 5px;}
    .stlist{padding: 0; margin:0; list-style: none;}
    .stlist li{text-align: left;display: inline-block;width: 100%;padding: 0px 5px;}
    .stlist li span{width:65%;float: right;}
    .stimg{width: 80px;height: auto;}
    .stimg img{width: 100%;height: auto;border-radius: 2px;display: block;}
    .staround{padding:3px 10px 3px 0;position: relative;overflow: hidden;}
    .staround2{position: relative; z-index: 9;}
    .stbottom{background: #453278;height: 20px;width: 100%;clear: both;margin-bottom: 5px;}
    .principal{margin-top: -40px;margin-right:10px; float:right;}
    .stred{color: #000;}
    .spanlr{padding-left: 5px; padding-right: 5px;}
    .cardleft{width: 20%;float: left;}
    .cardright{width: 77%;float: right; }
</style>
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
                            curl_setopt($client, CURLOPT_HTTPHEADER, [
                                'Content-Type: application/json',
                                'Authorization: ' . $accessToken
                            ]);
                            $response = curl_exec($client);
                            if (curl_errno($client)) {
                                error_log('Curl error: ' . curl_error($client));
                            }
                            curl_close($client);
                            return $response !== false 
                                ? "data:image/png;base64," . trim($response) 
                                : "<i class='fa fa-picture-o fa-3x'></i>";
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
                                <?php
                                $logoImage = fetchImage($idcard->logo);
                                ?>
                                <img src="<?=$logoImage?>" />
                                <?php echo $idcard->hospital_name; ?>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td valign="top" align="center" style="padding: 1px 0;">
                        <p><?php echo $idcard->hospital_address; ?></p>
                    </td>
                </tr>
                <tr>
                    <td valign="top" style="color: #fff;font-size: 16px; padding: 2px 0 0; position: relative; z-index: 1;background: <?php echo $idcard->header_color; ?>;text-transform: uppercase;">
                        <?php echo $idcard->title; ?>
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        <div class="staround">
                            <div class="cardleft">
                                <div class="stimg">
                                    <img src="<?php echo base_url('uploads/patient_images/no_image.png' . img_time()) ?>" class="img-responsive" />
                                </div>
                            </div>
                            <div class="cardright">
                                <ul class="stlist">
                                    <?php if ($idcard->enable_patient_name) echo "<li>".$this->lang->line('patient_name')."<span> James Bond</span></li>"; ?>
                                    <?php if ($idcard->enable_guardian_name) echo "<li>".$this->lang->line('guardian_name')."<span> Guardian Name</span></li>"; ?>
                                    <?php if ($idcard->enable_patient_unique_id) echo "<li>".$this->lang->line('patient_unique_id')."<span> 1001</span></li>"; ?>
                                    <?php if ($idcard->enable_address) echo "<li>".$this->lang->line('address')."<span>D.No.1 Street Name Address Line 2 Address Line 3</span></li>"; ?>
                                    <?php if ($idcard->enable_phone) echo "<li>".$this->lang->line('phone')."<span>1234567890</span></li>"; ?>
                                    <?php if ($idcard->enable_dob) echo "<li>".$this->lang->line('dbo')."<span>25.06.2006</span></li>"; ?>
                                    <?php if ($idcard->enable_blood_group) echo "<li class='stred'>".$this->lang->line('blood_group')."<span>A+</span></li>"; ?>
                                </ul>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td valign="top" align="right" class="principal">
                        <?php
                        $signImage = fetchImage($idcard->sign_image);
                        ?>
                        <img src="<?=$signImage?>" width="66" height="40" />
                    </td>
                </tr>
            </table>
        </td>
    </tr>  
</table>

