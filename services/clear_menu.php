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

    if (empty($week_start)) {
        die("Error: No menu week was selected.");
    }

    // get friday
    $week_end = date(
        'Y-m-d',
        strtotime($week_start . ' +4 days')
    );

    try {
        // delete curr week
        $stmt = $pdo->prepare("
            delete from menu
            where menu_date between :week_start and :week_end
        ");

        $stmt->execute([
            'week_start' => $week_start,
            'week_end' => $week_end
        ]);

        // ret to menu
        header("Location: ../pages/menu.php?cleared=1");
        exit();

    } catch (PDOException $e) {
        die("Error: Could not clear menu.");
    }

} else {
    // ret if accessed directly
    header("Location: ../pages/menu.php");
    exit();
}
?>