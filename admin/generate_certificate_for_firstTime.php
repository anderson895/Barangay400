<?php 
$certification_id = $_GET['certification_id'] ?? null;

include '../connection/config.php';

if (!$certification_id) {
    echo json_encode([
        "content1" => "<p>Error: Missing certification_id</p>",
        "content2" => ""
    ]);
    exit;
}

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

if (!$row) {
    echo json_encode([
        "content1" => "<p>No certificate found.</p>",
        "content2" => ""
    ]);
    exit;
}

$fullname = trim($row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name']);

// ================== HEADER TEMPLATE ==================
$header = '
<div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-3">
    <img src="../assets/left-logo.png" alt="Barangay Logo" class="img-fluid" style="max-height:70px;">
    <div class="text-center flex-fill px-2">
        <div><b>Republic of the Philippines</b></div>
        <div><b>City of Manila</b></div>
        <div><b>BARANGAY 400 ZONE 41, DISTRICT IV</b></div>
        <div><b>SAMPALOC, MANILA</b></div>
        <div class="office"><small>OFFICE OF THE BARANGAY CHAIRMAN</small></div>
    </div>
    <img src="../assets/right-logo.png" alt="Bagong Pilipinas Logo" class="img-fluid" style="max-height:70px;">
</div>
<hr class="my-2">';
  
// ================== CONTENT 1 ==================
$content1 = '
<div class="certificate p-3">
    ' . $header . '
    <h3 class="text-center mt-3">OATH OF UNDERTAKING</h3>
    <div class="certificate-body text-justify">
        <p>I, <strong>'.htmlspecialchars($fullname).'</strong>, resident of <strong>'.htmlspecialchars($row['user_address']).'</strong>, do hereby swear to comply with the requirements set forth for first-time job seekers. I pledge to act responsibly and follow the rules and regulations of the Barangay.</p>
    </div>
</div>';

// ================== CONTENT 2 ==================
$content2 = '
<div class="certificate p-3">
    ' . $header . '
    <h3 class="text-center">Barangay Clearance for First Time Job Seeker</h3>
    <div class="certificate-body text-justify">
        <p>This certifies that <strong>'.htmlspecialchars($fullname).'</strong> is a bona fide resident of Barangay 400, Zone 41, District IV, Sampaloc, Manila.</p>
        <p>Issued on <strong>'.date("F d, Y").'</strong> for employment purposes only.</p>
    </div>
</div>';

// Return JSON
echo json_encode([
    "content1" => $content1,
    "content2" => $content2
]);
