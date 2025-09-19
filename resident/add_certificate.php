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

    $certificationType = trim($_POST['certificationType'] ?? '');
    $first_name        = trim($_POST['first_name'] ?? '');
    $middle_name       = trim($_POST['middle_name'] ?? '');
    $last_name         = trim($_POST['last_name'] ?? '');
    $suffix            = trim($_POST['suffix'] ?? '');
    $address           = trim($_POST['address'] ?? '');
    $purpose           = trim($_POST['purpose'] ?? '');
    $other_purpose     = trim($_POST['other_purpose'] ?? '');
    $resident_status   = trim($_POST['resident_status'] ?? '');

    // ===== Step 1: General Required Fields =====
    if (empty($certificationType)) {
        echo "<script>
                alert('❌ Please select a Certificate Type.');
                history.back();
              </script>";
        exit();
    }

    if (empty($first_name)) {
        echo "<script>
                alert('❌ First Name is required.');
                history.back();
              </script>";
        exit();
    }

    if (empty($last_name)) {
        echo "<script>
                alert('❌ Last Name is required.');
                history.back();
              </script>";
        exit();
    }

    if (empty($address)) {
        echo "<script>
                alert('❌ Address is required.');
                history.back();
              </script>";
        exit();
    }

    // ===== Step 2: Purpose Handling =====
    if ($certificationType === 'Calamity') {
        $purpose = 'Calamity';   // Always store as "Calamity"
        $other_purpose = 'N/A';  // Not applicable
    } elseif ($purpose === 'Other') {
        if (empty($other_purpose)) {
            echo "<script>
                    alert('❌ Please specify your purpose since you selected Other.');
                    history.back();
                  </script>";
            exit();
        }
        $purpose = $other_purpose;
    }

    // Purpose required if not Calamity
    if ($certificationType !== 'Calamity' && empty($purpose)) {
        echo "<script>
                alert('❌ Please select your Purpose.');
                history.back();
              </script>";
        exit();
    }

    // ===== Step 3: Calamity-specific =====
if ($certificationType === 'Calamity') {
    $calamityType     = trim($_POST['calamityType'] ?? '');
    $calamityDate     = trim($_POST['calamityDate'] ?? '');
    $calamityTime     = trim($_POST['calamityTimeFire'] ?? '');
    $calamityLocation = trim($_POST['calamityLocation'] ?? '');
    $calamityCause    = trim($_POST['calamityCause'] ?? '');
    $requestedBy      = trim($_POST['requestedBy'] ?? '');
    $calamityPurpose  = trim($_POST['calamityPurpose'] ?? '');

    if (empty($calamityType)) {
        echo "<script>
                alert('❌ Please select the Type of Calamity.');
                history.back();
              </script>";
        exit();
    }

    if (empty($calamityDate)) {
        echo "<script>
                alert('❌ Please provide the Date of the Calamity.');
                history.back();
              </script>";
        exit();
    }

    if (empty($requestedBy)) {
        echo "<script>
                alert('❌ Please enter who requested the Calamity certificate.');
                history.back();
              </script>";
        exit();
    }

    // Fire-specific validations
    if ($calamityType === 'Fire') {
        if (empty($calamityTime)) {
            echo "<script>
                    alert('❌ Please provide the Time of the Fire incident.');
                    history.back();
                  </script>";
            exit();
        }
        if (empty($calamityLocation)) {
            echo "<script>
                    alert('❌ Please provide the Location of the Fire incident.');
                    history.back();
                  </script>";
            exit();
        }
        if (empty($calamityCause)) {
            echo "<script>
                    alert('❌ Please specify what the Fire incident caused.');
                    history.back();
                  </script>";
            exit();
        }
    }

    // Non-Fire calamities must have purpose
    if ($calamityType !== 'Fire' && empty($calamityPurpose)) {
        echo "<script>
                alert('❌ Please select a Purpose for your Calamity request.');
                history.back();
              </script>";
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

        // New fields
        $calamity_time    = $_POST['calamityTimeFire'] ?? null;
        $what_is_caused    = $_POST['calamityCaused'] ?? null;
        $location    = $_POST['calamityLocationFire'] ?? null;
        $calamity_purpose    = $_POST['calamityPurpose'] ?? null;
        
        

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
        document_path, status, type_of_calamity, calamity_date,calamity_time,what_is_caused,location,calamity_purpose, requested_by, calamity_notes
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?,?,?)";

    $insertStmt = $conn->prepare($insertQuery);
    $insertStmt->bind_param(
        "iissssisssssssssss",  
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
        $calamity_time,
        $what_is_caused,
        $location,
        $calamity_purpose,
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
