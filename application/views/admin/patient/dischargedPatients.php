<?php
$currency_symbol = $this->customlib->getHospitalCurrencyFormat();
$genderList = $this->customlib->getGender();
?>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title titlefix"> <?php echo $this->lang->line('ipd_discharged_patient'); ?></h3>

                        <div class="box-tools pull-right">

                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="download_label"> <?php echo $this->lang->line('ipd_discharged_patient'); ?></div>

                        <table class="table table-striped table-bordered table-hover" id="ajaxlist">
                            <thead class="thead-light">
                                <tr>
                                    <th>S.No</th>
                                    <th><?php echo $this->lang->line('name') ?></th>
                                    <th><?php echo $this->lang->line('patient_id'); ?></th>
                                    <th><?php echo $this->lang->line('case_id'); ?></th>
                                    <th><?php echo $this->lang->line('gender'); ?></th>
                                    <th><?php echo $this->lang->line('phone'); ?></th>
                                    <th><?php echo $this->lang->line('consultant') ?></th>
                                    <th><?php echo $this->lang->line('admission_date'); ?></th>
                                    <th><?php echo $this->lang->line('discharged_date'); ?></th>
                                </tr>
                            </thead>
                            <tbody id="table-body"></tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </section>
</div>
<script type="text/javascript">
$(function() {
    //Initialize Select2 Elements
    $('.select2').select2()
});
$(function() {
    $('#easySelectable').easySelectable();
})
</script>


<script type="text/javascript">
(function($) {
    //selectable html elements
    $.fn.easySelectable = function(options) {
        var el = $(this);
        var options = $.extend({
            'item': 'li',
            'state': true,
            onSelecting: function(el) {

            },
            onSelected: function(el) {

            },
            onUnSelected: function(el) {

            }
        }, options);
        el.on('dragstart', function(event) {
            event.preventDefault();
        });
        el.off('mouseover');
        el.addClass('easySelectable');
        if (options.state) {
            el.find(options.item).addClass('es-selectable');
            el.on('mousedown', options.item, function(e) {
                $(this).trigger('start_select');
                var offset = $(this).offset();
                var hasClass = $(this).hasClass('es-selected');
                var prev_el = false;
                el.on('mouseover', options.item, function(e) {
                    if (prev_el == $(this).index())
                        return true;
                    prev_el = $(this).index();
                    var hasClass2 = $(this).hasClass('es-selected');
                    if (!hasClass2) {
                        $(this).addClass('es-selected').trigger('selected');
                        el.trigger('selected');
                        options.onSelecting($(this));
                        options.onSelected($(this));
                    } else {
                        $(this).removeClass('es-selected').trigger('unselected');
                        el.trigger('unselected');
                        options.onSelecting($(this))
                        options.onUnSelected($(this));
                    }
                });
                if (!hasClass) {
                    $(this).addClass('es-selected').trigger('selected');
                    el.trigger('selected');
                    options.onSelecting($(this));
                    options.onSelected($(this));
                } else {
                    $(this).removeClass('es-selected').trigger('unselected');
                    el.trigger('unselected');
                    options.onSelecting($(this));
                    options.onUnSelected($(this));
                }
                var relativeX = (e.pageX - offset.left);
                var relativeY = (e.pageY - offset.top);
            });
            $(document).on('mouseup', function() {
                el.off('mouseover');
            });
        } else {
            el.off('mousedown');
        }
    };
})(jQuery);
</script>
<script>
const initialData = <?= json_encode($ipd_discharge_patient_list['data']) ?>;
const totalRecords = <?= $ipd_discharge_patient_list['recordsFiltered'] ?>;
$(document).ready(function() {
    $('#ajaxlist').DataTable({
        serverSide: true,
        searching: true,
        ordering: true,
        paging: true,
        lengthMenu: [5, 10, 25, 50],
        pageLength: 10,
        columnDefs: [{
            orderable: false,
            targets: [2, 5, 6]
        }],
        dom: 'Blfrtip',
        buttons: [
            { extend: 'copyHtml5', text: '<i class="fa fa-files-o"></i>', titleAttr: 'Copy', className: 'btn btn-default btn-copy' },
            { extend: 'excelHtml5', text: '<i class="fa fa-file-excel-o"></i>', titleAttr: 'Excel', className: 'btn btn-default btn-excel' },
            { extend: 'csvHtml5', text: '<i class="fa fa-file-text-o"></i>', titleAttr: 'CSV', className: 'btn btn-default btn-csv' },
            { extend: 'pdfHtml5', text: '<i class="fa fa-file-pdf-o"></i>', titleAttr: 'PDF', className: 'btn btn-default btn-pdf' },
            { extend: 'print', text: '<i class="fa fa-print"></i>', titleAttr: 'Print', className: 'btn btn-default btn-print' }
        ],
        language: {
            emptyTable: "No appointments found"
        },
        ajax: function(data, callback) {
            if (initialData && initialData.length > 0) {
                renderTable(initialData, totalRecords, data, callback);
                initialData.length = 0;
                return;
            }
            $("#pageloader").fadeIn();
            const page = Math.floor(data.start / data.length) + 1;
            fetch(
                    `${baseurl}admin/patient/ipddischargedPatients?limit=${data.length}&page=${page}&search=${data.search.value}`)
                .then(res => res.json())
                .then(result => {
                    $("#pageloader").fadeOut();
                    renderTable(result.data, result.recordsTotal, data, callback);
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

function renderTable(dataArray, recordCount, data, callback) {
    let count = data.start || 0;
    const rows = (dataArray || []).map(item => {
        const ipd_id = item.IPD_No || "-";
        const name = item.PatientName || "-";
        const PatientID = item.PatientID || "-";
        const caseId = item.Case_ID || "-";
        const gender = item.Gender || "-";
        const mobile = item.Phone || "-";
        const staff = item.Consultant || "-";
        const AdmissionDate = formatDate(item.AdmissionDate);
        const DischargedDate = formatDate(item.DischargedDate);
        const Tax = item.Tax || "-";
        const NetAmount = item.NetAmount || "-";
        const Total = item.Total || "-";

        return [
            ++count,
            name,
            PatientID,
            caseId,
            gender,
            mobile,
            staff,
            AdmissionDate,
            DischargedDate,
            Tax,
            NetAmount,
            Total
        ];
    });

    callback({
        draw: data.draw,
        recordsTotal: recordCount,
        recordsFiltered: recordCount,
        data: rows
    });

    setTimeout(() => {
        $('[data-toggle="tooltip"]').tooltip();
    }, 100);
}

function formatDate(dateStr) {
    if (!dateStr) return "-";
    const date = new Date(dateStr);
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();
    return `${day}/${month}/${year}`;
}
</script>