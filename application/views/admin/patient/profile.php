<?php
$currency_symbol = $this->customlib->getHospitalCurrencyFormat();
$genderList = $this->customlib->getGender();
?>
<script src="<?php echo base_url('/') ?>backend/js/Chart.bundle.js"></script>
<script src="<?php echo base_url('/') ?>backend/js/utils.js"></script>
<script>
    $(document).ready(function() {
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        $('#timeline_date').datetimepicker({
            format: 'DD/MM/YYYY',
            minDate: today,
            ignoreReadonly: true
        });
        $('#etimelinedate').datetimepicker({
            format: 'DD/MM/YYYY',
            minDate: today,
            ignoreReadonly: true
        });

    });
</script>
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs navlistscroll">
                        <li class="active"><a href="#overview" data-toggle="tab" aria-expanded="true"><i
                                    class="fa fa-th"></i> <?php echo $this->lang->line('overview'); ?></a></li>
                        <?php if ($this->rbac->hasPrivilege('visit', 'can_view')) { ?>
                            <li><a href="#activity" data-toggle="tab" aria-expanded="true"><i
                                        class="far fa-caret-square-down"></i> <?php echo $this->lang->line('visits'); ?></a>
                            </li>
                        <?php }
                        if ($this->rbac->hasPrivilege('opd_lab_investigation', 'can_view')) { ?>
                            <li><a href="#labinvestigation" data-toggle="tab" aria-expanded="true"><i
                                        class="fas fa-diagnoses"></i>
                                    <?php echo $this->lang->line('lab_investigation'); ?></a></li>
                        <?php }
                        if ($this->rbac->hasPrivilege('opd_treatment_history', 'can_view')) { ?>
                            <li><a href="#treatment_history" data-toggle="tab" aria-expanded="true"><i
                                        class="fas fa-diagnoses"></i>
                                    <?php echo $this->lang->line('treatment_history'); ?></a></li>
                        <?php }
                        if ($this->rbac->hasPrivilege('opd_timeline', 'can_view')) { ?>
                            <li><a href="#timeline" data-toggle="tab" aria-expanded="true"><i
                                        class="far fa-calendar-check"></i> <?php echo $this->lang->line('timeline'); ?></a>
                            </li>
                        <?php } ?>

                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane tab-content-height active" id="overview">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12 border-r">
                                    <div class="box-header border-b mb10 pl-0 pt0">
                                        <h3 class="text-uppercase bolds mt0 ptt10 pull-left font14">
                                            <?php echo composePatientName($result['patient_name'], $result['id']); ?>
                                        </h3>
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


                                            <img width="115" height="115" class="" src="<?php echo $logo_image; ?>"
                                                alt="No Image">


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
                                    <p><b><i class="fa fa-tag"></i>
                                            <?php echo $this->lang->line('known_allergies'); ?>:</b>
                                        <?php echo empty($patientdetails['patient']['allergy']) ? "-" : ""; ?>
                                    </p>
                                    <ul>
                                        <?php
                                        foreach ($patientdetails['patient']['allergy'] ?? [] as $row) { ?>
                                            <li>
                                                <div>
                                                    <?php echo !empty($row['known_allergies']) ? $row['known_allergies'] : "-"; ?>
                                                </div>
                                            </li>
                                        <?php } ?>
                                    </ul>

                                    <hr class="hr-panel-heading hr-10">
                                    <p><b><i class="fa fa-tag"></i> <?php echo $this->lang->line('findings'); ?>:</b>
                                        <?php echo empty($patientdetails['patient']['findings']) ? "-" : ""; ?>
                                    </p>
                                    <ul>
                                        <?php
                                        foreach ($patientdetails['patient']['findings'] ?? [] as $row) { ?>
                                            <li>
                                                <div>
                                                    <?php echo !empty($row['finding_description']) ? $row['finding_description'] : "-"; ?>
                                                </div>
                                            </li>
                                        <?php } ?>
                                    </ul>

                                    <hr class="hr-panel-heading hr-10">
                                    <p><b><i class="fa fa-tag"></i> <?php echo $this->lang->line('symptoms'); ?>:</b>
                                        <?php echo empty($patientdetails['patient']['symptoms']) ? "-" : ""; ?>
                                    </p>
                                    <ul>
                                        <?php
                                        foreach ($patientdetails['patient']['symptoms'] ?? [] as $row) { ?>
                                            <li>
                                                <div><?php echo !empty($row['symptoms']) ? $row['symptoms'] : "-"; ?></div>
                                            </li>
                                        <?php } ?>
                                    </ul>





                                    <hr class="hr-panel-heading hr-10">
                                    <div class="box-header mb10 pl-0">
                                        <h3 class="text-uppercase bolds mt0 ptt10 pull-left font14">
                                            <?php echo $this->lang->line('consultant_doctor'); ?></h3>
                                        <div class="pull-right">
                                            <div class="editviewdelete-icon pt8">

                                            </div>
                                        </div>
                                    </div>


                                    <div class="staff-members">
                                        <?php
                                        foreach ($patientdetails['patient']['doctor'] as $doc_value) {  ?>

                                            <div class="media">
                                                <div class="media-left">
                                                    <?php

                                                    $default_image = "uploads/patient_images/no_image.png";
                                                    $doc_image = $doc_value['image'];
                                                    $base64Image = '';

                                                    if ($doc_image && $doc_image !== $default_image) {
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
                                                            $base64Image = base_url($default_image);
                                                        }
                                                    } else {
                                                        $base64Image = base_url($default_image);
                                                    }

                                                    ?>

                                                    <a
                                                        href="<?php echo base_url() . 'admin/staff/profile/' . $doc_value['id']; ?>">
                                                        <img src="<?php echo htmlspecialchars($base64Image); ?>"
                                                            class="member-profile-small media-object" alt="Staff Image" />
                                                    </a>



                                                </div>
                                                <div class="media-body">
                                                    <a href="<?php echo base_url() . 'admin/staff/profile/' . base64_encode($doc_value['id']); ?>"
                                                        class="pull-right text-danger pt4" data-toggle="tooltip"
                                                        data-placement="top"></a>

                                                    <h5 class="media-heading">
                                                        <a
                                                            href="<?php echo base_url() . 'admin/staff/profile/' . base64_encode($doc_value['id']); ?>">
                                                            <?php echo $doc_value["name"] . " " . $doc_value["surname"] . "  (" . $doc_value["employee_id"] . ")"; ?>
                                                        </a>


                                                    </h5>
                                                </div>
                                            </div>
                                            <!--./media-->

                                        <?php
                                        } ?>
                                    </div>
                                    <!--./staff-members-->
                                    <?php
                                    if (!empty($timeline_list)) { ?>
                                        <hr class="hr-panel-heading hr-10">
                                        <div class="box-header mb10 pl-0">
                                            <h3 class="text-uppercase bolds mt0 ptt10 pull-left font14">
                                                <?php echo $this->lang->line('timeline'); ?></h3>
                                            <div class="pull-right">
                                                <div class="editviewdelete-icon pt8">
                                                    <a href="#" data-toggle="tooltip" data-placement="top"
                                                        title="add-edit-members"></a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>

                                    <div class="timeline-header no-border">
                                        <div id="timeline_list">
                                            <?php if (empty($timeline_list)) { ?>

                                            <?php } else { ?>
                                                <ul class="timeline timeline-inverse">
                                                    <?php
                                                    $i = 0;
                                                    foreach ($timeline_list as $key => $value) {
                                                        ++$i;
                                                        if ($i <= 5) {
                                                    ?>
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
                                                                        <?php echo htmlspecialchars($value['title']); ?> </h3>
                                                                    <div class="timeline-body">
                                                                        <?php echo htmlspecialchars($value['description']); ?>
                                                                    </div>
                                                                </div>
                                                            </li>

                                                    <?php
                                                        }
                                                    } ?>
                                                    <li><i class="fa fa-clock-o bg-gray"></i></li>
                                                <?php }
                                                ?>
                                                </ul>
                                        </div>
                                    </div>

                                </div>
                                <!--./col-lg-6-->
                                <div class="col-lg-6 col-md-6 col-sm-12">

                                    <!-- <div class="box-header border-b mb10 pl-0 pt0">
                                        <h3 class="text-uppercase bolds mt0 ptt10 pull-left font14">
                                            <?php echo $this->lang->line('medical_history'); ?></h3>
                                    </div> -->
                                    <div class="row">
                                        <!-- <div class="col-lg-12 col-md-12 col-sm-12">
                                            <div class="box box-info">

                                                <div class="box-body">
                                                    <div class="chart">
                                                        <canvas id="medical-history-chart" height="300"></canvas>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> -->
                                        <!--./col-lg-7-->
                                    </div>

                                    <div class="">
                                        <div class="">
                                            <div class="box-header mb10 pl-0">
                                                <h3 class="text-uppercase bolds mt0 ptt10 pull-left font14">
                                                    <?php echo $this->lang->line('visit_details'); ?></h3>
                                                <div class="pull-right">
                                                    <div class="editviewdelete-icon pt8">
                                                        <a href="#" data-toggle="tooltip" data-placement="top"
                                                            title="add-edit-members"></a>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="staff-members">
                                                <div class="table-responsive">
                                                    <table
                                                        class="table table-striped table-bordered table-hover ipdcharged"
                                                        data-export-title="<?php echo composePatientName($result['patient_name'], $result['id']) . ' ' . $this->lang->line('opd_details'); ?>">

                                                        <?php if (!empty($patientdetails['patient']['visitdetails'])): ?>
                                                            <thead class="thead-dark">
                                                                <tr>
                                                                    <th><?php echo $this->lang->line('opd_no'); ?></th>
                                                                    <th><?php echo $this->lang->line('appointment_no'); ?>
                                                                    </th>
                                                                    <th><?php echo $this->lang->line('case_id'); ?></th>
                                                                    <th><?php echo $this->lang->line('appointment_date'); ?>
                                                                    </th>
                                                                    <th><?php echo $this->lang->line('consultant'); ?></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $data = array_slice($opdlist['data'], 0, 5);
                                                                foreach ($data as $value):
                                                                    $opd_id = $value['opd_prefix'] . $value['opd_NO'];
                                                                    $appointment_id = $value['Appointment_No'];
                                                                ?>
                                                                    <tr>
                                                                        <td class="text-center">
                                                                            <?php if (!empty($opd_id)): ?>
                                                                                <a style="position: relative; overflow: hidden; display: inline;"
                                                                                    href="<?php echo base_url('admin/patient/visitdetails/' . base64_encode($id) . '/' . base64_encode($value['opd_NO'])); ?>">
                                                                                    <?php echo $opd_id; ?>
                                                                                </a>
                                                                            <?php else: ?>
                                                                                -
                                                                            <?php endif; ?>
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <?php if (!empty($appointment_id)): ?>
                                                                                <a style="position: relative; overflow: hidden; display: inline;"
                                                                                    href="javascript:void(0);"
                                                                                    onclick="viewDetail(<?php echo $appointment_id; ?>)">
                                                                                    <?php echo !empty($value['appointment_prefix']) ? $value['appointment_prefix'] . $appointment_id : $appointment_id; ?>
                                                                                </a>
                                                                            <?php else: ?>
                                                                                -
                                                                            <?php endif; ?>
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <?php echo !empty($value['case_id']) ? $value['case_id'] : '-'; ?>
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <?php echo !empty($value['appointment_date']) ? date('d/m/Y', strtotime($value['appointment_date'])) : '-'; ?>
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <?php echo !empty($value['consultant']) ? $value['consultant'] : '-'; ?>
                                                                        </td>
                                                                    </tr>
                                                                <?php endforeach; ?>
                                                            </tbody>

                                                        <?php else: ?>
                                                            <tbody>
                                                                <tr>
                                                                    <td colspan="5" class="text-center">
                                                                        <?php echo $this->lang->line('no_records_found'); ?>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        <?php endif; ?>
                                                    </table>


                                                </div>
                                            </div>
                                            <!--./staff-members-->

                                        </div>
                                    </div>

                                    <div class="">
                                        <div class="">
                                            <?php if (!empty($investigations)) { ?>
                                                <div class="box-header mb10 pl-0">
                                                    <h3 class="text-uppercase bolds mt0 ptt10 pull-left font14">
                                                        <?php echo $this->lang->line('lab_investigation'); ?></h3>
                                                    <div class="pull-right">
                                                        <div class="editviewdelete-icon pt8">
                                                            <a href="#" data-toggle="tooltip" data-placement="top"
                                                                title="add-edit-members"></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>


                                            <div class="staff-members">
                                                <div class="table-responsive">
                                                    <?php if (!empty($investigations)) { ?>
                                                        <table class="table table-striped table-bordered table-hover">
                                                            <thead>
                                                                <th><?php echo $this->lang->line('test_name'); ?></th>
                                                                <th><?php echo $this->lang->line('case_id'); ?></th>
                                                                <th><?php echo $this->lang->line('lab'); ?></th>
                                                                <th><?php echo $this->lang->line('sample_collected'); ?>
                                                                </th>
                                                                <td><strong><?php echo $this->lang->line('expected_date'); ?></strong>
                                                                </td>
                                                                <th><?php echo $this->lang->line('approved_by'); ?></th>

                                                            </thead>
                                                            <tbody id="">
                                                                <?php
                                                                $i = 1;
                                                                foreach ($investigations as $row) {
                                                                    if ($i <= $recent_record_count) {
                                                                        ++$i;
                                                                ?>
                                                                        <tr>
                                                                            <td><?php echo $row['test_name']; ?><br />
                                                                                <?php echo "(" . $row['short_name'] . ")"; ?></td>
                                                                            <td><?php echo $row['case_reference_id']; ?></td>
                                                                            <td><?php echo $this->lang->line($row['type']); ?></td>
                                                                            <td><label>
                                                                                    <?php echo composeStaffNameByString($row['collection_specialist_staff_name'], $row['collection_specialist_staff_surname'], $row['collection_specialist_staff_employee_id']); ?>
                                                                                </label>

                                                                                <br />


                                                                                <?php
                                                                                echo $row['test_center'];
                                                                                ?>
                                                                                <br />
                                                                                <?php echo $this->customlib->YYYYMMDDTodateFormat($row['collection_date']); ?>
                                                                            </td>

                                                                            <td>
                                                                                <?php

                                                                                echo  $this->customlib->YYYYMMDDTodateFormat($row['reporting_date']); ?>

                                                                            </td>
                                                                            <td class="text-left">
                                                                                <?php
                                                                                echo composeStaffNameByString($row['approved_by_staff_name'], $row['approved_by_staff_surname'], $row['approved_by_staff_employee_id']);
                                                                                ?>
                                                                                <br />
                                                                                <?php
                                                                                echo  $this->customlib->YYYYMMDDTodateFormat($row['parameter_update']);
                                                                                ?>
                                                                            </td>

                                                                        </tr>
                                                                <?php }
                                                                } ?>
                                                            </tbody>
                                                        </table>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <!--./staff-members-->

                                        </div>
                                    </div>

                                    <div class="">
                                        <div>
                                            <?php if (!empty($patientdetails['patient']['history'])) { ?>
                                                <div class="box-header mb10 pl-0">
                                                    <h3 class="text-uppercase bolds mt0 ptt10 pull-left font14">
                                                        <?php echo "Treatment History (Last 5 Visit History)"; ?></h3>
                                                    <div class="pull-right">
                                                        <div class="editviewdelete-icon pt8">

                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>

                                            <div class="staff-members">
                                                <div class="table-responsive">
                                                    <?php if (!empty($patientdetails['patient']['history'])) { ?>
                                                        <table class="table table-striped table-bordered table-hover "
                                                            data-export-title="<?php echo composePatientName($result['patient_name'], $result['id']) . " " . $this->lang->line('opd_details'); ?>">
                                                            <thead>
                                                                <th><?php echo $this->lang->line('opd_no'); ?></th>
                                                                <th><?php echo $this->lang->line('case_id'); ?></th>
                                                                <th><?php echo $this->lang->line('appointment_date'); ?>
                                                                </th>
                                                                <th><?php echo $this->lang->line('consultant'); ?></th>
                                                                <th><?php echo $this->lang->line('symptoms'); ?></th>

                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                foreach ($patientdetails['patient']['history'] as $value) {
                                                                    $opd_id = $this->customlib->getSessionPrefixByType('opd_no') . $value['opd_id'];
                                                                ?>
                                                                    <tr>
                                                                        <td>
                                                                            <a
                                                                                href="<?php echo base_url() . 'admin/patient/visitdetails/' . base64_encode($id) . '/' . base64_encode($value['opd_id']); ?>">
                                                                                <?php echo $opd_id; ?>
                                                                            </a>
                                                                        </td>
                                                                        <td><?php echo trim($value['case_reference_id']) !== '' ? $value['case_reference_id'] : '-'; ?>
                                                                        </td>
                                                                        <td><?php echo trim($value['appointment_date']) !== '' ? $this->customlib->YYYYMMDDHisTodateFormat($value['appointment_date'], $timeformat) : '-'; ?>
                                                                        </td>
                                                                        <td><?php echo trim(composeStaffNameByString($value['name'], $value['surname'], $value['employee_id'])) !== '' ? composeStaffNameByString($value['name'], $value['surname'], $value['employee_id']) : '-'; ?>
                                                                        </td>
                                                                        <td><?php echo trim($value['symptoms']) !== '' ? nl2br($value['symptoms']) : '-'; ?>
                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    <?php }  ?>
                                                </div>
                                            </div>
                                            <!--./staff-members-->

                                        </div>
                                    </div>


                                </div>
                                <!--./col-lg-6-->
                            </div>
                            <!--./row-->
                        </div>
                        <!--#/overview-->
                        <?php if ($this->rbac->hasPrivilege('visit', 'can_view')) { ?>
                            <div class="tab-pane " id="activity">
                                <div class="box-tab-header">
                                    <h3 class="box-tab-title"><?php echo $this->lang->line('visits'); ?></h3>
                                    <div class="box-tab-tools">
                                        <?php if ($this->rbac->hasPrivilege('visit', 'can_add')) { ?>

                                            <a href="#" onclick="getRevisitRecord('<?php echo $opd_details_id['opdid'] ?>')"
                                                class="btn btn-primary btn-sm revisitpatient" data-toggle="modal" title=""><i
                                                    class="fas fa-exchange-alt"></i>
                                                <?php echo $this->lang->line('new_visit'); ?>
                                            </a>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="download_label">
                                    <?php echo composePatientName($result['patient_name'], $result['id']) . " " . $this->lang->line('opd_details'); ?>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover" id="ajaxlist"
                                        data-export-title="<?php echo composePatientName($result['patient_name'], $result['id']) . " " . $this->lang->line('opd_details'); ?>">
                                        <thead class="thead-light">
                                            <tr>
                                                <th class="text-center">S.No</th>
                                                <th class="text-center"><?php echo $this->lang->line('opd_no'); ?></th>
                                                <th class="text-center"><?php echo $this->lang->line('appointment_no'); ?>
                                                </th>
                                                <th class="text-center"><?php echo $this->lang->line('case_id'); ?></th>
                                                <th class="text-center"><?php echo $this->lang->line('appointment_date'); ?>
                                                </th>
                                                <th class="text-center"><?php echo $this->lang->line('consultant'); ?></th>
                                                <th class="text-center"><?php echo $this->lang->line('reference'); ?></th>
                                                <th class="text-center"><?php echo $this->lang->line('symptoms'); ?></th>
                                                <?php if (!empty($fields)) {
                                                    foreach ($fields as $fields_key => $fields_value) { ?>
                                                        <th class="text-center"><?php echo $fields_value->name; ?></th>
                                                <?php }
                                                } ?>
                                                <th class="text-center noExport"><?php echo $this->lang->line('action') ?>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="table-body"></tbody>
                                    </table>
                                </div>
                            </div>
                        <?php } ?>

                        <!-- -->
                        <div class="tab-pane" id="labinvestigation">
                            <div class="box-tab-header">
                                <h3 class="box-tab-title"><?php echo $this->lang->line('lab_investigation'); ?></h3>
                            </div>
                            <div class="impbtnview-t9">

                            </div>

                            <div class="download_label">
                                <?php echo composePatientName($result['patient_name'], $result['id']) . " " . $this->lang->line('opd_details'); ?>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover example"
                                    data-export-title="<?php echo composePatientName($result['patient_name'], $result['id']) . " " . $this->lang->line('opd_details'); ?>">
                                    <thead>
                                        <th><?php echo $this->lang->line('test_name'); ?></th>
                                        <th><?php echo $this->lang->line('case_id'); ?></th>
                                        <th><?php echo $this->lang->line('lab'); ?></th>
                                        <th><?php echo $this->lang->line('sample_collected'); ?></th>
                                        <td><strong><?php echo $this->lang->line('expected_date'); ?></strong></td>
                                        <th><?php echo $this->lang->line('approved_by'); ?></th>
                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                    </thead>
                                    <tbody id="">
                                        <?php foreach ($investigations as $row) { ?>
                                            <tr>
                                                <td><?php echo $row['test_name']; ?><br />
                                                    <?php echo "(" . $row['short_name'] . ")"; ?></td>
                                                <td><?php echo $row['case_reference_id']; ?></td>
                                                <td><?php echo $this->lang->line($row['type']); ?></td>
                                                <td><label>
                                                        <?php echo composeStaffNameByString($row['collection_specialist_staff_name'], $row['collection_specialist_staff_surname'], $row['collection_specialist_staff_employee_id']); ?>
                                                    </label>

                                                    <br />


                                                    <?php
                                                    echo $row['test_center'];
                                                    ?>
                                                    <br />
                                                    <?php echo $this->customlib->YYYYMMDDTodateFormat($row['collection_date']); ?>
                                                </td>

                                                <td>
                                                    <?php

                                                    echo  $this->customlib->YYYYMMDDTodateFormat($row['reporting_date']); ?>

                                                </td>
                                                <td class="text-left">
                                                    <?php
                                                    echo composeStaffNameByString($row['approved_by_staff_name'], $row['approved_by_staff_surname'], $row['approved_by_staff_employee_id']);
                                                    ?>
                                                    <br />
                                                    <?php
                                                    echo  $this->customlib->YYYYMMDDTodateFormat($row['parameter_update']);
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
                                </table>
                            </div>
                        </div>
                        <!-- -->
                        <div class="tab-pane" id="treatment_history">
                            <div class="box-tab-header">
                                <h3 class="box-tab-title"><?php echo $this->lang->line('treatment_history'); ?></h3>
                            </div>
                            <div class="impbtnview-t9">

                            </div>

                            <div class="download_label">
                                <?php echo composePatientName($result['patient_name'], $result['id']) . " " . $this->lang->line('opd_details'); ?>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover treatmentlist"
                                    data-export-title="<?php echo composePatientName($result['patient_name'], $result['id']) . " " . $this->lang->line('opd_details'); ?>">
                                    <thead>
                                        <th><?php echo $this->lang->line('opd_no'); ?></th>
                                        <th><?php echo $this->lang->line('case_id'); ?></th>
                                        <th><?php echo $this->lang->line('appointment_date'); ?></th>
                                        <th><?php echo $this->lang->line('symptoms'); ?></th>
                                        <th><?php echo $this->lang->line('consultant'); ?></th>
                                        <th class="text-right noExport"><?php echo $this->lang->line('action') ?></th>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane" id="timeline">
                            <div class="box-tab-header">
                                <h3 class="box-tab-title"><?php echo $this->lang->line('timeline'); ?></h3>
                            </div>
                            <div class="impbtnview-t9">
                                <?php if ($this->rbac->hasPrivilege('opd_timeline', 'can_add')) { ?>
                                    <a data-toggle="modal" onclick="holdModal('myTimelineModal')"
                                        class="btn btn-primary btn-sm addtimeline"><i class="fa fa-plus"></i>
                                        <?php echo $this->lang->line('add') ?>
                                        <?php echo $this->lang->line('timeline'); ?></a>
                                <?php } ?>
                            </div>
                            <div class="timeline-header no-border">
                                <div id="timeline_list">
                                    <?php
                                    if (empty($timeline_list)) {
                                    ?>
                                        <br />
                                        <div class="alert alert-info"><?php echo $this->lang->line('no_record_found'); ?>
                                        </div>
                                    <?php } else {
                                    ?>
                                        <ul class="timeline timeline-inverse">

                                            <?php
                                            foreach ($timeline_list as $key => $value) {
                                            ?>
                                                <li class="time-label">
                                                    <span class="bg-blue">
                                                        <?php echo !empty($value['timeline_date']) ? date('d/m/Y', strtotime($value['timeline_date'])) : '-'; ?>
                                                    </span>

                                                </li>
                                                <li>
                                                    <i class="fa fa-list-alt bg-blue"></i>
                                                    <div class="timeline-item">
                                                        <span class="time">
                                                            <?php if ($this->rbac->hasPrivilege('opd_timeline', 'can_delete')) {

                                                                if ($value['generated_users_type'] != 'patient') {
                                                            ?>
                                                                    <a class="defaults-c" data-toggle="tooltip" title=""
                                                                        onclick="delete_timeline('<?php echo $value['id']; ?>')"
                                                                        data-original-title="<?php echo $this->lang->line('delete'); ?>"><i
                                                                            class="fa fa-trash"></i></a>
                                                            <?php }
                                                            } ?>
                                                        </span>
                                                        <span class="time">
                                                            <?php if ($this->rbac->hasPrivilege('opd_timeline', 'can_edit')) {
                                                                if ($value['generated_users_type'] != 'patient') { ?>
                                                                    <a onclick="editTimeline('<?php echo $value['id']; ?>')"
                                                                        class="btn btn-default btn-xs" data-toggle="tooltip" title=""
                                                                        data-original-title="<?php echo $this->lang->line('edit'); ?>">
                                                                        <i class="fa fa-pencil"></i>
                                                                    </a>
                                                            <?php }
                                                            } ?>
                                                        </span>
                                                        <?php
                                                        if (!empty(trim($value["document"]))) { ?>
                                                            <span class="time">
                                                                <?php
                                                                $userdata = $this->session->userdata('hospitaladmin');
                                                                $accessToken = $userdata['accessToken'] ?? '';

                                                                $name = trim($value["document"]);
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

                                                                $mimeTypes = array(
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
                                                                );

                                                                $mimeType = $mimeTypes[$fileExtension] ?? 'application/octet-stream';

                                                                $formattedName = preg_replace('/_\d+$/', '', $fileInfo['basename']);

                                                                echo "<a href='data:" . $mimeType . ";base64," . htmlspecialchars($base64File) . "' download='" . htmlspecialchars($formattedName) . "' class='btn btn-default btn-xs' data-toggle='tooltip' title='" . $this->lang->line('download') . "'><i class='fa fa-download'></i></a>";
                                                                ?>
                                                            </span>

                                                        <?php } ?>

                                                        <h3 class="timeline-header text-aqua"> <?php echo $value['title']; ?>
                                                        </h3>
                                                        <div class="timeline-body">
                                                            <?php
                                                            echo $value['description']; ?>

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
                        <div class="tab-pane" id="prescription">
                            <div class="download_label">
                                <?php echo composePatientName($result['patient_name'], $result['id']) . " " . $this->lang->line('opd_details'); ?>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover example">
                                    <thead>
                                        <th><?php echo $this->lang->line('opd') . " " . $this->lang->line('id'); ?></th>
                                        <th><?php echo $this->lang->line('appointment') . " " . $this->lang->line('date'); ?>
                                        </th>
                                        <th><?php echo $this->lang->line('note'); ?></th>
                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!empty($prescription_detail)) {
                                            foreach ($prescription_detail as $prescription_key => $prescription_value) {
                                        ?>
                                                <tr>
                                                    <td><?php echo $prescription_value["opd_id"] ?></td>
                                                    <td><?php echo $prescription_value["appointment_date"] ?></td>
                                                    <td><?php echo $prescription_value["note"] ?></td>
                                                    <th class="pull-right"><a href="#" data-toggle='tooltip'
                                                            title="<?php echo $this->lang->line('test_report_detail'); ?>"
                                                            onclick="view_prescription('<?php echo $prescription_value["opd_id"] ?>')"><i
                                                                class="fa fa-reorder"></i></a></th>
                                                </tr>
                                        <?php
                                            }
                                        }
                                        ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
</div>

<div id="modal-chkstatus" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog modal-lg">
        <form id="form-chkstatus" action="" method="POST">
            <div class="modal-content">
                <div class="">
                    <button type="button" class="close modalclosezoom" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body" id="zoom_details">
                </div>
            </div>
        </form>
    </div>
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
                                        <input type="hidden" name="type" id="type" value="opd" class="form-control" />
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
                                        <div class="col-sm-2 col-xs-4">
                                            <div class="form-group">
                                                <label for="pwd">SPO2</label>
                                                <input name="spo2" class="form-control" id="spo2" type="text"
                                                    class="form-control"
                                                    onkeyup="this.value=this.value.replace(/[^0-9.]/g,'')" />
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label for="exampleInputFile">
                                                    <?php echo $this->lang->line('symptoms_type'); ?></label>
                                                <div><select name='symptoms_type' id="act"
                                                        class="form-control select2 act" style="width:100%">
                                                        <option value=""><?php echo $this->lang->line('select'); ?>
                                                        </option>
                                                        <?php foreach ($symptomsresulttype as $dkey => $dvalue) {
                                                        ?>
                                                            <option value="<?php echo $dvalue["id"]; ?>">
                                                                <?php echo $dvalue["symptoms_type"]; ?></option>

                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <span
                                                    class="text-danger"><?php echo form_error('symptoms_type'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
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
                                        <div class="col-sm-4 col-xs-12">
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
                                                <input name="appointment_date" id="appointmentdate" type="text"
                                                    class="form-control datetime" placeholder="" readonly
                                                    style="pointer-events: none;" />
                                                <span
                                                    class="text-danger"><?php echo form_error('appointment_date'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>
                                                    <?php echo $this->lang->line('case'); ?></label>
                                                <div><input class="form-control" type='text' name="case" id="edit_case"
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
                                                <label>
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
                                                <label>
                                                    <?php echo $this->lang->line('tpa'); ?></label>
                                                <div><select class="form-control" onchange="get_Charges(this.value)"
                                                        id="edit_organisation" name='organisation'>
                                                        <option value="0"><?php echo $this->lang->line('select'); ?>
                                                        </option>
                                                        <?php foreach ($organisation as $orgkey => $orgvalue) {
                                                        ?>
                                                            <option value="<?php echo $orgvalue["id"]; ?>">
                                                                <?php echo $orgvalue["organisation_name"] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <span class="text-danger"><?php echo form_error('refference'); ?></span>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>
                                                    <?php echo $this->lang->line('reference'); ?></label>
                                                <div><input type="text" name="refference" class="form-control"
                                                        id="edit_refference"
                                                        onkeyup="this.value=this.value.replace(/[^a-zA-Z]/g,'')" />
                                                </div>
                                                <span class="text-danger"><?php echo form_error('refference'); ?></span>
                                            </div>
                                        </div>
                                        <input type="hidden" name="opdid" id="edit_opdid">

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


                                        <div class="col-sm-6" id="edit_payment_div">
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
                                        <div class="col-sm-6" id="edit_payment_mode_div">
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
<!-- Timeline -->
<div class="modal fade" id="myTimelineModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="moal-title"><?php echo $this->lang->line('add_timeline'); ?></h4>
            </div>
            <form id="add_timeline" accept-charset="utf-8" enctype="multipart/form-data" method="post" class="">
                <div class="scroll-area">
                    <div class="modal-body pt0 pb0">
                        <div class="ptt10">
                            <div class="row">
                                <div class=" col-md-12">
                                    <div class="form-group">
                                        <label
                                            for="exampleInputEmail1"><?php echo $this->lang->line('title'); ?></label><small
                                            class="req"> *</small>
                                        <input type="hidden" name="patient_id" id="patient_id"
                                            value="<?php echo $id ?>">
                                        <input id="timeline_title" name="timeline_title" placeholder="" type="text"
                                            class="form-control" />
                                        <span class="text-danger"><?php echo form_error('timeline_title'); ?></span>
                                    </div>
                                    <div class="form-group">
                                        <label
                                            for="exampleInputEmail1"><?php echo $this->lang->line('date'); ?></label><small
                                            class="req"> *</small>
                                        <input id="timeline_date" name="timeline_date"
                                            value="<?php echo date("d/m/Y"); ?>" placeholder="" type="text"
                                            class="form-control" />
                                        <span class="text-danger"><?php echo form_error('timeline_date'); ?></span>
                                    </div>
                                    <div class="form-group">
                                        <label
                                            for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label>
                                        <textarea id="timeline_desc" name="timeline_desc" placeholder=""
                                            class="form-control"></textarea>
                                        <span class="text-danger"><?php echo form_error('description'); ?></span>
                                    </div>

                                    <div class="form-group">
                                        <label
                                            for="exampleInputEmail1"><?php echo $this->lang->line('attach_document'); ?></label>
                                        <input id="timeline_doc_id" name="timeline_doc" placeholder="" type="file"
                                            class="filestyle form-control" data-height="40"
                                            value="<?php echo set_value('timeline_doc'); ?>" accept="image/*" />
                                        <span class="text-danger"><?php echo form_error('timeline_doc'); ?></span>
                                    </div>

                                    <div class="form-group">
                                        <label
                                            class="align-top"><?php echo $this->lang->line('visible_to_this_person'); ?></label>
                                        <input id="visible_check" checked="checked" name="visible_check" value="yes"
                                            placeholder="" type="checkbox" />

                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <div class="pull-right">
                        <button type="submit" data-loading-text="<?php echo $this->lang->line('processing') ?>"
                            id="add_timelinebtn" class="btn btn-info pull-right"><i class="fa fa-check-circle"></i>
                            <?php echo $this->lang->line('save'); ?></button>

                    </div>
                </div>

            </form>
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
            <form id="edit_timeline" accept-charset="utf-8" enctype="multipart/form-data" method="post" class="">
                <div class="scroll-area">
                    <div class="modal-body pt0 pb0">
                        <div class="ptt10">
                            <div class="row">
                                <div class=" col-md-12">
                                    <div class="form-group">
                                        <label
                                            for="exampleInputEmail1"><?php echo $this->lang->line('title'); ?></label><small
                                            class="req"> *</small>
                                        <input type="hidden" name="patient_id" id="epatientid" value="">
                                        <input type="hidden" name="timeline_id" id="etimelineid" value="">
                                        <input type="hidden" name="filedataalread" id="filedataalread" value="">
                                        <input id="etimelinetitle" name="timeline_title" placeholder="" type="text"
                                            class="form-control" />
                                        <span class="text-danger"><?php echo form_error('timeline_title'); ?></span>
                                    </div>
                                    <div class="form-group">
                                        <label
                                            for="exampleInputEmail1"><?php echo $this->lang->line('date'); ?></label><small
                                            class="req"> *</small>

                                        <input type="text" name="timeline_date" class="form-control"
                                            id="etimelinedate" />
                                        <span class="text-danger"><?php echo form_error('timeline_date'); ?></span>
                                    </div>
                                    <div class="form-group">
                                        <label
                                            for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label>
                                        <textarea id="timelineedesc" name="timeline_desc" placeholder=""
                                            class="form-control"></textarea>
                                        <span class="text-danger"><?php echo form_error('description'); ?></span>
                                    </div>

                                    <div class="form-group">
                                        <label
                                            for="exampleInputEmail1"><?php echo $this->lang->line('attach_document'); ?></label>
                                        <div class="" style="margin-top:-5px; border:0; outline:none;">
                                            <input id="etimeline_doc_id" name="timeline_doc" placeholder="" type="file"
                                                class="filestyle form-control" data-height="40" value="0" />
                                            <span class="text-danger"><?php echo form_error('timeline_doc'); ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label
                                            class="align-top"><?php echo $this->lang->line('visible_to_this_person'); ?></label>
                                        <input id="evisible_check" name="visible_check" value="yes" placeholder=""
                                            type="checkbox" />

                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                    <!--./modal-body-->
                </div>
                <div class="box-footer">
                    <div class="pull-right">
                        <button type="submit" data-loading-text="<?php echo $this->lang->line('processing'); ?>"
                            id="edit_timelinebtn" class="btn btn-info pull-right"><i class="fa fa-check-circle"></i>
                            <?php echo $this->lang->line('save'); ?></button>

                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
$data = $this->session->userdata('hospitaladmin');
$api_base_url = $this->config->item('api_base_url');
?>
<script>
    $(document).ready(function() {
        $("#edit_timeline").on('submit', function(e) {
            e.preventDefault();

            var formData = new FormData(this);
            var fileInput = document.getElementById('etimeline_doc_id').files[0];
            var documentUrl = $('#filedataalread').val();

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
                    "PATCH", transformedData,
                    function(response) {
                        handleResponse(response);
                    });
            });
        });
    });
</script>

<!-- -->
<div class="modal fade" id="edit_prescription" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('edit_prescription'); ?></h4>
            </div>

            <div class="modal-body pt0 pb0" id="editdetails_prescription">
            </div>
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

                            <!-- <a href="#" data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>"><i class="fa fa-pencil"></i></a> -->
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
                <h4 class="modal-title"><?php echo $this->lang->line('visit_details'); ?></h4>
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
            <div class="scroll-area">
                <div class="modal-body pt0 pb0" id="getdetails_prescription">
                </div>
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

<!-- -->
<div class="modal fade" id="myModaledit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"> <?php echo $this->lang->line('patient_details'); ?></h4>
            </div>
            <div class="modal-body pt0 pb0">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <form id="formeditrecord" accept-charset="utf-8" enctype="multipart/form-data" method="post"
                            class="ptt10">
                            <input id="eupdateid" name="updateid" placeholder="" type="hidden" class="form-control"
                                value="" />
                            <div class="row row-eq">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="row ptt10">

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('name'); ?></label><small
                                                    class="req"> *</small>
                                                <input id="ename" name="name" placeholder="" type="text"
                                                    class="form-control" value="<?php echo set_value('name'); ?>" />
                                                <span class="text-danger"><?php echo form_error('name'); ?></span>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('guardian_name') ?></label>
                                                <input type="text" name="guardian_name" id="eguardian_name"
                                                    placeholder="" value="" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-sm-12">
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label> <?php echo $this->lang->line('gender'); ?></label>
                                                        <select class="form-control" name="gender" id="egenders">
                                                            <option value=""><?php echo $this->lang->line('select'); ?>
                                                            </option>
                                                            <?php
                                                            foreach ($genderList as $key => $value) {
                                                            ?>
                                                                <option value="<?php echo $key; ?>"
                                                                    <?php if (set_value('gender') == $key) echo "selected"; ?>>
                                                                    <?php echo $value; ?></option>
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
                                                        <input type="text" name="dob" id="ebirth_date" placeholder=""
                                                            class="form-control date" /><?php echo set_value('dob'); ?>
                                                    </div>
                                                </div>

                                                <div class="col-sm-5" id="calculate">
                                                    <div class="form-group">
                                                        <label><?php echo $this->lang->line('age') ?></label>
                                                        <div style="clear: both;overflow: hidden;">
                                                            <input type="text"
                                                                placeholder="<?php echo $this->lang->line('year') ?>"
                                                                name="age" id="eage_year" value="" class="form-control"
                                                                style="width: 43%; float: left;">
                                                            <input type="text" id="eage_month"
                                                                placeholder="<?php echo $this->lang->line('month') ?>"
                                                                name="month" value="" class="form-control"
                                                                style="width: 53%;float: left; margin-left: 4px;">
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
                                                        <select class="form-control" id="blood_groups"
                                                            name="blood_group">
                                                            <option value=""><?php echo $this->lang->line('select'); ?>
                                                            </option>
                                                            <?php
                                                            foreach ($bloodgroup as $key => $value) {
                                                            ?>
                                                                <option value="<?php echo $value; ?>"
                                                                    <?php if (set_value('blood_group') == $key) echo "selected"; ?>>
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
                                                        <select name="marital_status" id="marital_statuss"
                                                            class="form-control">
                                                            <option value=""><?php echo $this->lang->line('select') ?>
                                                            </option>
                                                            <?php foreach ($marital_status as $key => $value) {
                                                            ?>
                                                                <option value="<?php echo $value; ?>"
                                                                    <?php if (set_value('marital_status') == $key) echo "selected"; ?>>
                                                                    <?php echo $value; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="exampleInputFile">
                                                            <?php echo $this->lang->line('patient_photo'); ?>
                                                        </label>
                                                        <div>
                                                            <input class="filestyle form-control-file" type='file'
                                                                name='file' id="exampleInputFile" size='20'
                                                                data-height="26"
                                                                data-default-file="<?php echo base_url() ?>uploads/patient_images/no_image.png">
                                                        </div>
                                                        <span
                                                            class="text-danger"><?php echo form_error('file'); ?></span>
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
                                                    value="<?php echo set_value('email'); ?>" name="email"
                                                    class="form-control">
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
                                    </div>
                                    <!--./row-->
                                </div>
                                <!--./col-md-8-->
                            </div>
                            <!--./row-->
                            <!--./row-->
                            <div class="box-footer">
                                <div class="pull-right">
                                    <button type="submit"
                                        data-loading-text="<?php echo $this->lang->line('processing') ?>"
                                        id="formeditrecordbtn"
                                        class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!--./col-md-12-->
                </div>
                <!--./row-->
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="revisitModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog pup100 modalfullmobile" role="document">
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
                                <div class="row row-eq">
                                    <div class="col-lg-8 col-md-8 col-sm-8">
                                        <div id="ajax_load"></div>
                                        <div class="row ptt10" id="patientDetails">
                                            <div class="col-md-9 col-sm-9 col-xs-9">
                                                <input type="hidden" name="password" id="revisit_password">
                                                <input type="hidden" name="patientid" id="pid">
                                                <input type="hidden" name="mobileno" id="pmobileno">
                                                <input type="hidden" name="email" id="pemail">
                                                <ul class="singlelist">
                                                    <li class="singlelist24bold pt5">
                                                        <span id="listname"></span>
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
                                                        <img id="gender_icon" src="" alt="Gender Icon"
                                                            data-toggle="tooltip" data-placement="top"
                                                            title="<?php echo $this->lang->line('gender'); ?>"
                                                            style="width: 20px; height: 20px;">
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
                                                        <b><?php echo $this->lang->line('any_known_allergies'); ?></b>
                                                        <span id="any_known_allergies"></span>
                                                    </li>
                                                    <li>
                                                        <b><?php echo $this->lang->line('remarks'); ?></b>
                                                        <span id="remarks"></span>
                                                    </li>
                                                    <li>
                                                        <b><?php echo $this->lang->line('tpa_id'); ?></b>
                                                        <span id="tpa_id"></span>
                                                    </li>
                                                    <li>
                                                        <b><?php echo $this->lang->line('tpa_validity'); ?></b>
                                                        <span id="tpa_validity"></span>
                                                    </li>
                                                    <li>
                                                        <b><?php echo $this->lang->line('national_identification_number'); ?></b>
                                                        <span id="identification_number"></span>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="col-md-3 col-sm-3 col-xs-3">
                                                <img width="115" height="115"
                                                    class="profile-user-img img-responsive img-rounded" src=""
                                                    id="patient_image">
                                            </div><!-- ./col-md-3 -->
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-2 col-xs-4">
                                                <div class="form-group">
                                                    <label for="pwd"><?php echo $this->lang->line('height'); ?></label>
                                                    <input name="height" id="revisit_height" type="text"
                                                        class="form-control" value="<?php echo set_value('height'); ?>"
                                                        onkeyup="this.value=this.value.replace(/[^0-9.]/g,'');" />
                                                </div>
                                            </div>
                                            <div class="col-sm-2 col-xs-4">
                                                <div class="form-group">
                                                    <label for="pwd"><?php echo $this->lang->line('weight'); ?></label>
                                                    <input name="weight" id="revisit_weight" type="text"
                                                        class="form-control" value="<?php echo set_value('weight'); ?>"
                                                        onkeyup="this.value=this.value.replace(/[^0-9.]/g,'');" />
                                                </div>
                                            </div>
                                            <div class="col-sm-2 col-xs-4">
                                                <div class="form-group">
                                                    <label for="pwd"><?php echo $this->lang->line('bp'); ?></label>
                                                    <input name="bp" type="text" id="revisit_bp" class="form-control"
                                                        value="<?php echo set_value('bp'); ?>"
                                                        onkeyup="this.value=this.value.replace(/[^0-9/]/g,'');" />
                                                </div>
                                            </div>
                                            <div class="col-sm-2 col-xs-4">
                                                <div class="form-group">
                                                    <label for="pwd"><?php echo $this->lang->line('pulse'); ?></label>
                                                    <input name="pulse" id="revisit_pulse" type="text"
                                                        class="form-control"
                                                        onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" />
                                                </div>
                                            </div>
                                            <div class="col-sm-2 col-xs-4">
                                                <div class="form-group">
                                                    <label
                                                        for="pwd"><?php echo $this->lang->line('temperature'); ?></label>
                                                    <input name="temperature" id="revisit_temperature" type="text"
                                                        class="form-control"
                                                        onkeyup="this.value=this.value.replace(/[^0-9.]/g,'');" />
                                                </div>
                                            </div>
                                            <div class="col-sm-2 col-xs-4">
                                                <div class="form-group">
                                                    <label
                                                        for="pwd"><?php echo $this->lang->line('respiration'); ?></label>
                                                    <input name="respiration" id="revisit_respiration" type="text"
                                                        class="form-control"
                                                        onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" />
                                                </div>
                                            </div>
                                            <div class="col-sm-2 col-xs-4">
                                                <div class="form-group">
                                                    <label for="Sp02">SpO2</label>
                                                    <input name="Sp02" type="text" id="revisit_sqo2"
                                                        class="form-control"
                                                        onkeyup="this.value=this.value.replace(/[^0-9]/g,'')" />
                                                </div>
                                            </div>
                                            <div class="col-sm-3 col-xs-6">
                                                <div class="form-group">
                                                    <label
                                                        for="act"><?php echo $this->lang->line('symptoms_type'); ?></label>
                                                    <div>
                                                        <select name='symptoms_type' id="act"
                                                            class="form-control select2 act" style="width:100%">
                                                            <option value=""><?php echo $this->lang->line('select') ?>
                                                            </option>
                                                            <?php foreach ($symptomsresulttype as $dkey => $dvalue) { ?>
                                                                <option value="<?php echo $dvalue["id"]; ?>">
                                                                    <?php echo $dvalue["symptoms_type"]; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    <span
                                                        class="text-danger"><?php echo form_error('symptoms_type'); ?></span>
                                                </div>
                                            </div>
                                            <div class="col-sm-3 col-xs-6">
                                                <label
                                                    for="filterinput"><?php echo $this->lang->line('symptoms_title'); ?></label>
                                                <div id="dd" class="wrapper-dropdown-3">
                                                    <input class="form-control filterinput" type="text">
                                                    <ul class="dropdown scroll150 section_ul">
                                                        <li><label
                                                                class="checkbox"><?php echo $this->lang->line('select'); ?></label>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="col-sm-4 col-xs-12">
                                                <div class="form-group">
                                                    <label
                                                        for="email"><?php echo $this->lang->line('symptoms_description'); ?></label>
                                                    <textarea name="symptoms" id="esymptoms"
                                                        class="form-control"><?php echo set_value('symptoms'); ?></textarea>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-xs-12">
                                                <div class="form-group">
                                                    <label
                                                        for="email"><?php echo $this->lang->line('any_known_allergies'); ?></label>
                                                    <textarea name="known_allergies" id="revisit_allergies"
                                                        class="form-control"><?php echo set_value('known_allergies'); ?></textarea>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-xs-12">
                                                <div class="form-group">
                                                    <label for="pwd"><?php echo $this->lang->line('note'); ?></label>
                                                    <textarea name="note_remark" id="revisit_note"
                                                        class="form-control"><?php echo set_value('note_remark'); ?></textarea>
                                                </div>
                                            </div>
                                            <div class="">
                                                <?php echo display_custom_fields('opd'); ?>
                                            </div>
                                        </div>

                                        <!--./row-->
                                    </div>


                                    <div class="col-lg-4 col-md-4 col-sm-4 col-eq ptt10">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label><?php echo $this->lang->line('appointment_date'); ?></label>
                                                    <small class="req"> *</small>
                                                    <input id="appointment_date" name="appointment_date" placeholder=""
                                                        type="text" class="form-control datetime" disabled
                                                        value="<?php echo date('d/m/Y'); ?>" />
                                                    <span
                                                        class="text-danger"><?php echo form_error('appointment_date'); ?></span>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="revisit_case">
                                                        <?php echo $this->lang->line('case'); ?></label>
                                                    <!-- <small class="req"> *</small> -->
                                                    <div><input class="form-control" type='text' id="revisit_case"
                                                            name='case'
                                                            onkeyup="this.value=this.value.replace(/[^a-zA-Z]/g,'')" />
                                                    </div>
                                                    <span class="text-danger"><?php echo form_error('case'); ?></span>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="casualty">
                                                        <?php echo $this->lang->line('casualty'); ?></label>
                                                    <div>

                                                        <select name="casualty" class="form-control casualty">
                                                            <?php foreach ($yesno_condition as $yesno_key => $yesno_value) {
                                                            ?>
                                                                <option value="<?php echo $yesno_key ?>" <?php
                                                                                                            if ($yesno_key == 'no') {
                                                                                                                echo "selected";
                                                                                                            }
                                                                                                            ?>>
                                                                    <?php echo $yesno_value ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    <span
                                                        class="text-danger"><?php echo form_error('casualty'); ?></span>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="old_patient">
                                                        <?php echo $this->lang->line('old_patient'); ?></label>
                                                    <div>

                                                        <select name="old_patient" class="form-control">
                                                            <?php foreach ($yesno_condition as $yesno_key => $yesno_value) {
                                                            ?>
                                                                <option value="<?php echo $yesno_key ?>" <?php
                                                                                                            if ($yesno_key == 'no') {
                                                                                                                echo "selected";
                                                                                                            }
                                                                                                            ?>>
                                                                    <?php echo $yesno_value ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    <span
                                                        class="text-danger"><?php echo form_error('old_patient'); ?></span>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="rorganisation">
                                                        <?php echo $this->lang->line('tpa'); ?></label>
                                                    <div><select class="form-control" onchange="get_Charges(this.value)"
                                                            id="rorganisation" name='organisation'>
                                                            <option value="0"><?php echo $this->lang->line('select') ?>
                                                            </option>
                                                            <?php foreach ($organisation as $orgkey => $orgvalue) {
                                                            ?>
                                                                <option value="<?php echo $orgvalue["id"]; ?>" <?php
                                                                                                                if ((isset($org_select)) && ($org_select == $orgvalue["id"])) {
                                                                                                                    echo "selected";
                                                                                                                }
                                                                                                                ?>>
                                                                    <?php echo $orgvalue["organisation_name"]; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    <span
                                                        class="text-danger"><?php echo form_error('refference'); ?></span>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="revisit_refference">
                                                        <?php echo $this->lang->line('reference'); ?></label>
                                                    <div><input class="form-control" type='text' id="revisit_refference"
                                                            name='refference'
                                                            onkeyup="this.value=this.value.replace(/[^a-zA-Z]/g,'')" />
                                                    </div>
                                                    <span
                                                        class="text-danger"><?php echo form_error('refference'); ?></span>
                                                </div>
                                            </div>

                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="consultant_doctor">
                                                        <?php echo $this->lang->line('consultant_doctor'); ?></label><small
                                                        class="req"> *</small>
                                                    <div><select name='consultant_doctor' id="consultant_doctor"
                                                            class="form-control select2" <?php
                                                                                            if ($disable_option == true) {
                                                                                                echo "disabled";
                                                                                            }
                                                                                            ?> style="width:100%">
                                                            <option value="" disabled selected>
                                                                <?php echo $this->lang->line('select'); ?>
                                                            </option>
                                                            <?php foreach ($doctors as $dkey => $dvalue) {
                                                            ?>
                                                                <option value="<?php echo $dvalue["id"]; ?>" <?php
                                                                                                                if ((isset($doctor_select)) && ($doctor_select == $dvalue["id"])) {
                                                                                                                    echo "selected";
                                                                                                                }
                                                                                                                ?>>
                                                                    <?php echo $dvalue["name"] . " " . $dvalue["surname"] . " (" . $dvalue["employee_id"] . ")" ?>
                                                                </option>
                                                            <?php } ?>
                                                        </select>
                                                        <?php if ($disable_option == true) { ?>
                                                            <input type="hidden" name="consultant_doctor"
                                                                value="<?php echo $doctor_select ?>">
                                                        <?php } ?>
                                                    </div>
                                                    <span
                                                        class="text-danger"><?php echo form_error('consultant_doctor'); ?></span>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label> <?php echo $this->lang->line('charge_category'); ?></label>
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
                                                    <label
                                                        for="exampleInputEmail1"><?php echo $this->lang->line('tax'); ?></label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control right-border-none"
                                                            name="percentage" id="percentage" readonly
                                                            autocomplete="off">
                                                        <span class="input-group-addon "> %</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><?php echo $this->lang->line('standard_charge') . " (" . $currency_symbol . ")" ?></label>
                                                    <input type="text" readonly name="standard_charge"
                                                        id="standard_charge" class="form-control"
                                                        value="<?php echo set_value('standard_charge'); ?>">

                                                    <span
                                                        class="text-danger"><?php echo form_error('standard_charge'); ?></span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><?php echo $this->lang->line('applied_charge') . " (" . $currency_symbol . ")" ?></label><small
                                                        class="req"> *</small><input type="text" name="amount"
                                                        id="apply_charge" onkeyup="update_amount(this.value)"
                                                        class="form-control" readonly>
                                                    <span
                                                        class="text-danger"><?php echo form_error('apply_charge'); ?></span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><?php echo $this->lang->line('amount') . " (" . $currency_symbol . ")" ?></label><small
                                                        class="req"> *</small><input type="text" name="apply_amount"
                                                        readonly id="apply_amount" class="form-control">

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
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><?php echo $this->lang->line('paid_amount'); ?></label><small
                                                        class="req"> *</small>
                                                    <input type="text" name="paid_amount" id="paid_amount"
                                                        class="form-control"
                                                        oninput="this.value = this.value.replace(/[^0-9.]/g, ''); checkpaylater(this.value);">
                                                    <span class="text-danger" id="amountchecker"></span>
                                                </div>
                                            </div>
                                            <div class="cheque_div" style="display: none;">

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
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label
                                                            for="document_file"><?php echo $this->lang->line('attach_document'); ?></label>
                                                        <input type="file" id="document_file"
                                                            class="filestyle form-control" name="document">
                                                        <span
                                                            class="text-danger"><?php echo form_error('document'); ?></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <?php
                                            if ($this->module_lib->hasActive('live_consultation')) { ?>
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label for="live_consult">
                                                            <?php echo $this->lang->line('live_consultation'); ?></label>
                                                        <div>
                                                            <select name="live_consult" id="live_consult"
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
                                            <?php  } ?>

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
                        <button type="submit" id="formrevisitbtn" name="save"
                            data-loading-text="<?php echo $this->lang->line('processing') ?>"
                            class="btn btn-info pull-right"><i class="fa fa-check-circle"></i>
                            <span><?php echo $this->lang->line('save'); ?></span></button>
                    </div>

                    <div class="pull-right" style="margin-right: 10px; ">
                        <button type="submit" data-loading-text="<?php echo $this->lang->line('processing') ?>"
                            id="get_print" name="save_print" class="btn btn-info pull-right printsavebtn"><i
                                class="fa fa-print"></i> <?php echo $this->lang->line('save_print'); ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- -->
<!-- Add Diagnosis -->
<div class="modal fade" id="add_diagnosis" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('add_diagnosis'); ?></h4>
            </div>
            <form id="form_diagnosis" accept-charset="utf-8" enctype="multipart/form-data" method="post" class="">
                <div class="scroll-area">
                    <div class="modal-body pt0 pb0">
                        <div class="ptt10">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>
                                            <?php echo $this->lang->line('report_type'); ?></label><small class="req">
                                            *</small>
                                        <input type="text" name="report_type" class="form-control" id="report_type" />
                                        <input type="hidden" value="<?php echo $id ?>" name="patient"
                                            class="form-control" id="patient" />


                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>
                                            <?php echo $this->lang->line('report_date'); ?></label><small class="req">
                                            *</small>
                                        <input type="text" name="report_date" class="form-control date"
                                            id="report_date" />
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label
                                            for="report_document"><?php echo $this->lang->line('document'); ?></label>
                                        <input type="file" class="form-control filestyle" name="report_document"
                                            id="report_document" />
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('report_center_name'); ?></label> <input
                                            type="text" class="form-control" name="report_center" id="report_center" />
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('description'); ?></label>
                                        <textarea name="description" class="form-control" id="description"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <div class="pull-right">
                        <button type="submit" id="form_diagnosisbtn"
                            data-loading-text="<?php echo $this->lang->line('processing'); ?>"
                            class="btn btn-info pull-right"><i class="fa fa-check-circle"></i>
                            <?php echo $this->lang->line('save'); ?></button>

                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Diagnosis -->
<div class="modal fade" id="edit_diagnosis" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('edit_diagnosis'); ?></h4>
            </div>
            <form id="form_editdiagnosis" accept-charset="utf-8" enctype="multipart/form-data" method="post">
                <div class="modal-body pt0 pb0">
                    <div class="ptt10">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>
                                        <?php echo $this->lang->line('report_type'); ?></label><small class="req">
                                        *</small>
                                    <input type="text" name="report_type" class="form-control" id="ereporttype" />
                                    <input type="hidden" value="" name="diagnosis_id" class="form-control" id="eid" />
                                    <input type="hidden" value="" name="diagnosispatient_id" class="form-control"
                                        id="epatient_id" />
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>
                                        <?php echo $this->lang->line('report_date'); ?></label><small class="req">
                                        *</small>
                                    <input type="text" name="report_date" class="form-control date" id="ereportdate" />
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="ereportdocument"><?php echo $this->lang->line('document'); ?></label>
                                    <input type="file" class="form-control filestyle" name="report_document"
                                        id="ereportdocument" />
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line("report_center_name"); ?></label>
                                    <input type="text" name="report_center" class="form-control" id="ereportcenter" />
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('description'); ?></label>
                                    <textarea name="description" class="form-control" id="edescription"></textarea>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <div class="pull-right">
                        <button type="submit" id="form_editdiagnosisbtn"
                            data-loading-text="<?php echo $this->lang->line('processing'); ?>"
                            class="btn btn-info pull-right"><i class="fa fa-check-circle"></i>
                            <?php echo $this->lang->line('save'); ?></button>

                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="myPaymentModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('add_payment'); ?></h4>
            </div>
            <form id="add_payment" accept-charset="utf-8" method="post" class="">
                <div class="modal-body pt0 pb0">
                    <div class="ptt10">
                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('amount') . " (" . $currency_symbol . ")"; ?></label><small
                                        class="req"> *</small>
                                    <input type="text" name="amount" id="amount" class="form-control">
                                    <input type="hidden" name="patient_id" id="payment_patient_id" class="form-control">
                                    <input type="hidden" name="total" id="total" class="form-control">
                                    <span class="text-danger"><?php echo form_error('amount'); ?></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('payment_mode'); ?></label>
                                    <select class="form-control" name="payment_mode">

                                        <?php foreach ($payment_mode as $key => $value) {
                                        ?>
                                            <option value="<?php echo $key ?>" <?php
                                                                                if ($key == 'cash') {
                                                                                    echo "selected";
                                                                                }
                                                                                ?>><?php echo $value ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('payment_mode'); ?></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('date'); ?></label><small class="req"> *</small>
                                    <input type="text" name="payment_date" id="date" class="form-control date">
                                    <span class="text-danger"><?php echo form_error('payment_date'); ?></span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label
                                        for="file_document"><?php echo $this->lang->line('attach_document'); ?></label>
                                    <input type="file" class="filestyle form-control" name="document">
                                    <span class="text-danger"><?php echo form_error('document'); ?></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('note'); ?></label>
                                    <input type="text" name="note" id="note" class="form-control" />
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" id="add_paymentbtn"
                        data-loading-text="<?php echo $this->lang->line('processing') ?>"
                        class="btn btn-info pull-right"><i class="fa fa-check-circle"></i>
                        <?php echo $this->lang->line('save'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- -->

<!-- Add Prescription -->
<div class="modal fade" id="add_prescription" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog pup100" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close pupclose" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="prescription_title"></h4>
            </div>
            <form id="form_prescription" accept-charset="utf-8" enctype="multipart/form-data" method="post">
                <div class="pup-scroll-area">
                    <div class="modal-body pt0 pb0">
                    </div>
                    <!--./modal-body-->
                </div>
                <div class="modal-footer sticky-footer">
                    <div class="pull-right">
                        <button type="submit" name="save_print" value="save_print"
                            data-loading-text="<?php echo $this->lang->line('processing'); ?>" class="btn btn-info"><i
                                class="fa fa-print"></i> <?php echo $this->lang->line('save_print'); ?>
                        </button>
                        <button type="submit" name="save" value="save"
                            data-loading-text="<?php echo $this->lang->line('processing'); ?>" class="btn btn-info"><i
                                class="fa fa-check-circle"></i> <?php echo $this->lang->line('save'); ?>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div><!-- Add Prescription -->


<div class="modal fade" id="moveIPDModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog pup100 modalfullmobile" role="document">
        <form id="form_confirm-move" method="POST" accept-charset="utf-8">
            <div class="modal-content modal-media-content">
                <div class="modal-header modal-media-header">
                    <button type="button" class="close pupclose" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><?php echo $this->lang->line('move_patient_to_ipd'); ?></h4>
                </div>
                <div class="pup-scroll-area">
                    <div class="modal-body pt0 pb0">
                        <p><?php echo $this->lang->line('some_text_in_the_modal'); ?></p>
                    </div>
                </div>
                <div class="modal-footer sticky-footer">
                    <button type="button" class="btn btn-default"
                        data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
                    <button type="submit" data-loading-text="<?php echo $this->lang->line('processing'); ?>"
                        class="btn btn-info pull-right btn-ok" id="ipd_move"><i class="fa fa-check-circle"></i>
                        <?php echo $this->lang->line('move'); ?></button>
                </div>
            </div>
        </form>
    </div>
</div>
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
<script type="text/javascript">
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
</script>
<script>
    var prescription_rows = 2;
    $(document).on('change', '.act', function() {

        $this = $(this);
        var sys_val = $(this).val();
        var section_ul = $(this).closest('div.row').find('ul.section_ul');
        $.ajax({
            type: 'POST',
            url: base_url + 'admin/patient/getPartialsymptoms',
            data: {
                'sys_id': sys_val
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
</script>
<script type="text/javascript">
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
<?php
$data = $this->session->userdata('hospitaladmin');
$api_base_url = $this->config->item('api_base_url');
?>
<script type="text/javascript">
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
            });
        });

    });

    $(function() {
        $("#compose-textareas,#compose-textareanew").wysihtml5({
            toolbar: {
                "image": false,
            }
        });
    });

    function edit_prescription(id) {
        $("#prescription_title").html('<?php echo $this->lang->line('edit_prescription'); ?>');
        $.ajax({
            url: base_url + 'admin/prescription/editopdPrescription',
            dataType: 'JSON',
            data: {
                'prescription_id': id
            },
            type: "POST",
            beforeSend: function() {

            },
            success: function(res) {
                $('#prescriptionview').modal('hide');
                $('.modal-body', "#add_prescription").html(res.page);
                var medicineTable = $('.modal-body', "#add_prescription").find('table#tableID');
                medicineTable.find('.select2').select2();
                $('.modal-body', "#add_prescription").find('.multiselect2').select2({
                    placeholder: 'Select',
                    allowClear: false,
                    minimumResultsForSearch: 2
                });
                prescription_rows = medicineTable.find('tr').length + 1;

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
                $("#compose-textareas,#compose-textareanew").wysihtml5({
                    toolbar: {
                        "image": false,
                    }
                });

            },
            error: function(xhr) { // if error occured
                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");


            }
        });
    }
    $('#modal-chkstatus').on('shown.bs.modal', function(e) {
        var $modalDiv = $(e.delegateTarget);
        // var id=$(this).data();
        var id = $(e.relatedTarget).data('id');

        $.ajax({
            type: "POST",
            url: base_url + 'admin/conference/getlivestatus',
            data: {
                'id': id
            },
            dataType: "JSON",
            beforeSend: function() {
                $('#zoom_details').html("");
                $modalDiv.addClass('modal_loading');
            },
            success: function(data) {

                $('#zoom_details').html(data.page);
                $modalDiv.removeClass('modal_loading');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $modalDiv.removeClass('modal_loading');
            },
            complete: function(data) {
                $modalDiv.removeClass('modal_loading');
            }
        });
    })


    $(document).on('click', '.print_visit_bill', function() {

        var opd_id = $(this).data('opdId');

        var $this = $(this);

        $.ajax({
            url: base_url + 'admin/patient/printbill',
            type: "POST",
            data: {
                opd_id: opd_id,
                type: "all",
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



    $(document).on('click', '.get_opd_detail', function() {
        var visitid = $(this).data('recordId');
        var opdid = $(this).data('opdId')
        var $this = $(this);
        $.ajax({
            url: base_url + 'admin/patient/getopdDetails',
            type: "POST",
            data: {
                visit_id: visitid,
                opd_id: opdid
            },
            dataType: 'json',
            beforeSend: function() {
                $this.button('loading');
            },
            success: function(data) {
                var patient_id = "<?php echo $result["id"] ?>";
                var visitid = "<?= $result["OPD_checkup_id"] ?>";
                $('#edit_delete').html(
                    "<?php if ($this->rbac->hasPrivilege('visit', 'can_edit')) { ?><a href='#'' onclick='editRecord(" +
                    data.visitid +
                    ")' data-target='#editModal' data-toggle='tooltip'  data-original-title='<?php echo $this->lang->line('edit'); ?>'><i class='fa fa-pencil'></i></a><?php } ?><?php if ($this->rbac->hasPrivilege('visit', 'can_delete')) { ?><a href='#' data-toggle='tooltip' data-patient_id=" +
                    patient_id + " data-record-id=" + opdid +
                    " class='delete_opd' data-original-title='<?php echo $this->lang->line('delete'); ?>'><i class='fa fa-trash'></i></a><?php } ?>"
                );
                $('#viewModal .modal-body').html(data.page);
                $('#viewModal').modal('show');

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

    $(document).on('click', '.delete_opd', function() {
        let patient_id = $(this).data('patient_id');
        let id = $(this).data('record-id');
        if (confirm(<?php echo "'" . $this->lang->line('delete_confirm') . "'"; ?>)) {
            let apiUrl = '<?= $api_base_url ?>internal-opd-overview-visits/' + id;
            sendAjaxRequest(apiUrl, 'DELETE', {}, function(response) {
                handleResponse(response, false, 'printVisitBill');
            });
        }

    });


    function delete_patient(id) {
        if (confirm(<?php echo "'" . $this->lang->line('delete_confirm') . "'"; ?>)) {
            $.ajax({
                url: '<?php echo base_url(); ?>admin/patient/deleteOPDPatient',
                type: "POST",
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(data) {
                    successMsg(<?php echo "'" . $this->lang->line('delete_message') . "'"; ?>);
                    window.location.href = '<?php echo base_url() ?>admin/patient/search';
                }
            })
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
                $("#ename").val(data.patient_name);
                $("#eguardian_name").val(data.guardian_name);
                $("#emobileno").val(data.mobileno);
                $("#eemail").val(data.email);
                $("#eaddress").val(data.address);
                $("#eage_year").val(data.age);
                $("#eage_month").val(data.month);
                $("#ebirth_date").val(data.dob);
                $("#enote").val(data.note);
                $("#exampleInputFile").attr("data-default-file", '<?php echo base_url() ?>' + data.image);
                $(".dropify-render").find("img").attr("src", '<?php echo base_url() ?>' + data.image);
                $("#eknown_allergies").val(data.known_allergies);
                $('select[id="blood_groups"] option[value="' + data.blood_group + '"]').attr("selected",
                    "selected");
                $('select[id="egenders"] option[value="' + data.gender + '"]').attr("selected", "selected");
                $('select[id="marital_statuss"] option[value="' + data.marital_status + '"]').attr("selected",
                    "selected");
                $("#myModal").modal('hide');
                holdModal('myModaledit');

            },
        });
    }

    function getRecord_id(visitid) {

        $('#prescription_title').html('<?php echo $this->lang->line('add_prescription'); ?>');
        $.ajax({
            url: base_url + 'admin/prescription/addopdPrescription',
            dataType: 'JSON',
            data: {
                'visit_detail_id': visitid
            },
            type: "POST",
            beforeSend: function() {},
            success: function(res) {
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
            error: function(xhr) { // if error occured
                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");


            }
        });
    }

    function editRecord(visitid) {

        $.ajax({
            url: '<?php echo base_url(); ?>admin/patient/getopdvisitdetails',
            type: "GET",
            data: {
                visitid: visitid
            },
            dataType: 'json',
            success: function(data) {

                $('#visitid').val(visitid);
                $('#visit_transaction_id').val(data.transaction_id);
                $('#customfield').html(data.custom_fields_value);
                $("#patientid").val(data.patient_id);
                $("#patientname").val(data.patient_name);
                let dateStr = data.appointment_date.split(" ")[0];
                let [month, day, year] = dateStr.split("/");
                let formattedDate = `${day}/${month}/${year}`;
                $("#appointmentdate").val(formattedDate);
                $("#edit_case").val(data.case_type);
                $("#symptoms_description").val(data.symptoms);
                $("#edit_casualty").val(data.casualty);
                $("#edit_knownallergies").val(data.known_allergies);
                $("#edit_refference").val(data.refference);
                $("#edit_revisit_note").html(data.note);
                $("#edit_amount").val(data.apply_charge);
                $('select[id="edit_oldpatient"] option[value="' + data.patient_old + '"]').attr("selected",
                    "selected");
                $("#edit_height").val(data.height);
                $("#edit_weight").val(data.weight);
                $("#edit_bp").val(data.bp);
                $("#edit_pulse").val(data.pulse);
                $("#edit_temperature").val(data.temperature);
                $("#edit_respiration").val(data.respiration);
                $("#edit_opdid").val(data.opdid);
                $("#spo2").val(data.spo2);
                $("#eknown_allergies").val(data.visit_known_allergies);
                $("#edit_visit_payment_date").val((d => isNaN(d.getTime()) ? "" :
                    `${String(d.getDate()).padStart(2, '0')}/${String(d.getMonth() + 1).padStart(2, '0')}/${d.getFullYear()}`
                )(new Date(data.payment_date)));
                $("#edit_visit_payment").val(data.amount);
                $("#visit_payment_mode").val(data.payment_mode).prop('selected');
                $(".visit_payment_mode").trigger('change');
                $("#edit_visit_cheque_no").val(data.cheque_no);
                $("#edit_visit_cheque_date").val(data.cheque_date);
                $("#edit_visit_payment_note").val(data.payment_note);
                $("#viewModal").modal('hide');
                $('select[id="edit_organisation"] option[value="' + data.organisation_id + '"]').attr(
                    "selected", "selected");
                $("#edit_organisation").prop('readonly', true);
                $('select[id="edit_consdoctor"] option[value="' + data.cons_doctor + '"]').attr("selected",
                    "selected");
                $(".select2").select2().select2('val', data.cons_doctor);
                if (data.payment_mode == null) {
                    $('#edit_payment_div').css("display", "none");
                    $('#edit_payment_mode_div').css("display", "none");
                    $("#visit_payment_mode").val('Paylater').prop('selected');
                }
                holdModal('editModal');
            },
        });
    }

    function editDiagnosis(id) {
        $.ajax({
            url: '<?php echo base_url(); ?>admin/patient/editDiagnosis',
            type: "POST",
            data: {
                id: id
            },
            dataType: 'json',
            success: function(data) {

                $("#eid").val(data.id);
                $("#epatient_id").val(data.patient_id);
                $("#ereporttype").val(data.report_type);
                $("#ereportdate").val(data.report_date);
                $("#ereportcenter").val(data.report_center);
                $("#edescription").val(data.description);
                holdModal('edit_diagnosis');

            },
        });
    }

    $(document).ready(function() {
        $("#formedit").on('submit', async function(e) {
            e.preventDefault();
            try {
                let formData = new FormData(this);
                let formDataObj = {};
                formData.forEach((value, key) => {
                    formDataObj[key] = (typeof value === 'string') ? value.trim() : value;
                });
                let mappedObject = {
                    cons_doctor: parseInt(formDataObj.consultant_doctor) || "",
                    case_type: formDataObj.case || "",
                    appointment_date: formatDateTime(formDataObj.appointment_date) || "",
                    symptoms_type: parseInt(formDataObj.symptoms_type) || null,
                    symptoms: formDataObj.symptoms || "",
                    bp: parseFloat(formDataObj.bp) || "",
                    height: parseFloat(formDataObj.height) || "",
                    weight: parseFloat(formDataObj.weight) || "",
                    pulse: parseInt(formDataObj.pulse) || "",
                    temperature: parseFloat(formDataObj.temperature) || "",
                    respiration: parseInt(formDataObj.respiration) || "",
                    known_allergies: formDataObj.known_allergies || "",
                    patient_old: formDataObj.old_patient || "",
                    casualty: formDataObj.casualty || "",
                    refference: formDataObj.refference || "",
                    note: formDataObj.note || "",
                    payment_mode: formDataObj.payment_mode || "Cash",
                    generated_by: <?= $data['id'] ?> || "",
                    live_consult: formDataObj.live_consult || "",
                    can_delete: "yes",
                    payment_date: extractDate(formDataObj.appointment_date) || "",
                    time: extractTime(formDataObj.appointment_date) || "",
                    standard_charge: parseFloat(formDataObj.standard_charge) || "",
                    charge_id: parseInt(formDataObj.charge_id) || "",
                    tax: parseFloat(formDataObj.percentage) || "",
                    apply_charge: parseFloat(formDataObj.apply_amount) || "",
                    amount: parseFloat(formDataObj.amount) || "",
                    patient_id: parseInt(formDataObj.patientid) || "",
                    Hospital_id: <?= $data['hospital_id'] ?> || "",
                    spo2: parseInt(formDataObj.spo2) || "",
                    received_by_name: '<?= $data['username'] ?>'
                };
                let requiredFields = ['appointment_date', 'casualty', 'consultant_doctor'];
                let errorMessages = [];
                requiredFields.forEach(field => {
                    if (!formDataObj[field]) {
                        errorMessages.push(field);
                        $(`[name="${field}"]`).addClass("is-invalid");
                    } else {
                        $(`[name="${field}"]`).removeClass("is-invalid");
                    }
                });
                if (errorMessages.length > 0) {
                    errorMsg(`Please fill all required fields: ${errorMessages.join(', ')}`);
                    return;
                }
                if (formDataObj.organisation != 0) {
                    mappedObject['organisation_id'] = parseInt(formDataObj.organisation) || "";
                    mappedObject['tpa_charge'] = 0.00;
                }
                sendAjaxRequest(
                    `<?= $api_base_url ?>internal-opd-overview-visits/${formDataObj.visitid}`,
                    "PATCH",
                    mappedObject,
                    function(response) {
                        handleResponse(response, false, 'printVisitBill');
                    }
                );
            } catch (error) {
                console.error("Unexpected error occurred:", error);
                errorMsg("An unexpected error occurred. Please try again.");
            }
        });

        function parseDateString(dateString) {
            if (!dateString) return null;
            let parts;
            if (dateString.includes("/")) {
                parts = dateString.split("/");
                if (parts[0].length === 2) {
                    return new Date(`${parts[2]}-${parts[1]}-${parts[0]}`);
                }
            } else if (dateString.includes("-")) {
                return new Date(dateString);
            }
            return new Date(dateString);
        }

        function formatDateTime(dateString) {
            let date = parseDateString(dateString);
            if (isNaN(date)) return "";
            let yyyy = date.getFullYear();
            let mm = String(date.getMonth() + 1).padStart(2, '0');
            let dd = String(date.getDate()).padStart(2, '0');
            let hh = String(date.getHours()).padStart(2, '0');
            let mi = String(date.getMinutes()).padStart(2, '0');
            let ss = String(date.getSeconds()).padStart(2, '0');
            return `${yyyy}-${mm}-${dd} ${hh}:${mi}:${ss}`;
        }

        function extractDate(dateString) {
            let date = parseDateString(dateString);
            if (isNaN(date)) return "";
            let yyyy = date.getFullYear();
            let mm = String(date.getMonth() + 1).padStart(2, '0');
            let dd = String(date.getDate()).padStart(2, '0');
            return `${yyyy}-${mm}-${dd}`;
        }

        function extractTime(dateString) {
            let date = parseDateString(dateString);
            if (isNaN(date)) return "";
            let hh = String(date.getHours()).padStart(2, '0');
            let mi = String(date.getMinutes()).padStart(2, '0');
            let ss = String(date.getSeconds()).padStart(2, '0');
            return `${hh}:${mi}:${ss}`;
        }
    });





    $(document).ready(function(e) {
        $("form#form_prescription button[type=submit]").click(function() {
            $("button[type=submit]", $(this).parents("form")).removeAttr("clicked");
            $(this).attr("clicked", "true");
        });
        $("#form_prescription").on("submit", function(e) {
            var sub_btn_clicked = $("button[type=submit][clicked=true]");
            var sub_btn_clicked_name = sub_btn_clicked.attr('name');
            e.preventDefault();
            const formData = $(this).serializeArray();
            const mappedData = {
                visit_details_id: parseInt(getValue(formData, "visit_details_id"), 10),
                header_note: getValue(formData, "header_note"),
                footer_note: getValue(formData, "footer_note"),
                finding_description: getValue(formData, "finding_description"),
                is_finding_print: getValue(formData, "finding_print"),
                prescribe_by: <?= $data["id"] ?>,
                prescriptions: [],
                prescriptionTest: [],
                Hospital_id: <?= $data["hospital_id"] ?>,
            };
            const rows = formData.filter((item) => item.name === "rows[]").map((item) => item.value);
            rows.forEach((row) => {
                const prescription = {
                    dosage: getValue(formData, `dosage_${row}`),
                    pharmacy_id: parseInt(getValue(formData, `medicine_${row}`), 10),
                    dose_interval_id: parseInt(getValue(formData, `interval_dosage_${row}`),
                        10),
                    dose_duration_id: parseInt(getValue(formData, `duration_dosage_${row}`),
                        10),
                    instruction: getValue(formData, `instruction_${row}`),
                };
                mappedData.prescriptions.push(prescription);
            });
            const pathologies = formData.filter((item) => item.name === "pathology[]").map((item) => item
                .value);
            const visibles = formData.filter((item) => item.name === "radiology[]").map((item) => item
                .value);
            pathologies.forEach((pathology, index) => {
                const test = {
                    pathology_id: parseInt(pathology, 10),
                    radiology_id: parseInt(visibles[index] || 0, 10),
                };
                mappedData.prescriptionTest.push(test);
            });
            console.log(JSON.stringify(mappedData, null, 2));
            $.ajax({
                url: '<?= $api_base_url ?>opd-prescription',
                type: "POST",
                data: JSON.stringify(mappedData),
                dataType: 'json',
                contentType: 'application/json',
                success: function(data) {
                    if (data[0].status == "fail") {
                        let message = "";
                        $.each(data.error, function(index, value) {
                            message += value;
                        });
                        errorMsg(message);
                    } else {
                        successMsg(data[0]['data '].message);
                        if (sub_btn_clicked_name === "save_print") {
                            printprescription(data.visitid, true);
                        }
                        $('#add_prescription').modal('hide');
                        $('.ajaxlistvisit').DataTable().ajax.reload();
                    }
                    $("#form_prescription button[type=submit]").removeAttr("clicked");
                },
                error: function() {
                    errorMsg("Something went wrong");
                }
            });

            function getValue(data, name) {
                const field = data.find((item) => item.name === name);
                return field ? field.value : null;
            }
        });

    });

    $(document).ready(function(e) {
        $("#form_diagnosis").on('submit', (function(e) {
            $("#form_diagnosisbtn").button('loading');
            e.preventDefault();
            $.ajax({
                url: '<?php echo base_url(); ?>admin/patient/add_diagnosis',
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
                    $("#form_diagnosisbtn").button('reset');
                },
                error: function() {}
            });
        }));
    });

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

    $(document).on('select2:select', '.medicine_category', function() {
        getMedicine($(this), $(this).val(), 0);
        selected_medicine_category_id = $(this).val();
        var medicine_dosage = getDosages(selected_medicine_category_id);
        $(this).closest('tr').find('.medicine_dosage').html(medicine_dosage);

    });
    $(document).on('select2:select', '.medicine_name', function() {

        var row_id_val = $(this).data('rowid');
        $.ajax({
            type: "POST",
            url: base_url + "admin/pharmacy/get_medicine_stockinfo",
            data: {
                'pharmacy_id': $(this).val()
            },
            dataType: 'json',
            success: function(res) {
                $('#stock_info_' + row_id_val).html(res);
            }
        });

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
                medicine_colomn.html("<option value=''><?php echo $this->lang->line('select') ?></option>");

            },
            success: function(res) {
                var div_data = "<option value=''><?php echo $this->lang->line('select') ?></option>";
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



    function getMedicineDosage(id) {

        var category_selected = $("#medicine_cat" + id).val();
        var arr = category_selected.split('-');
        var category_set = arr[0];

        div_data = '';

        $("#search-dosage" + id).html("<option value='l'><?php echo $this->lang->line('loading') ?></option>");
        $('#search-dosage' + id).select2("val", +id);

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
                    div_data += "<option value='" + obj.dosage + "'>" + obj.dosage + "" + obj.dosage +
                        "</option>";

                });
                $("#search-dosage" + id).html(
                    "<option value=''><?php echo $this->lang->line('select') ?></option>");
                $('#search-dosage' + id).append(div_data);
                $('#search-dosage' + id).select2("val", '');

            }
        });

    }


    $(document).on('click', '.add-record', function() {

        var rowCount = $('#tableID tr').length;
        // var prescription_rows=rowCount+1;
        var cat_row = "";
        var medicine_row = "";
        var dose_row = "";
        var dose_interval_row = "";
        var dose_duration_row = "";
        var instruction_row = "";
        var closebtn_row = "";
        if (rowCount == 0) {
            cat_row = "<label><?php echo $this->lang->line('medicine_category'); ?></label>";
            medicine_row = "<label><?php echo $this->lang->line('medicine'); ?></label>";
            dose_row = " <label><?php echo $this->lang->line("dose"); ?></label>";
            dose_interval_row = " <label><?php echo $this->lang->line("dose_interval"); ?></label>";
            dose_duration_row = " <label><?php echo $this->lang->line("dose_duration"); ?></label>";
            instruction_row = " <label><?php echo $this->lang->line("instruction"); ?></label>";
            closebtn_row = " <label>&nbsp;</label>";
        }

        var div = "<input type='hidden' name='rows[]' value='" + prescription_rows +
            "' autocomplete='off'><div id=row1><div class='col-lg-2 col-md-4 col-sm-6 col-xs-6'><div col-sm-2 col-xs-6 '>" +
            cat_row + " <select class='form-control select2 medicine_category'  name='medicine_cat_" +
            prescription_rows + "'  id='medicine_cat" + prescription_rows +
            "'><option value='<?php echo set_value('medicine_category_id'); ?>'><?php echo $this->lang->line('select'); ?></option><?php foreach ($medicineCategory as $dkey => $dvalue) { ?><option value='<?php echo $dvalue["id"]; ?>'><?php echo $dvalue["medicine_category"] ?></option><?php } ?></select></div></div><div class='col-lg-2 col-md-4 col-sm-6 col-xs-6'><div>" +
            medicine_row + " <select class='form-control select2 medicine_name' data-rowId='" + prescription_rows +
            "'  name='medicine_" + prescription_rows + "' id='search-query" + prescription_rows +
            "'><option value='l'><?php echo $this->lang->line('select') ?></option></select><small id='stock_info_" +
            prescription_rows + "''> </small></div></div><div class='col-lg-2 col-md-4 col-sm-6 col-xs-6'><div>" +
            dose_row + "<select  class='form-control select2 medicine_dosage' name='dosage_" + prescription_rows +
            "' id='search-dosage" + prescription_rows +
            "'><option value='l'><?php echo $this->lang->line('select'); ?></option></select></div></div><div class='col-lg-2 col-md-4 col-sm-6 col-xs-6'><div>" +
            dose_interval_row + "<select  class='form-control select2 interval_dosage' name='interval_dosage_" +
            prescription_rows + "' id='search-interval-dosage" + prescription_rows +
            "'><option value='<?php echo set_value('interval_dosage_id'); ?>'><?php echo $this->lang->line('select'); ?></option><?php foreach ($intervaldosage as $dkey => $dvalue) { ?><option value='<?php echo $dvalue["id"]; ?>'><?php echo $dvalue["name"] ?></option><?php } ?></select></div></div><div class='col-lg-2 col-md-4 col-sm-6 col-xs-6'><div> " +
            dose_duration_row + "<select class='form-control select2 duration_dosage' name='duration_dosage_" +
            prescription_rows + "' id='search-duration-dosage" + prescription_rows +
            "'><option value='<?php echo set_value('duration_dosage_id'); ?>'><?php echo $this->lang->line('select') ?></option><?php foreach ($durationdosage as $dkey => $dvalue) { ?><option value='<?php echo $dvalue["id"]; ?>'><?php echo $dvalue["name"] ?></option><?php } ?></select></div></div><div class='col-lg-2 col-md-4 col-sm-6 col-xs-6'><div>" +
            instruction_row + "<textarea style='height:28px' name='instruction_" + prescription_rows +
            "' class=form-control id=description></textarea></div></div></div>";

        var row = "<tr id='row" + prescription_rows + "'><td>" + div + "</td><td>" + closebtn_row +
            "<button type='button' onclick='delete_row(" + prescription_rows + ")' data-row-id='" +
            prescription_rows + "' class='closebtn delete_row'><i class='fa fa-remove'></i></button></td></tr>";
        $('#tableID').append(row).find('.select2').select2();
        prescription_rows++;
    });



    function delete_row(id) {
        var table = document.getElementById("tableID");
        var rowCount = table.rows.length;
        $("#row" + id).html("");

    }
    $(document).on('click', '.delete_row', function(e) {

        var del_row_id = $(this).data('rowId');
        var del_record_id = $(this).data('recordId');
        var result = confirm("<?php echo $this->lang->line('delete_confirm') ?>");

        if (result) {
            $("#row" + del_row_id).remove();
        }

    });


    //timetine add code 
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



    /////timeline code end ////////////////////////////////////////////////

    function CalculateAgeInQCe(DOB, txtAge, Txndate) {
        if (DOB.value != '') {
            now = new Date(Txndate)
            var txtValue = DOB;

            if (txtValue != null)
                dob = txtValue.split('/');
            if (dob.length === 3) {
                born = new Date(dob[2], dob[1] * 1 - 1, dob[0]);
                if (now.getMonth() == born.getMonth() && now.getDate() == born.getDate()) {
                    age = now.getFullYear() - born.getFullYear();
                } else {
                    age = Math.floor((now.getTime() - born.getTime()) / (365.25 * 24 * 60 * 60 * 1000));
                }
                if (isNaN(age) || age < 0) {

                } else {

                    if (now.getMonth() > born.getMonth()) {
                        var calmonth = now.getMonth() - born.getMonth();

                    } else {
                        var calmonth = born.getMonth() - now.getMonth();

                    }

                    $("#eage_year").val(age);
                    $("#eage_month").val(calmonth);
                    return age;
                }
            }
        }
    }

    $(document).ready(function() {
        $("#ebirth_date").change(function() {
            var mdate = $("#ebirth_date").val().toString();
            var yearThen = parseInt(mdate.substring(6, 10), 10);
            var dayThen = parseInt(mdate.substring(0, 2), 10);
            var monthThen = parseInt(mdate.substring(3, 5), 10);
            var DOB = dayThen + "/" + monthThen + "/" + yearThen;
            CalculateAgeInQCe(DOB, '', new Date());
        });
    });







    function delete_timeline(id) {
        var patient_id = $("#patient_id").val();
        if (confirm('<?= $this->lang->line("delete_confirm") ?>')) {
            sendAjaxRequest(
                '<?= $api_base_url ?>internal-opd-timeline/' + id + "?hosId=" + <?= json_encode($data['hospital_id']) ?>,
                'DELETE', {},
                function(response) {
                    handleResponse(response, false);
                }
            );
        }
    }



    function view_prescription(visitid) {
        $.ajax({
            url: '<?php echo base_url(); ?>admin/prescription/getPrescription/' + visitid,
            success: function(res) {
                $("#getdetails_prescription").html(res);
            },
            error: function() {
                alert("<?php echo $this->lang->line('fail'); ?>")
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
                    "<?php if ($this->rbac->hasPrivilege('prescription', 'can_view')) { ?><a href='#'' data-toggle='tooltip' onclick='printprescriptionmanual(" +
                    visitid +
                    ")'   data-original-title='<?php echo $this->lang->line('print'); ?>'><i class='fa fa-print'></i></a><?php } ?>"
                );
            },
            error: function() {
                alert("<?php echo $this->lang->line('fail'); ?>")
            }
        });
        holdModal('prescriptionviewmanual');
    }
</script>
<?php
$data = $this->session->userdata('hospitaladmin');
$api_base_url = $this->config->item('api_base_url');
?>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.select2').select2();
        $("form#formrevisit button[type=submit]").click(function() {
            $("button[type=submit]", $(this).closest("form")).removeAttr("clicked");
            $(this).attr("clicked", "true");
        });

        $("#formrevisit").on('submit', async function(e) {
            e.preventDefault();
            try {
                var formData = new FormData(this);
                var formObject = Object.fromEntries(formData.entries());
                var requiredFields = {
                    "consultant_doctor": "Consultant Doctor",
                    "patientid": "Patient ID",
                    "charge_category": "Charge Category",
                    "charge_id": "Charge",
                    "apply_amount": "Applied Charge (INR)",
                    "amount": "Amount (INR)",
                    "payment_mode": "Payment Mode"
                };
                var errorMessages = [];
                Object.keys(requiredFields).forEach(key => {
                    if (!formObject[key] || formObject[key].trim() === "") {
                        errorMessages.push(requiredFields[key]);
                        $(`[name="${key}"]`).addClass("is-invalid");
                    } else {
                        $(`[name="${key}"]`).removeClass("is-invalid");
                    }
                });
                if (errorMessages.length > 0) {
                    const formattedMessage = `
                <strong>Please fill all required fields:</strong>
                <ul style="margin: 8px 0; padding-left: 20px;">
                    ${errorMessages.map(msg => `<li>${msg}</li>`).join('')}
                </ul>`;
                    errorMsg(formattedMessage);
                    resetButtons();
                    return;
                }

                formObject.appointment_date = '<?= date("d/m/y") ?>';

                var mappedObject = {
                    "cons_doctor": parseInt(formObject.consultant_doctor) || null,
                    "case_type": formObject.case || " ",
                    "appointment_date": formatDateTime(formObject.appointment_date) || null,
                    "symptoms_type": parseInt(formObject.symptoms_type) || null,
                    "spo2": parseInt(formObject.Sp02) || null,
                    "symptoms": formObject.symptoms || "",
                    "bp": parseFloat(formObject.bp) || null,
                    "height": parseFloat(formObject.height) || null,
                    "weight": parseFloat(formObject.weight) || null,
                    "pulse": parseInt(formObject.pulse) || null,
                    "temperature": parseFloat(formObject.temperature) || null,
                    "respiration": parseInt(formObject.respiration) || null,
                    "known_allergies": formObject.known_allergies || null,
                    "patient_old": formObject.old_patient || null,
                    "casualty": formObject.casualty || null,
                    "refference": formObject.refference || null,
                    "date": extractDate(formObject.appointment_date) || null,
                    "note": formObject.note_remark || "",
                    "payment_mode": formObject.payment_mode,
                    "generated_by": <?= $data["id"] ?>,
                    "live_consult": formObject.live_consult || null,
                    "can_delete": "yes",
                    "payment_date": extractDate(formObject.appointment_date) || null,
                    "time": extractTime(formObject.appointment_date) || null,
                    "standard_charge": parseFloat(formObject.standard_charge) || null,
                    "charge_id": parseInt(formObject.charge_id) || null,
                    "tax": parseFloat(formObject.percentage) || null,
                    "apply_charge": parseFloat(formObject.apply_amount) || null,
                    "amount": parseFloat(formObject.amount) || null,
                    "patient_id": parseInt(formObject.patientid) || null,
                    "Hospital_id": <?= $data['hospital_id'] ?> || null,
                    "received_by_name": '<?= $data['username'] ?>'
                };

                if (formObject.organisation != 0) {
                    mappedObject.organisation = parseInt(formObject.organisation);
                    mappedObject.tpa_charge = formObject.tpa_charge;
                }
                let action = e.originalEvent.submitter.id;
                if (mappedObject.payment_mode === 'Online') {
                    razorpaystartProcess(mappedObject, "<?= $api_base_url ?>opd-out-patient", "POST",
                        function(response) {
                            handleResponse(response, action === "get_print", "printVisitBill");
                        });
                } else {
                    sendAjaxRequest("<?= $api_base_url ?>opd-out-patient", "POST", mappedObject,
                        function(response) {
                            handleResponse(response, action === 'get_print',
                                'printVisitBill');
                        });
                }
            } catch (error) {
                console.error("Error during form submission:", error);
                errorMsg("Unexpected error occurred. Please try again.");
            }
        });

        function formatDateTime(dateTimeStr) {
            let date = new Date(dateTimeStr);
            if (isNaN(date.getTime())) date = new Date();
            return date.toISOString().replace('T', ' ').substring(0, 19);
        }

        function extractDate(dateTimeStr) {
            let date = new Date(dateTimeStr);
            if (isNaN(date.getTime())) date = new Date();
            return date.toISOString().substring(0, 10);
        }

        function extractTime(dateTimeStr) {
            let date = new Date(dateTimeStr);
            if (isNaN(date.getTime())) date = new Date();
            return date.toISOString().substring(11, 19);
        }
    })

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
                $("#etimelinedate").val((d =>
                    `${String(d.getDate()).padStart(2, '0')}/${String(d.getMonth() + 1).padStart(2, '0')}/${d.getFullYear()}`
                )(new Date(data.timeline_date)));
                $("#timelineedesc").val(data.description);
                $("#filedataalread").val(data.document);
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


    function makeid(length) {
        var result = '';
        var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        var charactersLength = characters.length;
        for (var i = 0; i < length; i++) {
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
        }
        return result;
    }



    function printprescription(visitid) {
        var base_url = '<?php echo base_url() ?>';
        $.ajax({
            url: base_url + 'admin/prescription/printPrescription',
            type: 'GET',
            data: {
                visitid: visitid
            },
            dataType: "JSON",

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
        var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ?
            frame1[0]
            .contentDocument.document : frame1[0].contentDocument;
        frameDoc.document.open();
        //Create a new HTML document.
        frameDoc.document.write('<html>');
        frameDoc.document.write('<head>');
        frameDoc.document.write('<title></title>');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url +
            'backend/bootstrap/css/bootstrap.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url +
            'backend/dist/css/font-awesome.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url +
            'backend/dist/css/ionicons.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url +
            'backend/dist/css/AdminLTE.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url +
            'backend/dist/css/skins/_all-skins.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url +
            'backend/plugins/iCheck/flat/blue.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url +
            'backend/plugins/morris/morris.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url +
            'backend/plugins/jvectormap/jquery-jvectormap-1.2.2.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url +
            'backend/plugins/datepicker/datepicker3.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url +
            'backend/plugins/daterangepicker/daterangepicker-bs3.css">');
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

    function holdModal(modalId) {
        $('#' + modalId).modal({
            backdrop: 'static',
            keyboard: false,
            show: true
        });
    }


    function deleteOpdPatientDiagnosis(patient_id, id) {
        if (confirm(<?php echo "'" . $this->lang->line('delete_confirm') . "'"; ?>)) {
            $.ajax({
                url: '<?php echo base_url(); ?>admin/patient/deleteOpdPatientDiagnosis/' + patient_id +
                    '/' + id,
                success: function(res) {
                    successMsg(<?php echo "'" . $this->lang->line('delete_message') . "'"; ?>);
                    window.location.reload(true);
                }
            })
        }
    }

    function deleteOpdPatientDiagnosis1(url, Msg) {
        if (confirm(<?php echo "'" . $this->lang->line('delete_confirm') . "'"; ?>)) {
            $.ajax({
                url: url,
                success: function(res) {
                    successMsg(Msg);
                    window.location.reload(true);
                }
            })
        }
    }


    var attr = {};

    $(document).on('select2:select', '.charge_category', function() {

        var charge_category = $(this).val();

        $('.charge').html("<option value=''><?php echo $this->lang->line('loading') ?></option>");
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
                        div_data += "<option value='" + obj.id + "'>" + obj.name +
                            "</option>";

                    });
                    $('.charge').html(div_data);
                    $(".charge").select2("val", charge_id);

                }
            });
        }
    }

    function update_amount(apply_charge) {

        var apply_amount = apply_charge;
        var tax_percentage = $('#percentage').val();
        if (tax_percentage != '' && tax_percentage != 0) {
            apply_amount = (parseFloat(apply_charge) * tax_percentage / 100) + (parseFloat(apply_charge));

            $('#apply_amount').val(apply_amount);


        } else {
            $('#apply_amount').val(apply_amount);
        }
    }

    $(document).on('select2:select', '.charge', function() {
        var charge = $(this).val();
        var orgid = $("#rorganisation").val();

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
                    $('#apply_charge').val(parseFloat(res.standard_charge) * quantity);
                    $('#standard_charge').val(res.standard_charge);
                    $('#schedule_charge').val(res.org_charge);
                    $('#org_id').val(res.org_charge_id);
                    if (res.org_charge == null) {
                        if (res.percentage == null) {
                            apply_amount = parseFloat(res.standard_charge);
                        } else {
                            apply_amount = (parseFloat(res.standard_charge) * res
                                .percentage / 100) + (
                                parseFloat(res.standard_charge));
                        }

                        $('#apply_charge').val(res.standard_charge);
                        $('#apply_amount').val(apply_amount.toFixed(2));
                        $('#paid_amount').val(apply_amount.toFixed(2));

                    } else {
                        if (res.percentage == null) {
                            apply_amount = parseFloat(res.org_charge);
                        } else {
                            apply_amount = (parseFloat(res.org_charge) * res.percentage /
                                100) + (
                                parseFloat(res.org_charge));
                        }

                        $('#apply_charge').val(res.org_charge);
                        $('#apply_amount').val(apply_amount.toFixed(2));
                        $('#paid_amount').val(apply_amount.toFixed(2));

                    }
                } else {

                }
            }
        });
    });


    function get_Charges(orgid) {
        var charge = $('.charge').val();
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
                    $('#percentage').val(res.percentage);
                    if (orgid) {
                        if (res.percentage == null) {
                            apply_amount = parseFloat(res.org_charge);
                        } else {
                            apply_amount = (parseFloat(res.org_charge) * res.percentage / 100) + (
                                parseFloat(res
                                    .org_charge));
                        }

                        $('#apply_charge').val(res.org_charge);
                        $('#apply_amount').val(apply_amount);
                        $('#standard_charge').val(res.standard_charge);
                    } else {
                        if (res.percentage == null) {
                            apply_amount = parseFloat(res.standard_charge);
                        } else {
                            apply_amount = (parseFloat(res.standard_charge) * res.percentage /
                                100) + (
                                parseFloat(res.standard_charge));
                        }

                        $('#standard_charge').val(res.standard_charge);
                        $('#apply_charge').val(res.standard_charge);
                        $('#apply_amount').val(apply_amount);
                    }
                } else {
                    $('#standard_charge').val('');
                    $('#apply_charge').val('');
                }
            }
        });
    }

    $(document).ready(function(e) {
        $('#viewModal').modal({
            backdrop: 'static',
            keyboard: false,
            show: false
        });

        $("#add_bill").on('submit', (function(e) {
            if (confirm('Are you sure?')) {
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

                    },
                    error: function(e) {
                        alert("<?php echo $this->lang->line('fail'); ?>");
                        console.log(e);
                    }
                });
            } else {
                return false;
            }

        }));
    });

    $(document).ready(function(e) {
        $("#add_payment").on('submit', (function(e) {
            e.preventDefault();
            $("#add_paymentbtn").button("loading");
            $.ajax({
                url: '<?php echo base_url(); ?>admin/payment/create',
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
                    $("#add_paymentbtn").button("reset");
                },
                error: function() {}
            });
        }));
    });

    function calculate() {

        var totalopdcharges = $("#totalopdcharges").val();
        var total_payment = $("#total_payment").val();
        var total_amount = parseInt(totalopdcharges) - parseInt(total_payment);
        var discount = $("#discount").val();
        var other_charge = $("#other_charge").val();
        var tax = $("#tax").val();
        var gross_total = parseInt(total_amount) + parseInt(other_charge) + parseInt(tax);
        var net_amount = parseInt(total_amount) + parseInt(other_charge) + parseInt(tax) - parseInt(discount);
        $("#gross_total").val(gross_total);
        $("#net_amount").val(net_amount);
        $("#net_amount_span").html(net_amount);
        $("#save_button").show();
        $("#printBill").show();
    }

    function printBill(patientid, ipdid) {
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
                ipdid: ipdid,
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

    function printVisitBill(opdid) {


        $.ajax({
            url: base_url + 'admin/patient/printbill',
            type: "POST",
            data: {
                opd_id: opdid,
                type: 'all'
            },
            dataType: 'json',
            beforeSend: function() {

            },
            success: function(data) {
                popup(data.page);
            },

            error: function(xhr) {
                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                $this.button('reset');

            },
            complete: function() {

            }
        });
    }

    function generateBill(id, amount) {
        $("#opdidhide").val(id);
        $("#totalopdcharges").val(amount);
        $("#addBillModal").modal('show');
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

    $(".revisitpatient").click(function() {
        $('#formrevisit').trigger("reset");
        $('#select2-act-container').html("");
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

    $(document).on('change', '.payment_mode', function() {
        var mode = $(this).val();
        if (mode == "Cheque") {
            $('.cheque_div').css("display", "block");
        } else {
            $('.cheque_div').css("display", "none");
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
    function getDosages(medicine_category_id) {
        var dosage_opt = "<option value=''><?php echo $this->lang->line('select') ?></option>";
        var sss = '<?php echo json_encode($category_dosage); ?>';
        var aaa = JSON.parse(sss);

        if (aaa[medicine_category_id]) {
            $.each(aaa[medicine_category_id], function(key, item) {
                dosage_opt += "<option value='" + item.id + "'>" + item.dosage + "" + item.unit + "</option>";
            });

        }
        return dosage_opt;
    }
</script>

<script type="text/javascript">
    $(document).on('click', '.move_opd', function(e) {
        var data = $(this).data();
        var this_modal = $('#moveIPDModal');
        $('.title', this_modal).text(data.opdId);
        $('.btn-ok', this_modal).data('recordId', data.recordId);
        var btn = $(this);
        $.ajax({
            url: base_url + 'admin/patient/moveIpdForm',
            type: "POST",
            data: {
                'visit_details_id': data.recordId
            },
            dataType: 'json',
            beforeSend: function() {
                btn.button('loading');

            },
            success: function(data) {

                if (data.status == "fail") {
                    var message = "";
                    $.each(data.error, function(index, value) {
                        message += value;
                    });
                    errorMsg(message);
                } else {
                    $('.modal-body', this_modal).html(data.page);
                    $('.modal-body', this_modal).find('.select2').select2();

                }
                btn.button('reset');

            },
            error: function(xhr) { // if error occured
                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                btn.button('reset');
            },
            complete: function() {
                btn.button('reset');
            }
        });
        $('#moveIPDModal').modal({
            backdrop: "static",
        });
    });





    function getBed(bed_group, bed = '', active, htmlid = 'bed_no') {

        var div_data = "";
        $('#' + htmlid).html("<option value='l'><?php echo $this->lang->line('loading') ?></option>");
        $("#" + htmlid).select2("val", 'l');
        $.ajax({
            url: '<?php echo base_url(); ?>admin/setup/bed/getbedbybedgroup',
            type: "POST",
            data: {
                bed_group: bed_group,
                bed_id: bed,
                active: active
            },
            dataType: 'json',
            success: function(res) {
                $.each(res, function(i, obj) {

                    div_data += "<option value=" + obj.id + ">" + obj.name + "</option>";
                });
                $("#" + htmlid).html("<option value=''><?php echo $this->lang->line('select') ?></option>");
                $('#' + htmlid).append(div_data);
                $("#" + htmlid).select2().select2('val', bed);
            }
        });
    }

    $(document).ready(function(e) {
        $('#add_prescription').modal({
            backdrop: 'static',
            keyboard: false,
            show: false
        });
    });
</script>
<script type="text/javascript">
    $(document).ready(function() {

        $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
            $($.fn.dataTable.tables(true)).DataTable()
                .columns.adjust()
                .responsive.recalc();
        });

    });
</script>
<!-- //========datatable start===== -->
<script type="text/javascript">
    (function($) {
        var id = atob("<?php echo $this->uri->segment(4); ?>");
        'use strict';
        $(document).ready(function() {
            initDatatable('ajaxlistvisit', 'admin/patient/getopdvisitdatatable/' + id);
            initDatatable('treatmentlist', 'admin/patient/getopdtreatmenthistory/' + id);

        });

    }(jQuery))
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
                $('#viewDetailReportModal').modal({
                    backdrop: 'static'
                });
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

    $(document).ready(function() {
        $.ajax({
            url: baseurl + "admin/patient/yearchart",
            type: 'POST',
            data: {
                patient_id: '<?php echo $patient_id; ?>'
            },
            dataType: 'json',
            beforeSend: function() {

            },
            success: function(data) {
                var ctx = document.getElementById("medical-history-chart").getContext("2d");

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.labels,
                        datasets: data.dataset,
                    }
                });

            },
            error: function(xhr) { // if error occured
                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");

            },
            complete: function() {

            }

        });
    });
</script>
<script>
    function getRevisitRecord(opdid) {
        var password = makeid(5);
        $('.select2-selection__rendered').html("");
        $.ajax({
            url: base_url + 'admin/patient/getopdvisitdata',
            type: "POST",
            data: {
                opdid: opdid
            },
            dataType: 'json',
            success: function(data) {
                $("#revisit_id").val(data.id);
                $("#revisit_password").val(password);
                $("#listname").html(data.patients_name?.trim() || '-');
                $('#guardian').html(data.guardian_name?.trim() || '-');
                $('#rgender').html(data.gender?.trim() || '-');
                $("#revisit_contact").val(data.mobileno);
                $("#listnumber").html(data.mobileno?.trim() || '-');
                $("#pmobileno").val(data.mobileno);
                $("#revisit_case").val(data.case_type?.trim() || '-');
                $("#pid").val(data.patientid);
                $("#revisit_allergies").val(data.known_allergies?.trim() || '-');
                $("#revisit_note").val(data.note?.trim() || '-');
                $("#revisit_refference").val(data.refference?.trim() || '-');
                $("#pemail").val(data.email);
                $("#remail").html(data.email?.trim() || '-');
                $("#rage").html(data.patient_age?.trim() || '-');
                let basePath = "<?= base_url() . 'uploads/hospital_content/' ?>";
                let genderIcon = document.getElementById("gender_icon");
                if (data.gender?.trim() === "Male") {
                    genderIcon.src = basePath + "Man.png";
                } else if (data.gender?.trim() === "Female") {
                    genderIcon.src = basePath + "Women.png";
                } else {
                    genderIcon.src = basePath + "Others.png";
                }
                if (data.live_consult) {
                    $("#live_consultrevisit").val(data.live_consult);
                }
                if (data.image) {
                    $.ajax({
                        url: 'https://phr-api.plenome.com/file_upload/getDocs',
                        type: "POST",
                        contentType: "application/json",
                        data: JSON.stringify({
                            value: data.image
                        }),
                        success: function(decryptResponse) {
                            if (decryptResponse && typeof decryptResponse === 'string' &&
                                decryptResponse.length > 0 && !decryptResponse.includes(
                                    "[object Object]")) {
                                let base64Image = "data:image/png;base64," + decryptResponse;
                                $("#patient_image").attr("src", base64Image);
                            } else {
                                $("#patient_image").attr("src", defaultImg);
                            }
                        },
                        error: function() {
                            $("#patient_image").attr("src", defaultImg);
                        }
                    });
                } else {
                    $("#patient_image").attr("src", base_url + 'uploads/staff_images/no_image.png');
                }

                $("#revisit_month").val(data.month);
                $("#revisit_height").val(data.height);
                $("#revisit_weight").val(data.weight);
                $("#revisit_bp").val(data.bp);
                $("#revisit_pulse").val(data.pulse);
                $("#esymptoms").val(data.symptoms);
                $("#revisit_temperature").val(data.temperature);
                $("#revisit_respiration").val(data.respiration);
                $("#revisit_sqo2").val(data.spo2);
                $("#revisit_blood_group").val(data.blood_group);
                $("#rblood_group").html(data.blood_group_name);
                $("#revisi_tax").val(data.tax);
                $("#revisit_address").val(data.address);
                $("#raddress").html(data.address?.trim() || '-');
                $("#rmarital_status").html(data.marital_status?.trim() || '-');
                $("#any_known_allergies").html(data.any_known_allergies?.trim() || '-');
                $("#remarks").html(data.note?.trim() || '-');
                $("#tpa_id").html(data.insurance_id);
                $("#tpa_validity").html(data.insurance_validity?.trim() || '-');
                $("#identification_number").html(data.identification_number?.trim() || '-');

                $("#consultant_doctor").select2("val", data.cons_doctor);
                $('select[id="revisit_old_patient"] option[value="' + data.old_patient + '"]').attr("selected",
                    "selected");
                $('select[id="rorganisation"] option[value="' + data.organisation_id + '"]').attr("selected",
                    "selected");
                $('select[id="revisit_gender"] option[value="' + data.gender + '"]').attr("selected",
                    "selected");
                $('select[id="revisit_marital_status"] option[value="' + data.marital_status + '"]').attr(
                    "selected", "selected");

                holdModal('revisitModal');
            }
        });
    }
</script>
<script>
    $("form#form_confirm-move").on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const formDataArray = form.serializeArray();
        if (!formDataArray || formDataArray.length === 0) {
            console.error("Form data is empty or not correctly serialized.");
            return false;
        }
        const admissionDate = formDataArray.find(field => field.name === "appointment_date")?.value;
        const consultantDoctor = formDataArray.find(field => field.name === "consultant_doctor")?.value;
        const bedNo = formDataArray.find(field => field.name === "bed_no")?.value;
        let errorMessages = [];
        if (!consultantDoctor) {
            errorMessages.push('Consultant Doctor is required');
        }
        if (!bedNo) {
            errorMessages.push('Bed Number is required');
        }
        if (errorMessages.length > 0) {
            errorMsg(errorMessages.join('<br>'));
            setTimeout(() => {
                $("#ipd_move").button('reset');
            }, 3000);
            return false;
        }
        if (confirm('<?= $this->lang->line('are_you_sure_want_to_move_patient'); ?>')) {
            var btn = $(this).find("button[type=submit]:focus");
            let currentDate = new Date().toISOString().split('T')[0];
            var mappedData = {
                patient_id: parseInt(formDataArray.find(field => field.name === "patient_id")?.value) || 0,
                height: formDataArray.find(field => field.name === "height")?.value || "",
                weight: formDataArray.find(field => field.name === "weight")?.value || "",
                pulse: formDataArray.find(field => field.name === "pulse")?.value || "",
                temperature: formDataArray.find(field => field.name === "temperature")?.value || "",
                respiration: formDataArray.find(field => field.name === "respiration")?.value || "",
                bp: formDataArray.find(field => field.name === "bp")?.value || "",
                bed: parseInt(bedNo) || 0,
                bed_group_id: parseInt(formDataArray.find(field => field.name === "bed_group_id")?.value) || 0,
                case_type: formDataArray.find(field => field.name === "case")?.value || "",
                casualty: formDataArray.find(field => field.name === "casualty")?.value?.toLowerCase() || "",
                symptoms: formDataArray.find(field => field.name === "symptoms")?.value || "",
                known_allergies: "",
                patient_old: formDataArray.find(field => field.name === "old_patient")?.value?.toLowerCase() ||
                    "no",
                note: formDataArray.find(field => field.name === "note")?.value || "",
                refference: formDataArray.find(field => field.name === "refference")?.value || "",
                cons_doctor: parseInt(consultantDoctor) || 0,
                organisation_id: parseInt(formDataArray.find(field => field.name === "organisation")?.value) ||
                    0,
                credit_limit: formDataArray.find(field => field.name === "credit_limit")?.value || "",
                payment_mode: "cash",
                date: currentDate,
                discharged: "no",
                live_consult: formDataArray.find(field => field.name === "live_consult")?.value
                    ?.toLowerCase() || "no",
                is_active: "yes",
                revert_reason: "none",
                hospital_id: <?= $data['hospital_id'] ?>
            };
            let opdid = $('input[name="opd_id"]').val();
            sendAjaxRequest(
                '<?= $api_base_url ?>ipd-main-module/isIPDMoved/' + opdid,
                'PATCH',
                mappedData,
                function(response) {
                    handleResponse(response, false, 'printVisitBill');
                }
            );
        } else {
            setTimeout(() => {
                $("#ipd_move").button('reset');
            }, 3000);
        }
    });


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
    const initialData = <?= json_encode($opdlist['data']) ?>;
    const totalRecords = <?= $opdlist['recordsFiltered'] ?>;
    let isFirstLoad = true;
    const patientid = atob("<?php echo $this->uri->segment(4); ?>");
    $(document).ready(function() {
        initSummaryDataTable(
            '',
            '',
            '#ajaxlist',
            '',
            'renderSummaryTable',
            `${baseurl}admin/patient/getopdvisitdetials?patientid=${patientid}`
        );
    });

    function renderSummaryTable(dataArray, recordCount, data, callback) {
        let count = 0;
        const patientid = atob("<?php echo $this->uri->segment(4); ?>");
        return (dataArray || []).map(item => {
            const opdNo =
                `<span onclick="window.location.href='${baseurl}admin/patient/visitdetails/${btoa(patientid)}/${btoa(item.opd_NO)}'" style="cursor:pointer; color:#0084B4;">${item.opd_prefix || ''}${item.opd_NO || '-'}</span>`;
            const appointmentNo = item.Appointment_No ?
                `<span onclick="viewDetail(${item.Appointment_No})" style="cursor:pointer; color:#0084B4;">${item.appointment_prefix || 'APPN'}${item.Appointment_No}</span>` :
                '-';
            const caseId = `<span>${item.case_id || '-'}</span>`;
            const appointmentDate =
                `<span>${item.appointment_date ? new Date(item.appointment_date).toLocaleDateString('en-GB') : '-'}</span>`;
            const consultant = `<span>${item.consultant || '-'}</span>`;
            const reference = `<span>${item.refference?.trim() || '-'}</span>`;
            const symptoms = `<span>${item.symptoms ? item.symptoms.replace(/\n/g, "<br>") : '-'}</span>`;

            let dynamicFields = [];
            if (item.dynamic_fields && Array.isArray(item.dynamic_fields)) {
                dynamicFields = item.dynamic_fields.map(f => `<span>${f.value || '-'}</span>`);
            }
            let action = "";
            action +=
                `<span class='btn btn-default btn-xs print_visit_bill' data-opd-id="${item.opd_NO}" data-record-id="${item.OCID}" title='Print Bill'><i class='fa fa-file'></i></span> `;
            action +=
                `<span class='btn btn-default btn-xs get_opd_detail' data-opd-id="${item.opd_NO}" data-record-id="${item.OCID}" title='Show'><i class='fa fa-reorder'></i></span> `;
            if (item.is_ipd_moved == 0) {
                action +=
                    `<span class='btn btn-default btn-xs move_opd' data-opd-id="${item.opd_NO}" data-record-id="${item.OCID}" title='Move to IPD'><i class='fas fa-share-square'></i></span>`;
            }
            return [
                ++count,
                opdNo,
                appointmentNo,
                caseId,
                appointmentDate,
                consultant,
                reference,
                symptoms,
                ...dynamicFields,
                action
            ];
        });
    }

    function checkcheque(value) {
        if (value === 'Paylater') {
            $("#paid_amount").prop("readonly", true);
            $('#amountchecker').text('');
            $('#get_print, #formrevisitbtn').prop('disabled', false);
        } else {
            const applyAmount = $("#apply_amount").val();
            $("#paid_amount").prop("readonly", false);
            $("#paid_amount").val(applyAmount);
            checkpaylater(applyAmount);
        }
    }

    function checkpaylater(value) {
        const paymentMode = $("[name='payment_mode']").val();
        const applyAmount = parseFloat($("#apply_amount").val());
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