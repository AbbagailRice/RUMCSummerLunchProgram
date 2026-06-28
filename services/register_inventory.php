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
    $item_name = trim($_POST['item_name']);
    $quantity = trim($_POST['quantity']);
    $expire_date = trim($_POST['expire_date']);
    
    // if no check box default to 0
    $shelf_stable = isset($_POST['shelf_stable']) ? 1 : 0;

    // if no date default to null
    if ($expire_date === '') {
        $expire_date = null;
    }

    // make sure req fields arent empty
    if (!empty($item_name) && $quantity !== '') {
        
        //go ahead and try to nsert everything
        try {
            $sqlString = "insert into inventory (item_name, quantity, expire_date, shelf_stable) 
                values (:item_name, :quantity, :expire_date, :shelf_stable)";
            
            // execute
            $stmt = $pdo->prepare($sqlString);
            $stmt->execute([
                'item_name'    => $item_name,
                'quantity'     => $quantity,
                'expire_date'  => $expire_date,
                'shelf_stable' => $shelf_stable
            ]);

            // done go back
            header("Location: ../pages/manage_inventory.php?success=1");
            exit();

        } catch (PDOException $e) { // if something fails
            die("Error: Could not save stock details.");
        }
    } else { // if they missed something
        die("Error: Required fields (Item Name or Quantity) are missing.");
    }

} else { // if accessed directly
    header("Location: ../pages/manage_inventory.php");
    exit();
}
?>