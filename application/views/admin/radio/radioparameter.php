<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-2">
                <div class="box border0">
                    <ul class="tablists">
                        <?php if ($this->rbac->hasPrivilege('radiology_category', 'can_view')) { ?>
                        <li><a
                                href="<?php echo base_url(); ?>admin/lab/addlab"><?php echo $this->lang->line('radiology_category'); ?></a>
                        </li>
                        <?php } if ($this->rbac->hasPrivilege('radiology_unit', 'can_view')) { ?>
                        <li><a href="<?php echo base_url(); ?>admin/lab/unit"
                                class=""><?php echo $this->lang->line('unit'); ?></a></li>
                        <?php } if ($this->rbac->hasPrivilege('radiology_parameter', 'can_view')) { ?>
                        <li><a class="active" href="<?php echo base_url(); ?>admin/lab/radioparameter"
                                class=""><?php echo $this->lang->line('radiology_parameter'); ?></a></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
            <div class="col-md-10">
                <div class="box box-primary" id="tachelist">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('radiology_parameter_list'); ?></h3>
                        <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('radiology_parameter', 'can_add')) { ?>
                            <a data-toggle="modal" data-target="#myModal" class="btn btn-primary btn-sm radiology"><i
                                    class="fa fa-plus"></i>
                                <?php echo $this->lang->line('add_radiology_parameter'); ?></a>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="mailbox-controls">
                        </div>
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label"><?php echo $this->lang->line('radiology_parameter_list'); ?>
                            </div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('parameter_name'); ?></th>
                                        <th><?php echo $this->lang->line('reference_range'); ?></th>
                                        <th><?php echo $this->lang->line('unit'); ?></th>
                                        <th><?php echo $this->lang->line('description'); ?></th>
                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = 1;
                                    foreach ($parameterName as $parameter) {
                                        ?>
                                    <tr>
                                        <td><?php echo $parameter['parameter_name']; ?></td>
                                        <td><?php echo $parameter['reference_range']; ?></td>
                                        <td><?php echo $parameter['unit_name']; ?></td>
                                        <td><?php echo $parameter['description']; ?></td>
                                        <td class="text-right">
                                            <?php if ($this->rbac->hasPrivilege('radiology_parameter', 'can_edit')) { ?><a
                                                data-target="#editmyModal" onclick="get(<?php echo $parameter['id'] ?>)"
                                                class="btn btn-default btn-xs" data-toggle="tooltip"
                                                title="<?php echo $this->lang->line('edit'); ?>">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                            <?php } ?>
                                            <?php if ($this->rbac->hasPrivilege('radiology_parameter', 'can_delete')) { ?>
                                            <a href="javascript:void(0);" class="btn btn-default btn-xs"
                                                data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>"
                                                onclick="delete_lab_parameter('<?php echo $parameter['id']; ?>', '<?php echo $this->lang->line('delete_confirm'); ?>')">
                                                <i class="fa fa-trash"></i>
                                            </a>



                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <?php
                                        $count++;
                                    }
                                    ?>
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
                <h4 class="modal-title"><?php echo $this->lang->line('add_radiology_parameter'); ?></h4>
            </div>
            <form id="radioparameteradd" method="post" accept-charset="utf-8">
                <div class="modal-body pb0">
                    <div>
                        <div class="form-group">
                            <label><?php echo $this->lang->line('parameter_name'); ?></label><small class="req">
                                *</small>
                            <input id="parameter_name" name="parameter_name" placeholder="" type="text"
                                class="form-control" required
                                onkeyup="this.value=this.value.replace(/[^a-zA-Z]/g, '').trim()" />
                            <span class="text-danger"><?php echo form_error('parameter_name'); ?></span>
                        </div>

                        <div class="form-group">
                            <label><?php echo $this->lang->line('reference_range'); ?></label><small class="req">
                                *</small>
                            <input name="reference_range" id="reference_range" placeholder="" type="text"
                                class="form-control" required
                                onkeyup="this.value=this.value.replace(/[^0-9.-]/g, '').trim()" />
                            <span class="text-danger"><?php echo form_error('reference_range'); ?></span>
                        </div>

                        <div class="form-group">
                            <label><?php echo $this->lang->line('unit'); ?></label><small class="req"> *</small>
                            <select name="unit" class="form-control" id="unit" required>
                                <option value=""><?php echo $this->lang->line('select') ?></option>
                                <?php foreach ($unitname as $value) { ?>
                                <option value="<?php echo $value['id'] ?>"><?php echo $value['unit_name']; ?></option>
                                <?php } ?>
                            </select>
                            <span class="text-danger"><?php echo form_error('unit'); ?></span>
                        </div>

                        <div class="form-group">
                            <label><?php echo $this->lang->line('description'); ?></label>
                            <input name="description" id="description" placeholder="" type="text" class="form-control"
                                onkeyup="this.value=this.value.replace(/[^a-zA-Z0-9,. ]/g, '').trim()" />
                            <span class="text-danger"><?php echo form_error('description'); ?></span>
                        </div>
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
        <!--./row-->
    </div>
</div>
<?php
$data = $this->session->userdata('hospitaladmin');
$api_base_url = $this->config->item('api_base_url');
?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#radioparameteradd').on('submit', function(e) {
        e.preventDefault();

        var errorMessages = [];
        var parameterName = $('#parameter_name').val();
        var referenceRange = $('#reference_range').val();
        var unit = $('#unit').val();
        var description = $('#description').val();

        if (!parameterName) {
            errorMessages.push("Parameter name is required.");
        }

        if (!referenceRange) {
            errorMessages.push("Reference range is required.");
        }

        if (!unit) {
            errorMessages.push("Unit is required.");
        }

        // if (!description) {
        //     errorMessages.push("Description is required.");
        // }

        var unitPattern = /^[a-zA-Z0-9\s]+$/;
        var descriptionPattern = /^[a-zA-Z0-9\s,.'-]+$/;

        if (unit && !unitPattern.test(unit)) {
            errorMessages.push("Unit field contains invalid characters.");
        }

        // if (description && !descriptionPattern.test(description)) {
        //     errorMessages.push("Description field contains invalid characters.");
        // }

        if (errorMessages.length > 0) {
            errorMsg(errorMessages.join('<br>'));
            return;
        }

        var formData = {
            parameter_name: parameterName,
            reference_range: referenceRange,
            unit: unit,
            description: description,
            test_value: "",
            gender: "",
            Hospital_id: '<?=$data['hospital_id']?>'
        };

        $.ajax({
            url: '<?=$api_base_url?>setup-radiology-radiology-parameter',
            type: 'POST',
            data: formData,
            dataType: 'json',
            beforeSend: function() {
                $('#formaddbtn').button('loading');
            },
            success: function(response) {
                $('#formaddbtn').button('reset');
                successMsg('Parameter added successfully!');
                location.reload();
            },
            error: function(xhr, status, error) {
                $('#formaddbtn').button('reset');
                errorMsg('An error occurred while submitting the form.');
            }
        });
    });
});
</script>
<script>
function delete_lab_parameter(parameterId, confirmMessage) {
    if (confirm(confirmMessage)) {
        $.ajax({
            url: '<?=$api_base_url?>setup-radiology-radiology-parameter/' + parameterId,
            type: 'DELETE',
            success: function(response) {
                let message = Array.isArray(response) ? response[0]?.message : response.message ||
                    "Deleted Successfully";
                successMsg(message);
                // Optionally, reload the page or remove the deleted item from the DOM
                location.reload();
            },
            error: function(xhr, status, error) {
                alert('An error occurred while deleting the parameter.');
            }
        });
    }
}
</script>
<div class="modal fade" id="editmyModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('edit_radiology_parameter'); ?></h4>
            </div>

            <form id="editformadd" name="employeeform" method="post" accept-charset="utf-8"
                enctype="multipart/form-data">
                <div class="modal-body pb0">
                    <div class="">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('parameter_name'); //$this->lang->line('name');    ?></label><small
                                class="req"> *</small>
                            <input autofocus="" id="edit_parameter_name" name="parameter_name" placeholder=""
                                type="text" class="form-control" value="" />
                            <span class="text-danger"><?php echo form_error('parameter_name'); ?></span>
                            <input type="hidden" id="edit_id" name="parameter_id">

                        </div>
                        <div class="form-group">
                            <label><?php echo $this->lang->line('reference_range'); //$this->lang->line('name');    ?></label><small
                                class="req"> *</small>
                            <input autofocus="" id="edit_reference_range" name="test_value" placeholder="" type="text"
                                class="form-control" value="" />
                            <span class="text-danger"><?php echo form_error('reference_range'); ?></span>
                        </div>
                        <div class="form-group">
                            <label><?php echo $this->lang->line('unit'); ?></label><small class="req"> *</small>

                            <select name="unit" id="unit" onchange="" class="form-control">
                                <option value=""><?php echo $this->lang->line('select') ?></option>
                                <?php foreach ($unitname as $value) {
                                                ?>
                                <option value="<?php echo $value['id'] ?>"><?php echo $value['unit_name']; ?></option>
                                <?php } ?>
                            </select>
                            <span class="text-danger"><?php echo form_error('unit'); ?></span>
                        </div>
                        <div class="form-group">
                            <label><?php echo $this->lang->line('description');   ?></label>
                            <input autofocus="" id="edit_description" name="description" placeholder="" type="text"
                                class="form-control" value="" />
                            <span class="text-danger"><?php echo form_error('description'); ?></span>
                        </div>

                    </div>
                </div>
                <!--./modal-->
                <div class="modal-footer">
                    <button type="submit" id="editformaddbtn"
                        data-loading-text="<?php echo $this->lang->line('processing'); ?>"
                        class="btn btn-info pull-right"><i class="fa fa-check-circle"></i>
                        <?php echo $this->lang->line('save'); ?></button>
                </div>
            </form>
        </div>
        <!--./row-->
    </div>
