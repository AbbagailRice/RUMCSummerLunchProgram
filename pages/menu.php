<?php
session_start();

//check to make sure this is a volunteer
if(!isset($_SESSION['volunteer_id'])){
    header("Location: ../index.php");
    exit();
}

require_once '../services/db_connect.php';
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
                    <table class="menu-table">
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
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>

                            <tr>
                                <th>Sweet</th>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>

                            <tr>
                                <th>Salty</th>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>

                            <tr>
                                <th>Fruit</th>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>

                            <tr>
                                <th>Vegetable</th>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>

                            <tr>
                                <th>Dairy</th>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>

                            <tr>
                                <th>Drink</th>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
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