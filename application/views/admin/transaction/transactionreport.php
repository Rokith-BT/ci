<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style type="text/css">
    :root {
        --border-color: #e0e0e0;
        --text-primary: #202124;
        --text-secondary: #5f6368;
        --shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        --card-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        --transition: all 0.3s ease;
    }

    @media print {

        .no-print,
        .no-print * {
            display: none !important;
        }
    }

    .content-wrapper {
        padding: 25px;
        background-color: #f5f7fa;
        min-height: 100vh;
    }

    .box {
        position: relative;
        border-radius: 8px;
        background: #ffffff;
        border-top: 3px solid var(--primary-color);
        margin-bottom: 25px;
        width: 100%;
        box-shadow: var(--shadow);
        overflow: hidden;
    }

    .box-header {
        color: var(--text-primary);
        display: flex;
        align-items: center;
        padding: 20px;
        position: relative;
        border-bottom: 1px solid var(--border-color);
    }

    .box-header h3 {
        margin: 0;
        font-size: 20px;
        font-weight: 600;
        color: var(--primary-color);
    }

    .box-body {
        border-radius: 0 0 8px 8px;
        padding: 20px;
    }

    .select-form-control {
        border-radius: 6px;
        box-shadow: none;
        border: 1px solid var(--border-color);
        height: 40px;
        padding: 8px 15px;
        font-size: 14px;
        transition: var(--transition);
        width: 100%;
    }

    .select-form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 2px rgba(66, 133, 244, 0.2);
        outline: none;
    }

    .btn {
        border-radius: 6px;
        font-weight: 500;
        padding: 8px 16px;
        transition: var(--transition);
        cursor: pointer;
    }

    .btn-primary {
        background-color: var(--primary-color);
        border: none;
        color: white;
        box-shadow: 0 2px 5px rgba(66, 133, 244, 0.3);
    }

    .btn-primary:hover,
    .btn-primary:focus {
        background-color: var(--primary-dark);
        box-shadow: 0 4px 8px rgba(66, 133, 244, 0.4);
        transform: translateY(-1px);
    }

    .btn-default {
        background-color: #f5f5f5;
        border: 1px solid var(--border-color);
        color: var(--text-secondary);
    }

    .btn-default:hover {
        background-color: #e9e9e9;
    }

    .btn-sm {
        padding: 6px 12px;
        font-size: 13px;
    }

    .summary-click {
        font-weight: 600;
        transition: var(--transition);
        cursor: pointer;
    }

    .summary-click[data-type="OPD"] {
        color: var(--accent-color) !important;
    }

    .summary-click[data-type="IPD"] {
        color: var(--warning-color) !important;
    }

    .summary-click[data-type="Appointment"] {
        color: var(--danger-color) !important;
    }

    .summary-click[data-type="others"] {
        color: var(--danger-color) !important;
    }

    .summary-click:hover {
        opacity: 0.8;
        text-decoration: underline;
    }

    #summary-section {
        background-color: white;
        padding: 25px;
        border-radius: 8px;
        box-shadow: var(--shadow);
        margin-top: 20px;
    }

    #summary-title {
        color: var(--primary-color);
        border-bottom: 2px solid var(--primary-color);
        padding-bottom: 12px;
        margin-bottom: 25px;
        font-size: 18px;
        font-weight: 600;
    }

    .form-group {
        margin-bottom: 20px;
    }

    label {
        display: inline-block;
        max-width: 100%;
        margin-bottom: 8px;
        font-weight: 500;
        color: var(--text-secondary);
        font-size: 14px;
    }

    .req {
        color: var(--danger-color);
        margin-left: 3px;
    }

    .text-danger {
        color: var(--danger-color);
    }

    .back-btn {
        margin-top: 15px;
        margin-bottom: 20px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: var(--transition);
    }

    .back-btn:hover {
        transform: translateX(-3px);
    }

    .stats-container {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -10px 20px;
    }

    .stat-card {
        flex: 1;
        min-width: 200px;
        margin: 0 10px 20px;
        background: white;
        border-radius: 10px;
        box-shadow: var(--card-shadow);
        padding: 25px 20px;
        text-align: center;
        position: relative;
        overflow: hidden;
        transition: var(--transition);
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.15);
    }

    .stat-icon {
        font-size: 28px;
        margin-bottom: 15px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 60px;
        height: 60px;
        padding: 0;
        border-radius: 50%;
        background-color: rgba(66, 133, 244, 0.1);
    }

    .stat-value {
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 8px;
        line-height: 1;
    }

    .stat-label {
        font-size: 15px;
        color: var(--text-secondary);
        font-weight: 500;
    }

    .stat-card.primary .stat-icon {
        color: var(--primary-color);
        background-color: rgba(66, 133, 244, 0.1);
    }

    .stat-card.success .stat-icon {
        color: var(--accent-color);
        background-color: rgba(52, 168, 83, 0.1);
    }

    .stat-card.warning .stat-icon {
        color: var(--warning-color);
        background-color: rgba(251, 188, 5, 0.1);
    }

    .stat-card.danger .stat-icon {
        color: var(--danger-color);
        background-color: rgba(234, 67, 53, 0.1);
    }

    .stat-card.primary .stat-value {
        color: var(--primary-color);
    }

    .stat-card.success .stat-value {
        color: var(--accent-color);
    }

    .stat-card.warning .stat-value {
        color: var(--warning-color);
    }

    .stat-card.danger .stat-value {
        color: var(--danger-color);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        background-color: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: var(--shadow);
    }

    table thead th {
        background-color: #f8f9fa;
        color: var(--text-secondary);
        font-weight: 600;
        padding: 15px;
        border-bottom: 2px solid var(--border-color);
        text-align: left;
        font-size: 14px;
    }

    table tbody td {
        padding: 15px;
        border-bottom: 1px solid var(--border-color);
        color: var(--text-primary);
        font-size: 14px;
        vertical-align: middle;
    }

    table tbody tr:last-child td {
        border-bottom: none;
    }

    table tbody tr:hover {
        background-color: rgba(66, 133, 244, 0.03);
    }

    a {
        color: var(--primary-color);
        text-decoration: none;
        transition: var(--transition);
    }

    a:hover {
        color: var(--primary-dark);
        text-decoration: underline;
    }

    .modal-content {
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        border: none;
        overflow: hidden;
    }

    @media (max-width: 768px) {
        .content-wrapper {
            padding: 15px;
        }

        .form-group {
            min-width: 100%;
        }

        .stat-card {
            min-width: calc(50% - 20px);
        }

        .box-header h3 {
            font-size: 18px;
        }

        .stat-value {
            font-size: 28px;
        }

        table {
            display: block;
            overflow-x: auto;
        }
    }

    @media (max-width: 480px) {
        .stat-card {
            min-width: calc(100% - 20px);
        }

        .box-header {
            padding: 15px;
        }

        .box-body {
            padding: 15px;
        }
    }
