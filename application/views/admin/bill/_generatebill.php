<link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/sh-print.css">
<?php $currency_symbol = $this->customlib->getHospitalCurrencyFormat(); ?>

<div class="print-area">
    <div class="row">
        <div class="col-12">
            <?php if (!empty($print_details[0]['print_header'])) { ?>
                <div class="pprinta4">
                    <?php
                    if ($print_details[0]['print_header']) {
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

                        if ($response !== false && strpos($response, '"NoSuchKey"') === false) {
                            $logo_image = "data:image/png;base64," . trim($response);
                        } else {
                            $logo_image = base_url() . "uploads/staff_images/no_image.png";
                        }
                    } else {
                        $logo_image = base_url() . "uploads/staff_images/no_image.png";
                    }
                    ?>
                    <img src="<?=$logo_image?>" class="img-responsive">
                </div>
            <?php } ?>

            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><?php echo $this->lang->line('patient'); ?>: <?php echo composePatientName($patient['patient_name'], $patient['patient_id']); ?></p>
                            <p><?php echo $this->lang->line('case_id'); ?>: <?php echo $case_id; ?></p>
                        </div>
                        <div class="col-md-6 text-right">
                            <?php if (!empty($patient['date'])) { ?>
                                <p><span class="text-muted"><?php echo $this->lang->line('admission_date'); ?>: </span>
                                <?php echo date('d/m/Y', strtotime($patient['date'])); ?>
                                </p>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <table class="print-table">
                                <thead>
                                    <tr class="line">
                                        <td><strong>#</strong></td>
                                        <td><strong><?php echo "OPD ID"; ?></strong></td>
                                        <td><strong><?php echo $this->lang->line('date'); ?></strong></td>
                                        <td><strong><?php echo $this->lang->line('name'); ?></strong></td>
                                        <td><strong><?php echo "Standard Charge (" . $currency_symbol . ")"; ?></strong></td>
                                        <td><strong><?php echo $this->lang->line('qty'); ?></strong></td>
                                        <td><strong><?php echo $this->lang->line('apply_charge') . " (" . $currency_symbol . ")"; ?></strong></td>
                                        <td><strong><?php echo "Additional Charge (" . $currency_symbol . ")"; ?></strong></td>
                                        <td><strong><?php echo "Discount Amount (" . $currency_symbol . ")"; ?></strong></td>
                                        <td><strong><?php echo "Sub Total (" . $currency_symbol . ")"; ?></strong></td>
                                        <td><strong><?php echo $this->lang->line('tax') . " (%)"; ?></strong></td>
                                        <td class="text-right"><strong><?php echo $this->lang->line('net_amount') . " (" . $currency_symbol . ")"; ?></strong></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $total_tax = $apply_charge = $amount = $sub_total = $net_amount = 0;
                                    $s = 1;
                                    foreach ($charge_details as $value) {
                                        $opd_id = "OPDN" . $value['opd_id'];
                                        $date = date('d/m/Y', strtotime($value['date']));
                                        $charge_name = $value['charge_name'];
                                        $standard_charge = floatval($value['standard_charge']);
                                        $qty = intval($value['qty']);
                                        $apply_charge = floatval($value['apply_charge']);
                                        $additional_charge = floatval($value['additional_charge']);
                                        $discount = floatval($value['discount_amount']);
                                        $tax_rate = floatval($value['tax']);

                                        $row_sub_total = $apply_charge + $additional_charge - $discount;
                                        $row_tax = ($row_sub_total * $tax_rate) / 100;
                                        $row_net_amount = $row_sub_total + $row_tax;

                                        $sub_total += $row_sub_total;
                                        $total_tax += $row_tax;
                                        $net_amount += $row_net_amount;
                                    ?>
                                        <tr>
                                            <td><?php echo $s++; ?></td>
                                            <td><?php echo $opd_id; ?></td>
                                            <td><?php echo $date; ?></td>
                                            <td><?php echo $charge_name; ?></td>
                                            <td class="text-right"><?php echo $currency_symbol . number_format($standard_charge, 2); ?></td>
                                            <td class="text-center"><?php echo $qty; ?></td>
                                            <td class="text-right"><?php echo $currency_symbol . number_format($apply_charge, 2); ?></td>
                                            <td class="text-right"><?php echo $currency_symbol . number_format($additional_charge, 2); ?></td>
                                            <td class="text-right"><?php echo $currency_symbol . number_format($discount, 2); ?></td>
                                            <td class="text-right"><?php echo $currency_symbol . number_format($row_sub_total, 2); ?></td>
                                            <td class="text-right"><?php echo number_format($row_tax, 2) . " (" . $tax_rate . "%)"; ?></td>
                                            <td class="text-right font-weight-bold text-success"><?php echo $currency_symbol . number_format($row_net_amount, 2); ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="11" class="text-right thick-line"><strong><?php echo $this->lang->line('tax'); ?></strong></td>
                                        <td class="text-right thick-line"><?php echo $currency_symbol . number_format($total_tax, 2); ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="11" class="text-right no-line"><strong><?php echo $this->lang->line('net_amount'); ?></strong></td>
                                        <td class="text-right no-line"><?php echo $currency_symbol . number_format($net_amount, 2); ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="11" class="text-right no-line"><strong><?php echo $this->lang->line('paid'); ?></strong></td>
                                        <td class="text-right no-line"><?php echo $currency_symbol . amountFormat($paid_amount['total_pay']) ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="11" class="text-right no-line"><strong><?php echo $this->lang->line('due'); ?></strong></td>
                                        <td class="text-right"><?php echo $currency_symbol . number_format(max(0, $net_amount - $paid_amount['total_pay']), 2); ?></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="clear">
                <p><?php if (!empty($print_details[0]['print_footer'])) echo $print_details[0]['print_footer']; ?></p>
            </div>
        </div>
    </div>
</div>