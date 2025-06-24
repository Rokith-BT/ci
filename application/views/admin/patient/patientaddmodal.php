<?php
$genderList = $this->customlib->getGender_Patient();
$marital_status = $this->config->item('marital_status');
?>
<script>
    $(document).ready(function() {
        $('#birth_date').datepicker({
            endDate: new Date(),
            format: 'mm/dd/yyyy',
            autoclose: true,
            todayHighlight: true
        });
    });

    function clearPatientForm() {
        const form = document.getElementById('formaddpatient');
        form.reset();
        form.querySelectorAll('input, textarea').forEach(el => el.value = '');
        form.querySelectorAll('select').forEach(el => {
            el.value = '';
            if (typeof $(el).trigger === 'function') {
                $(el).trigger('change');
            }
        });
        form.querySelectorAll('.text-danger').forEach(el => el.textContent = '');
    }
</script>

<div class="modal fade" id="myModalpa" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal" onclick="clearPatientForm()">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('add_patient'); ?></h4>
            </div>
            <form id="formaddpatient" accept-charset="utf-8" action="" enctype="multipart/form-data" method="post">
                <div class="scroll-area">
                    <div class="modal-body pt0 pb0">
                        <div class="ptt10">
                            <div class="row row-eq">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('name'); ?></label><small
                                                    class="req"> *</small>
                                                <input id="name" name="patient_name" placeholder="" type="text"
                                                    class="form-control" value="<?php echo set_value('name'); ?>"
                                                    oninput="this.value=this.value.replace(/[^a-zA-Z\s]/g, '')" />
                                                <span class="text-danger"><?php echo form_error('name'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('guardian_name') ?></label>
                                                <input type="text" name="guardian_name" placeholder="" value=""
                                                    class="form-control"
                                                    oninput="this.value=this.value.replace(/[^a-zA-Z\s]/g, '')">
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-sm-12">
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label> <?php echo $this->lang->line('gender'); ?></label><small
                                                            class="req"> *</small>
                                                        <select class="form-control" name="gender" id="addformgender">
                                                            <option value=""><?php echo $this->lang->line('select'); ?>
                                                            </option>
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
                                                    </div>
                                                </div>
                                                <div class="col-sm-5" id="calculate">
                                                    <div class="form-group">
                                                        <label><?php echo $this->lang->line('age') . ' (' . $this->lang->line('yy_mm_dd') . ')'; ?>
                                                        </label><small class="req"> *</small>
                                                        <div style="clear: both;overflow: hidden;">
                                                            <input type="text"
                                                                placeholder="<?php echo $this->lang->line('year'); ?>"
                                                                name="age[year]" id="age_year" value=""
                                                                class="form-control patient_age_year"
                                                                oninput="findDOB()" style="width: 30%; float: left;">
                                                            <input type="text" id="age_month"
                                                                placeholder="<?php echo $this->lang->line('month'); ?>"
                                                                name="age[month]" value=""
                                                                class="form-control patient_age_month"
                                                                oninput="this.value=this.value.replace(/[^0-9]/g, '')"
                                                                style="width: 36%; float: left; margin-left: 4px;" readonly>
                                                            <input type="text" id="age_day"
                                                                placeholder="<?php echo $this->lang->line('day'); ?>"
                                                                name="age[day]" value=""
                                                                oninput="this.value=this.value.replace(/[^0-9]/g, '')"
                                                                class="form-control patient_age_day"
                                                                style="width: 26%; float: left; margin-left: 4px;" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label
                                                            for="dob"><?php echo $this->lang->line('date_of_birth'); ?></label><small
                                                            class="req"> *</small>
                                                        <input type="text" name="dob" id="birth_date" placeholder=""
                                                            class="form-control date patient_dob"
                                                            readonly /><?php echo set_value('dob'); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--./col-md-6-->
                                        <div class="col-md-6 col-sm-12">
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label><?php echo $this->lang->line('blood_group'); ?></label>
                                                        <select name="blood_bank_product_id" class="form-control">
                                                            <option value=""><?php echo $this->lang->line('select') ?>
                                                            </option>
                                                            <?php
                                                            foreach ($bloodgroup as $key => $value) {
                                                            ?>
                                                                <option value="<?php echo $key; ?>" <?php if (set_value('blood_group') == $key) {
                                                                                                        echo "selected";
                                                                                                    }
                                                                                                    ?>><?php echo $value; ?></option>
                                                            <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label
                                                            for="pwd"><?php echo $this->lang->line('marital_status'); ?></label>
                                                        <select name="marital_status" class="form-control">
                                                            <option value=""><?php echo $this->lang->line('select') ?>
                                                            </option>
                                                            <?php foreach ($marital_status as $mkey => $mvalue) {
                                                            ?>
                                                                <option value="<?php echo $mvalue; ?>"
                                                                    <?php if (set_value('marital_status') == $mkey) echo "selected"; ?>>
                                                                    <?php echo $mvalue; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="exampleInputFile">
                                                            <?php echo $this->lang->line('patient_photo'); ?>
                                                        </label>
                                                        <div><input class="filestyle form-control" type='file'
                                                                name='file' id="file" size='20' data-height="26" accept="image/*" />
                                                        </div>
                                                        <span
                                                            class="text-danger"><?php echo form_error('file'); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--./col-md-6-->


                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label for="pwd"><?php echo $this->lang->line('phone'); ?></label><small
                                                    class="req"> *</small>
                                                <input id="number" autocomplete="off" name="mobileno" type="tel"
                                                    placeholder="Enter mobile number" class="form-control"
                                                    value="<?php echo set_value('mobileno'); ?>" pattern="[0-9]{10}"
                                                    inputmode="numeric" maxlength="10"
                                                    oninput="this.value = this.value.replace(/[^0-9]/g, '');" />
                                                <span class="text-danger"><?php echo form_error('mobileno'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('email'); ?></label>
                                                <input type="text" placeholder="" id="addformemail"
                                                    value="<?php echo set_value('email'); ?>" name="email"
                                                    class="form-control">
                                                <span class="text-danger"><?php echo form_error('email'); ?></span>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label
                                                    for="address"><?php echo $this->lang->line('address'); ?></label><small
                                                    class="req"> *</small>
                                                <input name="address" placeholder=""
                                                    class="form-control" /><?php echo set_value('address'); ?>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="pwd"><?php echo $this->lang->line('remarks'); ?></label>
                                                <textarea name="note" id="note"
                                                    class="form-control"><?php echo set_value('note'); ?></textarea>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label
                                                    for="email"><?php echo $this->lang->line('any_known_allergies'); ?></label>
                                                <textarea name="known_allergies" id="" placeholder=""
                                                    class="form-control"><?php echo set_value('known_allergies'); ?></textarea>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label
                                                    for="insurance_id"><?php echo $this->lang->line("tpa_id"); ?></label>
                                                <input name="insurance_id" placeholder=""
                                                    class="form-control" /><?php echo set_value('insurance_id'); ?>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label
                                                    for="validity"><?php echo $this->lang->line("tpa_validity"); ?></label>
                                                <input name="insurance_validity" placeholder=""
                                                    class="form-control date" readonly />
                                                <?php echo set_value('validity'); ?>

                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line("national_identification_number"); ?></label>
                                                <input name="ABHA_number" placeholder="" class="form-control"
                                                    oninput="this.value=this.value.replace(/[^0-9]/g, '')" /><?php echo set_value('identification_number'); ?>
                                            </div>
                                        </div>
                                        <div class="">

                                            <?php
                                            echo display_custom_fields('patient');
                                            ?>

                                        </div>
                                    </div>
                                    <!--./row-->
                                </div>
                                <!--./col-md-8-->
                            </div>
                            <!--./row-->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="pull-right">
                        <button type="submit" id="formaddpabtn"
                            data-loading-text="<?php echo $this->lang->line('processing'); ?>"
                            class="btn btn-info pull-right"><i class="fa fa-check-circle"></i>
                            <?php echo $this->lang->line('save'); ?></button>
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
<script type="text/javascript">
    function addappointmentModal(patient_id = '', modalid) {
        var div_data = '';
        $.ajax({
            url: '<?php echo base_url(); ?>admin/patient/getpatientDetails',
            type: "POST",
            data: {
                id: patient_id
            },
            dataType: 'json',
            success: function(data) {
                var option = new Option(data.patient_name + " (" + data.id + ")", data.id, true, true);
                $(".patient_list_ajax").append(option).trigger('change');

                $("#" + modalid).modal('show');
                holdModal(modalid);
            }
        })
    }
</script>
<script type="text/javascript">
    $(".patient_dob").on('changeDate', function(event, date) {

        var birth_date = $(".patient_dob").val();

        $.ajax({
            url: '<?php echo base_url(); ?>admin/patient/getpatientage',
            type: "POST",
            dataType: "json",
            data: {
                birth_date: birth_date
            },
            success: function(data) {
                $('.patient_age_year').val(data.year);
                $('.patient_age_month').val(data.month);
                $('.patient_age_day').val(data.day);
            }
        });
    });
</script>
<script>
    $(document).ready(function() {
        $("#formaddpatient").on('submit', async function(e) {
            e.preventDefault();
            $('#formaddpabtn').button('loading');
            try {
                let formElement = $(this);
                let fileUploadUrl = 'https://phr-api.plenome.com/file_upload';
                let requiredFields = {
                    'patient_name': 'Patient Name',
                    'gender': 'Gender',
                    'mobileno': 'Mobile Number',
                    'dob': 'Date of Birth',
                    'address': 'Address'
                };
                let missingFields = [];
                Object.keys(requiredFields).forEach(field => {
                    let inputElement = formElement.find(`[name="${field}"]`);
                    if (inputElement.length === 0 || !inputElement.val().trim()) {
                        missingFields.push(requiredFields[field]);
                    }
                });
                if (missingFields.length > 0) {
                    throw new Error(`Please fill in the required field: ${missingFields.join(', ')}`);
                }
                let mobileno = formElement.find('[name="mobileno"]').val().trim();
                let email = formElement.find('[name="email"]').val().trim();
                let mobilePattern = /^[0-9]{10}$/;
                if (!mobilePattern.test(mobileno)) {
                    throw new Error("Please enter a valid 10-digit mobile number.");
                }
                if (email !== '') {
                    let emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
                    if (!emailPattern.test(email)) {
                        throw new Error("Please enter a valid email address.");
                    }
                }

                let fileInput = formElement.find('#file')[0];
                let uploadedFileData = null;

                if (fileInput && fileInput.files.length > 0) {
                    uploadedFileData = await uploadFile(formElement, fileUploadUrl);
                }

                await handleFormSubmission(formElement, uploadedFileData);
            } catch (error) {
                errorMsg(error.message);
                resetButtonWithDelay($('#formaddpabtn'));
            }
        });

        async function handleFormSubmission(formElement, uploadedFileData) {
            let clicked_submit_btn = formElement.find(':submit');
            let formData = new FormData(formElement[0]);
            let formObject = {
                'lang_id': 4,
                'Hospital_id': <?= $data['hospital_id'] ?>,
                "is_confirmed_to_create_new_patient": true
            };

            if (uploadedFileData) {
                formObject.image = uploadedFileData;
            }

            formData.forEach((value, key) => {
                if (!(value instanceof File) && value.trim() !== '') {
                    formObject[key] = value;
                }
            });

            if (formObject.insurance_validity) {
                formObject.insurance_validity = moment(formObject.insurance_validity, "MM/DD/YYYY").format("YYYY-MM-DD HH:mm:ss") || "";
            }
            formObject.blood_bank_product_id = formObject.blood_bank_product_id
            if (formObject.dob) {
                formObject.dob = formatDateTime(formObject.dob);
            }

            try {
                let response = await $.ajax({
                    url: '<?= $api_base_url ?>setup-patient-new-patient',
                    type: "POST",
                    data: JSON.stringify(formObject),
                    contentType: 'application/json',
                    dataType: 'json',
                    headers: {
                        'Authorization': accesstoken
                    },
                    beforeSend: function() {
                        clicked_submit_btn.button('loading');
                    }
                });

                if (response[0].status === 'failed') {
                    errorMsg(response[0].message || "Error occurred. Please try again.");
                    return false;
                }
                let message = response[0]?.['data ']?.messege;
                successMsg(message);
                addappointmentModal(response[0]["data "]["id  "], 'myModal');
                $("#formaddpatient")[0].reset();
                $('#myModalpa').modal('hide');
            } catch (error) {
                errorMsg(error.message || "Error occurred. Please try again.");
            } finally {
                resetButtonWithDelay(clicked_submit_btn);
            }
        }

        async function uploadFile(formElement, apiUrl) {
            let clicked_submit_btn = formElement.find(':submit');
            let formData = new FormData();
            let fileInput = formElement.find('#file')[0];

            if (!fileInput || fileInput.files.length === 0) {
                return null;
            }
            formData.append('file', fileInput.files[0]);
            try {
                let response = await $.ajax({
                    url: apiUrl,
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    beforeSend: function() {
                        clicked_submit_btn.button('loading');
                    }
                });

                if (response.status !== 'success') {
                    throw new Error(response.message);
                }

                return response.data;
            } catch (error) {
                throw new Error("File upload failed. Please try again.");
            } finally {
                resetButtonWithDelay(clicked_submit_btn);
            }
        }

        function resetButtonWithDelay(button) {
            setTimeout(() => {
                button.button('reset');
            }, 3000);
        }

        function formatDateTime(dateTimeStr) {
            const [day, month, year] = dateTimeStr.split('/');
            const formattedInput = `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`;
            const date = new Date(formattedInput);
            if (isNaN(date.getTime())) {
                return "";
            }
            const yyyy = date.getFullYear();
            const mm = String(date.getMonth() + 1).padStart(2, '0');
            const dd = String(date.getDate()).padStart(2, '0');
            const hh = String(date.getHours()).padStart(2, '0');
            const min = String(date.getMinutes()).padStart(2, '0');
            const ss = String(date.getSeconds()).padStart(2, '0');

            return `${yyyy}-${mm}-${dd} ${hh}:${min}:${ss}`;
        }
    });
</script>
<script>
    function findDOB() {
        var year = document.getElementById('age_year').value;
        var month = document.getElementById('age_month').value;
        var day = document.getElementById('age_day').value;
        var today = new Date();
        if (year) {
            var dob = new Date(today.getFullYear() - year, today.getMonth(), today.getDate());
            if (month) {
                dob.setMonth(month - 1);
            }
            if (day) {
                dob.setDate(day);
            }
            if (dob > today) {
                dob.setFullYear(dob.getFullYear() - 1);
            }
            var dobFormatted = dob.getDate() + '/' + (dob.getMonth() + 1) + '/' + dob.getFullYear();
            document.getElementById('birth_date').value = dobFormatted;
        } else {
            var defaultDOB = today.getDate() + '/' + (today.getMonth() + 1) + '/' + today.getFullYear();
            document.getElementById('birth_date').value = defaultDOB;
        }
    }
</script>