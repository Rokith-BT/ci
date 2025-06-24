<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-2">
                <div class="box border0">
                    <ul class="tablists">
                        <li><a href="<?php echo site_url('admin/operationtheatre/category') ?>"><?php echo $this->lang->line('operation_category'); ?></a></li>
                        <li><a href="<?php echo site_url('admin/operationtheatre') ?>" class="active"><?php echo $this->lang->line('operation'); ?></a></li>

                </div>
            </div><!--./col-md-3-->
            <div class="col-md-10">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('operation_list'); ?></h3>
                        <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('setup_front_office', 'can_add')) { ?>
                                <a data-toggle="modal" data-target="#myModal" class="btn btn-primary btn-sm addoperation"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add_operation'); ?></a>
                            <?php } ?>
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="download_label"><?php echo $this->lang->line('operation_category_list'); ?></div>
                        <div class="table-responsive mailbox-messages">
                         <table class="table table-striped table-bordered table-hover ajaxlist" id="ajaxlist">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th><?php echo $this->lang->line('name'); ?></th>
                                        <th><?php echo $this->lang->line('category'); ?></th>
                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
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
<div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('add_operation'); ?></h4>
            </div>
            <form id="operationadd" name="employeeform" method="post" accept-charset="utf-8">
                <div class="modal-body pt0 pb0">
                    <div class="ptt10">
                        <div class="form-group">
                            <label for="pwd"><?php echo $this->lang->line('operation_name'); ?></label> <small class="req"> *</small>
                            <input class="form-control" id="operation_name" name="operation_name" value="<?php echo set_value('operation_name'); ?>" />
                            <span class="text-danger"><?php echo form_error('operation_name'); ?></span>
                        </div>
                        <div class="form-group">
                            <label for="pwd"><?php echo $this->lang->line('category'); ?></label> <small class="req"> *</small>
                            <select class="form-control select2 " id="category" name="category" style="width:100%">
                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                <?php foreach ($category_list as $category) { ?>
                                    <option value="<?php echo $category['id']; ?>"><?php echo $category['category']; ?></option>
                                <?php  } ?>
                            </select>
                            <span class="text-danger"><?php echo form_error('category'); ?></span>
                        </div>
                    </div>
                </div><!--./col-md-12-->
                <div class="modal-footer">
                    <button type="submit" data-loading-text="<?php echo $this->lang->line('processing'); ?>" id="formaddbtn" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                </div>
            </form>
        </div><!--./row-->
    </div>
