<?php
    include "Components/_navbar.php";
?>
<?php
    session_start();

    $servername = "localhost:3307";
    $username = "root";
    $password = "";
    $database = "plantcare";
    $conn = mysqli_connect($servername, $username, $password, $database);
    if(!$conn){
        die("Connection failed: " . mysqli_connect_error());
    } else {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['uname']) && isset($_POST['upass'])) {
                $name = $_POST['uname'];
                $pass = $_POST['upass'];

                $un = "SELECT * FROM `userdetails` WHERE `user_name` = '$name'";
                $result1 = mysqli_query($conn, $un);
                $noofrows = mysqli_num_rows($result1);

                if ($noofrows == 1) {
                    while ($row = mysqli_fetch_assoc($result1)) {
                        if ($pass == $row['user_pass']) {
                            $_SESSION['loggedin'] = true;
                            $_SESSION['name'] = $name;
                            $_SESSION['email'] = $row['user_mail'];
                            header("Location: home.php");
                            exit();
                        } else {
                            echo "<script>alert('Incorrect password! Please try again.')</script>";
                        }
                    }
                } else {
                    echo "<script>alert('No such user found! Please check your username.')</script>";
                }
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            color: green;
        }
        .logincontainer {
            width: 400px;
            height: 270px;
            border: 2px solid;
            display: block;
            margin-left: auto;
            margin-top: 150px;
            margin-right: 100px;
            text-align: center;
            border-radius: 12px;
            border: 6px solid;
        }
        form {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        input {
            width: 250px;
            height: 30px;
            margin: 10px;
            text-align: center;
            border-radius: 12px;
            color: green;
            border: 2px solid;
        }
        a {
            color: green;
        }
        body::before {
            content: "";
            position: absolute;
            background: url(screen.jpg) no-repeat center center/cover;
            top: 0;
            bottom: 0;
            height: 100%;
            width: 100%;
            z-index: -1;
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="logincontainer">
        <h1>Login</h1>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="text" name="uname" id="uname" placeholder="Enter user name" required>
            <input type="password" name="upass" id="upass" placeholder="Enter password" required>
            <a href="http://localhost/MiniProject/signup.php">Register</a>
            <input type="submit" value="Login">
        </form>
    </div>
</body>
</html>
