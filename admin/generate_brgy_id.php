<?php 
$bid_id = $_GET['bid_id'];

include '../connection/config.php';

// Get current year in Manila timezone
date_default_timezone_set('Asia/Manila');
$current_year = date('Y');
$today = date('M. j, Y');  

$view_certificate = $conn->query("
    SELECT 
        c.*,
        r.address AS user_address,
        r.first_name, 
        r.middle_name, 
        r.last_name,
        r.birthday,
        r.image
    FROM tbl_bid c
    LEFT JOIN tbl_residents r ON r.user_id = c.user_id 
    WHERE c.bid_id = '$bid_id'
");


$row = $view_certificate->fetch_assoc();



$image = "../uploads/profile/" . ($row["image"] ?? "default_image.png");


include '../templates/barangayID.php';
?>
