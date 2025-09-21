<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'connection/config.php';
include 'functions.php';

// Load PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';


if (isset($_POST['verify'])) {
    $otp = $_POST['otp'];

    if ($otp == $_SESSION['otp']) {
        $user_data = $_SESSION['user_data'];
        $user_id = $user_data['user_id'];

        $account_status = 'Pending';
        $role = 'Resident';

        $insert = "INSERT INTO tbl_user ( user_id,
            first_name, middle_name, last_name, suffix,
            email, password, mobile, address, occupation,
            birthday, gender, civilStatus, is_household_head,
            household_head_name, relationship_to_head, is_senior,
            is_pwd, is_registered_voter, proof_of_residency,
            pwd_document, voter_document, senior_document, terms, image, role, account_status
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($insert);
        $stmt->bind_param(
            "sssssssssssssssssssssssssss",
            $user_data['user_id'],
            $user_data['first_name'],
            $user_data['middle_name'],
            $user_data['last_name'],
            $user_data['suffix'],
            $user_data['email'],
            $user_data['password'],
            $user_data['mobile'],
            $user_data['address'],
            $user_data['occupation'],
            $user_data['birthday'],
            $user_data['gender'],
            $user_data['civilStatus'],
            $user_data['is_household_head'],
            $user_data['household_head_name'],
            $user_data['relationship_to_head'],
            $user_data['is_senior'],
            $user_data['is_pwd'],
            $user_data['is_registered_voter'],
            $user_data['proof_of_residency'],
            $user_data['pwd_document'],
            $user_data['voter_document'],
            $user_data['senior_document'],
            $user_data['terms'],
            $user_data['image'],
            $role,
            $account_status
        );
        
        if ($user_data['is_household_head'] === 'Yes') {
            $stmt_head = $conn->prepare("INSERT INTO tbl_household_head (user_id) VALUES (?)");
            $stmt_head->bind_param("s", $user_id);
            $stmt_head->execute();

        } else if ($user_data['is_household_head'] === 'No') {
            $stmt_relation = $conn->prepare("
                INSERT INTO tbl_household_relation (thr_head_id, thr_user_id,thr_relationship) 
                VALUES (?, ?)
            ");
            $stmt_relation->bind_param("sss", $user_data['household_head_name'], $user_id,$user_data['relationship_to_head']);
            $stmt_relation->execute();
        }


        
        if ($stmt->execute()) {
            $id = $conn->insert_id;
            // Insert into tbl_resident
            $insert_resident = "INSERT INTO tbl_residents (
                user_id, first_name, middle_name, last_name, suffix,
                birthday, gender, civilStatus, mobile,
                address, is_household_head, household_head_name,
                relationship_to_head, is_senior, is_pwd,
                pwd_document, is_registered_voter, proof_of_residency_document,
                voter_document, senior_document, occupation, email, image
            ) VALUES (
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
            )";

            $stmt_resident = $conn->prepare($insert_resident);
            $stmt_resident->bind_param(
                "sssssssssssssssssssssss",
                $user_id,
                $user_data['first_name'],
                $user_data['middle_name'],
                $user_data['last_name'],
                $user_data['suffix'],
                $user_data['birthday'],
                $user_data['gender'],
                $user_data['civilStatus'],
                $user_data['mobile'],
                $user_data['address'],
                $user_data['is_household_head'],
                $user_data['household_head_name'],
                $user_data['relationship_to_head'],
                $user_data['is_senior'],
                $user_data['is_pwd'],
                $user_data['pwd_document'],
                $user_data['is_registered_voter'],
                $user_data['proof_of_residency'],
                $user_data['voter_document'],
                $user_data['senior_document'],
                $user_data['occupation'],
                $user_data['email'],
                $user_data['image']
            );

            if ($stmt_resident->execute()) {
                // insert into tbl_household_head if needed
                if (strtolower($user_data['is_household_head']) === 'yes' || $user_data['is_household_head'] == '1') {
                    $stmt_head = $conn->prepare("INSERT INTO tbl_household_head (user_id) VALUES (?)");
                    $stmt_head->bind_param("s", $user_id);
                    $stmt_head->execute();
                }
    
                logActivity($user_id, 'Resident', 'Registered in tbl_user and tbl_resident');
                unset($_SESSION['otp'], $_SESSION['user_data']);
                header('Location: login.php');
                exit();
            } else {
                $error[] = 'Registration to tbl_resident failed. Try again.';
            }
        } else {
            $error[] = 'Registration to tbl_user failed. Try again.';
        }
    } else {
        $error[] = 'Invalid OTP.';
    }
}



