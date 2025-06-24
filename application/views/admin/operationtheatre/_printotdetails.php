<?php
$currency_symbol = $this->customlib->getHospitalCurrencyFormat();
?>
<?php
if ($otdetails->opd_details_id != "") {
    $patient_name = $otdetails->opd_patient_name;
    $patient_id   = $otdetails->opd_patient_id;
    $case_id      = $otdetails->opd_case_id;
} else {
    $patient_name = $otdetails->ipd_patient_name;
    $patient_id   = $otdetails->ipd_patient_id;
    $case_id      = $otdetails->ipd_case_id;
}
?>
<?php
$currency_symbol = $this->customlib->getHospitalCurrencyFormat();
?>
<link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/sh-print.css">
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
                    <img src="<?= $logo_image ?>" class="img-responsive" style="height:100px; width: 100%;">
                </div>
            <?php } ?>
            <div class="card mt-1">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><?php echo $this->lang->line('patient'); ?>: <?php echo composePatientName($patient_name, $patient_id); ?></p>
                        </div>
                        <div class="col-md-6 text-right">
                            <p><?php echo $this->lang->line('case_id'); ?>: <?php echo $case_id; ?></p>
                        </div>
                    </div>
                    <div class="row">
                        <?php $currency_symbol = $this->customlib->getHospitalCurrencyFormat();  ?>
                        <div class="box-body pb0">
                            <div class="col-md-12 col-lg-10 col-sm-9">
                                <table class="print-table" style="text-align: left; padding-top: 10px;">
                                    <tbody>
                                        <tr>
                                            <th><?php echo $this->lang->line('reference_no'); ?></th>
                                            <td><?php echo !empty($this->customlib->getSessionPrefixByType('operation_theater_reference_no') . $otdetails->id) ? $this->customlib->getSessionPrefixByType('operation_theater_reference_no') . $otdetails->id : '-'; ?></td>
                                            <th><?php echo $this->lang->line('operation_name'); ?></th>
                                            <td><?php echo !empty($otdetails->operation) ? $otdetails->operation : '-'; ?></td>
                                        </tr>
                                        <tr>
                                            <th><?php echo $this->lang->line('date'); ?></th>
                                            <td><?php echo !empty($otdetails->date) ? $this->customlib->YYYYMMDDHisTodateFormat($otdetails->date, $this->customlib->getHospitalTimeFormat()) : '-'; ?></td>
                                            <th><?php echo $this->lang->line('operation_category'); ?></th>
                                            <td><?php echo !empty($otdetails->category) ? $otdetails->category : '-'; ?></td>
                                        </tr>
                                        <tr>
                                            <th><?php echo $this->lang->line('consultant_doctor'); ?></th>
                                            <td><?php echo (!empty($otdetails->name) && !empty($otdetails->surname) && !empty($otdetails->employee_id)) ? $otdetails->name . ' ' . $otdetails->surname . ' (' . $otdetails->employee_id . ')' : '-'; ?></td>
                                            <th><?php echo $this->lang->line('assistant_consultant') . ' 1'; ?></th>
                                            <td><?php echo !empty($otdetails->ass_consultant_1) ? $otdetails->ass_consultant_1 : '-'; ?></td>
                                        </tr>
                                        <tr>
                                            <th><?php echo $this->lang->line('assistant_consultant') . ' 2'; ?></th>
                                            <td><?php echo !empty($otdetails->ass_consultant_2) ? $otdetails->ass_consultant_2 : '-'; ?></td>
                                            <th><?php echo $this->lang->line('anesthetist'); ?></th>
                                            <td><?php echo !empty($otdetails->anesthetist) ? $otdetails->anesthetist : '-'; ?></td>
                                        </tr>
                                        <tr>
                                            <th><?php echo $this->lang->line('anaethesia_type'); ?></th>
                                            <td><?php echo !empty($otdetails->anaethesia_type) ? $otdetails->anaethesia_type : '-'; ?></td>
                                            <th><?php echo $this->lang->line('ot_technician'); ?></th>
                                            <td><?php echo !empty($otdetails->ot_technician) ? $otdetails->ot_technician : '-'; ?></td>
                                        </tr>
                                        <tr>
                                            <th><?php echo $this->lang->line('ot_assistant'); ?></th>
                                            <td><?php echo !empty($otdetails->ot_assistant) ? $otdetails->ot_assistant : '-'; ?></td>
                                            <th><?php echo $this->lang->line('remark'); ?></th>
                                            <td><?php echo !empty($otdetails->remark) ? $otdetails->remark : '-'; ?></td>
                                        </tr>
                                        <tr>
                                            <th><?php echo $this->lang->line('result'); ?></th>
                                            <td><?php echo !empty($otdetails->result) ? $otdetails->result : '-'; ?></td>
                                        </tr>
                                        <?php if (!empty($fields)) {
                                            foreach ($fields as $fields_key => $fields_value) {
                                                $display_field = !empty($otdetails->{$fields_value->name}) ? $otdetails->{$fields_value->name} : '-';
                                                if ($fields_value->type == "link" && !empty($otdetails->{$fields_value->name})) {
                                                    $display_field = "<a href='" . $otdetails->{$fields_value->name} . "' target='_blank'>" . $otdetails->{$fields_value->name} . "</a>";
                                                }
                                        ?>
                                                <tr>
                                                    <th><?php echo $fields_value->name; ?></th>
                                                    <td colspan="3"><?php echo $display_field; ?></td>
                                                </tr>
                                        <?php }
                                        } ?>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clear">
                <p>
                    <?php
                    if (!empty($print_details[0]['print_footer'])) {
                        echo $print_details[0]['print_footer'];
                    }
                    ?>
                </p>
            </div>
        </div>
    </div>
</div>