</div>
<?php
$data = $this->session->userdata('hospitaladmin');
$api_base_url = $this->config->item('api_base_url');
?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    $('#editformadd').on('submit', function(e) {
        e.preventDefault();

        var errorMessages = [];
        var parameterName = $('#edit_parameter_name').val();
        var referenceRange = $('#edit_reference_range').val();
        var unit = $('#unit').val();
        var description = $('#edit_description').val();

        if (!parameterName) {
            errorMessages.push("Parameter name is required.");
        }

        if (!referenceRange) {
            errorMessages.push("Reference range is required.");
        }

        if (!unit) {
            errorMessages.push("Unit is required.");
        }

        // if (!description) {
        //     errorMessages.push("Description is required.");
        // }

        var unitPattern = /^[a-zA-Z0-9\s]+$/;
        var descriptionPattern = /^[a-zA-Z0-9\s,.'-]+$/;

        if (unit && !unitPattern.test(unit)) {
            errorMessages.push("Unit field contains invalid characters.");
        }

        // if (description && !descriptionPattern.test(description)) {
        //     errorMessages.push("Description field contains invalid characters.");
        // }

        if (errorMessages.length > 0) {
            errorMsg(errorMessages.join('<br>'));
            return;
        }

        var formData = {
            parameter_name: parameterName,
            reference_range: referenceRange,
            unit: unit,
            description: description,
            gender: "",
            test_value: "",
            Hospital_id: '<?=$data['hospital_id']?>'
        };

        let parameterId = $('#edit_id').val();

        $.ajax({
            url: '<?=$api_base_url?>setup-radiology-radiology-parameter/' + parameterId,
            type: 'PATCH',
            data: formData,
            dataType: 'json',
            beforeSend: function() {
                $('#editformaddbtn').button('loading');
            },
            success: function(response) {
                $('#editformaddbtn').button('reset');
                let message = response[0]?. ['data ']?.messege || 'Default success message';
                successMsg(message);
                location.reload();
            },
            error: function() {
                $('#editformaddbtn').button('reset');
                errorMsg('An error occurred while updating the data.');
            }
        });
    });
});



function get(id) {
    $('#editmyModal').modal('show');
    $.ajax({
        dataType: 'json',
        url: '<?php echo base_url(); ?>admin/lab/get_parameterdata/' + id,
        success: function(result) {
            console.log(result.unit);
            $('#edit_id').val(result.id);
            $('#edit_parameter_name').val(result.parameter_name);
            $('#edit_reference_range').val(result.reference_range);
            $('#edit_description').val(result.description);
            $('select[name="unit"]').val(result.unit);

        }
    });
}



$(".radiology").click(function() {
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