// Resend OTP functionality
if (isset($_POST['resend_otp'])) {
    $email = $_SESSION['email'];
    $new_otp = rand(100000, 999999);
    $_SESSION['otp'] = $new_otp;

    $mail = new PHPMailer(true);

    try {
        // SMTP Settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'systembarangaymanagement@gmail.com'; // Your Gmail
        $mail->Password = 'qqwafpvwljoixsxa'; // Your App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Email Content
        $mail->setFrom('systembarangaymanagement@gmail.com', 'Barangay System');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Code (Resent)';
        $mail->Body = '
            <html>
            <body style="font-family: Arial, sans-serif;">
                <div style="background-color: #f6f6f6; padding: 20px;">
                    <div style="background-color: white; padding: 20px; border-radius: 10px;">
                        <h2 style="color: #4A90E2;">Barangay 400 Online System</h2>
                        <p>Your new OTP code is:</p>
                        <h1 style="color: #4A90E2; font-size: 32px; letter-spacing: 5px;">' . $new_otp . '</h1>
                        <p>Please use this code to verify your account.</p>
                        <p style="color: #666;">If you did not request this code, you can ignore this email.</p>
                    </div>
                </div>
            </body>
            </html>';
        $mail->AltBody = 'Your OTP code is: ' . $new_otp;
        $mail->send();

        echo "OTP resent successfully!";
        exit();
    } catch (Exception $e) {
        echo "Error resending OTP: {$mail->ErrorInfo}";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification | Learning Management System</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #141E30;
            --secondary-color: #F5F7FA;
            --accent-color: #34495E;
        }

        body {
            background: linear-gradient(135deg, var(--secondary-color) 0%, #E8EEF4 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .auth-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin: 40px auto;
            max-width: 500px;
        }

        .auth-header {
            background: var(--primary-color);
            padding: 30px;
            text-align: center;
            color: white;
        }

        .auth-body {
            padding: 40px;
        }

        .otp-inputs {
            display: flex;
            justify-content: space-between;
            margin: 30px 0;
        }

        .otp-input {
            width: 50px;
            height: 50px;
            text-align: center;
            font-size: 24px;
            border: 2px solid #E8EEF4;
            border-radius: 8px;
            margin: 0 5px;
        }

        .otp-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(74, 144, 226, 0.25);
            outline: none;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #357ABD;
            transform: translateY(-1px);
        }

        .resend-timer {
            text-align: center;
            margin-top: 20px;
            color: var(--accent-color);
        }

        .verification-icon {
            font-size: 48px;
            color: var(--primary-color);
            text-align: center;
            margin-bottom: 20px;
        }

        .email-sent-to {
            text-align: center;
            color: var(--accent-color);
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="auth-container">
            <div class="auth-header">
                <h2><i class="fas fa-shield-alt mr-2"></i>Verify Your Email</h2>
                <p class="mb-0">Enter the verification code sent to your email</p>
            </div>
            
            <div class="auth-body">
                <div class="verification-icon">
                    <i class="fas fa-envelope-open-text"></i>
                </div>

                <?php if (isset($_SESSION['email'])): ?>
                    <div class="email-sent-to">
                        Code sent to: <strong><?php echo htmlspecialchars($_SESSION['email']); ?></strong>
                    </div>
                <?php endif; ?>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($error as $err): ?>
                            <p class="mb-0"><i class="fas fa-exclamation-circle mr-2"></i><?php echo $err; ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="otp-inputs">
                        <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" required>
                        <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" required>
                        <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" required>
                        <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" required>
                        <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" required>
                        <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" required>
                        <input type="hidden" name="otp" id="otp">
                    </div>

                    <button type="submit" name="verify" class="btn btn-primary btn-block">
                        <i class="fas fa-check-circle mr-2"></i>Verify Code
                    </button>
                </form>

                <div class="resend-timer text-center mt-4">
                    <p>Didn't receive the code? <br>
                    You can resend it in <span id="timer">30</span> seconds</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // OTP input handling
        const otpInputs = document.querySelectorAll('.otp-input');
        const otpHidden = document.getElementById('otp');

        otpInputs.forEach((input, index) => {
            input.addEventListener('keyup', (e) => {
                const value = e.target.value;
                
                if (value.length === 1 && index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }

                // Combine all inputs into hidden field
                otpHidden.value = Array.from(otpInputs).map(input => input.value).join('');
            });

            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !e.target.value && index > 0) {
                    otpInputs[index - 1].focus();
                }
            });
        });

        // Resend timer
        let timeLeft = 10;
        const timerElement = document.getElementById('timer');
        
        const countdown = setInterval(() => {
            timeLeft--;
            timerElement.textContent = timeLeft;
            
            if (timeLeft <= 0) {
                clearInterval(countdown);
                document.querySelector('.resend-timer').innerHTML = 
                    '<a href="#" class="text-primary" onclick="resendOTP()">Resend verification code</a>';
            }
        }, 1000);

        function resendOTP() {
            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'resend_otp=1'
            })
            .then(response => response.text())
            .then(data => {
                alert(data); // show server message
                restartTimer();
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    </script>

</body>
</html>
