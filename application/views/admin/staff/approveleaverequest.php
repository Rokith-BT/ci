<?php
$data = $this->session->userdata('hospitaladmin');
$api_base_url = $this->config->item('api_base_url');
?>
<div class="content-wrapper">

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">

                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('approve_leave_request'); ?></h3>
                        <?php
                        if ($this->rbac->hasPrivilege('approve_leave_request', 'can_add')) {
                        ?>
                        <div class="box-tools pull-right"><a href="#addleave" onclick="addLeave()" role="button"
                                class="btn btn-primary btn-sm checkbox-toggle edit_setting" /> <i
                                class="fa fa-plus"></i> <?php echo $this->lang->line('add_leave_request'); ?></a></div>
                        <?php } ?>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="tab-pane active table-responsive no-padding">
                                    <div class="download_label">
                                        <?php echo $this->lang->line('approve_leave_request'); ?></div>
                                     <table class="table table-striped table-bordered table-hover" id="ajaxlist">
                                        <thead>
                                            <th>S.No</th>
                                            <th><?php echo $this->lang->line('staff'); ?></th>
                                            <th><?php echo $this->lang->line('leave_type'); ?></th>
                                            <th><?php echo $this->lang->line('leave_date'); ?></th>
                                            <th><?php echo $this->lang->line('days'); ?></th>
                                            <th><?php echo $this->lang->line('apply_date'); ?></th>
                                            <th><?php echo $this->lang->line('status'); ?></th>
                                            <th class="text-right noExport"><?php echo $this->lang->line('action'); ?>
                                            </th>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div id="leavedetails" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog2 modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('details'); ?></h4>
            </div>
            <div class="modal-body">

                <div class="row view_leave_detail">
                    <form role="form" id="leavedetails_form1" action="">
                        <input type="hidden" name="staff_id" id="staff_id">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table mb0 table-striped table-bordered">
                                    <tr>
                                        <th width="15%"><?php echo $this->lang->line('name'); ?></th>
                                        <td width="35%"><span id='name'></span></td>
                                    </tr>
                                    <tr>
                                        <th><?php echo $this->lang->line('submitted_by'); ?></th>
                                        <td><span id="appliedby"></span></td>
                                        <th><?php echo $this->lang->line('leave_type'); ?></th>
                                        <td><span id="leave_type"></span>
                                            <input id="leave_request_id" name="leave_request_id" placeholder=""
                                                type="hidden" class="form-control" />
                                            <span
                                                class="text-danger"><?php echo form_error('leave_request_id'); ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th><?php echo $this->lang->line('leave'); ?></th>
                                        <td><span id='leave_from'></span> - <label> </label><span id='leave_to'> </span>
                                            (<span id='days'></span>)
                                            <span class="text-danger"><?php echo form_error('leave_from'); ?></span>
                                        </td>
                                        <th><?php echo $this->lang->line('apply'); ?>
                                            <?php echo $this->lang->line('date'); ?></th>
                                        <td><span id="applied_date"></span></td>
                                    </tr>
                                    <tr>

                                        <th><?php echo $this->lang->line('status'); ?></th>
                                        <td>
                                            <label class="radio-inline">
                                                <input type="radio" value="<?php echo "pending"; ?>" name="status"
                                                    checked><?php echo $status["pending"]; ?>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" value="<?php echo "approve"; ?>"
                                                    name="status"><?php echo $status["approve"]; ?>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" value="<?php echo "disapprove"; ?>"
                                                    name="status"><?php echo $status["disapprove"]; ?>
                                            </label>
                                            <span class="text-danger"><?php echo form_error('status'); ?></span>
                                        </td>
                                        <th><?php echo $this->lang->line('reason'); ?></th>
                                        <td><span id="remark"> </span></td>
                                    </tr>
                                    <tr>
                                        <th><?php echo $this->lang->line('download'); ?></th>
                                        <td><span id="download_file"></span></td>
                                    </tr>
                                    <tr>
                                        <td colspan=" 4">
                                            <div id="reason">
                                                <textarea class="form-control" style="resize: none;" rows="2"
                                                    id="detailremark" name="detailremark" placeholder=""></textarea>
                                                <span class="text-danger"><?php echo form_error('address'); ?></span>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <?php
                                        if ($this->rbac->hasPrivilege('approve_leave_request', 'can_edit')) {
                                        ?>
                                        <td colspan="4">
                                            <button type="button" class="btn btn-primary submit_schsetting pull-right"
                                                data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Processing"><i
                                                    class="fa fa-check-circle"></i>
                                                <?php echo $this->lang->line('save'); ?></button>
                                        </td>
                                        <?php } ?>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>


