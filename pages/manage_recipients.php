<?php 
session_start();

//check to make sure this is a volunteer
if(!isset($_SESSION['volunteer_id'])){
    header("Location: ../index.php");
    exit();
}
require_once '../services/db_connect.php';

?>
<!DOCTYPE HTML>
<html>
<head>
    <title> Manage Recipients</title>
</head>
<body>

    <div class="layout">

        <main class="main-content">

            <?php include '../includes/header.php'; ?>

            <div class="workspace-container">
                
                <div class="recipient-actions-grid">
                    
                    <a href="add_recipients.php" class="action-link-wrapper">
                        <div class="action-item-box">
                            <span class="action-icon">+</span><br>
                            <span>Add Recipient</span>
                        </div>
                    </a>
                    
                    <a href="delete_recipients.php" class="action-link-wrapper">
                        <div class="action-item-box">
                            <span class="action-icon">-</span><br>
                            <span>Delete Recipient</span>
                        </div>
                    </a>
                    
                    <a href="edit_recipients.php" class="action-link-wrapper">
                        <div class="action-item-box">
                            <span class="action-icon">EDIT</span><br>
                            <span>Edit/View Recipient</span>
                        </div>
                    </a>
                    
                </div>

            </div>
        </main>

        <?php include '../includes/sidebar.php'; ?>

    </div>

</body>
</html>