<?php
$data = $this->session->userdata('hospitaladmin');
$api_base_url = $this->config->item('api_base_url');
?>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-2">
                <div class="box border0">
                    <ul class="tablists">
                        <?php  if ($this->rbac->hasPrivilege('pathology_category', 'can_view')) { ?>
                        <li><a
                                href="<?php echo base_url(); ?>admin/pathologycategory/addcategory"><?php echo $this->lang->line('pathology_category'); ?></a>
                        </li>
                        <?php } if ($this->rbac->hasPrivilege('pathology_unit', 'can_view')) { ?>
                        <li><a href="<?php echo base_url(); ?>admin/pathologycategory/unit"
                                class=""><?php echo $this->lang->line('unit'); ?></a></li>
                        <?php } if ($this->rbac->hasPrivilege('pathology_parameter', 'can_view')) { ?>
                        <li><a class="active" href="<?php echo base_url(); ?>admin/pathologycategory/pathoparameter"
                                class=""><?php echo $this->lang->line('pathology_parameter'); ?></a></li>
                        <?php }  ?>
                    </ul>
                </div>
            </div>
            <div class="col-md-10">
                <div class="box box-primary" id="tachelist">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('pathology_parameter_list'); ?></h3>
                        <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('pathology_parameter', 'can_add')) { ?>
                            <a data-toggle="modal" data-target="#myModal" class="btn btn-primary btn-sm pathology"><i
                                    class="fa fa-plus"></i>
                                <?php echo $this->lang->line('add_pathology_parameter'); ?></a>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="mailbox-controls">
                        </div>
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label"><?php echo $this->lang->line('pathology_parameter_list'); ?>
                            </div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('parameter_name'); ?></th>
                                        <!--  <th><?php echo $this->lang->line('test') . " " . $this->lang->line('value'); ?></th> -->
                                        <th><?php echo $this->lang->line('reference_range'); ?></th>
                                        <th><?php echo $this->lang->line('unit'); ?></th>
                                        <th><?php echo $this->lang->line('description'); ?></th>
                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = 1;
                                    foreach ($parameterName as $lab) {
                                        ?>
                                    <tr>
                                        <td><?php echo $lab['parameter_name']; ?></td>
                                        <!-- <td><?php echo $lab['test_value']; ?></td> -->
                                        <td><?php echo $lab['reference_range']; ?></td>
                                        <td><?php echo $lab['unit_name']; ?></td>
                                        <td><?php echo $lab['description']; ?></td>
                                        <td class="text-right">
                                            <?php if ($this->rbac->hasPrivilege('pathology_parameter', 'can_edit')) { ?>
                                            <a data-target="#editmyModal" onclick="get(<?php echo $lab['id'] ?>)"
                                                class="btn btn-default btn-xs" data-toggle="tooltip"
                                                title="<?php echo $this->lang->line('edit'); ?>">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                            <?php
                                                }
                                                if ($this->rbac->hasPrivilege('pathology_parameter', 'can_delete')) {
                                                    ?>
                                            <a href="javascript:void(0);" class="btn btn-default btn-xs"
                                                data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>"
                                                onclick="deletePathologyCategory('<?php echo $lab['id']; ?>')">
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
                <h4 class="modal-title"><?php echo $this->lang->line('add_pathology_parameter'); ?></h4>
            </div>
            <form id="pathoparameteradd" name="employeeform" method="post" accept-charset="utf-8">
                <div class="modal-body pt0 pb0">
                    <div class="ptt10">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('parameter_name'); ?></label><small class="req">
                                *</small>
                            <input name="parameter_name" placeholder="" type="text" class="form-control" required
                                onkeyup="this.value=this.value.replace(/[^a-zA-Z]/g, '').trim()" />
                            <span class="text-danger"><?php echo form_error('parameter_name'); ?></span>
                        </div>

                        <div class="form-group">
                            <label><?php echo $this->lang->line('reference_range'); ?></label><small class="req">
                                *</small>
                            <input name="reference_range" placeholder="" type="text" class="form-control" required
                                onkeyup="this.value=this.value.replace(/[^0-9.-]/g, '').trim()" />
                            <span class="text-danger"><?php echo form_error('reference_range'); ?></span>
                        </div>

                        <div class="form-group">
                            <label><?php echo $this->lang->line('unit'); ?></label>
                            <small class="req"> *</small>
                            <select name="unit" class="form-control" required>
                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                <?php foreach ($unitname as $value) { ?>
                                <option value="<?php echo $value['id'] ?>"><?php echo $value['unit_name']; ?></option>
                                <?php } ?>
                            </select>
                            <span class="text-danger"><?php echo form_error('unit'); ?></span>
                        </div>

                        <div class="form-group">
                            <label><?php echo $this->lang->line('description'); ?></label>
                            <input name="description" placeholder="" type="text" class="form-control"
                                onkeyup="this.value=this.value.replace(/[^a-zA-Z0-9,. ]/g, '').trim()" />
                            <span class="text-danger"><?php echo form_error('description'); ?></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" data-loading-text="<?php echo $this->lang->line('processing'); ?>"
                        id="formaddbtn"
                        class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
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
<!-- <script> -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    var namePattern = /^[A-Za-z]+(?: [A-Za-z]+)*$/;
    var rangePattern = /^[0-9a-zA-Z\s\-\.\,]+$/;

    $('#pathoparameteradd').on('submit', function(e) {
        e.preventDefault();

        var errors = []; // Array to hold error messages

        var parameterName = $('input[name="parameter_name"]').val().trim();
        var referenceRange = $('input[name="reference_range"]').val().trim();
        var unit = $('select[name="unit"]').val();

        if (parameterName === '') {
            errors.push('Parameter Name is required.');
        } else if (!namePattern.test(parameterName)) {
            errors.push('Parameter Name can only contain letters and spaces.');
        }

        if (referenceRange === '') {
            errors.push('Reference Range is required.');
        } else if (!rangePattern.test(referenceRange)) {
            errors.push(
                'Reference Range can contain letters, numbers, spaces, and special characters like -, ., ,'
            );
        }

        if (unit === '') {
            errors.push('Unit is required.');
        }

        // If there are any errors, show the messages and stop form submission
        if (errors.length > 0) {
            errorMsg(errors.join('<br>'));
            return;
        }

        var formData = {
            parameter_name: parameterName,
            reference_range: referenceRange,
            unit: unit,
            description: $('input[name="description"]').val(),
            Hospital_id: '<?=$data['hospital_id']?>',
            test_value: " ",
            gender: ""
        };

        $.ajax({
            url: '<?=$api_base_url?>setup-pathology-pathology-parameter',
            type: 'POST',
            data: formData,
            dataType: 'json',
            beforeSend: function() {
                $('#formaddbtn').button('loading');
            },
            success: function(response) {
                $('#formaddbtn').button('reset');
                successMsg('Pathology Parameter added successfully!');
                location.reload();
            },
            error: function(xhr, status, error) {
                $('#formaddbtn').button('reset');
                console.log('Error in form submission:', error);
            }
        });
    });
});
</script>


<script>
function deletePathologyCategory(id) {
    if (confirm('<?php echo $this->lang->line('delete_confirm'); ?>')) {
        $.ajax({
            url: '<?=$api_base_url?>setup-pathology-pathology-parameter/' + id + "?Hospital_id=" +
                <?=$data['hospital_id']?>,
            type: 'DELETE',
            dataType: 'json',
            beforeSend: function() {},
            success: function(response) {
                let message = Array.isArray(response) ? response[0]?.message : response.message ||
                    "Deleted Successfully";
                successMsg(message);
                location.reload();
            },
            error: function(xhr, status, error) {
                console.log('Error in form submission:', error);
                alert('An error occurred while deleting the record.');
            }
        });
    }
}
</script>
</script>
<div class="modal fade" id="editmyModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('edit_pathology_category'); ?></h4>
            </div>

            <form id="editformadd" name="employeeform" method="post" accept-charset="utf-8"
                enctype="multipart/form-data">
                <div class="modal-body pt0 pb0">
                    <div class="ptt10">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('parameter_name'); ?></label><small class="req">
                                *</small>
                            <input autofocus="" id="parameter_name" name="parameter_name" placeholder="" type="text"
                                class="form-control" value="" />
                            <span class="text-danger"><?php echo form_error('parameter_name'); ?></span>
                            <input type="hidden" id="id" name="pathology_parameter_id">
                        </div>

                        <div class="form-group">
                            <label><?php echo $this->lang->line('reference_range'); ?></label><small class="req">
                                *</small>
                            <input autofocus="" id="reference_range" name="reference_range" placeholder="" type="text"
                                class="form-control" value="" />
                            <span class="text-danger"><?php echo form_error('reference_range'); ?></span>

                        </div>
                        <div class="form-group">
                            <label><?php echo $this->lang->line('unit'); ?></label>
                            <small class="req"> *</small>
                            <select name="unit" id="unit" onchange="" class="form-control">
                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                <?php foreach ($unitname as $value) {
                                                ?>
                                <option value="<?php echo $value['id'] ?>"><?php echo $value['unit_name']; ?></option>
                                <?php } ?>
                            </select>
                            <span class="text-danger"><?php echo form_error('unit'); ?></span>
                        </div>
                        <div class="form-group">
                            <label><?php echo $this->lang->line('description') ; ?></label>
                            <input autofocus="" id="description" name="description" placeholder="" type="text"
                                class="form-control" value="" />
                            <span class="text-danger"><?php echo form_error('description'); ?></span>

                        </div>
                    </div>
                </div>
                <!--./modal-->
                <div class="modal-footer">
                    <button type="submit" id="editformaddbtn"
                        data-loading-text="<?php echo $this->lang->line('processing'); ?>"
                        class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                </div>
            </form>
        </div>
        <!--./row-->
    </div>
</div>
<script>
$(document).ready(function() {
    var namePattern = /^[A-Za-z]+(?: [A-Za-z]+)*$/;
    var rangePattern = /^[0-9a-zA-Z\s\-\.\,]+$/;

    $('#editformadd').on('submit', function(e) {
        e.preventDefault();

        var errors = []; // Array to hold error messages

        var parameterName = $('#parameter_name').val().trim();
        var referenceRange = $('#reference_range').val().trim();
        var unit = $('#unit').val();

        if (parameterName === '') {
            errors.push('Parameter Name is required.');
        } else if (!namePattern.test(parameterName)) {
            errors.push('Parameter Name can only contain letters and spaces.');
        }

        if (referenceRange === '') {
            errors.push('Reference Range is required.');
        } else if (!rangePattern.test(referenceRange)) {
            errors.push(
                'Reference Range can contain letters, numbers, spaces, and special characters like -, ., ,'
            );
        }

        if (unit === '') {
            errors.push('Unit is required.');
        }

        // If there are any errors, show the messages and stop form submission
        if (errors.length > 0) {
            errorMsg(errors.join('<br>'));
            return;
        }

        var formData = {
            parameter_name: parameterName,
            reference_range: referenceRange,
            unit: unit,
            description: $('#description').val(),
        };

        let pathologyParameterId = $('#id').val();

        $.ajax({
            url: '<?=$api_base_url?>setup-pathology-pathology-parameter/' +
                pathologyParameterId,
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
            error: function(xhr, status, error) {
                $('#editformaddbtn').button('reset');
                console.log('Error in form submission:', error);
            }
        });
    });
});
</script>


<script>
function get(id) {
    $('#editmyModal').modal('show');
    $.ajax({
        dataType: 'json',
        url: '<?php echo base_url(); ?>admin/pathologycategory/get_data_parameter/' + id,
        success: function(result) {
            $('#id').val(result.id);
            $('#parameter_name').val(result.parameter_name);
            $('#test_value').val(result.test_value);
            $('#reference_range').val(result.reference_range);
            $('#unit').val(result.unit);
            $('#description').val(result.description);
        }
    });
}



$(".pathology").click(function() {
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