<?php
$currency_symbol = $this->customlib->getHospitalCurrencyFormat();
$data = $this->session->userdata('hospitaladmin');
$api_base_url = $this->config->item('api_base_url');
?>
<script type="text/javascript">
    $(document).ready(function() {
        $('#editdate').datetimepicker({
            format: 'DD/MM/YYYY',
            defaultDate: today,
            ignoreReadonly: true
        });
    });
</script>
<form id="editexpense" class="ptt10" method="post" accept-charset="utf-8" enctype="multipart/form-data">
    <div class="row">

        <?php if ($this->session->flashdata('msg')) { ?>
            <?php echo $this->session->flashdata('msg') ?>
        <?php } ?>
        <?php
        if (isset($error_message)) {
            echo "<div class='alert alert-danger'>" . $error_message . "</div>";
        }
        ?>
        <?php echo $this->customlib->getCSRF(); ?>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="exampleInputEmail1"><?php echo $this->lang->line('expense_head'); ?></label><small
                    class="req"> *</small>
                <select autofocus="" id="exp_head_id" name="exp_head_id" class="form-control">
                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                    <?php
                    foreach ($expheadlist as $exphead) {
                    ?>
                        <option value="<?php echo $exphead['id'] ?>" <?php
                                                                        if ($expense['exp_head_id'] == $exphead['id']) {
                                                                            echo "selected =selected";
                                                                        }
                                                                        ?>><?php echo $exphead['exp_category'] ?></option>
                    <?php
                        $count++;
                    }
                    ?>
                </select>
                <span class="text-danger"><?php echo form_error('exp_head_id'); ?></span>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="exampleInputEmail1"><?php echo $this->lang->line('name'); ?></label><small class="req">
                    *</small>
                <input id="name" name="name" placeholder="" type="text" class="form-control"
                    value="<?php echo set_value('name', $expense['name']); ?>" />
                <input id="expense_id" type="hidden" class="form-control" value="<?php echo $expense['id']; ?>" />

            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="exampleInputEmail1"><?php echo $this->lang->line('invoice_number'); ?></label>
                <input id="invoice_no" name="invoice_no" placeholder="" type="text" class="form-control"
                    value="<?php echo set_value('invoice_no', $expense['invoice_no']); ?>" />

            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="exampleInputEmail1"><?php echo $this->lang->line('date'); ?></label><small class="req">
                    *</small>
                <input id="editdate" name="date" placeholder="" type="text" class="form-control"
                    value="<?php echo set_value('date', date('d/m/Y', strtotime($expense['date']))); ?>"
                    readonly="readonly" />

            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label
                    for="exampleInputEmail1"><?php echo $this->lang->line('amount') . " (" . $currency_symbol . ")"; ?></label><small
                    class="req"> *</small>
                <input id="amount" name="amount" placeholder="" type="text" class="form-control"
                    value="<?php echo set_value('amount', $expense['amount']); ?>" />

            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="exampleInputEmail1"><?php echo $this->lang->line('attach_document'); ?></label>
                <input id="documentsedit" name="documents" placeholder="" type="file" class="filestyle form-control"
                    value="<?php echo set_value('documents'); ?>" />
                <input id="oldfile" type="hidden" value="<?= $expense['documents'] ?>" />
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group">
                <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label>
                <textarea class="form-control" id="description" name="description" placeholder=""
                    rows="3"><?php echo set_value('description'); ?><?php echo set_value('description', $expense['note']) ?></textarea>

            </div>
        </div>
        <div class=""> <?php echo display_custom_fields('expenses', $expense['id']); ?> </div>
    </div><!-- /.box-body -->
    <div class="row">
        <div class="box-footer">
            <div class="pull-right">
                <button type="submit" data-loading-text="<?php echo $this->lang->line('processing'); ?>"
                    id="editexpensebtn"
                    class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
            </div>
        </div>
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function() {
        $('.filestyle').dropify();
    });
</script>
<script>
    $(document).ready(function(e) {
        $("#editexpense").on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            let formattedData = {
                "exp_head_id": parseInt(formData.get('exp_head_id'), 10) || 0,
                "name": formData.get('name') || "",
                "invoice_no": parseInt(formData.get('invoice_no'), 10) || 0,
                "date": moment(formData.get('date'), "DD/MM/YYYY").format("YYYY-MM-DD HH:mm:ss") || "",
                "amount": parseFloat(formData.get('amount')) || 0,
                "note": formData.get('description') || "",
                "is_deleted": "no",
                "documents": "",
                "is_active": "yes",
                "hospital_id": <?= $data['hospital_id'] ?>
            };
            var fileInput = document.getElementById('documentsedit').files[0];
            var documentUrl = $('#filedataalread').val();
            if (fileInput) {
                var fileUploadData = new FormData();
                fileUploadData.append('file', fileInput);
                $.ajax({
                    url: 'https://phr-api.plenome.com/file_upload',
                    type: 'POST',
                    data: fileUploadData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.data) {
                            formattedData.documents = response.data;
                        } else {
                            formattedData.documents = documentUrl;
                        }
                        submitForm(formattedData);
                    },
                    error: function() {
                        formattedData.documents = documentUrl;
                        submitForm(formattedData);
                    }
                });
            } else {
                formattedData.documents = $('#oldfile').val();
                submitForm(formattedData);
            }
        });

        function submitForm(data) {
            let id = $('#expense_id').val();
            sendAjaxRequest(
                `<?= $api_base_url ?>finance-expense/` + id,
                'PATCH',
                data,
                function(response) {
                    handleResponse(response);
                }
            );
        }
    });
</script>