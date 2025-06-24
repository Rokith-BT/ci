<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Refund Policy - Hospital Management</title>
    <style>
        /* Global Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        header {
            text-align: center;
            margin-bottom: 30px;
        }

        h1 {
            font-size: 36px;
            color: #333;
        }

        footer {
            text-align: center;
            font-size: 14px;
            color: #888;
            margin-top: 30px;
        }

        /* Policy Content */
        .policy-content {
            line-height: 1.6;
        }

        .policy-section {
            margin-bottom: 20px;
        }

        .policy-section h2 {
            background-color: #007bff;
            color: white;
            padding: 10px;
            cursor: pointer;
            border-radius: 4px;
        }

        .policy-text {
            padding: 0 15px;
            display: none;  /* Hidden by default */
        }

        .policy-section ul,
        .policy-section ol {
            margin-left: 20px;
        }

        /* Footer Styles */
        footer p {
            font-size: 14px;
            color: #555;
        }

        /* Hover Effect for Links */
        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Refund Policy</h1>
        </header>

        <section class="policy-content">
            <p>Thank you for choosing our hospital services. We strive to provide the best medical care and support. However, if you are not satisfied with the service provided, we are here to assist with your refund request under the terms outlined below.</p>

            <div class="policy-section" id="refund-eligibility">
                <h2 class="toggle-button">1. Refund Eligibility</h2>
                <div class="policy-text">
                    <p>Refunds are available for certain services under the following conditions:</p>
                    <ul>
                        <li>Refund requests must be made within 30 days of payment.</li>
                        <li>The request must be related to a non-fulfilled service or a canceled appointment.</li>
                        <li>Services must not have been utilized (e.g., consultations, treatments, surgeries).</li>
                        <li>Refunds are not applicable for medical tests or consultations after they have been rendered.</li>
                    </ul>
                </div>
            </div>

            <div class="policy-section" id="non-refundable-items">
                <h2 class="toggle-button">2. Non-Refundable Items</h2>
                <div class="policy-text">
                    <p>Non-refundable items include:</p>
                    <ul>
                        <li>Consultation fees (if the consultation has been completed).</li>
                        <li>Medical treatments or surgeries already performed.</li>
                        <li>Lab tests or diagnostic fees once the service is completed.</li>
                        <li>Prescription medications once dispensed.</li>
                    </ul>
                </div>
            </div>

            <div class="policy-section" id="how-to-request-refund">
                <h2 class="toggle-button">3. How to Request a Refund</h2>
                <div class="policy-text">
                    <p>To request a refund, please follow the steps outlined below:</p>
                    <ol>
                        <li>Contact our patient support team via phone or email within 30 days of service.</li>
                        <li>Provide your payment details, including the transaction number and date of service.</li>
                        <li>State the reason for the refund request (e.g., missed appointment, overcharge, canceled service).</li>
                        <li>Our team will review the request and provide further instructions.</li>
                    </ol>
                </div>
            </div>

            <div class="policy-section" id="processing-time">
                <h2 class="toggle-button">4. Processing Time</h2>
                <div class="policy-text">
                    <p>Once a refund request is received and approved, the refund will be processed within 7-10 business days. The refund will be issued to the same payment method used for the original transaction.</p>
                </div>
            </div>

            <div class="policy-section" id="shipping-costs">
                <h2 class="toggle-button">5. Shipping Costs</h2>
                <div class="policy-text">
                    <p>If any medical products were purchased (e.g., hospital goods, medications), shipping costs are non-refundable. Refunds for these items will only cover the cost of the item(s), not the shipping charges.</p>
                </div>
            </div>

            <div class="policy-section" id="changes-to-policy">
                <h2 class="toggle-button">6. Changes to This Policy</h2>
                <div class="policy-text">
                    <p>We reserve the right to update or modify this Refund Policy at any time. If we make any significant changes, we will notify you via email or by posting the updated policy on our website.</p>
                </div>
            </div>

            <div class="policy-section" id="contact-us">
                <h2 class="toggle-button">7. Contact Us</h2>
                <div class="policy-text">
                    <p>If you have any questions or concerns regarding our Refund Policy, please reach out to our support team at:</p>
                    <p>Email: <a href="mailto:support@hospital.com">support@hospital.com</a></p>
                    <p>Phone: +1 (800) 123-4567</p>
                </div>
            </div>
        </section>

        <footer>
            <p>&copy; <span id="year"></span> Plenome | All Rights Reserved</p>
        </footer>
    </div>

    <script>
        // Toggle visibility for each section in the refund policy
        document.querySelectorAll('.toggle-button').forEach(button => {
            button.addEventListener('click', () => {
                const content = button.nextElementSibling;
                if (content.style.display === 'block') {
                    content.style.display = 'none';
                } else {
                    content.style.display = 'block';
                }
            });
        });

        // Set current year in the footer
        document.getElementById('year').textContent = new Date().getFullYear();
    </script>
</body>
</html>
