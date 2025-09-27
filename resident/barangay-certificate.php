<?php
session_start();
include '../connection/config.php';

// ✅ Use active_user_id if set, fallback to user_id
$active_user_id = $_SESSION['active_user_id'] ?? $_SESSION['user_id'] ?? null;

// Redirect to login if not logged in
if (!$active_user_id) {
    header("Location: ../login.php");
    exit();
}

// Fetch user and resident info
$sql = "SELECT u.email, u.image, u.is_logged_in,
               r.first_name, r.middle_name, r.last_name, r.suffix,
               r.address
        FROM tbl_user u
        JOIN tbl_residents r ON u.user_id = r.user_id
        WHERE u.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $active_user_id);
$stmt->execute();
$result = $stmt->get_result();

// Initialize variables
$image = "../dist/assets/images/default_image.png"; // fallback image
$email = "";
$is_logged_in = 0;

$first_name = $middle_name = $last_name = $suffix = "";
$address = "";

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $image = $row["image"] ?: "../dist/assets/images/default_image.png";
    $email = $row["email"];
    $is_logged_in = $row["is_logged_in"];

    $first_name = $row["first_name"];
    $middle_name = $row["middle_name"];
    $last_name = $row["last_name"];
    $suffix = $row["suffix"];

    $address = $row["address"];
}

// Store in session if needed
$_SESSION['image'] = $image;
$_SESSION['is_logged_in'] = $is_logged_in;
$_SESSION['full_name'] = trim($first_name . ' ' . $last_name);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Barangay Certificate Request | Barangay System</title>
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
                            <?php
                // ✅ Get active user ID from session (switched account or original login)
$active_user_id = $_SESSION['active_user_id'] ?? $_SESSION['user_id'] ?? '';

// Handle mark all as read action
if (isset($_GET['mark_all_read']) && $_GET['mark_all_read'] == 1) {
    $markAllQuery = "UPDATE tbl_notifications SET is_read = 1 WHERE user_id = ?";
    $markAllStmt = $conn->prepare($markAllQuery);
    $markAllStmt->bind_param('s', $active_user_id);
    $markAllStmt->execute();
    
    // Redirect back to the current page without the query parameter
    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
    exit;
}

// Handle mark single notification as read
if (isset($_GET['read_notification']) && is_numeric($_GET['read_notification'])) {
    $notificationId = $_GET['read_notification'];
    $markReadQuery = "UPDATE tbl_notifications SET is_read = 1 WHERE notification_id = ? AND user_id = ?";
    $markReadStmt = $conn->prepare($markReadQuery);
    $markReadStmt->bind_param('is', $notificationId, $active_user_id);
    $markReadStmt->execute();
    
    // Redirect to the appropriate page based on notification type
    if (isset($_GET['redirect'])) {
        header('Location: ' . $_GET['redirect']);
        exit;
    }
}

// Fetch notifications for the current user - Only show unread ones and recently read ones (last 24 hours)
$query = "SELECT * FROM tbl_notifications 
        WHERE user_id = ? AND (is_read = 0 OR (is_read = 1 AND date_created > DATE_SUB(NOW(), INTERVAL 24 HOUR)))
        ORDER BY date_created DESC 
        LIMIT 10";
        
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $active_user_id);
$stmt->execute();
$result = $stmt->get_result();
$notifications = [];
while ($row = $result->fetch_assoc()) {
    $notifications[] = $row;
}

// Count unread notifications
$unreadQuery = "SELECT COUNT(*) as count FROM tbl_notifications 
                WHERE user_id = ? AND is_read = 0";
                
$unreadStmt = $conn->prepare($unreadQuery);
$unreadStmt->bind_param('s', $active_user_id);
$unreadStmt->execute();
$unreadResult = $unreadStmt->get_result();
$unreadRow = $unreadResult->fetch_assoc();
$unreadCount = (int)$unreadRow['count'];

// Function to get time ago
function time_ago($timestamp) {
    $time_ago = strtotime($timestamp);
    $current_time = time();
    $time_difference = $current_time - $time_ago;
    
    if ($time_difference < 60) {
        return "Just now";
    } elseif ($time_difference < 3600) {
        $minutes = round($time_difference / 60);
        return $minutes . ' minute' . ($minutes > 1 ? 's' : '') . ' ago';
    } elseif ($time_difference < 86400) {
        $hours = round($time_difference / 3600);
        return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
    } else {
        $days = round($time_difference / 86400);
        return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
    }
}

