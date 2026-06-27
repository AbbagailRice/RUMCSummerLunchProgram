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
            top: 50%;
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
    <link rel="stylesheet" type="text/css" href="../CSS/styles.css">
</head>
<body>
<div class="layout">
    <?php include '../includes/header.php'; ?>
    <main class="main-content">

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
                
            </div>
            
            <div class="search-container">
                    <label for="tableSearch">Search Recipients: </label>
                    <input type="text" id="tableSearch" placeholder="Type a name, phone, or allergy..." onkeyup="filterTable()" style="padding: 6px; width: 250px; margin-bottom: 15px;">
            </div>

            <div class ="recipent-table-container">
                <?php 
                    //if error with this table show why
                    if (isset($error_msg)) {
                        echo "<p class='error-message'>" . $error_msg . "</p>";
                    }
                ?>

                <table class="recipient-table" border="1" id="recipientTable">
                    <thead>
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Age</th>
                            <th>Allergies</th>
                            <th>Primary Guardian</th>
                            <th>Contact Info</th> 
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $recipientRows = [];
                            if ($stmt) {
                                while ($Row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    // save for delete modal
                                    $recipientRows[] = $Row;

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
                                    echo "<td class='cell-contact'>" . htmlspecialchars($Row['contact'] ?? '') . "</td>";
                                    
                                    //edit button
                                    echo "<td class='cell-action'>";
                                    echo "<button type='button' onclick='openEditModal(" . json_encode($Row) . ")'>Edit</button>";
                                    echo "</td>";
                                    echo "</tr>";
                                }
                            }
                        ?>
                    </tbody>
                </table>

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
                        <th>Allergies</th>
                        <th>Primary Guardian</th>
                        <th>Contact Info</th> 
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        foreach ($recipientRows as $Row) {
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
                            //submit
                            echo "<button type='submit' class='table-delete-btn'>Delete</button>";
                            echo "</form>";
                            echo "</td>";
                            echo "</tr>";
                            }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="editModal" class="modal-overlay">
        <div class="modal-window">
            <button type="button" class="modal-close-btn">&times;</button>
            <h3 class="modal-title">Edit Recipient Information</h3>
            
            <form action="../services/edit_recipients.php" method="POST" class="modal-form">
                <input type="hidden" name="recipient_id" id="edit_id">

                <div class ="form-options">
                    <label>Recipient First Name *</label><br>
                    <input type="text" name="first_name" id="edit_fname" required>
                </div>
                <br>
                <div class ="form-options">
                    <label>Recipient Last Name *</label><br>
                    <input type="text" name="last_name" id="edit_lname" required>
                </div>
                <br>
                <div class ="form-options">
                    <label>Age *</label><br>
                    <input type="number" name="age" id="edit_age" required>
                </div>
                <br>
                <div class ="form-options">
                    <label>Guardian First Name (Optional)</label><br>
                    <input type="text" name="guardian_fname" id="edit_g_fname">
                </div>
                <br>
                <div class ="form-options">
                    <label>Guardian Last Name (Optional)</label><br>
                    <input type="text" name="guardian_lname" id="edit_g_lname">
                </div>
                <br>
                <div class ="form-options">
                    <label>Contact Phone *</label><br>
                    <input type="text" name="contact" id="edit_contact" required>
                </div>
                <br>
                <div class ="form-options">
                    <label>Allergies </label><br>
                    <textarea name="allergies" id="edit_allergies" rows="3" cols="30"></textarea>
                </div>
                <br>
                <button type="submit">Save Changes</button>
            </form>
        </div>
    </div>

    <script>
        //table filter
        function filterTable() {
            const input = document.getElementById('tableSearch');
            const filter = input.value.toLowerCase();
            const table = document.getElementById('recipientTable');
            const rows = table.getElementsByClassName('table-row');

            for (let i = 0; i < rows.length; i++) {
                // Gget text content
                const rowText = rows[i].textContent || rows[i].innerText;
                
                // check if match otherwise hide
                if (rowText.toLowerCase().indexOf(filter) > -1) {
                    rows[i].style.display = "";
                } else {
                    rows[i].style.display = "none";
                }
            }
        }
    </script>

    <!--MODAL script-->
    <script>
        //edit modal part: to inject data based on what the user clicks on-
        //open and inject
        function openEditModal(recipientData) {
            //force close others
            document.querySelectorAll('.modal-overlay').forEach(modal => {
                modal.style.display = 'none';
            });

            //fill fields
            document.getElementById('edit_id').value = recipientData.recipient_id;
            document.getElementById('edit_fname').value = recipientData.first_name;
            document.getElementById('edit_lname').value= recipientData.last_name;
            document.getElementById('edit_age').value = recipientData.age;
            document.getElementById('edit_g_fname').value = recipientData.guardian_fname ?? '';
            document.getElementById('edit_g_lname').value = recipientData.guardian_lname ?? '';
            document.getElementById('edit_contact').value = recipientData.contact ?? '';
            document.getElementById('edit_allergies').value = recipientData.allergies ?? '';

            //redering
            document.getElementById('editModal').style.display = 'block';
        }

        //general modal thigns. what to do when clicking where stuff
        document.querySelectorAll('.action-btn-trigger').forEach(button => {
            button.addEventListener('click', () => {
                document.querySelectorAll('.modal-overlay').forEach(modal => {
                    modal.style.display = 'none';
                    const form = modal.querySelector('form');
                    if (form) { form.reset(); }
                });

                const targetId = button.getAttribute('data-target');
                document.getElementById(targetId).style.display = 'block';
            });
        });

        document.querySelectorAll('.modal-close-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const modal = e.target.closest('.modal-overlay');
                modal.style.display = 'none';
                const form = modal.querySelector('form');
                if (form) { form.reset(); }
            });
        });

        window.addEventListener('click', (e) => {
            if (e.target.classList.contains('modal-overlay')) {
                e.target.style.display = 'none';
                const form = e.target.querySelector('form');
                if (form) { form.reset(); }
            }
        });
    </script>

</body>
</html>