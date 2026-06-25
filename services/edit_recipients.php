<?php
session_start();

if (!isset($_SESSION['volunteer_id'])) {
    header("Location: ../index.php");
    exit();
}

require_once '../services/db_connect.php';
//get the data form db
try {
    $stmt = $pdo->prepare("select recipient_id, first_name, last_name, age, guardian_fname, guardian_lname from recipients order by last_name ASC, first_name ASC");
    $stmt->execute();
} catch (PDOException $e) {
    $recipients = [];
    $error_msg = "Could not load data.";
}

?>
<!DOCTYPE HTML>
<html>
<head>
    <title>Edit/View Recipient</title>
</head>
<body>

<div class="layout">
    <main class="main-content">
        <?php include '../includes/header.php'; ?>

        <div class="workspace-container">
            
            <div class="recipient-edit-list">
                <h3>Select a Recipient to Edit</h3>

                <?php 
                    
                ?>
                                 
            </div>
        </div>
    </main>

    <?php include '../includes/sidebar.php'; ?>

</div>

</body>
</html>