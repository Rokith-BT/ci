<?php
$data = $this->session->userdata('hospitaladmin');
$api_base_url = $this->config->item('api_base_url');
?>
<div class="content-wrapper">
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
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('unit_type_list'); ?> </h3>
                        <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('unit_type', 'can_add')) { ?>
                                <button type="button" data-record-id="0"
                                    class="btn btn-primary btn-sm addunittype add_unit_type_modal" id="load2"
                                    data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing') ?>"><i
                                        class="fa fa-plus"></i> <?php echo $this->lang->line('add_unit_type'); ?></button>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="mailbox-controls">
                        </div>
                        <div class="table-responsive mailbox-messages">
                            <table class="table table-striped table-bordered table-hover" id="ajaxlist">
                                <thead>
                                    <tr>
                                        <th>S.no</th>
                                        <th><?php echo $this->lang->line('unit_type'); ?></th>
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

<div class="modal fade" id="unitModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
                    <div id="modal_title"></div>
                </h4>
            </div>

            <form id="load2-form" action="" method="post" accept-charset="utf-8" class="ptt10">
                <input type="hidden" name="id" id="id" value="0">
                <div class="modal-body pt0 pb0">
                    <div class="form-group">
                        <label><?php echo $this->lang->line('unit'); ?></label><small class="req"> *</small>
                        <input name="unit" id="unit" type="text" class="form-control" maxlength="10" />
                        <span class="text-danger"><?php echo form_error('unit'); ?></span>
                    </div>
                </div>
                <!--./modal-body-->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info pull-right" id="submit-button"
                        data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>"><i
                            class="fa fa-check-circle"></i> <?php echo $this->lang->line('save'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('#unitModal').modal({
            backdrop: 'static',
            keyboard: false,
            show: false
        });
    });
    $(document).on('click', '.addunittype', function() {
        var record_id = $(this).data('recordId');
        $('#unitModal').modal('show');
    });

    $(document).on('click', '.delect_record', function(e) {
        if (confirm(<?php echo "'" . $this->lang->line('delete_confirm') . "'"; ?>)) {
            var record_id = $(this).data('recordId');
            sendAjaxRequest(
                '<?= $api_base_url ?>setup-hospital-charges-unit-type/' + record_id + '?Hospital_id=' +
                <?= $data['hospital_id'] ?>,
                'DELETE',
                {
                    'id': record_id
                },
                function(response) {
                   handleResponse(response);
                }
            );
        }
    });

    $('#unitModal').on('hidden.bs.modal', function(e) {
        $("form#formadd").find('input:text, input:password, input:file').val('');
    })
    $('.add_unit_type_modal').click(function() {
        $('#modal_title').empty();
        $('#modal_title').append('<?php echo $this->lang->line('add_unit_type'); ?>');
    })

    $(document).on('click', '.edit_unit_type_modal', function() {
        $('#modal_title').empty();
        $('#modal_title').append('<?php echo $this->lang->line('edit_unit_type'); ?>');
    })

    $(document).on('click', '.edit_unittype', function() {
        var record_id = $(this).data('recordId');
        var btn = $(this);
        $.ajax({
            url: base_url + 'admin/unittype/getByUnitId',
            type: "POST",
            data: {
                'id': record_id
            },
            dataType: 'JSON',
            beforeSend: function() {
                btn.button('loading');
            },

            success: function(data) {
                console.log(data);
                if (data.status == 0) {
                    var message = "";
                    $.each(data.error, function(index, value) {
                        message += value;
                    });
                    errorMsg(message);
                } else {
                    $('#unit').val(data.result.unit);
                    $('#id').val(data.result.id);
                    $('#unitModal').modal('show');
                }
                btn.button('reset');
            },
            error: function() {
                btn.button('reset');
            },
            complete: function() {
                btn.button('reset');
            }
        });
    });
</script>
<script type="text/javascript">
    $('.aa').on('click', function() {
        var $this = $(this);
        $this.button('loading');
        setTimeout(function() {
            $this.button('reset');
        }, 8000);
    });
</script>
<script>
    $(document).ready(function() {
        $('#submit-button').click(function(e) {
            e.preventDefault();
            try {
                const unit = $('#unit').val().trim();
                const hospital_id = '<?= $data['hospital_id'] ?>';
                const id = $('#id').val();
                const unitPattern = /^[a-zA-Z0-9\s\-]+$/;
                if (!unit) {
                    errorMsg('Unit is required');
                    return;
                }
                if (!unitPattern.test(unit)) {
                    errorMsg('Unit must contain only letters, numbers, spaces, and hyphens');
                    return;
                }
                const token = '<?= $data['accessToken'] ?? '' ?>';
                if (!token) {
                    errorMsg('Access token missing. Please login again.');
                    return;
                }
                let apiurl = '<?= $api_base_url ?>setup-hospital-charges-unit-type';
                let method = 'POST';
                if (id != 0 && id !== '') {
                    apiurl += '/' + id;
                    method = 'PATCH';
                }
                let data = {
                    'unit': unit,
                    'Hospital_id': hospital_id,
                };
                sendAjaxRequest(apiurl, method, data, function(response) {
                    handleResponse(response);
                });
            } catch (err) {
                console.error('Submit Error:', err);
                errorMsg('An unexpected error occurred.');
            }
        });
    });
</script>

<!-- //========datatable start===== -->
<script>
    const initialData = <?= json_encode($initialData) ?>;
    const initialDataTotal = initialData.recordsTotal || initialData.length || 0;

    $(document).ready(function() {
        let actionTemplate = `
        <a href='#' class='btn btn-default btn-xs edit_unittype edit_unit_type_modal' data-loading-text='<i class="fa fa-circle-o-notch fa-spin"></i>' data-toggle='tooltip' data-record-id='key:id' title='<?= $this->lang->line('edit') ?>'><i class='fa fa-pencil'></i></a>
        <a data-record-id='key:id' class='btn btn-default btn-xs delect_record' data-toggle='tooltip' title='<?= $this->lang->line('delete') ?>'><i class='fa fa-trash'></i></a>
    `;
        initializeTable(
            initialData,
            initialDataTotal,
            `${base_url}admin/unittype/unittype`,
            '#ajaxlist',
            ['sno', 'unit', 'action'],
            actionTemplate,
            'charge_unit_id'
        );
    });
</script>

<!-- //========datatable end===== -->