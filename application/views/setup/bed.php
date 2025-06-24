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
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('bed_list'); ?></h3>
                        <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('bed', 'can_add')) { ?>
                                <a data-toggle="modal" data-target="#myModal" class="btn btn-primary btn-sm addbed"><i
                                        class="fa fa-plus"></i> <?php echo $this->lang->line('add_bed'); ?></a>
                            <?php } ?>
                        </div><!-- /.box-tools -->
                    </div>
                    <div class="box-body">
                        <div class="download_label"><?php echo $this->lang->line('bed_list'); ?></div>
                        <div class="table-responsive mailbox-messages">
                            <table class="table table-striped table-bordered table-hover" id="ajaxlist">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th><?php echo $this->lang->line('name'); ?></th>
                                        <th><?php echo $this->lang->line('bed_type'); ?></th>
                                        <th><?php echo $this->lang->line('bed_group'); ?></th>
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
                <h4 class="modal-title"><?php echo $this->lang->line('add_bed'); ?></h4>
            </div>
            <form id="addbed" class="ptt10" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                <div class="modal-body pt0 pb0">
                    <div class="row" id="edit_bedtypedata">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('name'); ?></label>
                                <span class="req"> *</span>
                                <input name="name" placeholder="" type="text" class="form-control"
                                    value="<?php echo set_value('name'); ?>" />

                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('bed_type'); ?></label>
                                <span class="req"> *</span>
                                <select name="bed_type" id="bed_type" class="form-control">
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    <?php foreach ($bedtype_list as $value) { ?>
                                        <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>

                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('bed_group'); ?></label><span class="req"> *</span>
                                <select name="bed_group" id="bed_group" class="form-control">
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    <?php foreach ($bedgroup_list as $bedg) { ?>
                                        <option value="<?php echo $bedg['id']; ?>">
                                            <?php echo $bedg['name'] . " - " . $bedg['floor_name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="pull-right">
                        <button type="submit" id="addbedbtn"
                            data-loading-text="<?php echo $this->lang->line('processing'); ?>" class="btn btn-info"><i
                                class="fa fa-check-circle"></i> <?php echo $this->lang->line('save'); ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
</div>
</div>
<div class="modal fade" id="myModalEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm400" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('edit_bed'); ?></h4>
            </div>

            <form id="editbed" class="ptt10" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                <div class="modal-body pt0 pb0">
                    <div class="row" id="edit_bedtypedata">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('name'); ?></label>
                                <span class="req"> *</span>
                                <input id="name" name="name" placeholder="" type="text" class="form-control"
                                    value="<?php echo set_value('name'); ?>" />
                                <input id="bedid" name="bedid" placeholder="" type="hidden" class="form-control" />
                                <input id="bedstatus" name="bedstatus" placeholder="" type="hidden"
                                    class="form-control" />
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('bed_type'); ?></label><span class="req"> *</span>
                                <select name="bed_type" id="bedtype" class="form-control">
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    <?php foreach ($bedtype_list as $value) { ?>
                                        <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>

                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('bed_group'); ?></label><span class="req"> *</span>
                                <select name="bed_group" id="bedgroup" class="form-control">
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    <?php foreach ($bedgroup_list as $value) { ?>
                                        <option value="<?php echo $value['id']; ?>">
                                            <?php echo $value['name'] . " - " . $value['floor_name']; ?></option>

                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <!-- <div class="col-sm-12">
                                <div class="form-group">                                   
                                  <label class="checkbox-inline">
                            <input id="mark_as_unused" type="checkbox" name="mark_as_unused" > <?php echo $this->lang->line('mark_as_unused'); ?>                                                     </label>
                                </div>
                            </div> -->
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="pull-right">
                        <button type="submit" data-loading-text="<?php echo $this->lang->line('processing'); ?>"
                            id="editbedbtn" class="btn btn-info "><i class="fa fa-check-circle"></i>
                            <?php echo $this->lang->line('save'); ?></button>
                    </div>
                </div>
            </form>
        </div>
        </form>
    </div>
</div>
<?php
$data = $this->session->userdata('hospitaladmin');
$api_base_url = $this->config->item('api_base_url');
?>
<script>
    $(document).ready(function() {
        $('#addbed').on('submit', function(e) {
            e.preventDefault();
            var formData = {
                'name': $('input[name=name]').val(),
                'bed_type_id': $('select[name=bed_type]').val(),
                'bed_group_id': $('select[name=bed_group]').val(),
                'is_active': 'yes',
                'Hospital_id': '<?= $data['hospital_id'] ?>'
            };
            var requiredFields = [
                {
                    field: 'name',
                    pattern: /^[a-zA-Z0-9\s]+$/,
                    error: 'Name must contain only letters, numbers, and spaces'
                },
                {
                    field: 'bed_type_id',
                    error: 'Bed Type is required'
                },
                {
                    field: 'bed_group_id',
                    error: 'Bed Group is required'
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
            sendAjaxRequest('<?= $api_base_url ?>setup-bed-bed', 'POST', formData, function(response) {
                handleResponse(response);
            });
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#editbed').on('submit', function(e) {
            e.preventDefault();
            var formData = {
                'name': $('#name').val(),
                'bed_type_id': $('#bedtype').val(),
                'bed_group_id': $('#bedgroup').val(),
                'is_active': $('#mark_as_unused').is(':checked') ? 'No' : 'yes',
                'Hospital_id': '<?= $data['hospital_id'] ?>'
            };
            var requiredFields = [
                {
                    field: 'name',
                    pattern: /^[a-zA-Z0-9\s]+$/,
                    error: 'Name must contain only letters, numbers, and spaces'
                },
                {
                    field: 'bed_type_id',
                    error: 'Bed Type is required'
                },
                {
                    field: 'bed_group_id',
                    error: 'Bed Group is required'
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
            let id = $('#bedid').val();
            sendAjaxRequest('<?= $api_base_url ?>setup-bed-bed/' + id, 'PATCH', formData, function(response) {
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
    });

    function getbedcat(cate) {
        $.ajax({
            url: '<?php echo base_url() ?>admin/setup/bed/getbed_categore_type/' + cate,
            success: function(data) {
                $('#bed_categore_type').html(data);

            }
        });
    }

    function getRecord(id) {
        $('#myModalEdit').modal('show');
        $.ajax({
            url: '<?php echo base_url(); ?>admin/setup/bed/get/' + id,
            type: "POST",
            dataType: "json",
            success: function(data) {
                $("#name").val(data.name);
                $("#bedid").val(id);
                $("#bedstatus").val(data.is_active);
                $("#bedtype").val(data.bed_type_id);
                $("#bedgroup").val(data.bed_group_id);
                if (data.is_active == 'unused') {
                    $('#mark_as_unused').attr('checked', 'checked');
                }
            },
            error: function() {
                alert("Fail")
            }

        })
    }

    $(document).ready(function(e) {
        $('#myModal,#myModalEdit').modal({
            backdrop: 'static',
            keyboard: false,
            show: false
        });
    });

    $(".addbed").click(function() {
        $('#addbed').trigger("reset");
    });
</script>
<script>
    function delete_bed(id) {
        var conf = confirm("Are you sure you want to delete this bed?");
        if (conf) {
            sendAjaxRequest('<?= $api_base_url ?>setup-bed-bed/' + id + '?Hospital_id=<?= $data['hospital_id'] ?>', 'DELETE', {}, function(response) {
                handleResponse(response);
            });
        } else {
            return false;
        }
    }
</script>
<script>
    const initialData = <?= json_encode($initialData) ?>;
    const initialDataTotal = initialData.recordsTotal || initialData.length || 0;
    $(document).ready(function() {
        let actionTemplate = `
<a href="#" onclick="getRecord(key:id)" class="btn btn-default btn-xs" data-target="#myModalEdit" data-toggle="tooltip" title="" data-original-title="Edit">
                <i class="fa fa-pencil"></i>
            </a>
<a class="btn btn-default btn-xs" data-toggle="tooltip" title="" onclick="delete_bed(key:id)" data-original-title="Delete">
                <i class="fa fa-trash"></i>
            </a>
    `;
        initializeTable(initialData, initialDataTotal, `${base_url}admin/setup/bed/getbed`, '#ajaxlist', ['sno',
                'name', 'Bed_Type', 'bed_group', 'action'
            ],
            actionTemplate,
            'id'
        );
    });
</script>