<?php
include '../connection/config.php';
header('Content-Type: application/json'); // para AJAX JSON response

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $certification_id = intval($_POST['certification_id']);
    $status = $_POST['status'];
    $remarks = $_POST['remarks'];

    // Update status + remarks
    $stmt = $conn->prepare("UPDATE tbl_certification SET status = ?, remarks = ? WHERE certification_id = ?");
    $stmt->bind_param("ssi", $status, $remarks, $certification_id);
    $updateResult = $stmt->execute();
    $stmt->close();

    if ($updateResult) {
        if (strtolower($status) === 'approved') {
            // Generate certificate if approved
            $generateResult = generateCertificateFile($certification_id, $conn);

            if ($generateResult['success']) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Certificate approved and generated successfully.',
                    'file'    => $generateResult['filename']
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Approved but generation failed.',
                    'error'   => $generateResult['error']
                ]);
            }
        } else {
            echo json_encode([
                'success' => true,
                'message' => 'Certificate status updated successfully.'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Database update failed.'
        ]);
    }
    exit;
}

/**
 * Generate Certificate Image File
 */


function generateCertificateFile($certification_id, $conn) {
    try {
      $stmt = $conn->prepare("
            SELECT c.*, u.*,
                (SELECT CONCAT_WS(' ', bo.first_name, bo.middle_name, bo.last_name)
                    FROM tbl_brgyofficer bo
                    WHERE bo.position = 'BarangayCaptain' 
                    AND bo.status = 'Active'
                    LIMIT 1) AS barangay_captain,

                (SELECT CONCAT_WS(' ', bo.first_name, bo.middle_name, bo.last_name)
                    FROM tbl_brgyofficer bo
                    WHERE bo.position = 'BarangayTreasurer' 
                    AND bo.status = 'Active'
                    LIMIT 1) AS barangay_treasurer
            FROM tbl_certification c
            LEFT JOIN tbl_user u 
                ON u.user_id = c.user_id
            WHERE c.certification_id = ? 
            AND c.status = 'Approved'
        ");
        $stmt->bind_param("i", $certification_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return ['success' => false, 'error' => 'No approved certification found'];
        }

        $row = $result->fetch_assoc();
        $type = $row['certificationType'];
        $stmt->close();

        // Certificate templates
        $certificateTypes = [
            'Good Moral' => [
                'template' => 'temp-gm.jpg',
                'folder' => 'GoodMoralCert',
                'name_pos' => [550, 680],
                'address_pos' => [750, 730],
                'other_purpose_pos' => [1110, 1650],
                'checkbox_positions' => [
                    'Local Employment' => [250, 1075],
                    'PWD ID' => [250, 1130],
                    'Hospital Requirement' => [250, 1185],
                    'Transfer Residency' => [250, 1240],
                    'Bank Transaction' => [250, 1295],
                    'Proof Of Indigency' => [250, 1350],
                    'Financial Assistance' => [950, 1075],
                    'Maynilad Requirement' => [950, 1130],
                    'School Requirement' => [950, 1180],
                    'Proof Of Residency' => [950, 1240],
                    'Medical Assistance' => [950, 1295],
                ]
            ],
           'First Time Job Seeker' => [
                'template' => 'temp-ftjs.jpeg',
                'folder'   => 'FirstTimeJobSeekerCert',
                'name_pos' => [900, 830],     // Name
                'address_pos' => [400, 900],  // Address
                'age_pos' => [800, 900],      // Age
                'purpose_pos' => [250, 580],  // Purpose
                'day_pos' => [500, 1260],   
                'date_issued_pos' => [650, 1260], 
                'valid_until_pos' => [740, 1330], 
                'authorize_person' => [1150,1500],  
                'witness_pos' => [1100,1800],  
                'author_date' => [1150,1600],  
                'witness_date' => [1150,1900],  
            ],
           'Calamity' => [
                'template' => 'temp_calamity.jpg',
                'folder' => 'CalamityCert',
                'name_pos' => [500, 580],
                'address_pos' => [350, 645],
                'calamity_pos' => [750, 810],        // Type of calamity
                'date_event_pos' => [200, 840],        // Date of calamity event
                'requester_pos' => [750, 890],        // Person who requested
                'date_issued_pos' => [630, 1040],      // Date certificate was issued
                'day_pos' => [500, 1040],              // Day only
            ]
        ];

        if (!array_key_exists($type, $certificateTypes)) {
            return ['success' => false, 'error' => 'Invalid certificate type','type' => $type];
        }

        $data = $certificateTypes[$type];
        $templatePath = __DIR__ . '/generate_certificate/templates/' . $data['template'];
        $savePath = __DIR__ . '/generate_certificate/' . $data['folder'];
        $font = __DIR__ . '/generate_certificate/fonts/TimesNewRoman.ttf';
        $checkmarkImagePath = __DIR__ . '/generate_certificate/icons/checkmark.png';

        if (!file_exists($templatePath)) {
            return ['success' => false, 'error' => "Template not found: {$data['template']}"];
        }
        if (!file_exists($font)) {
            return ['success' => false, 'error' => 'Font file not found'];
        }

        if (!is_dir($savePath) && !mkdir($savePath, 0777, true)) {
            return ['success' => false, 'error' => 'Failed to create directory'];
        }

        $image = imagecreatefromjpeg($templatePath);
        if (!$image) {
            return ['success' => false, 'error' => 'Failed to load template image'];
        }

        $black = imagecolorallocate($image, 0, 0, 0);
                // Existing variables
        $name     = $row['name'];
        $address  = $row['address'];
        $birthday = $row['birthday'];
        $purpose  = trim($row['purpose']);
        $date     = date("F d, Y");

        // Barangay officers
        $captain   = $row['barangay_captain'];
        $treasurer = $row['barangay_treasurer'];

        // Draw name and address (common to all certificates)
        if (isset($data['name_pos'])) {
            imagettftext($image, 20, 0, $data['name_pos'][0], $data['name_pos'][1], $black, $font, $name);
        }
        if (isset($data['address_pos'])) {
            imagettftext($image, 20, 0, $data['address_pos'][0], $data['address_pos'][1], $black, $font, ucfirst($address));
        }

        // Good Moral Certificate Processing
        if ($type === 'Good Moral' && isset($data['checkbox_positions'])) {
            $matchedKey = null;
            foreach ($data['checkbox_positions'] as $key => $coords) {
                if (strcasecmp($key, $purpose) === 0) {
                    $matchedKey = $key;
                    break;
                }
            }

            if ($matchedKey && file_exists($checkmarkImagePath)) {
                [$x, $y] = $data['checkbox_positions'][$matchedKey];
                $checkmark = imagecreatefrompng($checkmarkImagePath);
                if ($checkmark) {
                    imagealphablending($image, true);
                    imagesavealpha($image, true);
                    $resized = imagescale($checkmark, 30, 30);
                    imagecopy($image, $resized, $x, $y, 0, 0, 30, 30);
                    imagedestroy($checkmark);
                    imagedestroy($resized);
                }
            } elseif (!empty($purpose) && isset($data['other_purpose_pos'])) {
                imagettftext($image, 20, 0, $data['other_purpose_pos'][0], $data['other_purpose_pos'][1], $black, $font, $purpose);
            }
        }

        // First Time Job Seeker Certificate Processing
        if ($type === 'First Time Job Seeker') {
            // Age field
            $birthday = $row['birthday'];
            // compute age
            if (!empty($birthday)) {
                $birthDate = new DateTime($birthday);
                $today = new DateTime();
                $age = $today->diff($birthDate)->y; // kukunin ang age in years
            } else {
                $age = ''; // fallback kung walang birthday
            }

            if (isset($data['age_pos'])) {
                imagettftext($image, 20, 0, $data['age_pos'][0], $data['age_pos'][1], $black, $font, $age);
            }

            // Purpose
            if (isset($data['purpose_pos']) && !empty($purpose)) {
                imagettftext($image, 20, 0, $data['purpose_pos'][0], $data['purpose_pos'][1], $black, $font, $purpose);
            }

            // Date issued
            if (isset($data['date_pos'])) {
                $today = date("F d, Y");
                imagettftext($image, 20, 0, $data['date_pos'][0], $data['date_pos'][1], $black, $font, $today);
            }

            // authorizing person (Barangay Captain)
            if (isset($data['authorize_person'])) {
                imagettftext($image, 20, 0, $data['authorize_person'][0], $data['authorize_person'][1], $black, $font, $captain);
            }

             // witness (Barangay Treasurer)
            if (isset($data['witness_pos'])) {
                imagettftext($image, 20, 0, $data['witness_pos'][0], $data['witness_pos'][1], $black, $font, $treasurer);
            }

            // Date certificate was issued (Month, Year only)
            if (isset($data['date_issued_pos'])) {
                $today = date("F, Y");
                imagettftext($image, 20, 0, $data['date_issued_pos'][0], $data['date_issued_pos'][1], $black, $font, $today);
            }

            // Day only (ordinal format)
            if (isset($data['day_pos'])) {
                $day = date("jS"); // e.g. "6th"
                imagettftext($image, 20, 0, $data['day_pos'][0], $data['day_pos'][1], $black, $font, $day);
            }

            // Valid until 1 year
            if (isset($data['valid_until_pos'])) {
                $valid_until = date("F d, Y", strtotime("+1 year")); 
                imagettftext(
                    $image,
                    20,
                    0,
                    $data['valid_until_pos'][0],
                    $data['valid_until_pos'][1],
                    $black,
                    $font,
                    $valid_until
                );
            }


            if (isset($data['author_date'])) {
                $author_date = date("F d, Y"); 
                imagettftext(
                    $image,
                    20,
                    0,
                    $data['author_date'][0],
                    $data['author_date'][1],
                    $black,
                    $font,
                    $author_date
                );
            }


            if (isset($data['witness_date'])) {
                $witness_date = date("F d, Y"); 
                imagettftext(
                    $image,
                    20,
                    0,
                    $data['witness_date'][0],
                    $data['witness_date'][1],
                    $black,
                    $font,
                    $witness_date
                );
            }

           

           
        }


        // Calamity Certificate Processing
        if ($type === 'Calamity') {
            // Type of calamity
            if (isset($data['calamity_pos'])) {
                $calamityType = !empty($row['type_of_calamity']) ? $row['type_of_calamity'] : 'N/A';
                imagettftext($image, 20, 0, $data['calamity_pos'][0], $data['calamity_pos'][1], $black, $font, $calamityType);
            }

            // Date of calamity event
            if (isset($data['date_event_pos'])) {
                $eventDate = !empty($row['calamity_date']) ? date("F d, Y", strtotime($row['calamity_date'])) : '';
                imagettftext($image, 20, 0, $data['date_event_pos'][0], $data['date_event_pos'][1], $black, $font, $eventDate);
            }

            // Purpose
            if (isset($data['purpose_pos']) && !empty($purpose)) {
                imagettftext($image, 20, 0, $data['purpose_pos'][0], $data['purpose_pos'][1], $black, $font, $purpose);
            }

            // Person who requested the certificate
            if (isset($data['requester_pos'])) {
                $requester = !empty($row['requested_by']) ? $row['requested_by'] : $name; // Default to the resident's name
                imagettftext($image, 20, 0, $data['requester_pos'][0], $data['requester_pos'][1], $black, $font, $requester);
            }

            // Date certificate was issued
            if (isset($data['date_issued_pos'])) {
                $today = date("F, Y");
                imagettftext($image, 20, 0, $data['date_issued_pos'][0], $data['date_issued_pos'][1], $black, $font, $today);
            }

            // Day only (ordinal format)
            if (isset($data['day_pos'])) {
                $day = date("jS"); // e.g. "6th"
                imagettftext($image, 20, 0, $data['day_pos'][0], $data['day_pos'][1], $black, $font, $day);
            }

            
        }

        // Generate filename and save
        $safeName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $name); // More robust name sanitization
        $filename = $savePath . '/' . $safeName . '_' . $certification_id . '_' . time() . '.jpg';

        $saveResult = imagejpeg($image, $filename, 90);
        imagedestroy($image);

        if (!$saveResult || !file_exists($filename)) {
            return ['success' => false, 'error' => 'Failed to save certificate image: ' . $filename];
        }

        // Return relative path accessible by browser
        $publicPath = 'generate_certificate/' . $data['folder'] . '/' . basename($filename);
        return ['success' => true, 'filename' => $publicPath];

    } catch (Exception $e) {
        return ['success' => false, 'error' => 'Exception: ' . $e->getMessage()];
    }
}
?>