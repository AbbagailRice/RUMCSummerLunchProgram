<?php
    //start the session
    session_start();

    //make sure the session var is set.
    //if it isnt redirect back to the index(start) page
    if (!isset($_SESSION['volunteer_id'])) {
        header("Location: ../index.php");
        exit();
    }
    require_once '../services/db_connect.php';

    try {
        // prev day total
        $query_yesterday = "select count(*) from attendance where date(attendance_date) = date_sub(curdate(), interval 1 day)";
        $stmt_yesterday = $pdo->prepare($query_yesterday);
        $stmt_yesterday->execute();
        $total_yesterday = $stmt_yesterday->fetchColumn();

        // current week total
        $query_curr_week = "select count(*) from attendance where yearweek(attendance_date, 1) = yearweek(curdate(), 1)";
        $stmt_curr_week = $pdo->prepare($query_curr_week);
        $stmt_curr_week->execute();
        $total_curr_week = $stmt_curr_week->fetchColumn();

        // past week total
        $query_past_week = "select count(*) from attendance where yearweek(attendance_date, 1) = yearweek(curdate(), 1) - 1";
        $stmt_past_week = $pdo->prepare($query_past_week);
        $stmt_past_week->execute();
        $total_past_week = $stmt_past_week->fetchColumn();

    } catch (PDOException $e) {
        echo "<p>Could not get data.</p>";
    }
?>

<DOCTYPE HTML>
<html>

<head>
    <title> Summer Lunch Program Dashboard </title>
    <link rel="stylesheet" type="text/css" href="../CSS/styles.css">
    <link rel="stylesheet" type="text/css" href="../CSS/dashboardStyle.css">
</head>

<body>
    <div class="layout">
        <?php include '../includes/header.php'; ?>
        <div class="main-content">

            <div class ="workspace-container">
                <div class ="dashboard-workspace">
                    <h2>Welcome to the Dashboard! It is <span class="dashboard-date"><?php echo date('l, F j'); ?>.</span></h2>
                    <div class="metrics-grid">
                        <div class="metric-card">
                            <h3>Previous Day</h3>
                            <div class="metric-number"><?php echo htmlspecialchars($total_yesterday); ?></div>
                            <p>Lunches Served</p>
                        </div>

                        <div class="metric-card">
                            <h3>Current Week</h3>
                            <div class="metric-number"><?php echo htmlspecialchars($total_curr_week); ?></div>
                            <p>Lunches Served</p>
                        </div>

                        <div class="metric-card">
                            <h3>Past Week</h3>
                            <div class="metric-number"><?php echo htmlspecialchars($total_past_week); ?></div>
                            <p>Lunches Served</p>
                        </div>
                    </div>

                    <a href="../services/logout.php" class="btn-logout">
                        Logout
                    </a>
                </div>
            </div>
        </div>

        <?php include '../includes/sidebar.php'; ?>

    </div>
</body>

</html>