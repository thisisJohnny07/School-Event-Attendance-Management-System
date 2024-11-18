<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link href="img/logo/ndmulogo.png" rel="icon">
  <title>Dashboard</title>
  <link href="../vendor/fontawesome-free/css/all.css" rel="stylesheet" type="text/css">
  <link href="../vendor/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.css" rel="stylesheet">
<style>
.card-box {
  padding: 20px;
  border-radius: 3px;
  background-color: #fff; /* White card background */
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.35); /* Add shadow */
  margin-bottom: 20px;
}

.thumb-lg {
  height: 50%;
  width: 50%;
}
</style>
</head>
<body>
  <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
          <!-- TopBar -->
          <?php include "Includes/topbarProfile.php"; ?>
          <div class="container-fluid">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
              <h1 class="h3 mb-0 text-gray-800">Profile</h1>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Profile</li>
              </ol>
            </div>
              <div class="container">
                  <div class="row justify-content-center"> <!-- Center the row -->
                      <div class="col-lg-4">
                          <div class="text-center card-box">
                              <div class="member-card pt-2 pb-2">
                                  <?php
                                    $query = "SELECT *
                                    FROM admin
                                    WHERE Id = '$_SESSION[userId]'";
                                    $rs = $conn->query($query);
                                    $num = $rs->num_rows;
                                    $rows = $rs->fetch_assoc();
                                  ?>
                                  <div class="thumb-lg member-thumb mx-auto"><img src="<?php echo 'uploads/' . $rows['profile']; ?>" class="rounded-circle img-thumbnail" alt="profile-image"></div>
                                  <div class="">
                                      <div class="h4"><?php echo $rows['organizationName'];  ?></div>
                                  </div>
                                  <a href="editProfile.php"><button type="button" class="btn btn-success mt-3 btn-rounded waves-effect w-md waves-light">Edit Profile</button></a>
                              </div>
                          </div>
                      </div>
                      <!-- end col -->
                  </div>
              </div>
          </div>
      </div>
  </div>
  <?php include "Includes/footer.php";?>
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

