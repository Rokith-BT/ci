<?php $currency_symbol = $this->customlib->getHospitalCurrencyFormat();
$data = $this->session->userdata('hospitaladmin');
$api_base_url = $this->config->item('api_base_url');
?>
<script>
    $(document).ready(function() {
        $('#date').datetimepicker({
            format: 'DD/MM/YYYY',
            minDate: today,
            defaultDate: today,
            ignoreReadonly: true
        });
    })
</script>
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('expense_list'); ?></h3>
                        <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('expense', 'can_add')) { ?>
                                <a data-toggle="modal" data-target="#myModal" class="btn btn-primary btn-sm addexpense"><i
                                        class="fa fa-plus"></i> <?php echo $this->lang->line('add_expense'); ?></a>
                            <?php } ?>
                        </div><!-- /.box-tools -->
                    </div>
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages">
                            <table class="table table-striped table-bordered table-hover" id="ajaxlist">
                                <thead class="thead-light">
                                    <tr>
                                        <th>S.No</th>
                                        <th><?php echo $this->lang->line('name'); ?></th>
                                        <th><?php echo $this->lang->line('invoice_number'); ?></th>
                                        <th><?php echo $this->lang->line('date'); ?></th>
                                        <th><?php echo $this->lang->line('description'); ?></th>
                                        <th><?php echo $this->lang->line('expense_head'); ?></th>
                                        <th class="text-right">
                                            <?php echo $this->lang->line('amount') . " (" . $currency_symbol . ")"; ?>
                                        </th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="table-body"></tbody>
                            </table>
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                </div>
            </div>
            <!--/.col (left) -->
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('add_expense'); ?></h4>
            </div>
            <div class="scroll-area">
                <div class="modal-body pt0 pb0">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12" id="edit_data">
                            <form class="ptt10" id="addexpense" method="post" accept-charset="utf-8"
                                enctype="multipart/form-data">
                                <div class="row">
                                    <?php if ($this->session->flashdata('msg')) { ?>
                                        <?php echo $this->session->flashdata('msg');
                                        $this->session->userdata('msg');
                                        ?>
                                    <?php } ?>
                                    <?php
                                    if (isset($error_message)) {
                                        echo "<div class='alert alert-danger'>" . $error_message . "</div>";
                                    }
                                    ?>
                                    <?php echo $this->customlib->getCSRF(); ?>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label
                                                for="exampleInputEmail1"><?php echo $this->lang->line('expense_head'); ?></label>
                                            <small class="req">*</small>
                                            <select autofocus="" id="exp_head_id" name="exp_head_id"
                                                class="form-control">
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                <?php
                                                foreach ($expheadlist as $exphead) {
                                                ?>
                                                    <option value="<?php echo $exphead['id'] ?>" <?php
                                                                                                    if (set_value('exp_head_id') == $exphead['id']) {
                                                                                                        echo "selected =selected";
                                                                                                    }
                                                                                                    ?>>
                                                        <?php echo $exphead['exp_category'] ?></option>

                                                <?php
                                                    $count++;
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label
                                                for="exampleInputEmail1"><?php echo $this->lang->line('name'); ?></label>
                                            <small class="req">*</small>
                                            <input id="name" name="name" placeholder="" type="text" class="form-control"
                                                value="<?php echo set_value('name'); ?>"
                                                oninput="this.value = this.value.replace(/[^a-zA-Z\s]/g, '')" />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label
                                                for="exampleInputEmail1"><?php echo $this->lang->line('invoice_number'); ?></label>
                                            <input id="invoice_no" name="invoice_no" placeholder="" type="text"
                                                class="form-control" value="<?php echo set_value('invoice_no'); ?>"
                                                oninput="this.value = this.value.replace(/[^0-9]/g, '')" />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label
                                                for="exampleInputEmail1"><?php echo $this->lang->line('date'); ?></label>
                                            <small class="req">*</small>
                                            <input id="date" name="exdate" placeholder="" type="text"
                                                class="form-control" value="" />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label
                                                for="exampleInputEmail1"><?php echo $this->lang->line('amount') . " (" . $currency_symbol . ")"; ?></label>
                                            <small class="req">*</small>
                                            <input id="amount" name="amount" placeholder="" type="text"
                                                class="form-control" value="<?php echo set_value('amount'); ?>"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label
                                                for="exampleInputEmail1"><?php echo $this->lang->line('attach_document'); ?></label>
                                            <input id="documents" name="documents" placeholder="" type="file"
                                                class="filestyle form-control" onchange="validateFileSize(this)"
                                                accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg"
                                                value="<?php echo set_value('documents'); ?>" />
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label
                                                for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label>
                                            <textarea class="form-control" id="description" name="description"
                                                placeholder=""
                                                rows="3"><?php echo set_value('description'); ?></textarea>
                                            <span class="text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="">
                                        <?php echo display_custom_fields('expenses'); ?>
                                    </div>
                                </div><!-- /.box-body -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="pull-right">
                    <button type="submit" data-loading-text="<?php echo $this->lang->line('processing'); ?>"
                        id="addexpensebtn" class="btn btn-info pull-right"><i class="fa fa-check-circle"></i>
                        <?php echo $this->lang->line('save'); ?></button>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="myModaledit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('edit_expense'); ?></h4>
            </div>
            <div class="modal-body pt0 pb0">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12" id="edit_expensedata">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function validateFileSize(input) {
        const file = input.files[0];
        if (file) {
            const maxSizeMB = 10;
            const maxSizeBytes = maxSizeMB * 1024 * 1024;

            if (file.size > maxSizeBytes) {
                errorMsg("File size must be less than 10MB.");
                input.value = '';
            }
        }
    }
    $(document).ready(function() {
        $('.detail_popover').popover({
            placement: 'right',
            trigger: 'hover',
            container: 'body',
            html: true,
            content: function() {
                return $(this).closest('td').find('.fee_detail_popover').html();
            }
        });
    });

    function delete_record(id) {
        if (confirm('<?php echo $this->lang->line('delete_confirm') ?>')) {
            sendAjaxRequest(
                '<?= $api_base_url ?>finance-expense/' + id,
                'DELETE', {},
                function(response) {
                    handleResponse(response, false);
                }
            );
        }
    }

    function edit(id) {
        $('#myModaledit').modal('show');
        $.ajax({
            url: '<?php echo base_url(); ?>admin/expense/getDataByid/' + id,
            success: function(data) {
                $('#edit_expensedata').html(data);
            }
        });
    }

    $(".addexpense").click(function() {
        $('#addexpense').trigger("reset");
        $(".dropify-clear").trigger("click");
    });

    $(document).ready(function(e) {
        $('#myModal,#myModaledit').modal({
            backdrop: 'static',
            keyboard: false,
            show: false
        });
    });