// Function to get appropriate icon and color based on notification type
function getNotificationStyle($type) {
    $style = [
        'icon' => 'ti-info-alt',
        'bg_color' => 'bg-primary',
        'href' => '#'
    ];
    
    if (strpos($type, 'certification') !== false) {
        $style['icon'] = 'ti-file';
        $style['bg_color'] = 'bg-primary';
        $style['href'] = 'barangay-certificate.php';
    } elseif (strpos($type, 'clearance') !== false) {
        $style['icon'] = 'ti-clipboard';
        $style['bg_color'] = 'bg-success';
        $style['href'] = 'barangay-clearance.php';
    } elseif (strpos($type, 'complain') !== false) {
        $style['icon'] = 'ti-alert';
        $style['bg_color'] = 'bg-warning';
        $style['href'] = 'barangay-complain.php';
    } elseif (strpos($type, 'blotter') !== false) {
        $style['icon'] = 'ti-notepad';
        $style['bg_color'] = 'bg-danger';
        $style['href'] = 'blotter.php';
    } elseif (strpos($type, 'bid') !== false) {
        $style['icon'] = 'ti-id-badge';
        $style['bg_color'] = 'bg-info';
        $style['href'] = 'barangay-id.php';
    }
    
    return $style;
}
                ?>

                <!-- Notification dropdown HTML structure -->
                <li class="nav-item dropdown notification-dropdown">
                    <a class="nav-link dropdown-toggle" id="notificationDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="icon-bell mx-0"></i>
                        <?php if ($unreadCount > 0): ?>
                            <div class="notification-count"><?php echo $unreadCount; ?></div>
                        <?php endif; ?>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
                        <p class="mb-0 font-weight-normal float-left dropdown-header">Notifications</p>
                        
                        <?php if (empty($notifications)): ?>
                            <div class="text-center p-3">No notifications</div>
                        <?php else: ?>
                            <?php foreach ($notifications as $notification): 
                                $style = getNotificationStyle($notification['notification_type']);
                                $notificationLink = $style['href'] . '?read_notification=' . $notification['notification_id'] . '&redirect=' . urlencode($style['href']);
                            ?>
                                <a class="dropdown-item preview-item <?php echo ($notification['is_read'] == 0) ? 'unread-notification' : ''; ?>" href="<?php echo $notificationLink; ?>">
                                    <div class="preview-thumbnail">
                                        <div class="preview-icon <?php echo $style['bg_color']; ?>">
                                            <i class="<?php echo $style['icon']; ?> mx-0"></i>
                                        </div>
                                    </div>
                                    <div class="preview-item-content">
                                        <h6 class="preview-subject font-weight-normal"><?php echo htmlspecialchars($notification['message']); ?></h6>
                                        <p class="font-weight-light small-text mb-0 text-muted">
                                            <?php echo time_ago($notification['date_created']); ?>
                                        </p>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                            
                            <?php if ($unreadCount > 0): ?>
                            <!-- Mark all as read button -->
                            <div class="dropdown-item d-flex justify-content-center border-top pt-3">
                                <a href="?mark_all_read=1" class="text-muted text-small" style="text-decoration: none;">
                                    <i class="ti-check mr-1"></i> Mark all as read
                                </a>
                            </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </li>

                <style>
                /* Updated notification styling */
                .notification-dropdown {
                    position: relative;
                }

                .notification-count {
                    position: absolute;
                    top: 2px;
                    right: 2px;
                    background-color: #ff0000;
                    color: #ffffff;
                    font-size: 10px;
                    height: 16px;
                    width: 16px;
                    line-height: 16px;
                    border-radius: 50%;
                    text-align: center;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }

                .unread-notification {
                    background-color: rgba(0, 123, 255, 0.05);
                }

                /* Ensure the bell icon container has proper positioning */
                .nav-link {
                    position: relative;
                    display: inline-block;
                }
                </style>

                <script>
                // Add this JavaScript to ensure the notification count is visible
                document.addEventListener('DOMContentLoaded', function() {
                    // Check if we have unread notifications
                    var unreadCount = <?php echo $unreadCount; ?>;
                    if (unreadCount > 0) {
                        // Make sure the notification count is visible
                        var countElement = document.querySelector('.notification-count');
                        if (countElement) {
                            countElement.style.display = 'flex';
                        }
                    }
                });
                </script>
                <li class="nav-item nav-profile dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" id="profileDropdown">
                        <img src="../dist/assets/images/default_image.png" alt="profile" />
                    </a>
                    <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                        <a class="dropdown-item" href="profile-management.php">
                            <i class="ti-user text-primary"></i> Profile Management</a>
                        <a class="dropdown-item" href="family.php">
                            <i class="ti-heart text-primary"></i> Household</a>
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

            .page-item.active .page-link {
                background-color: #0e1624 !important;
                border-color: #0e1624 !important;
            }

            .page-item.active .page-link:hover {
                background-color: #0e1624 !important;
            }

            .hidden-section {
                visibility: hidden;
                position: absolute;
                left: -9999px;
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
                        <span class="menu-title">My Request</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="collapse" id="requestManagement">
                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item"> <a class="nav-link" href="barangay-certificate.php">Certificate Request</a></li>
                            <li class="nav-item"> <a class="nav-link" href="barangay-clearance.php">Clearance Request</a></li>
                            <li class="nav-item"> <a class="nav-link" href="barangay-id.php">ID Request</a></li>
                            <li class="nav-item"> <a class="nav-link" href="barangay-complain.php">Complain Request</a></li>
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
                    <a class="nav-link" href="events.php">
                        <i class="fa-solid fa-calendar"></i>
                        <span class="menu-title">Events</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="feedback.php">
                        <i class="fa-solid fa-comments"></i>
                        <span class="menu-title">Feedback</span>
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
                            <li class="nav-item"> <a class="nav-link" href="profile-management.php">Profile Management</a></li>
                            <li class="nav-item"> <a class="nav-link" href="family.php">Household</a></li>
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
                            <div class="col-12 col-xl-8 mb-4 mb-xl-0">

                            </div>
                            <div class="col-12 col-xl-4">
                                <div class="justify-content-end d-flex">
                                    <div class="dropdown flex-md-grow-1 flex-xl-grow-0">


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Request Certificate Modal -->
                <div class="modal fade" id="CertificateModal" tabindex="-1" role="dialog" aria-labelledby="CertificateModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="CertificateModalLabel">Request Certificate</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="add_certificate.php" method="POST" enctype="multipart/form-data" novalidate id="certificateRequestForm" class="needs-validation">
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="certificationType">Certificate Type</label>
                                        <div class="input-group">
                                        <select class="form-control" id="certificationType" name="certificationType" required>
                                            <option value="">Select</option>
                                                <option value="Good Moral">Good Moral Character</option>
                                                <option value="First Time Job Seeker">First Time Job Seeker</option>
                                                <option value="Calamity">Calamity</option>
                                            </select>
                                            <div class="input-group-append">
                                                <span class="input-group-text validation-icon" id="certificationType_validation">
                                                    <i class="fas fa-check text-success d-none"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>


                                    <div id="calamityDetails" class="border rounded p-3 mt-3 hidden-section bg-light">
                                        
                                        <div class="form-group">
                                            <label for="calamityType">Type of Calamity</label>
                                            <select class="form-control" name="calamityType" id="calamityType">
                                                <option value="Typhoon">Typhoon</option>
                                                <option value="Fire">Fire</option>
                                            </select>
                                        </div>

                                        
                                      <!-- Date + Conditional Time -->
                                        <div class="form-group">
                                            <label for="calamityDate">When did it happen? (Date)</label>
                                            <input type="date" class="form-control mb-2" id="calamityDate" name="calamityDate">

                                          <div class="form-group" id="fireTimeWrapper" style="display:none;">
                                            <label for="calamityTimeFire">When did it happen? (Time)</label>
                                            <input type="time" class="form-control" id="calamityTimeFire" name="calamityTimeFire">
                                        </div>

                                        </div>


                                        

                                        <div class="form-group">
                                            <label for="requestedBy">Requested By</label>
                                            <input type="text" class="form-control" id="requestedBy" name="requestedBy" placeholder="Name of person requesting">
                                        </div>
                                        <div class="form-group">
                                            <label for="calamityNotes">Additional Notes (Optional)</label>
                                            <textarea class="form-control" id="calamityNotes" name="calamityNotes" rows="3" placeholder="Any other relevant information"></textarea>
                                        </div>

                                        <div class="form-group" id="causeWrapper" style="display:block;">
                                            <label for="calamityCaused">What is caused?</label>
                                            <input type="text" class="form-control" id="calamityCaused" name="calamityCaused">
                                        </div>


                                        <div class="form-group" id="locationWrapper" style="display:none;">
                                            <label for="calamityLocation">Location</label>
                                            <input type="text" class="form-control" id="calamityLocation" name="calamityLocationFire">
                                        </div>


                                        <div class="form-group" id="purposeWrapper">
                                            <label for="calamityPurpose">Purpose</label>
                                            <select class="form-control" name="calamityPurpose" id="calamityPurpose">
                                                <option value="Calamity Claim Purposes">Calamity Claim Purposes </option>
                                                <option value="Calamity Leave Purposes">Calamity Leave Purposes</option>
                                                <option value="SELA">SELA</option>
                                                <option value="Supporting Document For Submmission">Supporting Document For Submmission</option>
                                                <option value="Fire Victim Purposes" style="display:none;">Fire Victim Purposes</option>
                                             </select>
                                        </div>


                                    </div>

                                    <br>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="last_name">Last Name</label>
                                                <input type="text" class="form-control" id="last_name" name="last_name"
                                                    value="<?= htmlspecialchars($last_name) ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="first_name">First Name</label>
                                                <input type="text" class="form-control" id="first_name" name="first_name"
                                                    value="<?= htmlspecialchars($first_name) ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="middle_name">Middle Name</label>
                                                <input type="text" class="form-control" id="middle_name" name="middle_name"
                                                    value="<?= htmlspecialchars($middle_name) ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="suffix">Suffix</label>
                                                <input type="text" class="form-control" id="suffix" name="suffix"
                                                    value="<?= htmlspecialchars($suffix) ?>" readonly>
                                            </div>
                                        </div>
                                    </div>

                                <div class="row">
                                    <div class="col-md-6">

                                        <div class="form-group">
                                            <label for="address">Address</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="address" name="address"
                                                    value="<?= htmlspecialchars($address) ?>" readonly>
                                                <div class="input-group-append">
                                                    <span class="input-group-text validation-icon" id="address_validation">
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        
                                        
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="resident_status">Are you a New Resident?</label>
                                            <div class="input-group">
                                                <select class="form-control" id="resident_status" name="resident_status" required>
                                                    <option value="">Select</option>
                                                    <option value="Yes">Yes</option>
                                                    <option value="No">No</option>
                                                </select>
                                                <div class="input-group-append">
                                                    <span class="input-group-text validation-icon" id="resident_status_validation">
                                                        <i class="fas fa-check text-success d-none"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                    

                                <div class="form-group" id="purposeField">
                                    <label for="purpose">Purpose</label>
                                    <div class="input-group">
                                        <select class="form-control" id="purpose" name="purpose" required onchange="toggleOtherPurpose()">
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
                                                <option value="N/A" hidden>N/A</option>
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

                                    
                                    <?php
                                        // Calculate the latest birthday allowed (18 years ago from today)
                                        $maxBirthday = date('Y-m-d', strtotime('-18 years'));
                                    ?>

                                    <!-- Document Upload Section -->
                                    <div class="mb-4">
                                        <h5 class="border-bottom pb-2"><i class="fas fa-file-upload me-2"></i>Supporting Documents</h5>
                                        <div class="file-upload">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                            <h5>Upload Supporting Document/Image</h5>
                                            <p>Please upload any required documents (Valid ID, Proof of Residency, etc.)</p>
                                            <input type="file" class="form-control" id="document_path" name="document_path[]" multiple>
                                            <div class="file-upload-info mt-2">
                                                <small><i class="fas fa-info-circle me-1"></i>Accepted formats: PDF, JPG, PNG (Max size: 5MB)</small> <br>
                                                <small><i class="fas fa-info-circle me-1"></i>Hold Ctrl to select multiple files.</small>
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
                                    <button type="submit" class="btn btn-primary">Submit Certificate Request</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        

                        // Display file name when selected
                        document.getElementById('document_path').addEventListener('change', function () {
                            const files = this.files;
                            let fileListHTML = '<ul class="mt-2 mb-0">';
                            
                            for (let i = 0; i < files.length; i++) {
                                fileListHTML += '<li><i class="fas fa-check-circle text-success me-2"></i><strong>' + files[i].name + '</strong></li>';
                            }
                            
                            fileListHTML += '</ul>';

                            const existingInfo = document.querySelector('.file-selected-info');
                            if (existingInfo) {
                                existingInfo.remove();
                            }

                            const fileInfo = document.createElement('div');
                            fileInfo.className = 'file-selected-info';
                            fileInfo.innerHTML = fileListHTML;
                            this.parentNode.appendChild(fileInfo);
                        });
                    });
                </script>

                <?php
                include '../connection/config.php';

