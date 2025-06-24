<link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/sh-print.css">
<?php
if ($charge->opd_id != "" && $charge->opd_id != 0) {
    $patient_name = $charge->opd_patient_name;
    $patient_id = $charge->opd_patient_id;
    $case_reference_id = $charge->opd_case_reference_id;
} else {
    $patient_name = $charge->ipd_patient_name;
    $patient_id = $charge->ipd_patient_id;
    $case_reference_id = $charge->ipd_case_reference_id;
}
$currency_symbol = $this->customlib->getHospitalCurrencyFormat();
?>
<?php
$standfee = $charge->standard_charge * $charge->qty;
$total_fee = $charge->additional_charge + $standfee;
$total_after_discount = $total_fee - $charge->discount_amount;
$tax = ($charge->tax > 0) ? (($total_after_discount * $charge->tax) / 100) : 0;
$subtotal = $total_fee - $charge->discount_amount;
?>
<div>
    <div class="row">
        <div class="col-md-12">
            <?php if (!empty($print_details[0]['print_header'])) {
                $userdata = $this->session->userdata('hospitaladmin');
                $accessToken = $userdata['accessToken'] ?? '';

                $url = "https://phr-api.plenome.com/file_upload/getDocs";
                $client = curl_init($url);
                curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($client, CURLOPT_POST, true);
                curl_setopt($client, CURLOPT_POSTFIELDS, json_encode(['value' => $print_details[0]['print_header']]));
                curl_setopt($client, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Authorization: ' . $accessToken
                ]);
                $response = curl_exec($client);
                curl_close($client);

                $logo_image = ($response !== false && strpos($response, '"NoSuchKey"') === false)
                    ? "data:image/png;base64," . trim($response)
                    : base_url() . "uploads/staff_images/no_image.png";
            ?>
                <div class="pprinta4">
                    <img src="<?= $logo_image ?>" class="img-responsive" style="height:100px; width: 100%;">
                </div>
            <?php } ?>

            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><?php echo $this->lang->line('patient'); ?>: <?php echo composePatientName($patient_name, $patient_id); ?></p>
                            <p><?php echo $this->lang->line('case_id'); ?>: <?php echo $case_reference_id; ?></p>
                        </div>
                        <div class="col-md-6 text-right">
                            <p><span class="text-muted"><?php echo $this->lang->line('date'); ?>:</span> 
                            <?php echo date('d/m/Y', strtotime($charge->date)); ?>
                        </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <table class="print-table">
                                <thead>
                                    <tr class="line">
                                        <th>S.No</th>
                                        <th><?php echo $this->lang->line('category'); ?></th>
                                        <th><?php echo $this->lang->line('name'); ?></th>
                                        <th>Standard Charge</th>
                                        <th>Quantity</th>
                                        <th>Applied Charge</th>
                                        <th>Additional Charge</th>
                                        <th class="text-right"><?php echo $this->lang->line('amount') . ' (' . $currency_symbol . ')'; ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td><strong><?php echo $charge->charge_category_name; ?></strong><br><?php echo $charge->note; ?></td>
                                        <td><?php echo $charge->charge_name; ?></td>
                                        <td><?php echo $charge->standard_charge; ?></td>
                                        <td><?php echo $charge->qty; ?></td>
                                        <td><?php echo $charge->apply_charge; ?></td>
                                        <td><?php echo $charge->additional_charge; ?></td>
                                        <td class="text-right"><?php echo number_format($total_fee, 2); ?></td>
                                    </tr>

                                    <tr>
                                        <td colspan="6" class="text-right thick-line"><strong>Total Amount</strong></td>
                                        <td colspan="2" class="text-right thick-line">
                                            <strong><?php echo $currency_symbol . " " . number_format($total_fee, 2); ?></strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="6" class="text-right no-line"><strong><?php echo $this->lang->line('discount'); ?> (<?= $charge->discount_percentage . "%" ?>)</strong></td>
                                        <td colspan="2" class="text-right no-line">
                                            <strong><?php echo $currency_symbol . " " . number_format($charge->discount_amount, 2); ?></strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="6" class="text-right no-line"><strong>Sub Total</strong></td>
                                        <td colspan="2" class="text-right no-line">
                                            <strong><?php echo $currency_symbol . " " . number_format($subtotal, 2); ?></strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="6" class="text-right no-line"><strong><?php echo $this->lang->line('tax'); ?> (<?= $charge->tax ?>%)</strong></td>
                                        <td colspan="2" class="text-right no-line">
                                            <strong><?php echo $currency_symbol . number_format($tax, 2); ?></strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="6" class="text-right no-line"><strong><?php echo $this->lang->line('net_amount'); ?></strong></td>
                                        <td colspan="2" class="text-right no-line">
                                            <strong>
                                                <?php echo $currency_symbol . number_format($subtotal+$tax,2); ?>
                                            </strong>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-md-12">
                <p>
                    <?php if (!empty($print_details[0]['print_footer'])) {
                        echo $print_details[0]['print_footer'];
                    } ?>
                </p>
            </div>
        </div>
    </div>
</div>
