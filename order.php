<?php
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $paper_type = $_POST['paper_type'] ?? '';
    $academic_level = $_POST['academic_level'] ?? '';
    $deadline = $_POST['deadline'] ?? '';
    $number_of_pages = $_POST['number_of_pages'] ?? '';
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $contact_no = $_POST['contact_no'] ?? '';
    $total_amount = $_POST['total_amount'] ?? '';
    $career_documents_instructions = $_POST['career_documents_instructions'] ?? '';
    $from = 'azeem.alikhan777@gmail.com';

    // Validation
    $errors = [];
    if (empty($paper_type)) $errors[] = 'Paper Type';
    if (empty($academic_level)) $errors[] = 'Academic Level';
    if (empty($deadline)) $errors[] = 'Deadline';
    if (empty($number_of_pages)) $errors[] = 'Number of Pages';
    if (empty($name)) $errors[] = 'Name';
    if (empty($email)) $errors[] = 'Email';
    if (empty($contact_no)) $errors[] = 'Contact Number';
    if (empty($total_amount)) $errors[] = 'Total Amount';

    if (!empty($errors)) {
        $error_message = 'The following fields are required: ' . implode(', ', $errors);
        echo "
        <script>
            sessionStorage.setItem('orderPlaced', 'false');
            sessionStorage.setItem('errorMessage', '{$error_message}');
            window.location.href = 'index.html'; // Redirect to index.html
        </script>
        ";
        exit();
    }

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.titan.email'; // Titan email SMTP host
        $mail->SMTPAuth   = true;
        $mail->Username   = 'info@assignmenthelpuk.uk'; // Your Titan email address
        $mail->Password   = 'a6\_ZySx,UMhgf\\'; // Correctly escape the trailing backslash
        $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also works if required
        $mail->Port       = 587; // TCP port to connect to, use 465 for SSL
    
        // Sender and recipient settings
        $mail->setFrom('info@assignmenthelpuk.uk', 'Assignment Help Uk'); // Your name
        $mail->addAddress($email, $name);
        $mail->addAddress('info@assignmenthelpuk.uk', 'Assignment Help UK'); // Customer's email

        $formated_deadline = formatDeadline($deadline);

    
        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'Order Confirmation';
        $mail->Body    = "
            <html>
            <head>
                <style>
                    .hero-section {
                        background-color: #f9f9e3;
                        padding: 20px;
                        text-align: center;
                    }
                    .hero-heading {
                        color: #000;
                        font-size: 36px;
                        margin-bottom: 20px;
                    }
                    .image-container img {
                        max-width: 600px;
                        height: auto;
                        display: block;
                        margin: 0 auto;
                    }
                    .details-table {
                        width: 100%;
                        border-collapse: collapse;
                        margin: 20px auto;
                        border: 1px solid #4CAF50;
                    }
                    .details-table th, .details-table td {
                        border: 1px solid #4CAF50;
                        padding: 10px;
                        text-align: left;
                    }
                    .details-table th {
                        background-color: #f2f2f2;
                    }
                    .footer {
                        background-color: #f9f9e3;
                        padding: 20px;
                        text-align: center;
                    }
                </style>
            </head>
            <body>
                <div class='hero-section'>
                    <div class='hero-heading'>Order Confirmed</div>
                    <div class='image-container'>
                        <img src='https://www.sendx.io/hubfs/Email-Messages-for-Order-Confirmation-Page-v3.png' alt='Order Image'>
                    </div>
                    <table class='details-table'>
                        <tr>
                            <th>Deadline</th>
                            <td>{$formated_deadline} </td>
                        </tr>
                        <tr>
                            <th>Number of Pages</th>
                            <td>{$number_of_pages}</td>
                        </tr>
                        <tr>
                            <th>Paper Type</th>
                            <td>{$paper_type}</td>
                        </tr>
                        <tr>
                            <th>Academic Level</th>
                            <td>{$academic_level}</td>
                        </tr>
                        <tr>
                            <th>Total Amount</th>
                            <td>\${$total_amount}</td>
                        </tr>
                        <tr>
                            <th>Career Documents Instructions</th>
                            <td>{$career_documents_instructions}</td>
                        </tr>
                    </table>
                </div>
                <div class='footer'>
                    <h3>Thank you for your order!</h3>
                </div>
            </body>
            </html>
        ";
    
        // Send email
        $mail->send();
    
        // Redirect on success
        echo "
        <script>
            sessionStorage.setItem('orderPlaced', 'true');
            window.location.href = 'index.html'; // Redirect to index.html
        </script>
        ";
    } catch (Exception $e) {
        // Redirect on error
        echo "
        <script>
            sessionStorage.setItem('orderPlaced', 'false');
            window.location.href = 'index.html'; // Redirect to index.html
        </script>
        ";
    }

}

function formatDeadline($deadline) {
    if (strpos($deadline, 'Hrs') !== false) {
        // Replace 'Hrs' with 'Hours'
        return str_replace('Hrs', ' Hours', $deadline);
    } else {
        // Append 'Days' if 'Hrs' is not present
        return $deadline . ' Days';
    }
}
?>