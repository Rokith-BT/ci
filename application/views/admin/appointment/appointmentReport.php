<?php
$currency_symbol = $this->customlib->getHospitalCurrencyFormat();
?>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('appointment_report'); ?></h3>
                    </div>
                    <form id="form1" method="post">
                        <div class="box-body row">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('search_type'); ?></label><small class="req">
                                        *</small>
                                    <select class="form-control" name="search_type" onchange="showdate(this.value)">
                                        <option value="" disabled selected><?php echo $this->lang->line('select'); ?></option>
                                        <?php foreach ($searchlist as $key => $search) { ?>
                                            <option value="<?php echo $key ?>"
                                                <?php
                                                if ((isset($search_type) && $search_type == $key) || (!isset($search_type) && $key == 'today')) {
                                                    echo "selected";
                                                }
                                                ?>>
                                                <?php echo $search ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                    <span class="text-danger"
                                        id="error_search_type"><?php echo form_error('search_type'); ?></span>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line("doctor"); ?></label>
                                    <select class="form-control select2" name="collect_staff" id="collect_staff_select"
                                        onchange="getDoctorShift()" style="width: 100%;">
                                        <option value="" selected>All</option>
                                        <?php foreach ($doctorlist as $dkey => $value) { ?>
                                            <option value="<?php echo $value["id"] ?>"
                                                <?php echo (isset($doctorlist_select) && $doctorlist_select == $value["id"]) ? "selected" : ""; ?>>
                                                <?php echo $value["name"] . " " . $value["surname"] . " (" . $value["employee_id"] . ")" ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line("shift"); ?></label>
                                    <select class="form-control select2" name="shift" id="shift" style="width: 100%;">
                                        <option value="" selected>All</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line("appointment_priority"); ?></label>
                                    <select class="form-control select2 appointment_priority_select2" name="priority"
                                        style="width: 100%;">
                                        <option value="" selected>All</option>
                                        <?php foreach ($appoint_priority_list as $dkey => $dvalue) { ?>
                                            <option value="<?php echo $dvalue["id"]; ?>">
                                                <?php echo $dvalue["priority_status"]; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="box-body row">
                            <div class="col-sm-6 col-md-3" id="fromdate" style="display: none;">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('date_from'); ?></label><small class="req">
                                        *</small>
                                    <input id="date_from" name="date_from" type="text" class="form-control date"
                                        value="<?php echo set_value('date_from', date($this->customlib->getHospitalDateFormat())); ?>" />
                                    <span class="text-danger"><?php echo form_error('date_from'); ?></span>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-3" id="todate" style="display: none;">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('date_to'); ?></label><small class="req">
                                        *</small>
                                    <input id="date_to" name="date_to" type="text" class="form-control date"
                                        value="<?php echo set_value('date_to', date($this->customlib->getHospitalDateFormat())); ?>" />
                                    <span class="text-danger"><?php echo form_error('date_to'); ?></span>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <button type="submit" name="search" value="search_filter"
                                        class="btn btn-primary btn-sm pull-right">
                                        <i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="box border0 clear">
                        <div class="box-header ptbnull"></div>
                        <div class="box-body table-responsive">
                            <div class="download_label"><?php echo $this->lang->line('appointment_report'); ?></div>
                            <div class="table-responsive">
                                <div id="payment-summary-tags"></div>
                                <table class="table table-striped table-bordered table-hover" id="ajaxlist">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>Appointment No</th>
                                            <th><?php echo $this->lang->line('patient_name'); ?></th>
                                            <th><?php echo $this->lang->line('date'); ?></th>
                                            <th><?php echo $this->lang->line('phone'); ?></th>
                                            <th><?php echo $this->lang->line('gender'); ?></th>
                                            <th><?php echo $this->lang->line('doctor'); ?></th>
                                            <th><?php echo $this->lang->line('source'); ?></th>
                                            <?php
                                            if (!empty($fields)) {
                                                foreach ($fields as $fields_key => $fields_value) {
                                            ?>
                                                    <th><?php echo $fields_value->name; ?></th>
                                            <?php
                                                }
                                            }
                                            ?>
                                            <th><?php echo $this->lang->line('fees'); ?></th>
                                            <th class="text text-right">Appointment <?php echo $this->lang->line('status'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
</section>
</div>
<script>
    $(document).ready(function() {
        let baseurl = '<?= base_url() ?>';
        var dataTable = initSummaryDataTable(`${baseurl}/admin/Appointment/getappoitmentreport`, '#form1', '#ajaxlist');
        $('#form1').on('submit', function(e) {
            e.preventDefault();
            dataTable.ajax.reload();
        });
    });
    function renderSummaryTable(dataArray) {
        let count = 0;
        return (dataArray || []).map(function(row) {
            let app_prefix = '<?= $this->customlib->getSessionPrefixByType('appointment') ?>';
            return [
                ++count,
                row.id ? `<span onclick="viewDetail(${row.id})" style="cursor:pointer; color:#0084B4;">${app_prefix + row.id}</span>` : '-',
                row.patient_name ? (row.patient_name + ' (' + row.patient_id + ')') : '-',
                dateformate(row.date),
                row.mobileno || '-',
                row.gender || '-',
                row.name || '-',
                row.source || '-',
                row.amount ? "₹" + Number(row.amount).toFixed(2) : '-',
                row.appointment_status || '-'
            ];
        });
    }

    function dateformate(dateStr) {
        const [year, month, day] = dateStr.split('-');
        return `${day}-${month}-${year}`;
    }


    function showdate(value) {
        if (value == 'period') {
            $('#fromdate').show();
            $('#todate').show();
        } else {
            $('#fromdate').hide();
            $('#todate').hide();
        }
    }

    function getDoctorShift(prev_val = 0) {
        var doctor_id = $("#collect_staff_select").val();
        var select_box = "<option value=''>All</option> ";
        $.ajax({
            type: 'POST',
            url: base_url + "admin/onlineappointment/doctorshiftbyid",
            data: {
                doctor_id: doctor_id
            },
            dataType: 'json',
            success: function(res) {
                $.each(res, function(i, list) {
                    selected = list.id == prev_val ? "selected" : "";
                    select_box += "<option value='" + list.id + "' " + selected + ">" + list.name +
                        "</option>";
                });
                $("#shift").html(select_box);
            }
        });
    }
</script>