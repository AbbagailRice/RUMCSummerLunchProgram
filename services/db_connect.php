<?php
//grab the credentials from render
$host = getenv('DB_HOST');
$port = getenv('DB_PORT');
$dbname = getenv('DB_NAME');
$username = getenv('DB_USER');
$password = getenv('DB_PASS');

//try to connect normally (if DB isalready on)
try{
    //dns string for the connection
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";

    //PDO security options
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, //throw exceptions on errors
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //fetch associative arrays 
        PDO::ATTR_EMULATE_PREPARES => false, //use real prepared statements this is for the sql injecttion security
    ];

    //create a new PDO instance
    $pdo = new PDO($dsn, $username, $password, $options);


}
catch (PDOException $e) {
    //if it off wake up db
    $api_token   = getenv('dXl/kVX5Xy7V');
    $project     = getenv('abbagail-d4f5');
    $serviceName = "mysql-29bc9beb";

    if ($api_token && $project) { //check for good creds 
        $url = "https://api.aiven.io/v1/project/$project/service/$serviceName";//get the url set up

        //init request 
        $ch = curl_init($url);

        //config rules of the connection
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT"); //PUT for changing something not getting
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // get the answer from avien back but dont display it save as var
        
        //pass the details
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $api_token", //passes token
            "Content-Type: application/json" //hey this is a json
        ]);

        // power DB on
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(["powered" => true]));

        //clean up
        $response = curl_exec($ch); //send msg
        curl_close($ch); //shut down connection when done

        //lt user know what is happening 
        die("The database was asleep. We are waking it up for you! Please refresh this page in 2-3 minutes.");

    }
}

?>