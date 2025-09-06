<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Check if the user is not logged in, redirect to login page

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

include '../connection/config.php';



// // Add this at the top of certificate.php, after the session_start() and before the HTML

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $certification_id = intval($_POST['certification_id']);
//     $status = $_POST['status'];
//     $remarks = $_POST['remarks'];

//     // Update status + remarks
//     $stmt = $conn->prepare("UPDATE tbl_certification SET status = ?, remarks = ? WHERE certification_id = ?");
//     $stmt->bind_param("ssi", $status, $remarks, $certification_id);
//     $updateResult = $stmt->execute();
//     $stmt->close();

//     if ($updateResult && strtolower($status) === 'approved') {
//         // Generate certificate immediately after successful update
//         $generateResult = generateCertificateFile($certification_id, $conn);
        
//         if ($generateResult['success']) {
//             header("Location: certificate.php?msg=approved_and_generated");
//         } else {
//             header("Location: certificate.php?msg=approved_but_generation_failed&error=" . urlencode($generateResult['error']));
//         }
//     } else {
//         header("Location: certificate.php?msg=updated");
//     }
//     exit;
// }

// // Add this function before your HTML starts
// function generateCertificateFile($certification_id, $conn) {
//     try {
//         // Get the certification data
//         $stmt = $conn->prepare("SELECT * FROM tbl_certification WHERE certification_id = ? AND status = 'Approved'");
//         $stmt->bind_param("i", $certification_id);
//         $stmt->execute();
//         $result = $stmt->get_result();
        
//         if ($result->num_rows === 0) {
//             return ['success' => false, 'error' => 'No approved certification found'];
//         }
        
//         $row = $result->fetch_assoc();
//         $type = $row['certificationType'];
//         $stmt->close();
        
//         // Certificate type mapping
//         $certificateTypes = [
//             'Good Moral' => [
//                 'template' => 'temp-gm.jpg',
//                 'folder' => 'GoodMoralCert',
//                 'name_pos' => [550, 680],
//                 'address_pos' => [750, 730],
//                 'other_purpose_pos' => [1110, 1650],
//                 'checkbox_positions' => [
//                     'Local Employment' => [250, 1075],
//                     'PWD ID' => [250, 1130],
//                     'Hospital Requirement' => [250, 1185],
//                     'Transfer Residency' => [250, 1240],
//                     'Bank Transaction' => [250, 1295],
//                     'Proof Of Indigency' => [250, 1350],
//                     'Financial Assistance' => [950, 1075],
//                     'Maynilad Requirement' => [950, 1130],
//                     'School Requirement' => [950, 1180],
//                     'Proof Of Residency' => [950, 1240],
//                     'Medical Assistance' => [950, 1295],
//                 ]
//             ],
//             'First Time Job Seeker' => [
//                 'template' => 'temp-ftjs.jpg',
//                 'folder' => 'FirstTimeJobSeekerCert',
//                 'name_pos' => [550, 680],
//                 'address_pos' => [750, 730]
//             ],
//             'Calamity' => [
//                 'template' => 'temp-calamity.jpg',
//                 'folder' => 'CalamityCert',
//                 'name_pos' => [550, 680],
//                 'address_pos' => [750, 730]
//             ]
//         ];
        
//         if (!array_key_exists($type, $certificateTypes)) {
//             return ['success' => false, 'error' => 'Invalid certificate type'];
//         }
        
//         $data = $certificateTypes[$type];
//         $templatePath = __DIR__ . '/generate_certificate/templates/' . $data['template'];
//         $savePath = __DIR__ . '/generate_certificate/' . $data['folder'];
//         $font = __DIR__ . '/generate_certificate/fonts/TimesNewRoman.ttf';
//         $checkmarkImagePath = __DIR__ . '/generate_certificate/icons/checkmark.png';
        
//         // Validate required files
//         if (!file_exists($templatePath)) {
//             return ['success' => false, 'error' => "Template not found: {$data['template']}"];
//         }
        
//         if (!file_exists($font)) {
//             return ['success' => false, 'error' => 'Font file not found'];
//         }
        
//         // Create directory if it doesn't exist
//         if (!is_dir($savePath)) {
//             if (!mkdir($savePath, 0777, true)) {
//                 return ['success' => false, 'error' => 'Failed to create directory'];
//             }
//         }
        
//         // Load template image
//         $image = imagecreatefromjpeg($templatePath);
//         if (!$image) {
//             return ['success' => false, 'error' => 'Failed to load template image'];
//         }
        
//         $black = imagecolorallocate($image, 0, 0, 0);
//         $name = $row['name'];
//         $address = $row['address'];
//         $purpose = trim($row['purpose']);
        
//         // Draw name and address
//         if (isset($data['name_pos'])) {
//             imagettftext($image, 20, 0, $data['name_pos'][0], $data['name_pos'][1], $black, $font, $name);
//         }
//         if (isset($data['address_pos'])) {
//             imagettftext($image, 20, 0, $data['address_pos'][0], $data['address_pos'][1], $black, $font, $address);
//         }
        
//         // Handle checkboxes for Good Moral certificates
//         if ($type === 'Good Moral' && isset($data['checkbox_positions'])) {
//             $matchedKey = null;
//             foreach ($data['checkbox_positions'] as $key => $coords) {
//                 if (strcasecmp($key, $purpose) === 0) {
//                     $matchedKey = $key;
//                     break;
//                 }
//             }
            
//             if ($matchedKey && file_exists($checkmarkImagePath)) {
//                 [$x, $y] = $data['checkbox_positions'][$matchedKey];
//                 $checkmark = imagecreatefrompng($checkmarkImagePath);
//                 if ($checkmark) {
//                     imagealphablending($image, true);
//                     imagesavealpha($image, true);
//                     $resized = imagescale($checkmark, 30, 30);
//                     imagecopy($image, $resized, $x, $y, 0, 0, 30, 30);
//                     imagedestroy($checkmark);
//                     imagedestroy($resized);
//                 }
//             } elseif (!empty($purpose) && isset($data['other_purpose_pos'])) {
//                 imagettftext($image, 20, 0, $data['other_purpose_pos'][0], $data['other_purpose_pos'][1], $black, $font, $purpose);
//             }
//         }
        
