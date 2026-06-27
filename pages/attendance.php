<?php
session_start();

if (!isset($_SESSION['volunteer_id'])) {
    header("Location: ../index.php");
    exit();
}

require_once '../services/db_connect.php';
//this is for getting the dates for m-f of the current week

$monday_time = strtotime('monday this week');
$week_days = [];
for ($i = 0; $i < 5; $i++) {
    //stores as YYYY-MM-DD for the db and Mon 06/25 for layout
    $timestamp = strtotime("+$i days", $monday_time);
    $week_days[$i] = [
        'db_date'      => date('Y-m-d', $timestamp),
        'display_date' => date('D m/d', $timestamp)
    ];
}
//get data
try {
    $stmt = $pdo->prepare("select recipient_id, first_name, last_name, allergies
        from recipients order by last_name ASC, first_name ASC");
    $stmt->execute();

    $start_week = $week_days[0]['db_date'];
    $end_week   = $week_days[4]['db_date'];
    $logStmt = $pdo->prepare("select recipient_id, attendance_date from attendance where attendance_date between :start_week and :end_week");
    $logStmt->execute(['start_week' => $start_week, 'end_week' => $end_week]);
    
    //look for already there kids
    $existing_pickups = [];
    while ($log = $logStmt->fetch(PDO::FETCH_ASSOC)) {
        $existing_pickups[$log['recipient_id']][] = $log['attendance_date'];
    }

} catch (PDOException $e) {
    $error_msg = "Could not load data.";
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Attendance Tracking</title>
    <link rel="stylesheet" type="text/css" href="../CSS/styles.css">
    <link rel="stylesheet" type="text/css" href="../CSS/table.css">
</head>
<body>
    <div class="layout">

        <main class="main-content">
            <?php include '../includes/header.php'; ?>

            <div class="workspace-container">
                
                <div class="attendance-workspace">
                    <h3>Weekly Attendance</h3>

                    <?php 
                        if (isset($error_msg)){//if any error
                            echo "<p>" . $error_msg . "</p>";
                        } else if ($stmt->rowCount() == 0){//if noone there
                            echo "<p>No recipients registered in the system yet.</p>";
                        } else {
                    ?>
                    
                    <table class="attendance-table">
                        <thead>
                            <tr>
                                <th>Last Name</th>
                                <th>First Name</th>
                                <th>Allergies</th>
                                <?php 
                                    //dates for the current week
                                    foreach ($week_days as $day) {
                                        echo "<th>" . $day['display_date'] . "</th>";
                                    }
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                while ($Row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<tr class='attendance-row'>";
                                    echo "<td class='cell-lname'>" . htmlspecialchars($Row['last_name']) . "</td>";
                                    echo "<td class='cell-fname'>" . htmlspecialchars($Row['first_name']) . "</td>";
                                    echo "<td class='cell-allergies'>" . htmlspecialchars($Row['allergies'] ?? 'None') . "</td>";
                                    
                                    //go through each day to get the tracker box
                                    foreach ($week_days as $day) {
                                        echo "<td class='cell-day-check'>";
                                        
                                        // check if alray logged
                                        $has_picked_up = isset($existing_pickups[$Row['recipient_id']]) 
                                            && in_array($day['db_date'], $existing_pickups[$Row['recipient_id']]);
                                        
                                        // switch how it is displayed based on status.
                                        if ($has_picked_up) {
                                            $btn_text = "✓ Present"; //here
                                            $status_button = "mark-present";
                                        } else {
                                            $btn_text = "[ &nbsp; ]"; // empty
                                            $status_button = "mark-empty";
                                        }
                                        
                                        echo "<form action='../services/log_attendance.php' method='POST'>";
                                        echo "<input type='hidden' name='recipient_id' value='" . $Row['recipient_id'] . "'>";
                                        echo "<input type='hidden' name='attendance_date' value='" . $day['db_date'] . "'>";
                                        
                                        echo "<button type='submit' name='status' value='Present' class='table-submit " . $status_button . "'>" . $btn_text . "</button>";
                                        
                                        echo "</form>";
                                        echo "</td>";
                                    }
                                    echo "</tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                    
                    <?php 
                        } //close else
                    ?>
                                
                </div>

            </div>
        </main>

        <?php include '../includes/sidebar.php'; ?>

    </div>

</body>
</html>

