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
            <h2>Terms and Conditions</h2>
            <p class="lead">Please review our Terms and Conditions for MedSuite Pro</p>
        </div>
        <div class="effective-date text-center mb-4">
            <strong>Effective Date:</strong> January 15, 2025 &nbsp; | &nbsp; <strong>Version:</strong> 3.2.1
        </div>

        <div class="privacy-content">
            <h3>Terms and Conditions</h3>
            <p>Welcome to the Plenome Healthcare Management System (HMS). By accessing or using our website and services, you agree to comply with and be bound by the following terms and conditions. Please read them carefully before using our services</p>

            <h4>Acceptance of Terms</h4>
            <p>
                By using this website, you acknowledge that you have read, understood, and agree to these terms and conditions. If you do not agree to these terms, please do not use our website.
            </p>

            <h4>Medical Disclaimer</h4>
            <p>
                The information provided on this website is for general informational purposes only and is not a substitute for professional medical advice, diagnosis, or treatment. Always seek the advice of your physician or other qualified healthcare providers with any questions regarding your medical condition. Never disregard professional medical advice or delay in seeking it because of something you have read on this website.
            </p>

            <h4>Use of Services</h4>
            <p>The content and services provided on this website, including online appointment scheduling, health information, and other resources, are intended for personal, non-commercial use. You agree not to misuse or use these services for any illegal, fraudulent, or harmful activities.</p>

            <h4>Intellectual Property</h4>
            <p>All content on this website, including text, images, logos, and other materials, is the intellectual property of Plenome Healthcare and is protected by copyright, trademark, and other intellectual property laws. Unauthorized use, reproduction, or distribution of any content is strictly prohibited.</p>
            <h4>Privacy Policy</h4>
            <p>Your use of this website is also governed by our <span class="highlight">Privacy Policy</span>, which outlines how we collect, use, and protect your personal information. By using our services, you consent to the collection and use of your information as described in the Privacy Policy.</p>
            <h4>Limitation of Liability</h4>
            <p>Plenome Healthcare shall not be liable for any direct, indirect, incidental, special, consequential, or punitive damages arising from your use of this website or services. This includes, but is not limited to, damages for loss of profits, goodwill, use, data, or other intangible losses.</p>
            <h4>Changes to Terms</h4>
            <p>Plenome Healthcare reserves the right to modify or update these terms and conditions at any time without prior notice. Your continued use of the website after any changes indicates your acceptance of the revised terms.</p>
            <h4>Governing Law</h4>
            <p>These terms and conditions shall be governed by and construed in accordance with the laws of the jurisdiction in which Plenome Healthcare operates, without regard to its conflict of law principles.</p>
            
            <h4>Contact Information</h4>
            <p>For questions or concerns, contact our <span class="highlight">Support Officer</span> at <a href="mailto:privacy@medsuiteprp.com">privacy@medsuiteprp.com</a> or call <strong>1-800-XXXXX</strong>.</p>
        </div>
    </div>

    <!-- JS -->
    <script src="<?php echo base_url(); ?>backend/js/pagenation.js"></script>
    <script src="<?php echo base_url(); ?>backend/js/tour.js"></script>
</body>
</html>