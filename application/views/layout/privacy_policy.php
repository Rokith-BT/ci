<?php
$data = $this->session->userdata('hospitaladmin');
$api_base_url = $this->config->item('api_base_url');
$api_base_url_casesheet = $this->config->item('s3key');
$hospitalname = $this->customlib->gethospitalname();
$hospital_logo = isset($hospitalname[0]['mini_logo']) ? $hospitalname[0]['mini_logo'] : '';
$apiEndpoint = $api_base_url_casesheet . 'upload-doc-previw/get-signed-url';
$requestPayload = json_encode(["value" => $hospital_logo]);
$accessToken = isset($data['accessToken']) ? $data['accessToken'] : '';
$requestOptions = [
    'http' => [
        'header'  => "Content-Type: application/json\r\n" .
            "Authorization: Bearer " . $accessToken . "\r\n",
        'method'  => 'POST',
        'content' => $requestPayload,
        'ignore_errors' => true
    ]
];
$httpContext = stream_context_create($requestOptions);
$apiResponse = @file_get_contents($apiEndpoint, false, $httpContext);
if ($apiResponse === FALSE) {
    error_log("Failed to fetch the signed URL. API endpoint might be incorrect or unavailable.");
    $imageSignedUrl = '';
} else {
    $imageSignedUrl = json_decode($apiResponse, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log("Failed to decode API response: " . json_last_error_msg());
        $imageSignedUrl = '';
    }
}
$currency_symbol = $this->customlib->getHospitalCurrencyFormat();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $hospitalname[0]['name']; ?> - Privacy Policy</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#5190fd">
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="<?= isset($imageSignedUrl['imageURL']) ? $imageSignedUrl['imageURL'] : "" ?>" type="image/x-icon">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/style-main.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/jquery.mCustomScrollbar.min.css">

    <style>
        body {
            background-color: #f9f9f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .main-container {
            max-width: 900px;
            margin: 40px auto;
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 0 25px rgba(0,0,0,0.08);
        }
        .page-title h2 {
            font-size: 2rem;
            font-weight: 600;
        }
        .page-title p {
            font-size: 1rem;
            color: #666;
        }
        .effective-date {
            margin-top: 20px;
            font-size: 0.95rem;
            color: #444;
        }
        .privacy-content h3, .privacy-content h4 {
            color: #2358f3;
            margin-top: 30px;
        }
        .privacy-content ul {
            padding-left: 20px;
        }
        .privacy-content ul li {
            margin-bottom: 10px;
        }
        .highlight {
            font-weight: 600;
            color: #0d6efd;
        }
    </style>
</head>
<body>

    <div class="main-container">
        <div class="page-title text-center mb-4">
            <h2>Privacy Policy</h2>
            <p class="lead">Please review our privacy policy for MedSuite Pro</p>
        </div>
        <div class="effective-date text-center mb-4">
            <strong>Effective Date:</strong> January 15, 2025 &nbsp; | &nbsp; <strong>Version:</strong> 3.2.1
        </div>

        <div class="privacy-content">
            <h3>🔒 Privacy Policy</h3>
            <p>MedSuite Pro is committed to protecting your privacy and maintaining the confidentiality of all <span class="highlight">Protected Health Information (PHI)</span> in accordance with federal and state regulations.</p>

            <h4>Information Collection</h4>
            <ul>
                <li>Patient demographics and medical records</li>
                <li>Clinical data and treatment histories</li>
                <li>Billing and insurance information</li>
                <li>System access logs and audit trails</li>
            </ul>

            <h4>Data Protection Measures</h4>
            <ul>
                <li><span class="highlight">AES-256 encryption</span> for data at rest and in transit</li>
                <li>Multi-factor authentication protocols</li>
                <li>Regular security assessments and penetration testing</li>
                <li>24/7 security monitoring and incident response</li>
            </ul>

            <h4>Data Sharing</h4>
            <p>We only share your information as required for treatment, payment, healthcare operations, or as mandated by law. All third-party sharing complies with <span class="highlight">HIPAA</span> minimum necessary standards.</p>

            <h4>Contact Information</h4>
            <p>For privacy-related questions or concerns, contact our <span class="highlight">Privacy Officer</span> at <a href="mailto:privacy@medsuiteprp.com">privacy@medsuiteprp.com</a> or call <strong>1-800-PRIVACY</strong>.</p>
        </div>
    </div>

    <!-- JS -->
    <script src="<?php echo base_url(); ?>backend/js/pagenation.js"></script>
    <script src="<?php echo base_url(); ?>backend/js/tour.js"></script>
</body>
</html>