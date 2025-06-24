<?php $currency_symbol = $this->customlib->getHospitalCurrencyFormat();
$data = $this->session->userdata('hospitaladmin');
$api_base_url = $this->config->item('api_base_url');
?>
<form id="edit_incomedata1" class="ptt10" accept-charset="utf-8" enctype="multipart/form-data">
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
                <label for="exampleInputEmail1"><?php echo $this->lang->line('income_head'); ?><small class="req"> *</small></label>
                <select autofocus="" id="inc_head_id" name="inc_head_id" class="form-control">
                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                    <?php
                    foreach ($incheadlist as $inchead) {
                    ?>
                        <option value="<?php echo $inchead['id'] ?>" <?php
                                                                        if ($income['inc_head_id'] == $inchead['id']) {
                                                                            echo "selected =selected";
                                                                        }
                                                                        ?>><?php echo $inchead['income_category'] ?></option>
                    <?php
                        $count++;
                    }
                    ?>
                </select>
                <span class="text-danger"><?php echo form_error('inc_head_id'); ?></span>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="exampleInputEmail1"><?php echo $this->lang->line('name'); ?><small class="req"> *</small></label>
                <input id="name" name="name" placeholder="" type="text" class="form-control" value="<?php echo set_value('name', $income['name']); ?>" />
                <input id="income_id" type="hidden" class="form-control" name='editid' value="<?php echo $income['id']; ?>" />
                <input id="oldfile" type="hidden" class="form-control" value="<?php echo $income['documents']; ?>" />
                <span class="text-danger"><?php echo form_error('name'); ?></span>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="exampleInputEmail1"><?php echo $this->lang->line('invoice_number'); ?></label><small
                    class="req"> *</small>
                <input id="invoice_no" name="invoice_no" placeholder="" type="text" class="form-control" value="<?php echo set_value('invoice_no', $income['invoice_no']); ?>" />
                <span class="text-danger"><?php echo form_error('invoice_no'); ?></span>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="exampleInputEmail1"><?php echo $this->lang->line('date'); ?><small class="req"> *</small></label>
                <input id="editdate" name="date" placeholder="" type="text" class="form-control" value="<?php echo date('d/m/Y', strtotime($income['date'])); ?>" readonly="readonly" />
                <span class="text-danger"><?php echo form_error('date'); ?></span>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <label for="exampleInputEmail1"><?php echo $this->lang->line('amount') . " (" . $currency_symbol . ")"; ?><small class="req"> *</small></label>
                <input id="amount" name="amount" placeholder="" type="text" class="form-control" value="<?php echo set_value('amount', $income['amount']); ?>" />
                <span class="text-danger"><?php echo form_error('amount'); ?></span>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="exampleInputEmail1"><?php echo $this->lang->line('attach_document'); ?></label>
                <input id="documentsf" name="documents" placeholder="" type="file" class="filestyle form-control" name='file' size='20' data-height="26" value="<?php echo set_value('documents'); ?>" />
                <span class="text-danger"><?php echo form_error('documents'); ?></span>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group">
                <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label>
                <textarea class="form-control" id="description" name="description" placeholder="" rows="3"><?php echo set_value('description'); ?><?php echo set_value('description', $income['note']) ?></textarea>
                <span class="text-danger"><?php echo form_error('description'); ?></span>
            </div>
        </div><!-- /.box-body -->
        <div class=""><?php echo display_custom_fields('income', $income['id']); ?></div>

    </div>
    <div class="row">
        <div class="box-footer">
            <div class="pull-right">
                <button type="submit" data-loading-text="<?php echo $this->lang->line('processing'); ?>" id="edit_incomedatabtn" class="btn btn-info"><i class="fa fa-check-circle"></i> <?php echo $this->lang->line('save'); ?></button>
            </div>
        </div>
    </div>
</form>

<script type="text/javascript">
    $(document).ready(function() {
        $('.filestyle').dropify();
    });
</script>
<script type="text/javascript">
    $(document).ready(function(e) {
        $("#edit_incomedata1").on('submit', function(e) {
            var id = $("input[name='editid']").val();
            e.preventDefault();

            let formData = new FormData(this);
            let formDataObj = {};
            formData.forEach((value, key) => {
                formDataObj[key] = value;
            });

            let formattedData = {
                "inc_head_id": parseInt(formDataObj.inc_head_id, 10),
                "name": formDataObj.name || "",
                "invoice_no": parseInt(formDataObj.invoice_no, 10) || 0,
                "amount": parseFloat(formDataObj.amount) || 0,
                "note": formDataObj.description || "",
                "date": formatDate(formDataObj.date),
                "documents": '',
                "hospital_id": <?= $data['hospital_id'] ?>
            };

            var fileInput = document.getElementById('documentsf').files[0];
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
                            documentUrl = response.data;
                            formattedData.documents = documentUrl;
                        } else {
                            formattedData.documents = documentUrl;
                        }
                        console.log(JSON.stringify(formattedData, null, 2));
                        submitForm(formattedData);
                    },
                    error: function() {
                        formattedData.documents = documentUrl;
                        submitForm(formattedData);
                    }
                });
            } else {
                formattedData.documents = $('#oldfile').val();
                console.log(JSON.stringify(formattedData, null, 2));
                submitForm(formattedData);
            }
        });


        function formatDate(dateString) {
            const [day, month, year] = dateString.split('/');
            const date = new Date(`${year}-${month}-${day}`);
            return date.toISOString().slice(0, 10);
        }

        function submitForm(formattedData) {
            let id = $("input[name='editid']").val();

            sendAjaxRequest(
                `<?= $api_base_url ?>finance-income/` + id,
                'PATCH',
                formattedData,
                function(response) {
                    handleResponse(response);
                }
            );
        }
    });


    $(document).ready(function() {
        var date_format = '<?php echo $result = strtr($this->customlib->getHospitalDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy']) ?>';
        $('#editdate').datepicker({
            format: 'dd/mm/yyyy',
            endDate: '+0d',
            autoclose: true
        });
    });
</script>