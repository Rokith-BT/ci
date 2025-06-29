<?php
$currency_symbol = $this->customlib->getHospitalCurrencyFormat();
$data = $this->session->userdata('hospitaladmin');
$api_base_url = $this->config->item('api_base_url');
?>
<script src="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <?php if ($title == 'old_patient') { ?>
                            <h3 class="box-title titlefix"><?php echo $this->lang->line('opd_old_billing'); ?></h3>
                        <?php } else { ?>
                            <h3 class="box-title titlefix"><?php echo $this->lang->line('opd_billing'); ?></h3>

                        <?php } ?>
                        <div class="box-tools addmeeting box-tools-md">

                            <a data-toggle="modal" id="add" onclick="holdModal('myModal')"
                                class="btn btn-primary btn-sm addpatient"><i class="fa fa-plus"></i>
                                <?php echo $this->lang->line('add_patient'); ?></a>




                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="download_label"><?php
                                                    if ($title == 'old_patient') {
                                                        echo $this->lang->line('opd_old_patient');
                                                    ?>
                            <?php
                                                    } else {
                                                        echo $this->lang->line('opd_patient');
                            ?>

                            <?php } ?>
                        </div>
                        <table class="table table-striped table-bordered table-hover ajaxlist"
                            data-export-title="<?php echo $this->lang->line('opd_patient'); ?>">
                            <thead>
                                <tr>
                                    <th class="text-center"><?php echo $this->lang->line('name') ?></th>
                                    <th class="text-center"><?php echo $this->lang->line('patient_id'); ?></th>
                                    <th class="text-center"><?php echo $this->lang->line('guardian_name') ?></th>
                                    <th class="text-center"><?php echo $this->lang->line('gender'); ?></th>
                                    <th class="text-center"><?php echo $this->lang->line('phone'); ?></th>
                                    <th class="text-center"><?php echo $this->lang->line('consultant'); ?></th>
                                    <th class="text-center"><?php echo $this->lang->line('last_visit'); ?></th>
                                    <?php
                                    if (!empty($fields)) {
                                        foreach ($fields as $fields_key => $fields_value) {
                                    ?>
                                            <th class="text-center"><?php echo $fields_value->name; ?></th>
                                    <?php
                                        }
                                    }
                                    ?>
                                    <th class="text-center"><?php echo $this->lang->line('total_recheckup'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog pup100" role="document">
        <form id="formadd" accept-charset="utf-8" action="<?php echo base_url() . "admin/patient" ?>"
            enctype="multipart/form-data" method="post">
            <div class="modal-content modal-media-content">
                <div class="modal-header modal-media-header">
                    <button type="button" class="close pupclose" data-dismiss="modal">&times;</button>
                    <div class="row">
                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-9">
                            <div class="p-2 select2-full-width">
                                <select class="form-control patient_list_ajax" <?php
                                                                                if ($disable_option == true) {
                                                                                }
                                                                                ?> name='patient_id' id="addpatient_id">
                                    <option value="" disabled selected>Select patient</option>
                                </select>
                                <span class="text-danger"><?php echo form_error('refference'); ?></span>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-3 col-xs-1">

                            <?php if ($this->rbac->hasPrivilege('patient', 'can_add')) { ?>
                                <a data-toggle="modal" id="add" onclick="holdModal('myModalpa')" class="modalbtnpatient"><i
                                        class="fa fa-plus"></i>
                                    <span><?php echo $this->lang->line('new_patient'); ?></span></a>
                            <?php } ?>
                        </div>
                    </div>
                </div>

            </div><!--./modal-header-->

            <div class="pup-scroll-area">
                <div class="modal-body pt0 pb0">

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="ptt10">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('appointment_date'); ?></label>
                                            <small class="req"> *</small>
                                            <input id="admission_date" name="appointment_date" placeholder=""
                                                type="text" class="form-control datetime" />
                                            <input type="hidden" class="form-control" id="password" name="password">
                                            <span
                                                class="text-danger"><?php echo form_error('appointment_date'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="exampleInputFile">
                                                <?php echo $this->lang->line('case'); ?></label>
                                            <!-- <small class="req"> *</small> -->
                                            <div><input class="form-control" type='text' name='case' id="case_type" />
                                            </div>
                                            <span class="text-danger"><?php echo form_error('case'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="exampleInputFile">
                                                <?php echo $this->lang->line('casualty'); ?></label>
                                            <div>
                                                <select name="casualty" id="casualty" class="form-control">
                                                    <?php foreach ($yesno_condition as $yesno_key => $yesno_value) {
                                                    ?>
                                                        <option value="<?php echo $yesno_key ?>" <?php
                                                                                                    if ($yesno_key == 'no') {
                                                                                                        echo "selected";
                                                                                                    }
                                                                                                    ?>><?php echo $yesno_value ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>

                                            </div>
                                            <span class="text-danger"><?php echo form_error('case'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="exampleInputFile">
                                                <?php echo $this->lang->line('old_patient'); ?></label>
                                            <div>
                                                <select name="old_patient" id="" class="form-control">
                                                    <?php foreach ($yesno_condition as $yesno_key => $yesno_value) {
                                                    ?>
                                                        <option value="<?php echo $yesno_key ?>" <?php
                                                                                                    if ($yesno_key == 'no') {
                                                                                                        echo "selected";
                                                                                                    }
                                                                                                    ?>><?php echo $yesno_value ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <span class="text-danger"><?php echo form_error('case'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="exampleInputFile">
                                                <?php echo $this->lang->line('tpa'); ?></label>
                                            <!-- <small class="req"> *</small> -->
                                            <div><select class="form-control" onchange="get_Charges(this.value)"
                                                    id="organisation" name='organisation'>
                                                    <option value="0"><?php echo $this->lang->line('select'); ?>
                                                    </option>
                                                    <?php foreach ($organisation as $orgkey => $orgvalue) {
                                                    ?>
                                                        <option value="<?php echo $orgvalue["id"]; ?>">
                                                            <?php echo $orgvalue["organisation_name"] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <span class="text-danger"><?php echo form_error('refference'); ?></span>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="exampleInputFile">
                                                <?php echo $this->lang->line('reference'); ?></label>
                                            <div><input class="form-control" type='text' name='refference' />
                                            </div>
                                            <span class="text-danger"><?php echo form_error('refference'); ?></span>
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="exampleInputFile">
                                                <?php echo $this->lang->line('consultant_doctor'); ?></label><small
                                                class="req"> *</small>
                                            <div><select name='consultant_doctor' id="consultant_doctor" class="form-control select2" <?php
                                                                                                                                        if ($disable_option == true) {
                                                                                                                                            echo "disabled";
                                                                                                                                        }
                                                                                                                                        ?> style="width:100%">
                                                    <option value=""><?php echo $this->lang->line('select') ?></option>
                                                    <?php foreach ($doctors as $dkey => $dvalue) {
                                                    ?>
                                                        <option value="<?php echo $dvalue["id"]; ?>" <?php
                                                                                                        if ((isset($doctor_select)) && ($doctor_select == $dvalue["id"])) {
                                                                                                            echo "selected";
                                                                                                        }
                                                                                                        ?>><?php echo $dvalue["name"] . " " . $dvalue["surname"] . " (" . $dvalue["employee_id"] . ")" ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                                <?php if ($disable_option == true) { ?>
                                                    <input type="hidden" name="consultant_doctor"
                                                        value="<?php echo $doctor_select ?>">
                                                <?php } ?>
                                            </div>
                                            <span class="text-danger"><?php echo form_error('refference'); ?></span>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('charge_category'); ?></label>
                                            <select name="charge_category" style="width: 100%"
                                                class="form-control charge_category select2">
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                <?php foreach ($charge_category as $key => $value) {
                                                ?>
                                                    <option value="<?php echo $value['id']; ?>">
                                                        <?php echo $value['name']; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                            <span
                                                class="text-danger"><?php echo form_error('charge_category'); ?></span>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('charge'); ?></label><small class="req">
                                                *</small>
                                            <select name="charge_id" style="width: 100%"
                                                class="form-control charge select2">
                                                <option value=""><?php echo $this->lang->line('select') ?></option>
                                            </select>
                                            <span class="text-danger"><?php echo form_error('apply_charge'); ?></span>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label
                                                for="exampleInputEmail1"><?php echo $this->lang->line('tax'); ?></label>
                                            <div class="input-group">


                                                <input type="text" class="form-control right-border-none"
                                                    name="percentage" id="percentage" readonly autocomplete="off">
                                                <span class="input-group-addon "> %</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('standard_charge') . " (" . $currency_symbol . ")" ?></label>
                                            <input type="text" readonly name="standard_charge" id="standard_charge"
                                                class="form-control"
                                                value="<?php echo set_value('standard_charge'); ?>">

                                            <span
                                                class="text-danger"><?php echo form_error('standard_charge'); ?></span>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('applied_charge') . " (" . $currency_symbol . ")" ?></label><small
                                                class="req"> *</small><input type="text" name="amount" id="apply_charge"
                                                onkeyup="update_amount(this.value)" class="form-control">
                                            <span class="text-danger"><?php echo form_error('amount'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('amount') . " (" . $currency_symbol . ")" ?></label><small
                                                class="req"> *</small><input type="text" name="apply_amount" readonly
                                                id="apply_amount" class="form-control">

                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="pwd"><?php echo $this->lang->line('payment_mode'); ?></label>

                                            <select name="payment_mode" class="form-control payment_mode">
                                                <?php foreach ($payment_mode as $payment_key => $payment_value) {
                                                ?>
                                                    <option value="<?php echo $payment_key ?>" <?php
                                                                                                if ($payment_key == 'cash') {
                                                                                                    echo "selected";
                                                                                                }
                                                                                                ?>><?php echo $payment_value ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label
                                                for="pwd"><?php echo $this->lang->line('paid_amount') . " (" . $currency_symbol . ")"; ?></label><small
                                                class="req"> *</small>
                                            <input type="text" name="paid_amount" id="paid_amount" class="form-control">
                                            <span class="text-danger"><?php echo form_error('paid_amount'); ?></span>
                                        </div>
                                    </div>
                                    <div class="cheque_div" style="display: none;">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('cheque_no'); ?></label><small
                                                    class="req"> *</small>
                                                <input type="text" name="cheque_no" id="cheque_no" class="form-control">
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('cheque_date'); ?></label><small
                                                    class="req"> *</small>
                                                <input type="text" name="cheque_date" id="cheque_date"
                                                    class="form-control date">
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('attach_document'); ?></label>
                                                <input type="file" class="filestyle form-control" name="document">
                                                <span class="text-danger"><?php echo form_error('document'); ?></span>
                                            </div>
                                        </div>
                                    </div>

                                    <?php if ($this->module_lib->hasActive('live_consultation')) { ?>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>
                                                    <?php echo $this->lang->line('live_consultation'); ?></label>


                                                <select name="live_consult" class="form-control">
                                                    <?php

                                                    foreach ($yesno_condition as $yesno_key => $yesno_value) {
                                                    ?>
                                                        <option value="<?php echo $yesno_key ?>" <?php
                                                                                                    if ($yesno_key == 'no') {
                                                                                                        echo "selected";
                                                                                                    }
                                                                                                    ?>><?php echo $yesno_value; ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>


                                                <span class="text-danger"><?php echo form_error('live_consult'); ?></span>
                                            </div>
                                        </div>
                                    <?php } ?>

                                </div><!--./row-->
                            </div><!--./col-md-4-->

                        </div><!--./col-md-12-->
                    </div><!--./row-->
                </div>
            </div>

            <div class="box-footer sticky-footer">

                <div class="pull-right">
                    <button type="submit" id="formaddbtn" name="save"
                        data-loading-text="<?php echo $this->lang->line('processing') ?>"
                        class="btn btn-info pull-right"><i class="fa fa-check-circle"></i>
                        <span><?php echo $this->lang->line('save'); ?></span></button>
                </div>

                <div class="pull-right" style="margin-right: 10px; ">
                    <button type="submit" data-loading-text="<?php echo $this->lang->line('processing') ?>"
                        name="save_print" class="btn btn-info pull-right printsavebtn"><i
                            class="fa fa-check-circle"></i> <?php echo $this->lang->line('save_print'); ?></button>
                </div>
            </div>
        </form>
    </div>
</div>
</div>

<div class="modal fade" id="myModaledit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="box-title"> <?php echo $this->lang->line('patient_details'); ?></h4>
            </div>

            <div class="modal-body pt0 pb0">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 paddlr">
                        <form id="formedit" accept-charset="utf-8" enctype="multipart/form-data" method="post"
                            class="ptt10">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('name'); ?></label><small class="req">
                                            *</small>
                                        <input id="patient_name" name="name" placeholder="" type="text"
                                            class="form-control" value="<?php echo set_value('name'); ?>" />
                                        <input type="hidden" id="updateid" name="updateid">
                                        <input type="hidden" id="opdid" name="opdid">
                                        <span class="text-danger"><?php echo form_error('name'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('guardian_name'); ?></label>
                                        <input type="text" id="guardian_name" name="guardian_name"
                                            value="<?php echo set_value('guardian_name'); ?>" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('gender'); ?></label><small class="req">
                                            *</small>
                                        <select class="form-control" id="gender" name="gender">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($genderList as $key => $value) {
                                            ?>
                                                <option value="<?php echo $key; ?>" <?php if (set_value('gender') == $key)
                                                                                        echo "selected"; ?>><?php echo $value; ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('gender'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="pwd"><?php echo $this->lang->line('marital_status'); ?></label>
                                        <select name="marital_status" id="marital_status" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select') ?></option>
                                            <?php foreach ($marital_status as $mkey => $mvalue) {
                                            ?>
                                                <option value="<?php echo $mkey ?>"><?php echo $mvalue ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="exampleInputFile">
                                            <?php echo $this->lang->line('patient_photo'); ?></label>
                                        <div><input class="filestyle form-control" type='file' name='file' id="file"
                                                size='20' />
                                            <input type="hidden" name="patient_photo" id="patient_photo">
                                        </div>
                                        <span class="text-danger"><?php echo form_error('file'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('email'); ?></label>
                                        <input type="text" id="email" value="<?php echo set_value('email'); ?>"
                                            name="email" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label for="pwd"><?php echo $this->lang->line('phone'); ?></label>
                                        <input id="contact" autocomplete="off" name="contact" placeholder="" type="text"
                                            class="form-control" value="<?php echo set_value('contact'); ?>" />
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label> <?php echo $this->lang->line('blood_group'); ?></label><small
                                            class="req"> *</small>
                                        <select class="form-control" id="blood_group" name="blood_group">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($bloodgroup as $key => $value) {
                                            ?>
                                                <option value="<?php echo $value; ?>" <?php if (set_value('blood_group') == $key)
                                                                                            echo "selected"; ?>>
                                                    <?php echo $value; ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('blood_group'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('age'); ?></label>
                                        <div style="clear: both;overflow: hidden;">
                                            <input type="text" placeholder="Age" id="age" name="age"
                                                value="<?php echo set_value('age'); ?>" class="form-control"
                                                style="width: 40%; float: left;">
                                            <input type="text" placeholder="Month" id="month" name="month"
                                                value="<?php echo set_value('month'); ?>" class="form-control"
                                                style="width: 56%;float: left; margin-left: 5px;">

                                        </div>
                                    </div>
                                </div>

                            </div><!--./row-->
                            <button type="submit"
                                class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                    </div><!--./col-md-12-->
                </div><!--./row-->
            </div>
            <div class="box-footer">
                <div class="pull-right paddA10">
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).on('change', '.act', function() {
        $this = $(this);
        var sys_val = $(this).val();

        var section_ul = $(this).closest('div.row').find('ul.section_ul');
        $.ajax({
            type: 'POST',
            url: base_url + 'admin/patient/getPartialsymptoms',
            data: {
                'sys_id': sys_val
            },
            dataType: 'JSON',
            beforeSend: function() {

                $('ul.section_ul').find('li:not(:first-child)').remove();


            },
            success: function(data) {
                section_ul.append(data.record);
            },
            error: function(xhr) { // if error occured
                alert("Error occured.please try again");
            },
            complete: function() {}
        });
    });
</script>
<script type="text/javascript">
    $(document).on('click', '.remove_row', function() {
        $this = $(this);
        $this.closest('.row').remove();

    });
    $(document).mouseup(function(e) {
        var container = $(".wrapper-dropdown-3"); // YOUR CONTAINER SELECTOR

        if (!container.is(e.target) // if the target of the click isn't the container...
            &&
            container.has(e.target).length === 0) // ... nor a descendant of the container
        {
            $("div.wrapper-dropdown-3").removeClass('active');
        }
    });

    $(document).on('click', '.filterinput', function() {

        if (!$(this).closest('.wrapper-dropdown-3').hasClass("active")) {
            $(".wrapper-dropdown-3").not($(this)).removeClass('active');
            $(this).closest("div.wrapper-dropdown-3").addClass('active');
        }


    });

    $(document).on('click', 'input[name="section[]"]', function() {
        $(this).closest('label').toggleClass('active_section');
    });

    $(document).on('keyup', '.filterinput', function() {

        var valThis = $(this).val().toLowerCase();
        var closer_section = $(this).closest('div').find('.section_ul > li');

        var noresult = 0;
        if (valThis == "") {
            closer_section.show();
            noresult = 1;
            $('.no-results-found').remove();
        } else {
            closer_section.each(function() {
                var text = $(this).text().toLowerCase();
                var match = text.indexOf(valThis);
                if (match >= 0) {
                    $(this).show();
                    noresult = 1;
                    $('.no-results-found').remove();
                } else {
                    $(this).hide();
                }
            });
        };
        if (noresult == 0) {
            closer_section.append('<li class="no-results-found">No results found.</li>');
        }
    });
</script>
<script>
    $('#myModal').on('shown.bs.modal', function(e) {
        $("#patient_id").prop("selectedIndex", 0);
        var password = makeid(5);
        $('#password').val("").val(password);
    })
</script>

<script type="text/javascript">
    $('#myModal').on('hidden.bs.modal', function(e) {
        $(this).find('#formadd')[0].reset();
    });

    $('#myModalpa').on('hidden.bs.modal', function(e) {
        $(this).find('#formaddpa')[0].reset();
    });

    $(function() {
        $('#easySelectable').easySelectable();
        $('.select2').select2()

    })

    function makeid(length) {
        var result = '';
        var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        var charactersLength = characters.length;
        for (var i = 0; i < length; i++) {
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
        }
        return result;
    }

    function get_PatientDetails(id) {

        var img_url = "<?php echo base_url(); ?>backend/images/loading.gif";
        $("#ajax_load").html("<center><img src='" + img_url + "'/>");
        var password = makeid(5)
        if (id == '') {
            $("#ajax_load").html("");
            $("#patientDetails").hide();
        } else {

            $.ajax({
                url: base_url + 'admin/patient/patientDetails',
                type: "POST",
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(res) {

                    if (res) {

                        $("#ajax_load").html("");
                        $("#patientDetails").show();
                        $('#patient_unique_id').html(res.id);
                        $('#patient_id').val(res.id);
                        $('#password').val(password);
                        $('#revisit_password').val(password);
                        $('#listname').html(res.patient_name + " (" + res.id + ")");
                        $('#guardian').html(res.guardian_name);
                        $('#listnumber').html(res.mobileno);
                        $('#email').html(res.email);
                        $('#mobnumber').val(res.mobileno);
                        $('#pemail').val(res.email);
                        $('#patientname').val(res.patient_name);
                        $('#age').html(res.patient_age);
                        $('#doctname').val(res.name + " " + res.surname);
                        $("#bp").html(res.bp);
                        $("#symptoms").html(res.symptoms);
                        $("#known_allergies").html(res.known_allergies);
                        $("#insurance_id").html(res.insurance_id);
                        $("#validity").html(res.insurance_validity);
                        $("#identification_number").html(res.identification_number);
                        $("#address").html(res.address);
                        $("#note").html(res.note);
                        $("#height").html(res.height);
                        $("#weight").html(res.weight);
                        $("#genders").html(res.gender);
                        $("#marital_status").html(res.marital_status);
                        $("#blood_group").html(res.blood_group_name);
                        $("#allergies").html(res.known_allergies);
                        $("#image").attr("src", '<?php echo base_url() ?>' + res.image);
                    } else {
                        $("#ajax_load").html("");
                        $("#patientDetails").hide();
                    }
                }
            });
        }
    }

    function get_Charges(orgid) {
        var charge = $('.charge').val();
        var apply_amount = 0;
        $.ajax({
            url: base_url + 'admin/patient/getChargeById',
            type: "POST",
            data: {
                charge_id: charge,
                organisation_id: orgid
            },
            dataType: 'json',
            success: function(res) {
                if (res) {
                    $('#percentage').val(res.percentage);
                    if (orgid) {
                        if (res.percentage == null) {
                            apply_amount = parseFloat(res.org_charge);
                        } else {
                            apply_amount = (parseFloat(res.org_charge) * res.percentage / 100) + (parseFloat(res.org_charge));
                        }

                        $('#apply_charge').val(res.org_charge);
                        $('#apply_amount').val(apply_amount);
                        $('#standard_charge').val(res.standard_charge);
                        $('#paid_amount').val(res.apply_amount);
                    } else {
                        if (res.percentage == null) {
                            apply_amount = parseFloat(res.standard_charge);
                        } else {
                            apply_amount = (parseFloat(res.standard_charge) * res.percentage / 100) + (parseFloat(res.standard_charge));
                        }

                        $('#standard_charge').val(res.standard_charge);
                        $('#apply_charge').val(res.standard_charge);
                        $('#apply_amount').val(apply_amount);
                        $('#paid_amount').val(res.apply_amount);
                    }
                } else {
                    $('#standard_charge').val('');
                    $('#apply_charge').val('');
                }
            }
        });
    }

    function get_Chargesrevisit(id) {
        $("#standard_chargerevisit").html("standard_charge");
        var orgid = $("#revisit_organisation").val();
        if (id == '') {
            id = $("#revisit_doctor").val();
        }

        $.ajax({
            url: base_url + 'admin/patient/doctCharge',
            type: "POST",
            data: {
                doctor: id,
                organisation: orgid
            },
            dataType: 'json',
            success: function(res) {

                if (res) {
                    if (orgid) {
                        $('#revisit_amount').val(res.org_charge);
                        $('#standard_chargerevisit').val(res.standard_charge);
                    } else {
                        $('#standard_chargerevisit').val(res.standard_charge);
                        $('#revisit_amount').val(res.standard_charge);
                    }

                } else {
                    $('#standard_chargerevisit').val('');
                    $('#revisit_amount').val('');
                }
            }
        });
    }


    $(document).on('select2:select', '.charge_category', function() {

        var charge_category = $(this).val();

        $('.charge').html("<option value=''><?php echo $this->lang->line('loading') ?></option>");
        getchargecode(charge_category, "");
    });


    function getchargecode(charge_category, charge_id) {
        var div_data = "<option value=''>Select</option>";
        if (charge_category != "") {
            $.ajax({
                url: base_url + 'admin/charges/getchargeDetails',
                type: "POST",
                data: {
                    charge_category: charge_category
                },
                dataType: 'json',
                success: function(res) {

                    $.each(res, function(i, obj) {
                        var sel = "";
                        div_data += "<option value='" + obj.id + "'>" + obj.name + "</option>";

                    });
                    $('.charge').html(div_data);
                    $(".charge").select2("val", charge_id);

                }
            });
        }
    }

    function update_amount(apply_charge) {

        var apply_amount = apply_charge;
        var tax_percentage = $('#percentage').val();
        if (tax_percentage != '' && tax_percentage != 0) {
            apply_amount = (parseFloat(apply_charge) * tax_percentage / 100) + (parseFloat(apply_charge));

            $('#apply_amount').val(apply_amount);


        } else {
            $('#apply_amount').val(apply_amount);
        }
    }

    $(document).on('select2:select', '.charge', function() {
        var charge = $(this).val();
        var orgid = $("#organisation").val();
        var apply_amount = 0;

        $.ajax({
            url: base_url + 'admin/patient/getChargeById',
            type: "POST",
            data: {
                charge_id: charge,
                organisation_id: orgid
            },
            dataType: 'json',
            success: function(res) {

                if (res) {
                    var tax = res.percentage;
                    var quantity = $('#qty').val();
                    $('#percentage').val(tax);
                    $('#apply_charge').val(parseFloat(res.standard_charge) * quantity);
                    $('#standard_charge').val(res.standard_charge);
                    $('#schedule_charge').val(res.org_charge);

                    $('#org_id').val(res.org_charge_id);

                    if (res.org_charge == null) {
                        if (res.percentage == null) {
                            apply_amount = parseFloat(res.standard_charge);
                        } else {
                            apply_amount = (parseFloat(res.standard_charge) * res.percentage / 100) + (parseFloat(res.standard_charge));
                        }

                        $('#apply_charge').val(res.standard_charge);
                        $('#apply_amount').val(apply_amount);
                        $('#paid_amount').val(apply_amount);


                    } else {
                        if (res.percentage == null) {
                            apply_amount = parseFloat(res.org_charge);
                        } else {
                            apply_amount = (parseFloat(res.org_charge) * res.percentage / 100) + (parseFloat(res.org_charge));
                        }

                        $('#apply_charge').val(res.org_charge);
                        $('#apply_amount').val(apply_amount);
                        $('#paid_amount').val(apply_amount);


                    }
                } else {

                }
            }
        });
    });
</script>


<script type="text/javascript">
    /*
     Author: mee4dy@gmail.com
     */
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
        var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
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
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/jvectormap/jquery-jvectormap-1.2.2.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/datepicker/datepicker3.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/daterangepicker/daterangepicker-bs3.css">');
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

        $(".printsavedata").on('click', (function(e) {

            var form = $(this).parents('form').attr('id');
            var str = $("#" + form).serializeArray();
            var postData = new FormData();
            $.each(str, function(i, val) {
                postData.append(val.name, val.value);
            });


            $.ajax({
                url: base_url + 'admin/patient/add_revisit',
                type: "POST",
                data: postData,
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
                        patientid = $("#pid").val();
                        printVisitBill(patientid, data.id);
                    }
                    $("#formrevisitbtn").button('reset');
                },
                error: function() {

                }
            });

        }));
    });

    function printVisitBill(opdid) {
        $.ajax({
            url: base_url + 'admin/patient/printbill',
            type: "POST",
            data: {
                opd_id: opdid
            },
            dataType: 'json',
            beforeSend: function() {

            },
            success: function(data) {
                popup(data.page);
            },

            error: function(xhr) { // if error occured
                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                $this.button('reset');

            },
            complete: function() {
                $this.button('reset');

            }
        });


    }

    $(document).ready(function(e) {
        $("#formedit").on('submit', (function(e) {
            e.preventDefault();
            $.ajax({
                url: base_url + 'admin/patient/update',
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
                },
                error: function() {

                }
            });
        }));
    });

    /**/
    function getRecord(id) {

        $.ajax({
            url: base_url + 'admin/patient/getDetails',
            type: "POST",
            data: {
                recordid: id
            },
            dataType: 'json',
            success: function(data) {
                $("#patientid").val(data.id);
                $("#patient_name").val(data.patient_name);
                $("#contact").val(data.mobileno);
                $("#email").val(data.email);
                $("#age").val(data.age);
                $("#bp").val(data.bp);
                $("#month").val(data.month);
                $("#guardian_name").val(data.guardian_name);
                $("#appointment_date").val(data.appointment_date);
                $("#case").val(data.case_type);
                $("#symptoms").val(data.symptoms);
                $("#known_allergies").val(data.known_allergies);
                $("#refference").val(data.refference);
                $("#amount").val(data.amount);
                $("#tax").val(data.tax);
                $("#opdid").val(data.opdid);
                $("#address").val(data.address);
                $("#note").val(data.note);
                $("#height").val(data.height);
                $("#weight").val(data.weight);
                $("#updateid").val(id);
                $('select[id="blood_group"] option[value="' + data.blood_group + '"]').attr("selected", "selected");
                $('select[id="gender"] option[value="' + data.gender + '"]').attr("selected", "selected");
                $('select[id="marital_status"] option[value="' + data.marital_status + '"]').attr("selected", "selected");
                $('select[id="consultant_doctor"] option[value="' + data.cons_doctor + '"]').attr("selected", "selected");
                $('select[id="payment_mode"] option[value="' + data.payment_mode + '"]').attr("selected", "selected");
                $('select[id="casualty"] option[value="' + data.casualty + '"]').attr("selected", "selected");
            },

        })

    }

    function holdModal(modalId) {

        $('#' + modalId).modal({
            backdrop: 'static',
            keyboard: false,
            show: true
        });
    }
</script>
<script type="text/javascript">
    $("#myModal").on('hidden.bs.modal', function(e) {
        $(".filestyle").next(".dropify-clear").trigger("click");
        $("#patientDetails").hide();
        $('.select2-selection__rendered').html("");
        $('.cheque_div').css("display", "none");
        $('#formadd').find('input:text, input:password, input:file, textarea').val('');
        $('#formadd').find('select option:selected').removeAttr('selected');
        $('#formadd').find('input:checkbox, input:radio').removeAttr('checked');
    });

    $(".modalbtnpatient").click(function() {
        $('#formaddpa').trigger("reset");
        $(".dropify-clear").trigger("click");
    });


    $(document).on('change', '.payment_mode', function() {
        var mode = $(this).val();
        if (mode == "Cheque") {
            $('.cheque_div').css("display", "block");
        } else {
            $('.cheque_div').css("display", "none");
        }
    });
</script>
<script>
    $(document).ready(function() {
        $("form#formadd button[type=submit]").click(function() {
            $("button[type=submit]", $(this).parents("form")).removeAttr("clicked");
            $(this).attr("clicked", "true");
        });
        $("#formadd").on('submit', function(e) {
            e.preventDefault();
            let errorMessages = [];
            const requiredFields = [{
                    id: "addpatient_id",
                    label: "Patient Name"
                },
                {
                    id: "admission_date",
                    label: "Appointment Date"
                },
                {
                    id: "consultant_doctor",
                    label: "Consultant Doctor"
                },
                {
                    id: "apply_amount",
                    label: "Applied Charge"
                },
                {
                    id: "standard_charge",
                    label: "Amount"
                },
                {
                    id: "paid_amount",
                    label: "Paid Amount"
                },
                // {id:"case_type",   label:"Case"},                
            ];
            requiredFields.forEach(field => {
                const value = $(`#formadd #${field.id}`).val();
                if (!value || value.trim() === "") {
                    errorMessages.push(`${field.label} is required.`);
                }
            });
            const amount_to_be_paid = parseInt($("#formadd #apply_amount").val()) || 0;
            const amount_paying = parseInt($("#formadd #paid_amount").val()) || 0;
            if (amount_to_be_paid < amount_paying) {
                errorMessages.push("Invalid Amount: Paid Amount cannot exceed Applied Charge.");
            }
            if (errorMessages.length > 0) {
                errorMsg(errorMessages.join("\n"));
                return false;
            }
            const sub_btn_clicked = $("button[type=submit][clicked=true]");
            const formData = new FormData(this);
            const dataObject = {
                organisation: $("#organisation").val(),
                consultant_doctor: $("#consultant_doctor").val(),
                case: $("#case_type").val(),
                appointment_date: $("#admission_date").val(),
                symptoms_type: $("#symptoms_type").val(),
                symptoms: $("#symptoms").val(),
                bp: $("#bp").val(),
                height: $("#height").val(),
                weight: $("#weight").val(),
                pulse: $("#pulse").val(),
                spo2: $("#spo2").val(),
                temperature: $("#temperature").val(),
                respiration: $("#respiration").val(),
                known_allergies: $("#known_allergies").val(),
                old_patient: $("#patient_old").val(),
                casualty: $("#casualty").val(),
                refference: $("#refference").val(),
                note: $("#note").val(),
                payment_mode: $("#payment_mode").val(),
                patient_name: $("#patient_name").val(),
                live_consult: $("#live_consult").val(),
                standard_charge: $("#standard_charge").val(),
                charge_id: $("#charge_id").val(),
                tpa_charge: $("#tpa_charge").val(),
                percentage: $("#percentage").val(),
                apply_amount: $("#apply_amount").val(),
                amount: $("#amount").val(),
                patient_id: $("#addpatient_id").val()
            };
            const formObject = {
                cons_doctor: parseInt(dataObject.consultant_doctor) || 1,
                case_type: dataObject.case || "General",
                appointment_date: moment(dataObject.appointment_date, "MM/DD/YYYY hh:mm A").isValid() ?
                    moment(dataObject.appointment_date, "MM/DD/YYYY hh:mm A").format("YYYY-MM-DD HH:mm:ss") :
                    moment().format("YYYY-MM-DD HH:mm:ss"),
                symptoms_type: parseInt(dataObject.symptoms_type) || 0,
                symptoms: dataObject.symptoms || " ",
                bp: parseFloat(dataObject.bp) || 0,
                height: parseFloat(dataObject.height) || 0,
                weight: parseFloat(dataObject.weight) || 0,
                pulse: parseInt(dataObject.pulse) || 0,
                temperature: parseFloat(dataObject.temperature) || 0,
                respiration: parseInt(dataObject.respiration) || 0,
                known_allergies: dataObject.known_allergies || " ",
                patient_old: dataObject.old_patient || "No",
                casualty: dataObject.casualty || "No",
                refference: dataObject.refference || " ",
                date: moment().format("YYYY-MM-DD"),
                note: dataObject.note || "No additional notes",
                payment_mode: dataObject.payment_mode || " ",
                generated_by: dataObject.patient_name || " ",
                live_consult: dataObject.live_consult || "No",
                can_delete: "yes",
                payment_date: moment().format("YYYY-MM-DD"),
                time: moment(dataObject.appointment_date, "MM/DD/YYYY hh:mm A").isValid() ?
                    moment(dataObject.appointment_date, "MM/DD/YYYY hh:mm A").format("HH:mm:ss") :
                    moment().format("HH:mm:ss"),
                standard_charge: parseFloat(dataObject.standard_charge) || 0,
                charge_id: parseInt(dataObject.charge_id) || 1,
                tax: parseFloat(dataObject.percentage) || 0,
                apply_charge: parseFloat(dataObject.apply_amount) || 0,
                amount: parseFloat(dataObject.amount) || 0,
                patient_id: parseInt(dataObject.patient_id) || null,
                Hospital_id: <?= $data['hospital_id'] ?>,
                received_by_name: '<?= $data['username'] ?>',
            };
            if (dataObject.organisation != 0) {
                formObject.tpa_charge = parseFloat(dataObject.tpa_charge);
                formObject.organisation_id = parseInt(dataObject.organisation);
            }
            $.ajax({
                url: '<?= $api_base_url ?>opd-out-patient',
                type: "POST",
                data: JSON.stringify(formObject),
                dataType: 'json',
                contentType: 'application/json',
                cache: false,
                beforeSend: function() {
                    sub_btn_clicked.button('loading');
                },
                success: function(response) {
                    let message = response[0].message;
                    successMsg(message);
                    table.ajax.reload(null, false);
                    $('#myModal').modal('hide');
                    sub_btn_clicked.button('reset');
                },
                error: function() {
                    alert("Error occurred. Please try again");
                    sub_btn_clicked.button('reset');
                },
                complete: function() {
                    sub_btn_clicked.button('reset');
                }
            });
        });
    });
</script>

<!-- //========datatable start===== -->
<script type="text/javascript">
    (function($) {
        'use strict';
        $(document).ready(function() {
            initDatatable('ajaxlist', 'admin/bill/getopddatatable', [], [], 100);
        });
    }(jQuery))
</script>
<!-- //========datatable end===== -->
<?php $this->load->view('admin/patient/patientaddmodal'); ?>