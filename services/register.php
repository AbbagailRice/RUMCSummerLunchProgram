<?php
    require_once 'db_connect.php';

    if ($_SERVER['REQUEST_METHOD']=='POST'){
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
                $check_stmt = $pdo->prepare("select volunteer_id from volunteers where username = :username limit 1");
                $check_stmt->execute(['username' => $username]);
                
                if ($check_stmt->fetch()) {
                    echo "Error: The username '" . htmlspecialchars($username) . "' is in use. Please try another one.";
                    exit();
                }
                //hash the password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                //insert everthing
                $insert_sql = "insert into volunteers (first_name, last_name, contact, username, password, has_keys, is_approved, is_admin) 
                    values (:first_name, :last_name, :contact, :username, :password, :has_keys, :is_approved, :is_admin)";
            
                $insert_stmt = $pdo->prepare($insert_sql);
                $insert_stmt->execute([
                    'first_name' => $first_name,
                    'last_name'=> $last_name,
                    'contact' => $contact,
                    'username' => $username,
                    'password'=> $hashed_password, 
                    'has_keys'=> $has_keys,
                    'is_approved'=> 0, //false till admin aproves.
                    'is_admin'=> 0

                ]);// inline style for completion it is what it is (WANT TO CHANGE MORE)
                echo "<div style='font-family: Arial, sans-serif; max-width: 500px; margin: 60px auto; padding: 25px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); text-align: center; border-top: 5px solid #e50027;'>";
                    echo "<p style='color: #5d6769; font-size: 1rem; line-height: 1.5; margin-bottom: 20px; font-weight: 500;'>Account request submitted. Please wait for an administrator to verify your account.</p>";
                    echo "<a href='../index.php' style='display: inline-block; color: #e50027; text-decoration: none; font-weight: bold; font-size: 0.95rem; padding: 8px 16px; border: 1px solid #e50027; border-radius: 4px; transition: all 0.2s;'>Return to Login Screen</a>";
                echo "</div>";

            } catch (PDOException $e) {
                // if there is an issue.
                echo "An error occurred while processing your registration request.";
            }
        } 

    }
    

?>