<div id="addleave" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog2 modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('add_details'); ?></h4>
            </div>
            <div class="scroll-area">
                <div class="modal-body">

                    <div class="row">
                        <form role="form" id="addleave_form" method="post" enctype="multipart/form-data" action="">

                            <div class="form-group  col-xs-12 col-sm-12 col-md-12 col-lg-6">
                                <label>
                                    <?php echo $this->lang->line('staff_role'); ?></label><small class="req"> *</small>
                                <select name="role" class="form-control" onchange="getEmployeeName(this.value)">
                                    <option value="" selected><?php echo $this->lang->line('select') ?></option>
                                    <?php foreach ($staffrole as $rolekey => $rolevalue) {
                                    ?>
                                    <option value="<?php echo $rolevalue["id"] ?>"><?php echo $rolevalue["type"] ?>
                                    </option>
                                    <?php } ?>
                                </select>
                                <span class="text-danger"><?php echo form_error('role'); ?></span>
                            </div>
                            <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-6">
                                <label><?php echo $this->lang->line('staff_name'); ?></label><small class="req">
                                    *</small>
                                <select name="empname" id="empname" value="" onchange="   getLeaveTypeDDL(this.value)"
                                    class="form-control">
                                    <option value="" selected><?php echo $this->lang->line('select') ?></option>
                                </select>
                                <span class="text-danger"><?php echo form_error('empname'); ?></span>
                            </div>
                            <div class="form-group  col-xs-12 col-sm-12 col-md-12 col-lg-6">
                                <label><?php echo $this->lang->line('apply_date'); ?></label><small class="req">
                                    *</small>
                                <input type="text" id="applieddate" name="applieddate"
                                    value="<?php echo date($this->customlib->getHospitalDateFormat()) ?>"
                                    class="form-control date">

                            </div>

                            <div class="form-group  col-xs-12 col-sm-12 col-md-12 col-lg-6 ">
                                <label>
                                    <?php echo $this->lang->line('leave_type'); ?></label> <small class="req"> *</small>
                                <div id="leavetypeddl">
                                    <select name="leave_type" id="leave_type" class="form-control">
                                        <option value=""><?php echo $this->lang->line('select') ?></option>
                                        <?php foreach ($leavetype as $leave_key => $leave_value) {
                                        ?>
                                        <option value="<?php echo $leave_value["id"] ?>">
                                            <?php echo $leave_value["type"] ?></option>
                                        <?php }
                                        ?>
                                    </select>
                                </div>
                                <span class="text-danger"><?php echo form_error('leave_type'); ?></span>

                            </div>
                            <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <label><?php echo $this->lang->line('leave_date'); ?></label><span class="req"> *</span>

                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" readonly name="leavedates"
                                        class="form-control pull-right daterange" id="reservation">
                                </div>

                                <!-- /.input group -->
                            </div>


                            <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-6">
                                <label><?php echo $this->lang->line('reason'); ?></label><br />
                                <textarea name="reason" id="reason" style="resize: none;" rows="4"
                                    class="form-control"></textarea>
                                <input type="hidden" name="leaverequestid" id="leaverequestid">
                            </div>

                            <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-6" id="reason">
                                <label><?php echo $this->lang->line('staff_note'); ?></label>

                                <textarea class="form-control" style="resize: none;" rows="4" id="remark" name="remark"
                                    placeholder=""></textarea>
                                <span class="text-danger"><?php echo form_error('remark'); ?></span>

                            </div>
                            <div class="form-group  col-xs-12 col-sm-12 col-md-12 col-lg-6">
                                <label><?php echo $this->lang->line('attach_document'); ?></label>
                                <input type="file" id="file" name="userfile" class="filestyle form-control">
                                <input type="hidden" id="filename" name="filename">
                            </div>

                            <div class="form-group  col-xs-12 col-sm-12 col-md-12 col-lg-6">
                                <label><?php echo $this->lang->line('status'); ?> </label>
                                <br />
                                <label class="radio-inline">

                                    <input type="radio" value="<?php echo "pending" ?>" name="addstatus"
                                        checked><?php echo $status["pending"] ?>
                                </label>
                                <label class="radio-inline">

                                    <input type="radio" value="<?php echo "approve" ?>"
                                        name="addstatus"><?php echo $status["approve"] ?></label>
                                <label class="radio-inline">

                                    <input type="radio" value="<?php echo "disapprove" ?>"
                                        name="addstatus"><?php echo $status["disapprove"] ?></label>


                                <span class="text-danger"><?php echo form_error('addstatus'); ?></span>
                            </div>


                            <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" id="addleave_formbtn" class="btn btn-primary submit_addLeave pull-right"
                    data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>"><i
                        class="fa fa-check-circle"></i> <?php echo $this->lang->line('save'); ?></button>
                <input type="reset" name="resetbutton" id="resetbutton" style="display:none">
                <button type="button" style="display: none;" id="clearform" onclick="clearForm(this.form)"
                    class="btn btn-primary submit_addLeave pull-right"
                    data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>">
                    <?php echo $this->lang->line('clear'); ?></button>

            </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
