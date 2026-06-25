<?php
session_start();

if (!isset($_SESSION['volunteer_id'])) {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once 'db_connect.php';

    // colletion
    $recipient_id= isset($_POST['recipient_id']) ? (int)$_POST['recipient_id'] : 0;
    $first_name= trim($_POST['first_name'] ?? '');
    $last_name= trim($_POST['last_name'] ?? '');
    $age= isset($_POST['age']) ? (int)$_POST['age'] : 0;
    $guardian_fname= trim($_POST['guardian_fname'] ?? '');
    $guardian_lname= trim($_POST['guardian_lname'] ?? '');
    $contact= trim($_POST['contact'] ?? '');
    $allergies= trim($_POST['allergies'] ?? '');

    //validate make sure nothing req is being made empty
    if ($recipient_id > 0 && !empty($first_name) && !empty($last_name) && $age > 0 && !empty($contact)) {
        try {
            $sql = "update recipients set 
                        first_name = :first_name, 
                        last_name = :last_name, 
                        age = :age, 
                        guardian_fname = :guardian_fname, 
                        guardian_lname = :guardian_lname, 
                        contact= :contact, 
                        allergies = :allergies 
                    where recipient_id = :recipient_id";
            //execute the satement
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'first_name'=> $first_name,
                'last_name' => $last_name,
                'age' => $age,
                'guardian_fname' => $guardian_fname,
                'guardian_lname' => $guardian_lname,
                'contact' => $contact,
                'allergies' => $allergies,
                'recipient_id'=> $recipient_id
            ]);

            // complete
            header("Location: ../pages/manage_recipients.php?success=update");
            exit();

        } catch (PDOException $e) {//error to change
            die("Error: Failed to save recipient changes.");
        }
    } else {//misisng fields if somthing is being made empty
        die("Error: Required information fields are missing.");
    }
} else { 
    header("Location: ../pages/manage_recipients.php");
    exit();
}
?>