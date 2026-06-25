<?php
session_start();

if (!isset($_SESSION['volunteer_id'])) {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once 'db_connect.php';
    //grab data
    $recipient_id = isset($_POST['recipient_id']) ? (int)$_POST['recipient_id'] : 0;
    $attendance_date = isset($_POST['attendance_date']) ? trim($_POST['attendance_date']) : '';

    //make sure there is somethign to add
    if ($recipient_id > 0 && !empty($attendance_date)) {
        try {
            // check if already marked 
            $checkStmt = $pdo->prepare("select count(*) from attendance where recipient_id = :recipient_id and attendance_date = :attendance_date");
            $checkStmt->execute([
                'recipient_id'=> $recipient_id,
                'attendance_date'=> $attendance_date
            ]);
            
            if ($checkStmt->fetchColumn() == 0) {
                // log if they are not
                $insertStmt = $pdo->prepare("insert into attendance (recipient_id, attendance_date, status) values (:recipient_id, :attendance_date, :status)");
                $insertStmt->execute([
                    'recipient_id'=> $recipient_id,
                    'attendance_date'=> $attendance_date,
                    'status'=> $status
                ]);
            } else {
                // if this is hit again then unmark them (in case of acidiental)
                $deleteStmt = $pdo->prepare("delete from attendance where recipient_id = :recipient_id and attendance_date = :attendance_date");
                $deleteStmt->execute([
                    'recipient_id' => $recipient_id,
                    'attendance_date'=> $attendance_date
                ]);
            }

            //done
            header("Location: ../pages/attendance.php?success=1");
            exit();

        } catch (PDOException $e) {//issue
            die("Error: Failed to update database attendance tracking logs.");
        }
    } else {// if somehow they have a invalid param
        die("Error: Invalid attendance parameters submitted.");
    }
} else {
    header("Location: ../pages/attendance.php");
    exit();
}
?>