<?php 
$certification_id = $_GET['certification_id'];

include '../connection/config.php';
$view_certificate = $conn->query("
    SELECT 
        c.*,
        u.address AS user_address,
        u.first_name, 
        u.middle_name, 
        u.last_name
    FROM tbl_certification c
    LEFT JOIN tbl_residents u ON u.user_id = c.user_id 
    WHERE c.certification_id = '$certification_id'
");

$row = $view_certificate->fetch_assoc();

$fullname = $row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name'];
$user_address = $row['user_address'];
$what_is_caused = $row['what_is_caused'];

$calamityDate = new DateTime($row['calamity_date']);
$calamity_dateWord = $calamityDate->format('F j, Y');

$calamityTimeObj = new DateTime($row['calamity_time']);
$calamity_timeFormatted = $calamityTimeObj->format('g:i A'); // e.g., 2:30 PM

$location = $row['location'];
$requested_by = $row['requested_by'];
$calamity_purpose = $row['calamity_purpose'];


$today = new DateTime(); 
$day = $today->format('j');       // 1-31
$daySuffix = $today->format('S'); // st, nd, rd, th
$month = $today->format('F');     // January, February, ...
$year = $today->format('Y');      // 2025


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>OATH OF UNDERTAKING</title>
  <link rel="stylesheet" href="../css/printed_header.css">
 
</head>
<body>

  <!-- HEADER -->
  <div class="header">
    <img src="../assets/left-logo.png" alt="Barangay Logo"> 
    <div class="header-center">
      <div><b>Republic of the Philippines</b></div>
      <div><b>City of Manila</b></div>
      <div><b>BARANGAY 400 ZONE 41, DISTRICT IV</b></div>
      <div><b>SAMPALOC, MANILA</b></div>
      <div class="office">OFFICE OF THE BARANGAY CHAIRMAN</div>
    </div>
    <img src="../assets/right-logo.png" alt="Bagong Pilipinas Logo"> 
  </div>

  <!-- LINE SEPARATOR -->
  <div class="line-separator"></div>

  <?php 

  if($row['certificationType']==='Good Moral'){
      include '../templates/goodmoral.php';
  }else if($row['certificationType']==='Calamity'){
    if($row['calamity_purpose']==='Fire Victim Purposes'){
      include '../templates/certificate_for_calamity_fire.php';
    }else if($row['calamity_purpose']==='Supporting Document For Submmission'){
      include '../templates/certificate_for_calamity_sds.php';
    }else{
      include '../templates/certificate_for_calamity_ccls.php';
    }

  }else if($row['certificationType']==='First Time Job Seeker'){
      include '../templates/firstime_job_seeker.php';
  }
  
//  include '../templates/outh_of_undertaking.php';
//   include '../templates/firstime_job_seeker.php';
//   include '../templates/certificate_for_calamity_fire.php';
//    include '../templates/certificate_for_calamity_ccls.php';
//    include '../templates/blotter.php';
  ?>

</body>
</html>
