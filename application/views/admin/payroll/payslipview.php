<?php
$currency_symbol = $this->customlib->getHospitalCurrencyFormat();
$net_salary_in_words = convert_number_to_words(round($result["net_salary"]));
$month = strtolower($result["month"]);
$month_number = date('m', strtotime($month));
$current_year = date('Y');
$total_days = cal_days_in_month(CAL_GREGORIAN, $month_number, $current_year);
$currentMonth = strtolower(date('F'));
$daysUntilToday = $total_days;
function convert_number_to_words($num)
{
    $ones = array(
        0 => 'Zero',
        1 => 'One',
        2 => 'Two',
        3 => 'Three',
        4 => 'Four',
        5 => 'Five',
        6 => 'Six',
        7 => 'Seven',
        8 => 'Eight',
        9 => 'Nine',
        10 => 'Ten',
        11 => 'Eleven',
        12 => 'Twelve',
        13 => 'Thirteen',
        14 => 'Fourteen',
        15 => 'Fifteen',
        16 => 'Sixteen',
        17 => 'Seventeen',
        18 => 'Eighteen',
        19 => 'Nineteen'
    );
    $tens = array(
        20 => 'Twenty',
        30 => 'Thirty',
        40 => 'Forty',
        50 => 'Fifty',
        60 => 'Sixty',
        70 => 'Seventy',
        80 => 'Eighty',
        90 => 'Ninety'
    );
    $thousands = array("", "Thousand", "Million", "Billion", "Trillion");

    if ($num == 0) {
        return $ones[0];
    }

    $num_str = strrev((string)$num);
    $groups = str_split($num_str, 3);
    $words = "";
    $group_index = 0;

    foreach ($groups as $group) {
        $group = strrev($group);
        $group_int = (int)$group;

        if ($group_int > 0) {
            $group_words = "";

            if ($group_int < 20) {
                $group_words = $ones[$group_int];
            } else {
                $group_tens = (int)($group_int / 10) * 10;
                $group_ones = $group_int % 10;

                if ($group_int < 100) {
                    $group_words = $tens[$group_tens] . ($group_ones > 0 ? "-" . $ones[$group_ones] : "");
                } else {
                    $group_hundreds = (int)($group_int / 100);
                    $group_words = $ones[$group_hundreds] . " Hundred " . ($group_int % 100 > 0 ? convert_number_to_words($group_int % 100) : "");
                }
            }
            $words = $group_words . " " . $thousands[$group_index] . " " . $words;
        }
        $group_index++;
    }

    return trim($words);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Details</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <style>
        body {
            margin: 0;
            font-family: 'Poppins';
            background-color: #fbfbff;
        }

        .main-container {
            width: 850px;
            margin: 20px auto;
            background-color: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .header {
            background-color: #6070ff;
            border-radius: 12px;
            padding: 24px;
            color: #ffffff;
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo {
            width: 40px;
            height: 40px;
            background: url('./assets/images/12b7ea11-6b6c-4ad0-b0bd-895adf885ee4.png') center/cover no-repeat;
        }

        .hospital-name {
            font-size: 24px;
            font-weight: 600;
        }

        .address {
            font-size: 12px;
            margin-top: 10px;
        }

        .title-bar {
            background-color: #1c2253;
            color: #ffffff;
            border-radius: 4px 4px 0 0;
            text-align: center;
            padding: 10px;
            font-size: 14px;
            font-weight: 600;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 0;
        }

        th,
        td {
            border: 1px solid #8f98b3;
            padding: 10px;
            font-size: 12px;
        }

        th {
            color: #1c2253;
            text-align: left;
            font-family: Poppins;
            font-size: 12px;
            font-style: normal;
            font-weight: 500;
            line-height: normal;
        }

        td {
            color: #1c2253;
            font-family: Poppins;
            font-size: 12px;
            font-style: normal;
            font-weight: 500;
            line-height: normal;
        }

        .amount {
            text-align: right;
        }

        .summary-row td {
            background-color: #8f98b3;
            color: #ffffff;
            font-weight: 600;
        }

        .title-bar {
            display: flex;
            justify-content: space-between;
            background-color: #1c2253;
            color: #ffffff;
            padding: 10px;
            border-radius: 4px 4px 0 0;
            font-size: 14px;
            font-weight: 600;
        }

        .title-bar-section {
            flex: 1;
            text-align: left;
        }

        .title-bar-section.amount {
            text-align: right;
        }

        .title-bar-section {
            padding: 0 15px;
        }

        .vertical-line {
            width: 2px;
            height: 100%;
            background-color: #1c2253;
        }

        .summary-row td {
            background-color: #8f98b3;
            color: #ffffff;
            font-weight: 600;
        }

        .net-pay-container {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            font-weight: 600;
            font-size: 14px;
            color: #1c2253;
        }

        .amount-in-words {
            color: var(--Text, #1C2253);
            font-family: Poppins;
            font-size: 14px;
            font-style: normal;
            font-weight: 500;
            line-height: normal;
            text-align: center;
        }

        .note {
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 12px;
            color: #8f98b3;
            margin-top: 20px;
        }

        .amount {
            text-align: right;
        }

        .summary-row td {
            background-color: #8f98b3;
            color: #ffffff;
            font-weight: 600;
        }

        .net-pay-container {
            margin-top: 20px;
            font-weight: 600;
            font-size: 14px;
            color: #1c2253;
        }

        .additional-info {
            margin-top: 20px;
        }

        .additional-info div {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            color: #1c2253;
            margin-bottom: 5px;
        }

        .note {
            text-align: center;
            font-size: 12px;
            color: #8f98b3;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="main-container" id="employee-details">
        <div>
            <?php
            $logo_image = base_url() . "uploads/staff_images/no_image.png";
            if (!empty($print_details[0]['print_header'])) {
                $userdata = $this->session->userdata('hospitaladmin');
                $accessToken = $userdata['accessToken'] ?? '';

                $url = "https://phr-api.plenome.com/file_upload/getDocs";
                $payload = json_encode(['value' => $print_details[0]['print_header']]);
                $client = curl_init($url);
                curl_setopt_array($client, [
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => $payload,
                    CURLOPT_HTTPHEADER => ['Content-Type: application/json', 'Authorization: ' . $accessToken],
                ]);
                $response = curl_exec($client);
                curl_close($client);
                if ($response && strpos($response, '"NoSuchKey"') === false) {
                    $logo_image = "data:image/png;base64," . trim($response);
                } else {
                    $logo_image = base_url() . $print_details[0]['print_header'];
                }
            }
            ?>
            <img src="<?php echo $logo_image; ?>" class="img-responsive" style="height:100px; width: 100%; margin-bottom: 20px;">
        </div>

        <div class="title-bar">Employee Details</div>
        <table>
            <tr>
                <th>Employee Name</th>
                <td class="amount"><?php echo htmlspecialchars($result["name"]) . " " . htmlspecialchars($result["surname"]); ?></td>
                <th>Pay Period</th>
                <td class="amount"><?= ucfirst($result["month"]); ?> <?= $result["year"] ?></td>
            </tr>
            <tr>
                <th>Employee ID</th>
                <td class="amount"><?php echo htmlspecialchars($result["employee_id"]); ?></td>
                <th>Pay Date</th>
                <td class="amount"><?php echo date("d-m-Y"); ?></td>
            </tr>
            <tr>
                <th>Designation</th>
                <td class="amount"><?php echo htmlspecialchars($result["designation"]); ?></td>
                <th>Available calendar days</th>
                <td class="amount"><?= $daysUntilToday ?></td>
            </tr>
            <tr>
                <th>Department</th>
                <td class="amount"><?php echo htmlspecialchars($result["department"]); ?></td>
                <th>Paid Days</th>
                <td class="amount"><?= $daysUntilToday - $total_lop ?></td>
            </tr>
            <tr>
                <th>DOJ</th>
                <td class="amount"><?php echo date('d-m-Y', strtotime($result["date_of_joining"])); ?></td>
                <th>LOP Days</th>
                <td class="amount"><?= $total_lop ?></td>
            </tr>
            <tr>
                <th>PAN</th>
                <td class="amount"><?php echo htmlspecialchars($result["uan_no"]); ?></td>
                <th>Bank</th>
                <td class="amount"><?php echo htmlspecialchars($result["bank_name"]); ?></td>
            </tr>
            <tr>
                <th>PF Number</th>
                <td class="amount"><?php echo htmlspecialchars($result["epf_no"]); ?></td>
                <th>Account Number</th>
                <td class="amount"><?php echo htmlspecialchars($result["bank_account_no"]); ?></td>
            </tr>
            <tr>
                <th>ESI Number</th>
                <td class="amount"><?php echo htmlspecialchars($result["esi_no"]); ?></td>
                <th>Status</th>
                <td class="amount">Paid</td>
            </tr>
        </table>

        <div class="title-bar">
            <div class="title-bar-section">Earnings</div>
            <div class="vertical-line"></div>
            <div class="title-bar-section amount">Amount</div>
            <div class="vertical-line"></div>
            <div class="title-bar-section">Deductions</div>
            <div class="vertical-line"></div>
            <div class="title-bar-section amount">Amount</div>
        </div>
        <table>
            <?php
            $j = 0;
            $maxRows = max(count($positive_allowance), count($negative_allowance));
            for ($j = 0; $j < $maxRows; $j++) {
                echo "<tr>";
                if (array_key_exists($j, $positive_allowance)) {
                    $label = wordwrap($positive_allowance[$j]["allowance_type"], 13, "<br>", true);
                    echo "<td class='wrap-text'>" . $label . "</td>";
                    echo "<td class='amount'>" . amountFormat($positive_allowance[$j]["amount"]) . "</td>";
                } else {
                    echo "<td></td><td class='amount'></td>";
                }

                if (array_key_exists($j, $negative_allowance)) {
                    echo "<td>" . $negative_allowance[$j]["allowance_type"] . "</td>";
                    echo "<td class='amount'>" . amountFormat($negative_allowance[$j]["amount"]) . "</td>";
                } else {
                    echo "<td></td><td class='amount'></td>";
                }
                echo "</tr>";
            }
            ?>
            <tr class="summary-row">
                <td>Total Earnings</td>
                <td class="amount"><?php echo amountFormat($result["total_allowance"]); ?></td>
                <td>Total Deductions</td>
                <td class="amount"><?php echo amountFormat($result["total_deduction"]); ?></td>
            </tr>
        </table>

        <div class="net-pay-container">
            <span>Gross Salary:</span>
            <span><?php echo amountFormat($result["total_allowance"] - $result["total_deduction"]); ?></span>
        </div>
        <?php
        $Gross_Salary = amountFormat($result["total_allowance"] - $result["total_deduction"]);
        $five_percent = $Gross_Salary * ($result["tax"] / 100);
        ?>
        <div class="net-pay-container">
            <span>Tax (<?php echo $result["tax"]; ?>%)</span>
            <span><?= $five_percent ?></span>
        </div>
        <div class="net-pay-container">
            <span>Net Salary (INR):</span>
            <span><?php echo $currency_symbol . " " . amountFormat($result["net_salary"]); ?></span>
        </div>
        <div class="amount-in-words">
            Amount in words <?php echo $net_salary_in_words; ?>
        </div>

        <div class="note">
            This is a computer-generated payslip. No signature is required.
        </div>
    </div>

</body>

</html>