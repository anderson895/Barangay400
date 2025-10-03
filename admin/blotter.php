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
                            <!-- <li class="nav-item"> <a class="nav-link" href="complains.php"> Complains Request </a></li> -->
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

















<div class="modal fade" id="editModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form id="editBlotterForm" >
        <div class="modal-header bg-warning">
          <h5 class="modal-title">Edit Blotter Report</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <div class="modal-body">
          <input type="hidden" name="blotter_id" id="edit_id">

          <h6 class="text-primary">Complainant Information</h6>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label>Name</label>
              <input type="text" class="form-control" name="complainant_name" id="edit_complainant">
            </div>
            <div class="form-group col-md-3">
              <label>Age</label>
              <input type="number" class="form-control" name="complainant_age" id="edit_complainantage">
            </div>
            <div class="form-group col-md-3">
              <label>Phone</label>
              <input type="text" class="form-control" name="complainant_phone" id="edit_complainantphone">
            </div>
          </div>
          <div class="form-group">
            <label>Address</label>
            <textarea class="form-control" name="complainant_address" id="edit_complainantaddress"></textarea>
          </div>
          <div class="form-group">
            <label>Email</label>
            <input type="email" class="form-control" name="complainant_email" id="edit_complainantemail">
          </div>

          <h6 class="text-primary mt-3">Respondent Information</h6>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label>Name</label>
              <input type="text" class="form-control" name="respondent_name" id="edit_respondent">
            </div>
            <div class="form-group col-md-3">
              <label>Age</label>
              <input type="number" class="form-control" name="respondent_age" id="edit_respondentage">
            </div>
            <div class="form-group col-md-3">
              <label>Address</label>
              <input type="text" class="form-control" name="respondent_address" id="edit_respondentaddress">
            </div>
          </div>

          <h6 class="text-primary mt-3">Blotter Details</h6>
          <div class="form-group">
            <label>Type</label>
            <select class="form-control" name="blotter_type" id="edit_type">
                                <option value="">Select</option>
                                <option value="theft">Theft</option>
                                <option value="assault">Assault</option>
                                <option value="harassment">Harassment</option>
                            </select>
          </div>
          <div class="form-group">
            <label>Incident Location</label>
            <textarea class="form-control" name="incident_location" id="edit_incidentlocation"></textarea>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label>Date Reported</label>
              <input type="date" class="form-control" name="date_reported" id="edit_datereported">
            </div>
            <div class="form-group col-md-6">
              <label>Time Reported</label>
              <input type="time" class="form-control" name="time_reported" id="edit_timereported">
            </div>
          </div>
          <div class="form-group">
            <label>Incident Narrative</label>
            <textarea class="form-control" name="incident_narrative" id="edit_incidentnarrative"></textarea>
          </div>
          <div class="form-group">
            <label>Supporting Documents</label>
            <input type="file" class="form-control" name="supporting_documents">
            <small id="current_doc"></small>
          </div>

          <h6 class="text-primary mt-3">Hearing Information</h6>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label>Hearing Date</label>
              <input type="date" class="form-control" name="hearing_date" id="edit_hearingdate">
            </div>
            <div class="form-group col-md-6">
              <label>Hearing Time</label>
              <input type="time" class="form-control" name="hearing_time" id="edit_hearingtime">
            </div>
          </div>
          <div class="form-group">
            <label>Scheduled By</label>
            <input type="text" class="form-control" name="scheduled_by" id="edit_scheduledby">
          </div>

          <div class="form-group" hidden>
            <label>Status</label>
            <select class="form-control" name="blotter_status" id="edit_status">
              <option value="Ongoing">Ongoing</option>
              <option value="Scheduled Hearing">Scheduled Hearing</option>
              <option value="Resolved">Resolved</option>
              <option value="Dismissed">Dismissed</option>
            </select>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-warning">Update</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>





