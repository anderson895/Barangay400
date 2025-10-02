<?php
session_start();

// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

include '../connection/config.php';

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
$phone_number = "";
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
    <title>Blotter Request | Barangay System</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="../dist/assets/vendors/feather/feather.css">
    <link rel="stylesheet" href="../dist/assets/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="../dist/assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="../dist/assets/vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../dist/assets/vendors/mdi/css/materialdesignicons.min.css">
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


<style>
    
  .nav-link i {
    margin-right: 10px;
  }

</style>


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
                        <img src="../dist/assets/images/default_image.png" alt="profile" />
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



                <!-- Blotter Request Modal -->
                <!-- <div class="modal fade" id="BlotterModal" tabindex="-1" role="dialog" aria-labelledby="BlotterModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="BlotterModalLabel">Submit Blotter Request</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="add_blotter.php" method="POST" enctype="multipart/form-data" novalidate id="complainForm" class="needs-validation">
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="natureOfCase">Nature of Case</label>
                                        <div class="input-group">
                                            <select class="form-control" id="natureOfCase" name="natureOfCase" required>
                                                <option value="">Select</option>
                                                <option value="Family Dispute">Family Dispute</option>
                                                <option value="Neighborly Dispute">Neighborly Dispute</option>
                                                <option value="Noise Complaint">Noise Complaint</option>
                                                <option value="Property Damage">Property Damage</option>
                                                <option value="Theft">Theft</option>
                                                <option value="Harassment">Harassment</option>
                                                <option value="Others">Others</option>
                                            </select>
                                            <div class="input-group-append">
                                                <span class="input-group-text validation-icon" id="natureOfCase_validation">
                                                    <i class="fas fa-check text-success d-none"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        

                                        <input type="hidden" id="user_id" name="user_id">
                                        <input type="hidden" id="first_name" name="first_name">
                                        <input type="hidden" id="last_name" name="last_name">
                                        <input type="hidden" id="middle_name" name="middle_name">

                                    </div>

                                    <div class="form-group">
                                        <label for="complainant">Complainant's Full Name</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="complainant" name="complainant" placeholder="Enter Complainant's Full Name" required>
                                            <div class="input-group-append">
                                                <span class="input-group-text validation-icon" id="complainant_validation">
                                                    <i class="fas fa-check text-success d-none"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="respondent">Respondent's Full Name</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="respondent" name="respondent" placeholder="Enter Respondent's Full Name" required>
                                            <div class="input-group-append">
                                                <span class="input-group-text validation-icon" id="respondent_validation">
                                                    <i class="fas fa-check text-success d-none"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="victim">Victim's Full Name (if applicable)</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="victim" name="victim" placeholder="Enter Victim's Full Name">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text validation-icon" id="victim_validation">
                                                            <i class="fas fa-check text-success d-none"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="witness">Witness's Full Name (if applicable)</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="witness" name="witness" placeholder="Enter Witness's Full Name">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text validation-icon" id="witness_validation">
                                                            <i class="fas fa-check text-success d-none"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-4">
                                            <h5 class="border-bottom pb-2"><i class="fas fa-file-upload me-2"></i>Supporting Documents</h5>
                                            <div class="file-upload">
                                                <i class="fas fa-cloud-upload-alt"></i>
                                                <h5>Upload Supporting Document/Evidence</h5>
                                                <p>Please upload any evidence related to your blotter (photos, videos, written statements, etc.)</p>
                                                <input type="file" class="form-control" id="document_path" name="document_path" required>
                                                <div class="file-upload-info mt-2">
                                                    <small><i class="fas fa-info-circle me-1"></i>Accepted formats: PDF, JPG, PNG, MP4 (Max size: 10MB)</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3 d-flex justify-content-center">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="terms" required>
                                            <label class="form-check-label" for="terms">I confirm that the information provided is accurate and complete, and I understand that filing a false blotter may have legal consequences</label>
                                            <div class="invalid-feedback">
                                                You must agree before submitting.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div> -->

                <!-- Add Blotter Report Modal -->
                <div class="modal fade" id="BlotterModal" tabindex="-1" role="dialog" aria-labelledby="blotterModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">
                    
                    <!-- Modal Header -->
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="blotterModalLabel">Add Blotter Report</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="modal-body">
                        <form id="blotterForm">

                        <!-- Complainant Information -->
                        <h6 class="mb-3 font-weight-bold">Complainant Information</h6>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                            <label>Complainant Name</label>
                            <input type="text" class="form-control" name="complainant_name">
                            </div>
                            <div class="form-group col-md-6">
                            <label>Age</label>
                            <input type="number" class="form-control" name="complainant_age">
                            </div>
                            <div class="form-group col-md-6">
                            <label>Residential Address</label>
                            <input type="text" class="form-control" name="complainant_address">
                            </div>
                            <div class="form-group col-md-6">
                            <label>Email Address (If any)</label>
                            <input type="email" class="form-control" name="complainant_email">
                            </div>
                            <div class="form-group col-md-6">
                            <label>Phone Number</label>
                            <input type="text" class="form-control" name="complainant_phone">
                            </div>
                        </div>

                        <!-- Respondent Information -->
                        <h6 class="mt-4 mb-3 font-weight-bold">Respondent Information</h6>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                            <label>Respondent Name</label>
                            <input type="text" class="form-control" name="respondent_name">
                            <div class="d-flex align-items-center mt-1 ml-4">
                                <input type="checkbox" class="form-check-input mr-2" id="unknownRespondent" name="unknown_respondent">
                                <label class="form-check-label mb-0" for="unknownRespondent">I'm not sure what the name is</label>
                            </div>

                            </div>
                            <div class="form-group col-md-6">
                            <label>Age</label>
                            <input type="number" class="form-control" name="respondent_age">
                            </div>
                            <div class="form-group col-md-12">
                            <label>Residential Address</label>
                            <input type="text" class="form-control" name="respondent_address">
                            </div>
                        </div>

                        <!-- Incident Details -->
                        <h6 class="mt-4 mb-3 font-weight-bold">Incident Details</h6>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                            <label>Blotter Type</label>
                            <select class="form-control" name="blotter_type">
                                <option value="">Select</option>
                                <option value="theft">Theft</option>
                                <option value="assault">Assault</option>
                                <option value="harassment">Harassment</option>
                            </select>
                            </div>
                            <div class="form-group col-md-6">
                            <label>Location of Incident</label>
                            <input type="text" class="form-control" name="incident_location">
                            </div>
                            <div class="form-group col-md-6">
                            <label>Date Reported</label>
                            <input type="date" class="form-control" name="date_reported">
                            </div>
                            <div class="form-group col-md-6">
                            <label>Time Reported</label>
                            <input type="time" class="form-control" name="time_reported">
                            </div>
                        </div>

                        <!-- Narrative of Incident -->
                        <h6 class="mt-4 mb-3 font-weight-bold">Narrative of Incident</h6>
                        <div class="form-group">
                            <textarea class="form-control" name="incident_narrative" rows="4" placeholder="Incident Report Narrative"></textarea>
                        </div>

                        <!-- Supporting Documents -->
                        <h6 class="mt-4 mb-3 font-weight-bold">Supporting Documents and Evidence</h6>
                        <div class="form-group">
                            <input type="file" class="form-control-file" name="supporting_documents" accept=".pdf,.jpg,.jpeg,.png,.mp4">
                            <small class="form-text text-muted">
                            Accepted formats: PDF, JPG, PNG, MP4 (Max size: 10MB)
                            </small>
                        </div>

                        </form>
                    </div>

                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" form="blotterForm" class="btn btn-success">Submit Blotter</button>
                    </div>

                    </div>
                </div>
                </div>

                
                <?php
                include '../connection/config.php';

                // Check for success messages
                if (isset($_GET['success'])) {
                    $successMessages = [
                        1 => "Clearance Request Submitted Successfully",
                        2 => "Clearance Request Updated Successfully"
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

                // Get all column names from the tbl_clearance table for search
                $columnsQuery = "SHOW COLUMNS FROM tbl_blotter";
                $columnsResult = $conn->query($columnsQuery);
                $searchFields = [];

                if ($columnsResult) {
                    while ($column = $columnsResult->fetch_assoc()) {
                        $searchFields[] = "b." . $column['Field'];
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
                $count_sql = "SELECT COUNT(*) as total FROM tbl_blotter b WHERE $where_clause";

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

                // Fetch clearance requests query
                $sql = "SELECT b.blotter_id, b.res_id, b.user_id, b.dateFiled, 
b.caseNumber, b.complainant, b.respondent, b.victim, b.blotter_desc,
b.witness, b.natureOfCase, b.document_path,
b.status, b.created_at
FROM tbl_blotter b
WHERE $where_clause
ORDER BY b.created_at DESC 
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
                                <!-- Blotter Report Management Section -->
                                    <div class="container-fluid mt-3">
                                        <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                                            <h5 class="text-primary font-weight-bold mb-2">Blotter Report Management</h5>
                                            <button class="btn btn-warning text-black font-weight-bold" data-toggle="modal" data-target="#BlotterModal">
                                                Add Blotter
                                            </button>
                                        </div>

                                        <!-- Tabs -->
                                        <ul class="nav nav-tabs mb-3">
                                            <li class="nav-item">
                                                <a class="nav-link active font-weight-bold" href="#">Ongoing</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link font-weight-bold" href="#">Scheduled Hearings</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link font-weight-bold" href="#">Resolved</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link font-weight-bold" href="#">Dismissed</a>
                                            </li>
                                        </ul>

                                        <!-- Search Bar -->
                                        <form method="GET" action="" class="form-inline mb-3">
                                            <div class="input-group w-50">
                                                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search for Ongoing Blotter Cases">
                                                <div class="input-group-append">
                                                <button class="btn btn-success btn-sm" type="submit">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                                </div>
                                            </div>
                                        </form>

                                    </div>


                                <div class="table-responsive">
                                    <table class="table table-striped table-borderless" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Blotter No</th>
                                                <th>Complainant</th>
                                                <th>Hearing type</th>
                                                <th>Hearing Date</th>
                                                <th>Hearing Time</th>
                                                <th>Scheduled By</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if ($result->num_rows > 0): ?>
                                                <?php while ($row = $result->fetch_assoc()): ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($row['blotter_id']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['complainant']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['respondent']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['natureOfCase']); ?></td>

                                                        </td>
                                                        <td>
                                                            <span class="badge 
                                <?php
                                                    if ($row['status'] == 'To Be Approved')
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
                                                        <td><?php echo date('F d, Y', strtotime($row['dateFiled'])); ?>
                                                        </td>
                                                        <td>
                                                            <!-- View button for all positions -->
                                                            <button class="btn btn-info btn-sm" data-toggle="modal" title="View"
                                                                data-target="#viewComplainModal<?php echo $row['blotter_id']; ?>">
                                                                <i class="fa-solid fa-eye"></i>
                                                            </button>

                                                            <!-- Edit/Update button only for Barangay Secretary -->
                                                           
                                                                <button class="btn btn-warning btn-sm" data-toggle="modal" title="Update"
                                                                    data-target="#editModal<?php echo $row['blotter_id']; ?>">
                                                                    <i class="fa-solid fa-edit"></i>
                                                                </button>
                                                     
                                                        </td>
                                                    </tr>
                                                  
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
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>`


    <script>
        $('#blotterForm').on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData(this);
        formData.append('requestType', 'AddBlotter');

        $.ajax({
            type: "POST",
            url: "Jcontroller.php",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "text",
            success: function(response) {
                console.log(response);
            }
        });
        });

    </script>









    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var searchForm = document.getElementById('searchForm');
            var searchInput = document.getElementById('searchInput');
            var clearButton = document.getElementById('clearButton');
            var clearFilters = document.getElementById('clearFilters');
            var filterForm = document.getElementById('filterForm');

            // Original clear button for search form
            if (clearButton) {
                clearButton.addEventListener('click', function() {
                    searchInput.value = '';
                    searchForm.submit(); // Submit the form to reload all records
                });
            }

            // New clear filters button for filter form
            if (clearFilters) {
                clearFilters.addEventListener('click', function() {
                    // Get all date input fields in the filter form
                    var dateInputs = filterForm.querySelectorAll('input[type="date"]');

                    // Clear all date input values
                    dateInputs.forEach(function(input) {
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

    <script src="../dist/assets/vendors/datatables.net/jquery.dataTables.js"></script>
    <!-- <script src="assets/vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script> -->
    <script src="../dist/assets/vendors/datatables.net-bs5/dataTables.bootstrap5.js"></script>
    <script src="../dist/assets/js/dataTables.select.min.js"></script>
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