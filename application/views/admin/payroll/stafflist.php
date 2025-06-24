<?php
$currency_symbol = $this->customlib->getHospitalCurrencyFormat();
$data = $this->session->userdata('hospitaladmin');
$api_base_url = $this->config->item('api_base_url');
?>
<div class="content-wrapper" style="min-height: 946px;">

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('staff_payroll'); ?></h3>
                    </div>
                    <form id='form1' action="<?php echo site_url('admin/payroll') ?>" method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <div class="row">
                                <?php if ($this->session->flashdata('msg')) { ?> <div> <?php echo $this->session->flashdata('msg') ?> </div> <?php $this->session->unset_userdata('msg');
                                                                                                                                            }   ?>
                                <?php echo $this->customlib->getCSRF(); ?>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">
                                            <?php echo $this->lang->line("staff_role"); ?>
                                        </label>
                                        <select onchange="getEmployeeName(this.value)" id="role" name="role" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($classlist as $key => $class) {

                                                if (isset($_POST["role"])) {
                                                    $role_selected = $_POST["role"];
                                                } else {
                                                    $role_selected = '';
                                                }
                                            ?>
                                                <option value="<?php echo $class["type"] ?>"
                                                    <?php
                                                    if ($class["type"] == $role_selected) {
                                                        echo "selected";
                                                    }
                                                    ?>><?php print_r($class["type"]) ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('role'); ?></span>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('month') ?></label>

                                        <select autofocus="" id="class_id" name="month" class="form-control">
                                            <option value="select"><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            if (isset($month)) {
                                                $month_selected = date("F", strtotime($month));
                                            } else {
                                                $month_selected = date("F", strtotime("-1 month"));
                                            }
                                            foreach ($monthlist as $m_key => $month_value) {
                                            ?>
                                                <option value="<?php echo $m_key ?>" <?php
                                                                                        if ($month_selected == $m_key) {
                                                                                            echo "selected =selected";
                                                                                        }
                                                                                        ?>><?php echo $this->lang->line(strtolower($month_value)); ?></option>
                                            <?php
                                            }
                                            ?>

                                        </select>
                                        <span class="text-danger"><?php echo form_error('month'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('year'); ?></label>
                                        <?php
                                        if (isset($year)) {
                                            $selected_year = $year;
                                        } else {
                                            $selected_year = date('Y');
                                        }
                                        ?>
                                        <select autofocus="" id="class_id" name="year" class="form-control">
                                            <option value="select"><?php echo $this->lang->line('select'); ?></option>
                                            <option <?php
                                                    if (date("Y", strtotime("-1 year")) == $selected_year) {
                                                        echo "selected";
                                                    }
                                                    ?> value="<?php echo date("Y", strtotime("-1 year")) ?>"><?php echo date("Y", strtotime("-1 year")) ?></option>
                                            <option <?php
                                                    if (date("Y") == $selected_year) {
                                                        echo "selected";
                                                    }
                                                    ?> value="<?php echo date("Y") ?>"><?php echo date("Y") ?></option>
                                        </select>

                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" name="search" value="search" class="btn btn-primary btn-sm pull-right checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                        </div>

                    </form>

                    <?php
                    if (isset($resultlist)) {
                    ?>
                        <div class="ptbnull"></div>
                        <div class="box border0 clear">
                            <div class="box-body table-responsive">

                                <div class="download_label"><?php echo $this->lang->line('staff_list'); ?></div>
                                <table class="table table-striped table-bordered table-hover example">
                                    <thead>
                                        <tr>
                                            <th><?php echo $this->lang->line('staff_id'); ?></th>
                                            <th><?php echo $this->lang->line('staff_name'); ?></th>
                                            <th><?php echo $this->lang->line('staff_role'); ?></th>
                                            <th><?php echo $this->lang->line('staff_department'); ?></th>
                                            <th><?php echo $this->lang->line('staff_designation'); ?></th>
                                            <th><?php echo $this->lang->line('staff_phone'); ?></th>
                                            <th><?php echo $this->lang->line('status'); ?></th>
                                            <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $count = 1;
                                        foreach ($resultlist as $staff) {
                                            $status = $staff["status"];
                                            $label = "class='label label-default'";
                                            $wstatus = $payroll_status["not_generate"]; // Default value

                                            if ($status == "paid") {
                                                $label = "class='label label-success'";
                                                $wstatus = $payroll_status[$status];
                                            } elseif ($status == "generated") {
                                                $label = "class='label label-warning'";
                                                $wstatus = $payroll_status[$status];
                                            }
                                        ?>
                                            <tr>
                                                <td><?php echo $staff['employee_id']; ?></td>
                                                <td><?php echo $staff['name'] . " " . $staff['surname']; ?></td>
                                                <td><?php echo $staff['user_type']; ?></td>
                                                <td><?php echo $staff['department']; ?></td>
                                                <td><?php echo $staff['designation']; ?></td>
                                                <td><?php echo $staff['contact_no']; ?></td>
                                                <td><small <?php echo $label; ?>><?php echo $wstatus; ?></small></td>

                                                <td class="pull-right no-print">
                                                    <?php if ($status == "paid"): ?>
                                                        <?php if ($this->rbac->hasPrivilege('staff_payroll', 'can_add')): ?>
                                                            <a class="btn btn-default btn-xs" onclick="revertPaidPayslip(<?= $staff['payslip_id'] ?>)" data-toggle="tooltip" title="Revert">
                                                                <i class="fa fa-undo"></i>
                                                            </a>
                                                        <?php endif; ?>
                                                        <a href="#" onclick="getPayslip('<?= $staff['payslip_id'] ?>')" role="button" class="btn btn-primary btn-xs checkbox-toggle edit_setting" data-toggle="tooltip" title="<?= $this->lang->line('payslip_view'); ?>" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Processing">
                                                            <?= $this->lang->line('view'); ?> <?= $this->lang->line('staff_payslip'); ?>
                                                        </a>
                                                    <?php elseif ($status == "generated"): ?>
                                                        <a href="<?= site_url('admin/payroll/edit/' . $staff['payslip_id']) ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?= $this->lang->line('edit') ?>">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>
                                                        <?php if ($this->rbac->hasPrivilege('staff_payroll', 'can_delete')): ?>
                                                            <a class="btn btn-default btn-xs" onclick="deletepayslip(<?= $staff['payslip_id'] ?>)" data-toggle="tooltip" title="Revert">
                                                                <i class="fa fa-undo"></i>
                                                            </a>
                                                        <?php endif; ?>
                                                        <?php if ($this->rbac->hasPrivilege('staff_payroll', 'can_add')): ?>
                                                            <a href="#" onclick="getRecord('<?= $staff['id'] ?>', '<?= $year ?>')" role="button" class="btn btn-primary btn-xs checkbox-toggle edit_setting" data-toggle="tooltip" title="<?= $this->lang->line('proceed_to_payment'); ?>" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Processing">
                                                                <?= $this->lang->line('proceed_to_pay'); ?>
                                                            </a>
                                                        <?php endif; ?>
                                                    <?php elseif ($staff["payslip_id"] == 0): ?>
                                                        <?php if ($this->rbac->hasPrivilege('staff_payroll', 'can_add')): ?>
                                                            <a class="btn btn-primary btn-xs checkbox-toggle edit_setting" role="button" href="<?php echo base_url() . "admin/payroll/create/" . strtolower($month_selected) . "/" . $year . "/" . $staff["id"]; ?>">
                                                                <?= $this->lang->line('generate'); ?> <?= $this->lang->line('staff_payroll'); ?>
                                                            </a>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php
                                            $count++;
                                        }
                                        ?>
                                    </tbody>

                                </table>
                            </div>
                        </div>
                </div>
            <?php
                    }
            ?>
            </div>
            <form action="<?php echo base_url('admin/payroll/create') ?>" method="post" id="formsubmit">
                <input type="hidden" name="month" id="month">
                <input type="hidden" name="year" id="year">
                <input type="hidden" name="staffid" id="staffid">
            </form>
        </div>

    </section>
</div>

<div id="payslipview" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog2 modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('details'); ?> <span id="print"></span></h4>
            </div>
            <div class="modal-body" id="testdata">


            </div>
        </div>
    </div>
</div>


<div id="proceedtopay" class="modal fade " role="dialog">
    <div class="modal-dialog modal-dialog2 modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('proceed_to_pay'); ?></h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <form role="form" id="schsetting_form" enctype="multipart/form-data">

                        <div class="form-group  col-xs-12 col-sm-12 col-md-12 col-lg-6">
                            <label for="exampleInputEmail1">
                                <?php echo $this->lang->line('staff'); ?> <?php echo $this->lang->line('Name'); ?></label>
                            <input type="text" name="emp_name" readonly class="form-control" id="emp_name">

                        </div>
                        <div class="form-group  col-xs-12 col-sm-12 col-md-12 col-lg-6">
                            <label for="exampleInputEmail1"><?php echo $this->lang->line('payment'); ?> <?php echo $this->lang->line('amount') . ' (' . $currency_symbol . ')'; ?></label>
                            <input type="text" name="amount" readonly class="form-control" id="amount">
                        </div>

                        <div class="form-group  col-xs-12 col-sm-12 col-md-12 col-lg-6">
                            <label for="exampleInputEmail1">
                                <?php echo $this->lang->line("month") ?> <?php echo $this->lang->line('year'); ?></label>
                            <input id="monthid" name="month" readonly placeholder="" type="text" class="form-control" />
                            <input name="paymentmonth" placeholder="" type="hidden" class="form-control" />
                            <input name="paymentyear" placeholder="" type="hidden" class="form-control" />
                            <input name="paymentid" placeholder="" type="hidden" class="form-control" />

                            <input name="staff_id" placeholder="" type="hidden" class="form-control" />
                            <input name="staff_role" placeholder="" type="hidden" class="form-control" />
                            <!-- <input name="staff_name" placeholder="" type="text" class="form-control" /> -->
                        </div>
                        <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-6">
                            <label for="exampleInputEmail1"><?php echo $this->lang->line('payment'); ?> <?php echo $this->lang->line('mode'); ?></label><span class="req"> *</span><br /><span id="remark">
                            </span>
                            <select name="payment_mode" id="payment_mode" class="form-control payment_mode">
                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                <?php
                                foreach ($payment_mode as $pkey => $pvalue) {
                                    if (strtolower($pvalue) == 'paylater') {
                                        continue;
                                    }
                                ?>
                                    <option value="<?php echo $pkey ?>"><?php echo $pvalue ?></option>
                                <?php
                                }
                                ?>
                            </select>
                            <span class="text-danger"><?php echo form_error('payment_mode'); ?></span>
                        </div>

                        <div class="cheque_div" style="display: none;">

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('cheque_no'); ?></label><small class="req"> *</small>
                                    <input type="text" name="cheque_no" id="cheque_no" class="form-control">
                                    <span class="text-danger"><?php echo form_error('cheque_no'); ?></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('cheque_date'); ?></label><small class="req"> *</small>
                                    <input type="text" name="cheque_date" id="cheque_date" class="form-control date">
                                    <span class="text-danger"><?php echo form_error('cheque_date'); ?></span>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('attach_document'); ?></label>
                                    <input type="file" class="filestyle form-control" name="document" id="document">
                                    <span class="text-danger"><?php echo form_error('document'); ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-6">
                            <label for="exampleInputEmail1"><?php echo $this->lang->line('payment'); ?> <?php echo $this->lang->line('date'); ?></label><br /><span id="remark"> </span>
                            <input type="text" name="payment_date" id="payment_date" class="form-control date" value="<?php echo date($this->customlib->getHospitalDateFormat()) ?>">
                        </div>

                        <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-6">
                            <label for="exampleInputEmail1"><?php echo $this->lang->line('note'); ?></label><br /><span id="remark"> </span>
                            <textarea name="remarks" class="form-control"></textarea>
                        </div>

                        <div class="clearfix"></div>
                        <button id="submitSchSettingBtn" type="submit" class="btn btn-primary submit_schsetting pull-right" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Processing">
                            <i class="fa fa-check-circle"></i> <?php echo $this->lang->line('save'); ?>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
function printData(id) {
    let downloadIndicator = document.createElement('div');
    downloadIndicator.id = 'download-indicator';
    downloadIndicator.innerHTML = `
    <div style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); 
                background: rgba(0,0,0,0.8); color: white; padding: 20px; border-radius: 8px; 
                z-index: 9999; text-align: center; font-family: Arial, sans-serif;">
        <div style="margin-bottom: 15px;">Generating PDF...</div>
        <div style="width: 200px; height: 4px; background: #333; border-radius: 2px; overflow: hidden;">
            <div id="progress-bar" style="width: 0%; height: 100%; background: #4CAF50; 
                        border-radius: 2px; transition: width 0.3s ease;"></div>
        </div>
        <div id="progress-text" style="margin-top: 10px; font-size: 12px;">0%</div>
    </div>`;
    document.body.appendChild(downloadIndicator);
    let progress = 0;
    let progressBar = document.getElementById('progress-bar');
    let progressText = document.getElementById('progress-text');
    let progressInterval = setInterval(() => {
        progress += Math.random() * 15;
        if (progress > 90) progress = 90;
        progressBar.style.width = progress + '%';
        progressText.textContent = Math.round(progress) + '%';
    }, 200);
    $.ajax({
        url: "<?= base_url('admin/payroll/payslipView') ?>",
        type: 'GET',
        dataType: "json",
        data: { payslipid: id },
        success: function(result) {
            if (result.status === 'success') {
                popup(result.page);
                clearInterval(progressInterval);
                progressBar.style.width = '95%';
                progressText.textContent = '95%';
                let container = document.createElement('div');
                container.id = "hidden-print-area";
                container.style.position = "absolute";
                container.style.left = "-9999px";
                container.innerHTML = result.page;
                document.body.appendChild(container);
                html2canvas(container).then(canvas => {
                    const { jsPDF } = window.jspdf;
                    const pdf = new jsPDF('p', 'mm', 'a4');
                    const pageWidth = pdf.internal.pageSize.getWidth();
                    const pageHeight = pdf.internal.pageSize.getHeight();
                    const imgWidth = pageWidth - 20;
                    const imgHeight = (canvas.height * imgWidth) / canvas.width;
                    if (imgHeight <= pageHeight - 20) {
                        pdf.addImage(canvas.toDataURL('image/png'), 'PNG', 10, 10, imgWidth, imgHeight);
                    } else {
                        const totalPages = Math.ceil(imgHeight / (pageHeight - 20));
                        const canvas1 = document.createElement('canvas');
                        const ctx = canvas1.getContext('2d');
                        canvas1.width = canvas.width;
                        canvas1.height = (pageHeight - 20) * canvas.width / imgWidth;
                        for (let i = 0; i < totalPages; i++) {
                            ctx.clearRect(0, 0, canvas1.width, canvas1.height);
                            ctx.drawImage(canvas, 0, i * canvas1.height, canvas.width, canvas1.height, 0, 0, canvas1.width, canvas1.height);
                            const pageImage = canvas1.toDataURL('image/png');
                            if (i !== 0) pdf.addPage();
                            pdf.addImage(pageImage, 'PNG', 10, 10, imgWidth, pageHeight - 20);
                        }
                    }
                    progressBar.style.width = '100%';
                    progressText.textContent = '100%';
                    setTimeout(() => {
                        downloadIndicator.querySelector('div').innerHTML = `<div style="color: #4CAF50; font-size: 16px;">✓ Download Complete!</div>`;
                        setTimeout(() => {
                            document.body.removeChild(downloadIndicator);
                        }, 1000);
                    }, 300);
                    pdf.save('employee-payslip.pdf');
                    document.body.removeChild(container);
                });
            } else {
                document.body.removeChild(downloadIndicator);
                alert(result.message || "No record found.");
            }
        },
        error: function() {
            document.body.removeChild(downloadIndicator);
            alert("Error fetching payslip data.");
        }
    });
}
</script>
<script type="text/javascript">
    function getRecord(id, year) {
        $('input[name="amount"]').val('');
        $('input[name="emp_name"]').val('');
        $('input[name="paymentid"]').val('');
        $('input[name="paymentmonth"]').val('');
        $('input[name="paymentyear"]').val('');
        $('#monthid').val('');
        var month = '<?php echo $month_selected ?>';
        var base_url = '<?php echo base_url() ?>';
        $.ajax({
            url: base_url + 'admin/payroll/paymentRecord',
            type: 'POST',
            data: {
                staffid: id,
                month: month,
                year: year
            },
            dataType: "json",
            success: function(result) {
                $('input[name="amount"]').val(result.result.net_salary);
                $('input[name="emp_name"]').val(result.result.name + ' ' + result.result.surname + ' (' + result.result.employee_id + ')');
                $('input[name="paymentid"]').val(result.result.id);
                $('input[name="paymentmonth"]').val(month);
                $('input[name="paymentyear"]').val(year);
                $('input[name="staff_id"]').val(id);
                $('input[name="staff_role"]').val(result.result.role);
                $('#monthid').val(month + '-' + year);
            }
        });
        $('#proceedtopay').modal({
            show: true,
            backdrop: 'static',
            keyboard: false
        });

    };
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

    function getPayslip(id) {
        $('#payslipview').modal({
            show: true,
            backdrop: 'static',
            keyboard: false
        });
        $("#print").html("");
        $("#testdata").html(`
        <div style="text-align: center; padding: 50px 20px;">
            <div style="width: 40px; height: 40px; border: 4px solid #f3f3f3; border-top: 4px solid #3498db; 
                        border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto 20px;"></div>
            <div style="color: #666; font-size: 16px;">Loading Payslip...</div>
        </div>
        <style>
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        </style>
    `);
        var base_url = '<?php echo base_url() ?>';
        $.ajax({
            url: base_url + 'admin/payroll/payslipView',
            type: 'POST',
            data: {
                payslipid: id
            },
            success: function(result) {
                $("#print").html(
                    "<a href='#' data-toggle='tooltip' class='pull-right modal-title moprint' onclick='printData(" + id + ")' title='<?php echo $this->lang->line('download') ?>'><i class='fa fa-download'></i></a>"
                );
                $("#testdata").html(result);
            },
            error: function() {
                $("#testdata").html(`
                <div style="text-align: center; padding: 50px 20px; color: #d9534f;">
                    <i class="fa fa-exclamation-triangle" style="font-size: 24px; margin-bottom: 10px;"></i>
                    <div>Error loading payslip data.</div>
                </div>
            `);
            }
        });
    };

    function getEmployeeName(role) {

        var base_url = '<?php echo base_url() ?>';
        $("#name").html("<option value=''>select</option>");
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
                    div_data += "<option value='" + obj.name + "'>" + obj.name + "</option>";
                });

                $('#name').append(div_data);
            }
        });
    }

    function create(id) {

        var month = '<?php
                        if (isset($_POST["month"])) {
                            echo $_POST["month"];
                        }
                        ?>';
        var year = '<?php
                    if (isset($_POST["year"])) {
                        echo $_POST["year"];
                    }
                    ?>';

        $("#month").val(month);
        $("#year").val(year);
        $("#staffid").val(id);
        $("#formsubmit").submit();


    }




    function getSectionByClass(class_id, section_id) {
        if (class_id != "" && section_id != "") {
            $('#section_id').html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {
                    'class_id': class_id
                },
                dataType: "json",
                success: function(data) {
                    $.each(data, function(i, obj) {
                        var sel = "";
                        if (section_id == obj.section_id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.section_id + " " + sel + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                }
            });
        }
    }

    $(document).ready(function() {
        var class_id = $('#class_id').val();
        var section_id = '<?php echo set_value('section_id') ?>';
        getSectionByClass(class_id, section_id);
        $(document).on('change', '#class_id', function(e) {
            $('#section_id').html("");
            var class_id = $(this).val();
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {
                    'class_id': class_id
                },
                dataType: "json",
                success: function(data) {
                    $.each(data, function(i, obj) {
                        div_data += "<option value=" + obj.section_id + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                }
            });
        });
    });
</script>
<script type="text/javascript">
    $(document).on('change', '.payment_mode', function() {
        var mode = $(this).val();
        $('.cheque_div').css("display", mode === "Cheque" ? "block" : "none");
    });

    $("#schsetting_form").on('submit', function(e) {
        e.preventDefault();

        function addOneDay(date) {
            let newDate = new Date(date);
            newDate.setDate(newDate.getDate() + 1);
            return newDate.toISOString().split('T')[0];
        }

        try {
            let paymentDate = new Date($("input[name='payment_date']").val());
            let formattedPaymentDate = addOneDay(paymentDate);

            let formData = {
                "payment_mode": $("select[name='payment_mode']").val(),
                "payment_date": formattedPaymentDate,
                "remark": $("textarea[name='remarks']").val(),
                "hospital_id": <?= $data['hospital_id'] ?>,
                "cheque_no": "",
                "cheque_date": "",
                "attachment": "",
                "attachment_name": ""
            };

            if (!formData.payment_mode) {
                throw new Error("Payment mode is required.");
            }

            if (formData.payment_mode === "Cheque") {
                formData.cheque_no = $("input[name='cheque_no']").val();
                let chequeDate = new Date($("input[name='cheque_date']").val());
                formData.cheque_date = addOneDay(chequeDate);

                if (!formData.cheque_no) throw new Error("Cheque No is required.");
                if (!formData.cheque_date) throw new Error("Cheque Date is required.");

                var fileInput = document.getElementById('document').files[0];
                var documentUrl = $('#filedataalread').val();

                if (fileInput) {
                    var fileUploadData = new FormData();
                    fileUploadData.append('file', fileInput);

                    $.ajax({
                        url: 'https://phr-api.plenome.com/file_upload',
                        type: 'POST',
                        data: fileUploadData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            if (response.data) {
                                formData.attachment = response.data;
                                formData.attachment_name = fileInput.name;
                            }
                            submit(formData);
                        },
                        error: function() {
                            formData.attachment = documentUrl;
                            submit(formData);
                        }
                    });
                } else {
                    formData.attachment = documentUrl;
                    submit(formData);
                }
            } else {
                submit(formData);
            }
        } catch (error) {
            errorMsg(error.message);
        }

        function submit(formData) {
            let editid = $("input[name='paymentid']").val();
            sendAjaxRequest(
                "<?= $api_base_url ?>human-resource-payroll/ProceedToPay/" + editid,
                "PATCH",
                formData,
                function(response) {
                    handleResponse(response);
                }
            );
        }
    });
