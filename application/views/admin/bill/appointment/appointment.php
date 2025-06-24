<?php
$currency_symbol = $this->customlib->getHospitalCurrencyFormat();
$genderList      = $this->customlib->getGender_Patient();
?>
<style>
.bootstrap-datetimepicker-widget {
    overflow: visible !important
}
</style>
<script>
$(document).ready(function() {
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    $('#datetimepicker').datetimepicker({
        minDate: today,
        ignoreReadonly: true,
    });
    $('#rdates').datetimepicker({
        minDate: today,
        ignoreReadonly: true,
    });
});
</script>
<style>
.card-header {
    background-color: #3788d8;
    color: white;
    border-radius: 8px 8px 0 0 !important;
}

.btn-primary {
    background-color: #3788d8;
    border-color: #3788d8;
}

.btn-primary:hover {
    background-color: #2d6fb7;
    border-color: #2d6fb7;
}

.nav-tabs .nav-link {
    color: #495057;
    border: 1px solid transparent;
    border-top-left-radius: 0.25rem;
    border-top-right-radius: 0.25rem;
    padding: 0.5rem 1rem;
}

.nav-tabs .nav-link.active {
    color: #3788d8;
    background-color: #fff;
    border-color: #dee2e6 #dee2e6 #fff;
    font-weight: bold;
}

.table thead th {
    background-color: #f2f7fd;
    border-bottom: 2px solid #dee2e6;
}

.table-actions {
    display: flex;
    justify-content: flex-end;
    gap: 5px;
}

.badge-success {
    background-color: #28a745;
}

.badge-warning {
    background-color: #ffc107;
    color: #212529;
}