</script>
<script>
    $(document).ready(function() {
        $("#addexpense").on('submit', function(e) {
            e.preventDefault();
            $("#addexpensebtn").button('loading');
            try {
                let formData = new FormData(this);
                let errors = [];
                if (!formData.get('exp_head_id')) errors.push('expense head');
                if (!formData.get('name')) errors.push('expense name');
                if (!formData.get('exdate')) errors.push('expense date');
                if (!formData.get('amount')) errors.push('expense amount');
                if (errors.length > 0) {
                    errorMsg('Following fields are required:\n' + errors.join('\n'));
                    $("#addexpensebtn").button('reset');
                    return false;
                }
                let formattedData = {
                    "exp_head_id": parseInt(formData.get('exp_head_id'), 10) || 0,
                    "name": formData.get('name') || "",
                    "invoice_no": parseInt(formData.get('invoice_no'), 10) || 0,
                    "date": moment(formData.get('exdate'), "DD/MM/YYYY").format(
                        "YYYY-MM-DD HH:mm:ss") || "",
                    "amount": parseFloat(formData.get('amount')) || 0,
                    "note": formData.get('description') || "",
                    "is_deleted": "no",
                    "documents": "",
                    "is_active": "yes",
                    "hospital_id": <?= $data['hospital_id'] ?>
                };
                var fileInput = document.getElementById('documents').files[0];
                var documentUrl = $('#filedataalread').val();
                if (fileInput) {
                    if (fileInput.size > 5 * 1024 * 1024) {
                        errorMsg('File size should not exceed 5MB');
                        $("#addexpensebtn").button('reset');
                        return false;
                    }
                    var fileUploadData = new FormData();
                    fileUploadData.append('file', fileInput);
                    $.ajax({
                        url: 'https://phr-api.plenome.com/file_upload',
                        type: 'POST',
                        data: fileUploadData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            formattedData.documents = response.data || documentUrl;
                            submitForm(formattedData);
                        },
                        error: function() {
                            formattedData.documents = documentUrl;
                            submitForm(formattedData);
                        }
                    });
                } else {
                    formattedData.documents = documentUrl;
                    submitForm(formattedData);
                }
            } catch (error) {
                alert('An unexpected error occurred: ' + error.message);
                $("#addexpensebtn").button('reset');
            }
        });

        function submitForm(data) {
            try {
                 sendAjaxRequest(
                    `<?= $api_base_url ?>finance-expense`,
                    'POST',
                    data,
                    function(response) {
                        handleResponse(response);
                    }
                );                
            } catch (err) {
                errorMsg('Submission error: ' + err.message);
                $("#addexpensebtn").button('reset');
            }
        }
    });
</script>
<!-- //========datatable start===== -->
<script>
    const initialData = <?= json_encode($initialData) ?>;
    const initialDataTotal = initialData.recordsTotal || initialData.length || 0;

    $(document).ready(function() {
        const actionTemplate = `
        <a class="btn btn-default btn-xs" data-toggle="tooltip" title="Delete" onclick="delete_record(key:id)">
            <i class="fa fa-trash"></i>
        </a>
        <a class="btn btn-default btn-xs" data-toggle="tooltip" title="Edit" onclick="edit(key:id)">
            <i class="fa fa-pencil"></i>
        </a>
    `;
        const condition = {
            key: 'documents',
            condition_fn: function(value) {
                if (value) {
                    return `<a class="btn btn-default btn-xs" data-toggle="tooltip" title="Download" onclick="handleDownload('${value}')">
                        <i class="fa fa-download"></i>
                    </a>`;
                }
                return '';
            }
        };
        initializeTable(
            initialData,
            initialDataTotal,
            `${base_url}admin/expense/get_finance_expense`,
            '#ajaxlist',
            ['sno', 'Name', 'invoice_number', 'Date', 'Description', 'expense_head', 'Amount', 'action'],
            actionTemplate,
            'id',
            condition
        );
    });
</script>
<script>
    function handleDownload(file) {
        console.log("Downloading:", file);
        $.ajax({
            url: 'https://phr-api.plenome.com/file_upload/getDocs',
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify({
                value: file
            }),
            success: function(res) {
                const cleanedFileName = file.replace(/_\d+$/, '');
                const a = document.createElement('a');
                a.href = "data:application/octet-stream;base64," + res;
                a.download = cleanedFileName;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
            },
            error: function() {
                alert('Failed to download the document');
            }
        });
    }
</script>

<!-- //========datatable end===== -->