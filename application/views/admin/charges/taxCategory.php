<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-2">
                <div class="box border0">
                    <?php $this->load->view("admin/charges/sidebar"); ?>
                </div>
            </div>
            <div class="col-md-10">
                <div class="box box-primary" id="tachelist">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('tax_category_list'); ?></h3>
                        <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('tax_category', 'can_add')) { ?>
                                <a onclick="add()" class="btn btn-primary btn-sm charge_type"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add_tax_category'); ?></a>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="mailbox-controls">
                        </div>
                        <div class="table-responsive mailbox-messages">
                            <table class="table table-striped table-bordered table-hover" id="ajaxlist">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th><?php echo $this->lang->line('name'); ?></th>
                                        <th><?php echo $this->lang->line('percentage'); ?>(%)</th>
                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
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
                <h4 class="modal-title" id="title"><?php ?></h4>
            </div>
            <form id="formadd" action="" id="employeeform" name="employeeform" method="post" accept-charset="utf-8" class="ptt10">
                <div class="modal-body pt0 pb0">
                    <div class="form-group">
                        <label><?php echo $this->lang->line('name'); ?></label><small class="req"> *</small>
                        <input autofocus="" id="name" name="name" type="text" class="form-control" maxlength="15" />
                        <input autofocus="" id="id" name="id" type="hidden" class="form-control" />
                        <span class="text-danger"><?php echo form_error('name'); ?></span>
                    </div>
                    <div class="form-group">
                        <label><?php echo $this->lang->line('percentage'); ?></label><small class="req"> *</small>
                        <div class="input-group">


                            <input type="text" class="form-control right-border-none" name="percentage" id="percentage" autocomplete="off" maxlength="15">
                            <span class="input-group-addon "> %</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="formaddbtn" data-loading-text="<?php echo $this->lang->line('processing'); ?>" class="btn btn-info pull-right"><i class="fa fa-check-circle"></i> <?php echo $this->lang->line('save'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
$data = $this->session->userdata('hospitaladmin');
$api_base_url = $this->config->item('api_base_url');
?>
<script>
    function add() {
        $('#title').html('<?php echo $this->lang->line('add_tax_category'); ?>');
        $("#formadd").trigger('reset');
        $('#myModal').modal('show');
    }
    $(document).on('click', '.edit_record', function() {
        var record_id = $(this).data('recordId');
        var btn = $(this);
        $.ajax({
            url: base_url + 'admin/taxcategory/getDetails',
            type: "POST",
            data: {
                tax_id: record_id
            },
            dataType: 'json',
            beforeSend: function() {
                btn.button('loading');
            },
            success: function(data) {
                if (data.status == 0) {

                    errorMsg(message);
                } else {
                    $('#title').html('<?php echo $this->lang->line('edit_tax_category'); ?>');
                    $('#id').val(data.id);
                    $('#name').val(data.name);
                    $('#percentage').val(data.percentage);
                    $('#myModal').modal('show');
                }
                btn.button('reset');
            },
            error: function() {
                btn.button('reset');
            },
            complete: function() {
                btn.button('reset');
            }
        });

    });

    $(document).ready(function(e) {
        $('#myModal').modal({
            backdrop: 'static',
            keyboard: false,
            show: false
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#formadd').submit(function(e) {
            e.preventDefault();
            try {
                const name = $('#name').val().trim();
                const hospital_id = <?= $data['hospital_id'] ?>;
                const percentage = $('#percentage').val().trim();
                const id = $('#id').val();
                if (!name) {
                    errorMsg('Name is required');
                    return;
                }
                if (!percentage || !/^\d+(\.\d{1,2})?$/.test(percentage) || parseFloat(percentage) < 0 || parseFloat(percentage) > 100) {
                    errorMsg('Percentage must be a valid number between 0 and 100');
                    return;
                }
                let method, apiurl;
                if (id) {
                    apiurl = '<?= $api_base_url ?>setup-hospital-charges-tax-category/' + id;
                    method = 'PATCH';
                } else {
                    apiurl = '<?= $api_base_url ?>setup-hospital-charges-tax-category';
                    method = 'POST';
                }
                let data = {
                    name: name,
                    Hospital_id: hospital_id,
                    percentage: percentage
                };
                sendAjaxRequest(apiurl,method,data,function(response) {
                   handleResponse(response);
                });
            } catch (err) {
                console.error('Submit handler error:', err);
                errorMsg('An unexpected error occurred.');
            }
        });
    });
</script>
<script>
    const initialData = <?= json_encode($initialData) ?>;
    const initialDataTotal = initialData.recordsTotal || initialData.length || 0;
    $(document).ready(function() {
        let actionTemplate = `
        <a href="javascript:void(0)" class="btn btn-default btn-xs edit_record" data-loading-text="<?= $this->lang->line('please_wait') ?>" data-toggle="tooltip" data-record-id="key:id" title="<?= $this->lang->line('edit') ?>">
            <i class="fa fa-pencil"></i>
        </a>
        <a class="btn btn-default btn-xs" data-toggle="tooltip" title="<?= $this->lang->line('delete') ?>" onclick="delete_recordById('<?= $api_base_url ?>setup-hospital-charges-tax-category/key:id?Hospital_id=<?= $data['hospital_id'] ?>')">
            <i class="fa fa-trash"></i>
        </a>
    `;
        initializeTable(
            initialData,
            initialDataTotal,
            `${base_url}admin/taxcategory/taxcategory`,
            '#ajaxlist',
            ['sno', 'name', 'percentage', 'action'],
            actionTemplate,
            'id'
        );
    });
</script>