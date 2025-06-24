<?php
$currency_symbol = $this->customlib->getHospitalCurrencyFormat();
$genderList = $this->customlib->getGender();
$data = $this->session->userdata('hospitaladmin');
$api_base_url = $this->config->item('api_base_url');
?>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('radiology_test'); ?></h3>
                        <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('radiology_test', 'can_add')) { ?>
                                <button class="btn btn-primary btn-sm radiology addtest" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('please_wait'); ?>"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add_radiology_test'); ?></button>
                            <?php } ?>

                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="download_label"><?php echo $this->lang->line('radiology_test'); ?></div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover ajaxlist" cellspacing="0" width="100%" data-export-title="<?php echo $this->lang->line('radiology_test'); ?>">
                                <thead>
                                    <tr class="white-space-nowrap">
                                        <th><?php echo $this->lang->line('test_name'); ?></th>
                                        <th><?php echo $this->lang->line('short_name'); ?></th>
                                        <th><?php echo $this->lang->line('test_type'); ?></th>
                                        <th><?php echo $this->lang->line('category'); ?></th>
                                        <th><?php echo $this->lang->line('sub_category'); ?></th>
                                        <th><?php echo $this->lang->line('report_days'); ?></th>
                                        <?php
                                        if (!empty($fields)) {
                                            foreach ($fields as $fields_key => $fields_value) {
                                        ?>
                                                <th><?php echo $fields_value->name; ?></th>
                                        <?php
                                            }
                                        }
                                        ?>
                                        <th class="text-right"><?php echo $this->lang->line('tax'); ?> (%)</th>
                                        <th class="text-right"><?php echo $this->lang->line('charge') . " (" . $currency_symbol . ")"; ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('amount') . " (" . $currency_symbol . ")"; ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="modalicon">
                    <div id='edit_delete'>
                        <a href="#" data-target="#editModal" data-toggle="modal" title="" data-original-title="<?php echo $this->lang->line('edit'); ?>"><i class="fa fa-pencil"></i></a>

                        <a href="#" data-toggle="tooltip" title="" data-original-title="<?php echo $this->lang->line('delete'); ?>"><i class="fa fa-trash"></i></a>
                    </div>
                </div>
                <h4 class="modal-title"><?php echo $this->lang->line('test_details'); ?></h4>
            </div>
            <form id="view" accept-charset="utf-8" method="get">
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table mb0 table-striped table-bordered examples">

                        </table>
                        <div class="" id="parameterview">
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>
<div class="modal fade" id="addTestReportModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="modaltitle"></h4>
            </div>
            <form id="formadd" accept-charset="utf-8" method="post" class="ptt10">
                <div class="scroll-area">
                    <div class="modal-body pb0 pt0">

                    </div>
                </div>
                <div class="modal-footer">
                    <div class="pull-right">
                        <button type="submit" data-loading-text="<?php echo $this->lang->line('processing'); ?>" id="formaddbtn" class="btn btn-info"><i class="fa fa-check-circle"></i> <?php echo $this->lang->line('save'); ?></button>

                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- dd -->
<script type="text/javascript">
    var radiology_parmeter_rows = 1;

    function holdModal(modalId) {
        $('#' + modalId).modal({
            backdrop: 'static',
            keyboard: false,
            show: true
        });
    }



    $(document).on('click', '.addtest', function() {
        var createModal = $('#addTestReportModal');
        var $this = $(this);
        $this.button('loading');
        $.ajax({
            url: '<?php echo base_url(); ?>admin/radio/createRadiologyTest',
            type: "POST",
            dataType: 'json',
            beforeSend: function() {
                $this.button('loading');
                createModal.addClass('modal_loading');
            },
            success: function(res) {


                $('#modaltitle').html("<?php echo $this->lang->line('add_test_details'); ?>");

                $('#addTestReportModal .modal-body').html(res.page);
                $('.select2').select2();
                $('#addTestReportModal').modal('show');
                createModal.removeClass('modal_loading');
            },
            error: function(xhr) { // if error occured
                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                $this.button('reset');
                createModal.removeClass('modal_loading');
            },
            complete: function() {
                $this.button('reset');
                createModal.removeClass('modal_loading');
            }
        });
    });
