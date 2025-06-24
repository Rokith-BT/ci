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
                        <li><a href="<?php echo base_url(); ?>admin/pathologycategory/addcategory" ><?php echo $this->lang->line('pathology_category'); ?></a></li>
                    <?php } if ($this->rbac->hasPrivilege('pathology_unit', 'can_view')) { ?>
                        <li><a  class="active" href="<?php echo base_url(); ?>admin/pathologycategory/unit" class=""><?php echo $this->lang->line('unit'); ?></a></li>
                         <?php } if ($this->rbac->hasPrivilege('pathology_parameter', 'can_view')) { ?>
                        <li><a  href="<?php echo base_url(); ?>admin/pathologycategory/pathoparameter" class=""><?php echo $this->lang->line('pathology_parameter'); ?></a></li>
                         <?php }  ?>
                    </ul>
                </div>
            </div>
            <div class="col-md-10">   
            <?php if($this->session->flashdata('msg')){ 
                echo $this->session->flashdata('msg'); 
                $this->session->unset_userdata('msg');
            } ?> 

                <div class="box box-primary" id="tachelist">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo  $this->lang->line('unit_list') ; ?></h3>
                        <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('pathology_unit', 'can_add')) { ?>
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
                                                <?php if ($this->rbac->hasPrivilege('pathology_unit', 'can_edit')) { ?>
                                                    <a data-target="#editmyModal" onclick="get(<?php echo $lab['id'] ?>)"  class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                    <?php
                                                }
                                                if ($this->rbac->hasPrivilege('pathology_unit', 'can_delete')) {
                                                    ?>                                                    
                                                     <a href="javascript:void(0);" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="deletePathologyCategory('<?php echo $lab['id']; ?>')">
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
            <form   id="pathologuunit" name="employeeform" method="post" accept-charset="utf-8">
                <div class="modal-body pt0 pb0">
                    <div class="ptt10">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('unit_name'); ?></label><small class="req"> *</small>
                            <input autofocus="" name="unit_name" placeholder="" type="text" class="form-control"  />
                            <span class="text-danger"><?php echo form_error('medicine_category'); ?></span>
                        </div>          
                    </div>
                </div><!--./modal-->     
                <div class="modal-footer">
                    <button type="submit" data-loading-text="<?php echo $this->lang->line('processing'); ?>" id="formaddbtn" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                </div>
            </form>
        </div><!--./row--> 
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    var namePattern = /^[a-z0-9!@#$%^&*()_+={}[\]:;"'<>,.?/-]+(?: [a-z0-9!@#$%^&*()_+={}[\]:;"'<>,.?/-]+)*$/i;

    $('#pathologuunit').on('submit', function(e) {
        e.preventDefault();
        var unitName = $('input[name="unit_name"]').val().trim();     
        if (!unitName) {
            errorMsg('Unit name is required.');
            return;
        }       
        if (!namePattern.test(unitName)) {
            errorMsg('Unit name can contain letters, numbers, special characters, and spaces.');
            return;
        }
        var formData = {
            unit_name: unitName,
            Hospital_id: '<?=$data['hospital_id']?>',
            unit_type: "patho",
        };
        $.ajax({
            url: '<?=$api_base_url?>setup-pathology-unit',
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
                errorMsg('Error in form submission: ' + error);
            }
        });
    });
});
</script>

<script>
function deletePathologyCategory(id) {
    if (confirm('<?php echo $this->lang->line('delete_confirm'); ?>')) {
        $.ajax({
            url: '<?=$api_base_url?>setup-pathology-unit/' + id + "?Hospital_id=" + <?=$data['hospital_id']?>,
            type: 'DELETE',
            dataType: 'json',
            beforeSend: function() {
            },
            success: function(response) {
                let message = Array.isArray(response) ? response[0]?.message : response.message || "Deleted Successfully";
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
<div class="modal fade" id="editmyModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('edit_unit'); ?></h4> 
            </div>
            <form id="editformadd1"  name="employeeform"  accept-charset="utf-8"  enctype="multipart/form-data">
                <div class="modal-body pt0 pb0">
                    <div class="ptt10">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('unit_name'); ?></label><small class="req"> *</small>
                            <input autofocus="" id="unit_name" name="unit_name" placeholder="" type="text" class="form-control"  value="" />
                            <span class="text-danger"><?php echo form_error('unit_name'); ?></span>
                            <input type="hidden" id="id" name="unit_id">
                        </div>                 
                    </div>
                </div><!--./modal-->        
                <div class="modal-footer">
                    <button type="submit"  data-loading-text="<?php echo $this->lang->line('processing'); ?>" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                </div>
            </form>
        </div><!--./row--> 
    </div>
</div>
<script>
$(document).ready(function() {
    var namePattern = /^[a-z0-9!@#$%^&*()_+={}[\]:;"'<>,.?/-]+(?: [a-z0-9!@#$%^&*()_+={}[\]:;"'<>,.?/-]+)*$/i;

    $('#editformadd1').on('submit', function(e) {
        e.preventDefault();    

        var unitName = $('#unit_name').val().trim();

        if (unitName === '') {
            errorMsg('Unit name is required.');
            return;
        }

        if (!namePattern.test(unitName)) {
            errorMsg('Unit name can only contain letters and spaces.');
            return;
        }

        var formData = {
            unit_name: unitName,          
            Hospital_id: '<?=$data['hospital_id']?>',
            unit_type: "patho",
        };

        let unit_id = $('#id').val();

        $.ajax({
            url: '<?=$api_base_url?>setup-pathology-unit/' + unit_id,
            type: 'PATCH',
            data: formData,
            success: function(response) {
                let message = response[0]?.['data ']?.messege || 'Default success message';
                successMsg(message);
                location.reload();                
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error(textStatus, errorThrown);
                errorMsg('An error occurred while submitting the form.');
            },
            complete: function() {
                $('#editformaddbtn').prop('disabled', false).text($('#editformaddbtn').data('loading-text'));
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
            url: '<?php echo base_url(); ?>admin/pathologycategory/get_dataunit/' + id,
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