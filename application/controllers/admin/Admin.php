<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Admin extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('Enc_lib');
        $this->load->library('datatables');
        $this->config->load("payroll");
        $this->config->load("image_valid");
        $this->config->load("mailsms");
        $this->load->model('transaction_model');
        $marital_status = $this->config->item('marital_status');
        $bloodgroup = $this->config->item('bloodgroup');
        $this->load->library('Customlib');
        $this->load->helper('customfield_helper');
        $this->load->model('Admin_model');
    }

    public function unauthorized()
    {
        $data = array();
        $this->load->view('layout/header', $data);
        $this->load->view('unauthorized', $data);
        $this->load->view('layout/footer', $data);
    }

    public function getUserImage()
    {
        $id = $this->customlib->getLoggedInUserID();
        $result = $this->staff_model->get($id);
    }

    public function updatePurchaseCode()
    {
        $this->form_validation->set_rules('email', $this->lang->line('email'), 'required|valid_email|trim|xss_clean');
        $this->form_validation->set_rules('envato_market_purchase_code', $this->lang->line('purchase_code'), 'required|trim|xss_clean');

        if ($this->form_validation->run() == false) {
            $data = array(
                'email' => form_error('email'),
                'envato_market_purchase_code' => form_error('envato_market_purchase_code'),
            );
            $array = array('status' => '2', 'error' => $data);
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($array));
        } else {

            $response = $this->auth->app_update();
        }
    }

    public function backup()
    {
        if (!$this->rbac->hasPrivilege('backup', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'setup');
        $this->session->set_userdata('sub_menu', 'schsettings/index');
        $this->session->set_userdata('inner_menu', 'admin/backup');
        $data['title'] = $this->lang->line('backup_history');
        if ($this->input->server('REQUEST_METHOD') == "POST") {
            if ($this->input->post('backup') == "upload") {
                $this->form_validation->set_rules('file', $this->lang->line('image'), 'callback_handle_upload');
                if ($this->form_validation->run() == false) {
                } else {
                    if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
                        $fileInfo = pathinfo($_FILES["file"]["name"]);
                        $file_name = "db-" . date("Y-m-d_H-i-s") . ".sql";
                        move_uploaded_file($_FILES["file"]["tmp_name"], "./backup/temp_uploaded/" . $file_name);
                        $folder_name = 'temp_uploaded';
                        $path = './backup/';
                        $filePath = $path . $folder_name . '/' . $file_name;
                        $file_restore = $this->load->file($path . $folder_name . '/' . $file_name, true);
                        $db = (array) get_instance()->db;
                        $conn = mysqli_connect('localhost', $db['username'], $db['password'], $db['database']);

                        $sql = '';
                        $error = '';

                        if (file_exists($filePath)) {
                            $lines = file($filePath);

                            foreach ($lines as $line) {

                                // Ignoring comments from the SQL script
                                if (substr($line, 0, 2) == '--' || $line == '') {
                                    continue;
                                }

                                $sql .= $line;

                                if (substr(trim($line), -1, 1) == ';') {
                                    $result = mysqli_query($conn, $sql);
                                    if (!$result) {
                                        $error .= mysqli_error($conn) . "\n";
                                    }
                                    $sql = '';
                                }
                            }
                            $msg = $this->lang->line('restored_message');
                        } // end if file exists


                        $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
                        redirect('admin/admin/backup');
                    }
                }
            }
            if ($this->input->post('backup') == "backup") {
                $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
                $this->load->helper('download');
                $this->load->dbutil();
                $version = $this->customlib->getAppVersion();
                $filename = "db_ver_" . $version . '_' . date("Y-m-d_H-i-s") . ".sql";
                $prefs = array(
                    'ignore' => array(),
                    'format' => 'txt',
                    'filename' => 'mybackup.sql',
                    'add_drop' => true,
                    'add_insert' => true,
                    'newline' => "\n",
                );
                $backup = $this->dbutil->backup($prefs);
                $this->load->helper('file');
                write_file('./backup/database_backup/' . $filename, $backup);
                redirect('admin/admin/backup');
                force_download($filename, $backup);
                $this->session->set_flashdata('feedback', $this->lang->line('success_message_for_client_to_see'));
                redirect('admin/admin/backup');
            } else if ($this->input->post('backup') == "restore") {
                $folder_name = 'database_backup';
                $file_name = $this->input->post('filename');
                $path = './backup/';
                $filePath = $path . $folder_name . '/' . $file_name;
                $file_restore = $this->load->file($path . $folder_name . '/' . $file_name, true);
                $db = (array) get_instance()->db;
                $conn = mysqli_connect('localhost', $db['username'], $db['password'], $db['database']);

                $sql = '';
                $error = '';

                if (file_exists($filePath)) {
                    $lines = file($filePath);

                    foreach ($lines as $line) {

                        // Ignoring comments from the SQL script
                        if (substr($line, 0, 2) == '--' || $line == '') {
                            continue;
                        }

                        $sql .= $line;

                        if (substr(trim($line), -1, 1) == ';') {
                            $result = mysqli_query($conn, $sql);
                            if (!$result) {
                                $error .= mysqli_error($conn) . "\n";
                            }
                            $sql = '';
                        }
                    }
                    $msg = $this->lang->line('restored_message');
                } // end if file exists
                $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $msg . '</div>');
                redirect('admin/admin/backup');
            }
        }
        $dir = "./backup/database_backup/";
        $result = array();
        $cdir = scandir($dir);
        foreach ($cdir as $key => $value) {
            if (!in_array($value, array(".", ".."))) {
                if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) {
                    $result[$value] = dirToArray($dir . DIRECTORY_SEPARATOR . $value);
                } else {
                    $result[] = $value;
                }
            }
        }
        $data['dbfileList'] = $result;
        $setting_result = $this->setting_model->get();
        $data['settinglist'] = $setting_result;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/backup', $data);
        $this->load->view('layout/footer', $data);
    }

    public function changepass()
    {
        $this->session->set_userdata('top_menu', 'System Settings');
        $this->session->set_userdata('sub_menu', 'changepass/index');
        $data['title'] = $this->lang->line('change_password');
        $this->form_validation->set_rules('current_pass', $this->lang->line('current_password'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('new_pass', $this->lang->line('new_password'), 'trim|required|xss_clean|matches[confirm_pass]');
        $this->form_validation->set_rules('confirm_pass', $this->lang->line('confirm_password'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $sessionData = $this->session->userdata('hospitaladmin');
            $this->data['id'] = $sessionData['id'];
            $this->data['username'] = $sessionData['username'];
            $this->load->view('layout/header', $data);
            $this->load->view('admin/change_password', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $sessionData = $this->session->userdata('hospitaladmin');
            $userdata = $this->customlib->getUserData();
            $data_array = array(
                'current_pass' => $this->input->post('current_pass'),
                'new_pass' => md5($this->input->post('new_pass')),
                'user_id' => $sessionData['id'],
                'user_email' => $sessionData['email'],
                'user_name' => $sessionData['username'],
            );
            $newdata = array(
                'id' => $sessionData['id'],
                'password' => $this->enc_lib->passHashEnc($this->input->post('new_pass')),
            );
            $check = $this->enc_lib->passHashDyc($this->input->post('current_pass'), $userdata["password"]);
            $query1 = $this->admin_model->checkOldPass($data_array);

            if ($query1) {

                if ($check) {
                    $query2 = $this->staff_model->add($newdata);
                    if ($query2) {
                        $data['error_message'] = "<div class='alert alert-success'>" . $this->lang->line('password_changed_successfully') . "</div>";
                        $this->load->view('layout/header', $data);
                        $this->load->view('admin/change_password', $data);
                        $this->load->view('layout/footer', $data);
                    }
                } else {
                    $data['error_message'] = "<div class='alert alert-danger'>" . $this->lang->line('invalid_current_password') . "</div>";
                    $this->load->view('layout/header', $data);
                    $this->load->view('admin/change_password', $data);
                    $this->load->view('layout/footer', $data);
                }
            } else {

                $data['error_message'] = "<div class='alert alert-danger'>" . $this->lang->line('invalid_current_password') . "</div>";
                $this->load->view('layout/header', $data);
                $this->load->view('admin/change_password', $data);
                $this->load->view('layout/footer', $data);
            }
        }
    }

    public function downloadbackup($file)
    {
        $this->load->helper('download');
        $filepath = "./backup/database_backup/" . $file;
        $data = file_get_contents($filepath);
        $name = $file;
        force_download($name, $data);
    }

    public function dropbackup($file)
    {
        if (!$this->rbac->hasPrivilege('backup', 'can_delete')) {
            access_denied();
        }
        unlink('./backup/database_backup/' . $file);
        redirect('admin/admin/backup');
    }

    public function disablepatient()
    {
        if (!$this->Admin_model->validationModule('patient')) {
            access_denied();
        }
        $data['title'] = 'Search';
        $userdata = $this->customlib->getUserData();
        $data['fields'] = $this->customfield_model->get_custom_fields('patient', 1);
        $data["bloodgroup"] = $this->bloodbankstatus_model->get_product(null, 1);
        $this->load->view('layout/header', $data);
        $this->load->view('admin/searchdisablepatient', $data);
        $this->load->view('layout/footer', $data);
    }

    public function search()
    {

        if (!$this->rbac->hasPrivilege('patient', 'can_view')) {
            access_denied();
        }
        if (!$this->Admin_model->validationModule('patient')) {
            access_denied();
        }

        $api_base_url = $this->config->item('api_base_url');
        $limit = 10;
        $page = 1;
        $hospitaldata = $this->session->userdata('hospitaladmin');
        $token = $hospitaldata['accessToken'] ?? '';
        $searchval = $this->input->post('search_text');
        $url = $api_base_url . "setup-patient-new-patient/v2/getAllpatient?limit=$limit&page=$page&search=$searchval";
        $makeRequest = function ($accessToken) use ($url) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: ' . $accessToken
            ]);
            $response = curl_exec($ch);
            curl_close($ch);
            return $response;
        };
        $response = $makeRequest($token);
        $responseData = json_decode($response, true);
        $data["patientlist"] = [
            "draw" => isset($_GET['draw']) ? intval($_GET['draw']) : 1,
            "recordsTotal" => isset($responseData['total']) ? (int)$responseData['total'] : 0,
            "recordsFiltered" => isset($responseData['total']) ? (int)$responseData['total'] : 0,
            "data" => isset($responseData['data']) ? $responseData['data'] : []
        ];

        $this->session->set_userdata('top_menu', 'setup');
        $this->session->set_userdata('sub_menu', 'setup/patient');
        $data['title'] = 'Search';
        $search_text = $this->input->post('search_text');
        $data['search_text'] = trim($search_text);
        $data["bloodgroup"] = $this->bloodbankstatus_model->get_product(null, 1);
        $data['fields'] = $this->customfield_model->get_custom_fields('patient', 1);
        $userdata = $this->customlib->getUserData();

        $data['searchvalue'] = $this->input->post('search_text');
        $this->load->view('layout/header', $data);
        $this->load->view('admin/search', $data);
        $this->load->view('layout/footer', $data);
    }


    /*
    This Function is used to Get all active patient Recoerds
     */

    public function getpatientdatatable()
    {
        $search_text = $this->input->post('search_text');
        $from_date = $this->input->post('startDate');
        $to_date = $this->input->post('endDate');
        $this->db->stop_cache();
        $dt_response = $this->patient_model->searchDataTablePatientRecord($search_text, $from_date, $to_date);
        $fields = $this->customfield_model->get_custom_fields('patient', 1);
        $dt_response = json_decode($dt_response);
        $dt_data = [];
        $count = 0;
        if (!empty($dt_response->data)) {
            foreach ($dt_response->data as $key => $value) {
                if ($value->is_active == 'yes') {
                    $id = $value->id;
                    $encoded_id = base64_encode($value->id);
                    $info_url = [
                        base_url() . 'admin/patient/profile/' . $encoded_id . "/" . $value->is_active,
                        base_url() . 'admin/patient/ipdprofile/' . $value->id,
                        base_url() . 'admin/radio/getTestReportBatch',
                        base_url() . 'admin/pathology/getTestReportBatch',
                        base_url() . 'admin/pharmacy/bill'
                    ];
                    $info_tables = [
                        "opd_details",
                        "ipd_details",
                        "radiology_report",
                        "pathology_report",
                        "pharmacy_bill_basic"
                    ];
                    $data = [];
                    $url = [];
                    foreach ($info_tables as $index => $table) {
                        if ($this->db->where("patient_id", $id)->get($table)->num_rows() > 0) {
                            $data[$index] = $table;
                            $url[$index] = $info_url[$index];
                        }
                    }
                    $result[$key]['info'] = $data;
                    $result[$key]['url'] = $url;
                }
                $age_display = "0 " . $this->lang->line("years");
                if (!empty($value->age)) {
                    $age_display = (!empty($value->age) ? $value->age : "0") . " " . $this->lang->line("years");
                }
                $action = "<a href='#' onclick='getpatientData(" . $value->id . ")' class='btn btn-default btn-xs pull-right' data-toggle='modal' title='" . $this->lang->line('show') . "'><i class='fa fa-reorder'></i></a>";
                $action .= "<div class='btn-group' style='margin-left:2px;'>";
                if (!empty($result[$key]['info'])) {
                    $action .= "<a href='#' style='width: 20px;border-radius: 2px;' class='btn btn-default btn-xs' data-toggle='dropdown' title='" . $this->lang->line('show') . "'><i class='fa fa-ellipsis-v'></i></a>";
                    $action .= "<ul class='dropdown-menu dropdown-menu2' role='menu'>";
                    foreach ($result[$key]['info'] as $pkey => $pvalue) {
                        $action .= "<li><a href='" . $result[$key]['url'][$pkey] . "' target='_blank' class='btn btn-default btn-xs'>" . $pvalue . "</a></li>";
                    }
                    $action .= "</ul>";
                }
                $action .= "</div>";
                $row = [
                    '<td style="text-align: center;">' . ++$count . '</td>',
                    '<td style="text-align: center;">' . (!empty($value->patient_name) ? composePatientName($value->patient_name, $value->id) : '-') . '</td>',
                    '<td style="text-align: center;">' . $age_display . '</td>',
                    '<td style="text-align: center;">' . (!empty($value->gender) ? trim((string)$value->gender) : '-') . '</td>',
                    '<td style="text-align: center;">' . (!empty($value->mobileno) ? trim((string)$value->mobileno) : '-') . '</td>',
                    '<td style="text-align: center;">' . (!empty($value->guardian_name) ? trim((string)$value->guardian_name) : '-') . '</td>',
                    //  '<td style="text-align: center;">' . (!empty($value->address) ? trim((string)$value->address) : '-') . '</td>',
                    '<td style="text-align: center;">' . (!empty($value->created_at) ? date('d-m-Y', strtotime($value->created_at)) : '-') . '</td>',
                    '<td style="text-align: center;">' . ($value->is_dead == 'yes' ? $this->lang->line('yes') : $this->lang->line('no')) . '</td>',
                    '<td style="text-align: center;">' . $action . '</td>'
                ];
                $dt_data[] = $row;
            }
        }
        echo json_encode([
            "draw" => intval($dt_response->draw),
            "recordsTotal" => intval($dt_response->recordsTotal),
            "recordsFiltered" => intval($dt_response->recordsFiltered),
            "data" => $dt_data,
        ]);
    }


    /**
     *    This Function is used to Get all Deactive patient Recoerds
     */

    public function getdisablepatientdatatable()
    {
        $dt_response = $this->patient_model->getAlldisablepatientRecord();
        //echo $this->db->last_query();die;
        $fields = $this->customfield_model->get_custom_fields('patient', 1);
        $dt_response = json_decode($dt_response);
        $dt_data = array();
        $info = array();
        $data = array();
        $url = array();
        $info_data = array('OPD', 'IPD', 'Radiology', 'Pathology', 'Pharmacy', 'Operation Theatre');
        $info_url = array();
        if (!empty($dt_response->data)) {
            foreach ($dt_response->data as $key => $value) {

                $row = array();

                $age = $this->customlib->getPatientAge($value->age, $value->month, $value->day);
                $action = "<a href='#' onclick='getpatientData(" . $value->id . ")' class='btn btn-default btn-xs pull-right'  data-toggle='modal' title='" . $this->lang->line('show') . "'><i class='fa fa-reorder'></i></a>";

                $action .= "<div class='btn-group' style='margin-left:2px;'>";
                if (!empty($result[$key]['info'])) {
                    $action .= "<a href='#' style='width: 20px;border-radius: 2px;' class='btn btn-default btn-xs'  data-toggle='dropdown' title='" . $this->lang->line('show') . "'><i class='fa fa-ellipsis-v'></i></a>";
                    $action .= "<ul class='dropdown-menu dropdown-menu2' role='menu'>";

                    foreach ($result[$key]['info'] as $pkey => $pvalue) {
                        $action .= "<li>" . "<a href='" . $result[$key]['url'][$pkey] . "' class='btn btn-default btn-xs'  data-toggle='' title=''>" . $pvalue . "</a>" . "</li>";
                    }
                    $action .= "</ul>";
                }
                $action .= "</div>";
                $first_action = "<a href='#' onclick='getpatientData(" . $value->id . ")' class='btn btn-default btn-xs'  data-toggle='modal' title=''>";

                $row[] = $first_action . composePatientName($value->patient_name, $value->id) . "</a>";
                $row[] = $age;
                $row[] = $value->gender;
                $row[] = $value->mobileno;
                $row[] = $value->guardian_name;
                $row[] = $value->address;
                if (!empty($fields)) {
                    foreach ($fields as $fields_key => $fields_value) {
                        $display_field = $value->{"$fields_value->name"};
                        if ($fields_value->type == "link") {
                            $display_field = "<a href=" . $value->{"$fields_value->name"} . " target='_blank'>" . $value->{"$fields_value->name"} . "</a>";
                        }
                        $row[] = $display_field;
                    }
                }
                $row[] = $action;
                $dt_data[] = $row;
            }
        }
        $json_data = array(
            "draw" => intval($dt_response->draw),
            "recordsTotal" => intval($dt_response->recordsTotal),
            "recordsFiltered" => intval($dt_response->recordsFiltered),
            "data" => $dt_data,
        );
        echo json_encode($json_data);
    }

    public function patientDetails()
    {
        if (!$this->rbac->hasPrivilege('patient', 'can_view')) {
            access_denied();
        }
        $id = $this->input->post("id");
        $data = $this->patient_model->patientDetails($id);
        if (($data['dob'] == '') || ($data['dob'] == '0000-00-00') || ($data['dob'] == '1970-01-01')) {
            $data['dob'] = "";
        } else {
            $data['dob'] = $this->customlib->YYYYMMDDTodateFormat($data['dob']);
        }

        echo json_encode($data);
    }

    public function getCollectionbymonth()
    {
        $result = $this->admin_model->getMonthlyCollection();
        return $result;
    }

    public function getCollectionbyday($date)
    {
        $result = $this->admin_model->getCollectionbyDay($date);
        if ($result[0]['amount'] == "") {
            $return = 0;
        } else {
            $return = $result[0]['amount'];
        }
        return $return;
    }

    public function getExpensebyday($date)
    {
        $result = $this->admin_model->getExpensebyDay($date);
        if ($result[0]['amount'] == "") {
            $return = 0;
        } else {
            $return = $result[0]['amount'];
        }
        return $return;
    }

    public function getExpensebymonth()
    {
        $result = $this->admin_model->getMonthlyExpense();
        return $result;
    }

    public function whatever($feecollection_array, $start_month_date, $end_month_date)
    {
        $return_amount = 0;
        $st_date = strtotime($start_month_date);
        $ed_date = strtotime($end_month_date);
        if (!empty($feecollection_array)) {
            while ($st_date <= $ed_date) {
                $date = date('Y-m-d', $st_date);
                foreach ($feecollection_array as $key => $value) {
                    if ($value['date'] == $date) {
                        $return_amount = $return_amount + $value['amount'] + $value['amount_fine'];
                    }
                }
                $st_date = $st_date + 86400;
            }
        } else {
        }
        return $return_amount;
    }

    public function startmonthandend()
    {
        $startmonth = $this->setting_model->getStartMonth();
        if ($startmonth == 1) {
            $endmonth = 12;
        } else {
            $endmonth = $startmonth - 1;
        }
        return array($startmonth, $endmonth);
    }

    public function handle_upload()
    {
        if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
            $allowedExts = array('sql');
            $temp = explode(".", $_FILES["file"]["name"]);
            $extension = end($temp);
            if ($_FILES["file"]["error"] > 0) {
                $error .= "Error opening the file<br />";
            }
            if ($_FILES["file"]["type"] != 'application/octet-stream') {

                $this->form_validation->set_message('handle_upload', $this->lang->line('file_type_not_allowed'));
                return false;
            }
            if (!in_array(strtolower($extension), $allowedExts)) {

                $this->form_validation->set_message('handle_upload', $this->lang->line('extension_not_allowed'));
                return false;
            }
            if ($_FILES["file"]["size"] > 10240000) {

                $this->form_validation->set_message('handle_upload', $this->lang->line('file_size_shoud_be_less_than_100kB'));
                return false;
            }
            return true;
        } else {
            $this->form_validation->set_message('handle_upload', $this->lang->line('the_file_field_is_required'));
            return false;
        }
    }

    public function generate_key($length = 12)
    {
        $str = "";
        $characters = array_merge(range('A', 'Z'), range('a', 'z'), range('0', '9'));
        $max = count($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $rand = mt_rand(0, $max);
            $str .= $characters[$rand];
        }
        return $str;
    }

    public function addCronsecretkey($id)
    {
        $key = $this->generate_key(25);
        $data = array('cron_secret_key' => $key);
        $this->setting_model->add_cronsecretkey($data, $id);
        redirect('admin/admin/backup');
    }

    public function dashboard()
    {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        $this->session->set_userdata('top_menu', 'dashboard');
        $this->session->set_userdata('sub_menu', '');
        $role = $this->customlib->getStaffRole();
        $role_id = json_decode($role)->id;
        $staffid = $this->customlib->getStaffID();
        $notifications = $this->notification_model->getUnreadStaffNotification($staffid, $role_id);
        $data['notifications'] = $notifications;
        $systemnotifications = $this->notification_model->getUnreadNotification();
        $data['systemnotifications'] = $systemnotifications;
        $Current_year = date('Y');
        $Next_year = date("Y");
        $current_date = date('Y-m-d');
        $data['title'] = 'Dashboard';
        $Current_start_date = date('01');
        $Current_date = date('d');
        $Current_month = date('m');
        $month_collection = 0;
        $month_expense = 0;
        $total_opd_patients = 0;
        $total_ipd_patients = 0;
        $ar[0] = 01;
        $ar[1] = 12;
        $year_str_month = $Current_year . '-' . $ar[0] . '-01';
        $year_end_month = date("Y-m-t", strtotime($Next_year . '-' . $ar[1] . '-01'));
        //======================Current Month Collection ==============================
        $first_day_this_month = date('Y-m-01');
        $tot_roles = $this->role_model->get();
        foreach ($tot_roles as $key => $value) {
            if ($value["id"] != 1) {
                $count_roles[$value["name"]] = $this->role_model->count_roles($value["id"]);
            }
        }
        $data["roles"] = $count_roles;
        $expense = $this->expense_model->getTotalExpenseBwdate(date('Y-m-01'), date('Y-m-t'));
        $data["expense"] = $expense;
        $start_month = strtotime($year_str_month);
        $start = strtotime($year_str_month);
        $end = strtotime($year_end_month);
        $coll_month = array();
        $s = array();
        $ex = array();
        $total_month = array();
        $start_session_month = strtotime($year_str_month);
        while ($start_month <= $end) {
            $total_month[] = $this->lang->line(strtolower(date('M', $start_month)));
            $month_start = date('Y-m-d', $start_month);
            $month_end = date("Y-m-t", $start_month);
            $where_date = array('payment_date >=' => $month_start, 'payment_date <=' => $month_end);
            $return = $this->transaction_model->get_monthTransaction($where_date);

            if (!empty($return)) {
                $at = 0;
                $s[] = $at + $return;
            } else {
                $s[] = "0.00";
            }
            $where_condition = array('date >=' => $month_start, 'date <=' => $month_end);
            $expense_monthly = $this->expense_model->getTotalExpenseBwdate($month_start, $month_end);
            if (!empty($expense_monthly)) {
                $amt = 0;
                $ex[] = $amt + $expense_monthly->amount;
            }
            $start_month = strtotime("+1 month", $start_month);
        }
        $data['yearly_collection'] = $s;
        $data['yearly_expense'] = $ex;
        $data['total_month'] = $total_month;
        $event_colors = array("#03a9f4", "#c53da9", "#757575", "#8e24aa", "#d81b60", "#7cb342", "#fb8c00", "#fb3b3b");
        $data["event_colors"] = $event_colors;
        $userdata = $this->customlib->getUserData();
        $data["role"] = $userdata["user_type"];
        $search_opd_income = array('payment_date >=' => date('Y-m-01'), 'payment_date <=' => date("Y-m-t"), 'opd_id !=' => null);
        $data['opd_income'] = $this->transaction_model->get_monthTransaction($search_opd_income);
        $search_ipd_income = array('payment_date >=' => date('Y-m-01'), 'payment_date <=' => date("Y-m-t"), 'ipd_id !=' => null);
        $data['ipd_income'] = $this->transaction_model->get_monthTransaction($search_ipd_income);
        $search_pharmacy_income = array('payment_date >=' => date('Y-m-01'), 'payment_date <=' => date("Y-m-t"), 'pharmacy_bill_basic_id !=' => null);
        $data['pharmacy_income'] = $this->transaction_model->get_monthTransaction($search_pharmacy_income);
        $search_pathology_income = array('payment_date >=' => date('Y-m-01'), 'payment_date <=' => date("Y-m-t"), 'pathology_billing_id !=' => null);
        $data['pathology_income'] = $this->transaction_model->get_monthTransaction($search_pathology_income);
        $search_radiology_income = array('payment_date >=' => date('Y-m-01'), 'payment_date <=' => date("Y-m-t"), 'radiology_billing_id !=' => null);
        $data['radiology_income'] = $this->transaction_model->get_monthTransaction($search_radiology_income);
        $search_blood_bank_income = array('payment_date >=' => date('Y-m-01'), 'payment_date <=' => date("Y-m-t"), 'blood_issue_id !=' => null);
        $data['blood_bank_income'] = $this->transaction_model->get_monthTransaction($search_blood_bank_income);
        $search_ambulance_income = array('payment_date >=' => date('Y-m-01'), 'payment_date <=' => date("Y-m-t"), 'ambulance_call_id !=' => null);
        $data['ambulance_income'] = $this->transaction_model->get_monthTransaction($search_ambulance_income);
        $month_expences = $this->expense_model->getTotalExpenseBwdate(date('Y-m-01'), date('Y-m-t'));
        $data['expences'] = $month_expences->amount;
        $where_date = array('date >=' => date('Y-m-01'), 'date <=' => date('Y-m-t'));

        $search_appointment_income = array('payment_date >=' => date('Y-m-01'), 'payment_date <=' => date("Y-m-t"), 'appointment_id !=' => null);
        $data['appointment_income '] = $this->transaction_model->get_monthTransaction($search_appointment_income);

        $data['general_income'] = $this->income_model->getTotal($where_date);
        $parameter = array(
            'opd' => $data['opd_income'],
            'ipd' => $data['ipd_income'],
            'pharmacy' => $data['pharmacy_income'],
            'pathology' => $data['pathology_income'],
            'radiology' => $data['radiology_income'],
            'blood_bank' => $data['blood_bank_income'],
            'ambulance' => $data['ambulance_income'],
            'general' => $data['general_income'],
            'appointment' => $data['appointment_income '],
        );

        $label = array($this->lang->line('opd'), $this->lang->line('ipd'), $this->lang->line('pharmacy'), $this->lang->line('pathology'), $this->lang->line('radiology'), $this->lang->line('blood_bank'), $this->lang->line('ambulance'), $this->lang->line('income'), 'Appointment');
        $module = array('opd', 'ipd', 'pharmacy', 'pathology', 'radiology', 'blood_bank', 'ambulance', 'income', 'appointment');

        $tot_data = array_sum($parameter);
        $jsonarr = array();
        $i = 0;

        foreach ($parameter as $key => $value) {
            $data[$key . "_income"] = number_format($value !== null ? $value : 0, 2);

            if ($this->module_lib->hasActive($module[$i])) {
                $jsonarr['value'][] = $tot_data != 0 ? round(($value / $tot_data) * 100, 0) : 0;
                $jsonarr['label'][] = $label[$i];
            }

            $data[$key . "_cdata"] = $tot_data != 0 ? ($value / $tot_data) * 100 : 0;
            $i++;
        }


        $data['mysqlVersion'] = $this->setting_model->getMysqlVersion();
        $data['sqlMode'] = $this->setting_model->getSqlMode();
        $data['jsonarr'] = $jsonarr;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/dashboard', $data);
        $this->load->view('layout/footer', $data);
    }

    public function updateandappCode()
    {
        $this->form_validation->set_rules('app-email', $this->lang->line('email'), 'required|valid_email|trim|xss_clean');
        $this->form_validation->set_rules('app-envato_market_purchase_code', $this->lang->line('purchase_code'), 'required|trim|xss_clean');

        if ($this->form_validation->run() == false) {
            $data = array(
                'app-email' => form_error('app-email'),
                'app-envato_market_purchase_code' => form_error('app-envato_market_purchase_code'),
            );
            $array = array('status' => '2', 'error' => $data);

            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($array));
        } else {
            //==================
            $response = $this->auth->andapp_update();
        }
    }
    public function refundpolicy()
    {
        $this->load->view('layout/refundpolicy');
    }
    public function privacy_policy()
    {

        $this->load->view('layout/privacy_policy');
    }
    public function payroll_setup($id = null)
    {
        $data["payroll_tyle"] = $this->payroll_model->getpayroll_type($id);
        $data['initialData'] = $this->customlib->getpagenation('setup-human-resource-payslip-settings/v2/SetupHRPayrollSettings');
        $this->load->view("layout/header");
        $this->load->view('admin/staff/payroll_setup', $data);
        $this->load->view("layout/footer");
    }

    public function getpayrolldatatable_earn_deduction($id = null)
    {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        $dt_response = $this->payroll_model->getpayroll($id);
        $dt_data = array();

        if (!empty($dt_response)) {
            foreach ($dt_response as $row) {
                $actionHtml = '';

                if ($this->rbac->hasPrivilege('payroll', 'can_edit')) {
                    $actionHtml = "<a href='#' data-toggle='tooltip' onclick='get(" . $row->id . ")' class='btn btn-default btn-xs' data-toggle='#editmyModal' title='" . $this->lang->line('edit') . "'><i class='fa fa-pencil'></i></a>";
                }

                if ($this->rbac->hasPrivilege('payroll', 'can_delete')) {
                    $actionHtml .= "<a href='#' onclick='deleterecord(" . $row->id . ")' class='btn btn-default btn-xs' data-toggle='tooltip' title='" . $this->lang->line('delete') . "'><i class='fa fa-trash'></i></a>";
                }

                $payrollType = $row->category_name;
                $rowData = array(
                    $payrollType,
                    $row->payslip_setting_name,
                    $row->default_percentage,
                    $actionHtml
                );

                $dt_data[] = $rowData;
            }
        }

        $json_data = array(
            "draw"            => intval($this->input->get('draw')),
            "recordsTotal"    => count($dt_response),
            "recordsFiltered" => count($dt_response),
            "data"            => $dt_data,
        );

        echo json_encode($json_data);
    }

    public function getpayrolldatatable($id = null)
    {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        $dt_response = $this->payroll_model->getpayroll_type($id);
        $dt_data = array();

        if (!empty($dt_response)) {
            foreach ($dt_response as $row) {
                $actionHtml = '';

                // if ($this->rbac->hasPrivilege('payroll', 'can_edit')) {
                //     $actionHtml = "<a href='#' data-toggle='tooltip' onclick='get(" . $row->id . ")' class='btn btn-default btn-xs' data-toggle='#editmyModal' title='" . $this->lang->line('edit') . "'><i class='fa fa-pencil'></i></a>";
                // }

                // if ($this->rbac->hasPrivilege('payroll', 'can_delete')) {
                //     $actionHtml .= "<a href='#' onclick='deleterecord(" . $row->id . ")' class='btn btn-default btn-xs' data-toggle='tooltip' title='" . $this->lang->line('delete') . "'><i class='fa fa-trash'></i></a>";
                // }

                $payrollType = $row->category_name;
                $rowData = array(
                    $payrollType,
                    $actionHtml
                );

                $dt_data[] = $rowData;
            }
        }

        $json_data = array(
            "draw"            => intval($this->input->get('draw')),
            "recordsTotal"    => count($dt_response),
            "recordsFiltered" => count($dt_response),
            "data"            => $dt_data,
        );

        echo json_encode($json_data);
    }

    public function getpayrolldatatable_id($id)
    {
        $data = $this->payroll_model->getpayrolldatatable_id_edit($id);
        echo json_encode($data);
    }


    public function getpayrolldatatable_type($id = null)
    {
        $data = $this->payroll_model->getpayroll_detials_edit($id);
        echo json_encode($data);
    }


    public function payroll_setup_type()
    {
        $this->load->view("layout/header");
        $this->load->view('admin/staff/payroll_setup_type');
        $this->load->view("layout/footer");
    }

    public function patientlist()
    {
        $draw = isset($_GET['draw']) ? intval($_GET['draw']) : 1;
        $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $star_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
        $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';
        $api_base_url = $this->config->item('api_base_url');
        $hospitaldata = $this->session->userdata('hospitaladmin');
        $token = $hospitaldata['accessToken'] ?? '';
        $url = $api_base_url . "setup-patient-new-patient/v2/getAllpatient?limit=$limit&page=$page&search=$search&fromDate=$star_date&toDate=$end_date";
        $makeRequest = function ($accessToken) use ($url) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: ' . $accessToken
            ]);
            $response = curl_exec($ch);
            curl_close($ch);
            return $response;
        };
        $response = $makeRequest($token);
        $responseData = json_decode($response, true);
        $output = [
            "draw" => isset($_GET['draw']) ? intval($_GET['draw']) : 1,
            "recordsTotal" => isset($responseData['total']) ? (int)$responseData['total'] : 0,
            "recordsFiltered" => isset($responseData['total']) ? (int)$responseData['total'] : 0,
            "data" => isset($responseData['data']) ? $responseData['data'] : []
        ];
        header('Content-Type: application/json');
        echo json_encode($output);
    }

    public function getpayrolllist()
    {
        $data['getpayrolllist'] = $this->customlib->getpagenation('setup-human-resource-payslip-settings/v2/SetupHRPayrollSettings');
        echo json_encode($data['getpayrolllist']);
    }

    public function terms()
    {
        $this->load->view('layout/terms');
    }
}
