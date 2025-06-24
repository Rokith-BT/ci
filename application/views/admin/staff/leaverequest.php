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
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('my_leaves'); ?></h3>
                        <small class="pull-right">
                            <?php if ($this->rbac->hasPrivilege('apply_leave', 'can_add')) { ?>
                                <a href="#addleave" onclick="addLeave()" class="btn btn-primary btn-sm">
                                    <i class="fa fa-plus"></i> <?php echo $this->lang->line('apply_leave'); ?></a>
                            <?php }
                            if ($this->rbac->hasPrivilege('approve_leave_request', 'can_view')) { ?>
                                <a href="<?PHP echo base_url(); ?>admin/leaverequest/approveleaverequest" class="btn btn-primary btn-sm">
                                    <i class="fa fa-reorder"></i> <?php echo $this->lang->line('approve_leave_request'); ?></a>
                            <?php } ?>
                        </small>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="tab-pane active table-responsive no-padding">
                                    <div class="download_label"><?php echo $this->lang->line('staff_leaves'); ?></div>
                                    <table class="table table-striped table-bordered table-hover" id="ajaxlist">
                                        <thead>
                                            <tr>
                                                <th>S.No</th>
                                                <th><?php echo $this->lang->line('staff'); ?></th>
                                                <th><?php echo $this->lang->line('leave_type'); ?></th>
                                                <th><?php echo $this->lang->line('leave_date'); ?></th>
                                                <th><?php echo $this->lang->line('days'); ?></th>
                                                <th><?php echo $this->lang->line('apply_date'); ?></th>
                                                <th><?php echo $this->lang->line('status'); ?></th>
                                                <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
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
                <div class="row">
                    <form role="form" id="leavedetails_form" action="">
                        <div class="col-md-12 table-responsive">
                            <table class="table mb0 table-striped table-bordered ">
                                <tr>
                                    <th width="15%"><?php echo $this->lang->line('name'); ?></th>
                                    <td width="35%"><span id='name'></span> (<span id="employee_id"></span>)</td>

                                </tr>
                                <tr>
                                    <th><?php echo $this->lang->line('submitted_by'); ?></th>
                                    <td><span id="appliedby"></span></td>
                                    <th><?php echo $this->lang->line('leave_type'); ?></th>
                                    <td><span id="leave_type"></span>
                                        <input id="leave_request_id" name="leave_request_id" placeholder="" type="hidden" class="form-control" />
                                        <span class="text-danger"><?php echo form_error('leave_request_id'); ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo $this->lang->line('leave'); ?></th>
                                    <td><span id='leave_from'></span> - <label> </label><span id='leave_to'> </span> (<span id='days'></span>)
                                        <span class="text-danger"><?php echo form_error('leave_from'); ?></span>
                                    </td>
                                    <th><?php echo $this->lang->line('apply'); ?> <?php echo $this->lang->line('date'); ?></th>
                                    <td><span id="applied_date"></span></td>
                                </tr>
                                <tr>
                                    <th><?php echo $this->lang->line('reason'); ?></th>
                                    <td><span id="remark"> </span></td>
                                    <th><?php echo $this->lang->line('download'); ?></th>
                                    <td><span id="download_file"></span></td>
                                </tr>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="addleave" class="modal fade " role="dialog">
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
                                <label><?php echo $this->lang->line('apply_date'); ?></label>
                                <input type="text" id="applieddate" name="applieddate" value="<?php echo date($this->customlib->getHospitalDateFormat()) ?>" class="form-control date">
                            </div>
                            <div class="form-group  col-xs-12 col-sm-12 col-md-12 col-lg-6 ">
                                <label>
                                    <?php echo $this->lang->line('leave_type'); ?></label><small class="req"> *</small>
                                <div id="leavetypeddl">
                                    <select name="leave_type" id="leave_type" class="form-control">

                                    </select>
                                </div>
                                <span class="text-danger"><?php echo form_error('leave_type'); ?></span>
                            </div>
                            <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <label><?php echo $this->lang->line('leave_date'); ?></label><small class="req"> *</small>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" readonly name="leavedates" class="form-control pull-right daterange" id="reservation">
                                </div>
                                <!-- /.input group -->
                            </div>
                            <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <label><?php echo $this->lang->line('reason'); ?></label><br />
                                <textarea name="reason" id="reason" style="resize: none;" rows="4" class="form-control"></textarea>
                                <input type="hidden" name="leaverequestid" id="leaverequestid">
                            </div>
                            <div class="form-group  col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <label><?php echo $this->lang->line('attach_document'); ?></label>
                                <input type="file" id="file" name="userfile" class="filestyle form-control">
                                <input type="hidden" id="filename" name="filename">
                            </div>
                            <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <button type="submit" id="addleave_formbtn" class="btn btn-primary submit_addLeave pull-right" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>"><i class="fa fa-check-circle"></i> <?php echo $this->lang->line('save'); ?></button>
                <input type="reset" name="resetbutton" id="resetbutton" style="display:none">
                <button type="button" style="display: none;" id="clearform" onclick="clearForm(this.form)" class="btn btn-primary submit_addLeave pull-right" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>"> <?php echo $this->lang->line('clear'); ?></button>
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
        getLeaveTypeDDL('<?php echo $staff_id ?>', '');
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

    function addLeave() {
        $('input[type=text]').val('');
        $('textarea[name="reason"]').text('');
        $("#resetbutton").click();
        $("#clearform").click();
        var leavedate_format = '<?php echo $result = strtr($this->customlib->getHospitalDateFormat(), ['d' => 'dd', 'm' => 'MM', 'Y' => 'yyyy',]) ?>';
        var date = '<?php echo date("Y-m-d") ?>';
        $('input[type=text][name=applieddate]').val(new Date(date).toString(leavedate_format));

        $(".dropify-clear").trigger("click");

        $('#addleave').modal({
            show: true,
            backdrop: 'static',
            keyboard: false
        });
    }

    function deleterecord(id) {
        if (confirm('<?php echo $this->lang->line('delete_confirm') ?>')) {
            let api = '<?= $api_base_url ?>human-resource-apply-leave/' + id + '?hospital_id=<?= $data['hospital_id'] ?>';
            let data = {
                id: id
            };
            sendAjaxRequest(
                api,
                'DELETE',
                data,
                function(response) {
                    handleResponse(response);
                },
                function(jqXHR) {
                    alert('Failed to revert payslip: ' + jqXHR.statusText);
                }
            );
        }
    }


    function getRecord(id) {
        $("#download_file").html('');
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
                    let date = new Date(dateString);
                    let day = date.getDate();
                    let month = date.getMonth() + 1;
                    let year = date.getFullYear();
                    return `${day}/${month}/${year}`;
                }

                $('input[name="leave_request_id"]').val(result.id);
                $('#employee_id').html(result.employee_id);
                $('#name').html(result.name + ' ' + result.surname);
                $('#leave_from').html(formatDate(result.leave_from));
                $('#leave_to').html(formatDate(result.leave_to));
                $('#leave_type').html(result.type);
                $('#days').html(result.leave_days + ' Days');
                $('#remark').html(result.employee_remark);
                $('#applied_date').html(formatDate(result.date));
                $('#appliedby').html(result.applied_by);
                $("#detailremark").text(result.admin_remark);

                getDocumentFile(result.document_file);
            },
            error: function() {
                errorMsg("Failed to fetch leave record.");
            }
        });

        function getDocumentFile(document_file) {
            if (!document_file) return;

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
                    errorMsg(error.message);
                });
        }

        $('#leavedetails').modal({
            show: true,
            backdrop: 'static',
            keyboard: false
        });
    };

    function checkStatus(status) {
        if (status == 'approve') {
            $("#reason").hide();
        } else if (status == 'pending') {
            $("#reason").hide();
        } else if (status == 'disapprove') {
            $("#reason").show();
        }
    }

    $(document).ready(function() {
        $("#addleave_form").on('submit', function(e) {
            e.preventDefault();
            try {
                var formData = new FormData(this);
                if (!formData.get('applieddate') || !formData.get('leave_type') || !formData.get('leavedates')) {
                    throw new Error("Please fill in all required fields.");
                }
                var leavedates = formData.get('leavedates').split(' - ');
                var leave_from = formatDate(leavedates[0]);
                var leave_to = formatDate(leavedates[1]);
                var leaveDays = calculateDaysBetween(leave_from, leave_to);
                getLeaveCountFromSelectedOption(<?= $data['id'] ?>, formData.get('leave_type')).then(leaveCount => {
                    if (leaveDays > leaveCount) {
                        throw new Error(`You have a leave limit of ${leaveCount} days.`);
                    }
                    var applieddate = formatDate(formData.get('applieddate'));
                    var dataObject = {
                        staff_id: <?= $data['id'] ?>,
                        leave_type_id: formData.get('leave_type'),
                        leave_from: leave_from,
                        leave_to: leave_to,
                        employee_remark: formData.get('reason') || '',
                        applied_by: <?= $data['id'] ?>,
                        document_file: '',
                        date: applieddate,
                        hospital_id: <?= $data['hospital_id'] ?>
                    };

                    var file = $('#file')[0].files[0];
                    if (file) {
                        var fileUploadData = new FormData();
                        fileUploadData.append('file', file);
                        $.ajax({
                            url: 'https://phr-api.plenome.com/file_upload',
                            type: 'POST',
                            data: fileUploadData,
                            contentType: false,
                            processData: false,
                            success: function(response) {
                                if (response.data) dataObject.document_file = response.data;
                                submitLeaveRequest(dataObject);
                            },
                            error: function() {
                                errorMsg("File upload failed. Please try again.");
                            }
                        });
                    } else {
                        submitLeaveRequest(dataObject);
                    }
                });
            } catch (error) {
                errorMsg(error.message);
            }

            function formatDate(dateString) {
                var parts = dateString.split('/');
                return parts[2] + '-' + parts[0].padStart(2, '0') + '-' + parts[1].padStart(2, '0');
            }

            function calculateDaysBetween(from, to) {
                var fromDate = new Date(from);
                var toDate = new Date(to);
                var timeDiff = toDate - fromDate;
                return Math.ceil(timeDiff / (1000 * 60 * 60 * 24)) + 1;
            }

            function getLeaveCountFromSelectedOption(id, leavetypeid) {
                return new Promise((resolve, reject) => {
                    $.ajax({
                        type: "GET",
                        url: base_url + 'admin/leaverequest/remainingleave/' + id + '/' + leavetypeid,
                        dataType: "json",
                        success: function(response) {
                            resolve(response.leaveCount || 0);
                        },
                        error: function() {
                            errorMsg("Failed to fetch leave count.");
                            reject(0);
                        }
                    });
                });
            }

            function submitLeaveRequest(data) {
                sendAjaxRequest(
                    `<?= $api_base_url ?>human-resource-apply-leave`,
                    'POST',
                    data,
                    function(response) {
                        handleResponse(response);
                    }
                );
            }
        });
    });

    function getEmployeeName(role) {
        var ne = "";
        var base_url = '<?php echo base_url() ?>';
        $("#empname").html("<option value=''><?php echo $this->lang->line('select'); ?></option>");
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
                    div_data += "<option value='" + obj.id + "' >" + obj.name + " " + obj.surname + " " + "(" + obj.employee_id + ")</option>";
                });
                $('#empname').append(div_data);
            }
        });
    }

    function setEmployeeName(role, id = '') {
        var ne = "";
        var base_url = '<?php echo base_url() ?>';
        $("#empname").html("<option value=''><?php echo $this->lang->line('select'); ?></option>");
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

                    div_data += "<option value='" + obj.id + "' " + ne + " >" + obj.name + " " + obj.surname + " " + "(" + obj.employee_id + ")</option>";
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
                var leavedate_format = '<?php echo $result = strtr($this->customlib->getHospitalDateFormat(), ['d' => 'dd', 'm' => 'MM', 'Y' => 'yyyy',]) ?>';

                setEmployeeName(result.user_type, result.employee_id);
                getLeaveTypeDDL(result.staff_id, result.lid);
                $('select[name="role"] option[value="' + result.user_type + '"]').attr("selected", "selected");
                $('input[name="applieddate"]').val(new Date(result.date).toString(leavedate_format));
                $('input[name="leavefrom"]').val(new Date(result.leave_from).toString(leavedate_format));
                $('input[name="filename"]').val(result.document_file);
                $('input[name="leavedates"]').val(new Date(result.leave_from).toString(leavedate_format) + '-' + new Date(result.leave_to).toString(leavedate_format));
                $('#reservation').daterangepicker({
                    startDate: new Date(result.leave_from).toString(leavedate_format),
                    endDate: new Date(result.leave_to).toString(leavedate_format)
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
<!-- //========datatable start===== -->
<script>
    $(document).ready(function() {
        let baseurl = '<?= base_url() ?>';
        var dataTable = initSummaryDataTable(`${baseurl}/admin/staff/getleaverequestlist`, '', '#ajaxlist', '');
    });

    function renderSummaryTable(dataArray) {
        let count = 1;
        return (dataArray || []).map(item => {
            let action = `<div><a href='#leavedetails' onclick='getRecord(${item.id})' class='btn btn-default btn-xs' data-toggle='tooltip'  role='button' title='View'><i class='fa fa-reorder'></i></a>`;

            let label_color = '';            
            let status = item.Status == 'approve' ? 'approved' : item.Status;
            if (status === 'approved')
                label_color = "class='label label-success'";
            else if (status === 'disapprove')
                label_color = "class='label label-danger'";
            else if (status === 'pending')
                label_color = "class='label label-warning'";

            if (status === 'pending' && ('<?php echo $this->rbac->hasPrivilege('apply_leave', 'can_delete') ?>')) {
                action += `<a href="#leavedetails" onclick="deleterecord(${item.id})" class="btn btn-default btn-xs" data-toggle="tooltip" title="Delete">
                                <i class="fa fa-trash"></i>
                           </a>`;
            }

            action += `</div>`;
            const capitalized = status.replace(/^./, char => char.toUpperCase());
            return [
                count++,
                item.Staff || '-',
                item.LeaveType || "-",
                item.LeaveDate || "-",
                item.Days || "-",
                item.ApplyDate ? formatDatebydb(item.ApplyDate) : "-",
                `<small ${label_color} > ${capitalized} </small>`,
                action
            ];
        });
    }
</script>
<!-- //========datatable end===== -->