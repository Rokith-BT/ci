<div class="content-wrapper">  

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!--  <?php //if (($this->rbac->hasPrivilege('department', 'can_add')) || ($this->rbac->hasPrivilege('department', 'can_edit'))) {
?>      -->
            <?php $this->load->view('admin/pharmacy/pharmacyMasters') ?>

            <div class="col-md-10">              
                <div class="box box-primary" id="tachelist">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('medicine_category_list'); ?></h3>
                        <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('medicine_category', 'can_add')) { ?>
                                <a data-toggle="modal" data-target="#myModal" class="btn btn-primary btn-sm medicine"><i class="fa fa-plus"></i>  <?php echo $this->lang->line('add_medicine_category'); ?></a> 
                            <?php } ?>    
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="mailbox-controls">
                        </div>
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label"><?php echo $this->lang->line('medicine_category_list'); ?></div>
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
                                    foreach ($medicineCategory as $category) {
                                        ?>
                                        <tr>
                                            <td><?php echo $category['medicine_category']; ?></td>
                                            <td class="text-right">
                                                <?php if ($this->rbac->hasPrivilege('medicine_category', 'can_edit')) { ?>
                                                    <a data-target="#editmyModal" onclick="get(<?php echo $category['id'] ?>)"  class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                    <?php
                                                }
                                                if ($this->rbac->hasPrivilege('medicine_category', 'can_delete')) {
                                                    ?>
                                                    <a  class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="delete_recordById('<?php echo $category['id'] ?>')">
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
                <h4 class="modal-title"><?php echo $this->lang->line('add_medicine_category'); ?></h4> 
            </div>

            <form id='medicine_category' name="employeeform" method="post" accept-charset="utf-8">
                <div class="modal-body pt0 pb0">  
                    <div class="ptt10">
                        <div class="form-group">
                            <label for="exampleInputEmail1"><?php echo $this->lang->line('category_name'); ?></label><small class="req"> *</small>
                            <input autofocus="" name="medicine_category" placeholder="" type="text" class="form-control"  value="<?php
                            if (isset($result)) {
                                echo $result["medicine_category"];
                            }
                            ?>" />
                            <span class="text-danger"><?php echo form_error('medicine_category'); ?></span>

                        </div>          

                    </div>
                </div><!--./modal-body-->        
                <div class="modal-footer">
                    <button type="submit" id="formaddbtn" data-loading-text="<?php echo $this->lang->line('processing'); ?>" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                </div>
            </form>


        </div><!--./row--> 
    </div>
</div>
<?php
$data = $this->session->userdata('hospitaladmin');
$api_base_url = $this->config->item('api_base_url');
?>
<script>
$(document).ready(function() {
    $('#medicine_category').on('submit', function(e) {
        e.preventDefault();     
        var medicineCategory = $('input[name="medicine_category"]').val();     
        if (!medicineCategory) {
            errorMsg('Medicine category is required.');
            return;
        }
        var pattern = /^(?=.*[a-zA-Z0-9])[a-zA-Z0-9 ]+$/;
        if (!pattern.test(medicineCategory)) {
            errorMsg('Medicine category can only contain letters, numbers, and spaces.');
            return;
        }     
        var formData = {
            medicine_category: medicineCategory,
            Hospital_id: '<?=$data['hospital_id']?>'
        };
        
        $.ajax({
            url: '<?=$api_base_url?>setup_pharmacy_medicine_category', 
            type: 'POST',
            data: formData,
            dataType: 'json',
            beforeSend: function() {
                $('#formaddbtn').button('loading');
            },
            success: function(response) {
                $('#formaddbtn').button('reset');
                successMsg('Medicine Category added successfully!');
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




<div class="modal fade" id="editmyModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"> <?php echo $this->lang->line('edit_medicine_category'); ?></h4> 
            </div>

            <form id="editformadd" action="" name="employeeform" method="post" accept-charset="utf-8"  enctype="multipart/form-data">
                <div class="modal-body pt0 pb0">
                    <div class="ptt10">
                        <div class="form-group">
                            <label for="exampleInputEmail1"><?php echo $this->lang->line('category_name'); ?></label><small class="req"> *</small>
                            <input autofocus="" id="emedicine_category" name="medicine_category" placeholder="" type="text" class="form-control"  value="<?php
                            if (isset($result)) {
                                echo $result["medicine_category"];
                            }
                            ?>" />
                            <span class="text-danger"><?php echo form_error('medicine_category'); ?></span>
                            <input type="hidden" id="id" name="medicinecategoryid">
                        </div>                 
                    </div>
                </div><!--./madal-body-->     
                <div class="modal-footer">
                    <button type="submit" id="editformaddbtn" data-loading-text="<?php echo $this->lang->line('processing'); ?>" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                </div>
            </form>


        </div><!--./row--> 
    </div>
</div>
<script>
$(document).ready(function() {
    $("#editformadd").on('submit', function(e) {
        e.preventDefault();

        var medicineCategory = $('#emedicine_category').val();
        if (!medicineCategory) {
            errorMsg('Medicine category is required.');
            return;
        }
        var pattern = /^(?=.*[a-zA-Z0-9])[a-zA-Z0-9 ]+$/;
        if (!pattern.test(medicineCategory)) {
            errorMsg('Medicine category must contain at least one alphanumeric character and can only contain letters, numbers, and spaces.');
            return;
        }

        var formData = {
            medicine_category: medicineCategory,
            Hospital_id: '<?=$data['hospital_id']?>'
        };
        let medicineCategoryId = $('#id').val();

        $.ajax({
            url: '<?=$api_base_url?>setup_pharmacy_medicine_category/' + medicineCategoryId,
            type: 'PATCH',
            data: formData,
            dataType: 'json',
            beforeSend: function() {
                $("#editformaddbtn").button('loading');
            },
            success: function(response) {
                let message = response[0]['data '].messege || 'Saved successfully';
                successMsg(message);
                location.reload();
                $("#editformaddbtn").button('reset');
            },
            error: function(xhr, status, error) {
                console.log('Error in form submission:', error);
                $("#editformaddbtn").button('reset');
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

            url: '<?php echo base_url(); ?>admin/medicinecategory/get_data/' + id,

            success: function (result) {
                $('#id').val(result.id);
                $('input[name="medicine_category"]').val(result.medicine_category);

            }

        });

    }


    // $(document).ready(function (e) {

    //     $('#editformadd').on('submit', (function (e) {
    //         $("#editformaddbtn").button('loading');
    //         e.preventDefault();
    //         $.ajax({
    //             url: $(this).attr('action'),
    //             type: "POST",
    //             data: new FormData(this),
    //             dataType: 'json',
    //             contentType: false,
    //             cache: false,
    //             processData: false,
    //             success: function (data) {

    //                 if (data.status == "fail") {

    //                     var message = "";
    //                     $.each(data.error, function (index, value) {

    //                         message += value;
    //                     });
    //                     errorMsg(message);
    //                 } else {

    //                     successMsg(data.message);
    //                     window.location.reload(true);
    //                 }
    //                 $("#editformaddbtn").button('reset');
    //             },
    //             error: function () {

    //             }
    //         });
    //     }));
    // });


$(".medicine").click(function(){
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
<script>
     function delete_recordById(id) {
              
              if (confirm(<?php echo "'" . $this->lang->line('delete_confirm') . "'"; ?>)) {
                    $.ajax({
                        url: '<?=$api_base_url?>setup_pharmacy_medicine_category/' + id +"?Hospital_id=" +<?=$data['hospital_id']?>,                      
                        type:"DELETE",
                        success: function (response) {
                            let message = Array.isArray(response) ? response[0]?.message : response.message || "Deleted Successfully";
                            successMsg(message);
                            window.location.reload(true);  
                        }
                    });
                }
            }

</script>