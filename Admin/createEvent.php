
<?php 
  error_reporting(0);
  include '../Includes/dbcon.php';
  include '../Includes/session.php';

//------------------------SAVE--------------------------------------------------

if(isset($_POST['save'])) {
  // Extract data from the form
  $eventName = $_POST['eventName'];
  $startDate = $_POST['startDate'];
  $endDate = $_POST['endDate'];
  $fines = $_POST['fines'];
  $eventDescription = $_POST['eventDescription'];
  $selectedClasses = $_POST['check'];
  
  // Check if the event name already exists
  $query = mysqli_query($conn, "SELECT * FROM events WHERE eventName = '$eventName'");
  $ret = mysqli_fetch_array($query);

  if($ret > 0){ 
      $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>This Event Name Already Exists!</div>";
  } else {
      // Start the transaction
      mysqli_autocommit($conn, false);
      $success = true;

      // Insert into events table
      $queryEvents = "INSERT INTO events(eventName, startDate, endDate, fines, eventDescription) 
                      VALUES ('$eventName', '$startDate', '$endDate', '$fines', '$eventDescription')";
      $resultEvents = mysqli_query($conn, $queryEvents);
      if (!$resultEvents) {
          $success = false;
      }

      // Get the ID of the newly inserted event
      $eventId = mysqli_insert_id($conn);

      // Insert into event_classes table for each selected class
      foreach ($selectedClasses as $classId) {
          $queryEventClasses = "INSERT INTO classevents(eventId, classId) 
                                VALUES ('$eventId', '$classId')";
          $resultEventClasses = mysqli_query($conn, $queryEventClasses);
          if (!$resultEventClasses) {
              $success = false;
          }
      }

      // Commit or rollback the transaction based on success
      if ($success) {
          mysqli_commit($conn);
          $statusMsg = "<div class='alert alert-success' style='margin-right:700px;'>Event created successfully!</div>";
      } else {
          mysqli_rollback($conn);
          $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error occurred while creating the event!</div>";
      }

      // Restore autocommit mode
      mysqli_autocommit($conn, true);
  }
}


