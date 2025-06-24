<div class="content-wrapper" style="min-height: 348px;">
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
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('doctor_shift'); ?></h3>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages">
                            <table class="table table-striped table-bordered table-hover" id="ajaxlist">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th><?php echo $this->lang->line("doctor_name"); ?></th>
                                        <?php foreach ($global_shift as $gkey => $gvalue) { ?>
                                            <th><?php echo $gvalue['name']; ?></th>
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
<?php
$data = $this->session->userdata('hospitaladmin');
$api_base_url = $this->config->item('api_base_url');
?>
<script>
    function changeShift(doctor_id, shift_id, checkbox) {
        let status;
        if (checkbox.checked) {
            status = 1;
            document.querySelector("#checkbox_print_" + checkbox.dataset.id).innerHTML = "<?= $this->lang->line("yes"); ?>";
        } else {
            status = 0;
            document.querySelector("#checkbox_print_" + checkbox.dataset.id).innerHTML = "<?= $this->lang->line("no"); ?>";
        }
        let fromdata = {
            "staff_id": doctor_id,
            "global_shift_id": shift_id,
            "Hospital_id": <?= json_encode($data['hospital_id']) ?>
        };
        sendAjaxRequest('<?= $api_base_url ?>setup-appointment-doctor-shift', 'POST', fromdata, function(response) {
            handleResponse(response);
        });
    }
</script>
<script>
    $(document).ready(function() {
        $('#ajaxlist').DataTable({
            processing: true,
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
            "aaSorting": [],
            rowReorder: {
                selector: 'td:nth-child(2)'
            },
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
                fetch(
                        `${baseurl}admin/onlineappointment/docglobalshift?limit=${data.length}&page=${page}&search=${data.search.value}`
                    )
                    .then(res => res.json())
                    .then(result => {
                        $("#pageloader").fadeOut();
                        renderDoctorListTable(result.data, result.recordsTotal, data, callback);
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
    function renderDoctorListTable(dataArray, recordCount, data, callback) {
        let count = 0;

        const rows = (dataArray || []).map(item => {
            const doctorId = item.id || 0;
            const globalshifts = item.global_shifts || [];
            let checklistArray = [];
            <?php foreach ($global_shift as $gkey => $gvalue): ?> {
                    const shiftId = <?= $gvalue['id']; ?>;
                    const isChecked = globalshifts.some(s => s.id === shiftId) ? 'checked' : '';
                    const labelText = globalshifts.some(s => s.id === shiftId) ?
                        "<?= $this->lang->line('yes'); ?>" :
                        "<?= $this->lang->line('no'); ?>";
                    const checklist = `
                <td>
                    <input
                        type="checkbox"
                        onclick="changeShift(${doctorId}, ${shiftId}, this)"
                        id="global_shift_${shiftId}"
                        name="global_shift[]"
                        value="${shiftId}"
                        data-id="${doctorId}${shiftId}"
                        ${isChecked}
                    />
                    <span class="hide" id="checkbox_print_${doctorId}${shiftId}">
                        ${labelText}
                    </span>
                </td>
            `;
                    checklistArray.push(checklist);
                }
            <?php endforeach; ?>

            return [
                ++count,
                item.doctor_name || "-",
                ...checklistArray
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