<?php
$data = $this->session->userdata('hospitaladmin');
$api_base_url = $this->config->item('api_base_url');
?>
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-2">
                <?php
                $this->load->view('admin/onlineappointment/appointmentSidebar');
                ?>
            </div>
            <div class="col-md-10">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('shift'); ?></h3>
                        <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('online_appointment_shift', 'can_add')) { ?>
                                <button onclick="addShiftModal()" class="btn btn-primary btn-sm addpayment"><i
                                        class="fa fa-plus"></i> <?php echo $this->lang->line('add_shift'); ?></button>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages">
                            <table class="table table-striped table-bordered table-hover" id="ajaxlist">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th><?php echo $this->lang->line('name'); ?></th>
                                        <th><?php echo $this->lang->line('time_from'); ?></th>
                                        <th><?php echo $this->lang->line('time_to'); ?></th>
                                        <?php if ($this->rbac->hasPrivilege('online_appointment_shift', 'can_edit') || $this->rbac->hasPrivilege('online_appointment_shift', 'can_delete')) { ?>
                                            <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div><!-- /.box-body -->
                </div>
            </div>
        </div>
    </section>
</div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm400" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('add_shift'); ?></h4>
            </div>
            <form id="addglobalshift" class="ptt10" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                <div class="modal-body pt0 pb0">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name"><?php echo $this->lang->line('name'); ?></label>
                                <span class="req"> *</span>
                                <input name="name" placeholder="" type="text" class="form-control"
                                    value="<?php echo set_value('name'); ?>" />
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="time_from"><?php echo $this->lang->line('time_from'); ?></label>
                            <span class="req"> *</span>
                            <div class="form-group input-group">
                                <input type="text" name="time_from" class="form-control time_from time" id="time_from"
                                    value="">
                                <div class="input-group-addon">
                                    <span class="fa fa-clock-o"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="time_to"><?php echo $this->lang->line('time_to'); ?></label>
                            <span class="req"> *</span>
                            <div class="form-group input-group">
                                <input type="text" name="time_to" class="form-control time_to time" id="time_to"
                                    value="">
                                <div class="input-group-addon">
                                    <span class="fa fa-clock-o"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer clear">
                    <div class="pull-right">
                        <button type="submit" id="addshiftbtn"
                            data-loading-text="<?php echo $this->lang->line('processing'); ?>" class="btn btn-info"><i
                                class="fa fa-check-circle"></i> <?php echo $this->lang->line('save'); ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="myModalEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm400" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('edit_shift') ?></h4>
            </div>

            <form id="editshift" class="" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                <div class="modal-body pt0 pb0">
                    <div class="ptt10 row" id="">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('name'); ?></label>
                                <span class="req"> *</span>
                                <input id="edit_name" name="name" placeholder="" type="text" class="form-control"
                                    value="<?php echo set_value('name'); ?>" />
                                <input id="shiftid" name="shiftid" placeholder="" type="hidden" class="form-control" />
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="time_from"><?php echo $this->lang->line('time_from'); ?></label>
                            <span class="req"> *</span>
                            <div class="form-group input-group">
                                <input type="text" name="time_from" class="form-control time_from time"
                                    id="edit_time_from" value="">
                                <div class="input-group-addon">
                                    <span class="fa fa-clock-o"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="time_to"><?php echo $this->lang->line('time_to'); ?></label>
                            <span class="req"> *</span>
                            <div class="form-group input-group">
                                <input type="text" name="time_to" class="form-control time_to time" id="edit_time_to"
                                    value="">
                                <div class="input-group-addon">
                                    <span class="fa fa-clock-o"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer clear">
                    <div class="pull-right">
                        <button type="submit" data-loading-text="<?php echo $this->lang->line('processing'); ?>"
                            id="editshiftbtn" class="btn btn-info pull-right"><i class="fa fa-check-circle"></i>
                            <?php echo $this->lang->line('save'); ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="myModalEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm400" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('edit_shift') ?></h4>
            </div>

            <form id="editshift" class="" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                <div class="modal-body pt0 pb0">
                    <div class="ptt10 row" id="">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('name'); ?></label>
                                <span class="req"> *</span>
                                <input id="edit_name" name="name" placeholder="" type="text" class="form-control"
                                    value="<?php echo set_value('name'); ?>" />
                                <input id="shiftid" name="shiftid" placeholder="" type="hidden" class="form-control" />
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="time_from"><?php echo $this->lang->line('time_from'); ?></label>
                            <span class="req"> *</span>
                            <div class="form-group input-group">
                                <input type="text" name="time_from" class="form-control time_from time"
                                    id="edit_time_from" value="">
                                <div class="input-group-addon">
                                    <span class="fa fa-clock-o"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="time_to"><?php echo $this->lang->line('time_to'); ?></label>
                            <span class="req"> *</span>
                            <div class="form-group input-group">
                                <input type="text" name="time_to" class="form-control time_to time" id="edit_time_to"
                                    value="">
                                <div class="input-group-addon">
                                    <span class="fa fa-clock-o"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer clear">
                    <div class="pull-right">
                        <button type="submit" data-loading-text="<?php echo $this->lang->line('processing'); ?>"
                            id="editshiftbtn" class="btn btn-info pull-right"><i class="fa fa-check-circle"></i>
                            <?php echo $this->lang->line('save'); ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        function convertTo24HourFormat(time) {
            if (!time) return "";
            let [tp, period] = time.split(' ');
            if (!tp || !period) return "";
            let [h, m] = tp.split(':');
            h = period === 'PM' && h !== '12' ? parseInt(h) + 12 : (period === 'AM' && h === '12' ? '00' : h);
            return `${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}:00`;
        }

        function isValid(name, start, end) {
            let errors = [];
            if (!name || name.trim() === '' || !/^[A-Za-z0-9\s]+$/.test(name)) {
                errors.push('Please enter a valid name.');
            }
            if (!start || !start.trim()) {
                errors.push('Please enter a valid start time.');
            }
            if (!end || !end.trim()) {
                errors.push('Please enter a valid end time.');
            }
            if (errors.length) {
                errorMsg(errors.join('<br>'));
                return false;
            }
            return true;
        }

        function handleShiftForm(selector, method, urlBuilder) {
            $(document).off('submit', selector).on('submit', selector, function(e) {
                e.preventDefault();
                let btn = $(this).find(':submit');
                // if (btn.prop('disabled')) return;
                // btn.prop('disabled', true);

                let fd = new FormData(this);
                let name = fd.get('name'),
                    start = fd.get('time_from'),
                    end = fd.get('time_to');
                if (!isValid(name, start, end)) {
                    btn.prop('disabled', false);
                    return;
                }

                let payload = {
                    name: name,
                    start_time: convertTo24HourFormat(start),
                    end_time: convertTo24HourFormat(end),
                    Hospital_id: <?= $data['hospital_id'] ?>
                };

                let shiftid = fd.get('shiftid');
                let apiUrl = urlBuilder(shiftid);
                const accessToken = '<?= $data['accessToken'] ?? '' ?>';
                if (!accessToken) {
                    errorMsg("Access token missing. Please login again.");
                    return;
                }
                sendAjaxRequest(apiUrl, method, payload, function(response) {
                    handleResponse(response);
                });
            });
        }

        handleShiftForm('#editshift', 'PATCH', id => '<?= $api_base_url ?>setup-appointment-shift/' + id);
        handleShiftForm('#addglobalshift', 'POST', () => '<?= $api_base_url ?>setup-appointment-shift');
    });