/*--dropify--*/
$(document).ready(function() {
    // Basic
    $('.filestyle').dropify();
});
/*--end dropify--*/
</script>

<script type="text/javascript">
$(document).ready(function() {
    $('.detail_popover').popover({
        placement: 'right',
        title: '',
        trigger: 'hover',
        container: 'body',
        html: true,
        content: function() {
            return $(this).closest('td').find('.fee_detail_popover').html();
        }
    });
});



$(document).on('hidden.bs.modal', '#addleave', function() {
    $(".filestyle").next(".dropify-clear").trigger("click");
    $('.modal #addleave_form').find('input:text, input:password, input:file, textarea').val('');
    $('.modal #addleave_form').find('select option:selected').removeAttr('selected');
    $('.modal #addleave_form').find('input:checkbox, input:radio').removeAttr('checked');
    $("#applieddate").datepicker("update", new Date());
    $('input:radio[name=addstatus]')[0].checked = true;
    $('.modal #addleave_form #empname').find('option').not(':first').remove();
});

function addLeave() {

    $('#addleave').modal({
        show: true,
        backdrop: 'static',
        keyboard: false
    });
}

function getRecord(id) {
    $('input:radio[name=status]').prop('checked', false);
    var base_url = '<?php echo base_url() ?>';

    $.ajax({
        url: base_url + 'admin/leaverequest/leaveRecord',
        type: 'POST',
        data: {
            id: id
        },
        dataType: "json",
        success: function(result) {
            function formatDate(dateString) {
                if (!dateString) return "-";
                let date = new Date(dateString);
                let day = date.getDate().toString().padStart(2, '0');
                let month = (date.getMonth() + 1).toString().padStart(2, '0');
                let year = date.getFullYear();
                return `${day}/${month}/${year}`;
            }

            $('input[name="leave_request_id"]').val(result.id || "-");
            $('#employee_id').html(result.employee_id || "-");
            $('#staff_id').val(result.employee_id || "-");
            $('#name').html(result.staffname || "-");
            $('#leave_from').html(formatDate(result.leave_from));
            $('#leave_to').html(formatDate(result.leave_to));
            $('#leave_type').html(result.type || "-");
            $('#days').html((result.leave_days ? result.leave_days + ' Days' : "-"));
            $('#remark').html(result.employee_remark || "-");
            $('#applied_date').html(formatDate(result.date));
            $('#appliedby').html(result.applier_employee_id ? result.applied_by : "-");
            $("#detailremark").text(result.admin_remark || "-");

            const statusMap = {
                "Approved": "approve",
                "Pending": "pending",
                "Disapprove": "disapprove"
            };

            let statusValue = statusMap[result.status] || "pending";
            $(`input:radio[name=status][value="${statusValue}"]`).prop('checked', true);

            getDocumentFile(result.document_file);
        },
        error: function() {
            errorMsg("Failed to fetch leave record.");
        }
    });

    $('#leavedetails').modal({
        show: true,
        backdrop: 'static',
        keyboard: false
    });

    function getDocumentFile(document_file) {
        if (!document_file) {
            $("#download_file").html("-");
            return;
        }

        const url = "https://phr-api.plenome.com/file_upload/getDocs";

        fetch(url, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    value: document_file
                })
            })
            .then(response => {
                if (!response.ok) throw new Error("No response from server.");
                return response.text();
            })
            .then(base64File => {
                base64File = base64File.trim();
                const fileType = "application/pdf";
                const fileName = document_file.split("/").pop();

                const downloadLink = `
                <a href="data:${fileType};base64,${base64File}" download="${fileName}" class="btn btn-default btn-xs" data-toggle="tooltip" title="Download">
                    <i class='fa fa-download'></i> Download
                </a>`;

                $("#download_file").html(downloadLink);
            })
            .catch(error => {
                console.error("Error:", error);
                $("#download_file").html("-");
            });
    }
}


