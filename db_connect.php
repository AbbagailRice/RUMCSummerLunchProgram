<?php
//grab the credentials from render
$host = getenv('DB_HOST');
$port = getenv('DB_PORT');
$dbname = getenv('DB_NAME');
$username = getenv('DB_USER');
$password = getenv('DB_PASS');

//try to connect (learned from https://www.w3schools.com/php/php_mysql_connect.asp)
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
    //if the connection fails show an error message
    echo "Connection failed: " . $e->getMessage();
    exit();
}

?>