<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-2">
                <div class="box border0">
                    <ul class="tablists">
                        <?php if ($this->rbac->hasPrivilege('leave_types', 'can_view')) { ?>
                            <li><a href="<?php echo base_url(); ?>admin/leavetypes" class="active"><?php echo $this->lang->line('leave_type'); ?></a></li>
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
                            <li><a href="<?php echo base_url(); ?>admin/admin/payroll_setup_type"><?php echo $this->lang->line('payroll'); ?> Type</a></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
            <div class="col-md-10">
                <div class="box box-primary" id="tachelist">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('leave_type_list'); ?></h3>
                        <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('leave_types', 'can_add')) { ?>
                                <a data-toggle="modal" data-target="#myModal" class="btn btn-primary btn-sm leavetype"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add_leave_type'); ?></a>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="mailbox-controls">
                        </div>
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label"><?php echo $this->lang->line('leave_type_list'); ?></div>
                            <table class="table table-striped table-bordered table-hover ajaxlist" id="ajaxlist">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th><?php echo $this->lang->line('name'); ?></th>
                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?>
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
                <h4 class="modal-title"><?php echo $this->lang->line('add_leave_type'); ?></h4>
            </div>
            <form id="addleavetype" name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                <div class="modal-body pt0 pb0">
                    <div class="ptt10">
                        <?php if ($this->session->flashdata('msg')) { ?>
                            <?php echo $this->session->flashdata('msg');
                            $this->session->unset_userdata('msg');
                            ?>

                        <?php } ?>
                        <?php echo $this->customlib->getCSRF(); ?>
                        <div class="form-group">
                            <label for="exampleInputEmail1"><?php echo $this->lang->line('name'); ?></label><small class="req"> *</small>
                            <input autofocus="" name="type" placeholder="" type="text" class="form-control" value="<?php
                                                                                                                    if (isset($result)) {
                                                                                                                        echo $result["type"];
                                                                                                                    }
                                                                                                                    ?>" />
                            <span class="text-danger"><?php echo form_error('type'); ?></span>
                        </div>
                    </div>
                </div><!--./modal-->
                <div class="modal-footer">
                    <button type="submit" id="formaddbtn" data-loading-text="<?php echo $this->lang->line('processing'); ?>" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
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
        $('#addleavetype').on('submit', function(e) {
            e.preventDefault();
            let type = $('input[name=type]').val();
            if (!type || type.trim() === '' || !/^[A-Za-z0-9\s]+$/.test(type)) {
                errorMsg('Please enter a valid leave type. It should not contain only spaces or special characters.');
                return false;
            }
            var formData = {
                "type": $('input[name=type]').val(),
                "is_active": "yes",
                "Hospital_id": <?= $data['hospital_id'] ?>
            };
            sendAjaxRequest('<?= $api_base_url ?>setup-human-resource-leave-types', 'POST', formData, function(response) {
                handleResponse(response);
            });
        });
    });
</script>
<div class="modal fade" id="editmyModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('edit_leave_type'); ?></h4>
            </div>
            <form id="editformadd" name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                <div class="modal-body pt0 pb0">
                    <div class="ptt10">
                        <?php if ($this->session->flashdata('msg')) { ?>
                            <?php echo $this->session->flashdata('msg');
                            $this->session->unset_userdata('msg');
                            ?>
                        <?php } ?>
                        <?php echo $this->customlib->getCSRF(); ?>
                        <div class="form-group">
                            <label for="exampleInputEmail1"><?php echo $this->lang->line('name'); ?></label><small class="req"> *</small>
                            <input autofocus="" id="type" name="type" placeholder="" type="text" class="form-control" />
                            <span class="text-danger"><?php echo form_error('type'); ?></span>
                            <input autofocus="" id="id" name="leavetypeid" placeholder="" type="hidden" class="form-control" />
                        </div>
                    </div>
                </div><!--./modal-->
                <div class="modal-footer">
                    <button type="submit" data-loading-text="<?php echo $this->lang->line('processing'); ?>" id="editformaddbtn" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                </div>
            </form>
        </div><!--./row-->
    </div>
</div>
<script>
    //editformadd
    $(document).ready(function() {
        $('#editformadd').on('submit', function(e) {
            e.preventDefault();
            var formData = {
                "type": $('#type').val(),
                "is_active": "yes",
                "Hospital_id": <?= $data['hospital_id'] ?>
            };
            let type = $('#type').val();
            if (!type || type.trim() === '' || !/^[A-Za-z0-9\s]+$/.test(type)) {
                errorMsg('Please enter a valid leave type. It should not contain only spaces or special characters.');
                return false;
            }
            sendAjaxRequest('<?= $api_base_url ?>setup-human-resource-leave-types/' + $('#id').val(), 'PATCH', formData,function(response) {
                handleResponse(response);
            });
        });
    });
</script>
<!-- //========datatable start===== -->
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
        initializeTable(initialData, initialDataTotal, `${base_url}admin/leavetypes/getleavetypelist`, '#ajaxlist', [
                'sno', 'type', 'action'
            ],
            actionTemplate,
            'id'
        );
    });
</script>
<!-- //========datatable end===== -->
<script>
    function get(id) {
        $('#editmyModal').modal('show');
        $.ajax({
            dataType: 'json',
            url: '<?php echo base_url(); ?>admin/leavetypes/get_type/' + id,
            success: function(result) {
                $('#id').val(result.id);
                $('#type').val(result.type);
            }
        });
    }

    function deleterecord(id) {
        if (confirm('Are you sure you want to delete this')) {
            sendAjaxRequest('<?= $api_base_url ?>setup-human-resource-leave-types/' + id + '?Hospital_id=<?= $data['hospital_id'] ?>', 'DELETE', {}, function(response) {
                handleResponse(response);
            });
        }
    }

    $(".leavetype").click(function() {
        $('#formadd').trigger("reset");
    });

    $(document).ready(function(e) {
        $('#myModal,#editmyModal').modal({
            backdrop: 'static',
            keyboard: false,
            show: false
        });
    });
</script>
<script type="text/javascript">
    $(document).ready(function() {
        defultaddleave();
    });

    function defultaddleave() {
        $.ajax({
            type: "GET",
            url: "<?= base_url(); ?>admin/leavetypes/getleavetypesdatatable",
            dataType: "json",
            success: function(response) {
                let recordsTotal = response.recordsTotal || 0;
                if (recordsTotal == 0) {
                    let formData = {
                        "type": "LOP",
                        "is_active": "yes",
                        "Hospital_id": <?= $data['hospital_id'] ?>
                    };
                    const accessToken = '<?= $data['accessToken'] ?? '' ?>';
                    if (!accessToken) {
                        errorMsg("Access token missing. Please login again.");
                        return;
                    }
                    $.ajax({
                        url: '<?= $api_base_url ?>setup-human-resource-leave-types',
                        type: 'POST',
                        data: JSON.stringify(formData),
                        headers: {
                            'Authorization': accessToken
                        },
                        contentType: "application/json",
                        success: function(response) {},
                        error: function(jqXHR, textStatus, errorThrown) {
                            alert('An error occurred. Please try again.');
                            $('#formaddbtn').button('reset');
                        }
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error("Error occurred: " + error);
            }
        });
    }
</script>