<link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/sh-print.css">
<?php
$currency_symbol = $this->customlib->getHospitalCurrencyFormat();
?>
<div class="print-area p-3">
    <div class="row">
        <div class="col-12 text-center mb-3">
            <?php if (!empty($print_details[0]['print_header'])) { ?>
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
                    } elseif (!empty($print_details[0]['print_header'])) {
                        $logo_image = base_url() . $print_details[0]['print_header'];
                    }
                }
                ?>
                <img src="<?= $logo_image ?>" class="img-fluid" style="max-height:100px;">
            <?php } ?>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <h4 class="text-center font-weight-bold text-uppercase mb-3">Appointment Details</h4>

            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th>Patient Name</th>
                        <td><?= !empty(trim($result['patients_name'])) ? trim($result['patients_name']) : '-'; ?></td>
                        <th>Appointment No</th>
                        <td><?= !empty(trim($result['appointment_no'])) ? trim($result['appointment_no']) : '-'; ?></td>
                    </tr>
                    <tr>
                        <th>Doctor</th>
                        <td><?= !empty(trim($result['name'])) ? composeStaffNameByString($result['name'], $result['surname'], $result['employee_id']) : '-'; ?></td>
                        <th>Gender</th>
                        <td><?= !empty(trim($result['patients_gender'])) ? trim($result['patients_gender']) : '-'; ?></td>
                    </tr>
                    <tr>
                        <th>Token No</th>
                        <td><?= !empty(trim($result['appointment_serial_no'])) ? trim($result['appointment_serial_no']) : '-'; ?></td>
                        <th>Department</th>
                        <td><?= !empty(trim($result['department_name'])) ? trim($result['department_name']) : '-'; ?></td>
                    </tr>
                    <tr>
                        <th>Age</th>
                        <td><?= !empty(trim($result['age'])) ? $this->customlib->getPatientAge($result['age'], $result['month'], $result['day']) : '-'; ?></td>
                        <th>Appointment Date</th>
                        <td><?= !empty(trim($result["date"])) ? date("d/m/Y", strtotime($result["date"])) : '-'; ?></td>
                    </tr>
                    <tr>
                        <th>Payment Mode</th>
                        <td><?= $result['payment_mode']??"-"; ?></td>
                        <th>Email</th>
                        <td><?= !empty(trim($result['patient_email'])) ? trim($result['patient_email']) : '-'; ?></td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td><?= !empty(trim($result['patient_mobileno'])) ? trim($result['patient_mobileno']) : '-'; ?></td>
                        <th>Shift</th>
                        <td><?= !empty(trim($result['global_shift_name'])) ? trim($result['global_shift_name']) : '-'; ?></td>
                    </tr>
                    <tr>
                        <th>Slot</th>
                        <td><?= !empty(trim($result['doctor_shift_name'])) ? trim($result['doctor_shift_name']) : '-'; ?></td>
                        <th>Status</th>
                        <td><?= !empty(trim($result['appointment_status'])) ? trim($result['appointment_status']) : '-'; ?></td>
                    </tr>

                    <?php if (trim($result['payment_mode']) == 'Cheque') { ?>
                        <tr>
                            <th>Cheque No</th>
                            <td><?= !empty(trim($result['cheque_no'])) ? trim($result['cheque_no']) : '-'; ?></td>
                            <th>Cheque Date</th>
                            <td><?= !empty(trim($result['cheque_date'])) ? $this->customlib->YYYYMMDDTodateFormat(trim($result['cheque_date']), $this->customlib->getHospitalTimeFormat()) : '-'; ?></td>
                        </tr>
                    <?php } ?>

                    <tr>
                        <th>Message</th>
                        <td colspan="3"><?= !empty(trim($result['message'])) ? trim($result['message']) : '-'; ?></td>
                    </tr>
                </tbody>

            </table>

            <hr class="my-4">
            <?php if (!empty($result) && !empty($result['transaction_id']) && !empty($result['amount'])): ?>
                <h4 class="text-center font-weight-bold text-uppercase">Payment Details</h4>
                <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>Transaction ID</th>
                            <th>Source</th>
                            <th class="text-right">Paid Amount (<?= $currency_symbol; ?>)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?= $result['transaction_id']; ?></td>
                            <td><?= $result['payment_mode']; ?></td>
                            <td class="text-right"><?= $currency_symbol . (!empty($result['amount']) ? number_format($result['amount'], 2) : '0.00'); ?></td>
                        </tr>
                        <tr class="font-weight-bold">
                            <td colspan="2" class="text-right">Total Paid</td>
                            <td class="text-right"><?= $currency_symbol . (!empty($result['amount']) ? number_format($result['amount'], 2) : '0.00'); ?></td>
                        </tr>
                        <?php if (!empty($result['payment_note'])): ?>
                            <tr>
                                <th>Payment Note</th>
                                <td colspan="2"><?= $result['payment_note']; ?></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            <?php endif; ?>

        </div>
    </div>

    <div class="text-center mt-4">
        <p class="text-muted">
            <?php if (!empty($print_details[0]['print_footer'])) {
                echo $print_details[0]['print_footer'];
            } ?>
        </p>
    </div>
</div>