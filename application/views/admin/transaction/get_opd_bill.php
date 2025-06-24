<?php
$opd_no = $opd_or_ipd_id ?? '-';
$patient_name = $invoicedetials["name"] ?? '-';
$patient_id = $invoicedetials["patient_id"] ?? '-';
$bill_no = $transationid ?? '-';
$age = $invoicedetials["age"] ?? '-';
$gender = $invoicedetials["gender"] ?? '-';
$consultant = $invoicedetials["consultant"] ?? '-';
$date = $invoicedetials["date"] ?? '-';
$mobile = $invoicedetials["mobile"] ?? '-';
$payment_type = $invoicedetials["payment_type"] ?? '-';
$items = $invoicedetials["items"] ?? [];
$amount_receivable = isset($invoicedetials["amount_receivable"]) ? number_format((float)$invoicedetials["amount_receivable"], 2, '.', '') : '-';
$amount_received = isset($invoicedetials["amount_received"]) ? number_format((float)$invoicedetials["amount_received"], 2, '.', '') : '-';
$loyalty_points = $invoicedetials["loyalty_points"] ?? '-';
$received_by = $invoicedetials["received_by"] ?? '-';
$data = $this->session->userdata('hospitaladmin');
?>

<link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/sh-print.css">
<style>
    .bill-container {
        font-family: 'Arial', sans-serif;
        padding: 15px;
        border: 1px solid #ddd;
        border-radius: 5px;
        background-color: #fff;
    }

    .bill-header {
        border-bottom: 2px solid #005b96;
        padding-bottom: 10px;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .bill-title {
        font-size: 24px;
        font-weight: bold;
        color: #005b96;
        margin: 0;
    }

    .info-row {
        margin-bottom: 10px;
        display: flex;
        flex-wrap: wrap;
    }

    .info-group {
        flex: 1;
        min-width: 250px;
        padding: 5px 15px;
        margin-bottom: 5px;
    }

    .info-label {
        font-weight: bold;
        color: #444;
        margin-right: 5px;
    }

    .info-value {
        color: #000;
    }

    .table-container {
        margin: 20px 0;
    }

    .bill-table {
        width: 100%;
        border-collapse: collapse;
    }

    .bill-table th {
        background-color: #005b96;
        color: white;
        padding: 10px;
        text-align: left;
    }

    .bill-table td {
        padding: 8px 10px;
        border-bottom: 1px solid #ddd;
    }

    .bill-table tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .bill-footer {
        margin-top: 20px;
        /* border-top: 1px solid #ddd; */
        padding-top: 15px;
    }

    .totals-section {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
    }

    .print-button {
        background-color: #005b96;
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 4px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        margin-right: 10px;
    }

    .print-button:hover {
        background-color: #003b6f;
    }

    .print-icon {
        margin-right: 5px;
    }

    .modal-actions {
        display: flex;
        justify-content: flex-end;
        margin-top: 15px;
    }

    @media print {

        .modal-header,
        .modal-actions,
        .close {
            display: none !important;
        }

        .bill-container {
            border: none;
        }
    }
</style>

<div class="modal-header modal-media-header">
    <button type="button" class="close" data-toggle="tooltip"
        data-original-title="<?php echo $this->lang->line('close'); ?>" data-dismiss="modal">&times;</button>
    <h4 class="modal-title"><?= $type ?> Bill</h4>
</div>

<div class="modal-body">
    <?php if (!empty($print_details[0]['print_header'])) { ?>
        <div class="pprinta4">
            <?php
            $logo_image = base_url() . "uploads/staff_images/no_image.png";
            if (!empty($print_details[0]['print_header'])) {
                $url = "https://phr-api.plenome.com/file_upload/getDocs";
                $payload = json_encode(['value' => $print_details[0]['print_header']]);
                $client = curl_init($url);
                curl_setopt_array($client, [
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => $payload,
                    CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
                ]);
                $response = curl_exec($client);
                curl_close($client);
                if ($response && strpos($response, '"NoSuchKey"') === false) {
                    $logo_image = "data:image/png;base64," . trim($response);
                } elseif (!empty($print_details[0]['print_header'])) {
                    $logo_image = base_url() . $print_details[0]['print_header'];
                }
            }
            ?>
            <img src="<?= $logo_image ?>" class="img-responsive" style="height:100px; width: 100%;">
        </div>
    <?php } ?>
    <div class="bill-container">
        <div class="bill-header">
            <h2 class="bill-title"><?= $type ?> Bill</h2>
            <div>Bill No: <strong><?= $bill_no ?></strong></div>
        </div>

        <div class="info-row">
            <div class="info-group">
                <span class="info-label">Name:</span>
                <span class="info-value"><?= $patient_name ?></span>
            </div>
            <div class="info-group">
                <span class="info-label">Patient ID:</span>
                <span class="info-value"><?= $patient_id ?></span>
            </div>
        </div>

        <div class="info-row">
            <div class="info-group">
                <span class="info-label"><?= $prifix ?>:</span>
                <span class="info-value"><?= $opd_no ?></span>
            </div>
            <div class="info-group">
                <span class="info-label">Date:</span>
                <span class="info-value"><?= $date ?></span>
            </div>
        </div>

        <div class="info-row">
            <div class="info-group">
                <span class="info-label">Age:</span>
                <span class="info-value"><?= $age ?> years</span>
            </div>
            <div class="info-group">
                <span class="info-label">Gender:</span>
                <span class="info-value"><?= $gender ?></span>
            </div>
        </div>

        <div class="info-row">
            <div class="info-group">
                <span class="info-label">Consultant:</span>
                <span class="info-value"><?= $consultant ?></span>
            </div>
            <div class="info-group">
                <span class="info-label">Mobile:</span>
                <span class="info-value"><?= $mobile ?></span>
            </div>
        </div>
        <div class="table-container">
            <table class="bill-table">
                <thead>
                    <tr>
                        <th>Charge Name</th>
                        <th>Amount</th>
                        <th>Disc(%)</th>
                        <!-- <th>Discount</th> -->
                        <th>Additional Charges</th>
                        <th>Tax(%)</th>
                        <th>Payment Type</th>
                        <th>Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?= $item['description'] ?? '-' ?></td>
                            <td><?= isset($item['price']) ? '₹' . number_format((float)$item['price'], 2, '.', '') : '-' ?></td>
                            <td><?= isset($item['discount']) ? number_format((float)$item['discount'], 2, '.', '') . '%' : '-' ?></td>
                            <!-- <td><?= isset($item['discount_amount']) ? '₹' . number_format((float)$item['discount_amount'], 2, '.', '') : '-' ?></td> -->
                            <td><?= isset($item['additional_charge']) ? '₹' . number_format((float)$item['additional_charge'], 2, '.', '') : '-' ?></td>
                            <td><?= isset($item['tax']) ? number_format((float)$item['tax'], 2, '.', '') . '%' : '-' ?></td>
                            <td><?= isset($item['payment_mode']) ? $item['payment_mode'] : '-' ?></td>
                            <td><?= isset($item['total']) ? '₹' . number_format((float)$item['total'], 2, '.', '') : '-' ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="bill-footer">
            <div class="totals-section">
                <div>
                    <div><span class="info-label">Amount Receivable:</span> ₹<?= number_format((float)$amount_receivable, 2, '.', '') ?></div>
                </div>
                <div>
                    <div><span class="info-label">Amount Received:</span> ₹<?= number_format((float)$amount_received, 2, '.', '') ?></div>
                    <div><span class="info-label">Received By:</span> <?= $received_by ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-actions">
        <button class="print-button" onclick="printpdf(<?= $billid ?>, '<?= $type ?>')">
            <svg class="print-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="6 9 6 2 18 2 18 9"></polyline>
                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                <rect x="6" y="14" width="12" height="8"></rect>
            </svg>
            Print Bill
        </button>
    </div>
</div>
<?php
$data = $this->session->userdata('hospitaladmin');
$api_base_url = $this->config->item('api_base_url');
?>
<script>
    function printpdf(record_id, type) {
        let hospitalId = '<?= $data["hospital_id"] ?>';
        let opdorid_Id = '<?= $opd_or_ipd_id_op ?>';
        $.ajax({
            url: '<?php echo base_url(); ?>admin/transaction/getdetialsbyidopdipd',
            type: "GET",
            data: {
                transationId: record_id,
                hospital_id: hospitalId,
                type: type,
                opdoridId: opdorid_Id

            },
            success: function(response) {
                popup(response)
            }
        });
    }

    function popup(data) {
        var frame1 = $('<iframe />');
        frame1[0].name = "frame1";
        frame1.css({
            "position": "absolute",
            "top": "-1000000px"
        });
        $("body").append(frame1);
        var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0]
            .contentDocument.document : frame1[0].contentDocument;
        frameDoc.document.open();
        frameDoc.document.write('<html><head><title>OPD Bill</title>');
        frameDoc.document.write(
            '<link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/sh-print.css" type="text/css" />');
        frameDoc.document.write('</head><body>');
        frameDoc.document.write(data);
        frameDoc.document.write('</body></html>');
        frameDoc.document.close();
        setTimeout(function() {
            window.frames["frame1"].focus();
            window.frames["frame1"].print();
            frame1.remove();
        }, 500);
    }
</script>