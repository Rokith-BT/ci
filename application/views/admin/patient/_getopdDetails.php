<?php
$currency_symbol = $this->customlib->getHospitalCurrencyFormat();
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="table-responsive">
            <table class="table mb0 table-striped table-bordered examples tablelr0space noborder">
                <tr>
                    <th width="15%"><?php echo $this->lang->line('checkup_id'); ?></th>
                    <td width="35%"><span id="opd_no">
                            <?php echo !empty($result["OPD_checkup_id"]) ? $result["OPD_checkup_id"] : '-'; ?>
                        </span></td>
                    <th width="15%"><?php echo $this->lang->line('opd_id'); ?></th>
                    <td width="35%"><span id="opd_no">
                            <?php echo !empty($result["OPD_ID"]) ? $result["OPD_ID"] : '-'; ?>
                        </span></td>
                </tr>
                <tr>
                    <th width="15%"><?php echo $this->lang->line('case_id'); ?></th>
                    <td width="35%"><span
                            id="opd_no"><?php echo !empty($result['case_reference_id']) ? $result['case_reference_id'] : '-'; ?></span>
                    </td>
                    <th width="15%"><?php echo $this->lang->line('patient_name'); ?></th>
                    <td width="35%"><span id="patient_name">
                            <?php echo !empty($result['patient_name']) ? $result['patient_name'] : '-'; ?>
                        </span></td>
                </tr>
                <tr>
                    <th width="15%"><?php echo $this->lang->line('old_patient'); ?></th>
                    <td width="35%"><span id="old_patient">
                            <?php echo !empty($result['patient_old']) ? $this->lang->line($result['patient_old']) : '-'; ?>
                        </span></td>
                    <th width="15%"><?php echo $this->lang->line('guardian_name'); ?></th>
                    <td width="35%"><span id='guardian_name'>
                            <?php echo !empty($result['guardian_name']) ? $result['guardian_name'] : '-'; ?>
                        </span></td>
                </tr>
                <tr>
                    <th width="15%"><?php echo $this->lang->line('gender'); ?></th>
                    <td width="35%"><span
                            id='gen'><?php echo !empty($result['gender']) ? $result['gender'] : '-'; ?></span></td>
                    <th width="15%"><?php echo $this->lang->line('marital_status'); ?></th>
                    <td width="35%"><span
                            id="marital_status"><?php echo !empty($result['marital_status']) ? $result['marital_status'] : '-'; ?></span>
                    </td>
                </tr>
                <tr>
                    <th width="15%"><?php echo $this->lang->line('phone'); ?></th>
                    <td width="35%"><span
                            id="contact"><?php echo !empty($result['mobileno']) ? $result['mobileno'] : '-'; ?></span>
                    </td>
                    <th width="15%"><?php echo $this->lang->line('email'); ?></th>
                    <td width="35%"><span id='email' style="text-transform: none">
                            <?php echo !empty($result['email']) ? $result['email'] : '-'; ?>
                        </span></td>
                </tr>
                <tr>
                    <th width="15%"><?php echo $this->lang->line('address'); ?></th>
                    <td width="35%"><span id='patient_address'>
                            <?php echo !empty(trim($result['address'])) ? trim($result['address']) : '-'; ?>
                        </span></td>

                    <th width="15%"><?php echo $this->lang->line('age'); ?></th>
                    <td width="35%"><span id="age">
                            <?php echo !empty($result['age']) ? $result['age'] : '-'; ?>
                        </span></td>
                </tr>
                <tr>
                    <th width="15%"><?php echo $this->lang->line('blood_group'); ?></th>
                    <td width="35%"><span
                            id="blood_group"><?php echo !empty($result['blood_group']) ? $result['blood_group'] : '-'; ?></span>
                    </td>
                    <th width="15%"><?php echo $this->lang->line('height'); ?></th>
                    <td width="35%"><span
                            id='height'><?php echo !empty($result['height']) ? $result['height'] : '-'; ?></span></td>
                </tr>
                <tr>
                    <th width="15%"><?php echo $this->lang->line('weight'); ?></th>
                    <td width="35%"><span
                            id="weight"><?php echo !empty($result['weight']) ? $result['weight'] : '-'; ?></span></td>
                    <th width="15%"><?php echo $this->lang->line('bp'); ?></th>
                    <td width="35%"><span
                            id='patient_bp'><?php echo !empty($result['bp']) ? $result['bp'] : '-'; ?></span></td>
                </tr>
                <tr>
                    <th width="15%"><?php echo $this->lang->line('appointment_date'); ?></th>
                    <td width="35%"><span id="appointment_date">
                            <?php echo !empty($result['appointment_date']) ? date('d/m/Y', strtotime($result['appointment_date'])) : '-'; ?>
                        </span></td>
                    <th width="15%"><?php echo $this->lang->line('case'); ?></th>
                    <td width="35%">
                        <span id='case'>
                            <?php 
                                echo !empty(trim($result['case_type'] ?? '')) ? trim($result['case_type']) : '-'; 
                            ?>
                        </span>
                    </td>

                </tr>

                <!-- Dynamic Fields Handling -->
                <?php if (!empty($fields)) {
                    foreach ($fields as $fields_key => $fields_value) {
                        $display_field = !empty($result["$fields_value->name"]) ? $result["$fields_value->name"] : '-';
                        if ($fields_value->type == "link" && !empty($result["$fields_value->name"])) {
                            $display_field = "<a href='" . $result["$fields_value->name"] . "' target='_blank'>" . $result["$fields_value->name"] . "</a>";
                        }
                ?>
                <tr>
                    <th width="15%"><?php echo $fields_value->name; ?></th>
                    <td colspan="3"><?php echo $display_field; ?></td>
                </tr>
                <?php }
                } ?>
            </table>

        </div>
    </div>
</div>