<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("location:login.php");
    exit;
}

$servername = "localhost:3307";
$username = "root";
$password = "";
$database = "plantcare";
$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$user = $_SESSION['name'];

// Handle deletion
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    $delete_query = "DELETE FROM notifications WHERE id = '$delete_id' AND username = '$user'";
    mysqli_query($conn, $delete_query);
    echo "<script>alert('Reminder deleted successfully!'); window.location.href='reminders.php';</script>";
}

// Fetch user's reminders
$query = "SELECT * FROM notifications WHERE username = '$user'";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reminders</title>
    <link rel="stylesheet" href="basic.css">
</head>
<body>
    <nav id="navbar">
        <img src="logo.jpg" alt="not loaded" id="logo">
        <ul>
            <li><a href="home.php">Home</a></li>
            <li><a href="plantcare.php">Plant Info</a></li>
            <li><a href="reminders.php">Reminders</a></li>
            <li><a href="logout.php">Log out</a></li>
            <li><b><?php echo $_SESSION['name']; ?></b></li>
        </ul>
        <div class="nav-toggle">
            <img src="hicon.png" alt="Home" id="homeimg">
            <span></span>
        </div>
    </nav>

    <h1>Your Reminders</h1>
    <div class="reminder-container">
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="reminder-card">
                    <h3><?php echo htmlspecialchars($row['plant_name']); ?></h3>
                    <p><strong>Watering:</strong> <?php echo htmlspecialchars($row['watering']); ?></p>
                    <p><strong>Sunlight:</strong> <?php echo htmlspecialchars($row['sunlight']); ?></p>
                    <p><strong>Soil:</strong> <?php echo htmlspecialchars($row['soil']); ?></p>
                    <p><strong>Notification Time:</strong> <?php echo htmlspecialchars($row['time']); ?></p>
                    <form method="post">
                        <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                        <input type="submit" value="Delete">
                    </form>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No reminders set yet.</p>
        <?php endif; ?>
    </div>

    <script>
        let a = document.getElementById('homeimg');
        let n = document.querySelector('ul');
        a.addEventListener('click', () => {
            if (n.style.display == 'none') {
                n.style.display = 'block';
                document.querySelector('span').append(n);
            } else {
                n.style.display = 'none';
            }
        });
    </script>

    <style>
        body {
            background-image: url('remindersbg.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            font-family: Arial, sans-serif;
            color: white;
        }
        h1 {
            text-align: center;
            margin-top: 30px;
            font-size: 2.5em;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }
        .reminder-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            padding: 20px;
        }
        .reminder-card {
            border: 2px solid green;
            padding: 15px;
            border-radius: 8px;
            background: rgba(0, 0, 0, 0.6);
            width: 300px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(255, 255, 255, 0.2);
        }
        input[type="submit"] {
            background: red;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            margin-top: 10px;
            border-radius: 5px;
        }
    </style>
</body>
</html>
