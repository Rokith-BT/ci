<form id="editbedtype" class="ptt10" accept-charset="utf-8" enctype="multipart/form-data">
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label><?php echo $this->lang->line('name'); ?></label><small class="req"> *</small> 
                <input name="name" id="invoice_no1" placeholder="" type="text" class="form-control" value="<?php echo set_value('name', $bedtype_data['name']); ?>" />
                <input id="bedtype_id" name="bedtype_id" placeholder="" type="hidden" class="form-control" value="<?php echo $bedtype_data['id']; ?>" />
            </div>
        </div>
    </div>
    <div class="box-footer row">
        <div class="pull-right">
            <button type="submit" id="editbedtypebtn" data-loading-text="<?php echo $this->lang->line('processing') ?>" class="btn btn-info">
                <i class="fa fa-check-circle"></i> <?php echo $this->lang->line('save'); ?>
            </button>
        </div>
    </div>
</form>
<?php
$data = $this->session->userdata('hospitaladmin');
$api_base_url = $this->config->item('api_base_url');
?>
<script type="text/javascript">
$(document).ready(function() {
    $('#editbedtype').on('submit', function(e) {
        e.preventDefault();
        var formData = {
            name: $('#invoice_no1').val(),
            Hospital_id: <?= $data['hospital_id'] ?>
        };
        var id = $('#bedtype_id').val();
        var data = JSON.stringify(formData);
        sendAjaxRequest('<?= $api_base_url ?>setup-bed-bed-type/' + id,'PATCH',formData,function(response){
            handleResponse(response);
        });
    });
});
</script>

