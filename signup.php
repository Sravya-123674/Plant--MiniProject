<?php
    session_start();
    include "Components/_navbar.php";

?>
<style>
        body{
            color:green;
        }
        .Signupcontainer{
            width:400px;
            height:370px;
            border:2px solid;
            display:block;
            margin-left:auto;
            margin-top:150px;
            margin-right: 100px;
            text-align: center;
            border-radius: 12px;
            border:6px solid ;
        }
        form{
            display:flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        input{
            width:350px;
            height: 30px;
            margin: 10px;
            text-align: center;
            border-radius: 12px;
            color:green;
            border: 2px solid;
        }
        body::before{
            content:"";
            position: absolute;
            background: url(screen.jpg)no-repeat center center/cover;
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
    <div class="Signupcontainer">
        <h1>Signup</h1>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="text" name="uname" id="uname" placeholder="Enter user name">
            <input type="mail" name="umail" id="umail" placeholder="Enter user mail">
            <input type="password" name="upass" id="upass" placeholder="Enter password">
            <input type="password" name="cpass" id="cpass" placeholder="Confirm password">
            <input type="submit" value="Signup">
        </form>
    </div>
</body>
</html>


<?php
    $servername = 'localhost:3307';
    $username = 'root';
    $password = "";
    $database = "plantcare"; 
    $conn = mysqli_connect($servername,$username,$password,$database);
    if(!$conn){
        die('Cannot connect to the data base');
    }else{
        if($_SERVER['REQUEST_METHOD']=='POST'){
            $username = $_POST['uname'];
            $mail = $_POST['umail'];
            $upass = $_POST['upass'];
            $cpass = $_POST['cpass'];
            $uniquesql = "Select * from `userdetails` where `user_name`='$username'";
            $uniquesqlresult = mysqli_query($conn,$uniquesql);
            $noofrows = mysqli_num_rows($uniquesqlresult);
            if($noofrows > 0){
                echo '<script>alert("Username is already taken");</script>';
            }
            if (!preg_match("/^[a-zA-Z0-9]{8,}$/", $username)) {
                echo '<script>alert("Username must be at least 8 characters long.");</script>';
                exit();
            }


            if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                echo '<script>alert("Invalid email format.");</script>';
                exit();
            }

            if (!preg_match("/^(?=.*[A-Za-z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/", $upass)) {
                echo '<script>alert("Password must contain at least one letter, one number, and one special character, and be at least 8 characters long.");</script>';
                exit();
            }
            else{
                if($cpass == $upass){
                    $sql = "INSERT INTO `userdetails` ( `user_name`, `user_mail`, `user_pass`) VALUES ( '$username', '$mail', '$upass')";
                        $result = mysqli_query($conn,$sql);
                    if(!$result){
                        echo '<script>alert("You form is not submitted");</script>';
                    }else{
                        $_SESSION['loggedin']=true;
                        $_SESSION['name'] = $username;
                        $_SESSION['email'] = $mail;
                        echo '<script>alert("You form is successfully submitted");</script>';
                        header("location: home.php");
                        exit();
                    }
                }else{
                    echo '<script>alert("Your passwords doesnot match");</script>';
                }
            }
        }
        
    }
?>