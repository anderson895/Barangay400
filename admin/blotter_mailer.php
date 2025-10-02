<?php
include '../connection/config.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $blotter_id = intval($_POST['blotter_id']);

    // Fetch all necessary data from the database
    $stmt = $conn->prepare("SELECT * FROM j_blotter WHERE blotter_id = ?");
    $stmt->bind_param("i", $blotter_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $email = $row['complainant_email']; // get email directly from DB
        $complainant_name = $row['complainant_name'];
        $respondent_name = $row['respondent_name'];
        $blotter_type = $row['blotter_type'];
        $incident_location = $row['incident_location'];
        $incident_narrative = $row['incident_narrative'];
        $hearing_date = $row['hearing_date'];
        $hearing_time = $row['hearing_time'];
        $scheduled_by = $row['scheduled_by'];
        $blotter_status = $row['blotter_status'];

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'systembarangaymanagement@gmail.com';
            $mail->Password = 'qqwafpvwljoixsxa';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('systembarangaymanagement@gmail.com', 'Barangay Management System');
            $mail->addAddress($email, $complainant_name); // use email from DB

            $mail->isHTML(true);
            $mail->Subject = 'Hearing Schedule Notification';
            $mail->Body = "
                <h2>Hearing Schedule Notification</h2>
                <p><strong>Complainant Name:</strong> {$complainant_name}</p>
                <p><strong>Respondent Name:</strong> {$respondent_name}</p>
                <p><strong>Blotter Type:</strong> {$blotter_type}</p>
                <p><strong>Incident Location:</strong> {$incident_location}</p>
                <p><strong>Incident Narrative:</strong> {$incident_narrative}</p>
                <p><strong>Hearing Date:</strong> {$hearing_date}</p>
                <p><strong>Hearing Time:</strong> {$hearing_time}</p>
                <p><strong>Scheduled By:</strong> {$scheduled_by}</p>
                <p><strong>Status:</strong> {$blotter_status}</p>
                <hr>
                <p>Please be present on the scheduled date and time.</p>
            ";

            $mail->send();
            echo "Email sent successfully";
        } catch (Exception $e) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        }
    } else {
        echo "Blotter not found";
    }
}
?>
