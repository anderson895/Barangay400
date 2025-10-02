<?php
include '../connection/config.php';




if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['requestType'])) {
         if ($_POST['requestType'] == 'AddMenu') {

                $complainant_name  = $_POST['complainant_name'];
                $complainant_age  = $_POST['complainant_age'];
                $complainant_address = $_POST['complainant_address'];
                $complainant_email  = $_POST['complainant_email'];
                $complainant_phone  = $_POST['complainant_phone'];
                // FILES
                $menuImage = $_FILES['menuImage'];
                $uploadDir = '../../static/upload/';
                $menuImageFileName = ''; 
                if (isset($menuImage) && $menuImage['error'] === UPLOAD_ERR_OK) {
                    $bannerExtension = pathinfo($menuImage['name'], PATHINFO_EXTENSION);
                    $menuImageFileName = uniqid('menu_', true) . '.' . $bannerExtension;
                    $bannerPath = $uploadDir . $menuImageFileName;

                    $bannerUploaded = move_uploaded_file($menuImage['tmp_name'], $bannerPath);

                    if (!$bannerUploaded) {
                        echo json_encode([
                            'status' => 500,
                            'message' => 'Error uploading menuImage image.'
                        ]);
                        exit;
                    }
                } elseif ($menuImage['error'] !== UPLOAD_ERR_NO_FILE && $menuImage['error'] !== 0) {
                    echo json_encode([
                        'status' => 400,
                        'message' => 'Invalid image upload.'
                    ]);
                    exit;
                }
                $result = $conn->AddMenu(
                    $menuName,
                    $menuCategory,
                    $menuDescription,
                    $menuPrice,
                    $menuImageFileName 
                );

                if ($result) {
                    echo json_encode([
                        'status' => 200,
                        'message' => 'Posted Successfully.'
                    ]);
                } else {
                    echo json_encode([
                        'status' => 500,
                        'message' => 'Error saving data.'
                    ]);
                }

        }else {
            echo '404';
        }
    } else {
        echo 'Access Denied! No Request Type.';
    }
} 
?>