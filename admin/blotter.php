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
    $phone_number = $row["phone_number"];
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
                <!-- Blotter Request Modal -->
                <div class="modal fade" id="BlotterModal" tabindex="-1" role="dialog" aria-labelledby="BlotterModalLabel" aria-hidden="true">
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
                                        

                                        <!-- Hidden fields to store resident information -->
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
                                        <!-- Document Upload Section -->
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

                                    <!-- Terms and Submit -->
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
                </div>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        // Form validation
                        (function() {
                            'use strict';

                            // Fetch all forms to apply validation
                            var forms = document.querySelectorAll('.needs-validation');

                            // Loop and prevent submission
                            Array.prototype.slice.call(forms).forEach(function(form) {
                                form.addEventListener('submit', function(event) {
                                    if (!form.checkValidity()) {
                                        event.preventDefault();
                                        event.stopPropagation();
                                    }
                                    form.classList.add('was-validated');
                                }, false);
                            });
                        })();

                        // Auto-fill today's date
                        document.getElementById('dateToday').valueAsDate = new Date();

                        // Resident selection change handling
                        document.getElementById('resident_id').addEventListener('change', function() {
                            var selectedOption = this.options[this.selectedIndex];
                            document.getElementById('user_id').value = selectedOption.getAttribute('data-userid');
                            document.getElementById('first_name').value = selectedOption.getAttribute('data-firstname');
                            document.getElementById('last_name').value = selectedOption.getAttribute('data-lastname');
                            document.getElementById('middle_name').value = selectedOption.getAttribute('data-middlename');
                        });

                        // Display file name when selected
                        document.getElementById('document_path').addEventListener('change', function() {
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
                                            <button class="btn btn-warning text-white font-weight-bold" data-toggle="modal" data-target="#BlotterModal">
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
                                                <input type="text" name="search" class="form-control" placeholder="Search for Ongoing Blotter Cases">
                                                <div class="input-group-append">
                                                    <button class="btn btn-success" type="submit">
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
                                                <th>Blotter ID</th>
                                                <th>Complainant</th>
                                                <th>Respondent</th>
                                                <th>Nature of Case</th>
                                                <th>Status</th>
                                                <th>Date Filed</th>
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

                                                    <!-- View Modal -->
                                                    <div class="modal fade" id="viewComplainModal<?php echo $row['blotter_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg" role="document">
                                                            <div class="modal-content shadow-lg border-0">
                                                                <!-- Enhanced Header with gradient background -->
                                                                <div class="modal-header bg-gradient-primary text-white py-3">
                                                                    <h5 class="modal-title font-weight-bold" id="viewModalLabel">
                                                                        <i class="fas fa-file-alt mr-2"></i>Complain Request Details
                                                                    </h5>
                                                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>

                                                                <div class="modal-body py-4">
                                                                    <!-- Status badge at top -->
                                                                    <div class="text-center mb-4">
                                                                        <span class="badge badge-pill px-4 py-2 font-weight-bold text-white
                                                    <?php
                                                    if ($row['status'] == 'Approved') echo 'badge-warning';
                                                    elseif ($row['status'] == 'On Going') echo 'badge-info';
                                                    elseif ($row['status'] == 'Denied') echo 'badge-danger';
                                                    elseif ($row['status'] == 'Settled') echo 'badge-success';
                                                    else echo 'badge-warning';
                                                    ?>">
                                                                            <i class="fas 
                                                        <?php
                                                        if ($row['status'] == 'Approved') echo 'fa-check-circle';
                                                        elseif ($row['status'] == 'On Going') echo 'fa-clock';
                                                        elseif ($row['status'] == 'Denied') echo 'fa-times-circle';
                                                        elseif ($row['status'] == 'Settled') echo 'fa-handshake';
                                                        else echo 'fa-exclamation-circle';
                                                        ?> mr-1"></i>
                                                                            <?php echo htmlspecialchars(ucfirst($row['status'])); ?>
                                                                        </span>
                                                                    </div>

                                                                    <!-- Card container for details -->
                                                                    <div class="card border-0 shadow-sm mb-4">
                                                                        <div class="card-header bg-light py-3">
                                                                            <h6 class="font-weight-bold text-primary mb-0">
                                                                                <i class="fas fa-user mr-2"></i>Blotter Information
                                                                            </h6>
                                                                        </div>
                                                                        <div class="card-body">
                                                                            <div class="row">
                                                                                <div class="col-md-6">
                                                                                    <div class="info-group mb-3">
                                                                                        <label class="text-muted small text-uppercase">Blotter ID</label>
                                                                                        <p class="font-weight-bold mb-2"><?php echo htmlspecialchars($row['blotter_id']); ?></p>
                                                                                    </div>
                                                                                    <div class="info-group mb-3">
                                                                                        <label class="text-muted small text-uppercase">Complainant</label>
                                                                                        <p class="font-weight-bold mb-2"><?php echo htmlspecialchars($row['complainant']); ?></p>
                                                                                    </div>
                                                                                    <div class="info-group mb-3">
                                                                                        <label class="text-muted small text-uppercase">Respondent</label>
                                                                                        <p class="font-weight-bold mb-2"><?php echo htmlspecialchars($row['respondent']); ?></p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <div class="info-group mb-3">
                                                                                        <label class="text-muted small text-uppercase">Nature of Case</label>
                                                                                        <p class="font-weight-bold mb-2"><?php echo htmlspecialchars($row['natureOfCase']); ?></p>
                                                                                    </div>
                                                                                    <div class="info-group mb-3">
                                                                                        <label class="text-muted small text-uppercase">Victim (if applicable)</label>
                                                                                        <p class="font-weight-bold mb-2"><?php echo !empty($row['victim']) ? htmlspecialchars($row['victim']) : 'N/A'; ?></p>
                                                                                    </div>
                                                                                    <div class="info-group mb-3">
                                                                                        <label class="text-muted small text-uppercase">Witness (if applicable)</label>
                                                                                        <p class="font-weight-bold mb-2"><?php echo !empty($row['witness']) ? htmlspecialchars($row['witness']) : 'N/A'; ?></p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="card border-0 shadow-sm mb-4">
                                                                        <div class="card-header bg-light py-3">
                                                                            <h6 class="font-weight-bold text-primary mb-0">
                                                                                <i class="fas fa-user mr-2"></i>Description
                                                                            </h6>
                                                                        </div>

                                                                        <div class="card-body">
                                                                            <div class="info-group mb-3">
                                                                                <label class="text-muted small text-uppercase" for="comp_desc">Describe What Happened</label>
                                                                                <p class="font-weight-bold mb-2">
                                                                                    <?php echo !empty($row['comp_desc']) ? htmlspecialchars($row['comp_desc']) : 'No description provided.'; ?>
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
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
                                                                                                        <img src="../dist/assets/images/uploads/blotter-documents/<?php echo $path; ?>"
                                                                                                             class="img-fluid" alt="Document">
                                                                                                    </div>
                                                                                                    <a href="../dist/assets/images/uploads/blotter-documents/<?php echo $path; ?>"
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
                                                                                                        <a href="../dist/assets/images/uploads/blotter-documents/<?php echo $path; ?>"
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

                                                                <div class="modal-footer bg-light">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                                        <i class="fas fa-times mr-1"></i> Close
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Edit Modal for Barangay Secretary only -->
                                                    
                                                        <div class="modal fade"
                                                            id="editModal<?php echo $row['blotter_id']; ?>" tabindex="-1"
                                                            role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-lg" role="document">
                                                                <div class="modal-content shadow-lg border-0">
                                                                    <div class="modal-header bg-gradient-warning text-white py-3">
                                                                        <h5 class="modal-title font-weight-bold"
                                                                            id="editModalLabel">
                                                                            <i class="fas fa-edit mr-2"></i>Update Complain
                                                                            Request
                                                                        </h5>
                                                                        <button type="button" class="close text-white"
                                                                            data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <form action="update_blotter.php" method="POST">
                                                                        <div class="modal-body py-4">
                                                                            <input type="hidden" name="blotter_id"
                                                                                value="<?php echo $row['blotter_id']; ?>">

                                                                            <div class="form-group">
                                                                                <label
                                                                                    for="status<?php echo $row['blotter_id']; ?>">Update
                                                                                    Status</label>
                                                                                <select class="form-control" name="status"
                                                                                    id="status<?php echo $row['blotter_id']; ?>"
                                                                                    required>
                                                                                    <option value="">Select Status</option>
                                                                                    <option value="On Going" <?php echo ($row['status'] == 'On Going') ? 'selected' : ''; ?>>On Going</option>
                                                                                    <option value="Approved" <?php echo ($row['status'] == 'Approved') ? 'selected' : ''; ?>>Completed</option>
                                                                                    <option value="Denied" <?php echo ($row['status'] == 'Denied') ? 'selected' : ''; ?>>Denied</option>
                                                                                </select>
                                                                            </div>



                                                                            <div class="alert alert-info">
                                                                                <small><i class="fas fa-info-circle mr-1"></i> As
                                                                                    Admin, you can update the status of
                                                                                    this certificate request.</small>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer bg-light">
                                                                            <button type="button" class="btn btn-secondary"
                                                                                data-dismiss="modal">
                                                                                <i class="fas fa-times mr-1"></i> Cancel
                                                                            </button>
                                                                            <button type="submit" class="btn btn-success">
                                                                                <i class="fas fa-save mr-1"></i> Update Request
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
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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