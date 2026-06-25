<?php 
session_start();

//check to make sure this is a volunteer
if(!isset($_SESSION['volunteer_id'])){
    header("Location: ../index.php");
    exit();
}
require_once '../services/db_connect.php';
//init so if try fails it doesnt error
$stmt = null;
$error_msg = null;
try {
    $stmt = $pdo->prepare("select recipient_id, first_name, last_name, age, guardian_fname, 
        guardian_lname, allergies, contact from recipients order by last_name ASC, first_name ASC");
    $stmt->execute();
} catch (PDOException $e) {
    $error_msg = "Could not load data.";
}

?>
<!DOCTYPE HTML>
<html>
<head>
    <title> Manage Recipients</title>
    <!-- style to be moved later-->
     <style>

       .modal-overlay {
            display: none; 
            position: fixed; 
            width: 100%; 
            height: 100%;
        }

        .modal-window {
            background: #fff;
            position: fixed;
            top: 25%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 500px; 
            height: 600px;
            overflow-y: auto;
            padding: 20px;
            border: 1px solid #333;
        }

        .modal-close-btn {
            position: absolute;
            right: 15px;
            top: 10px;
        }
    </style>
</head>
<body>
<div class="layout">
        <main class="main-content">
            <?php include '../includes/header.php'; ?>

            <div class="workspace-container">
                
                <div class="recipient-actions-grid">
                    
                    <button type="button" class="action-btn-trigger" data-target="addModal">
                        <div class="action-item-box">
                            <span class="action-icon">+</span><br>
                            <span class="action-label">Add Recipient</span>
                        </div>
                    </button>
                    
                    <button type="button" class="action-btn-trigger" data-target="deleteModal">
                        <div class="action-item-box">
                            <span class="action-icon">-</span><br>
                            <span class="action-label">Delete Recipient</span>
                        </div>
                    </button>
                    
                    <button type="button" class="action-btn-trigger" data-target="editModal">
                        <div class="action-item-box">
                            <span class="action-icon">EDIT</span><br>
                            <span class="action-label">Edit/View Recipient</span>
                        </div>
                    </button>
                    
                </div>

            </div>
        </main>

        <?php include '../includes/sidebar.php'; ?>
    </div>

   <div id="addModal" class="modal-overlay">
        <div class="modal-window">
            <button type="button" class="modal-close-btn">&times;</button>
            <h3 class="modal-title">Add New Recipient</h3>
            
            <form action="../services/register_recipient.php" method="POST" class="modal-form">
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

    <div id="deleteModal" class="modal-overlay">
        <div class="modal-window extended-width">
            <button type="button" class="modal-close-btn">&times;</button>
            <h3 class="modal-title">Remove Recipient</h3>
            
            <?php 
                if (isset($error_msg)) {
                    echo "<p class='error-message'>" . $error_msg . "</p>";
                }
            ?>

            <table class="recipient-display-table" border="1">
                <thead>
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Age</th>
                        <th>Primary Guardian</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        if ($stmt) {//everything a echo! no switching sucjkhjbfwije
                            while ($Row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                //fill guardian name if empty 
                                $g_fname  = $Row['guardian_fname'] ?? '';
                                $g_lname  = $Row['guardian_lname'] ?? '';
                                $guardian = trim($g_fname . " " . $g_lname);
                                if ($guardian === '') { $guardian = "<em>None listed</em>"; }

                                echo "<tr class='table-row'>";
                                echo "<td class='cell-fname'>" . htmlspecialchars($Row['first_name']) . "</td>";
                                echo "<td class='cell-lname'>" . htmlspecialchars($Row['last_name']) . "</td>";
                                echo "<td class='cell-age'>" . htmlspecialchars($Row['age']) . "</td>";
                                echo "<td class='cell-allergies'>" . htmlspecialchars($Row['allergies'] ?? '') . "</td>";
                                echo "<td class='cell-guardian'>" . $guardian . "</td>";
                                echo "<td class='cell-contact'>" . htmlspecialchars($Row['contact']) . "</td>";
                                echo "<td class='cell-action'>";
                                echo "<form action='../services/delete.php' method='POST' class='table-delete-form' onsubmit=\"return confirm('Confirm Delete?');\">";
                                echo "<input type='hidden' name='recipient_id' value='" . $Row['recipient_id'] . "'>";
                                echo "<button type='submit' class='table-delete-btn'>Delete</button>";
                                echo "</form>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="editModal" class="modal-overlay">
        <div class="modal-window">
            <button type="button" class="modal-close-btn">&times;</button>
            <h3 class="modal-title">Edit / View Recipient</h3>
            <p class="placeholder-text">Edit coming soon...</p>
        </div>
    </div>

    <script>
        //open modal on click based on the thing clicked
        document.querySelectorAll('.action-btn-trigger').forEach(button => {
            button.addEventListener('click', () => {
                //close any open
                document.querySelectorAll('.modal-overlay').forEach(modal => {
                modal.style.display = 'none';
            });

            //open new one
            const targetId = button.getAttribute('data-target');
            document.getElementById(targetId).style.display = 'block';
            });
        });

        //close  when clicking the close button
        document.querySelectorAll('.modal-close-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.target.closest('.modal-overlay').style.display = 'none';
            });
        });

        //close  when clicking outside the main window box
        window.addEventListener('click', (e) => {
            if (e.target.classList.contains('modal-overlay')) {
                e.target.style.display = 'none';
            }
        });
    </script>

</body>
</html>