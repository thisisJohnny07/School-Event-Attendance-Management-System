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

    if(isset($_GET['Id'])) {
        $filename = "Attendance_list " . $rrw['className'];
        $dateTaken = date("Y-m-d");

        // Retrieve all students in the specified class
        $students_query = mysqli_query($conn,"SELECT *
                            FROM students
                            WHERE classId = $classId");

        // Output as Excel file
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=".$filename."-report.xls");

        // Output table headers
        echo "<table border='1'>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Admission No</th>
                        <th>Event</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>";

        // Initialize counter
        $cnt = 1;

        // Output student data
        while ($student_row = mysqli_fetch_array($students_query)) {
            echo "<tr>";
            echo "<td>".$cnt."</td>";
            echo "<td>".$student_row['firstName']."</td>";
            echo "<td>".$student_row['lastName']."</td>";
            echo "<td>".$student_row['Id']."</td>";

            // Retrieve attendance data for the current student
            $attendance_query = mysqli_query($conn,"SELECT *
                                    FROM attendance
                                    INNER JOIN events ON events.Id = attendance.eventId
                                    WHERE IdNumber = ".$student_row['Id']."");

            // Check if the student has attendance records
            if(mysqli_num_rows($attendance_query) > 0 ) {
                while ($attendance_row = mysqli_fetch_array($attendance_query)) {
                    $status = ($attendance_row['status'] == '1') ? 'Present' : 'Absent';
                    echo "<td>".$attendance_row['eventName']."</td>";
                    echo "<td>".$status."</td>";
                }
            } else {
                // If the student has no attendance records, output empty cells
                echo "<td></td>";
                echo "<td></td>";
            }

            echo "</tr>";
            $cnt++;
        }

        echo "</tbody></table>";
    } else {
        echo "Class ID not provided.";
    }
?>