.badge-danger {
    background-color: #dc3545;
}
</style>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border d-flex justify-content-between align-items-center">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('appointment_details'); ?></h3>
                        <div class="box-tools align-items-center gap-2">
                            <?php if ($this->rbac->hasPrivilege('appointment', 'can_add')) { ?>
                            <a data-toggle="modal" data-target="#myModal" class="btn btn-primary btn-sm addappointment">
                                <i class="fa fa-plus"></i> <?php echo $this->lang->line('add_appointment'); ?>
                            </a>
                            <?php } ?>
                            <a href="<?php echo base_url("admin/onlineappointment/patientschedule"); ?>"
                                class="btn btn-primary btn-sm">
                                <i class="fa fa-reorder"></i> <?php echo $this->lang->line('doctor_wise'); ?>
                            </a>
                            <!-- <i class="fa fa-plus"></i> -->
                            <select id="filterDue" class="btn btn-primary btn-sm" style="display:none">
                                <option value="all">All</option>
                                <option value="Due">Due</option>
                                <option value="NoDue">No Due</option>
                            </select>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="download_label mb-3 font-weight-bold">
                            <?php echo $this->lang->line('appointed_patient_list'); ?>
                        </div>
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" href="#today" data-tab="today" data-toggle="tab">
                                    <?php echo $this->lang->line('today'); ?>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#upcoming" data-tab="upcoming" data-toggle="tab">
                                    Upcoming
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#history" data-tab="history" data-toggle="tab">
                                    History
                                </a>
                            </li>
                        </ul>

                        <div class="table-responsive mt-3">
                            <table class="table table-striped table-bordered table-hover ajaxlist"
                                data-export-title="<?php echo $this->lang->line('appointment_details'); ?>">
                                <thead class="thead-dark">
                                    <tr>
                                        <th><?php echo $this->lang->line('patient_name'); ?></th>
                                        <th><?php echo $this->lang->line('appointment_no'); ?></th>
                                        <th class="text-center"><?php echo $this->lang->line('doctor'); ?></th>
                                        <th class="text-center">Token No</th>
                                        <th class="text-center"><?php echo $this->lang->line('appointment_date'); ?>
                                        </th>
                                        <th class="text-center">Slot</th>
                                        <th class="text-center">Paid</th>
                                        <th class="text-center"><?php echo $this->lang->line('status'); ?></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="myModal" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close pt4" data-dismiss="modal">&times;</button>
                <div class="row">
                    <div class="col-sm-8 col-xs-8">
                        <div class="row">
                            <div class="col-lg-10 col-md-10 col-sm-5 col-xs-9">
                                <div class="p-2 select2-full-width">
                                    <select class="form-control patient_list_ajax" form="formadd" id="addpatient_id"
                                        name="patient_id">
                                        <option value="" disabled selected>Select Patient</option>
                                    </select>

                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-3 col-xs-1">
                                <div class="p-2">
                                    <?php if ($this->rbac->hasPrivilege('patient', 'can_add')) { ?>
                                    <a data-toggle="modal" id="add" onclick="holdModal('myModalpa')"
                                        class="modalbtnpatient"><i class="fa fa-plus"></i>
                                        <span><?php echo $this->lang->line('new_patient'); ?></span></a>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--./col-sm-8-->
                </div><!-- ./row -->
            </div>
            <form id="formadd" accept-charset="utf-8" method="post">
                <div class="">
                    <div class="modal-body pb0">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label
                                                for="exampleInputFile"><?php echo $this->lang->line('doctor'); ?></label>
                                            <small class="req"> *
                                            </small>
                                            <div>
                                                <select class="form-control select2 doctor_select2" name="doctor"
                                                    onchange="getDoctorShift(this);getDoctorFees(this)" <?php
                                                    if ((isset($disable_option)) && ($disable_option == true)) {
                                                        echo 'disabled';
                                                    }
                                                    ?> name='doctor' id="doctorid" style="width:100%">
                                                    <option value="<?php echo set_value('doctor'); ?>">
                                                        <?php echo $this->lang->line('select') ?></option>
                                                    <?php foreach ($doctors as $dkey => $dvalue) {
                                                    ?>
                                                    <option value="<?php echo $dvalue["id"]; ?>" <?php
                                                                                                        if ($doctor_select == $dvalue['id']) {
                                                                                                            echo 'selected';
                                                                                                        }
                                                                                                        ?>>
                                                        <?php echo $dvalue["name"] . " " . $dvalue["surname"] . " (" . $dvalue["employee_id"] . ")" ?>
                                                    </option>
                                                    <?php } ?>
                                                </select>
                                                <input type="hidden" name="charge_id" value="" id="charge_id" />
                                            </div>
                                            <span class="text-danger"><?php echo form_error('doctor'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label
                                                for="doctor_fees"><?php echo $this->lang->line("doctor_fees"); ?></label>
                                            <small class="req"> *</small>
                                            <div>
                                                <input type="text" name="amount" id="doctor_fees" class="form-control"
                                                    readonly>
                                            </div>
                                            <span class="text-danger"><?php echo form_error('doctor_fees'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="pwd"><?php echo $this->lang->line('shift'); ?></label><span
                                                class="req"> *</span>
                                            <select name="global_shift_id" id="global_shift" class="select2"
                                                style="width: 100%;" onchange="getShift()">
                                                <option value=""><?= $this->lang->line('select'); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group" style="position: relative; overflow:visible !important">
                                            <label><?php echo $this->lang->line('appointment_date'); ?></label>
                                            <small class="req"> *</small>
                                            <input type="text" id="datetimepicker" name="date"
                                                class="form-control datetime" readonly>
                                            <span class="text-danger"><?php echo form_error('date'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="slot"><?php echo $this->lang->line('slot'); ?></label>
                                            <span class="req"> *</span>
                                            <select name="shift_id" id="slot" onchange="validateTime(this)"
                                                class="form-control">
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            </select>
                                            <span class="text-danger"><?php echo form_error('slot'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label
                                                for="exampleInputFile"><?php echo $this->lang->line('appointment_priority'); ?></label>
                                            <div>
                                                <select class="form-control select2 appointment_priority_select2"
                                                    name="priority" style="width:100%">
                                                    <option value="" disabled selected>Select</option>
                                                    <?php foreach ($appoint_priority_list as $dvalue) { ?>
                                                    <option value="<?= $dvalue["id"]; ?>">
                                                        <?= $dvalue["priority_status"]; ?></option>
                                                    <?php } ?>
                                                </select>

                                            </div>
                                            <span class="text-danger"><?php echo form_error('doctor'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('payment_mode'); ?></label>
                                            <select class="form-control payment_mode" name="payment_mode">
                                                <?php foreach ($payment_mode as $key => $value) { ?>
                                                <option value="<?php echo $key ?>"><?php echo $value ?></option>
                                                <?php } ?>
                                            </select>
                                            <span class="text-danger"><?php echo form_error('apply_charge'); ?></span>
                                        </div>
                                    </div>
                                    <!-- <div class="col-sm-3">
                      <div class="form-group">
                        <label for="appointment_status"><?php echo $this->lang->line('status'); ?><small class="req"> *</small></label>
                        <select name="appointment_status" onchange="appointmentstatus()" class="form-control" id="appointment_status">
                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                            <?php foreach ($appointment_status as $appointment_status_key => $appointment_status_value) {  ?>
                            <option value="<?php echo $appointment_status_key ?>"><?php echo ucfirst($appointment_status_key) ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div> -->
                                    <div class="cheque_div" style="display: none;">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('cheque_no'); ?></label><small
                                                    class="req"> *</small>
                                                <input type="text" name="cheque_no" id="cheque_no" class="form-control">
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('cheque_date'); ?></label><small
                                                    class="req"> *</small>
                                                <input type="text" name="cheque_date" id="cheque_date"
                                                    class="form-control date">
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('attach_document'); ?></label>
                                                <input type="file" class="filestyle form-control" name="document"
                                                    id="cheque_pic">
                                                <span class="text-danger"><?php echo form_error('document'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="form-group">
                                            <label for="message"><?php echo $this->lang->line('message'); ?> </label>
                                            <textarea name="message" id="note" class="form-control"></textarea>
                                            <span class="text-danger"><?php echo form_error('message'); ?></span>
                                        </div>
                                    </div>
                                    <?php if ($this->module_lib->hasActive('live_consultation')) { ?>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label
                                                for="exampleInputFile"><?php echo $this->lang->line('live_consultant_on_video_conference'); ?></label>
                                            <small class="req">*</small>
                                            <div>
                                                <select name="live_consult" id="live_consult" class="form-control">
                                                    <?php foreach ($yesno_condition as $yesno_key => $yesno_value) {
                                                        ?>
                                                    <option value="<?php echo $yesno_key ?>" <?php
                                                                                                        if ($yesno_key == 'no') {
                                                                                                            echo "selected";
                                                                                                        }
                                                                                                        ?>>
                                                        <?php echo $yesno_value ?>
                                                    </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <span class="text-danger"><?php echo form_error('live_consult'); ?></span>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <div class="">
                                        <?php echo display_custom_fields('appointment'); ?>
                                    </div>
                                </div>
                                <!--./row-->
                            </div>
                            <!--./col-md-12-->
                        </div>
                        <!--./row-->
                    </div>
                    <!--./modal-body-->
                </div>
                <div class="modal-footer">
                    <div class="pull-right">
                        <button type="submit" id="formaddbtn" name="save"
                            data-loading-text="<?php echo $this->lang->line('processing') ?>" class="btn btn-info"><i
                                class="fa fa-check-circle"></i> <?php echo $this->lang->line('save'); ?></button>
                    </div>
                    <div class="pull-right" style="margin-right: 10px; ">
                        <button type="submit" id="get_print"
                            data-loading-text="<?php echo $this->lang->line('processing') ?>" name="save_print"
                            class="btn btn-info pull-right printsavebtn"><i class="fa fa-print"></i>
                            <?php echo $this->lang->line('save_print'); ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- dd -->
<!--patient profile detials -->
<div class="modal fade" id="patientprofile" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog pup100" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-placement="bottom" data-toggle="tooltip"
                    title="<?php echo $this->lang->line('close'); ?>" data-dismiss="modal">&times;</button>

                <div class="modalicon">

                    <!-- <div id='edit_delete'>
                    
                        <?php if ($this->rbac->hasPrivilege('revisit', 'can_edit')) { ?>
                            <a href="#" data-placement="bottom" data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>" ><i class="fa fa-pencil"></i></a>
                            <?php
                        }
                        if ($this->rbac->hasPrivilege('revisit', 'can_delete')) {
                            ?>
                            <a href="#" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="<?php echo $this->lang->line('delete'); ?>"><i class="fa fa-trash"></i></a>
                        <?php } ?>
                    </div> -->
                </div>
                <h4 class="modal-title" id="modal_head"></h4>
                <div class="row">
                    <div class="col-sm-4 col-xs-6">
                        <div class="form-group15">
                        </div>
                    </div>
                    <!--./col-sm-4-->
                </div><!-- ./row -->
            </div>
            <!--./modal-header-->
            <div class="pup-scroll-area">
                <div class="modal-body pt0 pb0">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <form id="formadd" accept-charset="utf-8"
                                action="<?php echo base_url() . "admin/patient" ?>" enctype="multipart/form-data"
                                method="post">
                                <input class="" name="id" type="hidden" id="patientid">
                                <div class="row row-eq">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="row ptt10">
                                            <div class="col-lg-12">
                                                <div class="singlelist24bold pb10">
                                                    <span id="patient_name"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-9 col-sm-9 col-xs-9" id="Myinfo">
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                                        <ul class="singlelist">
                                                            <li>
                                                                <i class="fas fa-user-secret" data-toggle="tooltip"
                                                                    data-placement="top"
                                                                    title="<?php echo $this->lang->line('guardian'); ?>"></i>
                                                                <span id="guardian"></span>
                                                            </li>
                                                        </ul>
                                                        <ul class="multilinelist">
                                                            <li>
                                                                <img id="gender_icon" src="" alt="Gender Icon"
                                                                    data-toggle="tooltip" data-placement="top"
                                                                    title="<?php echo $this->lang->line('gender'); ?>"
                                                                    style="width: 20px; height: 20px;">

                                                                <span id="genders"></span>
                                                            </li>
                                                            <li>
                                                                <i class="fas fa-tint" data-toggle="tooltip"
                                                                    data-placement="top"
                                                                    title="<?php echo $this->lang->line('blood_group'); ?>"></i>
                                                                <span id="blood_group"></span>
                                                            </li>
                                                            <li>
                                                                <i class="fas fa-ring" data-toggle="tooltip"
                                                                    data-placement="top"
                                                                    title="<?php echo $this->lang->line('marital_status'); ?>"></i>
                                                                <span id="marital_status"></span>
                                                            </li>
                                                        </ul>
                                                        <ul class="singlelist">
                                                            <li>
                                                                <i class="fas fa-hourglass-half" data-toggle="tooltip"
                                                                    data-placement="top"
                                                                    title="<?php echo $this->lang->line('age'); ?>"></i>
                                                                <span id="age"></span>
                                                            </li>
                                                            <li>
                                                                <i class="fa fa-phone-square" data-toggle="tooltip"
                                                                    data-placement="top"
                                                                    title="<?php echo $this->lang->line('phone'); ?>"></i>
                                                                <span id="contact"></span>
                                                            </li>
                                                            <li>
                                                                <i class="fa fa-envelope" data-toggle="tooltip"
                                                                    data-placement="top"
                                                                    title="<?php echo $this->lang->line('email'); ?>"></i>
                                                                <span id="email"></span>
                                                            </li>
                                                            <li>
                                                                <i class="fas fa-street-view" data-toggle="tooltip"
                                                                    data-placement="top"
                                                                    title="<?php echo $this->lang->line('address'); ?>"></i>
                                                                <span id="address"></span>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                                        <ul class="singlelist">
                                                            <li>
                                                                <b><?php echo $this->lang->line('any_known_allergies') ?>
                                                                </b>
                                                                <span id="allergies"></span>
                                                            </li>
                                                            <li>
                                                                <b><?php echo $this->lang->line('remarks') ?> </b>
                                                                <span id="notedetials"></span>
                                                            </li>
                                                            <li>
                                                                <b><?php echo $this->lang->line('tpa_id'); ?> </b>
                                                                <span id="insurance_id"></span>
                                                            </li>
                                                            <li>
                                                                <b><?php echo $this->lang->line('tpa_validity'); ?> </b>
                                                                <span id="validity"></span>
                                                            </li>
                                                            <li>
                                                                <b><?php echo $this->lang->line('national_identification_number'); ?>
                                                                </b>
                                                                <span id="identification_number"></span>
                                                            </li>
                                                            <li id="field_data">
                                                                <b><span id="vcustom_name"></span></b>
                                                                <span id="vcustom_value"></span>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div><!-- ./col-md-9 -->
                                            <div class="col-lg-3 col-md-3 col-sm-12">
                                                <?php $file = "uploads/patient_images/no_image.png"; ?>
                                                <img class="profile-user-img img-responsive"
                                                    src="<?php echo base_url() . $file . img_time() ?>" id="image"
                                                    alt="User profile picture">
                                            </div><!-- ./col-md-3 -->
                                        </div>
                                        <div id="visit_report_id"></div>
                                    </div>
                                    <!--./col-md-8-->
                                </div>
                                <!--./row-->
                            </form>
                        </div>
                        <!--./col-md-12-->
                    </div>
                    <!--./row-->
                </div>
            </div>
        </div>
    </div>
</div>
<!--endprofile-->


<div class="modal fade" id="rescheduleModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('reschedule'); ?></h4>
            </div>
            <form id="rescheduleform" accept-charset="utf-8" method="post">
                <div class="">
                    <div class="modal-body pb0">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="row">
                                    <input type="hidden" name="appointment_id" id="appointment_id">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="exampleInputFile">
                                                <?php echo $this->lang->line('doctor'); ?></label>
                                            <small class="req"> *</small>
                                            <div>
                                                <select class="form-control"
                                                    onchange="getDoctorShiftedit(this);getDoctorFeesEdit(this)"
                                                    style="width:100%" id="rdoctor">
                                                    <option value="<?php echo set_value('doctor'); ?>">
                                                        <?php echo $this->lang->line('select') ?></option>
                                                    <?php foreach ($doctors as $dkey => $dvalue) {
                                                    ?>
                                                    <option value="<?php echo $dvalue["id"]; ?>">
                                                        <?php echo $dvalue["name"] . " " . $dvalue["surname"] . " (" . $dvalue["employee_id"] . ")" ?>
                                                    </option>
                                                    <?php } ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('rdoctor'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label
                                                for="doctor_fees"><?php echo $this->lang->line("doctor_fees"); ?></label>
                                            <small class="req"> *</small>
                                            <div>
                                                <input type="text" name="doctor_fees" id="rdoctor_fees_edit"
                                                    class="form-control" readonly="readonly">
                                            </div>
                                            <span class="text-danger"><?php echo form_error('doctor_fees'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="pwd"><?php echo $this->lang->line('shift'); ?></label><span
                                                class="req"> *</span>
                                            <select name="global_shift_id" id="rglobal_shift_edit"
                                                onchange="getreschsduleShift()" class="select2" style="width:100%">
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            </select>
                                            <span class="text-danger"><?php echo form_error('rglobal_shift'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('appointment_date') ?></label>
                                            <small class="req"> *</small>
                                            <input type="text" id="rdates" name="date" class="form-control datetime"
                                                value="<?php echo set_value('dates'); ?>" readonly>
                                            <span
                                                class="text-danger"><?php echo form_error('appointment_date'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="slot"><?php echo $this->lang->line('slot'); ?></label>
                                            <span class="req"> *</span>
                                            <select name="shift_id" id="rslot_edit" class="form-control">
                                                <option value="" disabled selected>
                                                    <?php echo $this->lang->line('select'); ?></option>
                                            </select>
                                            <input type="hidden" id="rslot_edit_field" />
                                            <span class="text-danger"><?php echo form_error('rslot'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="exampleInputFile">
                                                <?php echo $this->lang->line('appointment_priority'); ?></label>
                                            <div>
                                                <select class="form-control select2" name="priority" style="width:100%"
                                                    id="edit_appoint_priority">
                                                    <option value="" disabled selected>Select</option>
                                                    <?php foreach ($appoint_priority_list as $dvalue) { ?>
                                                    <option value="<?= $dvalue["id"]; ?>">
                                                        <?= $dvalue["priority_status"]; ?></option>
                                                    <?php } ?>
                                                </select>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label
                                                for="appointment_status"><?php echo $this->lang->line('status'); ?><small
                                                    class="req"> *</small></label>
                                            <select name="appointment_status1" onchange="editappointmentstatus()"
                                                class="form-control" id="edit_appointment_status">
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                <?php foreach ($appointment_status as $appointment_status_key => $appointment_status_value) { ?>
                                                <option value="<?php echo $appointment_status_value['id']; ?>"
                                                    <?php echo ($appointment_status_value['id'] == 6) ? 'disabled' : ''; ?>>
                                                    <?php echo $appointment_status_value['status']; ?>
                                                </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-8">
                                        <div class="form-group">
                                            <label for="message"><?php echo $this->lang->line('message'); ?></label>
                                            <textarea name="message" id="message"
                                                class="form-control"><?php echo set_value('message'); ?></textarea>
                                            <span class="text-danger"><?php echo form_error('message'); ?></span>
                                        </div>
                                    </div>
                                    <?php if ($this->module_lib->hasActive('live_consultation')) { ?>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('live_consultant_on_video_conference'); ?></label>
                                            <small class="req">*</small>
                                            <select name="live_consult" id="edit_liveconsult" class="form-control">
                                                <?php foreach ($yesno_condition as $yesno_key => $yesno_value) {
                                                    ?>
                                                <option value="<?php echo $yesno_key ?>" <?php
                                                                                                    if ($yesno_key == 'no') {
                                                                                                        echo "selected";
                                                                                                    }
                                                                                                    ?>>
                                                    <?php echo $yesno_value ?>
                                                </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <div class="" id="customfield"></div>
                                    <!-- <div class="" id="customfield" ></div>  -->
                                </div>
                                <!--./row-->
                            </div>
                            <!--./col-md-12-->
                        </div>
                        <!--./row-->
                    </div>
                    <!--./modal-body-->
                </div>
                <div class="modal-footer">
                    <div class="pull-right">
                        <button type="submit" id="rescheduleformbtn"
                            data-loading-text="<?php echo $this->lang->line('processing') ?>" class="btn btn-info"><i
                                class="fa fa-check-circle"></i> <?php echo $this->lang->line('save'); ?></button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="paymentTypeModal" tabindex="-1" role="dialog" aria-labelledby="paymentTypeLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentTypeLabel">Select Payment Type</h5>
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button> -->
            </div>
            <form id="paymentTypeForm" method="post">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="payment_type">Payment Type</label>
                                <select class="form-control payment_mode" name="payment_mode" id="balance_payment_mode">
                                    <?php foreach ($payment_mode as $key => $value) { ?>
                                    <option value="<?php echo $key ?>"><?php echo $value ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="amount">Amount</label>
                                <input type="number" class="form-control" id="balance_doctor_fees" name="amount"
                                    placeholder="Enter Amount" value="" readonly>
                                <input type="hidden" id="balance_appointment_status_id" name="appointment_status_id">
                                <input type="hidden" id="balance_appointment_status" name="appointment_status">
                                <input type="hidden" id="balance_doctor" name="doctor">
                                <input type="hidden" id="balance_appointment_id" name="appointment_id">
                                <input type="hidden" id="balance_global_shift_id" name="global_shift_id">
                                <input type="hidden" id="balance_shift_id" name="shift_id">
                                <input type="hidden" id="balance_priority" name="priority">
                                <input type="hidden" id="balance_source" name="source">
                                <input type="hidden" id="balance_date" name="balance_date">
                                <input type="hidden" id="balance_patient_id" name="patient_id">
                            </div>
                        </div>
                    </div>
                    <div class="row cheque_details" style="display: none;">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="cheque_no">Cheque Number</label>
                                <input type="text" class="form-control" id="cheque_no" name="cheque_no"
                                    placeholder="Enter Cheque Number">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="cheque_date">Cheque Date</label>
                                <input type="date" class="form-control" id="cheque_date" name="cheque_date">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="notes">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"
                            placeholder="Add any notes"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="paymentTypeFormbtn" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
                                <td colspan="5"><a id="opd_id" href="#" target="_blank"></a></td>
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
                                <th><?php echo $this->lang->line('tax'); ?>(<span id="tax_percentage"></span>%)</th>
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
                                <th><?php echo $this->lang->line('message'); ?></th>
                                <td><span id="messages"></span></td>
                            </tr>
                            <!-- <tr>
                <th><?php echo $this->lang->line('age'); ?></th>
                <td><span id='patient_age'></span></td>
              </tr> -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$data = $this->session->userdata('hospitaladmin');
$api_base_url = $this->config->item('api_base_url');
?>
<script>
function delete_record(id) {
    if (confirm('<?php echo $this->lang->line('delete_confirm'); ?>')) {
        $.ajax({
            url: '<?= $api_base_url ?>add-appointment/' + id + '?hos_id=1',
            type: 'DELETE',
            dataType: 'json',
            data: {
                hos_id: <?= $data['hospital_id'] ?>
            },
            success: function(res) {
                if (res.length > 0 && res[0].status === 'success') {
                    $('#viewModal').modal('hide');
                    successMsg(res[0].message);
                    table.ajax.reload(null, false);
                } else {
                    alert('Error: ' + (res[0] ? res[0].message : 'Unexpected response format.'));
                }
            },
            error: function() {
                alert('An error occurred whimle processing your request.');
            }
        });
    }
}
</script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
$(document).ready(function() {
    $('#formadd').on('submit', function(event) {
        event.preventDefault();
        let formData = new FormData(this);
        $('#formaddbtn').button('loading')
        $('#get_print').button('loading')
        let jsonData = {
            "Hospital_id": <?= $data['hospital_id'] ?>,
            "appointment_status_id": 3
        };
        formData.forEach((value, key) => {
            jsonData[key] = value;
        });

        jsonData["source"] = jsonData["live_consult"] === "no" ? "Offline" : "Online";

        const requiredFields = {
            "patient_id": "Patient",
            "doctor": "Doctor",
            "global_shift_id": "Shift",
            "date": "Date",
            "shift_id": "Slot"
        };

        let missingFields = [];
        Object.keys(requiredFields).forEach(field => {
            if (!jsonData[field]) {
                missingFields.push(requiredFields[field]);
            }
        });

        if (missingFields.length > 0) {
            errorMsg(`The following fields are required: ${missingFields.join(', ')}`);
            setTimeout(() => $('#formaddbtn').button('reset'), 3000);
            setTimeout(() => $('#get_print').button('reset'), 3000);
            return;
        }

        let dateObj = new Date(jsonData["date"]);
        jsonData["date"] = dateObj.toISOString().split('T')[0];

        const shiftId = jsonData["shift_id"];
        const optionText = $('#slot_' + shiftId).text();
        const timeOnly = optionText.split(' ')[0];
        jsonData["time"] = timeOnly;

        jsonData["priority"] = parseInt(jsonData["priority"]);
        jsonData["received_by_name"] = '<?=$data['username'] ?>';
        jsonData["payment_date"] = new Date().toISOString().slice(0, 19).replace("T", " ");
        let action = event.originalEvent.submitter.id;

        $.ajax({
            url: '<?= $api_base_url ?>check-for-duplicate-appointment',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                "patient_id": jsonData["patient_id"],
                "doctor": jsonData["doctor"],
                "date": jsonData["date"],
                "Hospital_id": <?= $data['hospital_id'] ?>,
                "shift_id": jsonData["shift_id"]
            }),
            success: function(response) {
                if (response.is_duplicate_appointment) {
                    errorMsg(
                        "This appointment is already scheduled. Please choose a different time."
                    );
                    setTimeout(() => $('#formaddbtn').button('reset'), 3000);
                    setTimeout(() => $('#get_print').button('reset'), 3000);
                } else {
                    startProcess(jsonData, action === "get_print");
                }
            },
            error: function() {
                errorMsg("Error submitting form. Please try again.");
            }
        });
    });

    function startProcess(jsonData, printAfterSave) {
        if (jsonData["payment_mode"] === 'Online') {
            setTimeout(() => $('#formaddbtn').button('reset'), 3000);
            setTimeout(() => $('#get_print').button('reset'), 3000);
            handlePayment(jsonData, printAfterSave).then((paymentSuccess) => {
                if (paymentSuccess) {
                    jsonData["payment_gateway"] = 'razorpay';
                    jsonData["payment_id"] = paymentSuccess.payment_id;
                    jsonData["payment_reference_number"] = paymentSuccess.reference_id;
                    submitForm(jsonData, printAfterSave);
                } else {
                    errorMsg("Payment failed. Please try again.");
                }
            });
        } else {
            submitForm(jsonData, printAfterSave);
        }
    }

    function submitForm(data, printAfterSave) {
        try {
            $.ajax({
                url: '<?= $api_base_url ?>add-appointment',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(data),
                success: function(response) {
                    setTimeout(() => $('#formaddbtn').button('reset'), 3000);
                    setTimeout(() => $('#get_print').button('reset'), 3000);
                    try {
                        if (response[0].status === 'success') {
                            successMsg(response[0]?.message || response[0]?.messege);
                            let id = response[0]?.inserted_details[0]?.id;
                            if (printAfterSave) {
                                $('.ajaxlist').DataTable().ajax.reload();
                                $('#myModal').modal('hide');
                                printAppointment(id);
                            } else {
                                location.reload();
                            }
                        } else {
                            errorMsg(response.message);
                            setTimeout(() => $('#formaddbtn').button('reset'), 3000);
                            setTimeout(() => $('#get_print').button('reset'), 3000);
                        }
                    } catch (error) {
                        console.error("Error processing response:", error);
                        errorMsg("An error occurred while processing the response.");
                        setTimeout(() => $('#formaddbtn').button('reset'), 3000);
                        setTimeout(() => $('#get_print').button('reset'), 3000);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("API Error:", xhr.responseText || error);
                    errorMsg("Error submitting form. Please try again.");
                    setTimeout(() => $('#formaddbtn').button('reset'), 3000);
                    setTimeout(() => $('#get_print').button('reset'), 3000);
                }
            });
        } catch (error) {
            console.error("Unexpected Error:", error);
            errorMsg("An unexpected error occurred.");
            setTimeout(() => $('#formaddbtn').button('reset'), 3000);
            setTimeout(() => $('#get_print').button('reset'), 3000);
        }
    }

});
</script>
<script>
function printAppointment(id) {
    $('#myModal').on('hidden.bs.modal', function() {
        $(this).find('form').trigger('reset');
        $("#global_shift").select2().select2("val", '');
    });
    $.ajax({
        url: base_url + 'admin/appointment/printAppointmentBill',
        type: "POST",
        data: {
            'appointment_id': id
        },
        dataType: 'json',
        success: function(data) {
            popup(data.page);
        },
        error: function(xhr) {
            alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
        }
    });
}
</script>
<script>
function viewreschedule(id) {
    $('#rescheduleModal').modal('show');
    $('#appointment_id').val(id);

    $.ajax({
        url: baseurl + 'admin/appointment/getDetailsAppointment',
        type: "GET",
        data: {
            appointment_id: id
        },
        dataType: 'json',
        success: function(data) {
            $('#customfield').html(data.custom_fields_value);
            $("#rdoctor").val(data.doctor).trigger("change");

            let [date] = data.date.split(" ");
            let time = data.time.slice(0, 5);
            $("#rdates").val(`${date} ${time}`);

            $("#rslot_edit_field").val(data.shift_id);
            $("#edit_appoint_priority").val(data.priority).trigger("change");
            $("#message").val(data.message);
            $("#edit_appointment_status").val(data.appointment_status_id);

            getDoctorShift("", data.doctor, data.global_shift_id);

            let doctorSelect = $('select[id="rdoctor"]');
            doctorSelect.prop('disabled', doctorSelect.find(`option[value="${data.doctor}"]`).length > 0);

            $('select[id="edit_liveconsult"]').val(data.live_consult);
            $('select[id="edit_appointment_status"]').val(data.appointment_status_id);
        },
        error: function(xhr, status, error) {
            console.error("Error fetching reschedule details:", error);
        }
    });
}

function viewDetail(id) {
    $("#viewModal").modal('hide');
    $("#field_data").html('<tr><td colspan="2">Loading...</td></tr>');

    function fetchAppointmentDetails(id, retries = 3) {
        $.ajax({
            url: baseurl + 'admin/appointment/getDetailsAppointment',
            type: "GET",
            data: {
                appointment_id: id
            },
            dataType: 'json',
            success: function(data) {
                if (!data || typeof data !== "object") {
                    if (retries > 0) {
                        setTimeout(() => fetchAppointmentDetails(id, retries - 1), 1000);
                    }
                    return;
                }

                let table_html = Array.isArray(data.field_data) ?
                    data.field_data.filter(obj => obj.visible_on_patient_panel == 1)
                    .map(obj => `
                            <tr>
                                <th width="15%"><span>${capitalizeFirstLetter($.trim(obj.name) || "-")}</span></th>
                                <td width="85%"><span>${$.trim(obj.field_value) || "-"}</span></td>
                            </tr>
                        `).join("") :
                    "";

                let appointmentIdPrefix = data.appointment_status === 'Requested' ? 'TEMP' : 'APPN';
                let appointmentId = `${appointmentIdPrefix}${$.trim(data.id) || "N/A"}`;

                $("#field_data").html(table_html);
                $("#appointmentno").html(appointmentId);
                $("#appointmentsno").html($.trim(data.appointment_serial_no) || "N/A");
                $("#dating").html(data.date ? new Date($.trim(data.date)).toLocaleDateString('en-GB') :
                    "-");
                $("#patient_names").html($.trim(data.patients_name) || "-");
                $("#view_genders").html($.trim(data.patients_gender) || "-");
                $("#emails").html($.trim(data.patient_email) || "-");
                $("#appointpriority").html($.trim(data.appoint_priority) || "-");
                $("#phones").html($.trim(data.patient_mobileno) || "-");
                $("#doctors").text(
                    [$.trim(data.name), $.trim(data.surname), $.trim(data.employee_id)]
                    .filter(Boolean).join(" ") || "-"
                );
                $("#department_name").html($.trim(data.department_name) || "-");
                $("#messages").html($.trim(data.message) || "-");
                $("#liveconsult").html($.trim(data.edit_live_consult) || "-");
                $("#global_shift_view").html($.trim(data.global_shift_name) || "-");
                $("#doctor_shift_view").text($.trim(data.doctor_shift_name) || $.trim(data.time) || "-");
                $("#source").html($.trim(data.source) || "-");
                $("#payment_note").html($.trim(data.payment_note) || "-");
                $("#patient_age").html($.trim(data.patient_age) || "-");

                let standard_charge = parseFloat(data.standard_charge) || 0;
                let additional_charge = parseFloat(data.additional_charge) || 0;
                let discount_amount = parseFloat(data.discount_amount) || 0;
                let sub_total = (standard_charge + additional_charge - discount_amount).toFixed(2);
                let tax_amount = (sub_total * ((parseFloat(data.tax) || 0) / 100)).toFixed(2);
                let net_amount = (parseFloat(sub_total) + parseFloat(tax_amount)).toFixed(2);
                let due_amount = (parseFloat(net_amount) - (parseFloat(data.amount) || 0)).toFixed(2);

                $("#fees").html(`<?php echo $currency_symbol; ?> ${standard_charge.toFixed(2)}`);
                $("#additional_charges").html(
                    `<?php echo $currency_symbol; ?> ${additional_charge.toFixed(2)}`);
                $("#discount").html(`<?php echo $currency_symbol; ?> ${discount_amount.toFixed(2)}`);
                $("#sub_total").html(`<?php echo $currency_symbol; ?> ${sub_total}`);
                $("#tax").html(`<?php echo $currency_symbol; ?> ${tax_amount}`);
                $("#tax_percentage").html(`<?php echo $currency_symbol; ?> ${data.tax || "0"}`);
                $("#discount_percentage").html(data.discount_percentage || "0");
                $("#net_amount").html(`<?php echo $currency_symbol; ?> ${net_amount}`);
                $("#due_amount").html(`<?php echo $currency_symbol; ?> ${due_amount}`);

                let moduleLinks = {
                    OPD: $.trim(data.redirect_link_opd),
                    IPD: $.trim(data.redirect_link_ipd),
                    APPOINTMENT: $.trim(data.redirect_link_opd)
                };

                let moduleId = {
                    OPD: "OPD NO",
                    IPD: "IPD NO",
                    APPOINTMENT: "OPD NO"
                };

                if (data.module in moduleLinks) {
                    $("#opd_id").html(
                        `<a href="${moduleLinks[data.module]}" target="_blank">${$.trim(data.opd_id) || $.trim(data.ipd_id) || "-"}</a>`
                    );
                    $("#module_name").html(moduleId[data.module]);
                } else {
                    $("#opd_id").html("-");
                    $("#module_name").html("-");
                }

                if (data.payment_mode === "Cheque") {
                    $("#payrow, #paydocrow").show();
                    $("#spn_chequeno").html($.trim(data.cheque_no) || "-");
                    $("#spn_chequedate").html($.trim(data.cheque_date) || "-");
                    $("#spn_doc").html($.trim(data.doc) || "-");
                } else {
                    $("#payrow, #paydocrow").hide();
                    $("#spn_chequeno, #spn_chequedate, #spn_doc").html("-");
                }

                $("#pay_amount").html(
                    `<?php echo $currency_symbol; ?> ${parseFloat(data.amount || 0).toFixed(2)}`);
                $("#payment_mode").html($.trim(data.payment_mode) || "-");
                $("#trans_id").html($.trim(data.transaction_id) || "-");

                const statusColors = {
                    "Requested": "#F59E0B",
                    "Reserved": "#FFC52F",
                    "Approved": "#00D65B",
                    "Cancelled": "#FF0600",
                    "InProcess": "#6070FF",
                    "Completed": "#00D65B"
                };
                let statusColor = statusColors[data.appointment_status] || "#000";
                $("#status").html(`
                    <small style="
                        display: inline-block; min-width: 80px; padding: 4px 8px; 
                        border-radius: 4px; background-color: ${statusColor}; 
                        color: #fff; font-size: 0.8em; line-height: 1.2; text-align: center;
                    ">
                        ${$.trim(data.appointment_status) || "-"}
                    </small>
                `);

                $("#edit_delete").html(`
                    <a href="#" data-toggle="tooltip" onclick="printAppointment(${id})" data-original-title="Print">
                        <i class="fa fa-print"></i>
                    </a>
                `);

                setTimeout(() => {
                    $("#viewModal").modal({
                        backdrop: 'static',
                        keyboard: false
                    }).modal('show');
                }, 500);
            },
            error: function(xhr, status, error) {
                if (retries > 0) {
                    setTimeout(() => fetchAppointmentDetails(id, retries - 1), 1000);
                }
            }
        });
    }

    fetchAppointmentDetails(id);
}
</script>
<script>
$(document).ready(function() {
    const url = window.location.href;
    const match = url.match(/index\/(\d+)/);

    if (match) {
        const appointmentId = match[1];
        viewDetail(appointmentId);
    }
});
</script>

<script>
$(document).on('change', '.payment_mode', function() {
    var mode = $(this).val();
    if (mode == "Cheque") {
        $('.filestyle', '#addPaymentModal').dropify();
        $('.cheque_div').css("display", "block");
    } else {
        $('.cheque_div').css("display", "none");
    }
});
</script>

<script type="text/javascript">
$(function() {
    $('#easySelectable').easySelectable();
})
</script>
<script type="text/javascript">
$(function() {
    $('.select2').select2()
});

function holdModal(modalId) {
    $('#' + modalId).modal({
        backdrop: 'static',
        keyboard: false,
        show: true
    });
}

(function($) {
    //selectable html elements
    $.fn.easySelectable = function(options) {
        var el = $(this);
        var options = $.extend({
            'item': 'li',
            'state': true,
            onSelecting: function(el) {

            },
            onSelected: function(el) {

            },
            onUnSelected: function(el) {

            }
        }, options);
        el.on('dragstart', function(event) {
            event.preventDefault();
        });
        el.off('mouseover');
        el.addClass('easySelectable');
        if (options.state) {
            el.find(options.item).addClass('es-selectable');
            el.on('mousedown', options.item, function(e) {
                $(this).trigger('start_select');
                var offset = $(this).offset();
                var hasClass = $(this).hasClass('es-selected');
                var prev_el = false;
                el.on('mouseover', options.item, function(e) {
                    if (prev_el == $(this).index())
                        return true;
                    prev_el = $(this).index();
                    var hasClass2 = $(this).hasClass('es-selected');
                    if (!hasClass2) {
                        $(this).addClass('es-selected').trigger('selected');
                        el.trigger('selected');
                        options.onSelecting($(this));
                        options.onSelected($(this));
                    } else {
                        $(this).removeClass('es-selected').trigger('unselected');
                        el.trigger('unselected');
                        options.onSelecting($(this))
                        options.onUnSelected($(this));
                    }
                });
                if (!hasClass) {
                    $(this).addClass('es-selected').trigger('selected');
                    el.trigger('selected');
                    options.onSelecting($(this));
                    options.onSelected($(this));
                } else {
                    $(this).removeClass('es-selected').trigger('unselected');
                    el.trigger('unselected');
                    options.onSelecting($(this));
                    options.onUnSelected($(this));
                }
                var relativeX = (e.pageX - offset.left);
                var relativeY = (e.pageY - offset.top);
            });
            $(document).on('mouseup', function() {
                el.off('mouseover');
            });
        } else {
            el.off('mousedown');
        }
    };
})(jQuery);
</script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('rescheduleform')?.addEventListener('submit', function(event) {
        event.preventDefault();
        let rescheduleBtn = $('#rescheduleformbtn');

        rescheduleBtn.button('loading').html('<i class="fa fa-spinner fa-spin"></i> Processing...');

        try {
            let formData = new FormData(this);
            let doctorElement = document.getElementById('rdoctor');
            let appointmentStatusElement = $('#edit_appointment_status');

            if (!doctorElement || !appointmentStatusElement.length) throw new Error(
                "Required elements not found.");

            let doctor_id = doctorElement.value;
            let appointment_status_id = appointmentStatusElement.val();
            let appointment_status = ["Unknown", "Requested", "Reserved", "Approved", "Cancelled",
                "InProcess", "Completed"
            ][appointment_status_id] || "Unknown";

            let jsonData = {
                "Hospital_id": <?= $data['hospital_id'] ?>,
                "appointment_status_id": appointment_status_id,
                "appointment_status": appointment_status,
                "doctor": doctor_id,
                "payment_mode": "Paylater",
                "received_by_name":'<?=$data['username'] ?>',
            };
            formData.forEach((value, key) => jsonData[key] = value);
            jsonData["source"] = jsonData["live_consult"] === "no" ? "Offline" : "Online";
            jsonData["global_shift_id"] = $('#rglobal_shift_edit').val() || null;
            jsonData["date"] = jsonData["date"] ? (new Date(jsonData["date"])).toLocaleDateString(
                'en-CA') : null;
            jsonData["time"] = new Date().toLocaleTimeString('en-GB', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });

            let appointmentId = document.getElementById('appointment_id')?.value;
            if (!appointmentId) throw new Error("Appointment ID is missing.");

            if (appointment_status_id == 4) {
                let data = {
                    "cancellationReason": " ",
                    "Hospital_id": <?= $data["hospital_id"] ?>
                };

                $.ajax({
                    type: "PATCH",
                    url: '<?= $api_base_url ?>add-appointment/summa/cancel/' + appointmentId,
                    data: JSON.stringify(data),
                    contentType: 'application/json',
                    dataType: 'json',
                    success: function(response) {
                        try {
                            if (response?. [0]?.status.toLowerCase() == "success") {
                                successMsg(response[0].message ||
                                    "Appointment cancelled successfully.");
                                $('.ajaxlist').DataTable().ajax.reload();
                                $('#rescheduleModal').modal('hide');
                            } else {
                                throw new Error(response?.message ||
                                    "Unknown error occurred.");
                            }
                        } catch (error) {
                            errorMsg(error.message);
                        }
                    },
                    error: function(xhr) {
                        errorMsg(xhr.responseJSON?.message ||
                            'An error occurred while cancelling the appointment.');
                    },
                    complete: function() {
                        setTimeout(() => rescheduleBtn.button('reset').html('Reschedule'),
                            3000);
                    }
                });
                return;
            }

            let missingFields = [];
            if (!jsonData.doctor) missingFields.push("Doctor");
            if (!jsonData.doctor_fees) missingFields.push("Doctor Fees");
            if (!jsonData.global_shift_id) missingFields.push("Shift");
            if (!jsonData.date) missingFields.push("Appointment Date");
            if (!jsonData.shift_id) missingFields.push("Slot");
            if (!jsonData.appointment_status_id) missingFields.push("Status");

            if (missingFields.length > 0) {
                errorMsg("Please fill the required fields: " + missingFields.join(", "));
                setTimeout(() => rescheduleBtn.button('reset').html('Reschedule'), 3000);
                return;
            }

            $.ajax({
                type: 'PATCH',
                url: '<?= $api_base_url ?>add-appointment/' + appointmentId,
                contentType: 'application/json',
                data: JSON.stringify(jsonData),
                dataType: 'json',
                success: function(data) {
                    try {
                        if (!data || data.status === 'failed') throw new Error(data
                            ?.message || "Failed to update appointment.");
                        successMsg(data?. [0]?.message ||
                            "Appointment updated successfully.");
                        $('.ajaxlist').DataTable().ajax.reload();
                        $('#rescheduleModal').modal('hide');
                    } catch (error) {
                        errorMsg(error.message);
                    }
                },
                error: function(xhr) {
                    errorMsg(xhr.responseJSON?.message ||
                        'An error occurred while updating the appointment.');
                },
                complete: function() {
                    setTimeout(() => rescheduleBtn.button('reset').html('Reschedule'),
                        3000);
                }
            });

        } catch (error) {
            errorMsg(error.message || "An unexpected error occurred.");
            setTimeout(() => rescheduleBtn.button('reset').html('Reschedule'), 3000);
        }
    });
});
</script>
<script type="text/javascript">
function popup(data) {
    var base_url = '<?php echo base_url() ?>';
    var frame1 = $('<iframe />');
    frame1[0].name = "frame1";
    frame1.css({
        "position": "absolute",
        "top": "-1000000px"
    });
    $("body").append(frame1);
    var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0]
        .contentDocument.document : frame1[0].contentDocument;
    frameDoc.document.open();
    //Create a new HTML document.
    frameDoc.document.write('<html>');
    frameDoc.document.write('<head>');
    frameDoc.document.write('<title></title>');
    frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/bootstrap/css/bootstrap.min.css">');
    frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/font-awesome.min.css">');
    frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/ionicons.min.css">');
    frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/AdminLTE.min.css">');
    frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/skins/_all-skins.min.css">');
    frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/iCheck/flat/blue.css">');
    frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/morris/morris.css">');
    frameDoc.document.write('<link rel="stylesheet" href="' + base_url +
        'backend/plugins/jvectormap/jquery-jvectormap-1.2.2.css">');
    frameDoc.document.write('<link rel="stylesheet" href="' + base_url +
        'backend/plugins/datepicker/datepicker3.css">');
    frameDoc.document.write('<link rel="stylesheet" href="' + base_url +
        'backend/plugins/daterangepicker/daterangepicker-bs3.css">');
    frameDoc.document.write('</head>');
    frameDoc.document.write('<body>');
    frameDoc.document.write(data);
    frameDoc.document.write('</body>');
    frameDoc.document.write('</html>');
    frameDoc.document.close();
    setTimeout(function() {
        window.frames["frame1"].focus();
        window.frames["frame1"].print();
        frame1.remove();

    }, 500);

    return true;
}
$(document).ready(function(e) {
    $("#formedit").on('submit', (function(e) {
        $("#formeditbtn").button('loading');
        e.preventDefault();
        $.ajax({
            url: baseurl + 'admin/appointment/update',
            type: "POST",
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                if (data.status == "fail") {
                    var message = "";
                    $.each(data.error, function(index, value) {
                        message += value;
                    });
                    errorMsg(message);
                } else {
                    successMsg(data.message);
                    window.location.reload(true);
                }
                $("#formeditbtn").button('reset');
            },
            error: function() {

            }
        });
    }));

    $("#datetimepicker").on("dp.change", function(e) {
        if ($("#global_shift").val() != '') {
            getShift();
        }
    });

    $("#dates").on("dp.change", function(e) {
        if ($("#global_shift_edit").val() != '') {
            getShiftEdit();
        }
    });

    $("#rdates").on("dp.change", function(e) {
        if ($("#rglobal_shift_edit").val() != '') {
            getreschsduleShift();
        }
    });
});

function get_PatientDetails(id) {
    $("#patient_name").html("patient_name");
    $('#gender option').removeAttr('selected');
    $.ajax({
        url: baseurl + 'admin/patient/patientDetails',
        type: "POST",
        data: {
            id: id
        },
        dataType: 'json',
        success: function(res) {
            if (res) {
                $('#patient_name').val(res.patient_name);
                $('#patientid').val(res.id);
                $('#guardian_name').html(res.guardian_name);
                $('#phone').val(res.mobileno);
                $('#email').val(res.email);
                $("#age").html(res.age);
                $("#bp").html(res.bp);
                $("#month").html(res.month);
                $("#symptoms").html(res.symptoms);
                $("#known_allergies").html(res.known_allergies);
                $("#address").html(res.address);
                $("#height").html(res.height);
                $("#weight").html(res.weight);
                $("#marital_status").html(res.marital_status);
                $('#gender option[value="' + res.gender + '"]').attr("selected", "selected");
            } else {
                $('#patient_name').val('');
                $('#phone').val("");
                $('#email').val("");
                $("#note").val("");
            }
        }
    });
}

function getBed(bed_group, bed = '', active, htmlid = 'bed_no') {
    var div_data = "";
    $('#' + htmlid).html("<option value='l'><?php echo $this->lang->line('loading') ?></option>");
    $("#" + htmlid).select2("val", 'l');
    $.ajax({
        url: baseurl + 'admin/setup/bed/getbedbybedgroup',
        type: "POST",
        data: {
            bed_group: bed_group,
            bed_id: bed,
            active: active
        },
        dataType: 'json',
        success: function(res) {
            $.each(res, function(i, obj) {
                div_data += "<option value=" + obj.id + ">" + obj.name + "</option>";
            });
            $("#" + htmlid).html("<option value=''><?php echo $this->lang->line('select') ?></option>");
            $('#' + htmlid).append(div_data);
            $("#" + htmlid).select2().select2('val', bed);
        }
    });
}

function getRecord(id) {
    $("#viewModal").modal('hide');
    $('#myModaledit').modal('show');
    $.ajax({
        url: baseurl + 'admin/appointment/getDetailsAppointment',
        type: "GET",
        data: {
            appointment_id: id
        },
        dataType: 'json',
        success: function(data) {
            $('#customfield').html(data.custom_fields_value);
            $("#id").val(data.id);
            $("#doctor").val(data.doctor).trigger("change");
            $("#dates").val(data.date);
            $("#slot_edit_field").val(data.shift_id);
            getDoctorShift("", data.doctor, data.global_shift_id);
            $("#edit_appointment_no").val(data.appointment_no);
            $("#edit_appoint_priority").val(data.priority).trigger("change");
            $("#message").val(data.message);
            if (data.patient_id == null) {
                data.patient_id = ""
            }
            var option = new Option(data.patients_name, data.patient_id, true, true);
            $("#myModaledit .patient_list_ajax").append(option).trigger('change');
            $("#myModaledit .patient_list_ajax").trigger({
                type: 'select2:select',
                params: {
                    data: data
                }
            });
            $('select[id="edit_gender"] option[value="' + data.patients_gender + '"]').attr("selected",
                "selected");
            $('select[id="doctor"] option[value="' + data.doctor + '"]').attr("selected", "selected");
            $('select[id="appointment_status"] option[value="' + data.appointment_status + '"]').attr(
                "selected", "selected");
            $('select[id="edit_liveconsult"] option[value="' + data.live_consult + '"]').attr("selected",
                "selected");
            $('select[id="edit_appoint_priority"] option[value="' + data.priority + '"]').attr("selected",
                "selected");

        },
    })
}
</script>
<script type="text/javascript">
function askconfirm() {

    if (confirm("<?php echo $this->lang->line('approve_appointment'); ?>")) {
        return true;
    } else {
        return false;
    }

}

$('#myModal').on('hidden.bs.modal', function() {
    $(".appointment_priority_select2").select2("val", "");
    $(".doctor_select2").select2("val", "");
    $("#addpatient_id").select2("val", "");
    $('#formadd').find('input:text, input:password, input:file, textarea').val('');
    $('#formadd').find('select option:selected').removeAttr('selected');
    $('#formadd').find('input:checkbox, input:radio').removeAttr('checked');
});

$(".modalbtnpatient").click(function() {
    $('#formaddpa').trigger("reset");
    $(".dropify-clear").trigger("click");
});


$(document).ready(function(e) {
    $('#myModal,#viewModal,#myModaledit').modal({
        backdrop: 'static',
        keyboard: false,
        show: false
    });
});
</script>
<script type="text/javascript">
function appointmentstatus() {
    var appointment_status = $('#appointment_status').val();
    var doctor_id = $('#doctorid').val();
    if (appointment_status == 'approved') {
        $.ajax({
            url: baseurl + 'admin/appointment/getDoctorFees/',
            type: "POST",
            data: {
                doctor_id: doctor_id
            },
            dataType: 'json',
            success: function(res) {
                $("#doctor_fees").val(res.fees);
                $("#charge_id").val(res.charge_id);
            }
        });
    } else {
        $('#doctor_fees').val('0');
    }
}

function editappointmentstatus() {

    var edit_appointment_status = $('#edit_appointment_status').val();
    var doctor_id = $('#rdoctor').val();
    // if(edit_appointment_status == '3'){
    $.ajax({
        url: baseurl + 'admin/appointment/getDoctorFees/',
        type: "POST",
        data: {
            doctor_id: doctor_id
        },
        dataType: 'json',
        success: function(res) {
            $("#rdoctor_fees_edit").val(res.fees);
            $("#charge_id_edit").val(res.charge_id);
        }
    });
    // }else{
    //     $('#rdoctor_fees_edit').val('0');
    // }
}

function getDoctorFees(object) {
    let doctor_id = object.value;
    $.ajax({
        url: baseurl + 'admin/appointment/getDoctorFees/',
        type: "POST",
        data: {
            doctor_id: doctor_id
        },
        dataType: 'json',
        success: function(res) {
            let doctorFees = res.fees || 0; // If fees is empty, set it to 0
            $("#doctor_fees").val(doctorFees);
            $("#charge_id").val(res.charge_id);
        }
    });
}


function getDoctorFeesEdit(object) {
    let doctor_id = object.value;
    $.ajax({
        url: baseurl + 'admin/appointment/getDoctorFees/',
        type: "POST",
        data: {
            doctor_id: doctor_id
        },
        dataType: 'json',
        success: function(res) {
            $("#doctor_fees_edit").val(res.fees);
            $("#rdoctor_fees_edit").val(res.fees);
            $("#charge_id_edit").val(res.charge_id);
            editappointmentstatus();
        }
    })
}
</script>
<script>
function getShift() {
    var div_data = "";
    var date = $("#datetimepicker").val();
    var doctor = $("#doctorid").val();
    var global_shift = $("#global_shift").val();

    $.ajax({
        url: baseurl + 'admin/onlineappointment/getShift',
        type: "POST",
        data: {
            doctor: doctor,
            date: date,
            global_shift: global_shift
        },
        dataType: 'json',
        success: function(res) {
            $.each(res, function(i, obj) {
                div_data += "<option value='" + obj.id + "' id='slot_" + obj.id + "'>" + obj
                    .start_time + " - " + obj.end_time + "</option>";
            });
            $("#slot").html("<option value='' disabled selected>Select</option>");
            $('#slot').append(div_data);
        }
    });
}


function getShiftEdit() {

    var div_data = "";
    var date = $("#dates").val();
    var doctor = $("#doctor").val();
    var global_shift = $("#global_shift_edit").val();

    $.ajax({
        url: baseurl + 'admin/onlineappointment/getShift',
        type: "POST",
        data: {
            doctor: doctor,
            date: date,
            global_shift: global_shift
        },
        dataType: 'json',
        success: function(res) {
            $.each(res, function(i, obj) {
                div_data += "<option value=" + obj.id + ">" + obj.start_time + " - " + obj
                    .end_time + "</option>";
            });
            $("#slot_edit").html(
                "<option value='' disabled selected><?php echo $this->lang->line('select'); ?></option>"
            );
            $('#slot_edit').append(div_data);
            $("#slot_edit").val($("#slot_edit_field").val()).trigger('change');
        }
    });
}

function getreschsduleShift() {

    var div_data = "";
    var date = $("#rdates").val();
    var doctor = $("#rdoctor").val();
    var global_shift = $("#rglobal_shift_edit").val();

    $.ajax({
        url: baseurl + 'admin/onlineappointment/getShift',
        type: "POST",
        data: {
            doctor: doctor,
            date: date,
            global_shift: global_shift
        },
        dataType: 'json',
        success: function(res) {
            $.each(res, function(i, obj) {
                div_data += "<option value=" + obj.id + ">" + obj.start_time + " - " + obj
                    .end_time + "</option>";
            });
            $("#rslot_edit").html("<option value=''><?php echo $this->lang->line('select'); ?></option>");
            $('#rslot_edit').append(div_data);
            $("#rslot_edit").val($("#rslot_edit_field").val()).trigger('change');
        }
    });
}

function getDoctorShift(obj, doctor_id = null, global_shift_id = null) {
    if (doctor_id == null) {
        var doctor_id = obj.value;
    }
    var select = "";
    var select_box = "<option value=''><?php echo $this->lang->line('select'); ?></option> ";
    $.ajax({
        type: 'POST',
        url: base_url + "admin/onlineappointment/doctorshiftbyid",
        data: {
            doctor_id: doctor_id
        },
        dataType: 'json',
        success: function(res) {
            select_box = "";
            if (res.length === 0) {
                select_box = "<option value='' disabled selected>No options available</option>";
            } else {
                select_box = "<option value='' disabled selected>Select</option>";
                $.each(res, function(i, list) {
                    select_box += "<option value='" + list.id + "'>" + list.name + "</option>";
                });
            }
            $("#global_shift").html(select_box).trigger('change');
            $("#global_shift_edit").html(select_box).trigger('change');
            $("#rglobal_shift_edit").html(select_box).trigger('change');
            if (global_shift_id != null) {
                $("#global_shift_edit").val(global_shift_id).trigger('change');
                $("#rglobal_shift_edit").val(global_shift_id).trigger('change');
            }
        }
    });
}

function getDoctorShiftedit(obj, doctor_id = null, global_shift_id = null) {
    if (doctor_id == null) {
        var doctor_id = obj.value;
    }
    var select = "";
    var select_box = "<option value=''><?php echo $this->lang->line('select'); ?></option> ";
    $.ajax({
        type: 'POST',
        url: base_url + "admin/onlineappointment/doctorshiftbyid",
        data: {
            doctor_id: doctor_id
        },
        dataType: 'json',
        success: function(res) {
            select_box = "";
            if (res.length === 0) {
                select_box = "<option value='' disabled selected>No options available</option>";
            } else {
                // select_box = "<option value='' disabled selected>Select</option>";
                $.each(res, function(i, list) {
                    select_box += "<option value='" + list.id + "'>" + list.name + "</option>";
                });
            }
            $("#global_shift").html(select_box).trigger('change');
            $("#global_shift_edit").html(select_box).trigger('change');
            $("#rglobal_shift_edit").html(select_box).trigger('change');
            if (global_shift_id != null) {
                $("#global_shift_edit").val(global_shift_id).trigger('change');
                $("#rglobal_shift_edit").val(global_shift_id).trigger('change');
            }
        }
    });
}

// function validateTime(obj) {
//     let id = obj.value;
//     let date = $("#datetimepicker").val();
//     if (id) {
//         $.ajax({
//             url: baseurl + 'admin/onlineappointment/getshiftbyid',
//             type: "POST",
//             data: {
//                 id: id,
//                 date: date
//             },
//             dataType: 'json',
//             success: function(res) {
//                 if (res.status) {
//                     alert("<?php echo $this->lang->line("appointment_time_is_expired"); ?>");
//                 }
//             }
//         });
//     }

// }
</script>
<script type="text/javascript">
function filterAppointment() {
    let activeTab = $('.nav-tabs .nav-link.active').data('tab') || '';
    let due_status = $('#filterDue').val() || '';
    let url = `admin/appointment/getappointmentdatatable/${activeTab}?due_status=${encodeURIComponent(due_status)}`;
    initDatatable('ajaxlist', url, [], [], 100);
}
$(document).ready(function() {
    $('.nav-tabs .nav-link').on('click', function() {
        $('.nav-tabs .nav-link').removeClass('active');
        $(this).addClass('active');
        filterAppointment();
    });
    $('#filterPaid, #filterDue').on('change', function() {
        filterAppointment();
    });
    filterAppointment();
});
</script>
<script>
function additionalFunction(appointmentId) {
    if (!confirm('Are you sure you want to approve this appointment?')) {
        return;
    }
    try {
        $.ajax({
            url: '<?= $api_base_url ?>add-appointment/set-status-to-approved',
            type: 'POST',
            data: JSON.stringify({
                "appointment_id": appointmentId,
                "hos_id": <?= $data['hospital_id'] ?>
            }),
            contentType: 'application/json',
            dataType: 'json',
            success: function(res) {
                try {
                    if (res.status === 'success') {
                        successMsg(res.message);
                        table.ajax.reload(null, false);
                    } else {
                        errorMsg('Error: ' + res.message);
                    }
                } catch (error) {
                    console.error("Error processing response:", error);
                    errorMsg("An error occurred while processing the response.");
                }
            },
            error: function(xhr) {
                console.error("API Error:", xhr.responseText);
                errorMsg('An error occurred while processing your request.');
            }
        });
    } catch (error) {
        console.error("Unexpected Error:", error);
        errorMsg("An unexpected error occurred.");
    }
}
</script>
<script>
function paymentaction(id, balanceamount, patientid) {
    $.ajax({
        url: baseurl + 'admin/appointment/getDetailsAppointment',
        type: "GET",
        data: {
            appointment_id: id
        },
        dataType: 'json',
        success: function(data) {
            $('#paymentTypeModal').modal('show');
            $('#balance_appointment_status_id').val(data.appointment_status_id);
            $('#balance_appointment_status').val(data.appointment_status);
            $('#balance_doctor').val(data.doctor);
            $('#balance_appointment_id').val(data.id);
            $('#balance_doctor_fees').val(balanceamount);
            $('#balance_global_shift_id').val(data.global_shift_id);
            $('#balance_shift_id').val(data.shift_id);
            $('#balance_priority').val(data.priority);
            $('#balance_source').val(data.source);
            $("#balance_date").val(data.date);
            $("#balance_patient_id").val(data.patient_id);
            // console.log(JSON.stringify(data));
        }
    });
}
</script>
<script>
$(document).ready(function() {
    $('.payment_mode').on('change', function() {
        const isCheque = $(this).val() === 'Cheque';
        $('.cheque_details').toggle(isCheque);
        if (!isCheque) {
            $('#cheque_no, #cheque_date').val('');
        }
    });

    $('#paymentTypeForm').on('submit', function(e) {
        e.preventDefault();
        $('#paymentTypeFormbtn').button('loading');

        try {
            const formData = getFormData($(this));
            const jsonData = prepareJsonData(formData);
            if (formData.payment_mode === 'Online') {
                console.log(jsonData);
                handlePayment(jsonData).then((paymentSuccess) => {
                    if (paymentSuccess) {
                        jsonData.payment_gateway = 'razorpay';
                        jsonData.payment_id = paymentSuccess.payment_id;
                        jsonData.payment_reference_number = paymentSuccess.reference_id;
                        submitFormWithRetry(jsonData, 3);
                    } else {
                        alert('Payment failed. Please try again.');
                        $('#paymentTypeFormbtn').button('reset');
                    }
                }).catch(error => {
                    console.error('Payment Error:', error);
                    alert('Payment processing failed. Please try again.');
                    $('#paymentTypeFormbtn').button('reset');
                });
            } else {
                submitFormWithRetry(jsonData, 3);
            }
        } catch (error) {
            console.error('Form Submission Error:', error);
            alert('An unexpected error occurred. Please try again.');
            $('#paymentTypeFormbtn').button('reset');
        }
    });

    function getFormData(form) {
        let data = {};
        form.serializeArray().forEach(item => data[item.name] = item.value);
        return data;
    }

    function prepareJsonData(formData) {
        const currentDate = new Date();
        return {
            "Hospital_id": <?= $data['hospital_id'] ?>,
            "appointment_status_id": formData.appointment_status_id,
            "appointment_status": formData.appointment_status,
            "doctor": formData.doctor,
            "payment_mode": formData.payment_mode,
            "appointment_id": formData.appointment_id,
            "doctor_fees": formData.amount,
            "global_shift_id": formData.global_shift_id,
            "date": new Date(formData.balance_date).toLocaleDateString('en-CA'),
            "shift_id": formData.shift_id,
            "priority": formData.priority,
            "message": formData.notes,
            "source": formData.source,
            "time": currentDate.toTimeString().split(' ')[0],
            "patient_id": formData.patient_id
        };
    }

    function submitFormWithRetry(data, retries) {
        $.ajax({
            url: '<?= $api_base_url ?>add-appointment/' + data.appointment_id,
            type: 'PATCH',
            contentType: 'application/json',
            data: JSON.stringify(data),
            dataType: 'json',
            success: function(response) {
                try {
                    if (Array.isArray(response) && response.length > 0 && response[0].status ===
                        'success') {
                        successMsg(response[0].message || 'Payment saved successfully!');
                        $('#paymentTypeModal').modal('hide');
                        table.ajax.reload(null, false);
                    } else {
                        throw new Error(response.message || 'An error occurred. Please try again.');
                    }
                } catch (error) {
                    console.error('Response Error:', error);
                    errorMsg(error.message);
                } finally {
                    $('#paymentTypeFormbtn').button('reset');
                }
            },
            error: function() {
                if (retries > 0) {
                    console.warn(`Retrying... (${4 - retries} attempt)`);
                    setTimeout(() => submitFormWithRetry(data, retries - 1), 3000);
                } else {
                    alert('An unexpected error occurred. Please try again.');
                    $('#paymentTypeFormbtn').button('reset');
                }
            }
        });
    }
});
</script>
<script>
function getpatientData(id) {
    $('#modal_head').html("<?php echo $this->lang->line('patient_details'); ?>");
    $.ajax({
        url: baseurl + 'admin/patient/getpatientDetails',
        type: "POST",
        data: {
            id: id
        },
        dataType: 'json',
        success: function(data) {
            if (data.is_active == 'yes') {
                var link =
                    "<?php if ($this->rbac->hasPrivilege('enabled_disabled', 'can_view')) { ?><a href='#' data-toggle='tooltip' title='<?php echo $this->lang->line('disable'); ?>' onclick='patient_deactive(" +
                    id +
                    ")' data-placement='bottom' data-original-title='<?php echo $this->lang->line('disable'); ?>'><i class='fa fa-thumbs-o-down'></i></a><?php } ?>";
            } else {
                var link =
                    "<?php if ($this->rbac->hasPrivilege('enabled_disabled', 'can_view')) { ?><a href='#' data-toggle='tooltip' title='<?php echo $this->lang->line('enable'); ?>' onclick='patient_active(" +
                    id +
                    ")' data-original-title='<?php echo $this->lang->line('enable'); ?>'><i class='fa fa-thumbs-o-up'></i></a> <?php }
                                                                                                                                if ($this->rbac->hasPrivilege('patient', 'can_delete')) { ?><a href='#' data-toggle='tooltip' onclick='delete_record(" +
                    id +
                    ")' data-original-title='<?php echo $this->lang->line('delete'); ?>'><i class='fa fa-trash'></i></a> <?php } ?>";
            }

            var table_html = '';
            $.each(data.field_data, function(i, obj) {
                let field_value = $.trim(obj.field_value) || "-";
                let name = $.trim(obj.name) || "-";
                table_html +=
                    `<p><b><span id='vcustom_name'>${capitalizeFirstLetter(name)}</span></b> <span id='vcustom_value'>${field_value}</span></p>`;
            });

            $("#field_data").html(table_html);
            $("#patient_name").html($.trim(data.patient_name) + " (" + (data.id || "-") + ")");
            $("#guardian").html($.trim(data.guardian_name) || "-");
            $("#patients_id").html($.trim(data.patient_unique_id) || "-");

            let basePath = "<?= base_url() . 'uploads/hospital_content/' ?>";
            let genderIcon = document.getElementById("gender_icon");
            let gender = $.trim(data.gender) || "-";
            $("#genders").html(gender);

            genderIcon.src = gender === "Male" ? basePath + "Man.png" :
                gender === "Female" ? basePath + "Women.png" :
                basePath + "Others.png";

            $("#marital_status").html($.trim(data.marital_status) || "-");
            $("#contact").html($.trim(data.mobileno) || "-");
            $("#email").html($.trim(data.email) || "-");
            $("#address").html($.trim(data.address) || "-");
            $("#is_active").html($.trim(data.is_active) || "-");
            $('select[id="blood_groups"] option[value="' + $.trim(data.blood_bank_product_id) + '"]').attr(
                "selected", "selected");
            $("#age").html($.trim(data.patient_age) || "-");
            $("#allergies").html($.trim(data.known_allergies) || "-");
            $("#insurance_id").html($.trim(data.insurance_id) || "-");
            $("#validity").html($.trim(data.insurance_validity) || "-");
            $("#identification_number").html($.trim(data.ABHA_number) || "-");
            $("#blood_group").html($.trim(data.blood_group_name) || "-");
            $("#notedetials").html($.trim(data.note || "-"));
            let defaultImage = "<?= base_url('uploads/staff_images/no_image.png'); ?>";
            $.ajax({
                url: 'https://phr-api.plenome.com/file_upload/getDocs',
                type: "POST",
                contentType: "application/json",
                data: JSON.stringify({
                    value: data.image
                }),
                success: function(decryptResponse) {
                    if (decryptResponse && typeof decryptResponse === 'string' &&
                        decryptResponse.length > 0 && !decryptResponse.includes(
                            "[object Object]")) {
                        let base64Image = "data:image/png;base64," + decryptResponse;
                        $("#image").attr("src", base64Image);
                    } else {
                        $("#image").attr("src", defaultImage);
                    }
                },
                error: function() {
                    $("#image").attr("src", defaultImage);
                }
            });
            holdModal('patientprofile');
            patientvisit(id);
        },
    });
}


function patientvisit(id) {
    $.ajax({
        url: baseurl + 'admin/patient/patientvisit',
        type: "POST",
        data: {
            id: id
        },
        dataType: 'json',
        success: function(data) {
            $('#visit_report_id').html(data);
        }
    });
}
document.getElementById("headreport").style.display = "block";
document.getElementById("print").style.display = "block";
document.getElementById("btnExport").style.display = "block";
document.getElementById("printhead").style.display = "none";

function printDiv() {
    document.getElementById("print").style.display = "none";
    document.getElementById("btnExport").style.display = "none";
    var divElements = document.getElementById('visit_report').innerHTML;
    var oldPage = document.body.innerHTML;
    document.body.innerHTML =
        "<html><head><title>Patient Bill Report</title></head><body>" +
        divElements + "</body>";
    window.print();
    document.body.innerHTML = oldPage;
    location.reload(true);
}
</script>

<!-- //========datatable end===== -->
<?php $this->load->view('admin/patient/patientaddmodal') ?>