</div>
<?php
$data = $this->session->userdata('hospitaladmin');
$api_base_url = $this->config->item('api_base_url');
?>
<div class="modal fade" id="editmyModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('edit_operation'); ?></h4>
            </div>
            <form id="editformadd" name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data" class="ptt10">
                <div class="modal-body pt0 pb0">
                    <div class="form-group">
                        <label for="pwd"><?php echo $this->lang->line('operation_name'); ?></label> <small class="req"> *</small>
                        <input class="form-control" id="edit_operation_name" name="edit_operation_name" value="<?php echo set_value('edit_operation_name'); ?>" />
                        <span class="text-danger"><?php echo form_error('edit_operation_name'); ?></span>
                        <input class="form-control" id="id" name="id" value="" type="hidden" />
                    </div>
                    <div class="form-group">
                        <label for="pwd"><?php echo $this->lang->line('category'); ?></label> <small class="req"> *</small>
                        <select class="form-control select2 " id="edit_category" name="edit_category" style="width:100%">
                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                            <?php foreach ($category_list as $category) { ?>
                                <option value="<?php echo $category['id']; ?>"><?php echo $category['category']; ?></option>
                            <?php  } ?>
                        </select>
                        <span class="text-danger"><?php echo form_error('category'); ?></span>
                    </div>
                </div><!--./modal-body-->
                <div class="modal-footer">
                    <button type="submit" data-loading-text="<?php echo $this->lang->line('processing'); ?>" id="editformaddbtn" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                </div>
            </form>
        </div><!--./row-->
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#editformadd').on('submit', function(e) {
            e.preventDefault();
            var formData = {
                operation: $('#edit_operation_name').val(),
                category_id: $('#edit_category').val(),
                Hospital_id: '<?= $data['hospital_id'] ?>'
            };
            let id = $('#id').val();
            let emptyFields = [];
            var namePattern = /^[a-zA-Z0-9]+[a-zA-Z0-9 ]*$/;

            if (!formData.operation) emptyFields.push('Operation Name');
            if (!formData.category_id) emptyFields.push('Category');

            if (formData.operation && !namePattern.test(formData.operation.trim())) {
                emptyFields.push('Operation Name (invalid)');
                $('#editformaddbtn').button('reset');
            }

            if (emptyFields.length > 0) {
                errorMsg("Please fill the following fields: " + emptyFields.join(', '));
                $('#editformaddbtn').button('reset');
                return;
            }
            sendAjaxRequest('<?= $api_base_url ?>setup-operation-operation/' + id, 'PATCH', formData, function(response) {
                handleResponse(response);
            });
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#operationadd').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            var emptyFields = [];
            var namePattern = /^[a-zA-Z0-9]+[a-zA-Z0-9 ]*$/;
            if (!formData.get('operation_name')) emptyFields.push('Operation Name');
            if (!formData.get('category')) emptyFields.push('Category');
            if (!'<?= $data['hospital_id'] ?>') emptyFields.push('Hospital ID');
            if (emptyFields.length > 0) {
                errorMsg("Please fill the following fields: " + emptyFields.join(', '));
                $("#formaddbtn").button('reset');
                return;
            }
            if (!namePattern.test(formData.get('operation_name').trim())) {
                errorMsg("Operation Name cannot contain only spaces or special characters.");
                $("#formaddbtn").button('reset');
                return;
            }
            var validatedData = {
                operation: formData.get('operation_name').trim(),
                category_id: formData.get('category'),
                is_active: "yes",
                Hospital_id: '<?= $data['hospital_id'] ?>'
            };
            sendAjaxRequest('<?= $api_base_url ?>setup-operation-operation', 'POST', validatedData, function(response) {
                handleResponse(response);
            });
        });
    });
</script>
<script type="text/javascript">
    $(document).ready(function(e) {
        $('.select2').select2();
    });
</script>
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

        $('#myModal,#editmyModal').modal({
            backdrop: 'static',
            keyboard: false,
            show: false
        });
    });
</script>
<script>
    function get(id) {
        $('#editmyModal').modal('show');
        $.ajax({
            dataType: 'json',
            url: '<?php echo base_url(); ?>admin/operationtheatre/getoperation/' + id,
            success: function(result) {
                $('#id').val(result.id);
                $('#edit_operation_name').val(result.operation);
                $("#edit_category").select2("val", result.category_id);

            }
        });
    }
    $('#myModal').on('hidden.bs.modal', function(e) {

        $("#category", $(this)).select2("val", "");
        $("#formadd", $(this)).find('input:text').val('');

    });
</script>
<script>
    function delete_recordById(id) {
        if (confirm(<?php echo "'" . $this->lang->line('delete_confirm') . "'"; ?>)) {
            sendAjaxRequest('<?= $api_base_url ?>setup-operation-operation/' + id + '?Hospital_id=' + <?= $data['hospital_id'] ?>, 'DELETE', {}, function(response) {
                handleResponse(response);
            });
        }
    }
</script>
<script>
    const initialData = <?= json_encode($initialData) ?>;
    const initialDataTotal = initialData.recordsTotal || initialData.length || 0;
    $(document).ready(function () {
        let actionTemplate = `
            <a data-target="#editmyModal" onclick="get(key:id)" class="btn btn-default btn-xs" data-toggle="tooltip" title="" data-original-title="<?php echo $this->lang->line('edit'); ?>">
                <i class="fa fa-pencil"></i>
            </a>
            <a class="btn btn-default btn-xs" data-toggle="tooltip" title="" onclick="delete_recordById('key:id')" data-original-title="<?php echo $this->lang->line('delete'); ?>">
                <i class="fa fa-trash"></i>
            </a>
        `;
        initializeTable(
            initialData,
            initialDataTotal,
            `${base_url}admin/operationtheatre/getoperationlist`,
            '#ajaxlist',
            ['sno','operation','category', 'action'],
            actionTemplate,
            'id'
        );
    });
</script>
