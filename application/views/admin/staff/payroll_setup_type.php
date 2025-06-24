<?php
$data = $this->session->userdata('hospitaladmin');
$api_base_url = $this->config->item('api_base_url');
?>
<style type="text/css">
    @media print {
        .no-print,
        .no-print * {
            display: none !important;
        }
    }
</style>

<div class="content-wrapper">
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
                            <li><a href="<?php echo base_url(); ?>admin/admin/payroll_setup"><?php echo $this->lang->line('payroll'); ?></a></li>
                        <?php } ?>
                        <?php if ($this->rbac->hasPrivilege('payroll', 'can_view')) { ?>
                            <li><a href="<?php echo base_url(); ?>admin/admin/payroll_setup_type" class="active"><?php echo $this->lang->line('payroll'); ?> Type</a></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>

            <div class="col-md-10">
                <div class="box box-primary" id="tachelist">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('payroll'); ?> List</h3>
                        <!-- <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('payroll', 'can_add')) { ?>
                                <a data-toggle="modal" data-target="#myModal" class="btn btn-primary btn-sm payroll"><i
                                        class="fa fa-plus"></i> <?php echo $this->lang->line('add_payroll'); ?></a>
                            <?php } ?>
                        </div> -->
                    </div>
                    <div class="box-body">
                        <div class="mailbox-controls"></div>
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label"><?php echo $this->lang->line('payroll_list'); ?></div>
                            <table class="table table-striped table-bordered table-hover ajaxlist">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('payroll'); ?> Type</th>                                      
                                        <th class="text-right no-print noExport">
                                            <?php echo $this->lang->line('action'); ?>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
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
            <form id="payroll_type" name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                <div class="modal-body pt0 pb0">
                    <div class="ptt10">
                        <div class="form-group">
                            <label for="payroll_type"><?php echo $this->lang->line('payroll'); ?> Type</label><small class="req"> *</small>
                            <input autofocus="" id="payroll_type_name" name="name" placeholder="" type="text" class="form-control"
                            value="<?php if (isset($result)) echo $result['name']; ?>" />
                            <span class="text-danger"><?php echo form_error('payroll_type'); ?></span>
                        </div>                       
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" data-loading-text="<?php echo $this->lang->line('processing'); ?>"
                        id="formaddbtn" class="btn btn-info pull-right">
                        <?php echo $this->lang->line('save'); ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="editmyModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add<?php echo $this->lang->line('payroll'); ?></h4>
            </div>
            <form id="edit_payroll_type" name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                <div class="modal-body pt0 pb0">
                    <div class="ptt10">
                        <div class="form-group">
                            <label for="payroll_type"><?php echo $this->lang->line('payroll'); ?> Type</label><small class="req"> *</small>
                            <input autofocus="" id="edit_payroll_type_name" name="name" placeholder="" type="text" class="form-control"
                            value="<?php if (isset($result)) echo $result['name']; ?>" />
                            <span class="text-danger"><?php echo form_error('payroll_type'); ?></span>
                        </div>
                        <input id="payrollid" name="payrollid" type="hidden" class="form-control"
                            value="<?php if (isset($result)) echo $result['id']; ?>" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" data-loading-text="<?php echo $this->lang->line('processing'); ?>"
                        id="formaddbtn" class="btn btn-info pull-right">
                        <?php echo $this->lang->line('save'); ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#payroll_type').on('submit', function(e) {
            e.preventDefault();
            let type = $('#payroll_type_name').val();
            if (!type || type.trim() === '' || !/^[A-Za-z0-9\s]+$/.test(type)) {
                errorMsg('Please enter a valid payroll name. It should not contain only spaces or special characters.');
                return false;
            }
            $.ajax({
                url: '<?= $api_base_url ?>setup-human-resource-payslip-category',
                type: 'POST',
                data: {
                    "category_name": $("#payroll_type_name").val(),
                    "hospital_id": <?= $data["hospital_id"] ?>
                },
                beforeSend: function() {
                    $('#formaddbtn').button('loading');
                },
                success: function(result) {
                    successMsg(result[0]?.data?.message);
                    location.reload();
                },
                error: function() {
                    alert('An error occurred. Please try again.');
                    $('#formaddbtn').button('reset');
                }
            });
        });
        $('#edit_payroll_type').on('submit', function(e) {
            e.preventDefault();
            let type = $('#edit_payroll_type_name').val();
            if (!type || type.trim() === '' || !/^[A-Za-z0-9\s]+$/.test(type)) {
                errorMsg('Please enter a valid payroll name. It should not contain only spaces or special characters.');
                return false;
            }
            $.ajax({
                url: '<?= $api_base_url ?>setup-human-resource-payslip-category/'+ $("#payrollid").val(),
                type: 'PATCH',
                data: {
                    "category_name": $("#edit_payroll_type_name").val(),
                    "hospital_id": <?= $data["hospital_id"] ?>
                },
                beforeSend: function() {
                    $('#formaddbtn').button('loading');
                },
                success: function(result) {
                    successMsg(result[0]?.data?.message);
                    location.reload();
                },
                error: function() {
                    alert('An error occurred. Please try again.');
                    $('#formaddbtn').button('reset');
                }
            });
        });

        window.get = function(id) {
            $('#editmyModal').modal('show');
            $.ajax({
                dataType: 'json',
                url: '<?php echo base_url(); ?>admin/admin/getpayrolldatatable_type/' + id,
                success: function(result) {
                    $('#payrollid').val(result[0].id);
                    $('#edit_payroll_type_name').val(result[0].category_name);
                }
            });
        };
        window.deleterecord = function(id) {
            if (confirm('Are you sure you want to delete this?')) {
                $.ajax({
                    url: '<?= $api_base_url ?>setup-human-resource-payslip-category/' + id + '?hospital_id=<?= $data['hospital_id'] ?>',
                    type: 'DELETE',
                    success: function(result) {
                        successMsg(result[0]?.data?.message);
                        location.reload();
                    }
                });
            }
        };

    });
</script>
<script type="text/javascript">
( function ( $ ) {    
    'use strict';
    $(document).ready(function () {
        initDatatable('ajaxlist','admin/admin/getpayrolldatatable');
    });
} ( jQuery ) )
</script>