$(document).ready(function() {
    $(".submit_schsetting").on('click', function(e) {
        e.preventDefault();
        var $this = $(this);
        var formData = {
            staff_id: $('#staff_id').val(),
            leave_request_id: $('#leave_request_id').val(),
            status: $('input[name="status"]:checked').val(),
            detailremark: $('#detailremark').val(),
            admin_remark: $('#detailremark').val(),
            status_updated_by: <?= $data['id'] ?>,
            hospital_id: <?= $data['hospital_id'] ?>
        };
        sendAjaxRequest(
            '<?= $api_base_url ?>human-resource-apply-leave/addLeaveRequest/' + formData.leave_request_id,
            'PATCH',formData,
            function(response) {
                handleResponse(response);
            },
            function(jqXHR, textStatus, errorThrown) {
                console.error("Error:", textStatus, errorThrown);
                errorMsg("An error occurred. Please try again.");
            }
        );
    });
});





function checkStatus(status) {
    if (status == 'approve') {

        $("#reason").hide();
    } else if (status == 'pending') {

        $("#reason").hide();
    } else if (status == 'disapprove') {

        $("#reason").show();
    }
}

function getEmployeeName(role) {
    var ne = "";
    var base_url = '<?php echo base_url() ?>';
    $("#empname").html('<option value=><?php echo $this->lang->line('select') ?></option>');
    var div_data = "";
    $.ajax({
        type: "POST",
        url: base_url + "admin/staff/getEmployeeByRole",
        data: {
            'role': role
        },
        dataType: "json",
        success: function(data) {
            $.each(data, function(i, obj) {
                div_data += "<option value='" + obj.id + "' >" + obj.name + " " + obj.surname +
                    " " + "(" + obj.employee_id + ")</option>";
            });

            $('#empname').append(div_data);
        }
    });
}

function setEmployeeName(role, id = '') {
    var ne = "";
    var base_url = '<?php echo base_url() ?>';
    $("#empname").html("<option value=><?php echo $this->lang->line('select') ?></option>");
    var div_data = "";
    $.ajax({
        type: "POST",
        url: base_url + "admin/staff/getEmployeeByRole",
        data: {
            'role': role
        },
        dataType: "json",
        success: function(data) {
            $.each(data, function(i, obj) {
                if (obj.employee_id == id) {
                    ne = 'selected';
                } else {
                    ne = "";
                }

                div_data += "<option value='" + obj.id + "' " + ne + " >" + obj.name + " " + obj
                    .surname + " " + "(" + obj.employee_id + ")</option>";
            });

            $('#empname').append(div_data);
        }
    });
}

function getLeaveTypeDDL(id, lid = '') {
    var base_url = '<?php echo base_url() ?>';
    $.ajax({
        url: base_url + 'admin/leaverequest/countLeave/' + id,
        type: 'POST',
        data: {
            lid: lid
        },
        success: function(result) {
            $("#leavetypeddl").html(result);
        }
    });
}

$("#leavedetails").on('hidden.bs.modal', function() {
    $('.view_leave_detail table span').html("");
    $(this).find("input,textarea,select").not("input[type=radio]")
        .val('')
        .end();
    $(this).find("input[type=checkbox], input[type=radio]")
        .prop('checked', false);
});

