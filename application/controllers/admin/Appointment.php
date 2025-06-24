<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Appointment extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->config->load("payroll");
        $this->config->load("mailsms");
        $this->notification = $this->config->item('notification');
        $this->notificationurl = $this->config->item('notification_url');
        $this->yesno_condition = $this->config->item('yesno_condition');
        $this->patient_notificationurl = $this->config->item('patient_notification_url');
        $this->search_type = $this->config->item('search_type');
        $this->load->library('mailsmsconf');
        $this->load->library('Enc_lib');
        $this->load->library('datatables');
        $this->load->library('system_notification');
        $this->load->model(array('appoint_priority_model', 'onlineappointment_model', 'transaction_model', 'conference_model'));
        $this->appointment_status = $this->config->item('appointment_status');
        $this->load->helper('customfield_helper');
        $this->time_format = $this->customlib->getHospitalTimeFormat();
        $this->config->load('image_valid');
        $this->payment_mode = $this->config->item('payment_mode');
        $this->load->model('Admin_model');
        if (!$this->Admin_model->validationModule('appointment')) {
            access_denied();
        }
    }

    public function unauthorized()
    {
        $data = array();
        $this->load->view('layout/header', $data);
        $this->load->view('unauthorized', $data);
        $this->load->view('layout/footer', $data);
    }

    public function index()
    {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        $this->session->set_userdata('top_menu', 'appointment');
        $app_data = $this->session->flashdata('app_data');
        $data['app_data'] = $app_data;
        $data["doctors"] = $this->staff_model->getStaffbyrole(3);
        $data["patients"] = $this->patient_model->getPatientListall();
        $data["appointment_status"] = $this->appointment_status;
        $data["yesno_condition"] = $this->yesno_condition;
        $userdata = $this->customlib->getUserData();
        $role_id = $userdata['role_id'];
        $data["bloodgroup"] = $this->bloodbankstatus_model->get_product(null, 1);
        $data['appoint_priority_list'] = $this->appoint_priority_model->appoint_priority_list();
        $doctorid = "";
        $disable_option = false;
        $api_base_url = $this->config->item('api_base_url');
        $hospitaldata = $this->session->userdata('hospitaladmin');
        $token = $hospitaldata['accessToken'] ?? '';
        $url = $api_base_url . "add-appointment/v2/getAllpage?limit=10&page=1&filter=period:today&hospital_id=" . $hospitaldata['hospital_id'];
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
        if (isset($responseData['status_code']) && $responseData['status_code'] == 403) {
            $newToken = $this->customlib->refreshToken();
            $hospitaldata['accessToken'] = $newToken;
            $this->session->set_userdata('hospitaladmin', $hospitaldata);
            $response = $makeRequest($newToken);
            $responseData = json_decode($response, true);
        }
        $output = [
            "draw" => 1,
            "recordsTotal" => isset($responseData['total']) ? (int)$responseData['total'] : 0,
            "recordsFiltered" => isset($responseData['total']) ? (int)$responseData['total'] : 0,
            "data" => isset($responseData['data']) ? $responseData['data'] : []
        ];
        $data["appoitmentlist"] = $output;
        if ($this->session->userdata['hospitaladmin']['doctor_restriction'] == 'enabled' && $role_id == 3) {
            $disable_option = true;
            $doctorid = $userdata['id'];
        }
        $data["doctor_select"] = $doctorid;
        $data["disable_option"] = $disable_option;
        $data['fields'] = $this->customfield_model->get_custom_fields('appointment', 1);
        $data['payment_mode'] = $this->payment_mode;

        $this->load->view('layout/header');
        $this->load->view('admin/appointment/index', $data);
        $this->load->view('layout/footer');
    }


    public function add()
    {
        $custom_fields = $this->customfield_model->getByBelong('appointment');
        foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
            if ($custom_fields_value['validation']) {
                $custom_fields_id = $custom_fields_value['id'];
                $custom_fields_name = $custom_fields_value['name'];
                $this->form_validation->set_rules("custom_fields[appointment][" . $custom_fields_id . "]", $custom_fields_name, 'trim|required');
            }
        }

        $this->form_validation->set_rules('date', $this->lang->line('appointment_date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('doctorid', $this->lang->line('doctor'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('amount', $this->lang->line('doctor_fees'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('patient_id', $this->lang->line('patient'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('global_shift', $this->lang->line('shift'), 'trim|required');
        $this->form_validation->set_rules('slot', $this->lang->line('slot'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('appointment_status', $this->lang->line('status'), 'trim|required|xss_clean');

        if ($this->input->post("payment_mode") == "Cheque") {
            $this->form_validation->set_rules('cheque_no', $this->lang->line('cheque_no'), 'trim|required');
            $this->form_validation->set_rules('cheque_date', $this->lang->line('cheque_date'), 'trim|required');
            $this->form_validation->set_rules('document', $this->lang->line("document"), 'callback_handle_doc_upload[document]');
        }

        if ($this->form_validation->run() == false) {
            $msg = array(
                'patient_id' => form_error('patient_id'),
                'doctor' => form_error('doctorid'),
                'amount' => form_error('amount'),
                'global_shift' => form_error('global_shift'),
                'date' => form_error('date'),
                'slot' => form_error('slot'),
                'appointment_status' => form_error('appointment_status'),
                'cheque_no' => form_error('cheque_no'),
                'cheque_date' => form_error('cheque_date'),
                'document' => form_error('document'),

            );
            if (!empty($custom_fields)) {
                foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
                    if ($custom_fields_value['validation']) {
                        $custom_fields_id = $custom_fields_value['id'];
                        $custom_fields_name = $custom_fields_value['name'];
                        $error_msg2["custom_fields[appointment][" . $custom_fields_id . "]"] = form_error("custom_fields[appointment][" . $custom_fields_id . "]");
                    }
                }
            }

            if (!empty($error_msg2)) {
                $error_msg = array_merge($msg, $error_msg2);
            } else {
                $error_msg = $msg;
            }
            $array = array('status' => 'fail', 'error' => $error_msg, 'message' => '');
        } else {
            $staff_id = $this->customlib->getLoggedInUserID();
            $date = $this->input->post('date');
            $date_appoint = $this->customlib->dateFormatToYYYYMMDDHis($date, $this->time_format);
            $patient_id = $this->input->post('patient_id');
            $consult = $this->input->post('live_consult');
            $cheque_date = $this->customlib->dateFormatToYYYYMMDD($this->input->post("cheque_date"));

            $appointment = array(
                'patient_id' => $patient_id,
                'date' => $date_appoint,
                'priority' => $this->input->post('priority'),
                'doctor' => $this->input->post('doctorid'),
                'message' => $this->input->post('message'),
                'global_shift_id' => $this->input->post('global_shift'),
                'shift_id' => $this->input->post('slot'),
                'is_queue' => 0,
                'live_consult' => $consult,
                'source' => 'Offline',
                'appointment_status' => $this->input->post('appointment_status'),
            );
            $insert_id = $this->appointment_model->add($appointment);

            $payment_data = array(
                'appointment_id' => $insert_id,
                'paid_amount' => $this->input->post('amount'),
                'charge_id' => $this->input->post('charge_id'),
                'payment_type' => 'Offline',
                'date' => date("Y-m-d H:i:s"),
            );
            $payment_section = $this->config->item('payment_section');
            $transaction_array = array(
                'amount' => $this->input->post("amount"),
                'patient_id' => $patient_id,
                'section' => $payment_section['appointment'],
                'type' => 'payment',
                'appointment_id' => $insert_id,
                'payment_mode' => $this->input->post("payment_mode"),
                'payment_date' => date('Y-m-d H:i:s'),
                'received_by' => $staff_id,
            );

            $attachment = "";
            $attachment_name = "";
            if (isset($_FILES["document"]) && !empty($_FILES['document']['name'])) {
                $fileInfo = pathinfo($_FILES["document"]["name"]);
                $attachment = uniqueFileName() . '.' . $fileInfo['extension'];
                $attachment_name = $_FILES["document"]["name"];
                move_uploaded_file($_FILES["document"]["tmp_name"], "./uploads/payment_document/" . $attachment);
            }

            if ($this->input->post('payment_mode') == "Cheque") {
                $transaction_array['cheque_date'] = $cheque_date;
                $transaction_array['cheque_no'] = $this->input->post('cheque_no');
                $transaction_array['attachment'] = $attachment;
                $transaction_array['attachment_name'] = $attachment_name;
            }

            $this->appointment_model->saveAppointmentPayment($payment_data, $transaction_array);
            $appointment_id = $insert_id;
            $appointment_details = $this->appointment_model->getDetails($appointment_id);
            $transaction_data = $this->transaction_model->getTransactionByAppointmentId($appointment_id);
            $appointment_payment = $this->appointment_model->getPaymentByAppointmentId($appointment_id);

            if ($this->input->post('appointment_status') == 'approved') {
                /* OPD Insert Code*/

                $charges = $this->charge_model->getChargeByChargeId($appointment_payment->charge_id);
                $apply_charge = $charges['standard_charge'] + ($charges['standard_charge'] * ($charges['percentage'] / 100));
                $opd_details = array(
                    'patient_id' => $appointment_details['patient_id'],
                    'generated_by' => $this->customlib->getStaffID(),
                );

                $visit_details = array(
                    'appointment_date' => date("Y-m-d H:i:s"),
                    'opd_details_id' => 0,
                    'cons_doctor' => $appointment_details['doctor'],
                    'generated_by' => $this->customlib->getLoggedInUserID(),
                    'patient_charge_id' => null,
                    'transaction_id' => $transaction_data->id,
                    'can_delete' => 'no',
                    'live_consult' => $consult,
                );
                $staff_data = $this->staff_model->getStaffByID($appointment_details['doctor']);
                $staff_name = composeStaffName($staff_data);
                $charge = array(
                    'opd_id' => 0,
                    'date' => date('Y-m-d H:i:s'),
                    'charge_id' => $appointment_payment->charge_id,
                    'qty' => 1,
                    'apply_charge' => $charges['standard_charge'],
                    'standard_charge' => $charges['standard_charge'],
                    'amount' => $appointment_payment->paid_amount,
                    'created_at' => date('Y-m-d H:i:s'),
                    'note' => null,
                    'tax' => $charges['percentage'],
                );
                $opd_visit_id = $this->appointment_model->moveToOpd($opd_details, $visit_details, $charge, $appointment_id, '');
                /* OPD Insert Code*/

                $visit_detail = $this->patient_model->getVisitDetailByid($opd_visit_id);
                $setting_result = $this->setting_model->getzoomsetting();
                $opdduration = $setting_result->opd_duration;
                if ($consult == 'yes') {
                    $api_type = 'global';
                    $params = array(
                        'zoom_api_key' => "",
                        'zoom_api_secret' => "",
                    );

                    $title = 'Online consult for ' . $this->customlib->getSessionPrefixByType('opd_no') . $visit_detail->opd_details_id . " Checkup ID " . $visit_detail->id;
                    $this->load->library('zoom_api', $params);
                    $insert_array = array(
                        'staff_id' => $this->input->post('doctorid'),
                        'visit_details_id' => $visit_detail->id,
                        'title' => $title,
                        'date' => $date_appoint,
                        'duration' => 60,
                        'created_id' => $this->customlib->getStaffID(),
                        'password' => random_string(),
                        'api_type' => $api_type,
                        'host_video' => 1,
                        'client_video' => 1,
                        'purpose' => 'consult',
                        'timezone' => $this->customlib->getTimeZone(),
                    );

                    $response = $this->zoom_api->createAMeeting($insert_array);

                    if (!empty($response)) {
                        if (isset($response->id)) {
                            $insert_array['return_response'] = json_encode($response);
                            $this->conference_model->add($insert_array);
                        }
                    }
                }
            }
            $custom_field_post = $this->input->post("custom_fields[appointment]");
            $custom_value_array = array();
            if (!empty($custom_field_post)) {
                foreach ($custom_field_post as $key => $value) {
                    $check_field_type = $this->input->post("custom_fields[appointment][" . $key . "]");
                    $field_value = is_array($check_field_type) ? implode(",", $check_field_type) : $check_field_type;
                    $array_custom = array(
                        'belong_table_id' => 0,
                        'custom_field_id' => $key,
                        'field_value' => $field_value,
                    );
                    $custom_value_array[] = $array_custom;
                }
            }

            if (!empty($custom_value_array)) {
                $this->customfield_model->insertRecord($custom_value_array, $insert_id);
            }

            $doctor_details = $this->notificationsetting_model->getstaffDetails($this->input->post('doctorid'));
            $event_data = array(
                'appointment_date' => $this->customlib->YYYYMMDDHisTodateFormat($date_appoint, $this->customlib->getHospitalTimeFormat()),
                'patient_id' => $patient_id,
                'doctor_id' => $this->input->post('doctorid'),
                'doctor_name' => composeStaffNameByString($doctor_details['name'], $doctor_details['surname'], $doctor_details['employee_id']),
                'message' => $this->input->post('message'),
                'appointment_status' => $this->input->post('appointment_status'),
            );

            $sender_details = array('patient_id' => $appointment_details["patient_id"], 'appointment_id' => $appointment_id);

            if ($this->input->post('appointment_status') == 'approved') {
                $this->mailsmsconf->mailsms('appointment_approved', $sender_details);
                $this->system_notification->send_system_notification('notification_appointment_created', $event_data);
                $this->system_notification->send_system_notification('appointment_approved', $event_data);
            }

            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'), 'patient_id' => $appointment_details['patient_id'], 'appointment_id' => $appointment_id);
        }
        echo json_encode($array);
    }

    public function printAppointmentBill()
    {
        $print_details = $this->printing_model->get('', 'opd');
        $data["print_details"] = $print_details;
        $id = $this->input->post("appointment_id");
        $result = $this->appointment_model->getDetailsAppointment($id);
        if ($result['appointment_status'] == 'Requested') {
            $result['appointment_no'] = 'TEMP' . $id;
        } else {
            $result['appointment_no'] = $this->customlib->getSessionPrefixByType('appointment') . $id;
        }

        $result["patients_name"] = composePatientName($result['patients_name'], $result['patient_id']);
        $result["edit_live_consult"] = $this->lang->line($result['live_consult']);
        $result["live_consult"] = $result['live_consult'];
        $result["date"] = $this->customlib->YYYYMMDDHisTodateFormat($result['date'], $this->time_format);
        $result['custom_fields_value'] = display_custom_fields('appointment', $id);
        $cutom_fields_data = get_custom_table_values($id, 'appointment');
        $result['field_data'] = $cutom_fields_data;
        $result['patients_gender'] = $result['patients_gender'];
        $result['transaction_id'] = $this->customlib->getSessionPrefixByType('transaction_id') . $result['transaction_id'];
        $data['appointment_id'] = $id;
        $data['fields'] = $this->customfield_model->get_custom_fields('appointment');
        $data['result'] = $result;
        $data['appointment_status'] = $result['appointment_status'];
        $data['payment_mode'] = $result['payment_mode'];
        $page = $this->load->view('patient/printAppointment', $data, true);
        echo json_encode(array('status' => 1, 'page' => $page));
    }
    /*
    This Function is Used to Update Records

     */
    public function update()
    {
        $custom_fields = $this->customfield_model->getByBelong('appointment');
        if (!empty($custom_fields)) {
            foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
                if ($custom_fields_value['validation']) {
                    $custom_fields_id = $custom_fields_value['id'];
                    $custom_fields_name = $custom_fields_value['name'];

                    $this->form_validation->set_rules("custom_fields[appointment][" . $custom_fields_id . "]", $custom_fields_name, 'trim|required');
                }
            }
        }
        $this->form_validation->set_rules('date', $this->lang->line('appointment_date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('doctor', $this->lang->line('doctor'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('amount', $this->lang->line('doctor_fees'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('message', $this->lang->line('message'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('patient_id', $this->lang->line('patient'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('global_shift', $this->lang->line('shift'), 'trim|required');
        $this->form_validation->set_rules('slot', $this->lang->line('slot'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $msg = array(
                'patient_id' => form_error('patient_id'),
                'doctor' => form_error('doctorid'),
                'amount' => form_error('amount'),
                'global_shift' => form_error('global_shift'),
                'date' => form_error('date'),
                'slot' => form_error('slot'),
                'message' => form_error('message'),
                'appointment_status' => form_error('appointment_status'),

            );
            if (!empty($custom_fields)) {
                foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
                    if ($custom_fields_value['validation']) {
                        $custom_fields_id = $custom_fields_value['id'];
                        $custom_fields_name = $custom_fields_value['name'];
                        $error_msg2["custom_fields[appointment][" . $custom_fields_id . "]"] = form_error("custom_fields[appointment][" . $custom_fields_id . "]");
                    }
                }
            }
            if (!empty($error_msg2)) {
                $error_msg = array_merge($msg, $error_msg2);
            } else {
                $error_msg = $msg;
            }
            $array = array('status' => 'fail', 'error' => $error_msg, 'message' => '');
        } else {
            $id = $this->input->post('id');
            $appointment_details = $this->appointment_model->getDetails($id);
            $date = $this->input->post('date');
            $custom_field_post = $this->input->post("custom_fields[appointment]");
            $consult = $this->input->post('live_consult');
            $appointment_payment = $this->appointment_model->getPaymentByAppointmentId($id);
            $charges = $this->charge_model->getChargeByChargeId($appointment_payment->charge_id);
            $apply_charge = $charges['standard_charge'] + ($charges['standard_charge'] * ($charges['percentage'] / 100));

            $appointment = array(
                'id' => $id,
                'patient_id' => $this->input->post('patient_id'),
                'date' => $this->customlib->dateFormatToYYYYMMDDHis($date, $this->time_format),
                'priority' => $this->input->post('priority'),
                'doctor' => $this->input->post('doctor'),
                'message' => $this->input->post('message'),
                'global_shift_id' => $this->input->post('global_shift'),
                'shift_id' => $this->input->post('slot'),
                'is_queue' => 0,
                'live_consult' => $consult,
            );
            $payment_data = array(
                'appointment_id' => $id,
                'paid_amount' => $this->input->post('amount'),
                'charge_id' => $this->input->post('charge_id'),
                'payment_type' => 'Offline',
                'date' => date("Y-m-d H:i:s"),
            );
            $payment_section = $this->config->item('payment_section');
            $transaction_array = array(
                'amount' => $this->input->post("amount"),
                'patient_id' => $this->input->post('patient_id'),
                'section' => $payment_section['appointment'],
                'type' => 'payment',
                'appointment_id' => $id,
                'payment_mode' => "Offline",
                'payment_date' => date('Y-m-d H:i:s'),
                'received_by' => $this->customlib->getLoggedInUserID(),
            );
            $visit_data = $this->patient_model->getVisitdataDetails($appointment_details['visit_details_id']);
            $opd_details = array(
                'id' => $visit_data['opdid'],
                'patient_id' => $appointment_details['patient_id'],
                'generated_by' => $this->customlib->getStaffID(),
            );
            $visit_details = array(
                'id' => $appointment_details['visit_details_id'],
                'appointment_date' => date("Y-m-d H:i:s"),
                'opd_details_id' => $visit_data['opdid'],
                'cons_doctor' => $appointment_details['doctor'],
                'generated_by' => $this->customlib->getLoggedInUserID(),
                'can_delete' => 'no',
            );
            $staff_data = $this->staff_model->getStaffByID($appointment_details['doctor']);
            $staff_name = composeStaffName($staff_data);
            $charge = array(
                'date' => date('Y-m-d'),
                'charge_id' => $appointment_payment->charge_id,
                'qty' => 1,
                'apply_charge' => $apply_charge,
                'standard_charge' => $charges['standard_charge'],
                'amount' => $appointment_payment->paid_amount,
                'created_at' => date('Y-m-d H:i:s'),
                'note' => $staff_name,
                'tax' => $charges['percentage'],
            );

            $this->appointment_model->updateAppointment($appointment, $payment_data, $transaction_array, $opd_details, $visit_details, $charge);
            if (!empty($custom_fields)) {
                foreach ($custom_field_post as $key => $value) {
                    $check_field_type = $this->input->post("custom_fields[appointment][" . $key . "]");
                    $field_value = is_array($check_field_type) ? implode(",", $check_field_type) : $check_field_type;
                    $array_custom = array(
                        'belong_table_id' => $id,
                        'custom_field_id' => $key,
                        'field_value' => $field_value,
                    );
                    $custom_value_array[] = $array_custom;
                }
                $this->customfield_model->updateRecord($custom_value_array, $id, 'appointment');
            }
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    public function search()
    {
        $this->session->set_userdata('top_menu', 'front_office');
        $app_data = $this->session->flashdata('app_data');
        $data['app_data'] = $app_data;
        $doctors = $this->staff_model->getStaffbyrole(3);
        $data["doctors"] = $doctors;
        $patients = $this->patient_model->getPatientListall();
        $data["patients"] = $patients;
        $data["appointment_status"] = $this->appointment_status;
        $userdata = $this->customlib->getUserData();
        $role_id = $userdata['role_id'];
        $data["yesno_condition"] = $this->yesno_condition;
        $doctorid = "";
        $data['appoint_priority_list'] = $this->appoint_priority_model->appoint_priority_list();
        $doctor_restriction = $this->session->userdata['hospitaladmin']['doctor_restriction'];
        $disable_option = false;
        if ($doctor_restriction == 'enabled') {
            if ($role_id == 3) {
                $disable_option = true;
                $doctorid = $userdata['id'];
            }
        }
        $data["doctor_select"] = $doctorid;
        $data["disable_option"] = $disable_option;
        $data['fields'] = $this->customfield_model->get_custom_fields('appointment', 1);
        $this->load->view('layout/header');
        $this->load->view('admin/appointment/search.php', $data);
        $this->load->view('layout/footer');
    }

    /*
    This Function is Used to get appointment records for datatable
     */
    public function getappointmentdatatable($type)
    {
        $due = $this->input->get('due_status');
        $dt_response = json_decode($this->appointment_model->getAllappointmentRecord($type, $due));
        $fields = $this->customfield_model->get_custom_fields('appointment', 1);
        $dt_data = [];
        if (!empty($dt_response->data)) {
            foreach ($dt_response->data as $value) {
                $label = $value->color_code ?? '#ccc';
                $status = $value->appointment_status ?? '-';
                $row = [];

                $sub_total = (($value->Total_appointment_amount ?? 0) + ($value->additional_charge ?? 0)) - ($value->discount_amount ?? 0);
                $tax_amount = ($sub_total * ($value->tax ?? 0)) / 100;
                $total_amount = !empty($value->name) ? $sub_total + $tax_amount : ($value->temp_amount ?? 0);
                $total_balance = ($total_amount - ($value->Paidamount ?? 0)) - ($value->temp_appt_amount ?? 0);
                $amountbalance = !empty($value->name) ? $total_balance : ($total_amount - ($value->temp_appt_amount ?? 0));

                $action = "<div class='rowoptionview rowview-btn-top'>";
                if (!in_array($status, ['Requested', 'Reserved', 'Cancelled'])) {
                    $action .= "<a href='#' class='btn btn-default btn-xs' data-toggle='tooltip' onclick='printAppointment({$value->id})' style='background-color: {$label}; color: #000000;'><i class='fa fa-print'></i></a>";
                }
                if ($this->rbac->hasPrivilege('reschedule', 'can_view') && !in_array($status, ['Completed', 'InProcess'])) {
                    $action .= "<a href='#' class='btn btn-default btn-xs' onclick='viewreschedule({$value->id})' style='background-color: {$label}; color: #000000;'><i class='fa fa-calendar'></i></a>";
                }

                if ($status == 'Reserved') {
                    $action .= "<a href='#' class='btn btn-default btn-xs' onclick='additionalFunction({$value->id})' style='background-color: {$label}; color: #000000;'><i class='fa fa-check'></i></a>";
                }

                if ($total_amount > ($value->Paidamount ?? 0) && in_array($status, ['Approved', 'InProcess', 'Completed'])) {
                    $action .= "<a href='#' class='btn btn-default btn-xs' onclick='paymentaction({$value->id}, {$amountbalance}, {$value->pid})' style='background-color: {$label}; color: #000000;'><i class='fa fa-credit-card'></i></a>";
                }

                if ($status == 'pending' && ($value->source ?? '-') != 'Online' && $this->rbac->hasPrivilege('appointment_approve', 'can_view')) {
                    $action .= "<a href='#' class='btn btn-default btn-xs' onclick='viewreschedule({$value->id})' style='background-color: {$label}; color: #fff;'><i class='fa fa-check'></i></a>";
                }

                $action .= "</div>";

                $first_action = "<a href='javascript:void(0)' onclick='viewDetail({$value->id})'>";
                $patient_profile = "<a href='#' onclick='getpatientData({$value->patient_id})'>";

                $row[] = "<div class='text-center'>{$patient_profile}" . composePatientName($value->patient_name, $value->pid) . "</a>" . $action . "</div>";
                $row[] = ($value->name)
                    ? $first_action . $this->customlib->getSessionPrefixByType('appointment') . $value->id
                    : $first_action . "TEMP" . $value->id;
                $row[] = "<div class='text-left'>" . ($value->mobileno ?? '-') . "</div>";
                $row[] = "<div class='text-center'>" . (!empty($value->name) ? composeStaffNameByString($value->name ?? '-', $value->surname ?? '-', $value->employee_id ?? '-') : '-') . "</div>";
                $row[] = "<div class='text-center'>" . ($value->appointment_serial_no ?? '-') . "</div>";
                $row[] = "<div class='text-center'>" . (!empty($value->date) ? date('d/m/Y', strtotime($value->date)) : '-') . "</div>";
                $row[] = "<div class='text-center'>" . ((!empty($value->start_time) && !empty($value->end_time))
                    ? date("h:i A", strtotime($value->start_time)) . '-' . date("h:i A", strtotime($value->end_time))
                    : "09:00 AM - 09:00 PM") . "</div>";
                $row[] = "<div class='text-center'>" . amountFormat(($value->Paidamount ?? 0) + ($value->temp_appt_amount ?? 0)) . "</div>";
                $row[] = "<small style='display: inline-block; min-width: 80px; padding: 4px 8px; border-radius: 4px; background-color: {$label}; color: #fff; font-size: 0.8em; line-height: 1.2; text-align: center; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;'>" . htmlspecialchars($status) . "</small>";

                $dt_data[] = $row;
            }
        }
        echo json_encode([
            "draw" => intval($dt_response->draw ?? 0),
            "recordsTotal" => intval($dt_response->recordsTotal ?? 0),
            "recordsFiltered" => intval($dt_response->recordsFiltered ?? 0),
            "data" => $dt_data,
        ]);
    }



    public function getDetails()
    {
        $id = $this->input->post("appointment_id");
        $result = $this->appointment_model->getDetails($id);
        $result["date"] = $this->customlib->YYYYMMDDHisTodateFormat($result['date'], $this->time_format);
        echo json_encode($result);
    }

    public function appoint_status()
    {
        $status_data = $this->appointment_model->Appointmentstatus();
        $this->load->view('admin/appointment/index', ['status_data' => $status_data]);
    }


    public function getDetailsAppointment()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        $id = $this->input->get("appointment_id");
        $result = $this->appointment_model->getDetailsAppointment($id);


        if ($result['appointment_status'] == 'Approved') {
            $result['appointment_no'] = $this->customlib->getSessionPrefixByType('appointment') . $id;
        }
        $result["patients_name"] = composePatientName($result['patients_name'], $result['patient_id']);
        $result["edit_live_consult"] = $this->lang->line($result['live_consult']);
        $result["live_consult"] = $result['live_consult'];
        $result["date"] = $this->customlib->YYYYMMDDHisTodateFormat($result['date'], $this->time_format);
        $result['custom_fields_value'] = display_custom_fields('appointment', $id);
        $cutom_fields_data = get_custom_table_values($id, 'appointment');
        $result['field_data'] = $cutom_fields_data;
        $result['patients_gender'] = $result['patients_gender'];
        $result['amount'] = amountFormat($result['Paidamount'] + ($result['temp_appt_amount']));
        $result["opd_id"] = $this->customlib->getSessionPrefixByType('opd_no') . $result['opd_details_id'];
        $result["redirect_link_opd"] = base_url('admin/patient/visitdetails/' . base64_encode($result['patient_id']) . '/' . base64_encode($result['opd_details_id']));
        $result["redirect_link_ipd"] = base_url('admin/patient/ipdprofile/' . $result['ipd_details_id']);
        $result["module"] = $result['module'];
        $result["ipd_id"] = $this->customlib->getSessionPrefixByType('ipd_no') . $result['ipd_details_id'];
        if (!empty($result['payment_mode'])) {
            $result['payment_mode'] = $result['payment_mode'];
        } else {
            $result['payment_mode'] = '';
        }
        $result['cheque_no'] = $result['cheque_no'];
        $result['appointment_serial_no'] = $result['appointment_serial_no'];
        $result['cheque_date'] = $this->customlib->YYYYMMDDTodateFormat($result['cheque_date']);
        $result['attachment'] = $result['attachment'];
        $transaction_id = $result['transaction_id'];
        if ($result['transaction_id'] != "") {
            $result['transaction_id'] = $this->customlib->getSessionPrefixByType('transaction_id') . $result['transaction_id'];
        } else {
            $result['transaction_id'] = "";
        }

        $result['department_name'] = $result['department_name'];
        $result['age'] = $result['age'];
        $result['day'] = $result['day'];
        $result['month'] = $result['month'];
        $result['appoint_priority'] = $result['priority_status'];
        $result['patient_age'] = $this->customlib->getPatientAge($result['age'], $result['month'], $result['day']);

        if ($result['attachment'] != "") {
            $result["doc"] = "<a href='" . site_url('admin/transaction/download/') . $transaction_id . "' class='btn btn-default btn-xs'  title=" . $this->lang->line('download') . "><i class='fa fa-download'></i></a>";
        } else {
            $result["doc"] = "";
        }

        echo json_encode($result);
    }

    public function getappDetails($id)
    {
        $result = $this->appointment_model->getDetails($id);
        $result["date"] = $this->customlib->YYYYMMDDHisTodateFormat($result['date'], $this->time_format);
        echo json_encode($result);
    }

    /*
    This Function is Used to Delete created Appointment patient
     */
    public function delete($id)
    {
        if (!empty($id)) {
            $appointment_details = $this->appointment_model->getDetails($id);
            $visit_details_id = $appointment_details['visit_details_id'];
            $visit_data = $this->patient_model->getVisitdataDetails($visit_details_id);
            $opd_id = $visit_data['opdid'];
            $this->appointment_model->delete($id, $visit_details_id, $opd_id);
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('delete_message'));
        } else {
            $array = array('status' => 'fail', 'error' => '', 'message' => '');
        }
        echo json_encode($array);
    }

    /*
    This Function is Used to move patient from appointment to other module

     */
    public function move($id)
    {
        $appointment_details = $this->appointment_model->getDetails($id);
        $patient_name = $appointment_details['patient_name'];
        $gender = $appointment_details['gender'];
        $email = $appointment_details['email'];
        $phone = $appointment_details['mobileno'];
        $doctor = $appointment_details['doctor'];
        $note = $appointment_details['message'];
        $appointment_date = $appointment_details['date'];
        $amount = $appointment_details['amount'];
        $live_consult = $appointment_details['live_consult'];

        $check_patient_id = $this->patient_model->getMaxId();
        if (empty($check_patient_id)) {
            $check_patient_id = 1000;
        }
        $patient_id = $check_patient_id + 1;
        $patient_data = array(
            'patient_name' => $patient_name,
            'mobileno' => $phone,
            'email' => $email,
            'gender' => $gender,
            'patient_unique_id' => $patient_id,
            'note' => $note,
            'is_active' => 'yes',
        );

        $insert_id = $this->patient_model->add_patient($patient_data);
        $user_password = $this->role->get_random_password($chars_min = 6, $chars_max = 6, $use_upper_case = false, $include_numbers = true, $include_special_chars = false);
        $data_patient_login = array(
            'username' => $this->patient_login_prefix . $insert_id,
            'password' => $user_password,
            'user_id' => $insert_id,
            'role' => 'patient',
        );
        $this->user_model->add($data_patient_login);
        $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
            $fileInfo = pathinfo($_FILES["file"]["name"]);
            $img_name = $insert_id . '.' . $fileInfo['extension'];
            move_uploaded_file($_FILES["file"]["tmp_name"], "./uploads/patient_images/" . $img_name);
            $data_img = array('id' => $insert_id, 'image' => 'uploads/patient_images/' . $img_name);
            $this->patient_model->add($data_img);
        }
        if (isset($insert_id)) {
            $check_opd_id = $this->patient_model->getMaxOPDId();
            $opdnoid = $check_opd_id + 1;

            $opd_data = array(
                'appointment_date' => $appointment_date,
                'opd_no' => 'OPDN' . $opdnoid,
                'cons_doctor' => $doctor,
                'patient_id' => $insert_id,
                'amount' => $amount,
                'live_consult' => $live_consult,
            );
            $opd_id = $this->patient_model->add_opd($opd_data);

            if (isset($opd_id)) {
                $this->appointment_model->delete($id);
            }
        }

        redirect('admin/appointment/search');
    }

    public function moveipd()
    {
        $custom_fields = $this->customfield_model->getByBelong('ipd');

        foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
            if ($custom_fields_value['validation']) {
                $custom_fields_id = $custom_fields_value['id'];
                $custom_fields_name = $custom_fields_value['name'];
                $this->form_validation->set_rules("custom_fields[ipd][" . $custom_fields_id . "]", $custom_fields_name, 'trim|required');
            }
        }
        $this->form_validation->set_rules('bed_no', $this->lang->line('bed_no'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('appointment_date', $this->lang->line('appointment_date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('consultant_doctor', $this->lang->line('consultant_doctor'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $msg = array(
                'appointment_date' => form_error('appointment_date'),
                'bed_no' => form_error('bed_no'),
                'consultant_doctor' => form_error('consultant_doctor'),
                'opd_id' => form_error('opd_id'),

            );
            if (!empty($custom_fields)) {
                foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
                    if ($custom_fields_value['validation']) {
                        $custom_fields_id = $custom_fields_value['id'];
                        $custom_fields_name = $custom_fields_value['name'];
                        $error_msg2["custom_fields[ipd][" . $custom_fields_id . "]"] = form_error("custom_fields[ipd][" . $custom_fields_id . "]");
                    }
                }
            }

            if (!empty($error_msg2)) {
                $error_msg = array_merge($msg, $error_msg2);
            } else {
                $error_msg = $msg;
            }
            $array = array('status' => 'fail', 'error' => $error_msg, 'message' => '');
        } else {

            $appointment_id = $this->input->post('appointment_id');
            $appointment_details = $this->appointment_model->getDetails($appointment_id);
            $ipd_details = array(
                'patient_id' => $appointment_details['patient_id'],
                'bed' => $this->input->post('bed_no'),
                'bed_group_id' => $this->input->post('bed_group_id'),
                'height' => $this->input->post('height'),
                'weight' => $this->input->post('weight'),
                'pulse' => $this->input->post('pulse'),
                'temperature' => $this->input->post('temperature'),
                'respiration' => $this->input->post('respiration'),
                'bp' => $this->input->post('bp'),
                'case_type' => $this->input->post('case'),
                'casualty' => $this->input->post('casualty'),
                'symptoms' => $this->input->post('symptoms'),
                'known_allergies' => $this->input->post('symptoms'),
                'date' => $this->customlib->dateFormatToYYYYMMDDHis($this->input->post('appointment_date'), $this->time_format),
                'refference' => $this->input->post('refference'),
                'cons_doctor' => $this->input->post('consultant_doctor'),
                'live_consult' => $this->input->post('live_consult'),
                'discharged' => 'no',
            );
            $bed_history = array(
                "bed_group_id" => $this->input->post("bed_group_id"),
                "bed_id" => $this->input->post("bed_no"),
                "from_date" => date("Y-m-d H:i:s"),
                "is_active" => "yes",
            );
            $ipd_id = $this->appointment_model->moveToIpd($ipd_details, $bed_history, $appointment_id);
            if ($ipd_id) {
                $array = array('status' => 'success', 'message' => $this->lang->line('success_message'), 'ipd_id' => $ipd_id);
            } else {
                $msg = array('no_insert' => $this->lang->line('something_went_wrong'));
                $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
            }
        }
        echo json_encode($array);
    }

    public function getpatientDetails()
    {
        $id = $this->input->post("patient_id");
        $result = $this->appointment_model->getpatientDetails($id);
        echo json_encode($result);
    }

    public function checkvalidation()
    {
        $search = $this->input->post('search');
        $this->form_validation->set_rules('search_type', $this->lang->line('search_type'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $msg = array(
                'search_type' => form_error('search_type'),
            );
            $json_array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $param = array(
                'search_type' => $this->input->post('search_type'),
                'collect_staff' => $this->input->post('collect_staff'),
                'date_from' => $this->input->post('date_from'),
                'date_to' => $this->input->post('date_to'),
                'shift' => $this->input->post('shift'),
                'priority' => $this->input->post('priority'),
                'appointment_type' => $this->input->post('appointment_type'),
            );

            $json_array = array('status' => 'success', 'error' => '', 'param' => $param, 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($json_array);
    }

    public function appointmentreport()
    {
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'admin/appointment/appointmentreport');
        $doctorlist = $this->staff_model->getEmployeeByRoleID(3);
        $data['doctorlist'] = $doctorlist;
        $custom_fields = $this->customfield_model->get_custom_fields('appointment', '', '', 1);
        $data['fields'] = $custom_fields;
        $data['appoint_priority_list'] = $this->appoint_priority_model->appoint_priority_list();
        $data['appointment_type'] = $this->config->item('appointment_type');
        $data["searchlist"] = $this->search_type;
        $this->load->view('layout/header');
        $this->load->view('admin/appointment/appointmentReport', $data);
        $this->load->view('layout/footer');
    }

    public function appointmentreports()
    {
        $search['search_type'] = $this->input->post('search_type');
        $search['collect_staff'] = $this->input->post('collect_staff');
        $search['date_from'] = $this->input->post('date_from');
        $search['date_to'] = $this->input->post('date_to');
        $shift = $this->input->post('shift');
        $priority = $this->input->post('priority');
        $appointment_type = $this->input->post('appointment_type');
        $start_date = '';
        $end_date = '';
        $fields = $this->customfield_model->get_custom_fields('appointment', '', '', 1);

        if ($search['search_type'] == 'period') {
            $start_date = $this->customlib->dateFormatToYYYYMMDD($search['date_from']);
            $end_date = $this->customlib->dateFormatToYYYYMMDD($search['date_to']);
        } else {
            if (!empty($search['search_type'])) {
                $dates = $this->customlib->get_betweendate($search['search_type']);
                $data['search_type'] = $search['search_type'];
            } else {
                $dates = $this->customlib->get_betweendate('this_year');
                $data['search_type'] = '';
            }
            $start_date = $dates['from_date'];
            $end_date = $dates['to_date'];
        }

        $reportdata = $this->report_model->appointmentRecord($start_date, $end_date, $search['collect_staff'], $shift, $priority, $appointment_type);
        $reportdata = json_decode($reportdata);
        $dt_data = [];
        $paid_amount = 0;
        $currency_symbol = $this->customlib->getHospitalCurrencyFormat();

        if (!empty($reportdata->data)) {
            foreach ($reportdata->data as $value) {
                $paid_amount += $value->paid_amount;
                $label = match ($value->appointment_status) {
                    "Requested" => "#FF985F",
                    "Reserved" => "#FFCB44",
                    "Approved", "Completed" => "#00D65B",
                    "Cancelled" => "#FF0600",
                    "InProcess" => "#6070FF",
                    default => ""
                };

                $row = [];
                $row[] = composePatientName($value->patient_name, $value->patient_id);
                $row[] = $this->customlib->YYYYMMDDHisTodateFormat($value->date, $this->time_format);
                $row[] = $value->mobileno;
                $row[] = $value->gender;
                $row[] = composeStaffNameByString($value->name, $value->surname, $value->employee_id);
                $row[] = $value->source;

                if (!empty($fields)) {
                    foreach ($fields as $fields_value) {
                        $display_field = $value->{$fields_value->name};
                        if ($fields_value->type == "link") {
                            $display_field = "<a href='{$value->{$fields_value->name}}' target='_blank'>{$value->{$fields_value->name}}</a>";
                        }
                        $row[] = $display_field;
                    }
                }

                $row[] = $value->paid_amount;
                $row[] = "<small style='display: inline-block; min-width: 80px; padding: 4px 8px; border-radius: 4px; background-color: {$label}; color: #fff; font-size: 0.8em; line-height: 1.2; text-align: center; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;'>" . htmlspecialchars($value->appointment_status) . "</small>";
                $dt_data[] = $row;
            }

            $footer_row = array_fill(0, count($dt_data[0] ?? []) - 2, "");
            $footer_row[] = "<b>" . $this->lang->line('total_amount') . "</b>:";
            $footer_row[] = "<b>{$currency_symbol}" . number_format($paid_amount, 2, '.', '') . "</b>";
            $dt_data[] = $footer_row;
        }

        echo json_encode([
            "draw" => intval($reportdata->draw),
            "recordsTotal" => intval($reportdata->recordsTotal),
            "recordsFiltered" => intval($reportdata->recordsFiltered),
            "data" => $dt_data,
        ]);
    }


    public function getDoctorFees()
    {
        $doctor_id = $this->input->post("doctor_id");
        $shift_details = $this->onlineappointment_model->getShiftDetails($doctor_id);
        $charge_details = $this->charge_model->getChargeDetailsById($shift_details['charge_id']);
        echo json_encode(
            array(
                "fees" => isset($charge_details->standard_charge) ? amountFormat($charge_details->standard_charge + ($charge_details->standard_charge * $charge_details->percentage / 100)) : "",
                "charge_id" => $shift_details['charge_id']
            )
        );
    }

    /**
     * This function is used to validate document for upload
     **/
    public function handle_doc_upload($str, $var)
    {
        $image_validate = $this->config->item('file_validate');

        if (isset($_FILES[$var]) && !empty($_FILES[$var]['name'])) {

            $file_type = $_FILES[$var]['type'];
            $file_size = $_FILES[$var]["size"];
            $file_name = $_FILES[$var]["name"];

            $allowed_extension = $image_validate["allowed_extension"];
            $allowed_mime_type = $image_validate["allowed_mime_type"];
            $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            if ($files = filesize($_FILES[$var]['tmp_name'])) {
                if (!in_array($file_type, $allowed_mime_type)) {
                    $this->form_validation->set_message('handle_doc_upload', $this->lang->line('file_type_extension_error_uploading_document'));
                    return false;
                }

                if (!in_array($ext, $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {
                    $this->form_validation->set_message('handle_doc_upload', $this->lang->line('extension_error_while_uploading_document'));
                    return false;
                }
                if ($file_size > 2097152) {
                    $this->form_validation->set_message('handle_doc_upload', $this->lang->line('file_size_shoud_be_less_than') . "2MB");
                    return false;
                }
            } else {
                $this->form_validation->set_message('handle_doc_upload', $this->lang->line('error_while_uploading_document'));
                return false;
            }

            return true;
        }
        return true;
    }

    public function reschedule()
    {
        $custom_fields = $this->customfield_model->getByBelong('appointment');
        if (!empty($custom_fields)) {
            foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
                if ($custom_fields_value['validation']) {
                    $custom_fields_id = $custom_fields_value['id'];
                    $custom_fields_name = $custom_fields_value['name'];

                    $this->form_validation->set_rules("custom_fields[appointment][" . $custom_fields_id . "]", $custom_fields_name, 'trim|required');
                }
            }
        }
        $this->form_validation->set_rules('appointment_date', $this->lang->line('appointment_date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('rglobal_shift', $this->lang->line('shift'), 'trim|required');
        $this->form_validation->set_rules('rslot', $this->lang->line('slot'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('edit_appointment_status', $this->lang->line('status'), 'trim|required');

        if ($this->form_validation->run() == false) {
            $msg = array(
                'appointment_date' => form_error('appointment_date'),
                'rglobal_shift' => form_error('rglobal_shift'),
                'rslot' => form_error('rslot'),
            );
            if (!empty($custom_fields)) {
                foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
                    if ($custom_fields_value['validation']) {
                        $custom_fields_id = $custom_fields_value['id'];
                        $custom_fields_name = $custom_fields_value['name'];
                        $error_msg2["custom_fields[appointment][" . $custom_fields_id . "]"] = form_error("custom_fields[appointment][" . $custom_fields_id . "]");
                    }
                }
            }
            if (!empty($error_msg2)) {
                $error_msg = array_merge($msg, $error_msg2);
            } else {
                $error_msg = $msg;
            }
            $array = array('status' => 'fail', 'error' => $error_msg, 'message' => '');
        } else {
            $appointment_id = $this->input->post('appointment_id');
            $appointment = array(
                'id' => $appointment_id,
                'date' => $this->customlib->dateFormatToYYYYMMDDHis($this->input->post('appointment_date'), $this->time_format),
                'priority' => $this->input->post('priority'),
                'global_shift_id' => $this->input->post('rglobal_shift'),
                'shift_id' => $this->input->post('rslot'),
                'live_consult' => $this->input->post('live_consult'),
                'appointment_status' => $this->input->post('edit_appointment_status'),
                'message' => $this->input->post('message'),
            );

            $this->appointment_model->update($appointment);

            $appointment_details = $this->appointment_model->getDetails($appointment_id);
            $transaction_data = $this->transaction_model->getTransactionByAppointmentId($appointment_id);
            $appointment_payment = $this->appointment_model->getPaymentByAppointmentId($appointment_id);

            if ($appointment_details['visit_details_id'] == '') {
                if ($this->input->post('edit_appointment_status') == 'approved') {
                    /* OPD Insert Code*/

                    $charges = $this->charge_model->getChargeByChargeId($appointment_payment->charge_id);
                    $apply_charge = $charges['standard_charge'] + ($charges['standard_charge'] * ($charges['percentage'] / 100));
                    $opd_details = array(
                        'patient_id' => $appointment_details['patient_id'],
                        'generated_by' => $this->customlib->getStaffID(),
                    );
                    $consult = $this->input->post('live_consult');
                    $visit_details = array(
                        'appointment_date' => date("Y-m-d H:i:s"),
                        'opd_details_id' => 0,
                        'cons_doctor' => $appointment_details['doctor'],
                        'generated_by' => $this->customlib->getLoggedInUserID(),
                        'patient_charge_id' => null,
                        'transaction_id' => $transaction_data->id,
                        'can_delete' => 'no',
                        'live_consult' => $consult,
                    );
                    $staff_data = $this->staff_model->getStaffByID($appointment_details['doctor']);
                    $staff_name = composeStaffName($staff_data);
                    $charge = array(
                        'opd_id' => 0,
                        'date' => date('Y-m-d H:i:s'),
                        'charge_id' => $appointment_payment->charge_id,
                        'qty' => 1,
                        'apply_charge' => $charges['standard_charge'],
                        'standard_charge' => $charges['standard_charge'],
                        'amount' => $appointment_payment->paid_amount,
                        'created_at' => date('Y-m-d H:i:s'),
                        'note' => null,
                        'tax' => $charges['percentage'],
                    );

                    $doctor_fees = $this->input->post('doctor_fees');

                    $opd_visit_id = $this->appointment_model->moveToOpd($opd_details, $visit_details, $charge, $appointment_id, $doctor_fees);
                    $this->appointment_model->updateappointmentpayment($appointment_id, $doctor_fees);

                    /* OPD Insert Code*/

                    $visit_detail = $this->patient_model->getVisitDetailByid($opd_visit_id);
                    $setting_result = $this->setting_model->getzoomsetting();
                    $opdduration = $setting_result->opd_duration;
                    if ($consult == 'yes') {
                        $api_type = 'global';
                        $params = array(
                            'zoom_api_key' => "",
                            'zoom_api_secret' => "",
                        );

                        $title = 'Online consult for ' . $this->customlib->getSessionPrefixByType('opd_no') . $visit_detail->opd_details_id . " Checkup ID " . $visit_detail->id;
                        $this->load->library('zoom_api', $params);
                        $insert_array = array(
                            'staff_id' => $appointment_details['doctor'],
                            'visit_details_id' => $visit_detail->id,
                            'title' => $title,
                            'date' => $date_appoint,
                            'duration' => 60,
                            'created_id' => $this->customlib->getStaffID(),
                            'password' => random_string(),
                            'api_type' => $api_type,
                            'host_video' => 1,
                            'client_video' => 1,
                            'purpose' => 'consult',
                            'timezone' => $this->customlib->getTimeZone(),
                        );

                        $response = $this->zoom_api->createAMeeting($insert_array);

                        if (!empty($response)) {
                            if (isset($response->id)) {
                                $insert_array['return_response'] = json_encode($response);
                                $this->conference_model->add($insert_array);
                            }
                        }
                    }
                }
            }

            $custom_field_post = $this->input->post("custom_fields[appointment]");
            if (!empty($custom_fields)) {
                foreach ($custom_field_post as $key => $value) {
                    $check_field_type = $this->input->post("custom_fields[appointment][" . $key . "]");
                    $field_value = is_array($check_field_type) ? implode(",", $check_field_type) : $check_field_type;
                    $array_custom = array(
                        'belong_table_id' => $appointment_id,
                        'custom_field_id' => $key,
                        'field_value' => $field_value,
                    );
                    $custom_value_array[] = $array_custom;
                }
                $this->customfield_model->updateRecord($custom_value_array, $appointment_id, 'appointment');
            }

            $sender_details = array('patient_id' => $appointment_details["patient_id"], 'appointment_id' => $appointment_id);

            if ($this->input->post('appointment_status') == 'approved') {
                $this->mailsmsconf->mailsms('appointment_approved', $sender_details);
                $this->system_notification->send_system_notification('notification_appointment_created', $event_data);
                $this->system_notification->send_system_notification('appointment_approved', $event_data);
            }

            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    public function appointstatusupdate($id)
    {
        $result = $this->appointment_model->update_appointment_status($id);

        $response = array();

        if ($result) {

            $response['status'] = 'success';
            $response['message'] = 'Action performed successfully.';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Error updating appointment status.';
        }

        echo json_encode($response);
    }

    public function processPayment()
    {
        $keyId = getenv('RAZORPAY_LIVE_KEY') ?: 'rzp_live_E1TEgAPfjZ0OIQ';
        $keySecret = getenv('RAZORPAY_LIVE_SECRET') ?: 'eSskg54N7Bulwusbu7dGEMNr';
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['status' => false, 'message' => 'Invalid request method']);
            exit;
        }
        $inputData = json_decode(file_get_contents('php://input'), true);
        $paymentId = $inputData['paymentId'] ?? null;
        if (!$paymentId) {
            http_response_code(400);
            echo json_encode(['status' => false, 'message' => 'Payment ID is required']);
            exit;
        }
        $paymentDetails = $this->razorpayRequest("https://api.razorpay.com/v1/payments/$paymentId", $keyId, $keySecret);
        if (!$paymentDetails || !isset($paymentDetails['status'])) {
            http_response_code(500);
            echo json_encode(['status' => false, 'message' => 'Error fetching payment details']);
            exit;
        }
        if ($paymentDetails['status'] === 'authorized') {
            $captureDetails = $this->razorpayRequest("https://api.razorpay.com/v1/payments/$paymentId/capture", $keyId, $keySecret, [
                'amount' => $paymentDetails['amount']
            ]);
            if ($captureDetails && isset($captureDetails['status']) && $captureDetails['status'] === 'captured') {
                http_response_code(200);
                echo json_encode([
                    'status' => true,
                    'message' => 'Payment captured successfully',
                    'payment_id' => $captureDetails['id'],
                    'amount' => $captureDetails['amount'] / 100,
                    'payment_method' => $captureDetails['method'] ?? 'unknown',
                    'reference_id' => $captureDetails['acquirer_data']['rrn'] ?? null
                ]);
                exit;
            }

            http_response_code(500);
            echo json_encode(['status' => false, 'message' => 'Failed to capture payment']);
            exit;
        }

        http_response_code(400);
        echo json_encode(['status' => false, 'message' => 'Payment not authorized or invalid payment ID']);
    }

    private function razorpayRequest($url, $keyId, $keySecret, $postData = null)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "$keyId:$keySecret");

        if ($postData) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        }

        $response = curl_exec($ch);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            error_log("Curl error: " . $curlError);
            return false;
        }

        return json_decode($response, true);
    }

    public function getpatientdetail()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        $patient_id = $_GET['patientid'];
        if (!$patient_id) {
            echo json_encode(['status' => false, 'message' => 'Patient ID is required']);
            return;
        }
        $patient = $this->appointment_model->get_patient_by_id($patient_id);
        if ($patient) {
            echo json_encode([
                'status' => true,
                'fullname' => $patient->patient_name,
                'mobilenumber' => $patient->mobileno,
                'email' => $patient->email
            ]);
        } else {
            echo json_encode(['status' => false, 'message' => 'Patient not found']);
        }
    }

    public function allappoitment()
    {
        $sessionData = $this->session->userdata('hospitaladmin');
        $api_base_url = $this->config->item('api_base_url');
        $token = $sessionData['accessToken'] ?? '';
        $draw = isset($_GET['draw']) ? intval($_GET['draw']) : 1;
        $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
        $page = isset($_GET['page']) ? intval($_GET['page']) : 0;
        $filter = isset($_GET['filter']) ? $_GET['filter'] : 'today';
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $url = $api_base_url . 'add-appointment/v2/getAllpage?limit=' . $limit . '&page=' . $page . '&filter=period:' . $filter . '&search=' . $search . '&hospital_id=' . $sessionData["hospital_id"];

        $makeRequest = function ($token) use ($url) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: ' . $token
            ]);
            $response = curl_exec($ch);
            curl_close($ch);
            return $response;
        };

        $response = $makeRequest($token);
        $responseData = json_decode($response, true);

        if (isset($responseData['status_code']) && $responseData['status_code'] == 403) {
            $newToken = $this->customlib->refreshToken();
            $sessionData['accessToken'] = $newToken;
            $this->session->set_userdata('hospitaladmin', $sessionData);
            $response = $makeRequest($newToken);
            $responseData = json_decode($response, true);
        }

        $output = [
            "draw" => $draw,
            "recordsTotal" => isset($responseData['total']) ? (int)$responseData['total'] : 0,
            "recordsFiltered" => isset($responseData['total']) ? (int)$responseData['total'] : 0,
            "data" => isset($responseData['data']) ? $responseData['data'] : []
        ];

        header('Content-Type: application/json');
        echo json_encode($output);
    }

    public function getappoitmentinfo()
    {
        $id = isset($_GET['appointment_id']) ? $_GET['appointment_id'] : "";
        if (!$id) {
            return false;
        }
        $api_base_url = $this->config->item('api_base_url');
        $data = $this->session->userdata('hospitaladmin');
        $url = $api_base_url . "add-appointment/$id?hospital_id=" . $data['hospital_id'];
        $token = $data['accessToken'];
        $makeRequest = function ($token) use ($url) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: ' . $token
            ]);
            $response = curl_exec($ch);
            curl_close($ch);
            return $response;
        };
        $response = $makeRequest($token);
        $responseData = json_decode($response, true);
        if (isset($responseData['status_code']) && $responseData['status_code'] == 403) {
            $newToken = $this->customlib->refreshToken();
            $sessionData['accessToken'] = $newToken;
            $this->session->set_userdata('hospitaladmin', $sessionData);
            $response = $makeRequest($newToken);
            $responseData = json_decode($response, true);
        }
        if (isset($responseData['status']) && $responseData['status'] == "failed") {
            echo json_encode(['status' => false, 'message' => 'Something went wrong']);
            return;
        }

        $result = $responseData[0];

        $result["patients_name"] = composePatientName($result['patient_name'], $result['patient_id']);
        $result["edit_live_consult"] = $this->lang->line($result['consultingType']);
        $result["live_consult"] = $result['consultingType'];
        $result["date"] = $this->customlib->YYYYMMDDHisTodateFormat($result['date'], $this->time_format);
        $result['custom_fields_value'] = display_custom_fields('appointment', $id);
        $result['field_data'] = get_custom_table_values($id, 'appointment');
        $result['patients_gender'] = $result['gender'];
        $result["redirect_link_opd"] = base_url('admin/patient/visitdetails/' . base64_encode($result['patient_id']) . '/' . base64_encode($result['opd_id']));
        $result["opd_id"] = $this->customlib->getSessionPrefixByType('opd_no') . $result['opd_id'];
        $result["redirect_link_ipd"] = base_url('admin/patient/ipdprofile/' . $result['ipd_id']);
        $result["module"] = $result['module'];
        $result["ipd_id"] = $this->customlib->getSessionPrefixByType('ipd_no') . $result['ipd_id'];
        $result["doctors_name"] = $result['doctorName'];
        $result["global_shift_name"] = $result['shift'];
        $result["doctor_shift_name"] = $result['slot'];
        $result["patient_email"] = $result['email'];
        $result["patient_mobileno"] = $result['mobileno'];
        $result["standard_charge"] = $result['consultFees'];
        $result["additional_charge"] = $result['additional_charge'];
        $result["discount_amount"] = $result['discount_amount'];
        $result["discount_percentage"] = $result['discount_percentage'];
        $result["additional_charge_note"] = $result['additional_charge_note'];
        $result["paid_amount"] = $result['paid_amount'];
        $result['payment_mode'] = !empty($result['payment_mode']) ? $result['payment_mode'] : '';
        $result['cheque_no'] = $result['cheque_no'];
        $result['appointment_serial_no'] = $result['tokenNumber'];
        $result['cheque_date'] = $this->customlib->YYYYMMDDTodateFormat($result['cheque_date']);
        $result['attachment'] = $result['attachment'];
        $transaction_id = $result['transactionID'];
        $result['transaction_id'] = $transaction_id != "" ? $transaction_id : "";
        $result['department_name'] = $result['department_name'];
        $result['age'] = $result['age'];
        $result['day'] = $result['day'];
        $result['month'] = $result['month'];
        $result['appoint_priority'] = $result['priority_status'];
        $result['patient_age'] = $this->customlib->getPatientAge($result['age'], $result['month'], $result['day']);
        $result["doc"] = $result['attachment'] != "" ? "<a href='" . site_url('admin/transaction/download/') . $transaction_id . "' class='btn btn-default btn-xs' title=" . $this->lang->line('download') . "><i class='fa fa-download'></i></a>" : "";

        echo json_encode($result);
    }


    public function getappoitmentreport()
    {
        $search_type = $this->input->post('search_type');
        $doctor_id = $this->input->post('collect_staff');
        $shift_id = $this->input->post('shift');
        $priority_id = $this->input->post('priority');
        $appointment_type = $this->input->post('appointment_type');
        $date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');
        $search_value = $this->input->get('search');
        $draw = isset($_GET['draw']) ? intval($_GET['draw']) : 1;
        $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $offset = ($page - 1) * $limit;
        $range = $this->transaction_model->getDateRangeByType($search_type, $date_from, $date_to);
        $data = [
            'fromDate' => $range["fromDate"],
            'toDate' => $range["toDate"],
            'doctorId' => $doctor_id,
            'shiftId' => $shift_id,
            'priority' => $priority_id,
            'source' => "Offline"
        ];
        $api_base_url = $this->config->item('api_base_url');
        $hospitaldata = $this->session->userdata('hospitaladmin');
        $token = $hospitaldata['accessToken'] ?? '';
        $url = $api_base_url . 'php-appointment-report?page=' . $page . '&search=' . $search_value . '&limit=' . $limit;

        $makeRequest = function ($accessToken) use ($url, $data) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
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

        if (isset($responseData['status_code']) && $responseData['status_code'] == 403) {
            $newToken = $this->customlib->refreshToken();
            $hospitaldata['accessToken'] = $newToken;
            $this->session->set_userdata('hospitaladmin', $hospitaldata);
            $response = $makeRequest($newToken);
            $responseData = json_decode($response, true);
        }

        $output = [
            "draw" => $draw,
            "recordsTotal" => isset($responseData['total']) ? (int)$responseData['total'] : 0,
            "recordsFiltered" => isset($responseData['total']) ? (int)$responseData['total'] : 0,
            "data" => isset($responseData['data']) ? $responseData['data'] : []
        ];

        echo json_encode($output);
    }
}
