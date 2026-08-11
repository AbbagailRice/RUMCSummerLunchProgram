<?php
session_start();

if (!isset($_SESSION['volunteer_id'])) {
    header("Location: ../index.php");
    exit();
}

require_once '../services/db_connect.php';

$shopping_list = [];
$week_start = $_GET['week_start'] ?? '';

// make sure a menu week exists
if (!empty($week_start)) {

    try {

        // get monday menu id
        $stmt = $pdo->prepare("
            select menu_id
            from menu
            where menu_date = :week_start
            limit 1
        ");

        $stmt->execute([
            'week_start' => $week_start
        ]);

        $menu_id = $stmt->fetchColumn();

        if ($menu_id) {

            // get shopping list consolidate items
            $stmt = $pdo->prepare("
                select
                    shopping_item_name,
                    sum(needed_quant) as needed_quant,
                    unit,
                    min(in_stock) as in_stock
                from shopping_list
                where menu_id = :menu_id
                group by
                    shopping_item_name,
                    unit
                order by shopping_item_name asc
            ");

            $stmt->execute([
                'menu_id' => $menu_id
            ]);

            $shopping_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

    } catch (PDOException $e) {
        $error_msg = "Could not load shopping list.";
    }
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Shopping List</title>

    <link rel="stylesheet" type="text/css" href="../CSS/styles.css">
    <link rel="stylesheet" type="text/css" href="../CSS/table.css">
</head>

<body>

<div class="layout">
    <main class="main-content">
        <?php include '../includes/header.php'; ?>
        <div class="workspace-container">
            <div class="manage-workspace">
                <h3>Weekly Shopping List</h3>

                <?php if (empty($week_start)): ?>
                    <p>Please generate a menu before viewing the shopping list.</p>
                <?php elseif (empty($shopping_list)): ?>
                    <p>No shopping items are needed for this menu.</p>
                <?php else: ?>

                    <div class="shopping-table-container">

                        <table class="recipient-table">

                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Needed</th>
                                    <th>Unit</th>
                                    <th>In Stock</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($shopping_list as $item): ?>
                                    <tr>
                                        <td>
                                            <?php echo htmlspecialchars($item['shopping_item_name']); ?>
                                        </td>

                                        <td>
                                            <?php echo htmlspecialchars($item['needed_quant']); ?>
                                        </td>

                                        <td>
                                            <?php echo htmlspecialchars($item['unit']); ?>
                                        </td>

                                        <td>
                                            <?php echo $item['in_stock'] ? 'Yes' : 'No'; ?>
                                        </td>
                                    </tr>

                                <?php endforeach; ?>

                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
    <?php include '../includes/sidebar.php'; ?>

</div>
</body>
</html>