</script>

<script>
    $(document).on('focus', '.time', function() {
        var $this = $(this);
        $this.datetimepicker({
            format: 'LT'
        });
    });

    function getRecord(id) {
        $('#myModalEdit').modal('show');
        $.ajax({
            url: '<?php echo base_url(); ?>admin/onlineappointment/getglobalshift/' + id,
            type: "POST",
            dataType: "json",
            success: function(data) {
                $("#edit_name").val(data.name);
                $("#shiftid").val(id);
                $("#edit_time_from").val(data.start_time);
                $("#edit_time_to").val(data.end_time);
            },
            error: function() {
                alert("<?php echo $this->lang->line('fail'); ?>")
            }
        });
    }

    function addShiftModal() {
        $('#myModal form')[0].reset();
        $("#myModal").modal("show");
    }
    $(document).ready(function(e) {
        $('#myModal,#myModalEdit').modal({
            backdrop: 'static',
            keyboard: false,
            show: false
        });
    });
</script>
<script>
    function delete_recordByIdReload(id) {
        let confirmation = confirm("Are you sure you want to delete this shift?");
        if (confirmation) {
            sendAjaxRequest('<?= $api_base_url ?>setup-appointment-shift/' + id + '?Hospital_id=<?= $data['hospital_id'] ?>', 'DELETE', {}, function(response) {
                 handleResponse(response);
            });
        } else {
            return false;
        }
    }
</script>
<script>
    $(document).on('focus', '.time', function() {
        var $this = $(this);
        $this.datetimepicker({
            format: 'LT'
        });
    });

    function getRecord(id) {
        $('#myModalEdit').modal('show');
        $.ajax({
            url: '<?php echo base_url(); ?>admin/onlineappointment/getglobalshift/' + id,
            type: "POST",
            dataType: "json",
            success: function(data) {
                $("#edit_name").val(data.name);
                $("#shiftid").val(id);
                $("#edit_time_from").val(data.start_time);
                $("#edit_time_to").val(data.end_time);
            },
            error: function() {
                alert("<?php echo $this->lang->line('fail'); ?>")
            }
        });
    }

    function addShiftModal() {
        $('#myModal form')[0].reset();
        $("#myModal").modal("show");
    }
    $(document).ready(function(e) {
        $('#myModal,#myModalEdit').modal({
            backdrop: 'static',
            keyboard: false,
            show: false
        });
    });
</script>
<script>
    function delete_recordByIdReload(id) {
        let confirmation = confirm("Are you sure you want to delete this shift?");
        if (confirmation) {
            sendAjaxRequest('<?= $api_base_url ?>setup-appointment-shift/' + id + '?Hospital_id=<?= $data['hospital_id'] ?>', 'DELETE', {}, function(response) {
                handleResponse(response);
            });
        } else {
            return false;
        }
    }
</script>
<script>
    const initialData = <?= json_encode($initialData) ?>;
    const initialDataTotal = initialData.recordsTotal || initialData.length || 0;
    $(document).ready(function() {

        let actionTemplate = `
        <a href="#" onclick="getRecord(key:id)" data-target="#myModalEdit" class="btn btn-default btn-xs" data-toggle="tooltip" data-record-id="key:id" title="Edit">
            <i class="fa fa-pencil"></i>
        </a>
        <a href="#" onclick="delete_recordByIdReload(key:id)" class="btn btn-default btn-xs" data-loading-text="Please Wait.." data-toggle="tooltip" data-record-id="key:id" title="Delete">
            <i class="fa fa-trash"></i>
        </a>
    `;
        initializeTable(initialData, initialDataTotal, `${base_url}admin/onlineappointment/getshiftlist`, '#ajaxlist', [
                'sno', 'name', 'start_time', 'end_time', 'action'
            ],
            actionTemplate,
            'id'
        );
    });
</script>