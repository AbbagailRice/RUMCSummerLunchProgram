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

        $stmt = $pdo->prepare("
            select
                m.menu_date,
                mi.category,
                mi.menu_item_name
            from menu m
            join menu_items mi
                on m.menu_id = mi.menu_id
            where m.menu_date between :week_start and :week_end
            order by m.menu_date asc
        ");

        $stmt->execute([
            'week_start' => $week_start,
            'week_end' => $week_end
        ]);

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $menu_data[$row['menu_date']][$row['category']]
                = $row['menu_item_name'];
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
                                ?>
                            </tr>

                           <tr>
                                <th>Sweet</th>
                                <?php
                                if ($week_start !== null) {
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
                                ?>
                            </tr>

                            <tr>
                                <th>Salty</th>
                                <?php
                                if ($week_start !== null) {
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
                                ?>
                            </tr>

                            <tr>
                                <th>Fruit</th>

                                <?php
                                if ($week_start !== null) {
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
                                ?>
                            </tr>

                            <tr>
                                <th>Vegetable</th>
                                <?php
                                if ($week_start !== null) {
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
                                ?>
                            </tr>

                            <tr>
                                <th>Dairy</th>
                                <?php
                                if ($week_start !== null) {
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
                                ?>
                            </tr>

                            <tr>
                                <th>Drink</th>
                                <?php
                                if ($week_start !== null) {
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
                                ?>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <?php include '../includes/sidebar.php'; ?>

</div>
</body>
</html>