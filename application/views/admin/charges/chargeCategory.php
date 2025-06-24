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
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('charge_category_list'); ?></h3>
                        <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('charge_category', 'can_add')) { ?>
                                <a data-toggle="modal" data-target="#myModal" id="add_charge_type_modal"
                                    class="btn btn-primary btn-sm charge_category"><i class="fa fa-plus"></i>
                                    <?php echo $this->lang->line('add_charge_category'); ?></a>
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
                                        <th><?php echo $this->lang->line('charge_type'); ?></th>
                                        <th class=""><?php echo $this->lang->line('description'); ?></th>
                                        <th class="noExport"><?php echo $this->lang->line('action'); ?></th>

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
                <h4 class="modal-title">
                    <div id="modal_title"></div>
                </h4>
            </div>
            <form id="formadd" action="" id="employeeform" name="employeeform" method="post" accept-charset="utf-8"
                class="ptt10">
                <div class="modal-body pt0 pb0">
                    <input type="hidden" name="id" value="0" class="id" id='editid'>
                    <div class="form-group">
                        <label for="pwd"><?php echo $this->lang->line('charge_type'); ?></label>
                        <small class="req"> *</small>
                        <select name="charge_type" class="form-control charge_type">

                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                            <?php foreach ($charge_type as $charge_key => $charge_value) {
                            ?>
                                <option value="<?php echo $charge_value->id; ?>"><?php echo $charge_value->charge_type; ?>
                                </option>
                            <?php } ?>
                        </select>
                        <span class="text-danger"><?php echo form_error('charge_type'); ?></span>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo $this->lang->line('name'); ?></label><small
                            class="req"> *</small>
                        <input autofocus="" id="name" name="name" type="text" class="form-control name" value=""
                            maxlength="15" />
                        <span class="text-danger"><?php echo form_error('name'); ?></span>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label><small
                            class="req"> *</small>
                        <textarea name="description" class="form-control description"></textarea>
                        <span class="text-danger"><?php echo form_error('description'); ?></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="formaddbtn"
                        data-loading-text="<?php echo $this->lang->line('processing'); ?>"
                        class="btn btn-info pull-right"><i class="fa fa-check-circle"></i>
                        <?php echo $this->lang->line('save'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).on('click', '.edit_record', function() {
        var record_id = $(this).data('recordId');
        var btn = $(this);
        $.ajax({
            url: base_url + 'admin/chargecategory/get_data',
            type: "POST",
            data: {
                id: record_id
            },
            dataType: 'json',
            beforeSend: function() {
                btn.button('loading');
            },
            success: function(data) {
                if (data.status == 0) {
                    errorMsg(message);
                } else {
                    $('.id').val(data.result.id);
                    $('.name').val(data.result.name);
                    $('.description').val(data.result.description);
                    $('.charge_type option[value="' + data.result.charge_type_id + '"]').prop(
                        'selected', true);
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
</script>
<script type="text/javascript">
    $('#myModal').on('hidden.bs.modal', function(e) {
        $('#formadd').find('input:text').val('');
        $('#formadd').find('.description').val('');
        $('.charge_type option:selected').prop('selected', false);
        $("#formadd").find('input.id').val(0);
    });
    $('#add_charge_type_modal').click(function() {
        $('#modal_title').empty();
        $('#modal_title').append('<?php echo $this->lang->line('add_charge_category'); ?>');
    })

    $(document).on('click', '.edit_charge_modal', function() {
        $('#modal_title').empty();
        $('#modal_title').append('<?php echo $this->lang->line('edit_charge_category'); ?>');
    })

    $(document).ready(function(e) {
        $('#myModal').modal({
            backdrop: 'static',
            keyboard: false,
            show: false
        });
    });
</script>
<?php
$data = $this->session->userdata('hospitaladmin');
$api_base_url = $this->config->item('api_base_url');
?>
<script>
$(document).ready(function () {
    $('#formadd').submit(async function (e) {
        e.preventDefault();
        const editid = $('#editid').val();
        const formData = {
            id: $('input[name="id"]').val(),
            charge_type_id: $('select[name="charge_type"]').val(),
            name: $('input[name="name"]').val(),
            description: $('textarea[name="description"]').val(),
            is_default: "yes",
            Hospital_id: '<?= $data['hospital_id'] ?>'
        };
        let errors = [];
        if (!formData.name) errors.push('Name is required');
        if (!formData.charge_type_id) errors.push('Charge Type is required');
        if (!formData.description) errors.push('Description is required');
        if (errors.length > 0) {
            errorMsg(errors.join('<br>'));
            return;
        }
        let apiurl = '<?= $api_base_url ?>setup-hospital-charges-charge-category';
        let method = 'POST';
        if (editid != 0) {
            apiurl += '/' + editid;
            method = 'PATCH';
        }       
       sendAjaxRequest(apiurl, method, formData, function (response) {
           handleResponse(response);
        }, function (error) {
            errorMsg(error.responseJSON.message || 'An error occurred');
        });
    });
});
</script>
<script>
    const initialData = <?= json_encode($initialData) ?>;
    const initialDataTotal = initialData.recordsTotal || initialData.length || 0;

    $(document).ready(function() {
        let actionTemplate = `
        <a href="javascript:void(0)" class="btn btn-default btn-xs edit_record edit_charge_modal" 
           data-loading-text="Please Wait..." 
           data-toggle="tooltip" 
           data-record-id="key:id" 
           title="Edit">
           <i class="fa fa-pencil"></i>
        </a>
        <a class='btn btn-default btn-xs' data-toggle='tooltip' title='' onclick='delete_recordById("<?= $api_base_url ?>setup-hospital-charges-charge-category/key:id?Hospital_id=<?= $data['hospital_id'] ?>", "<?= $this->lang->line('delete_message') ?>")' data-original-title='<?= $this->lang->line('delete') ?>'> 
            <i class='fa fa-trash'></i>
        </a>
        `;
        initializeTable(
            initialData,
            initialDataTotal,
            `${base_url}admin/chargecategory/chargecategory`,
            '#ajaxlist',
            ['sno', 'name', 'charge_type', 'description', 'action'],
            actionTemplate,
            'id'
        );
    });
</script>


<!-- //========datatable end===== -->