<!DOCTYPE html>
<html>
<head>
    <title> Processing Form Data </title>
</head>
<body>

    <h1> form processing results </h1>
    <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = $_POST["username"];
            $email = $_POST["email"];

            echo "<h2> Name: $username </h2>";
            echo "<h2> Email: $email </h2>";
        } else {
            echo "<h2> No data submitted! </h2>";
        }
    ?>

</body>
</html>