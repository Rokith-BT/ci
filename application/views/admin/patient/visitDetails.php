<?php
$currency_symbol = $this->customlib->getHospitalCurrencyFormat();
$genderList = $this->customlib->getGender();
$case_reference_id = $result['case_reference_id'];
$data = $this->session->userdata('hospitaladmin');
$api_base_url = $this->config->item('api_base_url');
$api_base_url_casesheet = $this->config->item('api_base_url_casesheet');

$total_payment = 0;
if (!empty($paymentlist['data'])) {
    foreach ($paymentlist['data'] as $payment) {
        if (!empty($payment['amount'])) {
            $total_payment += $payment['amount'];
        }
    }
}

$total = 0;
if (!empty($chargeslist['data'])) {
    foreach ($chargeslist['data'] as $charge) {
        $total += $charge['total'];
    }
}
?>

<style>
#botIcon {
    position: absolute;
    bottom: 20px;
    right: 20px;
    width: 60px;
    cursor: pointer;
    z-index: 1000;
}

body {
    position: relative;
}
</style>
<style>
.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.example th,
.example td {
    white-space: nowrap;
    text-align: center;
    vertical-align: middle;
}

.font-weight-bold {
    font-weight: 600;
}

.text-center {
    text-align: center;
}

.text-right {
    text-align: right;
}

