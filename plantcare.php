<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("location:login.php");
    exit;
}

$servername = 'localhost:3307';
$username = 'root';
$password = "";
$database = "plantcare"; 
$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die('Cannot connect to the database');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['searchbox']) && !empty($_POST['searchbox'])) {
        $name = $_POST['searchbox'];

        $un = "SELECT * FROM `plantdetails` WHERE `common_plant` = '$name'";
        $result1 = mysqli_query($conn, $un);
        
        if (mysqli_num_rows($result1) > 0) {
            $searchResult = mysqli_fetch_assoc($result1);
        } else {
            $searchResult = "No results found for '$name'.";
        }
    }
}
$plantsQuery = "SELECT * FROM plantdetails";
$plantsResult = mysqli_query($conn, $plantsQuery);
if (mysqli_num_rows($plantsResult) > 0) {
    while ($row = mysqli_fetch_assoc($plantsResult)) {
        $plants[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plant Grow Guide</title>
    <link rel="stylesheet" href="basic.css">
    <link rel="stylesheet" href="plantcare.css">
</head>
<body>
    <style>
        body {
            background-image: url('plantcarebg.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            font-family: Arial, sans-serif;
            color: white;
        }
        #notifyBtn {
            font-size: 18px;
            padding: 12px 12px;
            width: 200px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 8px; 
            cursor: pointer;
            transition: 0.3s;
        }
        #notifyBtn:hover {
            background-color: #218838;
        }
    </style>
    <nav id="navbar">
        <img src="logo.jpg" alt="not loaded" id="logo">
        <ul>
            <li><a href="home.php">Home</a></li>
            <li><a href="plantcare.php">Plant Info</a></li>
            <li><a href="reminders.php">Reminders</a></li>
            <li><a href="logout.php">Log out</a></li>
            <li><b><?php echo $_SESSION['name']; ?></b></li>
        </ul>
    </nav>

    <div id="searchsection">
        <h1>Search for plant information</h1>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="search" name="searchbox" id="searchbox" placeholder="Search for any plant">
            <input type="submit" id="sbutton">
        </form>

        <?php if (isset($searchResult)): ?>
            <?php if (is_array($searchResult)): ?>
                <div class="card">
                    <h3>Name: <i><?php echo htmlspecialchars($searchResult['common_plant']); ?></i></h3>
                    <div><strong>Scientific Name: </strong> <i><?php echo htmlspecialchars($searchResult['scientific_name']); ?></i></div>
                    <div><strong>Watering: </strong> <i><?php echo htmlspecialchars($searchResult['watering']); ?></i></div>
                    <div><strong>Sunlight: </strong><i> <?php echo htmlspecialchars($searchResult['sunlight']); ?></i></div>
                    <div><strong>Poisonous: </strong><i> <?php echo htmlspecialchars($searchResult['poisonous']); ?></i></div>
                    <div><strong>Soil Needed: </strong><i> <?php echo htmlspecialchars($searchResult['soil_needed']); ?></i></div>
                    <button id="notifyBtn">Receive Notifications</button>
                    <form id="notifyForm" action="subscribe.php" method="post" style="display:none;">
                        <input type="hidden" name="username" value="<?php echo htmlspecialchars($_SESSION['name']); ?>">
                        <input type="hidden" name="email" value="<?php echo htmlspecialchars($_SESSION['email']); ?>">
                        <input type="hidden" name="plant_name" value="<?php echo htmlspecialchars($searchResult['common_plant']); ?>">
                        <input type="hidden" name="sunlight" value="<?php echo htmlspecialchars($searchResult['sunlight']); ?>">
                        <input type="hidden" name="watering" value="<?php echo htmlspecialchars($searchResult['watering']); ?>">
                        <input type="hidden" name="soil" value="<?php echo htmlspecialchars($searchResult['soil_needed']); ?>">
                        <label for="time">Notification Time:</label>
                        <input type="time" name="time" required>
                        <input type="submit" value="Subscribe">
                    </form>
                </div>
            <?php else: ?>
                <div class="no-results"><?php echo htmlspecialchars($searchResult); ?></div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    <h1>You may also like....</h1>
    <div class="plantcards">
        <?php if (!empty($plants)): ?>
            <?php foreach ($plants as $plant): ?>
                <div class="card1">
                    <h3>Name: <i><?php echo htmlspecialchars($plant['common_plant']);?></i> </h3>
                    <div><strong>Scientific Name: </strong><i> <?php echo htmlspecialchars($plant['scientific_name']); ?></i></div>
                    <div><strong>Soil Needed: </strong><i> <?php echo htmlspecialchars($plant['soil_needed']); ?></i></div>
                </div>
        <?php endforeach; ?>
        <?php else: ?>
            <div class="no-results">No plants available.</div>
        <?php endif; ?>
    </div>
    <script>
        document.getElementById('notifyBtn')?.addEventListener('click', function() {
            document.getElementById('notifyForm').style.display = 'block';
            this.style.display = 'none';
        });
    </script>
</body>
</html>
