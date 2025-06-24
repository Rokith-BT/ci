<?php
$currency_symbol = $this->customlib->getHospitalCurrencyFormat();
?>
<form id="opd_discharge" accept-charset="utf-8" class="" enctype="multipart/form-data">
    <input type="hidden" name="opd_id" value="<?php echo $opd_id; ?>" class="form-control">
    <input type="hidden" name="id" id="id" value="<?php if (!empty($discharge_card)) {
        echo $discharge_card['id'];
    } ?>" class="form-control">
    <input type="hidden" name="ipd_id" id="ipd_id" value="<?php echo $ipd_id; ?>" class="form-control">
    <input type="hidden" name="case_reference_id" value="<?php echo $case_id; ?>" class="form-control">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <div class="alert alert-warning">
                    <?php echo $this->lang->line('please_note_that_before_discharging_the_patient_check_his_bill') ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label><?php echo $this->lang->line('discharge_date'); ?></label><small class="req"> *</small>
                <input type="text" name="discharge_date" value="<?php if ((!empty($discharge_card)) && $discharge_card['discharge_date'] != '') {
                    echo $this->customlib->YYYYMMDDHisTodateFormat($discharge_card['discharge_date']);
                } ?>" class="form-control datetime" autocomplete="off">
                <span class="text-danger"></span>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label><?php echo $this->lang->line('discharge_status'); ?><small class="req"> *</small> </label>
                <select class="form-control death_status" name="death_status" id="death_status_id">
                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                    <?php foreach ($death_status as $key => $value) { ?>
                        <option <?php if ((!empty($discharge_card)) && $discharge_card['discharge_status'] == $key) {
                            echo "selected";
                        } ?> value="<?php echo $key ?>"><?php echo $value ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group">
                <label for="email"><?php echo $this->lang->line('note'); ?></label>
                <textarea name="note" id="note" class="form-control"><?php if (!empty($discharge_card)) {
                    echo $discharge_card['note'];
                } ?></textarea>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="email"><?php echo $this->lang->line('operation'); ?></label>
                <textarea name="operation" id="operation" class="form-control"><?php if (!empty($discharge_card)) {
                    echo $discharge_card['operation'];
                } ?></textarea>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="email"><?php echo $this->lang->line('diagnosis'); ?></label>
                <textarea name="diagnosis" id="diagnosis" class="form-control"><?php if (!empty($discharge_card)) {
                    echo $discharge_card['diagnosis'];
                } ?></textarea>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="email"><?php echo $this->lang->line('investigation'); ?></label>
                <textarea name="investigations" id="investigations" class="form-control"><?php if (!empty($discharge_card)) {
                    echo $discharge_card['investigations'];
                } ?></textarea>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="email"><?php echo $this->lang->line('treatment_at_home'); ?></label>
                <textarea name="treatment_home" id="treatment_home" class="form-control"><?php if (!empty($discharge_card)) {
                    echo $discharge_card['treatment_home'];
                } ?></textarea>
            </div>
        </div>
    </div>
    <div class="row death_status_div" style="display: none;">
        <div class="col-md-6">
            <div class="form-group">
                <label><?php echo $this->lang->line('death_date'); ?></label><small class="req"> *</small>
                <input type="text" value="<?php if ((!empty($discharge_card)) && $discharge_card['death_date'] != '') {
                    echo $this->customlib->YYYYMMDDHisTodateFormat($discharge_card['death_date']);
                } ?>" style="z-index: 1700;" name="death_date" id="death_date"
                    class="form-control datetime">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label><?php echo $this->lang->line('guardian_name'); ?></label><small class="req"> *</small>
                <input type="hidden" name="patient_id" value="<?php echo $patient_id; ?>">
                <input type="hidden" name="deathrecord_id" value="<?php if (!empty($deathrecord)) {
                    echo $deathrecord['id'];
                } ?>">
                <input type="text" value="<?php echo $guardian_name; ?>" name="guardian_name" id="guardian_name"
                    class="form-control">
                <span class="text-danger"><?php echo form_error('guardian_name'); ?></span>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="email"><?php echo $this->lang->line('attachment'); ?></label>
                <input type="file" name="document" id="document" class="form-control filestyle">
                <span class="text-danger"><?php echo form_error('document'); ?></span>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="email"><?php echo $this->lang->line('death_report'); ?></label>
                <textarea name="death_report" id="death_report" class="form-control"><?php if (!empty($deathrecord)) {
                    echo $deathrecord['death_report'];
                } ?></textarea>
            </div>
        </div>
        <div class="">
            <?php echo display_custom_fields('death_report'); ?>
        </div>
    </div>
    <div class="row reffer_div" style="display: none;">
        <div class="col-md-4">
            <div class="form-group">
                <label><?php echo $this->lang->line('referral_date'); ?></label><small class="req"> *</small>
                <input type="text" value="<?php if ((!empty($discharge_card)) && $discharge_card['refer_date'] != '') {
                    echo $this->customlib->YYYYMMDDHisTodateFormat($discharge_card['refer_date']);
                } ?>" name="referral_date" id="referral_date"
                    class="form-control datetime">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label><?php echo $this->lang->line('referral_hospital_name'); ?> </label><small class="req"> *</small>
                <input type="text" value="<?php if ((!empty($discharge_card)) && $discharge_card['refer_to_hospital'] != '') {
                    echo $discharge_card['refer_to_hospital'];
                } ?>" name="hospital_name" id="hospital_name" class="form-control">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label><?php echo $this->lang->line('reason_for_referral'); ?></label>
                <?=$discharge_card['reason_for_referral']?>
                <input type="text" value="<?php if ((!empty($discharge_card)) && $discharge_card['reason_for_referral'] != '') {
                    echo $discharge_card['reason_for_referral'];
                } ?>" name="reason_for_referral" id="reason_for_referral"
                    class="form-control">
            </div>
        </div>
    </div>
    <?php if ($this->rbac->hasPrivilege('opd_patient_discharge', 'can_edit') || $this->rbac->hasPrivilege('ipd_patient_discharge', 'can_edit')) { ?>
        <div class="row">
            <div class="box-footer col-md-12">
                <div class="pull-right">
                    <button id="add_paymentbtn" type="submit"
                        data-loading-text="<?php echo $this->lang->line('processing'); ?>"
                        class="btn btn-info pull-right printsavebtn"><i class="fa fa-check-circle"></i>
                        <?php echo $this->lang->line('save'); ?></button>
                </div>
            </div>
        </div>
    <?php } ?>
