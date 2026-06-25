<?php 
session_start();

//check to make sure this is a volunteer
if(!isset($_SESSION['volunteer_id'])){
    header("Location: ../index.php");
    exit();
}
//conect to server
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once 'db_connect.php';

    //get all the data of the recipient coming in
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $age = trim($_POST['age']);
    $guardian_fname= trim($_POST['guardian_fname']);
    $guardian_lname = trim($_POST['guardian_lname']);
    $contact = trim($_POST['contact']);
    $allergies = trim($_POST['allergies']);

    //make sure everything is filled out right (dont necicarrilly need guardian)
    if (!empty($first_name) && !empty($last_name) && $age !== null && !empty($contact)) {
        //go ahead and try to nsert everything
        try {
            $sqlString= "insert into recipients (first_name, last_name, age, guardian_fname, guardian_lname, contact, allergies) 
                values (:first_name, :last_name, :age, :guardian_fname, :guardian_lname, :contact, :allergies)";
            
            //execute statement
            $stmt = $pdo->prepare($sqlString);
            $stmt->execute([
                'first_name' => $first_name,
                'last_name' => $last_name,
                'age' => $age,
                'guardian_fname' => $guardian_fname,
                'guardian_lname' => $guardian_lname,
                'contact' => $contact,
                'allergies' => $allergies
            ]);

            //all done go home
            header("Location: ../pages/manage_recipients.php?success=1");
            exit();

        }catch(PDOException $e) {//if something fails
            die("Error: Could not save details.");
        }
    }else {//if they missed something
        die("Error: Required fields (Names, Age, or Contact) are missing.");
    }

}else {//send back to manage page 
    header("Location: ../pages/manage_recipients.php");
    exit();
}

?>