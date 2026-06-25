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
    <title>Remove Recipient</title>
</head>
<body>


    <div class="layout">
        <main class="main-content">
            <?php include '../includes/header.php'; ?>

            <div class="workspace-container">
                
                <!-- remove spot -->
                <div class="recipient-delete">
                    <h3>Remove Recipient</h3>

                    <?php 
                        //if any errors show what it is
                        if (isset($error_msg)){
                            echo "<p>" . $error_msg . "</p>";
                        }else if (empty($recipients)){
                            //checks if there are any in the system.
                            echo "<p>No recipients registered yet.</p>";
                        }
                     ?>
                    <!--table showing everyone-->
                    <table border="1">
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
                                // everything in php so tired of switching.
                                while ($Row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<tr>";
                                    echo "<td>".htmlspecialchars($Row['first_name']) . "</td>";
                                    echo "<td>".htmlspecialchars($Row['last_name']) . "</td>";
                                    echo "<td>".htmlspecialchars($Row['age']) . "</td>";
                                    echo "<td>".htmlspecialchars($Row['guardian_fname'] . " " . $Row['guardian_lname']) . "</td>";
                                    echo "<td>";
                                    echo "<form action='../services/delete.php' method='POST' onsubmit=\"return confirm('Are you sure?');\">";
                                    echo "<input type='hidden' name='recipient_id' value='" . $Row['recipient_id'] . "'>";
                                    echo "<button type='submit'>Delete</button>";
                                    echo "</form>";
                                    echo "</td>";
                                    echo "</tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                                
                </div>

            </div>
        </main>

        <?php include '../includes/sidebar.php'; ?>

    </div>

</body>
</html>