</script>
<script>
    function revertPaidPayslip(id) {
        if (confirm("Are you sure you want to revert this payslip?")) {
            sendAjaxRequest(
                '<?= $api_base_url ?>human-resource-payroll/revertPaidPayslip/' + id + '?hospital_id=<?= $data['hospital_id'] ?>',
                'PATCH',
                null,
                function(response) {
                    let message = (response[0]?.data?.message) || 'Payslip reverted successfully.';
                    successMsg(message);
                    window.location.reload();
                },
                function(jqXHR, textStatus, errorThrown) {
                    alert('Failed to revert payslip: ' + errorThrown);
                }
            );
        } else {
            alert('Revert action cancelled.');
        }
    }
</script>
<script>
    function revertPaidPayslip(id) {
        if (confirm("Are you sure you want to revert this payslip?")) {
            sendAjaxRequest(
                '<?= $api_base_url ?>human-resource-payroll/revertPaidPayslip/' + id + '?hospital_id=<?= $data['hospital_id'] ?>',
                'PATCH', {},
                function(response) {
                    handleResponse(response);
                },
                function(jqXHR, textStatus, errorThrown) {
                    alert('Failed to revert payslip: ' + errorThrown);
                }
            );
        } else {
            alert('Revert action cancelled.');
        }
    }
</script>
<script>
    function deletepayslip(id) {
        if (confirm("Are you sure you want to revert this payslip?")) {
            sendAjaxRequest(
                '<?= $api_base_url ?>human-resource-payroll/' + id + '?hospital_id=<?= $data['hospital_id'] ?>',
                'DELETE', {},
                function(response) {
                    handleResponse(response);
                },
                function(jqXHR) {
                    alert('Failed to revert payslip: ' + jqXHR.statusText);
                }
            );
        } else {
            alert('Revert action cancelled.');
        }
    }
</script>