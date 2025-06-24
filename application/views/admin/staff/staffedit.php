<link href="<?php echo base_url(); ?>backend/multiselect/css/jquery.multiselect.css" rel="stylesheet">
<script src="<?php echo base_url(); ?>backend/multiselect/js/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>backend/multiselect/js/jquery.multiselect.js"></script>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <form id="form1" id="employeeform" name="employeeform" method="post" accept-charset="utf-8"
                        enctype="multipart/form-data">
                        <div class="box-body">
                            <div class="tshadow mb25 bozero">
                                <h4 class="pagetitleh2"><?php echo $this->lang->line('staff_basic_information'); ?>
                                </h4>
                                <div class="around10">
                                    <?php if ($this->session->flashdata('msg')) { ?> <div>
                                            <?php echo $this->session->flashdata('msg') ?> </div>
                                    <?php $this->session->unset_userdata('msg');
                                    }   ?>
                                    <?php echo $this->customlib->getCSRF(); ?>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label
                                                    for="exampleInputEmail1"><?php echo $this->lang->line('staff_first_name'); ?></label><small
                                                    class="req"> *</small>
                                                <input type='hidden' name="id" id="id" value="<?= $staff["id"] ?>">
                                                <input type='hidden' name="employee_id" id="employee_id"
                                                    value="<?= $staff["employee_id"] ?>">
                                                <input type="hidden" name="image_old"
                                                    value="<?php echo $staff["image"] ?>">
                                                <input type="hidden" name="resume_old"
                                                    value="<?php echo $staff["resume"] ?>">
                                                <input type="hidden" name="joining_letter_old"
                                                    value="<?php echo $staff["joining_letter"] ?>">
                                                <input type="hidden" name="other_document_file_old"
                                                    value="<?php echo $staff["other_document_file"] ?>">
                                                <input type="hidden" name="resignation_letter_old"
                                                    value="<?php echo $staff["resignation_letter"] ?>">
                                                <input id="firstname" name="name" placeholder="" type="text"
                                                    class="form-control" value="<?php echo $staff["name"] ?>"
                                                    onkeypress="return /[a-zA-Z]/.test(String.fromCharCode(event.which));" />
                                                <span class="text-danger"><?php echo form_error('name'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label
                                                    for="exampleInputEmail1"><?php echo $this->lang->line('staff_last_name'); ?></label><small
                                                    class="req"> *</small>
                                                <input id="surname" name="surname" placeholder="" type="text"
                                                    class="form-control" value="<?php echo $staff["surname"] ?>"
                                                    onkeypress="return /[a-zA-Z]/.test(String.fromCharCode(event.which));" />
                                                <span class="text-danger"><?php echo form_error('surname'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label
                                                    for="exampleInputEmail1"><?php echo $this->lang->line('staff_father_name'); ?></label>
                                                <input id="father_name" name="father_name" placeholder="" type="text"
                                                    class="form-control" value="<?php echo $staff["father_name"] ?>"
                                                    onkeypress="return /[a-zA-Z]/.test(String.fromCharCode(event.which));" />
                                                <span
                                                    class="text-danger"><?php echo form_error('father_name'); ?></span>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label
                                                    for="exampleInputEmail1"><?php echo $this->lang->line('staff_mother_name'); ?></label>
                                                <input id="mother_name" name="mother_name" placeholder="" type="text"
                                                    class="form-control" value="<?php echo $staff["mother_name"] ?>"
                                                    onkeypress="return /[a-zA-Z]/.test(String.fromCharCode(event.which));" />
                                                <span
                                                    class="text-danger"><?php echo form_error('mother_name'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputFile">
                                                    <?php echo $this->lang->line('staff_gender'); ?></label><small
                                                    class="req"> *</small>
                                                <select class="form-control" name="gender">
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php
                                                    foreach ($genderList as $key => $value) {
                                                    ?>
                                                        <option value="<?php echo $key; ?>"
                                                            <?php if ($staff['gender'] == $key) echo "selected"; ?>>
                                                            <?php echo $value; ?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('gender'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label
                                                    for="exampleInputEmail1"><?php echo $this->lang->line('staff_marital_status'); ?></label>
                                                <select class="form-control" name="marital_status">
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php foreach ($marital_status as $makey => $mavalue) {
                                                    ?>
                                                        <option <?php
                                                                if ($staff["marital_status"] == $mavalue) {
                                                                    echo "selected";
                                                                }
                                                                ?> value="<?php echo $mavalue; ?>">
                                                            <?php echo $mavalue; ?>
                                                        </option>
                                                    <?php } ?>

                                                </select>
                                                <span
                                                    class="text-danger"><?php echo form_error('marital_status'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label
                                                    for="exampleInputEmail1"><?php echo $this->lang->line('staff_blood_group'); ?></label>
                                                <select class="form-control" name="blood_group">
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php
                                                    print_r($staff["blood_group"]);
                                                    ?>
                                                    <?php foreach ($bloodgroup as $bgkey => $bgvalue) {
                                                    ?>
                                                        <option <?php
                                                                if ($staff["blood_group"] == $bgkey) {
                                                                    echo "selected";
                                                                }
                                                                ?> value="<?php echo $bgkey ?>"><?php echo $bgvalue ?>
                                                        </option>

                                                    <?php } ?>

                                                </select>
                                                <span
                                                    class="text-danger"><?php echo form_error('blood_group'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label
                                                    for="exampleInputEmail1"><?php echo $this->lang->line('staff_date_of_birth'); ?></label><small
                                                    class="req"> *</small>
                                                <input id="dob" name="dob" placeholder="" type="text"
                                                    class="form-control date" value="<?php
                                                                                        if (!empty($staff["dob"])) {
                                                                                            echo date($this->customlib->getHospitalDateFormat(), strtotime($staff["dob"]));
                                                                                        }
                                                                                        ?>" readonly="readonly" />
                                                <span class="text-danger"><?php echo form_error('dob'); ?></span>
                                            </div>
                                        </div>
                                        <!-- <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Password</label><small class="req"> *</small>
                                                <input id="password" name="Password" placeholder="" type="text" class="form-control" value="<?php echo $staff["password"] ?>" readonly />
                                                <span class="text-danger"><?php echo form_error('dob'); ?></span>
                                            </div>
                                        </div> -->
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label
                                                    for="language_select"><?php echo $this->lang->line('languages'); ?></label>
                                                <select id="language_select" name="languages[]" class="form-control"
                                                    multiple>

                                                </select>
                                                <span class="text-danger"><?php echo form_error('languages'); ?></span>

                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label
                                                    for="exampleInputEmail1"><?php echo $this->lang->line('staff_role'); ?></label><small
                                                    class="req"> *</small>
                                                <select id="role" name="role" class="form-control">
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php
                                                    foreach ($getStaffRole as $key => $role) {
                                                    ?>
                                                        <option value="<?php echo $role["id"] ?>" <?php
                                                                                                    if ($staff["user_type"] == $role["type"]) {
                                                                                                        echo "selected";
                                                                                                    }
                                                                                                    ?>>
                                                            <?php echo $role["type"] ?></option>
                                                    <?php }
                                                    ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('role'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label
                                                    for="exampleInputEmail1"><?php echo $this->lang->line('staff_designation'); ?></label><small
                                                    class="req"> *</small>

                                                <select id="designation" name="designation" placeholder="" type="text"
                                                    class="form-control">
                                                    <option value=""><?php echo $this->lang->line('select') ?></option>
                                                    <?php foreach ($designation as $key => $value) {
                                                    ?>
                                                        <option value="<?php echo $value["id"] ?>" <?php
                                                                                                    if ($staff["staff_designation_id"] == $value["id"]) {
                                                                                                        echo "selected";
                                                                                                    }
                                                                                                    ?>>
                                                            <?php echo $value["designation"] ?></option>
                                                    <?php }
                                                    ?>
                                                </select>
                                                <span
                                                    class="text-danger"><?php echo form_error('designation'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label
                                                    for="exampleInputEmail1"><?php echo $this->lang->line('staff_department'); ?></label><small
                                                    class="req"> *</small>
                                                <select id="department" name="department" placeholder="" type="text"
                                                    class="form-control">
                                                    <option value=""><?php echo $this->lang->line('select') ?></option>
                                                    <?php foreach ($department as $key => $value) {
                                                    ?>
                                                        <option value="<?php echo $value["id"] ?>" <?php
                                                                                                    if ($staff["department_id"] == $value["id"]) {
                                                                                                        echo "selected";
                                                                                                    }
                                                                                                    ?>>
                                                            <?php echo $value["department_name"] ?></option>
                                                    <?php }
                                                    ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('department'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label
                                                    for="exampleInputEmail1"><?php echo $this->lang->line('staff_specialist'); ?></label><small
                                                    class="req"> *</small>
                                                <?php
                                                if (is_string($specialist_list)) {
                                                    $specialist_list = json_decode($specialist_list, true);
                                                }

                                                $specialistarray = [];
                                                if (is_array($specialist_list)) {
                                                    foreach ($specialist_list as $specialist_list_value) {
                                                        $specialistarray[] = $specialist_list_value;
                                                    }
                                                } else {
                                                    // echo "Error: \$specialist_list is not an array.";
                                                }
                                                ?>

                                                <select id="specialistOpt" name="specialist[]" class="form-control"
                                                    multiple>
                                                    <?php foreach ($specialist as $dkey => $dvalue): ?>
                                                        <option value="<?php echo htmlspecialchars($dvalue['id']); ?>" <?php
                                                                                                                        if (in_array($dvalue['id'], $specialistarray)) {
                                                                                                                            echo "selected";
                                                                                                                        }
                                                                                                                        ?>>
                                                            <?php echo htmlspecialchars($dvalue['specialist_name']); ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>




                                                <span class="text-danger"><?php echo form_error('specialist'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label
                                                    for="exampleInputEmail1"><?php echo $this->lang->line('staff_date_of_joining'); ?></label><small
                                                    class="req"> *</small>
                                                <input id="date_of_joining" name="date_of_joining" placeholder=""
                                                    type="text" class="form-control date" value="<?php if ($staff["date_of_joining"] != '0000-00-00' && $staff["date_of_joining"] != "") {
                                                                                                        echo date($this->customlib->getHospitalDateFormat(), strtotime($staff["date_of_joining"]));
                                                                                                    }
                                                                                                    ?>" />
                                                <span
                                                    class="text-danger"><?php echo form_error('date_of_joining'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label
                                                    for="mobileno"><?php echo $this->lang->line('staff_phone'); ?></label><small
                                                    class="req"> *</small>
                                                <input id="mobileno" name="contactno" placeholder="Enter phone number"
                                                    type="text" class="form-control"
                                                    value="<?php echo $staff['contact_no'] ?>" maxlength="10"
                                                    pattern="\d{10}" title="Please enter exactly 10 digits" required />
                                                <input id="editid" name="editid" placeholder="" type="hidden"
                                                    class="form-control" value="<?php echo $staff['id']; ?>" />
                                                <span class="text-danger"><?php echo form_error('contactno'); ?></span>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label
                                                    for="emgmobileno"><?php echo $this->lang->line('staff_emergency_contact'); ?></label>
                                                <input id="emgmobileno" name="emgcontactno"
                                                    placeholder="Enter emergency contact" type="text"
                                                    class="form-control"
                                                    value="<?php echo $staff['emergency_contact_no'] ?>" maxlength="10"
                                                    pattern="\d{10}" title="Please enter exactly 10 digits" />
                                                <span
                                                    class="text-danger"><?php echo form_error('emgcontactno'); ?></span>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label
                                                    for="email"><?php echo $this->lang->line('staff_email'); ?></label><small
                                                    class="req"> *</small>
                                                <input id="email" name="email" placeholder="Enter email address"
                                                    type="email" class="form-control"
                                                    value="<?php echo $staff['email'] ?>"
                                                    pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                                                    title="Please enter a valid email address" required />
                                                <span class="text-danger"><?php echo form_error('email'); ?></span>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <?php
                                                if ($staff["image"]) {
                                                    $userdata = $this->session->userdata('hospitaladmin');
                                                    $accessToken = $userdata['accessToken'] ?? '';
                                                    $url = "https://phr-api.plenome.com/file_upload/getDocs";
                                                    $client = curl_init($url);
                                                    curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
                                                    curl_setopt($client, CURLOPT_POST, true);
                                                    curl_setopt($client, CURLOPT_POSTFIELDS, json_encode(['value' => $staff["image"]]));
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
                                                ?>
                                                <img src="<?= $logo_image ?>" alt="Staffimage"
                                                    style="border-radius:50%;width:20px">
                                                <label
                                                    for="exampleInputFile"><?php echo $this->lang->line('staff_photo'); ?></label>
                                                <div>
                                                    <input class="filestyle form-control" type="file" name="file"
                                                        id="file" size="20" accept="image/*" />
                                                </div>
                                                <span class="text-danger"><?php echo form_error('file'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label
                                                    for="exampleInputFile"><?php echo $this->lang->line('staff_current_address'); ?></label>
                                                <div><textarea name="address"
                                                        class="form-control"><?php echo $staff["local_address"] ?></textarea>
                                                </div>
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label
                                                    for="exampleInputFile"><?php echo $this->lang->line('staff_permanent_address'); ?></label>
                                                <div><textarea name="permanent_address"
                                                        class="form-control"><?php echo $staff["permanent_address"] ?></textarea>
                                                </div>
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>

                                        <div class="col-md-3">

                                            <div class="form-group">
                                                <label
                                                    for="exampleInputEmail1"><?php echo $this->lang->line('staff_qualification'); ?></label>
                                                <textarea id="qualification" name="qualification" placeholder=""
                                                    class="form-control"><?php echo $staff["qualification"] ?></textarea>
                                                <span
                                                    class="text-danger"><?php echo form_error('qualification'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">

                                            <div class="form-group">
                                                <label
                                                    for="exampleInputEmail1"><?php echo $this->lang->line('staff_work_experience'); ?></label>
                                                <textarea id="permanent_address" name="work_exp" placeholder=""
                                                    class="form-control"><?php echo $staff["work_exp"] ?></textarea>
                                                <span class="text-danger"><?php echo form_error('work_exp'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label
                                                    for="exampleInputFile"><?php echo $this->lang->line('staff_specialization'); ?></label>
                                                <div><textarea name="specialization"
                                                        class="form-control"><?php echo $staff["specialization"] ?></textarea>
                                                </div>
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label
                                                    for="exampleInputFile"><?php echo $this->lang->line('staff_note'); ?></label>
                                                <div><textarea name="note"
                                                        class="form-control"><?php echo $staff["note"] ?></textarea>
                                                </div>
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('pan_number'); ?></label>
                                                <input id="pan_number" name="pan_number" placeholder="" type="text"
                                                    class="form-control" value="<?php echo $staff['pan_number']; ?>" />
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('national_identification_number'); ?></label>
                                                <input id="identification_number" name="identification_number"
                                                    placeholder="" type="text" class="form-control"
                                                    value="<?php echo $staff['identification_number']; ?>" />
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('local_identification_number'); ?></label>
                                                <input id="local_identification_number"
                                                    name="local_identification_number" placeholder="" type="text"
                                                    class="form-control"
                                                    value="<?php echo $staff['local_identification_number']; ?>" />
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>

                                        <div class="">
                                            <?php
                                            echo display_custom_fields('staff', $staff['id']);
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="box-group collapsed-box">
                                <div class="panel box box-success collapsed-box">
                                    <div class="box-header with-border">
                                        <a data-widget="collapse" data-original-title="Collapse" aria-expanded="false"
                                            class="collapsed btn boxplus">
                                            <i
                                                class="fa fa-fw fa-plus"></i><?php echo $this->lang->line('add_more_details'); ?>
                                        </a>
                                    </div>
                                    <div class="box-body" style="padding: 0;">
                                        <div class="tshadow-new">
                                            <h4 class="pagetitleh2"><?php echo $this->lang->line('staff_payroll'); ?>
                                            </h4>
                                            <div class="row around10">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label
                                                            for="exampleInputEmail1"><?php echo $this->lang->line('staff_epf_no'); ?></label>
                                                        <input id="epf_no" name="epf_no" placeholder="" type="text"
                                                            class="form-control"
                                                            value="<?php echo $staff["epf_no"] ?>" />
                                                        <span
                                                            class="text-danger"><?php echo form_error('epf_no'); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label
                                                            for="exampleInputEmail1"><?php echo $this->lang->line('staff_contract_type'); ?></label>
                                                        <select class="form-control" name="contract_type">
                                                            <option value=""><?php echo $this->lang->line('select') ?>
                                                            </option>
                                                            <?php foreach ($contract_type as $key => $value) { ?>
                                                                <option value="<?php echo $key ?>" <?php
                                                                                                    if ($staff["contract_type"] == $key) {
                                                                                                        echo "selected";
                                                                                                    }
                                                                                                    ?>>
                                                                    <?php echo $value ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <span
                                                            class="text-danger"><?php echo form_error('contract_type'); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label
                                                            for="exampleInputEmail1"><?php echo $this->lang->line('staff_basic_salary'); ?></label>
                                                        <input type="text" value="<?php echo $staff["basic_salary"] ?>"
                                                            id="basic_salary" class="form-control" name="basic_salary">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Salary Per Year</label>
                                                        <input id="yearsalary" name="yearsalary" placeholder=""
                                                            type="text" class="form-control"
                                                            value="<?php echo set_value('ctc') ?>" />
                                                        <span
                                                            class="text-danger"><?php echo form_error('location'); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label
                                                            for="exampleInputEmail1"><?php echo $this->lang->line('staff_work_shift'); ?></label>
                                                        <input id="shift" name="shift" placeholder="" type="text"
                                                            class="form-control"
                                                            value="<?php echo $staff["shift"] ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label
                                                            for="exampleInputEmail1"><?php echo $this->lang->line('staff_work_location'); ?></label>
                                                        <input id="location" name="location" placeholder="" type="text"
                                                            class="form-control"
                                                            value="<?php echo $staff["location"] ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label
                                                            for="exampleInputEmail1"><?php echo $this->lang->line('staff_date_of_leaving'); ?></label>
                                                        <input id="date_of_leaving" name="date_of_leaving"
                                                            placeholder="" type="text" class="form-control date" value="<?php
                                                                                                                        if ($staff["date_of_leaving"] != '0000-00-00' && $staff["date_of_leaving"] != '') {
                                                                                                                            echo date($this->customlib->getHospitalDateFormat(), strtotime($staff["date_of_leaving"]));
                                                                                                                        }
                                                                                                                        ?>" />
                                                        <span
                                                            class="text-danger"><?php echo form_error('date_of_leaving'); ?></span>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                        <div class="tshadow-new">
                                            <h4 class="pagetitleh2"><?php echo $this->lang->line('staff_leaves'); ?>
                                            </h4>
                                            <div class="row around10">
                                                <?php
                                                $j = 0;
                                                foreach ($leavetypeList as $key => $leave) {
                                                    # code...
                                                ?>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label
                                                                for="exampleInputEmail1"><?php echo $leave["type"]; ?></label>
                                                            <input id="" name="alloted_leave[]"
                                                                placeholder="<?php echo $this->lang->line('staff_number_of_leaves'); ?>"
                                                                type="text" class="form-control" value="<?php
                                                                                                        if (array_key_exists($j, $staffLeaveDetails)) {
                                                                                                            echo $staffLeaveDetails[$j]["alloted_leave"];
                                                                                                        }
                                                                                                        ?>" />
                                                            <input name="leave_type[]" placeholder="" type="hidden" readonly
                                                                class="form-control" value="<?php echo $leave["type"] ?>" />
                                                            <input name="altid[]" placeholder="" type="hidden" readonly
                                                                class="form-control" value="<?php
                                                                                            if (array_key_exists($j, $staffLeaveDetails)) {
                                                                                                echo $staffLeaveDetails[$j]["altid"];
                                                                                            }
                                                                                            ?>" />
                                                            <input name="leave_type_id[]" placeholder="" type="hidden"
                                                                class="form-control" value="<?php echo $leave["id"]; ?>" />
                                                            <span
                                                                class="text-danger"><?php echo form_error('ifsc_code'); ?></span>
                                                        </div>
                                                    </div>


                                                <?php
                                                    $j++;
                                                }
                                                ?>

                                            </div>
                                        </div>
                                        <div class="tshadow-new">
                                            <h4 class="pagetitleh2">
                                                <?php echo $this->lang->line('staff_bank_account_details'); ?>
                                            </h4>
                                            <div class="row around10">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label
                                                            for="exampleInputEmail1"><?php echo $this->lang->line('staff_account_title'); ?></label>
                                                        <input id="account_title" name="account_title" placeholder=""
                                                            type="text" class="form-control"
                                                            value="<?php echo $staff["account_title"] ?>" />
                                                        <span
                                                            class="text-danger"><?php echo form_error('staff_bank_account_number'); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label
                                                            for="exampleInputEmail1"><?php echo $this->lang->line('staff_bank_account_number'); ?></label>
                                                        <input id="bank_account_no" name="bank_account_no"
                                                            placeholder="" type="text" class="form-control"
                                                            value="<?php echo $staff["bank_account_no"] ?>" />
                                                        <span
                                                            class="text-danger"><?php echo form_error('bank_account_no'); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label
                                                            for="exampleInputEmail1"><?php echo $this->lang->line('staff_ifsc_code'); ?></label>
                                                        <input id="ifsc_code" name="ifsc_code" placeholder=""
                                                            type="text" class="form-control"
                                                            value="<?php echo $staff["ifsc_code"] ?>" />
                                                        <span
                                                            class="text-danger"><?php echo form_error('ifsc_code'); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label
                                                            for="exampleInputEmail1"><?php echo $this->lang->line('staff_bank_name'); ?></label>
                                                        <input id="bank_name" name="bank_name" placeholder=""
                                                            type="text" class="form-control"
                                                            value="<?php echo $staff["bank_name"] ?>" />
                                                        <span
                                                            class="text-danger"><?php echo form_error('bank_name'); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label
                                                            for="exampleInputEmail1"><?php echo $this->lang->line('staff_bank_branch_name'); ?></label>
                                                        <input id="bank_branch" name="bank_branch" placeholder=""
                                                            type="text" class="form-control"
                                                            value="<?php echo $staff["bank_branch"] ?>" />
                                                        <span
                                                            class="text-danger"><?php echo form_error('bank_branch'); ?></span>
                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                        <div class="tshadow-new">
                                            <h4 class="pagetitleh2">
                                                <?php echo $this->lang->line('staff_social_media_link'); ?>
                                            </h4>

                                            <div class="row around10">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label
                                                            for="exampleInputEmail1"><?php echo $this->lang->line('staff_facebook_url'); ?></label>
                                                        <input id="bank_account_no" name="facebook" placeholder=""
                                                            type="text" class="form-control"
                                                            value="<?php echo $staff["facebook"] ?>" />

                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label
                                                            for="exampleInputEmail1"><?php echo $this->lang->line('staff_twitter_url'); ?></label>
                                                        <input id="bank_account_no" name="twitter" placeholder=""
                                                            type="text" class="form-control"
                                                            value="<?php echo $staff["twitter"] ?>" />

                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label
                                                            for="exampleInputEmail1"><?php echo $this->lang->line('staff_linkedin_url'); ?></label>
                                                        <input id="bank_name" name="linkedin" placeholder="" type="text"
                                                            class="form-control"
                                                            value="<?php echo $staff["linkedin"] ?>" />

                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label
                                                            for="exampleInputEmail1"><?php echo $this->lang->line('staff_instagram_url'); ?></label>
                                                        <input id="instagram" name="instagram" placeholder=""
                                                            type="text" class="form-control"
                                                            value="<?php echo $staff["instagram"] ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id='upload_documents_hide_show'>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="tshadow-new">
                                                        <h4 class="pagetitleh2">
                                                            <?php echo $this->lang->line('staff_upload_documents'); ?>
                                                        </h4>

                                                        <div class="row around10">
                                                            <div class="col-md-6">
                                                                <table class="table">
                                                                    <tbody>
                                                                        <tr>
                                                                            <th style="width: 10px">#</th>
                                                                            <th><?php echo $this->lang->line('staff_title'); ?>
                                                                            </th>
                                                                            <th><?php echo $this->lang->line('staff_documents'); ?>
                                                                            </th>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>1.</td>
                                                                            <td><?php echo $this->lang->line('staff_resume'); ?>
                                                                            </td>
                                                                            <td>
                                                                                <input class="filestyle form-control"
                                                                                    type='file' name='first_doc'
                                                                                    id="doc1">
                                                                                <input class="form-control"
                                                                                    type='hidden' name='resume'
                                                                                    value="<?php echo $staff["resume"] ?>">
                                                                                <?php if (!empty(trim($staff['resume'])) && trim($staff['resume']) !== "NA"): ?>
                                                                                    <a href="javascript:void(0);"
                                                                                        onclick="downloadFile('<?php echo $staff['resume']; ?>')"
                                                                                        class="btn btn-light btn-sm">
                                                                                        <i class="fas fa-download"></i>
                                                                                    </a>
                                                                                <?php endif; ?>
                                                                                <span
                                                                                    class="text-danger"><?php echo form_error('first_doc'); ?></span>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>3.</td>
                                                                            <td><?php echo $this->lang->line('staff_resignation_letter'); ?>
                                                                            </td>
                                                                            <td>
                                                                                <input class="filestyle form-control"
                                                                                    type='file' name='third_doc'
                                                                                    id="doc3">
                                                                                <input class="form-control"
                                                                                    type='hidden'
                                                                                    name='resignation_letter'
                                                                                    value="<?php echo $staff["resignation_letter"] ?>">
                                                                                <?php if (!empty(trim($staff['resignation_letter'])) && trim($staff['resignation_letter']) !== "NA"): ?>
                                                                                    <a href="javascript:void(0);"
                                                                                        onclick="downloadFile('<?php echo $staff['resignation_letter']; ?>')"
                                                                                        class="btn btn-light btn-sm">
                                                                                        <i class="fas fa-download"></i>
                                                                                    </a>
                                                                                <?php endif; ?>
                                                                                <span
                                                                                    class="text-danger"><?php echo form_error('third_doc'); ?></span>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <table class="table">
                                                                    <tbody>
                                                                        <tr>
                                                                            <th style="width: 10px">#</th>
                                                                            <th><?php echo $this->lang->line('staff_title'); ?>
                                                                            </th>
                                                                            <th><?php echo $this->lang->line('staff_documents'); ?>
                                                                            </th>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>2.</td>
                                                                            <td><?php echo $this->lang->line('staff_joining_letter'); ?>
                                                                            </td>
                                                                            <td>
                                                                                <input class="filestyle form-control"
                                                                                    type='file' name='second_doc'
                                                                                    id="doc2">
                                                                                <input class="form-control"
                                                                                    type='hidden' name='joining_letter'
                                                                                    value="<?php echo $staff["joining_letter"] ?>">
                                                                                <?php if (!empty(trim($staff['joining_letter'])) && trim($staff['joining_letter']) !== "NA"): ?>
                                                                                    <a href="javascript:void(0);"
                                                                                        onclick="downloadFile('<?php echo $staff['joining_letter']; ?>')"
                                                                                        class="btn btn-light btn-sm">
                                                                                        <i class="fas fa-download"></i>
                                                                                    </a>
                                                                                <?php endif; ?>
                                                                                <span
                                                                                    class="text-danger"><?php echo form_error('second_doc'); ?></span>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>4.</td>
                                                                            <td>
                                                                                <?php echo $this->lang->line('staff_other_documents'); ?>
                                                                                <input type="hidden" name='fourth_title'
                                                                                    value="<?php echo $staff["other_document_file"] ?>"
                                                                                    class="form-control"
                                                                                    placeholder="Other Documents">
                                                                            </td>
                                                                            <td>
                                                                                <input class="filestyle form-control"
                                                                                    type='file' name='fourth_doc'
                                                                                    id="doc4">
                                                                                <input class="form-control"
                                                                                    type='hidden'
                                                                                    name='other_document_file'
                                                                                    value="<?php echo $staff["other_document_file"] ?>">
                                                                                <?php if (!empty(trim($staff['other_document_file'])) && trim($staff['other_document_file']) !== "NA"): ?>
                                                                                    <a href="javascript:void(0);"
                                                                                        onclick="downloadFile('<?php echo $staff['other_document_file']; ?>')"
                                                                                        class="btn btn-light btn-sm">
                                                                                        <i class="fas fa-download"></i>
                                                                                    </a>
                                                                                <?php endif; ?>
                                                                                <span
                                                                                    class="text-danger"><?php echo form_error('fourth_doc'); ?></span>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-info pull-right"><i class="fa fa-check-circle"></i>
                                <?php echo $this->lang->line('save'); ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
</div>
</section>
</div>
<?php
$data = $this->session->userdata('hospitaladmin');
$api_base_url = $this->config->item('api_base_url');
?>
<script>
    $(document).ready(function() {
        $('#form1').on('submit', function(event) {
            event.preventDefault();
            var $submitButton = $(this).find('button[type="submit"]');
            $submitButton.prop('disabled', true).text('Loading...');
            var formData = $(this).serialize();
            var jsonObject = {};
            var errorMessages = [];
            var password = $('#password').val();
            formData.split('&').forEach(function(pair) {
                var parts = pair.split('=');
                var key = decodeURIComponent(parts[0]);
                var value = decodeURIComponent(parts[1].replace(/\+/g, ' '));
                if (key.endsWith('[]')) {
                    key = key.slice(0, -2);
                    if (!jsonObject[key]) {
                        jsonObject[key] = [];
                    }
                    jsonObject[key].push(value);
                } else {
                    jsonObject[key] = value;
                }
            });
            if (!jsonObject.name) errorMessages.push('First Name is required');
            if (!jsonObject.surname) errorMessages.push('Last Name is required');
            if (!jsonObject.gender) errorMessages.push('Gender is required');
            if (!jsonObject.dob) errorMessages.push('Date of Birth is required');
            if (!jsonObject.email) {
                errorMessages.push('Email is required');
            } else {
                var emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.(com|in|org)$/;
                if (!emailPattern.test(jsonObject.email)) {
                    errorMessages.push('Email must end with .com, .in, or .org');
                }
            }
            if (!jsonObject.specialist) errorMessages.push('Specialist is required');
            if (!jsonObject.date_of_joining) errorMessages.push('Date of Joining is required');
            if (!jsonObject.contactno) errorMessages.push('Phone Number is required');
            if (!jsonObject.role) errorMessages.push('Role is required');
            if (!jsonObject.designation) errorMessages.push('Designation is required');
            if (!jsonObject.department) errorMessages.push('Department is required');

            if (errorMessages.length > 0) {
                errorMsg(errorMessages.join('<br>'));
                $submitButton.prop('disabled', false).text('Submit');
                return;
            }
            var leaveTypes = [];
            $('.form-group').each(function() {
                var leaveTypeId = $(this).find('input[name="leave_type_id[]"]').val();
                var allottedLeave = $(this).find('input[name^="alloted_leave[]"]').val() || "0";
                if (leaveTypeId) {
                    leaveTypes.push({
                        "leave_type_id": parseInt(leaveTypeId, 10),
                        "alloted_leave": allottedLeave
                    });
                }
            });
            leaveTypes = leaveTypes.filter(function(leaveType) {
                return leaveType.alloted_leave !== "0";
            });
            var files = {
                image: $('#file')[0].files[0],
                resume: $('#doc1')[0].files[0],
                joining_letter: $('#doc2')[0].files[0],
                resignation_letter: $('#doc3')[0].files[0],
                other_document_file: $('#doc4')[0].files[0]
            };
            var fileUploadUrl = 'https://phr-api.plenome.com/file_upload';
            var uploadPromises = [];
            var fileKeys = {};

            function uploadFile(file, apiUrl, fileId) {
                return new Promise(function(resolve, reject) {
                    var formData = new FormData();
                    formData.append('file', file);
                    $.ajax({
                        url: apiUrl,
                        type: "POST",
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success') {
                                resolve({
                                    fileId: fileId,
                                    fileKey: response.data
                                });
                            } else {
                                reject('Upload failed: ' + response.message);
                            }
                        },
                        error: function(xhr, status, error) {
                            reject(error);
                        }
                    });
                });
            }
            var fileSizeErrors = [];
            var maxSizeImage = 5 * 1024 * 1024;
            var maxSizeOther = 10 * 1024 * 1024;
            if (files.image && files.image.size > maxSizeImage) {
                fileSizeErrors.push('Image file must be less than 5 MB.');
            }
            if (files.resume && files.resume.size > maxSizeOther) {
                fileSizeErrors.push('Resume file must be less than 10 MB.');
            }
            if (files.joining_letter && files.joining_letter.size > maxSizeOther) {
                fileSizeErrors.push('Joining letter must be less than 10 MB.');
            }
            if (files.resignation_letter && files.resignation_letter.size > maxSizeOther) {
                fileSizeErrors.push('Resignation letter must be less than 10 MB.');
            }
            if (files.other_document_file && files.other_document_file.size > maxSizeOther) {
                fileSizeErrors.push('Other document file must be less than 10 MB.');
            }
            if (fileSizeErrors.length > 0) {
                errorMsg(fileSizeErrors.join('<br>'));
                $submitButton.prop('disabled', false).text('Submit');
                return;
            }
            for (var key in files) {
                if (files[key]) {
                    uploadPromises.push(uploadFile(files[key], fileUploadUrl, key).then(function(response) {
                        fileKeys[response.fileId] = response.fileKey;
                    }).catch(function(error) {
                        errorMsg('Failed to upload ' + key + ': ' + error);
                    }));
                } else {
                    fileKeys[key] = $('input[name="' + key + '_old"]').val() || "";
                }
            }
            Promise.all(uploadPromises).then(function() {
                try {
                    var mappedObject = {
                        "employee_id": jsonObject.employee_id,
                        "lang_id": null,
                        "department_id": jsonObject.department || null,
                        "staff_designation_id": jsonObject.designation || null,
                        "specialist": "[" + (Array.isArray(jsonObject.specialist) ? jsonObject
                            .specialist.join(",") : (typeof jsonObject.specialist ===
                                "string" ? jsonObject.specialist : "")) + "]",
                        "qualification": jsonObject.qualification || "",
                        "work_exp": jsonObject.work_exp || "",
                        "specialization": jsonObject.specialization || "",
                        "name": jsonObject.name || "",
                        "surname": jsonObject.surname || "",
                        "father_name": jsonObject.father_name || "",
                        "mother_name": jsonObject.mother_name || "",
                        "contact_no": jsonObject.contactno || "",
                        "emergency_contact_no": jsonObject.emgcontactno || "",
                        "email": jsonObject.email || "",
                        "dob": formatDate(jsonObject.dob) || "",
                        "marital_status": jsonObject.marital_status || "",
                        "date_of_joining": formatDateTime(jsonObject.date_of_joining) || "",
                        "date_of_leaving": formatDateTime(jsonObject.date_of_leaving) || null,
                        "local_address": jsonObject.address || "",
                        "permanent_address": jsonObject.permanent_address || "",
                        "note": jsonObject.note || "",
                        "image": fileKeys.image || "",
                        "password": $('#password').val() || "",
                        "gender": jsonObject.gender || "",
                        "blood_group": jsonObject.blood_group || 0,
                        "account_title": jsonObject.account_title || "",
                        "bank_account_no": jsonObject.bank_account_no || "",
                        "bank_name": jsonObject.bank_name || "",
                        "ifsc_code": jsonObject.ifsc_code || "",
                        "bank_branch": jsonObject.bank_branch || "",
                        "payscale": "",
                        "basic_salary": jsonObject.basic_salary || "",
                        "epf_no": jsonObject.epf_no || "",
                        "contract_type": jsonObject.contract_type || "",
                        "shift": jsonObject.shift || "",
                        "location": jsonObject.location || "",
                        "facebook": jsonObject.facebook || "",
                        "twitter": jsonObject.twitter || "",
                        "linkedin": jsonObject.linkedin || "",
                        "instagram": jsonObject.instagram || "",
                        "resume": fileKeys.resume || "",
                        "joining_letter": fileKeys.joining_letter || "",
                        "resignation_letter": fileKeys.resignation_letter || "",
                        "other_document_name": fileKeys.other_document_file || "",
                        "other_document_file": fileKeys.other_document_file || "",
                        "user_id": 0,
                        "is_active": 1,
                        "verification_code": "",
                        "zoom_api_key": "",
                        "zoom_api_secret": "",
                        "pan_number": jsonObject.pan_number || "",
                        "identification_number": jsonObject.identification_number || "",
                        "local_identification_number": jsonObject.local_identification_number ||
                            " ",
                        "Health_Professional_Registry": "",
                        "languagesKnown": "[" + (Array.isArray(jsonObject.languages) ?
                            jsonObject.languages.join(",") : (typeof jsonObject
                                .languages === "string" ? jsonObject.languages : "")) + "]",
                        "role_id": jsonObject.role,
                        "hospital_id": <?= $data['hospital_id'] ?>,
                        "leave_types": leaveTypes
                    };
                    var staff_id = $('#id').val();
                    sendAjaxRequest("<?= $api_base_url ?>human-resource-staff/" + staff_id,
                        "PATCH", mappedObject,
                        function(response) {
                            handleResponse(response);
                        });
                } catch (e) {
                    errorMsg(e.message);
                    $submitButton.prop('disabled', false).text('Submit');
                }
            }).catch(function(error) {
                errorMsg(error);
                $submitButton.prop('disabled', false).text('Submit');
            });
        });

        function formatDate(dateStr) {
            return dateStr ? dateStr.replace(/(\d{2})\/(\d{2})\/(\d{4})/, "$3-$1-$2") + " 18:30:00" : "";
        }

        function formatDateTime(dateTimeStr) {
            return dateTimeStr ? dateTimeStr.replace(/(\d{2})\/(\d{2})\/(\d{4})/, "$3-$1-$2") + " 18:30:00" : "";
        }
    });
</script>

<script type="text/javascript" src="<?php echo base_url(); ?>backend/dist/js/savemode.js"></script>
<script>
    $('#specialistOpt').multiselect({
        columns: 1,
        placeholder: 'Select Specialist',
        search: true
    });
</script>
<script>
    $(document).ready(function() {
        $.ajax({
            url: '<?= $api_base_url ?>setting-languages',
            type: 'GET',
            dataType: 'json',
            headers: {
                'Authorization': accesstoken
            },
            success: function(response) {
                if (Array.isArray(response)) {
                    var select = $('#language_select');
                    select.empty();
                    var knownLanguages = <?= json_encode($staff["languagesKnown"]) ?>;
                    response.forEach(function(language) {
                        if (language.is_deleted !== 'yes') {
                            var option = new Option(language.language, language.id);
                            select.append(option);
                            if (knownLanguages.includes(language.id)) {
                                option.selected = true;
                            }
                        }
                    });
                    select.multiselect({
                        columns: 1,
                        placeholder: 'Select Languages',
                        search: true
                    });
                } else {
                    console.error('Unexpected response format:', response);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching languages:', error);
            }
        });
    });
</script>
<script>
    function downloadFile(fileName) {
        const url = "https://phr-api.plenome.com/file_upload/getDocs";

        fetch(url, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    value: fileName
                }),
            })
            .then((response) => {
                if (!response.ok) {
                    throw new Error("No response from server.");
                }
                return response.text();
            })
            .then((base64File) => {
                const binaryData = atob(base64File.trim());
                const byteArray = new Uint8Array(binaryData.length);
                for (let i = 0; i < binaryData.length; i++) {
                    byteArray[i] = binaryData.charCodeAt(i);
                }

                const fileInfo = fileName.split(".");
                const fileExtension = fileInfo.pop().toLowerCase();
                const fileBaseName = fileInfo.join(".") || "download";

                const mimeTypes = {
                    jpg: "image/jpeg",
                    jpeg: "image/jpeg",
                    png: "image/png",
                    gif: "image/gif",
                    pdf: "application/pdf",
                    txt: "text/plain",
                    doc: "application/msword",
                    docx: "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
                    xlsx: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
                    xls: "application/vnd.ms-excel",
                    csv: "text/csv",
                    zip: "application/zip",
                };

                const mimeType = mimeTypes[fileExtension] || "application/octet-stream";

                const blob = new Blob([byteArray], {
                    type: mimeType
                });
                const downloadUrl = URL.createObjectURL(blob);
                const a = document.createElement("a");
                a.href = downloadUrl;
                a.download = `${fileBaseName}.${fileExtension}`;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                URL.revokeObjectURL(downloadUrl);
            })
            .catch((error) => {
                console.error("Error:", error);
                alert("An error occurred while downloading the file.");
            });
    }
</script>
<script>
    $(document).ready(function() {
        $('#ifsc_code').on('input', function() {
            let ifscid = $(this).val();
            if (ifscid) {
                $.ajax({
                    url: `https://ifsc.razorpay.com/${ifscid}`,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        $('#bank_name').val(response.BANK || '');
                        $('#bank_branch').val(response.BRANCH || '');
                    },
                    error: function() {
                        $('#bank_name').val('');
                        $('#bank_branch').val('');
                        errorMsg('Invalid IFSC Code or an error occurred. Please try again.');
                    }
                });
            } else {
                $('#bank_name').val('');
                $('#bank_branch').val('');
            }
        });
    });

    function calculateYearSalary() {
        let basic_salary = parseFloat($('#basic_salary').val()) || 0;
        let yearsalary = basic_salary * 12;
        $('#yearsalary').val(yearsalary);
    }

    $(document).ready(function() {
        calculateYearSalary();
        $('#basic_salary').on('input', function() {
            calculateYearSalary();
        });
    });
</script>