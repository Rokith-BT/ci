<?php
$currency_symbol = $this->customlib->getHospitalCurrencyFormat();
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
                            <h3 class="box-title titlefix"><?php echo $this->lang->line('opd_old_patient'); ?></h3>
                        <?php } else { ?>
                            <h3 class="box-title titlefix"><?php echo $this->lang->line('opd_patient'); ?></h3>

                        <?php } ?>
                        <div class="box-tools addmeeting">
                            <?php if ($this->rbac->hasPrivilege('opd_patient', 'can_add')) { ?>
                                <a data-toggle="modal" id="add" onclick="holdModal('myModal')"
                                    class="btn btn-primary btn-sm addpatient"><i class="fa fa-plus"></i>
                                    <?php echo $this->lang->line('add_patient'); ?></a>
                            <?php } ?>

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
                        <table class="table table-striped table-bordered table-hover" id="ajaxlist">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-left">S.No</th>
                                    <th class="text-center"><?php echo $this->lang->line('name') ?></th>
                                    <th class="text-center"><?php echo $this->lang->line('patient_id'); ?></th>
                                    <th class="text-center"><?php echo $this->lang->line('guardian_name') ?></th>
                                    <th class="text-center"><?php echo $this->lang->line('gender'); ?></th>
                                    <th class="text-center"><?php echo $this->lang->line('phone'); ?></th>
                                    <th class="text-center"><?php echo $this->lang->line('consultant'); ?></th>
                                    <th class="text-center"><?php echo $this->lang->line('last_visit'); ?></th>
                                    <th class="text-right"><?php echo $this->lang->line('total_recheckup'); ?></th>
                                </tr>
                            </thead>
                            <tbody id="table-body"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog pup100" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close pupclose" data-dismiss="modal">&times;</button>
                <div class="row">
                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-9">
                        <div class="p-2 select2-full-width">
                            <select onchange="get_PatientDetails(this.value)" class="form-control patient_list_ajax"
                                <?php if ($disable_option == true) {
                                    echo 'disabled';
                                } ?> name="addpatient_id" id="addpatient_id">
                                <option value="" disabled selected>Select patient</option>
                                <!-- Other options will go here -->
                            </select>

                            <span class="text-danger"><?php echo form_error('refference'); ?></span>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-3 col-xs-1">

                        <?php if ($this->rbac->hasPrivilege('patient', 'can_add')) { ?>
                            <a data-toggle="modal" id="add" onclick="holdModal('myModalpa')" class="modalbtnpatient"><i
                                    class="fa fa-plus"></i> <span><?php echo $this->lang->line('new_patient'); ?></span></a>
                        <?php } ?>
                    </div>
                </div>
            </div>

        </div>
        <!--./modal-header-->
        <form id="addopdpatient" accept-charset="utf-8" enctype="multipart/form-data" method="post">
            <div class="pup-scroll-area">
                <div class="modal-body pt0 pb0">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <input name="patient_id" id="patient_id" type="hidden" class="form-control" />
                            <input name="email" id="pemail" type="hidden" class="form-control" />
                            <input name="mobileno" id="mobnumber" type="hidden" class="form-control" />
                            <input name="patient_name" id="patientname" type="hidden" class="form-control" />
                            <input name="password" id="password" type="hidden" class="form-control" />
                            <div class="row row-eq">
                                <div class="col-lg-8 col-md-8 col-sm-8">
                                    <div id="ajax_load"></div>
                                    <div class="row ptt10" id="patientDetails" style="display:none">
                                        <div class="col-md-9 col-sm-9 col-xs-9">
                                            <ul class="singlelist">
                                                <li class="singlelist24bold">
                                                    <span id="listname"></span>
                                                </li>
                                                <li>
                                                    <i class="fas fa-user-secret" data-toggle="tooltip"
                                                        data-placement="top"
                                                        title="<?php echo $this->lang->line('guardian'); ?>"></i>
                                                    <span id="guardian"></span>
                                                </li>
                                            </ul>
                                            <ul class="multilinelist">
                                                <li>
                                                    <img id="gender_icon" src="" alt="Gender Icon" data-toggle="tooltip"
                                                        data-placement="top"
                                                        title="<?php echo $this->lang->line('gender'); ?>"
                                                        style="width: 20px; height: 20px;">
                                                    <span id="genders"></span>
                                                </li>
                                                <li>
                                                    <i class="fas fa-tint" data-toggle="tooltip" data-placement="top"
                                                        title="<?php echo $this->lang->line('blood_group'); ?>"></i>
                                                    <span id="blood_group"></span>
                                                </li>
                                                <li>
                                                    <i class="fas fa-ring" data-toggle="tooltip" data-placement="top"
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
                                                    <span id="listnumber"></span>
                                                </li>
                                                <li>
                                                    <i class="fa fa-envelope" data-toggle="tooltip" data-placement="top"
                                                        title="<?php echo $this->lang->line('email'); ?>"></i>
                                                    <span id="email"></span>
                                                </li>
                                                <li>
                                                    <i class="fas fa-street-view" data-toggle="tooltip"
                                                        data-placement="top"
                                                        title="<?php echo $this->lang->line('address'); ?>"></i>
                                                    <span id="address"></span>
                                                </li>

                                                <li>
                                                    <b><?php echo $this->lang->line('any_known_allergies') ?> </b>
                                                    <span id="allergies"></span>
                                                </li>
                                                <li>
                                                    <b><?php echo $this->lang->line('remarks') ?> </b>
                                                    <span id="note"></span>
                                                </li>
                                                <li>
                                                    <b><?php echo $this->lang->line("tpa_id"); ?> </b>
                                                    <span id="insurance_id"></span>
                                                </li>
                                                <li>
                                                    <b><?php echo $this->lang->line("tpa_validity"); ?> </b>
                                                    <span id="validity"></span>
                                                </li>
                                                <li>
                                                    <b><?php echo $this->lang->line("national_identification_number"); ?>
                                                    </b>
                                                    <span id="identification_number"></span>
                                                </li>
                                            </ul>
                                        </div><!-- ./col-md-9 -->
                                        <div class="col-md-3 col-sm-3 col-xs-3">
                                            <div class="pull-right">
                                                <?php
                                                $file = "uploads/patient_images/no_image.png";
                                                ?>
                                                <img class="modal-profile-user-img img-responsive" src="" id="image"
                                                    alt="User profile picture">
                                            </div>
                                        </div><!-- ./col-md-3 -->
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="dividerhr"></div>
                                        </div>
                                        <div class="col-sm-2 col-xs-4">
                                            <div class="form-group">
                                                <label for="pwd"><?php echo $this->lang->line('height'); ?></label>
                                                <input name="height" type="text" class="form-control"
                                                    onkeyup="this.value=this.value.replace(/[^0-9.]/g,'')" />
                                            </div>
                                        </div>
                                        <div class="col-sm-2 col-xs-4">
                                            <div class="form-group">
                                                <label for="pwd"><?php echo $this->lang->line('weight'); ?></label>
                                                <input name="weight" type="text" class="form-control"
                                                    onkeyup="this.value=this.value.replace(/[^0-9.]/g,'')" />
                                            </div>
                                        </div>
                                        <div class="col-sm-2 col-xs-4">
                                            <div class="form-group">
                                                <label for="pwd"><?php echo $this->lang->line('bp'); ?></label>
                                                <input name="bp" type="text" class="form-control"
                                                    onkeyup="this.value=this.value.replace(/[^0-9/]/g,'')" />
                                            </div>
                                        </div>
                                        <div class="col-sm-2 col-xs-4">
                                            <div class="form-group">
                                                <label for="pwd"><?php echo $this->lang->line('pulse'); ?></label>
                                                <input name="pulse" type="text" class="form-control"
                                                    onkeyup="this.value=this.value.replace(/[^0-9]/g,'')" />
                                            </div>
                                        </div>
                                        <div class="col-sm-2 col-xs-4">
                                            <div class="form-group">
                                                <label for="pwd"><?php echo $this->lang->line('temperature'); ?></label>
                                                <input name="temperature" type="text" class="form-control"
                                                    onkeyup="this.value=this.value.replace(/[^0-9.]/g,'')" />
                                            </div>
                                        </div>
                                        <div class="col-sm-2 col-xs-4">
                                            <div class="form-group">
                                                <label for="pwd"><?php echo $this->lang->line('respiration'); ?></label>
                                                <input name="respiration" type="text" class="form-control"
                                                    onkeyup="this.value=this.value.replace(/[^0-9]/g,'')" />
                                            </div>
                                        </div>
                                        <div class="col-sm-2 col-xs-4">
                                            <div class="form-group">
                                                <label for="Sp02">SpO2</label>
                                                <input name="Sp02" type="text" class="form-control"
                                                    onkeyup="this.value=this.value.replace(/[^0-9]/g,'')" />
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-xs-6">
                                            <div class="form-group">
                                                <label
                                                    for="exampleInputFile"><?php echo $this->lang->line('symptoms_type'); ?></label>
                                                <div>
                                                    <select name='symptoms_type' id="act"
                                                        class="form-control select2 act" style="width:100%">
                                                        <option value=""><?php echo $this->lang->line('select') ?>
                                                        </option>
                                                        <?php foreach ($symptomsresulttype as $dkey => $dvalue) { ?>
                                                            <option value="<?php echo $dvalue["id"]; ?>">
                                                                <?php echo $dvalue["symptoms_type"]; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <span
                                                    class="text-danger"><?php echo form_error('symptoms_type'); ?></span>
                                            </div>
                                        </div>
                                        <input name="rows[]" type="hidden" value="1">
                                        <div class="col-sm-3 col-xs-6">
                                            <label
                                                for="exampleInputFile"><?php echo $this->lang->line('symptoms_title'); ?></label>
                                            <div id="dd" class="wrapper-dropdown-3">
                                                <input class="form-control filterinput" type="text">
                                                <ul class="dropdown scroll150 section_ul">
                                                    <li><label
                                                            class="checkbox"><?php echo $this->lang->line('select'); ?></label>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 col-xs-12">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('symptoms_description'); ?></label>
                                                <textarea class="form-control" id="symptoms_description"
                                                    name="symptoms"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <label for="pwd"><?php echo $this->lang->line('note'); ?></label>
                                                <textarea name="note" rows="3"
                                                    class="form-control"><?php echo set_value('note'); ?></textarea>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <label
                                                    for="email"><?php echo $this->lang->line('any_known_allergies'); ?></label>
                                                <textarea name="known_allergies" rows="3" id="eknown_allergies"
                                                    class="form-control"><?php echo set_value('address'); ?></textarea>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <?php echo display_custom_fields('opd'); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!--./row-->
                                </div>
                                <!--./col-md-8-->
                                <div class="col-lg-4 col-md-4 col-sm-4 col-eq ptt10">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('appointment_date'); ?></label>
                                                <small class="req"> *</small>
                                                <input id="admission_date" name="appointment_date" placeholder=""
                                                    type="text" class="form-control datetime"
                                                    value="<?= date('d/m/Y') ?>" readonly />
                                                <span
                                                    class="text-danger"><?php echo form_error('appointment_date'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="exampleInputFile">
                                                    <?php echo $this->lang->line('case'); ?></label>
                                                <div><input class="form-control" type='text' name='case'
                                                        onkeyup="this.value=this.value.replace(/[^a-zA-Z]/g,'')" />
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
                                                                                                        ?>>
                                                                <?php echo $yesno_value ?>
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
                                                                                                        ?>>
                                                                <?php echo $yesno_value ?>
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
                                                <div>
                                                    <select class="form-control" onchange="get_Charges(this.value)"
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
                                                <div><input class="form-control" type='text' name='refference'
                                                        onkeyup="this.value=this.value.replace(/[^a-zA-Z]/g,'')" />
                                                </div>
                                                <span class="text-danger"><?php echo form_error('refference'); ?></span>
                                            </div>
                                        </div>

                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="consultant_doctor">
                                                    <?php echo $this->lang->line('consultant_doctor'); ?>
                                                </label>
                                                <small class="req"> *</small>
                                                <div>
                                                    <select name="consultant_doctor" id="consultant_doctor"
                                                        class="form-control select2"
                                                        <?php if ($disable_option == true) echo "disabled"; ?>
                                                        style="width:100%">
                                                        <option value="" disabled selected>
                                                            <?php echo $this->lang->line('select') ?></option>
                                                        <?php foreach ($doctors as $dkey => $dvalue): ?>
                                                            <option value="<?php echo $dvalue['id']; ?>"
                                                                <?php if (isset($doctor_select) && $doctor_select == $dvalue['id']) echo "selected"; ?>>
                                                                <?php echo $dvalue['name'] . ' ' . $dvalue['surname'] . ' (' . $dvalue['employee_id'] . ')'; ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                    <?php if ($disable_option == true): ?>
                                                        <input type="hidden" name="consultant_doctor"
                                                            value="<?php echo $doctor_select; ?>">
                                                    <?php endif; ?>
                                                </div>
                                                <span
                                                    class="text-danger"><?php echo form_error('consultant_doctor'); ?></span>
                                            </div>
                                        </div>


                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('charge_category'); ?></label><small
                                                    class="req"> *</small>
                                                <select name="charge_category" style="width: 100%"
                                                    class="form-control charge_category select2">
                                                    <option value="" disabled selected>
                                                        <?php echo $this->lang->line('select') ?></option>
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
                                                <label><?php echo $this->lang->line('charge'); ?></label><small
                                                    class="req"> *</small>
                                                <select name="charge_id" style="width: 100%"
                                                    class="form-control charge select2">
                                                    <option value="" disabled selected>
                                                        <?php echo $this->lang->line('select') ?></option>
                                                </select>
                                                <input type="hidden" class="form-control right-border-none"
                                                    name="org_charge_amount" id="org_charge_amount" readonly
                                                    autocomplete="off">
                                                <span
                                                    class="text-danger"><?php echo form_error('apply_charge'); ?></span>
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
                                                    class="req"> *</small><input type="text" name="amount"
                                                    id="apply_charge" onkeyup="update_amount(this.value)"
                                                    class="form-control" readonly>
                                                <span class="text-danger"><?php echo form_error('amount'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('amount') . " (" . $currency_symbol . ")" ?></label><small
                                                    class="req"> *</small><input type="text" name="apply_amount"
                                                    readonly id="apply_amount" class="form-control">

                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label
                                                    for="pwd"><?php echo $this->lang->line('payment_mode'); ?></label><small
                                                    class="req"> *</small>
                                                <select name="payment_mode" class="form-control payment_mode"
                                                    onchange="checkcheque(this.value)">
                                                    <option value="" selected disabled>Select Payment Method</option>
                                                    <?php foreach ($payment_mode as $payment_key => $payment_value) { ?>
                                                        <option value="<?php echo $payment_key; ?>">
                                                            <?php echo $payment_value; ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                                <span class="text-danger" id="paymentmethodchecker"></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label
                                                    for="pwd"><?php echo $this->lang->line('paid_amount') . " (" . $currency_symbol . ")"; ?></label><small
                                                    class="req"> *</small>
                                                <input type="text" name="paid_amount" id="paid_amount"
                                                    class="form-control"
                                                    oninput="this.value = this.value.replace(/[^0-9.]/g, ''); checkpaylater(this.value);">
                                                <span class="text-danger" id="amountchecker"></span>
                                            </div>
                                        </div>
                                        <div class="cheque_div" style="display: none;">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><?php echo $this->lang->line('cheque_no'); ?></label><small
                                                        class="req"> *</small>
                                                    <input type="text" name="cheque_no" id="cheque_no"
                                                        class="form-control">
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
                                                    <span
                                                        class="text-danger"><?php echo form_error('document'); ?></span>
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
                                                                                                        ?>>
                                                                <?php echo $yesno_value; ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>

                                                    <span
                                                        class="text-danger"><?php echo form_error('live_consult'); ?></span>
                                                </div>
                                            </div>
                                        <?php  } ?>

                                    </div>
                                    <!--./row-->
                                </div>
                                <!--./col-md-4-->
                            </div>
                            <!--./row-->
                        </div>
                        <!--./col-md-12-->
                    </div>
                    <!--./row-->
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
<?php
$data = $this->session->userdata('hospitaladmin');
$api_base_url = $this->config->item('api_base_url');
?>
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
                                                <option value="<?php echo $key; ?>"
                                                    <?php if (set_value('gender') == $key) echo "selected"; ?>>
                                                    <?php echo $value; ?></option>
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
                                                <option value="<?php echo $value; ?>"
                                                    <?php if (set_value('blood_group') == $key) echo "selected"; ?>>
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

                            </div>
                            <!--./row-->
                            <button type="submit"
                                class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                    </div>
                    <!--./col-md-12-->
                </div>
                <!--./row-->
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
            error: function(xhr) {
                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
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
            closer_section.append(
                '<li class="no-results-found"><?php echo $this->lang->line('no_result_found'); ?></li>');
        }
    });
</script>

<script type="text/javascript">
    $('#myModal').on('hidden.bs.modal', function(e) {
        $(this).find('#addopdpatient')[0].reset();
    });

    $('#myModalpa').on('hidden.bs.modal', function(e) {
        $(this).find('#formaddpa')[0].reset();
    });

    $(function() {
        $('#easySelectable').easySelectable();
        $('.select2').select2();
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
        var base_url = "<?php echo base_url(); ?>";
        var loadingGif = base_url + "backend/images/loading.gif";
        var defaultImg = base_url + "uploads/patient_images/no_image.png";
        var password = makeid(5);

        if (id === '') {
            $("#ajax_load").html("");
            $("#patientDetails").hide();
            return;
        }
        $("#ajax_load").html("<center><img src='" + loadingGif + "'/></center>");
        $.ajax({
            url: base_url + "admin/patient/patientDetails",
            type: "POST",
            data: {
                id: id
            },
            dataType: 'json',
            success: function(res) {
                $("#ajax_load").html("");
                if (!res) {
                    $("#patientDetails").hide();
                    return;
                }
                $("#patientDetails").show();
                $('#patient_unique_id').html(res.id);
                $('#patient_id').val(res.id);
                $('#password, #revisit_password').val(password);
                $('#listname').html(`${res.patient_name?.trim() || '-'} (${res.id})`);
                $('#guardian').html(res.guardian_name?.trim() || '-');
                $('#listnumber').html(res.mobileno?.trim() || '-');
                $('#email').html(res.email?.trim() || '-');
                $('#mobnumber').val(res.mobileno);
                $('#pemail').val(res.email);
                $('#patientname').val(res.patient_name);
                $('#age').html(res.patient_age?.trim() || '-');
                $('#doctname').val(`${res.name?.trim() || '-'} ${res.surname?.trim() || ''}`);
                $('#bp').html(res.bp?.trim() || '-');
                $('#symptoms').html(res.symptoms?.trim() || '-');
                $('#known_allergies, #allergies').html(res.known_allergies?.trim() || '-');
                $('#insurance_id').html(res.insurance_id?.trim() || '-');
                $('#validity').html(res.insurance_validity?.trim() || '-');
                $('#identification_number').html(res.ABHA_number?.trim() || '-');
                $('#address').html(res.address?.trim() || '-');
                $('#note').html(res.note?.trim() || '-');
                $('#height').html(res.height?.trim() || '-');
                $('#weight').html(res.weight?.trim() || '-');
                $('#genders').html(res.gender?.trim() || '-');
                $('#marital_status').html(res.marital_status?.trim() || '-');
                $('#blood_group').html(res.blood_group_name?.trim() || '-');

                let basePath = "<?= base_url() . 'uploads/hospital_content/' ?>";
                let genderIcon = document.getElementById("gender_icon");
                if (res.gender?.trim() === "Male") {
                    genderIcon.src = basePath + "Man.png";
                } else if (res.gender?.trim() === "Female") {
                    genderIcon.src = basePath + "Women.png";
                } else {
                    genderIcon.src = basePath + "Others.png";
                }

                if (res.image) {
                    $.ajax({
                        url: 'https://phr-api.plenome.com/file_upload/getDocs',
                        type: "POST",
                        contentType: "application/json",
                        data: JSON.stringify({
                            value: res.image
                        }),
                        success: function(decryptResponse) {
                            if (decryptResponse && typeof decryptResponse === 'string' &&
                                decryptResponse.length > 0 && !decryptResponse.includes(
                                    "[object Object]")) {
                                let base64Image = "data:image/png;base64," + decryptResponse;
                                $("#image").attr("src", base64Image);
                            } else {
                                $("#image").attr("src", defaultImg);
                            }
                        },
                        error: function() {
                            $("#image").attr("src", defaultImg);
                        }
                    });
                } else {
                    $("#image").attr("src", base_url + 'uploads/staff_images/no_image.png');
                }
            }
        });
    }

    function get_Charges(orgid) {
        var charge = $('.charge').val();
        $('#org_charge_amount').val('');
        var apply_amount = 0;
        $.ajax({
            url: '<?php echo base_url(); ?>admin/patient/getChargeById',
            type: "POST",
            data: {
                charge_id: charge,
                organisation_id: orgid
            },
            dataType: 'json',
            success: function(res) {

                if (res) {
                    $('#percentage').val(res.percentage);
                    if (organisation_id) {
                        if (res.percentage == null) {
                            apply_amount = parseFloat(res.org_charge);
                        } else {
                            apply_amount = (parseFloat(res.org_charge) * res.percentage / 100) + (parseFloat(res
                                .org_charge));
                        }

                        $('#org_charge_amount').val(res.org_charge);
                        $('#apply_charge').val(res.org_charge);
                        $('#apply_amount').val(apply_amount);
                        $('#standard_charge').val(res.standard_charge);
                        $('#paid_amount').val(res.apply_amount);
                    } else {
                        if (res.percentage == null) {
                            apply_amount = parseFloat(res.standard_charge);
                        } else {
                            apply_amount = (parseFloat(res.standard_charge) * res.percentage / 100) + (
                                parseFloat(res.standard_charge));
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
            url: '<?php echo base_url(); ?>admin/patient/doctCharge',
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
        var div_data = "<option value=''><?php echo $this->lang->line('select') ?></option>";
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
        $('#org_charge_amount').val('');
        $.ajax({
            url: '<?php echo base_url(); ?>admin/patient/getChargeById',
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
                            apply_amount = (parseFloat(res.standard_charge) * res.percentage / 100) + (
                                parseFloat(res.standard_charge));
                        }

                        $('#apply_charge').val(res.standard_charge);
                        $('#apply_amount').val(apply_amount.toFixed(2));
                        $('#paid_amount').val(apply_amount.toFixed(2));

                    } else {
                        if (res.percentage == null) {
                            apply_amount = parseFloat(res.org_charge);
                        } else {
                            apply_amount = (parseFloat(res.org_charge) * res.percentage / 100) + (
                                parseFloat(res.org_charge));
                        }

                        $('#org_charge_amount').val(res.org_charge);
                        $('#apply_charge').val(res.org_charge);
                        $('#apply_amount').val(apply_amount.toFixed(2));
                        $('#paid_amount').val(apply_amount.toFixed(2));

                    }
                } else {

                }
            }
        });
    });
</script>

<script type="text/javascript">
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
        var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0]
            .contentDocument.document : frame1[0].contentDocument;
        frameDoc.document.open();
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
        $(".printsavedata").on('click', (function(e) {
            var form = $(this).parents('form').attr('id');
            var str = $("#" + form).serializeArray();
            var postData = new FormData();
            $.each(str, function(i, val) {
                postData.append(val.name, val.value);
            });

            $.ajax({
                url: '<?php echo base_url(); ?>admin/patient/add_revisit',
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
                opd_id: opdid,
                type: 'all'
            },
            dataType: 'json',
            beforeSend: function() {

            },
            success: function(data) {
                popup(data.page);

            },
            error: function(xhr) {
                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                $this.button('reset');

            },
            complete: function() {}
        });
    }

    $(document).ready(function(e) {
        $("#formedit").on('submit', (function(e) {
            e.preventDefault();
            $.ajax({
                url: '<?php echo base_url(); ?>admin/patient/update',
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
            url: '<?php echo base_url(); ?>admin/patient/getDetails',
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
                $('select[id="blood_group"] option[value="' + data.blood_group + '"]').attr("selected",
                    "selected");
                $('select[id="gender"] option[value="' + data.gender + '"]').attr("selected", "selected");
                $('select[id="marital_status"] option[value="' + data.marital_status + '"]').attr("selected",
                    "selected");
                $('select[id="consultant_doctor"] option[value="' + data.cons_doctor + '"]').attr("selected",
                    "selected");
                $('select[id="payment_mode"] option[value="' + data.payment_mode + '"]').attr("selected",
                    "selected");
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
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    $(document).ready(function() {
        $('#addopdpatient').on('submit', async function(event) {
            event.preventDefault();
            const submitter = event.originalEvent.submitter;
            const action = submitter.id;
            const sub_btn_clicked_name = submitter.name || '';
            let formData = new FormData(this);
            let dataObject = {};
            let errorMessages = [];
            if ($("#addpatient_id").val() == null) errorMessages.push("Patient is required");
            if ($("#consultant_doctor").val() == null) errorMessages.push("Consultant Doctor is required");
            if ($("[name='payment_mode']").val() == null) errorMessages.push("Payment Mode is required");
            let requiredFields = {
                'charge_id': 'Charge',
                'apply_amount': 'Apply Amount',
                'amount': 'Amount',
                'charge_category': 'Charge Category',
                'paid_amount': 'Paid Amount'
            };
            formData.forEach((value, key) => {
                let val = typeof value === 'string' ? value.trim() : value;
                if (!val && requiredFields.hasOwnProperty(key)) {
                    errorMessages.push(requiredFields[key] + " is required");
                }
                dataObject[key] = val;
            });
            if (errorMessages.length) {
                const formattedMessage = `
                <strong>Please fill all required fields:</strong>
                <ul style="margin: 8px 0; padding-left: 20px;">
                    ${errorMessages.map(msg => `<li>${msg}</li>`).join('')}
                </ul>`;
                errorMsg(formattedMessage);
                setTimeout(() => {
                    $("#formaddbtn").button('reset');
                    $("#get_print").button('reset');
                }, 3000);
                return;
            }
            let jsonData = {
                "cons_doctor": parseInt(dataObject.consultant_doctor) || null,
                "case_type": dataObject.case || " ",
                "appointment_date": moment(dataObject.appointment_date, "DD/MM/YYYY").format("YYYY-MM-DD HH:mm:ss"),
                "symptoms_type": parseInt(dataObject.symptoms_type) || null,
                "symptoms": dataObject.symptoms || null,
                "bp": parseFloat(dataObject.bp) || "",
                "height": parseFloat(dataObject.height) || "",
                "weight": parseFloat(dataObject.weight) || "",
                "pulse": parseInt(dataObject.pulse) || "",
                "spo2": parseInt(dataObject.Sp02) || "",
                "temperature": parseFloat(dataObject.temperature) || "",
                "respiration": parseInt(dataObject.respiration) || "",
                "known_allergies": dataObject.known_allergies || "",
                "patient_old": dataObject.old_patient || "No",
                "casualty": dataObject.casualty || "No",
                "refference": dataObject.refference || "",
                "date": moment().format("YYYY-MM-DD"),
                "note": dataObject.note || "",
                "payment_mode": dataObject.payment_mode || "Cash",
                "generated_by": dataObject.patient_name || "",
                "live_consult": dataObject.live_consult || "No",
                "can_delete": "yes",
                "payment_date": moment().format("YYYY-MM-DD"),
                "time": moment().format("HH:mm:ss"),
                "standard_charge": parseFloat(dataObject.standard_charge) || "",
                "charge_id": parseInt(dataObject.charge_id) || null,
                "tax": parseFloat(dataObject.percentage) || "",
                "apply_charge": parseFloat(dataObject.apply_amount) || 0,
                "amount": $("#paid_amount").val() || 0,
                "patient_id": parseInt(dataObject.patient_id) || null,
                "Hospital_id": <?= $data['hospital_id'] ?>,
                "message": " ",
                "received_by_name": '<?= $data['username'] ?>',
            };
            if (dataObject.organisation != 0) {
                jsonData.organisation_id = parseInt(dataObject.organisation);
                jsonData.tpa_charge = dataObject.tpa_charge;
            }
            const apiUrl = `<?= $api_base_url ?>opd-out-patient`;
            const method = "POST";
            if (jsonData.payment_mode === 'Online') {
                razorpaystartProcess(jsonData, apiUrl, method, function(response) {
                    handleResponse(response, sub_btn_clicked_name === 'save_print', 'printVisitBill');
                });
            } else {
                sendAjaxRequest(apiUrl, method, jsonData, function(response) {
                    handleResponse(response, sub_btn_clicked_name === 'save_print', 'printVisitBill');
                });
            }
        });
    });
</script>
<script>
    const initialData = <?= json_encode($opdlist['data']) ?>;
    const totalRecords = <?= $opdlist['recordsFiltered'] ?>;
    // $(document).ready(function() {
    //     const table = $('#ajaxlist').DataTable({
    //         serverSide: true,
    //         searching: true,
    //         ordering: true,
    //         paging: true,
    //         lengthMenu: [5, 10, 25, 50],
    //         pageLength: 10,
    //         columnDefs: [{
    //             orderable: false,
    //             targets: [2, 5, 6]
    //         }],
    //         dom: 'Blfrtip',
    //         buttons: [{
    //                 extend: 'copyHtml5',
    //                 text: '<i class="fa fa-files-o"></i>',
    //                 titleAttr: 'Copy',
    //                 className: 'btn btn-default btn-copy'
    //             },
    //             {
    //                 extend: 'excelHtml5',
    //                 text: '<i class="fa fa-file-excel-o"></i>',
    //                 titleAttr: 'Excel',
    //                 className: 'btn btn-default btn-excel'
    //             },
    //             {
    //                 extend: 'csvHtml5',
    //                 text: '<i class="fa fa-file-text-o"></i>',
    //                 titleAttr: 'CSV',
    //                 className: 'btn btn-default btn-csv'
    //             },
    //             {
    //                 extend: 'pdfHtml5',
    //                 text: '<i class="fa fa-file-pdf-o"></i>',
    //                 titleAttr: 'PDF',
    //                 className: 'btn btn-default btn-pdf'
    //             },
    //             {
    //                 extend: 'print',
    //                 text: '<i class="fa fa-print"></i>',
    //                 titleAttr: 'Print',
    //                 className: 'btn btn-default btn-print'
    //             }
    //         ],
    //         language: {
    //             emptyTable: "No appointments found"
    //         },
    //         ajax: function(data, callback) {
    //             if (initialData && initialData.length > 0) {
    //                 renderTable(initialData, totalRecords, data, callback);
    //                 initialData.length = 0;
    //                 return;
    //             }
    //             $("#pageloader").fadeIn();
    //             const page = Math.floor(data.start / data.length) + 1;
    //             fetch(
    //                     `${baseurl}admin/patient/getopdpatientlist?limit=${data.length}&page=${page}&search=${data.search.value}`
    //                 )
    //                 .then(res => res.json())
    //                 .then(result => {
    //                     $("#pageloader").fadeOut();
    //                     renderTable(result.data, result.recordsTotal, data, callback);
    //                 })
    //                 .catch(() => {
    //                     $("#pageloader").fadeOut();
    //                     callback({
    //                         draw: data.draw,
    //                         recordsTotal: 0,
    //                         recordsFiltered: 0,
    //                         data: []
    //                     });
    //                 });
    //         }
    //     });
    // });
    $(document).ready(function() {
        initSummaryDataTable(`${baseurl}admin/patient/getopdpatientlist`, '', '#ajaxlist', '');
    })
    function renderSummaryTable(dataArray, recordCount, data, callback) {
        let count = 0;
        return (dataArray || []).map(item => {
            const encodedId = item.id ? btoa(item.id.toString()) : "";
            const profileUrl = `${baseurl}admin/patient/profile/${encodedId}`;
            const patientname = item.patient_name ?
                `<span class="text-primary" style="cursor:pointer;" onclick="window.location.href='${profileUrl}'">${item.patient_name}</span>` :
                "-";
            const patientId = item.id || "-";
            const guardianName = item.guardian_name || "-";
            const gender = item.gender || "-";
            const phone = item.mobileno || "-";
            const consultant = item.doctor || "-";
            const lastVisit = item.last_consultation ? formatDateDMY(item.last_consultation) : "-";
            const totalRecheckup = item.totalrecheckup || "0";
            return [
                ++count,
                patientname,
                patientId,
                guardianName,
                gender,
                phone,
                consultant,
                lastVisit,
                `<div class="text-right">${totalRecheckup}</div>`
            ];
        });
    }
    function formatDateDMY(dateStr) {
        const date = new Date(dateStr);
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        return `${day}/${month}/${year}`;
    }

    function checkcheque(value) {
        if (value === 'Paylater') {
            $("#paid_amount").prop("readonly", true);
            $('#amountchecker').text('');
            $('#get_print, #formaddbtn').prop('disabled', false);
        } else {
            const applyAmount = $("#apply_amount").val();
            $("#paid_amount").prop("readonly", false);
            $("#paid_amount").val(applyAmount);
            checkpaylater(applyAmount);
        }
    }

    function checkpaylater(value) {
        const paymentMode = $("[name='payment_mode']").val();
        const applyAmount = parseFloat($("#apply_amount").val());
        const paidAmount = parseFloat(value) || 0;
        if (paidAmount === 0) {
            $('#amountchecker').text('Paid amount cannot be 0');
            $('#get_print, #formaddbtn').prop('disabled', true);
            return;
        }
        if (paidAmount > applyAmount) {
            $('#amountchecker').text('Paid amount cannot be greater than the total amount.');
            $('#get_print, #formaddbtn').prop('disabled', true);
            return;
        }
        $('#amountchecker').text('');
        $('#get_print, #formaddbtn').prop('disabled', false);
    }
</script>
<!-- //========datatable end===== -->
<?php $this->load->view('admin/patient/patientaddmodal'); ?>