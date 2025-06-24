<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Transaction extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('transaction_model'));
    }

    public function printTransaction()
    {
        $print_details         = $this->printing_model->get('', 'paymentreceipt');
        $id                    = $this->input->post('id');
        $transaction           = $this->transaction_model->getTransaction($id);
        $data['transaction']   = $transaction;
        $data['print_details'] = $print_details;
        $page                  = $this->load->view('admin/transaction/_printTransaction', $data, true);
        echo json_encode(array('status' => 1, 'page' => $page));
    }

    public function deleteByID()
    {
        $id          = $this->input->post('id');
        $transaction = $this->transaction_model->delete($id);
        $array       = array('status' => 'success', 'message' => $this->lang->line('delete_message'));
        echo json_encode($array);
    }

    public function download($trans_id)
    {
        $transaction = $this->transaction_model->getTransaction($trans_id);
        $this->load->helper('download');
        $filepath    = "./uploads/payment_document/" . $transaction->attachment;
        $report_name = $transaction->attachment_name;
        $data        = file_get_contents($filepath);
        force_download($report_name, $data);
    }

    public function transactionreport()
    {
        if (!$this->rbac->hasPrivilege('daily_transaction_report', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'admin/transaction/dailytransactionreport');
        $data['initialData'] = $this->customlib->getpagenation('php-daily-transaction-summary-report');
        $data['title'] = 'title';
        $this->form_validation->set_rules('date_from', $this->lang->line('date_from'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('date_to', $this->lang->line('date_to'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $msg = array(
                'date_from' => form_error('date_from'),
                'date_to'   => form_error('date_to'),
            );
            $json_array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $date_from = $this->customlib->dateFormatToYYYYMMDD($this->input->post('date_from'));
            $date_to   = $this->customlib->dateFormatToYYYYMMDD($this->input->post('date_to'));

            $reportdata = $this->transaction_model->getTransactionBetweenDate($date_from, $date_to, 'payment');
            $start_date = strtotime($date_from);
            $end_date   = strtotime($date_to);
            $date_array = array();
            for ($i = $start_date; $i <= $end_date; $i += 86400) {
                $date_array[date('Y-m-d', $i)] = array('amount' => 0, 'online_transaction' => 0, 'offline_transaction' => 0, 'total_transaction' => 0);
            }

            if (!empty($reportdata)) {
                foreach ($reportdata as $key => $value) {

                    $date_array[date('Y-m-d', strtotime($value->payment_date))]['amount']            = $date_array[date('Y-m-d', strtotime($value->payment_date))]['amount'] + $value->amount;
                    $date_array[date('Y-m-d', strtotime($value->payment_date))]['total_transaction'] = $date_array[date('Y-m-d', strtotime($value->payment_date))]['total_transaction'] + 1;

                    if ($value->payment_mode == "Online") {
                        $date_array[date('Y-m-d', strtotime($value->payment_date))]['online_transaction'] = $date_array[date('Y-m-d', strtotime($value->payment_date))]['online_transaction'] + $value->amount;
                    } else {
                        $date_array[date('Y-m-d', strtotime($value->payment_date))]['offline_transaction'] = $date_array[date('Y-m-d', strtotime($value->payment_date))]['offline_transaction'] + $value->amount;
                    }
                }
            }

            $dt_data = array();
            foreach ($date_array as $dt_key => $dt_value) {
                $row                        = array();
                $row['date']                = $dt_key;
                $row['total_transaction']   = $dt_value['total_transaction'];
                $row['online_transaction']  = $dt_value['online_transaction'];
                $row['offline_transaction'] = $dt_value['offline_transaction'];
                $row['amount']              = $dt_value['amount'];
                $dt_data[]                  = $row;
            }

            $data['result'] = $dt_data;            
        }

        $this->load->view('layout/header', $data);
        $this->load->view('admin/transaction/transactionreport', $data);
        $this->load->view('layout/footer', $data);
    }

    public function gettransactionbydate()
    {
        if (!$this->rbac->hasPrivilege('transaction_report', 'can_view')) {
            access_denied();
        }
        $date          = $this->input->post('date');
        $data['title'] = 'title';
        $result         = $this->transaction_model->getTransactionBetweenDate($date, $date, 'payment');
        $data['result'] = $result;
        $page           = $this->load->view('admin/transaction/_gettransactionbydate', $data, true);
        echo json_encode(array('status' => 1, 'page' => $page));
    }

    public function getdetialsbyidopdipd()
    {
        $api_base_url = $this->config->item('api_base_url');
        $hospitaldata = $this->session->userdata('hospitaladmin');
        $token = $hospitaldata['accessToken'] ?? '';
        $username = $hospitaldata["username"] ?? '-';

        $Transation_Id = $this->input->get('transationId');
        $hospital_id = $this->input->get('hospital_id');
        $type = $this->input->get('type');
        $opd_or_ipd_id = $this->input->get('opdoridId');

        switch ($type) {
            case "Appointment":
                $prefix = $this->customlib->getSessionPrefixByType('appointment');
                break;
            case "OPD":
                $prefix = $this->customlib->getSessionPrefixByType('opd_no');
                break;
            case "IPD":
                $prefix = $this->customlib->getSessionPrefixByType('ipd_no');
                break;
            default:
                $prefix = $this->customlib->getSessionPrefixByType('opd_no');
                break;
        }

        $transactionPrefix = $this->customlib->getSessionPrefixByType('transaction_id');
        $api_url = $api_base_url . "php-daily-transaction-summary-report/{$Transation_Id}?hospital_id={$hospital_id}";

        $makeRequest = function ($accessToken, $url) {
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

        $response = $makeRequest($token, $api_url);
        $responseData = json_decode($response, true);
        if ($responseData['status_code'] == 200 && !empty($responseData['data'][0])) {
            $api_data = $responseData['data'][0];
            $patient = $api_data['patientDetails'];
            $invoice_list = $api_data['invoiceDetails'];
            $print_details = $this->printing_model->get('', 'paymentreceipt');

            $itemdata = [];
            foreach ($invoice_list as $invoice) {
                $itemdata[] = [
                    'description' => $invoice['chargeName'],
                    'quantity' => $invoice['qty'],
                    'price' => $invoice['standard_charge'],
                    'tax' => $invoice['taxPercentage'] ?? 0,
                    'discount' => $invoice['discount_percentage'] ?? 0,
                    'discount_amount' => $invoice['discount_amount'] ?? 0,
                    'total' => $invoice['total'],
                    'payment_mode' => $invoice['payment_mode'],
                    'additional_charge' => $invoice['additional_charge'] ?? 0,
                ];
            }

            $first_invoice = $invoice_list[0] ?? [];
            $patient_data = [
                'name' => $patient['patient_name'] ?? '-',
                'patient_id' => $patient['patient_id'] ?? '-',
                'bill_no' => $Transation_Id,
                'age' => $patient['age'] ?? '-',
                'gender' => isset($patient['gender']) ? substr($patient['gender'], 0, 1) : '-',
                'consultant' => (!empty($first_invoice['doctor_first_name']) || !empty($first_invoice['doctor_last_name']))
                    ? 'Dr. ' . ($first_invoice['doctor_first_name'] ?? '') . ' ' . ($first_invoice['doctor_last_name'] ?? '')
                    : '-',
                'date' => isset($first_invoice['payment_date']) ? date("d/m/Y", strtotime($first_invoice['payment_date'])) : '-',
                'mobile' => $patient['mobileno'] ?? '-',
                'items' => $itemdata,
                'amount_receivable' => $api_data['total'] ?? 0,
                'amount_received' => $api_data['total'] ?? 0,
                'received_by' => $username,
            ];

            $data = [
                "invoicedetials" => $patient_data,
                "print_details" => $print_details,
                "transationid" => $transactionPrefix . $Transation_Id,
                "billid" => $Transation_Id,
                "type" => $type,
                "opd_or_ipd_id" => $opd_or_ipd_id ? ($prefix . $opd_or_ipd_id) : '-',
                "opd_or_ipd_id_op" => $opd_or_ipd_id,
                "prifix" => $prefix,
            ];

            $page = $this->load->view('admin/transaction/get_opd_bill', $data);
            echo json_encode(['status' => 1, 'page' => $page]);
        } else {
            echo json_encode(['status' => 0, 'message' => 'Failed to fetch data.']);
        }
    }



    public function generate_bill()
    {
        $page                   = $this->load->view('admin/bill/_generatebill', $data, true);
        echo json_encode(array('status' => 1, 'page' => $page));
    }

    public function getalltransationreport()
    {
        $search_type = $this->input->post('search_type');
        $date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');
        $search_value = $this->input->get('search');
        $draw = isset($_GET['draw']) ? intval($_GET['draw']) : 1;
        $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $collect_staff =$this->input->post('collect_staff');
        $offset = ($page - 1) * $limit;
        $range = $this->transaction_model->getDateRangeByType($search_type, $date_from, $date_to);
        $data = [
            'startDate' => $range['fromDate'],
            'endDate' => $range['toDate']
        ];
        $api_base_url = $this->config->item('api_base_url');
        $hospitaldata = $this->session->userdata('hospitaladmin');
        $token = $hospitaldata['accessToken'] ?? '';
        $url = $api_base_url . 'php-transaction-report?page=' . $page . '&search=' . urlencode($search_value) . '&limit=' . $limit . '&filter=' . urlencode($collect_staff);
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
        $payment_summary = $this->transaction_model->getpaymentmethod_detials($responseData['data']);
        $output = [
            "draw" => $draw,
            "recordsTotal" => isset($responseData['total']) ? (int)$responseData['total'] : 0,
            "recordsFiltered" => isset($responseData['total']) ? (int)$responseData['total'] : 0,
            "data" => isset($responseData['data']) ? $responseData['data'] : [],
            "payment_summary" => $payment_summary,
        ];
        echo json_encode($output);
    }


    public function dailysummaryreport()
    {
        $search_type = $this->input->post('search_type');
        $date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');
        $search_value = $this->input->get('search');
        $draw = isset($_GET['draw']) ? intval($_GET['draw']) : 1;
        $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $offset = ($page - 1) * $limit;

        $range = $this->transaction_model->getDateRangeByType($search_type, $date_from, $date_to);
        $data = [
            'fromDate' => $range['fromDate'],
            'toDate' => $range['toDate'],
        ];

        $api_base_url = $this->config->item('api_base_url');
        $hospitaldata = $this->session->userdata('hospitaladmin');
        $token = $hospitaldata['accessToken'] ?? '';
        $url = $api_base_url . 'php-daily-transaction-summary-report?page=' . $page . '&search=' . $search_value . '&limit=' . $limit;

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
            "data" => isset($responseData['data']) ? $responseData['data'] : [],
        ];

        echo json_encode($output);
    }


    public function dailysummaryreportdetails()
    {
        $api_base_url = $this->config->item('api_base_url');
        $hospitaldata = $this->session->userdata('hospitaladmin');
        $token = $hospitaldata['accessToken'] ?? '';

        $type = $this->input->get('type');
        $date = $this->input->get('date');

        $url = $api_base_url . 'php-daily-transaction-summary-report?date=' . $date . '&section=' . $type;

        $makeRequest = function ($accessToken) use ($url) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPGET, true);
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

        $data = isset($responseData['data']) && is_array($responseData['data']) ? $responseData['data'] : [];
        $payment_summary = $this->transaction_model->getpaymentmethod_detials($responseData['data']);
        $output = [
            "draw" => (int)$this->input->get('draw'),
            "recordsTotal" => isset($responseData['total']) ? (int)$responseData['total'] : 0,
            "recordsFiltered" => isset($responseData['total']) ? (int)$responseData['total'] : 0,
            "data" => $data,
            "payment_summary" => $payment_summary
        ];
        echo json_encode($output);
    }

   
}
