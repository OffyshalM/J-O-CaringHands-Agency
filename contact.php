<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './vendor/autoload.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=utf-8");

if ($_SERVER['REQUEST_METHOD']==='POST') {

    $json = file_get_contents('php://input');
    $dataGotten = json_decode($json, true);

    $fullname = htmlspecialchars($dataGotten['fullname'] ?? '');
    $email = htmlspecialchars($dataGotten['email'] ?? '');
    $phonenumber = htmlspecialchars($dataGotten['phonenumber'] ?? '');
    $age = htmlspecialchars($dataGotten['age'] ?? '');
    $address = htmlspecialchars($dataGotten['address'] ?? '');
    $messageBox = htmlspecialchars($dataGotten['message'] ?? '');

    $mail = new PHPMailer(true);

    try {
        // --- FIXED FOR GODADDY SHARED HOSTING ---
        $mail->isSMTP();
        $mail->Host       = 'localhost';          // Use GoDaddy internal relay
        $mail->SMTPAuth   = false;                // No login needed for internal relay
        $mail->SMTPAutoTLS = false; 
        $mail->Port       = 25;                   // Standard GoDaddy port
        // ----------------------------------------

        // The "From" must be your domain for GoDaddy to authorize the send
        $mail->setFrom('info@jandocaringhands.net', 'J & O CaringHands Agency, LLC');
        
        $mail->addAddress('Jandocaringhands@gmail.com');
        $mail->addReplyTo($email, $fullname);
        $mail->isHTML(true);
        $mail->Subject = "New Care Inquiry: $fullname";

        $mail->Body = "
        <div style='font-family: \"Segoe UI\", Roboto, Helvetica, Arial, sans-serif; max-width: 600px; margin: 0 auto; border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden; background-color: #ffffff;'>
            <div style='background-color: #0ea5e9; padding: 30px 20px; text-align: center;'>
                <h2 style='color: #ffffff; margin: 0; text-transform: uppercase; letter-spacing: 3px; font-size: 18px; font-weight: 900;'>J & O CaringHands Agency, LLC.</h2>
                <p style='color: #e0f2fe; margin: 8px 0 0 0; font-size: 12px; font-weight: 600; text-transform: uppercase;'>New Inquiry Notification</p>
            </div>
            <div style='padding: 30px;'>
                <h3 style='color: #0f172a; font-size: 15px; margin-bottom: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 10px; text-transform: uppercase; letter-spacing: 1px;'>Contact Information</h3>
                <table style='width: 100%; border-collapse: collapse;'>
                    <tr>
                        <td style='padding: 10px 0; color: #64748b; font-size: 13px; font-weight: bold; width: 35%;'>Full Name:</td>
                        <td style='padding: 10px 0; color: #0f172a; font-weight: 600;'>$fullname</td>
                    </tr>
                    <tr>
                        <td style='padding: 10px 0; color: #64748b; font-size: 13px; font-weight: bold;'>Email Address:</td>
                        <td style='padding: 10px 0; color: #0ea5e9; font-weight: 600;'>$email</td>
                    </tr>
                    <tr>
                        <td style='padding: 10px 0; color: #64748b; font-size: 13px; font-weight: bold;'>Phone Number:</td>
                        <td style='padding: 10px 0; color: #0f172a; font-weight: 600;'>$phonenumber</td>
                    </tr>
                    <tr>
                        <td style='padding: 10px 0; color: #64748b; font-size: 13px; font-weight: bold;'>Age:</td>
                        <td style='padding: 10px 0; color: #0f172a;'>$age</td>
                    </tr>
                    <tr>
                        <td style='padding: 10px 0; color: #64748b; font-size: 13px; font-weight: bold;'>Residential Address:</td>
                        <td style='padding: 10px 0; color: #0f172a; font-size: 14px;'>$address</td>
                    </tr>
                </table>
                <div style='margin-top: 30px;'>
                    <h3 style='color: #0f172a; font-size: 15px; margin-bottom: 15px; border-bottom: 1px solid #f1f5f9; padding-bottom: 10px; text-transform: uppercase; letter-spacing: 1px;'>Message / Details</h3>
                    <div style='background-color: #f8fafc; padding: 20px; border-radius: 12px; color: #334155; line-height: 1.7; font-size: 15px; border-left: 4px solid #0ea5e9;'>
                        " . nl2br(htmlspecialchars($messageBox)) . "
                    </div>
                </div>
                <div style='text-align: center; margin-top: 35px;'>
                    <a href='tel:$phonenumber' style='display: inline-block; background-color: #0ea5e9; color: #ffffff; padding: 14px 28px; border-radius: 10px; text-decoration: none; font-weight: bold; font-size: 14px; margin-right: 10px; box-shadow: 0 4px 6px rgba(14, 165, 233, 0.2);'>Call $fullname</a>
                    <a href='mailto:$email' style='display: inline-block; background-color: #0f172a; color: #ffffff; padding: 14px 28px; border-radius: 10px; text-decoration: none; font-weight: bold; font-size: 14px; box-shadow: 0 4px 6px rgba(15, 23, 42, 0.2);'>Reply via Email</a>
                </div>
            </div>
            <div style='background-color: #f1f5f9; padding: 20px; text-align: center;'>
                <p style='color: #94a3b8; font-size: 11px; margin: 0;'>
                    &copy; 2026 J & O CaringHands Agency, LLC. All rights reserved. 
                    <br>Confidential Website Submission.
                </p>
            </div>
        </div>";

        $mail->send();
        echo json_encode([
            "status" => "success", 
            "message" => "Message sent successfully!"
        ]);

    } catch (Exception $e) {
        echo json_encode([
            "status" => "error",
            "message" => "Mail Error: " . $mail->ErrorInfo
        ]);
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid Request"
    ]);
}
?>