<?php
$data = $this->session->userdata('hospitaladmin');
$api_base_url = $this->config->item('api_base_url');
$current_date_time = date('Y-m-d H:i:s');
?>
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-2">
                <div class="box border0">
                    <ul class="tablists">
                        <li><a href="<?php echo site_url('admin/visitorspurpose') ?>" ><?php echo $this->lang->line('purpose'); ?></a></li>
                        <li><a href="<?php echo site_url('admin/complainttype') ?>"><?php echo $this->lang->line('complain_type'); ?></a></li>
                        <li><a href="<?php echo site_url('admin/source') ?>"><?php echo $this->lang->line('source'); ?></a></li>
                        <li><a href="<?php echo site_url('admin/appointpriority') ?>" class="active"><?php echo $this->lang->line('appointment_priority'); ?></a></li>
                    </ul>
                </div>
            </div><!--./col-md-3-->

            <div class="col-md-10">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('appointment_priority_list'); ?> </h3>
                        <div class="box-tools pull-right">
                             <?php if ($this->rbac->hasPrivilege('setup_front_office', 'can_add')) {?>
                            <a data-toggle="modal" data-target="#myModal" class="btn btn-primary btn-sm addappointment"><i class="fa fa-plus"></i>  <?php echo $this->lang->line('add_priority'); ?></a>
                            <?php }?>
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="download_label"><?php echo $this->lang->line('appointment_priority_list'); ?></div>
                        <div class="table-responsive mailbox-messages">
                            <table class="table table-hover table-striped table-bordered example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('priority'); ?></th>
                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
if (empty($appoint_priority_list)) {
    ?>
                                        <?php
} else {
                                                  
                                        foreach ($appoint_priority_list as $key => $value) {
                                            ?>
                                            <tr>

                                                <td class="mailbox-name">
                                                    <a href="#" data-toggle="popover" class="detail_popover"><?php echo $value['priority_status'] ?></a>

                                                </td>


                                                <td class="mailbox-date pull-right">
                                                    <?php if ($this->rbac->hasPrivilege('setup_front_office', 'can_edit')) {?>
                                                        <a data-target="#editmyModal" onclick="get(<?php echo $value['id']; ?>)"  class="btn btn-default btn-xs" data-toggle="tooltip" title="" data-original-title="<?php echo $this->lang->line('edit'); ?>">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>
                                                    <?php }?>
                                                     <?php if ($value['id'] > 1) { ?>
                                                        <?php if ($this->rbac->hasPrivilege('setup_front_office', 'can_delete')) {?>
                                                            <a class="btn btn-default btn-xs" data-toggle="tooltip" title="" onclick="delete_appointpriority('<?php echo $api_base_url; ?>setup-front-office-appointment-priority/<?php echo $value['id']; ?>?Hospital_id=<?php echo $data['hospital_id']; ?>')" data-original-title="<?php echo $this->lang->line('delete'); ?>">
                                                                    <i class="fa fa-trash"></i>
                                                                </a>

                                                        <?php } ?>
                                                    <?php } ?>
                                                </td>


                                            </tr>
                                            <?php
}
}
?>
                                </tbody>
                            </table><!-- /.table -->
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                </div>
            </div><!--/.col (left) -->
            <!-- right column -->
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<!-- new END -->

</div><!-- /.content-wrapper -->

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('add_priority'); ?></h4>
            </div>

            <form id="formadd" action="<?php echo site_url('admin/appointpriority/add') ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8">
                <div class="modal-body pt0 pb0">
                    <div class="ptt10">
                        <div class="form-group">
                            <label for="pwd"><?php echo $this->lang->line('priority'); ?></label> <small class="req"> *</small>
                            <input class="form-control" id="appoint_priority" name="appoint_priority" value="<?php echo set_value('appoint_priority'); ?>"/>
                            <span class="text-danger"><?php echo form_error('appoint_priority'); ?></span>
                        </div>
                    </div>
                </div><!--./col-md-12-->
                <div class="modal-footer">
                    <button type="submit" data-loading-text="<?php echo $this->lang->line('processing'); ?>" id="formaddbtn" class="btn btn-info pull-right"><i class="fa fa-check-circle"></i> <?php echo $this->lang->line('save'); ?></button>
                </div>
            </form>
        </div><!--./row-->
    </div>
