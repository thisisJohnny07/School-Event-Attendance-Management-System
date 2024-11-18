<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

?>
        <table border="1">
        <thead>
            <tr>
            <th>#</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Admission No</th>
            <th>Class</th>
            <th>Event</th>
            <th>Status</th>
            </tr>
        </thead>
<?php
    if(isset($_GET['Id'])) {
        $classId = $_GET['Id'];
        $filename="Attendance list";
        $dateTaken = date("Y-m-d");

        $cnt=1;			
        $ret = mysqli_query($conn,"SELECT *
                            From attendance
                            INNER JOIN students ON students.Id = attendance.IdNumber
                            INNER JOIN class ON class.Id = attendance.classId
                            INNER JOIN events ON events.Id = attendance.eventId
                            WHERE attendance.classId = $classId");

        if(mysqli_num_rows($ret) > 0 )
        {
        while ($row=mysqli_fetch_array($ret)) 
        { 
            
            if($row['status'] == '1'){$status = "Present";}else{$status = "Absent";}

        echo '  
        <tr>  
        <td>'.$cnt.'</td> 
        <td>'.$firstName= $row['firstName'].'</td> 
        <td>'.$lastName= $row['lastName'].'</td> 
        <td>'.$otherName= $row['idNumber'].'</td> 
        <td>'.$className= $row['className'].'</td>
        <td>'.$className= $row['eventName'].'</td>
        <td>'.$status.'</td> 	 	 					
        </tr>  
        ';
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=".$filename."-report.xls");
        header("Pragma: no-cache");
        header("Expires: 0");
                    $cnt++;
                    }
        }
    }
?>
</table>