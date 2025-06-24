<style type="text/css">
    .datepicker.dropdown-menu {
        z-index: 1020 !important;
    }
</style>
<script>
    $(document).ready(function() {
        $('.date').datepicker({
            //   format: 'yyyy-mm-dd',
            endDate: new Date(),
            autoclose: true
        });
    });
</script>
<link href="<?php echo base_url(); ?>backend/multiselect/css/jquery.multiselect.css" rel="stylesheet">
<script src="<?php echo base_url(); ?>backend/multiselect/js/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>backend/multiselect/js/jquery.multiselect.js"></script>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <form id="employeeform" name="employeeform" method="post" accept-charset="utf-8"
                        enctype="multipart/form-data">
                        <div class="box-body">
                            <!-- <div class="alert alert-info">
                                <?php echo $this->lang->line('add_staff_message'); ?>
                            </div> -->
                            <div class="tshadow mb25 bozero">
                                <!-- <div class="box-tools pull-right pt3">
                                    <a class="btn btn-sm btn-primary mr3 mt-md-0"
                                        href="<?php echo base_url(); ?>admin/staff/import" autocomplete="off"><i
                                            class="fa fa-plus"></i> <?php echo $this->lang->line('import_staff') ?></a>

                                </div> -->
                                <h4 class="pagetitleh2"><?php echo $this->lang->line('staff_basic_information'); ?>
                                </h4>
                                <div class="around10">
                                    <?php if ($this->session->flashdata('msg')) { ?>
                                        <div> <?php echo $this->session->flashdata('msg') ?> </div>
                                    <?php $this->session->unset_userdata('msg');
                                    } ?>
                                    <?php echo $this->customlib->getCSRF(); ?>
                                    <div class="row">

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label
                                                    for="exampleInputEmail1"><?php echo $this->lang->line('staff_first_name'); ?></label><small
                                                    class="req"> *</small>
                                                <input id="name" name="name" placeholder="" type="text"
                                                    class="form-control" value="<?php echo set_value('name'); ?>"
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
                                                    class="form-control" value="<?php echo set_value('surname') ?>"
                                                    onkeypress="return /[a-zA-Z]/.test(String.fromCharCode(event.which));" />
                                                <span class="text-danger"><?php echo form_error('surname'); ?></span>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label
                                                    for="exampleInputEmail1"><?php echo $this->lang->line('staff_father_name'); ?></label>
                                                <input id="father_name" name="father_name" placeholder="" type="text"
                                                    class="form-control" value="<?php echo set_value('father_name') ?>"
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
                                                    class="form-control" value="<?php echo set_value('mother_name') ?>"
                                                    onkeypress="return /[a-zA-Z]/.test(String.fromCharCode(event.which));" />
                                                <span
                                                    class="text-danger"><?php echo form_error('mother_name'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
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
                                                            <?php echo set_select('gender', $key, set_value('gender')); ?>>
                                                            <?php echo $value; ?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('gender'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label
                                                    for="exampleInputEmail1"><?php echo $this->lang->line('staff_marital_status'); ?></label>
                                                <select class="form-control" name="marital_status">
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php foreach ($marital_status as $makey => $mavalue) {
                                                    ?>
                                                        <option value="<?php echo $mavalue ?>"
                                                            <?php echo set_select('marital_status', $mavalue, set_value('marital_status')); ?>>
                                                            <?php echo $mavalue; ?>
                                                        </option>

                                                    <?php } ?>

                                                </select>
                                                <span
                                                    class="text-danger"><?php echo form_error('marital_status'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label
                                                    for="exampleInputEmail1"><?php echo $this->lang->line('staff_blood_group'); ?></label>
                                                <select class="form-control" name="blood_group">
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php foreach ($bloodgroup as $bgkey => $bgvalue) {
                                                    ?>
                                                        <option value="<?php echo $bgkey ?>"
                                                            <?php echo set_select('blood_group', $bgvalue, set_value('blood_group')); ?>>
                                                            <?php echo $bgvalue ?></option>

                                                    <?php } ?>

                                                </select>
                                                <span
                                                    class="text-danger"><?php echo form_error('marital_status'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label
                                                    for="exampleInputEmail1"><?php echo $this->lang->line('staff_date_of_birth'); ?></label><small
                                                    class="req"> *</small>
                                                <input id="dob" name="dob" placeholder="" type="text"
                                                    class="form-control date" value="<?php echo set_value('dob') ?>" />
                                                <span class="text-danger"><?php echo form_error('dob'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Password</label><small class="req">
                                                    *</small>
                                                <input id="password" name="Password" placeholder="" type="text"
                                                    class="form-control" value="<?php echo set_value('Password') ?>" />
                                                <span class="text-danger"><?php echo form_error('dob'); ?></span>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label
                                                    for="language_select"><?php echo $this->lang->line('languages'); ?></label>
                                                <select id="language_select" name="languages[]" class="form-control"
                                                    multiple>
                                                    <!-- Options will be populated here via JavaScript -->
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
                                                    ` <?php foreach ($roles as $key => $role) {
                                                        ?>
                                                        <option value="<?php echo $role['id'] ?>"
                                                            <?php echo set_select('role', $role['id'], set_value('role')); ?>>
                                                            <?php echo $role["name"] ?>
                                                        </option>
                                                        <?php }
                                                        ?>`
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
                                                        <option value="<?php echo $value["id"] ?>"
                                                            <?php echo set_select('designation', $value['id'], set_value('designation')); ?>>
                                                            <?php echo $value["designation"] ?>
                                                        </option>
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
                                                        <option value="<?php echo $value["id"] ?>"
                                                            <?php echo set_select('department', $value['id'], set_value('department')); ?>>
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
                                                <select id="specialistOpt" name="specialist[]" placeholder=""
                                                    type="text" class="form-control" multiple>
                                                    <?php foreach ($specialist as $key => $value) {
                                                    ?>
                                                        <option value="<?php echo $value["id"] ?>"
                                                            <?php echo set_select('specialist', $value['id'], set_value('specialist')); ?>>
                                                            <?php echo $value["specialist_name"] ?></option>
                                                    <?php }
                                                    ?>
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
                                                    type="text" class="form-control date"
                                                    value="<?php echo set_value('date_of_joining') ?>" />
                                                <span
                                                    class="text-danger"><?php echo form_error('date_of_joining'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label
                                                    for="mobileno"><?php echo $this->lang->line('staff_phone'); ?></label><small
                                                    class="req"> *</small>
                                                <input id="mobileno" name="contactno" placeholder="" type="tel"
                                                    class="form-control" value="<?php echo set_value('contactno') ?>"
                                                    maxlength="10" pattern="[0-9]{10}" inputmode="numeric"
                                                    onkeyup="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);"
                                                    title="Please enter a valid 10-digit mobile number." />


                                                <span class="text-danger"><?php echo form_error('contactno'); ?></span>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label
                                                    for="emgmobileno"><?php echo $this->lang->line('staff_phone'); ?></label>
                                                <label
                                                    for="emgmobileno"><?php echo $this->lang->line('staff_emergency_contact'); ?></label>
                                                <input id="emgmobileno" name="emgcontactno" placeholder="" type="tel"
                                                    class="form-control" pattern="[0-9]{10}" inputmode="numeric"
                                                    value="<?php echo set_value('emgcontactno') ?>" maxlength="10"
                                                    onkeyup="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);"
                                                    title="Please enter a valid 10-digit mobile number." />

                                                <span
                                                    class="text-danger"><?php echo form_error('emgcontactno'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label
                                                    for="exampleInputEmail1"><?php echo $this->lang->line('staff_email'); ?></label><small
                                                    class="req"> *</small>
                                                <input id="email" name="email" placeholder="" type="text"
                                                    class="form-control" value="<?php echo set_value('email') ?>"
                                                    oninput="this.value = this.value.toLowerCase();" />
                                                <span class="text-danger"><?php echo form_error('email'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label
                                                    for="exampleInputFile"><?php echo $this->lang->line('staff_photo'); ?></label>
                                                <div>
                                                    <input class="filestyle form-control" type='file' name='file'
                                                        id="file" size='20' accept='image/*' />
                                                </div>
                                                <span class="text-danger"><?php echo form_error('file'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label
                                                    for="exampleInputFile"><?php echo $this->lang->line('staff_current_address'); ?>
                                                </label>
                                                <div><textarea name="address"
                                                        class="form-control"><?php echo set_value('address'); ?></textarea>
                                                </div>
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label
                                                    for="exampleInputFile"><?php echo $this->lang->line('staff_permanent_address'); ?></label>
                                                <div><textarea name="permanent_address"
                                                        class="form-control"><?php echo set_value('permanent_address'); ?></textarea>
                                                </div>
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">

                                            <div class="form-group">
                                                <label
                                                    for="exampleInputEmail1"><?php echo $this->lang->line('staff_qualification'); ?></label>
                                                <textarea id="qualification" name="qualification" placeholder=""
                                                    class="form-control"><?php echo set_value('qualification') ?></textarea>
                                                <span
                                                    class="text-danger"><?php echo form_error('qualification'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">

                                            <div class="form-group">
                                                <label
                                                    for="exampleInputEmail1"><?php echo $this->lang->line('staff_work_experience'); ?></label>
                                                <textarea id="work_exp" name="work_exp" placeholder=""
                                                    class="form-control"><?php echo set_value('work_exp') ?></textarea>
                                                <span class="text-danger"><?php echo form_error('work_exp'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label
                                                    for="exampleInputFile"><?php echo $this->lang->line('staff_specialization'); ?></label>
                                                <div><textarea name="specialization"
                                                        class="form-control"><?php echo set_value('specialization'); ?></textarea>
                                                </div>
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label
                                                    for="exampleInputFile"><?php echo $this->lang->line('staff_note'); ?></label>
                                                <div><textarea name="note"
                                                        class="form-control"><?php echo set_value('note'); ?></textarea>
                                                </div>
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('pan_number'); ?></label>
                                                <input id="pan_number" name="pan_number" placeholder="" type="text"
                                                    class="form-control"
                                                    value="<?php echo set_value('pan_number') ?>" />
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('national_identification_number'); ?></label>
                                                <input id="identification_number" name="identification_number"
                                                    placeholder="" type="text" class="form-control"
                                                    value="<?php echo set_value('identification_number') ?>" />
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('local_identification_number'); ?></label>
                                                <input id="local_identification_number"
                                                    name="local_identification_number" placeholder="" type="text"
                                                    class="form-control"
                                                    value="<?php echo set_value('local_identification_number') ?>" />
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="">

                                            <?php
                                            echo display_custom_fields('staff');
                                            ?>

                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="box-group collapsed-box">
                                <div class="panel box box-success collapsed-box">
                                    <div class="box-header with-border">
                                        <a data-widget="collapse" data-original-title="Collapse"
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
                                                        <label><?php echo $this->lang->line('staff_epf_no'); ?></label>
                                                        <input id="epf_no" name="epf_no" placeholder="" type="text"
                                                            class="form-control"
                                                            value="<?php echo set_value('epf_no') ?>" />
                                                        <span
                                                            class="text-danger"><?php echo form_error('epf_no'); ?></span>
                                                    </div>
                                                </div>


                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label><?php echo $this->lang->line('staff_basic_salary'); ?></label>
                                                        <input type="number" class="form-control" name="basic_salary"
                                                            id="basic_salary" pattern="[0-9]{10}"
                                                            value="<?php echo set_value('basic_salary') ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Salary Per Year</label>
                                                        <input id="yearsalary" name="yearsalary" placeholder=""
                                                            type="text" class="form-control"
                                                            value="<?php echo set_value('ctc') ?>" readonly />
                                                        <span
                                                            class="text-danger"><?php echo form_error('location'); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label><?php echo $this->lang->line('staff_contract_type'); ?></label>
                                                        <select class="form-control" name="contract_type">
                                                            <option value=""><?php echo $this->lang->line('select'); ?>
                                                            </option>
                                                            <?php foreach ($contract_type as $key => $value) { ?>
                                                                <option value="<?php echo $key ?>"
                                                                    <?php echo set_select('contract_type', $key, set_value('contract_type')); ?>>
                                                                    <?php echo $value ?>
                                                                </option>

                                                            <?php } ?>


                                                        </select>
                                                        <span
                                                            class="text-danger"><?php echo form_error('contract_type'); ?></span>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label><?php echo $this->lang->line('staff_work_shift'); ?></label>
                                                        <input id="shift" name="shift" placeholder="" type="text"
                                                            class="form-control"
                                                            value="<?php echo set_value('shift') ?>" />
                                                        <span
                                                            class="text-danger"><?php echo form_error('shift'); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label><?php echo $this->lang->line('staff_work_location'); ?></label>
                                                        <input id="location" name="location" placeholder="" type="text"
                                                            class="form-control"
                                                            value="<?php echo set_value('location') ?>" />
                                                        <span
                                                            class="text-danger"><?php echo form_error('location'); ?></span>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="tshadow-new">
                                            <h4 class="pagetitleh2"><?php echo $this->lang->line('staff_leaves'); ?>
                                            </h4>
                                            <div class="row around10">
                                                <?php
                                                foreach ($leavetypeList as $key => $leave) {
                                                    # code...
                                                ?>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label
                                                                for="exampleInputEmail1"><?php echo $leave["type"]; ?></label>

                                                            <input name="leave_type[]" type="hidden" readonly
                                                                class="form-control" value="<?php echo $leave['id'] ?>" />
                                                            <input name="alloted_leave_<?php echo $leave['id'] ?>"
                                                                placeholder="<?php echo $this->lang->line('staff_number_of_leaves'); ?>"
                                                                value="<?php echo set_value('alloted_leave_' . $leave['id']); ?>"
                                                                type="number" class="form-control"
                                                                oninput="if(this.value.length > 4) this.value = this.value.slice(0, 4);" />

                                                            <span
                                                                class="text-danger"><?php echo form_error('alloted_leave'); ?></span>
                                                        </div>
                                                    </div>



                                                <?php } ?>
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
                                                            value="<?php echo set_value('account_title') ?>" />
                                                        <span
                                                            class="text-danger"><?php echo form_error('account_title'); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label
                                                            for="exampleInputEmail1"><?php echo $this->lang->line('bank_account_no'); ?></label>
                                                        <input id="bank_account_no" name="bank_account_no"
                                                            placeholder="" type="text" class="form-control"
                                                            value="<?php echo set_value('bank_account_no') ?>" />
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
                                                            value="<?php echo set_value('ifsc_code') ?>" />
                                                        <span class="text-danger" id="ifsc_danger"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label
                                                            for="exampleInputEmail1"><?php echo $this->lang->line('staff_bank_name'); ?></label>
                                                        <input id="bank_name" name="bank_name" placeholder=""
                                                            type="text" class="form-control"
                                                            value="<?php echo set_value('bank_name') ?>" />
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
                                                            value="<?php echo set_value('bank_branch') ?>" />
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
                                                            value="<?php echo set_value('facebook') ?>" />
                                                        <span
                                                            class="text-danger"><?php echo form_error('facebook'); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label
                                                            for="exampleInputEmail1"><?php echo $this->lang->line('staff_twitter_url'); ?></label>
                                                        <input id="bank_account_no" name="twitter" placeholder=""
                                                            type="text" class="form-control"
                                                            value="<?php echo set_value('twitter') ?>" />
                                                        <span
                                                            class="text-danger"><?php echo form_error('twitter_profile'); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label
                                                            for="exampleInputEmail1"><?php echo $this->lang->line('staff_linkedin_url'); ?></label>
                                                        <input id="bank_name" name="linkedin" placeholder="" type="text"
                                                            class="form-control"
                                                            value="<?php echo set_value('linkedin') ?>" />
                                                        <span
                                                            class="text-danger"><?php echo form_error('linkedin'); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label
                                                            for="exampleInputEmail1"><?php echo $this->lang->line('staff_instagram_url'); ?></label>
                                                        <input id="instagram" name="instagram" placeholder=""
                                                            type="text" class="form-control"
                                                            value="<?php echo set_value('instagram') ?>" />

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
                                                            <div class="col-md-6 col-lg-6 col-sm-12">
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
                                                                            <td class="tabletter">
                                                                                <?php echo $this->lang->line('staff_resume'); ?>
                                                                            </td>
                                                                            <td>
                                                                                <input class="filestyle form-control"
                                                                                    type='file' name='first_doc'
                                                                                    id="doc1" />
                                                                                <span
                                                                                    class="text-danger"><?php echo form_error('first_doc'); ?></span>
                                                            </div>
                                                            </td>
                                                            </tr>


                                                            </tbody>
                                                            </table>
                                                        </div>
                                                        <div class="col-md-6 col-lg-6 col-sm-12">
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
                                                                        <td class="tabletter">
                                                                            <?php echo $this->lang->line('staff_joining_letter'); ?>
                                                                        </td>
                                                                        <td>
                                                                            <input class="filestyle form-control"
                                                                                type='file' name='second_doc' id="doc2">
                                                                            <span
                                                                                class="text-danger"><?php echo form_error('second_doc'); ?></span>
                                                        </div>
                                                        </td>
                                                        </tr>


                                                        </tbody>
                                                        </table>
                                                    </div>

                                                    <div class="col-md-6 col-lg-6 col-sm-12">
                                                        <table class="table">
                                                            <tbody>
                                                                <tr>
                                                                    <td style="width: 10px">3.</td>
                                                                    <td class="tabletter">
                                                                        <?php echo $this->lang->line('staff_other_documents'); ?><input
                                                                            type="hidden" name='fourth_title'
                                                                            class="form-control"
                                                                            placeholder="Other Documents">
                                                                    </td>
                                                                    <td>
                                                                        <input class="filestyle form-control"
                                                                            type='file' name='fourth_doc' id="doc4" />
                                                                        <span
                                                                            class="text-danger"><?php echo form_error('fourth_doc'); ?></span>
                                                    </div>
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
            <button type="submit" id="addsatff" class="btn btn-info pull-right"><i class="fa fa-check-circle"></i>
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
                        $('#ifsc_danger').html(' ');
                    },
                    error: function() {
                        $('#bank_name').val('');
                        $('#bank_branch').val('');
                        $('#ifsc_danger').html('Invalid IFSC Code');
                    }
                });
            } else {
                $('#bank_name').val('');
                $('#bank_branch').val('');
            }
        });

        $('#basic_salary').on('input', function() {
            let basic_salary = parseFloat($('#basic_salary').val()) || 0;
            let yearsalary = basic_salary * 12;
            $('#yearsalary').val(yearsalary);
        });
    });
    $(document).ready(function() {
        $('#employeeform').on('submit', function(event) {
            event.preventDefault();
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
            if (!jsonObject.Password) errorMessages.push('Password is required');
            var passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
            if (password && !passwordPattern.test(password)) {
                errorMessages.push(
                    'Password must be at least 8 characters long, contain at least one uppercase letter, one lowercase letter, one number, and one special character.'
                );
            }
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
                return;
            }
            var leaveTypes = [];
            $('.form-group').each(function() {
                var leaveTypeId = $(this).find('input[name="leave_type[]"]').val();
                var allottedLeave = $(this).find('input[name^="alloted_leave_"]').val();
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
                }
            }
            Promise.all(uploadPromises).then(function() {
                try {
                    var mappedObject = {
                        "lang_id": null,
                        "department_id": jsonObject.department || "",
                        "staff_designation_id": jsonObject.designation || "",
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
                        "dob": formatDateTime(jsonObject.dob) || "",
                        "marital_status": jsonObject.marital_status || "",
                        "date_of_joining": formatDateTime(jsonObject.date_of_joining) || "",
                        "date_of_leaving": null,
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
                        "resignation_letter": "",
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
                            "",
                        "health_professional_registry": "",
                        "languagesKnown": "[" + (Array.isArray(jsonObject.languages) ?
                            jsonObject.languages.join(",") : (typeof jsonObject
                                .languages === "string" ? jsonObject.languages : "")) + "]",
                        "role_id": jsonObject.role || "",
                        "hospital_id": <?= $data['hospital_id'] ?>,
                        "leave_types": leaveTypes || ""
                    };
                    sendAjaxRequest("<?= $api_base_url ?>human-resource-staff",
                        "POST", mappedObject,
                        function(response) {
                            handleResponse(response);
                        });
                } catch (e) {
                    errorMsg(e.message);
                }
            }).catch(function(error) {
                errorMsg(error);
            });
        });
        function formatDate(dateStr) {
            return dateStr.replace(/(\d{2})\/(\d{2})\/(\d{4})/, "$3-$1-$2");
        }
        function formatDateTime(dateTimeStr) {
            return dateTimeStr.replace(/(\d{2})\/(\d{2})\/(\d{4})/, "$3-$1-$2") + " 18:30:00";
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
                    select.empty(); // Clear existing options

                    response.forEach(function(language) {
                        if (language.is_deleted !==
                            'yes') { // Optionally filter out deleted items
                            select.append(new Option(language.language, language.id));
                        }
                    });

                    // Initialize multiselect after populating options
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
    const nameInput = document.getElementById('name');
    const dobInput = document.getElementById('dob');
    const passwordInput = document.getElementById('password');

    function generatePassword() {
        const nameInput = document.getElementById('name');
        const dobInput = document.getElementById('dob');
        const passwordInput = document.getElementById('password');

        const name = nameInput.value.trim().toLowerCase();
        const dob = dobInput.value;

        if (name && dob) {
            const formattedDob = dob.replace(/[-/]/g, '').slice(0, 8);
            const password = `${name.charAt(0).toUpperCase()}${name.slice(1)}@${formattedDob}`;
            passwordInput.value = password;
        } else {
            passwordInput.value = '';
        }
    }
    $(dobInput).on('changeDate', generatePassword);
</script>