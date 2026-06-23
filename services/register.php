<?php
    require_once 'db_connect.php';

    if ($_SERVER ['REQUEST _METHOD']=='POST'){
        //grab andsanitize the param
        $first_name = trim($_POST['first_name']);
        $last_name = trim($_POST['last_name']);
        $contact = trim($_POST['contact']);
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        $has_keys = isset($_POST['has_keys']) ? 1 : 0; //if there is no check then it defaults to 0 (for no)

        //make sure nothing is empty
        if (!empty($first_name) && !empty($last_name) && !empty($contact) && !empty($username) && !empty($password)) {

            try{

                //check for duplicates then send back if there is.
                $check_stmt = $pdo->prepare("SELECT volunteer_id FROM volunteers WHERE username = :username LIMIT 1");
                $check_stmt->execute(['username' => $username]);
                
                if ($check_stmt->fetch()) {
                    echo "Error: The username '" . htmlspecialchars($username) . "' is in use. Please try another one.";
                    exit();
                }
                //hash the password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                //insert everthing
                $insert_sql = "INSERT INTO volunteers (first_name, last_name, contact, username, password, has_keys, is_approved) 
                           VALUES (:first_name, :last_name, :contact, :username, :password, :has_keys, :is_approved, :is_admin)";
            
                $insert_stmt = $pdo->prepare($insert_sql);
                $insert_stmt->execute([
                    'first_name'  => $first_name,
                    'last_name'   => $last_name,
                    'contact'     => $contact,
                    'username'    => $username,
                    'password'    => $hashed_password, 
                    'has_keys'    => $has_keys,
                    'is_approved' => 0, //false till admin aproves.
                    'is_admin'    => 0

                ]);
            } catch (PDOException $e) {
                // if there is an issue.
                echo "An error occurred while processing your registration request.";
            }
        } 

    }
    

?>