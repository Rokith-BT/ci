<?php
$currency_symbol = $this->customlib->getHospitalCurrencyFormat();
$data = $this->session->userdata('hospitaladmin');
$api_base_url = $this->config->item('api_base_url');
?>

<?php
$userdata = $this->customlib->getUserData();
$logged_in_User = $this->customlib->getLoggedInUserData();
$logged_in_User_Role = json_decode($this->customlib->getStaffRole());
$permission_access = 0;
if (($staff["user_type"] == "Super Admin") && $userdata["id"] == $staff["id"]) {
    $permission_access = 1;
} elseif (($this->rbac->hasPrivilege('can_see_other_users_profile', 'can_view') && $staff["user_type"] != "Super Admin") || $userdata["id"] == $staff["id"]) {
    $permission_access = 1;
}
?>
<div class="content-wrapper">
    <div class="row">
        <div>
            <?php if ($this->rbac->hasPrivilege('can_see_other_users_profile', 'can_view')) { ?>
            <!-- <a id="sidebarCollapse" class="studentsideopen"><i class="fa fa-navicon"></i></a> -->
            <?php } ?>
            <aside class="studentsidebar">
                <div class="stutop" id="">
                    <!-- Create the tabs -->
                    <div class="studentsidetopfixed">
                        <p class="classtap"><?php echo $this->lang->line('staff'); ?> <a href="#"
                                data-toggle="control-sidebar" class="studentsideclose"><i class="fa fa-times"></i>
                            </a>
                        </p>
                        <ul class="nav nav-justified studenttaps">
                            <?php foreach ($roles as $role_key => $role_value) {
                            ?>
                            <li <?php if ($staff["role_id"] == $role_value["id"]) {
                                        echo "class='active'";
                                    } ?>>
                                <a href="#role<?php echo $role_value["id"] ?>"
                                    data-toggle="tab"><?php echo $role_value["name"] ?></a>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <?php foreach ($roles as $rolet_key => $rolet_value) {
                        ?>
                        <div class="tab-pane <?php
                                                    if ($staff["role_id"] == $rolet_value["id"]) {
                                                        echo "active";
                                                    }
                                                    ?>" id="role<?php echo $rolet_value['id'] ?>">
                            <?php
                                foreach ($stafflist as $skey => $svalue) {
                                    if ($rolet_value['id'] == $svalue["role_id"]) {
                                        if (!empty($svalue["image"])) {
                                            $image = $svalue['image'];
                                        } else {
                                            $image = "no_image.png";
                                        }
                                ?>
                            <div class="studentname">
                                <a href="<?php echo base_url("admin/staff/profile/" . $svalue["id"]); ?>">
                                    <div class="icon"><img
                                            src="<?php echo base_url() . "uploads/staff_images/" . $image . img_time(); ?>"
                                            alt="User Image"></div>
                                    <div class="student-tittle">
                                        <?php echo $svalue['name'] . " " . $svalue['surname']; ?>
                                    </div>
                                </a>
                            </div>
                            <?php
                                    }
                                }
                                ?>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </aside>
        </div>
    </div>
    <section class="content">
        <div class="row">
            <div class="col-lg-3 col-md-4 col-sm-12">
                <div class="box box-primary" <?php if ($staff["is_active"] == 0) {
                                                    echo "style='background-color:#f0dddd;'";
                                                } ?>>
                    <div class="box-body box-profile">
                        <?php
                        if ($staff["image"]) {
                            $userdata = $this->session->userdata('hospitaladmin');
                            $accessToken = $userdata['accessToken'] ?? '';
                            $url = "https://phr-api.plenome.com/file_upload/getDocs";
                            $client = curl_init($url);
                            curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($client, CURLOPT_POST, true);
                            curl_setopt($client, CURLOPT_POSTFIELDS, json_encode(['value' => $staff["image"]]));
                            curl_setopt($client, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Authorization: ' . $accessToken]);
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
                        <img class="profile-user-img img-responsive img-circle" style="height: 60px;"
                            src="<?= $logo_image ?>" id="child_image" alt="User profile picture">


                        <h3 class="profile-username text-center"><?php echo $staff['name'] . " " . $staff['surname']; ?>
                        </h3>
                        <div class="editviewdelete-icon pt8 text-center">
                            <?php if ($this->rbac->hasPrivilege('staff', 'can_edit') && $permission_access == 1) { ?>
                            <?php if ($staff["is_active"] == 1 && $this->rbac->hasPrivilege('disable_staff', 'can_view')) { ?>
                            <a href="#" class="change_password text-green" data-toggle="tooltip"
                                title="<?php echo $this->lang->line('staff_change_password'); ?>">
                                <i class="fa fa-key"></i>
                            </a>
                            <?php } ?>
                            <?php if ($staff["is_active"] == 1 && $this->rbac->hasPrivilege('disable_staff', 'can_view')) { ?>
                            <a href="<?php echo base_url('admin/staff/edit/' . $id); ?>" data-toggle="tooltip"
                                title="<?php echo $this->lang->line('edit'); ?>" class="text-light">
                                <i class="fa fa-pencil"></i>
                            </a>
                            <?php } ?>
                            <?php if ($userdata["id"] != $staff["id"]) { ?>
                            <?php if ($staff["is_active"] == 1 && $this->rbac->hasPrivilege('disable_staff', 'can_view')) { ?>
                            <a href="javascript:void(0);" class="text-red" data-toggle="tooltip"
                                title="<?php echo $this->lang->line('staff_disable'); ?>"
                                onclick="disableStaff(<?= $id ?>);">
                                <i class="fa fa-thumbs-o-down"></i>
                            </a>
                            <?php } else if ($staff["is_active"] == 0) { ?>
                            <a href="javascript:void(0);" class="text-green" data-toggle="tooltip"
                                title="<?php echo $this->lang->line('staff_enable'); ?>"
                                onclick="enableStaff(<?= $id ?>);">
                                <i class="fa fa-thumbs-o-up"></i>
                            </a>
                            <?php if ($this->rbac->hasPrivilege('staff', 'can_delete') && $permission_access == 1 && $userdata["id"] != $staff["id"]) { ?>
                            <a href="javascript:void(0);" class="text-red delete-staff" data-id="<?php echo $id; ?>"
                                data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>">
                                <i class="fa fa-trash"></i>
                            </a>
                            <?php } ?>
                            <?php } ?>
                            <?php } ?>
                            <?php } ?>
                        </div>

                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item listnoback">
                                <b><?php echo $this->lang->line('staff_id'); ?></b>
                                <a
                                    class="pull-right text-aqua"><?php echo !empty(trim($staff['employee_id'])) ? $staff['employee_id'] : '-'; ?></a>
                            </li>
                            <li class="list-group-item listnoback">
                                <b><?php echo $this->lang->line('staff_role'); ?></b>
                                <a
                                    class="pull-right text-aqua"><?php echo !empty(trim($staff['user_type'])) ? $staff['user_type'] : '-'; ?></a>
                            </li>
                            <li class="list-group-item listnoback">
                                <b><?php echo $this->lang->line('staff_designation'); ?></b>
                                <a
                                    class="pull-right text-aqua"><?php echo !empty(trim($staff['designation'])) ? $staff['designation'] : '-'; ?></a>
                            </li>
                            <li class="list-group-item listnoback">
                                <b><?php echo $this->lang->line('staff_department'); ?></b>
                                <a
                                    class="pull-right text-aqua"><?php echo !empty(trim($staff['department'])) ? $staff['department'] : '-'; ?></a>
                            </li>
                            <li class="list-group-item listnoback">
                                <b><?php echo $this->lang->line('staff_specialist'); ?></b>
                                <?php
                                if (!empty($staff_speciality)) {
                                    foreach ($staff_speciality as $staff_speciality_value) {
                                        echo '<a class="pull-right text-aqua">' . trim($staff_speciality_value->specialist_name) . '</a><br />';
                                    }
                                } else {
                                    echo '<a class="pull-right text-aqua">-</a>';
                                }
                                ?>
                            </li>
                            <li class="list-group-item listnoback">
                                <b><?php echo $this->lang->line('staff_epf_no'); ?></b>
                                <a
                                    class="pull-right text-aqua"><?php echo !empty(trim($staff['epf_no'])) ? $staff['epf_no'] : '-'; ?></a>
                            </li>
                            <li class="list-group-item listnoback">
                                <b><?php echo $this->lang->line('staff_basic_salary'); ?></b>
                                <a
                                    class="pull-right text-aqua"><?php echo !empty(trim($staff['basic_salary'])) ? $staff['basic_salary'] : '-'; ?></a>
                            </li>
                            <li class="list-group-item listnoback">
                                <b><?php echo $this->lang->line('staff_contract_type'); ?></b>
                                <a class="pull-right text-aqua">
                                    <?php
                                    echo array_key_exists($staff['contract_type'], $contract_type) ? trim($contract_type[$staff['contract_type']]) : '-';
                                    ?>
                                </a>
                            </li>
                            <li class="list-group-item listnoback">
                                <b><?php echo $this->lang->line('staff_work_shift'); ?></b>
                                <a
                                    class="pull-right text-aqua"><?php echo !empty(trim($staff['shift'])) ? $staff['shift'] : '-'; ?></a>
                            </li>
                            <li class="list-group-item listnoback">
                                <b><?php echo $this->lang->line('staff_work_location'); ?></b>
                                <a
                                    class="pull-right text-aqua"><?php echo !empty(trim($staff['location'])) ? $staff['location'] : '-'; ?></a>
                            </li>
                            <li class="list-group-item listnoback">
                                <b><?php echo $this->lang->line('staff_date_of_joining'); ?></b>
                                <a class="pull-right text-aqua">
                                    <?php
                                    echo (!empty(trim($staff["date_of_joining"])) && $staff["date_of_joining"] != '0000-00-00')
                                        ? date("d/m/Y", strtotime(trim($staff['date_of_joining'])))
                                        : '-';
                                    ?>

                                </a>
                            </li>
                            <?php if (($staff["is_active"] == 0)) { ?>
                            <li class="list-group-item listnoback">
                                <b><?php echo $this->lang->line('staff_date_of_leaving'); ?></b>
                                <a class="pull-right text-aqua">
                                    <?php
                                        echo (!empty(trim($staff["date_of_leaving"])) && $staff["date_of_leaving"] != '0000-00-00')
                                            ? date("d/m/Y", strtotime(trim($staff['date_of_leaving'])))
                                            : '-';
                                        ?>

                                </a>
                            </li>
                            <?php } ?>
                        </ul>

                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-md-8 col-sm-12">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs navlistscroll">
                        <li class="active"><a href="#activity" data-toggle="tab"
                                aria-expanded="true"><?php echo $this->lang->line('staff_profile'); ?></a></li>
                        <?php if ($this->rbac->hasPrivilege('staff_payroll', 'can_view')) { ?>
                        <li class=""><a href="#payroll" data-toggle="tab"
                                aria-expanded="true"><?php echo $this->lang->line('staff_payroll'); ?></a></li>
                        <?php }
                        if ($this->rbac->hasPrivilege('apply_leave', 'can_view')) { ?>
                        <li class=""><a href="#leaves" data-toggle="tab"
                                aria-expanded="true"><?php echo $this->lang->line('staff_leaves'); ?></a></li>
                        <?php }
                        if ($this->rbac->hasPrivilege('staff_attendance', 'can_view')) { ?>
                        <li class=""><a href="#attendance" data-toggle="tab"
                                aria-expanded="true"><?php echo $this->lang->line('staff_attendance'); ?></a></li>
                        <?php } ?>
                        <li class=""><a href="#documents" data-toggle="tab"
                                aria-expanded="true"><?php echo $this->lang->line('staff_documents'); ?></a></li>
                        <li class=""><a href="#timeline" data-toggle="tab"
                                aria-expanded="true"><?php echo $this->lang->line('staff_timeline'); ?></a></li>

                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="activity">
                            <div class="tshadow mb25 bozero">
                                <div class="table-responsive around10 pt0">
                                    <table class="table table-hover table-striped tmb0">
                                        <tbody>
                                            <tr>
                                                <td><?php echo $this->lang->line('staff_phone'); ?></td>
                                                <td><?php echo !empty(trim($staff['contact_no'])) ? $staff['contact_no'] : '-'; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><?php echo $this->lang->line('staff_emergency_contact_number'); ?>
                                                </td>
                                                <td><?php echo !empty(trim($staff['emergency_contact_no'])) ? $staff['emergency_contact_no'] : '-'; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><?php echo $this->lang->line('staff_email'); ?></td>
                                                <td><?php echo !empty(trim($staff['email'])) ? $staff['email'] : '-'; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><?php echo $this->lang->line('staff_gender'); ?></td>
                                                <td><?php echo !empty(trim($staff['gender'])) ? $staff['gender'] : '-'; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><?php echo $this->lang->line('staff_blood_group'); ?></td>
                                                <td><?php echo !empty(trim($staff['Blood_group_name'])) ? $staff['Blood_group_name'] : '-'; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><?php echo $this->lang->line('staff_date_of_birth'); ?></td>
                                                <td>
                                                    <?php
                                                    echo (!empty(trim($staff["dob"])))
                                                        ? date("d/m/Y", strtotime(trim($staff['dob'])))
                                                        : '-';
                                                    ?>

                                                </td>
                                            </tr>
                                            <tr>
                                                <td><?php echo $this->lang->line('staff_marital_status'); ?></td>
                                                <td><?php echo !empty(trim($staff['marital_status'])) ? $staff['marital_status'] : '-'; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="col-md-4">
                                                    <?php echo $this->lang->line('staff_father_name'); ?></td>
                                                <td class="col-md-5">
                                                    <?php echo !empty(trim($staff['father_name'])) ? $staff['father_name'] : '-'; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><?php echo $this->lang->line('staff_mother_name'); ?></td>
                                                <td><?php echo !empty(trim($staff['mother_name'])) ? $staff['mother_name'] : '-'; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><?php echo $this->lang->line('staff_qualification'); ?></td>
                                                <td><?php echo !empty(trim($staff['qualification'])) ? $staff['qualification'] : '-'; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><?php echo $this->lang->line('staff_work_experience'); ?></td>
                                                <td><?php echo !empty(trim($staff['work_exp'])) ? $staff['work_exp'] : '-'; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><?php echo $this->lang->line('staff_specialization'); ?></td>
                                                <td><?php echo !empty(trim($staff['specialization'])) ? $staff['specialization'] : '-'; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><?php echo $this->lang->line('staff_note'); ?></td>
                                                <td><?php echo !empty(trim($staff['note'])) ? $staff['note'] : '-'; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><?php echo $this->lang->line('pan_number'); ?></td>
                                                <td><?php echo !empty(trim($staff['pan_number'])) ? $staff['pan_number'] : '-'; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><?php echo $this->lang->line('national_identification_number'); ?>
                                                </td>
                                                <td><?php echo !empty(trim($staff['identification_number'])) ? $staff['identification_number'] : '-'; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><?php echo $this->lang->line('local_identification_number'); ?></td>
                                                <td><?php echo !empty(trim($staff['local_identification_number'])) ? $staff['local_identification_number'] : '-'; ?>
                                                </td>
                                            </tr>

                                            <?php
                                            $custom_fields_data = get_custom_table_values($staff['id'], 'staff');
                                            if (!empty($custom_fields_data)) {
                                                foreach ($custom_fields_data as $field_value) { ?>
                                            <tr>
                                                <td><?php echo $field_value->name; ?></td>
                                                <td>
                                                    <?php
                                                            if (!empty(trim($field_value->field_value))) {
                                                                if (is_string($field_value->field_value) && is_array(json_decode($field_value->field_value, true)) && (json_last_error() == JSON_ERROR_NONE)) {
                                                                    $field_array = json_decode($field_value->field_value);
                                                                    echo "<ul class='patient_custom_field'>";
                                                                    foreach ($field_array as $each_value) {
                                                                        echo "<li>" . trim($each_value) . "</li>";
                                                                    }
                                                                    echo "</ul>";
                                                                } else {
                                                                    $display_field = trim($field_value->field_value);
                                                                    if ($field_value->type == "link") {
                                                                        $display_field = "<a href='" . $field_value->field_value . "' target='_blank'>" . $field_value->field_value . "</a>";
                                                                    }
                                                                    echo $display_field;
                                                                }
                                                            } else {
                                                                echo '-';
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
                            <div class="tshadow mb25 bozero">
                                <h3 class="pagetitleh2"><?php echo $this->lang->line('address'); ?>
                                    <?php echo $this->lang->line('detail'); ?>
                                </h3>
                                <div class="table-responsive around10 pt0">
                                    <table class="table table-hover table-striped tmb0">
                                        <tbody>
                                            <tr>
                                                <td class="col-md-4">
                                                    <?php echo $this->lang->line('staff_current_address'); ?>
                                                </td>
                                                <td class="col-md-5">
                                                    <?php echo !empty(trim($staff['local_address'])) ? $staff['local_address'] : '-'; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><?php echo $this->lang->line('staff_permanent_address'); ?></td>
                                                <td><?php echo !empty(trim($staff['permanent_address'])) ? $staff['permanent_address'] : '-'; ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                            <div class="tshadow mb25 bozero">
                                <h3 class="pagetitleh2"><?php echo $this->lang->line('staff_bank_account_details'); ?>
                                </h3>
                                <div class="table-responsive around10 pt10">
                                    <table class="table table-hover table-striped tmb0">
                                        <tbody>
                                            <tr>
                                                <td class="col-md-4">
                                                    <?php echo $this->lang->line('staff_account_title'); ?>
                                                </td>
                                                <td class="col-md-5">
                                                    <?php echo !empty(trim($staff['account_title'])) ? $staff['account_title'] : '-'; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><?php echo $this->lang->line('staff_bank_name'); ?></td>
                                                <td><?php echo !empty(trim($staff['bank_name'])) ? $staff['bank_name'] : '-'; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><?php echo $this->lang->line('staff_bank_branch_name'); ?></td>
                                                <td><?php echo !empty(trim($staff['bank_branch'])) ? $staff['bank_branch'] : '-'; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><?php echo $this->lang->line('staff_bank_account_number'); ?></td>
                                                <td><?php echo !empty(trim($staff['bank_account_no'])) ? $staff['bank_account_no'] : '-'; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><?php echo $this->lang->line('staff_ifsc_code'); ?></td>
                                                <td><?php echo !empty(trim($staff['ifsc_code'])) ? $staff['ifsc_code'] : '-'; ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                            <div class="tshadow mb25  bozero">
                                <h3 class="pagetitleh2"><?php echo $this->lang->line('staff_social_media_link'); ?></h3>
                                <div class="table-responsive around10 pt0">
                                    <table class="table table-hover table-striped tmb0">
                                        <tbody>
                                            <tr>
                                                <td class="col-md-4">
                                                    <?php echo $this->lang->line('staff_facebook_url'); ?>
                                                </td>
                                                <td class="col-md-5">
                                                    <?php echo !empty(trim($staff['facebook'])) ? '<a href="' . $staff['facebook'] . '" target="_blank">' . $staff['facebook'] . '</a>' : '-'; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><?php echo $this->lang->line('staff_twitter_url'); ?></td>
                                                <td>
                                                    <?php echo !empty(trim($staff['twitter'])) ? '<a href="' . $staff['twitter'] . '" target="_blank">' . $staff['twitter'] . '</a>' : '-'; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><?php echo $this->lang->line('staff_linkedin_url'); ?></td>
                                                <td>
                                                    <?php echo !empty(trim($staff['linkedin'])) ? '<a href="' . $staff['linkedin'] . '" target="_blank">' . $staff['linkedin'] . '</a>' : '-'; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><?php echo $this->lang->line('staff_instagram_url'); ?></td>
                                                <td>
                                                    <?php echo !empty(trim($staff['instagram'])) ? '<a href="' . $staff['instagram'] . '" target="_blank">' . $staff['instagram'] . '</a>' : '-'; ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="payroll">
                            <div class="row">
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="staffprofile">
                                        <h5><?php echo $this->lang->line('staff_total_net_salary_paid'); ?> </h5>
                                        <h4><?php
                                            if (!empty($salary["net_salary"])) {
                                                echo $currency_symbol . number_format((float) $salary["net_salary"], 2, '.', '');
                                            } else {
                                                echo $currency_symbol . "0";
                                            }
                                            ?>

                                        </h4>
                                        <div class="icon mt12font40">
                                            <i class="fa fa-money"></i>
                                        </div>
                                    </div>
                                </div>
                                <!--./col-md-3-->
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="staffprofile">
                                        <h5><?php echo $this->lang->line('staff_total_gross_salary'); ?></h5>
                                        <h4><?php
                                            if (!empty($salary["basic_salary"])) {
                                                $grosssalary = ($salary["earnings"] - $salary["deduction"]);
                                                echo $currency_symbol . number_format((float) $grosssalary, 2, '.', '');
                                            } else {
                                                echo $currency_symbol . "0";
                                            }
                                            ?></h4>
                                        <div class="icon mt12font40">
                                            <i class="fa fa-money"></i>
                                        </div>
                                    </div>
                                </div>
                                <!--./col-md-3-->
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="staffprofile">
                                        <h5><?php echo $this->lang->line('staff_total_earning'); ?></h5>
                                        <h4><?php
                                            if (!empty($salary["earnings"])) {
                                                echo $currency_symbol . number_format((float) $salary["earnings"], 2, '.', '');
                                            } else {
                                                echo $currency_symbol . "0";
                                            }
                                            ?></h4>
                                        <div class="icon mt12font40">
                                            <i class="fa fa-money"></i>
                                        </div>
                                    </div>
                                </div>
                                <!--./col-md-3-->
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="staffprofile">
                                        <h5><?php echo $this->lang->line('staff_total_deduction'); ?></h5>
                                        <h4><?php $sunnum = ($salary["deduction"]);
                                            echo $currency_symbol . number_format((float) $sunnum, 2, '.', '');

                                            ?> </h4>
                                        <div class="icon mt12font40">
                                            <i class="fa fa-money"></i>
                                        </div>
                                    </div>
                                </div>
                                <!--./col-md-3-->
                            </div>
                            <div class="download_label"><?php echo $this->lang->line('staff_payroll_details_for'); ?>
                                <?php echo $staff["name"] . " " . $staff["surname"]; ?>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover table-striped example">
                                    <thead>
                                        <tr>
                                            <th class="text text-left"><?php echo $this->lang->line('staff_payslip'); ?>
                                                #</th>
                                            <th class="text text-left">
                                                <?php echo $this->lang->line('month_year'); ?><span></span>
                                            </th>
                                            <th class="text text-left"><?php echo $this->lang->line('date'); ?></th>
                                            <th class="text text-left"><?php echo $this->lang->line('mode'); ?></th>
                                            <th class="text text-left"><?php echo $this->lang->line('status'); ?></th>
                                            <th class="text-right"><?php echo $this->lang->line('net_salary'); ?>
                                                <span><?php echo "(" . $currency_symbol . ")"; ?></span>
                                            </th>
                                            <th class="text-right noExport"><?php echo $this->lang->line('action'); ?>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($staff_payroll as $key => $payroll_value) {
                                            if ($payroll_value["status"] == "paid") {
                                                $label = "class='label label-success'";
                                            } else if ($payroll_value["status"] == "generated") {
                                                $label = "class='label label-warning'";
                                            } else {
                                                $label = "class='label label-default'";
                                            }
                                        ?>
                                        <tr>
                                            <td>
                                                <a data-toggle="popover" href="#" class="detail_popover"
                                                    data-original-title=""
                                                    title=""><?php echo $payroll_value['id'] ?></a>
                                                <div class="fee_detail_popover" style="display: none">
                                                    <?php echo $payroll_value['remark']; ?>
                                                </div>
                                            </td>
                                            <td><?php echo $this->lang->line($payroll_value['month']) . " - " . $payroll_value['year']; ?>
                                            </td>
                                            <td>
                                                <?php
                                                    echo (!empty(trim($payroll_value['payment_date'])) && $payroll_value['payment_date'] != '0000-00-00')
                                                        ? date("d/m/Y", strtotime(trim($payroll_value['payment_date'])))
                                                        : '-';
                                                    ?>
                                            </td>

                                            <td>
                                                <?php
                                                    if (!empty($payroll_value['payment_mode']) && isset($payment_mode[$payroll_value['payment_mode']])) {
                                                        echo $payment_mode[$payroll_value['payment_mode']];
                                                    } else {
                                                        echo "N/A"; // Provide a default value if the payment mode is not valid
                                                    }
                                                    ?>
                                            </td>

                                            <td><span
                                                    <?php echo $label ?>><?php echo $payroll_status[$payroll_value['status']]; ?></span>
                                            </td>
                                            <td class="text-right"><?php echo $payroll_value['net_salary'] ?></td>
                                            <td class="text-right noExport">
                                                <?php if ($payroll_value["status"] == "paid") { ?>
                                                <a href="#" onclick="getPayslip('<?php echo $payroll_value["id"]; ?>')"
                                                    role="button"
                                                    class="btn btn-primary btn-xs checkbox-toggle edit_setting"
                                                    data-toggle="tooltip"
                                                    title="<?php echo $this->lang->line('payslip_view'); ?>"><?php echo $this->lang->line('view'); ?>
                                                    <?php echo $this->lang->line('staff_payslip'); ?></a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane" id="documents">
                            <div class="timeline-header no-border">
                                <div class="row">
                                    <?php
                                    if (
                                        (empty(trim($staff["resume"])) || trim($staff["resume"]) === "NA") &&
                                        (empty(trim($staff["joining_letter"])) || trim($staff["joining_letter"]) === "NA") &&
                                        (empty(trim($staff["resignation_letter"])) || trim($staff["resignation_letter"]) === "NA") &&
                                        (empty(trim($staff["other_document_file"])) || trim($staff["other_document_file"]) === "NA")
                                    ) {
                                    ?>
                                    <div class="col-md-12">
                                        <div class="alert alert-info">
                                            <?php echo $this->lang->line("no_record_found"); ?>
                                        </div>
                                    </div>
                                    <?php } else { ?>
                                    <?php if (!empty($staff["resume"]) && strtoupper($staff["resume"]) !== 'NA') { ?>
                                    <div class="col-md-3 col-sm-6">
                                        <div class="staffprofile">
                                            <?php
                                                    if (!empty($staff['resume'])) {
                                                        $userdata = $this->session->userdata('hospitaladmin');
                                                        $accessToken = $userdata['accessToken'] ?? '';
                                                        $name = $staff['resume'];
                                                        $url = "https://phr-api.plenome.com/file_upload/getDocs";
                                                        $client = curl_init($url);
                                                        curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
                                                        curl_setopt($client, CURLOPT_POST, true);
                                                        curl_setopt($client, CURLOPT_POSTFIELDS, json_encode(['value' => $name]));
                                                        curl_setopt($client, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Authorization: ' . $accessToken]);
                                                        $response = curl_exec($client);
                                                        curl_close($client);
                                                        if ($response === false) {
                                                            echo json_encode(["error" => "No response from server."]);
                                                            return;
                                                        }
                                                        $base64File = trim($response);
                                                        $fileData = base64_decode($base64File);
                                                        if ($fileData === false) {
                                                            echo json_encode(["error" => "Failed to decode base64 file data."]);
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


                                                        $decodedFileContent = base64_decode($base64File);
                                                        $fileName = basename($name);


                                                        echo '<h5>' . $this->lang->line('resume') . '</h5>';
                                                        echo '<a href="data:' . $mimeType . ';base64,' . base64_encode($decodedFileContent) . '" download="' . $fileName . '" class="btn btn-default btn-xs" data-toggle="tooltip" title="' . $this->lang->line('download') . '">';
                                                        echo '<i class="fa fa-download"></i> Resume</a>';
                                                    }
                                                    ?>
                                            <div class="icon">
                                                <i class="fa fa-file-text-o"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <!--./col-md-3-->
                                    <?php } ?>
                                    <?php if (!empty($staff["joining_letter"]) && strtoupper($staff["joining_letter"]) !== 'NA') { ?>
                                    <div class="col-md-3 col-sm-6">
                                        <div class="staffprofile">
                                            <?php
                                                    if (!empty($staff['joining_letter'])) {
                                                        $userdata = $this->session->userdata('hospitaladmin');
                                                        $accessToken = $userdata['accessToken'] ?? '';

                                                        $joiningLetter = $staff['joining_letter'];
                                                        $url = "https://phr-api.plenome.com/file_upload/getDocs";
                                                        $client = curl_init($url);

                                                        curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
                                                        curl_setopt($client, CURLOPT_POST, true);
                                                        curl_setopt($client, CURLOPT_POSTFIELDS, json_encode(['value' => $joiningLetter]));
                                                        curl_setopt($client, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Authorization: ' . $accessToken]);

                                                        $response = curl_exec($client);
                                                        curl_close($client);

                                                        if ($response === false) {
                                                            echo json_encode(["error" => "No response from server."]);
                                                            return;
                                                        }

                                                        $base64File = trim($response);
                                                        $fileData = base64_decode($base64File);

                                                        if ($fileData === false) {
                                                            echo json_encode(["error" => "Failed to decode base64 file data."]);
                                                            return;
                                                        }

                                                        $fileInfo = pathinfo($joiningLetter);
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

                                                        // Prepare HTML Output
                                                        echo '<h5>' . $this->lang->line('joining_letter') . '</h5>';
                                                        echo '<a href="data:' . $mimeType . ';base64,' . base64_encode($fileData) . '" download="' . basename($joiningLetter) . '" class="btn btn-default btn-xs" data-toggle="tooltip" title="' . $this->lang->line('download') . '">';
                                                        echo '<i class="fa fa-download"></i></a>';
                                                    }
                                                    ?>
                                            </a>
                                            <div class="icon">
                                                <i class="fa fa-file-archive-o"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <!--./col-md-3-->
                                    <?php } ?>
                                    <?php if (!empty(trim($staff["resignation_letter"])) && strtoupper(trim($staff["resignation_letter"])) !== 'NA') { ?>
                                    <div class="col-md-3 col-sm-6">
                                        <div class="staffprofile">
                                            <?php
                                                    if (!empty($staff['resignation_letter'])) {
                                                        $userdata = $this->session->userdata('hospitaladmin');
                                                        $accessToken = $userdata['accessToken'] ?? '';
                                                        $resignationLetter = $staff['resignation_letter'];
                                                        $url = "https://phr-api.plenome.com/file_upload/getDocs";
                                                        $client = curl_init($url);

                                                        curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
                                                        curl_setopt($client, CURLOPT_POST, true);
                                                        curl_setopt($client, CURLOPT_POSTFIELDS, json_encode(['value' => $resignationLetter]));
                                                        curl_setopt($client, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Authorization: ' . $accessToken]);

                                                        $response = curl_exec($client);
                                                        curl_close($client);

                                                        if ($response === false) {
                                                            echo json_encode(["error" => "No response from server."]);
                                                            return;
                                                        }

                                                        $base64File = trim($response);
                                                        $fileData = base64_decode($base64File);

                                                        if ($fileData === false) {
                                                            echo json_encode(["error" => "Failed to decode base64 file data."]);
                                                            return;
                                                        }

                                                        $fileInfo = pathinfo($resignationLetter);
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

                                                        // Prepare HTML Output
                                                        echo '<h5>' . $this->lang->line('resignation_letter') . '</h5>';
                                                        echo '<a href="data:' . $mimeType . ';base64,' . base64_encode($fileData) . '" download="' . basename($resignationLetter) . '" class="btn btn-default btn-xs" data-toggle="tooltip" title="' . $this->lang->line('download') . '">';
                                                        echo '<i class="fa fa-download"></i></a>';
                                                    }
                                                    ?>
                                            <div class="icon">
                                                <i class="fa fa-file-archive-o"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <!--./col-md-3-->
                                    <?php } ?>
                                    <?php if (!empty(trim($staff["other_document_file"])) && strtoupper(trim($staff["other_document_file"])) !== 'NA') { ?>
                                    <div class="col-md-3 col-sm-6">
                                        <div class="staffprofile">
                                            <?php
                                                    if (!empty($staff['other_document_file'])) {
                                                        $userdata = $this->session->userdata('hospitaladmin');
                                                        $accessToken = $userdata['accessToken'] ?? '';
                                                        $otherDocument = $staff['other_document_file'];
                                                        $url = "https://phr-api.plenome.com/file_upload/getDocs";
                                                        $client = curl_init($url);

                                                        curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
                                                        curl_setopt($client, CURLOPT_POST, true);
                                                        curl_setopt($client, CURLOPT_POSTFIELDS, json_encode(['value' => $otherDocument]));
                                                        curl_setopt($client, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Authorization: ' . $accessToken]);

                                                        $response = curl_exec($client);
                                                        curl_close($client);

                                                        if ($response === false) {
                                                            echo json_encode(["error" => "No response from server."]);
                                                            return;
                                                        }

                                                        $base64File = trim($response);
                                                        $fileData = base64_decode($base64File);

                                                        if ($fileData === false) {
                                                            echo json_encode(["error" => "Failed to decode base64 file data."]);
                                                            return;
                                                        }

                                                        $fileInfo = pathinfo($otherDocument);
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

                                                        // Prepare HTML Output
                                                        echo '<h5>' . $this->lang->line('other_documents') . '</h5>';
                                                        echo '<a href="data:' . $mimeType . ';base64,' . base64_encode($fileData) . '" download="' . basename($otherDocument) . '" class="btn btn-default btn-xs" data-toggle="tooltip" title="' . $this->lang->line('download') . '">';
                                                        echo '<i class="fa fa-download"></i></a>';
                                                    }
                                                    ?>
                                            <div class="icon">
                                                <i class="fa fa-file-archive-o"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <!--./col-md-3-->
                                    <?php } ?>
                                    <?php } ?>
                                </div>
                                <!--./row-->
                            </div>
                            </table>
                        </div>
                        <div class="tab-pane" id="timeline">
                            <div><?php if ($this->rbac->hasPrivilege('staff_timeline', 'can_add')) { ?>


                                <button id="myTimelineButton" class="btn btn-sm btn-primary pull-right"><i
                                        class="fa fa-plus"></i> <?php echo $this->lang->line('add'); ?></button>

                                <?php } ?>
                            </div>
                            <br />
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
                                            <span class="bg-blue"><?php
                                                                            echo (!empty(trim($value['timeline_date'])) && $value['timeline_date'] != '0000-00-00')
                                                                                ? date("d/m/Y", strtotime(trim($value['timeline_date'])))
                                                                                : '-';
                                                                            ?>
                                            </span>
                                        </li>
                                        <li>
                                            <i class="fa fa-list-alt bg-blue"></i>
                                            <div class="timeline-item">
                                                <?php if ($this->rbac->hasPrivilege('edittimeline', 'can_delete')) {
                                                        ?>
                                                <span class="time">
                                                    <a onclick="editstaffTimeline('<?php echo $value['id']; ?>')"
                                                        class="btn btn-default btn-xs" data-toggle="tooltip" title=""
                                                        data-original-title="<?php echo $this->lang->line('edit'); ?>">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                </span>
                                                <?php } ?>
                                                <?php if ($this->rbac->hasPrivilege('staff_timeline', 'can_delete')) { ?>
                                                <span class="time"><a class="defaults-c text-right"
                                                        data-toggle="tooltip" title=""
                                                        onclick="delete_timeline('<?php echo $value['id']; ?>')"
                                                        data-original-title="<?php echo $this->lang->line('delete'); ?>"><i
                                                            class="fa fa-trash"></i></a></span>
                                                <?php } ?>
                                                <?php if (!empty($value["document"])) { ?>
                                                <span class="time">
                                                    <?php
                                                                if (!empty($value["document"])) {
                                                                    $userdata = $this->session->userdata('hospitaladmin');
                                                                    $accessToken = $userdata['accessToken'] ?? '';
                                                                    echo  $otherDocument = $value["document"];
                                                                    $url = "https://phr-api.plenome.com/file_upload/getDocs";
                                                                    $client = curl_init($url);

                                                                    curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
                                                                    curl_setopt($client, CURLOPT_POST, true);
                                                                    curl_setopt($client, CURLOPT_POSTFIELDS, json_encode(['value' => $otherDocument]));
                                                                    curl_setopt($client, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Authorization: ' . $accessToken]);

                                                                    $response = curl_exec($client);
                                                                    curl_close($client);

                                                                    if ($response === false) {
                                                                        echo json_encode(["error" => "No response from server."]);
                                                                        return;
                                                                    }

                                                                    $base64File = trim($response);
                                                                    $fileData = base64_decode($base64File);

                                                                    if ($fileData === false) {
                                                                        echo json_encode(["error" => "Failed to decode base64 file data."]);
                                                                        return;
                                                                    }

                                                                    $fileInfo = pathinfo($otherDocument);
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
                                                                }
                                                                ?>
                                                    <a class="defaults-c text-right" data-toggle="tooltip" title=""
                                                        href="<?= $value["document"] ?>"
                                                        data-original-title="<?php echo $this->lang->line('download'); ?>"><i
                                                            class="fa fa-download"></i></a></span>
                                                <?php } ?>
                                                <h3 class="timeline-header text-aqua"> <?php echo $value['title']; ?>
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
                        <div class="tab-pane" id="attendance">
                            <div class="row">
                                <div class="col-lg-3 col-md-6 col-sm-6 col20per">
                                    <div class="staffprofile">
                                        <h5><?php echo $this->lang->line('total_present'); ?></h5>
                                        <h4>
                                            <?php
                                            if (!empty($countAttendance[date("Y")]["present"])) {
                                                echo $countAttendance[date("Y")]["present"];
                                            } else {
                                                echo "0";
                                            }
                                            ?>
                                        </h4>
                                        <div class="icon">
                                            <i class="fa  fa-check-square-o"></i>
                                        </div>
                                    </div>
                                </div>
                                <!--./col-md-3-->
                                <div class="col-lg-3 col-md-6 col-sm-6 col20per">
                                    <div class="staffprofile">
                                        <h5><?php echo $this->lang->line('total_late'); ?></h5>
                                        <h4>
                                            <?php
                                            if (!empty($countAttendance[date("Y")]["late"])) {
                                                echo $countAttendance[date("Y")]["late"];
                                            } else {
                                                echo "0";
                                            }
                                            ?>
                                        </h4>
                                        <div class="icon">
                                            <i class="fa  fa-check-square-o"></i>
                                        </div>
                                    </div>
                                </div>
                                <!--./col-md-3-->
                                <div class="col-lg-3 col-md-6 col-sm-6 col20per">
                                    <div class="staffprofile">
                                        <h5><?php echo $this->lang->line('total_absent'); ?></h5>
                                        <h4>
                                            <?php
                                            if (!empty($countAttendance[date("Y")]["absent"])) {
                                                echo $countAttendance[date("Y")]["absent"];
                                            } else {
                                                echo "0";
                                            }
                                            ?>
                                        </h4>
                                        <div class="icon">
                                            <i class="fa  fa-check-square-o"></i>
                                        </div>
                                    </div>
                                </div>
                                <!--./col-md-3-->
                                <div class="col-lg-3 col-md-6 col-sm-6 col20per">
                                    <div class="staffprofile">
                                        <h5><?php echo $this->lang->line('total_half_day'); ?></h5>
                                        <h4>
                                            <?php
                                            if (!empty($countAttendance[date("Y")]["half_day"])) {
                                                echo $countAttendance[date("Y")]["half_day"];
                                            } else {
                                                echo "0";
                                            }
                                            ?>
                                        </h4>
                                        <div class="icon">
                                            <i class="fa  fa-check-square-o"></i>
                                        </div>
                                    </div>
                                </div>
                                <!--./col-md-3-->
                                <div class="col-lg-3 col-md-6 col-sm-6 col20per">
                                    <div class="staffprofile">
                                        <h5><?php echo $this->lang->line('total_holiday'); ?></h5>
                                        <h4>
                                            <?php
                                            if (!empty($countAttendance[date("Y")]["holiday"])) {
                                                echo $countAttendance[date("Y")]["holiday"];
                                            } else {
                                                echo "0";
                                            }
                                            ?>
                                        </h4>
                                        <div class="icon">
                                            <i class="fa  fa-check-square-o"></i>
                                        </div>
                                    </div>
                                </div>
                                <!--./col-md-3-->
                            </div>
                            <div class="row">
                                <div class="col-md-3 col-sm-3">
                                    <form id="" action="" method="">
                                        <div class="form-group">
                                            <label class="sess18"><?php echo $this->lang->line('year'); ?></label>
                                            <div class="sessyearbox">
                                                <select class="form-control" style="margin-top: -5px;" name="year"
                                                    onchange="ajax_attendance('<?php echo $staff["id"]; ?>', this.value)">
                                                    <?php foreach ($yearlist as $yearkey => $yearvalue) {
                                                    ?>
                                                    <option <?php
                                                                if ($yearvalue["year"] == date("Y")) {
                                                                    echo "selected";
                                                                }
                                                                ?> value="<?php echo $yearvalue["year"]; ?>">
                                                        <?php echo $yearvalue["year"]; ?>
                                                    </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <span class="text-danger"><?php echo form_error('year'); ?></span>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-9 col-sm-9">
                                    <div class="halfday pull-right">
                                        <?php

                                        foreach ($attendencetypeslist as $key_type => $value_type) {
                                        ?>
                                        <b>
                                            <?php
                                                $att_type = strtolower($value_type['type']);
                                                echo $this->lang->line($att_type) . ": " . $value_type['key_value'] . "";
                                                ?>
                                        </b>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="download_label"><?php echo $this->lang->line('staff_attendance_report'); ?>
                                    <?php echo $staff["name"] . " " . $staff["surname"]; ?>
                                </div>
                                <div id="ajaxattendance" class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover" id="attendancetable">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <?php echo $this->lang->line('date_month'); ?>
                                                </th>
                                                <?php foreach ($monthlist as $monthkey => $monthvalue) {
                                                ?>
                                                <th><?php echo $monthvalue; ?></th>
                                                <?php }
                                                ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $j = 0;
                                            for ($i = 1; $i <= 31; $i++) {
                                            ?>
                                            <tr>
                                                <td><?php echo $attendence_array[$j] ?></td>
                                                <?php

                                                    foreach ($monthlist as $key => $value) {
                                                        $datemonth = date("m", strtotime($value));
                                                        $dayscount = intval(date('t', strtotime($value)));

                                                        if ($i <= $dayscount) {
                                                            $att_dates = date("Y") . "-" . $datemonth . "-" . sprintf("%02d", $i);


                                                    ?>
                                                <td><span data-toggle="popover" class="detail_popover"
                                                        data-original-title="" title=""><a href="#" style="color:#333"><?php
                                                                                            if (array_key_exists($att_dates, $resultlist)) {
                                                                                                echo $resultlist[$att_dates]["key"];
                                                                                            }
                                                                                            ?></a></span>
                                                    <div class="fee_detail_popover" style="display: none">
                                                        <?php echo $resultlist[$att_dates]["remark"]; ?>
                                                    </div>
                                                </td>


                                                <?php } else {
                                                            echo "<td></td>";
                                                        }
                                                    } ?>
                                            </tr>
                                            <?php
                                                $j++;
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="leaves">
                            <div class="row row-flex">
                                <?php foreach ($leavedetails as $ldkey => $ldvalue) {
                                ?>
                                <?php if (!empty($ldvalue["alloted_leave"])) {
                                    ?>
                                <div class="col-lg-3 col-md-4 col-sm-6">
                                    <div class="staffprofile">
                                        <h5><?php echo $ldvalue["type"] . " (" . $ldvalue["alloted_leave"] . ")"; ?>
                                        </h5>
                                        <p><?php echo $this->lang->line('used'); ?>: <?php
                                                                                                if (!empty($ldvalue["approve_leave"])) {
                                                                                                    echo $ldvalue["approve_leave"];
                                                                                                } else {
                                                                                                    echo "0";
                                                                                                }
                                                                                                ?></p>
                                        <p><?php echo $this->lang->line('available'); ?>:
                                            <?php echo $ldvalue["alloted_leave"] - $ldvalue["approve_leave"] ?>
                                        </p>
                                        <div class="icon">
                                            <i class="fa fa-plane"></i>
                                        </div>
                                    </div>
                                </div>
                                <!--./col-md-3-->
                                <?php
                                    }
                                }
                                ?>
                            </div>
                            <div class="timeline-header no-border">
                                <div class="download_label"><?php echo $this->lang->line('staff_leave_request'); ?>
                                    <?php echo $staff["name"] . " " . $staff["surname"]; ?>
                                </div>
                                <div class="table-responsive" style="clear: both;">
                                    <table class="table table-striped table-bordered table-hover example">
                                        <thead>
                                            <th><?php echo $this->lang->line('leave_type'); ?></th>
                                            <th><?php echo $this->lang->line('leave_date'); ?></th>
                                            <th><?php echo $this->lang->line('days'); ?></th>
                                            <th><?php echo $this->lang->line('apply_date'); ?></th>
                                            <th><?php echo $this->lang->line("status") ?></th>
                                            <th class="text-right noExport"><?php echo $this->lang->line("action") ?>
                                            </th>
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach ($staff_leaves as $key => $value) {
                                                $label = "";
                                                if ($value["status"] == "approve") {
                                                    $label = "class='label label-success'";
                                                } else if ($value["status"] == "pending") {
                                                    $label = "class='label label-warning'";
                                                } else if ($value["status"] == "disapprove") {
                                                    $label = "class='label label-danger'";
                                                }
                                            ?>
                                            <tr>
                                                <td><?php echo $value["type"]; ?></td>
                                                <td>
                                                    <?php
                                                        $leaveFrom = (!empty(trim($value['leave_from'])) && $value['leave_from'] != '0000-00-00')
                                                            ? date("d/m/Y", strtotime(trim($value['leave_from'])))
                                                            : '-';

                                                        $leaveTo = (!empty(trim($value['leave_to'])) && $value['leave_to'] != '0000-00-00')
                                                            ? date("d/m/Y", strtotime(trim($value['leave_to'])))
                                                            : '-';

                                                        echo $leaveFrom . " - " . $leaveTo;
                                                        ?>
                                                </td>

                                                </td>
                                                <td><?php echo $value["leave_days"]; ?></td>
                                                <td>
                                                    <?php
                                                        echo (!empty(trim($value['date'])) && $value['date'] != '0000-00-00')
                                                            ? date("d/m/Y", strtotime(trim($value['date'])))
                                                            : '-';
                                                        ?>
                                                </td>

                                                </td>
                                                <td><small style="text-transform: capitalize;"
                                                        <?php echo $label ?>><?php echo $status[$value["status"]]; ?></small>
                                                </td>
                                                <td class="text-right noExport"><a href="#leavedetails"
                                                        onclick="getRecord('<?php echo $value["id"] ?>')" role="button"
                                                        class="btn btn-default btn-xs" data-toggle="tooltip"
                                                        title="<?php echo $this->lang->line('view'); ?>"><i
                                                            class="fa fa-reorder"></i></a>
                                                    <?php if (!empty($value['document_file'])) { ?>
                                                    <a href="<?php echo base_url(); ?>admin/staff/download/<?php echo $value['staff_id'] . "/" . $value['document_file']; ?>"
                                                        class="btn btn-default btn-xs" data-toggle="tooltip"
                                                        title="<?php echo $this->lang->line('download'); ?>">
                                                        <i class="fa fa-download"></i>
                                                    </a>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
</div>
<script type="text/javascript">
$(".myTransportFeeBtn").click(function() {
    $("span[id$='_error']").html("");
    $('#transport_amount').val("");
    $('#transport_amount_discount').val("0");
    $('#transport_amount_fine').val("0");
    var student_session_id = $(this).data("student-session-id");
    $('.transport_fees_title').html("<b>Upload Document</b>");
    $('#transport_student_session_id').val(student_session_id);
    $('#myTransportFeesModal').modal({
        backdrop: 'static',
        keyboard: false,
        show: true

    });
});
</script>
<div id="leavedetails" class="modal fade " role="dialog">
    <div class="modal-dialog modal-dialog2 modal-lg">
        <div class="modal-dialog modal-dialog2 modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><?php echo $this->lang->line('details'); ?></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <form role="form" id="leavedetails_form" action="">
                            <div class="col-md-12 table-responsive">
                                <table class="table mb0 table-striped table-bordered examples">
                                    <tr>
                                        <th width="15%"><?php echo $this->lang->line('name'); ?></th>
                                        <td width="35%"><span id='name'></span></td>
                                        <th width="15%"><?php echo $this->lang->line('staff_id'); ?></th>
                                        <td width="35%"><span id="employee_id"></span>
                                            <span
                                                class="text-danger"><?php echo form_error('leave_request_id'); ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th><?php echo $this->lang->line('leave'); ?></th>
                                        <td><span id='leave_from'></span> - <label for="exampleInputEmail1">
                                            </label><span id='leave_to'> </span> (<span id='days'></span>)
                                            <span class="text-danger"><?php echo form_error('leave_from'); ?></span>
                                        </td>
                                        <th><?php echo $this->lang->line('leave_type'); ?></th>
                                        <td><span id="leave_type"></span>
                                            <input id="leave_request_id" name="leave_request_id" placeholder=""
                                                type="hidden" class="form-control" />
                                            <span
                                                class="text-danger"><?php echo form_error('leave_request_id'); ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th><?php echo $this->lang->line('status'); ?></th>
                                        <td>
                                            <span id="status" style="text-transform: capitalize;"></span>
                                        </td>
                                        <th><?php echo $this->lang->line('apply'); ?>
                                            <?php echo $this->lang->line('date'); ?>
                                        </th>
                                        <td><span id="applied_date"></span></td>
                                    </tr>
                                    <tr>
                                        <th><?php echo $this->lang->line('reason'); ?></th>
                                        <td><span id="reason"> </span></td>
                                        <th><?php echo $this->lang->line('note'); ?></th>
                                        <td>
                                            <span id="remark"> </span>
                                        </td>
                                    </tr>

                                </table>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myTimelineModal" role="dialog">
    <div class="modal-dialog modal-sm400">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title title transport_fees_title"></h4>
            </div>

            <form id="timelineform" name="timelineform" method="post"
                action="<?php echo base_url() . "admin/timeline/add_staff_timeline" ?>" enctype="multipart/form-data"
                class="ptt10">
                <div class="modal-body pt0 pb0">
                    <?php echo $this->customlib->getCSRF(); ?>
                    <div id='timeline_hide_show'>
                        <input type="hidden" name="staff_id" value="<?php echo $staff["id"] ?>" id="staff_id">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('title'); ?></label><small class="req">
                                        *</small>
                                    <input id="timeline_title" name="timeline_title" placeholder="" type="text"
                                        class="form-control" />
                                    <span class="text-danger"><?php echo form_error('timeline_title'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('date'); ?></label><small class="req"> *</small>
                                    <input id="timeline_date" name="timeline_date"
                                        value="<?php echo set_value('timeline_date', date($this->customlib->getHospitalDateFormat())); ?>"
                                        placeholder="" type="text" class="form-control" />
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
                                    <div><input id="timeline_doc_id" name="timeline_doc" placeholder="" type="file"
                                            class="filestyle form-control" data-height="28"
                                            value="<?php echo set_value('timeline_doc'); ?>" />
                                        <span class="text-danger"><?php echo form_error('timeline_doc'); ?></span>
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
                <div class="modal-footer">
                    <button type="submit" id='timelinebtn'
                        data-loading-text="<?php echo $this->lang->line('processing'); ?>"
                        class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- Edit Timeline -->
<div class="modal fade" id="myTimelineEditModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm400" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('edit_timeline'); ?></h4>
            </div>
            <div class="modal-body pt0 pb0">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 paddlr">
                        <form id="edit_timeline" accept-charset="utf-8" enctype="multipart/form-data" method="post"
                            class="ptt10">
                            <div class="row">
                                <div class=" col-md-12">
                                    <div class="form-group">
                                        <label
                                            for="exampleInputEmail1"><?php echo $this->lang->line('title'); ?></label><small
                                            class="req"> *</small>
                                        <input type="hidden" name="staff_id" id="estaffid" value="">
                                        <input type="hidden" name="timeline_id" id="etimelineid" value="">
                                        <input id="etimelinetitle" name="timeline_title" placeholder="" type="text"
                                            class="form-control" />
                                        <span class="text-danger"><?php echo form_error('timeline_title'); ?></span>
                                    </div>
                                    <div class="form-group">
                                        <label
                                            for="exampleInputEmail1"><?php echo $this->lang->line('date'); ?></label><small
                                            class="req"> *</small>
                                        <input type="text" name="timeline_date" class="form-control date"
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
                                        <div class="" style="margin-top:-5px; border:0; outline:none;"><input
                                                id="etimeline_doc_id" name="timeline_doc" placeholder="" type="file"
                                                class="filestyle form-control" data-height="40"
                                                value="<?php echo set_value('timeline_doc'); ?>" />
                                            <span class="text-danger"><?php echo form_error('timeline_doc'); ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label
                                            for="exampleInputEmail1"><?php echo $this->lang->line('visible_to_this_person'); ?></label>
                                        <input id="evisible_check" name="visible_check" value="yes" placeholder=""
                                            type="checkbox" />
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <div class="pull-right">
                                    <button type="submit"
                                        data-loading-text="<?php echo $this->lang->line('processing') ?>"
                                        id="edit_timelinebtn"
                                        class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="scheduleModal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title_logindetail"></h4>
            </div>
            <div class="modal-body_logindetail">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                    data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
            </div>
        </div>
    </div>
</div>

<div id="payslipview" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog2 modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('details'); ?> <span id="print"></span></h4>
            </div>
            <div class="modal-body" id="testdata">

            </div>
        </div>
    </div>
</div>

<div id="changepwdmodal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('staff_change_password') ?></h4>
            </div>
            <form method="post" id="change_pass">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="email"><?php echo $this->lang->line('staff_password') ?></label><small class="req">
                            *</small>
                        <input type="password" class="form-control" name="new_pass" id="pass">
                    </div>
                    <div class="form-group">
                        <label for="pwd"><?php echo $this->lang->line('staff_confirm_password') ?></label><small
                            class="req"> *</small>
                        <input type="password" class="form-control" name="confirm_pass" id="pwd">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="changepassbtn1"
                        data-loading-text="<?php echo $this->lang->line('processing'); ?>" class="btn btn-primary"><i
                            class="fa fa-check-circle"> <?php echo $this->lang->line('save') ?></i></button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="appointmentModal" tabindex="-1" aria-labelledby="appointmentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="appointmentModalLabel"><span id="Tittle"></span> Doctor Confirmation</h5>
            </div>
            <div class="modal-body">
                <p id="modalContent" class="fs-5">
                    This doctor has <strong id="appointmentCount">3 upcoming appointments</strong>.
                    If you <span id="action"></span> the doctor, all these appointments will need to be canceled.
                    Are you sure you want to proceed?
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="confirmDisable">Yes, <span id="buttonname"></span>
                    Doctor</button>
                <button type="button" class="btn btn-secondary" id="cancelModal">No, Cancel</button>
            </div>
        </div>
    </div>
</div>




<?php
$data = $this->session->userdata('hospitaladmin');
$api_base_url = $this->config->item('api_base_url');
?>
<script type="text/javascript">
function holdModal(modalId) {
    $('#' + modalId).modal({
        backdrop: 'static',
        keyboard: false,
        show: true
    });
}

$(document).ready(function(e) {
    $("#change_pass").on('submit', function(e) {
        e.preventDefault();
        $("#changepassbtn").button('loading');
        var staff_id = $("#staff_id").val();
        let password = $("#pwd").val();
        let new_pass = $("#pass").val();
        let hospital_id = '<?= $data["hospital_id"] ?>';
        var passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
        if (!passwordPattern.test(password)) {
            errorMsg(
                'Password must be at least 8 characters long, contain at least one uppercase letter, one lowercase letter, one number, and one special character.'
            );
            $("#changepassbtn").button('reset');
            return;
        }
        if (password !== new_pass) {
            errorMsg('Passwords do not match');
            $("#changepassbtn").button('reset');
            return;
        }
        let data = {
            staff_id: staff_id,
            password: password,
            hospital_id: hospital_id
        };
        sendAjaxRequest("<?= $api_base_url ?>human-resource-staff/password/" + staff_id,
            "PATCH", data,
            function(response) {
                handleResponse(response);
            });
    });
});

$(document).ready(function(e) {
    $("#edit_timeline").on('submit', (function(e) {
        $("#edit_timelinebtn").button('loading');
        var staff_id = $("#staff_id").val();
        e.preventDefault();
        $.ajax({
            url: "<?php echo site_url("admin/timeline/edit_staff_timeline") ?>",
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
                $("#edit_timelinebtn").button('reset');
            },
            error: function(e) {
                alert("Fail");
                console.log(e);
            }
        });
    }));
});

$("#myTimelineButton").click(function() {
    $("#reset").click();
    $('.transport_fees_title').html("<b><?php echo $this->lang->line('add_timeline'); ?></b>");
    $('#myTimelineModal').modal({
        backdrop: 'static',
        keyboard: false,
        show: true

    });
});

$(document).ready(function(e) {
    $("#timelineform").on('submit', (function(e) {
        $("#timelinebtn").button('loading');
        var staff_id = $("#staff_id").val();
        e.preventDefault();
        $.ajax({
            url: "<?php echo site_url("admin/timeline/add_staff_timeline") ?>",
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
                    $.ajax({
                        url: '<?php echo base_url(); ?>admin/timeline/staff_timeline/' +
                            staff_id,
                        success: function(res) {
                            $('#timeline_list').html(res);
                            $('#myTimelineModal').modal('toggle');
                        },
                        error: function() {
                            alert("Fail")
                        }
                    });

                }
                $("#timelinebtn").button('reset');
            },
            error: function(e) {
                alert("Fail");
                console.log(e);
            }
        });
    }));
});

$('#myTimelineModal').on('hidden.bs.modal', function() {
    $(this).find('form').trigger('reset');
    $(".dropify-clear").click();
})


function editstaffTimeline(id) {
    $.ajax({
        url: '<?php echo base_url(); ?>admin/patient/editstaffTimeline',
        type: "POST",
        data: {
            id: id
        },
        dataType: 'json',
        success: function(data) {
            var date_format =
                '<?php echo $result = strtr($this->customlib->getHospitalDateFormat(), ['d' => 'dd', 'm' => 'MM', 'Y' => 'yyyy']) ?>';
            var dt = new Date(data.timeline_date).toString(date_format);
            $("#etimelineid").val(id);
            $("#estaffid").val(data.staff_id);
            $("#etimelinetitle").val(data.title);
            $("#etimelinedate").val(dt);
            $("#timelineedesc").val(data.description);
            if (data.status == '') {} else {
                $("#evisible_check").attr('checked', true);
            }
            holdModal('myTimelineEditModal');
        },
    });
}

function delete_timeline(id) {
    var staff_id = $("#staff_id").val();
    if (confirm('<?php echo $this->lang->line("delete_confirm"); ?>')) {
        $.ajax({
            url: '<?php echo base_url(); ?>admin/timeline/delete_staff_timeline/' + id,
            success: function(res) {
                $.ajax({
                    url: '<?php echo base_url(); ?>admin/timeline/staff_timeline/' + staff_id,
                    success: function(res) {
                        $('#timeline_list').html(res);
                        successMsg('<?php echo $this->lang->line('delete_message') ?>');
                    },
                    error: function() {
                        alert("Fail")
                    }
                });
            },
            error: function() {
                alert("Fail")
            }
        });
    }
}

$(document).ready(function() {
    $("#attendancetable").DataTable({
        searching: false,
        ordering: false,
        paging: false,
        bSort: false,
        info: false,
        dom: "Bfrtip",
        buttons: [

            {
                extend: 'copyHtml5',
                text: '<i class="fa fa-files-o"></i>',
                titleAttr: 'Copy',
                title: $('.download_label').html(),
                exportOptions: {
                    columns: ':visible'
                }
            },

            {
                extend: 'excelHtml5',
                text: '<i class="fa fa-file-excel-o"></i>',
                titleAttr: 'Excel',
                title: $('.download_label').html(),
                exportOptions: {
                    columns: ':visible'
                }
            },

            {
                extend: 'csvHtml5',
                text: '<i class="fa fa-file-text-o"></i>',
                titleAttr: 'CSV',
                title: $('.download_label').html(),
                exportOptions: {
                    columns: ':visible'
                }
            },

            {
                extend: 'pdfHtml5',
                text: '<i class="fa fa-file-pdf-o"></i>',
                titleAttr: 'PDF',
                title: $('.download_label').html(),
                exportOptions: {
                    columns: ':visible'

                }
            },

            {
                extend: 'print',
                text: '<i class="fa fa-print"></i>',
                titleAttr: 'Print',
                title: $('.download_label').html(),
                customize: function(win) {
                    $(win.document.body)
                        .css('font-size', '10pt');

                    $(win.document.body).find('table')
                        .addClass('compact')
                        .css('font-size', 'inherit');
                },
                exportOptions: {
                    columns: ':visible'
                }
            },

            {
                extend: 'colvis',
                text: '<i class="fa fa-columns"></i>',
                titleAttr: 'Columns',
                title: $('.download_label').html(),
                postfixButtons: ['colvisRestore']
            },
        ]
    });
});
</script>
<script>
$(document).ready(function() {
    $(document).on('click', '.change_password', function() {
        $('#changepwdmodal').modal('show');
    });

    $('.detail_popover').popover({
        placement: 'right',
        title: '',
        trigger: 'hover',
        container: 'body',
        html: true,
        content: function() {
            return $(this).closest('td').find('.fee_detail_popover').html();
        }
    });

    var date_format =
        '<?php echo $result = strtr($this->customlib->getHospitalDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy']) ?>';

    $("#timeline_date").datepicker({
        format: date_format,
        autoclose: true

    });
});

function getRecord(id) {
    $('input:radio[name=status]').attr('checked', false);
    var base_url = '<?php echo base_url() ?>';
    $.ajax({
        url: base_url + 'admin/leaverequest/leaveRecord',
        type: 'POST',
        data: {
            id: id
        },
        dataType: "json",
        success: function(result) {
            var leavedate_format =
                '<?php echo $result = strtr($this->customlib->getHospitalDateFormat(), ['d' => 'dd', 'm' => 'MM', 'Y' => 'yyyy']) ?>';
            $('inputs[name="leave_request_id"]').val(result.id);
            $('#name').html(result.name + ' ' + result.surname);
            $('#leave_from').html(new Date(result.leave_from).toString(leavedate_format));
            $('#leave_to').html(new Date(result.leave_to).toString(leavedate_format));
            $('#leave_type').html(result.type);
            $('#reason').html(result.employee_remark);
            $('#applied_date').html(new Date(result.date).toString(leavedate_format));
            $('#days').html(result.leave_days + ' Days');
            $("#remark").html(result.admin_remark);
            $("#employee_id").html(' ' + result.employee_id);
            $("#status").html(' ' + result.status);
        }
    });

    $('#leavedetails').modal({
        show: true,
        backdrop: 'static',
        keyboard: false
    });
};

function ajax_attendance(id, year) {
    var base_url = '<?php echo base_url() ?>';
    $.ajax({
        url: base_url + 'admin/staff/ajax_attendance/' + id,
        type: 'POST',
        data: {
            id: id,
            year: year
        },
        success: function(result) {
            $("#ajaxattendance").html(result);
        }
    });
}

function getPayslip(id) {
    var base_url = '<?php echo base_url() ?>';
    $.ajax({
        url: base_url + 'admin/payroll/payslipView',
        type: 'POST',
        data: {
            payslipid: id
        },
        success: function(result) {
            $("#print").html("<a href='#' class='pull-right modal-title moprint' onclick='printData(" + id +
                ")'  title='Print'><i class='fa fa-print'></i></a>");
            $("#testdata").html(result);
        }
    });

    $('#payslipview').modal({
        show: true,
        backdrop: 'static',
        keyboard: false
    });
};

function printData(id) {
    var base_url = '<?php echo base_url() ?>';
    $.ajax({
        url: base_url + 'admin/payroll/payslipView',
        type: 'POST',
        data: {
            payslipid: id
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
    frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/bootstrap/css/bootstrap.min.css">');
    frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/font-awesome.min.css">');
    frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/ionicons.min.css">');
    frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/AdminLTE.min.css">');
    frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/skins/_all-skins.min.css">');
    frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/iCheck/flat/blue.css">');
    frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/morris/morris.css">');
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
</script>
<?php
$data = $this->session->userdata('hospitaladmin');
$api_base_url = $this->config->item('api_base_url');
?>
<script>
function disableStaff(staffId) {
    if (confirm('<?php echo $this->lang->line("staff_are_you_sure_you_want_to_disable_this_record"); ?>')) {
        $.ajax({
            type: "GET",
            url: "<?php echo base_url() ?>admin/staff/Cheack_appointment",
            data: {
                id: staffId
            },
            dataType: "json",
            success: function(response) {
                if (response.staff_appointment_count > 0) {
                    $("#appointmentCount").text(response.staff_appointment_count +
                        " upcoming appointments");
                    $("#Tittle").text("Disable");
                    $("#action").text("Disable");
                    $("#buttonname").text('Disable');
                    $("#appointmentModal").modal("show");
                    $("#confirmDisable").prop('disabled', true);
                } else {
                    disableDoctor(staffId);
                }
            }
        });
    }
}

function disableDoctor(staff_id) {
    sendAjaxRequest("<?= $api_base_url ?>human-resource-staff/disable/" + staff_id,
        "POST", {},
        function(response) {
            handleResponse(response);
        });
}
</script>

<script>
function enableStaff(staff_id) {
    if (confirm('<?php echo $this->lang->line("staff_are_you_sure_you_want_to_enable_this_record"); ?>')) {
        sendAjaxRequest("<?= $api_base_url ?>human-resource-staff/enable/" + staff_id,
            "POST", {},
            function(response) {
                handleResponse(response);
            });
    }
}
</script>
<script>
$(document).ready(function() {
    $("#cancelModal, #closeModal").on("click", function() {
        $("#appointmentModal").modal("hide");
    });

    $('.delete-staff').off('click').on('click', function(e) {
        e.preventDefault();
        if (confirm(
                '<?php echo $this->lang->line("staff_are_you_sure_you_want_to_enable_this_record"); ?>'
            )) {
            var staffId = $(this).data('id');

            $.ajax({
                type: "GET",
                url: "<?php echo base_url() ?>admin/staff/Cheack_appointment",
                data: {
                    id: staffId
                },
                dataType: "json",
                success: function(response) {
                    if (response.staff_appointment_count > 0) {
                        $("#appointmentCount").text(response.staff_appointment_count +
                            " upcoming appointments");
                        $("#Tittle").text("Disable");
                        $("#action").text("Disable");
                        $("#buttonname").text('Disable');
                        $("#appointmentModal").modal("show");
                        $("#confirmDisable").prop('disabled', true);
                    } else {
                        deleteDoctor(staffId);
                    }
                }
            });

            function deleteDoctor(staffId) {
                var url = '<?= $api_base_url ?>human-resource-staff/' + staffId +
                    "?hospital_id=<?= $data["hospital_id"] ?>";
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    success: function(response) {
                        successMsg("Staff deleted successfully");
                        location.href = "<?php echo base_url('admin/staff/'); ?>";
                    },
                    error: function(xhr, status, error) {
                        console.error('Error deleting staff member:', error);
                    }
                });
            }
        }
    });
});
</script>