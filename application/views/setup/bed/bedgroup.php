<?php
$data = $this->session->userdata('hospitaladmin');
$api_base_url = $this->config->item('api_base_url');
?>
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
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('bed_group_list'); ?></h3>
                        <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('bed_group', 'can_add')) { ?>
                            <a data-toggle="modal" data-target="#myModal" class="btn btn-primary btn-sm bedgroup"><i
                                    class="fa fa-plus"></i> <?php echo $this->lang->line('add_bed_group'); ?></a>
                            <?php } ?>
                        </div><!-- /.box-tools -->
                    </div>

                    <div class="box-body">
                        <div class="download_label"><?php echo $this->lang->line('bed_group_list'); ?></div>
                        <div class="table-responsive mailbox-messages">
                            <table class="table table-striped table-bordered table-hover" id="ajaxlist">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th><?php echo $this->lang->line('name'); ?></th>
                                        <th><?php echo $this->lang->line('floor'); ?></th>
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
            </div>
            <!--/.col (left) -->
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
                <h4 class="modal-title"><?php echo $this->lang->line('add_bed_group'); ?></h4>
            </div>
            <form id="addbedgroup" class="ptt10" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                <div class="modal-body pt0 pb0">
                    <div class="" id="edit_expensedata">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('name'); ?></label>
                                    <span class="req"> *</span>
                                    <input name="name" placeholder="" type="text" class="form-control"
                                        value="<?php echo set_value('invoice_no'); ?>" />
                                    <span class="text-danger name"></span>

                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('floor'); ?></label>
                                    <span class="req"> *</span>
                                    <select name="floor" class="form-control">
                                        <option value=""><?php echo $this->lang->line('select') ?></option>
                                        <?php foreach ($floor as $key => $floorvalue) {
                                        ?>
                                        <option value="<?php echo $floorvalue["id"] ?>">
                                            <?php echo $floorvalue["name"] ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="text-danger floor"></span>

                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('color'); ?></label>

                                    <input name="color" value="#f4f4f4" placeholder="" type="color" class="form-control"
                                        value="" />


                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('description'); ?></label>
                                    <textarea class="form-control" name="description" placeholder=""
                                        rows="2"><?php echo set_value('description'); ?><?php echo set_value('description') ?></textarea>
                                    <span class="text-danger description"></span>

                                </div>
                            </div>
                        </div><!-- /.box-body -->
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="pull-right">
                        <button type="submit" data-loading-text="<?php echo $this->lang->line('processing'); ?>"
                            id="addwardbtn" class="btn btn-info"><i class="fa fa-check-circle"></i>
                            <?php echo $this->lang->line('save'); ?></button>
                    </div>
                </div>


            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="myeditModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm400" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('edit_bed_group'); ?></h4>
            </div>
            <form id="editbedgroup" class="ptt10" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                <div class="modal-body pt0 pb0">
                    <div class="row">

                        <div class="col-sm-12">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('name'); ?></label>
                                <span class="req"> *</span>
                                <input id="name" name="name" placeholder="" type="text" class="form-control"
                                    value="<?php echo set_value('name'); ?>" />
                                <input type="hidden" id="id" name="id">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('floor'); ?></label>
                                <span class="req"> *</span>
                                <select name="floor" id="floor" class="form-control">
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    <?php foreach ($floor as $key => $floorvalue) {
                                    ?>
                                    <option value="<?php echo $floorvalue["id"] ?>"><?php echo $floorvalue["name"] ?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('color'); ?></label>

                                <input name="color" id="color" placeholder="" type="color" class="form-control"
                                    value="" />


                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('description'); ?></label>
                                <textarea class="form-control" id="description" name="description" placeholder=""
                                    rows="2"><?php echo set_value('description'); ?><?php echo set_value('description') ?></textarea>

                            </div>
                        </div>
                    </div><!-- /.box-body -->
                </div>
                <div class="modal-footer">
                    <div class="pull-right">
                        <button type="submit" id="editbedgroupbtn"
                            data-loading-text="<?php echo $this->lang->line('processing'); ?>" class="btn btn-info"><i
                                class="fa fa-check-circle"></i> <?php echo $this->lang->line('save'); ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
$('#editbedgroup').on('submit', function(e) {
    e.preventDefault();
    var formData = {
        name: $('#name').val(),
        floor: $('#floor').val(),
        color: $('#color').val(),
        description: $('#description').val(),
        Hospital_id: <?= $data['hospital_id'] ?>
    };
    let id = $('#id').val();
    var requiredFields = [{
            field: "name",
            pattern: /^[a-zA-Z0-9\s]+$/,
            error: "Name must contain only letters, numbers, and spaces"
        },
        {
            field: "floor",
            pattern: /^[0-9]+$/,
            error: "Floor must be a valid number"
        }
    ];
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
    sendAjaxRequest('<?= $api_base_url ?>setup-bed-bed-group/' + id, 'PATCH', formData, function(response) {
        handleResponse(response);
    });
});
</script>
<script>
$(document).ready(function() {
    $('#addbedgroup').on('submit', function(e) {
        e.preventDefault();
        var formData = {
            name: $('input[name=name]').val(),
            floor: $('select[name=floor]').val(),
            color: $('input[name=color]').val(),
            description: $('textarea[name=description]').val(),
            is_active: 0,
            Hospital_id: <?= $data['hospital_id'] ?>
        };
        var requiredFields = [{
                field: "name",
                pattern: /^[a-zA-Z0-9\s]+$/,
                error: "Name must contain only letters, numbers, and spaces"
            },
            {
                field: "floor",
                pattern: /^[0-9]+$/,
                error: "Floor must be a valid number"
            }
        ];
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
        sendAjaxRequest('<?= $api_base_url ?>setup-bed-bed-group/', 'POST', formData, function(
            response) {
            handleResponse(response);
        });
    });
});
</script>
<script>
function edit(id) {
    $('#myeditModal').modal('show');
    $.ajax({
        url: '<?php echo base_url(); ?>admin/setup/bedgroup/getbedgroupdata/' + id,
        dataType: 'json',
        success: function(data) {
            console.log(data);
            $('#id').val(data.id);
            $('#name').val(data.name);
            $('#floor').val(data.floor);
            $('#color').val(data.color);
            $('#description').val(data.description);
        }
    });
}
$(".bedgroup").click(function() {
    $('#addward').trigger("reset");
});
$(document).ready(function(e) {
    $('#myModal,#myeditModal').modal({
        backdrop: 'static',
        keyboard: false,
        show: false
    });
});
</script>
<script>
function delete_bedgroup(id) {
    if (confirm("Are you sure you want to delete this Bet Group?")) {
        sendAjaxRequest('<?= $api_base_url ?>setup-bed-bed-group/' + id + '?Hospital_id=<?= $data['hospital_id'] ?>',
            'DELETE', {}, function(response) {
                handleResponse(response);
            });
    }
}
</script>
<script>
const initialData = <?= json_encode($initialData) ?>;
const initialDataTotal = initialData.recordsTotal || initialData.length || 0;

$(document).ready(function() {
    let actionTemplate = `
            <a data-target="#myeditModal" onclick="edit(key:id)" class="btn btn-default btn-xs" data-toggle="tooltip" title="" data-original-title="Edit">
                <i class="fa fa-pencil"></i>
            </a>
            <a class="btn btn-default btn-xs" data-toggle="tooltip" title="" onclick="delete_bedgroup(key:id)" data-original-title="Delete">
                <i class="fa fa-trash"></i>
            </a>
        `;

    initializeTable(
        initialData,
        initialDataTotal,
        `${base_url}admin/setup/bedgroup/getbedGroup`,
        '#ajaxlist',
        ['sno', 'name', 'floor_name', 'description', 'action'],
        actionTemplate,
        'id'
    );
});
</script>