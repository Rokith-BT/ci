<div class="content-wrapper" style="min-height: 946px;">  
    <!-- Main content -->
    <section class="content">
        <div class="row">           
            <div class="col-md-2">
                <div class="box border0">
                    <ul class="tablists">
                        <?php  if ($this->rbac->hasPrivilege('pathology_category', 'can_view')) { ?>
                        <li><a href="<?php echo base_url(); ?>admin/pathologycategory/addcategory" class="active"><?php echo $this->lang->line('pathology_category'); ?></a></li>
                    <?php } if ($this->rbac->hasPrivilege('pathology_unit', 'can_view')) { ?>
                        <li><a href="<?php echo base_url(); ?>admin/pathologycategory/unit" class=""><?php echo $this->lang->line('unit'); ?></a></li>
                         <?php } if ($this->rbac->hasPrivilege('pathology_parameter', 'can_view')) { ?>
                        <li><a href="<?php echo base_url(); ?>admin/pathologycategory/pathoparameter" class=""><?php echo $this->lang->line('pathology_parameter'); ?></a></li>
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
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('pathology_category_list'); ?></h3>
                        <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('pathology_category', 'can_add')) { ?>
                                <a data-toggle="modal" data-target="#myModal" class="btn btn-primary btn-sm pathology"><i class="fa fa-plus"></i>  <?php echo $this->lang->line('add_pathology_category'); ?></a> 
                            <?php } ?>    
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="mailbox-controls">
                        </div>
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label"><?php echo $this->lang->line('pathology_category_list'); ?></div>
                            <table class="table table-striped table-bordered table-hover example" >
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('category_name'); ?></th>
                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = 1;
                                    foreach ($categoryName as $lab) {
                                        ?>
                                        <tr>
                                            <td><?php echo $lab['category_name']; ?></td>
                                            <td class="text-right">
                                                <?php if ($this->rbac->hasPrivilege('pathology_category', 'can_edit')) { ?>
                                                    <a data-target="#editmyModal" onclick="get(<?php echo $lab['id'] ?>)"  class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                    <?php
                                                }
                                                if ($this->rbac->hasPrivilege('pathology_category', 'can_delete')) {
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
                <h4 class="modal-title"><?php echo $this->lang->line('add_pathology_category'); ?></h4> 
            </div>
            <form id="pathologycategory" name="employeeform" method="post" accept-charset="utf-8">
                <div class="modal-body pt0 pb0">
                    <div class="ptt10">
                        <div class="form-group">
                            <label for="exampleInputEmail1"><?php echo $this->lang->line('category_name'); ?></label><small class="req"> *</small>
                            <input autofocus="" name="category_name"id="addcategory_name" placeholder="" type="text" class="form-control"  onkeyup="this.value=this.value.replace(/[^a-zA-Z ]/g, '')"/>
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
<?php
$data = $this->session->userdata('hospitaladmin');
$api_base_url = $this->config->item('api_base_url');
?>
<!-- <form id="pathologycategory">
    <input type="text" id="category_name" name="category_name" placeholder="Enter Category Name">
    <button type="submit" id="formaddbtn">Add Category</button>
</form> -->

<script>
$(document).ready(function() {
    $('#pathologycategory').on('submit', function(e) {
        e.preventDefault();
        var categoryName = $('#addcategory_name').val().trim();
        var namePattern = /^[A-Za-z]+(?: [A-Za-z]+)*$/;       
        if (categoryName === '') {
            errorMsg('Category name is required.');
            $('#addcategory_name').focus();
            return;
        }

        if (!namePattern.test(categoryName)) {
            errorMsg('Category name can only contain letters and should not have extra spaces.');
            $('#addcategory_name').focus();
            return;
        }

        var formData = {
            category_name: categoryName,
            Hospital_id: '<?=$data['hospital_id']?>'
        };

        $.ajax({
            url: '<?=$api_base_url?>setup-pathology-pathology-category',
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
            url: '<?=$api_base_url?>setup-pathology-pathology-category/' + id + "?Hospital_id=" + <?=$data['hospital_id']?>,
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
                <h4 class="modal-title"><?php echo $this->lang->line('edit_pathology_category'); ?></h4> 
            </div>
            <form id="editformadd"  name="employeeform" method="post" accept-charset="utf-8"  enctype="multipart/form-data">
                <div class="modal-body pt0 pb0">
                    <div class="ptt10">
                        <div class="form-group">
                            <label for="exampleInputEmail1"><?php echo $this->lang->line('category_name'); ?></label><small class="req"> *</small>
                            <input autofocus="" id="category_name" name="category_name" placeholder="" type="text" class="form-control"  value="<?php
                            if (isset($result)) {
                                echo $result["medicine_category"];
                            }
                            ?>" />
                            <span class="text-danger"><?php echo form_error('medicine_category'); ?></span>
                            <input type="hidden" id="id" name="pathology_category_id">
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
$(document).ready(function () {
    var namePattern = /^[A-Za-z]+(?: [A-Za-z]+)*$/;

    $('#editformadd').on('submit', function (e) {
        e.preventDefault();

        var categoryName = $('#category_name').val().trim();

        if (categoryName === '') {
            errorMsg('Category name is required.');
            $('#category_name').focus();
            return;
        }

        if (!namePattern.test(categoryName)) {
            errorMsg('Category name can only contain letters and spaces.');
            $('#category_name').focus();
            return;
        }

        var formData = {
            category_name: categoryName,
            Hospital_id: <?=$data['hospital_id']?>
        };

        let pathology_category_id = $('#id').val();

        $.ajax({
            url: '<?=$api_base_url?>setup-pathology-pathology-category/' + pathology_category_id,
            type: 'PATCH',
            data: formData,
            beforeSend: function () {
                $('#editformaddbtn').button('loading');
            },
            success: function (response) {
                let message = response[0]["data "].message || 'Updated successfully';
                successMsg(message);
                location.reload(); 
            },
            error: function () {
                errorMsg('An error occurred while updating the category.');
            },
            complete: function () {
                $('#editformaddbtn').button('reset');
            }
        });
    });
});
</script>
<script>

    $(document).ready(function (e) {
        $('#formadd').on('submit', (function (e) {
            $("#formaddbtn").button('loading');
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                type: "POST",
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {

                    if (data.status == "fail") {

                        var message = "";
                        $.each(data.error, function (index, value) {

                            message += value;
                        });
                        errorMsg(message);
                    } else {

                        successMsg(data.message);
                        window.location.reload(true);
                    }
                    $("#formaddbtn").button('reset');
                },
                error: function () {

                }
            });
        }));
    });

    function get(id) {
        $('#editmyModal').modal('show');
        $.ajax({
            dataType: 'json',
            url: '<?php echo base_url(); ?>admin/pathologycategory/get_data/' + id,
            success: function (result) {
                $('#id').val(result.id);
                $('#category_name').val(result.category_name);
            }
        });
    }
    
  
	
$(".pathology").click(function(){
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