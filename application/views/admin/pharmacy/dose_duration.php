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
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('dosage_duration_list'); ?></h3>
                        <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('dosage_duration', 'can_add')) { ?>
                            <a onclick="add()" class="btn btn-primary btn-sm medicine"><i class="fa fa-plus"></i>
                                <?php echo $this->lang->line('add_dosage_duration'); ?></a>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="mailbox-controls">
                        </div>
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label"><?php echo $this->lang->line('dosage_duration_list'); ?></div>
                            <table class="table table-striped table-bordered table-hover ajaxlist">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('name'); ?></th>

                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>

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
                <h4 class="modal-title"><?php echo $this->lang->line('add_dosage_duration') ?></h4>
            </div>
            <form id="dose_duration" name="employeeform" method="post" accept-charset="utf-8">
                <div class="modal-body pt0 pb0">
                    <div class="ptt10">
                        <div class="form-group">
                            <label for="name"><?php echo $this->lang->line('duration'); ?></label>
                            <small class="req"> *</small>
                            <input name="id" id="id" type="hidden" class="form-control" value="0" />
                            <input name="name" id="name" placeholder="" type="text" class="form-control" required
                                onkeyup="this.value=this.value.replace(/[^a-zA-Z0-9 ]/g, '')" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="formaddbtn"
                        data-loading-text="<?php echo $this->lang->line('processing'); ?>"
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
<script>
$(document).ready(function() {
    $('#dose_duration').on('submit', function(e) {
        e.preventDefault();

        if (!$.trim($('#name').val())) {
            errorMsg('Please enter Duration');
            return false;
        }


        var formData = {
            name: $('#name').val(),
            Hospital_id: '<?=$data['hospital_id']?>'
        };

        let editid = $('#id').val();
        let api = '<?=$api_base_url?>setup-pharmacy-dose-duration';
        let method = 'POST';

        if (editid != 0) {
            api = '<?=$api_base_url?>setup-pharmacy-dose-duration/' + editid;
            method = 'PATCH';
        }

        $.ajax({
            url: api,
            type: method,
            data: JSON.stringify(formData),
            contentType: 'application/json',
            dataType: 'json',
            beforeSend: function() {
                $('#formaddbtn').button('loading');
            },
            success: function(response) {
                $('#formaddbtn').button('reset');
                let message = response[0]['data ']?.messege || 'Updated successfully';
                successMsg(message);
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


<script type="text/javascript">
(function($) {
    'use strict';
    $(document).ready(function() {
        initDatatable('ajaxlist', 'admin/medicinedosage/get_dosedurationlist');
    });
}(jQuery))
</script>
<script>
function add() {
    $('#myModal').modal('show');

}


$('#myModal').on('hidden.bs.modal', function(e) {
    $('#formadd').trigger("reset");
    $('#myModal .box-title').html('<?php echo $this->lang->line('add_dosage_duration') ?>');
})

$(document).ready(function(e) {
    $('#myModal').modal({
        backdrop: 'static',
        keyboard: false,
        show: false
    });
    $(".select2").select2();
});




function get(id) {

    $.ajax({
        dataType: 'JSON',
        url: base_url + 'admin/medicinedosage/get_dosedurationbyid/' + id,

        beforeSend: function() {

        },
        success: function(result) {
            $('#id').val(result.id);
            $('#name').val(result.name);

            $('#myModal .modal-title').html('<?php echo $this->lang->line('edit_dosage_duration') ?>');
            $('#myModal').modal('show');
        },
        error: function(xhr) { // if error occured
            alert("Error occured.please try again");

        },
        complete: function() {

        }
    });
}

function delete_durationById(id) {

    if (confirm('<?php echo $this->lang->line('delete_confirm'); ?>')) {
        $.ajax({
            url: "<?=$api_base_url?>setup-pharmacy-dose-duration/" + id + "?Hospital_id=" +
                <?=$data['hospital_id']?>,
            type: 'DELETE',
            success: function(response) {
                let message = Array.isArray(response) ? response[0]?.message : response.message ||
                    "Deleted Successfully";
                successMsg(message);
                window.location.reload(true);
            }
        });
    }
}
</script>