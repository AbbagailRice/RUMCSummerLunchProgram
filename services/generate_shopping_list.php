<?php
session_start();

if (!isset($_SESSION['volunteer_id'])) {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once 'db_connect.php';

    // get week
    $week_start = $_POST['week_start'] ?? '';

    // make sure menu exists
    if (empty($week_start)) {
        die("Error: Please generate a menu before creating a shopping list.");
    }
    // temp test
    echo "Shopping list can be generated.";
    exit();

} else {

    // return if accessed directly
    header("Location: ../pages/menu.php");
    exit();
}
?>