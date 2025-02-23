<?php
session_start();
$servername = "localhost:3307";
$username = "root";
$password = "";
$database = "plantcare";

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $plant_name = $_POST['plant_name'];
    $sunlight = $_POST['sunlight'];
    $watering = $_POST['watering'];
    $soil = $_POST['soil'];
    $time = $_POST['time'];

    $sql = "INSERT INTO notifications (username, email, plant_name, sunlight, watering, soil, time) 
            VALUES ('$username', '$email', '$plant_name', '$sunlight', '$watering', '$soil', '$time')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Subscription successful!'); window.location.href='plantcare.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>
