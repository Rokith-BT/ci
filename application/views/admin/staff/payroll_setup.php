<style type="text/css">
    @media print {

        .no-print,
        .no-print * {
            display: none !important;
        }
    }
</style>

<div class="content-wrapper">

    <!-- Main content -->
    <section class="content">
        <div class="row">

            <div class="col-md-2">
                <div class="box border0">
                    <ul class="tablists">
                        <?php if ($this->rbac->hasPrivilege('leave_types', 'can_view')) { ?>
                            <li><a href="<?php echo base_url(); ?>admin/leavetypes"><?php echo $this->lang->line('leave_type'); ?></a></li>
                        <?php } ?>
                        <?php if ($this->rbac->hasPrivilege('department', 'can_view')) { ?>
                            <li><a href="<?php echo base_url(); ?>admin/department"><?php echo $this->lang->line('department'); ?></a></li>
                        <?php } ?>
                        <?php if ($this->rbac->hasPrivilege('designation', 'can_view')) { ?>
                            <li><a href="<?php echo base_url(); ?>admin/designation/designation"><?php echo $this->lang->line('designation'); ?></a></li>
                        <?php } ?>
                        <?php if ($this->rbac->hasPrivilege('specialist', 'can_view')) { ?>
                            <li><a href="<?php echo base_url(); ?>admin/specialist"><?php echo $this->lang->line('specialist'); ?></a></li>
                        <?php } ?>
                        <?php if ($this->rbac->hasPrivilege('payroll', 'can_view')) { ?>
                            <li><a href="<?php echo base_url(); ?>admin/admin/payroll_setup" class="active"><?php echo $this->lang->line('payroll'); ?></a></li>
                        <?php } ?>
                        <?php if ($this->rbac->hasPrivilege('payroll', 'can_view')) { ?>
                            <li><a href="<?php echo base_url(); ?>admin/admin/payroll_setup_type"><?php echo $this->lang->line('payroll'); ?> Type</a></li>
                        <?php } ?>

                    </ul>
                </div>
            </div>

            <div class="col-md-10">
                <div class="box box-primary" id="tachelist">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('payroll'); ?> List</h3>
                        <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('payroll', 'can_add')) { ?>
                                <a data-toggle="modal" data-target="#myModal" class="btn btn-primary btn-sm payroll"><i
                                        class="fa fa-plus"></i> <?php echo $this->lang->line('add_payroll'); ?></a>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="mailbox-controls">
                        </div>
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label"><?php echo $this->lang->line('payroll_list'); ?></div>
                            <table class="table table-striped table-bordered table-hover ajaxlist" id="ajaxlist">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th><?php echo $this->lang->line('payroll'); ?> Type</th>
                                        <th><?php echo $this->lang->line('payroll'); ?> Name</th>
                                        <th>Percentage%</th>
                                        <th class="text-right no-print noExport">
                                            <?php echo $this->lang->line('action'); ?>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="">
                        <div class="mailbox-controls">
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add<?php echo $this->lang->line('payroll'); ?></h4>
            </div>
            <form id="payroll" name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                <div class="modal-body pt0 pb0">
                    <div class="ptt10">
                        <!-- Payroll Type Field -->
                        <div class="form-group">
                            <label for="payroll_type"><?php echo $this->lang->line('payroll'); ?> Type</label><small class="req"> *</small>
                            <select id="payroll_type" name="payroll_type" class="form-control">
                                <option value="" disable selected>Select <?php echo $this->lang->line('payroll'); ?> Type</option>
                                <?php
                                foreach ($payroll_tyle as $tyle) {
                                    echo "<option value='" . $tyle->id . "' " . (isset($result) && $result['payroll_type_id'] == $tyle->id ? 'selected' : '') . ">" . $tyle->category_name . "</option>";
                                }
                                ?>
                            </select>
                            <span class="text-danger"><?php echo form_error('payroll_type'); ?></span>
                        </div>

                        <!-- Name Field -->
                        <div class="form-group">
                            <label for="name"><?php echo $this->lang->line('payroll'); ?> <?php echo $this->lang->line('name'); ?></label><small class="req"> *</small>
                            <input autofocus="" id="name" name="name" placeholder="" type="text" class="form-control"
                                value="<?php if (isset($result)) echo $result['name']; ?>" />
                            <span class="text-danger"><?php echo form_error('name'); ?></span>
                        </div>

                        <!-- Percentage Field -->
                        <div class="form-group">
                            <label for="percentage"><?php echo $this->lang->line('percentage'); ?></label><small class="req"> *</small>
                            <input id="percentage" name="percentage" type="number" step="0.01" class="form-control"
                                placeholder="Enter percentage"
                                value="<?php if (isset($result)) echo $result['percentage']; ?>" />
                            <span class="text-danger"><?php echo form_error('percentage'); ?></span>
                        </div>

                        <!-- Hidden Payroll ID -->
                        <input id="payrollid" name="payrollid" type="hidden" class="form-control"
                            value="<?php if (isset($result)) echo $result['id']; ?>" />
                    </div>
                </div><!-- ./modal-body -->

                <div class="modal-footer">
                    <button type="submit" data-loading-text="<?php echo $this->lang->line('processing'); ?>"
                        id="formaddbtn" class="btn btn-info pull-right">
                        <?php echo $this->lang->line('save'); ?>
                    </button>
                </div>
            </form>
        </div><!--./row-->
    </div>
</div>
<div class="modal fade" id="editmyModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Payroll</h4>
            </div>
            <form id="edit_payroll" name="edit_employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                <div class="modal-body pt0 pb0">
                    <div class="ptt10">
                        <!-- Payroll Type Field -->
                        <div class="form-group">
                            <label for="edit_payroll_type"><?php echo $this->lang->line('payroll'); ?> Type</label><small class="req"> *</small>
                            <select id="edit_payroll_type" name="payroll_type" class="form-control">
                                <option value="" disable selected>Select <?php echo $this->lang->line('payroll'); ?> Type</option>
                                <?php
                                foreach ($payroll_tyle as $tyle) {
                                    echo "<option value='" . $tyle->id . "'>" . $tyle->category_name . "</option>";
                                }
                                ?>
                            </select>
                            <span class="text-danger"><?php echo form_error('payroll_type'); ?></span>
                        </div>

                        <!-- Name Field -->
                        <div class="form-group">
                            <label for="edit_name"><?php echo $this->lang->line('payroll'); ?> <?php echo $this->lang->line('name'); ?></label><small class="req"> *</small>
                            <input autofocus="" id="edit_name" name="name" placeholder="" type="text" class="form-control"
                                value="" />
                            <span class="text-danger"><?php echo form_error('name'); ?></span>
                        </div>

                        <!-- Percentage Field -->
                        <div class="form-group">
                            <label for="edit_percentage"><?php echo $this->lang->line('percentage'); ?></label><small class="req"> *</small>
                            <input id="edit_percentage" name="percentage" type="number" step="0.01" class="form-control"
                                placeholder="Enter percentage" value="" />
                            <span class="text-danger"><?php echo form_error('percentage'); ?></span>
                        </div>

                        <!-- Hidden Payroll ID -->
                        <input id="edit_payrollid" name="payrollid" type="hidden" class="form-control" value="" />
                    </div>
                </div><!-- ./modal-body -->

                <div class="modal-footer">
                    <button type="submit" data-loading-text="<?php echo $this->lang->line('processing'); ?>"
                        id="edit_formaddbtn" class="btn btn-info pull-right">
                        <?php echo $this->lang->line('save'); ?>
                    </button>
                </div>
            </form>
        </div><!--./row-->
    </div>
</div>
<?php
$data = $this->session->userdata('hospitaladmin');
$api_base_url = $this->config->item('api_base_url');
?>
<script>
    $(document).ready(function() {
        $('#payroll').on('submit', function(e) {
            e.preventDefault();
            let payrollType = $('#payroll_type').val();
            let name = $('#name').val();
            let percentage = $('#percentage').val();
            let hospitalId = <?= $data["hospital_id"] ?>;
            let errors = [];
            if (!payrollType || payrollType.trim() === "") {
                errors.push("Payroll Type is required");
            }
            if (!name || name.trim() === "") {
                errors.push("Payroll Name is required");
            }
            if (!percentage || percentage.trim() === "") {
                errors.push("Percentage is required");
            }
            if (errors.length > 0) {
                errors.forEach(function(error) {
                    errorMsg(error);
                });
                return;
            }
            let requestData = {
                payslip_category_id: payrollType,
                payslip_setting_name: name,
                default_amount: 0,
                default_percentage: percentage,
                hospital_id: <?= $data["hospital_id"] ?>
            };
            sendAjaxRequest('<?= $api_base_url ?>setup-human-resource-payslip-settings', 'POST', requestData, function(response) {
                handleResponse(response);
            });
        });
        window.get = function(id) {
            $('#editmyModal').modal('show');
            $.ajax({
                dataType: 'json',
                url: '<?php echo base_url(); ?>admin/admin/getpayrolldatatable_id/' + id,
                success: function(result) {
                    $('#edit_payrollid').val(result.id);
                    $('#edit_payroll_type').val(result.payslip_category_id);
                    $('#edit_name').val(result.payslip_setting_name);
                    $('#edit_percentage').val(result.default_percentage);
                }
            });
        };
        window.deleterecord = function(id) {
            if (confirm('Are you sure you want to delete this?')) {
                sendAjaxRequest('<?= $api_base_url ?>setup-human-resource-payslip-settings/' + id + '?hospital_id=<?= $data['hospital_id'] ?>', 'DELETE',{}, function(response) {
                    handleResponse(response);
                });
            }
        };
        $(document).ready(function() {
            $('#edit_payroll').on('submit', function(e) {
                e.preventDefault();
                const payslip_category_id = $('#edit_payroll_type').val();
                const payslip_setting_name = $('#edit_name').val();
                const default_percentage = $('#edit_percentage').val();
                const payroll_id = $('#edit_payrollid').val();

                let errors = [];
                if (!payslip_category_id) {
                    errors.push("Payroll Type is required.");
                }
                if (!payslip_setting_name) {
                    errors.push("Payroll Name is required.");
                }
                if (!default_percentage || default_percentage <= 0) {
                    errors.push("Percentage must be greater than 0.");
                }

                if (errors.length > 0) {
                    alert(errors.join("\n"));
                    return;
                }

                const requestData = {
                    payslip_category_id: parseInt(payslip_category_id, 10),
                    payslip_setting_name: payslip_setting_name,
                    default_amount: 0,
                    default_percentage: parseFloat(default_percentage),
                    hospital_id: <?= $data["hospital_id"] ?>
                };
               sendAjaxRequest('<?= $api_base_url ?>setup-human-resource-payslip-settings/' + payroll_id, 'PATCH', requestData, function(response) {
                    handleResponse(response);
                });
            });
        });

    });
</script>
<script>
    const initialData = <?= json_encode($initialData) ?>;
    const initialDataTotal = initialData.recordsTotal || initialData.length || 0;
    $(document).ready(function() {

        let actionTemplate = `
        <a href="#" onclick="get(key:id)" class="btn btn-default btn-xs" data-toggle='#editmyModal' data-record-id="key:id" title="Edit">
            <i class="fa fa-pencil"></i>
        </a>
        <a href="#" onclick="deleterecord(key:id)" class="btn btn-default btn-xs" data-loading-text="Please Wait.." data-toggle="tooltip" data-record-id="key:id" title="Delete">
            <i class="fa fa-trash"></i>
        </a>
    `;
        initializeTable(initialData, initialDataTotal, `${base_url}admin/admin/getpayrolllist`, '#ajaxlist', [
                'sno', 'payslip_category_name', 'payslip_setting_name', 'default_percentage', 'action'
            ],
            actionTemplate,
            'id'
        );
    });
</script>