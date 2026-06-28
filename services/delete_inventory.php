<?php 
session_start();

//check to make sure this is a volunteer
if(!isset($_SESSION['volunteer_id'])){
    header("Location: ../index.php");
    exit();
}
//conect to server
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once 'db_connect.php';

    // grab data
    $item_id = isset($_POST['item_id']) ? (int)$_POST['item_id'] : 0;

    if ($item_id > 0) {
        try {
            // delete
            $sqlString = "delete from inventory where item_id = :item_id";
            $stmt = $pdo->prepare($sqlString);
            $stmt->execute(['item_id' => $item_id]);

            // check if it existed and was deleted
            if ($stmt->rowCount() > 0) {
                // done return
                header("Location: ../pages/inventory.php?success=delete");
                exit();
            } else {// not found
                die("Error: Record not found in the system database.");
            }

        } catch (PDOException $e) {
            die("Error: Failed to delete inventory item.");
        }
    } else {
        die("Error: Invalid item ID.");
    }
} else {
    header("Location: ../pages/inventory.php");
    exit();
}
?>


