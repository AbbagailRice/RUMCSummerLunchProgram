<?php
session_start();

//check to make sure this is a volunteer
if(!isset($_SESSION['volunteer_id'])){
    header("Location: ../index.php");
    exit();
}

require_once '../services/db_connect.php';
$menu_data = [];
$week_start = null;

//if a generated week is here
if (isset($_GET['week_start'])) {

    $week_start = $_GET['week_start'];

    // get friday
    $week_end = date(
        'Y-m-d',
        strtotime($week_start . ' +4 days')
    );

    try {
        // this very longpart is
        // getting our menu and ingridients 
        // but also checking if an item is suggested or not.
        $stmt = $pdo->prepare("
            select
                m.menu_date,
                mi.menu_item_id,
                mi.category,
                mi.menu_item_name,
                max(
                    case
                        when ming.item_id is null then 1
                        else 0
                    end
                ) as is_suggested
            from menu m
            join menu_items mi
                on m.menu_id = mi.menu_id
            left join menu_ingredients ming
                on mi.menu_item_id = ming.menu_item_id
            where m.menu_date between :week_start and :week_end
            group by
                m.menu_date,
                mi.menu_item_id,
                mi.category,
                mi.menu_item_name
            order by m.menu_date asc
        ");

        $stmt->execute([
            'week_start' => $week_start,
            'week_end' => $week_end
        ]);

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $display_name = $row['menu_item_name'];

            if ($row['is_suggested']) {
                $display_name .= ' *';
            }

            $menu_data[$row['menu_date']][$row['category']]
                = $display_name;
        }

    } catch (PDOException $e) {
        $error_msg = "Could not load generated menu.";
    }
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Menu Generator</title>

    <link rel="stylesheet" type="text/css" href="../CSS/styles.css">
    <link rel="stylesheet" type="text/css" href="../CSS/table.css">
</head>

<body>

<div class="layout">

    <main class="main-content">

        <?php include '../includes/header.php'; ?>

        <div class="workspace-container">

            <div class="manage-workspace">

                <h3>Generate Weekly Menu</h3>

                <div class="menu-actions-grid">
                    <form action="../services/generate_menu.php" method="post">
                        <div class="form-options">
                            <label for="estimated_recipients">Estimated Recipients *</label><br>
                            <input
                                type="number"
                                name="estimated_recipients"
                                id="estimated_recipients"
                                min="1"
                                required
                            >
                        </div>
                        <br>
                        <button type="submit">
                            <div class="action-item-box">
                                <span class="action-label">Generate Menu</span>
                            </div>
                        </button>
                    </form>

                    <button type="button" id="clearMenuBtn">
                        <div class="action-item-box">
                            <span class="action-label">Clear</span>
                        </div>
                    </button>

                </div>

                <div class="menu-table-container">
                    <table class="recipient-table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Monday</th>
                                <th>Tuesday</th>
                                <th>Wednesday</th>
                                <th>Thursday</th>
                                <th>Friday</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <th>Sandwich</th>
                                <?php
                                if ($week_start !== null) {
                                    for ($i = 0; $i < 5; $i++) {
                                        $date = date(
                                            'Y-m-d',
                                            strtotime($week_start . " +{$i} days")
                                        );

                                        echo "<td>" .
                                            htmlspecialchars(
                                                $menu_data[$date]['sandwich'] ?? ''
                                            ) .
                                            "</td>";
                                    }
                                } else {
                                    // empty cells before a menu has been generated
                                    for ($i = 0; $i < 5; $i++) {
                                        echo "<td></td>";
                                    }
                                }
                                ?>
                            </tr>

                           <tr>
                                <th>Sweet</th>
                                <?php
                                if ($week_start !== null) {
                                    for ($i = 0; $i < 5; $i++) {
                                        $date = date(
                                            'Y-m-d',
                                            strtotime($week_start . " +{$i} days")
                                        );

                                        echo "<td>" .
                                            htmlspecialchars(
                                                $menu_data[$date]['sweet'] ?? ''
                                            ) .
                                            "</td>";
                                    }
                                } else {
                                    // empty cells before a menu has been generated
                                    for ($i = 0; $i < 5; $i++) {
                                        echo "<td></td>";
                                    }
                                }
                                ?>
                            </tr>

                            <tr>
                                <th>Salty</th>
                                <?php
                                if ($week_start !== null) {
                                    for ($i = 0; $i < 5; $i++) {
                                        $date = date(
                                            'Y-m-d',
                                            strtotime($week_start . " +{$i} days")
                                        );

                                        echo "<td>" .
                                            htmlspecialchars(
                                                $menu_data[$date]['salty'] ?? ''
                                            ) .
                                            "</td>";
                                    }
                                } else {
                                    // empty cells before a menu has been generated
                                    for ($i = 0; $i < 5; $i++) {
                                        echo "<td></td>";
                                    }
                                }
                                ?>
                            </tr>

                            <tr>
                                <th>Fruit</th>

                                <?php
                                if ($week_start !== null) {
                                    for ($i = 0; $i < 5; $i++) {
                                        $date = date(
                                            'Y-m-d',
                                            strtotime($week_start . " +{$i} days")
                                        );

                                        echo "<td>" .
                                            htmlspecialchars(
                                                $menu_data[$date]['fruit'] ?? ''
                                            ) .
                                            "</td>";
                                    }
                                } else {
                                    // empty cells before a menu has been generated
                                    for ($i = 0; $i < 5; $i++) {
                                        echo "<td></td>";
                                    }
                                }
                                ?>
                            </tr>

                            <tr>
                                <th>Vegetable</th>
                                <?php
                                if ($week_start !== null) {
                                    for ($i = 0; $i < 5; $i++) {
                                        $date = date(
                                            'Y-m-d',
                                            strtotime($week_start . " +{$i} days")
                                        );

                                        echo "<td>" .
                                            htmlspecialchars(
                                                $menu_data[$date]['vegetable'] ?? ''
                                            ) .
                                            "</td>";
                                    }
                                } else {
                                    // empty cells before a menu has been generated
                                    for ($i = 0; $i < 5; $i++) {
                                        echo "<td></td>";
                                    }
                                }
                                ?>
                            </tr>

                            <tr>
                                <th>Dairy</th>
                                <?php
                                if ($week_start !== null) {
                                    for ($i = 0; $i < 5; $i++) {
                                        $date = date(
                                            'Y-m-d',
                                            strtotime($week_start . " +{$i} days")
                                        );

                                        echo "<td>" .
                                            htmlspecialchars(
                                                $menu_data[$date]['dairy'] ?? ''
                                            ) .
                                            "</td>";
                                    }
                                } else {
                                    // empty cells before a menu has been generated
                                    for ($i = 0; $i < 5; $i++) {
                                        echo "<td></td>";
                                    }
                                }
                                ?>
                            </tr>

                            <tr>
                                <th>Drink</th>
                                <?php
                                if ($week_start !== null) {
                                    for ($i = 0; $i < 5; $i++) {
                                        $date = date(
                                            'Y-m-d',
                                            strtotime($week_start . " +{$i} days")
                                        );

                                        echo "<td>" .
                                            htmlspecialchars(
                                                $menu_data[$date]['drink'] ?? ''
                                            ) .
                                            "</td>";
                                    }
                                } else {
                                    // empty cells before a menu has been generated
                                    for ($i = 0; $i < 5; $i++) {
                                        echo "<td></td>";
                                    }
                                }
                                ?>
                            </tr>
                        </tbody>
                    </table>
                    <p>* Suggested item not currently in inventory</p>
                </div>
            </div>
        </div>
    </main>

    <?php include '../includes/sidebar.php'; ?>

</div>
</body>
</html>