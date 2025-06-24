<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('doctor_wise_appointment'); ?></h3>
                    </div>
                    <div class="box-body">
                        <form action="<?php echo site_url("admin/onlineappointment/patientschedule"); ?>" method="post"
                            accept-charset="utf-8">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="pwd"><?php echo $this->lang->line('doctor') ?></label>
                                        <span class="req"> *</span>
                                        <select name="doctor" id="doctor" class="form-control select2">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php if (count($doctors) == 1) { ?>
                                            <?php $doctor_value = $doctors[0]; ?>
                                            <option value="<?php echo $doctor_value['id']; ?>" selected>
                                                <?php echo $doctor_value['name'] . " " . $doctor_value['surname']; ?>
                                                (<?php echo $doctor_value["employee_id"]; ?>)
                                            </option>
                                            <?php } else { ?>
                                            <?php foreach ($doctors as $doctor_key => $doctor_value) { ?>
                                            <option value="<?php echo $doctor_value['id']; ?>"
                                                <?php echo $doctor_value["id"] == set_value("doctor") ? "selected" : ""; ?>>
                                                <?php echo $doctor_value['name'] . " " . $doctor_value['surname']; ?>
                                                (<?php echo $doctor_value["employee_id"]; ?>)
                                            </option>
                                            <?php } ?>
                                            <?php } ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('doctor'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="date"><?php echo $this->lang->line('date') . " " ?></label>
                                        <span class="req"> *</span>
                                        <div class='input-group'>
                                            <input type='text' value="<?php echo set_value('date'); ?>"
                                                class="form-control date" name="date" /><span
                                                class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        </div>
                                        <span class="text-danger"><?php echo form_error('date'); ?></span>
                                    </div>
                                </div>
                            </div>
                            <button type="submit"
                                class="btn btn-primary btn-sm pull-right"><?php echo $this->lang->line('search'); ?></button>
                        </form>
                    </div>

                    <?php if (isset($doctor_id)) {
                   
                 ?>
                    <div class="box-body">
                        <div class="download_label"><?php echo $this->lang->line('doctor_wise_appointment'); ?></div>
                        <div class="table-responsive mailbox-messages">
                            <table class="table table-hover table-striped table-bordered dt-list"
                                data-export-title="<?php echo $this->lang->line('doctor_wise_appointment');?>">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('patient_name'); ?></th>
                                        <th><?php echo $this->lang->line('phone'); ?></th>
                                        <th><?php echo $this->lang->line('time'); ?></th>
                                        <th><?php echo $this->lang->line('email'); ?></th>
                                        <th><?php echo $this->lang->line('date'); ?></th>
                                        <th class="text-right"><?php echo $this->lang->line("source"); ?></th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>
</div>
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

<!-- //========datatable start===== -->
<script type="text/javascript">
(function($) {
    'use strict';
    $(document).ready(function() {
        $(".select2").select2();
        initDatatable('dt-list',
            'admin/onlineappointment/getpatientschedule/?doctor=<?php echo isset($doctor_id)?$doctor_id:""; ?>&date=<?php echo isset($date)?$date:""; ?>'
        );
    });
}(jQuery))
</script>
<script>
function holdModal(modalId) {
    $('#' + modalId).modal({
        backdrop: 'static',
        keyboard: false,
        show: true
    });
}

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
</script>
<!-- //========datatable end===== -->