</style>

<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3><i class="fas fa-chart-bar mr-2"></i> Daily Summary</h3>
                    </div>
                    <div class="row" id="search_filter">
                        <div class="col-md-12">
                            <div class="box-body">
                                <form id="form1" action="" method="post">
                                    <div class="row">
                                        <div class="col-sm-6 col-md-3">
                                            <div class="form-group">
                                                <label>Search Type <span class="req">*</span></label>
                                                <select class="select-form-control" name="search_type"
                                                    id="search_type_select" onchange="showdate(this.value)">
                                                    <option value="" disabled>Select</option>
                                                    <option value="today" selected>Today</option>
                                                    <option value="this_week">This Week</option>
                                                    <option value="last_week">Last Week</option>
                                                    <option value="this_month">This Month</option>
                                                    <option value="last_month">Last Month</option>
                                                    <option value="last_3_month">Last 3 Months</option>
                                                    <option value="last_6_month">Last 6 Months</option>
                                                    <option value="last_12_month">Last 12 Months</option>
                                                    <option value="this_year">This Year</option>
                                                    <option value="last_year">Last Year</option>
                                                    <option value="period">Period</option>
                                                </select>
                                                <span class="text-danger" id="error_search_type"></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-3" id="fromdate" style="display: none">
                                            <div class="form-group">
                                                <label>Date From <span class="req">*</span></label>
                                                <input id="date_from" name="select-form-control" placeholder=""
                                                    type="text" class="select-form-control date"
                                                    value="<?php echo set_value('date_from', date($this->customlib->getHospitalDateFormat())); ?>">
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-3" id="todate" style="display: none">
                                            <div class="form-group">
                                                <label>Date To <span class="req">*</span></label>
                                                <input id="date_to" name="select-form-control" placeholder=""
                                                    type="text" class="select-form-control date"
                                                    value="<?php echo set_value('date_to', date($this->customlib->getHospitalDateFormat())); ?>">
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-3">
                                            <div class="form-group" style="margin-top: 28px;">
                                                <button type="submit" name="search" value="search_filter"
                                                    class="btn btn-primary"><i class="fa fa-search mr-1"></i>
                                                    Search</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="stats-container">
                            <div class="stat-card primary">
                                <div class="stat-icon"><i class="fas fa-exchange-alt"></i></div>
                                <div class="stat-value" id="total_transation">0</div>
                                <div class="stat-label">Total Transactions</div>
                            </div>
                            <div class="stat-card success">
                                <div class="stat-icon"><i class="fas fa-user-md"></i></div>
                                <div class="stat-value" id="total_opd">0</div>
                                <div class="stat-label">Total OPD</div>
                            </div>
                            <div class="stat-card warning">
                                <div class="stat-icon"><i class="fas fa-procedures"></i></div>
                                <div class="stat-value" id="total_ipd">0</div>
                                <div class="stat-label">Total IPD</div>
                            </div>
                            <div class="stat-card danger">
                                <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
                                <div class="stat-value" id="total_appointment">0</div>
                                <div class="stat-label">Total Appointments</div>
                            </div>
                        </div>

                        <div class="table-responsive" id="table-container">
                            <table class="table table-striped table-bordered table-hover" id="ajaxlist">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Date</th>
                                        <th>Appointment</th>
                                        <th>O/P</th>
                                        <th>I/P</th>
                                        <th>Others</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody id="table-body"></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div id="summary-section" style="display:none; margin-top:-22px;">
                    <button class="btn btn-default back-btn" id="back-to-main"><i class="fa fa-arrow-left"></i> Back
                        to Main Report</button>
                    <h4 id="summary-title"></h4>
                    <div id="payment-summary-tags"></div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover ipdcharged" id="summary-table">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Date</th>
                                    <th>Bill ID</th>
                                    <th>Patient Name</th>
                                    <th>Consultant</th>
                                    <th>Referred By</th>
                                    <th>Provider/TPA</th>
                                    <th>Amount</th>
                                    <th>Payment Type</th>
                                    <th>Received By</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<div class="modal fade" id="billModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id="billContent"></div>
    </div>
