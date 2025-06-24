<style type="text/css">
    .modal-dialog2 {
        margin: 1% auto;
    }

    .color_box {
        float: left;
        width: 10px;
        height: 10px;
        margin: 5px;
        border: 1px solid rgba(0, 0, 0, .2);
    }
</style>
<style>
    .modales {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        overflow: auto;
        padding-top: 50px;
    }

    #qr {
        background-color: #fff;
        margin: 5% auto;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        width: 80%;
        max-width: 350px;
        text-align: center;
    }

    .close {
        color: #aaa;
        font-size: 30px;
        font-weight: bold;
        position: absolute;
        top: 10px;
        right: 20px;
        padding: 5px;
        cursor: pointer;
        transition: color 0.3s ease;
    }

    .close:hover,
    .close:focus {
        color: #ff0000;
        text-decoration: none;
    }

    #qrcode {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 15px;
    }
</style>

<?php
$currency_symbol = $this->customlib->getHospitalCurrencyFormat();
$genderList = $this->customlib->getGender();
?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>

<div class="content-wrapper" style="min-height: 946px;">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php $this->load->view('setting/sidebar.php'); ?>

            <div class="col-md-10">
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="box-title titlefix"><?php echo $this->lang->line('general_settings'); ?></h3>
                            <div class="col-md-4 text-right">
                                <div class="form-group row">
                                    <div id="qrModal" class="modales">
                                        <div class="modal-content" id="qr">
                                            <span class="close" onclick="closeModal()">&times;</span>
                                            <h2>Hospital QR Code</h2>
                                            <div id="qrcode"></div>
                                            &nbsp;
                                            <button id="downloadPDF" style="display: none; margin: 10px;"
                                                class="btn btn-secondary">Download QR as PDF</button>
                                        </div>
                                    </div>
                                    <?php if ($this->rbac->hasPrivilege('general_setting', 'can_edit')) { ?>
                                        <a href="#" role="button" onclick="generateqr()" class="btn btn-primary"
                                            data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Processing"><i
                                                class="fa fa-qrcode"></i>
                                        </a>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div><!-- /.box-header -->

                    <div class="">
                        <form role="form" id="schsetting_form" class="" method="post" enctype="multipart/form-data">
                            <input value="<?php echo $settinglist[0]['image'] ?>" type="hidden" name="id"
                                id="old_file" />
                            <input value="<?php echo $settinglist[0]['mini_logo'] ?>" type="hidden" name="id"
                                id="old_file_small" />
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-4">Hospital Name <small class="req">*</small></label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="name" name="sch_name"
                                                    value="<?php echo $settinglist[0]['name'] ?>" required>
                                                <span class="text-danger"><?php echo form_error('name'); ?></span>
                                                <input type="hidden" name="sch_id"
                                                    value="<?php echo $settinglist[0]['id']; ?>" id="sch_id">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-4">Hospital Code <small class="req">*</small></label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="dise_code"
                                                    name="sch_dise_code" maxlength="20" oninput="this.value = this.value.replace(/[^a-zA-Z0-9]/g, '')"
                                                    value="<?php echo $settinglist[0]['dise_code']; ?>" required>
                                                <span class="text-danger" id="diseCodeError"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-4"><?php echo $this->lang->line('phone'); ?><small
                                                    class="req"> *</small></label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="phone" name="sch_phone"
                                                    value="<?php echo $settinglist[0]['phone'] ?>" pattern="^\d{10}$"
                                                    maxlength="10" oninput="this.value = this.value.replace(/\D/g, '')"
                                                    required>
                                                <span class="text-danger"><?php echo form_error('phone'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-4"><?php echo $this->lang->line('email'); ?><small
                                                    class="req"> *</small></label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="email" name="sch_email"
                                                    value="<?php echo $settinglist[0]['email']; ?>"
                                                    onkeyup="document.getElementById('emailError').textContent = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(this.value) ? '' : 'Invalid email format';"
                                                    required>
                                                <span class="text-danger" id="emailError"></span>

                                                <span class="text-danger"><?php echo form_error('email'); ?></span>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <!-- <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-4">Country <small class="req">*</small></label>
                                            <div class="col-sm-8">
                                                <select class="form-control" id="country" name="sch_country" required disabled>
                                                        <option value="IN">India</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-4">State <small class="req">*</small></label>
                                            <div class="col-sm-8">
                                                <select class="form-control" id="state" name="sch_state" required>
                                                    <option value="">Select Country First</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-4">District <small class="req">*</small></label>
                                            <div class="col-sm-8">
                                                <select class="form-control" id="city" name="sch_city" required>
                                                    <option value="">Select State First</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div> -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-4">Country <small class="req">*</small></label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="country" name="sch_country"
                                                    required value="India" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-4">State <small class="req">*</small></label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="state" name="sch_state"
                                                    required placeholder="Enter State" value="<?= $api_data[0]["state"] ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-4">District <small class="req">*</small></label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="district" name="sch_district"
                                                    required placeholder="Enter District" value="<?= $api_data[0]["district"] ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-4">Pin Code <small class="req">*</small></label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="pin_code"
                                                    name="sch_pin_code"
                                                    value="<?php echo $api_data[0]['pincode'] ?? ''; ?>"
                                                    pattern="^\d{6}$" maxlength="6"
                                                    oninput="this.value = this.value.replace(/\D/g, '')" required
                                                    value="<?= $api_data[0]["pincode"] ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-sm-2"><?php echo $this->lang->line('address'); ?><small
                                                    class="req"> *</small></label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="address" name="sch_address"
                                                    value="<?php echo $settinglist[0]['address'] ?>" required> <span
                                                    class="text-danger"><?php echo form_error('address'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--./row-->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="settinghr"></div>
                                        <h4 class="session-head">Hospital Timing</h4>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-4">From Time<small class="req">*</small></label>
                                            <div class="col-sm-8">
                                                <input type="time" class="form-control" id="hospital_timing_from"
                                                    name="hospital_timing_from"
                                                    value="<?php echo isset($api_data[0]['hospital_opening_timing']) ? $api_data[0]['hospital_opening_timing'] : ''; ?>"
                                                    required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-4">To Time<small class="req">*</small></label>
                                            <div class="col-sm-8">
                                                <input type="time" class="form-control" id="hospital_timing_to"
                                                    name="hospital_timing_to"
                                                    value="<?php echo isset($api_data[0]['hospital_closing_timing']) ? $api_data[0]['hospital_closing_timing'] : ''; ?>"
                                                    required>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr />
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label
                                                class="col-sm-4"><?php echo $this->lang->line('hospital_logo'); ?><small
                                                    class="req"> *</small></label>
                                            <?php
                                            $logo_image = base_url() . "uploads/staff_images/no_image.png";
                                            if (!empty($settinglist[0]['image'])) {
                                                $userdata = $this->session->userdata('hospitaladmin');
                                                $accessToken = $userdata['accessToken'] ?? '';
                                                $url = "https://phr-api.plenome.com/file_upload/getDocs";
                                                $payload = json_encode(['value' => $settinglist[0]['image']]);
                                                $client = curl_init($url);
                                                curl_setopt_array($client, [
                                                    CURLOPT_RETURNTRANSFER => true,
                                                    CURLOPT_POST => true,
                                                    CURLOPT_POSTFIELDS => $payload,
                                                    CURLOPT_HTTPHEADER => ['Content-Type: application/json', 'Authorization: ' . $accessToken],
                                                ]);
                                                $response = curl_exec($client);
                                                curl_close($client);
                                                if ($response && strpos($response, '"NoSuchKey"') === false) {
                                                    $logo_image = "data:image/png;base64," . trim($response);
                                                } elseif (!empty($settinglist[0]['image'])) {
                                                    $logo_image = base_url() . $settinglist[0]['image'];
                                                }
                                            }
                                            ?>

                                            <img src="<?= $logo_image ?>" class="" alt="" style="height: 15px;" id="largelogo">
                                            &nbsp;
                                            <?php if ($this->rbac->hasPrivilege('general_setting', 'can_edit')) { ?>
                                                <a href="#schsetting" role="button"
                                                    class="btn btn-primary btn-sm upload_logo"
                                                    data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Processing"><i
                                                        class="fa fa-picture-o"></i>
                                                    <?php echo $this->lang->line('edit_logo'); ?></a>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label
                                                class="col-sm-4"><?php echo $this->lang->line('hospital_small_logo'); ?><small
                                                    class="req"> *</small></label>
                                            <?php
                                            $logo_image = base_url() . "uploads/staff_images/no_image.png";
                                            if (!empty($settinglist[0]['mini_logo'])) {
                                                $userdata = $this->session->userdata('hospitaladmin');
                                                $accessToken = $userdata['accessToken'] ?? '';
                                                $url = "https://phr-api.plenome.com/file_upload/getDocs";
                                                $payload = json_encode(['value' => $settinglist[0]['mini_logo']]);
                                                $client = curl_init($url);
                                                curl_setopt_array($client, [
                                                    CURLOPT_RETURNTRANSFER => true,
                                                    CURLOPT_POST => true,
                                                    CURLOPT_POSTFIELDS => $payload,
                                                    CURLOPT_HTTPHEADER => ['Content-Type: application/json', 'Authorization: ' . $accessToken],
                                                ]);
                                                $response = curl_exec($client);
                                                curl_close($client);
                                                if ($response && strpos($response, '"NoSuchKey"') === false) {
                                                    $logo_image = "data:image/png;base64," . trim($response);
                                                } elseif (!empty($settinglist[0]['mini_logo'])) {
                                                    $logo_image = base_url() . $settinglist[0]['mini_logo'];
                                                }
                                            }
                                            ?>
                                            <img style="height: 15px;margin-top: 5px;" src="<?= $logo_image ?>" class=""
                                                alt="" id="minilogo">
                                            &nbsp;
                                            <?php if ($this->rbac->hasPrivilege('general_setting', 'can_edit')) { ?>
                                                <a href="#" role="button" class="btn btn-primary btn-sm upload_minilogo "
                                                    data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Processing"><i
                                                        class="fa fa-picture-o"></i>
                                                    <?php echo $this->lang->line('edit_small_logo'); ?></a>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="settinghr"></div>
                                        <h4 class="session-head">Location</h4>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-4">Latitude<small class="req"> *</small></label>
                                            <div class="col-sm-8">
                                                <input type="text" id="latitude" name="latitude" class="form-control"
                                                    readonly>
                                                <span class="text-danger"><?php echo form_error('latitude'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-4">Longitude<small class="req"> *</small></label>
                                            <div class="col-sm-8">
                                                <input type="text" id="longitude" name="longitude" class="form-control"
                                                    readonly>
                                                <span class="text-danger"><?php echo form_error('longitude'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div id="map" style="height: 400px; width: 100%;"></div>
                                    </div>
                                    <div class="col-md-12">
                                        <input id="searchBox" class="form-control" type="text"
                                            placeholder="Search for a hospital">
                                    </div>
                                    <div class="col-md-12">
                                        <div class="settinghr"></div>
                                        <h4 class="session-head">Fees Detials</h4>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-4">Select Fees Type<small class="req">
                                                    *</small></label>
                                            <div class="col-sm-8">
                                                <select id="fees_type" name="fees_type" class="form-control"
                                                    onchange="toggleFeesFields()">
                                                    <option value="" selected disabled>Select Fees Type</option>
                                                    <option value="appointment_fees">Hospital Consulting Charge</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="appointment_fees_section" class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-4">Appointment Fees<small class="req">
                                                    *</small></label>
                                            <div class="col-sm-8">
                                                <input type="number" id="appointment_fees" name="appointment_fees"
                                                    class="form-control" oninput="calculateTax()"
                                                    value="<?= $api_data[0]["hospital_consulting_charge"] ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6" id="Percentage_section">
                                        <div class="form-group row">
                                            <label class="col-sm-4">Tax Percentage<small class="req"> *</small></label>
                                            <div class="col-sm-8">
                                                <input type="number" id="tax_percentage" name="tax_percentage"
                                                    class="form-control"
                                                    onkeyup="this.value = this.value > 100 ? 100 : this.value; calculateTax(this.value);"
                                                    value="<?= $api_data[0]['tax_percentage'] ?>"
                                                    max="100">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6" id="Tax_section">
                                        <div class="form-group row">
                                            <label class="col-sm-4">Tax Amount</label>
                                            <div class="col-sm-8">
                                                <input type="text" id="tax_amount" name="tax_amount"
                                                    class="form-control" readonly
                                                    value="<?= $api_data[0]["tax_amount"] ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- <div class="col-md-12">
                                        <div class="settinghr"></div>
                                        <h4 class="session-head"><?php echo $this->lang->line('language'); ?></h4>
                                    </div> -->
                                    <!--./col-md-12-->

                                    <div class="col-md-6" style="display:none">
                                        <div class="form-group row">
                                            <label class="col-sm-4"><?php echo $this->lang->line('language'); ?><small
                                                    class="req"> *</small></label>
                                            <div class="col-sm-8">
                                                <select id="language_id" name="sch_lang_id" class="form-control">
                                                    <option value="">--<?php echo $this->lang->line('select') ?>--
                                                    </option>
                                                    <?php
                                                    foreach ($languagelist as $language) {
                                                    ?>
                                                        <option value="<?php echo $language['id']; ?>" <?php if ($settinglist[0]['lang_id'] == $language['id']) {
                                                                                                            echo 'selected';
                                                                                                        } ?>>
                                                            <?php echo $language['language'] ?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                                <span
                                                    class="text-danger"><?php echo form_error('language_id'); ?></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6" style="display:none">
                                        <div class="form-group row">
                                            <label
                                                class="col-sm-6"><?php echo $this->lang->line('language_rtl_text_mode'); ?></label>
                                            <div class="col-sm-6">
                                                <label class="radio-inline">
                                                    <input type="radio" name="sch_is_rtl" value="disabled" <?php if ($settinglist[0]['is_rtl'] == 'disabled') {
                                                                                                                echo 'checked';
                                                                                                            } ?>><?php echo $this->lang->line('disabled'); ?>
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="sch_is_rtl" value="enabled" <?php if ($settinglist[0]['is_rtl'] == 'enabled') {
                                                                                                                echo 'checked';
                                                                                                            } ?>><?php echo $this->lang->line('enabled'); ?>
                                                </label>

                                            </div>
                                        </div>
                                    </div>


                                </div>
                                <!--./row-->


                                <div class="row" style="display:none">
                                    <div class="col-md-12">
                                        <div class="settinghr"></div>
                                        <h4 class="session-head"><?php echo $this->lang->line('date_time'); ?></h4>
                                    </div>
                                    <!--./col-md-12-->


                                    <div class="col-md-6" style="display:none">
                                        <div class="form-group row">
                                            <label
                                                class="col-sm-4"><?php echo $this->lang->line('date_format'); ?><small
                                                    class="req"> *</small></label>
                                            <div class="col-sm-8">
                                                <select id="date_format" name="sch_date_format" class="form-control">
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php

                                                    foreach ($dateFormatList as $key => $dateformat) {
                                                    ?>
                                                        <option value="<?php echo $key ?>" <?php if ($settinglist[0]['date_format'] == $key) {
                                                                                                echo 'selected';
                                                                                            } ?>>
                                                            <?php echo $dateformat; ?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                                <span
                                                    class="text-danger"><?php echo form_error('date_format'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6" style="display:none">
                                        <div class="form-group row">
                                            <label class="col-sm-4"><?php echo $this->lang->line('time_zone'); ?><small
                                                    class="req"> *</small></label>
                                            <div class="col-sm-8">
                                                <select id="language_id" name="sch_timezone" class="form-control">
                                                    <option value=""><?php echo $this->lang->line('select') ?></option>
                                                    <?php

                                                    foreach ($timezoneList as $key => $timezone) {
                                                    ?>
                                                        <option value="<?php echo $key ?>" <?php if ($settinglist[0]['timezone'] == $key) {
                                                                                                echo 'selected';
                                                                                            } ?>>
                                                            <?php echo $timezone ?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('timezone'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--./row-->
                                <div class="row" style="display:none">
                                    <div class="col-md-12">
                                        <div class="settinghr"></div>
                                        <h4 class="session-head"><?php echo $this->lang->line('currency') ?></h4>
                                    </div>
                                    <!--./col-md-12-->


                                    <div class="col-md-6" style="display:none">
                                        <div class="form-group row">
                                            <label class="col-sm-4"><?php echo $this->lang->line('currency'); ?><small
                                                    class="req"> *</small></label>
                                            <div class="col-sm-8">
                                                <select id="currency" name="sch_currency" class="form-control">
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php
                                                    foreach ($currencyList as $currency) {
                                                    ?>
                                                        <option value="<?php echo $currency ?>" <?php if ($settinglist[0]['currency'] == $currency) {
                                                                                                    echo 'selected';
                                                                                                } ?>>
                                                            <?php echo $currency; ?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('currency'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6" style="display:none">
                                        <div class="form-group row">
                                            <label
                                                class="col-sm-4"><?php echo $this->lang->line('currency_symbol'); ?><small
                                                    class="req"> *</small></label>
                                            <div class="col-sm-8">
                                                <input id="currency_symbol" name="sch_currency_symbol" placeholder=""
                                                    type="text" class="form-control"
                                                    value="<?php echo $settinglist[0]['currency_symbol'] ?>" />
                                                <input type="hidden" id="newimagelargeimage" value="">
                                                <input type="hidden" id="newimagemini" value="">
                                                <span
                                                    class="text-danger"><?php echo form_error('currency_symbol'); ?></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6" style="display:none">
                                        <div class="form-group row">
                                            <label
                                                class="col-sm-4"><?php echo $this->lang->line('credit_limit'); ?><small
                                                    class="req"> *</small></label>
                                            <div class="col-sm-8">
                                                <input id="credit_limit" name="credit_limit" placeholder="" type="text"
                                                    class="form-control"
                                                    value="<?php echo $settinglist[0]['credit_limit']; ?>" />
                                                <span
                                                    class="text-danger"><?php echo form_error('credit_limit'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6" style="display:none">
                                        <div class="form-group row">
                                            <label class="col-sm-4"><?php echo $this->lang->line('time_format') ?><small
                                                    class="req"> *</small></label>
                                            <div class="col-sm-8">
                                                <select id="time_format" name="time_format" class="form-control">
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php
                                                    foreach ($timeFormat as $time_k => $time_v) {
                                                    ?>
                                                        <option value="<?php echo $time_k; ?>" <?php if ($settinglist[0]['time_format'] == $time_k) {
                                                                                                    echo 'selected';
                                                                                                } ?>>
                                                            <?php echo $time_v; ?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                                <span
                                                    class="text-danger"><?php echo form_error('time_format'); ?></span>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <!--./row-->

                                <!-- <div class="row">
                                    <div class="col-md-12">
                                        <div class="settinghr"></div>
                                        <div class="relative">

                                            <h4 class="session-head"><?php echo $this->lang->line('mobile_app'); ?> <?php if ($app_response) {
                                                                                                                        echo "<small class=' alert-success'>(" . $this->lang->line('android_app_purchase_code_already_registered') . ")</small>";
                                                                                                                    } ?></h4>

                                            <?php if (!$app_response) {
                                            ?>
                                                <button type="button" class="btn btn-info btn-sm impbtntitle3" data-toggle="modal" data-target="#andappModal"><?php echo $this->lang->line('register_your_android_app') ?></button>
                                            <?php
                                            }
                                            ?>

                                        </div>



                                    </div>

                                </div> -->
                                <!-- <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-sm-2"> <?php echo $this->lang->line('mobile_app_api_url') ?></label>
                                            <div class="col-sm-10">
                                                <input type="text" name="mobile_api_url" id="mobile_api_url" class="form-control" value="<?php echo $settinglist[0]['mobile_api_url']; ?>">
                                                <span class="text-danger"><?php echo form_error('mobile_api_url'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->


                                <!-- <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-5"><?php echo $this->lang->line('mobile_app_primary_color_code'); ?></label>
                                            <div class="col-sm-7">
                                                <input type="text" name="app_primary_color_code" id="app_primary_color_code" class="form-control" value="<?php echo $settinglist[0]['app_primary_color_code']; ?>">
                                                <span class="text-danger"><?php echo form_error('app_primary_color_code'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-6"> <?php echo $this->lang->line('mobile_app_secondary_color_code'); ?></label>
                                            <div class="col-sm-6">
                                                <input type="text" name="app_secondary_color_code" id="app_secondary_color_code" class="form-control" value="<?php echo $settinglist[0]['app_secondary_color_code']; ?>">
                                                <span class="text-danger"><?php echo form_error('app_secondary_color_code'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->

                                <!-- <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-sm-2"> <?php echo $this->lang->line('mobile_app_logo'); ?></label>
                                            <?php
                                            if ($settinglist[0]['app_logo'] == "") {
                                            ?>
                                                <img src="<?php echo base_url('uploads/hospital_content/logo/images.png' . img_time()) ?>" class="" alt="" style="height: 15px;">
                                            <?php
                                            } else {
                                            ?>
                                                <img src="<?php echo base_url('uploads/hospital_content/logo/' . $settinglist[0]['app_logo'] . img_time()) ?>" class="" alt="" style="height: 15px;margin-top: 5px;">
                                            <?php
                                            }
                                            ?>
                                            <?php if ($this->rbac->hasPrivilege('general_setting', 'can_edit')) { ?>
                                                &nbsp;<a href="#" role="button" class="btn btn-primary btn-sm upload_applogo " data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Processing"><i class="fa fa-picture-o"></i> <?php echo $this->lang->line('edit_app_logo'); ?></a>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div> -->

                                <div class="row" style="display:none">
                                    <div class="col-md-12">
                                        <div class="settinghr"></div>
                                        <div class="relative">
                                            <h4 class="session-head"><?php echo $this->lang->line('miscellaneous'); ?>
                                            </h4>
                                        </div>
                                    </div>
                                    <!--./col-md-12-->
                                </div>
                                <div class="row" style="display:none">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label
                                                class="col-sm-5"><?php echo $this->lang->line('doctor_restriction_mode'); ?></label>
                                            <div class="col-sm-7">
                                                <label class="radio-inline">
                                                    <input type="radio" name="doctor_restriction_mode" value="disabled" <?php if ($settinglist[0]['doctor_restriction'] == 'disabled') {
                                                                                                                            echo 'checked';
                                                                                                                        } ?>><?php echo $this->lang->line('disabled'); ?>
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="doctor_restriction_mode" value="enabled" <?php if ($settinglist[0]['doctor_restriction'] == 'enabled') {
                                                                                                                            echo 'checked';
                                                                                                                        } ?>><?php echo $this->lang->line('enabled'); ?>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-6">
                                                <?php echo $this->lang->line('superadmin_visibility'); ?></label>
                                            <div class="col-sm-6">
                                                <label class="radio-inline">
                                                    <input type="radio" name="superadmin_restriction_mode"
                                                        value="disabled" <?php if ($settinglist[0]['superadmin_restriction'] == 'disabled') {
                                                                                echo 'checked';
                                                                            } ?>><?php echo $this->lang->line('disabled'); ?>
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="superadmin_restriction_mode"
                                                        value="enabled" <?php if ($settinglist[0]['superadmin_restriction'] == 'enabled') {
                                                                            echo 'checked';
                                                                        } ?>><?php echo $this->lang->line('enabled'); ?>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6" style="display:none">
                                        <div class="form-group row">
                                            <label class="col-sm-5">
                                                <?php echo $this->lang->line('patient_panel'); ?></label>
                                            <div class="col-sm-7">
                                                <label class="radio-inline">
                                                    <input type="radio" name="patient_panel" value="disabled" <?php if ($settinglist[0]['patient_panel'] == 'disabled') {
                                                                                                                    echo 'checked';
                                                                                                                } ?>><?php echo $this->lang->line('disabled'); ?>
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="patient_panel" value="enabled" <?php if ($settinglist[0]['patient_panel'] == 'enabled') {
                                                                                                                    echo 'checked';
                                                                                                                } ?>><?php echo $this->lang->line('enabled'); ?>
                                                </label>
                                            </div>
                                        </div>
                                    </div>



                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="settinghr"></div>
                                        <h4 class="session-head"><?php echo $this->lang->line('current_theme'); ?></h4>
                                    </div>
                                    <!--./col-md-12-->
                                    <div class="col-sm-12">

                                        <div id="input-type">
                                            <div class="row">

                                                <div class="col-sm-3 col-xs-6 col20">
                                                    <label class="radio-img">
                                                        <input name="theme" <?php
                                                                            if ($settinglist[0]['theme'] == "default.jpg") {
                                                                                echo "checked";
                                                                            }
                                                                            ?> value="default.jpg" type="radio" />
                                                        <img
                                                            src="<?php echo base_url('backend/images/default.jpg' . img_time()); ?>">
                                                    </label>
                                                </div>
                                                <div class="col-sm-3 col-xs-6 col20">
                                                    <label class="radio-img">
                                                        <input name="theme" <?php
                                                                            if ($settinglist[0]['theme'] == "red.jpg") {
                                                                                echo "checked";
                                                                            }
                                                                            ?> value="red.jpg" type="radio" />
                                                        <img
                                                            src="<?php echo base_url('backend/images/red.jpg' . img_time()); ?>">
                                                    </label>
                                                </div>
                                                <div class="col-sm-3 col-xs-6 col20">
                                                    <label class="radio-img">
                                                        <input name="theme" <?php
                                                                            if ($settinglist[0]['theme'] == "blue.jpg") {
                                                                                echo "checked";
                                                                            }
                                                                            ?> value="blue.jpg" type="radio" />
                                                        <img
                                                            src="<?php echo base_url('backend/images/blue.jpg' . img_time()); ?>">
                                                    </label>
                                                </div>
                                                <div class="col-sm-3 col-xs-6 col20">
                                                    <label class="radio-img">
                                                        <input name="theme" <?php
                                                                            if ($settinglist[0]['theme'] == "gray.jpg") {
                                                                                echo "checked";
                                                                            }
                                                                            ?> value="gray.jpg" type="radio" />
                                                        <img
                                                            src="<?php echo base_url('backend/images/gray.jpg' . img_time()); ?>">
                                                    </label>
                                                </div>


                                            </div>
                                            <!--./row-->

                                        </div>
                                    </div>
                                </div>
                            </div><!-- /.box-body -->
                            <div class="box-footer">
                                <?php
                                if ($this->rbac->hasPrivilege('general_setting', 'can_edit')) {
                                ?>
                                    <button type="button" class="btn btn-primary submit_schsetting pull-right edit_setting"
                                        data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Processing"><i
                                            class="fa fa-check-circle"></i>
                                        <?php echo $this->lang->line('save'); ?></button>
                                <?php
                                }
                                ?>


                            </div>
                        </form>
                    </div><!-- /.box-body -->
                </div>
            </div>
            <!--./col-md-9-->

        </div>
    </section>
</div>



<div class="modal fade" id="modal-uploadfile" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="clearPreview()">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">
                    <?php echo $this->lang->line('edit_logo'); ?>
                    <span id="imagesize">(182px X 18px)</span>
                </h4>
            </div>
            <div class="modal-body">
                <form class="box_upload boxupload" id="ajaxlogo" enctype="multipart/form-data">
                    <div class="box__input">
                        <i class="fa fa-download box__icon"></i>
                        <input class="box__file" type="file" name="file" id="file" accept="image/*" />
                        <input value="<?php echo $settinglist[0]['id'] ?>" type="hidden" name="id" id="id" />
                        <label for="file">
                            <strong></strong>
                            <span class="box__dragndrop">
                                <?php echo $this->lang->line('choose_a_file_or_drag_it_here'); ?>
                            </span>.
                        </label>
                        <button class="box__button" type="button" id="uploadButton">
                            <?php echo $this->lang->line('upload'); ?>
                        </button>
                    </div>
                    <div class="box__uploading">
                        <?php echo $this->lang->line('uploading'); ?>&hellip;
                    </div>
                </form>
                <div id="cropContainer" style="display:none;">
                    <img id="previewImage" style="max-width: 100%;" />
                    <button id="cropButton" class="btn btn-primary">Save</button>
                    <button id="skipCropButton" class="btn btn-secondary">Skip Crop</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="andappModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('register_your_android_app_purchase_code'); ?></h4>
            </div>
            <form action="<?php echo site_url('admin/admin/updateandappCode') ?>" method="POST" id="andapp_code">
                <div class="modal-body andapp_modal-body">
                    <div class="error_message">
                    </div>
                    <div class="form-group">
                        <label class="ainline"><span><?php echo $this->lang->line('envato_market_purchase_code_for_smart_hospital_android_app'); ?>
                                ( <a target="_blank"
                                    href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-">
                                    <?php echo $this->lang->line('how_to_find_it'); ?></a> )</span></label>
                        <input type="text" class="form-control" id="input-app-envato_market_purchase_code"
                            name="app-envato_market_purchase_code">
                        <div id="error" class="input-error text text-danger"></div>
                    </div>

                    <div class="form-group">
                        <label
                            for="exampleInputEmail1"><?php echo $this->lang->line('your_email_registered_with_envato'); ?></label>
                        <input type="text" class="form-control" id="input-app-email" name="app-email">
                        <div id="error" class="input-error text text-danger"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info"
                        data-loading-text="<i class='fa fa-spinner fa-spin '></i> Saving..."><?php echo $this->lang->line('save'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
$state = $api_data[0]["state"];
$district = $api_data[0]["district"];
$stateMap = [
    "Andaman and Nicobar Islands" => "AN",
    "Andhra Pradesh" => "AP",
    "Arunachal Pradesh" => "AR",
    "Assam" => "AS",
    "Bihar" => "BR",
    "Chandigarh" => "CH",
    "Chhattisgarh" => "CT",
    "Dadra and Nagar Haveli and Daman and Diu" => "DH",
    "Delhi" => "DL",
    "Goa" => "GA",
    "Gujarat" => "GJ",
    "Haryana" => "HR",
    "Himachal Pradesh" => "HP",
    "Jammu and Kashmir" => "JK",
    "Jharkhand" => "JH",
    "Karnataka" => "KA",
    "Kerala" => "KL",
    "Ladakh" => "LA",
    "Lakshadweep" => "LD",
    "Madhya Pradesh" => "MP",
    "Maharashtra" => "MH",
    "Manipur" => "MN",
    "Meghalaya" => "ML",
    "Mizoram" => "MZ",
    "Nagaland" => "NL",
    "Odisha" => "OR",
    "Puducherry" => "PY",
    "Punjab" => "PB",
    "Rajasthan" => "RJ",
    "Sikkim" => "SK",
    "Tamil Nadu" => "TN",
    "Telangana" => "TG",
    "Tripura" => "TR",
    "Uttar Pradesh" => "UP",
    "Uttarakhand" => "UT",
    "West Bengal" => "WB"
];

$stateCode = isset($stateMap[$state]) ? $stateMap[$state] : "";
?>

<script>
    const apiKey = "NHhvOEcyWk50N2Vna3VFTE00bFp3MjFKR0ZEOUhkZlg4RTk1MlJlaA==";
    const apiHeaders = {
        "X-CSCAPI-KEY": apiKey
    };

    async function fetchStates(selectedState = null) {
        try {
            let response = await fetch("https://api.countrystatecity.in/v1/countries/IN/states", {
                headers: apiHeaders
            });
            let data = await response.json();
            let stateDropdown = document.getElementById("state");
            stateDropdown.innerHTML = '<option value="">Select State</option>';
            document.getElementById("city").innerHTML = '<option value="">Select City</option>';
            data.forEach(state => {
                let option = new Option(state.name, state.iso2);
                if (selectedState && state.name.toLowerCase() === selectedState.toLowerCase()) option.selected =
                    true;
                stateDropdown.add(option);
            });
            if (selectedState) fetchCities(stateDropdown.value, "<?= $district ?>");
        } catch (error) {
            console.error("Error fetching states:", error);
        }
    }

    async function fetchCities(stateCode, selectedCity = null) {
        try {
            let response = await fetch(`https://api.countrystatecity.in/v1/countries/IN/states/${stateCode}/cities`, {
                headers: apiHeaders
            });
            let data = await response.json();
            let cityDropdown = document.getElementById("city");
            cityDropdown.innerHTML = '<option value="">Select District</option>';
            data.forEach(city => {
                let option = new Option(city.name, city.name);
                if (selectedCity && city.name.toLowerCase() === selectedCity.toLowerCase()) option.selected =
                    true;
                cityDropdown.add(option);
            });
        } catch (error) {
            console.error("Error fetching cities:", error);
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        fetchStates("<?= $stateCode ?>");
        document.getElementById("state").addEventListener("change", function() {
            fetchCities(this.value);
        });
    });
</script>


<?php
$data = $this->session->userdata('hospitaladmin');
$api_base_url = $this->config->item('api_base_url');
?>
<script type="text/javascript">
    const sch_id = $('#sch_id').val();
    $('.edit_setting').on('click', function(e) {
        e.preventDefault();
        var $this = $(this);
        $this.button('loading');
        $.ajax({
            url: base_url + '/schsettings/getSchsetting',
            type: 'POST',
            data: {},
            dataType: "json",
            success: function(result) {
                $('input[name="sch_id"]').val(result.id);
                $('input[name="sch_name"]').val(result.name);
                $('input[name="sch_dise_code"]').val(result.dise_code);
                $('input[name="sch_phone"]').val(result.phone);
                $('input[name="credit_limit"]').val(result.credit_limit);
                $('#opd_record_month').val(result.opd_record_month);
                $('input[name="sch_email"]').val(result.email);
                $('input[name="fee_due_days"]').val(result.fee_due_days);
                $('input[name="sch_currency_symbol"]').val(result.currency_symbol);
                $('input[name="sch_mobile_api_url"]').val(result.mobile_api_url);
                $('input[name="sch_app_primary_color_code"]').val(result.app_primary_color_code);
                $('input[name="sch_app_secondary_color_code"]').val(result.app_secondary_color_code);
                $('textarea[name="sch_address"]').text(result.address);
                $("input[name=sch_is_rtl][value=" + result.is_rtl + "]").attr('checked', 'checked');
                $("input[name=doctor_restriction_mode][value=" + result.doctor_restriction + "]").attr(
                    'checked', 'checked');
                $("input[name=superadmin_restriction_mode][value=" + result.superadmin_restriction +
                    "]").attr('checked', 'checked');
                $("input[name=theme][value='" + result.theme + "']").attr('checked', 'checked');
                $('select[name="sch_session_id"] option[value="' + result.session_id + '"]').attr(
                    "selected", "selected");
                $('select[name="sch_start_month"] option[value="' + result.start_month + '"]').attr(
                    "selected", "selected");
                $('select[name="sch_lang_id"] option[value="' + result.lang_id + '"]').attr("selected",
                    "selected");
                $('select[name="sch_timezone"] option[value="' + result.timezone + '"]').attr(
                    "selected", "selected");
                $('select[name="sch_date_format"] option[value="' + result.date_format + '"]').attr(
                    "selected", "selected");
                $('select[name="time_format"] option[value="' + result.time_format + '"]').attr(
                    "selected", "selected");
                $('select[name="sch_currency"] option[value="' + result.currency + '"]').attr(
                    "selected", "selected");
                $('#schsetting').modal({
                    show: true,
                    backdrop: 'static',
                    keyboard: false
                });
            },
            error: function() {
                console.log("error on form");
            }

        }).done(function() {
            $this.button('reset');
        });
    });


    $(document).on('click', '.submit_schsetting', function(e) {
        const sch_id = $('#sch_id').val();
        var $this = $(this);
        $this.button('loading');

        var formData = $('#schsetting_form').serialize();

        const queryStringToJson = (queryString) => {
            const pairs = queryString.split('&');
            const result = {};
            pairs.forEach(pair => {
                const [key, value] = pair.split('=');
                result[decodeURIComponent(key)] = decodeURIComponent(value);
            });
            return result;
        };

        const jsonData = queryStringToJson(formData);
        const requiredFields = [{
                key: 'sch_name',
                label: 'Hospital Name'
            },
            {
                key: 'sch_dise_code',
                label: 'Hospital Code'
            },
            {
                key: 'sch_address',
                label: 'Address'
            },
            {
                key: 'sch_phone',
                label: 'Phone'
            },
            {
                key: 'sch_email',
                label: 'Email'
            },
            {
                key: 'sch_date_format',
                label: 'Date Format'
            },
            {
                key: 'sch_timezone',
                label: 'Time Zone'
            },
            {
                key: 'sch_currency',
                label: 'Currency'
            },
            {
                key: 'sch_currency_symbol',
                label: 'Currency Symbol'
            },
            {
                key: 'credit_limit',
                label: 'Credit Limit'
            },
            {
                key: 'theme',
                label: 'Current Theme'
            }
        ];

        for (let field of requiredFields) {
            if (!jsonData[field.key]) {
                errorMsg(`Please provide ${field.label}`);
                $this.button('reset');
                return;
            }
        }

        let hospitalName = jsonData.sch_name.replace(/\+/g, ' ');
        let sch_address = jsonData.sch_address.replace(/\+/g, ' ');

        const finaldata = {
            "name": hospitalName,
            "email": jsonData.sch_email,
            "phone": jsonData.sch_phone,
            "address": sch_address,
            "start_month": jsonData.sch_date_format,
            "session_id": jsonData.sch_id,
            "lang_id": jsonData.sch_lang_id,
            "languages": jsonData.sch_lang_id,
            "dise_code": jsonData.sch_dise_code,
            "date_format": jsonData.sch_date_format,
            "time_format": jsonData.time_format,
            "currency": jsonData.sch_currency,
            "currency_symbol": jsonData.sch_currency_symbol,
            "is_rtl": jsonData.sch_is_rtl,
            "timezone": jsonData.sch_timezone,
            "theme": jsonData.theme,
            "credit_limit": jsonData.credit_limit,
            "cron_secret_key": " ",
            "doctor_restriction": jsonData.doctor_restriction_mode,
            "superadmin_restriction": jsonData.superadmin_restriction_mode,
            "patient_panel": jsonData.patient_panel || "enabled",
            "mobile_api_url": " ",
            "app_primary_color_code": " ",
            "app_secondary_color_code": " ",
            "zoom_api_key": " ",
            "zoom_api_secret": " ",
            "app_logo": " ",
            "hospital_id": <?= $data['hospital_id'] ?>,
            "hospital_opening_timing": jsonData.hospital_timing_from,
            "hospital_closing_timing": jsonData.hospital_timing_to,
            "admin_address": jsonData.sch_address,
            "state": jsonData.sch_state,
            "district": jsonData.sch_district,
            "pincode": jsonData.sch_pin_code,
            "tax_percentage": jsonData.tax_percentage,
            "tax_amount": jsonData.tax_amount,
            "lattitude": jsonData.latitude,
            "longitude": jsonData.longitude,
            "hospital_consulting_charge": jsonData.appointment_fees
        };
        $.ajax({
            url: '<?= $api_base_url ?>settings-general-setting/' + sch_id,
            type: 'PATCH',
            data: JSON.stringify(finaldata),
            headers: {
                'Authorization': accesstoken
            },
            contentType: 'application/json',
            dataType: 'json',
            success: function(data) {
                if (data.status === "fail") {
                    let message = "";
                    $.each(data.error, function(index, value) {
                        message += value;
                    });
                    errorMsg(message);
                } else {
                    submitForm().then((success) => {
                        if (success) {
                            successMsg("General Setting Updated Successfully!");
                            window.location.reload(true);
                        } else {
                            errorMsg("Error uploading Logo. Try again later.");
                        }
                    });
                }
                $this.button('reset');
            },
            error: function() {
                errorMsg("Internal Server Error");
                $this.button('reset');
            }
        });
    });

    function submitForm() {
        return new Promise((resolve) => {
            const finalData = {
                "image": $('#newimagelargeimage').val() || $('#old_file').val(),
                "mini_logo": $('#newimagemini').val() || $('#old_file_small').val(),
                "hospital_id": <?= $data['hospital_id'] ?>
            };

            $.ajax({
                url: '<?= $api_base_url ?>settings-general-setting/logo/' + $("#sch_id").val(),
                type: 'PATCH',
                headers: {
                    'Authorization': accesstoken
                },
                data: JSON.stringify(finalData),
                contentType: 'application/json',
                processData: false,
                success: function(data) {
                    resolve(data.status !== "fail");
                },
                error: function() {
                    errorMsg('An error occurred while uploading. Please try again.');
                    resolve(false);
                }
            });
        });
    }
</script>
<script type="text/javascript">
    var isAdvancedUpload = function() {
        var div = document.createElement('div');
        return (('draggable' in div) || ('ondragstart' in div && 'ondrop' in div)) && 'FormData' in window &&
            'FileReader' in window;
    }();
    var forms = $('#ajaxlogo');
    Array.prototype.forEach.call(forms, function(form) {
        var input = form.querySelector('input[type="file"]'),
            label = form.querySelector('label'),
            errorMsg = form.querySelector('.box__error span'),
            restart = form.querySelectorAll('.box__restart'),
            droppedFiles = false,
            showFiles = function(files) {

            },
            showErrors = function(files) {
                toastr.error(files);
            },
            showSuccess = function(msg) {
                toastr.success(msg);
                setTimeout(function() {
                    window.location.reload(1);
                }, 2000);
            },
            triggerFormSubmit = function() {
                var event = document.createEvent('HTMLEvents');
                event.initEvent('submit', true, false);
                form.dispatchEvent(event);
            };
        var ajaxFlag = document.createElement('input');
        ajaxFlag.setAttribute('type', 'hidden');
        ajaxFlag.setAttribute('name', 'ajax');
        ajaxFlag.setAttribute('value', 1);
        form.appendChild(ajaxFlag);
        input.addEventListener('change', function(e) {
            showFiles(e.target.files);
            triggerFormSubmit();
        });
        if (isAdvancedUpload) {
            form.classList.add(
                'has-advanced-upload');
            ['drag', 'dragstart', 'dragend', 'dragover', 'dragenter', 'dragleave', 'drop'].forEach(function(event) {
                form.addEventListener(event, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                });
            });
            ['dragover', 'dragenter'].forEach(function(event) {
                form.addEventListener(event, function() {
                    form.classList.add('is-dragover');
                });
            });
            ['dragleave', 'dragend', 'drop'].forEach(function(event) {
                form.addEventListener(event, function() {
                    form.classList.remove('is-dragover');
                });
            });
            form.addEventListener('drop', function(e) {
                droppedFiles = e.dataTransfer.files;
                showFiles(droppedFiles);
                triggerFormSubmit();
            });
        }
        form.addEventListener('submit', function(e) {
            if (form.classList.contains('is-uploading'))
                return false;
            form.classList.add('is-uploading');
            form.classList.remove('is-error');
            if (isAdvancedUpload) {
                e.preventDefault();
                var ajaxData = new FormData(form);
                if (droppedFiles) {
                    Array.prototype.forEach.call(droppedFiles, function(file) {
                        ajaxData.append(input.getAttribute('name'), file);
                    });
                }
                var ajax = new XMLHttpRequest();
                ajax.open(form.getAttribute('method'), form.getAttribute('action'), true);
                ajax.onload = function() {
                    form.classList.remove('is-uploading');
                    if (ajax.status >= 200 && ajax.status < 400) {
                        var data = JSON.parse(ajax.responseText);
                        if (data.success) {
                            var sucmsg = "Record updated Successfully";
                            showSuccess(sucmsg);
                        }
                        if (!data.success) {
                            var message = "";
                            $.each(data.error, function(index, value) {
                                message += value;
                            });
                            showErrors(message);
                        };
                    } else {}
                };

                ajax.onerror = function() {
                    form.classList.remove('is-uploading');
                    alert('Error. Please, try again!');
                };

                ajax.send(ajaxData);
            } else {
                var iframeName = 'uploadiframe' + new Date().getTime(),
                    iframe = document.createElement('iframe');

                $iframe = $('<iframe name="' + iframeName + '" style="display: none;"></iframe>');

                iframe.setAttribute('name', iframeName);
                iframe.style.display = 'none';

                document.body.appendChild(iframe);
                form.setAttribute('target', iframeName);

                iframe.addEventListener('load', function() {
                    var data = JSON.parse(iframe.contentDocument.body.innerHTML);
                    form.classList.remove('is-uploading')
                    form.removeAttribute('target');
                    if (!data.success)
                        errorMsg.textContent = data.error;
                    iframe.parentNode.removeChild(iframe);
                });
            }
        });
        Array.prototype.forEach.call(restart, function(entry) {
            entry.addEventListener('click', function(e) {
                e.preventDefault();
                input.click();
            });
        });

        input.addEventListener('focus', function() {
            input.classList.add('has-focus');
        });
        input.addEventListener('blur', function() {
            input.classList.remove('has-focus');
        });

    });
</script>
<script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    $('#modal-uploadfile').on('shown.bs.modal', function() {
        $('.upload_logo').button('reset');
    });
    $('#modal-uploadappfile').on('shown.bs.modal', function() {
        $('.upload_applogo').button('reset');
    });
    $('#modal-uploadfile').on('shown.bs.modal', function() {
        $('.upload_minilogo').button('reset');
    });
    document.getElementById('file').addEventListener('click', function() {
        this.value = null;
    });
    document.getElementById('file').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const maxSize = 5 * 1024 * 1024;
        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!file) {
            errorMsg('Please select a file.');
            return;
        }
        if (file.size > maxSize) {
            errorMsg('File size must be 5MB or less.');
            e.target.value = '';
            return;
        }
        if (!allowedTypes.includes(file.type)) {
            errorMsg('Invalid file type. Please upload an image (JPG, PNG, GIF, WebP).');
            e.target.value = '';
            return;
        }
        const reader = new FileReader();
        reader.onload = function(event) {
            document.getElementById('cropContainer').style.display = 'block';
            document.getElementById('ajaxlogo').style.display = 'none';
            const previewImage = document.getElementById('previewImage');
            previewImage.src = event.target.result;
            if (window.cropper) cropper.destroy();
            window.cropper = new Cropper(previewImage, {
                aspectRatio: uploadType === 'image' ? 182 / 50 : 41 / 25,
                viewMode: 1,
            });
        };
        reader.readAsDataURL(file);
    });

    document.getElementById('skipCropButton').addEventListener('click', function() {
        const fileInput = document.getElementById('file');
        const file = fileInput.files[0];

        if (!file) {
            errorMsg("Please select a file.");
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            if (uploadType === 'image') {
                document.getElementById('largelogo').src = e.target.result;
            } else {
                document.getElementById('minilogo').src = e.target.result;
            }
        };
        reader.readAsDataURL(file);

        const uploadData = new FormData();
        uploadData.append('file', file);

        fetch('https://phr-api.plenome.com/file_upload', {
                method: 'POST',
                body: uploadData,
                headers: {
                    'Authorization': accesstoken
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.data) {
                    const uploadedImageUrl = data.data;
                    if (uploadType === 'image') {
                        $("#newimagelargeimage").val(data.data)
                    } else {
                        $("#newimagemini").val(data.data)
                    }
                    clearPreview();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                errorMsg("Error uploading file. Try again.");
            });
    });

    document.getElementById('cropButton').addEventListener('click', function() {
        const croppedCanvas = cropper.getCroppedCanvas({
            width: uploadType === 'image' ? 182 : 41,
            height: uploadType === 'image' ? 50 : 25,
        });

        croppedCanvas.toBlob(function(blob) {
            const uploadData = new FormData();
            uploadData.append('file', blob);
            const reader = new FileReader();
            reader.onload = function(e) {
                if (uploadType === 'image') {
                    document.getElementById('largelogo').src = e.target.result;
                } else {
                    document.getElementById('minilogo').src = e.target.result;
                }
            };
            reader.readAsDataURL(blob);
            fetch('https://phr-api.plenome.com/file_upload', {
                    method: 'POST',
                    body: uploadData,
                    headers: {
                        'Authorization': accesstoken
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.data) {
                        const uploadedImageUrl = data.data;
                        if (uploadType === 'image') {
                            $("#newimagelargeimage").val(data.data)
                        } else {
                            $("#newimagemini").val(data.data)
                        }
                        clearPreview();
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    });

    function clearPreview() {
        $('#modal-uploadfile').modal('hide');
        $('#ajaxlogo').show();
        $('#cropContainer').hide();
    }


    let uploadType = 'image';
    $('.upload_logo').on('click', function(e) {
        e.preventDefault();
        uploadType = 'image';
        $("#myModalLabel").html('<?php echo $this->lang->line('edit_logo') ?> (182px X 50px)');
        $("#imagesize").html(' (182px X 50px)');
        var $this = $(this);
        $this.button('loading');
        $('#modal-uploadfile').modal({
            show: true,
            backdrop: 'static',
            keyboard: false
        });
    });
    $('.upload_minilogo').on('click', function(e) {
        e.preventDefault();
        uploadType = 'mini_logo';
        $("#myModalLabel").html('<?php echo $this->lang->line('edit_small_logo'); ?> (41px X 25px)');
        $("#imagesize").html(' (41px X 25px)');
        var $this = $(this);
        $this.button('loading');
        $('#modal-uploadfile').modal({
            show: true,
            backdrop: 'static',
            keyboard: false
        });
    });

    function generateqr() {
        $.ajax({
            url: '<?= $api_base_url ?>hospitals/QR/<?= $data["hospital_id"] ?>',
            type: 'GET',
            dataType: 'json',
            headers: {
                'Authorization': accesstoken
            },
            success: function(data) {
                if (data && data.hospitals) {
                    const hospitalData = JSON.stringify({
                        hospitals: {
                            hospital_id: data.hospitals.hospital_id,
                            hospital_name: data.hospitals.hospital_name,
                        },
                        QR_Type: data.QR_type,
                    });

                    const qrContainer = document.getElementById("qrcode");
                    new QRCode(qrContainer, {
                        text: hospitalData,
                        width: 256,
                        height: 256,
                        colorDark: "#000000",
                        colorLight: "#ffffff",
                        correctLevel: QRCode.CorrectLevel.H,
                    });

                    setTimeout(() => {
                        enablePDFDownload(data, qrContainer);
                    }, 500);

                    openModal();
                } else {
                    errorMsg("QR Data not available.");
                }
            },
            error: function() {
                errorMsg("Internal Server Error");
            }
        });
    }

    function openModal() {
        document.getElementById("qrModal").style.display = "block";
    }

    function enablePDFDownload(data, container) {
        const qrCanvas = container.querySelector("canvas");
        if (qrCanvas) {
            const downloadPDFLink = document.getElementById("downloadPDF");
            downloadPDFLink.onclick = () => {
                const {
                    jsPDF
                } = window.jspdf;
                const pdf = new jsPDF('portrait', 'mm', 'a4');
                pdf.setFont("helvetica", "bold");
                pdf.setFontSize(18);
                pdf.text("Hospital QR Code", 105, 20, {
                    align: "center"
                });
                pdf.setFont("helvetica", "normal");
                pdf.setFontSize(14);
                pdf.text(`Hospital Name: ${data.hospitals.hospital_name}`, 20, 40);
                const qrDataURL = qrCanvas.toDataURL("image/png");
                pdf.addImage(qrDataURL, 'PNG', 60, 70, 90, 90);
                pdf.setFontSize(12);
                pdf.text("Scan this QR code for hospital details.", 105, 180, {
                    align: "center"
                });
                pdf.save("Hospital_QR_Code.pdf");
            };
            downloadPDFLink.style.display = "block";
        }
    }

    function closeModal() {
        document.getElementById("qrModal").style.display = "none";
        document.getElementById("qrcode").innerHTML = '';
        document.getElementById("downloadPDF").style.display = "none";
    }
</script>
<script type="text/javascript">
    function getLocation() {
        console.log("Attempting to get location...");

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                document.getElementById('latitude').value = position.coords.latitude;
                document.getElementById('longitude').value = position.coords.longitude;
            }, function() {
                alert('Geolocation is not supported or permission denied.');
            });
        } else {
            alert('Geolocation is not supported by this browser.');
        }
    }

    function toggleFeesFields() {
        let feesType = document.getElementById("fees_type").value || "appointment_fees";
        if (feesType === "appointment_fees") {
            document.getElementById("appointment_fees_section").style.display = "block";
            document.getElementById("Percentage_section").style.display = "block";
            document.getElementById("Tax_section").style.display = "block";
            document.getElementById("fees_type").value = "appointment_fees";

        } else {
            document.getElementById("Percentage_section").style.display = "none";
            document.getElementById("Tax_section").style.display = "none";
            document.getElementById("appointment_fees_section").style.display = "none";
        }
    }
    toggleFeesFields();

    function calculateTax(percentage) {
        if (!percentage) {
            percentage = document.getElementsByName("tax_percentage")[0].value;
        }
        let feesAmount = parseFloat(document.getElementById("appointment_fees").value) || 0;
        let taxAmountField = document.getElementById("tax_amount");

        if (percentage > 0) {
            taxAmountField.value = (feesAmount * (percentage / 100)).toFixed(2);
        } else {
            taxAmountField.value = 0;
        }
    }


    window.onload = function() {
        getLocation();
        toggleFeesFields();
    };
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBxmaThKepm4CWAc6s_0tg1aYNNF_sNpXQ&libraries=places" async
    defer></script>
<?php
$latitude = !empty($api_data[0]['lattitude']) ? $api_data[0]['lattitude'] : null;
$longitude = !empty($api_data[0]['longitude']) ? $api_data[0]['longitude'] : null;
?>
<script>
    function getCurrentTimestamp() {
        return (new Date()).getTime();
    }

    let map, searchBox, marker;

    function initMap() {
        let initialLocation = null;

        <?php if ($latitude !== null && $longitude !== null): ?>
            initialLocation = {
                lat: <?= $latitude ?>,
                lng: <?= $longitude ?>
            };
            setUpMapWithLocation(initialLocation);
        <?php else: ?>
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    initialLocation = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };
                    setUpMapWithLocation(initialLocation);
                }, function() {
                    setUpDefaultLocation();
                });
            } else {
                setUpDefaultLocation();
            }
        <?php endif; ?>
    }

    function setUpDefaultLocation() {
        const defaultLocation = {
            lat: -34.397,
            lng: 150.644
        };
        setUpMapWithLocation(defaultLocation);
    }

    function setUpMapWithLocation(location) {
        map = new google.maps.Map(document.getElementById("map"), {
            center: location,
            zoom: 15,
        });

        marker = new google.maps.Marker({
            map: map,
            position: location,
            draggable: true,
        });

        updateLatLngInputs(location.lat, location.lng);

        google.maps.event.addListener(marker, 'dragend', function() {
            const position = marker.getPosition();
            updateLatLngInputs(position.lat(), position.lng());
        });

        searchBox = new google.maps.places.SearchBox(document.getElementById("searchBox"));

        map.addListener("bounds_changed", function() {
            searchBox.setBounds(map.getBounds());
        });

        searchBox.addListener("places_changed", function() {
            const places = searchBox.getPlaces();
            if (places.length === 0) {
                return;
            }

            const place = places[0];
            if (!place.geometry || !place.geometry.location) {
                return;
            }

            const position = place.geometry.location;

            marker.setPosition(position);
            map.setCenter(position);
            map.setZoom(15);

            updateLatLngInputs(position.lat(), position.lng());
        });
    }

    function updateLatLngInputs(lat, lng) {
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;
    }

    window.onload = function() {
        initMap();
    };
</script>