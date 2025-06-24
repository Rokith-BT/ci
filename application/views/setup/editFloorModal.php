<form id="editfloor_data" class="ptt10" accept-charset="utf-8" enctype="multipart/form-data">
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label><?php echo $this->lang->line('name'); ?> <span class="req"> *</span></label>
                <input name="name" id="name" placeholder="" type="text" class="form-control"
                    value="<?php echo set_value('name', $floor_data['name']); ?>" />
                <input id="floor_id" name="floor_id" placeholder="" type="hidden" class="form-control"
                    value="<?php echo $floor_data['id']; ?>" />
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group">
                <label><?php echo $this->lang->line('description'); ?></label>
                <textarea class="form-control" id="description" name="description2" placeholder=""
                    rows="2"><?php echo set_value('description', $floor_data['description']); ?></textarea>
            </div>
        </div>
    </div>
    <div class="box-footer row">
        <div class="pull-right">
            <button type="submit" id="editfloor_databtn"
                data-loading-text="<?php echo $this->lang->line('processing') ?>" class="btn btn-info"><i
                    class="fa fa-check-circle"></i> <?php echo $this->lang->line('save'); ?></button>
        </div>
    </div>
</form>
<?php
$data = $this->session->userdata('hospitaladmin');
$api_base_url = $this->config->item('api_base_url');
?>
<script>
$(document).ready(function() {
    $('#editfloor_data').on('submit', function(e) {
        e.preventDefault();
        var formData = {
            name: $('#name').val(),
            description: $('textarea[name="description2"]').val(),
            Hospital_id: <?= $data['hospital_id'] ?>
        };
        let id = $('#floor_id').val();
        let data = JSON.stringify(formData);
        sendAjaxRequest('<?= $api_base_url ?>setup-bed-floor/' + id , 'PATCH', formData, function(response) {
            handleResponse(response);
        });
    });
});
</script>