function editRecord(id) {
    var leave_from = '05/01/2018';
    var leave_to = '05/10/2018';
    $("#resetbutton").click();
    $('textarea[name="reason"]').text('');

    $('textarea[name="remark"]').text('');
    $('input:radio[name=addstatus]').attr('checked', false);

    var base_url = '<?php echo base_url() ?>';
    $.ajax({
        url: base_url + 'admin/leaverequest/leaveRecord',
        type: 'POST',
        data: {
            id: id
        },
        dataType: "json",
        success: function(result) {
            leave_from = result.leavefrom;
            leave_to = result.leaveto;
            var daterange_format =
                '<?php echo $result = strtr($this->customlib->getHospitalDateFormat(), ['d' => 'DD', 'm' => 'MM', 'Y' => 'YYYY']) ?>';
            var leavedate_format =
                '<?php echo $result = strtr($this->customlib->getHospitalDateFormat(), ['d' => 'dd', 'm' => 'MM', 'Y' => 'yyyy',]) ?>';

            setEmployeeName(result.user_type, result.employee_id);
            getLeaveTypeDDL(result.staff_id, result.lid);
            $('select[name="role"] option[value="' + result.user_type + '"]').attr("selected", "selected");

            $('input[name="applieddate"]').val(new Date(result.date).toString(leavedate_format));

            $('input[name="leavefrom"]').val(new Date(result.leave_from).toString(leavedate_format));
            $('input[name="filename"]').val(result.document_file);
            $('input[name="leavedates"]').val(new Date(result.leave_from).toString(leavedate_format) + '-' +
                new Date(result.leave_to).toString(leavedate_format));
            $('#reservation').daterangepicker({
                startDate: new Date(result.leave_from).toString(leavedate_format),
                endDate: new Date(result.leave_to).toString(leavedate_format),
                locale: {
                    format: daterange_format,
                },
            });

            $('input[name="leaverequestid"]').val(id);
            $('textarea[name="reason"]').text(result.employee_remark);
            $('textarea[name="remark"]').text(result.admin_remark);
            if (result.status == 'approve') {
                $('input:radio[name=addstatus]')[1].checked = true;
            } else if (result.status == 'pending') {
                $('input:radio[name=addstatus]')[0].checked = true;
            } else if (result.status == 'disapprove') {
                $('input:radio[name=addstatus]')[2].checked = true;
            }
        }
    });

    $('#addleave').modal({
        show: true,
        backdrop: 'static',
        keyboard: false
    });
};

