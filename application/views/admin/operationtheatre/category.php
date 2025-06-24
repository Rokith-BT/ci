<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-2">
                <div class="box border0">
                    <ul class="tablists">
                        <li><a href="<?php echo site_url('admin/operationtheatre/category') ?>" class="active"><?php echo $this->lang->line('operation_category'); ?></a></li>
                        <li><a href="<?php echo site_url('admin/operationtheatre') ?>"><?php echo $this->lang->line('operation'); ?></a></li>
                </div>
            </div><!--./col-md-3-->
            <div class="col-md-10">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('operation_category_list'); ?></h3>
                        <div class="box-tools pull-right">
                            <a data-toggle="modal" data-target="#myModal" class="btn btn-primary btn-sm addcategory"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add_category'); ?></a>
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
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="modaltitle"></h4>
            </div>
            <form id="operationtheatrecategoryadd" name="employeeform" method="post" accept-charset="utf-8">
                <div class="modal-body pt0 pb0">
                    <div class="ptt10">
                        <div class="form-group">
                            <label for="pwd"><?php echo $this->lang->line('operation_category'); ?></label> <small class="req"> *</small>
                            <input class="form-control" id="category_name" name="category_name" value="<?php echo set_value('operation_category'); ?>" />
                            <span class="text-danger"><?php echo form_error('category_name'); ?></span>
                            <input class="form-control" id="id" name="id" value="" type="hidden" value="0" />
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#operationtheatrecategoryadd').on('submit', function(e) {
            e.preventDefault();
            var formData = {
                category: $('input[name="category_name"]').val(),
                is_active: "no",
                Hospital_id: '<?= $data['hospital_id'] ?>'
            };
            var emptyFields = [];
            var categoryPattern = /^[a-zA-Z0-9]+[a-zA-Z0-9 ]*$/;
            if (!formData.category) emptyFields.push('Category Name');
            if (formData.category && !categoryPattern.test(formData.category.trim())) {
                emptyFields.push('Category Name (invalid)');
            }
            if (emptyFields.length > 0) {
                errorMsg("Please fill the following fields: " + emptyFields.join(', '));
                $('#formaddbtn').button('reset');
                return;
            }
            var editid = $('#id').val();
            var api;
            var method;
            if (editid == 0) {
                api = "<?= $api_base_url ?>setup-operation-operation-category";
                method = "POST";
            } else {
                api = "<?= $api_base_url ?>setup-operation-operation-category/" + editid;
                method = "PATCH";
            }
            sendAjaxRequest(api, method, formData, function(response) {
                handleResponse(response);
            });
        });
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
        $("#modaltitle").html('<?php echo $this->lang->line('edit_category'); ?>');
        $('#myModal').modal('show');

        $.ajax({
            dataType: 'json',
            url: '<?php echo base_url(); ?>admin/operationtheatre/getcategory/' + id,
            success: function(result) {
                $('#id').val(result.id);
                $('#category_name').val(result.category);

            }
        });
    }

    $(".addcategory").click(function() {
        $("#modaltitle").html('<?php echo $this->lang->line('add_category'); ?>');
        $('#formadd').trigger("reset");
    });
</script>
<script>
    function delete_recordById(id) {
        if (confirm(<?php echo "'" . $this->lang->line('delete_confirm') . "'"; ?>)) {
            sendAjaxRequest('<?= $api_base_url ?>setup-operation-operation-category/' + id + '?Hospital_id=' + <?= $data['hospital_id'] ?>, "DELETE", {
                id: id
            }, function(response) {
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
            `${base_url}admin/operationtheatre/getcategorylist`,
            '#ajaxlist',
            ['sno', 'category', 'action'],
            actionTemplate,
            'id'
        );
    });
</script>
