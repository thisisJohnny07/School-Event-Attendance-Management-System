<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

$statusMsg = '';
  
$query = mysqli_query($conn,"SELECT password, organizationName, profile FROM admin WHERE Id ='$_SESSION[userId]'");
$ret = mysqli_fetch_array($query);

if(isset($_POST['update'])) {
  
    $organizationName = $_POST['organizationName'];

    $sampPass = $_POST['password'];
    $sampPass_2 = md5($sampPass);

    $oldPass = $_POST['oldPassword'];
    $sampOldPass_2 = md5($oldPass);

    $path = $_FILES['photo']['name'];
    $path_tmp = $_FILES['photo']['tmp_name'];

    if (empty($sampPass) && empty($path_tmp)) {
      $query = mysqli_query($conn, "UPDATE admin SET organizationName='$organizationName' WHERE Id='$_SESSION[userId]'");

      if ($query) {
          echo "<script type='text/javascript'>
          window.location = 'editProfile.php';
          </script>";
      } else {
          $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error occurred while updating profile!</div>";
      }
    } else if (empty($sampPass) && !empty($path_tmp)) {
              // Check if a file was uploaded
              if(!empty($path_tmp) && $_FILES['photo']['size'] > 0) {
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif');
    
                // Check if the file extension is valid
                if(in_array($ext, $allowed_extensions)) {
                    $final_name = 'profile-'.$_SESSION['userId'].'.'.$ext;
                    unlink('uploads/'.$ret['profile']);
                    move_uploaded_file($path_tmp, 'uploads/'.$final_name);
    
                    // Update database with new information
                    $query = mysqli_query($conn, "UPDATE admin SET organizationName='$organizationName',
                    profile='$final_name' WHERE Id='$_SESSION[userId]'");
    
                    if ($query) {
                        echo "<script type='text/javascript'>
                        window.location = 'editProfile.php';
                        </script>";
                    } else {
                        $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error occurred while updating profile!</div>";
                    }
                } else {
                    $statusMsg = 'You must upload a file with jpg, jpeg, gif, or png extension.';
                }
            } else {
                // Handle case where no file was uploaded
                $statusMsg = 'Please select a file to upload.';
            }
    } else if (!empty($sampPass) && empty($path_tmp)) {
      // Check if the old password matches the password in the database
      if($sampOldPass_2 === $ret['password']) {

                // Update database with new information
                $query = mysqli_query($conn, "UPDATE admin SET organizationName='$organizationName', 
                password='$sampPass_2' WHERE Id='$_SESSION[userId]'");

                if ($query) {
                    echo "<script type='text/javascript'>
                    window.location = 'editProfile.php';
                    </script>";
                } else {
                    $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error occurred while updating profile!</div>";
                }
      } else {
          // Handle case where old password does not match
          $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>Wrong Password!</div>";
      }
    } else {
      // Check if the old password matches the password in the database
      if($sampOldPass_2 === $ret['password']) {

        // Check if a file was uploaded
        if(!empty($path_tmp) && $_FILES['photo']['size'] > 0) {
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif');

            // Check if the file extension is valid
            if(in_array($ext, $allowed_extensions)) {
                $final_name = 'profile-'.$_SESSION['userId'].'.'.$ext;
                unlink('uploads/'.$ret['profile']);
                move_uploaded_file($path_tmp, 'uploads/'.$final_name);

                // Update database with new information
                $query = mysqli_query($conn, "UPDATE admin SET organizationName='$organizationName',
                password='$sampPass_2', profile='$final_name' WHERE Id='$_SESSION[userId]'");

                if ($query) {
                    echo "<script type='text/javascript'>
                    window.location = 'editProfile.php';
                    </script>";
                } else {
                    $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error occurred while updating profile!</div>";
                }
            } else {
                $statusMsg = 'You must upload a file with jpg, jpeg, gif, or png extension.';
            }
        } else {
            // Handle case where no file was uploaded
            $statusMsg = 'Please select a file to upload.';
        }
    } else {
        // Handle case where old password does not match
        $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>Wrong Password!</div>";
    }
    }
}
?>

<!-- Your HTML code goes here -->


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link href="img/logo/ndmulogo.png" rel="icon">
  <?php include 'includes/title.php';?>
  <link href="../vendor/fontawesome-free/css/all.css" rel="stylesheet" type="text/css">
  <link href="../vendor/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.css" rel="stylesheet">



   <script>
    function classArmDropdown(str) {
    if (str == "") {
        document.getElementById("txtHint").innerHTML = "";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("txtHint").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","ajaxClassArms2.php?cid="+str,true);
        xmlhttp.send();
    }
}
</script>
</head>

<body id="page-top">
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <!-- TopBar -->
       <?php include "Includes/topbarProfile.php";?>
        <!-- Topbar -->

        <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Edit Profile</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="index.php">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Edit Profile</li>
            </ol>
          </div>
          <?php
              $getInfo=mysqli_query($conn,"SELECT organizationName FROM admin WHERE Id ='$_SESSION[userId]'");
              $row=mysqli_fetch_array($getInfo);
          ?>
          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Edit Profile</h6>
                    <?php echo $statusMsg; ?>
                </div>
                <div class="card-body">
                  <form method="post" enctype="multipart/form-data">
                   <div class="form-group row mb-3">
                        <div class="col-xl-6">
                          <label class="form-control-label">Organization Name<span class="text-danger ml-2">*</span></label>
                          <input type="text" class="form-control" name="organizationName" value="<?php echo $row['organizationName'];?>" id="exampleInputFirstName" >
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <div class="col-xl-6">
                          <label class="form-control-label">Profile<span class="text-danger ml-2">*</span></label>
                          <input type="file" class="form-control" name="photo" id="exampleInputFirstName" >(Only jpg, jpeg, gif and png are allowed)
                        </div>
                    </div>
                     <div class="form-group row mb-3">
                        <div class="col-xl-6">
                          <label class="form-control-label">Old Password<span class="text-danger ml-2">*</span></label>
                          <input type="password" class="form-control" name="oldPassword" value="<?php echo $row['password'];?>" id="exampleInputPassword" >
                          </div>
                        <div class="col-xl-6">
                          <label class="form-control-label">Password<span class="text-danger ml-2">*</span></label>
                          <input type="password" class="form-control" name="password" value="<?php echo $row['password'];?>" id="exampleInputPassword" >
                        </div>
                    </div>
                    <button type="submit" name="update" class="btn btn-success">Save</button>
                    <a href="profile.php" name="cancel" class="btn btn-danger">Cancel</a>
                  </form>
                </div>
              </div>

          <!-- Documentation Link -->
          <!-- <div class="row">
            <div class="col-lg-12 text-center">
              <p>For more documentations you can visit<a href="https://getbootstrap.com/docs/4.3/components/forms/"
                  target="_blank">
                  bootstrap forms documentations.</a> and <a
                  href="https://getbootstrap.com/docs/4.3/components/input-group/" target="_blank">bootstrap input
                  groups documentations</a></p>
            </div>
          </div> -->

        </div>
        <!---Container Fluid-->
      </div>
      <!-- Footer -->
       <?php include "Includes/footer.php";?>
      <!-- Footer -->
    </div>


  <!-- Scroll to top -->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="js/ruang-admin.min.js"></script>
   <!-- Page level plugins -->
  <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <!-- Page level custom scripts -->
  <script>
    $(document).ready(function () {
      $('#dataTable').DataTable(); // ID From dataTable 
      $('#dataTableHover').DataTable(); // ID From dataTable with Hover
    });
  </script>
</body>

</html>