function clearForm(oForm) {
    var elements = oForm.elements;
    for (i = 0; i < elements.length; i++) {
        field_type = elements[i].type.toLowerCase();
        switch (field_type) {
            case "text":
            case "password":
            case "hidden":
                elements[i].value = "";
                break;

            case "select-one":
            case "select-multi":
                elements[i].selectedIndex = "";
                break;

            default:
                break;
        }
    }
}
</script>
<script>
$(document).ready(function() {
    $("#addleave_form").on('submit', async function(e) {
        e.preventDefault();

        try {
            let formData = new FormData(this);
            let leavedates = formData.get('leavedates')?.split(' - ') || [];
            let errorMessages = [];

            if (!formData.get('empname')) errorMessages.push('Employee name is required.');
            if (!formData.get('leave_type')) errorMessages.push('Leave type is required.');
            if (!leavedates[0]) errorMessages.push('Leave from date is required.');
            if (!leavedates[1]) errorMessages.push('Leave to date is required.');
            if (!formData.get('applieddate')) errorMessages.push('Applied date is required.');

            if (errorMessages.length > 0) {
                throw new Error(errorMessages.join('<br>'));
            }

            let leave_from = formatDate(leavedates[0]);
            let leave_to = formatDate(leavedates[1]);
            let applieddate = formatDate(formData.get('applieddate'));
            let leaveDays = calculateDaysBetween(leave_from, leave_to);

            let leaveCount = await getLeaveCountFromSelectedOption(
                formData.get('empname'), 
                formData.get('leave_type')
            );

            if (leaveDays > leaveCount) {
                throw new Error(`You have a leave limit of ${leaveCount} days.`);
            }

            let finaldata = {
                staff_id: formData.get('empname'),
                leave_type_id: formData.get('leave_type'),
                leave_from: leave_from,
                leave_to: leave_to,
                employee_remark: formData.get('reason') || " ",
                admin_remark: formData.get('remark') || " ",
                status: formData.get('addstatus'),
                applied_by: <?= $data['id'] ?>,
                document_file: "",
                date: applieddate,
                hospital_id: <?= $data['hospital_id'] ?>
            };

            let file = $('#file')[0].files[0];
            if (file) {
                try {
                    let fileResponse = await uploadFile(file);
                    finaldata.document_file = fileResponse?.data || "";
                } catch (fileError) {
                    console.error("File upload failed:", fileError);
                }
            }

            await submitLeaveRequest(finaldata);
        } catch (error) {
            errorMsg(error.message);
        }
    });

    function formatDate(dateString) {
        if (!dateString) return '';
        let parts = dateString.split('/');
        return `${parts[2]}-${parts[0].padStart(2, '0')}-${parts[1].padStart(2, '0')}`;
    }

    function calculateDaysBetween(from, to) {
        let fromDate = new Date(from);
        let toDate = new Date(to);
        return Math.ceil((toDate - fromDate) / (1000 * 60 * 60 * 24)) + 1;
    }

    function getLeaveCountFromSelectedOption(id, leavetypeid) {
        return new Promise((resolve, reject) => {
            sendAjaxRequest(
                base_url + 'admin/leaverequest/remainingleave/' + id + '/' + leavetypeid,
                'GET',
                null,
                (response) => resolve(response.leaveCount || 0),
                () => {
                    errorMsg("Failed to fetch leave count.");
                    reject(new Error("Failed to fetch leave count."));
                }
            );
        });
    }

    function uploadFile(file) {
        return new Promise((resolve, reject) => {
            let fileUploadData = new FormData();
            fileUploadData.append('file', file);

            $.ajax({
                url: 'https://phr-api.plenome.com/file_upload',
                type: 'POST',
                data: fileUploadData,
                contentType: false,
                processData: false,
                success: (response) => resolve(response),
                error: () => reject(new Error("File upload failed."))
            });
        });
    }

    function submitLeaveRequest(dataObject) {
        return new Promise((resolve, reject) => {
            sendAjaxRequest(
                "<?= $api_base_url ?>human-resource-apply-leave/addLeaveRequest",
                "POST",dataObject,
                function (response) {
                    handleResponse(response);
                }
            );
        });
    }
});
</script>

<!-- //========datatable start===== -->

<script>
let isFirstLoad = true;
$(document).ready(function () {
    let baseurl = '<?= base_url() ?>';
    initSummaryDataTable(`${baseurl}/admin/leaverequest/getapproveleaverequest`, '', '#ajaxlist', '');
});
function formatDateRange(dateRange) {
    if (!dateRange) return '';
    const [start, end] = dateRange.split(' - ');
    const formatDate = (dateStr) => {
        const [year, month, day] = dateStr.split('-');
        return `${day}/${month}/${year}`;
    };
    return `${formatDate(start)} - ${formatDate(end)}`;
}
function formatDate(dateStr) {
    if (!dateStr) return '';
    const date = new Date(dateStr);
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();
    return `${day}/${month}/${year}`;
}
function renderSummaryTable(dataArray) {
    let count = 1;
    return (dataArray || []).map(item => {
        let labelClass = '';
        if (item.status === "approve" || item.status === "approved") {
            labelClass = "label label-success";
        } else if (item.status === "pending") {
            labelClass = "label label-warning";
        } else if (item.status === "disapprove") {
            labelClass = "label label-danger";
        }
        let statusLeave = `<small class="${labelClass}">${item.status}</small>`;
        let action = '-';
        if (item.applied_by === <?= $data["id"] ?>) {
            action = '';
            if (item.can_edit) {
                action += `<a href="#addleave" onclick="editRecord(${item.id})" class="btn btn-default btn-xs" data-toggle="tooltip" title="Edit"><i class="fa fa-pencil"></i></a>`;
            }
            if (item.document_file) {
                action += `<a href="${item.download_url}" class="btn btn-default btn-xs" data-toggle="tooltip" title="Download"><i class="fa fa-download"></i></a>`;
            }
        }
        return [
            count++,
            item.name,
            item.type,
            formatDateRange(item.leave_date),
            item.leave_days,
            formatDate(item.date),
            statusLeave,
            action
        ];
    });
}
</script>






<!-- //========datatable end===== -->