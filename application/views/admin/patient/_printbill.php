<link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/sh-print.css">
<?php $currency_symbol = $this->customlib->getHospitalCurrencyFormat(); ?>

<div class="print-area">
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

                $logo_image = ($response !== false && strpos($response, '"NoSuchKey"') === false) ? "data:image/png;base64," . trim($response) : base_url() . "uploads/staff_images/no_image.png";
            ?>
            <div class="pprinta4">
                <img src="<?= $logo_image ?>" class="img-responsive" style="height:100px; width: 100%;">
            </div>
            <?php } ?>

            <div class="card">
                <div class="card-body">
                    <?php if (!empty($result)) { ?>
                    <div class="row">
                        <div class="col-md-12" style="padding-top:10px">
                            <table class="noborder_table">
                                <tr>
                                    <th><?php echo $this->lang->line("opd_id"); ?></th>
                                    <td><?php echo !empty($result["opd_details_id"]) ? $opd_prefix . $result["opd_details_id"] : '-'; ?>
                                    </td>

                                    <th><?php echo $this->lang->line("checkup_id"); ?></th>
                                    <td><?php echo !empty($result["id"]) ? $checkup_prefix . $result["id"] : '-'; ?>
                                    </td>

                                    <th><?php echo $this->lang->line("appointment_date"); ?></th>
                                    <td class="text-center">
                                        <?php echo !empty($result["appointment_date"]) ? date('d/m/Y', strtotime($result["appointment_date"])) : '-'; ?>
                                    </td>
                                </tr>

                                <?php if (!empty($result["appointment_no"]) || !empty($result["appointment_serial_no"])) { ?>
                                <tr>
                                    <th><?php echo $this->lang->line("appointment_no"); ?></th>
                                    <td><?php echo !empty($result["appointment_no"]) ? $this->customlib->getSessionPrefixByType('appointment') . $result["appointment_no"] : '-'; ?>
                                    </td>

                                    <th>Token No</th>
                                    <td><?php echo !empty($result["appointment_serial_no"]) ? $result["appointment_serial_no"] : '-'; ?>
                                    </td>
                                </tr>
                                <?php } ?>

                                <tr>
                                    <th><?php echo $this->lang->line("patient_name"); ?></th>
                                    <td><?php echo !empty($result["patient_name"]) ? $result["patient_name"] . ' (' . $result["patient_id"] . ')' : '-'; ?>
                                    </td>

                                    <th><?php echo $this->lang->line("weight"); ?></th>
                                    <td><?php echo !empty($result["weight"]) ? $result["weight"] : '-'; ?></td>

                                    <th><?php echo $this->lang->line("bp"); ?></th>
                                    <td><?php echo !empty($result["bp"]) ? $result["bp"] : '-'; ?></td>
                                </tr>
                            </table>

                        </div>
                    </div>
                    <?php } ?>

                    <hr style="height: 1px; clear: both; margin-bottom: 10px; margin-top: 10px" />
                    <h4 class="font-bold"><?php echo $this->lang->line("payment_details"); ?></h4>

                    <?php if (!empty($charge)) { ?>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="print-table">
                                <thead>
                                    <tr class="line">
                                        <th>S.No</th>
                                        <th>Charge Name</th>
                                        <th><?php echo $this->lang->line('quantity'); ?></th>
                                        <th><?php echo $this->lang->line('standard_charge'); ?></th>
                                        <th><?php echo $this->lang->line('apply_charge'); ?></th>
                                        <th>Additional Charges</th>
                                        <th><?php echo $this->lang->line('discount'); ?></th>
                                        <th><?php echo $this->lang->line('tax') . ' (%)'; ?></th>
                                        <th class="text-right">
                                            <?php echo $this->lang->line('amount') . ' (' . $currency_symbol . ')'; ?>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $count = 1; 
                                        $total_amount = 0; 
                                        $total_tax = 0; 
                                        $total_paid = 0; 
                                        foreach ($charge as $charge): 
                                            $total_amount_additional = $charge['apply_charge'] + $charge['additional_charge'] - $charge['discount_amount'];                                              
                                            $tax = ($charge["tax"] > 0) ? (($total_amount_additional * $charge["tax"]) / 100) : 0;
                                            $total_tax += $tax;
                                            $charge_total = $charge['total'] ?? $charge['amount'];
                                            $total_amount += ($charge["discount_percentage"] == 0) ? amountFormat($charge["amount"]) : amountFormat($charge_total);
                                        ?>
                                    <tr>
                                        <td><?php echo $count++; ?></td>
                                        <td><strong><?php echo $charge['charge_name']; ?></strong><br><?php echo $charge['note']; ?>
                                        </td>
                                        <td><strong><?php echo $charge['qty']; ?></strong></td>
                                        <td><strong><?php echo $charge['standard_charge']; ?></strong></td>
                                        <td><strong><?php echo $charge['apply_charge']; ?></strong></td>
                                        <td><strong><?php echo $charge['additional_charge']; ?></strong></td>
                                        <td><strong><?php echo $charge['discount_amount'] . " (" . $charge['discount_percentage'] . '%)'; ?></strong>
                                        </td>
                                        <td><?php echo amountFormat($tax) . " (" . $charge['tax'] . "% )"; ?></td>
                                        <td class="text-right">
                                            <?php echo $currency_symbol . amountFormat($charge_total); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <p><?php echo !empty($print_details[0]['print_footer']) ? $print_details[0]['print_footer'] : ''; ?></p>
    </div>
</div>