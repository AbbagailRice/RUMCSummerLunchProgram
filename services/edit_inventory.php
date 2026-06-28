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

    // get all the data of the inventory item coming in
    $item_id = isset($_POST['item_id']) ? (int)$_POST['item_id'] : 0;
    $item_name = trim($_POST['item_name'] ?? '');
    $quantity = isset($_POST['quantity']) ? trim($_POST['quantity']) : '';
    $expire_date = trim($_POST['expire_date'] ?? '');

    // if no check box default to 0
    $shelf_stable = isset($_POST['shelf_stable']) ? 1 : 0;

    // if no date default to null
    if ($expire_date === '') {
        $expire_date = null;
    }

    // make sure req fields arent empty
    if ($item_id > 0 && !empty($item_name) && $quantity !== '') {
        try {
            $sql = "update inventory set item_name = :item_name, quantity = :quantity, 
                        expire_date = :expire_date, shelf_stable = :shelf_stable where item_id = :item_id";
                    
            // execute the statement securely
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'item_name' => $item_name,
                'quantity'=> $quantity,
                'expire_date'=> $expire_date,
                'shelf_stable'=> $shelf_stable,
                'item_id'=> $item_id
            ]);

            // done go back
            header("Location: ../pages/manage_inventory.php?success=update");
            exit();

        } catch (PDOException $e) {
            die("Error: Failed to save inventory changes.");
        }
    } else {
        die("Error: Required fields are missing.");
    }
} else { 
    header("Location: ../pages/manage_inventory.php");
    exit();
}
?>