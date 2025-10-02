<?php
include '../connection/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['requestType'])) {
        if ($_POST['requestType'] == 'AddBlotter') {


                // Collect POST Data
                $complainant_name     = $_POST['complainant_name'];
                $complainant_age      = $_POST['complainant_age'];
                $complainant_address  = $_POST['complainant_address'];
                $complainant_email    = $_POST['complainant_email'];
                $complainant_phone    = $_POST['complainant_phone'];

                $respondent_name      = $_POST['respondent_name'];

                // Check kung naka-check ang checkbox
                if (isset($_POST['unknown_respondent']) && $_POST['unknown_respondent'] === "on") {
                    $respondent_name = "Unknown";
                }

                $respondent_age       = $_POST['respondent_age'];
                $respondent_address   = $_POST['respondent_address'];
                $blotter_type         = $_POST['blotter_type'];
                $incident_location    = $_POST['incident_location'];
                $date_reported        = $_POST['date_reported'];
                $time_reported        = $_POST['time_reported'];
                $incident_narrative   = $_POST['incident_narrative'];

                // FILES
                $documentFileName = '';
                if (!empty($_FILES['supporting_documents']) && $_FILES['supporting_documents']['error'] === UPLOAD_ERR_OK) {
                    // Limit: 10MB
                    $maxFileSize = 10 * 1024 * 1024; 

                    if ($_FILES['supporting_documents']['size'] > $maxFileSize) {
                        echo json_encode([
                            'status' => 400,
                            'message' => 'File size exceeds the 10MB limit.'
                        ]);
                        exit;
                    }

                    $uploadDir = '../uploads/blotter/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true); // create directory if not exists
                    }

                    $documentExtension = pathinfo($_FILES['supporting_documents']['name'], PATHINFO_EXTENSION);
                    $documentFileName = uniqid('doc_', true) . '.' . $documentExtension;
                    $documentPath = $uploadDir . $documentFileName;

                    $documentUploaded = move_uploaded_file($_FILES['supporting_documents']['tmp_name'], $documentPath);

                    if (!$documentUploaded) {
                        echo json_encode([
                            'status' => 500,
                            'message' => 'Error uploading supporting document.'
                        ]);
                        exit;
                    }
                } elseif (!empty($_FILES['supporting_documents']) && $_FILES['supporting_documents']['error'] !== UPLOAD_ERR_NO_FILE) {
                    echo json_encode([
                        'status' => 400,
                        'message' => 'Invalid document upload.'
                    ]);
                    exit;
                }

                // Insert Query
                $stmt = $conn->prepare("INSERT INTO j_blotter (
                    complainant_name, complainant_age, complainant_address, complainant_email, complainant_phone,
                    respondent_name, respondent_age, respondent_address,
                    blotter_type, incident_location, date_reported, time_reported, incident_narrative, supporting_documents
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

                // Bind all as string to avoid type mismatch errors
                $stmt->bind_param(
                    "ssssssssssssss",
                    $complainant_name,
                    $complainant_age,
                    $complainant_address,
                    $complainant_email,
                    $complainant_phone,
                    $respondent_name,
                    $respondent_age,
                    $respondent_address,
                    $blotter_type,
                    $incident_location,
                    $date_reported,
                    $time_reported,
                    $incident_narrative,
                    $documentFileName
                );

                $result = $stmt->execute();

                if ($result) {
                    echo json_encode([
                        'status' => 200,
                        'message' => 'Blotter record saved successfully.'
                    ]);
                } else {
                    echo json_encode([
                        'status' => 500,
                        'message' => 'Error saving data: ' . $stmt->error
                    ]);
                }

                $stmt->close();
        } else if ($_POST['requestType'] == "UpdateBlotter") {

            // echo "<pre>";
            // print_r($_POST);
            // echo "</pre>";
                
                $id                   = $_POST['blotter_id'];

                $complainant_name     = $_POST['complainant_name'];
                $complainant_age      = $_POST['complainant_age'];
                $complainant_address  = $_POST['complainant_address'];
                $complainant_email    = $_POST['complainant_email'];
                $complainant_phone    = $_POST['complainant_phone'];

                $respondent_name      = $_POST['respondent_name'];
                // Check kung naka-check ang unknown respondent
                if (isset($_POST['unknown_respondent']) && $_POST['unknown_respondent'] === "on") {
                    $respondent_name = "Unknown";
                }

                $respondent_age       = $_POST['respondent_age'];
                $respondent_address   = $_POST['respondent_address'];

                $blotter_type         = $_POST['blotter_type'];
                $incident_location    = $_POST['incident_location'];
                $date_reported        = $_POST['date_reported'];
                $time_reported        = $_POST['time_reported'];
                $incident_narrative   = $_POST['incident_narrative'];

                $hearing_date  = trim($_POST['hearing_date']) !== '' ? $_POST['hearing_date'] : null;
                $hearing_time  = trim($_POST['hearing_time']) !== '' ? $_POST['hearing_time'] : null;
                $scheduled_by  = trim($_POST['scheduled_by']) !== '' ? $_POST['scheduled_by'] : null;



                $blotter_status       = $_POST['blotter_status'];

                // 🔹 File handling
                $documentFileName = $_POST['existing_document'] ?? ''; // from hidden field if no new file
                if (!empty($_FILES['supporting_documents']) && $_FILES['supporting_documents']['error'] === UPLOAD_ERR_OK) {
                    $maxFileSize = 10 * 1024 * 1024; 
                    if ($_FILES['supporting_documents']['size'] > $maxFileSize) {
                        echo json_encode([
                            'status' => 400,
                            'message' => 'File size exceeds 10MB limit.'
                        ]);
                        exit;
                    }

                    $uploadDir = '../uploads/blotter/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }

                    $documentExtension = pathinfo($_FILES['supporting_documents']['name'], PATHINFO_EXTENSION);
                    $documentFileName = uniqid('doc_', true) . '.' . $documentExtension;
                    $documentPath = $uploadDir . $documentFileName;

                    if (!move_uploaded_file($_FILES['supporting_documents']['tmp_name'], $documentPath)) {
                        echo json_encode([
                            'status' => 500,
                            'message' => 'Error uploading new document.'
                        ]);
                        exit;
                    }
                }

                // 🔹 Update Query (all fields)
                $stmt = $conn->prepare("UPDATE j_blotter SET 
                    complainant_name=?, complainant_age=?, complainant_address=?, complainant_email=?, complainant_phone=?,
                    respondent_name=?, respondent_age=?, respondent_address=?,
                    blotter_type=?, incident_location=?, date_reported=?, time_reported=?, incident_narrative=?, supporting_documents=?,
                    hearing_date=?, hearing_time=?, scheduled_by=?, blotter_status=?
                    WHERE blotter_id=?");

                $stmt->bind_param(
                    "sissssisssssssisssi",
                    $complainant_name,
                    $complainant_age,
                    $complainant_address,
                    $complainant_email,
                    $complainant_phone,
                    $respondent_name,
                    $respondent_age,
                    $respondent_address,
                    $blotter_type,
                    $incident_location,
                    $date_reported,
                    $time_reported,
                    $incident_narrative,
                    $documentFileName,
                    $hearing_date,
                    $hearing_time,
                    $scheduled_by,
                    $blotter_status,
                    $id
                );

                if ($stmt->execute()) {
                    echo json_encode([
                        "status" => 200,
                        "message" => "Blotter record updated successfully."
                    ]);
                } else {
                    echo json_encode([
                        "status" => 500,
                        "message" => "Error updating record: " . $stmt->error
                    ]);
                }

                $stmt->close();
                exit;
        }else {
            echo '404';
        }
    } else {
        echo 'Access Denied! No Request Type.';
    }
}
?>
