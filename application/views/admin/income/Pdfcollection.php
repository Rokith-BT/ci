<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collection Report - Plenome Hospital</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f7fa;
            color: #2c3e50;
            line-height: 1.6;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .page-header {
            background: #3498db;
            color: white;
            padding: 30px;
            text-align: center;
        }

        .page-title {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .page-subtitle {
            font-size: 18px;
            opacity: 0.9;
        }

        .content {
            padding: 30px;
        }

        .hospital-info {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #ecf0f1;
        }

        .hospital-name {
            font-size: 28px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .report-type {
            font-size: 18px;
            color: #7f8c8d;
        }

        .summary-card {
            background: linear-gradient(135deg, #74b9ff, #0984e3);
            color: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 30px;
            text-align: center;
        }

        .summary-amount {
            font-size: 36px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .summary-text {
            font-size: 16px;
            opacity: 0.9;
        }

        .info-grid {
            display: grid;
            /* grid-template-columns: 1fr 1fr; */
            gap: 20px;
            margin-bottom: 30px;
        }

        .info-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #3498db;
        }

        .info-row {
            display: flex;
            margin-bottom: 12px;
        }

        .info-label {
            font-weight: 600;
            color: #2c3e50;
            min-width: 130px;
        }

        .info-value {
            color: #34495e;
            flex: 1;
        }

        .transactions-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        .transactions-table thead {
            background: #34495e;
            color: white;
        }

        .transactions-table th,
        .transactions-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ecf0f1;
        }

        .transactions-table th {
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .transactions-table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .transactions-table tbody tr:nth-child(even) {
            background-color: #fdfdfd;
        }

        .amount-cell {
            font-weight: 600;
            color: #27ae60;
            text-align: right;
        }

        .total-row {
            background: #e8f4f8 !important;
            font-weight: bold;
            border-top: 3px solid #3498db;
        }

        .total-row td {
            font-size: 16px;
            padding: 15px;
            font-weight: bold;
        }

        .bill-link {
            color: #3498db;
            text-decoration: none;
            font-weight: 600;
        }

        .bill-link:hover {
            color: #2980b9;
            text-decoration: underline;
        }

        .action-buttons {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #ecf0f1;
        }

        .btn {
            background: #3498db;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            margin: 0 10px;
            transition: background 0.3s;
        }

        .btn:hover {
            background: #2980b9;
        }

        .btn-secondary {
            background: #95a5a6;
        }

        .btn-secondary:hover {
            background: #7f8c8d;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .action-buttons {
                display: none;
            }

            .container {
                box-shadow: none;
                border-radius: 0;
            }
        }

        @media (max-width: 768px) {
            .info-grid {
                grid-template-columns: 1fr;
            }

            .transactions-table {
                font-size: 12px;
            }

            .transactions-table th,
            .transactions-table td {
                padding: 8px 6px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="page-header">
            <h1 class="page-title">Transaction Details</h1>
            <p class="page-subtitle">Hospital Collection Report</p>
        </div>

        <div class="content">
            <div class="hospital-info">
                <div class="hospital-name">Plenome Hospital</div>
                <div class="report-type"><?=$bill_title?></div>
            </div>

            <div class="summary-card">
                <div class="summary-amount">₹<?= number_format($total_amount, 2) ?></div>
                <div class="summary-text">Total Cash Collection by <?=$collected_by?></div>
            </div>
            <div class="info-grid">
                <div class="info-section">
                    <!-- Row 1 -->
                    <div class="info-row" style="display: flex; justify-content: space-between;">
                        <div style="width: 48%;">
                            <span class="info-label">Total Transactions:</span>
                            <span class="info-value">2</span>
                        </div>
                        <div style="width: 48%; text-align: right;">
                            <span class="info-label">Report Date:</span>
                            <span class="info-value"><?= $formatted_datetime ?></span>
                        </div>
                    </div>
                    <!-- Row 2 -->
                    <div class="info-row" style="display: flex; justify-content: space-between; margin-top: 10px;">
                        <div style="width: 48%;">
                            <span class="info-label">Collected By:</span>
                            <span class="info-value"> <?=$collected_by?></span>
                        </div>
                        <div style="width: 48%; text-align: right;">
                            <span class="info-label">Payment Type:</span>
                            <span class="info-value"> <?=$payment_type?></span>
                        </div>
                    </div>
                </div>
            </div>




            <table class="transactions-table">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Bill Date</th>
                        <th>Bill No</th>
                        <th>Patient ID</th>
                        <th>Patient Name</th>
                        <th>Consultant</th>
                        <th>Amount</th>
                        <th>Bill Type</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($transactions as $index => $transaction): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= date('d-m-Y', strtotime($transaction['bill_date'])) ?></td>
                        <td><a href="#" class="bill-link"><?= $transaction['bill_no'] ?></a></td>
                        <td><?= $transaction['patient_id'] ?></td>
                        <td><?= $transaction['patient_name'] ?></td>
                        <td><?= $transaction['consultant'] ?></td>
                        <td class="amount-cell">₹<?= number_format($transaction['amount'], 2) ?></td>
                        <td><?= $transaction['bill_type'] ?></td>
                    <tr>
                    <?php endforeach; ?>              
                    <tr class="total-row">
                        <td colspan="6"><strong>TOTAL COLLECTION</strong></td>
                        <td class="amount-cell"><strong>₹<?= number_format($total_amount, 2) ?></strong></td>
                        <td><strong>All Types</strong></td>
                    </tr>
                </tbody>
            </table>

            <div class="action-buttons">
                <button class="btn" onclick="window.print()">Print Report</button>
            </div>
        </div>
    </div>
    <script>
        function downloadPDF(paymentType, collectedBy, type = null) {
            $.ajax({
                type: "GET",
                url: "<?= base_url() ?>admin/income/Collection_generate_bill",
                success: function(response) {
                    popup(response);
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
            frameDoc.document.write('<html><head><title>Collction Report</title>');
            frameDoc.document.write('');
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
</body>

</html>