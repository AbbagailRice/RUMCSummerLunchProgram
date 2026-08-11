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
    //echo "Shopping list can be generated.";
    //exit();
    // get friday
    $week_end = date(
        'Y-m-d',
        strtotime($week_start . ' +4 days')
    );

    try {

        // get suggested ingredients for the week or item id == null
        $stmt = $pdo->prepare("
            select
                ming.ingredient_name,
                sum(ming.req_quant) as needed_quant,
                ming.unit
            from menu m
            join menu_items mi
                on m.menu_id = mi.menu_id
            join menu_ingredients ming
                on mi.menu_item_id = ming.menu_item_id
            where m.menu_date between :week_start and :week_end
            and ming.item_id is null
            group by
                ming.ingredient_name,
                ming.unit
            order by ming.ingredient_name
        ");

        $stmt->execute([
            'week_start' => $week_start,
            'week_end' => $week_end
        ]);

        $shopping_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // temp test
        echo "<pre>";
        print_r($shopping_items);
        echo "</pre>";
        exit();

    } catch (PDOException $e) {
        die("Error: Could not create shopping list.");
    }

} else {

    // return if accessed directly
    header("Location: ../pages/menu.php");
    exit();
}
?>