.btn-xs {
    padding: 3px 5px;
    font-size: 12px;
}
</style>
<script>
$(document).ready(function() {
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    $('#timeline_date').datetimepicker({
        format: 'DD/MM/YYYY',
        minDate: today,
        defaultDate: today,
        ignoreReadonly: true
    });
    $('#date').datetimepicker({
        format: 'DD/MM/YYYY',
        minDate: today,
        defaultDate: today,
        ignoreReadonly: true
    });
    $('#charge_date').datetimepicker({
        format: 'DD/MM/YYYY',
        minDate: today,
        defaultDate: today,
        ignoreReadonly: true
    });
    $('#etimelinedate').datetimepicker({
        format: 'DD/MM/YYYY',
        minDate: today,
        ignoreReadonly: true
    });
});
</script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/timepicker/bootstrap-timepicker.min.css">
<script src="<?php echo base_url(); ?>backend/plugins/timepicker/bootstrap-timepicker.min.js"></script>
<div class="content-wrapper">
    <section class="content">
        <div class="row">

            <div class="col-md-12 itemcol">
                <div class="nav-tabs-custom relative">

                    <ul class="nav nav-tabs navlistscroll">
                        <li class="active">
                            <a href="#overview" class="active" data-toggle="tab" aria-expanded="true">
                                <i class="fa fa-th"></i> <?php echo $this->lang->line('overview'); ?>
                            </a>
                        </li>

                        <!-- New Nurse Notes Tab -->
                        <!-- <?php if ($this->rbac->hasPrivilege('nurse_notes', 'can_view')) { ?>
                            <li>
                                <a href="#nursenotes" data-toggle="tab" aria-expanded="true">
                                    <i class="fas fa-notes-medical"></i> <?php echo $this->lang->line('nurse_notes'); ?>
                                </a>
                            </li>
                        <?php } ?> -->

                        <?php if ($this->rbac->hasPrivilege('checkup', 'can_view')) { ?>
                        <li>
                            <a href="#activity" data-toggle="tab" aria-expanded="true">
                                <i class="far fa-caret-square-down"></i> <?php echo $this->lang->line('visits'); ?>
                            </a>
                        </li>
                        <?php } ?>

                        <!-- <?php if ($this->rbac->hasPrivilege('opd_medication', 'can_view')) { ?>
                            <li>
                                <a href="#medication" class="medication" data-toggle="tab" aria-expanded="true">
                                    <i class="fa fa-medkit" aria-hidden="true"></i> <?php echo $this->lang->line("medication"); ?>
                                </a>
                            </li>
                        <?php } ?> -->
                        <?php if ($this->rbac->hasPrivilege('ipd_prescription', 'can_view')) { ?>
                        <!-- <li>
                            <a href="#prescription" data-toggle="tab" aria-expanded="true"><i
                                    class="far fa-calendar-check"></i>
                                Clinical Notes</a>
                        </li> -->
                        <?php } ?>

                        <!-- <?php if ($this->rbac->hasPrivilege('opd_lab_investigation', 'can_view')) { ?>
                            <li>
                                <a href="#labinvestigation" data-toggle="tab" aria-expanded="true">
                                    <i class="fas fa-diagnoses"></i> <?php echo $this->lang->line('lab_investigation'); ?>
                                </a>
                            </li>
                        <?php } ?> -->

                        <!-- <?php if ($this->rbac->hasPrivilege('opd_operation_theatre', 'can_view')) { ?>
                            <li>
                                <a href="#operationtheatre" data-toggle="tab" aria-expanded="true">
                                    <i class="fas fa-cut"></i> <?php echo $this->lang->line("operations"); ?>
                                </a>
                            </li>
                            <?php } ?> -->

                        <?php if ($this->rbac->hasPrivilege('opd_charges', 'can_view')) { ?>
                        <li>
                            <a href="#charges" data-toggle="tab" aria-expanded="true">
                                <i class="far fa-calendar-check"></i> <?php echo $this->lang->line('charges'); ?>
                            </a>
                        </li>
                        <?php } ?>

                        <?php if ($this->rbac->hasPrivilege('opd_payment', 'can_view')) { ?>
                        <li>
                            <a href="#payment" data-toggle="tab" aria-expanded="true">
                                <i class="far fa-calendar-check"></i> <?php echo $this->lang->line('payments'); ?>
                            </a>
                        </li>
                        <?php } ?>

                        <?php if ($this->module_lib->hasActive('live_consultation')) {
                            if ($this->rbac->hasPrivilege('opd_live_consult', 'can_view')) { ?>
                        <!-- <li><a href="#live_consult" data-toggle="tab" aria-expanded="true"><i
                class="fa fa-video-camera ftlayer"></i>
            <?php echo $this->lang->line('live_consultation'); ?></a></li> -->
                        <?php }
                        } ?>

                        <?php if ($this->rbac->hasPrivilege('opd_timeline', 'can_view')) { ?>
                        <li>
                            <a href="#timeline" data-toggle="tab" aria-expanded="true">
                                <i class="far fa-calendar-check"></i> <?php echo $this->lang->line('timeline'); ?>
                            </a>
                        </li>
                        <?php } ?>
                    </ul>
                    <div class="tab-content pt6">
                        <div class="tab-pane tab-content-height  active" id="overview">

                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12 border-r">

                                    <div class="box-header border-b mb10 pl-0 pt0">
                                        <h3 class="text-uppercase bolds mt0 ptt10 pull-left font14">
                                            <?php echo composePatientName($result['patient_name'], $result['patient_id']); ?>
                                        </h3>
                                        <div class="pull-right">
                                            <div class="editviewdelete-icon pt8 text-center">
                                                <?php if ($this->rbac->hasPrivilege('opd_patient', 'can_edit')) { ?>
                                                <a href="javascript:void(0);" id="print-button"><i
                                                        class="fas fa-print"></i></a>
                                                <a href="#" onclick="editRecord('<?php echo $visitminid; ?>')"
                                                    data-target="#editModal" data-toggle="tooltip"
                                                    data-original-title="Edit"><i class="fa fa-pencil"></i></a>
                                                <?php } ?>
                                                <?php if ($this->rbac->hasPrivilege('opd_patient', 'can_delete')) { ?>
                                                <a class="" href="#"
                                                    onclick="delete_patient('<?php echo $result['id'] ?>','<?php echo $result['patient_id'] ?>')"
                                                    data-toggle="tooltip"
                                                    title="<?php echo $this->lang->line('delete_patient'); ?>">
                                                    <i class="fa fa-trash"></i>
                                                </a>

                                                <?php } ?>
                                                <?php if ($this->rbac->hasPrivilege('opd_patient', 'can_view')) { ?>
                                                <a href="javascript:void(0);" id="case-sheet">
                                                    <i class="fa fa-file"></i>
                                                </a>
                                                <?php } ?>
                                                <!-- <?php if ($this->rbac->hasPrivilege('opd_patient_discharge', 'can_view')): ?>
                                                    <a class="patient_discharge" href="#" data-toggle="tooltip" title="<?php echo $this->lang->line('patient_discharge'); ?>">
                                                        <i class="fa fa-hospital-o"></i>
                                                    </a>
                                                <?php endif; ?>

                                                <?php if (!$is_discharge && $this->rbac->hasPrivilege('opd_patient_discharge_revert', 'can_view')): ?>
                                                    <a data-toggle="tooltip" href="#" title="<?php echo $this->lang->line('discharge_revert'); ?>"
                                                    onclick="discharge_revert('<?php echo $result['case_reference_id']; ?>')">
                                                        <i class="fa fa-undo"></i>
                                                    </a>
                                                <?php endif; ?> -->

                                                <input type="hidden" id="result_opdid" name=""
                                                    value="<?php echo $result['id'] ?>">
                                                <input type="hidden" id="result_pid" name=""
                                                    value="<?php echo $result['patient_id'] ?>">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 col-sm-12 ptt10">
                                            <?php
                                            if ($result['image']) {
                                                $userdata = $this->session->userdata('hospitaladmin');
                                                $accessToken = $userdata['accessToken'] ?? '';

                                                $url = "https://phr-api.plenome.com/file_upload/getDocs";
                                                $client = curl_init($url);
                                                curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
                                                curl_setopt($client, CURLOPT_POST, true);
                                                curl_setopt($client, CURLOPT_POSTFIELDS, json_encode(['value' => $result['image']]));
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

                                            <img width="115" height="115"
                                                class="profile-user-img img-responsive img-rounded"
                                                src="<?= $logo_image ?>" alt="Profile Image">


                                        </div>
                                        <!--./col-lg-5-->
                                        <div class="col-lg-9 col-md-8 col-sm-12">
                                            <table class="table table-bordered mb0">
                                                <tr>
                                                    <td class="bolds"><?php echo $this->lang->line('gender'); ?></td>
                                                    <td><?php echo !empty($result['gender']) ? $result['gender'] : "-"; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bolds"><?php echo $this->lang->line('age'); ?></td>
                                                    <td><?php echo !empty($result['age']) || !empty($result['month']) || !empty($result['day'])
                                                            ? $this->customlib->getPatientAge($result['age'], $result['month'], $result['day'])
                                                            : "-"; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bolds"><?php echo $this->lang->line('guardian_name'); ?>
                                                    </td>
                                                    <td><?php echo !empty($result['guardian_name']) ? $result['guardian_name'] : "-"; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="bolds"><?php echo $this->lang->line('phone'); ?></td>
                                                    <td><?php echo !empty($result['mobileno']) ? $result['mobileno'] : "-"; ?>
                                                    </td>
                                                </tr>
                                            </table>

                                        </div>
                                        <!--./col-lg-7-->
                                    </div>
                                    <!--./row-->
                                    <hr class="hr-panel-heading hr-10">
                                    <div class="row">
                                        <div class="col-lg-8 col-md-8 col-sm-12">
                                            <div class="align-content-center pt25">
                                                <table class="table table-bordered">
                                                    <tr>
                                                        <td class="bolds">
                                                            <?php echo $this->lang->line('appointment_no'); ?>
                                                        </td>
                                                        <td>
                                                            <a href="javascript:void(0);"
                                                                onclick="viewDetail(<?= htmlspecialchars($appointment_id, ENT_QUOTES, 'UTF-8') ?>)">
                                                                <?= $this->customlib->getSessionPrefixByType('appointment') . htmlspecialchars($appointment_id, ENT_QUOTES, 'UTF-8') ?>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="bolds"><?php echo $this->lang->line('case_id') ?>
                                                        </td>
                                                        <td><?php echo $result['case_reference_id'] ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="bolds"><?php echo $this->lang->line('opd_no'); ?>
                                                        </td>
                                                        <td><?php echo $this->customlib->getSessionPrefixByType('opd_no') . $opdid; ?>
                                                        </td>
                                                    </tr>


                                                </table>
                                            </div>
                                        </div>

                                    </div>

                                    <hr class="hr-panel-heading hr-10">
                                    <p><b><i class="fa fa-tag"></i>
                                            <?php echo $this->lang->line('known_allergies'); ?>:</b>
                                        <?php echo !empty($patientdetails['patient']['allergy']) ? "" : "-"; ?>
                                    </p>
                                    <ul>
                                        <?php
                                        if (!empty($patientdetails['patient']['allergy'])) {
                                            foreach ($patientdetails['patient']['allergy'] as $row) { ?>
                                        <li>
                                            <div>
                                                <?php echo !empty($row['known_allergies']) ? $row['known_allergies'] : "-"; ?>
                                            </div>
                                        </li>
                                        <?php }
                                        } ?>
                                    </ul>

                                    <hr class="hr-panel-heading hr-10">
                                    <p><b><i class="fa fa-tag"></i> <?php echo $this->lang->line('findings'); ?>:</b>
                                        <?php echo !empty($patientdetails['patient']['findings']) ? "" : "-"; ?>
                                    </p>
                                    <ul>
                                        <?php
                                        if (!empty($patientdetails['patient']['findings'])) {
                                            foreach ($patientdetails['patient']['findings'] as $row) { ?>
                                        <li>
                                            <div>
                                                <?php echo !empty($row['finding_description']) ? $row['finding_description'] : "-"; ?>
                                            </div>
                                        </li>
                                        <?php }
                                        } ?>
                                    </ul>

                                    <hr class="hr-panel-heading hr-10">
                                    <p><b><i class="fa fa-tag"></i> <?php echo $this->lang->line('symptoms'); ?>:</b>
                                        <?php echo !empty($patientdetails['patient']['symptoms']) ? "" : "-"; ?>
                                    </p>
                                    <ul>
                                        <?php
                                        if (!empty($patientdetails['patient']['symptoms'])) {
                                            foreach ($patientdetails['patient']['symptoms'] as $row) { ?>
                                        <li>
                                            <div><?php echo !empty($row['symptoms']) ? $row['symptoms'] : "-"; ?></div>
                                        </li>
                                        <?php }
                                        } ?>
                                    </ul>


                                    <hr class="hr-panel-heading hr-10">
                                    <div class="box-header mb10 pl-0">
                                        <h3 class="text-uppercase bolds mt0 ptt10 pull-left font14">
                                            <?php echo $this->lang->line('consultant_doctor'); ?>
                                        </h3>
                                        <div class="pull-right">
                                            <div class="editviewdelete-icon pt8">

                                            </div>
                                        </div>
                                    </div>


                                    <div class="staff-members">
                                        <?php
                                        if (!empty($patientdetails['patient']['doctor'])) {
                                            foreach ($patientdetails['patient']['doctor'] as $value) { ?>
                                        <div class="media">
                                            <div class="media-left">
                                                <?php
                                                        $default_image = "uploads/staff_images/no_image.png";
                                                        $doc_image = $value['image'];
                                                        $base64Image = '';

                                                        if (!empty($doc_image)) {
                                                            $userdata = $this->session->userdata('hospitaladmin');
                                                            $accessToken = $userdata['accessToken'] ?? '';
                                                            $url = "https://phr-api.plenome.com/file_upload/getDocs";
                                                            $client = curl_init($url);
                                                            curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
                                                            curl_setopt($client, CURLOPT_POST, true);
                                                            curl_setopt($client, CURLOPT_POSTFIELDS, json_encode(['value' => $doc_image]));
                                                            curl_setopt($client, CURLOPT_HTTPHEADER, [
                                                                'Content-Type: application/json',
                                                                'Authorization: ' . $accessToken
                                                            ]);
                                                            $response = curl_exec($client);
                                                            curl_close($client);
                                                            if ($response !== false && strpos($response, '"NoSuchKey"') === false) {
                                                                $base64Image = "data:image/png;base64," . trim($response);
                                                            } else {
                                                                $base64Image = base_url($default_image . img_time());
                                                            }
                                                        } else {
                                                            $base64Image = base_url($default_image . img_time());
                                                        }
                                                        ?>

                                                <a href="#">
                                                    <img src="<?php echo htmlspecialchars($base64Image); ?>"
                                                        class="member-profile-small media-object" alt="Staff Image" />
                                                </a>


                                            </div>
                                            <div class="media-body">
                                                <a href="#" class="pull-right text-danger pt4" data-toggle="tooltip"
                                                    data-placement="top"></a>
                                                <h5 class="media-heading"><a
                                                        href="#"><?php echo $value["name"] . " " . $value["surname"] . "  (" . $value["employee_id"] . ")" ?></a>

                                                </h5>
                                            </div>
                                        </div>
                                        <!--./media-->
                                        <?php }
                                        } ?>
                                    </div>
                                    <!--./staff-members-->
                                    <?php if (!empty($timeline_list)) { ?>
                                    <div class="box-tab-header">
                                        <h3 class="box-tab-title"><?php echo $this->lang->line('timeline'); ?></h3>
                                    </div>
                                    <?php } ?>
                                    <div class="timeline-header no-border">
                                        <div id="timeline_list">
                                            <?php if (!empty($timeline_list)) { ?>
                                            <ul class="timeline timeline-inverse">
                                                <?php $i = 0;
                                                    foreach ($timeline_list as $key => $value) {
                                                        ++$i;
                                                        if ($i <= $recent_record_count) { ?>
                                                <li class="time-label">
                                                    <span class="bg-blue">
                                                        <?php echo date('d/m/Y', strtotime($value['timeline_date'])); ?>
                                                    </span>
                                                </li>
                                                <li>
                                                    <i class="fa fa-list-alt bg-blue"></i>
                                                    <div class="timeline-item">
                                                        <?php if (!empty($value["document"]) && $value["document"] != 'None') { ?>
                                                        <span class="time">
                                                            <?php
                                                                            $userdata = $this->session->userdata('hospitaladmin');
                                                                            $accessToken = $userdata['accessToken'] ?? '';

                                                                            $name = $value["document"];
                                                                            $url = "https://phr-api.plenome.com/file_upload/getDocs";
                                                                            $client = curl_init($url);

                                                                            curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
                                                                            curl_setopt($client, CURLOPT_POST, true);
                                                                            curl_setopt($client, CURLOPT_POSTFIELDS, json_encode(['value' => $name]));
                                                                            curl_setopt($client, CURLOPT_HTTPHEADER, [
                                                                                'Content-Type: application/json',
                                                                                'Authorization: ' . $accessToken
                                                                            ]);

                                                                            $response = curl_exec($client);
                                                                            curl_close($client);

                                                                            if ($response === false) {
                                                                                echo "<span class='text-danger'>No response from server.</span>";
                                                                                return;
                                                                            }

                                                                            $base64File = trim($response);
                                                                            $fileData = base64_decode($base64File);

                                                                            if ($fileData === false) {
                                                                                echo "<span class='text-danger'>Failed to decode base64 file data.</span>";
                                                                                return;
                                                                            }

                                                                            $fileInfo = pathinfo($name);
                                                                            $fileExtension = strtolower($fileInfo['extension'] ?? 'bin');

                                                                            $mimeTypes = [
                                                                                'jpg' => 'image/jpeg',
                                                                                'jpeg' => 'image/jpeg',
                                                                                'png' => 'image/png',
                                                                                'gif' => 'image/gif',
                                                                                'pdf' => 'application/pdf',
                                                                                'txt' => 'text/plain',
                                                                                'doc' => 'application/msword',
                                                                                'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                                                                'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                                                                'xls' => 'application/vnd.ms-excel',
                                                                                'csv' => 'text/csv',
                                                                                'zip' => 'application/zip',
                                                                            ];

                                                                            $mimeType = $mimeTypes[$fileExtension] ?? 'application/octet-stream';
                                                                            $formattedName = preg_replace('/_\d+$/', '', $fileInfo['basename']);

                                                                            echo "<a href='data:" . $mimeType . ";base64," . htmlspecialchars($base64File) . "' download='" . htmlspecialchars($formattedName) . "' class='btn btn-default btn-xs' data-toggle='tooltip' title='" . $this->lang->line('download') . "'><i class='fa fa-download'></i></a>";
                                                                            ?>

                                                        </span>
                                                        <?php } ?>
                                                        <h3 class="timeline-header text-aqua">
                                                            <?php echo $value['title']; ?></h3>
                                                        <div class="timeline-body">
                                                            <?php echo $value['description']; ?>
                                                        </div>
                                                    </div>
                                                </li>
                                                <?php }
                                                    } ?>
                                                <li><i class="fa fa-clock-o bg-gray"></i></li>
                                            </ul>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>


                                <!--./col-lg-6-->
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="row">
                                        <div class="col-md-6 project-progress-bars">
                                            <div class="row">
                                                <div class="col-md-12 mtop5">
                                                    <div class="topprograssstart">
                                                        <h5 class="text-uppercase mt5 bolds">
                                                            <?php echo $this->lang->line('opd_billing_payment_graph'); ?>
                                                        </h5>
                                                        <p class="text-muted bolds">
                                                            <?php echo $graph['opd']['opd_bill_payment_ratio']; ?>%<span
                                                                class="pull-right">
                                                                <?php echo $this->customlib->get_payment_bill($graph['opd']['payment']['total_payment'], $graph['opd']['bill']['total_bill']); ?></span>
                                                        </p>
                                                        <div class="progress-group">
                                                            <div class="progress progress-minibar">
                                                                <div class="progress-bar progress-bar-aqua"
                                                                    style="width: <?php echo $graph['opd']['opd_bill_payment_ratio']; ?>%">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--./row-->
                                        </div>
                                        <!--./col-lg-6-->
                                        <div class="col-md-6 project-progress-bars">
                                            <div class="row">
                                                <div class="col-md-12 mtop5">
                                                    <div class="topprograssstart">
                                                        <h5 class="text-uppercase mt5 bolds">
                                                            <?php echo $this->lang->line('pharmacy_billing_payment_graph'); ?>
                                                        </h5>
                                                        <p class="text-muted bolds">
                                                            <?php echo $graph['pharmacy']['pharmacy_bill_payment_ratio']; ?>%<span
                                                                class="pull-right">
                                                                <?php echo $this->customlib->get_payment_bill(($graph['pharmacy']['payment']['total_payment'] - $graph['pharmacy']['payment_refund']['total_payment']), $graph['pharmacy']['bill']['total_bill']); ?></span>
                                                        </p>
                                                        <div class="progress-group">
                                                            <div class="progress progress-minibar">
                                                                <div class="progress-bar progress-bar-aqua"
                                                                    style="width: <?php echo $graph['pharmacy']['pharmacy_bill_payment_ratio']; ?>%">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--./col-lg-6-->

                                    </div>
                                    <!--./row-->
                                    <div class="row">
                                        <div class="col-md-6 project-progress-bars">
                                            <div class="row">
                                                <div class="col-md-12 mtop5">
                                                    <div class="topprograssstart">
                                                        <h5 class="text-uppercase mt5 bolds">
                                                            <?php echo $this->lang->line('pathology_billing_payment_graph'); ?>
                                                        </h5>
                                                        <p class="text-muted bolds">
                                                            <?php echo $graph['pathology']['pathology_bill_payment_ratio']; ?>%<span
                                                                class="pull-right">
                                                                <?php echo $this->customlib->get_payment_bill($graph['pathology']['payment']['total_payment'], $graph['pathology']['bill']['total_bill']); ?></span>
                                                        </p>
                                                        <div class="progress-group">
                                                            <div class="progress progress-minibar">
                                                                <div class="progress-bar progress-bar-aqua"
                                                                    style="width: <?php echo $graph['pathology']['pathology_bill_payment_ratio']; ?>%">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--./row-->
                                        </div>
                                        <!--./col-lg-6-->
                                        <div class="col-md-6 project-progress-bars">
                                            <div class="row">
                                                <div class="col-md-12 mtop5">
                                                    <div class="topprograssstart">
                                                        <h5 class="text-uppercase mt5 bolds">
                                                            <?php echo $this->lang->line('radiology_billing_payment_graph'); ?>
                                                        </h5>
                                                        <p class="text-muted bolds">
                                                            <?php echo $graph['radiology']['radiology_bill_payment_ratio']; ?>%<span
                                                                class="pull-right">
                                                                <?php echo $this->customlib->get_payment_bill($graph['radiology']['payment']['total_payment'], $graph['radiology']['bill']['total_bill']); ?></span>
                                                        </p>
                                                        <div class="progress-group">
                                                            <div class="progress progress-minibar">
                                                                <div class="progress-bar progress-bar-aqua"
                                                                    style="width: <?php echo $graph['radiology']['radiology_bill_payment_ratio']; ?>%">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--./col-lg-6-->

                                    </div>
                                    <!--./row-->
                                    <div class="row">
                                        <div class="col-md-6 project-progress-bars">
                                            <div class="row">
                                                <div class="col-md-12 mtop5">
                                                    <div class="topprograssstart">
                                                        <h5 class="text-uppercase mt5 bolds">
                                                            <?php echo $this->lang->line('blood_bank_billing_payment_graph'); ?>
                                                        </h5>
                                                        <p class="text-muted bolds">
                                                            <?php echo $graph['blood_bank']['blood_bank_bill_payment_ratio']; ?>%<span
                                                                class="pull-right">
                                                                <?php echo $this->customlib->get_payment_bill($graph['blood_bank']['payment']['total_payment'], $graph['blood_bank']['bill']['total_bill']); ?></span>
                                                        </p>
                                                        <div class="progress-group">
                                                            <div class="progress progress-minibar">
                                                                <div class="progress-bar progress-bar-aqua"
                                                                    style="width: <?php echo $graph['blood_bank']['blood_bank_bill_payment_ratio']; ?>%">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--./row-->
                                        </div>
                                        <!--./col-lg-6-->
                                        <div class="col-md-6 project-progress-bars">
                                            <div class="row">
                                                <div class="col-md-12 mtop5">
                                                    <div class="topprograssstart">
                                                        <h5 class="text-uppercase mt5 bolds">
                                                            <?php echo $this->lang->line('ambulance_billing_payment_graph'); ?>
                                                        </h5>
                                                        <p class="text-muted bolds">
                                                            <?php echo $graph['ambulance']['ambulance_bill_payment_ratio']; ?>%<span
                                                                class="pull-right">
                                                                <?php echo $this->customlib->get_payment_bill($graph['ambulance']['payment']['total_payment'], $graph['ambulance']['bill']['total_bill']); ?></span>
                                                        </p>
                                                        <div class="progress-group">
                                                            <div class="progress progress-minibar">
                                                                <div class="progress-bar progress-bar-aqua"
                                                                    style="width: <?php echo $graph['ambulance']['ambulance_bill_payment_ratio']; ?>%">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--./col-lg-6-->

                                    </div>
                                    <!--./row-->
                                    <?php
                                    if (!empty($medicationreport_overview)) { ?>
                                    <div class="box-header mb10 pl-0">
                                        <h3 class="text-uppercase bolds mt0 ptt10 pull-left font14">
                                            <?php echo $this->lang->line('medication'); ?>
                                        </h3>
                                        <div class="pull-right">
                                        </div>
                                    </div>
                                    <div class="box-header mb10 pl-0">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-hover ">
                                                <thead>
                                                    <tr>
                                                        <th><?php echo $this->lang->line('date'); ?></th>
                                                        <th><?php echo $this->lang->line('medicine_name'); ?></th>
                                                        <th><?php echo $this->lang->line('dose'); ?></th>
                                                        <th><?php echo $this->lang->line('time'); ?></th>
                                                        <th><?php echo $this->lang->line('remark'); ?></th>
                                                    <tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        for ($i = 0; $i < $recent_record_count; $i++) {
                                                            if (!empty($medicationreport_overview[$i])) {
                                                        ?>
                                                    <tr>
                                                        <td><?php echo $this->customlib->YYYYMMDDTodateFormat($medicationreport_overview[$i]['date']); ?>
                                                        </td>
                                                        <td><?php echo $medicationreport_overview[$i]['medicine_name'] ?>
                                                        </td>
                                                        <td><?php echo $medicationreport_overview[$i]['medicine_dosage'] . " (" . $medicationreport_overview[$i]['unit'] . ")"; ?>
                                                        </td>
                                                        <td><?php echo $this->customlib->getHospitalTime_Format($medicationreport_overview[$i]['time']); ?>
                                                        </td>
                                                        <td><?php echo $medicationreport_overview[$i]['remark']; ?>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                            }
                                                        }
                                                        ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <?php  }  ?>
                                    <!---lab investigation-->
                                    <?php if (!empty($investigations)) { ?>
                                    <div class="box-header mb10 pl-0">
                                        <h3 class="text-uppercase bolds mt0 ptt10 pull-left font14">
                                            <?php echo $this->lang->line('lab_investigation'); ?>
                                        </h3>
                                        <div class="pull-right">

                                        </div>
                                    </div>
                                    <div class="box-header mb10 pl-0">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-hover"
                                                data-export-title="<?php echo $this->lang->line('lab_investigation'); ?>">
                                                <thead>
                                                    <th><?php echo $this->lang->line('test_name'); ?></th>
                                                    <th><?php echo $this->lang->line('lab'); ?></th>
                                                    <th><?php echo $this->lang->line('sample_collected'); ?></th>
                                                    <td><strong><?php echo $this->lang->line('expected_date'); ?></strong>
                                                    </td>
                                                    <th><?php echo $this->lang->line('approved_by'); ?></th>

                                                </thead>
                                                <tbody id="">
                                                    <?php
                                                        $i = 0;
                                                        foreach ($investigations as $row) {
                                                            ++$i;

                                                            if ($i <= $recent_record_count) {
                                                        ?>
                                                    <tr>
                                                        <td><?php echo $row['test_name']; ?><br />
                                                            <?php echo "(" . $row['short_name'] . ")"; ?></td>
                                                        <td><?php echo $this->lang->line($row['type']); ?></td>
                                                        <td><label>
                                                                <?php echo composeStaffNameByString($row['collection_specialist_staff_name'], $row['collection_specialist_staff_surname'], $row['collection_specialist_staff_employee_id']); ?>
                                                            </label>

                                                            <br />
                                                            <label for=""><?php if ($row['type'] == 'pathology') {
                                                                                            echo $this->lang->line('pathology');
                                                                                        } else {
                                                                                            echo $this->lang->line('radiology');
                                                                                        } ?> : </label>

                                                            <?php
                                                                        echo $row['test_center'];
                                                                        ?>
                                                            <br />
                                                            <?php echo $this->customlib->YYYYMMDDTodateFormat($row['collection_date']); ?>
                                                        </td>

                                                        <td>
                                                            <?php

                                                                        echo $this->customlib->YYYYMMDDTodateFormat($row['reporting_date']); ?>

                                                        </td>
                                                        <td class="text-left">
                                                            <label
                                                                for=""><?php echo $this->lang->line('approved_by'); ?>
                                                                :
                                                            </label>
                                                            <?php
                                                                        echo composeStaffNameByString($row['approved_by_staff_name'], $row['approved_by_staff_surname'], $row['approved_by_staff_employee_id']);
                                                                        ?>
                                                            <br />
                                                            <?php
                                                                        echo $this->customlib->YYYYMMDDTodateFormat($row['parameter_update']);
                                                                        ?>
                                                        </td>

                                                    </tr>
                                                    <?php }
                                                        } ?>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                    <?php } ?>

                                    <!---lab investigation-->

                                    <?php if (!empty($operation_theatre)) { ?>
                                    <div class="box-header mb10 pl-0">
                                        <h3 class="text-uppercase bolds mt0 ptt10 pull-left font14">
                                            <?php echo $this->lang->line('operation'); ?>
                                        </h3>
                                        <div class="pull-right">

                                        </div>
                                    </div>
                                    <div class="box-header mb10 pl-0">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-hover"
                                                data-export-title="<?php echo $this->lang->line('operations'); ?>">
                                                <thead>
                                                    <th><?php echo $this->lang->line("reference_no"); ?></th>
                                                    <th><?php echo $this->lang->line("operation_date"); ?></th>
                                                    <th><?php echo $this->lang->line("operation_name"); ?></th>
                                                    <th><?php echo $this->lang->line("operation_category"); ?></th>
                                                    <th><?php echo $this->lang->line("ot_technician"); ?></th>
                                                </thead>
                                                <tbody id="">
                                                    <?php
                                                        $i = 0;
                                                        if (!empty($operation_theatre)) {
                                                            foreach ($operation_theatre as $ot_key => $ot_value) {


                                                                $i++;
                                                                if ($i <= $recent_record_count) {
                                                        ?>
                                                    <tr>
                                                        <td><?php echo $this->customlib->getSessionPrefixByType('operation_theater_reference_no') . $ot_value["id"] ?>
                                                        </td>
                                                        <td><?php echo
                                                                            $this->customlib->YYYYMMDDHisTodateFormat($ot_value["date"], $this->customlib->getHospitalTimeFormat());
                                                                            ?></td>
                                                        <td><?php echo $ot_value["operation"]; ?></td>
                                                        <td><?php echo $ot_value["category"] ?></td>
                                                        <td><?php echo $ot_value['ot_technician'] ?></td>


                                                    </tr>

                                                    <?php }
                                                            }
                                                        } ?>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                    <?php } ?>

                                    <!---consultant section-->
                                    <?php if (!empty($chargeslist)) { ?>
                                    <div class="box-header mb10 pl-0">
                                        <h3 class="text-uppercase bolds mt0 ptt10 pull-left font14">
                                            <?php echo $this->lang->line('charges'); ?>
                                        </h3>
                                        <div class="pull-right">
                                        </div>
                                    </div>
                                    <div class="box-header mb10 pl-0">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-hover ipdcharged">
                                                <thead>
                                                    <tr>
                                                        <th class="text-left"><?php echo $this->lang->line('name'); ?>
                                                        </th>
                                                        <th class="text-right">Applied Charge (₹)</th>
                                                        <th class="text-right">
                                                            <?php echo $this->lang->line('discount'); ?> (%)</th>
                                                        <th class="text-right">Additional Charges (₹)</th>
                                                        <th class="text-right">tax(%)</th>
                                                        <th class="text-right">
                                                            <?php echo $this->lang->line('net_amount') . ' (₹)'; ?>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        if (!empty($chargeslist['data'])) {
                                                            $last_five_charges = array_slice($chargeslist['data'], 0, 5);
                                                            foreach ($last_five_charges as $charge) {
                                                                $subtotal = $charge['applied_charge'] - $charge['discount_amount'] + $charge['additional_charges'];
                                                                $tax_amount = ($subtotal * $charge['tax'] / 100);
                                                                $taxamount = amountFormat($tax_amount);
                                                        ?>
                                                    <tr>
                                                        <td class="text-left">
                                                            <?php echo htmlspecialchars($charge["name"]); ?>
                                                            <?php if (!empty($charge["note"])) { ?>
                                                            <div class="bill_item_footer text-muted">
                                                                <label><?php echo $this->lang->line('charge_note') . ': '; ?></label>
                                                                <?php echo htmlspecialchars($charge["note"]); ?>
                                                            </div>
                                                            <?php } ?>
                                                        </td>
                                                        <td class="text-right">
                                                            ₹<?php echo number_format($charge["applied_charge"], 2); ?>
                                                        </td>
                                                        <td class="text-right">
                                                            ₹<?php echo number_format($charge['discount_amount'], 2); ?>
                                                            (<?php echo number_format($charge["discount_percentage"], 2); ?>%)
                                                        </td>
                                                        <td class="text-right">
                                                            ₹<?php echo number_format($charge["additional_charges"] ?? 0, 2); ?>
                                                        </td>
                                                        <td class="text-right">
                                                            ₹<?php echo $taxamount; ?>
                                                            (<?php echo number_format($charge["tax"], 2); ?>%)
                                                        </td>
                                                        <td class="text-right">
                                                            ₹<?php echo number_format($charge["total"], 2); ?>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                            }
                                                        }
                                                        ?>
                                                </tbody>
                                            </table>


                                        </div>

                                    </div>
                                    <?php } ?>
                                    <?php if (!empty($paymentlist)) { ?>
                                    <div class="box-header mb10 pl-0">
                                        <h3 class="text-uppercase bolds mt0 ptt10 pull-left font14">
                                            <?php echo $this->lang->line('payment'); ?>
                                        </h3>
                                        <div class="pull-right"></div>
                                    </div>
                                    <div class="box-header mb10 pl-0">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">
                                                            <?php echo $this->lang->line('transaction_id'); ?></th>
                                                        <th class="text-center"><?php echo $this->lang->line('date'); ?>
                                                        </th>
                                                        </th>
                                                        <th class="text-center">
                                                            <?php echo $this->lang->line('payment_mode'); ?></th>
                                                        <th class="text-right text-center">
                                                            <?php echo $this->lang->line('paid_amount') . " (" . $currency_symbol . ")"; ?>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        if (!empty($paymentlist['data'])) {
                                                            $recent_payments = array_slice($paymentlist['data'], -$recent_record_count);
                                                            foreach ($recent_payments as $payment) {
                                                                $transaction_id = preg_replace('/\D/', '', $payment['transaction_ID']);
                                                        ?>
                                                    <tr>
                                                        <td class="text-center">
                                                            <?php echo $this->customlib->getSessionPrefixByType('transaction_id') . $transaction_id; ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php echo date('d/m/Y', strtotime($payment['payment_date'])); ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php
                                                                        echo $this->lang->line(strtolower($payment["payment_mode"])) . "<br>";
                                                                        // Add more conditions here if needed (e.g., cheque info)
                                                                        ?>
                                                        </td>
                                                        <td class="text-center text-right">
                                                            <?php echo $payment["amount"]; ?>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                            }
                                                        }
                                                        ?>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <?php } ?>



                                    <?php if (!empty($visitconferences)) { ?>
                                    <div class="box-header mb10 pl-0">
                                        <h3 class="text-uppercase bolds mt0 ptt10 pull-left font14">
                                            <?php echo $this->lang->line('live_consultation'); ?>
                                        </h3>
                                        <div class="pull-right">

                                        </div>
                                    </div>
                                    <div class="box-header mb10 pl-0">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-hover">
                                                <thead>
                                                    <th><?php echo $this->lang->line('consultation_title'); ?></th>
                                                    <th><?php echo $this->lang->line('date'); ?></th>
                                                    <th><?php echo $this->lang->line('created_by'); ?> </th>
                                                    <th><?php echo $this->lang->line('created_for'); ?></th>
                                                    <th><?php echo $this->lang->line('patient'); ?></th>
                                                </thead>
                                                <tbody>
                                                    <?php $i = 0;
                                                        if (empty($visitconferences)) {
                                                        ?>

                                                    <?php
                                                        } else {
                                                            foreach ($visitconferences as $conference_key => $conference_value) {

                                                                ++$i;

                                                                if ($i <= $recent_record_count) {

                                                                    $return_response = json_decode($conference_value->return_response);
                                                            ?>
                                                    <tr>
                                                        <td class="mailbox-name">
                                                            <a href="#" data-toggle="popover"
                                                                class="detail_popover"><?php echo $conference_value->title; ?></a>

                                                            <div class="fee_detail_popover" style="display: none">
                                                                <?php
                                                                                if ($conference_value->description == "") {
                                                                                ?>
                                                                <p class="text text-danger">
                                                                    <?php echo $this->lang->line('no_description'); ?>
                                                                </p>
                                                                <?php
                                                                                } else {
                                                                                ?>
                                                                <p class="text text-info">
                                                                    <?php echo $conference_value->description; ?>
                                                                </p>
                                                                <?php
                                                                                }
                                                                                ?>
                                                            </div>
                                                        </td>

                                                        <td class="mailbox-name">
                                                            <?php echo date($this->customlib->getHospitalDateFormat(true, true), strtotime($conference_value->date)) ?>

                                                        <td class="mailbox-name">

                                                            <?php
                                                                            if ($conference_value->created_id == $logged_staff_id) {
                                                                                echo $this->lang->line('self');
                                                                            } else {
                                                                                $name = ($conference_value->create_by_surname == "") ? $conference_value->create_by_name : $conference_value->create_by_name . " " . $conference_value->create_by_surname;
                                                                                echo $name . " (" . $conference_value->for_create_role_name . " : " . $conference_value->for_create_employee_id . ")";
                                                                            }
                                                                            ?>
                                                        </td>

                                                        <td class="mailbox-name">
                                                            <?php

                                                                            $name = ($conference_value->create_for_surname == "") ? $conference_value->create_for_name : $conference_value->create_for_name . " " . $conference_value->create_for_surname;
                                                                            echo $name . " (" . $conference_value->for_create_role_name . " : " . $conference_value->for_create_employee_id . ")";



                                                                            ?>
                                                        </td>

                                                        <td class="mailbox-name">
                                                            <?php

                                                                            $name = ($conference_value->patient_name == "") ? $conference_value->patient_name : $conference_value->patient_name;
                                                                            echo $name . " (" . $this->lang->line('patient') . " : " . $conference_value->patientid . ")";



                                                                            ?>

                                                        </td>


                                                    </tr>
                                                    <?php
                                                                }
                                                            }
                                                        }
                                                        ?>

                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                    <?php } ?>



                                </div>
                                <!--./col-lg-6-->
                            </div>
                            <!--./row-->
                        </div>
                        <!--#/overview-->
                        <?php if ($this->rbac->hasPrivilege('checkup', 'can_view')) { ?>
                        <div class="tab-pane" id="activity">
                            <div class="box-tab-header">
                                <h3 class="box-tab-title"><?php echo $this->lang->line('checkups'); ?></h3>
                                <div class="box-tab-tools">
                                    <?php if ($this->rbac->hasPrivilege('checkup', 'can_add')) {
                                            if ($is_discharge) { ?>

                                    <a href="#" onclick="getRevisitRecord('<?php echo $visitdata['visitid'] ?>')"
                                        class="btn btn-primary btn-sm revisitrecheckup" data-toggle="modal" title=""><i
                                            class="fa fa-plus"></i>
                                        <?php echo $this->lang->line('new_checkup'); ?></a>
                                    <?php }
                                        } ?>
                                </div>
                            </div>

                            <div class="download_label">
                                <?php echo composePatientName($result['patient_name'], $result['patient_id']) . " " . $this->lang->line('opd_details'); ?>
                            </div>
                            <div class="table-responsive">
                                <h5><?php echo $opd_prefix . $result['id']; ?></h5>
                                <table class="table table-striped table-bordered table-hover" id="ajaxlist"
                                    data-export-title="<?php echo composePatientName($result['patient_name'], $result['id']) . " " . $this->lang->line('opd_details'); ?>">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-center">S.No</th>
                                            <th class="text-center"><?php echo $this->lang->line('checkup_id'); ?></th>
                                            <th class="text-center"><?php echo $this->lang->line('appointment_date'); ?>
                                            </th>
                                            <th class="text-center"><?php echo $this->lang->line('consultant'); ?></th>
                                            <th class="text-center"><?php echo $this->lang->line('reference'); ?></th>
                                            <th class="text-center"><?php echo $this->lang->line('symptoms'); ?></th>
                                            <?php
                                                if (!empty($fields)) {
                                                    foreach ($fields as $fields_key => $fields_value) {
                                                ?>
                                            <th class="text-center"><?php echo $fields_value->name; ?></th>
                                            <?php
                                                    }
                                                }
                                                ?>
                                            <th class="text-center noExport"><?php echo $this->lang->line('action'); ?>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="table-body"></tbody>
                                </table>
                            </div>
                        </div>
                        <?php } ?>
                        <!-------------------------nursenotes------------------------------------------------>
                        <div class="tab-pane tab-content-height " id="nursenotes">
                            <div class="box-tab-header">
                                <h3 class="box-tab-title"><?php echo $this->lang->line('nurse_notes'); ?></h3>
                                <div class="box-tab-tools">
                                    <?php
                                    if ($this->rbac->hasPrivilege('nurse_note', 'can_add')) {

                                        if ($is_discharge) {
                                    ?>
                                    <a href="#" class="btn btn-sm btn-primary dropdown-toggle addnursenote"
                                        onclick="holdModal('add_nurse_note')" data-toggle="modal"><i
                                            class="fas fa-plus"></i>
                                        <?php echo $this->lang->line('add_nurse_note'); ?></a>
                                    <?php
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                            <!--./box-tab-header-->
                            <div class="download_label">
                                <?php echo composePatientName($result['patient_name'], $result['patient_id']) . " " . $this->lang->line('ipd_details'); ?>
                            </div>
                            <div id="">
                                <?php if (empty($nurse_note)) { ?>
                                <div class="alert alert-info"><?php echo $this->lang->line('no_record_found'); ?>
                                </div>
                                <?php } else { ?>
                                <ul class="timeline timeline-inverse">
                                    <?php
                                        foreach ($nurse_note as $key => $value) {
                                            $id = $value['id'];
                                        ?>
                                    <li class="time-label">
                                        <span class="bg-blue">
                                            <?php echo $this->customlib->YYYYMMDDHisTodateFormat($value['date']); ?></span>
                                    </li>
                                    <li>
                                        <i class="fa fa-list-alt bg-blue"></i>
                                        <div class="timeline-item">
                                            <?php if ($is_discharge) {
                                                        if ($this->rbac->hasPrivilege('nurse_note', 'can_delete')) { ?>
                                            <span class="time">

                                                <a class="btn btn-default btn-xs" data-toggle="tooltip" title=""
                                                    onclick="delete_recordcommend('<?php echo $value['id']; ?>', '<?php echo $this->lang->line('delete_message'); ?>')"
                                                    data-original-title="<?php echo $this->lang->line('delete'); ?>">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </span>
                                            <?php }
                                                    } ?>
                                            <?php if ($is_discharge) {
                                                        if ($this->rbac->hasPrivilege('nurse_note', 'can_edit')) {
                                                    ?>
                                            <span class="time">
                                                <a onclick="addcommentNursenote('<?php echo $value['id']; ?>',<?php echo $value['ipd_id']; ?>)"
                                                    class="defaults-c text-right" data-toggle="tooltip" title=""
                                                    data-original-title="<?php echo $this->lang->line('comment'); ?>">
                                                    <i class="fa fa-comment"></i>
                                                </a>
                                            </span>
                                            <span class="time">
                                                <a onclick="editNursenote('<?php echo $value['id']; ?>')"
                                                    class="defaults-c text-right" data-toggle="tooltip" title=""
                                                    data-original-title="<?php echo $this->lang->line('edit'); ?>">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                            </span>

                                            <?php }
                                                    } ?>
                                            <h3 class="timeline-header text-aqua">
                                                <?php echo $value['name'] . ' ' . $value['surname'] . " ( " . $value['employee_id'] . " )"; ?>
                                            </h3>
                                            <div class="timeline-body">
                                                <?php echo "<b>" . $this->lang->line('note') . "</b>" . "</br>" . nl2br($value['note']); ?>
                                            </div>
                                            <?php
                                                    if (!empty($fields_nurse)) {
                                                        foreach ($fields_nurse as $fields_key => $fields_value) {
                                                            if (!empty($fields_value->name)) {
                                                                $display_field = $value[$fields_value->name];
                                                                $fields = $fields_value->name;
                                                            } else {
                                                                $display_field = '';
                                                                $fields = '';
                                                            }

                                                    ?>
                                            <div class="timeline-body">
                                                <?php if ($fields != null) {
                                                                    echo $fields . "</br> " . $display_field;
                                                                }
                                                                ?>
                                            </div>
                                            <?php
                                                        }
                                                    }
                                                    ?>
                                            <div class="timeline-body">
                                                <?php echo "<b>" . $this->lang->line('comment') . "</b>" . "</br> " . nl2br($value['comment']); ?>
                                            </div>

                                            <?php foreach ($nursenote[$id] as $ckey => $cvalue) {
                                                        if (!empty($cvalue['staffname'])) {
                                                            $comment_by =  " (" . $cvalue['staffname'] . " " . $cvalue['staffsurname'] . ": " . $cvalue['employee_id'] . ")";
                                                            $comment_date = $this->customlib->YYYYMMDDHisTodateFormat($cvalue['created_at'], $this->customlib->getHospitalTimeFormat());
                                                        }

                                                    ?>
                                            <div class="timeline-body">
                                                <?php echo nl2br($cvalue['comment_staff']);
                                                            if ($is_discharge) {
                                                                if ($this->rbac->hasPrivilege('nurse_note', 'can_delete')) { ?>
                                                <a class="btn btn-default btn-xs" data-toggle="tooltip" title=""
                                                    onclick="delete_recordnotecommend('<?php echo $cvalue['id']; ?>', '<?php echo $this->lang->line('delete_message'); ?>')"
                                                    data-original-title="<?php echo $this->lang->line('delete'); ?>">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                                <?php }
                                                            } ?>
                                                <span class="pull-right">
                                                    <?php echo $comment_date . " " . $comment_by ?></span>
                                            </div>
                                            <?php  } ?>

                                        </div>
                                    </li>
                                    <?php } ?>
                                    <li><i class="fa fa-clock-o bg-gray"></i></li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                        <!-------------------------nursenotes------------------------------------------------>
                        <div class="tab-pane" id="operationtheatre">
                            <div class="box-tab-header">
                                <h3 class="box-tab-title"><?php echo $this->lang->line("operations"); ?></h3>
                                <div class="box-tab-tools">
                                    <?php if ($is_discharge) {

                                        if ($this->rbac->hasPrivilege('opd_operation_theatre', 'can_add')) { ?>
                                    <a data-toggle="modal" onclick="holdModal('add_operationtheatre')"
                                        class="btn btn-primary btn-sm addoperationtheatre"><i class="fa fa-plus"></i>
                                        <?php echo $this->lang->line("add_operation"); ?></a>
                                    <?php }
                                    } ?>
                                </div>
                            </div>

                            <div class="download_label">
                                <?php echo composePatientName($result['patient_name'], $result['patient_id']) . " " . $this->lang->line('opd_details'); ?>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover example"
                                    data-export-title="<?php echo $this->lang->line('operation_theatre'); ?>">
                                    <thead>
                                        <th><?php echo $this->lang->line("reference_no"); ?></th>
                                        <th><?php echo $this->lang->line("operation_date"); ?></th>
                                        <th><?php echo $this->lang->line("operation_name"); ?></th>
                                        <th><?php echo $this->lang->line("operation_category"); ?></th>
                                        <th><?php echo $this->lang->line("ot_technician"); ?></th>
                                        <?php
                                        if (!empty($ot_fields)) {
                                            foreach ($ot_fields as $fields_key => $fields_value) {
                                        ?>
                                        <th class="white-space-nowrap"><?php echo $fields_value->name; ?></th>
                                        <?php
                                            }
                                        }
                                        ?>
                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?>
                                        </th>
                                    </thead>
                                    <tbody id="">
                                        <?php
                                        if (!empty($operation_theatre)) {
                                            foreach ($operation_theatre as $ot_key => $ot_value) {
                                        ?>
                                        <tr>
                                            <td><?php echo $this->customlib->getSessionPrefixByType('operation_theater_reference_no') . $ot_value["id"] ?>
                                            </td>
                                            <td><?php echo
                                                        $this->customlib->YYYYMMDDHisTodateFormat($ot_value["date"], $this->customlib->getHospitalTimeFormat());
                                                        ?></td>
                                            <td><?php echo $ot_value["operation"]; ?></td>
                                            <td><?php echo $ot_value["category"] ?></td>
                                            <td><?php echo $ot_value['ot_technician'] ?></td>
                                            <?php
                                                    if (!empty($ot_fields)) {

                                                        foreach ($ot_fields as $fields_key => $fields_value) {
                                                            $display_field = $ot_value[$fields_value->name];
                                                            if ($fields_value->type == "link") {
                                                                $display_field = "<a href=" . $ot_value[$fields_value->name] . " target='_blank'>" . $ot_value[$fields_value->name] . "</a>";
                                                            }
                                                    ?>
                                            <td>
                                                <?php echo $display_field; ?>

                                            </td>
                                            <?php
                                                        }
                                                    }
                                                    ?>
                                            <td class="text-right">
                                                <a href='javascript:void(0);' class='btn btn-default btn-xs viewot'
                                                    data-backdrop="static" data-keyboard="false"
                                                    data-loading-text='<i class="fa fa-circle-o-notch fa-spin"></i>'
                                                    data-toggle='tooltip'
                                                    data-record-id='<?php echo $ot_value['id']; ?>'
                                                    title="<?php echo $this->lang->line('show') ?>"><i
                                                        class='fa fa-reorder'></i></a>
                                                <?php
                                                        if ($is_discharge) {
                                                            if ($this->rbac->hasPrivilege('opd_operation_theatre', 'can_edit')) { ?>

                                                <a data-record-id='<?php echo $ot_value['id']; ?>'
                                                    class="btn btn-default btn-xs editot" data-toggle="tooltip" title=""
                                                    data-original-title="<?php echo $this->lang->line('edit'); ?>">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                                <?php }
                                                            if ($this->rbac->hasPrivilege('opd_operation_theatre', 'can_delete')) { ?>

                                                <a onclick="deleteot('<?php echo $ot_value['id']; ?>')"
                                                    class="btn btn-default btn-xs" data-toggle="tooltip" title=""
                                                    data-original-title="<?php echo $this->lang->line('delete'); ?>">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                                <?php
                                                            }
                                                        }
                                                        ?>
                                            </td>
                                        </tr>

                                        <?php }
                                        } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>


                        <div class="tab-pane" id="medication">
                            <div class="box-tab-header">
                                <h3 class="box-tab-title"><?php echo $this->lang->line("medication"); ?></h3>
                                <div class="box-tab-tools">
                                    <?php if ($this->rbac->hasPrivilege('opd_medication', 'can_add')) {
                                        if ($is_discharge) { ?>
                                    <a href="#" class="btn btn-sm btn-primary dropdown-toggle addmedication"
                                        onclick="addmedicationModal()" data-toggle='modal'><i class="fa fa-plus"></i>
                                        <?php echo $this->lang->line("add_medication_dose"); ?></a>
                                    <?php }
                                    } ?>
                                </div>
                            </div>
                            <!--./box-tab-header-->

                            <div class="download_label">
                                <?php echo composePatientName($result['patient_name'], $result['patient_id']) . " " . $this->lang->line('opd_details'); ?>
                            </div>
                            <div class="table_inner">
                                <table class="table table-striped table-bordered table-hover">
                                    <?php
                                    if (!empty($medication)) { ?>
                                    <thead>
                                        <th class="hard_left"><?php echo $this->lang->line("date"); ?> </th>
                                        <th class="next_left"><?php echo $this->lang->line("medicine_name"); ?></th>
                                        <?php
                                            if (!empty($max_dose)) {
                                                $dosage_count = $max_dose;
                                            } else {
                                                $dosage_count = 0;
                                            }

                                            for ($x = 1; $x <= $dosage_count; $x++) { ?>

                                        <th class="sticky-col" width="150">
                                            <?php echo $this->lang->line("dose") . '' . $x; ?>
                                        </th>
                                        <?php }
                                            ?>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $count = 1;
                                            foreach ($medication as $medication_key => $medication_value) {

                                                $pharmacy_id = $medication_value['pharmacy_id'];
                                                $medicine_category_id = $medication_value['medicine_category_id'];
                                                $date = $medication_value['date']; ?>
                                        <tr>
                                            <?php $subcount = 1;
                                                    foreach ($medication_value['dosage'][$date] as $mkey => $mvalue) {
                                                        $date = $this->customlib->YYYYMMDDTodateFormat($medication_value['date']);
                                                    ?>
                                            <td class="hard_left"><?php if ($subcount == 1) {
                                                                                    echo $date . "<br>(" . date('l', strtotime($medication_value['date'])) . ")";
                                                                                } else {
                                                                                    echo "<span class='fa-level-span'><i class='fa fa-level-up fa-level-roated' aria-hidden='true'></i></span>";
                                                                                } ?></td>
                                            <td class="next_left"><?php echo $mvalue['name'] ?></td>
                                            <?php
                                                        for ($x = 0; $x <= $dosage_count; $x++) {
                                                            if (array_key_exists($x, $mvalue['dose_list'])) {
                                                                $medicine_id = $mvalue['dose_list'][$x]['pharmacy_id'];
                                                                $medicine_category_id = $mvalue['dose_list'][$x]['medicine_category_id'];
                                                                $add_index = $x;
                                                                if ($this->rbac->hasPrivilege('opd_medication', 'can_edit')) {
                                                                    $medication_edit = "<a href='#' class='btn btn-default btn-xs' data-toggle='tooltip' data-original-title='" . $this->lang->line('edit') . "' onclick='medicationDoseModal(" . $mvalue['dose_list'][$x]['id'] . ")'><i class='fa fa-pencil'></i></a>";
                                                                } else {
                                                                    $medication_edit = "";
                                                                }

                                                                if ($this->rbac->hasPrivilege('opd_medication', 'can_delete')) {
                                                                    $medication_delete = "<a  class='btn btn-default btn-xs delete_record_dosage' data-toggle='tooltip' data-original-title='" . $this->lang->line('delete') . "' data-record-id='" . $mvalue['dose_list'][$x]['id'] . "'><i class='fa fa-trash'></i></a>";
                                                                } else {
                                                                    $medication_delete = "";
                                                                }

                                                        ?>
                                            <td class="dosehover">
                                                <?php echo $this->lang->line('time') . ": " . date('h:i A', strtotime($mvalue['dose_list'][$x]['time'])) . "</a><span>" . $medication_edit . "</span><span>" . $medication_delete . "</span></br>" . $mvalue['dose_list'][$x]['medicine_dosage'] . " " . $mvalue['dose_list'][$x]['unit'];
                                                                    if ($mvalue['dose_list'][$x]['remark'] != '') {
                                                                        echo " <br>" . $this->lang->line('remark') . ": " . $mvalue['dose_list'][$x]['remark'];
                                                                    } ?>
                                            </td>
                                            <?php
                                                            } else {
                                                            ?>
                                            <td class="dosehover"> <?php
                                                                                        if ($add_index + 1 == $x) {
                                                                                        ?>
                                                <?php if ($this->rbac->hasPrivilege('opd_medication', 'can_add')) {
                                                                                                if ($is_discharge) {
                                                                        ?>
                                                <a href="#" class="btn btn-sm btn-primary dropdown-toggle addmedication"
                                                    onclick="medicationModal('<?php echo $medicine_category_id; ?>','<?php echo $medicine_id; ?>','<?php echo $date; ?>')"
                                                    data-toggle='modal'><i class="fa fa-plus"></i>

                                                </a>
                                                <?php }
                                                                                            } ?>
                                                <?php
                                                                                        }
                                                                    ?>
                                            </td>
                                            <?php
                                                            }
                                                            ?>



                                            <?php } ?>


                                        </tr>
                                        <?php $subcount++;
                                                    }
                                                } ?>

                                    </tbody>
                                    <?php } else { ?>

                                    <tr>
                                        <td>
                                            <div class="alert alert-danger">
                                                <?php echo $this->lang->line('no_record_found'); ?>
                                            </div>
                                        </td>
                                    </tr>

                                    <?php } ?>
                                </table>

                            </div>
                        </div>

                        <!-- -->
                        <div class="tab-pane" id="labinvestigation">
                            <div class="box-tab-header">
                                <h3 class="box-tab-title">
                                    <?php echo $this->lang->line('lab_investigation'); ?>
                                </h3>


                            </div>
                            <div class="download_label">
                                <?php echo composePatientName($result['patient_name'], $result['patient_id']) . " " . $this->lang->line('opd_details'); ?>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover example"
                                    data-export-title="<?php echo $this->lang->line('lab_investigation'); ?>">
                                    <thead>
                                        <th><?php echo $this->lang->line('test_name'); ?></th>
                                        <th><?php echo $this->lang->line('lab'); ?></th>
                                        <th><?php echo $this->lang->line('sample_collected'); ?></th>
                                        <td><strong><?php echo $this->lang->line('expected_date'); ?></strong></td>
                                        <th><?php echo $this->lang->line('approved_by'); ?></th>
                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?>
                                        </th>
                                    </thead>
                                    <tbody id="">
                                        <?php foreach ($investigations as $row) { ?>
                                        <tr>
                                            <td><?php echo $row['test_name']; ?><br />
                                                <?php echo "(" . $row['short_name'] . ")"; ?></td>
                                            <td><?php echo $this->lang->line($row['type']); ?></td>
                                            <td><label>
                                                    <?php echo composeStaffNameByString($row['collection_specialist_staff_name'], $row['collection_specialist_staff_surname'], $row['collection_specialist_staff_employee_id']); ?>
                                                </label>

                                                <br />
                                                <label for=""><?php if ($row['type'] == 'pathology') {
                                                                        echo $this->lang->line('pathology');
                                                                    } else {
                                                                        echo $this->lang->line('radiology');
                                                                    } ?> : </label>

                                                <?php
                                                    echo $row['test_center'];
                                                    ?>
                                                <br />
                                                <?php echo $this->customlib->YYYYMMDDTodateFormat($row['collection_date']); ?>
                                            </td>

                                            <td>
                                                <?php

                                                    echo $this->customlib->YYYYMMDDTodateFormat($row['reporting_date']); ?>

                                            </td>
                                            <td class="text-left">
                                                <label for=""><?php echo $this->lang->line('approved_by'); ?> :
                                                </label>
                                                <?php
                                                    echo composeStaffNameByString($row['approved_by_staff_name'], $row['approved_by_staff_surname'], $row['approved_by_staff_employee_id']);
                                                    ?>
                                                <br />
                                                <?php
                                                    echo $this->customlib->YYYYMMDDTodateFormat($row['parameter_update']);
                                                    ?>
                                            </td>
                                            <td class="text-right"><a href='javascript:void(0)'
                                                    data-loading-text='<i class="fa fa-reorder"></i>'
                                                    data-record-id='<?php echo $row['report_id']; ?>'
                                                    data-type-id='<?php echo $row['type']; ?>'
                                                    class='btn btn-default btn-xs view_report' data-toggle='tooltip'
                                                    title='<?php echo $this->lang->line("show"); ?>'><i
                                                        class='fa fa-reorder'></i></a></td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>



                        <!-- Charges -->
                        <?php if ($this->rbac->hasPrivilege('opd_charges', 'can_view')) { ?>
                        <div class="tab-pane" id="charges">
                            <div class="box-tab-header">
                                <h3 class="box-tab-title"><?php echo $this->lang->line('charges'); ?></h3>
                                <div class="box-tab-tools">
                                    <?php if ($this->rbac->hasPrivilege('opd_charges', 'can_add') && $is_discharge) { ?>
                                    <a data-toggle="modal" onclick="holdModal('add_chargeModal')"
                                        class="btn btn-primary btn-sm addcharges">
                                        <i class="fa fa-plus"></i> <?php echo $this->lang->line('add_charges') ?>
                                    </a>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="download_label">
                                <?php echo composePatientName($result['patient_name'], $result['patient_id']) . " " . $this->lang->line('opd_details'); ?>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover ipdcharged" id="opdcharged"
                                    style="width: 100%; table-layout: auto; border-collapse: collapse;">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>S.No</th>
                                            <th><?php echo $this->lang->line('date'); ?></th>
                                            <th><?php echo $this->lang->line('name'); ?></th>
                                            <th><?php echo $this->lang->line('standard_charge') . ' (' . $currency_symbol . ')'; ?>
                                            </th>
                                            <th><?php echo $this->lang->line('qty'); ?></th>
                                            <th><?php echo $this->lang->line('applied_charge') . ' (' . $currency_symbol . ')'; ?>
                                            </th>
                                            <th><?php echo $this->lang->line('discount') . ' (%)'; ?></th>
                                            <th><?php echo "Additional Charges" . ' (' . $currency_symbol . ')'; ?></th>
                                            <th><?php echo "Sub Total" . ' (' . $currency_symbol . ')'; ?></th>
                                            <th><?php echo $this->lang->line('tax') . ' (%)'; ?></th>
                                            <th><?php echo $this->lang->line('net_amount') . ' (' . $currency_symbol . ')'; ?>
                                            </th>
                                            <th><?php echo $this->lang->line('tpa_charge') . ' (' . $currency_symbol . ')'; ?>
                                            </th>
                                            <th><?php echo $this->lang->line('status'); ?>
                                            </th>
                                            <th><?php echo $this->lang->line('action'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                        <?php }

                        if ($this->rbac->hasPrivilege('opd_payment', 'can_view')) {
                        ?>
                        <div class="tab-pane" id="payment">
                            <div class="box-tab-header">
                                <h3 class="box-tab-title"><?php echo $this->lang->line('payments'); ?></h3>

                                <?php
                                    if ($this->rbac->hasPrivilege('opd_payment', 'can_add')) {
                                        if ($is_discharge) { ?>


                                <div class="box-tab-tools">

                                    <a href="#" class="btn btn-sm btn-primary dropdown-toggle addpayment"
                                        data-toggle='modal'><i class="fa fa-plus"></i>
                                        <?php echo $this->lang->line('add_payment'); ?></a>
                                </div>
                                <!--./impbtnview-->
                                <?php
                                        }
                                    }
                                    ?>
                            </div>
                            <div class="download_label"><?php echo $this->lang->line('payments'); ?></div>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover ipdcharged" id="opdpayment"
                                    style="table-layout: auto">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th><?php echo $this->lang->line('transaction_id'); ?></th>
                                            <th><?php echo $this->lang->line('date'); ?></th>
                                            <th><?php echo $this->lang->line('note'); ?></th>
                                            <th><?php echo $this->lang->line('payment_mode'); ?></th>
                                            <th class="text-right">
                                                <?php echo $this->lang->line('paid_amount') . " (" . $currency_symbol . ")"; ?>
                                            </th>
                                            <th class="text-right noExport"><?php echo $this->lang->line('action') ?>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="table-body"></tbody>
                                </table>
                            </div>
                        </div>
                        <!-- -->
                        <?php } ?>
                        <div class="tab-pane" id="timeline">
                            <div class="box-tab-header">
                                <h3 class="box-tab-title"><?php echo $this->lang->line('timeline'); ?></h3>
                                <div class="box-tab-tools">
                                    <?php if ($this->rbac->hasPrivilege('opd_timeline', 'can_add')) {
                                        if ($is_discharge) { ?>
                                    <a data-toggle="modal" onclick="holdModal('myTimelineModal')"
                                        class="btn btn-primary btn-sm addtimeline"><i class="fa fa-plus"></i>
                                        <?php echo $this->lang->line('add_timeline'); ?></a>
                                    <?php }
                                    } ?>
                                </div>
                            </div>
                            <div class="timeline-header no-border">

                                <div id="timeline_list">
                                    <?php
                                    if (empty($timeline_list)) {
                                    ?>
                                    <br />
                                    <div class="alert alert-info">
                                        <?php echo $this->lang->line('no_record_found'); ?>
                                    </div>
                                    <?php } else {
                                    ?>
                                    <ul class="timeline timeline-inverse">

                                        <?php
                                            foreach ($timeline_list as $key => $value) {
                                            ?>
                                        <li class="time-label">
                                            <span class="bg-blue">

                                                <?php
                                                        echo date('d/m/Y', strtotime($value['timeline_date']));
                                                        ?>

                                            </span>
                                        </li>
                                        <li>
                                            <i class="fa fa-list-alt bg-blue"></i>
                                            <div class="timeline-item">
                                                <?php if ($this->rbac->hasPrivilege('opd_timeline', 'can_edit')) {
                                                            if ($is_discharge) {
                                                                if ($value['generated_users_type'] != 'patient') {

                                                        ?>
                                                <span class="time">
                                                    <a onclick="editTimeline('<?php echo $value['id']; ?>')"
                                                        class="btn btn-default btn-xs" data-toggle="tooltip" title=""
                                                        data-original-title="<?php echo $this->lang->line('edit'); ?>">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                </span>
                                                <?php }
                                                            }
                                                        } ?>
                                                <?php if ($this->rbac->hasPrivilege('opd_timeline', 'can_delete')) {
                                                            if ($value['generated_users_type'] != 'patient') {
                                                                if ($is_discharge) {
                                                        ?>
                                                <span class="time"><a class="defaults-c text-right"
                                                        data-toggle="tooltip" title=""
                                                        onclick="delete_timeline('<?php echo $value['id']; ?>')"
                                                        data-original-title="<?php echo $this->lang->line('delete'); ?>"><i
                                                            class="fa fa-trash"></i></a></span>
                                                <?php }
                                                            }
                                                        } ?>
                                                <?php if (!empty(trim($value["document"])) && strtolower(trim($value["document"])) !== "none") { ?>
                                                <span class="time">
                                                    <?php
                                                                $userdata = $this->session->userdata('hospitaladmin');
                                                                $accessToken = $userdata['accessToken'] ?? '';
                                                                $document = $value["document"];
                                                                $documentId = $value["id"];
                                                                $url = "https://phr-api.plenome.com/file_upload/getDocs";
                                                                $client = curl_init($url);

                                                                curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
                                                                curl_setopt($client, CURLOPT_POST, true);
                                                                curl_setopt($client, CURLOPT_POSTFIELDS, json_encode(['value' => $document]));
                                                                curl_setopt($client, CURLOPT_HTTPHEADER, [
                                                                    'Content-Type: application/json',
                                                                    'Authorization: ' . $accessToken
                                                                ]);

                                                                $response = curl_exec($client);
                                                                curl_close($client);

                                                                if ($response !== false) {
                                                                    $base64File = trim($response);
                                                                    $fileData = base64_decode($base64File);

                                                                    if ($fileData !== false) {
                                                                        $fileInfo = pathinfo($document);
                                                                        $fileExtension = strtolower($fileInfo['extension'] ?? 'bin');

                                                                        $mimeTypes = [
                                                                            'jpg' => 'image/jpeg',
                                                                            'jpeg' => 'image/jpeg',
                                                                            'png' => 'image/png',
                                                                            'gif' => 'image/gif',
                                                                            'pdf' => 'application/pdf',
                                                                            'txt' => 'text/plain',
                                                                            'doc' => 'application/msword',
                                                                            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                                                            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                                                            'xls' => 'application/vnd.ms-excel',
                                                                            'csv' => 'text/csv',
                                                                            'zip' => 'application/zip',
                                                                        ];

                                                                        $mimeType = $mimeTypes[$fileExtension] ?? 'application/octet-stream';
                                                                        $fileName = preg_replace('/_\d+$/', '', $fileInfo['basename']);


                                                                        echo '<a class="defaults-c text-right" data-toggle="tooltip" title="' . $this->lang->line('download') . '" 
                                                                        href="data:' . $mimeType . ';base64,' . base64_encode($fileData) . '" 
                                                                        download="' . $fileName . '">
                                                                        <i class="fa fa-download"></i>
                                                                    </a>';
                                                                    }
                                                                }
                                                                ?>
                                                </span>
                                                <?php } ?>

                                                <h3 class="timeline-header text-aqua">
                                                    <?php echo $value['title']; ?>
                                                </h3>
                                                <div class="timeline-body">
                                                    <?php echo $value['description']; ?>

                                                </div>

                                            </div>
                                        </li>
                                        <?php } ?>
                                        <li><i class="fa fa-clock-o bg-gray"></i></li>
                                        <?php } ?>

                                    </ul>
                                </div>

                            </div>

                        </div>
                        <!-- -->

                        <div class="tab-pane" id="live_consult">
                            <div class="box-tab-header">
                                <h3 class="box-tab-title"><?php echo $this->lang->line('live_consultation'); ?></h3>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover example">
                                    <thead>
                                        <th><?php echo $this->lang->line('consultation_title'); ?></th>
                                        <th><?php echo $this->lang->line('date'); ?></th>
                                        <th><?php echo $this->lang->line('created_by'); ?> </th>
                                        <th><?php echo $this->lang->line('created_for'); ?></th>
                                        <th><?php echo $this->lang->line('patient'); ?></th>
                                        <th><?php echo $this->lang->line('status'); ?></th>
                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?>
                                        </th>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (empty($visitconferences)) {
                                        ?>

                                        <?php
                                        } else {
                                            foreach ($visitconferences as $conference_key => $conference_value) {

                                                $return_response = json_decode($conference_value->return_response);
                                            ?>
                                        <tr>
                                            <td class="mailbox-name">
                                                <a href="#" data-toggle="popover"
                                                    class="detail_popover"><?php echo $conference_value->title; ?></a>

                                                <div class="fee_detail_popover" style="display: none">
                                                    <?php
                                                            if ($conference_value->description == "") {
                                                            ?>
                                                    <p class="text text-danger">
                                                        <?php echo $this->lang->line('no_description'); ?>
                                                    </p>
                                                    <?php
                                                            } else {
                                                            ?>
                                                    <p class="text text-info">
                                                        <?php echo $conference_value->description; ?>
                                                    </p>
                                                    <?php
                                                            }
                                                            ?>
                                                </div>
                                            </td>

                                            <td class="mailbox-name">
                                                <?php echo date($this->customlib->getHospitalDateFormat(true, true), strtotime($conference_value->date)) ?>

                                            <td class="mailbox-name">

                                                <?php
                                                        if ($conference_value->created_id == $logged_staff_id) {
                                                            echo $this->lang->line('self');
                                                        } else {
                                                            $name = ($conference_value->create_by_surname == "") ? $conference_value->create_by_name : $conference_value->create_by_name . " " . $conference_value->create_by_surname;
                                                            echo $name . " (" . $conference_value->for_create_role_name . " : " . $conference_value->for_create_employee_id . ")";
                                                        }
                                                        ?>
                                            </td>

                                            <td class="mailbox-name">
                                                <?php

                                                        $name = ($conference_value->create_for_surname == "") ? $conference_value->create_for_name : $conference_value->create_for_name . " " . $conference_value->create_for_surname;
                                                        echo $name . " (" . $conference_value->for_create_role_name . " : " . $conference_value->for_create_employee_id . ")";



                                                        ?>
                                            </td>

                                            <td class="mailbox-name">
                                                <?php

                                                        $name = ($conference_value->patient_name == "") ? $conference_value->patient_name : $conference_value->patient_name;
                                                        echo $name . " (" . $this->lang->line('patient') . " : " . $conference_value->patientid . ")";



                                                        ?>

                                            </td>
                                            <td class="mailbox-name">
                                                <form class="chgstatus_form" method="POST"
                                                    action="<?php echo site_url('admin/zoom_conference/changeconsultation') ?>">
                                                    <input type="hidden" name="conference_id"
                                                        value="<?php echo $conference_value->id; ?>">
                                                    <select class="form-control chgstatus_dropdown" name="chg_status">
                                                        <option value="0" <?php if ($conference_value->status == 0)
                                                                                        echo "selected='selected'" ?>>
                                                            <?php echo $this->lang->line('awaited'); ?>
                                                        </option>
                                                        <option value="1" <?php if ($conference_value->status == 1)
                                                                                        echo "selected='selected'" ?>>
                                                            <?php echo $this->lang->line('cancelled'); ?>
                                                        </option>
                                                        <option value="2" <?php if ($conference_value->status == 2)
                                                                                        echo "selected='selected'" ?>>
                                                            <?php echo $this->lang->line('finished'); ?>
                                                        </option>
                                                    </select>
                                                </form>
                                            </td>
                                            <td class="mailbox-date pull-right relative">
                                                <?php
                                                        if ($conference_value->status == 0) {
                                                        ?>
                                                <a href="<?php echo $return_response->start_url; ?>"
                                                    class="btn btn-default btn-xs starsuccessbtn" target="_blank">
                                                    <i class="fa fa-sign-in"></i>
                                                    <?php echo $this->lang->line('start'); ?>
                                                </a>
                                                <?php
                                                        }
                                                        ?>

                                                <?php
                                                        if ($conference_value->api_type != 'self') {
                                                        ?>
                                                <?php
                                                            if ($this->rbac->hasPrivilege('live_classes', 'can_delete')) {
                                                            ?>
                                                <a href="<?php echo base_url(); ?>admin/zoom_conference/delete_consult/<?php echo $conference_value->id . "/" . $return_response->id; ?>"
                                                    class="btn btn-default btn-xs" data-toggle="tooltip"
                                                    title="<?php echo $this->lang->line('delete'); ?>"
                                                    onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                    <i class="fa fa-remove"></i>
                                                </a>
                                                <?php
                                                            }
                                                            ?>

                                                <?php
                                                        }
                                                        ?>

                                            </td>
                                        </tr>
                                        <?php
                                            }
                                        }
                                        ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="tab-pane" id="prescription">
                            <div class="box-tab-header">
                                <h3 class="box-tab-title"><?php echo $this->lang->line('prescription'); ?></h3>
                                <div class="box-tab-tools">
                                    <?php if ($this->rbac->hasPrivilege('ipd_prescription ', 'can_add')) {
                                        if ($is_discharge) {
                                    ?>
                                    <a href="#" onclick="getRecord_id('<?php echo $result['id'] ?>')"
                                        class="btn btn-sm btn-primary dropdown-toggle addprescription"
                                        data-toggle="modal"><i class="fas fa-plus"></i>
                                        Clinical Notes</a>
                                    <?php }
                                    } ?>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <!-- <table class="table table-striped table-bordered table-hover example">
                                    <thead>
                                        <tr>
                                            <th><?php echo $this->lang->line('opd') . " " . $this->lang->line('id'); ?>
                                            </th>
                                            <th><?php echo $this->lang->line('medicine_name'); ?></th>
                                            <th><?php echo $this->lang->line('note'); ?></th>
                                            <th>Prescribed At</th>
                                            <th class="text-right noExport">
                                                <?php echo $this->lang->line('action'); ?>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($prescription_detail)) { ?>
                                        <?php foreach ($prescription_detail as $prescription_value) { ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($prescription_value["opd_id"]); ?></td>
                                            <td><?php echo htmlspecialchars($prescription_value["medicine_name"]); ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($prescription_value["remarks"]); ?></td>
                                            <td><?php echo htmlspecialchars($prescription_value["created_at"]); ?>
                                            </td>
                                            <td class="text-center"
                                                style="align-items: center;left: 59px;position: relative;">
                                                <a href="#" data-toggle="tooltip"
                                                    title="<?php echo $this->lang->line('test_report_detail'); ?>"
                                                    onclick="view_prescription('<?php echo htmlspecialchars($prescription_value['opd_id']); ?>')">
                                                    <i class="fa fa-reorder"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                        <?php } else { ?>
                                        <tr>
                                            <td colspan="5" class="text-center">No prescriptions found</td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table> -->

                            </div>


                        </div>
                        <!-- -->
                    </div>

                </div>
                </form>

            </div>
    </section>
</div>



<!--new edit modal-->
<div class="modal fade" id="editModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog pup100" role="document">
        <form id="formedit" accept-charset="utf-8" enctype="multipart/form-data" method="post" class="ptt10">
            <div class="modal-content modal-media-content">
                <div class="modal-header modal-media-header">
                    <button type="button" class="close pupclose" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"> <?php echo $this->lang->line('edit_visit_details'); ?></h4>
                </div>
            </div>
            <!--./modal-header-->

            <div class="pup-scroll-area">
                <div class="modal-body pt0 pb0">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="row row-eq">
                                <div class="col-lg-8 col-md-8 col-sm-8">
                                    <div id="ajax_load"></div>


                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="dividerhr"></div>
                                        </div>
                                        <!--./col-md-12-->
                                        <input type="hidden" name="visitid" id="visitid" class="form-control" />
                                        <input type="hidden" name="visit_transaction_id" id="visit_transaction_id"
                                            class="form-control" />
                                        <input type="hidden" name="type" id="type" value="visit" class="form-control" />
                                        <div class="col-sm-2 col-xs-4">
                                            <div class="form-group">
                                                <label for="pwd"><?php echo $this->lang->line('height'); ?></label>
                                                <input id="edit_height" name="height" type="text" class="form-control"
                                                    onkeyup="this.value=this.value.replace(/[^0-9.]/g,'')" />
                                            </div>
                                        </div>
                                        <div class="col-sm-2 col-xs-4">
                                            <div class="form-group">
                                                <label for="pwd"><?php echo $this->lang->line('weight'); ?></label>
                                                <input id="edit_weight" name="weight" type="text" class="form-control"
                                                    onkeyup="this.value=this.value.replace(/[^0-9.]/g,'')" />
                                            </div>
                                        </div>
                                        <div class="col-sm-2 col-xs-4">
                                            <div class="form-group">
                                                <label for="pwd"><?php echo $this->lang->line('bp'); ?></label>
                                                <input name="bp" type="text" name="bp" class="form-control" id="edit_bp"
                                                    onkeyup="this.value=this.value.replace(/[^0-9.]/g,'')" />
                                            </div>
                                        </div>
                                        <div class="col-sm-2 col-xs-4">
                                            <div class="form-group">
                                                <label for="pwd"><?php echo $this->lang->line('pulse'); ?></label>
                                                <input id="edit_pulse" name="pulse" type="text" class="form-control"
                                                    onkeyup="this.value=this.value.replace(/[^0-9.]/g,'')" />
                                            </div>
                                        </div>
                                        <div class="col-sm-2 col-xs-4">
                                            <div class="form-group">
                                                <label for="pwd"><?php echo $this->lang->line('temperature'); ?></label>
                                                <input id="edit_temperature" name="temperature" type="text"
                                                    class="form-control"
                                                    onkeyup="this.value=this.value.replace(/[^0-9.]/g,'')" />
                                            </div>
                                        </div>
                                        <div class="col-sm-2 col-xs-4">
                                            <div class="form-group">
                                                <label for="pwd"><?php echo $this->lang->line('respiration'); ?></label>
                                                <input name="respiration" class="form-control" id="edit_respiration"
                                                    type="text" class="form-control"
                                                    onkeyup="this.value=this.value.replace(/[^0-9.]/g,'')" />
                                            </div>
                                        </div>
                                        <div class="col-sm-2 col-xs-2">
                                            <div class="form-group">
                                                <label for="pwd">SPO2</label>
                                                <input name="spo2" class="form-control" id="edit_spo2" type="text"
                                                    class="form-control"
                                                    onkeyup="this.value=this.value.replace(/[^0-9.]/g,'')" />
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="exampleInputFile">
                                                    <?php echo $this->lang->line('symptoms_type'); ?></label>
                                                <div>
                                                    <select name='symptoms_type' id="act"
                                                        class="form-control select2 act" style="width:100%">
                                                        <option value=""><?php echo $this->lang->line('select'); ?>
                                                        </option>
                                                        <?php foreach ($symptomsresulttype as $dkey => $dvalue) {
                                                        ?>
                                                        <option value="<?php echo $dvalue["id"]; ?>">
                                                            <?php echo $dvalue["symptoms_type"]; ?>
                                                        </option>

                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <span
                                                    class="text-danger"><?php echo form_error('symptoms_type'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label for="exampleInputFile">
                                                    <?php echo $this->lang->line('symptoms'); ?></label>
                                                <div id="dd" class="wrapper-dropdown-3">
                                                    <input class="form-control filterinput" type="text">
                                                    <ul class="dropdown scroll150 section_ul">
                                                        <li><label
                                                                class="checkbox"><?php echo $this->lang->line('select'); ?></label>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 col-xs-8">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('symptoms_description'); ?></label>
                                                <textarea class="form-control" id="symptoms_description"
                                                    name="symptoms"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <label for="pwd"><?php echo $this->lang->line('note'); ?></label>
                                                <textarea rows="3" class="form-control" id="edit_revisit_note"
                                                    name="revisit_note"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <label
                                                    for="email"><?php echo $this->lang->line('any_known_allergies'); ?></label>
                                                <textarea name="known_allergies" rows="3" id="eknown_allergies"
                                                    placeholder=""
                                                    class="form-control"><?php echo set_value('address'); ?></textarea>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <div id="customfield"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--./row-->

                                </div>
                                <!--./col-md-8-->
                                <div class="col-lg-4 col-md-4 col-sm-4 col-eq ptt10">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('appointment_date'); ?></label>
                                                <small class="req"> *</small>
                                                <input name="appointment_date" class="form-control datetime"
                                                    id="appointmentdate" placeholder="" type="text" value="" readonly
                                                    style="pointer-events: none;" />

                                                <span
                                                    class="text-danger"><?php echo form_error('appointment_date'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="exampleInputFile">
                                                    <?php echo $this->lang->line('case'); ?>
                                                </label>
                                                <!-- <small class="req"> *</small> -->
                                                <div><input class="form-control" type='text' name="case" id="edit_case"
                                                        onkeyup="this.value=this.value.replace(/[^a-zA-Z]/g,'')" />
                                                </div>
                                                <span class="text-danger"><?php echo form_error('case'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="exampleInputFile">
                                                    <?php echo $this->lang->line('casualty'); ?></label>
                                                <div>
                                                    <select name="casualty" id="edit_casualty" class="form-control">
                                                        <?php foreach ($yesno_condition as $yesno_key => $yesno_value) {
                                                        ?>
                                                        <option value="<?php echo $yesno_key ?>" <?php
                                                                                                        if ($yesno_key == 'no') {
                                                                                                            echo "selected";
                                                                                                        }
                                                                                                        ?>>
                                                            <?php echo $yesno_value ?>
                                                        </option>
                                                        <?php } ?>
                                                    </select>

                                                </div>
                                                <span class="text-danger"><?php echo form_error('case'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="exampleInputFile">
                                                    <?php echo $this->lang->line('old_patient'); ?></label>
                                                <div>
                                                    <select name="old_patient" id="edit_oldpatient"
                                                        class="form-control">
                                                        <?php foreach ($yesno_condition as $yesno_key => $yesno_value) { ?>
                                                        <option value="<?php echo $yesno_key ?>">
                                                            <?php echo $yesno_value ?>
                                                        </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <span class="text-danger"><?php echo form_error('case'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="exampleInputFile">
                                                    <?php echo $this->lang->line('tpa'); ?></label>
                                                <div><select class="form-control" onchange="get_Charges(this.value)"
                                                        id="edit_organisation" name='organisation'>
                                                        <option value="0"><?php echo $this->lang->line('select'); ?>
                                                        </option>
                                                        <?php foreach ($organisation as $orgkey => $orgvalue) {
                                                        ?>
                                                        <option value="<?php echo $orgvalue["id"]; ?>">
                                                            <?php echo $orgvalue["organisation_name"] ?>
                                                        </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <span class="text-danger"><?php echo form_error('refference'); ?></span>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="exampleInputFile">
                                                    <?php echo $this->lang->line('reference'); ?></label>
                                                <div><input type="text" name="refference" class="form-control"
                                                        id="edit_refference" />
                                                </div>
                                                <span class="text-danger"><?php echo form_error('refference'); ?></span>
                                            </div>
                                        </div>


                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('consultant_doctor'); ?></label><small
                                                    class="req"> *</small>
                                                <select onchange="" name="consultant_doctor" <?php
                                                                                                if ($disable_option == true) {
                                                                                                    echo "disabled";
                                                                                                }
                                                                                                ?> style="width:100%"
                                                    class="form-control select2" id="edit_consdoctor">
                                                    <option value="" disabled><?php echo $this->lang->line('select'); ?>
                                                    </option>

                                                    <?php foreach ($doctors as $dkey => $dvvalue) { ?>
                                                    <option value="<?php echo $dvvalue["id"] ?>">
                                                        <?php echo composeStaffNameByString($dvvalue["name"], $dvvalue["surname"], $dvvalue["employee_id"]); ?>
                                                    </option>
                                                    <?php } ?>
                                                </select>
                                                <?php if ($disable_option == true) { ?>
                                                <input type="hidden" name="consultant_doctor"
                                                    value="<?php echo $doctor_select ?>">
                                                <?php } ?>
                                            </div>
                                            <span class="text-danger"><?php echo form_error('refference'); ?></span>
                                        </div>


                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('payment_date'); ?></label><small
                                                    class="req"> *</small>

                                                <input type="text" name="payment_date" id="edit_visit_payment_date"
                                                    class="form-control datetime" autocomplete="off" readonly
                                                    style="pointer-events: none;">

                                                <input type="hidden" class="form-control" id="edit_visit_payment_id"
                                                    name="edit_payment_id">
                                                <span class="text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('amount') . " (" . $currency_symbol . ")" ?></label><small
                                                    class="req"> *</small> <input type="text" name="amount"
                                                    id="edit_visit_payment" class="form-control" value="" readonly>

                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label
                                                    for="pwd"><?php echo $this->lang->line('payment_mode'); ?></label>
                                                <select class="form-control visit_payment_mode" name="payment_mode"
                                                    id="visit_payment_mode" disabled>

                                                    <?php foreach ($payment_mode as $key => $value) {
                                                    ?>
                                                    <option value="<?php echo $key ?>" <?php
                                                                                            if ($key == 'cash') {
                                                                                                echo "selected";
                                                                                            }
                                                                                            ?>><?php echo $value ?>
                                                    </option>
                                                    <?php } ?>
                                                </select>
                                                <input type="hidden" name="defult_val" id="defult_val">
                                            </div>
                                        </div>
                                        <!--  <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="pwd"><?php echo $this->lang->line('paid_amount') . " (" . $currency_symbol . ")"; ?></label><small class="req"> *</small>
                                                <input type="text" name="paid_amount" id="paid_amount" class="form-control">
                                                <span class="text-danger"><?php echo form_error('paid_amount'); ?></span>
                                            </div>
                                        </div> -->
                                        <div class="cheque_div" style="display: none;">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label><?php echo $this->lang->line('cheque_no'); ?></label><small
                                                        class="req"> *</small>
                                                    <input type="text" name="cheque_no" id="edit_visit_cheque_no"
                                                        class="form-control">
                                                    <span class="text-danger"></span>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label><?php echo $this->lang->line('cheque_date'); ?></label><small
                                                        class="req"> *</small>
                                                    <input type="text" name="cheque_date" id="edit_visit_cheque_date"
                                                        class="form-control date">
                                                    <span class="text-danger"></span>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label><?php echo $this->lang->line('attach_document'); ?></label>
                                                    <input type="file" class="filestyle form-control" name="document">
                                                    <span
                                                        class="text-danger"><?php echo form_error('document'); ?></span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- <div class="col-sm-12">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('payment_note'); ?></label>
                                                <input type="text" name="note" id="edit_visit_payment_note"
                                                    class="form-control" />
                                            </div>
                                        </div> -->
                                    </div>
                                    <!--./row-->
                                </div>
                                <!--./col-md-4-->
                            </div>
                            <!--./row-->
                        </div>
                        <!--./col-md-12-->
                    </div>
                    <!--./row-->
                </div>
            </div>

            <div class="box-footer sticky-footer">
                <div class="pull-right">
                    <button type="submit" id="formeditbtn" name="save"
                        data-loading-text="<?php echo $this->lang->line('processing') ?>"
                        class="btn btn-info pull-right"><i class="fa fa-check-circle"></i>
                        <span><?php echo $this->lang->line('save'); ?></span></button>
                </div>
            </div>
        </form>
    </div>
</div>
</div>


<!-- end new added modal-->


<!-- Add Charges -->
<div class="modal fade" id="edit_chargeModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('edit_charge'); ?></h4>
            </div>
            <form id="edit_charges" accept-charset="utf-8" method="post" class="ptt10">
                <div class="scroll-area">
                    <div class="modal-body pt0">

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">

                                <input type="hidden" name="opd_id" value="<?php echo $result['id'] ?>" id='opd_id'>
                                <input type="hidden" name="patient_charge_id" id="editpatient_charge_id" value="0">
                                <input type="hidden" name="organisation_id" id="editorganisation_id"
                                    value="<?php echo $visitdata['organisation_id'] ?>">

                                <div class="row">

                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('charge_type'); ?></label><small
                                                class="req"> *</small>

                                            <select name="charge_type" id="editcharge_type"
                                                class="form-control editcharge_type select2" style="width:100%">
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                <?php foreach ($charge_type as $key => $value) {
                                                ?>
                                                <option value="<?php echo $value->id; ?>">
                                                    <?php echo $value->charge_type; ?>
                                                </option>
                                                <?php } ?>
                                            </select>
                                            <span class="text-danger"><?php echo form_error('charge_type'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('charge_category'); ?></label><small
                                                class="req"> *</small>

                                            <select name="charge_category" id="editcharge_category" style="width: 100%"
                                                class="form-control select2 editcharge_category">
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            </select>
                                            <span
                                                class="text-danger"><?php echo form_error('charge_category'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('charge_name'); ?></label><small
                                                class="req"> *</small>
                                            <select name="charge_id" id="editcharge_id" style="width: 100%"
                                                class="form-control editcharge select2 ">
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            </select>
                                            <span class="text-danger"><?php echo form_error('code'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('standard_charge') . " (" . $currency_symbol . ")" ?></label>
                                            <input type="text" readonly name="standard_charge" id="editstandard_charge"
                                                class="form-control"
                                                value="<?php echo set_value('standard_charge'); ?>">

                                            <span
                                                class="text-danger"><?php echo form_error('standard_charge'); ?></span>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('tpa_charge') . " (" . $currency_symbol . ")" ?></label>
                                            <input type="text" readonly name="schedule_charge" id="editscd_charge"
                                                placeholder="" class="form-control"
                                                value="<?php echo set_value('schedule_charge'); ?>">
                                            <span
                                                class="text-danger"><?php echo form_error('schedule_charge'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('qty'); ?></label><small class="req">
                                                *</small>
                                            <input type="text" name="qty" id="editqty" class="form-control" value="1">
                                            <span class="text-danger"><?php echo form_error('qty'); ?></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="divider"></div>

                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('date'); ?></label> <small class="req">
                                                *</small>

                                            <input id="editcharge_date" name="date" placeholder="" type="text"
                                                class="form-control datetime" />
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="row">

                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label><?php echo $this->lang->line('charge_note'); ?></label>
                                                    <textarea name="note" id="edit_note" rows="3"
                                                        class="form-control"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--./col-sm-6-->


                                    <div class="col-sm-6">

                                        <table class="printablea4">


                                            <tr>
                                                <th width="40%">
                                                    <?php echo $this->lang->line('total') . " (" . $currency_symbol . ")"; ?>
                                                </th>
                                                <td width="60%" colspan="2" class="text-right ipdbilltable">
                                                    <input type="text" placeholder="Total" value="0" name="apply_charge"
                                                        id="editapply_charge" style="width: 30%; float: right"
                                                        class="form-control total" readonly />
                                                </td>
                                            </tr>
                                            <tr>
                                                <th><?php echo $this->lang->line('tax') . " (" . $currency_symbol . ")"; ?>
                                                </th>
                                                <td class="text-right ipdbilltable">
                                                    <h4 style="float: right;font-size: 12px; padding-left: 5px;"> %</h4>
                                                    <input type="text"
                                                        placeholder="<?php echo $this->lang->line('tax'); ?>"
                                                        name="charge_tax" id="editcharge_tax"
                                                        class="form-control charge_tax" readonly
                                                        style="width: 70%; float: right;font-size: 12px;" />
                                                </td>

                                                <td class="text-right ipdbilltable">
                                                    <input type="text"
                                                        placeholder="<?php echo $this->lang->line('tax'); ?>" name="tax"
                                                        value="0" id="edittax" style="width: 50%; float: right"
                                                        class="form-control tax" readonly />

                                                </td>
                                            </tr>
                                            <tr>
                                                <th><?php echo $this->lang->line('net_amount') . " (" . $currency_symbol . ")"; ?>
                                                </th>
                                                <td colspan="2" class="text-right ipdbilltable">
                                                    <input type="text" placeholder="Net Amount" value="0" name="amount"
                                                        id="editfinal_amount" style="width: 30%; float: right"
                                                        class="form-control net_amount" readonly />
                                                </td>
                                            </tr>
                                        </table>


                                    </div>

                                </div>
                                <!--./row-->
                            </div>

                        </div>
                    </div>

                </div> <!-- scroll-area -->
                <div class="modal-footer">

                    <button type="submit" data-loading-text="<?php echo $this->lang->line('processing') ?>"
                        name="charge_data" class="btn btn-info pull-right"><i class="fa fa-check-circle"></i>
                        <?php echo $this->lang->line('save') ?></button>

                </div>
            </form>

        </div>
    </div>

</div>
<div class="modal fade" id="add_chargeModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog pup100  " role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close pupclose" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('add_charges'); ?></h4>
            </div>
            <form id="add_charges" accept-charset="utf-8" method="post" class="ptt10">
                <div class="pup-scroll-area">
                    <div class="modal-body pt0">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <input type="hidden" name="opd_id" value="<?php echo $result['id'] ?>">
                                <input type="hidden" name="patient_charge_id" id="patient_charge_id" value="0">
                                <input type="hidden" name="organisation_id" id="organisation_id"
                                    value="<?php echo $visitdata['organisation_id'] ?>">
                                <div class="row">
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label
                                                class="displayblock"><?php echo $this->lang->line('charge_type'); ?><small
                                                    class="req"> *</small></label>
                                            <select name="charge_type" id="add_charge_type"
                                                class="form-control charge_type select2" style="width: 100%">
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                <?php foreach ($charge_type as $key => $value) { ?>
                                                <option value="<?php echo $value->id; ?>">
                                                    <?php echo $value->charge_type; ?>
                                                </option>
                                                <?php } ?>
                                            </select>
                                            <span class="text-danger"><?php echo form_error('charge_type'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('charge_category'); ?></label><small
                                                class="req"> *</small>
                                            <select name="charge_category" id="charge_category" style="width: 100%"
                                                class="form-control select2 charge_category">
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            </select>
                                            <span
                                                class="text-danger"><?php echo form_error('charge_category'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('charge_name'); ?></label><small
                                                class="req"> *</small>
                                            <select name="charge_id" id="charge_id" style="width: 100%"
                                                class="form-control addcharge select2 ">
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            </select>
                                            <span class="text-danger"><?php echo form_error('code'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('standard_charge') . " (" . $currency_symbol . ")" ?></label>
                                            <input type="text" readonly name="standard_charge" id="addstandard_charge"
                                                class="form-control"
                                                value="<?php echo set_value('standard_charge'); ?>">
                                            <span
                                                class="text-danger"><?php echo form_error('standard_charge'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('tpa_charge') . " (" . $currency_symbol . ")" ?></label>
                                            <input type="text" readonly name="schedule_charge" id="addscd_charge"
                                                placeholder="" class="form-control"
                                                value="<?php echo set_value('schedule_charge'); ?>">
                                            <span
                                                class="text-danger"><?php echo form_error('schedule_charge'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('qty'); ?></label><small class="req">
                                                *</small>
                                            <input type="text" name="qty" id="qty" class="form-control" value="1"
                                                onkeyup="this.value=this.value.replace(/[^0-9.]/g,'')" />
                                            <span class="text-danger"><?php echo form_error('qty'); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="divider"></div>
                                <div class="row">
                                    <div class="col-sm-5">
                                        <table class="printablea4">
                                            <tr>
                                                <th width="40%">
                                                    <?php echo $this->lang->line('total') . " (" . $currency_symbol . ")"; ?>
                                                </th>
                                                <td width="60%" colspan="2" class="text-right ipdbilltable">
                                                    <input type="text" placeholder="Total" value="0" name="apply_charge"
                                                        id="apply_charge" style="width: 30%; float: right"
                                                        class="form-control total" readonly />
                                                </td>
                                            </tr>
                                            <tr>
                                                <th><?php echo $this->lang->line('tax') . " (" . $currency_symbol . ")"; ?>
                                                </th>
                                                <td class="text-right ipdbilltable">
                                                    <h4 style="float: right;font-size: 12px; padding-left: 5px;"> %</h4>
                                                    <input type="text"
                                                        placeholder="<?php echo $this->lang->line('tax'); ?>"
                                                        name="charge_tax" id="charge_tax"
                                                        class="form-control charge_tax" readonly
                                                        style="width: 70%; float: right;font-size: 12px;" />
                                                </td>
                                                <td class="text-right ipdbilltable"><input type="text"
                                                        placeholder="<?php echo $this->lang->line('tax'); ?>" name="tax"
                                                        value="0" id="opdcharges_tax" style="width: 50%; float: right"
                                                        class="form-control tax" readonly /></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo $this->lang->line('net_amount') . " (" . $currency_symbol . ")"; ?>
                                                </th>
                                                <td colspan="2" class="text-right ipdbilltable"><input type="text"
                                                        placeholder="Net Amount" value="0" name="amount"
                                                        id="final_amount" style="width: 30%; float: right"
                                                        class="form-control net_amount" readonly /></td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label><?php echo $this->lang->line('charge_note'); ?></label>
                                                    <textarea name="note" id="edit_note" rows="3"
                                                        class="form-control edit_charge_note"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--./col-sm-6-->
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('date'); ?></label> <small class="req">
                                                *</small>
                                            <input id="charge_date" name="date" type="text" class="form-control" />
                                        </div>
                                        <button type="submit"
                                            data-loading-text="<?php echo $this->lang->line('processing') ?>"
                                            name="charge_data" value="add" class="btn btn-info pull-right"><i
                                                class="fa fa-check-circle"></i>
                                            <?php echo $this->lang->line('add') ?></button>
                                    </div>
                                </div>
                                <!--./row-->
                                <hr>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12" class="table-responsive ptt10">
                                <table class="table table-striped table-bordered table-hover">
                                    <tr>
                                        <th><?php echo $this->lang->line('date') ?></th>
                                        <th><?php echo $this->lang->line('charge_type') ?></th>
                                        <th><?php echo $this->lang->line('charge_category') ?></th>
                                        <th><?php echo $this->lang->line('charge_name') ?></th>
                                        <th class="text-right">
                                            <?php echo $this->lang->line('standard_charge') . ' (' . $currency_symbol . ')'; ?>
                                        </th>
                                        <th class="text-right">
                                            <?php echo $this->lang->line('tpa_charge') . ' (' . $currency_symbol . ')'; ?>
                                        </th>
                                        <th class="text-right"><?php echo $this->lang->line('qty') ?></th>
                                        <th class="text-right">
                                            <?php echo $this->lang->line('total') . ' (' . $currency_symbol . ')'; ?>
                                        </th>
                                        <th class="text-right">
                                            <?php echo $this->lang->line('tax') . ' (' . $currency_symbol . ')'; ?>
                                        </th>
                                        <th class="text-right">
                                            <?php echo $this->lang->line('net_amount') . ' (' . $currency_symbol . ')'; ?>
                                        </th>
                                        <th class="text-right"><?php echo $this->lang->line('action') ?></th>
                                    </tr>
                                    <tbody id="preview_charges">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div> <!-- scroll-area -->
                <div class="modal-footer sticky-footer">
                    <div class="pull-right">
                        <button type="submit" data-loading-text="<?php echo $this->lang->line('processing') ?>"
                            value="save" name="charge_data" class="btn btn-info"><i class="fa fa-check-circle"></i>
                            <?php echo $this->lang->line('save') ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- -->
<div class="modal fade" id="add_nurse_note" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close close_button" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('add_nurse_note'); ?> </h4>
            </div>
            <div class="scroll-area">
                <div class="modal-body pt0 pb0">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <form id="nurse_note_form" accept-charset="utf-8" enctype="multipart/form-data"
                                method="post">
                                <input name="patient_id" placeholder="" id="nurse_patient_id"
                                    value="<?php echo $result["id"] ?>" type="hidden" class="form-control" />
                                <!-- <input type="hidden" name="ipdid" value="<?php echo $ipdid ?>" id='ipdid'> -->
                                <div class="scroll-area">
                                    <div class="modal-body pb0 ptt10">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label><?php echo $this->lang->line('date'); ?>
                                                        <small class="req"> *</small>
                                                    </label>
                                                    <input type="text" name="date" value=""
                                                        class="form-control datetime">

                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label><?php echo $this->lang->line('nurse'); ?><small class="req">
                                                            *</small> </label>
                                                    <input type="hidden" name="nurse" id="nurse_set">
                                                    <select name="nurse_field" <?php if ($disable_option == true) {
                                                                                    echo "disabled";
                                                                                } ?> style="width: 100%"
                                                        id="nurse_field" class="form-control select2">
                                                        <!-- Placeholder Option -->
                                                        <option value="" disabled selected>Select Nurse</option>
                                                        <?php foreach ($nurse as $key => $value) { ?>
                                                        <option value="<?php echo $value["id"]; ?>">
                                                            <?php echo composeStaffNameByString($value["name"], $value["surname"], $value["employee_id"]); ?>
                                                        </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label><?php echo $this->lang->line('note') ?> <small class="req">
                                                            *</small> </label>
                                                    <textarea name="note" style="height:50px"
                                                        class="form-control"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label><?php echo $this->lang->line('comment'); ?> <small
                                                            class="req"> *</small> </label>
                                                    <textarea name="comment" style="height:50px"
                                                        class="form-control"></textarea>
                                                </div>
                                            </div>

                                            <div class="">
                                                <?php echo display_custom_fields('ipdnursenote'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" id="nurse_notebtn"
                                        data-loading-text="<?php echo $this->lang->line('processing'); ?>"
                                        class="btn btn-info pull-right"><i class="fa fa-check-circle"></i>
                                        <?php echo $this->lang->line('save'); ?></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Add Diagnosis -->
<div class="modal fade" id="add_operationtheatre" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close close_button" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line("add_operation"); ?></h4>
            </div>
            <div class="scroll-area">
                <div class="modal-body pt0 pb0">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <form id="form_operationtheatre" accept-charset="utf-8" enctype="multipart/form-data"
                                method="post" class="ptt10">
                                <div class="row">
                                    <input type="hidden" value="<?php echo $opdid ?>" name="opdid" class="form-control"
                                        id="opdid" />
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('operation_category'); ?></label><small
                                                class="req"> *</small>
                                            <select name="operation_category" id="operation_category"
                                                class="form-control select2" onchange="getcategory(this.value)"
                                                style="width:100%">
                                                <option value=""><?php echo $this->lang->line('select') ?></option>
                                                <?php foreach ($categorylist as $operation) { ?>
                                                <option value="<?php echo $operation['id']; ?>">
                                                    <?php echo $operation['category']; ?>
                                                </option>
                                                <?php } ?>
                                            </select>
                                            <span
                                                class="text-danger"><?php echo form_error('operation_category'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('operation_name'); ?></label>
                                            <small class="req"> *</small>
                                            <div>
                                                <select name="operation_name" id="operation_name"
                                                    class="form-control select2 " style="width:100%">
                                                </select>
                                            </div>

                                            <span class="text-danger"><?php echo form_error('operation_name'); ?></span>
                                        </div>
                                    </div>


                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('operation_date'); ?></label>
                                            <small class="req"> *</small>
                                            <input type="text" value="<?php //echo set_value('email');     
                                                                        ?>" id="date" name="date"
                                                class="form-control datetime">
                                            <span class="text-danger"><?php echo form_error('date'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>
                                                <?php echo $this->lang->line('consultant_doctor'); ?></label>
                                            <small class="req"> *</small>
                                            <div><select class="form-control select2" <?php
                                                                                        if ($disable_option == true) {
                                                                                            echo "disabled";
                                                                                        }
                                                                                        ?> style="width:100%"
                                                    id='consultant_doctorid' name='consultant_doctor'>
                                                    <option value="<?php echo set_value('consultant_doctor'); ?>">
                                                        <?php echo $this->lang->line('select') ?>
                                                    </option>
                                                    <?php foreach ($doctors as $dkey => $dvalue) {
                                                    ?>
                                                    <option value="<?php echo $dvalue["id"]; ?>" <?php
                                                                                                        if ((isset($doctor_select)) && ($doctor_select == $dvalue["id"])) {
                                                                                                            echo "selected";
                                                                                                        }
                                                                                                        ?>>
                                                        <?php echo composeStaffNameByString($dvalue["name"], $dvalue["surname"], $dvalue["employee_id"]); ?>
                                                    </option>
                                                    <?php } ?>
                                                </select>
                                                <input type="hidden" id="consultant_doctorname"
                                                    name="consultant_doctor">
                                            </div>
                                            <span
                                                class="text-danger"><?php echo form_error('consultant_doctor'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('assistant_consultant') . " " . '1'; ?></label>
                                            <input type="text" name="ass_consultant_1" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('assistant_consultant') . " " . '2'; ?></label>
                                            <input type="text" name="ass_consultant_2" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('anesthetist'); ?></label>
                                            <input type="text" name="anesthetist" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('anesthesia_type'); ?></label>
                                            <input type="text" name="anaethesia_type" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('ot_technician'); ?></label>
                                            <input type="text" name="ot_technician" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('ot_assistant'); ?></label>
                                            <input type="text" value="<?php //echo set_value('email');     
                                                                        ?>" name="ot_assistant" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('remark'); ?></label>
                                            <textarea name="ot_remark" id="ot_remark" class="form-control"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('result'); ?></label>
                                            <textarea name="ot_result" id="ot_result" class="form-control"></textarea>
                                        </div>
                                    </div>
                                    <div class="">
                                        <?php echo display_custom_fields('operationtheatre'); ?>
                                    </div>


                                </div>
                        </div>
                    </div>
                </div>
            </div> <!-- scroll-area -->
            <div class="modal-footer">
                <div class="pull-right">
                    <button type="submit" id="form_operationtheatrebtn"
                        data-loading-text="<?php echo $this->lang->line('processing'); ?>" class="btn btn-info"><i
                            class="fa fa-check-circle"></i> <?php echo $this->lang->line('save'); ?></button>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>


<!-- Edit Operation Theatre -->

<div class="modal fade" id="edit_operationtheatre" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line("edit_operation"); ?></h4>
            </div>
            <div class="scroll-area">
                <div class="modal-body pt0 pb0">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <form id="form_editoperationtheatre" accept-charset="utf-8" enctype="multipart/form-data"
                                method="post" class="ptt10">
                                <div class="row">
                                    <input type="hidden" value="<?php echo $opdid ?>" name="opdid" class="form-control"
                                        id="opdid" />
                                    <input type="hidden" value="" name="otid" class="form-control" id="otid" />


                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('operation_category'); ?></label>
                                            <small class="req"> *</small>
                                            <select name="eoperation_category" id="eoperation_category"
                                                class="form-control select2" onchange="getcategory(this.value)"
                                                style="width:100%">
                                                <option value=""><?php echo $this->lang->line('select') ?></option>
                                                <?php foreach ($categorylist as $operation) { ?>
                                                <option value="<?php echo $operation['id']; ?>">
                                                    <?php echo $operation['category']; ?>
                                                </option>
                                                <?php } ?>
                                            </select>
                                            <span
                                                class="text-danger"><?php echo form_error('operation_category'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('operation_name'); ?></label>
                                            <small class="req"> *</small>

                                            <div>
                                                <select name="eoperation_name" id="eoperation_name"
                                                    class="form-control select2" style="width:100%">
                                                    <option value=""><?php echo $this->lang->line('select') ?></option>
                                                    <?php foreach ($operationlist as $operation) { ?>
                                                    <option value="<?php echo $operation['id']; ?>">
                                                        <?php echo $operation['operation']; ?>
                                                    </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <span class="text-danger"><?php echo form_error('operation_name'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('operation_date'); ?></label>
                                            <small class="req"> *</small>
                                            <input type="text" value="" id="edate" name="date"
                                                class="form-control datetime">
                                            <span class="text-danger"><?php echo form_error('date'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="exampleInputFile">
                                                <?php echo $this->lang->line('consultant_doctor'); ?></label> <small
                                                class="req"> *</small>
                                            <div>
                                                <select class="form-control select2" <?php
                                                                                        if ($disable_option == true) {
                                                                                            echo "disabled";
                                                                                        }
                                                                                        ?> style="width:100%"
                                                    id='econsultant_doctorid' name='consultant_doctor'>
                                                    <option value="<?php echo set_value('consultant_doctor'); ?>">
                                                        <?php echo $this->lang->line('select') ?>
                                                    </option>
                                                    <?php foreach ($doctors as $dkey => $dvalue) {
                                                    ?>
                                                    <option value="<?php echo $dvalue["id"]; ?>" <?php
                                                                                                        if ((isset($doctor_select)) && ($doctor_select == $dvalue["id"])) {
                                                                                                            echo "selected";
                                                                                                        }
                                                                                                        ?>>
                                                        <?php echo composeStaffNameByString($dvalue["name"], $dvalue["surname"], $dvalue["employee_id"]); ?>
                                                    </option>
                                                    <?php } ?>
                                                </select>
                                                <input type="hidden" id="econsultant_doctorname"
                                                    name="consultant_doctor">
                                            </div>
                                            <span
                                                class="text-danger"><?php echo form_error('consultant_doctor'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('assistant_consultant') . " " . '1'; ?></label>
                                            <input type="text" name="ass_consultant_1" id="eass_consultant_1"
                                                class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('assistant_consultant') . " " . '2'; ?></label>
                                            <input type="text" name="ass_consultant_2" id="eass_consultant_2"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('anesthetist'); ?></label>
                                            <input type="text" name="anesthetist" id="eanesthetist"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('anesthesia_type'); ?></label>
                                            <input type="text" name="anaethesia_type" id="eanaethesia_type"
                                                class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('ot_technician'); ?></label>
                                            <input type="text" name="ot_technician" id="eot_technician"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('ot_assistant'); ?></label>
                                            <input type="text" value="" name="ot_assistant" id="eot_assistant"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('remark'); ?></label>
                                            <textarea name="eot_remark" id="eot_remark" class="form-control"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('result'); ?></label>
                                            <textarea name="eot_result" id="eot_result" class="form-control"></textarea>
                                        </div>
                                    </div>
                                    <div id="custom_fields_ot">

                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div> <!-- scroll-area -->
            <div class="modal-footer">
                <div class="pull-right">
                    <button type="submit" id="form_editoperationtheatrebtn"
                        data-loading-text="<?php echo $this->lang->line('processing'); ?>" class="btn btn-info"><i
                            class="fa fa-check-circle"></i> <?php echo $this->lang->line('save'); ?></button>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $('#form_editoperationtheatre').on('submit', function(e) {
        e.preventDefault();

        var formData = {
            "opd_details_id": $('#opdid').val() || '',
            "operation_id": $('#eoperation_name').val() || '',
            "date": convertToDateTime($('#edate').val()) || '',
            "consultant_doctor": $('#econsultant_doctorid').val() || '',
            "ass_consultant_1": $('#eass_consultant_1').val() || '',
            "ass_consultant_2": $('#eass_consultant_2').val() || '',
            "anesthetist": $('#eanesthetist').val() || '',
            "anaethesia_type": $('#eanaethesia_type').val() || '',
            "ot_technician": $('#eot_technician').val() || '',
            "ot_assistant": $('#eot_assistant').val() || '',
            "result": $('#eot_result').val() || '',
            "remark": $('#eot_remark').val() || '',
            "Hospital_id": <?= $data['hospital_id'] ?> || ''
        };


        var emptyFields = [];
        var namePattern = /^[a-zA-Z0-9]+[a-zA-Z0-9 ]*$/;

        if (!formData["operation_id"]) emptyFields.push('Operation Name');
        if (!formData["date"]) emptyFields.push('Date');
        if (!formData["consultant_doctor"]) emptyFields.push('Consultant Doctor');
        // if (!formData["anesthetist"]) emptyFields.push('Anesthetist');
        // if (!formData["ot_technician"]) emptyFields.push('OT Technician');
        // if (!formData["ot_assistant"]) emptyFields.push('OT Assistant');
        // if (!formData["result"]) emptyFields.push('Result');

        if (emptyFields.length > 0) {
            errorMsg("Please fill the following fields: " + emptyFields.join(', '));
            return;
        }

        if (!namePattern.test(formData["operation_id"])) {
            emptyFields.push('Operation Name invalid');
        }

        if (emptyFields.length > 0) {
            errorMsg("Please fill the following fields: " + emptyFields.join(', '));
            return;
        }

        let updateid = $('#otid').val();

        $.ajax({
            url: '<?= $api_base_url ?>opd-operation/' + updateid,
            type: 'PATCH',
            data: JSON.stringify(formData),
            contentType: 'application/json',
            success: function(response) {
                let message = response[0]?. ['data ']?.messege || 'Default success message';
                successMsg(message);
                location.reload();
            },
            error: function(error) {
                alert('An error occurred');
            }
        });
    });

    function convertToDateTime(inputDate) {
        var date = new Date(inputDate);
        var year = date.getFullYear();
        var month = ('0' + (date.getMonth() + 1)).slice(-2);
        var day = ('0' + date.getDate()).slice(-2);
        var hours = ('0' + date.getHours()).slice(-2);
        var minutes = ('0' + date.getMinutes()).slice(-2);
        var seconds = ('0' + date.getSeconds()).slice(-2);

        return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
    }
});
</script>


<div class="modal fade" id="myaddMedicationModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close close_modal" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line("add_medication_dose"); ?></h4>
            </div>
            <form id="add_medication" accept-charset="utf-8" method="post" class="ptt10">
                <div class="modal-body pt0 pb0">
                    <div class="row">
                        <div class="col-md-6 col-lg-6 col-sm-6">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('date'); ?></label><small class="req"> *</small>
                                <input type="text" name="date" id="dateInput" id="date" class="form-control date">
                                <span class="text-danger"><?php echo form_error('date'); ?></span>
                                <input type="hidden" name="opdid" value="<?php echo $opdid ?>">
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-6">
                            <div class="form-group">
                                <label for="pwd"><?php echo $this->lang->line("time"); ?></label><small class="req">
                                    *</small>
                                <div class="bootstrap-timepicker">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="text" name="time" class="form-control timepicker" id="mtime"
                                                value="<?php echo set_value('time'); ?>">
                                            <div class="input-group-addon">
                                                <i class="fa fa-clock-o"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <span class="text-danger"><?php echo form_error('time'); ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-lg-6 col-sm-6">
                            <div class="form-group">
                                <label><?php echo $this->lang->line("medicine_category"); ?></label><small class="req">
                                    *</small>
                                <select class="form-control medicine_category_medication select2" style="width:100%"
                                    id="mmedicine_category_id" name='medicine_category_id'>
                                    <option value="<?php echo set_value('medicine_category_id'); ?>">
                                        <?php echo $this->lang->line('select') ?>
                                    </option>
                                    <?php foreach ($medicineCategory as $dkey => $dvalue) {
                                    ?>
                                    <option value="<?php echo $dvalue["id"]; ?>">
                                        <?php echo $dvalue["medicine_category"] ?>
                                    </option>
                                    <?php } ?>
                                </select>
                                <span class="text-danger"><?php echo form_error('medicine_category_id'); ?></span>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-6">
                            <div class="form-group">
                                <label><?php echo $this->lang->line("medicine_name"); ?></label><small class="req">
                                    *</small>
                                <select class="form-control select2 medicine_name_medication" style="width:100%"
                                    id="mmedicine_id" name='medicine_name_id'>
                                    <option value=""><?php echo $this->lang->line('select') ?>
                                    </option>
                                </select>
                                <span class="text-danger"><?php echo form_error('medicine_name_id'); ?></span>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-6">
                            <div class="form-group">
                                <label><?php echo $this->lang->line("dosage"); ?></label><small class="req"> *</small>
                                <select class="form-control select2 dosage_medication" style="width:100%" id="dosage"
                                    onchange="get_dosagename(this.value)" name='dosage'>
                                    <option value=""><?php echo $this->lang->line('select') ?>
                                    </option>
                                </select>
                                <span class="text-danger"><?php echo form_error('dosage'); ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><?php echo $this->lang->line("remarks"); ?></label>
                                <textarea name="remark" id="remark" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" id="add_medicationbtn"
                        data-loading-text="<?php echo $this->lang->line('processing'); ?>"
                        class="btn btn-info pull-right"><i class="fa fa-check-circle"></i>
                        <?php echo $this->lang->line('save'); ?></button>
                    <button type="button" id="vioce" class="btn btn-info" onclick="toggleRecording()">
                        <i class="fa fa-check-circle"></i>
                        Vioce
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- -->
<!-- -->
<div class="modal fade" id="myMedicationModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line("add_medication_dose"); ?></h4>
            </div>
            <form id="add_medicationdose" accept-charset="utf-8" method="post" class="ptt10">
                <div class="modal-body pt0 pb0">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('date'); ?></label><small class="req"> *</small>
                                <input type="text" name="date" id="add_dose_date" class="form-control date">
                                <span class="text-danger"><?php echo form_error('date'); ?></span>
                                <input type="hidden" name="opdid" value="<?php echo $opdid ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="pwd"><?php echo $this->lang->line("time"); ?></label><small class="req">
                                    *</small>
                                <div class="bootstrap-timepicker">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="text" name="time" class="form-control timepicker"
                                                id="add_dose_time" value="<?php echo set_value('time'); ?>">
                                            <div class="input-group-addon">
                                                <i class="fa fa-clock-o"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <span class="text-danger"><?php echo form_error('time'); ?></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo $this->lang->line("medicine_category"); ?></label><small class="req">
                                    *</small>
                                <select class="form-control medicine_category_medication select2" style="width:100%"
                                    id="add_dose_medicine_category" name='medicine_category_id'>
                                    <option value=""><?php echo $this->lang->line('select') ?>
                                    </option>
                                    <?php foreach ($medicineCategory as $dkey => $dvalue) {
                                    ?>
                                    <option value="<?php echo $dvalue["id"]; ?>">
                                        <?php echo $dvalue["medicine_category"] ?>
                                    </option>
                                    <?php } ?>
                                </select>
                                <span class="text-danger"><?php echo form_error('medicine_category_id'); ?></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo $this->lang->line("medicine_name"); ?></label><small class="req">
                                    *</small>
                                <select class="form-control select2 medicine_name_medication" style="width:100%"
                                    id="add_dose_medicine_id" name='medicine_name_id'>
                                    <option value=""><?php echo $this->lang->line('select') ?>
                                    </option>
                                </select>
                                <span class="text-danger"><?php echo form_error('medicine_name_id'); ?></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo $this->lang->line("dosage"); ?></label> <small class="req"> *</small>
                                <select class="form-control select2 dosage_medication" style="width:100%" id="mdosage"
                                    onchange="" name='dosage'>
                                    <option value=""><?php echo $this->lang->line('select'); ?>
                                    </option>
                                </select>
                                <span class="text-danger"><?php echo form_error('dosage'); ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><?php echo $this->lang->line("remarks"); ?></label>
                                <textarea name="remark" id="remark" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="add_medicationdosebtn"
                        data-loading-text="<?php echo $this->lang->line('processing'); ?>"
                        class="btn btn-info pull-right"><i class="fa fa-check-circle"></i>
                        <?php echo $this->lang->line('save'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- -->
<!-- -->
<div class="modal fade" id="myMedicationDoseModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="modalicon">
                    <?php if ($this->rbac->hasPrivilege('opd_medication', 'can_delete')) { ?>
                    <div id='edit_delete_medication'></div>
                    <?php } ?>
                </div>
                <h4 class="modal-title"><?php echo $this->lang->line("edit_medication_dose"); ?></h4>
            </div>
            <form id="update_medication" accept-charset="utf-8" method="post" class="ptt10">
                <div class="modal-body pt0 pb0">
                    <input type="hidden" name="medication_id" id="medication_id" value="">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('date'); ?></label><small class="req"> *</small>
                                <input type="text" name="date" id="date_edit_medication" class="form-control date">
                                <span class="text-danger"><?php echo form_error('date'); ?></span>
                                <input type="hidden" name="opdid" value="<?php echo $opdid ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="pwd"><?php echo $this->lang->line("time"); ?></label><small class="req">
                                    *</small>
                                <div class="bootstrap-timepicker">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="text" name="time" class="form-control timepicker"
                                                id="dosagetime" value="<?php echo set_value('time'); ?>">
                                            <div class="input-group-addon">
                                                <i class="fa fa-clock-o"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <span class="text-danger"><?php echo form_error('time'); ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo $this->lang->line("medicine_category"); ?></label><small class="req">
                                    *</small>
                                <select class="form-control medicine_category_medication select2" style="width:100%"
                                    id="mmedicine_category_edit_id" name='medicine_category_id'>
                                    <option value="<?php echo set_value('medicine_category_id'); ?>">
                                        <?php echo $this->lang->line('select') ?>
                                    </option>
                                    <?php foreach ($medicineCategory as $dkey => $dvalue) {
                                    ?>
                                    <option value="<?php echo $dvalue["id"]; ?>">
                                        <?php echo $dvalue["medicine_category"] ?>
                                    </option>
                                    <?php } ?>
                                </select>
                                <span class="text-danger"><?php echo form_error('medicine_category_id'); ?></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo $this->lang->line("medicine_name"); ?></label><small class="req">
                                    *</small>
                                <select class="form-control select2 medicine_name_medication" style="width:100%"
                                    id="mmedicine_edit_id" name='medicine_name_id'>
                                    <option value=""><?php echo $this->lang->line('select') ?>
                                    </option>
                                </select>
                                <span class="text-danger"><?php echo form_error('medicine_name_id'); ?></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo $this->lang->line("dosage"); ?></label><small class="req"> *</small>
                                <select class="form-control  select2" style="width:100%" id="medicine_dose_edit_id"
                                    name='dosage_id'>
                                    <option value="<?php echo set_value('dosage_id'); ?>">
                                        <?php echo $this->lang->line('select'); ?>
                                    </option>
                                    <?php foreach ($dosage as $key => $value) { ?>
                                    <option value="<?php echo $value["id"]; ?>">
                                        <?php echo $value["dosage"] . " " . $value['unit']; ?>
                                    </option>

                                    <?php } ?>
                                </select>
                                <span class="text-danger"><?php echo form_error('dosage_id'); ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><?php echo $this->lang->line("remarks"); ?></label>
                                <textarea name="remark" id="medicine_dosage_remark" class="form-control"></textarea>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" id="update_medicationbtn"
                        data-loading-text="<?php echo $this->lang->line('processing'); ?>"
                        class="btn btn-info pull-right"><i class="fa fa-check-circle"></i>
                        <?php echo $this->lang->line('save'); ?></button>
                </div>

            </form>
        </div>
    </div>
</div>
<!-- -->


<!--lab investigation modal-->
<div class="modal fade" id="viewDetailReportModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-toggle="tooltip"
                    title="<?php echo $this->lang->line('clase'); ?>" data-dismiss="modal">&times;</button>
                <div class="modalicon">
                    <div id='action_detail_report_modal'>

                    </div>
                </div>
                <h4 class="modal-title" id="modal_head"></h4>
            </div>
            <div class="modal-body ptt10 pb0">
                <div id="reportbilldata"></div>
            </div>
        </div>
    </div>
</div>
<!-- end lab investigation modal-->


<!-- Timeline -->
<div class="modal fade" id="myTimelineModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('add_timeline'); ?></h4>
            </div>
            <div class="scroll-area">
                <div class="modal-body pt0 pb0">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <form id="add_timeline" accept-charset="utf-8" enctype="multipart/form-data" method="post"
                                class="ptt10">
                                <div class="row">
                                    <div class=" col-md-12">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('title'); ?></label><small class="req">
                                                *</small>
                                            <input type="hidden" name="patient_id" id="patient_id"
                                                value="<?php echo $result['patient_id'] ?>">
                                            <input id="timeline_title" name="timeline_title" placeholder="" type="text"
                                                class="form-control"
                                                onkeyup="this.value=this.value.replace(/[^a-zA-Z]/g,'')" />
                                            <span class="text-danger"><?php echo form_error('timeline_title'); ?></span>
                                        </div>
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('date'); ?></label><small class="req">
                                                *</small>
                                            <input id="timeline_date" name="timeline_date" value="" placeholder=""
                                                type="text" class="form-control" />
                                            <span class="text-danger"><?php echo form_error('timeline_date'); ?></span>
                                        </div>
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('description'); ?></label>
                                            <textarea id="timeline_desc" name="timeline_desc" placeholder=""
                                                class="form-control"></textarea>
                                            <span class="text-danger"><?php echo form_error('description'); ?></span>
                                        </div>

                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('attach_document'); ?></label>
                                            <div><input id="timeline_doc_id" name="timeline_doc" placeholder=""
                                                    type="file" class="filestyle form-control" data-height="40"
                                                    value="<?php echo set_value('timeline_doc'); ?>" />
                                                <span
                                                    class="text-danger"><?php echo form_error('timeline_doc'); ?></span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label
                                                class="vertical-align-middle"><?php echo $this->lang->line('visible_to_this_person'); ?></label>
                                            <input id="visible_check" checked="checked" name="visible_check" value="yes"
                                                placeholder="" type="checkbox" />

                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="pull-right">
                    <button type="submit" data-loading-text="<?php echo $this->lang->line('processing') ?>"
                        id="add_timelinebtn" class="btn btn-info"><i class="fa fa-check-circle"></i>
                        <?php echo $this->lang->line('save'); ?></button>

                </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- -->

<!-- Edit Timeline -->
<div class="modal fade" id="myTimelineEditModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('edit_timeline'); ?></h4>
            </div>
            <div class="scroll-area">
                <div class="modal-body pt0 pb0">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <form id="edit_timeline" accept-charset="utf-8" enctype="multipart/form-data" method="post"
                                class="ptt10">
                                <div class="row">


                                    <div class=" col-md-12">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('title'); ?></label><small class="req">
                                                *</small>
                                            <input type="hidden" name="patient_id" id="epatientid" value="">
                                            <input type="hidden" name="timeline_id" id="etimelineid" value="">
                                            <input type="hidden" name="old_document" id="old_document" value="">
                                            <input id="etimelinetitle" name="timeline_title" placeholder="" type="text"
                                                class="form-control"
                                                onkeyup="this.value=this.value.replace(/[^a-zA-Z]/g,'')" />
                                            <span class="text-danger"><?php echo form_error('timeline_title'); ?></span>
                                        </div>
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('date'); ?></label><small class="req">
                                                *</small>
                                            <!-- <input id="etimelinedate" name="timeline_date" value="<?php echo set_value('timeline_date', date($this->customlib->getHospitalDateFormat())); ?>" placeholder="" type="text" class="form-control date"  />-->
                                            <input type="text" name="timeline_date" class="form-control"
                                                id="etimelinedate" />
                                            <span class="text-danger"><?php echo form_error('timeline_date'); ?></span>
                                        </div>
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('description'); ?></label>
                                            <textarea id="timelineedesc" name="timeline_desc" placeholder=""
                                                class="form-control"></textarea>
                                            <span class="text-danger"><?php echo form_error('description'); ?></span>
                                        </div>

                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('attach_document'); ?></label>
                                            <div><input id="etimeline_doc_id" name="timeline_doc" placeholder=""
                                                    type="file" class="filestyle form-control" data-height="40"
                                                    value="<?php echo set_value('timeline_doc'); ?>" />
                                                <span
                                                    class="text-danger"><?php echo form_error('timeline_doc'); ?></span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label
                                                class="vertical-align-middle"><?php echo $this->lang->line('visible_to_this_person'); ?></label>
                                            <input id="evisible_check" name="visible_check" value="yes" placeholder=""
                                                type="checkbox" />

                                        </div>
                                    </div>


                                </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="pull-right">
                    <button type="submit" data-loading-text="<?php echo $this->lang->line('processing') ?>"
                        id="edit_timelinebtn" class="btn btn-info pull-right"><i class="fa fa-check-circle"></i>
                        <?php echo $this->lang->line('save'); ?></button>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="edit_prescription" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
                    <?php echo $this->lang->line('edit') . " " . $this->lang->line('prescription'); ?>
                </h4>
            </div>

            <div class="modal-body pt0 pb0" id="editdetails_prescription">
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="add_prescription" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog pup100" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close pupclose" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <form id="form_prescription" accept-charset="utf-8" enctype="multipart/form-data" method="post">
                <div class="pup-scroll-area">
                    <div class="modal-body pt0 pb0"></div>
                </div>
                <div class="modal-footer sticky-footer">
                    <div class="pull-right">
                        <button type="submit" name="save_print" value="save_print"
                            data-loading-text="<?php echo $this->lang->line('processing'); ?>"
                            class="btn btn-info btn-sm">
                            <i class="fa fa-print"></i> <?php echo $this->lang->line('save_print'); ?>
                        </button>
                        <button type="submit" name="save" value="save" class="btn btn-primary btn-sm"
                            id="form_prescriptionbtn" data-loading-text="<?php echo $this->lang->line('processing') ?>">
                            <i class="fa fa-check-circle"></i> <?php echo $this->lang->line('save'); ?>
                        </button>
                    </div>
                </div>
            </form>
            <script>
            document.addEventListener("DOMContentLoaded", function() {
                document.getElementById("form_prescription").addEventListener("keydown", function(event) {
                    if (event.key === "Enter" && event.target.tagName !== "TEXTAREA") {
                        event.preventDefault();
                    }
                });
            });
            </script>

        </div>
    </div>
</div>


<div class="modal fade" id="viewModal" role="dialog">
    <div class="modal-dialog modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <button type="button" data-toggle="tooltip" data-original-title="Close" class="close"
                    data-dismiss="modal">&times;</button>
                <div class="modalicon">
                    <div id='edit_delete'>
                        <?php if ($this->rbac->hasPrivilege('revisit', 'can_edit')) { ?>
                        <a href="javascript:void(0)" data-toggle="tooltip"
                            title="<?php echo $this->lang->line('edit'); ?>"><i class="fa fa-pencil"></i></a>
                        <?php
                        }

                        if ($this->rbac->hasPrivilege('revisit', 'can_delete')) {
                        ?>
                        <a href="#" data-toggle="tooltip"
                            data-original-title="<?php echo $this->lang->line('delete'); ?>"><i
                                class="fa fa-trash"></i></a>
                        <?php } ?>
                    </div>
                </div>
                <h4 class="modal-title"> <?php echo $this->lang->line('visit_details'); ?></h4>
            </div>

            <div class="modal-body pt0 pb0">

            </div>

        </div>
    </div>
</div>



<!-- -->
<div class="modal fade" id="prescriptionview" tabindex="-1" role="dialog" aria-labelledby="follow_up">
    <div class="modal-dialog modal-mid modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="modalicon">
                    <div id='edit_deleteprescription'>

                    </div>
                </div>
                <h4 class="modal-title"><?php echo $this->lang->line('prescription'); ?></h4>
            </div>
            <div class="modal-body pt0 pb0" id="getdetails_prescription">

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="prescriptionviewmanual" tabindex="-1" role="dialog" aria-labelledby="follow_up">
    <div class="modal-dialog modal-mid modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="modalicon">
                    <div id='edit_deleteprescriptionmanual'>

                    </div>
                </div>
                <h4 class="modal-title"><?php echo $this->lang->line('prescription'); ?></h4>
            </div>
            <div class="modal-body pt0 pb0" id="getdetails_prescriptionmanual">

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModaledit" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('patient_details'); ?></h4>
            </div>
            <!--./modal-header-->
            <form id="formeditpa" accept-charset="utf-8" action="" enctype="multipart/form-data" method="post">
                <div class="modal-body pt0 pb0">
                    <input id="eupdateid" name="updateid" placeholder="" type="hidden" class="form-control" value="" />
                    <div class="row row-eq">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="row ptt10">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('name'); ?></label><small class="req">
                                            *</small>
                                        <input id="ename" name="name" placeholder="" type="text" class="form-control"
                                            value="<?php echo set_value('name'); ?>" />
                                        <span class="text-danger"><?php echo form_error('name'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('guardian_name') ?></label>
                                        <input type="text" name="guardian_name" id="eguardian_name" placeholder=""
                                            value="" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label> <?php echo $this->lang->line('gender'); ?></label>
                                                <select class="form-control" name="gender" id="egenders">
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php
                                                    foreach ($genderList as $key => $value) {
                                                    ?>
                                                    <option value="<?php echo $key; ?>" <?php if (set_value('gender') == $key)
                                                                                                echo "selected"; ?>>
                                                        <?php echo $value; ?>
                                                    </option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label
                                                    for="dob"><?php echo $this->lang->line('date_of_birth'); ?></label>
                                                <input type="text" name="dob" id="birth_date" placeholder=""
                                                    class="form-control date patient_dob" /><?php echo set_value('dob'); ?>
                                            </div>
                                        </div>

                                        <div class="col-sm-5" id="calculate">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('age') . ' (' . $this->lang->line('yy_mm_dd') . ')'; ?>
                                                </label><small class="req"> *</small>
                                                <div style="clear: both;overflow: hidden;">
                                                    <input type="text"
                                                        placeholder="<?php echo $this->lang->line('year'); ?>"
                                                        name="age[year]" id="age_year" value=""
                                                        class="form-control patient_age_year"
                                                        style="width: 30%; float: left;">

                                                    <input type="text" id="age_month"
                                                        placeholder="<?php echo $this->lang->line('month'); ?>"
                                                        name="age[month]" value=""
                                                        class="form-control patient_age_month"
                                                        style="width: 36%;float: left; margin-left: 4px;">
                                                    <input type="text" id="age_day"
                                                        placeholder="<?php echo $this->lang->line('day'); ?>"
                                                        name="age[day]" value="" class="form-control patient_age_day"
                                                        style="width: 26%;float: left; margin-left: 4px;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--./col-md-6-->
                                <div class="col-md-6 col-sm-12">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label> <?php echo $this->lang->line('blood_group'); ?></label>
                                                <select class="form-control" id="blood_groups" name="blood_group">
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php
                                                    foreach ($bloodgroup as $key => $value) {
                                                    ?>
                                                    <option value="<?php echo $key; ?>" <?php if (set_value('blood_group') == $key) {
                                                                                                echo "selected";
                                                                                            }
                                                                                            ?>>
                                                        <?php echo $value; ?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                                <span
                                                    class="text-danger"><?php echo form_error('blood_group'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label
                                                    for="pwd"><?php echo $this->lang->line('marital_status'); ?></label>
                                                <select name="marital_status" id="marital_statuss" class="form-control">
                                                    <option value=""><?php echo $this->lang->line('select') ?></option>
                                                    <?php foreach ($marital_status as $key => $value) {
                                                    ?>
                                                    <option value="<?php echo $value; ?>" <?php if (set_value('marital_status') == $key)
                                                                echo "selected"; ?>>
                                                        <?php echo $value; ?>
                                                    </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="exampleInputFile">
                                                    <?php echo $this->lang->line('patient') . " " . $this->lang->line('photo'); ?>
                                                </label>
                                                <div>
                                                    <input class="filestyle form-control-file" type='file' name='file'
                                                        id="exampleInputFile" size='20' data-height="26"
                                                        data-default-file="<?php echo base_url() ?>uploads/patient_images/no_image.png">
                                                </div>
                                                <span class="text-danger"><?php echo form_error('file'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--./col-md-6-->
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="pwd"><?php echo $this->lang->line('phone'); ?></label>
                                        <input id="emobileno" autocomplete="off" name="contact" type="text"
                                            placeholder="" class="form-control"
                                            value="<?php echo set_value('mobileno'); ?>" />
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('email'); ?></label>
                                        <input type="text" placeholder="" id="eemail"
                                            value="<?php echo set_value('email'); ?>" name="email" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="address"><?php echo $this->lang->line('address'); ?></label>
                                        <input name="address" id="eaddress" placeholder=""
                                            class="form-control" /><?php echo set_value('address'); ?>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="pwd"><?php echo $this->lang->line('remarks'); ?></label>
                                        <textarea name="note" id="enote"
                                            class="form-control"><?php echo set_value('note'); ?></textarea>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label
                                            for="email"><?php echo $this->lang->line('any_known_allergies'); ?></label>
                                        <textarea name="known_allergies" id="eknown_allergies" placeholder=""
                                            class="form-control"><?php echo set_value('address'); ?></textarea>
                                    </div>
                                </div>
                                <div class="" id="customfieldpatient">

                                </div>
                            </div>
                            <!--./row-->
                        </div>
                        <!--./col-md-8-->
                    </div>
                    <!--./row-->
                </div>

                <div class="modal-footer">
                    <div class="pull-right">
                        <button type="submit" id="formeditpabtn"
                            data-loading-text="<?php echo $this->lang->line('processing') ?>"
                            class="btn btn-info"><?php echo $this->lang->line('save'); ?></button>
                    </div>
                </div>
            </form>


        </div>
    </div>
</div>
<!-- discharged summary   -->
<div class="modal fade" id="myModaldischarged" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="modalicon">
                    <div id='summary_print'>
                    </div>
                </div>
                <h4 class="modal-title">
                    <?php echo $this->lang->line('discharged') . " " . $this->lang->line('summary') ?>
                </h4>
                <div class="row">
                </div>
                <!--./row-->
            </div>
            <form id="formdishrecord" accept-charset="utf-8" enctype="multipart/form-data" method="post">
                <div class="modal-body pt0 pb0">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 ">
                            <div class="row row-eq">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="ptt10">
                                        <div id="evajax_load"></div>
                                        <div class="row" id="">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <ul class="multilinelist">
                                                    <li> <label
                                                            for="pwd"><?php echo $this->lang->line('name'); ?></label>
                                                        <span id="disevlistname"></span>
                                                    </li>
                                                    <li>
                                                        <label for="pwd"><?php echo $this->lang->line('age'); ?></label>
                                                        <span id="disevage"></span>
                                                    </li>
                                                    <li>
                                                        <label
                                                            for="pwd"><?php echo $this->lang->line('gender'); ?></label>
                                                        <span id="disevgenders"></span>
                                                    </li>
                                                </ul>
                                                <ul class="multilinelist">
                                                    <li>
                                                        <label><?php echo $this->lang->line('admission') . " " . $this->lang->line('date') ?></label>
                                                        <span id="disedit_admission_date"></span>
                                                    </li>
                                                    <li>
                                                        <label><?php echo $this->lang->line('discharged') . " " . $this->lang->line('date') ?></label>
                                                        <span id="disedit_discharge_date"></span>
                                                    </li>
                                                </ul>
                                                <ul class="singlelist">
                                                    <li>
                                                        <label><?php echo $this->lang->line('address') ?></label>
                                                        <span id="disevaddress"></span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label
                                                        for="pwd"><?php echo $this->lang->line('diagnosis'); ?></label>
                                                    <input name="diagnosis" id='disdiagnosis' rows="3"
                                                        class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label
                                                        for="pwd"><?php echo $this->lang->line('operation'); ?></label>
                                                    <input name="operation" id='disoperation' class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="pwd"><?php echo $this->lang->line('note'); ?></label>
                                                    <textarea name="note" id='disevnoteipd' rows="3"
                                                        class="form-control"><?php echo set_value('note'); ?></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="dividerhr"></div>
                                            </div>
                                            <!--./col-md-12-->
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label
                                                        for="pwd"><?php echo $this->lang->line('investigations'); ?></label>
                                                    <textarea name="investigations" id='disinvestigations' rows="3"
                                                        class="form-control"><?php echo set_value('note'); ?></textarea>
                                                </div>
                                            </div>

                                            <div class="col-sm-8">
                                                <div class="form-group">
                                                    <label
                                                        for="pwd"><?php echo $this->lang->line('treatment_at_home'); ?></label>
                                                    <textarea name="treatment_at_home" id='distreatment_at_home'
                                                        rows="3"
                                                        class="form-control"><?php echo set_value('note'); ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <input name="patient_id" id="disevpatients_id" type="hidden">
                                        <input type="hidden" id="disupdateid" name="updateid">
                                        <input type="hidden" id="disopdid" name="opdid">
                                    </div>
                                </div>
                            </div>
                            <!--./row-->
                        </div>
                        <!--./col-md-12-->
                    </div>
                    <!--./row-->
                </div>
                <div class="modal-footer">
                    <div class="pull-right">
                        <button type="submit" id="formdishrecordbtn"
                            data-loading-text="<?php echo $this->lang->line('processing') ?>"
                            class="btn btn-info pull-right"> <?php echo $this->lang->line('save'); ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="patient_discharge" tabindex="-1" role="dialog" aria-labelledby="follow_up">
    <div class="modal-dialog modal-mid modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="modalicon">
                    <div id='allpayments_print'>
                    </div>
                    <div id='deathdoc_download'>
                    </div>
                </div>
                <h4 class="modal-title"><?php echo $this->lang->line('patient_discharge'); ?></h4>
            </div>
            <div class="modal-body pb0" id="patient_discharge_result">

            </div>
        </div>
    </div>
</div>
<!-- discharged summary   -->
<div class="modal fade" id="revisitModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog pup100 " role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close pupclose" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('patient_details'); ?></h4>
            </div>
            <form id="formrevisit" accept-charset="utf-8" enctype="multipart/form-data" method="post">
                <div class="pup-scroll-area">
                    <div class="modal-body pt0 pb0">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">

                                <input type="hidden" name="id" id="pid">
                                <input type="hidden" name="password" id="revisit_password">
                                <input type="hidden" name="opd_id" class="form-control"
                                    value="<?php echo $result['id']; ?>">
                                <input type="hidden" name="case_reference_id" class="form-control"
                                    value="<?php echo $result['case_reference_id']; ?>">
                                <input type="hidden" name="email" id="revisit_email">
                                <input type="hidden" name="contact" id="revisit_contact">
                                <input id="revisit_name" name="name" placeholder="" type="hidden" class="form-control"
                                    value="" />
                                <div class="row row-eq">
                                    <div class="col-lg-8 col-md-8 col-sm-8 ptt10">
                                        <div class="row">
                                            <div class="col-lg-9 col-md-9 col-sm-9">
                                                <ul class="singlelist">
                                                    <li class="singlelist24bold">
                                                        <span id="patientname"></span>
                                                    </li>
                                                    <li>
                                                        <i class="fas fa-user-secret" data-toggle="tooltip"
                                                            data-placement="top"
                                                            title="<?php echo $this->lang->line('guardian'); ?>"></i>
                                                        <span id="guardian"></span>
                                                    </li>
                                                </ul>
                                                <ul class="multilinelist">
                                                    <li>
                                                        <i class="fas fa-venus-mars" data-toggle="tooltip"
                                                            data-placement="top"
                                                            title="<?php echo $this->lang->line('gender'); ?>"></i>
                                                        <span id="rgender"></span>
                                                    </li>
                                                    <li>
                                                        <i class="fas fa-tint" data-toggle="tooltip"
                                                            data-placement="top"
                                                            title="<?php echo $this->lang->line('blood_group'); ?>"></i>
                                                        <span id="rblood_group"></span>
                                                    </li>
                                                    <li>
                                                        <i class="fas fa-ring" data-toggle="tooltip"
                                                            data-placement="top"
                                                            title="<?php echo $this->lang->line('marital_status'); ?>"></i>
                                                        <span id="rmarital_status"></span>
                                                    </li>
                                                </ul>
                                                <ul class="singlelist">
                                                    <li>
                                                        <i class="fas fa-hourglass-half" data-toggle="tooltip"
                                                            data-placement="top"
                                                            title="<?php echo $this->lang->line('age'); ?>"></i>
                                                        <span id="rage"></span>
                                                    </li>

                                                    <li>
                                                        <i class="fa fa-phone-square" data-toggle="tooltip"
                                                            data-placement="top"
                                                            title="<?php echo $this->lang->line('phone'); ?>"></i>
                                                        <span id="listnumber"></span>
                                                    </li>
                                                    <li>
                                                        <i class="fa fa-envelope" data-toggle="tooltip"
                                                            data-placement="top"
                                                            title="<?php echo $this->lang->line('email'); ?>"></i>
                                                        <span id="remail"></span>
                                                    </li>
                                                    <li>
                                                        <i class="fas fa-street-view" data-toggle="tooltip"
                                                            data-placement="top"
                                                            title="<?php echo $this->lang->line('address'); ?>"></i>
                                                        <span id="raddress"></span>
                                                    </li>
                                                    <li>
                                                        <b><?php echo $this->lang->line('any_known_allergies') ?> </b>
                                                        <span id="rallergies"></span>
                                                    </li>
                                                    <li>
                                                        <b><?php echo $this->lang->line('remarks') ?> </b>
                                                        <span id="rnote"></span>
                                                    </li>
                                                    <li>
                                                        <b><?php echo $this->lang->line('tpa_id') ?> </b>
                                                        <span id="rtpa_id"></span>
                                                    </li>
                                                    <li>
                                                        <b><?php echo $this->lang->line('tpa_validity') ?> </b>
                                                        <span id="rtpa_validity"></span>
                                                    </li>
                                                    <li>
                                                        <b><?php echo $this->lang->line('national_identification_number') ?>
                                                        </b>
                                                        <span id="ridentification_number"></span>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3">
                                                <img id="patient_image" class="profile-user-img img-responsive"
                                                    alt="User profile picture" src="">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-2 col-xs-4">
                                                <div class="form-group">
                                                    <label for="pwd"><?php echo $this->lang->line('height'); ?></label>
                                                    <input name="height" id="revisit_height" type="text"
                                                        class="form-control" value="<?php echo set_value('height'); ?>"
                                                        onkeyup="this.value=this.value.replace(/[^0-9.]/g,'')" />
                                                </div>
                                            </div>
                                            <div class="col-sm-2 col-xs-4">
                                                <div class="form-group">
                                                    <label for="pwd"><?php echo $this->lang->line('weight'); ?></label>
                                                    <input name="weight" id="revisit_weight" type="text"
                                                        class="form-control" value="<?php echo set_value('weight'); ?>"
                                                        onkeyup="this.value=this.value.replace(/[^0-9.]/g,'')" />
                                                </div>
                                            </div>
                                            <div class="col-sm-2 col-xs-4">
                                                <div class="form-group">
                                                    <label for="pwd"><?php echo $this->lang->line('bp'); ?></label>
                                                    <input name="bp" type="text" id="revisit_bp" class="form-control"
                                                        value="<?php echo set_value('bp'); ?>"
                                                        onkeyup="this.value=this.value.replace(/[^0-9.]/g,'')" />
                                                </div>
                                            </div>
                                            <div class="col-sm-2 col-xs-4">
                                                <div class="form-group">
                                                    <label for="pwd"><?php echo $this->lang->line('pulse'); ?></label>
                                                    <input name="pulse" id="revisit_pulse" type="text"
                                                        class="form-control" value="<?php echo set_value('pulse'); ?>"
                                                        onkeyup="this.value=this.value.replace(/[^0-9.]/g,'')" />
                                                </div>
                                            </div>


                                            <div class="col-sm-2 col-xs-4">
                                                <div class="form-group">
                                                    <label
                                                        for="pwd"><?php echo $this->lang->line('temperature'); ?></label>
                                                    <input name="temperature" id="revisit_temperature" type="text"
                                                        class="form-control"
                                                        value="<?php echo set_value('temperature'); ?>"
                                                        onkeyup="this.value=this.value.replace(/[^0-9.]/g,'')" />
                                                </div>
                                            </div>
                                            <div class="col-sm-2 col-xs-4">
                                                <div class="form-group">
                                                    <label
                                                        for="pwd"><?php echo $this->lang->line('respiration'); ?></label>
                                                    <input name="respiration" type="text" id="revisit_respiration"
                                                        class="form-control" value="<?php echo set_value('bp'); ?>"
                                                        onkeyup="this.value=this.value.replace(/[^0-9.]/g,'')" />
                                                </div>
                                            </div>
                                        </div>
                                        <!--./row-->
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label>
                                                        <?php echo $this->lang->line('symptoms_type'); ?></label>
                                                    <div><select name='symptoms_type' id="act"
                                                            class="form-control select2 act" style="width:100%">
                                                            <option value=""><?php echo $this->lang->line('select') ?>
                                                            </option>
                                                            <?php foreach ($symptomsresulttype as $dkey => $dvalue) {
                                                            ?>
                                                            <option value="<?php echo $dvalue["id"]; ?>">
                                                                <?php echo $dvalue["symptoms_type"]; ?>
                                                            </option>

                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    <span
                                                        class="text-danger"><?php echo form_error('symptoms_type'); ?></span>
                                                </div>
                                            </div>


                                            <div class="col-sm-3">
                                                <label>
                                                    <?php echo $this->lang->line('symptoms_title'); ?></label>
                                                <div id="dd" class="wrapper-dropdown-3">
                                                    <input class="form-control filterinput" type="text">
                                                    <ul class="dropdown scroll150 section_ul">
                                                        <li><label
                                                                class="checkbox"><?php echo $this->lang->line('select'); ?></label>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label
                                                        for="email"><?php echo $this->lang->line('symptoms'); ?></label>
                                                    <textarea name="symptoms" id="esymptoms"
                                                        class="form-control"><?php echo set_value('address'); ?></textarea>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="pwd"><?php echo $this->lang->line('note'); ?></label>
                                                    <textarea name="note_remark" id="revisit_note"
                                                        class="form-control"><?php echo set_value('note_remark'); ?></textarea>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label
                                                        for="email"><?php echo $this->lang->line('any_known_allergies'); ?></label>
                                                    <textarea name="known_allergies" id="eknown_allergies"
                                                        placeholder=""
                                                        class="form-control"><?php echo set_value('address'); ?></textarea>
                                                </div>
                                            </div>
                                            <div class="">
                                                <?php
                                                echo display_custom_fields('opdrecheckup');
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <!--./col-md-8-->

                                    <div class="col-lg-4 col-md-4 col-sm-4 col-eq ptt10">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label><?php echo $this->lang->line('appointment_date'); ?></label>
                                                    <small class="req">*</small>
                                                    <input id="revisit_date" name="appointment_date" placeholder=""
                                                        type="text" class="form-control"
                                                        value="<?php echo date("d/m/Y") ?>" disabled />
                                                    <span
                                                        class="text-danger"><?php echo form_error('appointment_date'); ?></span>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label>
                                                        <?php echo $this->lang->line('case'); ?></label>
                                                    <div><input class="form-control" type='text' id="revisit_case"
                                                            name='revisit_case'
                                                            onkeyup="this.value=this.value.replace(/[^a-zA-Z]/g,'')" />
                                                    </div>
                                                    <span class="text-danger"><?php echo form_error('case'); ?></span>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>
                                                        <?php echo $this->lang->line('casualty'); ?></label>
                                                    <div>
                                                        <select name="casualty" id="revisit_casualty"
                                                            class="form-control">
                                                            <?php foreach ($yesno_condition as $yesno_key => $yesno_value) {
                                                            ?>
                                                            <option value="<?php echo $yesno_key ?>" <?php
                                                                                                            if ($yesno_key == 'no') {
                                                                                                                echo "selected";
                                                                                                            }
                                                                                                            ?>>
                                                                <?php echo $yesno_value ?>
                                                            </option>
                                                            <?php } ?>
                                                        </select>


                                                    </div>
                                                    <span
                                                        class="text-danger"><?php echo form_error('casualty'); ?></span>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>
                                                        <?php echo $this->lang->line('old_patient'); ?></label>
                                                    <div>
                                                        <select name="old_patient" id="revisit_old_patient"
                                                            class="form-control">
                                                            <?php foreach ($yesno_condition as $yesno_key => $yesno_value) {
                                                            ?>
                                                            <option value="<?php echo $yesno_key ?>" <?php
                                                                                                            if ($yesno_key == 'no') {
                                                                                                                echo "selected";
                                                                                                            }
                                                                                                            ?>>
                                                                <?php echo $yesno_value ?>
                                                            </option>
                                                            <?php } ?>
                                                        </select>

                                                    </div>
                                                    <span
                                                        class="text-danger"><?php echo form_error('old_patient'); ?></span>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>
                                                        <?php echo $this->lang->line('tpa'); ?></label>
                                                    <div>
                                                        <select class="form-control" name='organisation_name'
                                                            onchange="" id="revisit_organisation">
                                                            <option value=""><?php echo $this->lang->line('select') ?>
                                                            </option>
                                                            <?php foreach ($organisation as $orgkey => $orgvalue) {
                                                            ?>
                                                            <option value="<?php echo $orgvalue["id"]; ?>" <?php
                                                                                                                if ((isset($org_select)) && ($org_select == $orgvalue["id"])) {
                                                                                                                    echo "selected";
                                                                                                                }
                                                                                                                ?>>
                                                                <?php echo $orgvalue["organisation_name"]; ?>
                                                            </option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    <span
                                                        class="text-danger"><?php echo form_error('organisation_name'); ?></span>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>
                                                        <?php echo $this->lang->line('reference'); ?></label>
                                                    <div><input class="form-control" id="revisit_refference" type='text'
                                                            name='refference'
                                                            onkeyup="this.value=this.value.replace(/[^a-zA-Z]/g,'')" />
                                                    </div>
                                                    <span
                                                        class="text-danger"><?php echo form_error('refference'); ?></span>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label>
                                                        <?php echo $this->lang->line('consultant_doctor'); ?></label>
                                                    <div><select onchange="" class="form-control" style="width: 100%"
                                                            <?php
                                                                                                                        if ($disable_option == true) {
                                                                                                                            echo "disabled";
                                                                                                                        }
                                                                                                                        ?> name='consultant_doctor' id="revisit_doctor">
                                                            <option value="" disabled selected>
                                                                <?php echo $this->lang->line('select') ?>
                                                            </option>
                                                            <?php foreach ($doctors as $dkey => $dvalue) {
                                                            ?>
                                                            <option value="<?php echo $dvalue["id"]; ?>" <?php
                                                                                                                if ((isset($doctor_select)) && ($doctor_select == $dvalue["id"])) {
                                                                                                                    echo "selected";
                                                                                                                }
                                                                                                                ?>>
                                                                <?php echo composeStaffNameByString($dvalue["name"], $dvalue["surname"], $dvalue["employee_id"]); ?>
                                                            </option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    <span
                                                        class="text-danger"><?php echo form_error('refference'); ?></span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><?php echo $this->lang->line('charge_category'); ?></label>
                                                    <small class="req"> *</small>
                                                    <select name="charge_category" style="width: 100%"
                                                        class="form-control charge_category select2">
                                                        <option value=""><?php echo $this->lang->line('select'); ?>
                                                        </option>
                                                        <?php foreach ($charge_category as $key => $value) {
                                                        ?>
                                                        <option value="<?php echo $value['id']; ?>">
                                                            <?php echo $value['name']; ?>
                                                        </option>
                                                        <?php } ?>
                                                    </select>

                                                    <span
                                                        class="text-danger"><?php echo form_error('charge_category'); ?></span>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><?php echo $this->lang->line('charge'); ?></label><small
                                                        class="req"> *</small>
                                                    <select name="charge_id" style="width: 100%"
                                                        class="form-control charge select2">
                                                        <option value=""><?php echo $this->lang->line('select') ?>
                                                        </option>
                                                    </select>
                                                    <span
                                                        class="text-danger"><?php echo form_error('charge_id'); ?></span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><?php echo $this->lang->line('tax'); ?></label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control right-border-none"
                                                            name="percentage" id="percentage" readonly
                                                            autocomplete="off">
                                                        <span class="input-group-addon "> %</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label><?php echo $this->lang->line('standard_charge') . " (" . $currency_symbol . ")" ?></label>
                                                    <input type="text" readonly name="standard_charge"
                                                        id="standard_chargevisit" class="form-control"
                                                        value="<?php echo set_value('standard_charge'); ?>">

                                                    <span
                                                        class="text-danger"><?php echo form_error('standard_charge'); ?></span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><?php echo $this->lang->line('applied_charge') . " (" . $currency_symbol . ")" ?></label><small
                                                        class="req"> *</small><input type="text" name="amount"
                                                        id="apply_chargevisit" onkeyup="update_amount(this.value)"
                                                        class="form-control" readonly>
                                                    <span
                                                        class="text-danger"><?php echo form_error('apply_charge'); ?></span>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="pwd"><?php echo $this->lang->line('amount'); ?>
                                                        <?php echo '(' . $currency_symbol . ')'; ?></label><small
                                                        class="req"> *</small>
                                                    <input name="apply_amount" readonly type="text" class="form-control"
                                                        id="revisit_amount" />
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label
                                                        for="pwd"><?php echo $this->lang->line('payment_mode'); ?></label>
                                                    <select name="payment_mode" class="form-control payment_mode"
                                                        onchange="checkcheque(this.value)">
                                                        <option value="" selected disabled>Select Payment Method
                                                        </option>
                                                        <?php foreach ($payment_mode as $payment_key => $payment_value) { ?>
                                                        <option value="<?php echo $payment_key; ?>">
                                                            <?php echo $payment_value; ?>
                                                        </option>
                                                        <?php } ?>
                                                    </select>
                                                    <span class="text-danger" id="paymentmethodchecker"></span>
                                                </div>
                                            </div>
                                            <div class="revisit_cheque_div" style="display:none;">

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label><?php echo $this->lang->line('cheque_no'); ?></label><small
                                                            class="req"> *</small>
                                                        <input type="text" name="cheque_no" id="cheque_no"
                                                            class="form-control">
                                                        <span
                                                            class="text-danger"><?php echo form_error('cheque_no'); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label><?php echo $this->lang->line('cheque_date'); ?></label><small
                                                            class="req"> *</small>
                                                        <input type="text" name="cheque_date" id="cheque_date"
                                                            class="form-control date">
                                                        <span
                                                            class="text-danger"><?php echo form_error('cheque_date'); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label><?php echo $this->lang->line('attach_document'); ?></label>
                                                        <input type="file" class="filestyle form-control"
                                                            name="document">
                                                        <span
                                                            class="text-danger"><?php echo form_error('document'); ?></span>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label
                                                        for="pwd"><?php echo $this->lang->line('paid_amount'); ?></label><small
                                                        class="req"> *</small>
                                                    <input type="text" name="paid_amount" id="paid_amount"
                                                        class="form-control"
                                                        oninput="this.value = this.value.replace(/[^0-9.]/g, ''); checkpaylater(this.value);">
                                                    <span class="text-danger" id="amountchecker"></span>
                                                </div>
                                            </div>
                                            <?php if ($this->module_lib->hasActive('live_consultation')) { ?>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label>
                                                        <?php echo $this->lang->line('live_consultation'); ?></label>
                                                    <div>
                                                        <select name="live_consult" id="live_consultvisit"
                                                            class="form-control">
                                                            <?php foreach ($yesno_condition as $yesno_key => $yesno_value) {
                                                                ?>
                                                            <option value="<?php echo $yesno_key ?>" <?php
                                                                                                                if ($yesno_key == 'no') {
                                                                                                                    echo "selected";
                                                                                                                }
                                                                                                                ?>>
                                                                <?php echo $yesno_value ?>
                                                            </option>
                                                            <?php } ?>
                                                        </select>

                                                    </div>
                                                    <span
                                                        class="text-danger"><?php echo form_error('live_consult'); ?></span>
                                                </div>
                                            </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <!--./row-->


                            </div>
                            <!--./col-md-12-->

                        </div>
                        <!--./row-->

                    </div>
                </div> <!-- scroll area -->
                <div class="modal-footer sticky-footer">
                    <div class="pull-right">
                        <button type="submit" id="formrevisitbtn"
                            data-loading-text="<?php echo $this->lang->line('processing'); ?>" class="btn btn-info"><i
                                class="fa fa-check-circle"></i> <?php echo $this->lang->line('save'); ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- -->
<div class="modal fade" id="myPaymentModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('add_payment'); ?></h4>
            </div>
            <form id="add_payment" accept-charset="utf-8" method="post" class="ptt10">
                <div class="">
                    <div class="modal-body pt0 pb0">
                        <input type="hidden" name="opd_id" id="payment_opd_id" class="form-control"
                            value="<?php echo $result['id']; ?>">
                        <input type="hidden" name="case_reference_id" id="payment_opd_id" class="form-control"
                            value="<?php echo $result['case_reference_id']; ?>">
                        <input type="hidden" name="patient_id" value="<?php echo $id; ?>">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('date'); ?></label><small class="req">
                                                *</small>

                                            <input type="text" name="payment_date" id="date" class="form-control"
                                                autocomplete="off" value="<?= date('d/m/Y'); ?>" readonly>
                                            <span class="text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('amount') . " (" . $currency_symbol . ")"; ?></label><small
                                                class="req"> *</small>

                                            <input type="text" name="amount" id="amount" class="form-control"
                                                value="<?php echo number_format((float) ($total - $total_payment), 2, '.', ''); ?>"
                                                readonly>
                                            <input type="hidden" name="net_amount" class="form-control"
                                                value="<?php echo $total - $total_payment; ?>">
                                            <span class="text-danger"><?php echo form_error('amount'); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('payment_mode'); ?></label>
                                            <select class="form-control payment_mode" name="payment_mode">

                                                <?php foreach ($payment_mode as $key => $value) {
                                                    if ($key != 'Paylater') {
                                                ?>
                                                <option value="<?php echo $key ?>" <?php
                                                                                            if ($key == 'cash') {
                                                                                                echo "selected";
                                                                                            }
                                                                                            ?>><?php echo $value ?>
                                                </option>
                                                <?php }
                                                } ?>
                                            </select>
                                            <span class="text-danger"><?php echo form_error('apply_charge'); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row cheque_div" style="display: none;">

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('cheque_no'); ?></label><small
                                                class="req"> *</small>
                                            <input type="text" name="cheque_no" id="cheque_no" class="form-control">
                                            <span class="text-danger"><?php echo form_error('cheque_no'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('cheque_date'); ?></label><small
                                                class="req"> *</small>
                                            <input type="text" name="cheque_date" id="cheque_date"
                                                class="form-control date">
                                            <span class="text-danger"><?php echo form_error('cheque_date'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('attach_document'); ?></label>
                                            <input type="file" class="filestyle form-control" name="document">
                                            <span class="text-danger"><?php echo form_error('document'); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('note'); ?></label>
                                            <input type="text" name="note" id="note" class="form-control" />
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- scroll-area -->
                <div class="modal-footer">
                    <button type="submit" id="add_paymentbtn"
                        data-loading-text="<?php echo $this->lang->line('processing') ?>"
                        class="btn btn-info pull-right"><i class="fa fa-check-circle"></i>
                        <?php echo $this->lang->line('save'); ?></button>
            </form>
        </div>
    </div>
</div>
</div>
<!-- -->
<div class="modal fade" id="view_ot_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="modalicon">


                    <div id='action_detail_modal'>

                    </div>


                </div>

                <h4 class="modal-title"><?php echo $this->lang->line('operation_details'); ?></h4>
            </div>
            <div class="modal-body min-h-3">
                <div id="show_ot_data"></div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="editpayment_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <form id="editpaymentform" accept-charset="utf-8" method="post">
                <div class="modal-header modal-media-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <div class="modalicon">
                    </div>

                    <h4 class="modal-title"><?php echo $this->lang->line('payment_details'); ?></h4>
                </div>
                <div class="modal-body ">
                    <!-- <div clas="row">
                     <div clas="col-md-12">
                        <label><?php echo $this->lang->line('amount'); ?></label> <span class="req">*</span>
                         <input type="text" class="form-control" id="edit_payment" name="edit_payment" >
                         
                     </div>

                   </div> -->
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('date'); ?></label><small class="req">
                                            *</small>
                                        <input type="hidden" class="form-control" id="edit_payment_id"
                                            name="edit_payment_id">

                                        <input type="text" name="payment_date" id="payment_date"
                                            class="form-control datetime" autocomplete="off">
                                        <input type="hidden" class="form-control" id="edit_payment_id"
                                            name="edit_payment_id">
                                        <span class="text-danger"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('amount') . " (" . $currency_symbol . ")"; ?></label><small
                                            class="req"> *</small>

                                        <input type="text" name="amount" id="edit_payment" class="form-control"
                                            value="">

                                        <span class="text-danger"><?php echo form_error('amount'); ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('payment_mode'); ?></label>
                                        <select class="form-control payment_mode" name="payment_mode" id="payment_mode">

                                            <?php foreach ($payment_mode as $key => $value) {
                                            ?>
                                            <option value="<?php echo $key ?>" <?php
                                                                                    if ($key == 'cash') {
                                                                                        echo "selected";
                                                                                    }
                                                                                    ?>><?php echo $value ?></option>
                                            <?php } ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('apply_charge'); ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row cheque_div" style="display: none;">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('cheque_no'); ?></label><small class="req">
                                            *</small>
                                        <input type="text" name="cheque_no" id="edit_cheque_no" class="form-control">
                                        <span class="text-danger"><?php echo form_error('cheque_no'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('cheque_date'); ?></label><small
                                            class="req"> *</small>
                                        <input type="text" name="cheque_date" id="edit_cheque_date"
                                            class="form-control date">
                                        <span class="text-danger"><?php echo form_error('cheque_date'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('attach_document'); ?></label>
                                        <input type="file" class="filestyle form-control" name="document">
                                        <span class="text-danger"><?php echo form_error('document'); ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('note'); ?></label>
                                        <input type="text" name="note" id="edit_payment_note" class="form-control" />
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="editpaymentbtn" data-loading-text="Processing..."
                        class="btn btn-info pull-right"><i class="fa fa-check-circle"></i>
                        <?php echo $this->lang->line('save'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#formedit').on('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        let dataObject = {};
        let errorMessages = [];
        formData.forEach((value, key) => {
            let stringValue = (typeof value === 'string') ? value.trim() : value;
            dataObject[key] = stringValue;
        });

        let requiredFields = ['appointment_date', 'consultant_doctor'];
        requiredFields.forEach(field => {
            if (!dataObject[field]) errorMessages.push(field);
        });

        if (errorMessages.length) {
            errorMsg(`Please fill all required fields: ${errorMessages.join(', ')}`);
            return;
        }
        const url = window.location.href;
        const segments = url.split('/');
        const encodedPatientId = segments[segments.length - 2].split("#")[0];
        const patientId = atob(encodedPatientId);
        let mappedData = {
            "cons_doctor": parseInt(dataObject.consultant_doctor) || "",
            "case_type": dataObject.case || "",
            "appointment_date": moment(dataObject.appointment_date, "DD/MM/YYYY hh:mm A").format(
                "YYYY-MM-DD HH:mm:ss") || "",
            "symptoms_type": parseInt(dataObject.symptoms_type) || null,
            "symptoms": dataObject.symptoms || "",
            "bp": parseFloat(dataObject.bp) || "",
            "height": parseFloat(dataObject.height) || "",
            "weight": parseFloat(dataObject.weight) || "",
            "pulse": parseInt(dataObject.pulse) || "",
            "temperature": parseFloat(dataObject.temperature) || "",
            "respiration": parseInt(dataObject.respiration) || "",
            "known_allergies": dataObject.known_allergies || "",
            "patient_old": dataObject.old_patient || "",
            "casualty": dataObject.casualty || "",
            "refference": dataObject.refference || "",
            "note": dataObject.note || "",
            "payment_mode": dataObject.payment_mode || $("#defult_val").val() || "",
            "generated_by": <?= $data['id'] ?> || "",
            "live_consult": dataObject.live_consult || "",
            "can_delete": "yes",
            "payment_date": moment(dataObject.payment_date, "DD/MM/YYYY hh:mm A").format(
                "YYYY-MM-DD HH:mm:ss") || "",
            "time": moment(dataObject.appointment_date, "DD/MM/YYYY hh:mm A").format("HH:mm:ss") ||
                "",
            "standard_charge": parseFloat(dataObject.standard_charge) || "",
            "charge_id": parseInt(dataObject.charge_id) || "",
            "tax": parseFloat(dataObject.percentage) || "",
            "apply_charge": parseFloat(dataObject.apply_amount) || "",
            "amount": parseFloat(dataObject.amount) || 0,
            "patient_id": patientId || "",
            "Hospital_id": <?= $data['hospital_id'] ?> || "",
            "spo2": dataObject.spo2,
            "received_by_name": '<?= $data['username'] ?>',
        };

        if (dataObject.organisation != 0) {
            mappedData.organisation_id = parseInt(dataObject.organisation);
            mappedData.tpa_charge = dataObject.tpa_charge;
        }
        let encodedVisitId = window.location.href.split('/').pop().split('#')[0];
        let visitId = atob(encodedVisitId);

        sendAjaxRequest(`<?= $api_base_url ?>opd-out-patient/${visitId}`, "PATCH", mappedData, function(
            response) {
            handleResponse(response);
        });
    });
});
</script>

<!-- //========datatable start===== -->
<script type="text/javascript">
$(document).on('click', '.print_ot_bill', function() {
    var $this = $(this);
    var record_id = $this.data('recordId');
    $this.button('loading');
    $.ajax({
        url: '<?php echo base_url(); ?>admin/operationtheatre/print_otdetails',
        type: "POST",
        data: {
            'id': record_id
        },
        dataType: 'json',
        beforeSend: function() {
            $this.button('loading');

        },
        success: function(res) {
            popup(res.page);
        },
        error: function(xhr) { // if error occured
            alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
            $this.button('reset');

        },
        complete: function() {
            $this.button('reset');

        }
    });
});
</script>
<script type="text/javascript">
(function($) {
    var opdid = atob("<?php echo strtok($this->uri->segment(5), '#'); ?>");
    var patient_id = atob("<?php echo $this->uri->segment(4); ?>");

    'use strict';
    $(document).ready(function() {
        $('#view_ot_modal,#myPaymentModal,#viewModal,#add_chargeModal').modal({
            backdrop: 'static',
            keyboard: false,
            show: false
        })
        initDatatable('ajaxlist', 'admin/patient/getvisitdatatable/' + opdid);
    });

}(jQuery))
</script>

<!-- //========datatable end===== -->

<script type="text/javascript">
$('#add_chargeModal').on('hidden.bs.modal', function(e) {
    $('.charge_type ', $(this)).select2('val', '');
    $('.addcharge', $(this)).html('').select2();
    $('.charge_category', $(this)).html('').select2();
});
var datetime_format =
    '<?php echo strtr($this->customlib->getHospitalDateFormat(true, true), ['d' => 'DD', 'm' => 'MM', 'Y' => 'YYYY', 'H' => 'hh', 'i' => 'mm']) ?>';


$(document).on('click', '.add-btn', function() {
    var s = "";
    s += "<div class='row'>";
    s += "<input name='rows[]' type='hidden' value='" + rows + "'>";
    s += "<div class='col-md-6'>";
    s += "<div class='form-group'>";
    s += "<label for='act'>Act</label>";
    s += "<select class='form-control act select2' id='act' name='act" + rows + "' data-row_id='" + rows + "'>";
    s += "<option value=''>--Select--</option>";
    s += $('#act-template').html();
    s += "</select>";
    s += "<small class='text text-danger help-inline'></small>";
    s += "</div>";
    s += "</div>";
    s += "<div class='col-md-5'>";
    s += "<label for='validationDefault02'>Section</label>";
    s += "<div id='dd' class='wrapper-dropdown-3'>";
    s += "<input class='form-control filterinput' type='text'>";
    s += "<ul class='dropdown scroll150 section_ul'>";
    s += "<li><label class='checkbox'>--Select--</label></li>";
    s += "</ul>";
    s += "</div>";
    s += "</div>";
    s += "<div class='col-md-1'>";
    s += "<div class='form-group'>";
    s += "<label for='removebtn'>&nbsp;</label>";
    s +=
        "<button type='button' class='form-control btn btn-sm btn-danger remove_row'><i class='fa fa-remove'></i></button>";
    s += "</div>";
    s += "</div>";
    s += "</div>";
    $(".multirow").append(s);
    $('.select2').select2();
    link = 2;
    rows++;
});
</script>

<script type="text/html" id="act-template">
<?php foreach ($symptomsresulttype as $dkey => $dvalue) {
    ?>
<option value="<?php echo $dvalue["id"]; ?>"><?php echo $dvalue["symptoms_type"]; ?></option>
<?php
    }
    ?>
</script>

<script>
$(document).on('change', '.act', function() {
    $this = $(this);
    var sys_val = $(this).val();

    var row_id = $this.data('row_id');
    var section_ul = $(this).closest('div.row').find('ul.section_ul');

    var sel_option = "";
    $.ajax({
        type: 'POST',
        url: base_url + 'admin/patient/getPartialsymptoms',
        data: {
            'sys_id': sys_val,
            'row_id': row_id
        },
        dataType: 'JSON',
        beforeSend: function() {

            $('ul.section_ul').find('li:not(:first-child)').remove();
            $("div.wrapper-dropdown-3").removeClass('active');

        },
        success: function(data) {

            section_ul.append(data.record);

        },
        error: function(xhr) { // if error occured
            alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");

        },
        complete: function() {

        }
    });

});
</script>
<script type="text/javascript">
$(function() {

    $(".timepicker").timepicker({
        defaultTime: '12:00 PM'
    });
});


$(document).on('select2:select', '.medicine_category_medication', function() {

    var medicine_category = $(this).val();

    $('.medicine_name_medication').html(
        "<option value=''><?php echo $this->lang->line('loading'); ?></option>");
    getMedicineForMedication(medicine_category, "");
    getMedicineDosageForMedication(medicine_category);
});

function getMedicineForMedication(medicine_category, medicine_id) {

    var div_data = "<option value=''><?php echo $this->lang->line('select'); ?></option>";
    if (medicine_category != "") {
        $.ajax({
            url: base_url + 'admin/pharmacy/get_medicine_name',
            type: "POST",
            data: {
                medicine_category_id: medicine_category
            },
            dataType: 'json',
            success: function(res) {

                $.each(res, function(i, obj) {
                    var sel = "";
                    div_data += "<option value='" + obj.id + "'>" + obj.medicine_name + "</option>";

                });
                $('.medicine_name_medication').html(div_data);
                $(".medicine_name_medication").select2("val", medicine_id);
                $("#mmedicine_edit_id").val(medicine_id).trigger("change");
                $("#add_dose_medicine_id").val(medicine_id).trigger("change");
            }
        });
    }
}

function getMedicineDosageForMedication(medicine_category) {
    var div_data = "<option value=''><?php echo $this->lang->line('select'); ?></option>";
    if (medicine_category != "") {
        $.ajax({
            url: base_url + 'admin/pharmacy/get_medicine_dosage',
            type: "POST",
            data: {
                medicine_category_id: medicine_category
            },
            dataType: 'json',
            success: function(res) {

                $.each(res, function(i, obj) {
                    var sel = "";
                    div_data += "<option value='" + obj.id + "'>" + obj.dosage + " " + obj.unit +
                        "</option>";

                });
                $('.dosage_medication').html(div_data);
                $(".dosage_medication").select2("val", '');

            }
        });
    }
}

function get_dosagename(id) {
    $.ajax({
        url: '<?php echo base_url(); ?>admin/pharmacy/get_dosagename',
        type: "POST",
        data: {
            dosage_id: id
        },
        dataType: 'json',
        success: function(res) {
            if (res) {
                $('#medicine_dosage_medication').val(res.dosage_unit);
            } else {

            }
        }
    });
}


$(document).ready(function(e) {
    $("#add_medication").on('submit', function(e) {
        e.preventDefault();
        $("#add_medicationbtn").button('loading');
        var formData = new FormData(this);
        var formObject = {};
        var errorMessages = [];
        if (!formData.get('date')) errorMessages.push("Date");
        if (!formData.get('time')) errorMessages.push("Time");
        if (!formData.get('dosage')) errorMessages.push("Dosage");
        if (!formData.get('medicine_name_id')) errorMessages.push("Medicine Name");
        if (errorMessages.length > 0) {
            errorMsg("Please fill in the following required fields: " + errorMessages.join(", "));
            $("#add_medicationbtn").button('reset');
            return;
        }
        var rawDate = formData.get('date');
        var rawTime = formData.get('time');

        var dateParts = rawDate.split('/');
        var formattedDate =
            `${dateParts[2]}-${dateParts[0].padStart(2, '0')}-${dateParts[1].padStart(2, '0')}`;

        var timeParts = rawTime.match(/(\d+):(\d+) (\w+)/);
        var hours = parseInt(timeParts[1], 10);
        var minutes = timeParts[2];
        var meridian = timeParts[3];

        if (meridian === "PM" && hours < 12) {
            hours += 12;
        }
        if (meridian === "AM" && hours === 12) {
            hours = 0;
        }
        var formattedTime = `${hours.toString().padStart(2, '0')}:${minutes}:00`;

        formObject["medicine_dosage_id"] = formData.get('dosage') || null;
        formObject["pharmacy_id"] = formData.get('medicine_name_id') || null;
        formObject["opd_details_id"] = formData.get('opdid') || null;
        formObject["date"] = formattedDate;
        formObject["time"] = formattedTime;
        formObject["remark"] = formData.get('remark') || null;
        formObject["Hospital_id"] = <?= $data['hospital_id'] ?>;
        formObject["generated_by"] = <?= $data['id'] ?>;



        $.ajax({
            url: '<?= $api_base_url ?>opd-medication',
            type: "POST",
            data: JSON.stringify(formObject),
            contentType: 'application/json',
            success: function(data) {
                if (data[0]['data '].status == "fail") {
                    var message = data.message;
                    $.each(data.error, function(index, value) {
                        message += value;
                    });
                    errorMsg(message);
                } else {
                    successMsg(data[0]['data '].messege);
                    window.location.reload(true);
                }
                $("#add_medicationbtn").button('reset');
            },
            error: function() {
                $("#add_medicationbtn").button('reset');
            },
            complete: function() {
                $("#add_medicationbtn").button('reset');
            }
        });
    });
});







$(document).on('click', '.remove_row', function() {
    $this = $(this);
    $this.closest('.row').remove();

});
$(document).mouseup(function(e) {
    var container = $(".wrapper-dropdown-3"); // YOUR CONTAINER SELECTOR

    if (!container.is(e.target) // if the target of the click isn't the container...
        &&
        container.has(e.target).length === 0) // ... nor a descendant of the container
    {
        $("div.wrapper-dropdown-3").removeClass('active');
    }
});

$(document).on('click', '.filterinput', function() {

    if (!$(this).closest('.wrapper-dropdown-3').hasClass("active")) {
        $(".wrapper-dropdown-3").not($(this)).removeClass('active');
        $(this).closest("div.wrapper-dropdown-3").addClass('active');
    }


});

$(document).on('click', 'input[name="section[]"]', function() {
    $(this).closest('label').toggleClass('active_section');
});

$(document).on('keyup', '.filterinput', function() {

    var valThis = $(this).val().toLowerCase();
    var closer_section = $(this).closest('div').find('.section_ul > li');

    var noresult = 0;
    if (valThis == "") {
        closer_section.show();
        noresult = 1;
        $('.no-results-found').remove();
    } else {
        closer_section.each(function() {
            var text = $(this).text().toLowerCase();
            var match = text.indexOf(valThis);
            if (match >= 0) {
                $(this).show();
                noresult = 1;
                $('.no-results-found').remove();
            } else {
                $(this).hide();
            }
        });
    };
    if (noresult == 0) {
        closer_section.append('<li class="no-results-found">No results found.</li>');
    }
});
</script>
<script type="text/javascript">
function holdModal(modalId) {
    $("#report_document").dropify();
    $('#' + modalId).modal({
        backdrop: 'static',
        keyboard: false,
        show: true
    });
}


function addmedicationModal() {
    document.querySelector("#add_medication").reset();
    $("#mmedicine_id").val("").trigger("change");
    holdModal('myaddMedicationModal');
}


$('#myaddMedicationModal').on('hidden.bs.modal', function() {
    $('#add_medication').find('input:text, input:password, input:file, textarea').val('');
    $('#add_medication').find('select option:selected').removeAttr('selected');
    $('#add_medication').find('input:checkbox, input:radio').removeAttr('checked');
    $('.medicine_category_medication').val("").trigger("change");
    $('.medicine_name_medication').val("").trigger("change");
    $('.dosage_medication').val("").trigger("change");
    $('#mtime').val('12:00 PM');
});


function medicationModal(medicine_category_id, pharmacy_id, date) {

    var div_data = "<option value=''><?php echo $this->lang->line('select'); ?></option>";
    if (medicine_category_id != "") {
        $.ajax({
            url: '<?php echo base_url(); ?>admin/patient/getMedicineDoseDetails',
            type: "POST",
            data: {
                medicine_category_id: medicine_category_id
            },
            dataType: 'json',
            success: function(res) {
                $.each(res, function(i, obj) {
                    var sel = "";
                    div_data += "<option value='" + obj.id + "'>" + obj.dosage + " " + obj.unit +
                        "</option>";

                });

                $("#mdosage").html(div_data);

                $("#add_dose_medicine_category").select2("val", medicine_category_id);
                $("#mdosage").select2("val", '');
                getMedicineForMedication(medicine_category_id, pharmacy_id);

                $("#add_dose_date").val(date);

                holdModal('myMedicationModal');
            },
        });
    }

}


function medicationDoseModal(medication_id) {

    $.ajax({
        url: '<?php echo base_url(); ?>admin/patient/getMedicationDoseDetails',
        type: "POST",
        data: {
            medication_id: medication_id
        },
        dataType: 'json',
        success: function(data) {
            $("#date_edit_medication").val(data.date);

            $('#dosagetime').timepicker('setTime', timeConvert(data.time));

            $('select[id="medicine_dose_id"] option[value="' + data.medicine_dosage_id + '"]').attr(
                "selected", "selected");
            $("#medicine_dose_edit_id").select2().select2('val', data.medicine_dosage_id);
            $("#mmedicine_category_edit_id ").val(data.medicine_category_id).trigger('change');
            getMedicineForMedication(data.medicine_category_id, data.pharmacy_id);
            $("#medicine_dosage_remark").val(data.remark);
            $("#medication_id").val(data.id);
            $('#edit_delete_medication').html("<a href='#' class='delete_record_dosage' data-record-id='" +
                medication_id +
                "' data-toggle='tooltip' title='<?php echo $this->lang->line('delete'); ?>' data-target='' data-toggle='modal'  data-original-title='<?php echo $this->lang->line('delete'); ?>'><i class='fa fa-trash'></i></a>"
            );

            holdModal('myMedicationDoseModal');
        },
    });
}

$(document).ready(function(e) {

    $(document).on('click', '.delete_record_dosage', function() {
        if (confirm(<?php echo "'" . $this->lang->line('delete_confirm') . "'"; ?>)) {
            var id = $(this).data('recordId');

            $.ajax({
                url: base_url + 'admin/patient/deletemedication',
                type: "POST",
                data: {
                    'id': id
                },
                dataType: 'json',
                beforeSend: function() {

                },
                success: function(data) {
                    successMsg(data.message);
                    window.location.reload(true);
                },
                error: function() {
                    alert(
                        "<?php echo $this->lang->line('error_occurred_please_try_again'); ?>"
                    );
                },

                complete: function() {

                }
            });
        }
    });

    $("#add_medicationdose").on('submit', function(e) {
        e.preventDefault();
        $("#add_medicationdosebtn").button('loading');

        var formData = new FormData(this);
        var formObject = {};
        var errorMessages = [];

        // Validate required fields
        if (!formData.get('date')) errorMessages.push("Date");
        if (!formData.get('time')) errorMessages.push("Time");
        if (!formData.get('dosage')) errorMessages.push("Dosage");
        if (!formData.get('medicine_name_id')) errorMessages.push("Medicine Name");

        if (errorMessages.length > 0) {
            errorMsg("Please fill in the following required fields: " + errorMessages.join(", "));
            $("#add_medicationdosebtn").button('reset');
            return;
        }

        // Format date and time
        var rawDate = formData.get('date');
        var rawTime = formData.get('time');

        var dateParts = rawDate.split('/');
        var formattedDate =
            `${dateParts[2]}-${dateParts[0].padStart(2, '0')}-${dateParts[1].padStart(2, '0')}`;

        var timeParts = rawTime.match(/(\d+):(\d+) (\w+)/);
        var hours = parseInt(timeParts[1], 10);
        var minutes = timeParts[2];
        var meridian = timeParts[3];

        if (meridian === "PM" && hours < 12) {
            hours += 12;
        }
        if (meridian === "AM" && hours === 12) {
            hours = 0;
        }
        var formattedTime = `${hours.toString().padStart(2, '0')}:${minutes}:00`;

        // Map form data to formObject
        formObject["medicine_dosage_id"] = formData.get('dosage') || null;
        formObject["pharmacy_id"] = formData.get('medicine_name_id') || null;
        formObject["opd_details_id"] = formData.get('opdid') || null;
        formObject["date"] = formattedDate;
        formObject["time"] = formattedTime;
        formObject["remark"] = formData.get('remark') || null;
        formObject["Hospital_id"] = <?= $data['hospital_id'] ?>;
        formObject["generated_by"] = <?= $data['id'] ?>;

        // AJAX request
        $.ajax({
            url: '<?= $api_base_url ?>opd-medication',
            type: "POST",
            data: JSON.stringify(formObject),
            contentType: 'application/json',
            success: function(data) {
                if (data[0]['data '].status == "fail") {
                    var message = data.message;
                    $.each(data.error, function(index, value) {
                        message += value;
                    });
                    errorMsg(message);
                } else {
                    successMsg(data[0]['data '].messege);
                    window.location.reload(true);
                }
                $("#add_medicationdosebtn").button('reset');
            },
            error: function() {
                $("#add_medicationdosebtn").button('reset');
            },
            complete: function() {
                $("#add_medicationdosebtn").button('reset');
            }
        });
    });

});

$(document).ready(function(e) {
    $("#update_medication").on('submit', function(e) {
        e.preventDefault();
        $("#update_medicationbtn").button('loading');

        var formData = new FormData(this);
        var formObject = {};

        // Format the date
        var rawDate = formData.get('date');
        var dateParts = rawDate.split('/');
        var formattedDate =
            `${dateParts[2]}-${dateParts[0].padStart(2, '0')}-${dateParts[1].padStart(2, '0')}`; // YYYY-MM-DD

        // Format the time
        var rawTime = formData.get('time');
        var timeParts = rawTime.match(/(\d+):(\d+) (\w+)/);
        var hours = parseInt(timeParts[1], 10);
        var minutes = timeParts[2];
        var meridian = timeParts[3];

        if (meridian === "PM" && hours < 12) {
            hours += 12;
        }
        if (meridian === "AM" && hours === 12) {
            hours = 0;
        }
        var formattedTime = `${hours.toString().padStart(2, '0')}:${minutes}:00`;

        var combinedDateTime = `${formattedDate} ${formattedTime}`;


        formObject["medicine_dosage_id"] = formData.get('dosage_id') || null;
        formObject["pharmacy_id"] = formData.get('medicine_name_id') || null;
        formObject["date"] = combinedDateTime;
        formObject["time"] = formattedTime;
        formObject["remark"] = formData.get('remark') || null;
        formObject["Hospital_id"] = <?= $data['hospital_id'] ?>;


        var jsonData = JSON.stringify(formObject, null, 2);
        // console.log("Formatted JSON Data:", jsonData);

        let edit = $('#medication_id').val();
        $.ajax({
            url: '<?= $api_base_url ?>opd-medication/' + edit,
            type: "PATCH",
            data: jsonData,
            contentType: 'application/json',
            dataType: 'json',
            beforeSend: function() {
                $("#update_medicationbtn").button('loading');
            },
            success: function(data) {
                if (data[0]['data '].status === "fail") {
                    var message = data['data '].message;
                    if (data['data '].error) {
                        $.each(data['data '].error, function(index, value) {
                            message += value;
                        });
                    }
                    errorMsg(message);
                } else {
                    successMsg(data[0]['data '].messege);
                    window.location.reload(true);
                }

                $("#update_medicationbtn").button('reset');
            },
            error: function() {
                $("#update_medicationbtn").button('reset');
            }
        });
    });
});




$(function() {
    //Initialize Select2 Elements
    $(function() {
        var hash = window.location.hash;
        hash && $('ul.nav-tabs a[href="' + hash + '"]').tab('show');

        $('.nav-tabs a').click(function(e) {
            $(this).tab('show');
            var scrollmem = $('body').scrollTop();
            window.location.hash = this.hash;
            $('html,body').scrollTop(scrollmem);
            var pid = $("#result_pid").val();
            var opdid = $("#result_opdid").val();
            if (this.hash == '#charges') {

            } else if (this.hash == '#payment') {

            } else if (this.hash == '#diagnosis') {

            }
        });
    });
});


function getdatavalue(dataurl) {

    var pid = $("#result_pid").val();
    var opdid = $("#result_opdid").val();
    var base_url = '<?php echo base_url(); ?>';
    var url = base_url + dataurl;
    $.ajax({
        url: url,
        type: 'POST',
        data: {
            pid: pid,
            opdid: opdid
        },
        success: function(result) {

            $('#datadiganosis').html(result);
        }
    });
}

$(function() {
    $("#compose-textareas,#compose-textareanew").wysihtml5({
        toolbar: {
            "image": false,
        }
    });
});

function edit_prescription(id) {
    //console.log(id);
    $.ajax({
        url: base_url + 'admin/prescription/editopdPrescription',
        dataType: 'JSON',
        data: {
            'prescription_id': id
        },
        type: "POST",
        beforeSend: function() {
            $('.modal-title', "#add_prescription").html('');

        },
        success: function(res) {
            $('.modal-title', "#add_prescription").html(
                'Clinical Notes');

            $('#prescriptionview').modal('hide');
            $('.modal-body', "#add_prescription").html(res.page);
            var medicineTable = $('.modal-body', "#add_prescription").find('table#tableID');
            medicineTable.find('.select2').select2();
            $('.modal-body', "#add_prescription").find('.multiselect2').select2({
                placeholder: 'Select',
                allowClear: false,
                minimumResultsForSearch: 2
            });


            medicineTable.find("tbody tr").each(function() {

                var medicine_category_obj = $(this).find("td select.medicine_category");
                var post_medicine_category_id = $(this).find("td input.post_medicine_category_id")
                    .val();
                var post_medicine_id = $(this).find("td input.post_medicine_id").val();
                var dosage_id = $(this).find("td input.post_dosage_id").val();
                var medicine_dosage = getDosages(post_medicine_category_id, dosage_id);

                $(this).find('.medicine_dosage').html(medicine_dosage);
                $(this).find('.medicine_dosage').select2().select2('val', dosage_id);

                getMedicine(medicine_category_obj, post_medicine_category_id, post_medicine_id);

            });
            $('#add_prescription').modal('show');
        },

        complete: function() {
            $(function() {
                $("#compose-textareas,#compose-textareanew").wysihtml5({
                    toolbar: {
                        "image": false,
                    }
                });
            });

        },
        error: function(xhr) { // if error occured
            alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");


        }
    });
}

function editDiagnosis(id) {
    //alert(patient_id);
    $.ajax({
        url: '<?php echo base_url(); ?>admin/patient/editDiagnosis',
        type: "POST",
        data: {
            id: id
        },
        dataType: 'json',
        success: function(data) {
            // console.log(data);
            $("#eid").val(data.id);
            $("#epatient_id").val(data.patient_id);
            $("#ereporttype").val(data.report_type);
            $("#ereportdate").val(data.report_date);
            $("#edescription").val(data.description);
            $("#ereportcenter").val(data.report_center);
            holdModal('edit_diagnosis');

        },
    });
}
$(document).on('click', '.editot', function() {
    let id = $(this).data('recordId');
    $.ajax({
        url: '<?php echo base_url(); ?>admin/operationtheatre/getotDetails',
        type: "POST",
        data: {
            id: id
        },
        dataType: 'json',
        success: function(data) {
            // console.log(data);
            $("#otid").val(data.id);

            $('#eoperation_category').select2().select2('val', data.category_id);

            getcategory(data.category_id, data.operation_id);
            $('#edate').datetimepicker({
                format: datetime_format,

            });

            $('#edate').data("DateTimePicker").date(new Date(data.date));


            $("#eass_consultant_1").val(data.ass_consultant_1);
            $("#eass_consultant_2").val(data.ass_consultant_2);
            $("#eanesthetist").val(data.anesthetist);
            $("#eanaethesia_type").val(data.anaethesia_type);
            $("#eot_technician").val(data.ot_technician);
            $("#eot_assistant").val(data.ot_assistant);
            $("#eot_remark").val(data.remark);
            $("#eot_result").val(data.result);

            $('#econsultant_doctorid').select2().select2('val', data.consultant_doctor);
            $('#custom_fields_ot').html(data.custom_fields_value);
            $('#eoperation_name').select2().select2('val', data.operation_id);
            holdModal('edit_operationtheatre');

        },
    });
});


function getchargecode(charge_category) {
    var div_data = "";
    $('#code').html("<option value='l'><?php echo $this->lang->line('loading') ?></option>");
    $("#code").select2("val", 'l');


    $.ajax({
        url: '<?php echo base_url(); ?>admin/charges/getchargeDetails',
        type: "POST",
        data: {
            charge_category: charge_category
        },
        dataType: 'json',
        success: function(res) {

            $.each(res, function(i, obj) {
                var sel = "";
                div_data += "<option value='" + obj.id + "'>" + obj.code + " - " + obj.description +
                    "</option>";

            });

            $('#code').html("<option value=''><?php echo $this->lang->line('select'); ?></option>");

            $('#code').append(div_data);
            $("#code").select2("val", '');

            $('#standard_charge').val('');
            $('#apply_charge').val('');
        }
    });
}

$(document).ready(function(e) {
    $("#form_editdiagnosis").on('submit', (function(e) {

        $("#form_editdiagnosisbtn").button('loading');
        e.preventDefault();
        $.ajax({
            url: '<?php echo base_url(); ?>admin/patient/update_diagnosis',
            type: "POST",
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                if (data.status == "fail") {
                    var message = "";
                    $.each(data.error, function(index, value) {
                        message += value;
                    });

                    errorMsg(message);
                } else {
                    successMsg(data.message);
                    window.location.reload(true);
                }
                $("#form_editdiagnosisbtn").button('reset');
            },
            error: function() {

            }
        });
    }));
});

$(document).on('click', '.get_opd_detail', function() {
    var visitid = $(this).data('recordId');
    var $this = $(this);

    $.ajax({
        url: base_url + 'admin/patient/getopdrecheckupDetails',
        type: "POST",
        data: {
            visit_id: visitid
        },
        dataType: 'json',
        beforeSend: function() {
            $this.button('loading');
        },
        success: function(data) {
            var can_delete = data.can_delete;
            if (can_delete == 'yes') {
                var delete_action = "<a href='#' data-toggle='tooltip'  onclick='delete_record(" +
                    visitid +
                    ")' data-original-title='<?php echo $this->lang->line('delete'); ?>'><i class='fa fa-trash'></i></a>";
            } else {
                var delete_action = '';
            }
            var patient_id = "<?php echo $result["id"] ?>";
            $('#edit_delete').html(
                "<?php if ($this->rbac->hasPrivilege('revisit', 'can_edit')) { ?><a href='#'' onclick='editRecord(" +
                visitid +
                ")' data-target='#editModal' data-toggle='tooltip'  data-original-title='<?php echo $this->lang->line('edit'); ?>'><i class='fa fa-pencil'></i></a><?php } ?><?php if ($this->rbac->hasPrivilege('revisit', 'can_delete')) { ?>" +
                delete_action + "<?php } ?>");
            $('#viewModal .modal-body').html(data.page);
            $('#viewModal').modal('show');

        },
        error: function(xhr) {
            alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
            $this.button('reset');
        },
        complete: function() {
            $this.button('reset');
        }
    });
});

$(document).on('click', '#add_newcharge', function() {

});


function editRecord(visitid) {

    var $exampleDestroy = $('#edit_consdoctor').select2();
    $.ajax({
        url: '<?php echo base_url(); ?>admin/patient/getvisitdetailsdata',
        type: "GET",
        data: {
            visitid: visitid
        },
        dataType: 'json',
        success: function(data) {
            $exampleDestroy.val(data.cons_doctor).select2('destroy').select2()
            $('#customfield').html(data.custom_fields_value);
            $("#appointmentdate").val((d => isNaN(d) ? "" :
                `${String(d.getDate()).padStart(2, '0')}/${String(d.getMonth() + 1).padStart(2, '0')}/${d.getFullYear()}`
            )(new Date(data.appointment_date)));
            $('#act').val(data.symptoms_type).find(`option[value="${data.symptoms_type}"]`).attr('selected',
                'selected').trigger('change');
            $('#visitid').val(visitid);
            $('#visit_transaction_id').val(data.transaction_id);
            $("#edit_case").val(data.case_type);
            $("#symptoms_description").val(data.symptoms);
            $("#edit_casualty").val(data.casualty);
            $("#edit_oldpatient").val(data.patient_old);
            $("#edit_refference").val(data.refference);
            $("#edit_revisit_note").val(data.note);
            $('select[id="edit_organisation"] option[value="' + data.organisation_id + '"]').attr(
                "selected", "selected");
            $("#edit_height").val(data.height);
            $("#edit_weight").val(data.weight);
            $("#edit_bp").val(data.bp);
            $("#edit_pulse").val(data.pulse);
            $("#edit_temperature").val(data.temperature);
            $("#edit_respiration").val(data.respiration);
            $("#edit_paymentmode").val(data.payment_mode);
            $("#edit_opdid").val(data.opdid);
            $("#eknown_allergies").val(data.visit_known_allergies);
            $("#edit_visit_payment_date").val((d => isNaN(d) ? "" :
                `${String(d.getDate()).padStart(2, '0')}/${String(d.getMonth() + 1).padStart(2, '0')}/${d.getFullYear()}`
            )(new Date(data.payment_date)));
            $("#edit_visit_payment").val(data.amount);
            $("#visit_payment_mode").val(data.payment_mode).prop('selected');
            $("#defult_val").val(data.payment_mode);
            $(".visit_payment_mode").trigger('change');
            $("#edit_visit_cheque_no").val(data.cheque_no);
            $("#edit_visit_cheque_date").val(data.cheque_date);
            $("#edit_visit_payment_note").val(data.payment_note);
            $("#viewModal").modal('hide');
            $("#edit_spo2").val(data.spo2);
            if (data.payment_mode == null) {
                $('#edit_payment_div').css("display", "none");
                $('#edit_payment_mode_div').css("display", "none");
                $("#visit_payment_mode").val('Paylater').prop('selected');
            }
            holdModal('editModal');
        },
    });
}

function delete_record(id) {
    if (confirm(<?php echo "'" . $this->lang->line('delete_confirm') . "'"; ?>)) {
        $.ajax({
            url: '<?php echo base_url(); ?>admin/patient/deleteVisit/' + id,
            type: "POST",
            data: {
                id: id
            },
            dataType: 'json',
            success: function(data) {
                successMsg(<?php echo "'" . $this->lang->line('delete_message') . "'"; ?>);
                window.location.reload(true);
            }
        })
    }
}

function deleteot(id) {
    if (confirm(<?php echo "'" . $this->lang->line('delete_confirm') . "'"; ?>)) {
        $.ajax({
            url: '<?= $api_base_url ?>opd-operation/deleteOperation/' + id + '?Hospital_id=' +
                <?= $data['hospital_id'] ?>,
            type: "DELETE",
            data: {
                id: id
            },
            dataType: 'json',
            success: function(data) {
                successMsg(<?php echo "'" . $this->lang->line('delete_message') . "'"; ?>);
                window.location.reload(true);
            }
        })
    }
}

function delete_patient(id, patient_id) {
    if (confirm(<?= json_encode($this->lang->line('delete_confirm')) ?>)) {
        sendAjaxRequest(
            '<?= $api_base_url ?>opd-out-patient/' + id + '?hos_id=' + <?= $data['hospital_id'] ?>,
            'DELETE',
            {},
            function(response) {
                handleResponse(response, function() {
                    successMsg(<?= json_encode($this->lang->line('delete_message')) ?>);
                    window.location.href = '<?= base_url() ?>admin/patient/profile/' + btoa(patient_id);
                });
            }
        );
    }
}

function getEditRecord(id) {

    $.ajax({
        url: '<?php echo base_url(); ?>admin/patient/getpatientDetails',
        type: "POST",
        data: {
            id: id
        },
        dataType: 'json',
        success: function(data) {
            $("#eupdateid").val(data.id);
            $('#customfieldpatient').html(data.custom_fields_value);
            $("#ename").val(data.patient_name);
            $("#eguardian_name").val(data.guardian_name);
            $("#emobileno").val(data.mobileno);
            $("#eemail").val(data.email);
            $("#eaddress").val(data.address);
            $("#age_year").val(data.age);
            $("#age_month").val(data.month);
            $("#age_day").val(data.day);
            $("#birth_date").val(data.dob);
            $("#enote").val(data.note);
            $("#exampleInputFile").attr("data-default-file", '<?php echo base_url() ?>' + data.image);
            $(".dropify-render").find("img").attr("src", '<?php echo base_url() ?>' + data.image);
            $("#eknown_allergies").val(data.known_allergies);
            $('select[id="blood_groups"] option[value="' + data.blood_bank_product_id + '"]').attr(
                "selected", "selected");
            $('select[id="egenders"] option[value="' + data.gender + '"]').attr("selected", "selected");
            $('select[id="marital_statuss"] option[value="' + data.marital_status + '"]').attr("selected",
                "selected");
            $("#myModal").modal('hide');
            holdModal('myModaledit');
        },
    });
}

function editTimeline(id) {
    $.ajax({
        url: '<?php echo base_url(); ?>admin/patient/editTimeline',
        type: "POST",
        data: {
            id: id
        },
        dataType: 'json',
        success: function(data) {
            $("#etimelineid").val(data.id);
            $("#epatientid").val(data.patient_id);
            $("#etimelinetitle").val(data.title);
            $("#etimelinedate").val(formatDate(data.timeline_date));
            $("#timelineedesc").val(data.description);
            $("#old_document").val(data.document);
            if (data.status == '') {} else {
                $("#evisible_check").attr('checked', true);
            }

            holdModal('myTimelineEditModal');

        },
    });
}

function formatDate(dateString) {
    if (!dateString) return '';
    let [datePart] = dateString.split(" ");
    let parts = datePart.split("/");
    return parts.length === 3 ? `${parts[1]}/${parts[0]}/${parts[2]}` : '';
}

function getRecordDischarged(id, opdid) {
    $.ajax({
        url: '<?php echo base_url(); ?>admin/patient/getopdDetailsSummary',
        type: "POST",
        data: {
            patient_id: id,
            opd_id: opdid
        },
        dataType: 'json',
        success: function(data) {

            $('#disevlistname').html(data.patient_name);
            $('#disevguardian').html(data.guardian_name);
            $('#disevlistnumber').html(data.mobileno);
            $('#disevemail').html(data.email);
            if (data.age == "") {
                $("#disevage").html("");
            } else {
                if (data.age) {
                    var age = data.age + " " + "Years";
                } else {
                    var age = '';
                }
                if (data.month) {
                    var month = data.month + " " + "Month";
                } else {
                    var month = '';
                }
                if (data.dob) {
                    var dob = "(" + data.dob + ")";
                } else {
                    var dob = '';
                }

                $("#disevage").html(age + "," + month + " " + dob);
            }
            $("#disevaddress").html(data.address);
            $("#disenote").html(data.note);
            $("#disevgenders").html(data.gender);
            $("#disevmarital_status").html(data.marital_status);
            $("#disedit_admission_date").html(data.appointment_date);
            $("#disedit_discharge_date").html(data.discharge_date);
            $("#disopdid").val(data.opdid);
            $("#disupdateid").val(data.summary_id);
            $("#disevpatients_id").val(data.pid);
            $("#disinvestigations").val(data.summary_investigations);
            $("#disevnoteipd").val(data.summary_note);
            $("#disdiagnosis").val(data.disdiagnosis);
            $("#disoperation").val(data.disoperation);
            $("#distreatment_at_home").val(data.summary_treatment_home);
            $('#summary_print').html(
                "<?php if ($this->rbac->hasPrivilege('discharged_summary', 'can_view')) { ?><a href='#' data-toggle='tooltip' onclick='printData(" +
                data.summary_id +
                ")'   data-original-title='<?php echo $this->lang->line('print'); ?>'><i class='fa fa-print'></i></a> <?php } ?>"
            );
            holdModal('myModaldischarged');
        },
    });
}

function printData(insert_id) {
    var base_url = '<?php echo base_url() ?>';
    $.ajax({
        url: base_url + 'admin/patient/getopdsummaryDetails/' + insert_id,
        type: 'POST',
        data: {
            id: insert_id,
            print: 'yes'
        },
        success: function(result) {
            popup(result);
        }
    });
}

$(document).ready(function(e) {
    $("#formeditpa").on('submit', (function(e) {
        e.preventDefault();
        $.ajax({
            url: '<?php echo base_url(); ?>admin/patient/update',
            type: "POST",
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                if (data.status == "fail") {
                    var message = "";
                    $.each(data.error, function(index, value) {
                        message += value;
                    });
                    errorMsg(message);
                } else {
                    successMsg(data.message);
                    window.location.reload(true);
                }
                $("#formeditpabtn").button('reset');
            },
            error: function() {

            }
        });
    }));
});

function getRecord_id(visitid) {

    $.ajax({
        url: base_url + 'admin/prescription/addopdPrescription',
        dataType: 'JSON',
        data: {
            'visit_detail_id': visitid
        },
        type: "POST",
        beforeSend: function() {
            $('.modal-title', "#add_prescription").html('');
        },
        success: function(res) {
            $('.modal-title', "#add_prescription").html(
                'Clinical Notes');
            $('.modal-body', "#add_prescription").html(res.page);
            $('.modal-body', "#add_prescription").find('table').find('.select2').select2();
            $('.modal-body', "#add_prescription").find('.multiselect2').select2({
                placeholder: 'Select',
                allowClear: false,
                minimumResultsForSearch: 2
            });

            $('#add_prescription').modal('show');
        },

        complete: function() {
            $("#compose-textareass,#compose-textareaneww").wysihtml5({
                toolbar: {
                    "image": false,
                }
            });

        },
        error: function(xhr) {
            alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
        }
    });
}

$(document).ready(function() {
    $("form#form_prescription button[type=submit]").click(function() {
        $("button[type=submit]", $(this).closest("form")).removeAttr("clicked");
        $(this).attr("clicked", "true");
    });

    $("#form_prescription").on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        var jsonObject = {};

        formData.forEach((value, key) => {
            jsonObject[key] = value;
        });

        let chiefComplaints = (jsonObject['chief_complaints'] || "").split(',').map(complaint => ({
            "opd_id": jsonObject['opd_id'],
            "complaint_name": complaint.trim(),
            "filled_using": "text"
        })).filter(c => c.complaint_name);

        let clinicalnotes = {
            "followUp": {
                "opd_id": jsonObject['opd_id'],
                "count": jsonObject['count'],
                "duration_limit": "days",
                "date": jsonObject['date'],
                "remarks": jsonObject['remarks'],
                "filled_using": "text"
            },
            "dietPlan": {
                "opd_id": jsonObject['opd_id'],
                "diet_plan": jsonObject['diet_plan'],
                "filled_using": "text"
            },
            "diagnosisReport": [],
            "treatmentAdvice": {
                "opd_id": jsonObject['opd_id'],
                "advice": jsonObject['history'],
                "filled_using": "text"
            },
            "pastTreatmentHistory": {
                "opd_id": jsonObject['opd_id'],
                "history": jsonObject['past_treatment_history'],
                "filled_using": "text"
            },
            "pastTreatmentHistoryDocs": [{
                "opd_id": jsonObject['opd_id'],
                "documents": " ",
                "filled_using": "text"
            }],
            "chiefComplaintsBasic": chiefComplaints,
            "chiefComplaintDetails": {
                "opd_id": jsonObject['opd_id'],
                "count": jsonObject['Chief_duration'],
                "duration_limit": "days",
                "remarks": jsonObject['remarks'],
                "filled_using": "text"
            },
            "hospital_id": "<?= $data['hospital_id'] ?>"
        };

        let opdprescription = {
            "medicine_name": jsonObject['medicine'],
            "frequency": jsonObject['frequency'],
            "dosage": jsonObject['dosage'],
            "duration_count": jsonObject['timing'],
            "duration_limit": "days",
            "quantity": jsonObject['quantity'],
            "when": jsonObject['duration'],
            "remarks": jsonObject['remarks'],
            "filled_using": "text",
            "opd_id": jsonObject['opd_id'],
            "hospital_id": "<?= $data['hospital_id'] ?>"
        };

        let manualvitals = {
            "spo2": jsonObject['spo2'],
            "respiration": jsonObject['Respiration'],
            "temperature": jsonObject['temperature'],
            "pulse": jsonObject['pulse'],
            "weight": jsonObject['weight'],
            "height": jsonObject['height'],
            "bp": jsonObject['bp']
        };

        let diagnosisrows = formData.getAll('diagnosisrows[]');
        if (diagnosisrows.length > 0) {
            diagnosisrows.forEach(id => {
                clinicalnotes.diagnosisReport.push({
                    "opd_id": jsonObject['opd_id'],
                    "test_categories": jsonObject[`test_category_${id}`] || "",
                    "sub_category": jsonObject[`sub_category_${id}`] || "",
                    "laboratory": jsonObject[`laboratory_${id}`] || "",
                    "remarks": jsonObject[`remarks_${id}`] || "",
                    "filled_using": "text"
                });
            });
        }

        // console.log("Clinical Notes:", JSON.stringify(clinicalnotes, null, 2));
        // console.log("OPD Prescription:", JSON.stringify(opdprescription, null, 2));
        // console.log("Manual Vitals:", JSON.stringify(manualvitals, null, 2));

        $.ajax({
            type: "POST",
            url: "<?= $api_base_url_casesheet ?>clinical-notes-with-abha",
            data: JSON.stringify(clinicalnotes),
            contentType: "application/json",
            success: function(response) {
                sendOpdPrescription();
            }
        });

        function sendOpdPrescription() {
            $.ajax({
                type: "POST",
                url: "<?= $api_base_url_casesheet ?>opd-prescription",
                data: JSON.stringify(opdprescription),
                contentType: "application/json",
                success: function(response) {
                    sendManualVitals();
                }
            });
        }

        function sendManualVitals() {
            $.ajax({
                type: "POST",
                url: `<?= $api_base_url_casesheet ?>manual-vitals?opd_id=${jsonObject['opd_id']}&hospital_id=<?= $data['hospital_id'] ?>`,
                data: JSON.stringify(manualvitals),
                contentType: "application/json",
                success: function(response) {
                    successMsg("Prescription and clinical notes saved successfully!");
                    $("#form_prescription").trigger('reset');
                    $("#add_prescription").modal('hide');
                    location.reload();
                }
            });
        }
    });
});







$(document).ready(function(e) {
    $("#form_operationtheatre").on('submit', function(e) {
        e.preventDefault();
        $("#form_operationtheatrebtn").button('loading');

        var formData = new FormData(this);
        var emptyFields = [];

        if (!formData.get('operation_category')) emptyFields.push('Operation Category');
        if (!formData.get('date')) emptyFields.push('Operation Date');
        if (!formData.get('consultant_doctor')) emptyFields.push('Consultant Doctor');

        if (emptyFields.length > 0) {
            errorMsg("Please fill the following fields: " + emptyFields.join(', '));
            $("#form_operationtheatrebtn").button('reset');
            return;
        }

        var transformedData = {
            "opd_details_id": parseInt(formData.get('opdid')) || 0,
            "operation_id": parseInt(formData.get('operation_name')) || 0,
            "date": formatDate(formData.get('date')) || "",
            "consultant_doctor": parseInt(formData.get('consultant_doctor')) || 0,
            "ass_consultant_1": formData.get('ass_consultant_1') || "",
            "ass_consultant_2": formData.get('ass_consultant_2') || "",
            "anesthetist": formData.get('anesthetist') || "",
            "anaethesia_type": formData.get('anaethesia_type') || "",
            "ot_technician": formData.get('ot_technician') || "",
            "ot_assistant": formData.get('ot_assistant') || "",
            "result": formData.get('ot_result') || "",
            "remark": formData.get('ot_remark') || "",
            "Hospital_id": <?= $data['hospital_id'] ?> || 0
        };


        $.ajax({
            url: '<?= $api_base_url ?>opd-operation',
            type: "POST",
            data: JSON.stringify(transformedData),
            contentType: 'application/json',
            dataType: 'json',
            success: function(data) {
                console.log(data);
                successMsg(data[0]['data '].messege);
                location.reload();
                $("#form_operationtheatrebtn").button('reset');
            },
            error: function() {
                $("#form_operationtheatrebtn").button('reset');
            }
        });

        function formatDate(dateStr) {
            var date = new Date(dateStr);
            return date.getFullYear() + "-" +
                String(date.getMonth() + 1).padStart(2, '0') + "-" +
                String(date.getDate()).padStart(2, '0') + " " +
                String(date.getHours()).padStart(2, '0') + ":" +
                String(date.getMinutes()).padStart(2, '0') + ":" +
                String(date.getSeconds()).padStart(2, '0');
        }
    });
});



var prescription_rows = 2;
$(document).on('click', '.add-record', function() {

    var table = document.getElementById("tableID");
    var table_len = (table.rows.length);

    var id = parseInt(table_len);

    var rowCount = $('#tableID tr').length;
    var cat_row = "";
    var medicine_row = "";
    var dose_row = "";
    var dose_interval_row = "";
    var dose_duration_row = "";
    var instruction_row = "";
    if (table_len == 0) {
        cat_row = "<label><?php echo $this->lang->line('medicine_category'); ?></label>";
        medicine_row = "<label><?php echo $this->lang->line('medicine'); ?></label>";
        dose_row = " <label><?php echo $this->lang->line("dose"); ?></label>";
        dose_interval_row = " <label><?php echo $this->lang->line("dose_interval"); ?></label>";
        dose_duration_row = " <label><?php echo $this->lang->line("dose_duration"); ?></label>";
        instruction_row = " <label><?php echo $this->lang->line("instruction"); ?></label>";
    }

    var div = "<input type='hidden' name='rows[]' value='" + prescription_rows +
        "' autocomplete='off'><div id=row1><div class='col-lg-2 col-md-4 col-sm-6 col-xs-6'><div '>" + cat_row +
        "<select class='form-control select2 medicine_category'  name='medicine_cat_" + prescription_rows +
        "'  id='medicine_cat" + prescription_rows +
        "'><option value='<?php echo set_value('medicine_category_id'); ?>'><?php echo $this->lang->line('select'); ?></option><?php foreach ($medicineCategory as $dkey => $dvalue) { ?><option value='<?php echo $dvalue["id"]; ?>'><?php echo $dvalue["medicine_category"] ?></option><?php } ?></select></div></div><div class='col-lg-2 col-md-4 col-sm-6 col-xs-6'><div>" +
        medicine_row + "<select class='form-control select2 medicine_name' data-rowId='" + prescription_rows +
        "'  name='medicine_" + prescription_rows + "' id='search-query" + prescription_rows +
        "'><option value='l'><?php echo $this->lang->line('select') ?></option></select><small id='stock_info_" +
        prescription_rows + "''> </small></div></div><div class='col-lg-2 col-md-4 col-sm-6 col-xs-6'><div>" +
        dose_row + "<select  class='form-control select2 medicine_dosage' name='dosage_" + prescription_rows +
        "' id='search-dosage" + prescription_rows +
        "'><option value='l'><?php echo $this->lang->line('select'); ?></option></select></div></div><div class='col-lg-2 col-md-4 col-sm-6 col-xs-6'><div>" +
        dose_interval_row + " <select  class='form-control select2 interval_dosage' name='interval_dosage_" +
        prescription_rows + "' id='search-interval-dosage" + prescription_rows +
        "'><option value='<?php echo set_value('interval_dosage_id'); ?>'><?php echo $this->lang->line('select'); ?></option><?php foreach ($intervaldosage as $dkey => $dvalue) { ?><option value='<?php echo $dvalue["id"]; ?>'><?php echo $dvalue["name"] ?></option><?php } ?></select></div></div><div class='col-lg-2 col-md-4 col-sm-6 col-xs-6'><div>" +
        dose_duration_row + "<select class='form-control select2 duration_dosage' name='duration_dosage_" +
        prescription_rows + "' id='search-duration-dosage" + prescription_rows +
        "'><option value='<?php echo set_value('duration_dosage_id'); ?>'><?php echo $this->lang->line('select') ?></option><?php foreach ($durationdosage as $dkey => $dvalue) { ?><option value='<?php echo $dvalue["id"]; ?>'><?php echo $dvalue["name"] ?></option><?php } ?></select></div></div><div class='col-lg-2 col-md-4 col-sm-6 col-xs-6'><div>" +
        instruction_row + "<textarea style='height:28px' name='instruction_" + prescription_rows +
        "' class=form-control id=description></textarea></div></div></div>";

    var table_row = "<tr id='row" + prescription_rows + "'><td>" + div +
        "</td><td><button type='button' onclick='delete_row(" + prescription_rows + ")' data-row-id='" +
        prescription_rows + "' class='closebtn delete_row'><i class='fa fa-remove'></i></button></td></tr>";
    //$(table).find('tbody').append(table_row);
    $('#tableID').append(table_row).find('.select2').select2();

    $('.modal-body', "#add_prescription").find('table#tableID').find('.select2').select2();
    prescription_rows++;
});

function delete_row(id) {
    var table = document.getElementById("tableID");
    var rowCount = table.rows.length;
    $("#row" + id).html("");
}


$(document).ready(function() {
    $("#add_timeline").on('submit', function(e) {
        e.preventDefault();
        var patient_id = $("#patient_id").val();
        var formData = new FormData(this);
        var title = formData.get('timeline_title');
        var date = formData.get('timeline_date');
        const requiredFields = ['timeline_title', 'timeline_date'];
        let missingFields = requiredFields.filter(field => !formData.get(field));
        if (missingFields.length > 0) {
            errorMsg(`The following fields are required: ${missingFields.join(', ')}`);
            return;
        }
        if (!/^[a-zA-Z0-9 ]+$/.test(title.trim())) {
            errorMsg("Invalid title format. Only alphanumeric characters and spaces are allowed.");
            return;
        }
        var fileInput = document.getElementById("timeline_doc_id").files[0];
        if (fileInput) {
            uploadFile(fileInput, function(uploadedUrl) {
                submitTimeline(uploadedUrl);
            });
        } else {
            submitTimeline("");
        }
    });
    function submitTimeline(documentUrl) {
        var patient_id = $("#patient_id").val();
        var formData = new FormData($("#add_timeline")[0]);
        var transformedData = {
            patient_id: parseInt(patient_id, 10),
            title: formData.get('timeline_title'),
            timeline_date: moment(formData.get('timeline_date'), "DD/MM/YYYY").format(
                "YYYY-MM-DD HH:mm:ss") || "",
            description: formData.get('timeline_desc') || " ",
            document: documentUrl,
            status: formData.get('visible_check'),
            generated_users_type: "staff",
            hospital_id: <?= $data['hospital_id'] ?>
        };
        sendAjaxRequest("<?= $api_base_url ?>internal-opd-timeline", "POST", transformedData, function(
        response) {
            handleResponse(response, false, 'printVisitBill');
        });
    }
});

$(document).ready(function() {
    $("#edit_timeline").on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData(this);
        var fileInput = document.getElementById('etimeline_doc_id').files[0];
        var documentUrl = $('#old_document').val();

        const requiredFields = ['timeline_title', 'timeline_date'];
        let missingFields = requiredFields.filter(field => !formData.get(field));
        var title = formData.get('timeline_title');

        if (missingFields.length > 0) {
            errorMsg(`The following fields are required: ${missingFields.join(', ')}`);
            return;
        }
        if (!/^[a-zA-Z0-9 ]+$/.test(title.trim())) {
            errorMsg("Invalid title format. Only alphanumeric characters and spaces are allowed.");
            return;
        }

        uploadFile(fileInput, function(uploadedUrl) {
            documentUrl = uploadedUrl || documentUrl || '';
            var transformedData = {
                id: $('#etimelineid').val(),
                patient_id: $('#epatientid').val(),
                title: formData.get('timeline_title'),
                timeline_date: moment(formData.get('timeline_date'), "DD/MM/YYYY").format("YYYY-MM-DD HH:mm:ss") || "",
                description: formData.get('timeline_desc'),
                document: documentUrl,
                status: formData.get('visible_check'),
                generated_users_type: "staff",
                hospital_id: <?= json_encode($data['hospital_id']) ?>
            };
            sendAjaxRequest("<?= $api_base_url ?>internal-opd-timeline/" + $('#etimelineid').val(),
                "PATCH", transformedData, function(response) {
                    handleResponse(response);
                });
        });
    });
});

function delete_timeline(id) {
    var patient_id = $("#patient_id").val();
    if (confirm('<?= $this->lang->line("delete_confirm") ?>')) {
        sendAjaxRequest(
            '<?= $api_base_url ?>internal-opd-timeline/' + id + "?hosId=" + <?= json_encode($data['hospital_id']) ?>,
            'DELETE',{},
            function(response) {
                handleResponse(response, false);
            }
        );
    }
}

function view_prescription(visitid) {
    $.ajax({
        url: '<?php echo base_url(); ?>admin/prescription/getcasesheetbyid/' + visitid,
        success: function(res) {
            $("#getdetails_prescription").html(res);
        },
        error: function() {
            alert("Fail")
        }
    });
    holdModal('prescriptionview');
}

function viewmanual_prescription(visitid) {
    $.ajax({
        url: '<?php echo base_url(); ?>admin/prescription/getPrescriptionmanual/' + visitid,
        success: function(res) {
            $("#getdetails_prescriptionmanual").html(res);
            $('#edit_deleteprescriptionmanual').html(
                "<?php if ($this->rbac->hasPrivilege('prescription', 'can_view')) { ?><a href='#'' onclick='printprescriptionmanual(" +
                visitid +
                ")'   data-original-title='<?php echo $this->lang->line('print'); ?>' title='<?php echo $this->lang->line('print'); ?>'><i class='fa fa-print'></i></a><?php } ?>"
            );
        },
        error: function() {
            alert("Fail")
        }
    });
    holdModal('prescriptionviewmanual');
}
</script>
<link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/animate.min.css">
<script type="text/javascript">
$(document).ready(function() {
    $(".dshow").click(function() {
        $('.sidebarlists').fadeIn(1000);
        $('.sidebarlists').show();
        $('.dshow').hide();
        $('.sidebarlists').removeClass('animated slideInRight faster').addClass(
            'animated slideInLeft faster');
        $('.dhide').show();
        $('.itemcol').removeClass('col-md-12').addClass('col-md-9');
    });

    $(".dhide").click(function() {
        $('.sidebarlists').fadeOut(1000);
        $('.sidebarlists').hide();
        $('.dshow').show();
        $('.dhide').hide();
        $('.sidebarlists').addClass('animated slideInLeft faster').removeClass(
            'animated slideInRight faster');
        $('.itemcol').addClass('col-md-12').removeClass('col-md-9');

    });
});
</script>
<?php
$data = $this->session->userdata('hospitaladmin');
$api_base_url = $this->config->item('api_base_url');
?>
<script>
$(document).ready(function() {
    $("#formrevisit").on('submit', function(e) {
        e.preventDefault();
        try {
            var formData = new FormData(this);
            var formObject = Object.fromEntries(formData.entries());
            var mappedObject = {
                "opd_details_id": parseInt(formObject.opd_id) || null,
                "cons_doctor": parseInt(formObject.consultant_doctor) || null,
                "case_type": $("#revisit_case").val() || " ",
                "appointment_date": formatDateTime(formObject.appointment_date) || null,
                "symptoms_type": parseInt(formObject.symptoms_type) || null,
                "symptoms": formObject.symptoms || "",
                "bp": parseFloat(formObject.bp) || null,
                "height": parseFloat(formObject.height) || null,
                "weight": parseFloat(formObject.weight) || null,
                "pulse": parseInt(formObject.pulse) || null,
                "temperature": parseFloat(formObject.temperature) || null,
                "respiration": parseInt(formObject.respiration) || null,
                "known_allergies": formObject.known_allergies || "",
                "patient_old": formObject.old_patient || "No",
                "casualty": formObject.casualty || "No",
                "refference": formObject.refference || "",
                "date": extractDate(formObject.appointment_date) || null,
                "note": formObject.note_remark || "",
                "payment_mode": formObject.payment_mode || "",
                "generated_by": <?= $data['id'] ?>,
                "live_consult": formObject.live_consult || "No",
                "can_delete": "Yes",
                "payment_date": extractDate(formObject.appointment_date) || null,
                "time": extractTime(formObject.appointment_date) || null,
                "standard_charge": parseFloat(formObject.standard_charge) || null,
                "charge_id": parseInt(formObject.charge_id) || null,
                "tpa_charge": parseFloat(formObject.tpa_charge) || null,
                "tax": parseFloat(formObject.percentage) || null,
                "apply_charge": parseFloat(formObject.apply_amount) || null,
                "amount": parseFloat(formObject.amount) || null,
                "patient_id": parseInt(formObject.id) || null,
                "Hospital_id": <?= $data['hospital_id'] ?>,
                "received_by_name": '<?= $data['username'] ?>',
            };

            var errorMessages = [];
            if (!mappedObject.cons_doctor) errorMessages.push("Consultant Doctor");
            if (!mappedObject.patient_id) errorMessages.push("Patient ID");
            if (!formObject.charge_category) errorMessages.push("Charge Category");
            if (!mappedObject.charge_id) errorMessages.push("Charge");
            if (!mappedObject.apply_charge) errorMessages.push("Applied Charge (INR)");
            if (!mappedObject.amount) errorMessages.push("Amount (INR)");
            if (!mappedObject.payment_mode) errorMessages.push("Payment Mode");
            if (errorMessages.length > 0) {
                const formattedMessage = `
                    <strong>Please fill all required fields:</strong>
                    <ul style="margin: 8px 0; padding-left: 20px;">
                        ${errorMessages.map(msg => `<li>${msg}</li>`).join('')}
                    </ul>`;
                errorMsg(formattedMessage);
                return;
            }
            sendAjaxRequest(
                '<?= $api_base_url ?>internal-opd-overview-visits',
                'POST',
                mappedObject,
                function(response) {
                    handleResponse(response);
                },
                function(xhr, status, error) {
                    try {
                        let responseText = xhr.responseText ? JSON.parse(xhr.responseText).message :
                            "An error occurred while submitting.";
                        errorMsg(responseText);
                    } catch {
                        errorMsg("An unexpected error occurred.");
                    }
                }
            );

        } catch (err) {
            errorMsg("An unexpected error occurred while processing the form.");
        }
    });

    function formatDateTime(dateTimeStr) {
        var dateObj = dateTimeStr ? new Date(dateTimeStr) : new Date();
        return dateObj.toISOString().replace('T', ' ').substring(0, 19);
    }

    function extractDate(dateTimeStr) {
        var dateObj = dateTimeStr ? new Date(dateTimeStr) : new Date();
        return dateObj.toISOString().substring(0, 10);
    }

    function extractTime(dateTimeStr) {
        var dateObj = dateTimeStr ? new Date(dateTimeStr) : new Date();
        return dateObj.toISOString().substring(11, 19);
    }
});
</script>
<script type="text/javascript">
$(document).ready(function(e) {
    $('.select2').select2();
});

function makeid(length) {
    var result = '';
    var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var charactersLength = characters.length;
    for (var i = 0; i < length; i++) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
}

function getRevisitRecord(visitid) {
    $('.select2-selection__rendered').html("");
    $.ajax({
        url: '<?php echo base_url(); ?>admin/patient/getvisitDetails',
        type: "POST",
        data: {
            visitid: visitid
        },
        dataType: 'json',
        success: function(data) {
            $("#patientname").html(data.patients_name);
            $('#guardian').html(data.guardian_name);
            $('#rgender').html(data.gender);
            $("#listnumber").html(data.mobileno);
            $("#remail").html(data.email);
            $("#rblood_group").html(data.blood_group_name);
            $("#raddress").html(data.address);
            $("#rmarital_status").html(data.marital_status);
            $("#rtpa_id").html(data.insurance_id);
            $("#rtpa_validity").html(data.tpa_validity);
            $("#ridentification_number").html(data.ABHA_number);
            $("#rallergies").html(data.any_known_allergies);
            $("#rnote").html(data.note);
            if (data.image) {
                $.ajax({
                    url: 'https://phr-api.plenome.com/file_upload/getDocs',
                    type: "POST",
                    contentType: "application/json",
                    data: JSON.stringify({
                        value: data.image
                    }),
                    success: function(decryptResponse) {
                        try {
                            if (decryptResponse && typeof decryptResponse === 'string' &&
                                decryptResponse.length > 0) {
                                let base64Image = "data:image/png;base64," + decryptResponse;
                                $("#patient_image").attr("src", base64Image);
                            } else {
                                $("#patient_image").attr("src", base_url + data.image +
                                    '<?php echo img_time(); ?>');
                            }
                        } catch (error) {
                            console.error("Error processing image:", error);
                            $("#patient_image").attr("src", base_url + data.image +
                                '<?php echo img_time(); ?>');
                        }
                    },
                    error: function() {
                        console.error("Error fetching image. Setting default image.");
                        $("#patient_image").attr("src", base_url + "default.png");
                    }
                });
            } else {
                console.warn("Image not found in response. Setting default image.");
                $("#patient_image").attr("src", base_url + "uploads/patient_images/no_image.png");
            }


            var date_format =
                '<?php echo $result = strtr($this->customlib->getHospitalDateFormat(), ['d' => 'dd', 'm' => 'MM', 'Y' => 'yyyy',]) ?>';
            var dob_format = new Date(data.dob).toString(date_format);

            $("#rage").html(data.patient_age);
            $("#revisit_id").val(data.id);
            $("#revisit_name").val(data.patient_name);
            $('#revisit_guardian').val(data.guardian_name);
            $("#revisit_contact").val(data.mobileno);
            // $("#revisit_date").val(data.appointment_date);
            $("#revisit_case").val(data.case_type);
            $("#pid").val(data.patientid);
            $("#revisit_refference").val(data.refference);
            $("#revisit_email").val(data.email);
            if (data.live_consult) {
                $("#live_consultvisit").val(data.live_consult);
            }
            $("#esymptoms").val(data.symptoms);
            $("#revisit_age").val(data.age);
            $("#revisit_month").val(data.month);
            $("#revisit_height").val(data.height);
            $("#revisit_weight").val(data.weight);
            $("#revisit_bp").val(data.bp);
            $("#revisit_pulse").val(data.pulse);
            $("#revisit_temperature").val(data.temperature);
            $("#revisit_respiration").val(data.respiration);
            $("#revisit_blood_group").val(data.blood_group);
            $("#revisi_tax").val(data.tax);
            $("#revisit_address").val(data.address);
            $("#revisit_note").val(data.note);
            $('select[id="revisit_old_patient"] option[value="' + data.old_patient + '"]').attr("selected",
                "selected");
            $('select[id="revisit_doctor"] option[value="' + data.cons_doctor + '"]').attr("selected",
                "selected");
            $('select[id="revisit_organisation"] option[value="' + data.organisation_id + '"]').attr(
                "selected", "selected");

            $('select[id="revisit_organisation"]').attr("disabled", true);
            holdModal('revisitModal');
        },
    })
}

function printprescription(visitid) {
    var base_url = '<?php echo base_url() ?>';
    $.ajax({
        url: base_url + 'admin/prescription/printPrescription',
        type: 'GET',
        data: {
            visitid: visitid
        },
        dataType: "json",
        success: function(result) {
            popup(result.page);
        }
    });
}

function printprescriptionmanual(visitid) {
    var base_url = '<?php echo base_url() ?>';
    $.ajax({
        url: base_url + 'admin/prescription/getPrescriptionmanual/' + visitid,
        type: 'POST',
        data: {
            payslipid: visitid,
            print: 'yes'
        },
        success: function(result) {
            $("#testdata").html(result);
            popup(result);
        }
    });
}

function popup(data) {
    var base_url = '<?php echo base_url() ?>';
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
    //Create a new HTML document.
    frameDoc.document.write('<html>');
    frameDoc.document.write('<head>');
    frameDoc.document.write('<title></title>');
    frameDoc.document.write('</head>');
    frameDoc.document.write('<body>');
    frameDoc.document.write(data);
    frameDoc.document.write('</body>');
    frameDoc.document.write('</html>');
    frameDoc.document.close();
    setTimeout(function() {
        window.frames["frame1"].focus();
        window.frames["frame1"].print();
        frame1.remove();
    }, 500);
    return true;
}

function deleteOpdPatientDiagnosis(patient_id, id) {
    if (confirm(<?php echo "'" . $this->lang->line('delete_confirm') . "'"; ?>)) {
        $.ajax({
            url: '<?php echo base_url(); ?>admin/patient/deleteOpdPatientDiagnosis/' + patient_id + '/' + id,
            success: function(res) {
                successMsg(<?php echo "'" . $this->lang->line('delete_message') . "'"; ?>);
                window.location.reload(true);
            }
        })
    }
}

function deleteOpdPatientCharge(id) {
    if (confirm("<?php echo $this->lang->line('delete_confirm'); ?>")) {
        try {
            sendAjaxRequest('<?= $api_base_url ?>internal-opd-charges/' + id + '?Hospital_id=' +
                <?= $data['hospital_id']; ?>,
                "DELETE", {},
                function(response) {
                    handleResponse(response);
                }
            );
        } catch (err) {
            console.error("AJAX Request Failed:", err);
            errorMsg("An unexpected error occurred while deleting the record.");
        }
    }
}


function deletePayment(id) {
    if (confirm(<?php echo "'" . $this->lang->line('delete_confirm') . "'"; ?>)) {
        $.ajax({
            url: '<?= $api_base_url ?>internal-opd-payment/' + id + '?hos_id=' + <?= $data['hospital_id'] ?>,
            type: 'DELETE',
            success: function(res) {
                successMsg(<?php echo "'" . $this->lang->line('delete_message') . "'"; ?>);
                window.location.reload(true);
            }
        });
    }
}


var attr = {};

$(document).ready(function(e) {
    $("#formdishrecord").on('submit', (function(e) {
        $("#formdishrecordbtn").button('loading');
        e.preventDefault();
        $.ajax({
            url: '<?php echo base_url(); ?>admin/patient/add_opddischarged_summary',
            type: "POST",
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                if (data.status == "fail") {
                    var message = "";
                    $.each(data.error, function(index, value) {
                        message += value;
                    });
                    errorMsg(message);
                } else {
                    successMsg(data.message);
                    window.location.reload(true);
                }
                $("#formdishrecordbtn").button('reset');
            },
            error: function() {

            }
        });
    }));
});

function getMedicineName(id) {
    // console.log(id);
    var category_selected = $("#medicine_cat" + id).val();
    var arr = category_selected.split('-');
    var category_set = arr[0];
    div_data = '';
    $("#search-query" + id).html("<option value='l'><?php echo $this->lang->line('loading') ?></option>");
    $('#search-query' + id).select2("val", +id);
    $.ajax({
        type: "POST",
        url: base_url + "admin/pharmacy/get_medicine_name",
        data: {
            'medicine_category_id': category_selected
        },
        dataType: 'json',
        success: function(res) {
            console.log(res);
            $.each(res, function(i, obj) {
                var sel = "";
                div_data += "<option value='" + obj.medicine_name + "'>" + obj.medicine_name +
                    "</option>";
            });

            $("#search-query" + id).html(
                "<option value=''><?php echo $this->lang->line('select'); ?></option>");
            $('#search-query' + id).append(div_data);
            $('#search-query' + id).select2("val", '');
            getMedicineDosage(id);
        }
    });
};

function getMedicineDosage(id) {
    var category_selected = $("#medicine_cat" + id).val();
    var arr = category_selected.split('-');
    var category_set = arr[0];
    div_data = '';
    $("#search-dosage" + id).html("<option value='l'><?php echo $this->lang->line('loading') ?></option>");
    $.ajax({
        type: "POST",
        url: base_url + "admin/pharmacy/get_medicine_dosage",
        data: {
            'medicine_category_id': category_selected
        },
        dataType: 'json',
        success: function(res) {
            $.each(res, function(i, obj) {
                var sel = "";
                div_data += "<option value='" + obj.dosage + "'>" + obj.dosage + "</option>";
            });
            $("#search-dosage" + id).html(
                "<option value=''><?php echo $this->lang->line('select'); ?></option>");
            $('#search-dosage' + id).append(div_data);
        }
    });
}

function getcharge_category(id) {
    var div_data = "";
    $('#charge_category').html("<option value='l'><?php echo $this->lang->line('loading') ?></option>");
    $("#charge_category").select2("val", 'l');

    $.ajax({
        url: '<?php echo base_url(); ?>admin/charges/get_charge_category',
        type: "POST",
        data: {
            charge_type: id
        },
        dataType: 'json',
        success: function(res) {
            $.each(res, function(i, obj) {
                var sel = "";
                div_data += "<option value='" + obj.name + "'>" + obj.name + "</option>";
            });
            $('#charge_category').html(
                "<option value=''><?php echo $this->lang->line('select'); ?></option>");
            $('#charge_category').append(div_data);
            $("#charge_category").select2("val", '');
        }
    });
}

function update_amount(apply_charge) {
    var apply_amount = apply_charge;
    var tax_percentage = $('#percentage').val();
    if (tax_percentage != '' && tax_percentage != 0) {
        apply_amount = (parseFloat(apply_charge) * tax_percentage / 100) + (parseFloat(apply_charge));
        $('#revisit_amount').val(apply_amount);
    } else {
        $('#revisit_amount').val(apply_amount);
    }
}

$(document).on('select2:select', '.charge', function() {
    var charge = $(this).val();
    var orgid = $("#revisit_organisation").val();

    $.ajax({
        url: '<?php echo base_url(); ?>admin/patient/getChargeById',
        type: "POST",
        data: {
            charge_id: charge,
            organisation_id: orgid
        },
        dataType: 'json',
        success: function(res) {
            if (res) {
                var tax = res.percentage;
                var quantity = $('#qty').val();
                $('#percentage').val(tax);
                $('#apply_chargevisit').val(parseFloat(res.standard_charge) * quantity);
                $('#standard_chargevisit').val(res.standard_charge);

                if (res.org_charge == null) {
                    if (res.percentage == null) {
                        apply_amount = parseFloat(res.standard_charge);
                    } else {
                        apply_amount = (parseFloat(res.standard_charge) * res.percentage / 100) + (
                            parseFloat(res.standard_charge));
                    }

                    $('#apply_chargevisit').val(res.standard_charge);
                    $('#revisit_amount').val(apply_amount.toFixed(2));
                    $('#paid_amount').val(apply_amount.toFixed(2));

                } else {
                    if (res.percentage == null) {
                        apply_amount = parseFloat(res.org_charge);
                    } else {
                        apply_amount = (parseFloat(res.org_charge) * res.percentage / 100) + (
                            parseFloat(res.org_charge));
                    }

                    $('#apply_chargevisit').val(res.org_charge);
                    $('#revisit_amount').val(apply_amount.toFixed(2));
                    $('#paid_amount').val(apply_amount.toFixed(2));

                }
            } else {

            }
        }
    });
});

$(document).on('change', '.charge_type', function() {
    var charge_type = $(this).val();
    $('.charge_category').html("<option value=''><?php echo $this->lang->line('loading') ?></option>");
    getcharge_category(charge_type, "");
});

function getcharge_category(charge_type, charge_category) {
    var div_data = "";
    if (charge_type != "") {

        $.ajax({
            url: base_url + 'admin/charges/get_charge_category',
            type: "POST",
            data: {
                charge_type: charge_type
            },
            dataType: 'json',
            success: function(res) {
                $.each(res, function(i, obj) {
                    var sel = "";
                    div_data += "<option value='" + obj.id + "'>" + obj.name + "</option>";
                });
                $('.charge_category').html(
                    "<option value=''><?php echo $this->lang->line('select'); ?></option>");
                $('.charge_category').append(div_data);
                $('.charge_category').select2("val", charge_category);
            }
        });
    }
}

$(document).on('change', '.editcharge_type', function() {
    var charge_type = $(this).val();
    $('.editcharge_category').html("<option value=''><?php echo $this->lang->line('loading') ?></option>");
    geteditcharge_category(charge_type, "");
});

function geteditcharge_category(charge_type, charge_category) {
    var div_data = "";
    if (charge_type != "") {

        $.ajax({
            url: base_url + 'admin/charges/get_charge_category',
            type: "POST",
            data: {
                charge_type: charge_type
            },
            dataType: 'json',
            success: function(res) {
                $.each(res, function(i, obj) {
                    var sel = "";
                    div_data += "<option value='" + obj.id + "'>" + obj.name + "</option>";
                });
                $('.editcharge_category').html(
                    "<option value=''><?php echo $this->lang->line('select'); ?></option>");
                $('.editcharge_category').append(div_data);
                $('.editcharge_category').select2("val", charge_category);
            }
        });
    }
}

$(document).on('select2:select', '.charge_category', function() {
    var charge_category = $(this).val();
    $('.charge').html("<option value=''><?php echo $this->lang->line('loading') ?></option>");
    $('.addcharge').html("<option value=''><?php echo $this->lang->line('loading') ?></option>");
    getchargecode(charge_category, "");
});

function getchargecode(charge_category, charge_id) {
    var div_data = "<option value=''><?php echo $this->lang->line('select'); ?></option>";
    if (charge_category != "") {
        $.ajax({
            url: base_url + 'admin/charges/getchargeDetails',
            type: "POST",
            data: {
                charge_category: charge_category
            },
            dataType: 'json',
            success: function(res) {
                $.each(res, function(i, obj) {
                    var sel = "";
                    div_data += "<option value='" + obj.id + "'>" + obj.name + "</option>";
                });
                $('.charge').html(div_data);
                $(".charge").select2("val", charge_id);
                $('.addcharge').html(div_data);
                $(".addcharge").select2("val", charge_id);
            }
        });
    }
}

$(document).on('select2:select', '.editcharge_category', function() {
    var charge_category = $(this).val();
    $('.charge').html("<option value=''><?php echo $this->lang->line('loading') ?></option>");
    $('.editcharge').html("<option value=''><?php echo $this->lang->line('loading') ?></option>");
    geteditchargecode(charge_category, "");
});

function geteditchargecode(charge_category, charge_id) {
    var div_data = "<option value=''><?php echo $this->lang->line('select'); ?></option>";
    if (charge_category != "") {
        $.ajax({
            url: base_url + 'admin/charges/getchargeDetails',
            type: "POST",
            data: {
                charge_category: charge_category
            },
            dataType: 'json',
            success: function(res) {
                $.each(res, function(i, obj) {
                    var sel = "";
                    div_data += "<option value='" + obj.id + "'>" + obj.name + "</option>";
                });
                $('.charge').html(div_data);
                $(".charge").select2("val", charge_id);
                $('.editcharge').html(div_data);
                $(".editcharge").select2("val", charge_id);
            }
        });
    }
}

$(document).ready(function(e) {
    $("#add_bill").on('submit', (function(e) {
        if (confirm('<?php echo $this->lang->line('are_you_sure') ?>')) {
            $("#save_button").button('loading');
            e.preventDefault();
            $.ajax({
                url: "<?php echo site_url("admin/payment/addopdbill") ?>",
                type: "POST",
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    if (data.status == "fail") {
                        var message = "";
                        $.each(data.error, function(index, value) {
                            message += value;
                        });
                        errorMsg(message);
                    } else {
                        successMsg(data.message);
                        window.location.reload = true;
                    }
                    $("#save_button").button('reset');
                    location.reload();
                },
                error: function(e) {
                    alert("Fail");
                    console.log(e);
                }
            });
        } else {
            return false;
        }

    }));
});


$(document).ready(function() {
    $("#add_charges button[type=submit]").click(function() {
        $("button[type=submit]", $(this).parents("form")).removeAttr("clicked");
        $(this).attr("clicked", "true");
    });

    $("#add_charges").on("submit", function(e) {
        e.preventDefault();
        var $this = $("button[type=submit][clicked=true]");
        var form = $(this);
        var form_data = form.serializeArray();
        var button_val = $this.attr("value");

        if (button_val === "add") {
            var errorMessages = [];
            if (!$("#add_charge_type").val()) errorMessages.push("Charge Type");
            if (!$("#charge_category").val()) errorMessages.push("Charge Category");
            if (!$("#charge_id").val()) errorMessages.push("Charge Name");
            if (!$("#qty").val()) errorMessages.push("Quantity");
            if (!$("#charge_date").val()) errorMessages.push("Date");
            if (errorMessages.length > 0) {
                errorMsg("Please fill in the following required fields: " + errorMessages.join(", "));
                return;
            }
            form_data.push({
                name: "add_type",
                value: button_val
            });
            $.ajax({
                url: "<?php echo base_url(); ?>admin/charges/add_opdcharges",
                type: "post",
                data: form_data,
                dataType: "json",
                beforeSend: function() {
                    $this.button("loading");
                },
                success: function(res) {
                    if (res.status === "fail") {
                        var message = res.error.join("");
                        errorMsg(message);
                    } else if (res.status === "new_charge") {
                        var data = res.data;
                        var row_id = makeid(8);
                        var charge =
                            '<tr id="' +
                            row_id +
                            '">' +
                            '<td>' +
                            data.date +
                            '<input type="hidden" name="pre_date[]" value="' +
                            data.date +
                            '">' +
                            "</td>" +
                            "<td>" +
                            data.charge_type_name +
                            "</td>" +
                            "<td>" +
                            data.charge_category +
                            "</td>" +
                            "<td>" +
                            data.charge_name +
                            '<input type="hidden" name="pre_charge_id[]" value="' +
                            data.charge_id +
                            '">' +
                            "<br><h6>" +
                            data.note +
                            '<input type="hidden" name="pre_note[]" value="' +
                            data.note +
                            '">' +
                            "</h6></td>" +
                            '<td class="text-right">' +
                            data.standard_charge +
                            '<input type="hidden" name="pre_standard_charge[]" value="' +
                            data.standard_charge +
                            '">' +
                            '<input type="hidden" name="pre_tax_percentage[]" value="' +
                            data.tax_percentage +
                            '">' +
                            "</td>" +
                            '<td class="text-right">' +
                            data.tpa_charge +
                            '<input type="hidden" name="pre_tpa_charges[]" value="' +
                            data.tpa_charge +
                            '">' +
                            "</td>" +
                            '<td class="text-right">' +
                            data.qty +
                            '<input type="hidden" name="pre_qty[]" value="' +
                            data.qty +
                            '">' +
                            "</td>" +
                            '<td class="text-right">' +
                            data.amount +
                            '<input type="hidden" name="pre_total[]" value="' +
                            data.amount +
                            '">' +
                            "</td>" +
                            '<td class="text-right">' +
                            data.tax +
                            '<input type="hidden" name="pre_tax[]" value="' +
                            data.tax +
                            '">' +
                            '<input type="hidden" name="pre_apply_charge[]" value="' +
                            data.apply_charge +
                            '">' +
                            "</td>" +
                            '<td class="text-right">' +
                            data.net_amount +
                            '<input type="hidden" name="pre_net_amount[]" value="' +
                            data.net_amount +
                            '">' +
                            "</td>" +
                            '<td><button type="button" class="closebtn delete_row" data-row-id="' +
                            row_id +
                            '" data-record-id="' +
                            data.charge_id +
                            '"><i class="fa fa-remove"></i></button></td>' +
                            "</tr>";
                        $("#preview_charges").append(charge);
                        charge_reset();
                        $this.button("reset");
                    }
                },
                error: function() {
                    $this.button("reset");
                },
                complete: function() {
                    $this.button("reset");
                },
            });
        } else {
            var dataArray = [];
            if ($("#preview_charges tr").length === 0) {
                errorMsg("Please add a charge first before submitting.");
                return;
            }
            $("#preview_charges tr").each(function() {
                var row = $(this);
                var originalDate = row.find('input[name="pre_date[]"]').val();
                var formattedDate = moment(originalDate, 'DD/MM/YYYY hh:mm A').format(
                    'YYYY-MM-DD HH:mm:ss');
                var payment_opd_id = $("#payment_opd_id").val();
                var rowData = {
                    date: formattedDate,
                    opd_id: payment_opd_id,
                    qty: parseInt(row.find('input[name="pre_qty[]"]').val()),
                    charge_id: parseInt(row.find('input[name="pre_charge_id[]"]').val()),
                    standard_charge: parseFloat(row.find(
                        'input[name="pre_standard_charge[]"]').val()),
                    tpa_charge: 0.0,
                    tax: parseFloat(row.find('input[name="pre_tax_percentage[]"]').val()),
                    apply_charge: parseFloat(row.find('input[name="pre_apply_charge[]"]')
                        .val()),
                    amount: parseFloat(row.find('input[name="pre_net_amount[]"]').val()),
                    note: row.find('input[name="pre_note[]"]').val(),
                    Hospital_id: <?= $data["hospital_id"] ?>,
                };
                dataArray.push(rowData);
            });

            try {
                sendAjaxRequest("<?= $api_base_url ?>internal-opd-charges", "POST", dataArray, function(
                    response) {
                    handleResponse(response);
                });
            } catch (err) {
                console.error("AJAX Request Failed:", err);
                errorMsg("An unexpected error occurred.");
            }

        }
    });

    $(document).on("click", ".delete_row", function() {
        var rowId = $(this).data("row-id");
        var result = confirm("<?php echo $this->lang->line('delete_confirm') ?>");
        if (result) {
            $("#" + rowId).remove();
        }
    });
});


function makeid(length) {
    var result = '';
    var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var charactersLength = characters.length;
    for (var i = 0; i < length; i++) {
        result += characters.charAt(Math.floor(Math.random() *
            charactersLength));
    }
    return result;
}

function charge_reset() {
    $("#charge_category").select2("val", '');
    $("#add_charge_type").select2("val", '');
    $("#charge_id").select2("val", '');
    $("#addstandard_charge").val('');
    $("#addscd_charge").val('');
    $("#qty").val('');
    $("#standard_charge").val('');
    $("#schedule_charge").val('');
    $("#charge_date").val('');
    $("#edit_note").val('');
    $("#charge_tax").val('');
    $("#tax").val('');
    $("#final_amount").val('');
    $("#apply_charge").val('');
    $("#opdcharges_tax").val('');
}

$(document).ready(function(e) {
    $("#edit_charges").on('submit', function(e) {
        e.preventDefault();
        let button_clicked = $("button[type=submit]", this);
        let formDataArray = $(this).serializeArray();
        let formDataObject = {};
        $.each(formDataArray, function(index, field) {
            formDataObject[field.name] = field.value;
        });
        let originalDate = formDataObject.date;
        let formattedDate = moment(originalDate, 'MM/DD/YYYY hh:mm A').format('YYYY-MM-DD HH:mm:ss');
        let jsonData = {
            "qty": formDataObject.qty,
            "date": formattedDate,
            "note": formDataObject.note,
            "standard_charge": parseFloat(formDataObject.standard_charge),
            "tpa_charge": 0.00,
            "apply_charge": parseFloat(formDataObject.apply_charge),
            "amount": parseFloat(formDataObject.amount),
            "tax": parseFloat(formDataObject.charge_tax).toFixed(2),
            "Hospital_id": <?= $data['hospital_id'] ?>
        };
        let id = $('#editpatient_charge_id').val();
        sendAjaxRequest(`<?= $api_base_url ?>internal-opd-charges/${id}`,
            "PATCH", jsonData,
            function(response) {
                handleResponse(response);
            });
    });
});

//payemnt add

$(document).ready(function() {
    $("#add_payment").on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        var paymentDate = formData.get('payment_date');
        var amount = parseFloat(formData.get('amount')) || 0;
        if (!paymentDate || amount <= 0) {
            errorMsg(!paymentDate ? 'Date is required.' : 'Amount should be greater than zero.');
            return;
        }
        var jsonData = {
            opd_id: parseInt(formData.get('opd_id')) || null,
            payment_date: moment(paymentDate, 'DD/MM/YYYY').format('YYYY-MM-DD HH:mm:ss'),
            amount: amount,
            payment_mode: (formData.get('payment_mode') || ''),
            note: formData.get('note') || '',
            Hospital_id: <?= $data['hospital_id'] ?>,
            patient_id: "",
            received_by_name: "<?= $data['username'] ?>"
        };
        if (jsonData.payment_mode === 'Online') {
            razorpaystartProcess(jsonData,"<?= $api_base_url ?>internal-opd-payment", "POST",function(
                response) {
                handleResponse(response);
            });
        } else {
            sendAjaxRequest("<?= $api_base_url ?>internal-opd-payment", "POST", jsonData, function(
                response) {
                handleResponse(response);
            });
        }
    });
});
function calculate() {
    var discount_percent = $("#discount_percent").val();
    var tax_percent = $("#tax_percent").val();
    var other_charge = $("#other_charge").val();
    var paid_amount = $("#paid_amountpa").val();
    var total_amount = $("#total_amount").val();
    var subtotal_amount = parseFloat(total_amount) + parseFloat(other_charge);

    if (discount_percent != '') {
        var discount = (subtotal_amount * discount_percent) / 100;
        $("#discount").val(discount.toFixed(2));
    } else {
        var discount = $("#discount").val();
    }

    if (tax_percent != '') {
        var tax = ((subtotal_amount - discount) * tax_percent) / 100;
        $("#tax").val(tax.toFixed(2));
    } else {
        var tax = $("#tax").val();
    }

    var gross_total = parseFloat(total_amount) + parseFloat(other_charge) + parseFloat(tax) - parseFloat(discount);
    var net_amount = parseFloat(total_amount) + parseFloat(other_charge) + parseFloat(tax) - parseFloat(discount);
    var net_amount_payble = parseFloat(net_amount) - parseFloat(paid_amount);
    $("#gross_total").val(gross_total.toFixed(2));
    $("#net_amount").val(net_amount.toFixed(2));
    $("#grass_amount").val(net_amount.toFixed(2));
    $("#grass_amount_span").html(net_amount.toFixed(2));
    $("#net_amount_span").html(net_amount_payble.toFixed(2));
    $("#net_amount_payble").val(net_amount_payble.toFixed(2));
    $("#save_button").show();
    $("#printBill").show();
}

function printBill(patientid, opdid) {
    var total_amount = $("#total_amount").val();
    var discount = $("#discount").val();
    var other_charge = $("#other_charge").val();
    var gross_total = $("#gross_total").val();
    var tax = $("#tax").val();
    var net_amount = $("#net_amount").val();
    var status = $("#status").val();
    var base_url = '<?php echo base_url() ?>';
    $.ajax({
        url: base_url + 'admin/payment/getOPDBill/',
        type: 'POST',
        data: {
            patient_id: patientid,
            opdid: opdid,
            total_amount: total_amount,
            discount: discount,
            other_charge: other_charge,
            gross_total: gross_total,
            tax: tax,
            net_amount: net_amount,
            status: status
        },
        success: function(result) {
            $("#testdata").html(result);
            popup(result);
        }
    });
}
</script>
<script type="text/javascript">
$(document).on('change', '.chgstatus_dropdown', function() {
    $(this).parent('form.chgstatus_form').submit()

});

$("form.chgstatus_form").submit(function(e) {

    e.preventDefault(); // avoid to execute the actual submit of the form.

    var form = $(this);
    var url = form.attr('action');

    $.ajax({
        type: "POST",
        url: url,
        data: form.serialize(), // serializes the form's elements.
        dataType: "JSON",
        success: function(data) {
            if (data.status == 0) {
                var message = "";
                $.each(data.error, function(index, value) {
                    message += value;
                });
                errorMsg(message);
            } else {
                successMsg(data.message);
                window.location.reload(true);
            }
        }
    });
});

$(".addcharges").click(function() {
    $('#add_charges').trigger("reset");
    $('#select2-charge_category-container').html("");
    $('#select2-code-container').html("");
});

$(".revisitrecheckup").click(function() {
    $('#formrevisit').trigger("reset");
});

$("#myPaymentModal").on('hidden.bs.modal', function(e) {
    $(".filestyle").next(".dropify-clear").trigger("click");
    $('.cheque_div').css("display", "none");
    // $('form#add_payment').find('input:text, input:password, input:file, textarea').val('');
    $('form#add_payment').find('select option:selected').removeAttr('selected');
    $('form#add_payment').find('input:checkbox, input:radio').removeAttr('checked');
});

$(document).on('click', '.addpayment', function() {
    $('#myPaymentModal').modal('show');
});

$(".adddiagnosis").click(function() {
    $('#form_diagnosis').trigger("reset");
    $(".dropify-clear").trigger("click");
});

$(".addtimeline").click(function() {
    $('#add_timeline').trigger("reset");
    $(".dropify-clear").trigger("click");
});

$(".prescription").click(function() {
    $('#form_prescription').trigger("reset");
    $('#select2-medicine_cat0-container').html('');
    $('#select2-search-query0-container').html('');
    $('#select2-search-dosage0-container').html('');
    var table = document.getElementById("tableID");
    var table_len = (table.rows.length);
    for (i = 1; i < table_len; i++) {
        delete_row(i);
    }
});
</script>

<script type="text/javascript">
$(document).ready(function() {
    $("#radiologyOpt").select2({

        placeholder: 'Select',
        allowClear: false,
        minimumResultsForSearch: 2
    });
    $("#pathologyOpt").select2({

        placeholder: 'Select',
        allowClear: false,
        minimumResultsForSearch: 2
    });
});
</script>
<script type="text/javascript">
$(document).on('change', '.payment_mode', function() {
    var mode = $(this).val();

    if (mode == "Cheque") {

        $('.filestyle', '#myPaymentModal').dropify();
        $(".date").trigger("change");
        $('.cheque_div').css("display", "block");

    } else {

        $('.cheque_div').css("display", "none");
    }
});

$(document).on('change', '.visit_payment_mode', function() {
    var mode = $(this).val();

    if (mode == "Cheque") {

        $('.filestyle', '#myPaymentModal').dropify();
        $(".date").trigger("change");
        $('.cheque_div').css("display", "block");

    } else {

        $('.cheque_div').css("display", "none");
    }
});

$(document).on('select2:select', '.medicine_category', function() {
    getMedicine($(this), $(this).val(), 0);
    selected_medicine_category_id = $(this).val();
    var medicine_dosage = getDosages(selected_medicine_category_id);
    $(this).closest('tr').find('.medicine_dosage').html(medicine_dosage);
});

function getMedicine(med_cat_obj, val, medicine_id) {
    var medicine_colomn = med_cat_obj.closest('tr').find('.medicine_name');
    medicine_colomn.html("");
    $.ajax({
        url: '<?php echo base_url(); ?>admin/pharmacy/get_medicine_name',
        type: "POST",
        data: {
            medicine_category_id: val
        },
        dataType: 'json',
        beforeSend: function() {
            medicine_colomn.html("<option value=''><?php echo $this->lang->line('loading') ?></option>");

        },
        success: function(res) {
            var div_data = "<option value=''><?php echo $this->lang->line('select'); ?></option>";
            $.each(res, function(i, obj) {
                var sel = "";
                if (medicine_id == obj.id) {
                    sel = "selected";
                }
                div_data += "<option value=" + obj.id + " " + sel + ">" + obj.medicine_name +
                    "</option>";

            });

            medicine_colomn.html(div_data);
            medicine_colomn.select2("val", medicine_id);

        }
    });
}
</script>

<script type="text/javascript">
function getDosages(medicine_category_id) {
    var dosage_opt = "<option value=''><?php echo $this->lang->line('select') ?></option>";
    var sss = '<?php echo json_encode($category_dosage); ?>';
    var aaa = JSON.parse(sss);
    if (aaa[medicine_category_id]) {
        $.each(aaa[medicine_category_id], function(key, item) {
            dosage_opt += "<option value='" + item.id + "'>" + item.dosage + "</option>";
        });
    }
    return dosage_opt;
}
</script>

<script type="text/javascript">
$(document).on('click', '.print_visit', function() {
    var $this = $(this);
    var record_id = $this.data('recordId')
    $this.button('loading');
    $.ajax({
        url: '<?php echo base_url(); ?>admin/patient/printVisit',
        type: "POST",
        data: {
            'visit_detail_id': record_id
        },
        dataType: 'json',
        beforeSend: function() {
            $this.button('loading');

        },
        success: function(res) {
            popup(res.page);
        },
        error: function(xhr) { // if error occured
            alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
            $this.button('reset');

        },
        complete: function() {
            $this.button('reset');

        }
    });
});

$(document).on('click', '.print_trans', function() {
    var $this = $(this);
    var record_id = $this.data('recordId')
    $this.button('loading');
    $.ajax({
        url: '<?php echo base_url(); ?>admin/transaction/printTransaction',
        type: "POST",
        data: {
            'id': record_id
        },
        dataType: 'json',
        beforeSend: function() {
            $this.button('loading');

        },
        success: function(res) {
            popup(res.page);
        },
        error: function(xhr) { // if error occured
            alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
            $this.button('reset');

        },
        complete: function() {
            $this.button('reset');
        }
    });
});

$(document).on('click', '.print_charge', function() {

    var $this = $(this);
    var record_id = $this.data('recordId')
    $this.button('loading');
    $.ajax({
        url: '<?php echo base_url(); ?>admin/patient/printCharge',
        type: "POST",
        data: {
            'id': record_id,
            'type': 'opd'
        },
        dataType: 'json',
        beforeSend: function() {
            $this.button('loading');

        },
        success: function(res) {
            popup(res.page);
        },
        error: function(xhr) { // if error occured
            alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
            $this.button('reset');

        },
        complete: function() {
            $this.button('reset');

        }
    });
});

$(document).on('change keyup input paste', '#qty', function() {
    var quantity = $(this).val();
    var standard_charge = $('#addstandard_charge').val();
    var schedule_charge = $('#addscd_charge').val();
    var tax_percent = $('#charge_tax').val();
    var total_charge = (schedule_charge == "") ? standard_charge : schedule_charge;
    var apply_charge = isNaN(parseFloat(total_charge) * parseFloat(quantity)) ? 0 : parseFloat(total_charge) *
        parseFloat(quantity);
    $('#apply_charge').val(apply_charge.toFixed(2));
    var discount_percent = 0;
    var discount_amount = isNaN((apply_charge * discount_percent) / 100) ? 0 : (apply_charge *
        discount_percent) / 100;
    var final_amount = apply_charge - discount_amount;
    $('#discount').val(discount_amount);
    $('#opdcharges_tax').val(((final_amount * tax_percent) / 100).toFixed(2));
    $('#final_amount').val((final_amount + ((final_amount * tax_percent) / 100)).toFixed(2));
});

$(document).on('change keyup input paste', '#editqty', function() {
    var quantity = $(this).val();
    var standard_charge = $('#editstandard_charge').val();
    var schedule_charge = $('#editscd_charge').val();
    var tax_percent = $('#editcharge_tax').val();
    var total_charge = (schedule_charge == "") ? standard_charge : schedule_charge;
    var apply_charge = isNaN(parseFloat(total_charge) * parseFloat(quantity)) ? 0 : parseFloat(total_charge) *
        parseFloat(quantity);
    $('#editapply_charge').val(apply_charge.toFixed(2));
    var discount_percent = 0;
    var discount_amount = isNaN((apply_charge * discount_percent) / 100) ? 0 : (apply_charge *
        discount_percent) / 100;
    var final_amount = apply_charge - discount_amount;
    $('#editdiscount').val(discount_amount);
    $('#edittax').val(((final_amount * tax_percent) / 100).toFixed(2));
    $('#editfinal_amount').val((final_amount + ((final_amount * tax_percent) / 100)).toFixed(2));
});
</script>

<script type="text/javascript">
$(document).on('click', '.edit_charge', function() {
    var edit_charge_id = $(this).data('recordId');
    var createModal = $('#edit_chargeModal');
    var $this = $(this);
    $this.button('loading');
    $.ajax({
        url: base_url + 'admin/patient/getCharge',
        type: "POST",
        data: {
            'id': edit_charge_id
        },
        dataType: 'json',
        beforeSend: function() {
            $this.button('loading');
        },
        success: function(res) {
            console.log(res.result);
            $('#editstandard_charge').val(res.result.standard_charge);
            if (res.result.tpa_charge > 0) {
                $('#editscd_charge').val(res.result.tpa_charge);
            }
            if (res.appoimentcharges == res.result.id) {
                $('#editqty').val(res.result.qty).prop('readonly', true);
            } else {
                $('#editqty').val(res.result.qty).prop('readonly', false);
            }

            $('#editqty').val(res.result.qty);
            $('#editcharge_tax').val(res.result.percentage);
            $('#editapply_charge').val(res.result.apply_charge);
            $('#editfinal_amount').val(res.result.amount);
            $('#editcharge_date').val(res.result.charge_date);
            $('#editorg_id').val(res.result.org_charge_id);
            $('#editpatient_charge_id').val(res.result.id);
            var tax_charge = (res.result.apply_charge * res.result.percentage) / 100;
            $('#edittax').val(tax_charge.toFixed(2));
            $('#edit_note').val(res.result.note);
            $('#editcharge_type').select2('val', res.result.charge_type_master_id);
            $('#edit_chargeModal').modal({
                backdrop: 'static'
            });
            geteditcharge_category(res.result.charge_type_master_id, res.result.charge_category_id);
            geteditchargecode(res.result.charge_category_id, res.result.charge_id);
        },
        error: function(xhr) { // if error occured
            alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
            $this.button('reset');

        },
        complete: function() {
            $this.button('reset');

        }
    });
});

$(document).on('select2:select', '.addcharge', function() {
    var charge = $(this).val();
    var orgid = $('#editorganisation_id').val();
    $('#qty').val('1');
    $.ajax({
        url: '<?php echo base_url(); ?>admin/patient/getChargeById',
        type: "POST",
        data: {
            charge_id: charge,
            organisation_id: orgid
        },
        dataType: 'json',
        success: function(res) {
            if (res) {
                var quantity = $('#qty').val();
                $('#apply_charge').val(parseFloat(res.standard_charge) * quantity);
                $('#addstandard_charge').val(res.standard_charge);
                $('#addscd_charge').val(res.org_charge);
                $('#charge_tax').val(res.percentage);
                var standard_charge = res.standard_charge;
                var schedule_charge = res.org_charge;
                var discount_percent = 0;
                var total_charge = (schedule_charge == null) ? standard_charge : schedule_charge;
                var apply_charge = isNaN(parseFloat(total_charge) * parseFloat(quantity)) ? 0 :
                    parseFloat(total_charge) * parseFloat(quantity);
                var discount_amount = (apply_charge * discount_percent) / 100;
                $('#apply_charge').val(apply_charge.toFixed(2));
                var final_amount = apply_charge - discount_amount;
                $('#opdcharges_tax').val(((final_amount * res.percentage) / 100).toFixed(2));
                $('#final_amount').val((final_amount + ((final_amount * res.percentage) / 100))
                    .toFixed(2));
            }
        }
    });
});

$(document).on('select2:select', '.editcharge', function() {
    var charge = $(this).val();
    var orgid = $('#organisation_id').val();

    $.ajax({
        url: '<?php echo base_url(); ?>admin/patient/getChargeById',
        type: "POST",
        data: {
            charge_id: charge,
            organisation_id: orgid
        },
        dataType: 'json',
        success: function(res) {
            if (res) {
                var quantity = $('#editqty').val();
                $('#editapply_charge').val(parseFloat(res.standard_charge) * quantity);
                $('#editstandard_charge').val(res.standard_charge);
                $('#editscd_charge').val(res.org_charge);
                $('#editcharge_tax').val(res.percentage);
                var standard_charge = res.standard_charge;
                var schedule_charge = res.org_charge;
                var discount_percent = 0;
                var total_charge = (schedule_charge == null) ? standard_charge : schedule_charge;
                var apply_charge = isNaN(parseFloat(total_charge) * parseFloat(quantity)) ? 0 :
                    parseFloat(total_charge) * parseFloat(quantity);
                var discount_amount = (apply_charge * discount_percent) / 100;
                $('#editapply_charge').val(apply_charge.toFixed(2));
                var final_amount = apply_charge - discount_amount;
                $('#edittax').val(((final_amount * res.percentage) / 100).toFixed(2));
                $('#editfinal_amount').val((final_amount + ((final_amount * res.percentage) / 100))
                    .toFixed(2));
            }
        }
    });
});

$(document).on('change', '.death_status', function() {
    var status = $(this).val();
    if (status == "1") {
        $('.filestyle', '#addPaymentModal').dropify();
        $('.filestyle', '#add_refund').dropify();
        $('.death_status_div').css("display", "block");
        $('.reffer_div').css("display", "none");
    } else if (status == "2") {
        $('.reffer_div').css("display", "block");
        $('.death_status_div').css("display", "none");
    } else {
        $('.reffer_div').css("display", "none");
        $('.death_status_div').css("display", "none");
    }
});

$(document).on('click', '.patient_discharge', function() {
    var case_reference_id = "<?php echo $case_reference_id; ?>";
    var payment_modal = $('#patient_discharge');
    payment_modal.addClass('modal_loading');
    payment_modal.modal('show');
    $.ajax({
        url: base_url + 'admin/bill/patient_discharge/' + case_reference_id,
        type: "POST",
        data: {
            'module_type': 'opd'
        },
        dataType: 'json',
        beforeSend: function() {

        },
        success: function(data) {

            $('.modal-body', payment_modal).html(data.page);
            $('.filestyle', '#patient_discharge').dropify();
            $('.date', '#patient_discharge').trigger("change");
            payment_modal.removeClass('modal_loading');
        },

        error: function(xhr) { // if error occured
            alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");

        },
        complete: function() {
            payment_modal.removeClass('modal_loading');

        }
    });
});

$(document).on('submit', '#opd_discharge', function(e) {
    e.preventDefault();
    var btn = $("button[type=submit]");
    btn.button('loading');

    var form = $(this);
    var jsonData = {};

    jsonData.opd_details_id = parseInt(form.find('input[name="opd_id"]').val());
    jsonData.discharge_by = <?= $data['id'] ?>;
    jsonData.discharge_date = form.find('input[name="discharge_date"]').val().split(' ')[0].replace(
        /(\d{2})\/(\d{2})\/(\d{4})/, '$3-$1-$2');
    jsonData.discharge_status = parseInt($('#death_status_id').val());

    if (jsonData.discharge_status == 1) {
        jsonData.death_date = form.find('input[name="death_date"]').val().split(' ')[0].replace(
            /(\d{2})\/(\d{2})\/(\d{4})/, '$3-$1-$2');
        let fileInput = document.getElementById('document').files[0];
        jsonData.operation = $('#operation').val();
        jsonData.diagnosis = $('#diagnosis').val();
        jsonData.investigations = $('#investigations').val();
        jsonData.treatment_home = $('#treatment_home').val();
        jsonData.note = form.find('textarea[name="note"]').val();
        jsonData.is_active = "no";
        jsonData.discharged = "yes";
        jsonData.Hospital_id = <?= $data['hospital_id'] ?>;
        jsonData.death_report = $('#death_report').val();
        var documentUrl = $('#filedataalread').val();

        if (fileInput) {
            var fileUploadData = new FormData();
            fileUploadData.append('file', fileInput);
            $.ajax({
                url: 'https://phr-api.plenome.com/file_upload',
                type: 'POST',
                data: fileUploadData,
                contentType: false,
                processData: false,
                success: function(response) {
                    jsonData.attachment = response.data;
                    jsonData.attachment_name = response.data;
                    submitForm(jsonData);
                },
                error: function() {
                    jsonData.documents = documentUrl;
                    submitForm(jsonData);
                }
            });
        } else {
            jsonData.attachment = 'none.img';
            jsonData.attachment_name = 'none';
            submitForm(jsonData);
        }
    } else if (jsonData.discharge_status == 2) {
        jsonData.refer_date = form.find('input[name="referral_date"]').val().split(' ')[0].replace(
            /(\d{2})\/(\d{2})\/(\d{4})/, '$3-$1-$2');
        jsonData.refer_to_hospital = form.find('input[name="hospital_name"]').val();
        jsonData.reason_for_referral = form.find('input[name="reason_for_referral"]').val();
        jsonData.operation = $('#operation').val();
        jsonData.diagnosis = $('#diagnosis').val();
        jsonData.investigations = $('#investigations').val();
        jsonData.treatment_home = $('#treatment_home').val();
        jsonData.note = form.find('textarea[name="note"]').val();
        jsonData.is_active = "no";
        jsonData.discharged = "yes";
        jsonData.Hospital_id = <?= $data['hospital_id'] ?>;
        submitForm(jsonData);
    } else if (jsonData.discharge_status == 3) {
        jsonData.operation = $('#operation').val();
        jsonData.diagnosis = $('#diagnosis').val();
        jsonData.investigations = $('#investigations').val();
        jsonData.treatment_home = $('#treatment_home').val();
        jsonData.note = form.find('textarea[name="note"]').val();
        jsonData.is_active = "no";
        jsonData.discharged = "yes";
        jsonData.Hospital_id = <?= $data['hospital_id'] ?>;
        submitForm(jsonData);
    }
});

function submitForm(data) {
    // console.log(JSON.stringify(data, null, 2));
    $.ajax({
        url: '<?= $api_base_url ?>discharge-patient-opd-module',
        type: "POST",
        data: JSON.stringify(data),
        contentType: 'application/json',
        dataType: 'json',
        success: function(response) {
            let data = response[0]["data "];
            if (data?.status.toLowerCase() == "fail") {
                let message = data.error?.join(", ") || "An error occurred.";
                errorMsg(message);
            } else if (data?.status.toLowerCase() == "success") {
                successMsg(data.messege || "Operation completed successfully.");
                window.location.reload(true);
            }
        },
        error: function() {
            errorMsg("An error occurred while submitting the form.");
        },
        complete: function() {
            $("button[type=submit]").button('reset');
        }
    });
}

$(document).on('click', '.print_dischargecard1', function() {
    var $this = $(this);
    var record_id = $this.data('recordId');
    var case_id = $this.data('case_id');
    $this.button('loading');
    $.ajax({
        url: '<?php echo base_url(); ?>admin/bill/print_dischargecard',
        type: "POST",
        data: {
            'id': record_id,
            'case_id': case_id,
            'module_type': 'opd'
        },
        dataType: 'json',
        beforeSend: function() {
            $this.button('loading');

        },
        success: function(res) {
            popup(res.page);
        },
        error: function(xhr) { // if error occured
            alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
            $this.button('reset');

        },
        complete: function() {
            $this.button('reset');
        }
    });
});

$(document).on('click', '.viewot', function() {
    var $this = $(this);
    var record_id = $this.data('recordId');
    $this.button('loading');
    $.ajax({
        url: base_url + 'admin/operationtheatre/otdetails',
        type: "POST",
        data: {
            ot_id: record_id
        },
        dataType: 'json',
        beforeSend: function() {
            $this.button('loading');

        },
        success: function(data) {
            $('#view_ot_modal').modal('show');
            $('#show_ot_data').html(data.page);
            $('#action_detail_modal').html(data.actions);
        },
        error: function(xhr) { // if error occured
            alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
            $this.button('reset');

        },
        complete: function() {
            $this.button('reset');
        }
    });
});

$(document).ready(function(e) {
    $('#patient_discharge').modal({
        backdrop: 'static',
        keyboard: false,
        show: false
    });
});
</script>
<script>
function getcategory(id, operation = null) {
    var div_data = "";
    $('#operation_name').html("<option value='l'><?php echo $this->lang->line('loading') ?></option>");
    $.ajax({
        url: '<?php echo base_url(); ?>admin/operationtheatre/getoperationbycategory',
        type: "POST",
        data: {
            id: id
        },
        dataType: 'json',
        async: false,
        success: function(res) {
            $.each(res, function(i, obj) {
                var sel = "";
                if ((operation != '') && (operation == obj.id)) {
                    sel = "selected";
                }
                div_data += "<option value=" + obj.id + " " + sel + ">" + obj.operation +
                    "</option>";
            });
            $("#operation_name").html("<option value=''>Select</option>");
            $('#operation_name').append(div_data);
            $("#operation_name").select2().select2('val', operation);
            if (operation != "") {
                $("#eoperation_name").html("<option value=''>Select</option>");
                $('#eoperation_name').append(div_data);
                $("#eoperation_name").select2().select2('val', operation);
            }
        }
    });
}
</script>

<script>
$(document).on('click', '.view_report', function() {
    var id = $(this).data('recordId');
    var lab = $(this).data('typeId');
    getinvestigationparameter(id, $(this), lab);
});

function getinvestigationparameter(id, btn_obj, lab) {
    var modal_view = $('#viewDetailReportModal');
    var $this = btn_obj;
    $.ajax({
        url: base_url + 'admin/patient/getinvestigationparameter',
        type: "POST",
        data: {
            'id': id,
            'lab': lab
        },
        dataType: 'json',
        beforeSend: function() {
            $this.button('loading');
            modal_view.addClass('modal_loading');

        },
        success: function(data) {
            $('#viewDetailReportModal .modal-body').html(data.page);
            $('#viewDetailReportModal #action_detail_report_modal').html(data.actions);
            $('#viewDetailReportModal').modal('show');
            modal_view.removeClass('modal_loading');
        },

        error: function(xhr) { // if error occured
            alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
            $this.button('reset');
            modal_view.removeClass('modal_loading');
        },
        complete: function() {
            $this.button('reset');
            modal_view.removeClass('modal_loading');

        }
    });
}
</script>

<script type="text/javascript">
$(document).on('click', '.print_bill', function() {
    var id = $(this).data('recordId');

    var $this = $(this);
    var lab = $(this).data('typeId');
    $.ajax({
        url: base_url + 'admin/patient/printpathoparameter',
        type: "POST",
        data: {
            'id': id,
            'lab': lab
        },
        dataType: 'json',
        beforeSend: function() {
            $this.button('loading');
        },
        success: function(data) {
            popup(data.page);
        },

        error: function(xhr) { // if error occured
            alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
            $this.button('reset');

        },
        complete: function() {
            $this.button('reset');

        }
    });
});
</script>

<script>
$(document).on('change', '.findingtype', function() {
    $this = $(this);

    var section_ul = $(this).closest('div.row').find('ul.section_ul');
    var finding_id = $(this).val();
    div_data = "";
    $.ajax({
        type: 'POST',
        url: base_url + 'admin/patient/findingbycategory',
        data: {
            'finding_id': finding_id
        },
        dataType: 'JSON',

        beforeSend: function() {
            // setting a timeout
            $('ul.section_ul').find('li:not(:first-child)').remove();
        },
        success: function(data) {
            section_ul.append(data.record);

        },
        error: function(xhr) { // if error occured
            alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");

        },
        complete: function() {

        }
    });
});

$(document).on('change', '.findinghead', function() {

    $this = $(this);
    var head_id = $(this).val();
    div_data = "";
    $.ajax({
        type: 'POST',
        url: base_url + 'admin/patient/getfinding',
        data: {
            'head_id': head_id
        },
        success: function(res) {
            $("#finding_description").val(res);

        },
    });
});

$('.close_button').click(function() {
    $('#form_operationtheatre')[0].reset();
    $("#operation_category").select2().select2('val', '');
    $("#operation_name").select2().select2('val', '');
    $("#consultant_doctorid").select2().select2('val', '');
})
</script>

<script type="text/javascript">
function delete_prescription(visitid) {
    if (confirm('Are you sure')) {
        $.ajax({
            url: '<?php echo base_url(); ?>admin/prescription/deletePrescription/' + visitid,
            success: function(res) {
                window.location.reload(true);
            },
            error: function() {
                alert("Fail")
            }
        });
    }
}

$(document).ready(function(e) {
    $('#viewDetailReportModal').modal({
        backdrop: 'static',
        keyboard: false,
        show: false
    });
});

function discharge_revert(case_id) {
    var base_url = '<?php echo base_url() ?>';
    $.ajax({
        type: 'POST',
        url: base_url + 'admin/bill/discharge_revert',
        data: {
            'module_type': 'opd',
            'case_id': case_id
        },
        dataType: 'json',

        success: function(res) {
            if (res.status == 'success') {
                successMsg(res.message);
                window.location.reload(true);
            } else {
                errorMsg(res.message);
            }
        },
    });
}

$(document).on('change', '.revisit_payment_mode', function() {
    var mode = $(this).val();
    if (mode == "Cheque") {
        $('.filestyle', '#revisitModal').dropify();
        $(".date").trigger("change");
        $('.revisit_cheque_div').css("display", "block");

    } else {
        $('.revisit_cheque_div').css("display", "none");
    }
});
</script>

<script type="text/javascript">
$(".patient_dob").on('changeDate', function(event, date) {
    var birth_date = $(".patient_dob").val();

    $.ajax({
        url: '<?php echo base_url(); ?>admin/patient/getpatientage',
        type: "POST",
        dataType: "json",
        data: {
            birth_date: birth_date
        },
        success: function(data) {
            $('.patient_age_year').val(data.year);
            $('.patient_age_month').val(data.month);
            $('.patient_age_day').val(data.day);
        }
    });
});
</script>
<script>
$(document).on('click', '.editpayment', function() {
    var $this = $(this);
    var record_id = $this.data('recordId');
    var amount = $this.data('paymentAmount');
    $("#edit_payment").val(amount);
    $("#edit_payment_id").val(record_id);
    $.ajax({
        url: '<?php echo base_url(); ?>admin/patient/getopdpaymentdetails',
        type: 'post',
        data: {
            'payment_id': record_id
        },
        dataType: 'json',
        success: function(data) {
            let payment_type = data.payment_mode;
            payment_type = payment_type.charAt(0).toUpperCase() + payment_type.slice(1);
            if (payment_type == 'Upi') {
                payment_type = 'UPI';
            } else if (payment_type == 'Transfer_to_bank_account') {
                payment_type = 'transfer_to_bank_account';
            }
            // console.log(payment_type); 


            $("#payment_mode").val(payment_type).prop('selected');

            $(".payment_mode").trigger('change');
            $("#edit_cheque_no").val(data.cheque_no);
            $("#edit_cheque_date").val(data.cheque_date);
            $("#payment_date").val(data.payment_date);
            $("#edit_payment_note").val(data.note);
            $("#edit_payment_id").val(data.edit_payment_id);
        }
    });

    $('#editpayment_modal').modal('show');
    //$this.button('loading');

});
</script>

<script>
$(document).ready(function() {
    $("#editpaymentform").on('submit', function(e) {
        e.preventDefault();
        $("#editpaymentbtn").button('loading');

        var formData = new FormData(this);
        var formDataObject = {};
        formData.forEach(function(value, key) {
            formDataObject[key] = value;
        });

        var formattedData = {
            "payment_date": moment(formDataObject["payment_date"], 'MM/DD/YYYY hh:mm A').format(
                'YYYY-MM-DD HH:mm:ss'),
            "amount": parseFloat(formDataObject["amount"]),
            "payment_mode": formDataObject["payment_mode"].toLowerCase(),
            "note": formDataObject["note"],
            "Hospital_id": <?= $data['hospital_id'] ?>,
            "received_by_name": '<?= $data['username'] ?>',
        };

        // console.log("Formatted Data:", JSON.stringify(formattedData, null, 2));
        let editid = $('#edit_payment_id').val();

        $.ajax({
            url: '<?= $api_base_url ?>internal-opd-payment/' + editid,
            type: "PATCH",
            data: JSON.stringify(formattedData),
            dataType: 'json',
            contentType: 'application/json',
            cache: false,
            processData: false,
            beforeSend: function() {
                $("#editpaymentbtn").button('loading');
            },
            success: function(response) {
                var message = response[0]?.data?.message || 'Default message';
                successMsg(message);
                // location.reload();
                $("#editpaymentbtn").button('reset');
            },
            error: function() {
                $("#editpaymentbtn").button('reset');
            },
            complete: function() {
                $("#editpaymentbtn").button('reset');
            }
        });
    });
});
</script>
<script>
let isRecording = false;
let ws;
let audioContext;
let mediaStreamSource;
let processor;

async function toggleRecording() {
    if (!isRecording) {
        await startRecording();
    } else {
        stopRecording();
    }
}

async function startRecording() {
    try {
        audioContext = new(window.AudioContext || window.webkitAudioContext)({
            sampleRate: 16000
        });
        const stream = await navigator.mediaDevices.getUserMedia({
            audio: true
        });
        mediaStreamSource = audioContext.createMediaStreamSource(stream);
        ws = new WebSocket(
            'wss://apis.augnito.ai/v2/speechapi?content-type=audio/x-raw,+layout=(string)interleaved,+rate=(int)16000,+format=(string)S16LE,+channels=(int)1&accountcode=7d958f52-cbaa-405c-9150-949f1255a282&accesskey=3a53f314-a0b0-11ef-9fd3-b2078d669786-QVdTX0FzaWFQYWNpZmljKE11bWJhaSk%3d&lmid=111801200&usertag=98f0552c-d7a2-4200-9ca3-1c680337a734&logintoken=&noisect=1&otherinfo=&sourceapp=dev-portal'
        );

        ws.onopen = () => {
            console.log('Recording started...');
        };

        ws.onmessage = (event) => {
            try {
                const response = JSON.parse(event.data);
                if (response.Result && response.Result.Final) {
                    const transcript = response.Result.Transcript.trim();
                    document.getElementById('remark').innerText = transcript;
                    if (response.Result.IsCommand) {
                        handleCommand(response.Result.Action, transcript);
                    } else if (transcript.toLowerCase().includes("select date")) {
                        handleDateSelection(transcript);
                    } else if (transcript.toLowerCase().includes("select time")) {
                        handletimeSelection(transcript);
                    } else {
                        let activeElement = document.activeElement;
                        if (activeElement && (activeElement.tagName === "INPUT" || activeElement.tagName ===
                                "TEXTAREA")) {
                            activeElement.value += transcript + " ";
                        }
                    }
                }
            } catch (err) {
                console.error("Error parsing WebSocket message:", err);
            }
        };

        ws.onerror = (error) => {
            console.error("WebSocket error:", error);
        };

        processor = audioContext.createScriptProcessor(4096, 1, 1);
        mediaStreamSource.connect(processor);
        processor.connect(audioContext.destination);
        processor.onaudioprocess = (event) => {
            const inputData = event.inputBuffer.getChannelData(0);
            const outputData = new Int16Array(inputData.length);
            for (let i = 0; i < inputData.length; i++) {
                const sample = Math.max(-1, Math.min(1, inputData[i]));
                outputData[i] = sample < 0 ? sample * 0x8000 : sample * 0x7FFF;
            }
            if (ws.readyState === WebSocket.OPEN) {
                ws.send(outputData.buffer);
            }
        };

        isRecording = true;
        document.getElementById('vioce').innerHTML = '<i class="fa fa-stop-circle"></i> Stop';
    } catch (error) {
        console.error("Error accessing microphone:", error);
    }
}

function stopRecording() {
    if (processor) {
        processor.disconnect();
        processor.onaudioprocess = null;
    }
    if (mediaStreamSource) {
        mediaStreamSource.disconnect();
    }
    if (audioContext) {
        audioContext.close();
    }
    if (ws && ws.readyState === WebSocket.OPEN) {
        ws.close();
    }
    isRecording = false;
    document.getElementById('vioce').innerHTML = '<i class="fa fa-check-circle"></i> Voice';
}

function handleCommand(action, transcript) {
    let activeElement = document.activeElement;
    switch (action) {
        case "delete previous line":
            if (activeElement && (activeElement.tagName === "INPUT" || activeElement.tagName === "TEXTAREA")) {
                const lines = activeElement.value.split('\n');
                if (lines.length > 1) {
                    lines.pop();
                    activeElement.value = lines.join('\n');
                } else {
                    activeElement.value = '';
                }
            }
            break;
        case "delete previous word":
            if (activeElement && (activeElement.tagName === "INPUT" || activeElement.tagName === "TEXTAREA")) {
                const value = activeElement.value;
                const lastSpaceIndex = value.lastIndexOf(' ');
                activeElement.value = lastSpaceIndex > -1 ? value.substring(0, lastSpaceIndex) : '';
            }
            break;
        case "start":
            if (!isRecording) toggleRecording();
            break;
        case "stop":
            if (isRecording) toggleRecording();
            break;
        default:
            console.log("Unrecognized command:", action);
    }
}

function handleDateSelection(transcript) {
    const dateFormats = [
        /\b(\w{3,9})\s*(\d{1,2})\b/i
    ];
    const cleanedTranscript = transcript.replace(/select\s+date/i, "").trim();
    let match = cleanedTranscript.match(dateFormats[0]);
    if (match) {
        let date;
        const monthName = match[1].toLowerCase();
        const day = match[2];
        const monthMap = {
            'jan': 0,
            'feb': 1,
            'mar': 2,
            'apr': 3,
            'may': 4,
            'jun': 5,
            'jul': 6,
            'aug': 7,
            'sep': 8,
            'oct': 9,
            'nov': 10,
            'dec': 11
        };
        const month = monthMap[monthName.slice(0, 3)];
        const year = new Date().getFullYear();
        if (month !== undefined) {
            date = new Date(year, month, day);
        }
        if (date && !isNaN(date)) {
            const formattedDate = date.getDate().toString().padStart(2, '0') + '/' +
                (date.getMonth() + 1).toString().padStart(2, '0') + '/' + date.getFullYear();

            const dateInput = document.getElementById("dateInput");
            if (dateInput) {
                dateInput.value = formattedDate;

            }
        } else {
            console.log("Invalid date format:", transcript);
        }
    }
}

function handletimeSelection(transcript) {
    const timeFormats = [
        /\b(\d{1,2}):(\d{2})\s*(AM|PM|am|pm)?\s*\.?\b/i
    ];

    let cleanedTranscript = transcript.replace(/select\s+time/i, "").trim();
    cleanedTranscript = cleanedTranscript.replace(/[\.]/g, "").toUpperCase();

    let match = cleanedTranscript.match(timeFormats[0]);

    if (match) {
        let hours = parseInt(match[1]);
        const minutes = parseInt(match[2]);
        let ampm = match[3] ? match[3].toUpperCase() : '';

        if (ampm === 'AM' && hours === 12) {
            hours = 0;
        } else if (ampm === 'PM' && hours < 12) {
            hours += 12;
        }

        if (hours >= 0 && hours < 24 && minutes >= 0 && minutes < 60) {
            let time = new Date();
            time.setHours(hours, minutes, 0, 0);

            let formattedHours = time.getHours() % 12 || 12;
            let formattedMinutes = time.getMinutes().toString().padStart(2, '0');
            let period = time.getHours() >= 12 ? 'PM' : 'AM';

            const formattedTime = `${formattedHours.toString().padStart(2, '0')}:${formattedMinutes} ${period}`;

            const timeInput = document.getElementById("mtime");
            if (timeInput) {
                timeInput.value = formattedTime;
            }
        }
    }
}
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const printButton = document.getElementById('print-button');
    if (!printButton) return;

    printButton.addEventListener('click', async function() {
        const tabContent = document.querySelector('.tab-content');
        if (!tabContent) {
            console.error('Tab content not found!');
            return;
        }

        try {
            const scale = 2;
            const canvas = await html2canvas(tabContent, {
                scale
            });
            const imgData = canvas.toDataURL('image/png');
            const {
                jsPDF
            } = window.jspdf;
            const pdf = new jsPDF();

            const imgWidth = 190; // A4 width in mm
            const imgHeight = (canvas.height * imgWidth) / canvas.width;

            pdf.addImage(imgData, 'PNG', 10, 10, imgWidth, imgHeight);
            pdf.save('TabContent_HighQuality.pdf');
        } catch (error) {
            console.error('Error generating PDF:', error);
        }
    });
});
</script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const casesheetElement = document.getElementById('case-sheet');
    let url = window.location.href;
    const encodedCasesheet = url.split("/").pop().split("#")[0];
    const casesheet = atob(encodedCasesheet);
    if (!casesheet) return;
    casesheetElement.addEventListener('click', async function() {
        $.ajax({
            url: "<?php echo base_url(); ?>admin/patient/getcasesheet?opdid=" + casesheet,
            method: "GET",
            success: function(response) {
                const data = JSON.parse(response);
                if (data.case_sheet_document) {
                    const fetchUrl = "https://phr-api.plenome.com/file_upload/getDocs";
                    fetch(fetchUrl, {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json"
                            },
                            body: JSON.stringify({
                                value: data.case_sheet_document
                            })
                        })
                        .then(fetchResponse => {
                            if (!fetchResponse.ok) {
                                throw new Error("Failed to fetch the document.");
                            }
                            return fetchResponse.text();
                        })
                        .then(base64File => {
                            base64File = base64File.trim();
                            const fileType = "application/pdf";
                            const fileBlob = new Blob([Uint8Array.from(atob(
                                base64File), c => c.charCodeAt(0))], {
                                type: fileType
                            });
                            const fileURL = URL.createObjectURL(fileBlob);
                            window.open(fileURL, "_blank");
                        })
                        .catch(error => {
                            console.error("Error fetching the document:", error);
                            alert(
                                "Failed to fetch the document. Please try again."
                            );
                        });
                } else {
                    errorMsg("Please complete consultation From OPHUB");
                }
            },
            error: function(error) {
                console.error("Error fetching case sheet:", error);
            }
        });
    });
});
</script>
<script>
function printAppointment(id) {
    $('#myModal').on('hidden.bs.modal', function() {
        $(this).find('form').trigger('reset');
        $("#global_shift").select2().select2("val", '');
    });
    $.ajax({
        url: base_url + 'admin/appointment/printAppointmentBill',
        type: "POST",
        data: {
            'appointment_id': id
        },
        dataType: 'json',
        success: function(data) {
            popup(data.page);
        },
        error: function(xhr) {
            alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
        }
    });
}
</script>
<script>
const initialData = <?= json_encode($ocdlist['data']) ?>;
const initialRecordsTotal = <?= $ocdlist["recordsFiltered"] ?>;
let isFirstLoad = true;
var opdid = atob("<?php echo strtok($this->uri->segment(5), '#'); ?>");
var patient_id = atob("<?php echo $this->uri->segment(4); ?>");
$(document).ready(function() {
    initSummaryDataTable(
        '',
        '',
        '#ajaxlist',
        '',
        'renderSummaryTable',
        `${baseurl}admin/patient/getocddetials?patientid=${patient_id}&opd_details_id=${opdid}`
    );
});

// $(document).ready(function() {
//     $('#ajaxlist').DataTable({
//         serverSide: true,
//         searching: true,
//         ordering: true,
//         paging: true,
//         lengthMenu: [5, 10, 25, 50],
//         pageLength: 10,
//         columnDefs: [{
//             orderable: false,
//             targets: -1
//         }],
//         dom: 'Blfrtip',
//         buttons: [{
//                 extend: 'copyHtml5',
//                 text: '<i class="fa fa-files-o"></i>',
//                 titleAttr: 'Copy',
//                 className: 'btn btn-default btn-copy'
//             },
//             {
//                 extend: 'excelHtml5',
//                 text: '<i class="fa fa-file-excel-o"></i>',
//                 titleAttr: 'Excel',
//                 className: 'btn btn-default btn-excel'
//             },
//             {
//                 extend: 'csvHtml5',
//                 text: '<i class="fa fa-file-text-o"></i>',
//                 titleAttr: 'CSV',
//                 className: 'btn btn-default btn-csv'
//             },
//             {
//                 extend: 'pdfHtml5',
//                 text: '<i class="fa fa-file-pdf-o"></i>',
//                 titleAttr: 'PDF',
//                 className: 'btn btn-default btn-pdf'
//             },
//             {
//                 extend: 'print',
//                 text: '<i class="fa fa-print"></i>',
//                 titleAttr: 'Print',
//                 className: 'btn btn-default btn-print'
//             }
//         ],
//         language: {
//             emptyTable: "No Data found"
//         },
//         ajax: function(data, callback) {
//             $("#pageloader").fadeIn();
//             const page = Math.floor(data.start / data.length) + 1;

//             if (isFirstLoad && Array.isArray(initialData) && initialData.length > 0) {
//                 isFirstLoad = false;
//                 // $("#pageloader").fadeOut();
//                 renderTable(initialData, initialRecordsTotal, data, callback);
//                 return;
//             }
//             var opdid = atob("<?php echo strtok($this->uri->segment(5), '#'); ?>");
//             var patient_id = atob("<?php echo $this->uri->segment(4); ?>");
//             console.log(patient_id);
//             fetch(
//                     ``
//                 )
//                 .then(res => res.json())
//                 .then(result => {
//                     //  $("#pageloader").fadeOut();
//                     renderTable(result.data, result.recordsTotal, data, callback);
//                 })
//                 .catch(() => {
//                     $("#pageloader").fadeOut();
//                     callback({
//                         draw: data.draw,
//                         recordsTotal: 0,
//                         recordsFiltered: 0,
//                         data: []
//                     });
//                 });
//         }
//     });
// });

function renderSummaryTable(dataArray, recordCount, data, callback) {
    let count = 0;
    return (dataArray || []).map(item => {
        const ocdNo = `<span>${item.OPD_checkup_ID || '-'}</span>`;
        const appointmentdate = `<span>${formatDate(item.appointment_date) || '-'}</span>`;
        const consultant = `<span>${item.consultant || '-'}</span>`;
        const reference = `<span>${item.refference?.trim() || '-'}</span>`;
        const symptoms = `<span>${item.symptoms ? item.symptoms.replace(/\n/g, "<br>") : '-'}</span>`;
        const ocdNoInt = item.OPD_checkup_ID ? item.OPD_checkup_ID.replace(/\D/g, '') : '-';
        let dynamicFields = [];
        if (item.dynamic_fields && Array.isArray(item.dynamic_fields)) {
            dynamicFields = item.dynamic_fields.map(f => {
                if (f.type === "link") {
                    return `<span>${f.value}</span>`;
                }
                return `<span>${f.value || '-'}</span>`;
            });
        }
        action =
            `<a href="javascript:void(0)" data-loading-text="Please Wait.." data-record-id="${ocdNoInt}" class="btn btn-default btn-xs get_opd_detail" data-toggle="tooltip" title="" data-original-title="Show"><i class="fa fa-reorder"></i></a>`;
        return [
            ++count,
            ocdNo,
            appointmentdate,
            consultant,
            reference,
            symptoms,
            action
        ];
    });
}

function formatDate(dateStr) {
    const date = new Date(dateStr);
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();
    return `${day}/${month}/${year}`;
}
</script>
<script>
const initialChargesData = <?= json_encode($chargeslist['data']) ?>;
const initialChargesTotal = <?= $chargeslist["recordsFiltered"] ?>;
let isFirstLoadCharges = true;
$(document).ready(function() {
    initSummaryDataTable(
        '',
        '',
        '#opdcharged',
        '',
        'renderCharges',
        `${baseurl}admin/patient/getocdcharges?patientid=${patient_id}&opd_details_id=${opdid}`
    );
});
// $(document).ready(function() {
//     $('#opdcharged').DataTable({
//         serverSide: true,
//         searching: true,
//         ordering: true,
//         paging: true,
//         lengthMenu: [5, 10, 25, 50],
//         pageLength: 10,
//         columnDefs: [{
//             orderable: false,
//             targets: -1
//         }],
//         dom: 'Blfrtip',
//         buttons: [{
//                 extend: 'copyHtml5',
//                 text: '<i class="fa fa-files-o"></i>',
//                 titleAttr: 'Copy',
//                 className: 'btn btn-default btn-copy'
//             },
//             {
//                 extend: 'excelHtml5',
//                 text: '<i class="fa fa-file-excel-o"></i>',
//                 titleAttr: 'Excel',
//                 className: 'btn btn-default btn-excel'
//             },
//             {
//                 extend: 'csvHtml5',
//                 text: '<i class="fa fa-file-text-o"></i>',
//                 titleAttr: 'CSV',
//                 className: 'btn btn-default btn-csv'
//             },
//             {
//                 extend: 'pdfHtml5',
//                 text: '<i class="fa fa-file-pdf-o"></i>',
//                 titleAttr: 'PDF',
//                 className: 'btn btn-default btn-pdf'
//             },
//             {
//                 extend: 'print',
//                 text: '<i class="fa fa-print"></i>',
//                 titleAttr: 'Print',
//                 className: 'btn btn-default btn-print'
//             }
//         ],
//         language: {
//             emptyTable: "No Data found"
//         },
//         ajax: function(data, callback) {
//             const page = Math.floor(data.start / data.length) + 1;
//             if (isFirstLoadCharges && Array.isArray(initialChargesData) && initialChargesData
//                 .length > 0) {
//                 isFirstLoadCharges = false;
//                 renderCharges(initialChargesData, initialChargesTotal, data, callback);
//                 return;
//             }
//             var opdid = atob("<?= strtok($this->uri->segment(5), '#') ?>");
//             var patient_id = atob("<?= $this->uri->segment(4) ?>");
//             fetch(
//                     `${baseurl}admin/patient/getocdcharges?patientid=${patient_id}&opd_details_id=${opdid}&limit=${data.length}&page=${page}&search=${data.search.value}`
//                 )
//                 .then(res => res.json())
//                 .then(result => {
//                     renderCharges(result.data, result.recordsTotal, data, callback);
//                 })
//                 .catch(() => {
//                     callback({
//                         draw: data.draw,
//                         recordsTotal: 0,
//                         recordsFiltered: 0,
//                         data: []
//                     });
//                 });
//         },
//         drawCallback: function() {
//             $('[data-toggle="tooltip"]').tooltip({
//                 container: 'body'
//             });
//         }
//     });
// });
function renderCharges(dataArray, recordCount, data, callback) {
    let count = 0;
    let totalAmountPaid = 0;
    let rows = (dataArray || []).map((item, index) => {
        const amount = parseFloat(item.total) || 0;
        totalAmountPaid += amount;
        const isPaid = (item.payment_status || "").toUpperCase() === "PAID";
        const isFirst = index === 0;
        const disableAction = isPaid || isFirst;
        let tooltip = "";
        if (isFirst) {
            tooltip = "First record, action disabled.";
        } else if (isPaid) {
            tooltip = "This charge is paid and cannot be modified.";
        }
        const editBtn = `<a href="javascript:void(0);" class="btn btn-default btn-xs edit_charge ${disableAction ? 'no-drop disabled' : ''}" data-toggle="tooltip" title="${disableAction ? tooltip : '<?= $this->lang->line('edit') ?>'}" ${disableAction ? '' : `data-record-id="${item.patient_charge_id}"`}><i class="fa fa-pencil"></i></a>`;
        const deleteBtn = `<a href="javascript:void(0);" class="btn btn-default btn-xs ${disableAction ? 'no-drop disabled' : ''}" data-toggle="tooltip" title="${disableAction ? tooltip : '<?= $this->lang->line('delete') ?>'}" ${disableAction ? '' : `onclick="deleteOpdPatientCharge(${item.patient_charge_id})"`}><i class="fa fa-trash"></i></a>`;
        const action = editBtn + deleteBtn;
        const discountAmount = parseFloat(item.discount_amount) || 0;
        const discountPercentage = parseFloat(item.discount_percentage) || 0;
        const taxPercentage = parseFloat(item.tax_percentage) || 0;
        const taxAmount = parseFloat(item.tax_amount) || 0;
        item.payment_status = (item.payment_status || "").toUpperCase();
        return [
            ++count,
            formatDate(item.Date),
            item.name || '-',
            `₹${parseFloat(item.standard_charge).toFixed(2)}`,
            item.qty || 0,
            `₹${parseFloat(item.applied_charge).toFixed(2)}`,
            `${discountPercentage.toFixed(2)}% (₹${discountAmount.toFixed(2)})`,
            `₹${parseFloat(item.additional_charges).toFixed(2)}`,
            `₹${parseFloat(item.sub_total).toFixed(2)}`,
            `${taxPercentage.toFixed(2)}% (₹${taxAmount.toFixed(2)})`,
            `₹${parseFloat(item.total).toFixed(2)}`,
            `₹${parseFloat(item.tpa_charge).toFixed(2)}`,
            item.payment_status,
            action
        ];
    });

    rows.push([
        "", "", "", "", "", "", "", "", "", "", "<strong>Total</strong>",
        `<strong>₹${totalAmountPaid.toFixed(2)}</strong>`, "", ""
    ]);

    return rows;
}

</script>


<script>
const initialPaymentData = <?= json_encode($paymentlist['data']) ?>;
const initialPaymentTotal = <?= $paymentlist["recordsFiltered"] ?>;
let isFirstLoadPayment = true;
var opdid = atob("<?= strtok($this->uri->segment(5), '#') ?>");
var patient_id = atob("<?= $this->uri->segment(4) ?>");
$(document).ready(function() {
    initSummaryDataTable(
        '',
        '',
        '#opdpayment',
        '',
        'renderPayments',
        `${baseurl}admin/patient/getocdpayment?patientid=${patient_id}&opd_details_id=${opdid}}`
    );
});
// $(document).ready(function() {
//     $('#opdpayment').DataTable({
//         serverSide: true,
//         searching: true,
//         ordering: true,
//         paging: true,
//         lengthMenu: [5, 10, 25, 50],
//         pageLength: 10,
//         columnDefs: [{
//             orderable: false,
//             targets: -1
//         }],
//         dom: 'Blfrtip',
//         buttons: [{
//                 extend: 'copyHtml5',
//                 text: '<i class="fa fa-files-o"></i>',
//                 titleAttr: 'Copy',
//                 className: 'btn btn-default btn-copy'
//             },
//             {
//                 extend: 'excelHtml5',
//                 text: '<i class="fa fa-file-excel-o"></i>',
//                 titleAttr: 'Excel',
//                 className: 'btn btn-default btn-excel'
//             },
//             {
//                 extend: 'csvHtml5',
//                 text: '<i class="fa fa-file-text-o"></i>',
//                 titleAttr: 'CSV',
//                 className: 'btn btn-default btn-csv'
//             },
//             {
//                 extend: 'pdfHtml5',
//                 text: '<i class="fa fa-file-pdf-o"></i>',
//                 titleAttr: 'PDF',
//                 className: 'btn btn-default btn-pdf'
//             },
//             {
//                 extend: 'print',
//                 text: '<i class="fa fa-print"></i>',
//                 titleAttr: 'Print',
//                 className: 'btn btn-default btn-print'
//             }
//         ],
//         language: {
//             emptyTable: "No Data found"
//         },
//         ajax: function(data, callback) {
//             const page = Math.floor(data.start / data.length) + 1;

//             if (isFirstLoadPayment && Array.isArray(initialPaymentData) && initialPaymentData
//                 .length > 0) {
//                 isFirstLoadPayment = false;
//                 $("#pageloader").fadeOut();
//                 renderPayments(initialPaymentData, initialPaymentTotal, data, callback);
//                 return;
//             }

//             var opdid = atob("<?= strtok($this->uri->segment(5), '#') ?>");
//             var patient_id = atob("<?= $this->uri->segment(4) ?>");

//             fetch(
//                     `${baseurl}admin/patient/getocdpayment?patientid=${patient_id}&opd_details_id=${opdid}&limit=${data.length}&page=${page}&search=${data.search.value}`
//                 )
//                 .then(res => res.json())
//                 .then(result => {
//                     $("#pageloader").fadeOut();
//                     renderPayments(result.data, result.recordsTotal, data, callback);
//                 })
//                 .catch(() => {
//                     $("#pageloader").fadeOut();
//                     callback({
//                         draw: data.draw,
//                         recordsTotal: 0,
//                         recordsFiltered: 0,
//                         data: []
//                     });
//                 });
//         }
//     });
// });

function renderPayments(dataArray, recordCount, data, callback) {
    let count = 0;
    const rows = (dataArray || []).map((item) => {
        let id = item.transaction_ID ? item.transaction_ID.replace(/\D/g, '') : '';
        const action = `<a href="javascript:void(0);" class="btn btn-default btn-xs print_trans"
                            data-toggle="tooltip" title=""
                            data-loading-text="<?php echo $this->lang->line('please_wait'); ?>"
                            data-record-id="${id}"
                            data-original-title="<?php echo $this->lang->line('print'); ?>">
                            <i class="fa fa-print"></i>
                        </a>`;
        return [
            ++count,
            item.transaction_ID || '-',
            formatDate(item.payment_date),
            item.note || '-',
            item.payment_mode || '-',
            parseFloat(item.amount).toFixed(2) || 0,
            action
        ];
    });

    rows.push([
        "", "", "", "", "<strong>Total</strong>",
        `<strong>₹${dataArray.reduce((sum, item) => sum + parseFloat(item.amount || 0), 0).toFixed(2)}</strong>`, ""
    ]);
    return rows;
}

function checkcheque(value) {
    if (value === 'Paylater') {
        $('#amountchecker').text('');
        $("#paid_amount").prop("readonly", true);
        $('#get_print, #formrevisitbtn').prop('disabled', false);
    } else {
        const applyAmount = $("#revisit_amount").val();
        $("#paid_amount").prop("readonly", false);
        $("#paid_amount").val(applyAmount);
        checkpaylater(applyAmount);
    }
}

function checkpaylater(value) {
    const paymentMode = $("[name='payment_mode']").val();
    const applyAmount = parseFloat($("#revisit_amount").val());
    const paidAmount = parseFloat(value) || 0;
    if (paidAmount === 0) {
        $('#amountchecker').text('Paid amount cannot be 0');
        $('#get_print, #formrevisitbtn').prop('disabled', true);
        return;
    }
    if (paidAmount > applyAmount) {
        $('#amountchecker').text('Paid amount cannot be greater than the total amount.');
        $('#get_print, #formrevisitbtn').prop('disabled', true);
        return;
    }
    $('#amountchecker').text('');
    $('#get_print, #formrevisitbtn').prop('disabled', false);
}
</script>



<!-- //========datatable end===== -->