</script>
<script type="text/javascript">
    $(document).ready(function(e) {

        $('#addTestReportModal').modal({
            backdrop: 'static',
            keyboard: false,
            show: false
        })
        $(".printsavebtn").on('click', (function(e) {
            var form = $(this).parents('form').attr('id');
            var str = $("#" + form).serializeArray();
            var postData = new FormData();
            $.each(str, function(i, val) {
                postData.append(val.name, val.value);
            });

            var input = document.querySelector('input[type=file]'),
                file = input.files[0];
            postData.append("radiology_report", file);
            $("#formbatchbtn").button('loading');
            e.preventDefault();
            $.ajax({
                url: '<?php echo base_url(); ?>admin/radio/testReportBatch',
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
                        var radioid = $("#radio_id").val();
                        successMsg(data.message);
                        printData(data.id, radioid);
                    }
                    $("#formbatchbtn").button('reset');
                },
                error: function() {}
            });


        }));
    });

    function printData(id, radioid) {
        //alert(id);
        var base_url = '<?php echo base_url() ?>';
        $.ajax({
            url: base_url + 'admin/radio/getBillDetails/' + id + '/' + radioid,
            type: 'POST',
            data: {
                id: id,
                print: 'yes'
            },
            success: function(result) {
                popup(result);
            }
        });
    }




    function get_PatientDetails(id) {
        var base_url = "<?php echo base_url(); ?>backend/images/loading.gif";
        $("#ajax_load").html("<center><img src='" + base_url + "'/>");
        $.ajax({
            url: '<?php echo base_url(); ?>admin/patient/patientDetails',
            type: "POST",
            data: {
                id: id
            },
            dataType: 'json',
            success: function(res) {
                if (res) {
                    $("#ajax_load").html("");
                    $("#patientDetails").show();
                    $('#patient_unique_id').html(res.patient_unique_id);
                    $('#radio_patientid').val(res.id);

                    $('#listname').html(res.patient_name);
                    $('#guardian').html(res.guardian_name);
                    $('#listnumber').html(res.mobileno);
                    $('#email').html(res.email);
                    if (res.age == "") {
                        $("#age").html("");
                    } else {
                        if (res.age) {
                            var age = res.age + " " + "Years";
                        } else {
                            var age = '';
                        }
                        if (res.month) {
                            var month = res.month + " " + "Month";
                        } else {
                            var month = '';
                        }
                        if (res.dob) {
                            var dob = "(" + res.dob + ")";
                        } else {
                            var dob = '';
                        }

                        $("#age").html(age + "," + month + " " + dob);
                    }

                    $('#doctname').val(res.name + " " + res.surname);
                    $("#bp").html(res.bp);
                    $("#symptoms").html(res.symptoms);
                    $("#address").html(res.address);
                    $("#note").html(res.note);
                    $("#height").html(res.height);
                    $("#weight").html(res.weight);
                    $("#genders").html(res.gender);
                    $("#marital_status").html(res.marital_status);
                    $("#blood_group").html(res.blood_group);
                    $("#allergies").html(res.known_allergies);
                    $("#image").attr("src", '<?php echo base_url() ?>' + res.image);
                } else {
                    $("#ajax_load").html("");
                    $("#patientDetails").hide();
                }
            }
        });
    }

    $(document).ready(function() {
        $("#formadd").on('submit', function(e) {
            $("#formaddbtn").button('loading');
            e.preventDefault();

            var formData = new FormData(this);
            var jsonData = {};
            formData.forEach(function(value, key) {
                jsonData[key] = value;
            });

            let errorMessages = [];
            let requiredFields = {
                test_name: "Test Name",
                short_name: "Short Name",
                radiology_category_id: "Category Name",
                charge_category_id: "Charge Category",
                tax: "Tax",
                standard_charge: "Standard Charge",
                amount: "Amount"
            };

            for (let field in requiredFields) {
                if (!jsonData[field]) {
                    errorMessages.push(`${requiredFields[field]} is required`);
                }
            }

            let totalRows = parseInt(jsonData["total_rows[]"]) || 0;
            for (let i = 1; i <= totalRows; i++) {
                if (!jsonData[`parameter_name_${i}`]) {
                    errorMessages.push(`Test Parameter Name for row ${i} is required`);
                }
                // if (!jsonData[`reference_range_${i}`]) {
                //     errorMessages.push(`Reference Range for row ${i} is required`);
                // }
            }

            let unitPattern = /^[a-zA-Z0-9\s]+$/;
            let descriptionPattern = /^[a-zA-Z0-9\s,.'-]+$/;

            if (jsonData["unit"] && !unitPattern.test(jsonData["unit"])) {
                errorMessages.push("Unit contains invalid characters.");
            }

            if (jsonData["description"] && !descriptionPattern.test(jsonData["description"])) {
                errorMessages.push("Description contains invalid characters.");
            }

            if (errorMessages.length > 0) {
                errorMsg(errorMessages.join('<br>'));
                $("#formaddbtn").button('reset');
                return false;
            }

            let mainData = {
                test_name: jsonData.test_name || "",
                short_name: jsonData.short_name || "",
                test_type: jsonData.test_type || "",
                radiology_category_id: parseInt(jsonData.radiology_category_id) || 0,
                sub_category: jsonData.sub_category || "",
                report_days: jsonData.report_days || "",
                charge_id: parseInt(jsonData.code),
                hospital_id: <?= $data['hospital_id'] ?>
            };

            let parameters = [];
            for (let i = 1; i <= totalRows; i++) {
                let parameterId = parseInt(jsonData[`parameter_name_${i}`]) || 0;
                if (parameterId) {
                    parameters.push({
                        radiology_id: '',
                        radiology_parameter_id: parameterId,
                        hospital_id: <?= $data['hospital_id'] ?>
                    });
                }
            }

            let editid = $('input[name="id"]').val();
            let api1, method, api2;
            if (editid == '0') {
                api1 = '<?= $api_base_url ?>radiology-test';
                method = 'POST';
                api2 = '<?= $api_base_url ?>radiology-test/radiologyParameterDetails';
            } else {
                api1 = '<?= $api_base_url ?>radiology-test/' + editid;
                method = 'PATCH';
                api2 = '<?= $api_base_url ?>radiology-test/editRadioParameter/details';
            }

            let previousIds = $('[name^="inserted_id_"]').map(function() {
                return $(this).val();
            }).get();

            parameters.forEach((item, index) => {
                item.id = previousIds[index] || null;
            });

            $.ajax({
                url: api1,
                type: method,
                data: JSON.stringify(mainData),
                dataType: 'json',
                contentType: 'application/json',
                cache: false,
                success: function(data) {
                    let id = data?.[0]?.["data "]?.Radiology_Values?.[0]?.id ||
                        data?.[0]?.["data "]?.updated_values?.[0]?.id ||
                        null;
                    if (id) {
                        parameters.forEach(param => {
                            param.radiology_id = id;
                        });

                        $.ajax({
                            url: api2,
                            type: method,
                            data: JSON.stringify(parameters),
                            dataType: 'json',
                            contentType: 'application/json',
                            cache: false,
                            success: function(data) {
                                $("#formaddbtn").button('reset');
                                successMsg(data.message);
                                window.location.reload(true);
                            },
                            error: function() {
                                alert("An error occurred while processing your request.");
                                $("#formaddbtn").button('reset');
                            }
                        });
                    } else {
                        $("#formaddbtn").button('reset');
                        alert("No ID returned from the first request.");
                    }
                },
                error: function() {
                    alert("An error occurred while processing your request.");
                    $("#formaddbtn").button('reset');
                }
            });
        });
    });








    // $(document).ready(function (e) {
    //     $("#formedit").on('submit', (function (e) {
    //         $("#formeditbtn").button('loading');
    //         e.preventDefault();
    //         $.ajax({
    //             url: '<?php echo base_url(); ?>admin/radio/update',
    //             type: "POST",
    //             data: new FormData(this),
    //             dataType: 'json',
    //             contentType: false,
    //             cache: false,
    //             processData: false,
    //             success: function (data) {
    //                 if (data.status == "fail") {
    //                     var message = "";
    //                     $.each(data.error, function (index, value) {
    //                         message += value;
    //                     });
    //                     errorMsg(message);
    //                 } else {
    //                     successMsg(data.message);
    //                     window.location.reload(true);
    //                 }
    //                 $("#formeditbtn").button('reset');
    //             },
    //             error: function () {
    //             }
    //         });
    //     }));
    // });

    function getRecord(id) {
        $.ajax({
            url: '<?php echo base_url(); ?>admin/radio/getDetails',
            type: "POST",
            data: {
                radiology_id: id
            },
            dataType: 'json',
            success: function(data) {
                $("#id").val(data.id);
                $('#customfield').html(data.custom_fields_value);
                $("#test_name").val(data.test_name);
                $("#short_name").val(data.short_name);
                $("#test_type").val(data.test_type);
                $("#sub_category").val(data.sub_category);
                $("#report_days").val(data.report_days);
                $("#edit_charge_category").val(data.charge_category);
                $("#edit_standard_charge").val(data.standard_charge);
                editchargecode(data.charge_category, data.charge_id);
                $("#updateid").val(id);
                //console.log(data);
                $('select[id="radiology_category_id"] option[value="' + data.radiology_category_id + '"]').attr("selected", "selected");
                $('select[id="charge_category_id"] option[value="' + data.charge_category_id + '"]').attr("selected", "selected");
                $("#viewModal").modal('hide');
                $("#radiology_category_id").select2().select2('val', data.radiology_category_id);
                holdModal('myModaledit');
            },
        })
        $.ajax({
            url: '<?php echo base_url(); ?>admin/radio/editparameter/' + id,
            success: function(res) {

                $("#edit_parameter_details").html(res);
                holdModal('myModaledit');
            },
            error: function() {
                alert("Fail")
            }
        });
    }

    $(function() {
        //Initialize Select2 Elements
        $('.select2').select2();
    });

    function delete_record(id) {
        if (confirm('<?php echo $this->lang->line('delete_confirm'); ?>')) {
            $.ajax({
                url: '<?= $api_base_url ?>radiology-test/' + id + '?hospital_id=<?= $data['hospital_id'] ?>',
                type: "DELETE",
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(data) {
                    successMsg('<?php echo $this->lang->line('delete_message'); ?>');
                    table.ajax.reload();
                    $("#viewModal").modal('hide');
                }
            })
        }
    }

    $(document).on('select2:select', '.charge_category', function() {

        var charge_category = $(this).val();

        $('.charge').html("<option value=''><?php echo $this->lang->line('loading'); ?></option>");
        getchargecode(charge_category, "");
    });


    function getchargecode(charge_category, charge_id) {
        var div_data = "<option value=''><?php echo $this->lang->line('select'); ?></option>";
        if (charge_category != "") {
            $.ajax({
                url: base_url + 'admin/charges/getchargeDetails',
                type: "POST",
                data: {
                    charge_category: charge_category
                },
                dataType: 'json',
                success: function(res) {
                    //alert(res)
                    $.each(res, function(i, obj) {
                        var sel = "";
                        if (charge_id == obj.id) {
                            sel = "selected";
                        }

                        div_data += "<option value=" + obj.id + " " + sel + ">" + obj.name + "</option>";

                    });
                    $('.charge').html(div_data);
                    $(".charge").select2("val", charge_id);

                }
            });
        }
    }
    $(document).on('select2:select', '.charge', function() {
        var charge = $(this).val();

        $.ajax({
            url: '<?php echo base_url(); ?>admin/patient/getChargeById',
            type: "POST",
            data: {
                charge_id: charge
            },
            dataType: 'json',
            success: function(res) {
                if (res) {
                    $('#standard_charge').val(res.standard_charge);
                    $('#amount').val((parseFloat((res.standard_charge) * res.percentage / 100) + (parseFloat(res.standard_charge))).toFixed(2));
                    $('#tax').val(res.percentage);
                } else {

                }
            }
        });
    });



    function viewDetail(id) {
        $.ajax({
            url: '<?php echo base_url(); ?>admin/radio/radiologyDetails',
            type: "POST",
            data: {
                radiology_id: id
            },
            dataType: 'json',
            success: function(data) {
                $('#viewModal .modal-body').html(data.page);
                $('#viewModal').modal('show');

                $('#edit_delete').html("<?php if ($this->rbac->hasPrivilege('radiology_test', 'can_edit')) { ?><a href='#' class='edittest' data-record-id='" + id + "'  data-loading-text='<i class= \"fa fa-circle-o-notch fa-spin\"></i>' data-toggle='tooltip'   data-original-title='<?php echo $this->lang->line('edit'); ?>'><i class='fa fa-pencil'></i></a><?php }
                                                                                                                                                                                                                                                                                                                                                                if ($this->rbac->hasPrivilege('radiology_test', 'can_delete')) { ?><a onclick='delete_record(" + id + ")'  href='#'  data-toggle='tooltip'  data-original-title='<?php echo $this->lang->line('delete'); ?>'><i class='fa fa-trash'></i></a><?php } ?>");
                holdModal('viewModal');
            },
        });


    }

    $(document).ready(function(e) {
        $("#formbatch").on('submit', (function(e) {
            $("#formbatchbtn").button('loading');
            e.preventDefault();
            $.ajax({
                url: '<?php echo base_url(); ?>admin/radio/testReportBatch',
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
                    $("#formbatchbtn").button('reset');
                },
                error: function() {

                }
            });
        }));
    });


    function showtextbox(value) {
        if (value != 'direct') {
            $("#opd_ipd_no").show();
        } else {
            $("#opd_ipd_no").hide();
        }
    }

    $(document).on('click', '.add-record', function() {
        var table = document.getElementById("tableID");
        var table_len = (table.rows.length);
        radiology_parmeter_rows++;
        var div = "<td width='35%'><input type='hidden' name='total_rows[]' value='" + radiology_parmeter_rows + "'><input type='hidden' name='inserted_id_' value='0'><select class='form-control select2 radiology_parmeter' name='parameter_name_" + radiology_parmeter_rows + "'><option value='<?php echo set_value('parameter_name'); ?>'><?php echo $this->lang->line('select') ?></option><?php foreach ($parametername as $dkey => $dvalue) { ?><option value='<?php echo $dvalue["id"]; ?>'><?php echo $dvalue["parameter_name"] ?></option><?php } ?></select></td><td width='30%'><input type='text' name='reference_range_" + radiology_parmeter_rows + "' readonly id='reference_range" + radiology_parmeter_rows + "' class='form-control reference_range'></td><td width='30%'><input type='text' name='radio_unit_" + radiology_parmeter_rows + "' readonly id='radio_unit" + radiology_parmeter_rows + "' class='form-control radio_unit'></td>";

        var row = table.insertRow(table_len).outerHTML = "<tr id='row" + radiology_parmeter_rows + "'>" + div + "<td><button type='button' class='closebtn delete_row'><i class='fa fa-remove'></i></button></td></tr>";
        $('.select2').select2();
    });

    $(document).on('click', '.delete_row', function(e) {
        var table_row = $(this).closest('table#tableID tr');
        table_row.remove();
    });



    $(document).on('change', '.radiology_parmeter', function() {

        var pathology_parmeter_id = $(this).val();
        getparameterdetails($(this), pathology_parmeter_id);
    });



    function getparameterdetails(pathology_parmeter_obj, parameter_id) {
        var medicine_colomn = pathology_parmeter_obj.closest('tr').find('.reference_range');
        var radio_unit = pathology_parmeter_obj.closest('tr').find('.radio_unit');
        $.ajax({
            type: "POST",
            url: base_url + "admin/radio/getparameterdetails",
            data: {
                'id': parameter_id
            },
            dataType: 'json',
            success: function(res) {
                if (res != null) {
                    medicine_colomn.val(res.reference_range);
                    radio_unit.val(res.unit_name);
                }
            }
        });
    }

    $(document).on('click', '.edittest', function() {
        var createModal = $('#addTestReportModal');

        var $this = $(this);
        var record_id = $this.data('recordId')
        $this.button('loading');
        $.ajax({
            url: '<?php echo base_url(); ?>admin/radio/editRadiologyTest',
            type: "POST",
            data: {
                'id': record_id
            },
            dataType: 'json',
            beforeSend: function() {
                $this.button('loading');
                createModal.addClass('modal_loading');
            },
            success: function(res) {
                radiology_parmeter_rows = res.total_rows;
                $('#modaltitle').html("<?php echo $this->lang->line('edit_test_details'); ?>");
                $('#addTestReportModal .modal-body').html(res.page);
                var post_charge_category_id = $('#addTestReportModal .modal-body').find("input[name='post_charge_category_id']").val();
                var post_charge_id = $('#addTestReportModal .modal-body').find("input[name='post_charge_id']").val();
                $('.select2').select2();
                getchargecode(post_charge_category_id, post_charge_id);

                $('#tableID').find("tbody tr").each(function() {

                    var pathology_parmeter_obj = $(this).find("td select.radiology_parmeter");
                    var post_parameter_value = $(this).find("td input.post_parameter_id").val();
                    getparameterdetails(pathology_parmeter_obj, post_parameter_value)

                });
                $('#addTestReportModal').modal('show');
                createModal.removeClass('modal_loading');
            },
            error: function(xhr) { // if error occured
                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                $this.button('reset');
                createModal.removeClass('modal_loading');
            },
            complete: function() {
                $this.button('reset');
                createModal.removeClass('modal_loading');
            }
        });
    });

    function getchargeDetails(id, htmlid) {

        $('#' + htmlid).val("");
        $.ajax({
            url: '<?php echo base_url(); ?>admin/charges/getDetails',
            type: "POST",
            data: {
                charges_id: id,
                organisation: ''
            },
            dataType: 'json',
            success: function(res) {
                $('#' + htmlid).val(res.standard_charge);
            }
        })
    }

    $(document).ready(function(e) {
        $('#viewModal').modal({
            backdrop: 'static',
            keyboard: false,
            show: false
        });
    });
</script>

<script type="text/javascript">
    function addpatientreport() {
        $('#formbatch').trigger("reset");
        $("#patientDetails").hide();
        $('#select2-addpatient_id-container').html("");
        $(".dropify-clear").trigger("click");
    }

    $(".modalbtnpatient").click(function() {
        $('#formaddpa').trigger("reset");
        $(".dropify-clear").trigger("click");
    });

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
        frameDoc.document.write('<body >');
        frameDoc.document.write(data);
        frameDoc.document.write('</body>');
        frameDoc.document.write('</html>');
        frameDoc.document.close();
        setTimeout(function() {
            window.frames["frame1"].focus();
            window.frames["frame1"].print();
            frame1.remove();
            window.location.reload(true);
        }, 500);


        return true;
    }
</script>

<!-- //========datatable start===== -->
<script type="text/javascript">
    (function($) {
        'use strict';
        $(document).ready(function() {
            initDatatable('ajaxlist', 'admin/radio/getradiologytestDatatable', [], [], 100,
                [{
                    "sWidth": "100px",
                    "bSortable": false,
                    "aTargets": [-1, -2, -3],
                    'sClass': 'dt-body-right'
                }, ]);
        });
    }(jQuery))
</script>
<!-- //========datatable end===== -->
<?php $this->load->view('admin/patient/patientaddmodal') ?>