<?php 
    include 'Includes/dbcon.php';
    session_start();

    if(isset($_POST['login'])){

        $userType = $_POST['userType'];
        $Id = $_POST['Id'];
        $password = $_POST['password'];
        $password = md5($password);

        if($userType == "Administrator") {

        $query = "SELECT * FROM admin WHERE Id = '$Id' AND password = '$password'";
        $rs = $conn->query($query);
        $num = $rs->num_rows;
        $rows = $rs->fetch_assoc();

        if($num > 0){

            $_SESSION['userId'] = $rows['Id'];
            $_SESSION['organizationName'] = $rows['organizationName'];
            $_SESSION['emailAddress'] = $rows['emailAddress'];

            echo "<script type = \"text/javascript\">
            window.location = (\"Admin/index.php\")
            </script>";
        } else{

            echo "<div class='alert alert-danger' role='alert'>
            Invalid Username/Password!
            </div>"; 
        }
        } else if($userType == "student") {

            $query = "SELECT * FROM students WHERE Id = '$Id' AND password = '$password'";
            $rs = $conn->query($query);
            $num = $rs->num_rows;
            $rows = $rs->fetch_assoc();

        if($num > 0){

            $_SESSION['userId'] = $rows['Id'];
            $_SESSION['firstName'] = $rows['firstName'];
            $_SESSION['lastName'] = $rows['fastName'];

            echo "<script type = \"text/javascript\">
            window.location = (\"student/index.php\")
            </script>";
        }

        else{

            echo "<div class='alert alert-danger' role='alert'>
            Invalid Username/Password!
            </div>";

        }
        }
        else{

            echo "<div class='alert alert-danger' role='alert'>
            Invalid Username/Password!
            </div>";

        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="css/ruang-admin.css" rel="stylesheet">
    <title>Login</title>
</head>
<body>
  <div class="wrapper">
    <div class="container main">
        <div class="row">
            <div class="col-md-6 side-image">
                       
                <!-------------      image     ------------->
                
                <img src="img/logo/ndmulogo.png" alt="">
                <div class="text">
                <p>Notre Dame of Marbel University</p>
                </div>
                
            </div>

            <div class="col-md-6 right">
                
                <div class="input-box">
                   
                   <header>Sign In to CheckInTrace</header>
                        <form class="user" method="Post" action="index.php">
                        <div class="input-field">   
                                <select required name="userType" class="input">
                                    <option value="">--Select User Roles--</option>
                                    <option value="Administrator">Administrator</option>
                                    <option value="student">Student</option>
                                </select>
                            </div> 
                        <div class="input-field">
                                <input type="number" class="input" name="Id" required >
                                <label for="email">Enter Id Number</label> 
                            </div> 
                        <div class="input-field">
                                <input type="password" class="input" name="password" id="pass" required>
                                <label for="pass">Password</label>
                            </div> 
                        <div class="input-field">   
                                <input type="submit" class="submit btn-success" value="Login" name="login">
                        </div> 
                        </form>
                   <div class="signin">
                   </div>
                </div>  
            </div>
        </div>
    </div>
</div>
<script src="vendor/jquery/jquery.min.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
        <script src="js/ruang-admin.min.js"></script>
</body>
</html>