<?php 
$currency_symbol = $this->customlib->getHospitalCurrencyFormat();
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="table-responsive">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th width="15%">Case ID</th>
                        <td width="35%"><span
                                id="case_id"><?php echo !empty($result['case_id']) ? $result['case_id'] : '-'; ?></span>
                        </td>
                        <th width="15%">Recheckup ID</th>
                        <td width="35%"><span
                                id="recheckup_id"><?php echo !empty($result['Recheckup_id']) ? $prefix["Recheckupprefix"].$result['Recheckup_id'] : '-'; ?></span>
                        </td>
                    </tr>
                    <tr>
                        <th width="15%">OPD No</th>
                        <td width="35%"><span
                                id="opd_no"><?php echo !empty($result['opd_no']) ? $prefix["opdprefix"].$result['opd_no'] : '-'; ?></span>
                        </td>
                        <th width="15%">Old Patient</th>
                        <td width="35%"><span
                                id="old_patient"><?php echo isset($result['old_patient']) ? $result['old_patient'] : '-'; ?></span>
                        </td>
                    </tr>
                    <tr>
                        <th width="15%">Patient Name</th>
                        <td width="35%"><span
                                id="patient_name"><?php echo !empty($result['patient_name']) ? $result['patient_name'] : '-'; ?></span>
                        </td>
                        <th width="15%">Guardian Name</th>
                        <td width="35%"><span
                                id="guardian_name"><?php echo !empty($result['guardian_name']) ? $result['guardian_name'] : '-'; ?></span>
                        </td>
                    </tr>
                    <tr>
                        <th width="15%">Gender</th>
                        <td width="35%"><span
                                id="gender"><?php echo !empty($result['gender']) ? $result['gender'] : '-'; ?></span>
                        </td>
                        <th width="15%">Marital Status</th>
                        <td width="35%"><span
                                id="marital_status"><?php echo !empty($result['marital_status']) ? $result['marital_status'] : '-'; ?></span>
                        </td>
                    </tr>
                    <tr>
                        <th width="15%">Phone</th>
                        <td width="35%"><span
                                id="phone"><?php echo !empty($result['phone']) ? $result['phone'] : '-'; ?></span></td>
                        <th width="15%">Email</th>
                        <td width="35%"><span
                                id="email"><?php echo !empty($result['email']) ? $result['email'] : '-'; ?></span></td>
                    </tr>
                    <tr>
                        <th width="15%">Address</th>
                        <td width="35%"><span
                                id="address"><?php echo !empty(trim($result['address'])) ? $result['address'] : '-'; ?></span>
                        </td>
                        <th width="15%">Age</th>
                        <td width="35%"><span
                                id="age"><?php echo !empty($result['age']) ? $result['age'] : '-'; ?></span></td>
                    </tr>
                    <tr>
                        <th width="15%">Blood Group</th>
                        <td width="35%"><span
                                id="blood_group"><?php echo !empty($result['blood_group']) ? $result['blood_group'] : '-'; ?></span>
                        </td>
                        <th width="15%">Height</th>
                        <td width="35%"><span
                                id="height"><?php echo !empty($result['height']) ? $result['height'] : '-'; ?></span>
                        </td>
                    </tr>
                    <tr>
                        <th width="15%">Weight</th>
                        <td width="35%"><span
                                id="weight"><?php echo !empty($result['weight']) ? $result['weight'] : '-'; ?></span>
                        </td>
                        <th width="15%">BP</th>
                        <td width="35%"><span id="bp"><?php echo !empty($result['BP']) ? $result['BP'] : '-'; ?></span>
                        </td>
                    </tr>
                    <tr>
                        <th width="15%">Pulse</th>
                        <td width="35%"><span
                                id="pulse"><?php echo !empty($result['pulse']) ? $result['pulse'] : '-'; ?></span></td>
                        <th width="15%">Temperature</th>
                        <td width="35%"><span
                                id="temperature"><?php echo !empty($result['Temperature']) ? $result['Temperature'] : '-'; ?></span>
                        </td>
                    </tr>
                    <tr>
                        <th width="15%">Respiration</th>
                        <td width="35%"><span
                                id="respiration"><?php echo !empty($result['respiration']) ? $result['respiration'] : '-'; ?></span>
                        </td>
                        <th width="15%">SPO2</th>
                        <td width="35%"><span
                                id="spo2"><?php echo !empty($result['spo2']) ? $result['spo2'] : '-'; ?></span></td>
                    </tr>
                    <tr>
                        <th width="15%">Appointment Date</th>
                        <td width="35%"><span
                                id="appointment_date"><?php echo !empty($result['appointment_date']) ? date('d/m/Y H:i', strtotime($result['appointment_date'])) : '-'; ?></span>
                        </td>
                        <th width="15%">Case</th>
                        <td width="35%"><span
                                id="cases"><?php echo !empty($result['cases']) ? $result['cases'] : '-'; ?></span></td>
                    </tr>
                    <tr>
                        <th width="15%">Casualty</th>
                        <td width="35%"><span
                                id="casualty"><?php echo !empty($result['casualty']) ? $result['casualty'] : '-'; ?></span>
                        </td>
                        <th width="15%">Reference</th>
                        <td width="35%"><span
                                id="refference"><?php echo !empty($result['refference']) ? $result['refference'] : '-'; ?></span>
                        </td>
                    </tr>
                    <tr>
                        <th width="15%">TPA</th>
                        <td width="35%"><span
                                id="tpa"><?php echo !empty($result['TPA']) ? $result['TPA'] : '-'; ?></span></td>
                        <th width="15%">Consultant Doctor</th>
                        <td width="35%"><span
                                id="consultant_Doctor"><?php echo !empty($result['consultant_Doctor']) ? $result['consultant_Doctor'] : '-'; ?></span>
                        </td>
                    </tr>
                    <tr>
                        <th width="15%">Note</th>
                        <td width="35%"><span
                                id="note"><?php echo !empty($result['note']) ? $result['note'] : '-'; ?></span></td>
                        <th width="15%">Symptoms</th>
                        <td width="35%"><span
                                id="symptoms"><?php echo !empty($result['symptoms']) ? nl2br($result['symptoms']) : '-'; ?></span>
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>
</div>