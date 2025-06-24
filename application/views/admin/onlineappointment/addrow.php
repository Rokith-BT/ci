<style type="text/css">
.relative label.text-danger {
    position: absolute;
    left: 5px;
    bottom: 0;
}

.shift-info {
    background-color: #e9f5fe;
    border-left: 4px solid #007bff;
    padding: 15px;
    margin-bottom: 25px;
    border-radius: 6px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.table-appointment {
    border-radius: 6px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.table-appointment thead th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
    padding: 12px 15px;
    font-weight: 600;
}

.table-appointment tbody td {
    padding: 15px;
    vertical-align: middle;
}

.btn-action {
    border-radius: 4px;
    padding: 6px 12px;
    transition: all 0.3s ease;
}

.btn-delete {
    background-color: #dc3545;
    border-color: #dc3545;
    color: white;
}

.btn-delete:hover {
    background-color: #c82333;
    border-color: #bd2130;
}

.btn-add {
    background-color: #007bff;
    border-color: #007bff;
    color: white;
    padding: 8px 16px;
    margin-bottom: 20px;
}

.btn-add:hover {
    background-color: #0069d9;
    border-color: #0062cc;
}

.btn-save {
    background-color: #28a745;
    border-color: #28a745;
    color: white;
    padding: 8px 16px;
}

.btn-save:hover {
    background-color: #218838;
    border-color: #1e7e34;
}

.form-container {
    background-color: #ffffff;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}
</style>
<div class="row clearfix">
    <div class="shift-info d-none" id="shift_info">
        <div class="row">
            <div class="col-md-4">
                <strong>Current Shift:</strong> <span id="current_shift_name"><?php echo $global_shift["name"] ?>
                    Shift</span>
            </div>
            <div class="col-md-4">
                <strong>Shift Hours:</strong> <span
                    id="shift_hours"><?php echo $global_shift["start_time"] . " - " . $global_shift["end_time"] ?></span>
            </div>
            <div class="col-md-4">
                <strong>Doctor:</strong>
                <span id="doctor_name">
                    <?php
                    echo $doctordetials["name"] . ' ' . $doctordetials["surname"] . ' (' . $doctordetials["employee_id"] . ')';
                    ?>
                </span>

            </div>
        </div>
    </div>
    <div class="col-md-12 column">
        <form method="POST" id="form_<?php echo $day; ?>" class="commentForm autoscroll" id="addslot">
            <input type="hidden" name="day" value="<?php echo $day; ?>">
            <input type="hidden" name="doctor" value="<?php echo $doctor; ?>">
            <input type="hidden" name="shift" value="<?php echo $shift; ?>">
            <div class="">
                <table class="table table-bordered table-hover order-list tablewidthRS" id="tab_logic">
                    <thead>
                        <tr>
                            <th>
                                <?php echo $this->lang->line('time_from'); ?>
                            </th>
                            <th>
                                <?php echo $this->lang->line('time_to'); ?>
                            </th>
                            <th class="text-right">
                                <?php echo $this->lang->line('action') ?>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($prev_record)) {
                            $counter = 1;
                            foreach ($prev_record as $prev_rec_key => $prev_rec_value) {
                        ?>
                        <input type="hidden" name="prev_array[]" value="<?php echo $prev_rec_value->id; ?>">
                        <tr id='addr0'>
                            <td>
                                <input type="hidden" name="total_row[]" value="<?php echo $counter; ?>">
                                <input type="hidden" name="prev_id_<?php echo $counter; ?>"
                                    value="<?php echo $prev_rec_value->id; ?>">
                                <div class="input-group">
                                    <input type="text" name="time_from_<?php echo $counter; ?>"
                                        class="form-control time_from time" id="time_from_<?php echo $counter; ?>"
                                        value="<?php echo ($prev_rec_value->start_time != "") ? $prev_rec_value->start_time :  $prev_rec_value->start_time; ?>">
                                    <div class="input-group-addon">
                                        <span class="fa fa-clock-o"></span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="input-group">
                                    <input type="text" name="time_to_<?php echo $counter; ?>"
                                        class="form-control time_to time" id="time_to_<?php echo $counter; ?>"
                                        value="<?php echo ($prev_rec_value->end_time != "") ? $prev_rec_value->end_time :  $prev_rec_value->end_time; ?>">
                                    <div class="input-group-addon">
                                        <span class="fa fa-clock-o"></span>
                                    </div>
                                </div>
                            </td>
                            <td class="text-right" width="30">
                                <?php if ($this->rbac->hasPrivilege('online_appointment_slot', 'can_delete')) { ?>
                                <button class="btn btn-danger"
                                    onclick="deleteslort('<?php echo $prev_rec_value->id; ?>')"> <i
                                        class="fa fa-trash"></i></button>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php
                                $counter++;
                            }
                        } else {
                            ?>
                        <tr id='addr0'>
                            <td>
                                <input type="hidden" name="total_row[]" value="<?php echo $total_count; ?>">
                                <input type="hidden" name="prev_id_<?php echo $total_count; ?>" value="0">
                                <div class="input-group">
                                    <input type="text" name="time_from_<?php echo $total_count; ?>"
                                        class="form-control time_from time" id="time_from_<?php echo $total_count; ?>"
                                        aria-invalid="false">
                                    <div class="input-group-addon">
                                        <span class="fa fa-clock-o"></span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="input-group">
                                    <input type="text" name="time_to_<?php echo $total_count; ?>"
                                        class="form-control time_to time" id="time_to_<?php echo $total_count; ?>"
                                        aria-invalid="false">
                                    <div class="input-group-addon">
                                        <span class="fa fa-clock-o"></span>
                                    </div>
                                </div>
                            </td>
                            <td class="text-right" width="30">
                                <?php if ($this->rbac->hasPrivilege('online_appointment_slot', 'can_delete')) { ?>
                                <button class="ibtnDel btn btn-danger btn-sm btn-danger"> <i
                                        class="fa fa-trash"></i></button>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
                <?php if ($this->rbac->hasPrivilege('online_appointment_slot', 'can_add')) { ?>
                <a id="add_row" class="addrow addbtnright btn btn-primary btn-sm pull-left"><i class="fa fa-plus"></i>
                    <?php echo $this->lang->line('add_new'); ?></a>
                <?php } ?>
            </div>
            <?php if ($this->rbac->hasPrivilege('online_appointment_slot', 'can_edit')) { ?>
            <button class="btn btn-primary btn-sm pull-right" id="Slots_add"
                data-loading-text="<?php echo $this->lang->line('processing'); ?>" type="submit"><i
                    class="fa fa-check-circle"></i> <?php echo $this->lang->line('save'); ?></button>
            <?php } ?>
        </form>
    </div>
</div>
</div>
<?php
$data = $this->session->userdata('hospitaladmin');
$api_base_url = $this->config->item('api_base_url');
?>
<script>
var form_id = "<?= $day ?>";
$(function() {
    $('form#form_' + form_id).on('submit', function(event) {
        console.log(form_id);
        event.preventDefault();
        $('input[id^="time_from_"], input[id^="time_to_"]').each(function() {
            $(this).rules('add', {
                required: true,
                messages: {
                    required: "Required"
                }
            });
        });
        if ($('form#form_' + form_id).validate().form()) {
            $("#Slots_add").button("loading");
            var formData = $('#form_' + form_id).serializeArray();
            var jsonDataArray = [];
            let day = formData.find(item => item.name === 'day')?.value;
            let staff_id = formData.find(item => item.name === 'doctor')?.value;
            let global_shift_id = formData.find(item => item.name === 'shift')?.value;
            let hospital_id = <?= $data['hospital_id'] ?>;

            $('form#form_' + form_id).find('input[id^="time_from_"]').each(function() {
                const id = $(this).attr('id');
                const match = id.match(/time_from_(\d+)/);

                if (match) {
                    const index = match[1];
                    const startTime = $(`form#form_${form_id} input#time_from_${index}`).val();
                    const endTime = $(`form#form_${form_id} input#time_to_${index}`).val();

                    if (startTime && endTime) {
                        const idValue = parseInt(
                            $(`form#form_${form_id} input[name="prev_id_${index}"]`).val(),
                            10
                        );

                        const jsonEntry = {
                            "day": day,
                            "staff_id": parseInt(staff_id),
                            "global_shift_id": parseInt(global_shift_id),
                            "start_time": convertTo24Hour(startTime),
                            "end_time": convertTo24Hour(endTime),
                            "Hospital_id": hospital_id
                        };

                        if (idValue) {
                            jsonEntry["id"] = idValue;
                        }

                        jsonDataArray.push(jsonEntry);
                    }
                }
            });

            const consult = {
                staff_id: parseInt(staff_id),
                consult_duration: parseInt($('#consult_time').val()),
                charge_id: parseInt($('#charge_id').val()),
                Hospital_id: hospital_id
            };
            var errorMessages = [];
            if (!consult.staff_id) errorMessages.push('Staff ID is required');
            if (!consult.consult_duration) errorMessages.push('Consult duration is required');
            if (!consult.charge_id) errorMessages.push('Charge ID is required');
            if (!consult.Hospital_id) errorMessages.push('Hospital ID is required');
            if (errorMessages.length > 0) {
                errorMsg(errorMessages.join(', '));
                resetButton();
                return;
            }
            $.ajax({
                type: 'POST',
                url: "<?= $api_base_url ?>setup_appt_slot_amount",
                data: JSON.stringify(consult),
                headers: {
                    'Authorization': accesstoken
                },
                contentType: 'application/json',
                dataType: 'json',
                processData: false,
                success: function(data) {
                    if (data[0].status === 'failed') {
                        errorMsg(data[0].message);
                        slotUpdate();
                    } else {
                        slotUpdate();
                    }
                }
            });

            function slotUpdate() {
                if (jsonDataArray.length > 0) {
                    $.ajax({
                        type: 'POST',
                        url: "<?= $api_base_url ?>setup-appt-slot-timimgs/updateandpost",
                        data: JSON.stringify(jsonDataArray),
                        contentType: 'application/json',
                        headers: {
                            'Authorization': accesstoken
                        },
                        dataType: 'json',
                        processData: false,
                        success: function(data) {
                            let successMessages = [];
                            let errorMessages = [];
                            data.forEach(item => {
                                if (item.status.toLowerCase() === 'failed') {
                                    errorMessages.push("❌ " + item.message);
                                } else if (item.status.toLowerCase() ===
                                    'success') {
                                    if (!successMessages.includes(item.message)) {
                                        successMessages.push("✅ " + item.message);
                                    }
                                }
                            });
                            if (errorMessages.length > 0) {
                                errorMsg([...errorMessages, ...successMessages].join('\n'));
                            } else {
                                successMsg(successMessages.join('\n'));
                            }
                            resetButton();
                        },
                        error: function(xhr) {
                            resetButton();
                            errorMsg("❌ Something went wrong.");
                        }
                    });
                } else {
                    errorMsg("❌ No valid time slots found!");
                    resetButton();
                }
            }

        } else {
            errorMsg('<?= $this->lang->line("invalid_input"); ?>');
            resetButton();
        }
    });

    $('form#form_' + form_id).validate({
        errorClass: 'text-danger'
    });
});

function convertTo24Hour(time12h) {
    if (typeof time12h !== 'string') {
        console.error('Invalid time format:', time12h);
        return '';
    }
    var [time, modifier] = time12h.split(' ');
    if (!modifier || !time) {
        console.error('Invalid time format:', time12h);
        return '';
    }
    var [hours, minutes] = time.split(':');
    if (hours === '12') {
        hours = '00';
    }
    if (modifier === 'PM') {
        hours = parseInt(hours, 10) + 12;
    }
    hours = hours.toString().padStart(2, '0');
    minutes = minutes.padStart(2, '0');
    return hours + ':' + minutes + ':00';
}

function resetButton() {
    setTimeout(() => {
        $("#Slots_add").button("reset");
    }, 3000);
}

function reloadPage() {
    setTimeout(() => {
        window.location.reload();
    }, 2000);
}
</script>
<script>
function deleteslort(id) {
    if (confirm('Are you sure you want to delete this slot?')) {
        $.ajax({
            type: 'DELETE',
            url: "<?= $api_base_url ?>setup-appt-slot-timimgs/" + id +
                "?Hospital_id=<?= $data['hospital_id'] ?>",
            headers: {
                'Authorization': accesstoken
            },
            success: function(data) {
                if (data.status === 'success') {
                    successMsg(data.message);
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    errorMsg(data.message);
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                }
            },
            error: function(xhr) {},
            complete: function() {}
        });
    }
}
</script>