<div class="modal fade" id="viewModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document" style="max-width:900px;">

    <div class="modal-content">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title">View Blotter Report</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body">
        <input type="hidden" id="view_id">

        <h6 class="text-primary">Complainant Information</h6>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label>Name</label>
            <input type="text" class="form-control" id="view_complainant" readonly>
          </div>
          <div class="form-group col-md-3">
            <label>Age</label>
            <input type="number" class="form-control" id="view_complainantage" readonly>
          </div>
          <div class="form-group col-md-3">
            <label>Phone</label>
            <input type="text" class="form-control" id="view_complainantphone" readonly>
          </div>
        </div>
        <div class="form-group">
          <label>Address</label>
          <textarea class="form-control" id="view_complainantaddress" readonly></textarea>
        </div>
        <div class="form-group">
          <label>Email</label>
          <input type="email" class="form-control" id="view_complainantemail" readonly>
        </div>

        <h6 class="text-primary mt-3">Respondent Information</h6>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label>Name</label>
            <input type="text" class="form-control" id="view_respondent" readonly>
          </div>
          <div class="form-group col-md-3">
            <label>Age</label>
            <input type="number" class="form-control" id="view_respondentage" readonly>
          </div>
          <div class="form-group col-md-3">
            <label>Address</label>
            <input type="text" class="form-control" id="view_respondentaddress" readonly>
          </div>
        </div>

        <h6 class="text-primary mt-3">Blotter Details</h6>
        <div class="form-group">
          <label>Type</label>
          <input type="text" class="form-control" id="view_blottertype" readonly>
        </div>
        <div class="form-group">
          <label>Incident Location</label>
          <textarea class="form-control" id="view_incidentlocation" readonly></textarea>
        </div>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label>Date Reported</label>
            <input type="date" class="form-control" id="view_datereported" readonly>
          </div>
          <div class="form-group col-md-6">
            <label>Time Reported</label>
            <input type="time" class="form-control" id="view_timereported" readonly>
          </div>
        </div>
        <div class="form-group">
          <label>Incident Narrative</label>
          <textarea class="form-control" id="view_incidentnarrative" readonly></textarea>
        </div>
        <div class="form-group">
          <label>Supporting Documents</label>
          <input type="text" class="form-control" id="view_supportingdoc" readonly>
        </div>

        <h6 class="text-primary mt-3">Hearing Information</h6>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label>Hearing Date</label>
            <input type="date" class="form-control" id="view_hearingdate" readonly>
          </div>
          <div class="form-group col-md-6">
            <label>Hearing Time</label>
            <input type="time" class="form-control" id="view_hearingtime" readonly>
          </div>
        </div>
        <div class="form-group">
          <label>Scheduled By</label>
          <input type="text" class="form-control" id="view_scheduledby" readonly>
        </div>
        <div class="form-group">
          <label>Status</label>
          <input type="text" class="form-control" id="view_status" readonly>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>



<div class="modal fade" id="scheduleModal" tabindex="-1" role="dialog" aria-labelledby="scheduleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success text-white py-2">
        <h5 class="modal-title" id="scheduleModalLabel">Set Hearing Schedule</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="scheduleForm">
        <input type="hidden" id="schedule_complainant_email" name="complainant_email">
        <input type="hidden" id="schedule_id" name="blotter_id">

        <div class="modal-body p-2">
          <div class="mb-2">
            <label class="form-label">Complainant</label>
            <input type="text" id="schedule_complainant" class="form-control form-control-sm" readonly>
          </div>

          <div class="mb-2">
            <label class="form-label">Hearing Date</label>
            <input type="date" id="schedule_hearingdate" name="hearing_date" class="form-control form-control-sm" required>
          </div>

          <div class="mb-2">
            <label class="form-label">Hearing Time</label>
            <input type="time" id="schedule_hearingtime" name="hearing_time" class="form-control form-control-sm" required>
          </div>

          <div class="mb-2">
            <label class="form-label">Scheduled By</label>
            <input type="text" id="schedule_scheduledby" name="scheduled_by" class="form-control form-control-sm" required>
          </div>

          <div class="mb-2">
            <label class="form-label">Status</label>
            <select id="schedule_status" name="blotter_status" class="form-select form-select-sm text-black" required>
              <option value="Ongoing">Ongoing</option>
              <option value="Scheduled Hearing">Scheduled Hearing</option>
              <option value="Resolved">Resolved</option>
              <option value="Dismissed">Dismissed</option>
            </select>
          </div>
        </div>

        <div class="modal-footer py-2">
          <button type="submit" class="btn btn-success btn-sm w-100">Save Schedule</button>
        </div>
      </form>
    </div>
  </div>
