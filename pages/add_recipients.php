<?php
session_start();

if (!isset($_SESSION['volunteer_id'])) {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <title> Add Recipients</title>
</head>
<body>
    <div class="layout">
        <main class="main-content">
            <?php include '../includes/header.php'; ?>

            <div class="workspace-container">
                
                <!-- form for registration -->
                <div class="recipient-registration-box">
                    <h3>Register New Child / Recipient</h3>
                    
                    <form action="../services/register_recipient.php" method="POST">
                        <div class ="form-options">
                            <label>Recipient First Name *</label><br>
                            <input type="text" name="first_name" required>
                        </div>
                        <br>
                        <div class ="form-options">
                            <label>Recipient Last Name *</label><br>
                            <input type="text" name="last_name" required>
                        </div>
                        <br>
                        <div class ="form-options">
                            <label>Age *</label><br>
                            <input type="number" name="age" required>
                        </div>
                        <br>
                        <div class ="form-options">
                            <label>Guardian First Name (Optional)</label><br>
                            <input type="text" name="guardian_fname">
                        </div>
                        <br>
                        <div class ="form-options">
                            <label>Guardian Last Name (Optional)</label><br>
                            <input type="text" name="guardian_lname">
                        </div>
                        <br>
                        <div class ="form-options">
                            <label>Contact Phone *</label><br>
                            <input type="text" name="contact" required>
                        </div>
                        <br>
                        <div class ="form-options">
                            <label>Allergies </label><br>
                            <textarea name="allergies" rows="3" cols="30" placeholder="List any food allergies..."></textarea>
                        </div>
                        <br>
                        <button type="submit">Submit Registration</button>
                    </form>
                </div>

            </div>
        </main>

        <?php include '../includes/sidebar.php'; ?>

    </div>

</body>
</html>