//--------------------EDIT------------------------------------------------------------
  if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "edit"){
    $Id= $_GET['Id'];

    $query=mysqli_query($conn,"select * from events where Id ='$Id'");
    $row=mysqli_fetch_array($query);

    //------------UPDATE-----------------------------
    if(isset($_POST['update'])){

      mysqli_autocommit($conn, false);
      $success = true;
    
      $eventName = $_POST['eventName'];
      $startDate = $_POST['startDate'];
      $endDate = $_POST['endDate'];
      $fines = $_POST['fines'];
      $eventDescription = $_POST['eventDescription'];
      $selectedClasses = $_POST['check'];

      $queryUpdateEvent=mysqli_query($conn,"update events set eventName='$eventName', startDate='$startDate', 
      endDate='$endDate', fines='$fines', eventDescription='$eventDescription'
      where Id='$Id'");

      if (!$queryUpdateEvent) {
        $success = false;
      }

      $queryDeleteEventClasses = mysqli_query($conn, "DELETE FROM classevents WHERE eventId='$Id'");

      if (!$queryDeleteEventClasses) {
        $success = false;
      }

      // Insert into event_classes table for each selected class
      foreach ($selectedClasses as $classId) {
          $queryUpdateEventClasses = "INSERT INTO classevents(eventId, classId) 
                                VALUES ('$Id', '$classId')";
          $resultUpdateEventClasses = mysqli_query($conn, $queryUpdateEventClasses);
          if (!$resultUpdateEventClasses) {
              $success = false;
          }
      }
      
      if ($success) {
        mysqli_commit($conn);        
        echo "<script type = \"text/javascript\">
        window.location = (\"createEvent.php\")
        </script>"; 
      }
      else {
        mysqli_rollback($conn);
        $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
      }
    }
  }

//--------------------------------DELETE------------------------------------------------------------------

  if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "delete") {
    
    $Id= $_GET['Id'];

    mysqli_autocommit($conn, false);
    $success = true;

    $queryDeleteEvent = mysqli_query($conn,"DELETE FROM events WHERE Id='$Id'");

    if (!$queryDeleteEvent) {
      $success = false;
    }

    $queryDeleteEventClasses = mysqli_query($conn, "DELETE FROM classEvents WHERE eventId='$Id'");
    if (!$queryDeleteEventClasses) {
        $success = false;
    }

    if ($success) {
      mysqli_commit($conn);
      echo "<script type = \"text/javascript\">
      window.location = (\"createEvent.php\")
      </script>";
    }
    else {
      mysqli_rollback($conn);
      $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>"; 
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
            <h1 class="h3 mb-0 text-gray-800">Create Events</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="index.php">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Create Events</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Create Events</h6>
                    <?php echo $statusMsg; ?>
                </div>
                <div class="card-body">
                  <form method="post">
                   <div class="form-group row mb-3">
                        <div class="col-xl-6">
                          <label class="form-control-label">Event Name<span class="text-danger ml-2">*</span></label>
                          <input type="text" class="form-control" required name="eventName" value="<?php echo $row['eventName'];?>" id="exampleInputFirstName" >
                        </div>
                        <div class="col-xl-6">
                          <label class="form-control-label">Start<span class="text-danger ml-2">*</span></label>
                          <input type="datetime-local" class="form-control" required name="startDate" value="<?php echo $row['startDate'];?>" id="exampleInputFirstName" >
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <div class="col-xl-6">
                          <label class="form-control-label">End<span class="text-danger ml-2">*</span></label>
                          <input type="datetime-local" class="form-control" required name="endDate" value="<?php echo $row['endDate'];?>" id="exampleInputFirstName" >
                        </div>
                        <div class="col-xl-6">
                          <label class="form-control-label">Fines<span class="text-danger ml-2">*</span></label>
                          <input type="number" class="form-control" required name="fines" value="<?php echo $row['fines'];?>" id="exampleInputPassword" >
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <div class="col-xl-6">
                          <label class="form-control-label">Description<span class="text-danger ml-2">*</span></label>
                          <input type="text" class="form-control" required name="eventDescription" value="<?php echo $row['eventDescription'];?>" id="exampleInputFirstName" >
                        </div>
                        <div class="col-xl-6">
                        <label class="form-control-label">Select Participating Class<span class="text-danger ml-2">*</span></label>
                        <?php
                          $qry= "SELECT * FROM class ORDER BY className ASC";
                          $result = $conn->query($qry);
                          $num = $result->num_rows;		
                          if ($num > 0){
                              echo '<div class="mb-3">';
                              while ($rows = $result->fetch_assoc()){
                                  echo '<div class="form-check">';
                                  echo '<input type="checkbox" name="check[]" value="'.$rows['Id'].'" class="form-check-input" id="class_'.$rows['Id'].'">';
                                  echo '<label class="form-check-label" for="class_'.$rows['Id'].'">'.$rows['className'].'</label>';
                                  echo '</div>';
                              }
                              echo '</div>';
                          }
                        ?> 
                        </div>
                    </div>
                      <?php
                    if (isset($Id))
                    {
                    ?>
                    <button type="submit" name="update" class="btn btn-warning">Update</button>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <?php
                    } else {           
                    ?>
                    <button type="submit" name="save" class="btn btn-primary">Save</button>
                    <?php
                    }         
                    ?>
                  </form>
                </div>
              </div>

              <!-- Input Group -->
                 <div class="row">
              <div class="col-lg-12">
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">All Events</h6>
                </div>
                <div class="table-responsive p-3">
                  <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                    <thead class="thead-light">
                      <tr>
                        <th>#</th>
                        <th>Event Name</th>
                        <th>Description</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Fines</th>
                        <th>Participants</th>
                        <th>Edit</th>
                        <th>Delete</th>
                      </tr>
                    </thead>
                
                    <tbody>

                    <?php
                      $query = "SELECT events.*, GROUP_CONCAT(class.className SEPARATOR ', ') AS classesInvolved
                                FROM events
                                LEFT JOIN classEvents ON events.Id = classEvents.eventId
                                LEFT JOIN class ON class.Id = classEvents.classId
                                GROUP BY events.Id
                                ORDER BY events.startDate";
                      $rs = $conn->query($query);
                      $num = $rs->num_rows;
                      $sn=0;
                      $status="";
                      if($num > 0) { 
                          while ($rows = $rs->fetch_assoc()) {
                              $sn = $sn + 1;
                              echo "
                                  <tr>
                                      <td>".$sn."</td>
                                      <td>".$rows['eventName']."</td>
                                      <td>".$rows['eventDescription']."</td>
                                      <td>".$rows['startDate']."</td>
                                      <td>".$rows['endDate']."</td>
                                      <td>".$rows['fines']."</td>
                                      <td>".$rows['classesInvolved']."</td>
                                      <td><a href='?action=edit&Id=".$rows['Id']."'><i class='fas fa-fw fa-edit'></i></a></td>
                                      <td><a href='?action=delete&Id=".$rows['Id']."'><i class='fas fa-fw fa-trash'></i></a></td>
                                  </tr>";
                          }
                      } else {
                          echo "<div class='alert alert-danger' role='alert'>No Record Found!</div>";
                      }
                      ?>
                    </tbody>
                  </table>
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
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- Page level custom scripts -->
  <script>
    $(document).ready(function () {
      $('#dataTable').DataTable(); // ID From dataTable 
      $('#dataTableHover').DataTable(); // ID From dataTable with Hover
    });
    
    $(document).ready(function(){
        $('form').submit(function(e){
            if($('input[name="check[]"]:checked').length === 0){
                alert("Please select at least one class.");
                e.preventDefault();
            }
        });
    });

  </script>
</body>

</html>