</form>
<?php
$data = $this->session->userdata('hospitaladmin');
$api_base_url = $this->config->item('api_base_url');
?>
<script>
    $('#opd_discharge').submit(function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        var errormessages = [];

        if (!formData.get('discharge_date')) errormessages.push('Discharge Date is required');
        if (!formData.get('death_status')) errormessages.push('Discharge Status is required');
        if (formData.get('death_status') === "1" && !formData.get('death_date')) errormessages.push('Death Date is required when discharge status is "Deceased"');
        if (formData.get('death_status') === "1" && !formData.get('guardian_name')) errormessages.push('Guardian Name is required');
        if (formData.get('death_status') === "2" && !formData.get('referral_date')) errormessages.push('Referral Date is required when discharge status is "Referred"');
        if (formData.get('death_status') === "2" && !formData.get('hospital_name')) errormessages.push('Referral Hospital Name is required when discharge status is "Referred"');

        if (errormessages.length > 0) {
            errorMsg(errormessages.join("<br>"));
            return;
        }

        let currentUrl = window.location.href;
        let id = currentUrl.split('/').slice(-1)[0].split('#')[0];
        var discharge_status = formData.get('death_status');
        var jsonObject = {
            ipd_details_id: id,
            discharge_date: new Date(formData.get('discharge_date')).toISOString().split('T')[0],
            discharge_status: parseInt(discharge_status),
            operation: formData.get('operation') || "",
            diagnosis: formData.get('diagnosis') || "",
            investigations: formData.get('investigations') || "",
            treatment_home: formData.get('treatment_home') || "",
            note: formData.get('note') || "",
            guardian_name: formData.get('guardian_name') || "",
            Hospital_id: '<?= $data['hospital_id'] ?>',
            discharge_by: '<?= $data["id"] ?>',
            is_active: "no",
            discharged: "yes"
        };

        if (discharge_status === "1") {
            jsonObject.death_date = new Date(formData.get('death_date')).toISOString().split('T')[0];
            jsonObject.death_report = formData.get('death_report') || "";
            const imageFile = $('#document')[0].files[0];
            const uploadData = new FormData();
            uploadData.append('file', imageFile);

            if (imageFile) {
                $.ajax({
                    url: 'https://phr-api.plenome.com/file_upload',
                    type: 'POST',
                    data: uploadData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        if (response && response.data) {
                            jsonObject.attachment = response.data;
                            jsonObject.attachment_name = response.data;
                            submitForm(jsonObject);
                        } else {
                            alert('File upload failed. Please try again.');
                        }
                    },
                    error: function () {
                        alert('File upload failed. Please try again.');
                    }
                });
            } else {
                jsonObject.attachment = " ";
                jsonObject.attachment_name = " ";
                submitForm(jsonObject);
            }
        } else if (discharge_status === "2") {
            jsonObject.refer_date = new Date(formData.get('referral_date')).toISOString().split('T')[0];
            jsonObject.refer_to_hospital = formData.get('hospital_name') || "";
            jsonObject.reason_for_referral = formData.get('reason_for_referral') || "";
            submitForm(jsonObject);
        } else {
            submitForm(jsonObject);
        }

        function submitForm(jsonData) {
            var jsonString = JSON.stringify(jsonData, null, 2);
            console.log(jsonString);
            let edit_id = $('#id').val();
            let url = edit_id
                ? "<?= $api_base_url ?>discharge-patient-ipd-module/" + jsonData.ipd_details_id
                : "<?= $api_base_url ?>discharge-patient-ipd-module";
            let method = edit_id ? "PATCH" : "POST";

            $.ajax({
                url: url,
                type: method,
                data: jsonString,
                contentType: 'application/json',
                success: function (response) {
                    if (response.status === 'failed') {
                        errorMsg(response.message);
                    } else {
                        successMsg(response[0]?.['data '].messege);
                        const dischargeStatus = response[0]?.['data ']?.Discharge_patient_details[0]?.discharge_status;
                        if(dischargeStatus=="1"){
                            window.location.href = "<?php echo base_url(); ?>admin/patient/ipdsearch";
                        }else{
                            window.location.reload();
                        }                        
                    }
                },
                error: function () {
                    errorMsg('Failed to update discharge details. Please try again.');
                }
            });
        }
    });
</script>


<script type="text/javascript">
    $('.death_status').trigger("change");
    var download = "";
    <?php if( (!empty($deathrecord)) && $deathrecord['attachment']!= "" ){ ?> 

        var download = ' <a href="<?php echo site_url('admin/birthordeath/download_deathrecord/'.$deathrecord['id']); ?> "   class="" data-recordId="" data-toggle="tooltip" data-placement="top" data-title="<?php echo $this->lang->line('download') ;?>" ><i class="fa fa-download"></i> </a>&nbsp; ' ;
        <?php }   ?>
    <?php if((!empty($discharge_card))){ ?>
      
$('#allpayments_print').html(' <a href="javascript:void(0);"   class="print_dischargecard" data-recordId="<?php echo $discharge_card['id'];?>" data-case_id="<?php echo $case_id; ?>" data-toggle="tooltip" data-placement="top" data-title="<?php echo $this->lang->line('print') ;?>" ><i class="fa fa-print"></i> </a>&nbsp; '+download);

<?php }   ?>

</script>