//         // Generate filename and save
//         $safeName = preg_replace('/\s+/', '_', $name);
//         $filename = $savePath . '/' . $safeName . '_' . $certification_id . '_' . time() . '.jpg';
        
//         $saveResult = imagejpeg($image, $filename, 90); // 90% quality
//         imagedestroy($image);
        
//         if (!$saveResult) {
//             return ['success' => false, 'error' => 'Failed to save certificate image'];
//         }
        
//         // Verify file was created
//         if (!file_exists($filename)) {
//             return ['success' => false, 'error' => 'Certificate file was not created'];
//         }
        
//         return ['success' => true, 'filename' => basename($filename)];
        
//     } catch (Exception $e) {
//         return ['success' => false, 'error' => 'Exception: ' . $e->getMessage()];
//     }
// }





// Fetch the user's data from the tbl_user table based on the user ID
$id = $_SESSION['user_id'];
$sql = "SELECT * FROM tbl_user WHERE user_id = '$id'";
$result = $conn->query($sql);

// Initialize the variables with default values
$image = "default_image.jpg"; // Assuming default image name
$first_name = "";
$middle_name = "";
$last_name = "";
$address = "";
$email = "";
$is_logged_in = 0;
$account_status = "";
$profile_title = "My Profile";

// Check if the query was successful and populate variables
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $image = $row["image"] ?? "default_image.jpg";
    $first_name = $row["first_name"];
    $middle_name = $row["middle_name"];
    $last_name = $row["last_name"];
    $address = $row["address"];
    $email = $row["email"];
    $is_logged_in = $row['is_logged_in'];
    $account_status = $row["account_status"];
}

// Assign the value of $username and other variables to $_SESSION variables
$_SESSION['image'] = $image;
$_SESSION['is_logged_in'] = $is_logged_in;
$_SESSION['full_name'] = $first_name . ' ' . $last_name;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Certificate Request | Barangay System</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="../dist/assets/vendors/feather/feather.css">
    <link rel="stylesheet" href="../dist/assets/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="../dist/assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="../dist/assets/vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../dist/assets/vendors/mdi/css/materialdesignicons.min.css">

     <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!--FONTAWESOME CSS-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="../dist/assets/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" type="text/css" href="../dist/assets/js/select.dataTables.min.css">
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="../dist/assets/css/style.css">
    <!-- endinject -->
    <link rel="shortcut icon" href="../dist/assets/images/logos.png" />

</head>

