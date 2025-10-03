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

// Fetch the user's data from the "users" table based on the user ID
$id = $_SESSION['user_id'];
$sql = "SELECT u.email, u.mobile, u.image, u.is_logged_in,
               b.first_name, b.middle_name, b.last_name
        FROM tbl_user u
        JOIN tbl_brgyofficer b ON u.user_id = b.user_id
        WHERE u.user_id = '$id'";
$result = $conn->query($sql);

// Initialize the variables with default values
$image = "default_image.jpg"; // Assuming default image name
$first_name = "";
$last_name = "";
$mobile = "";
$email = "";
$is_logged_in = 0; // Default to not logged in

// Check if the query was successful
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $image = $row["image"];
    $full_name = $_SESSION['full_name']; // Fetch full name from session
    $mobile = $row["mobile"];
    $email = $row["email"];
    $is_logged_in = $row['is_logged_in']; // Now safe to use
}

// Assign the value of $username and $uploadID to $_SESSION variables
$_SESSION['image'] = $image;
$_SESSION['is_logged_in'] = $is_logged_in; // Using the initialized variable
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Events | Barangay System</title>
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
            .btn-primary:hover {
                background-color: #0e1624 !important;
            }
            .page-item.active .page-link {
                background-color: #0e1624 !important;
                border-color: #0e1624 !important;
            }

            .page-item.active .page-link:hover {
                background-color: #0e1624 !important;
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
                    <a class="nav-link" href="residents.php">
                        <i class="fa-solid fa-user"></i>
                        <span class="menu-title">Residents</span>
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
                    <a class="nav-link" href="events.php">
                        <i class="fa-solid fa-calendar"></i>
                        <span class="menu-title">Events</span>
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
                            <li class="nav-item"> <a class="nav-link" href="logout.php">Logout</a></li>
                        </ul>
                    </div>
                </li>
            </ul>
        </nav>

        <!-- partial -->
        <div class="main-panel">
            <div class="content-wrapper">

                <?php
                   if (isset($_GET['success'])) {
                    $successMessages = [
                        1 => "Events Added Successfully",
                        2 => "Events Updated Successfully",
                        3 => "Your Successfully Comment",
                        4 => "You Banned/Unbaned User Successfully"
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

                // Check if the user is banned
                function isUserBanned($conn, $user_id)
                {
                    $stmt = $conn->prepare("SELECT * FROM tbl_banned_users WHERE user_id = ? AND status = 'Active'");
                    $stmt->bind_param("s", $user_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    return $result->num_rows > 0;
                }

                // Get user's position if they are a barangay official
                function getUserPosition($conn, $user_id)
                {
                    $stmt = $conn->prepare("SELECT position FROM tbl_brgyofficer WHERE user_id = ? AND status = 'Active'");
                    $stmt->bind_param("s", $user_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result->num_rows > 0) {
                        return $result->fetch_assoc()['position'];
                    }
                    return null;
                }

                // Check if user is admin or Barangay Secretary
                function canManageEvents($conn, $user_id)
                {
                    // Check if user is admin
                    $stmt = $conn->prepare("SELECT role FROM tbl_user WHERE user_id = ?");
                    $stmt->bind_param("s", $user_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        if ($row['role'] === 'admin') {
                            return true;
                        }
                    }

                    // Check if user is Barangay Secretary
                    $position = getUserPosition($conn, $user_id);
                    if ($position === 'Barangay Secretary') {
                        return true;
                    }

                    return false;
                }



                // Initialize variables
                $search = $_GET['search'] ?? '';
                $page = $_GET['page'] ?? 1;
                $limit = 10;
                $offset = ($page - 1) * $limit;

                // Check if user can manage events
                $can_manage_events = canManageEvents($conn, $_SESSION['user_id']);

                // Build WHERE clause based on search
                $where_conditions = [];
                $params = [];
                $types = "";

                if (!empty($search)) {
                    $where_conditions[] = "(e.title LIKE ? OR e.description LIKE ?)";
                    $params[] = "%" . $search . "%";
                    $params[] = "%" . $search . "%";
                    $types .= "ss";
                }

                // Combine WHERE conditions
                $where_clause = !empty($where_conditions) ? " WHERE " . implode(" AND ", $where_conditions) : "";

                // Count total records for pagination
                $count_sql = "SELECT COUNT(*) as total FROM tbl_event e $where_clause";

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

                // Fetch events querys
                $sql = "SELECT e.*, 
         u.first_name, 
         CONCAT(IFNULL(bo.first_name, r.first_name), ' ', IFNULL(bo.last_name, r.last_name)) AS author_name,
         bo.position,
         (SELECT COUNT(*) FROM tbl_event_comments WHERE event_id = e.event_id) as comment_count
       FROM tbl_event e
       LEFT JOIN tbl_user u ON e.user_id = u.user_id
       LEFT JOIN tbl_brgyofficer bo ON e.brgyOfficer_id = bo.brgyOfficer_id
       LEFT JOIN tbl_residents r ON e.res_id = r.res_id
       $where_clause
       ORDER BY e.dateCreated DESC 
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
                $events_result = $stmt->get_result();
                ?>

                <!-- Events Module UI -->
                <div class="row">
                    <div class="col-md-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <!-- Header section with title and add button -->
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h4 class="card-title">Barangay Events</h4>
                                    <?php if ($can_manage_events): ?>
                                        <button class="btn btn-primary" data-toggle="modal" data-target="#addEventModal">
                                            <i class="fas fa-plus-circle mr-2"></i>Add New Event
                                        </button>
                                    <?php endif; ?>
                                </div>

                                <!-- Search section -->
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <form method="GET" action="" class="form-inline" id="searchForm">
                                            <div class="input-group mb-2 mr-sm-2">
                                                <input type="text" name="search" id="searchInput" class="form-control"
                                                    value="<?php echo htmlspecialchars($search); ?>"
                                                    placeholder="Search events">
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-outline-secondary"
                                                        id="clearButton" style="padding:10px;">&times;</button>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-primary mb-2"><i class="fa-solid fa-magnifying-glass"></i></button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Events List -->
                                <div class="row">
                                    <?php if ($events_result->num_rows > 0): ?>
                                        <?php while ($event = $events_result->fetch_assoc()): ?>
                                            <div class="col-md-6 mb-4">
                                                <div class="card h-100 shadow-sm">
                                                    <!-- Event Image -->
                                                    <?php if (!empty($event['image'])): ?>
                                                        <img src="../dist/assets/images/uploads/events/<?php echo htmlspecialchars($event['image']); ?>"
                                                            class="card-img-top" alt="Event Image"
                                                            style="height: 200px; object-fit: cover;">
                                                    <?php else: ?>
                                                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                                            style="height: 200px;">
                                                            <i class="fas fa-calendar-alt fa-4x text-muted"></i>
                                                        </div>
                                                    <?php endif; ?>

                                                    <div class="card-body">
                                                        <!-- Event Title -->
                                                        <h5 class="card-title"><?php echo htmlspecialchars($event['title']); ?></h5>

                                                        <!-- Event Date & Author -->
                                                        <div class="d-flex justify-content-between mb-2">
                                                            <small class="text-muted">
                                                                <i class="fas fa-calendar-alt mr-1"></i>
                                                                <?php echo date('F d, Y', strtotime($event['dateCreated'])); ?>
                                                            </small>
                                                            <small class="text-muted">
                                                                <img src="../dist/assets/images/user/<?php echo $_SESSION['image']; ?>" class="mr-3 rounded-circle" alt="User Avatar" width="50" height="50" />
                                                                <?php echo htmlspecialchars($event['author_name'] ?? ''); ?>
                                                                <?php if (!empty($event['position'])): ?>
                                                                    <span class="badge badge-info text-white font-weight-bold"><?php echo htmlspecialchars($event['position']); ?></span>
                                                                <?php endif; ?>
                                                            </small>
                                                        </div>

                                                        <!-- Event Description (Truncated) -->
                                                        <p class="card-text">
                                                            <?php
                                                            echo substr(htmlspecialchars($event['description']), 0, 100);
                                                            if (strlen($event['description']) > 100) echo '...';
                                                            ?>
                                                        </p>

                                                        <!-- Action Buttons -->
                                                        <div class="d-flex justify-content-between mt-3">
                                                            <button class="btn btn-outline-primary btn-sm"
                                                                data-toggle="modal"
                                                                data-target="#viewEventModal<?php echo $event['event_id']; ?>">
                                                                <i class="fas fa-eye mr-1"></i> View Details
                                                            </button>

                                                            <span class="text-muted">
                                                                <i class="fas fa-comments mr-1"></i>
                                                                <?php echo $event['comment_count']; ?> Comments
                                                            </span>
                                                        </div>

                                                        <?php if ($can_manage_events): ?>
                                                            <div class="mt-2">
                                                                <button class="btn btn-warning btn-sm btn-block"
                                                                    data-toggle="modal"
                                                                    data-target="#editEventModal<?php echo $event['event_id']; ?>">
                                                                    <i class="fas fa-edit mr-1"></i> Edit Event
                                                                </button>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- View Event Modal -->
                                            <div class="modal fade" id="viewEventModal<?php echo $event['event_id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-primary text-white">
                                                            <h5 class="modal-title">
                                                                <i class="fas fa-calendar-day mr-2"></i>
                                                                <?php echo htmlspecialchars($event['title']); ?>
                                                            </h5>
                                                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <!-- Event Details -->
                                                            <div class="mb-4">
                                                                <?php if (!empty($event['image'])): ?>
                                                                    <img src="../dist/assets/images/uploads/events/<?php echo htmlspecialchars($event['image']); ?>"
                                                                        class="img-fluid rounded mb-3" alt="Event Image">
                                                                <?php endif; ?>

                                                                <div class="d-flex justify-content-between mb-3">
                                                                    <div>
                                                                        <span class="badge badge-secondary text-white font-weight-bold">
                                                                            <i class="fas fa-calendar-alt mr-1"></i>
                                                                            Posted: <?php echo date('F d, Y', strtotime($event['dateCreated'])); ?>
                                                                        </span>
                                                                        <?php if ($event['dateCreated'] != $event['lastEdited']): ?>
                                                                            <span class="badge badge-info ml-2 text-white font-weight-bold">
                                                                                <i class="fas fa-edit mr-1"></i>
                                                                                Edited: <?php echo date('F d, Y', strtotime($event['lastEdited'])); ?>
                                                                            </span>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                    <span class="badge badge-primary text-white font-weight-bold">
                                                                        <i class="fas fa-user mr-1"></i>
                                                                        <?php echo htmlspecialchars($event['author_name']); ?>
                                                                        <?php if (!empty($event['position'])): ?>
                                                                            - <?php echo htmlspecialchars($event['position']); ?>
                                                                        <?php endif; ?>
                                                                    </span>
                                                                </div>

                                                                <div class="card mb-4">
                                                                    <div class="card-body">
                                                                        <p class="card-text"><?php echo nl2br(htmlspecialchars($event['description'])); ?></p>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Comments Section -->
                                                            <div class="card">
                                                                <div class="card-header bg-light">
                                                                    <h6 class="mb-0">
                                                                        <i class="fas fa-comments mr-2"></i>Comments
                                                                    </h6>
                                                                </div>
                                                                <div class="card-body">
                                                                    <!-- Comments List -->
                                                                    <div class="comments mb-4">
                                                                        <?php
                                                                        // Fetch comments for this event
                                                                        $comment_sql = "SELECT c.*, 
                        u.first_name,
                        COALESCE(bo.first_name, r.first_name) AS first_name,
                        COALESCE(bo.last_name, r.last_name) AS last_name,
                        u.image AS user_image
                    FROM tbl_event_comments c
                    LEFT JOIN tbl_user u ON c.user_id = u.user_id
                    LEFT JOIN tbl_brgyofficer bo ON c.user_id = bo.user_id
                    LEFT JOIN tbl_residents r ON c.user_id = r.user_id
                    WHERE c.event_id = ?
                    ORDER BY c.comment_date DESC";
                                                                        $comment_stmt = $conn->prepare($comment_sql);
                                                                        $comment_stmt->bind_param("i", $event['event_id']);
                                                                        $comment_stmt->execute();
                                                                        $comments = $comment_stmt->get_result();
                                                                        ?>

                                                                        <?php if ($comments->num_rows > 0): ?>
                                                                            <?php while ($comment = $comments->fetch_assoc()): ?>
                                                                                <div class="media mb-3 p-3 <?php echo $comment['is_official'] ? 'bg-light border-left border-primary' : ''; ?> rounded">
                                                                                    <!-- User Avatar -->
                                                                                    <img src="<?php echo !empty($comment['user_image']) ? '../dist/assets/images/user/' . $comment['user_image'] : '../assets/images/default-avatar.png'; ?>"
                                                                                        class="mr-3 rounded-circle" alt="User Avatar" width="50" height="50">

                                                                                    <div class="media-body">
                                                                                        <div class="d-flex justify-content-between align-items-center">
                                                                                            <h6 class="mt-0 mb-1">
                                                                                                <?php echo htmlspecialchars($comment['first_name'] . ' ' . $comment['last_name']); ?>

                                                                                                <?php if ($comment['is_official']): ?>
                                                                                                    <span class="badge badge-primary ml-2 text-white font-weight-bold">
                                                                                                        <?php echo htmlspecialchars($comment['position']); ?>
                                                                                                    </span>
                                                                                                <?php endif; ?>
                                                                                            </h6>

                                                                                            <small class="text-muted">
                                                                                                <?php echo date('M d, Y h:i A', strtotime($comment['comment_date'])); ?>
                                                                                            </small>
                                                                                        </div>

                                                                                        <p class="mb-1"><?php echo nl2br(htmlspecialchars($comment['comment'])); ?></p>

                                                                                        <!-- Ban User Button (for admins and Barangay Secretary) -->
                                                                                        <?php if ($can_manage_events && $comment['user_id'] != $_SESSION['user_id']): ?>
                                                                                            <?php
                                                                                            $is_banned = isUserBanned($conn, $comment['user_id']);
                                                                                            ?>
                                                                                            <div class="mt-2">
                                                                                                <?php if ($is_banned): ?>
                                                                                                    <button class="btn btn-sm btn-outline-success"
                                                                                                        data-toggle="modal"
                                                                                                        data-target="#unbanUserModal<?php echo $comment['comment_id']; ?>">
                                                                                                        <i class="fas fa-user-check mr-1"></i> Unban User
                                                                                                    </button>
                                                                                                <?php else: ?>
                                                                                                    <button class="btn btn-sm btn-outline-danger"
                                                                                                        data-toggle="modal"
                                                                                                        data-target="#banUserModal<?php echo $comment['comment_id']; ?>">
                                                                                                        <i class="fas fa-user-slash mr-1"></i> Ban User
                                                                                                    </button>
                                                                                                <?php endif; ?>
                                                                                            </div>
                                                                                        <?php endif; ?>
                                                                                    </div>
                                                                                </div>

                                                                                <!-- Ban User Modal -->
                                                                                <div class="modal fade" id="banUserModal<?php echo $comment['comment_id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                                                        <div class="modal-content">
                                                                                            <div class="modal-header bg-danger text-white">
                                                                                                <h5 class="modal-title">
                                                                                                    <i class="fas fa-user-slash mr-2"></i>Ban User
                                                                                                </h5>
                                                                                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                                                                    <span aria-hidden="true">&times;</span>
                                                                                                </button>
                                                                                            </div>
                                                                                            <form method="POST" action="banned.php">
                                                                                                <div class="modal-body">
                                                                                                    <input type="hidden" name="ban_action" value="ban">
                                                                                                    <input type="hidden" name="user_id_to_ban" value="<?php echo $comment['user_id']; ?>">

                                                                                                    <p>Are you sure you want to ban <?php echo htmlspecialchars($comment['first_name'] . ' ' . $comment['last_name']); ?>?</p>

                                                                                                    <div class="form-group">
                                                                                                        <label for="ban_reason">Reason for Ban:</label>
                                                                                                        <textarea class="form-control" name="ban_reason" rows="3" required></textarea>
                                                                                                    </div>

                                                                                                    <div class="alert alert-warning">
                                                                                                        <i class="fas fa-exclamation-triangle mr-2"></i>
                                                                                                        Banned users will not be able to comment on any events.
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="modal-footer">
                                                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                                                    <button type="submit" class="btn btn-danger">Ban User</button>
                                                                                                </div>
                                                                                            </form>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                <!-- Unban User Modal -->
                                                                                <div class="modal fade" id="unbanUserModal<?php echo $comment['comment_id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                                                        <div class="modal-content">
                                                                                            <div class="modal-header bg-success text-white">
                                                                                                <h5 class="modal-title">
                                                                                                    <i class="fas fa-user-check mr-2"></i>Unban User
                                                                                                </h5>
                                                                                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                                                                    <span aria-hidden="true">&times;</span>
                                                                                                </button>
                                                                                            </div>
                                                                                            <form method="POST" action="banned.php">
                                                                                                <div class="modal-body">
                                                                                                    <input type="hidden" name="ban_action" value="unban">
                                                                                                    <input type="hidden" name="user_id_to_ban" value="<?php echo $comment['user_id']; ?>">

                                                                                                    <p>Are you sure you want to unban <?php echo htmlspecialchars($comment['first_name'] . ' ' . $comment['last_name']); ?>?</p>

                                                                                                    <div class="alert alert-success">
                                                                                                        <i class="fas fa-info-circle mr-2"></i>
                                                                                                        Unbanning this user will allow them to comment on events again.
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="modal-footer">
                                                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                                                    <button type="submit" class="btn btn-success">Unban User</button>
                                                                                                </div>
                                                                                            </form>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            <?php endwhile; ?>
                                                                        <?php else: ?>
                                                                            <div class="alert alert-info">
                                                                                <i class="fas fa-info-circle mr-2"></i>
                                                                                No comments yet. Be the first to comment!
                                                                            </div>
                                                                        <?php endif; ?>
                                                                    </div>

                                                                    <!-- Add Comment Form -->
                                                                    <form method="POST" action="add_comment.php" class="mt-3">
                                                                        <input type="hidden" name="event_id" value="<?php echo $event['event_id']; ?>">

                                                                        <div class="form-group">
                                                                            <label for="comment"><i class="fas fa-pen mr-2"></i>Add Your Comment:</label>
                                                                            <textarea class="form-control" name="comment" rows="3" required
                                                                                <?php echo isUserBanned($conn, $_SESSION['user_id']) ? 'disabled' : ''; ?>></textarea>

                                                                            <?php if (isUserBanned($conn, $_SESSION['user_id'])): ?>
                                                                                <div class="alert alert-danger mt-2">
                                                                                    <i class="fas fa-ban mr-2"></i>
                                                                                    You have been banned from commenting on events.
                                                                                </div>
                                                                            <?php endif; ?>
                                                                        </div>

                                                                        <button type="submit" name="add_comment" class="btn btn-primary"
                                                                            <?php echo isUserBanned($conn, $_SESSION['user_id']) ? 'disabled' : ''; ?>>
                                                                            <i class="fas fa-paper-plane mr-1"></i> Submit Comment
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Edit Event Modal -->
                                            <?php if ($can_manage_events): ?>
                                                <div class="modal fade" id="editEventModal<?php echo $event['event_id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-warning">
                                                                <h5 class="modal-title">
                                                                    <i class="fas fa-edit mr-2"></i>
                                                                    Edit Event
                                                                </h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <form method="POST" action="update_events.php" enctype="multipart/form-data">
                                                                <div class="modal-body">
                                                                    <input type="hidden" name="event_id" value="<?php echo $event['event_id']; ?>">

                                                                    <div class="form-group">
                                                                        <label for="title"><i class="fas fa-heading mr-1"></i>Event Title:</label>
                                                                        <input type="text" class="form-control" name="title"
                                                                            value="<?php echo htmlspecialchars($event['title']); ?>" required>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label for="description"><i class="fas fa-align-left mr-1"></i>Event Description:</label>
                                                                        <textarea class="form-control" name="description" rows="5" required><?php echo htmlspecialchars($event['description']); ?></textarea>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label for="event_image"><i class="fas fa-image mr-1"></i>Event Image:</label>
                                                                        <div class="custom-file">
                                                                            <input type="file" class="custom-file-input" name="event_image" id="eventImageEdit<?php echo $event['event_id']; ?>" accept="image/*">
                                                                            <label class="custom-file-label" for="eventImageEdit<?php echo $event['event_id']; ?>">
                                                                                <?php echo !empty($event['image']) ? $event['image'] : 'Choose file'; ?>
                                                                            </label>
                                                                        </div>
                                                                        <small class="form-text text-muted">Leave empty to keep the current image.</small>

                                                                        <?php if (!empty($event['image'])): ?>
                                                                            <div class="mt-2">
                                                                                <img src="../dist/assets/images/uploads/events/<?php echo htmlspecialchars($event['image']); ?>"
                                                                                    class="img-thumbnail" width="200" alt="Current Event Image">
                                                                            </div>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                    <button type="submit" name="edit_event" class="btn btn-warning">
                                                                        <i class="fas fa-save mr-1"></i> Save Changes
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <div class="col-12">
                                            <div class="alert alert-info text-center">
                                                <i class="fas fa-info-circle mr-2"></i>
                                                No events found. <?php echo !empty($search) ? 'Try a different search term.' : ''; ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Pagination -->
                                <?php if ($total_pages > 1): ?>
                                    <nav aria-label="Page navigation">
                                        <ul class="pagination justify-content-center">
                                            <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                                                <a class="page-link" href="?search=<?php echo urlencode($search); ?>&page=<?php echo $page - 1; ?>" aria-label="Previous">
                                                    <span aria-hidden="true">&laquo;</span>
                                                </a>
                                            </li>
                                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                                <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                                    <a class="page-link" href="?search=<?php echo urlencode($search); ?>&page=<?php echo $i; ?>">
                                                        <?php echo $i; ?>
                                                    </a>
                                                </li>
                                            <?php endfor; ?>
                                            <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                                                <a class="page-link" href="?search=<?php echo urlencode($search); ?>&page=<?php echo $page + 1; ?>" aria-label="Next">
                                                    <span aria-hidden="true">&raquo;</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </nav>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Add Event Modal -->
                <?php if ($can_manage_events): ?>
                    <div class="modal fade" id="addEventModal" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title">
                                        <i class="fas fa-plus-circle mr-2"></i>
                                        Add New Event
                                    </h5>
                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form method="POST" action="add_event.php" enctype="multipart/form-data">
                                    
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="title"><i class="fas fa-heading mr-1"></i>Event Title:</label>
                                            <input type="text" class="form-control" name="title" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="description"><i class="fas fa-align-left mr-1"></i>Event Description:</label>
                                            <textarea class="form-control" name="description" rows="5" required></textarea>
                                        </div>

                                        <div class="form-group">
                                            <label for="event_image"><i class="fas fa-image mr-1"></i>Event Image:</label>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" name="event_image" id="eventImage" accept="image/*">
                                                <label class="custom-file-label" for="eventImage">Choose file</label>
                                            </div>
                                            <small class="form-text text-muted">Optional. Recommended size: 1200 x 800 pixels.</small>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        <button type="submit" name="add_event" class="btn btn-primary">
                                            <i class="fas fa-save mr-1"></i> Create Event
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                <?php endif; ?>


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