</div>









  <?php
include '../connection/config.php';

// ✅ GET parameters
$search = $_GET['search'] ?? '';
$page = $_GET['page'] ?? 1;
$status_filter = $_GET['status'] ?? 'Ongoing'; // default tab = Ongoing
$limit = 10;
$offset = ($page - 1) * $limit;

// ✅ Build WHERE clause
$where_clause = "1=1";
$params = [];
$types = "";

// Search filter
if (!empty($search)) {
    $where_clause .= " AND (b.complainant_name LIKE ? OR b.respondent_name LIKE ? OR b.blotter_type LIKE ?)";
    $params = array_merge($params, ["%$search%", "%$search%", "%$search%"]);
    $types .= "sss";
}

// Status filter
if (!empty($status_filter)) {
    $where_clause .= " AND b.blotter_status = ?";
    $params[] = $status_filter;
    $types .= "s";
}

// ✅ Count total rows
$count_sql = "SELECT COUNT(*) as total FROM j_blotter b WHERE $where_clause";
$count_stmt = $conn->prepare($count_sql);
if (!empty($params)) {
    $count_stmt->bind_param($types, ...$params);
}
$count_stmt->execute();
$total_rows = $count_stmt->get_result()->fetch_assoc()['total'];
$count_stmt->close();
$total_pages = ceil($total_rows / $limit);

// ✅ Fetch Blotters
$sql = "SELECT b.*
        FROM j_blotter b
        WHERE $where_clause
        ORDER BY b.date_reported DESC, b.time_reported DESC
        LIMIT ? OFFSET ?";
