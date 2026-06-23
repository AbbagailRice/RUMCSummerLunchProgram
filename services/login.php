<?php
    //start the session
    session_start();

    //connect to the database
    require_once 'db_connect.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        //get the username and password from the form 
        $username = $_POST['username'];
        $password = $_POST['password'];

        //make sure the username and password are not empty
        if (!empty($username) && !empty($password)) {
            //get the user from the database (including fname, username, password, and is_approved) and limit to 1 result
            $stmt = $pdo->prepare("SELECT volunteer_id, first_name, username, password, 
                is_approved, is_admin FROM volunteers WHERE username = :username LIMIT 1");
            $stmt->execute(['username' => $username]); //run query using the provided parameter, do this way to prevent SQL injection
            $user = $stmt->fetch(); //get res as array

            //check if the user exists and the password is correct
            if ($user && password_verify($password, $user['password'])) {

                //check if the user is approved
                if ($user['is_approved'] == 1) {
                    //set session var
                    $_SESSION['volunteer_id'] = $user['volunteer_id'];
                    $_SESSION['first_name'] = $user['first_name'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['is_admin']     = $user['is_admin'];

                    if ($_SESSION['is_admin'] == 1) {
                        // check if admin
                        header("Location: ../pages/admin_dashboard.php");
                    } else {
                        // normal vonunteer goes to normal dash
                        header("Location: ../pages/dashboard.php");
                    }
                    exit(); // exit after redirect

                } else {
                    //user is not approved, show error message
                    echo "Your account is not approved yet. Please wait for approval.";
                }
            } else {
                //user does not exist or password is incorrect, show error message
                echo "Invalid username or password.";

            }

        }
    }

?>