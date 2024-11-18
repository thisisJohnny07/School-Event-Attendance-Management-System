
<?php 
  include '../Includes/dbcon.php';
  include '../Includes/session.php';

  $query = "SELECT * FROM students WHERE Id = ".$_SESSION['userId']."";

  $rs = $conn->query($query);
  $num = $rs->num_rows;
  $rrw = $rs->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

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
  </head>

  <body id="page-top">
    <div id="wrapper">
      <!-- Sidebar -->
      <?php include "Includes/sidebar.php";?>
      <!-- Sidebar -->
      <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <!-- TopBar -->
            <?php include "Includes/topbar.php";?>
            <!-- Topbar -->
          <!-- Container Fluid-->
          <div class="container-fluid" id="container-wrapper">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
              <h1 class="h3 mb-0 text-gray-800">Events List</h1>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
              </ol>
            </div>

            <div class="row mb-3">
            <!-- Students Card -->
            <?php 
              $query1 = mysqli_query($conn, "SELECT events.*
                                FROM events
                                LEFT JOIN attendance ON attendance.eventId = events.Id
                                WHERE events.Id IN (attendance.eventId)
                                GROUP BY events.Id");
                       
            while ($row = mysqli_fetch_assoc($query1)) {
                $startDate = date("Y-m-d", strtotime($row['startDate']));
                $start = date("H:i:s", strtotime($row['startDate']));
                $end = date("H:i:s", strtotime($row['endDate']));
            ?>
            <div class="col-xl-3 col-md-10 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="h5 mb-0 mr-1 font-weight-bold text-gray-800  mb-1"><?php echo $row['eventName'];?></div>
                                <div class="text-xs font-weight-bold text-uppercase"><?php echo "Date: " . $startDate;?></div>
                                <div class="text-xs font-weight-bold text-uppercase"><?php echo "Time: " . $start . " - " . $end;?></div>
                                <a href="participantsEdit.php?Id=<?php echo $row['Id'] ?>">Edit Event Attendance</a>
                                <div class="mt-2 mb-0 text-muted text-xs">
                                    <!-- <span class="text-success mr-2"><i class="fas fa-arrow-up"></i> 20.4%</span>
                                    <span>Since last month</span> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php 
            } // End while loop
            ?>

            <!--Row-->

            <!-- <div class="row">
              <div class="col-lg-12 text-center">
                <p>Do you like this template ? you can download from <a href="https://github.com/indrijunanda/RuangAdmin"
                    class="btn btn-primary btn-sm" target="_blank"><i class="fab fa-fw fa-github"></i>&nbsp;GitHub</a></p>
              </div>
            </div> -->

          </div>
          <!---Container Fluid-->
          <a class="btn btn-success" href="eventsList.php">Take Attendance</a>
        </div>
        <!-- Footer -->
        <?php include 'includes/footer.php';?>
        <!-- Footer -->
      </div>
    </div>

    <!-- Scroll to top -->
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fas fa-angle-up"></i>
    </a>

    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/ruang-admin.min.js"></script>
    <script src="../vendor/chart.js/Chart.min.js"></script>
    <script src="js/demo/chart-area-demo.js"></script>  
  </body>
</html>