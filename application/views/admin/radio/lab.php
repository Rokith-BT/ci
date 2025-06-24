<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-2">
                <div class="box border0">
                    <ul class="tablists">
                        <?php if ($this->rbac->hasPrivilege('radiology_category', 'can_view')) { ?>
                        <li><a href="<?php echo base_url(); ?>admin/lab/addlab"
                                class="active"><?php echo $this->lang->line('radiology_category'); ?></a></li>
                        <?php } if ($this->rbac->hasPrivilege('radiology_unit', 'can_view')) { ?>
                        <li><a href="<?php echo base_url(); ?>admin/lab/unit"
                                class=""><?php echo $this->lang->line('unit'); ?></a></li>
                        <?php } if ($this->rbac->hasPrivilege('radiology_parameter', 'can_view')) { ?>
                        <li><a href="<?php echo base_url(); ?>admin/lab/radioparameter"
                                class=""><?php echo $this->lang->line('radiology_parameter'); ?></a></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
            <div class="col-md-10">
                <div class="box box-primary" id="tachelist">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('radiology_category_list'); ?></h3>
                        <div class="box-tools addmeeting">
                            <?php if ($this->rbac->hasPrivilege('radiology_category', 'can_add')) { ?>
                            <a data-toggle="modal" data-target="#myModal" class="btn btn-primary btn-sm radiology"><i
                                    class="fa fa-plus"></i>
                                <?php echo $this->lang->line('add_radiology_category'); ?></a>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="mailbox-controls">
                        </div>
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label"><?php echo $this->lang->line('radiology_category_list'); ?>
                            </div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('category_name'); ?></th>
                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = 1;
                                    foreach ($labName as $lab) {
                                        ?>
                                    <tr>
                                        <td><?php echo $lab['lab_name']; ?></td>
                                        <td class="text-right">
                                            <?php if ($this->rbac->hasPrivilege('radiology_category', 'can_edit')) { ?><a
                                                data-target="#editmyModal" onclick="get(<?php echo $lab['id'] ?>)"
                                                class="btn btn-default btn-xs" data-toggle="tooltip"
                                                title="<?php echo $this->lang->line('edit'); ?>">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                            <?php } ?>
                                            <?php if ($this->rbac->hasPrivilege('radiology_category', 'can_delete')) { ?>

                                            <a href="javascript:void(0);" class="btn btn-default btn-xs"
                                                data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>"
                                                onclick="deleteRadiologyCategory('<?php echo $lab['id']; ?>')">
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
                <h4 class="modal-title"> <?php echo $this->lang->line('add_radiology_category'); ?></h4>
            </div>

            <form id="formadd" name="employeeform" method="post" accept-charset="utf-8">
                <div class="modal-body pt0 pb0">
                    <div class="ptt10">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('category_name'); ?></label><small class="req">
                                *</small>
                            <input name="lab_name" id="lab_name" placeholder="" type="text" class="form-control"
                                required onkeyup="this.value=this.value.replace(/[^a-zA-Z]/g, '').trim()" />
                            <span class="text-danger"><?php echo form_error('lab_name'); ?></span>
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


<div class="modal fade" id="editmyModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('edit_radiology_category'); ?></h4>
            </div>
            <form id="radiolab" name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                <div class="modal-body pt0 pb0">
                    <div class="ptt10">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('category_name'); //$this->lang->line('name');    ?></label><small
                                class="req"> *</small>
                            <input autofocus="" id="edit_lab_name" name="edit_lab_name" placeholder="" type="text"
                                class="form-control" value="<?php
                            if (isset($result)) {
                                echo $result["lab_name"];
                            }
                            ?>" />
                            <span class="text-danger"><?php echo form_error('lab_name'); ?></span>
                            <input type="hidden" id="id" name="lab_id">

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
    $('#radiolab').on('submit', function(e) {
        e.preventDefault();
        var labName = $('#edit_lab_name').val();

        if (!labName || !labName.trim()) {
            errorMsg("Lab Name is required and cannot be empty or just spaces.");
            return;
        }

        var specialCharPattern = /^[A-Za-z0-9\s]+$/;
        if (!specialCharPattern.test(labName)) {
            errorMsg("Lab Name cannot contain only special characters.");
            return;
        }

        var formData = {
            lab_name: labName,
            Hospital_id: <?=$data['hospital_id']?>
        };

        let editid = $('#id').val();
        $.ajax({
            url: '<?=$api_base_url?>setup-radiology-radiology-category/' + editid,
            type: 'PATCH',
            data: formData,
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
                alert('An error occurred while submitting the form.');
            }
        });
    });
});
</script>

<script>
function deleteRadiologyCategory(id) {
    if (confirm('<?php echo $this->lang->line('delete_confirm'); ?>')) {
        $.ajax({
            url: '<?=$api_base_url?>setup-radiology-radiology-category/' + id + "?Hospital_id=" +
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
<script>
$(document).ready(function() {
    $("#formadd").on('submit', function(e) {
        e.preventDefault();
        var labName = $('input[name="lab_name"]').val();

        if (!labName || !labName.trim()) {
            errorMsg("Lab Name is required and cannot be empty or just spaces.");
            return;
        }

        var specialCharPattern = /^[A-Za-z0-9\s]+$/;
        if (!specialCharPattern.test(labName)) {
            errorMsg("Lab Name cannot contain only special characters.");
            return;
        }

        var formData = {
            lab_name: labName,
            Hospital_id: <?=$data['hospital_id']?>
        };
        let form = JSON.stringify(formData);

        $.ajax({
            url: '<?=$api_base_url?>setup-radiology-radiology-category',
            type: 'POST',
            data: form,
            contentType: 'application/json',
            beforeSend: function() {
                $('#formaddbtn').button('loading');
            },
            success: function(response) {
                $('#formaddbtn').button('reset');
                let message = response[0]['data '].messege || 'Default success message';
                successMsg(message);
                location.reload();
            },
            error: function(xhr, status, error) {
                $('#formaddbtn').button('reset');
                alert('An error occurred while submitting the form.');
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
        url: '<?php echo base_url(); ?>admin/lab/get_data/' + id,
        success: function(result) {
            $('#id').val(result.id);
            $('#edit_lab_name').val(result.lab_name);

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