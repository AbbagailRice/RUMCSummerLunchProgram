<?php
session_start();

    // i f they are not logged in, OR if they are not an admin, send away.
    if (!isset($_SESSION['volunteer_id']) || $_SESSION['is_admin'] != 1) {
        header("Location: ../index.php");
        exit();
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['volunteer_id'])) {
        require_once '../services/db_connect.php'; //log into db
        //make sure id is still an int
        $volunteer_id = (int)$_POST['volunteer_id'];
        try {
        // update the column to true
        $stmt = $pdo->prepare("UPDATE volunteers SET is_approved = 1 WHERE volunteer_id = :id");
        $stmt->execute(['id' => $volunteer_id]);

        // go back to admin dash
        header("Location: ../pages/admin_dashboard.php");
        exit();

    } catch (PDOException $e) { //if fail for some reason
        die("System error: Unable to authorize this volunteer profile.");
    }
} else {
    // if someone routes here for no reason.
    header("Location: ../index.php");
    exit();
}

?>