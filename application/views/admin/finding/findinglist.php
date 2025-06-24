<?php
$data = $this->session->userdata('hospitaladmin');
$api_base_url = $this->config->item('api_base_url');
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-2">
                <div class="box border0">
                    <ul class="tablists">
                        <?php
                        if ($this->rbac->hasPrivilege('finding', 'can_view')) { ?>
                            <li><a class="<?php echo set_sidebar_Submenu('setup/finding/finding_head'); ?>" href="<?php echo base_url(); ?>admin/finding"><?php echo $this->lang->line('finding'); ?></a></li>
                        <?php } ?>
                        <?php if ($this->rbac->hasPrivilege('finding_category', 'can_view')) { ?>
                            <li><a class="<?php echo set_sidebar_Submenu('setup/finding/category'); ?>" href="<?php echo base_url(); ?>admin/finding/category"><?php echo $this->lang->line('category'); ?></a></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
            <div class="col-md-10">
                <!-- general form elements -->
                <div class="box box-primary" id="exphead">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('finding_list'); ?></h3>
                        <div class="box-tools pull-right">
                            <a data-toggle="modal" data-target="#myModal" class="btn btn-primary btn-sm finding"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add_finding'); ?></a>
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="download_label"><?php echo $this->lang->line('finding_list'); ?></div>
                        <div class="table-responsive mailbox-messages">
                            <table class="table table-striped table-bordered table-hover ajaxlist" id="ajaxlist">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th><?php echo $this->lang->line('finding'); ?></th>
                                        <th><?php echo $this->lang->line('category'); ?></th>
                                        <th><?php echo $this->lang->line('finding_description'); ?></th>
                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table><!-- /.table -->
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                </div>
            </div>
            <!-- right column -->
        </div> <!-- /.row -->
    </section><!-- /.content -->
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('add_finding'); ?></h4>
            </div>
            <form id="findinglistadd" name="employeeform" method="post" accept-charset="utf-8">
                <div class="modal-body pt0 pb0">
                    <div class="ptt10">
                        <div class="form-group">
                            <label for="exampleInputEmail1"><?php echo $this->lang->line('finding'); ?></label><small class="req"> *</small>
                            <input autofocus="" id="name" name="name" placeholder="" type="text" class="form-control" value="<?php echo set_value('finding'); ?>" />
                            <span class="text-danger"><?php echo form_error('finding'); ?></span>
                        </div>
                        <div class="form-group">
                            <label><?php echo $this->lang->line('category'); ?></label>
                            <small class="req"> *</small>
                            <select name="type" class="form-control">
                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                <?php foreach ($findingresulttype as $value) {
                                ?>
                                    <option value="<?php echo $value['id'] ?>"><?php echo $value['category']; ?></option>
                                <?php } ?>
                            </select>
                            <span class="text-danger"><?php echo form_error('type'); ?></span>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label>
                            <textarea class="form-control" id="description" name="description" placeholder="" rows="3"><?php echo set_value('description'); ?></textarea>
                            <span class="text-danger"><?php echo form_error('description'); ?></span>
                        </div>
                    </div>
                </div><!--./mpdal-->
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
        $('#findinglistadd').on('submit', function(e) {
            e.preventDefault();
            try {
                var name = $('input[name="name"]').val().trim();
                var findingCategoryId = $('select[name="type"]').val();
                if (!name) {
                    errorMsg('Name is required');
                    return;
                }
                if (!findingCategoryId) {
                    errorMsg('Category is required');
                    return;
                }
                var formData = {
                    name: name,
                    finding_category_id: findingCategoryId,
                    description: $('textarea[name="description"]').val(),
                    Hospital_id: <?= $data['hospital_id'] ?>
                };
                const accessToken = '<?= $data['accessToken'] ?? '' ?>';
                if (!accessToken) {
                    errorMsg("Access token missing. Please login again.");
                    return;
                }
                sendAjaxRequest('<?= $api_base_url ?>setup-findings-finding', 'POST', formData, function(response) {
                    handleResponse(response);
                });
            } catch (error) {
                console.error('AJAX Error:', error);
                const errMsg = error.responseJSON?.message || error.responseText ||
                    'An unexpected error occurred.';
                errorMsg(errMsg);
            }
        });
    });
