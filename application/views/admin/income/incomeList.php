<?php $currency_symbol = $this->customlib->getHospitalCurrencyFormat();
$data = $this->session->userdata('hospitaladmin');
$api_base_url = $this->config->item('api_base_url');
?>
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title titlefix"> <?php echo $this->lang->line('income_list'); ?></h3>
                        <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('income', 'can_add')) { ?>
                                <a data-toggle="modal" data-target="#myModal" class="btn btn-primary btn-sm addincome"><i
                                        class="fa fa-plus"></i> <?php echo $this->lang->line('add_income'); ?></a>
                            <?php } ?>

                        </div>
                    </div>
                    <div class="box-body">
                        <div class="download_label"><?php echo $this->lang->line('income_list'); ?></div>
                        <div class="table-responsive mailbox-messages">
                            <table class="table table-striped table-bordered table-hover" id="ajaxlist">
                                <thead class="thead-light">
                                    <tr>
                                        <th>S.No</th>
                                        <th><?php echo $this->lang->line('name'); ?></th>
                                        <th><?php echo $this->lang->line('invoice_number'); ?></th>
                                        <th><?php echo $this->lang->line('date'); ?></th>
                                        <th><?php echo $this->lang->line('description'); ?></th>
                                        <th><?php echo $this->lang->line('income_head'); ?></th>
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
            <!-- right column -->

        </div>

    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('add_income'); ?></h4>
            </div>
            <form id="add_income" class="ptt10" accept-charset="utf-8" enctype="multipart/form-data">
                <div class="scroll-area">
                    <div class="modal-body pt0 pb0">
                        <div class="row">
                            <?php if ($this->session->flashdata('msg')) { ?>
                                <?php echo $this->session->flashdata('msg');
                                $this->session->unset_userdata('msg'); ?>
                            <?php } ?>
                            <?php
                            if (isset($error_message)) {
                                echo "<div class='alert alert-danger'>" . $error_message . "</div>";
                            }
                            ?>
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('income_head'); ?>
                                        <small class="req"> *</small></label>
                                    <select autofocus="" id="inc_head_id" name="inc_head_id" class="form-control">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php foreach ($incheadlist as $inchead) { ?>
                                            <option value="<?php echo $inchead['id'] ?>" <?php
                                                                                            if (set_value('inc_head_id') == $inchead['id']) {
                                                                                                echo "selected = selected";
                                                                                            }
                                                                                            ?>>
                                                <?php echo $inchead['income_category'] ?>

                                            </option>
                                        <?php $count++;
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('name'); ?><small
                                            class="req"> *</small></label>
                                    <input id="name" name="name" placeholder="" type="text" class="form-control"
                                        value="<?php echo set_value('name'); ?>"
                                        onkeydown="return /^[A-Za-z ]*$/.test(event.key)" />
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label
                                        for="exampleInputEmail1"><?php echo $this->lang->line('invoice_number'); ?></label>
                                    <input id="invoice_no" name="invoice_no" placeholder="" type="text"
                                        class="form-control" value="<?php echo set_value('invoice_no'); ?>"
                                        onkeydown="return (event.key >= '0' && event.key <= '9') || event.key === 'Backspace' || event.key === 'Delete' || event.key === 'Tab';" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('date'); ?><small
                                            class="req"> *</small></label>
                                    <input id="date" name="date" placeholder="" type="text" class="form-control"
                                        value="<?php echo date('d/m/Y'); ?>"
                                        readonly="readonly" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label
                                        for="exampleInputEmail1"><?php echo $this->lang->line('amount') . " (" . $currency_symbol . ")"; ?><small
                                            class="req"> *</small></label>
                                    <input id="amount" name="amount" placeholder="" type="text" class="form-control"
                                        value="<?php echo set_value('amount'); ?>"
                                        onkeydown="return (event.key >= '0' && event.key <= '9') || event.key === 'Backspace' || event.key === 'Delete' || event.key === 'Tab';" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label
                                        for="exampleInputEmail1"><?php echo $this->lang->line('attach_document'); ?></label>
                                    <input id="documents" name="documents" placeholder="" type="file"
                                        class="filestyle form-control" value="<?php echo set_value('documents'); ?>"
                                        onchange="validateFileSize(this)" accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg" />

                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label
                                        for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label>
                                    <textarea class="form-control" id="description" name="description" placeholder=""
                                        rows="3"
                                        placeholder="Enter ..."><?php echo set_value('description'); ?></textarea>
                                    <span class="text-danger"></span>
                                </div>
                            </div>
                            <div class="">
                                <?php echo display_custom_fields('income'); ?>
                            </div>
                        </div><!-- /.box-body -->
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="pull-right">
                        <button type="submit" id="add_incomebtn"
                            data-loading-text="<?php echo $this->lang->line('processing'); ?>"
                            class="btn btn-info pull-right"><i class="fa fa-check-circle"></i>
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
                <h4 class="modal-title"><?php echo $this->lang->line('edit_income'); ?></h4>
            </div>

            <div class="modal-body pt0 pb0">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12" id="edit_data">
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<script type="text/javascript">
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
        var date_format =
            '<?php echo $result = strtr($this->customlib->getHospitalDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy']) ?>';
        $('#date').datepicker({
            format: 'dd/mm/yyyy',
            endDate: '+0d',
            autoclose: true
        });
        $("#btnreset").click(function() {
            $("#form1")[0].reset();
        });
    });

    function delete_record(id) {
        if (confirm('<?php echo $this->lang->line('delete_confirm') ?>')) {
            sendAjaxRequest(
                '<?= $api_base_url ?>finance-income/' + id,
                'DELETE', {},
                function(response) {
                    handleResponse(response, false);
                }
            );
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
    $(document).ready(function() {
        $("#add_income").on('submit', function(e) {
            e.preventDefault();
            try {
                let formData = new FormData(this);
                let formDataObj = {};
                formData.forEach((value, key) => {
                    formDataObj[key] = value;
                });

                let formattedData = {
                    "inc_head_id": parseInt(formDataObj.inc_head_id, 10),
                    "name": formDataObj.name || "",
                    "invoice_no": parseInt(formDataObj.invoice_no, 10) || 0,
                    "date": formatDate(formDataObj.date),
                    "amount": parseFloat(formDataObj.amount) || 0,
                    "note": formDataObj.description || "",
                    "is_deleted": "no",
                    "documents": "",
                    "is_active": "yes",
                    "hospital_id": <?= $data['hospital_id'] ?>
                };

                const errors = validateForm(formattedData);
                if (errors.length > 0) {
                    errorMsg(errors.join('<br>'));
                    $("#add_incomebtn").button('reset');
                    return;
                }

                var fileInput = document.getElementById('documents').files[0];
                var documentUrl = $('#filedataalread').val();

                if (fileInput) {
                    uploadFile(fileInput, function(uploadedUrl) {
                        formattedData.documents = uploadedUrl || documentUrl;
                        submitForm(formattedData);
                    });
                } else {
                    formattedData.documents = documentUrl;
                    submitForm(formattedData);
                }

            } catch (error) {
                console.error(error);
                errorMsg('An unexpected error occurred.');
                $("#add_incomebtn").button('reset');
            }
        });

        function formatDate(dateString) {
            const [day, month, year] = dateString.split('/');
            const date = new Date(`${year}-${month}-${day}`);
            return date.toISOString().slice(0, 10);
        }

        function validateForm(data) {
            let errors = [];
            if (!data.inc_head_id || isNaN(data.inc_head_id)) {
                errors.push('Income Head ID is required and must be a number');
            }
            if (!data.name.trim()) {
                errors.push('Name is required');
            }
            if (!data.date) {
                errors.push('Date is required');
            }
            if (!data.amount || isNaN(data.amount)) {
                errors.push('Amount is required and must be a number');
            }
            return errors;
        }

        function submitForm(formattedData) {
            sendAjaxRequest(
                `<?= $api_base_url ?>finance-income`,
                'POST',
                formattedData,
                function(response) {
                    handleResponse(response);
                }
            );
        }
    });
    function edit(id) {
        $('#myModaledit').modal('show');
        $.ajax({
            url: '<?php echo base_url(); ?>admin/income/getDataByid/' + id,
            success: function(data) {
                $('#edit_data').html(data);
            },
            error: function() {
                alert("Fail")
            }
        });
    }

    $('#myModal').on('hidden.bs.modal', function() {
        $(".filestyle").next(".dropify-clear").trigger("click");
        $('form#add_income').find('input:text, input:password, input:file, textarea').val('');
        $('form#add_income').find('select option:selected').removeAttr('selected');
        $('form#add_income').find('input:checkbox, input:radio').removeAttr('checked');
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
    const initialData = <?= json_encode($initialData['data']) ?>;
    const initialDataTotal = initialData.recordsTotal || initialData.length || 0;
    $(document).ready(function() {
        let actionTemplate = `
        <a class="btn btn-default btn-xs" data-toggle="tooltip" onclick="delete_record(key:id)" title="Delete">
            <i class="fa fa-trash"></i>
        </a>
        <a onclick="edit(key:id)" class="btn btn-default btn-xs" data-toggle="tooltip" title="Edit">
            <i class="fa fa-pencil"></i>
        </a>`;
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
        initializeTable(initialData, initialDataTotal, `${base_url}admin/income/getinclist`, '#ajaxlist', [
            'sno', 'Name', 'invoice_number', 'Date', 'Description', 'Income_Head', 'Amount', 'action'
        ], actionTemplate, 'id', condition);
    });
</script>




<!-- //========datatable end===== -->