// ✅ Determine active user
$user_id = $_SESSION['active_user_id'] ?? $_SESSION['user_id'] ?? null;

// Redirect if not logged in
if (!$user_id) {
    header("Location: ../login.php");
    exit();
}

// Check for success messages
if (isset($_GET['success'])) {
    $successMessages = [
        1 => "Certificate Request Submitted Successfully"
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
$search = $_GET['search'] ?? '';
$page = $_GET['page'] ?? 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Date filter variables
$date_from = $_GET['date_from'] ?? '';
$date_to = $_GET['date_to'] ?? '';

// Build WHERE clause for both queries
$where_conditions = ["c.user_id = ?"];
$params = [$user_id];
$types = "i"; // assuming user_id is integer

// Enhanced search: Get all column names from the tbl_certification table
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
$where_clause = implode(" AND ", $where_conditions);

// Count total records for pagination
$count_sql = "SELECT COUNT(*) as total FROM tbl_certification c
    WHERE $where_clause";

$count_stmt = $conn->prepare($count_sql);
$count_stmt->bind_param($types, ...$params);
$count_stmt->execute();
$count_result = $count_stmt->get_result();
$total_rows = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);
$count_stmt->close();

// Fetch certification requests query
$sql = "SELECT c.*, c.remarks,u.*
        FROM tbl_certification c
        left join tbl_residents u on c.user_id = u.user_id
        WHERE $where_clause
        ORDER BY c.created_at DESC 
        LIMIT ? OFFSET ?";

// Add limit and offset params
$params[] = $limit;
$params[] = $offset;
$types .= "ii";

// Prepare and execute statement
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
                                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                                    <p class="card-title mb-0">Barangay Certificate Requests</p>
                                    <div class="ml-auto">
                                        <button class="btn btn-primary mb-3" data-toggle="modal"
                                            data-target="#CertificateModal">Request</button>
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
                                                    <button type="button" class="btn btn-outline-secondary" id="clearButton"
                                                        style="padding:10px;">&times;</button>
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
                                            <?php if ($result->num_rows > 0): ?>
                                                <?php while ($row = $result->fetch_assoc()): ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($row['certification_id']); ?></td>
                                                        <td><?= htmlspecialchars(implode(' ', [$row['first_name'], $row['middle_name'], $row['last_name']])) ?></td>
                                                        <td><?php echo htmlspecialchars($row['certificationType']); ?></td>
                                                        <td>
                                                            <?php 
                                                                if ($row['certificationType'] === "Calamity") {
                                                                    echo htmlspecialchars($row['calamity_purpose']);
                                                                } else {
                                                                    echo htmlspecialchars($row['purpose']);
                                                                }
                                                            ?>
                                                        </td>

                                                        <td><?php echo date('F d, Y h:i A', strtotime($row['dateApplied'])); ?></td>
                                                        <td>
                                                            <span class="badge 
                                                <?php
                                                    if ($row['status'] == 'Approved') echo 'badge-warning text-white font-weight-bold';
                                                    elseif ($row['status'] == 'On Going') echo 'badge-info text-white font-weight-bold';
                                                    elseif ($row['status'] == 'Denied') echo 'badge-danger text-white font-weight-bold';
                                                    elseif ($row['status'] == 'Resumbit') echo 'badge-secondary text-white font-weight-bold';
                                                    elseif ($row['status'] == 'Completed') echo 'badge-success text-white font-weight-bold';
                                                    else echo 'badge-warning text-white font-weight-bold';
                                                ?>">
                                                                <?php echo htmlspecialchars(ucfirst($row['status'])); ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-info btn-sm" data-toggle="modal"
                                                                data-target="#viewModal<?php echo $row['certification_id']; ?>"><i
                                                                    class="fa-solid fa-eye"></i></button>

                                                        </td>
                                                    </tr>

                                                    <!-- View Modal -->
                                                    <div class="modal fade" id="viewModal<?php echo $row['certification_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg" role="document">
                                                            <div class="modal-content shadow-lg border-0">
                                                                <!-- Enhanced Header with gradient background -->
                                                                <div class="modal-header bg-gradient-primary text-white py-3">
                                                                    <h5 class="modal-title font-weight-bold" id="viewModalLabel">
                                                                        <i class="fas fa-file-alt mr-2"></i>Certificate Request Details
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
                                                    elseif ($row['status'] == 'Resumbit') echo 'badge-secondary';
                                                    elseif ($row['status'] == 'Completed') echo 'badge-primary';
                                                    else echo 'badge-warning';
                        ?>">
                                                                            <i class="fas 
                            <?php
                                                    if ($row['status'] == 'Approved') echo 'fa-check-circle';
                                                    elseif ($row['status'] == 'On Going') echo 'fa-clock';
                                                    elseif ($row['status'] == 'Denied') echo 'fa-times-circle';
                                                    elseif ($row['status'] == 'Resumbit') echo 'fa-redo';
                                                    elseif ($row['status'] == 'Completed') echo 'fa-check-double';
                                                    else echo 'fa-exclamation-circle';
                            ?> mr-1"></i>
                                                                            <?php echo htmlspecialchars(ucfirst($row['status'])); ?>
                                                                        </span>
                                                                    </div>

                                                                    <!-- Card container for details -->
                                                                    <div class="card border-0 shadow-sm mb-4">
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
                                                                        <div class="card-header bg-light py-3">
                                                                            <h6 class="font-weight-bold text-primary mb-0">
                                                                                <i class="fas fa-user mr-2"></i>Personal Information
                                                                            </h6>
                                                                        </div>
                                                                        <div class="card-body">
                                                                            <div class="row">
                                                                                <div class="col-md-6">
                                                                                    <div class="info-group mb-3">
                                                                                        <label class="text-muted small text-uppercase">Certification ID</label>
                                                                                        <p class="font-weight-bold mb-2"><?php echo htmlspecialchars($row['certification_id']); ?></p>
                                                                                    </div>
                                                                                    <div class="info-group mb-3">
                                                                                        <label class="text-muted small text-uppercase">Name</label>
                                                                                        <p class="font-weight-bold mb-2"><?php echo htmlspecialchars($row['name']); ?></p>
                                                                                    </div>
                                                                                    <div class="info-group mb-3">
                                                                                        <label class="text-muted small text-uppercase">Certification Type</label>
                                                                                        <p class="font-weight-bold mb-2"><?php echo htmlspecialchars($row['certificationType']); ?></p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    
                                                                                    <div class="info-group mb-3">
                                                                                        <label class="text-muted small text-uppercase">Date Applied</label>
                                                                                        <p class="font-weight-bold mb-2"><?php echo date('F d, Y h:i A', strtotime($row['dateApplied'])); ?></p>
                                                                                    </div>
                                                                                    <div class="info-group mb-3">
                                                                                        <label class="text-muted small text-uppercase">Registered Voter</label>
                                                                                        <p class="font-weight-bold mb-2">
                                                                                            <?php echo ($row['registeredVoter'] == 1) ? 'Yes' : 'No'; ?>
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
                                                                                <i class="fas fa-info-circle mr-2"></i>Additional Information
                                                                            </h6>
                                                                        </div>
                                                                        <div class="card-body">
                                                                            <div class="row">
                                                                                <div class="col-md-6">
                                                                                    <div class="info-group mb-3">
                                                                                        <label class="text-muted small text-uppercase">Address</label>
                                                                                        <p class="font-weight-bold mb-2"><?php echo htmlspecialchars($row['address']); ?></p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <div class="info-group mb-3">
                                                                                        <label class="text-muted small text-uppercase">Purpose</label>
                                                                                        <p class="font-weight-bold mb-2"><?php echo htmlspecialchars($row['purpose']); ?></p>
                                                                                    </div>
                                                                                </div>
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

                                                                <div class="modal-footer bg-light">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                                        <i class="fas fa-times mr-1"></i> Close
                                                                    </button>

                                                                </div>
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
                                                    style="background:color:#141E30 !important;"><?php echo $i; ?></a>
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
                        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block font-weight-bold">Copyright ©
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $("#calamityType").on("change", function () {
                if ($(this).val() === "Fire") {
                    // Show time input
                    $("#fireTimeWrapper").show();

                    // Show Location & Cause fields
                    $("#locationWrapper").show();
                    // $("#causeWrapper").show();

                    // Auto-select "Fire Victim Purposes"
                    $("#calamityPurpose").val("Fire Victim Purposes");

                    // Hide Purpose dropdown
                    $("#purposeWrapper").hide();
                } else {
                    // Hide time input
                    $("#fireTimeWrapper").hide();

                    // Hide Location & Cause fields
                    $("#locationWrapper").hide();
                    // $("#causeWrapper").hide();

                    // Reset Purpose dropdown
                    $("#calamityPurpose").val("");

                    // Show Purpose dropdown again
                    $("#purposeWrapper").show();
                }
            });
        });


        
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

        document.addEventListener('DOMContentLoaded', function () {
            const typeSelect = document.getElementById('certificationType');
            const calamityDetails = document.getElementById('calamityDetails');
            const purposeField = document.getElementById('purposeField');
            const form = document.getElementById('certificateRequestForm');  // Get the form element

            function handleCertificationChange() {
                const selectedType = typeSelect.value;
                if (selectedType === 'Calamity') {
                    calamityDetails.classList.remove('hidden-section');
                    if (purposeField) purposeField.classList.add('hidden-section');
                } else {
                    calamityDetails.classList.add('hidden-section');
                    if (purposeField) purposeField.classList.remove('hidden-section');
                }
            }

            if (typeSelect) {
                typeSelect.addEventListener('change', handleCertificationChange);
                handleCertificationChange(); // Initialize on page load
            }

            // Ensure form is validated correctly on submission
            form.addEventListener('submit', function (e) {
                // Get form data
                const certificationType = document.querySelector('input[name="certificationType"]'.value);
                const purpose = document.querySelector('select[name="purpose"]').value;
                const otherPurpose = document.querySelector('input[name="other_purpose"]').value;
                const calamityFields = document.querySelectorAll('input[name^="calamity"]'); // All calamity fields
                
                let isValid = true;

                // Case: If Calamity is selected, set purpose and other_purpose to N/A
                if (certificationType && certificationType.value === 'Calamity') {
                    // Set purpose and other_purpose to 'N/A' for Calamity
                    document.querySelector('select[name="purpose"]').value = 'N/A';
                    document.querySelector('input[name="other_purpose"]').value = 'N/A';

                    // Ensure calamity fields are filled
                    //let calamityValid = true;
                    
                    //calamityDetails.classList.add('hidden-section');
                    calamityFields.forEach(field => {
                        field.setAttribute('required', 'required');
                        if (!field.value.trim()) {
                            isValid = false;
                        }
                    });

                    if (!isValid) {
                        e.preventDefault();
                        form.classList.add('was-validated');
                        alert('❌ Please complete all required Calamity fields!');
                        return false;
                    }
                } else if (certificationType && (certificationType.value === 'Good Moral' || certificationType.value === 'First Time Job Seeker')) {
                    // Case: If Good Moral or First Time Job Seeker is selected, set calamity fields to 'N/A'
                    calamityFields.forEach(field => {
                        field.value = 'N/A'; 
                        field.removeAttribute('required'); // always remove required
                    });
                
                    // Validate 'Other' purpose
                    if (purpose === 'Other' && !otherPurpose.trim()) {
                        e.preventDefault();
                        form.classList.add('was-validated');
                        alert('❌ Please specify your purpose!');
                        return false;
                    }
                } else {
                    // Regular case: Validate other purposes
                    if (purpose === 'Other' && !otherPurpose.trim()) {
                        e.preventDefault();
                        form.classList.add('was-validated');
                        alert('❌ Please specify your purpose!');
                        return false;
                    }
                
                    // Ensure calamity fields are never required if not Calamity
                    calamityFields.forEach(field => {
                        field.removeAttribute('required');
                    });
                }

            });
        });

        
    </script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <!-- Add SweetAlert script -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script src="../dist/assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="../dist/assets/vendors/chart.js/chart.umd.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
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
    <!-- <script src="assets/js/Chart.roundedBarCharts.js"></script> -->
    <!-- End custom js for this page-->

</body>

</html>