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
$currency_symbol = $this->customlib->getHospitalCurrencyFormat();
$url = $api_base_url . "billing/v2/get_billing_details_by_opdID?opd_id=$opd_id&search=&limit=10&page=1";
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
$charges_detail = $billing_data['data'];
$net_amount = $billing_data['totalAmount'];
$paid_amount = $billing_data['paid'];
$total_tax = $billing_data['totalTaxAmount'];
?>

<?php if (!empty($billing_data)) { ?>
    <div class="box-tools pull-right box-miustop">
        <?php if ($this->rbac->hasPrivilege('opd_billing_payment', 'can_add')) { ?>
            <a href="javascript:void(0);" class="btn btn-primary btn-sm add_payment"
                data-record-id=""
                data-balance-amount="<?php echo $net_amount - $paid_amount; ?>"
                data-case-id-opd=""
                data-toggle="tooltip">
                <i class="fa fa-money"></i> Make Payment
            </a>
        <?php } ?>
        <?php if ($this->rbac->hasPrivilege('opd_billing_payment', 'can_view')) { ?>
            <a href="javascript:void(0);" class="btn btn-primary btn-sm view_payment"
                data-record-id=""
                data-case-id=""
                data-module-value="OPD"
                data-toggle="tooltip">
                <i class="fa fa-money"></i> <?php echo $this->lang->line('view_payments'); ?>
            </a>
        <?php } ?>
        <?php if ($this->rbac->hasPrivilege('generate_bill', 'can_view')) { ?>
            <?php if (!isset($_GET['case']) || $_GET['case'] != "patient_id") { ?>
                <a href="javascript:void(0);" class="btn btn-primary btn-sm text-right view_generate_bill"
                    data-case-id=""
                    data-record-id=""
                    data-toggle="tooltip">
                    <i class="fas fa-exchange-alt"></i> <?php echo $this->lang->line('generate_bill'); ?>
                </a>
            <?php } ?>
        <?php } ?>
    </div>
<?php } ?>

<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover ipdcharged" id="ajaxlistopd">
        <thead class="thead-light">
            <tr>
                <th>S.No</th>
                <th class="text-center">OPD ID</th>
                <th class="text-center"><?php echo $this->lang->line('date'); ?></th>
                <th class="text-center"><?php echo $this->lang->line('name'); ?></th>
                <th class="text-center"><?php echo $this->lang->line('charge_type'); ?></th>
                <th class="text-center"><?php echo $this->lang->line('charge_category'); ?></th>
                <th class="text-center">Standard Charge</th>
                <th class="text-center"><?php echo $this->lang->line('qty'); ?></th>
                <th class="text-center"><?php echo $this->lang->line('apply_charge'); ?></th>
                <th class="text-center">Additional Charge</th>
                <th class="text-center">Discount Amount</th>
                <th class="text-center">Sub Total</th>
                <th class="text-center"><?php echo $this->lang->line('tax'); ?></th>
                <th class="text-center"><?php echo $this->lang->line('net_amount'); ?></th>
            </tr>
        </thead>
        <tbody id="table-body"></tbody>
    </table>
</div>

<script>
    const initialData = <?= json_encode($responseData) ?>;
    const totalRecords = <?= $billing_data["recordsFiltered"] ?>;
    const opdId = <?= $opd_id ?>;
    let firstDrawDone = false;

    $(document).ready(function() {
        $('#ajaxlistopd').DataTable({
            serverSide: true,
            searching: true,
            ordering: true,
            paging: true,
            lengthMenu: [1, 10, 25, 50],
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
                emptyTable: "No billing records found"
            },
            ajax: function(data, callback) {
                if (!firstDrawDone && initialData && Array.isArray(initialData.data)) {
                    renderTable({
                        totaldata: initialData
                    }, totalRecords, data, callback);
                    firstDrawDone = true;
                    return;
                }
                $("#pageloader").fadeIn();
                const page = Math.floor(data.start / data.length) + 1;
                fetch(`${baseurl}admin/bill/getopdbillinglist?opd_id=${opdId}&limit=${data.length}&page=${page}&search=${data.search.value}`)
                    .then(res => res.json())
                    .then(result => {
                        $("#pageloader").fadeOut();
                        renderTable(result, result.recordsTotal, data, callback);
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

    function renderTable(apiResponse, recordCount, data, callback) {
        let count = data.start || 0;
        const totals = apiResponse.totaldata || {};
        const records = Array.isArray(totals.data) ? totals.data : [];
        const rows = records.map(item => [
            ++count,
            item.OPD_No,
            formatDateDMY(item.ChargesDate),
            item.charge_name,
            item.charge_type,
            item.charge_category,
            item.StandardCharge || 0,
            item.QTY || 0,
            item.ApplyCharge || 0,
            item.AdditionalCharge || 0,
            item.DiscountAmount || 0,
            item.SubTotal || 0,
            item.TAX || 0,
            item.NetAmount || 0
        ]);
        rows.push([
            '', '', '', '', '', '', '', '', '',
            `<strong>Total Tax: ${totals.totalTaxAmount || 0}</strong>`,
            `<strong>Total Amount: ${totals.totalAmount || 0}</strong>`,
            `<strong>Paid: ${totals.paid || 0} | </strong>`,
            `<strong>Unpaid: ${totals.unpaid || 0}</strong>`,
            ``,
        ]);
        callback({
            draw: data.draw,
            recordsTotal: recordCount,
            recordsFiltered: recordCount,
            data: rows
        });
        setTimeout(() => $('[data-toggle="tooltip"]').tooltip(), 100);
    }

    function formatDateDMY(dateStr) {
        const date = new Date(dateStr);
        return `${String(date.getDate()).padStart(2, '0')}/${String(date.getMonth() + 1).padStart(2, '0')}/${date.getFullYear()}`;
    }
</script>