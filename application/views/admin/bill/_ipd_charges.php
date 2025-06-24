<style>
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    .font-weight-bold {
        font-weight: 600;
    }
    .text-center {
        text-align: center;
    }
    .text-right {
        text-align: right;
    }
    .text-left {
        text-align: left;
    }
    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    .btn-sm {
        padding: 5px 10px;
        font-size: 12px;
        border-radius: 3px;
        margin-left: 5px;
        transition: all 0.3s ease;
    }
    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        color: white;
    }
    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #0056b3;
    }
</style>
<?php
$userdata = $this->session->userdata('hospitaladmin');
$accessToken = $userdata['accessToken'] ?? '';

$api_base_url = $this->config->item('api_base_url');
$ipdid = $ipdid;
$url = $api_base_url . "billing/v2/get_billing_details_by_ipdID?ipd_id=$ipdid&search=&limit=10&page=1";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: ' . $accessToken
]);
$response = curl_exec($ch);
curl_close($ch);
$responseData = json_decode($response, true);
$billing_data = [
    "recordsTotal" => (int)($responseData['total'] ?? 0),
    "recordsFiltered" => (int)($responseData['total'] ?? 0),
    "data" => $responseData['data'] ?? [],
    "totalTaxAmount" => $responseData['totalTaxAmount'] ?? 0,
    "totalApplyCharge" => $responseData['totalApplyCharge'] ?? 0,
    "totalAmount" => $responseData['totalAmount'] ?? 0,
    "paid" => $responseData['paid'] ?? 0,
    "unpaid" => $responseData['unpaid'] ?? 0
];
$currency_symbol = $this->customlib->getHospitalCurrencyFormat();
?>
<?php if (!empty($billing_data)) { ?>
<div class="box-tools pull-right box-miustop">
    <?php if ($this->rbac->hasPrivilege('ipd_billing_payment', 'can_add')) { ?>
        <a href="javascript:void(0);" data-caseid="<?= $ipdid ?>" data-balance-amount="<?= $billing_data['unpaid'] ?>" data-module="ipd" data-record-id="<?= $ipdid ?>" class="btn btn-primary btn-sm add_payment">
            <i class="fa fa-money"></i> Make Payment
        </a>
    <?php } ?>
    <?php if ($this->rbac->hasPrivilege('ipd_billing_payment', 'can_view')) { ?>
        <a href="javascript:void(0);" data-module-value="IPD" data-case_id="<?= $ipdid ?>" data-module_type="ipd_id" data-record-id="<?= $ipdid ?>" class="btn btn-primary btn-sm view_payment">
            <i class="fa fa-money"></i> <?= $this->lang->line('view_payments') ?>
        </a>
    <?php } ?>
    <?php if ($this->rbac->hasPrivilege('generate_bill', 'can_view') && (!isset($_GET['case']) || $_GET['case'] != "patient_id")) { ?>
        <a href="javascript:void(0);" class="btn btn-primary btn-sm view_generate_bill" data-module-value="IPD" data-record-id="<?= $ipdid ?>" data-module_type="ipd" data-case-id="<?= $ipdid ?>">
            <i class="fas fa-exchange-alt"></i> <?= $this->lang->line('generate_bill') ?>
        </a>
    <?php } ?>
</div>
<?php } ?>

<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover ipdcharged" id="ajaxlistipd">
        <thead class="thead-light">
            <tr>
                <th>S.No</th>
                <th class="text-center">IPD ID</th>
                <th class="text-right">Date</th>
                <th class="text-right">Name</th>
                <th class="text-right">Charge Type</th>
                <th class="text-right">Charge Category</th>
                <th class="text-right">Qty</th>
                <th class="text-right">ApplyCharge</th>
                <th class="text-right">Tax</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody id="table-body"></tbody>
    </table>
</div>
<script>
const initialData = <?= json_encode($responseData) ?>;
const totalRecords = <?= $billing_data["recordsFiltered"] ?>;
const ipdid = <?= $ipdid ?>;
$(document).ready(function () {
    $('#ajaxlistipd').DataTable({
        serverSide: true,
        searching: true,
        ordering: true,
        paging: true,
        lengthMenu: [5, 10, 25, 50],
        pageLength: 10,
        columnDefs: [{ orderable: false, targets: [2, 5, 6] }],
        dom: 'Blfrtip',
        buttons: [
            { extend: 'copyHtml5', text: '<i class="fa fa-files-o"></i>', titleAttr: 'Copy', className: 'btn btn-default btn-copy' },
            { extend: 'excelHtml5', text: '<i class="fa fa-file-excel-o"></i>', titleAttr: 'Excel', className: 'btn btn-default btn-excel' },
            { extend: 'csvHtml5', text: '<i class="fa fa-file-text-o"></i>', titleAttr: 'CSV', className: 'btn btn-default btn-csv' },
            { extend: 'pdfHtml5', text: '<i class="fa fa-file-pdf-o"></i>', titleAttr: 'PDF', className: 'btn btn-default btn-pdf' },
            { extend: 'print', text: '<i class="fa fa-print"></i>', titleAttr: 'Print', className: 'btn btn-default btn-print' }
        ],
        language: { emptyTable: "No appointments found" },
        ajax: function (data, callback) {
            if (initialData && initialData.length > 0) {
                renderTable({ totaldata: { data: initialData } }, totalRecords, data, callback);
                initialData.length = 0;
                return;
            }
            $("#pageloader").fadeIn();
            const page = Math.floor(data.start / data.length) + 1;
            fetch(`${baseurl}admin/bill/getipdbillinglist?ipdid=${ipdid}&limit=${data.length}&page=${page}&search=${data.search.value}`)
                .then(res => res.json())
                .then(result => {
                    $("#pageloader").fadeOut();
                    renderTable(result, result.recordsTotal, data, callback);
                })
                .catch(() => {
                    $("#pageloader").fadeOut();
                    callback({ draw: data.draw, recordsTotal: 0, recordsFiltered: 0, data: [] });
                });
        }
    });
});

function renderTable(apiResponse, recordCount, data, callback) {
    let count = data.start || 0;
    const totals = apiResponse.totaldata || {};
    const rows = (totals.data || []).map(item => [
        ++count,
        item.IPD_No,
        formatDateDMY(item.ChargesDate),
        item.charge_name,
        item.charge_type,
        item.charge_category,
        item.QTY,
        item.ApplyCharge,
        item.TAX,
        item.AMOUNT,
    ]);

    rows.push([
        '', '', '', '', '', '',
        ``,
        `<strong>Total Tax: ${totals.totalTaxAmount || 0}</strong>`,
        `<strong>Total Amount: ${totals.totalAmount || 0}</strong>`,
        `<strong>Paid: ${totals.paid || 0} | Unpaid: ${totals.unpaid || 0}</strong>`
    ]);
    callback({ draw: data.draw, recordsTotal: recordCount, recordsFiltered: recordCount, data: rows });
    setTimeout(() => $('[data-toggle="tooltip"]').tooltip(), 100);
}

function formatDateDMY(dateStr) {
    const date = new Date(dateStr);
    return `${String(date.getDate()).padStart(2, '0')}/${String(date.getMonth() + 1).padStart(2, '0')}/${date.getFullYear()}`;
}
</script>


