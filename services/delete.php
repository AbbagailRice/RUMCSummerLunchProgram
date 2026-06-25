<?php
session_start();

if (!isset($_SESSION['volunteer_id'])) {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once 'db_connect.php';

    // Grab the data
    $recipient_id = isset($_POST['recipient_id']) ? (int)$_POST['recipient_id'] : 0;

    if ($recipient_id > 0) {
        try {
            // delete
            $sqlString = "DELETE FROM recipients WHERE recipient_id = :recipient_id";
            $stmt = $pdo->prepare($sqlString);
            $stmt->execute(['recipient_id' => $recipient_id]);

            // check if the record existed and was deleted
            if ($stmt->rowCount() > 0) {
                // successful, return to the workspace
                header("Location: ../pages/manage_recipients.php?success=1");
                exit();
            } else {
                die("Error: Record not found in the system database.");
            }

        } catch (PDOException $e) {
            die("Error: Failed to delete.");
        }
    } else {
        die("Error: Invalid recipient ID.");
    }
} else {
    header("Location: ../pages/manage_recipients.php");
    exit();
}
?>