</div>
<?php
$data = $this->session->userdata('hospitaladmin');
$api_base_url = $this->config->item('api_base_url');
?>
<script>
    $(document).on("click", ".view-opd-bill", function(e) {
        e.preventDefault();
        let transationid = $(this).data("id");
        let type = $(this).data("type");
        let opdorid_Id = $(this).data("opdoripdid");
        let hospitalId = '<?= $data["hospital_id"] ?>';
        $.ajax({
            url: baseurl + 'admin/transaction/getdetialsbyidopdipd',
            type: "GET",
            data: {
                transationId: transationid,
                hospital_id: hospitalId,
                type: type,
                opdoridId: opdorid_Id
            },
            success: function(response) {
                $("#billContent").html(response);
                $("#billModal").modal("show");
            }
        });
    });

    function showdate(value) {
        if (value == 'period') {
            $('#fromdate').show();
            $('#todate').show();
        } else {
            $('#fromdate').hide();
            $('#todate').hide();
        }
    }
</script>
<script>
    $(document).ready(function() {
        var dataTable = $('#ajaxlist').DataTable({
            columnDefs: [{
                orderable: false,
                targets: -1
            }],
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
            language: {
                emptyTable: "Select the Filter to get report"
            },
            ajax: function(data, callback) {
                $("#pageloader").fadeIn();
                var formData = $('#form1').serializeArray();
                const page = Math.floor(data.start / data.length) + 1;
                $.ajax({
                    url: baseurl + "admin/transaction/dailysummaryreport",
                    type: "POST",
                    data: formData,
                    dataType: "json",
                    success: function(response) {
                        const counts = response.data?.counts?.[0] || {};
                        $("#total_transation").text((+counts.totalcount || 0)
                            .toLocaleString('en-IN'));
                        $("#total_opd").text((+counts.opdcount || 0).toLocaleString(
                            'en-IN'));
                        $("#total_ipd").text((+counts.ipdcount || 0).toLocaleString(
                            'en-IN'));
                        $("#total_appointment").text((+counts.appointmentCount || 0)
                            .toLocaleString(
                                'en-IN'));
                        $("#total_other").text((+counts.othercount || 0).toLocaleString(
                            'en-IN'));
                        callback({
                            draw: data.draw,
                            recordsTotal: response.recordsTotal,
                            recordsFiltered: response.recordsFiltered,
                            data: renderTable(response.data.details)
                        });
                        $("#pageloader").fadeOut();
                    },
                    error: function() {
                        $("#pageloader").fadeOut();
                        callback({
                            draw: data.draw,
                            recordsTotal: 0,
                            recordsFiltered: 0,
                            data: []
                        });
                    }
                });
            },
        });
        $('#form1').on('submit', function(event) {
            event.preventDefault();
            dataTable.ajax.reload();
        });
    });

    function renderTable(dataArray) {
        let count = 0;
        return (dataArray || []).map(function(row) {
            return [
                ++count,
                `<td>${formatDate(row.paymentDate)}</td>`,
                `<td><span class="summary-click" data-type="Appointment" data-date="${row.paymentDate}" style="cursor:pointer; color:#ea4335;">₹${Number(row.appointmentAmount).toFixed(2).toLocaleString('en-IN')}</span></td>`,
                `<td><span class="summary-click" data-type="OPD" data-date="${row.paymentDate}" style="cursor:pointer; color:#34a853;">₹${Number(row.opdAmount).toFixed(2).toLocaleString('en-IN')}</span></td>`,
                `<td><span class="summary-click" data-type="IPD" data-date="${row.paymentDate}" style="cursor:pointer; color:#fbbc05;">₹${Number(row.ipdAmount).toFixed(2).toLocaleString('en-IN')}</span></td>`,
                `<td><span class="summary-click" data-type="Others" data-date="${row.paymentDate}" style="cursor:pointer; color:#4285f4;">₹${Number(row.otherAmount).toFixed(2).toLocaleString('en-IN')}</span></td>`,
                `<td><span>₹${Number(row.totalAmount).toFixed(2).toLocaleString('en-IN')}</span></td>`
            ];
        });
    }

    function formatDate(input) {
        const d = new Date(input);
        return `${String(d.getDate()).padStart(2, '0')}/${String(d.getMonth() + 1).padStart(2, '0')}/${d.getFullYear()}`;
    }
    $(document).on('click', '.summary-click', function() {
        const date = $(this).data('date');
        const type = $(this).data('type');
        $('#summary-title').text(`${type} Payment Summary`);
        $('#summary-section').show();
        $('#table-container').hide();
        $('.stats-container').hide();
        $('#search_filter').hide();

        if ($.fn.DataTable.isDataTable('#summary-table')) {
            $('#summary-table').DataTable().clear().destroy();
        }
        $('#summary-table').DataTable({
            columnDefs: [{
                orderable: false,
                targets: -1
            }],
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
            language: {
                emptyTable: "Select the Filter to get report"
            },
            ajax: function(data, callback) {
                $("#pageloader").fadeIn();
                const page = Math.floor(data.start / data.length) + 1;
                $.ajax({
                    url: baseurl + "admin/transaction/dailysummaryreportdetails?type=" + type +
                        "&date=" + date,
                    type: "GET",
                    dataType: "json",
                    success: function(response) {
                        callback({
                            draw: data.draw,
                            recordsTotal: response.recordsTotal || 0,
                            recordsFiltered: response.recordsFiltered || 0,
                            data: renderdetialsTable(response.data || [], type)
                        });
                        updatePaymentSummary(response.payment_summary);
                        $("#pageloader").fadeOut();
                    },
                    error: function() {
                        $("#pageloader").fadeOut();
                        callback({
                            draw: data.draw,
                            recordsTotal: 0,
                            recordsFiltered: 0,
                            data: []
                        });
                    }
                });
            }
        });
    });

    function renderdetialsTable(dataArray, type) {
        let count = 0;
        return (dataArray || []).map(function(row) {
            let trans_prefix = '<?= $this->customlib->getSessionPrefixByType('transaction_id') ?>';
            return [
                ++count,
                formatDate(row.payment_date) || "-",
                row.id ?
                `<a href="#" class="view-opd-bill" data-opdoripdid="${row.section_id}" data-type="${type}" data-id="${row.id}">${trans_prefix}${row.id}</a>` :
                "-",
                row.patient_name || "-",
                row.staff_first_name || "-",
                row.referred_by || "-",
                row.provider_tpa || "-",
                `₹${Number(row.amount || 0).toFixed(2).toLocaleString('en-IN')}`,
                row.payment_mode || "-",
                row.received_by || "-"
            ];
        });
    }
    $('#back-to-main').on('click', function() {
        $('#summary-section').hide();
        $('#table-container').show();
        $('.stats-container').show();
        $('#search_filter').show();
    });
</script>