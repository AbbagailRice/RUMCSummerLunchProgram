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
    $stmt = $pdo->prepare("select recipient_id, first_name, last_name, age, guardian_fname, guardian_lname from recipients order by last_name ASC, first_name ASC");
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
            top: 0; 
            left: 0; 
            width: 100%; 
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9999;
        }

        .modal-window {
            background: #fff;
            margin: 15% auto;
            padding: 20px;
            width: 450px;
            position: relative;
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
                <div class="form-group">
                    <label class="form-label">First Name:</label>
                    <input type="text" name="first_name" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Last Name:</label>
                    <input type="text" name="last_name" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Age:</label>
                    <input type="number" name="age" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Guardian First Name:</label>
                    <input type="text" name="guardian_fname" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Guardian Last Name:</label>
                    <input type="text" name="guardian_lname" class="form-input" required>
                </div>
                <button type="submit" class="form-submit-btn">Submit Registration</button>
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
                        if ($stmt) {
                            while ($Row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<tr class='table-row'>";
                                echo "<td class='cell-fname'>" . htmlspecialchars($Row['first_name']) . "</td>";
                                echo "<td class='cell-lname'>" . htmlspecialchars($Row['last_name']) . "</td>";
                                echo "<td class='cell-age'>" . htmlspecialchars($Row['age']) . "</td>";
                                echo "<td class='cell-guardian'>" . htmlspecialchars($Row['guardian_fname'] . " " . $Row['guardian_lname']) . "</td>";
                                echo "<td class='cell-action'>";
                                echo "<form action='../services/delete.php' method='POST' class='table-delete-form' onsubmit=\"return confirm('Are you sure you want to permanently delete this recipient?');\">";
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