$params[] = $limit;
$params[] = $offset;
$types .= "ii";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
?>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">

                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                    <h5 class="text-primary font-weight-bold mb-2">Blotter Report Management</h5>
                    <button class="btn btn-warning text-black font-weight-bold" data-toggle="modal" data-target="#BlotterModal">
                        Add Blotter
                    </button>
                </div>

                <!-- Tabs -->
                <ul class="nav nav-tabs mb-3">
                    <?php 
                    $statuses = ['Ongoing', 'Scheduled Hearing', 'Resolved', 'Dismissed'];
                    foreach ($statuses as $status):
                        $active = ($status === $status_filter) ? 'active' : '';
                    ?>
                    <li class="nav-item">
                        <a class="nav-link font-weight-bold <?= $active ?>" 
                           href="?status=<?= urlencode($status) ?>&search=<?= urlencode($search) ?>">
                           <?= $status ?>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>

                <!-- Search -->
                <form method="GET" action="" class="form-inline mb-3">
                    <input type="hidden" name="status" value="<?= htmlspecialchars($status_filter) ?>">
                    <div class="input-group w-50">
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Search for Blotter Cases" value="<?= htmlspecialchars($search) ?>">
                        <div class="input-group-append">
                            <button class="btn btn-success btn-sm" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-striped table-borderless">
                        <thead>
                            <tr>
                                <th>Blotter No</th>
                                <th>Complainant</th>
                                <th>Respondent</th>
                                <th>Type</th>
                                <th>Date Reported</th>
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
                                    <td><?= htmlspecialchars($row['blotter_id']) ?></td>
                                    <td><?= htmlspecialchars($row['complainant_name']) ?></td>
                                    <td><?= htmlspecialchars($row['respondent_name']) ?></td>
                                    <td><?= htmlspecialchars($row['blotter_type']) ?></td>
                                    <td><?= date('F d, Y', strtotime($row['date_reported'])) ?></td>
                                    <td><?= $row['hearing_date'] ? date('F d, Y', strtotime($row['hearing_date'])) : '-' ?></td>
                                    <td><?= $row['hearing_time'] ? date('h:i A', strtotime($row['hearing_time'])) : '-' ?></td>
                                    <td><?= $row['scheduled_by'] ?? '-' ?></td>
                                    <td>
                                        <span class="badge 
                                            <?php
                                            switch ($row['blotter_status']) {
                                                case 'Ongoing': echo 'badge-info'; break;
                                                case 'Scheduled Hearing': echo 'badge-warning'; break;
                                                case 'Resolved': echo 'badge-success'; break;
                                                case 'Dismissed': echo 'badge-danger'; break;
                                                default: echo 'badge-light';
                                            }
                                            ?> text-white font-weight-bold">
                                            <?= htmlspecialchars($row['blotter_status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                      <button class="btn btn-info btn-sm btnView" 
                                            data-id="<?= $row['blotter_id'] ?>"
                                            data-complainant="<?= htmlspecialchars($row['complainant_name']) ?>"
                                            data-complainantage="<?= $row['complainant_age'] ?>"
                                            data-complainantaddress="<?= htmlspecialchars($row['complainant_address']) ?>"
                                            data-complainantemail="<?= htmlspecialchars($row['complainant_email']) ?>"
                                            data-complainantphone="<?= htmlspecialchars($row['complainant_phone']) ?>"
                                            data-respondent="<?= htmlspecialchars($row['respondent_name']) ?>"
                                            data-respondentage="<?= $row['respondent_age'] ?>"
                                            data-respondentaddress="<?= htmlspecialchars($row['respondent_address']) ?>"
                                            data-type="<?= htmlspecialchars($row['blotter_type']) ?>"
                                            data-incidentlocation="<?= htmlspecialchars($row['incident_location']) ?>"
                                            data-datereported="<?= $row['date_reported'] ?>"
                                            data-timereported="<?= $row['time_reported'] ?>"
                                            data-incidentnarrative="<?= htmlspecialchars($row['incident_narrative']) ?>"
                                            data-supportingdocuments="<?= htmlspecialchars($row['supporting_documents']) ?>"
                                            data-hearingdate="<?= $row['hearing_date'] ?>"
                                            data-hearingtime="<?= $row['hearing_time'] ?>"
                                            data-scheduledby="<?= htmlspecialchars($row['scheduled_by']) ?>"
                                            data-status="<?= htmlspecialchars($row['blotter_status']) ?>"
                                            data-toggle="modal" data-target="#viewModal">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>

                                        <button class="btn btn-warning btn-sm btnEdit" 
                                            data-id="<?= $row['blotter_id'] ?>"
                                            data-complainant="<?= htmlspecialchars($row['complainant_name']) ?>"
                                            data-complainantage="<?= $row['complainant_age'] ?>"
                                            data-complainantaddress="<?= htmlspecialchars($row['complainant_address']) ?>"
                                            data-complainantemail="<?= htmlspecialchars($row['complainant_email']) ?>"
                                            data-complainantphone="<?= htmlspecialchars($row['complainant_phone']) ?>"
                                            data-respondent="<?= htmlspecialchars($row['respondent_name']) ?>"
                                            data-respondentage="<?= $row['respondent_age'] ?>"
                                            data-respondentaddress="<?= htmlspecialchars($row['respondent_address']) ?>"
                                            data-type="<?= htmlspecialchars($row['blotter_type']) ?>"
                                            data-incidentlocation="<?= htmlspecialchars($row['incident_location']) ?>"
                                            data-datereported="<?= $row['date_reported'] ?>"
                                            data-timereported="<?= $row['time_reported'] ?>"
                                            data-incidentnarrative="<?= htmlspecialchars($row['incident_narrative']) ?>"
                                            data-supportingdocuments="<?= htmlspecialchars($row['supporting_documents']) ?>"
                                            data-hearingdate="<?= $row['hearing_date'] ?>"
                                            data-hearingtime="<?= $row['hearing_time'] ?>"
                                            data-scheduledby="<?= htmlspecialchars($row['scheduled_by']) ?>"
                                            data-status="<?= htmlspecialchars($row['blotter_status']) ?>"
                                            data-toggle="modal" data-target="#editModal">
                                            <i class="fa-solid fa-edit"></i>
                                        </button>


                                        <!-- Set Schedule Button -->
                                        <button class="btn btn-success btn-sm btnSchedule" 
                                            data-id="<?= $row['blotter_id'] ?>"
                                            data-complainant="<?= htmlspecialchars($row['complainant_name']) ?>"
                                            data-hearingdate="<?= $row['hearing_date'] ?>"
                                            data-hearingtime="<?= $row['hearing_time'] ?>"
                                            data-scheduledby="<?= htmlspecialchars($row['scheduled_by']) ?>"
                                            data-status="<?= htmlspecialchars($row['blotter_status']) ?>"
                                            data-toggle="modal" data-target="#scheduleModal">
                                            <i class="fa-solid fa-calendar"></i> 
                                        </button>


</td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="10" class="text-center">No blotter reports found</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                            <a class="page-link" href="?status=<?= urlencode($status_filter) ?>&search=<?= urlencode($search) ?>&page=<?= $page - 1 ?>">&laquo;</a>
                        </li>
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                <a class="page-link" href="?status=<?= urlencode($status_filter) ?>&search=<?= urlencode($search) ?>&page=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                            <a class="page-link" href="?status=<?= urlencode($status_filter) ?>&search=<?= urlencode($search) ?>&page=<?= $page + 1 ?>">&raquo;</a>
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

$(document).on("click", ".btnView", function() {
    $("#view_id").val($(this).data("id"));
    $("#view_complainant").val($(this).data("complainant"));
    $("#view_complainantage").val($(this).data("complainantage"));
    $("#view_complainantaddress").val($(this).data("complainantaddress"));
    $("#view_complainantemail").val($(this).data("complainantemail"));
    $("#view_complainantphone").val($(this).data("complainantphone"));
    $("#view_respondent").val($(this).data("respondent"));
    $("#view_respondentage").val($(this).data("respondentage"));
    $("#view_respondentaddress").val($(this).data("respondentaddress"));
    $("#view_blottertype").val($(this).data("type"));
    $("#view_incidentlocation").val($(this).data("incidentlocation"));
    $("#view_datereported").val($(this).data("datereported"));
    $("#view_timereported").val($(this).data("timereported"));
    $("#view_incidentnarrative").val($(this).data("incidentnarrative"));
    $("#view_status").val($(this).data("status"));

    $("#view_hearingdate").val($(this).data("hearingdate") || "-");
    $("#view_hearingtime").val($(this).data("hearingtime") || "-");
    $("#view_scheduledby").val($(this).data("scheduledby") || "-");

    // supporting documents
    var doc = $(this).data("supportingdocuments");
    var container = $("#view_supportingdoc");
    container.val(''); // clear previous value

    if (doc) {
        let ext = doc.split('.').pop().toLowerCase();
        let filePath = "../uploads/blotter/" + doc;

        if (["jpg", "jpeg", "png", "gif", "webp"].includes(ext)) {
            container.replaceWith(`
                <div id="view_supportingdoc">
                    <a href="${filePath}" target="_blank">
                        <img src="${filePath}" class="img-thumbnail" style="max-height:120px;">
                    </a>
                </div>
            `);
        } else if (ext === "pdf") {
            container.replaceWith(`
                <div id="view_supportingdoc">
                    <a href="${filePath}" target="_blank">
                        <i class="fas fa-file-pdf fa-3x text-danger"></i> View PDF
                    </a>
                </div>
            `);
        } else if (["doc", "docx"].includes(ext)) {
            container.replaceWith(`
                <div id="view_supportingdoc">
                    <a href="${filePath}" target="_blank">
                        <i class="fas fa-file-word fa-3x text-primary"></i> View Document
                    </a>
                </div>
            `);
        } else if (["xls", "xlsx"].includes(ext)) {
            container.replaceWith(`
                <div id="view_supportingdoc">
                    <a href="${filePath}" target="_blank">
                        <i class="fas fa-file-excel fa-3x text-success"></i> View Spreadsheet
                    </a>
                </div>
            `);
        } else {
            container.replaceWith(`
                <div id="view_supportingdoc">
                    <a href="${filePath}" target="_blank">
                        <i class="fas fa-file fa-3x text-secondary"></i> Download File
                    </a>
                </div>
            `);
        }
    } else {
        container.replaceWith(`
            <div id="view_supportingdoc">
                <span class="text-muted">No supporting documents uploaded</span>
            </div>
        `);
    }

});








$('.btnSchedule').on('click', function() {
    $('#schedule_id').val($(this).data('id'));
    $('#schedule_complainant').val($(this).data('complainant'));
    $('#schedule_hearingdate').val($(this).data('hearingdate'));
    $('#schedule_hearingtime').val($(this).data('hearingtime'));
    $('#schedule_scheduledby').val($(this).data('scheduledby'));
    $('#schedule_status').val($(this).data('status'));
    $('#schedule_complainant_email').val($(this).data('complainantemail')); // hidden email
});



$('#scheduleForm').on('submit', function(e) {
    e.preventDefault();

    var status = $('#schedule_status').val(); // make sure you add a hidden/select input for status in your modal
    var formData = $(this).serialize();
    formData += '&requestType=UpdateSchedule';

    $.ajax({
        type: "POST",
        url: "Jcontroller.php",
        data: formData,
        dataType: "json",
        success: function(response) {
            if (response.status === 200) {
                Swal.fire({
                    icon: "success",
                    title: "Schedule Updated Successfully",
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    $("#scheduleModal").modal("hide");
                    location.reload();
                });

                // 🔔 Only send email if status is "Scheduled Hearing"
                if(status === "Scheduled Hearing") {
                    $.ajax({
                        type: "POST",
                        url: "blotter_mailer.php",
                        data: {
                            blotter_id: $('#schedule_id').val()
                        },
                        success: function(mailResponse) {
                            console.log("Email notification sent:", mailResponse);
                        },
                        error: function(xhr, status, error) {
                            console.error("Email could not be sent:", error);
                        }
                    });
                }

            } else {
                Swal.fire({
                    icon: "error",
                    title: "Update Failed",
                    text: response.message
                });
            }
        },
        error: function(xhr, status, error) {
            Swal.fire({
                icon: "error",
                title: "Server Error",
                text: "Unable to update the schedule."
            });
            console.error(error);
        }
    });
});







$('.btnEdit').on('click', function(){
        $('#edit_id').val($(this).data('id'));
        $('#edit_complainant').val($(this).data('complainant'));
        $('#edit_complainantage').val($(this).data('complainantage'));
        $('#edit_complainantaddress').val($(this).data('complainantaddress'));
        $('#edit_complainantemail').val($(this).data('complainantemail'));
        $('#edit_complainantphone').val($(this).data('complainantphone'));
        $('#edit_respondent').val($(this).data('respondent'));
        $('#edit_respondentage').val($(this).data('respondentage'));
        $('#edit_respondentaddress').val($(this).data('respondentaddress'));
        $('#edit_type').val($(this).data('type'));
        $('#edit_incidentlocation').val($(this).data('incidentlocation'));
        $('#edit_datereported').val($(this).data('datereported'));
        $('#edit_timereported').val($(this).data('timereported'));
        $('#edit_incidentnarrative').val($(this).data('incidentnarrative'));
        $('#current_doc').text("Current File: " + $(this).data('supportingdocuments'));
        $('#edit_hearingdate').val($(this).data('hearingdate'));
        $('#edit_hearingtime').val($(this).data('hearingtime'));
        $('#edit_scheduledby').val($(this).data('scheduledby'));
        $('#edit_status').val($(this).data('status'));
    });


    $("#editBlotterForm").on("submit", function(e) {
    e.preventDefault();

    var status       = $("#edit_status").val();
    var hearingDate  = $("#edit_hearingdate").val();
    var hearingTime  = $("#edit_hearingtime").val();
    var scheduledBy  = $("#edit_scheduledby").val();
    var email        = $("#edit_complainantemail").val(); // email from form
    var blotterId    = $("#edit_id").val();

    // 🔍 Validation kapag Scheduled Hearing
    if (status === "Scheduled Hearing") {
        if (!hearingDate || !hearingTime || !scheduledBy.trim()) {
            Swal.fire({
                icon: "warning",
                title: "Validation Error",
                text: "Please set Hearing Date, Hearing Time, and Scheduled By when status is Scheduled Hearing."
            });
            return; // stop submission
        }
    }

    var formData = new FormData(this);
    formData.append("requestType", "UpdateBlotter"); 

    // 1️⃣ First, update blotter in database
    $.ajax({
        type: "POST",
        url: "Jcontroller.php",
        data: formData,
        processData: false,
        contentType: false,
        dataType: "json",
        success: function(response) {
            if (response.status === 200) {

                Swal.fire({
                    icon: "success",
                    title: "Blotter Updated Successfully",
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    $("#editModal").modal("hide");
                    location.reload();
                });

            } else {
                Swal.fire({
                    icon: "error",
                    title: "Update Failed",
                    text: response.message
                });
            }
        },
        error: function(xhr, status, error) {
            Swal.fire({
                icon: "error",
                title: "Server Error",
                text: "Unable to update the record."
            });
            console.error(error);
        }
    });
});










    $('#blotterForm').on('submit', function(e) {
        e.preventDefault();

        // Basic validation
        let complainant_name    = $('input[name="complainant_name"]').val().trim();
        let complainant_age     = $('input[name="complainant_age"]').val().trim();
        let complainant_address = $('input[name="complainant_address"]').val().trim();
        let complainant_phone   = $('input[name="complainant_phone"]').val().trim();
        let respondent_name     = $('input[name="respondent_name"]').val().trim();
        let respondent_unknown  = $('#unknownRespondent').is(':checked');
        let blotter_type        = $('select[name="blotter_type"]').val();
        let incident_location   = $('input[name="incident_location"]').val().trim();
        let date_reported       = $('input[name="date_reported"]').val();
        let time_reported       = $('input[name="time_reported"]').val();
        let incident_narrative  = $('textarea[name="incident_narrative"]').val().trim();

        // Check complainant info
        if (complainant_name === "" || complainant_age === "" || complainant_address === "" || complainant_phone === "") {
            Swal.fire({
                icon: 'warning',
                title: 'Missing Fields',
                text: 'Please fill in all complainant information.'
            });
            return;
        }

        // Check respondent (allow Unknown if checked)
        if (!respondent_unknown && respondent_name === "") {
            Swal.fire({
                icon: 'warning',
                title: 'Missing Respondent',
                text: 'Please enter respondent name or check "Unknown".'
            });
            return;
        }

        // Check incident details
        if (blotter_type === "" || incident_location === "" || date_reported === "" || time_reported === "" || incident_narrative === "") {
            Swal.fire({
                icon: 'warning',
                title: 'Incomplete Incident Details',
                text: 'Please complete all incident details before submitting.'
            });
            return;
        }

        // If validation passes, send via AJAX
        var formData = new FormData(this);
        formData.append('requestType', 'AddBlotter');

        $.ajax({
            type: "POST",
            url: "Jcontroller.php",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function(response) {
                if (response.status === 200) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Blotter Successfully Recorded',
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Server Error',
                    text: 'Unable to process your request at the moment.'
                });
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