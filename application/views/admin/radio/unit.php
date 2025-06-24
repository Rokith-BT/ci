<div class="content-wrapper">  
    <!-- Main content -->
    <section class="content">
        <div class="row">           
            <div class="col-md-2">
                <div class="box border0">
                      <ul class="tablists">
                          <?php if ($this->rbac->hasPrivilege('radiology_category', 'can_view')) { ?>
                        <li><a href="<?php echo base_url(); ?>admin/lab/addlab" ><?php echo $this->lang->line('radiology_category'); ?></a></li>
                    <?php } if ($this->rbac->hasPrivilege('radiology_unit', 'can_view')) { ?>
                        <li><a class="active" href="<?php echo base_url(); ?>admin/lab/unit" class=""><?php echo $this->lang->line('unit'); ?></a></li>
                    <?php } if ($this->rbac->hasPrivilege('radiology_parameter', 'can_view')) { ?>
                        <li><a  href="<?php echo base_url(); ?>admin/lab/radioparameter" class=""><?php echo $this->lang->line('radiology_parameter'); ?></a></li>
                    <?php } ?>
                    </ul>
                </div>
            </div>
            <div class="col-md-10">              
                <div class="box box-primary" id="tachelist">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo  $this->lang->line('unit_list') ; ?></h3>
                        <div class="box-tools addmeeting">
                            <?php if ($this->rbac->hasPrivilege('radiology_unit', 'can_add')) { ?>
                                <a data-toggle="modal" data-target="#myModal" class="btn btn-primary btn-sm unit"><i class="fa fa-plus"></i>  <?php echo $this->lang->line('add_unit'); ?></a> 
                            <?php } ?>    
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="mailbox-controls">
                        </div>
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label"><?php echo  $this->lang->line('unit_list') ; ?></div>
                            <table class="table table-striped table-bordered table-hover example" >
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('unit_name'); ?></th>
                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = 1;
                                    foreach ($unitname as $lab) {
                                        ?>
                                        <tr>
                                            <td><?php echo $lab['unit_name']; ?></td>
                                            <td class="text-right">
                                                <?php if ($this->rbac->hasPrivilege('radiology_unit', 'can_edit')) { ?>
                                                    <a data-target="#editmyModal" onclick="get(<?php echo $lab['id'] ?>)"  class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                    <?php
                                                }
                                                if ($this->rbac->hasPrivilege('radiology_unit', 'can_delete')) {
                                                    ?> 
                                                    <a href="javascript:void(0);" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="delete_lab_unit('<?php echo $lab['id']; ?>', '<?php echo $this->lang->line('delete_confirm'); ?>')">
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
                <h4 class="modal-title"><?php echo $this->lang->line('add_unit'); ?></h4> 
            </div>
            <form id="radiounitadd"  name="employeeform" method="post" accept-charset="utf-8">
                <div class="modal-body pb0">
                    <div class="form-group">
                        <label for="exampleInputEmail1"><?php echo $this->lang->line('unit_name'); ?></label><small class="req"> *</small>
                        <input autofocus="" name="unit_name" placeholder="" type="text" class="form-control"  />
                        <span class="text-danger"><?php echo form_error('unit_name'); ?></span>
                    </div>
                </div><!--./modal-->     
                <div class="modal-footer">
                    <button type="submit" data-loading-text="<?php echo $this->lang->line('processing'); ?>" id="formaddbtn" class="btn btn-info pull-right"><i class="fa fa-check-circle"></i>  <?php echo $this->lang->line('save'); ?></button>
                </div>
            </form>
        </div><!--./row--> 
    </div>
</div>
<?php
$data = $this->session->userdata('hospitaladmin');
$api_base_url = $this->config->item('api_base_url');
?>
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<script>
$(document).ready(function() {
    $('#radiounitadd').on('submit', function(e) {
        e.preventDefault();
        var unitName = $('input[name="unit_name"]').val();

        if (!unitName || !unitName.trim()) {
            errorMsg("Unit Name is required and cannot be empty or just spaces.");
            return;
        }

        var validPattern = /^(?!\s+$)(?=.*[A-Za-z0-9])[A-Za-z0-9\s!@#$%^&*(),.?":{}|<>/-]+$/;
        if (!validPattern.test(unitName)) {
            errorMsg("Unit Name cannot be empty or contain only spaces.");
            return;
        }

        var formData = {
            unit_name: unitName,
            unit_type: "radio",
            Hospital_id: <?=$data['hospital_id']?>
        };

        $.ajax({
            url: '<?=$api_base_url?>setup-radiology-unit',
            type: 'POST',
            data: formData,
            dataType: 'json',
            beforeSend: function() {
                $('#formaddbtn').button('loading');
            },
            success: function(response) {
                $('#formaddbtn').button('reset');
                successMsg(response[0]["data "].messege); 
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
    function delete_lab_unit(labId, confirmMessage) {
        if (confirm(confirmMessage)) {
            $.ajax({
                url: '<?=$api_base_url?>setup-radiology-unit/' + labId,
                type: 'DELETE',
                success: function(response) {
                    let message = Array.isArray(response) ? response[0]?.message : response.message || "Deleted Successfully";
                    successMsg(message);
                    // Optionally, reload the page or remove the deleted item from the DOM
                    location.reload();
                },
                error: function(xhr, status, error) {
                    alert('An error occurred while deleting the unit.');
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
                <h4 class="modal-title"><?php echo $this->lang->line('edit_unit'); ?></h4> 
            </div>
            <form id="editformadd" name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                <div class="modal-body pb0">
                        <div class="form-group">
                            <label for="exampleInputEmail1"><?php echo $this->lang->line('unit_name'); ?></label><small class="req"> *</small>
                            <input autofocus="" id="unit_name" name="unit_name" placeholder="" type="text" class="form-control"  value="" />
                            <span class="text-danger"><?php echo form_error('unit_name'); ?></span>
                            <input type="hidden" id="id" name="unit_id">
                        </div>                 
                </div><!--./modal-->        
                <div class="modal-footer">
                    <button type="submit" id="editformaddbtn" data-loading-text="<?php echo $this->lang->line('processing'); ?>" class="btn btn-info pull-right"><i class="fa fa-check-circle"></i>  <?php echo $this->lang->line('save'); ?></button>
                </div>
            </form>
        </div><!--./row--> 
    </div>
</div>
<script>
$('#editformadd').on('submit', function(e) {
    e.preventDefault();
    var unit_name = $('#unit_name').val();
    var unit_name_pattern = /^[A-Za-z0-9\s!@#$%^&*()_+={}\[\]:;"'<>,.?/-]*$/;

    if (!unit_name || !unit_name_pattern.test(unit_name)) {
        alert('Unit Name is required and cannot contain only special characters.');
        return;
    }

    var formData = {
        unit_name: unit_name,
        unit_type: "radio",
        Hospital_id: <?=$data['hospital_id']?>         
    };      
    let unit_id = $('#id').val();
    $.ajax({
        url: '<?=$api_base_url?>setup-radiology-unit/' + unit_id,
        type: 'PATCH',
        data: formData,
        dataType: 'json',
        beforeSend: function() {
            $('#editformaddbtn').button('loading');
        },
        success: function(response) {
            $('#editformaddbtn').button('reset');
            let message = response[0]?.['data ']?.messege || 'Default success message';
            successMsg(message);
            location.reload();  
        },
        error: function(xhr, status, error) {
            $('#editformaddbtn').button('reset');
            alert('An error occurred while submitting the form.');
        }
    });
});
</script>


<script>

   

    function get(id) {
        $('#editmyModal').modal('show');
        $.ajax({
            dataType: 'json',
            url: '<?php echo base_url(); ?>admin/lab/get_dataunit/' + id,
            success: function (result) {
                $('#id').val(result.id);
                $('#unit_name').val(result.unit_name);
            }
        });
    }
    
    
	
$(".unit").click(function(){
	$('#formadd').trigger("reset");
});

    $(document).ready(function (e) {
        $('#myModal,#editmyModal').modal({
            backdrop: 'static',
            keyboard: false,
            show:false
        });
    });
</script>