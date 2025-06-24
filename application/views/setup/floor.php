<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-2">
                <?php
                $this->load->view('setup/bedsidebar');
                ?>
            </div>
            <div class="col-md-10">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('floor_list'); ?></h3>
                        <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('floor', 'can_add')) { ?>
                                <a data-toggle="modal" data-target="#myModal" class="btn btn-primary btn-sm floor"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add_floor'); ?></a>
                            <?php } ?>
                        </div><!-- /.box-tools -->
                    </div>
                    <div class="box-body">
                        <div class="download_label"><?php echo $this->lang->line('floor_list'); ?></div>
                        <div class="table-responsive mailbox-messages">
                            <table class="table table-striped table-bordered table-hover" id="ajaxlist">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th><?php echo $this->lang->line('name'); ?></th>
                                        <th><?php echo $this->lang->line('description'); ?></th>
                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table><!-- /.table -->
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                </div>
            </div><!--/.col (left) -->
            <!-- right column -->
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<!-- new END -->
</div><!-- /.content-wrapper -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm400" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('add_floor'); ?></h4>
            </div>
            <form id="addfloor" class="ptt10" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                <div class="modal-body pt0 pb0">
                    <div class="" id="edit_expensedata">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('name'); ?></label> <small class="req"> *</small>
                                    <input id="invoice_no" name="name" placeholder="" type="text" class="form-control" value="<?php echo set_value('invoice_no'); ?>" />
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('description'); ?></label>
                                    <textarea class="form-control" id="description" name="description" placeholder="" rows="2" placeholder="Enter ..."><?php echo set_value('description'); ?><?php echo set_value('description') ?></textarea>
                                    <span class="text-danger description"></span>

                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- /.box-body -->
                <div class="modal-footer">
                    <div class="pull-right">
                        <button type="submit" data-loading-text="<?php echo $this->lang->line('processing'); ?>" id="addfloorbtn" class="btn btn-info "><i class="fa fa-check-circle"></i> <?php echo $this->lang->line('save'); ?></button>
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
    <div class="modal-dialog modal-sm400" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('edit_floor'); ?></h4>
            </div>
            <div class="modal-body pt0 pb0">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12" id="edit_floor">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('.detail_popover').popover({
            placement: 'right',
            trigger: 'hover',
            container: 'body',
            html: true,
            content: function() {
                return $(this).closest('td').find('.fee_detail_popover').html();
            }
        });
    });


    function edit(id) {
        $('#myModaledit').modal('show');
        $.ajax({
            url: '<?php echo base_url(); ?>admin/setup/floor/getDataByid/' + id,
            success: function(data) {
                $('#edit_floor').html(data);
            }
        });
    }

    $(".floor").click(function() {
        $('#addfloor').trigger("reset");
    });

    $(document).ready(function(e) {
        $('#myModal,#myModaledit').modal({
            backdrop: 'static',
            keyboard: false,
            show: false
        });
    });
</script>
<script>
function delete_floot(id) {
    if (confirm("Are you sure you want to delete this floor?")) {
        sendAjaxRequest('<?= $api_base_url ?>setup-bed-floor/' + id + '?Hospital_id=<?= $data['hospital_id'] ?>', 'DELETE', {}, function(response) {
           handleResponse(response);
        });
    }
}
</script>
<script>
$(document).ready(function() {
    $('#addfloor').on('submit', function(e) {
        e.preventDefault();
        var formData = {
            name: $('#invoice_no').val(),
            description: $('#description').val(),
            Hospital_id: <?= $data['hospital_id'] ?>
        };

        var requiredFields = [{
            field: "name",
            pattern: /^[a-zA-Z0-9\s]+$/,
            error: "Name must contain only letters, numbers, and spaces"
        }];

        var errorMessages = [];
        requiredFields.forEach(function(field) {
            if (!formData[field.field]) {
                errorMessages.push(field.error);
            } else if (field.pattern && !field.pattern.test(formData[field.field])) {
                errorMessages.push(field.error);
            }
        });
        if (errorMessages.length > 0) {
            errorMsg(errorMessages.join(', '));
            return;
        }
        sendAjaxRequest('<?= $api_base_url ?>setup-bed-floor', 'POST', formData, function(response) {
            handleResponse(response);
        });
    });
});
</script>
<script>
    const initialData = <?= json_encode($initialData) ?>;
    const initialDataTotal = initialData.recordsTotal || initialData.length || 0;

    $(document).ready(function () {
        let actionTemplate = `
            <a data-target="#myModaledit" onclick="edit(key:id)" class="btn btn-default btn-xs" data-toggle="tooltip" title="" data-original-title="Edit">
                <i class="fa fa-pencil"></i>
            </a>
            <a class="btn btn-default btn-xs" data-toggle="tooltip" title="" onclick="delete_floot(key:id)" data-original-title="Delete">
                <i class="fa fa-trash"></i>
            </a>
        `;

        initializeTable(
            initialData,
            initialDataTotal,
            `${base_url}admin/setup/floor/getbedfloor`,
            '#ajaxlist',
            ['sno', 'name', 'description', 'action'],
            actionTemplate,
            'id'
        );
    });
</script>