</script>
<div class="modal fade" id="editmyModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('edit_finding'); ?></h4>
            </div>
            <form id="editformadd" action="<?php echo site_url('admin/finding/edit') ?>" name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                <div class="modal-body pt0 pb0">
                    <div class="ptt10">
                        <div class="form-group">
                            <label for="exampleInputEmail1"><?php echo $this->lang->line('finding'); ?></label><small class="req"> *</small>
                            <input id="findingtitle" name="name" placeholder="" type="text" class="form-control" value="" />
                            <span class="text-danger"><?php echo form_error('finding'); ?></span>
                        </div>
                        <div class="form-group">
                            <label><?php echo $this->lang->line('category'); ?></label>
                            <small class="req"> *</small>
                            <select name="type" id="findingcategory" onchange="" class="form-control">
                                <option value=""><?php echo $this->lang->line('select') ?></option>
                                <?php foreach ($findingresulttype as $value) {
                                ?>
                                    <option value="<?php echo $value['id'] ?>"><?php echo $value['category']; ?></option>
                                <?php } ?>
                            </select>
                            <span class="text-danger"><?php echo form_error('category'); ?></span>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label>
                            <textarea class="form-control" id="description_edit" name="description" placeholder="" rows="3"><?php echo set_value('description'); ?></textarea>
                            <input type="hidden" id="finding_id" name="finding_id">
                            <span class="text-danger"><?php echo form_error('description'); ?></span>
                        </div>

                    </div>
                </div><!--./modal-->
                <div class="modal-footer">
                    <button type="submit" id="editformaddbtn" data-loading-text="<?php echo $this->lang->line('processing'); ?>" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                </div>
            </form>
        </div><!--./row-->
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#editformadd').on('submit', function(e) {
            e.preventDefault();
            try {
                var formData = {
                    name: $('#findingtitle').val(),
                    finding_category_id: $('#findingcategory').val(),
                    description: $('#description_edit').val()
                };
                let finding_id = $('#finding_id').val();
                sendAjaxRequest('<?= $api_base_url ?>setup-findings-finding/' + finding_id, 'PATCH', formData, function(response) {
                    handleResponse(response);
                });
            } catch (error) {
                console.error('AJAX Error:', error);
                const errMsg = error.responseJSON?.message || error.responseText ||
                    'An unexpected error occurred.';
                errorMsg(errMsg);
            }
        });
    });
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#btnreset").click(function() {
            $("#form1")[0].reset();
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
    });
</script>

<script type="text/javascript">
    var base_url = '<?php echo base_url() ?>';
    $("#print_div").click(function() {
        Popup($('#exphead').html());
    });
</script>

<script>
    function get(id) {
        $('#editmyModal').modal('show');
        $.ajax({
            dataType: 'Json',
            url: '<?php echo base_url(); ?>admin/finding/get_data/' + id,
            success: function(result) {
                console.log(result.finding_type)
                $('#finding_id').val(result.id);
                $('#findingtitle').val(result.name);
                $('#findingcategory').val(result.finding_category_id);
                $('#description_edit').val(result.description);
            }
        });
    }
    $(".finding").click(function() {
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
<script>
    const initialData = <?= json_encode($initialData) ?>;
    const initialDataTotal = initialData.recordsTotal || initialData.length || 0;
    $(document).ready(function() {

        let actionTemplate = `
        <a data-target="#editmyModal" onclick="get(key:id)" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>
        <a class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="delete_recordByIdReload('<?= $api_base_url ?>setup-findings-finding/key:id', '<?php echo $this->lang->line('delete_confirm'); ?>')">
            <i class="fa fa-trash"></i>
        </a>
    `;
        initializeTable(initialData, initialDataTotal, `${base_url}admin/finding/getfindinglist`, '#ajaxlist', [
                'sno', 'name', 'finding_category', 'description', 'action'
            ],
            actionTemplate,
            'id'
        );
    });
</script>