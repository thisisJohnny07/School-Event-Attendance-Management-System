<?php 
  error_reporting(0);
  include '../Includes/dbcon.php';
  include '../Includes/session.php';

    $classId = $_GET['Id'];
    $query = "SELECT * 
    FROM class
    WHERE Id = $classId";
    $rs = $conn->query($query);
    $num = $rs->num_rows;
    $rrw = $rs->fetch_assoc();

    if(isset($_POST['save'])) {

      $dateTaken = date("Y-m-d");
      $check = isset($_POST['check']) ? $_POST['check'] : array();
      $classId = isset($_GET['Id']) ? $_GET['Id'] : null; // Get the classId from the URL parameter
      $eventId = isset($_GET['eventId']) ? $_GET['eventId'] : null; // Get the eventId from the URL parameter
      $statusMsg = "";
      
      // Check if the event name already exists
      $checkAttendance = mysqli_query($conn, "SELECT * FROM attendance WHERE eventId = '$eventId' AND classId = $classId");
      $ret = mysqli_fetch_array($checkAttendance);

      if($ret > 0){ 
        $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>Attendance has been taken!</div>";
      } else { 
        if($classId !== null) { // Proceed only if classId is provided

          // Select student IDs where classId matches the provided Id from URL parameter
          $query = "SELECT Id FROM students WHERE classId = $classId";
          $rs = $conn->query($query);
          
          while ($row = $rs->fetch_assoc()) {
              $studentId = $row['Id'];
              $statusValue = in_array($studentId, $check) ? 1 : 0;
              $qquery = mysqli_query($conn, "INSERT INTO attendance(idNumber, status, dateTaken, classId, eventId) VALUES($studentId, $statusValue, '$dateTaken', $classId, $eventId)");
  
              if ($qquery) {
                  $statusMsg = "<div class='alert alert-success' style='margin-right:700px;'>Attendance Taken Successfully!</div>";
              } else {
                  $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error occurred!</div>";
              }
          }
        } else {
            $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>Class ID not provided!</div>";
        }
      }
  }  
  
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
  <title>Take Attendance <?php echo  'd'?></title>
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
            <?php 
              $eventId = $_GET['eventId'];
              $query = "SELECT * 
              FROM events
              WHERE Id = $eventId";
              $rs = $conn->query($query);
              $num = $rs->num_rows;
              $row = $rs->fetch_assoc();
            ?>
            <h1 class="h3 mb-0 text-gray-800">Take Attendance (<?php echo $row['eventName'];?>)</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="index.php">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">All Student in Class</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->


              <!-- Input Group -->
        <form method="post">
            <div class="row">
              <div class="col-lg-12">
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">All Student in (<?php echo $rrw['className'];?>) Class</h6>
                  <h6 class="m-0 font-weight-bold text-danger">Note: <i>Click on the checkboxes besides each student to take attendance!</i></h6>
                </div>
                <div class="table-responsive p-3">
                <?php echo $statusMsg; ?>
                  <table class="table align-items-center table-flush table-hover">
                    <thead class="thead-light">
                      <tr>
                        <th>#</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Admission No</th>
                        <th>Check</th>
                      </tr>
                    </thead>
                    
                    <tbody>

                  <?php
                      if(isset($_GET['Id']) && isset($_GET['eventId'])) {
                      $classId = $_GET['Id'];
                      $eventId = $_GET['eventId'];
                      $query = "SELECT class.*, students.*
                      FROM students
                      INNER JOIN class ON class.Id = students.classId
                      where class.Id = $classId";
                      $rs = $conn->query($query);
                      $num = $rs->num_rows;
                      $sn=0;
                      $status="";
                      if($num > 0)
                      { 
                        while ($rows = $rs->fetch_assoc())
                          {
                             $sn = $sn + 1;
                            echo"
                              <tr>
                                <td>".$sn."</td>
                                <td>".$rows['firstName']."</td>
                                <td>".$rows['lastName']."</td>
                                <td>".$rows['Id']."</td>
                                <td><input name='check[]' type='checkbox' value=".$rows['Id']." class='form-control'></td>
                              </tr>";
                          }
                      }
                      else
                      {
                           echo   
                           "<div class='alert alert-danger' role='alert'>
                            No Record Found!
                            </div>";
                      }
                    }
                      ?>
                    </tbody>
                  </table>
                  <br>
                  <button type="submit" name="save" class="btn btn-primary">Take Attendance</button>
                  </form>
                </div>
              </div>
            </div>
            </div>
          </div>
          <!--Row-->

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