<?php
    session_start();

    // i f they are not logged in, OR if they are not an admin, send away.
    if (!isset($_SESSION['volunteer_id']) || $_SESSION['is_admin'] != 1) {
        header("Location: ../index.php");
        exit();
    }
    require_once '../services/db_connect.php'; //log into db

    //grab all volunteers. aproved and not approved
    try{
        // get the non active ones (is_approved = 0)
        $stmt_pending = $pdo->prepare("SELECT volunteer_id, first_name, last_name, contact, username, has_keys FROM volunteers WHERE is_approved = 0 AND is_admin = 0");
        $stmt_pending->execute();
        $pending_users = $stmt_pending->fetchAll();

        // get the active ones (is_approved = 1)
        $stmt_approved = $pdo->prepare("SELECT volunteer_id, first_name, last_name, contact, username, has_keys FROM volunteers WHERE is_approved = 1 AND is_admin = 0");
        $stmt_approved->execute();
        $approved_users = $stmt_approved->fetchAll();
    } catch (PDOException $e) { //if for some reason records dont load.
        $pending_users = [];
        $approved_users = [];
        $error_msg = "Could not load volunteer records.";
    }


?>


<!DOCTYPE HTML>
<html>
<head>
    <title>SLP Admin Dashboard</title>
</head>
<body>
    <div class="background-image">
        <!-- <img src="photos/logo.png" alt="background"> -->
    </div>

    <div class="logo-header">
        <div class="logo">
            <!-- <img src="photos/logo.png" alt="Logo"> -->
        </div>
        <h1>Summer Lunch <br> Program</h1>
    </div>
    
    <div class="admin-container">
        <h2> ADMIN</h2>
        
        <div class="pending-container">
            <h3> Pending Volunteer Account Approvals</h3>

            //check if there are none
            <?php if(empty($pending_users)): ?>
                <p> No pending volunteers.<p>
            
            //If there are.
            <?php else: ?>
                <table border="1">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Contact</th>
                            <th>Has Church Keys?</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        // loop though each pending user and process them out.
                        <?php foreach ($pending_users as $user): ?>
                            <tr>
                                //cleans and echos out the info
                                <td><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($user['username']); ?></td>
                                <td><?php echo htmlspecialchars($user['contact']); ?></td>
                                <td><?php echo $user['has_keys'] == 1 ? 'Yes' : 'No'; ?></td> //looks at bool
                                <td>
                                    //active form for adding a new user only sends the id back to db
                                    <form action="../services/approve_user.php" method="POST">
                                        <input type="hidden" name="volunteer_id" value="<?php echo $user['volunteer_id']; ?>">
                                        <button type="submit">Approve User</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>


        <br>
        <a href="../services/logout.php">Logout</a>
    </div>

</body>
</html>