</div>


<div class="modal fade" id="editmyModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('edit_priority'); ?></h4>
            </div>
            <form id="editformadd" action="" name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                    <div class="modal-body pt0 pb0">
                        <div class="ptt10">
                            <div class="form-group">
                                <label for="pwd"><?php echo $this->lang->line('priority'); ?></label> <small class="req"> *</small>
                                <input class="form-control" id="appoint_priority_edit" name="appoint_priority" value="<?php echo set_value('appoint_priority'); ?>"/>
                                <span class="text-danger"><?php echo form_error('appoint_priority'); ?></span>
                                <input type="hidden" id="id" name="id">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" data-loading-text="<?php echo $this->lang->line('processing'); ?>" id="editformaddbtn" class="btn btn-info pull-right"><i class="fa fa-check-circle"></i> <?php echo $this->lang->line('save'); ?></button>
                    </div>
                </form>
        </div><!--./row-->
    </div>
</div>

<script>
$(document).ready(function() {
    $('#formadd').on('submit', function(e) {
        e.preventDefault();
        
        var formData = {
            id: '<?=$data['hospital_id']?>',
            priority_status: $('#appoint_priority').val()
        };        
        $.ajax({
            url: '<?=$api_base_url?>setup-front-office-appointment-priority', 
            type: 'POST',
            data: formData,
            dataType: 'json',
            beforeSend: function() {
                $('#editformaddbtn').button('loading');
            },
            success: function(response) {
                $('#editformaddbtn').button('reset');
                successMsg('Appointment Priority add successfully!'); 
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
    $(document).ready(function(){
    $("#editformadd").on('submit', function(e){
        e.preventDefault();

        // Create the formData object
        var formData = JSON.stringify({
            id: '<?=$data['hospital_id']?>',
            priority_status: $('#appoint_priority_edit').val(),
            created_at: '<?=$current_date_time?>'
        });

        var id = $('#id').val();

        $.ajax({
            url: '<?=$api_base_url?>setup-front-office-appointment-priority/' + id,
            type: 'PATCH',
            data: formData,
            contentType: 'application/json', // Correct content type for JSON data
            processData: false, // No need to process the data since it's a JSON string
            beforeSend: function(){
                $("#editformaddbtn").button('loading');
            },
            success: function(response){
                const message = response?.data?.message || 'Updated success ';
                successMsg(message);
                location.reload();
                $("#editformaddbtn").button('reset');
            },
            error: function(xhr, status, error){
                console.log(xhr.responseText);
                $("#editformaddbtn").button('reset');
            }
        });
    });
});

</script>

<script>

    $(document).ready(function () {
        $('.detail_popover').popover({
            placement: 'right',
            trigger: 'hover',
            container: 'body',
            html: true,
            content: function () {
                return $(this).closest('td').find('.fee_detail_popover').html();
            }
        });
    });
</script>


<script>
    function get(id) {

        $('#editmyModal').modal('show');
        $.ajax({

            dataType: 'json',

            url: '<?php echo base_url(); ?>admin/appointpriority/get_data/' + id,

            success: function (result) {

                $('#id').val(result.id);
                $('#appoint_priority_edit').val(result.priority_status);
            }

        });
    }

    


$(".addappointment").click(function(){
    $('#formadd').trigger("reset");
});

    $(document).ready(function (e) {
        $('#myModal,#editmyModal').modal({
        backdrop: 'static',
        keyboard: false,
        show:false
        });
    });

    function delete_appointpriority(url){
        delete_recordByIdReload(url, '<?php echo $this->lang->line('delete_confirm') ?>')
    }
</script>