<body>
    <div class="container-scroller">

    </div>
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
            <a class="navbar-brand brand-logo me-5" href="index.php"><img src="../dist/assets/images/logos.png"
                    class="me-2" alt="logo" /></a>
            <a class="navbar-brand brand-logo-mini" href="index.php"><img src="../dist/assets/images/logos.png"
                    alt="logo" /></a>
        </div>
        <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
            <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                <span class="icon-menu"></span>
            </button>


            <ul class="navbar-nav navbar-nav-right">

                <li class="nav-item nav-profile dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" id="profileDropdown">
                        <img src="../dist/assets/images/user/<?php echo $_SESSION['image']; ?>" alt="profile" />
                    </a>
                    <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                        <a class="dropdown-item" href="profile-management.php">
                            <i class="ti-user text-primary"></i> Profile Management</a>
                        <a class="dropdown-item" href="logout.php">
                            <i class="ti-power-off text-primary"></i> Logout </a>
                    </div>
                </li>

            </ul>
            <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
                data-toggle="offcanvas">
                <span class="icon-menu"></span>
            </button>
        </div>
    </nav>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_sidebar.html -->
        <style>
            .nav-link i {
                margin-right: 10px;
            }
        </style>
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
            <ul class="nav">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">
                        <i class="fa-solid fa-gauge"></i>
                        <span class="menu-title">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="services.php">
                        <i class="fa-solid fa-s"></i>
                        <span class="menu-title">Services</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#requestManagement" aria-expanded="false"
                        aria-controls="requestManagement">
                        <i class="fa-solid fa-registered"></i>
                        <span class="menu-title">Request</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="collapse" id="requestManagement">
                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item"> <a class="nav-link" href="certificate.php"> Certificate Request </a></li>
                            <li class="nav-item"> <a class="nav-link" href="clearance.php"> Clearance Request </a></li>
                            <li class="nav-item"> <a class="nav-link" href="barangayid.php"> Barangay ID Request </a></li>
                            <li class="nav-item"> <a class="nav-link" href="blotter.php"> Blotter Request </a></li>
                            <li class="nav-item"> <a class="nav-link" href="complains.php"> Complains Request </a></li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="officials.php">
                        <i class="fa-solid fa-user-tie"></i>
                        <span class="menu-title">Officials</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="audit.php">
                        <i class="fa-solid fa-clock"></i>
                        <span class="menu-title">Audit</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="population.php">
                        <i class="fa-solid fa-rectangle-list"></i>
                        <span class="menu-title">Populations</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="residents.php">
                        <i class="fa-solid fa-user"></i>
                        <span class="menu-title">Residents</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="events.php">
                        <i class="fa-solid fa-calendar"></i>
                        <span class="menu-title">Events</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="cms-dashboard.php">
                        <i class="fa-solid fa-desktop"></i>
                        <span class="menu-title">Manage Website</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="feedback.php">
                        <i class="fa-solid fa-comments"></i>
                        <span class="menu-title">Feedback Reports</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#settingsManagement" aria-expanded="false"
                        aria-controls="settingsManagement">
                        <i class="fa-solid fa-gear"></i>
                        <span class="menu-title">Settings</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="collapse" id="settingsManagement">
                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item"> <a class="nav-link" href="profile-management.php">Profile
                                    Management</a></li>
                            <li class="nav-item"> <a class="nav-link" href="create_super_admin.php"onclick="return confirm('Are you sure you want to create/reset the Super Admin account?');">Create/reset default admin</a></li>
                            <li class="nav-item"> <a class="nav-link" href="logout.php">Logout</a></li>
                        </ul>
                    </div>
                </li>
            </ul>
        </nav>
        <!-- partial -->
        <div class="main-panel">
            <div class="content-wrapper">
                <div class="row">
                    <div class="col-md-12 grid-margin">
                        <div class="row">

                        </div>
                    </div>
                </div>
                <!-- Request Certificate Modal -->
                <div class="modal fade" id="CertificateModal" tabindex="-1" role="dialog"
                    aria-labelledby="CertificateModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="CertificateModalLabel">Request Certificate</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="add_certificate.php" method="POST" enctype="multipart/form-data" novalidate
                                id="certificateRequestForm" class="needs-validation">
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="certificationType">Certificate Type</label>
                                        <div class="input-group">
                                            <select class="form-control" id="certificationType" name="certificationType"
                                                required>
                                                <option value="">Select</option>
                                                <option value="Good Moral">Good Moral Character</option>
                                                <option value="First Time Job Seeker">First Time Job Seeker</option>
                                                <option value="Calamity">Calamity</option>
                                            </select>
                                            <div class="input-group-append">
                                                <span class="input-group-text validation-icon"
                                                    id="certificationType_validation">
                                                    <i class="fas fa-check text-success d-none"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="calamityDetails" class="border rounded p-3 mt-3 hidden-section bg-light">
                                        <div class="form-group">
                                            <label for="calamityType">Type of Calamity</label>
                                            <input type="text" class="form-control" id="calamityType" name="calamityType" placeholder="e.g., Typhoon, Earthquake" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="calamityDate">When did it happen?</label>
                                            <input type="date" class="form-control" id="calamityDate" name="calamityDate" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="requestedBy">Requested By</label>
                                            <input type="text" class="form-control" id="requestedBy" name="requestedBy" placeholder="Name of person requesting" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="calamityNotes">Additional Notes (Optional)</label>
                                            <textarea class="form-control" id="calamityNotes" name="calamityNotes" rows="3" placeholder="Any other relevant information"></textarea>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <!-- BarangayID Input Field -->
                                        <div class="form-group">
                                            <label for="barangay_id">Barangay ID</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="barangay_id" name="barangay_id" required>
                                                <div class="input-group-append">
                                                    <span class="input-group-text validation-icon" id="barangay_id_validation">
                                                        <i class="fas fa-check text-success d-none"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Hidden fields to store resident information -->
                                        <input type="hidden" id="user_id" name="user_id">
                                        <input type="hidden" id="first_name" name="first_name">
                                        <input type="hidden" id="last_name" name="last_name">
                                        <input type="hidden" id="middle_name" name="middle_name">
                                    </div>

                                    
                                    <div class="form-group">
                                        <label for="address">Address</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="address" name="address"
                                                placeholder="Enter Address" required>
                                            <div class="input-group-append">
                                                <span class="input-group-text validation-icon" id="address_validation">
                                                    <i class="fas fa-check text-success d-none"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="purpose">Purpose</label>
                                        <div class="input-group">
                                            <select class="form-control" id="purpose" name="purpose" required>
                                                <option value="">Select</option>
                                                <option value="Local Employment">Local Employment</option>
                                                <option value="PWD ID">ID for PWD</option>
                                                <option value="Hospital Requirement">Hospital Requirement/Bill</option>
                                                <option value="Transfer Residency">Transfer Residency</option>
                                                <option value="Bank Transaction">Bank Transaction</option>
                                                <option value="Proof Of Indigency">Proof Of Indigency</option>
                                                <option value="Financial Assistance">Financial Assistance</option>
                                                <option value="Maynilad Requirement">Maynila Requirement</option>
                                                <option value="School Requirement">School Requirement</option>
                                                <option value="Proof Of Residency">Proof Of Residency</option>
                                                <option value="Medical Assistance">Medical Assistance</option>
                                                <option value="Other">Other</option>
                                            </select>
                                            <div class="input-group-append">
                                                <span class="input-group-text validation-icon" id="purpose_validation">
                                                    <i class="fas fa-check text-success d-none"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- This input appears only when "Other" is selected -->
                                    <div class="form-group d-none" id="other_purpose_wrapper">
                                        <label for="other_purpose">Please specify your purpose</label>
                                        <input type="text" class="form-control" id="other_purpose" name="other_purpose" placeholder="Type your purpose here">
                                    </div>

                                    

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="date_time_applied">Date and Time Applied</label>
                                                <div class="input-group">
                                                    <input type="datetime-local" class="form-control"
                                                        id="date_time_applied" name="date_time_applied" required>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text validation-icon"
                                                            id="date_time_applied_validation">
                                                            <i class="fas fa-check text-success d-none"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>

                                    <!-- Document Upload Section -->
                                    <div class="mb-4">
                                        <h5 class="border-bottom pb-2"><i class="fas fa-file-upload me-2"></i>Supporting
                                            Documents</h5>
                                        <div class="file-upload">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                            <h5>Upload Supporting Document/Image</h5>
                                            <p>Please upload any required documents (Valid ID, Proof of Residency, etc.)
                                            </p>
                                            <input type="file" class="form-control" id="document_path"
                                                name="document_path">
                                            <div class="file-upload-info mt-2">
                                                <small><i class="fas fa-info-circle me-1"></i>Accepted formats: PDF,
                                                    JPG, PNG (Max size: 5MB)</small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Terms and Submit -->
                                    <div class="mb-3 d-flex justify-content-center">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="terms" required>
                                            <label class="form-check-label" for="terms">
                                                I confirm that the information provided is accurate and complete
                                            </label>
                                            <div class="invalid-feedback">
                                                You must agree before submitting.
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Submit Request</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        // Form validation
                        (function () {
                            'use strict';

                            // Fetch all forms to apply validation
                            var forms = document.querySelectorAll('.needs-validation');

                            // Loop and prevent submission
                            Array.prototype.slice.call(forms).forEach(function (form) {
                                form.addEventListener('submit', function (event) {
                                    if (!form.checkValidity()) {
                                        event.preventDefault();
                                        event.stopPropagation();
                                    }
                                    form.classList.add('was-validated');
                                }, false);
                            });
                        })();



                        // Display file name when selected
                        document.getElementById('document_path').addEventListener('change', function () {
                            var fileName = this.files[0]?.name;
                            if (fileName) {
                                var fileInfo = document.createElement('p');
                                fileInfo.className = 'mt-2 mb-0';
                                fileInfo.innerHTML = '<i class="fas fa-check-circle text-success me-2"></i>Selected file: <strong>' + fileName + '</strong>';

                                // Remove previous file info if exists
                                var existingInfo = document.querySelector('.file-selected-info');
                                if (existingInfo) {
                                    existingInfo.remove();
                                }

                                fileInfo.className += ' file-selected-info';
                                this.parentNode.appendChild(fileInfo);
                            }
                        });
                    });
                </script>
                <?php
                include '../connection/config.php';

                // Check for success messages
                if (isset($_GET['success'])) {
                    $successMessages = [
                        1 => "Certificate Request Submitted Successfully",
                        2 => "Certificate Request Updated Successfully"
                    ];

                    if (isset($successMessages[$_GET['success']])) {
                        echo '<script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                icon: "success",
                title: "' . $successMessages[$_GET['success']] . '",
                showConfirmButton: false,
                timer: 1500
            });
        });
        </script>';
                    }
                }

                // Check for error messages
                if (isset($_GET['error'])) {
                    echo '<script>
    document.addEventListener("DOMContentLoaded", function() {
        Swal.fire({
            icon: "error",
            title: "Error",
            text: "Something went wrong. Please try again.",
            showConfirmButton: true
        });
    });
    </script>';
                }

                // Initialize variables
                $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
                $search = $_GET['search'] ?? '';
                $page = $_GET['page'] ?? 1;
                $limit = 10;
                $offset = ($page - 1) * $limit;


                // Date filter variables
                $date_from = $_GET['date_from'] ?? '';
                $date_to = $_GET['date_to'] ?? '';

                // Build WHERE clause based on search
                $where_conditions = [];
                $params = [];
                $types = "";

                // Get all column names from the tbl_certification table for search
                $columnsQuery = "SHOW COLUMNS FROM tbl_certification";
                $columnsResult = $conn->query($columnsQuery);
                $searchFields = [];

                if ($columnsResult) {
                    while ($column = $columnsResult->fetch_assoc()) {
                        $searchFields[] = "c." . $column['Field'];
                    }
                }

                if (!empty($search) && !empty($searchFields)) {
                    $searchConditions = [];
                    foreach ($searchFields as $field) {
                        $searchConditions[] = "$field LIKE ?";
                        $params[] = "%" . $search . "%";
                        $types .= "s";
                    }
                    $where_conditions[] = "(" . implode(" OR ", $searchConditions) . ")";
                }

                // Combine WHERE conditions
                $where_clause = !empty($where_conditions) ? implode(" AND ", $where_conditions) : "1=1"; // 1=1 ensures valid SQL if no conditions
                
                // Count total records for pagination
                $count_sql = "SELECT COUNT(*) as total FROM tbl_certification c WHERE $where_clause";

                if (!empty($params)) {
                    $count_stmt = $conn->prepare($count_sql);
                    $count_stmt->bind_param($types, ...$params);
                    $count_stmt->execute();
                    $count_result = $count_stmt->get_result();
                    $total_rows = $count_result->fetch_assoc()['total'];
                    $count_stmt->close();
                } else {
                    $count_result = $conn->query($count_sql);
                    $total_rows = $count_result->fetch_assoc()['total'];
                }

                $total_pages = ceil($total_rows / $limit);

                // Fetch certification requests query
                $sql = "SELECT c.certification_id, c.res_id, c.user_id, c.name, 
        c.address, c.purpose, c.registeredVoter, c.resident_status, 
        c.dateApplied, c.document_path, c.certificationType,
        c.status, c.created_at, c.remarks
        FROM tbl_certification c
        WHERE $where_clause
        ORDER BY c.created_at DESC 
        LIMIT ? OFFSET ?";

                // Add limit and offset params
                $params[] = $limit;
                $params[] = $offset;
                $types .= "ii";

                // Prepare and execute statement
                $stmt = $conn->prepare($sql);
                if (!empty($params)) {
                    $stmt->bind_param($types, ...$params);
                }
                $stmt->execute();
                $result = $stmt->get_result();
                $stmt->close();
                ?>

                <div class="row">
                    <div class="col-md-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <!-- Filter section for request button and validation -->
                                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                                    <p class="card-title mb-0">Barangay Certificate Requests</p>
                                    <div class="ml-auto">
                                    <!--<button class="btn btn-primary mb-3" data-toggle="modal"
                                    data-target="#CertificateModal">Request</button>-->
                                    </div>
                                </div>


                                
                                <!-- Filter section -->
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <form method="GET" action="" class="form-inline" id="searchForm">
                                            <div class="input-group mb-2 mr-sm-2">
                                                <input type="text" name="search" id="searchInput" class="form-control"
                                                    value="<?php echo htmlspecialchars($search); ?>"
                                                    placeholder="Search Certification Request">
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-outline-secondary"
                                                        id="clearButton" style="padding:10px;">&times;</button>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-primary mb-2">Search</button>
                                        </form>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-striped table-borderless" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Certificate ID</th>
                                                <th>Name</th>
                                                <th>Certification Type</th>
                                                <th>Purpose</th>
                                                <th>Date Applied</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                            if ($result->num_rows > 0): ?>
                                                <?php while ($row = $result->fetch_assoc()): ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($row['certification_id']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['certificationType']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['purpose']); ?></td>
                                                        <td><?php echo date('F d, Y h:i A', strtotime($row['dateApplied'])); ?>
                                                        </td>
                                                        <td>
                                                            <span class="badge 
                                                                <?php
                                                                if ($row['status'] == 'Approved')
                                                                    echo 'badge-warning text-white font-weight-bold';
                                                                elseif ($row['status'] == 'On Going')
                                                                    echo 'badge-info text-white font-weight-bold';
                                                                elseif ($row['status'] == 'Denied')
                                                                    echo 'badge-danger text-white font-weight-bold';
                                                                elseif ($row['status'] == 'Resubmit')
                                                                    echo 'badge-secondary text-white font-weight-bold';
                                                                elseif ($row['status'] == 'Completed')
                                                                    echo 'badge-success text-white font-weight-bold';
                                                                else
                                                                    echo 'badge-warning text-white font-weight-bold';
                                                                ?>">
                                                                <?php echo htmlspecialchars(ucfirst($row['status'])); ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <!-- View button for all positions -->
                                                            <?php if ($row['status'] === 'Approved'): ?>
                                                                <button class="btn btn-info btn-sm" data-toggle="modal" title="View"
                                                                    data-target="#viewModal<?php echo $row['certification_id']; ?>">
                                                                    <i class="fa-solid fa-eye"></i>
                                                                </button>
                                                            <?php endif; ?>

                                                            <!-- Edit/Update button only for Barangay Secretary -->
                                                          
                                                            <button class="btn btn-warning btn-sm" data-toggle="modal" title="Edit"
                                                                data-target="#editModal<?php echo $row['certification_id']; ?>">
                                                                <i class="fa-solid fa-edit"></i>
                                                            </button>
                                                        </td>
                                                    </tr>

                                                    <!-- View Modal -->
                                                    <?php if ($row['status'] === 'Approved'): ?>
                                                    <div class="modal fade" id="viewModal<?php echo $row['certification_id']; ?>" tabindex="-1"
                                                        role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg" role="document">
                                                            <div class="modal-content shadow-lg border-0">
                                                                <div class="modal-header bg-gradient-warning text-white py-3">
                                                                    <h5 class="modal-title font-weight-bold" id="editModalLabel">
                                                                        <i class="fas fa-edit mr-2"></i> View Certificate
                                                                    </h5>
                                                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                    
                                                                <div class="modal-body text-center">
                                                                    <?php
                                                                    $certification_id = $row['certification_id'];
                                                                    $type = $row['certificationType'];
                                                    
                                                                    $folderMap = [
                                                                        'Good Moral' => 'GoodMoralCert',
                                                                        'First Time Job Seeker' => 'FirstTimeJobSeekerCert',
                                                                        'Calamity' => 'CalamityCert'
                                                                    ];
                                                    
                                                                    if (isset($folderMap[$type])) {
                                                                        $folder = $folderMap[$type];
                                                                        $relativePath = "generate_certificate/$folder";
                                                                        $absolutePath = __DIR__ . "/$relativePath";
                                                    
                                                                        if (is_dir($absolutePath)) {
                                                                            // Multiple glob patterns to catch different naming conventions
                                                                            $patterns = [
                                                                                "$absolutePath/*_{$certification_id}_*.jpg",
                                                                                "$absolutePath/*{$certification_id}*.jpg",
                                                                                "$absolutePath/{$certification_id}_*.jpg"
                                                                            ];
                                                                            
                                                                            $images = [];
                                                                            foreach ($patterns as $pattern) {
                                                                                $found = glob($pattern, GLOB_BRACE);
                                                                                if (!empty($found)) {
                                                                                    $images = array_merge($images, $found);
                                                                                }
                                                                            }
                                                                            
                                                                            // Remove duplicates
                                                                            $images = array_unique($images);
                                                    
                                                                            if (!empty($images)) {
                                                                                // Sort by file modification time (descending - newest first)
                                                                                usort($images, function($a, $b) {
                                                                                    return filemtime($b) - filemtime($a);
                                                                                });
                                                                                $latestImage = $images[0];
                                                                                $filename = basename($latestImage);
                                                                                ?>
                                                    
                                                                                <div class="certificate-container mb-3">
                                                                                    <img src="<?php echo $relativePath . '/' . $filename; ?>" 
                                                                                         class="img-fluid border shadow-sm" 
                                                                                         style="max-height: 600px; max-width: 100%;"
                                                                                         alt="Certificate for <?php echo htmlspecialchars($row['name']); ?>">
                                                                                </div>
                                                                                
                                                                                <div class="certificate-actions">
                                                                                    <a href="<?php echo $relativePath . '/' . $filename; ?>" 
                                                                                       download="Certificate_<?php echo $certification_id; ?>.jpg" 
                                                                                       class="btn btn-success btn-lg">
                                                                                        <i class="fas fa-download mr-2"></i> Download Certificate
                                                                                    </a>

                                                                                     <button type="button" 
                                                                                            class="btn btn-primary generate-cert btn-lg" 
                                                                                            data-id="<?php echo $certification_id; ?>">
                                                                                        <i class="fas fa-redo mr-2"></i>Re Generate Certificate Now
                                                                                    </button>
                                                                                    
                                                                                    <a href="<?php echo $relativePath . '/' . $filename; ?>" 
                                                                                       target="_blank" 
                                                                                       class="btn btn-info btn-lg ml-2">
                                                                                        <i class="fas fa-external-link-alt mr-2"></i> Open in New Tab
                                                                                    </a>
                                                                                </div>
                                                    
                                                                                <div class="mt-3">
                                                                                    <small class="text-muted">
                                                                                        Certificate generated on: <?php echo date('F d, Y h:i A', filemtime($latestImage)); ?>
                                                                                    </small>
                                                                                </div>
                                                    
                                                                                <?php
                                                                            } else {
                                                                                ?>
                                                                                <div class="alert alert-warning">
                                                                                    <h5><i class="fas fa-exclamation-triangle mr-2"></i> Certificate Not Generated</h5>
                                                                                    <p>The certificate for this request has not been generated yet.</p>
                                                                                    <p><strong>Certification ID:</strong> <?php echo $certification_id; ?><br>
                                                                                       <strong>Type:</strong> <?php echo htmlspecialchars($type); ?><br>
                                                                                       <strong>Status:</strong> <?php echo htmlspecialchars($row['status']); ?></p>
                                                                                    
                                                                                    <button type="button" 
                                                                                            class="btn btn-primary generate-cert" 
                                                                                            data-id="<?php echo $certification_id; ?>">
                                                                                        <i class="fas fa-redo mr-2"></i> Generate Certificate Now
                                                                                    </button>

                                                                                </div>
                                                                                <?php
                                                                            }
                                                                        } else {
                                                                            ?>
                                                                            <div class="alert alert-danger">
                                                                                <h5><i class="fas fa-folder-open mr-2"></i> Directory Not Found</h5>
                                                                                <p>The certificate directory does not exist: <code><?php echo htmlspecialchars($relativePath); ?></code></p>
                                                                                <p>Please ensure the certificate generation system is properly configured.</p>
                                                                            </div>
                                                                            <?php
                                                                        }
                                                                    } else {
                                                                        ?>
                                                                        <div class="alert alert-danger">
                                                                            <h5><i class="fas fa-times-circle mr-2"></i> Invalid Certificate Type</h5>
                                                                            <p>Unknown certificate type: <strong><?php echo htmlspecialchars($type); ?></strong></p>
                                                                            <p>Supported types: Good Moral, First Time Job Seeker, Calamity</p>
                                                                        </div>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </div>
                                                    
                                                                <div class="modal-footer bg-light">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                                        <i class="fas fa-times mr-1"></i> Close
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php endif; ?>

                                                   


                                                    <!-- Edit Modal for Barangay Secretary only -->
                                                    <div class="modal fade"
                                                        id="editModal<?php echo $row['certification_id']; ?>" tabindex="-1"
                                                        role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg" role="document">
                                                            <div class="modal-content shadow-lg border-0">
                                                                <div class="modal-header bg-gradient-warning text-white py-3">
                                                                    <h5 class="modal-title font-weight-bold"
                                                                        id="editModalLabel">
                                                                        <i class="fas fa-edit mr-2"></i>Update Certificate
                                                                        Request
                                                                    </h5>
                                                                    <button type="button" class="close text-white"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body py-4">
                                                                    <h5 class="modal-title font-weight-bold"
                                                                        id="viewModalLabel">
                                                                        <i class="fas fa-file-alt mr-2"></i>Certificate Request
                                                                        Details
                                                                    </h5>
                                                                </div>

    
                                                                <div class="modal-body py-4">
                                                                    <!-- Status badge at top -->
                                                                    <div class="text-center mb-4">
                                                                        <span class="badge badge-pill px-4 py-2 font-weight-bold text-white
                                                                        <?php
                                                                        if ($row['status'] == 'Approved')
                                                                            echo 'badge-warning';
                                                                        elseif ($row['status'] == 'On Going')
                                                                            echo 'badge-info';
                                                                        elseif ($row['status'] == 'Denied')
                                                                            echo 'badge-danger';
                                                                        elseif ($row['status'] == 'Resubmit')
                                                                            echo 'badge-secondary';
                                                                        elseif ($row['status'] == 'Completed')
                                                                            echo 'badge-primary';
                                                                        else
                                                                            echo 'badge-warning';
                                                                        ?>">
                                                                            <i class="fas 
                                                                            <?php
                                                                            if ($row['status'] == 'Approved')
                                                                                echo 'fa-check-circle';
                                                                            elseif ($row['status'] == 'On Going')
                                                                                echo 'fa-clock';
                                                                            elseif ($row['status'] == 'Denied')
                                                                                echo 'fa-times-circle';
                                                                            elseif ($row['status'] == 'Resubmit')
                                                                                echo 'fa-redo';
                                                                            elseif ($row['status'] == 'Completed')
                                                                                echo 'fa-check-double';
                                                                            else
                                                                                echo 'fa-exclamation-circle';
                                                                            ?> mr-1"></i>
                                                                            <?php echo htmlspecialchars(ucfirst($row['status'])); ?>
                                                                        </span>
                                                                    </div>

                                                                    <!-- Card container for details -->
                                                                    <div class="card border-0 shadow-sm mb-4">
                                                                        <div class="card-header bg-light py-3">
                                                                            <h6 class="font-weight-bold text-primary mb-0">
                                                                                <i class="fas fa-user mr-2"></i>Personal
                                                                                Information
                                                                            </h6>
                                                                        </div>
                                                                        <div class="card-body">
                                                                            <div class="row">
                                                                                <div class="col-md-6">
                                                                                    <div class="info-group mb-3">
                                                                                        <label
                                                                                            class="text-muted small text-uppercase">Certification
                                                                                            ID</label>
                                                                                        <p class="font-weight-bold mb-2">
                                                                                            <?php echo htmlspecialchars($row['certification_id']); ?>
                                                                                        </p>
                                                                                    </div>
                                                                                    <div class="info-group mb-3">
                                                                                        <label
                                                                                            class="text-muted small text-uppercase">Name</label>
                                                                                        <p class="font-weight-bold mb-2">
                                                                                            <?php echo htmlspecialchars($row['name']); ?>
                                                                                        </p>
                                                                                    </div>
                                                                                    <div class="info-group mb-3">
                                                                                        <label
                                                                                            class="text-muted small text-uppercase">Certification
                                                                                            Type</label>
                                                                                        <p class="font-weight-bold mb-2">
                                                                                            <?php echo htmlspecialchars($row['certificationType']); ?>
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    
                                                                                    <div class="info-group mb-3">
                                                                                        <label
                                                                                            class="text-muted small text-uppercase">Date
                                                                                            Applied</label>
                                                                                        <p class="font-weight-bold mb-2">
                                                                                            <?php echo date('F d, Y h:i A', strtotime($row['dateApplied'])); ?>
                                                                                        </p>
                                                                                    </div>
                                                                                    <div class="info-group mb-3">
                                                                                        <label
                                                                                            class="text-muted small text-uppercase">Registered
                                                                                            Voter</label>
                                                                                        <p class="font-weight-bold mb-2">
                                                                                            <?php echo ($row['registeredVoter'] == 1) ? 'Yes' : 'No'; ?>
                                                                                        </p>
                                                                                    </div>
                                                                                    <div class="info-group mb-3">
                                                                                        <label
                                                                                            class="text-muted small text-uppercase">Resident
                                                                                            Status</label>
                                                                                        <p class="font-weight-bold mb-2">
                                                                                            <?php echo ($row['resident_status'] == 1) ? 'Yes' : 'No'; ?>
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Address and Purpose Card -->
                                                                    <div class="card border-0 shadow-sm mb-4">
                                                                        <div class="card-header bg-light py-3">
                                                                            <h6 class="font-weight-bold text-primary mb-0">
                                                                                <i
                                                                                    class="fas fa-info-circle mr-2"></i>Additional
                                                                                Information
                                                                            </h6>
                                                                        </div>
                                                                        <div class="card-body">
                                                                            <div class="row">
                                                                                <div class="col-md-6">
                                                                                    <div class="info-group mb-3">
                                                                                        <label
                                                                                            class="text-muted small text-uppercase">Address</label>
                                                                                        <p class="font-weight-bold mb-2">
                                                                                            <?php echo htmlspecialchars($row['address']); ?>
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <div class="info-group mb-3">
                                                                                        <label
                                                                                            class="text-muted small text-uppercase">Purpose</label>
                                                                                        <p class="font-weight-bold mb-2">
                                                                                            <?php echo htmlspecialchars($row['purpose']); ?>
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Remarks Section (if available) -->
                                                                    <?php if (!empty($row['remarks'])): ?>
                                                                        <div class="card border-0 shadow-sm mb-4">
                                                                            <div class="card-header bg-light py-3">
                                                                                <h6 class="font-weight-bold text-primary mb-0">
                                                                                    <i class="fas fa-comment-alt mr-2"></i>Remarks
                                                                                </h6>
                                                                            </div>
                                                                            <div class="card-body">
                                                                                <p><?php echo nl2br(htmlspecialchars($row['remarks'])); ?>
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                    <?php endif; ?>

                                                                    <!-- Document Preview Section -->
                                                                    <?php
                                                                        // Assuming $row['document_path'] contains comma-separated file paths
                                                                        $document_paths = explode(',', $row['document_path']);
                                                                        
                                                                        if (!empty($document_paths)): ?>
                                                                            <div class="card border-0 shadow-sm">
                                                                                <div class="card-header bg-light py-3">
                                                                                    <h6 class="font-weight-bold text-primary mb-0">
                                                                                        <i class="fas fa-file-alt mr-2"></i>Supporting Documents
                                                                                    </h6>
                                                                                </div>
                                                                                <div class="card-body">
                                                                                    <div class="row">
                                                                                        <?php foreach ($document_paths as $path):
                                                                                            $path = trim($path);
                                                                                            $file_ext = strtolower(pathinfo($path, PATHINFO_EXTENSION)); ?>
                                                                                            
                                                                                            <div class="col-md-4 mb-4 text-center">
                                                                                                <?php if (in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                                                                                                    <div class="img-container border p-2 mb-2">
                                                                                                        <img src="../dist/assets/images/uploads/certification-documents/<?php echo $path; ?>"
                                                                                                             class="img-fluid" alt="Document">
                                                                                                    </div>
                                                                                                    <a href="../dist/assets/images/uploads/certification-documents/<?php echo $path; ?>"
                                                                                                       target="_blank" class="btn btn-sm btn-outline-primary">
                                                                                                        <i class="fas fa-external-link-alt mr-1"></i> View Full Size
                                                                                                    </a>
                                                                                                <?php else: ?>
                                                                                                    <div class="border p-4">
                                                                                                        <div class="display-4 text-muted mb-2">
                                                                                                            <i class="far fa-file-<?php
                                                                                                                echo $file_ext == 'pdf' ? 'pdf' :
                                                                                                                    (in_array($file_ext, ['doc', 'docx']) ? 'word' :
                                                                                                                    (in_array($file_ext, ['xls', 'xlsx']) ? 'excel' :
                                                                                                                    (in_array($file_ext, ['ppt', 'pptx']) ? 'powerpoint' : 'alt')));
                                                                                                            ?>"></i>
                                                                                                        </div>
                                                                                                        <p class="text-muted small mb-1"><?php echo strtoupper($file_ext); ?> Document</p>
                                                                                                        <a href="../dist/assets/images/uploads/certification-documents/<?php echo $path; ?>"
                                                                                                           target="_blank" class="btn btn-sm btn-outline-secondary">
                                                                                                            <i class="fas fa-eye mr-1"></i> View Document
                                                                                                        </a>
                                                                                                    </div>
                                                                                                <?php endif; ?>
                                                                                            </div>
                                                                                        <?php endforeach; ?>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                    <?php endif; ?>

                                                                </div>

                                                                <form action="update_certification.php" method="POST" id="updateForm<?php echo $row['certification_id']; ?>">
                                                                    <input type="hidden" name="certification_id" value="<?php echo $row['certification_id']; ?>">
                                                                    <div class="modal-body py-4">
                                                                        <input type="hidden" name="certification_id"
                                                                            value="<?php echo $row['certification_id']; ?>">

                                                                        <div class="form-group">
                                                                            <label
                                                                                for="status<?php echo $row['certification_id']; ?>">Update
                                                                                Status</label>
                                                                            <select class="form-control" name="status"
                                                                                id="status<?php echo $row['certification_id']; ?>"
                                                                                required>
                                                                                <option value="">Select Status</option>
                                                                                <option value="Pending" <?php echo ($row['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                                                                <option value="Approved" <?php echo ($row['status'] == 'Approved') ? 'selected' : ''; ?>>Approved</option>
                                                                                <option value="Denied" <?php echo ($row['status'] == 'Denied') ? 'selected' : ''; ?>>Denied</option>
                                                                            </select>
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label
                                                                                for="remarks<?php echo $row['certification_id']; ?>">Remarks/Comments</label>
                                                                            <textarea class="form-control" name="remarks"
                                                                                id="remarks<?php echo $row['certification_id']; ?>"
                                                                                rows="4"><?php echo isset($row['remarks']) ? htmlspecialchars($row['remarks']) : ''; ?></textarea>
                                                                        </div>

                                                                        <div class="alert alert-info">
                                                                            <small><i class="fas fa-info-circle mr-1"></i> As
                                                                                Admin, you can update the status of
                                                                                this certificate request.</small>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer bg-light">
                                                                       <button type="button" 
                                                                                class="btn btn-success generate-cert" 
                                                                                data-id="<?php echo $row['certification_id']; ?>">
                                                                            <i class="fas fa-save mr-1"></i> Update
                                                                        </button>

                                                                        

                                                                        <?php
                                                                            $status = isset($row['status']) ? strtolower($row['status']) : '';
                                                                            $isApproved = ($status === 'approved');
                                                                            $isDenied = ($status === 'denied');
                                                                        ?>
                                                                   


                                                                       




                                                                        
                                                                       

                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-dismiss="modal">
                                                                            <i class="fas fa-times mr-1"></i> Cancel
                                                                        </button>
                                                                        
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                  
                                                <?php endwhile; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="9" class="text-center">No request found</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>

                                <br>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-center">
                                        <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                                            <a class="page-link"
                                                href="?search=<?php echo htmlspecialchars($search); ?>&page=<?php echo $page - 1; ?>"
                                                aria-label="Previous">
                                                <span aria-hidden="true">&laquo; </span>
                                            </a>
                                        </li>
                                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                            <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                                <a class="page-link"
                                                    href="?search=<?php echo htmlspecialchars($search); ?>&page=<?php echo $i; ?>"
                                                    style="background-color: <?php echo ($i == $page) ? '#141E30' : ''; ?> !important;"><?php echo $i; ?></a>
                                            </li>
                                        <?php endfor; ?>
                                        <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                                            <a class="page-link"
                                                href="?search=<?php echo htmlspecialchars($search); ?>&page=<?php echo $page + 1; ?>"
                                                aria-label="Next">
                                                <span aria-hidden="true"> &raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>


                <br><br><br><br><br><br><br>

                <!-- content-wrapper ends -->
                <!-- partial:partials/_footer.html -->
                <footer class="footer" style="background-color: LightGray;">
                    <div class="d-flex justify-content-center">
                        <span
                            class="text-muted text-center text-sm-left d-block d-sm-inline-block font-weight-bold">Copyright
                            ©
                            2025-2030. <a href="" target="_blank">Barangay System</a>. All rights reserved.</span>
                        <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center"></span>
                    </div>
                </footer>
                <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <!-- jQuery -->

    <!-- container-scroller -->
    <!-- plugins:js -->
    <script>



                                                                                $('.generate-cert').on("click",function (e) {
                                                                                    e.preventDefault();

                                                                                    var $btn = $(this);
                                                                                    var certification_id = $btn.data("id");

                                                                                    // Get form values
                                                                                    var status = $("#status" + certification_id).val();
                                                                                    var remarks = $("#remarks" + certification_id).val();

                                                                                    // Show loading state
                                                                                    var originalText = $btn.html();
                                                                                    $btn.html('<i class="fas fa-spinner fa-spin mr-1"></i> Processing...');
                                                                                    $btn.prop("disabled", true);

                                                                                    // AJAX request
                                                                                    $.ajax({
                                                                                        url: "generate_certificate.php",
                                                                                        type: "POST",
                                                                                        dataType: "json", 
                                                                                        data: {
                                                                                            certification_id: certification_id,
                                                                                            status: status,
                                                                                            remarks: remarks
                                                                                        },
                                                                                      success: function (response) {
                                                                                        console.log(response); // For debugging
                                                                                        if (response.success) {
                                                                                            alert(response.message);

                                                                                           location.reload(); // Reload the page to reflect changes
                                                                                        } else {
                                                                                            alert("Error: " + response.message + 
                                                                                                (response.error ? "\nDetails: " + response.error : ""));
                                                                                        }
                                                                                    },
                                                                                        error: function (xhr, status, error) {
                                                                                            alert("Network error: " + error);
                                                                                        },
                                                                                        complete: function () {
                                                                                            // Reset button state kahit success or fail
                                                                                            $btn.html(originalText);
                                                                                            $btn.prop("disabled", false);
                                                                                        }
                                                                                    });
                                                                                });









        document.addEventListener('DOMContentLoaded', function () {
            var searchForm = document.getElementById('searchForm');
            var searchInput = document.getElementById('searchInput');
            var clearButton = document.getElementById('clearButton');
            var clearFilters = document.getElementById('clearFilters');
            var filterForm = document.getElementById('filterForm');

            // Original clear button for search form
            if (clearButton) {
                clearButton.addEventListener('click', function () {
                    searchInput.value = '';
                    searchForm.submit(); // Submit the form to reload all records
                });
            }

            // New clear filters button for filter form
            if (clearFilters) {
                clearFilters.addEventListener('click', function () {
                    // Get all date input fields in the filter form
                    var dateInputs = filterForm.querySelectorAll('input[type="date"]');

                    // Clear all date input values
                    dateInputs.forEach(function (input) {
                        input.value = '';
                    });

                    // Submit the filter form to reload with cleared filters
                    filterForm.submit();
                });
            }
        });

        
    </script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <!-- Add SweetAlert script -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="../dist/assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->

    <!-- <script src="../dist/assets/vendors/datatables.net/jquery.dataTables.js"></script> -->
    <!-- <script src="assets/vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script> -->
    <!-- <script src="../dist/assets/vendors/datatables.net-bs5/dataTables.bootstrap5.js"></script> -->
    <!-- <script src="../dist/assets/js/dataTables.select.min.js"></script> -->
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="../dist/assets/js/off-canvas.js"></script>
    <script src="../dist/assets/js/template.js"></script>
    <script src="../dist/assets/js/settings.js"></script>
    <script src="../dist/assets/js/todolist.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page-->
    <script src="../dist/assets/js/jquery.cookie.js" type="text/javascript"></script>
    <script src="../dist/assets/js/dashboard.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</body>

</html>