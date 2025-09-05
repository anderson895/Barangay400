<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include '../connection/config.php';

$user_id = $_SESSION['user_id'];

$query = "SELECT res_id FROM tbl_residents WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $resident = $result->fetch_assoc();
    $res_id = $resident['res_id'];
} else {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate required fields (trim the input to remove any leading/trailing spaces)
// Debugging: Print POST data to check if 'purpose' is coming through correctly
//echo "<pre>";
//print_r($_POST);
//echo "</pre>";
//exit();

// Validate required fields (trim to avoid empty spaces)

     $certificationType = trim($_POST['certificationType'] ?? '');
    $first_name        = trim($_POST['first_name'] ?? '');
    $middle_name       = trim($_POST['middle_name'] ?? '');
    $last_name         = trim($_POST['last_name'] ?? '');
    $suffix            = trim($_POST['suffix'] ?? '');
    $address           = trim($_POST['address'] ?? '');
    $purpose           = trim($_POST['purpose'] ?? '');
    $other_purpose     = trim($_POST['other_purpose'] ?? '');
    $resident_status   = trim($_POST['resident_status'] ?? '');

    // ===== Step 1: Required fields =====
    
    
    
    if ($certificationType === '' || $first_name === '' || $last_name === '' || $address === '') {
        echo "❌ Please complete all required fields!";
        exit();
    }
    
    if ($certificationType === 'Calamity') {
        $purpose = 'Calamity';   // Always store as "Calamity"
        $other_purpose = 'N/A';  // Not applicable
    } elseif ($purpose === 'Other') {
        if ($other_purpose === '') {
            echo "❌ Please specify your purpose!";
            exit();
        }
        $purpose = $other_purpose;
    }

     //===== Step 2: Purpose-specific =====
     if ($certificationType !== 'Calamity' && $purpose === '') {
        echo "❌ Please select your purpose!";
        exit();
    }

    // ===== Step 3: Calamity-specific =====
    if ($certificationType === 'Calamity') {
        if (
            empty(trim($_POST['calamityType'] ?? '')) ||
            empty(trim($_POST['calamityDate'] ?? '')) ||
            empty(trim($_POST['requestedBy'] ?? '')) ||
            empty(trim($_POST['calamityNotes'] ?? ''))
        ) {
            echo "❌ Please complete all Calamity-related fields!";
            exit();
        }
    }

    // ===== Step 4: Other-purpose-specific =====
    // if ($purpose === 'Other' && $other_purpose === '') {
    //     echo "❌ Please specify your purpose!";
    //     exit();
    // }

    // ===== Step 5: Setup common data =====
    $dateToday = date('Y-m-d');
    $status = "To Be Approved";
    $name = $first_name . ' ' . ($middle_name ? $middle_name . ' ' : '') . $last_name . ($suffix ? ' ' . $suffix : '');

    // ===== Step 6: Calamity fields =====
    $type_of_calamity = $calamity_date = $requested_by = $calamity_notes = null;
    if ($purpose === 'Calamity') {
        $type_of_calamity = $_POST['calamityType'] ?? null;
        $calamity_date    = $_POST['calamityDate'] ?? null;
        $requested_by     = $_POST['requestedBy'] ?? null;
        $calamity_notes   = $_POST['calamityNotes'] ?? null;
    }

    // ===== Step 7: File upload handling =====
    $document_path = "";
    $uploaded_files = [];
    if (isset($_FILES['document_path']) && !empty($_FILES['document_path']['name'][0])) {
        $upload_dir = "../dist/assets/images/uploads/certification-documents/";
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

        foreach ($_FILES['document_path']['name'] as $key => $filename) {
            if ($_FILES['document_path']['error'][$key] === UPLOAD_ERR_OK) {
                $tmp_name = $_FILES['document_path']['tmp_name'][$key];
                $unique_name = time() . '_' . uniqid() . '_' . basename($filename);
                if (move_uploaded_file($tmp_name, $upload_dir . $unique_name)) {
                    $uploaded_files[] = $unique_name;
                }
            }
        }

        if (!empty($uploaded_files)) {
            $document_path = implode(",", $uploaded_files);
        }
    }

    // ===== Step 8: Final purpose =====
    $final_purpose = ($purpose === 'Other') ? $other_purpose : $purpose;

    // Insert query without calamity JSON
    $insertQuery = "INSERT INTO tbl_certification (
        res_id, user_id, certificationType, name, address, purpose, resident_status, dateToday,
        document_path, status, type_of_calamity, calamity_date, requested_by, calamity_notes
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $insertStmt = $conn->prepare($insertQuery);
    $insertStmt->bind_param(
        "iissssisssssss",  // Matching the parameters with the database columns
        $res_id,
        $user_id,
        $certificationType,
        $name,
        $address,
        $final_purpose,
        $resident_status,
        $dateToday,
        $document_path,
        $status,
        $type_of_calamity,
        $calamity_date,
        $requested_by,
        $calamity_notes
    );

    // Debugging: Output if the query will execute
    echo "Prepared Statement: " . $insertStmt->error . "<br>";

    // Execute query and check for errors
    if ($insertStmt->execute()) {
        header("Location: barangay-certificate.php?success=1");
        exit();
    } else {
        // Debugging: Output error if query fails
        echo "Error executing query: " . $conn->error . "<br>";
        header("Location: barangay-certificate.php?error=1&message=" . urlencode($conn->error));
        exit();
    }

    $insertStmt->close();
}
?>
