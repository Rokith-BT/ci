<div class="content-wrapper" style="min-height: 946px;">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-2">
                <div class="box border0">
                    <?php $this->load->view("admin/charges/sidebar"); ?>
                </div>
            </div>
            <div class="col-md-10">
                <div class="box box-primary" id="tachelist">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('charge_type_list'); ?></h3>
                        <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('charge_type', 'can_add')) { ?>
                                <a data-toggle="modal" data-target="#myModal" class="btn btn-primary btn-sm charge_type"><i
                                        class="fa fa-plus"></i> <?php echo $this->lang->line('add_charge_type'); ?></a>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="mailbox-controls">
                        </div>
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label"><?php echo $this->lang->line('charge_category_list'); ?></div>
                            <table class="table table-striped table-bordered table-hover" id="ajaxlist">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th><?php echo $this->lang->line('charge_type'); ?></th>
                                        <?php foreach ($charge_type_modules as $module_shortcode => $module_name) { ?>
                                            <?php if ($this->module_lib->hasActive($module_shortcode)) { ?>
                                                <th><?= $module_name; ?></th>
                                            <?php } ?>
                                        <?php } ?>
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
                <h4 class="modal-title"><?php echo $this->lang->line('add_charge_type'); ?></h4>
            </div>

            <form id="formadd" class="ptt10" action="" id="employeeform" name="employeeform" method="post"
                accept-charset="utf-8">
                <div class="modal-body pt0 pb0">
                    <div class="form-group">
                        <label><?php echo $this->lang->line('charge_type'); ?></label><small class="req"> *</small>
                        <input autofocus="" id="type" name="charge_type" type="text" class="form-control"
                            value="<?php
                                    if (isset($result)) {
                                        echo $result["name"];
                                    }
                                    ?>" maxlength="15" />
                        <span class="text-danger"><?php echo form_error('name'); ?></span>
                    </div>
                    <hr>
                    <label><?= $this->lang->line("module"); ?></label><small class="req"> *</small>
                    <div class="form-group">
                        <?php foreach ($charge_type_modules as $module_shortcode => $module_name) { ?>
                            <?php if ($this->module_lib->hasActive($module_shortcode)) { ?>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="charge_module[]" value="<?= $module_shortcode; ?>">
                                    <?= $module_name; ?>
                                </label>
                                <br>
                            <?php } ?>
                        <?php } ?>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" id="formaddbtn"
                        data-loading-text="<?php echo $this->lang->line('processing'); ?>"
                        class="btn btn-info pull-right"><i class="fa fa-check-circle"></i>
                        <?php echo $this->lang->line('save'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="editmyModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('edit_charge_category'); ?></h4>
            </div>

            <form id="editformadd" action="<?php echo site_url('admin/chargecategory/add') ?>" name="employeeform"
                method="post" accept-charset="utf-8" enctype="multipart/form-data" class="ptt10">
                <div class="modal-body pt0 pb0">
                    <div class="form-group">
                        <label><?php echo $this->lang->line('name'); ?></label><small class="req"> *</small>
                        <input id="type1" name="name" type="text" class="form-control" value="<?php
                                                                                                if (isset($result)) {
                                                                                                    echo $result["name"];
                                                                                                }
                                                                                                ?>" />
                        <span class="text-danger"><?php echo form_error('name'); ?></span>
                    </div>
                    <div class="form-group">
                        <label><?php echo $this->lang->line('description'); ?></label>
                        <small class="req"> *</small>
                        <textarea name="description" id="description1" class="form-control">
                            <?php
                            if (isset($result)) {
                                echo $result["description"];
                            }
                            ?>
                        </textarea>
                        <span class="text-danger"><?php echo form_error('description'); ?></span>
                    </div>
                    <div class="form-group">
                        <label for="pwd"><?php echo $this->lang->line('charge_type'); ?></label>
                        <small class="req"> *</small>
                        <select name="charge_type" id="charge_type1" class="form-control">
                            <option value=""><?php echo $this->lang->line('select') ?></option>
                            <?php foreach ($charge_type as $charge_key => $charge_value) {
                            ?>
                                <option value="<?php echo $charge_key; ?>" <?php if ((isset($result['charge_type'])) && ($result['charge_type'] == $charge_key)) {
                                                                                echo "selected";
                                                                            }
                                                                            ?>><?php echo $charge_value; ?></option>
                            <?php } ?>
                        </select>
                        <input type="hidden" id="chargecategory_id" name="id">
                        <span class="text-danger"><?php echo form_error('charge_type'); ?></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" data-loading-text="<?php echo $this->lang->line('processing') ?>"
                        id="editformaddbtn" class="btn btn-info pull-right"><i class="fa fa-check-circle"></i>
                        <?php echo $this->lang->line('save'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="editchargeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('edit_charge_category'); ?></h4>
            </div>

            <form id="editform" name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data"
                class="ptt10">
                <div class="modal-body pt0 pb0">
                    <div class="form-group">
                        <label><?php echo $this->lang->line('name'); ?></label><small class="req"> *</small>
                        <input id="editchargetype" name="editchargetype" type="text" class="form-control" value="" maxlength="15" />
                        <input id="editchargeid" name="editchargeid" type="hidden" class="form-control" value="" />
                        <span class="text-danger"><?php echo form_error('name'); ?></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" data-loading-text="<?php echo $this->lang->line('processing') ?>"
                        id="editformbtn"
                        class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
$data = $this->session->userdata('hospitaladmin');
$api_base_url = $this->config->item('api_base_url');
?>
<script>
    $(document).ready(function() {
        $('#formadd').on('submit', function(e) {
            e.preventDefault();
            var formData = {
                charge_type: $('input[name="charge_type"]').val(),
                is_default: "yes",
                is_active: "yes",
                Hospital_id: '<?= $data['hospital_id'] ?>',
                module: $('input[name="charge_module[]"]:checked').map(function() {
                    return this.value;
                }).get()
            };
            sendAjaxRequest('<?= $api_base_url ?>setup-hospital-charges-charge-type-master', 'POST', formData, function(response) {
                if (response && response[0] && response[0].data) {
                    var id = response[0].data.id;
                    updateChargeTypeModule(id, formData.module);
                } else {
                    errorMsg('Failed to add charge type. Please try again.');
                }
            });
        });
    });

    function updateChargeTypeModule(id, modules) {
        var completedRequests = 0;
        var totalRequests = modules.length;
        modules.forEach(function(module, index) {
            setTimeout(function() {
                var data = {
                    charge_type_master_id: id,
                    module_shortcode: module,
                    Hospital_id: '<?= $data['hospital_id'] ?>'
                };
                sendAjaxRequest('<?= $api_base_url ?>setup-hospital-charges-charge-type-module', 'POST', data, function(response) {
                    completedRequests++;
                    if (completedRequests === totalRequests) {
                        handleResponse(response);
                    }
                });
            }, index * 1000);
        });
    }

    function updatemodule(chargetypeId, module_shortcode) {
        var data = {
            charge_type_master_id: chargetypeId,
            module_shortcode: module_shortcode,
            Hospital_id: '<?= $data['hospital_id'] ?>'
        };
        sendAjaxRequest('<?= $api_base_url ?>setup-hospital-charges-charge-type-module', 'POST', data, function(response) {
            handleResponse(response);
        });
    }
    $(function() {
        $('#editform').submit(function(e) {
            e.preventDefault();
            let id = $('#editchargeid').val();
            let formdata = {
                'charge_type': $('#editchargetype').val(),
                "is_default": "yes",
                "is_active": "yes",
                "Hospital_id": '<?= $data['hospital_id'] ?>',
            };
            sendAjaxRequest('<?= $api_base_url ?>setup-hospital-charges-charge-type-master/' + id, 'PATCH', formdata, function(response) {
                handleResponse(response);
            });
        });
    });

    function deleteChargeType(id) {
        var msg = '<?php echo $this->lang->line("delete_charge_category_message"); ?>';
        if (confirm(msg)) {
            var url = 'admin/chargetype/delete/' + id;
            $.ajax({
                url: baseurl + url,
                dataType: 'json',
                success: function(res) {
                    successMsg(res.msg);
                    window.location.reload(true);
                },
                error: function(xhr) {
                    alert("Something went wrong");
                },
                complete: function() {}
            })
        }
    }
    $(".charge_type").click(function() {
        $('#formadd').trigger("reset");
    });
    $(document).ready(function(e) {
        $('#myModal').modal({
            backdrop: 'static',
            keyboard: false,
            show: false
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#ajaxlist').DataTable({
            serverSide: true,
            searching: true,
            ordering: true,
            paging: true,
            lengthMenu: [5, 10, 25, 50],
            columnDefs: [{
                orderable: false,
                targets: -1
            }],
            pageLength: 10,
            dom: 'Blfrtip',
            buttons: [{
                    extend: 'copyHtml5',
                    text: '<i class="fa fa-files-o"></i>',
                    titleAttr: 'Copy',
                    className: 'btn btn-default btn-copy'
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="fa fa-file-excel-o"></i>',
                    titleAttr: 'Excel',
                    className: 'btn btn-default btn-excel'
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="fa fa-file-text-o"></i>',
                    titleAttr: 'CSV',
                    className: 'btn btn-default btn-csv'
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="fa fa-file-pdf-o"></i>',
                    titleAttr: 'PDF',
                    className: 'btn btn-default btn-pdf'
                },
                {
                    extend: 'print',
                    text: '<i class="fa fa-print"></i>',
                    titleAttr: 'Print',
                    className: 'btn btn-default btn-print'
                }
            ],
            ajax: function(data, callback) {
                const page = Math.floor(data.start / data.length) + 1;
                $("#pageloader").fadeIn();
                fetch(
                        `${baseurl}admin/chargetype/chargetypelist?limit=${data.length}&page=${page}&search=${data.search.value}`
                    )
                    .then(res => res.json())
                    .then(result => {
                        $("#pageloader").fadeOut();
                        renderChargeTypeListTable(result.data, result.recordsTotal, data, callback);
                    })
                    .catch(() => {
                        $("#pageloader").fadeOut();
                        callback({
                            draw: data.draw,
                            recordsTotal: 0,
                            recordsFiltered: 0,
                            data: []
                        });
                    });
            }
        });
    });
</script>

<script>
    $(document).on('click', '.editcharge', function() {

    var $this = $(this);
    var recordId = $this.data('recordId');

    // $this.button('loading');
    $.ajax({
        url: base_url + 'admin/chargetype/getchargetype',
        type: "POST",
        data: {
            'id': recordId
        },
        dataType: 'json',
        beforeSend: function() {
            $this.button('loading');
        },
        success: function(res) {

            $('#editchargeModal').modal();
            $("#editchargetype").val(res.result.charge_type);
            $("#editchargeid").val(res.result.id);
            $this.button('reset');
        },
        error: function(xhr) { // if error occured
            alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
        },
        complete: function() {
            $this.button('reset');
        }
    });
});

    function renderChargeTypeListTable(dataArray, recordCount, data, callback) {
        const chargeTypeModules = <?php echo json_encode($charge_type_modules); ?>;
        const moduleData = <?php echo json_encode($module_data); ?>;
        const hasPrivilege = (module, action) => {
            return <?php echo json_encode($this->rbac->hasPrivilege('charge_type', 'can_delete')); ?>;
        };
        const hasActiveModule = (module) => {
            const activeModules = <?php $active = [];
                                    foreach ($charge_type_modules as $shortcode => $name) {
                                        if ($this->module_lib->hasActive($shortcode)) {
                                            $active[] = $shortcode;
                                        }
                                    }
                                    echo json_encode($active);
                                    ?>;
            return activeModules.includes(module);
        };

        let count = 0;
        const rows = (dataArray || []).map(item => {
            const chargetypeId = item.id || 0;
            const charge_type = item.charge_type || "";

            const checklistArray = [];

            for (const [module_shortcode, module_name] of Object.entries(chargeTypeModules)) {
                if (hasActiveModule(module_shortcode)) {
                    const isChecked = (moduleData[module_shortcode] || []).includes(String(chargetypeId)) ? "checked" : "";
                    checklistArray.push(`
                    <td>
                        <input
                            type="checkbox"
                            onclick="updatemodule(${chargetypeId}, '${module_shortcode}')"
                            ${isChecked}
                        />
                    </td>
                `);
                }
            }

            const actionButtons = [];
            actionButtons.push(`
            <a class="btn btn-default btn-xs editcharge"
               data-record-id='${chargetypeId}' data-toggle="tooltip"
               title="Edit">
               <i class="fa fa-edit"></i>
            </a>
        `);

            if (item.is_default !== 'yes' && hasPrivilege('charge_type', 'can_delete')) {
                actionButtons.push(`
                <a class="btn btn-default btn-xs" data-toggle="tooltip"
                   title="Delete"
                   onclick="deleteChargeType('${chargetypeId}')">
                   <i class="fa fa-trash"></i>
                </a>
            `);
            }

            return [
                ++count,
                charge_type,
                ...checklistArray,
                `<td class="text-right">${actionButtons.join(' ')}</td>`
            ];
        });

        callback({
            draw: data.draw,
            recordsTotal: recordCount,
            recordsFiltered: recordCount,
            data: rows
        });
    }
</script>