<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Information Card</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4a6cf7;
            --secondary-color: #3151d3;
            --accent-color: #f0f4ff;
            --light-gray: #f8f9fa;
            --border-color: #e9ecef;
            --text-dark: #343a40;
            --text-muted: #6c757d;
            --success-color: #10b981;
            --warning-color: #f59e0b;
        }

        body {
            font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f4f7fd;
            margin: 20px;
            padding: 0;
            color: var(--text-dark);
        }

        .container {
            width: 100%;
            margin: 0 auto;
        }

        .patient-card {
            background-color: #fff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            margin-bottom: 30px;
            transition: all 0.3s ease;
        }

        .patient-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .patient-card-body {
            padding: 30px;
        }

        .patient-card-row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -15px;
        }

        .patient-profile {
            flex: 0 0 25%;
            max-width: 25%;
            padding: 0 15px;
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
        }

        .patient-profile:after {
            content: '';
            position: absolute;
            right: 0;
            top: 10%;
            height: 80%;
            width: 1px;
            background-color: var(--border-color);
        }

        .patient-image-container {
            position: relative;
            margin-bottom: 25px;
        }

        .patient-image {
            width: 160px;
            height: 160px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid white;
            box-shadow: 0 8px 25px rgba(74, 108, 247, 0.12);
            transition: all 0.3s ease;
        }

        .status-indicator {
            position: absolute;
            bottom: 12px;
            right: 12px;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background-color: var(--success-color);
            border: 3px solid white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .bill-btn {
            background-color: white;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
            border-radius: 50px;
            padding: 12px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            width: 100%;
            max-width: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
            font-size: 14px;
            outline: none;
        }

        .bill-btn:hover {
            background-color: var(--primary-color);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(74, 108, 247, 0.2);
        }

        .patient-details {
            flex: 0 0 75%;
            max-width: 75%;
            padding: 0 15px;
        }

        .patient-header {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border-color);
        }

        .patient-name {
            font-size: 1.7rem;
            font-weight: 700;
            margin: 0;
            color: var(--text-dark);
            letter-spacing: -0.5px;
        }

        .patient-id {
            background-color: var(--accent-color);
            color: var(--primary-color);
            padding: 6px 15px;
            border-radius: 50px;
            font-size: 0.9rem;
            margin-left: 15px;
            font-weight: 600;
        }

        .info-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background-color: #fff;
            border-radius: 12px;
            overflow: hidden;
        }

        .info-table tr {
            transition: all 0.2s ease;
        }

        .info-table tr:hover {
            background-color: rgba(74, 108, 247, 0.03);
        }

        .info-table tr:nth-child(odd) {
            background-color: var(--light-gray);
        }

        .info-table tr:nth-child(odd):hover {
            background-color: #f0f2f5;
        }

        .info-table th,
        .info-table td {
            padding: 16px 20px;
            vertical-align: middle;
            border-bottom: 1px solid var(--border-color);
        }

        .info-table th {
            color: var(--text-muted);
            font-weight: 600;
            text-align: right;
            width: 150px;
            font-size: 14px;
        }

        .info-table td {
            font-weight: 500;
            color: var(--text-dark);
        }

        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-left: 10px;
        }

        .badge-warning {
            background-color: #fff8e6;
            color: var(--warning-color);
            border: 1px solid #ffeeba;
        }

        @media (max-width: 992px) {
            .patient-profile,
            .patient-details {
                flex: 0 0 100%;
                max-width: 100%;
            }

            .patient-profile {
                border-right: none;
                padding-bottom: 30px;
                margin-bottom: 30px;
                border-bottom: 1px solid var(--border-color);
            }

            .patient-profile:after {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .patient-card-body {
                padding: 20px;
            }

            .patient-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .patient-id {
                margin-left: 0;
                margin-top: 10px;
            }

            .info-table th,
            .info-table td {
                padding: 12px;
            }
        }
    </style>
</head>

<body>
    <?php
    $userdata = $this->session->userdata('hospitaladmin');
    $accessToken = $userdata['accessToken'] ?? '';

    $default_image = base_url() . "uploads/patient_images/no_image.png";
    if ($result['PatientImage']) {
        $url = "https://phr-api.plenome.com/file_upload/getDocs";
        $client = curl_init($url);
        curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($client, CURLOPT_POST, true);
        curl_setopt($client, CURLOPT_POSTFIELDS, json_encode(['value' => $result['PatientImage']]));
        curl_setopt($client, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: ' . $accessToken
            ]);
        $response = curl_exec($client);
        curl_close($client);

        if ($response !== false && strpos($response, '"NoSuchKey"') === false) {
            $imageSrc = "data:image/png;base64," . trim($response);
        } else {
            $imageSrc = base_url() . "uploads/staff_images/no_image.png";
        }
    } else {
        $imageSrc = base_url() . "uploads/staff_images/no_image.png";
    }
    ?>
    <div class="container">
        <div class="patient-card">
            <div class="patient-card-body">
                <div class="patient-card-row">
                    <div class="patient-profile">
                        <div class="patient-image-container">
                            <img src="<?=$imageSrc?>" class="patient-image" alt="Patient Image">
                            <div class="status-indicator"></div>
                        </div>
                        <button class="bill-btn showbill" data-case-id="21">
                            <i class="fas fa-file-invoice-dollar"></i>
                            Bill Summary
                        </button>
                    </div>
                    <div class="patient-details">
                        <div class="patient-header">
                            <h2 class="patient-name">PATIENT</h2>
                            <span class="patient-id">PID: <?= $result["PatientID"] ? $result["PatientID"] : '-' ?></span>
                        </div>
                        <table class="info-table">
                            <tbody>
                                <tr>
                                    <th>Name</th>
                                    <td><?= $result["patientName"] ?? '-' ?></td>
                                    <th>Guardian Name</th>
                                    <td><?= trim($result['guardianName'] ?? '-') ?: '-' ?></td>
                                </tr>
                                <tr>
                                    <th>Gender</th>
                                    <td><?= trim($result['gender'] ?? '-') ?: '-' ?></td>
                                    <th>Age</th>
                                    <td><?= trim($result['age'] ?? '-') ?: '-' ?></td>
                                </tr>
                                <tr>
                                    <th>Phone</th>
                                    <td><?= trim($result['phone'] ?? '-') ?: '-' ?></td>
                                    <th>Address</th>
                                    <td><?= trim($result['address'] ?? '-') ?: '-' ?></td>
                                </tr>
                                <?php if(isset($type) && $type=="ipd_id") { ?>
                                <tr>
                                    <th>IPD No</th>
                                    <td>
                                        <?= $this->customlib->getSessionPrefixByType('ipd_no') . trim($case_id ?? '-') ?: '-' ?>
                                        <?php if(isset($result['Bed_name']) && $result['Bed_name']=="Discharge") { ?>
                                        <span class="badge badge-warning">Discharged</span>
                                        <?php } ?>
                                    </td>
                                    <th>Bed</th>
                                    <td><?= trim($result['Bed_name'] ?? '-') ?: '-' ?></td>
                                </tr>
                                <?php } ?>
                                <tr>
                                    <th>Admission Date</th>
                                    <td>
                                        <?php
                                        $admissionDate = $result['AdmissionDate'] ?? null;
                                        if ($admissionDate) {
                                            $formattedDate = date('d F, Y', strtotime($admissionDate));
                                            echo $formattedDate;
                                        } else {
                                            echo '-';
                                        }
                                        ?>
                                    </td>
                                    <th>CaseID</th>
                                    <td><?= trim($result['CaseID'] ?? '-') ?: '-' ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>