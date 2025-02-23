<?php
require __DIR__ . '/vendor/autoload.php'; 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mysqli = new mysqli("localhost", "root", "", "plantcare", 3307);
if ($mysqli->connect_error) {
    die("Database connection failed: " . $mysqli->connect_error);
}

// Set timezone
date_default_timezone_set('Asia/Kolkata');

// Run indefinitely (Task Scheduler will start it once, and it will keep running)
while (true) {
    $current_time = date('H:i:00'); 
    $current_date = date('Y-m-d');

    // Log execution time
    file_put_contents('notification_log.txt', "Checking at $current_time on $current_date\n", FILE_APPEND);

    // Fetch notifications
    $query = "SELECT username, email, plant_name, message FROM notifications 
              WHERE TIME(time) = ? AND DATE(time) = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ss", $current_time, $current_date);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            sendEmailNotification($row['email'], $row['plant_name'], $row['message']);
        }
    }

    $stmt->close();

    // Wait 60 seconds before checking again
    sleep(60);
}

$mysqli->close();

// Function to send email
function sendEmailNotification($recipientEmail, $plantName, $message) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'sravyakandimalla21@gmail.com';
        $mail->Password = 'haxf cqrw oert njzx'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('sravyakandimalla21@gmail.com', 'Plant Care Reminder');
        $mail->addAddress($recipientEmail);
        $mail->Subject = "Reminder: Water Your Plant - $plantName";
        $mail->Body = "Hello,\n\nIt's time to water your $plantName.\n\nTake care of your plants! 🌱";

        $mail->send();
        file_put_contents('notification_log.txt', "Sent to $recipientEmail for $plantName\n", FILE_APPEND);
    } catch (Exception $e) {
        file_put_contents('notification_log.txt', "Failed to send: " . $mail->ErrorInfo . "\n", FILE_APPEND);
    }
}
?>
