<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once 'db_connect.php';

    try {
        $stmt = $pdo->prepare("select item_id, item_name, quantity, expire_date, shelf_stable, category
            from inventory
            where quantity > 0
            order by expire_date is null, expire_date asc, item_name asc 
        ");//shelf stable stuff to be used last thats why order by expire is null

        $stmt->execute();

        $inventory = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // temporary test
            echo "<pre>";
            print_r($inventory);
            echo "</pre>";

        } catch (PDOException $e) {
            die("Error: Could not load inventory data.");
        }

    } else {
        header("Location: ../pages/menu.php");
        exit();
    }
?>