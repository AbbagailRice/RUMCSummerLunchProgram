<DOCTYPE html>
<html>
<head>
    <title>PHP Handler for Form</title>
    <?php
        $favcolor = isset($_POST["color"]) ? $_POST["color"] : "black"; // isset check to see if color has content if not defaults to black

    ?>

    <style>
        h2 {
            color: <?php echo $favcolor; ?>;
        }
    </style>

</head>
<body>
    <h1> Form Data Received </h1>
    <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") { // checks if the form was submitted using POST method
            $firstname = $_POST["firstname"];
            $lastname = $_POST["lastname"];
            $address = $_POST["address"];
            $city = $_POST["city"];
            $state = $_POST["state"];
            $zipcode = $_POST["zipcode"];
            $number = $_POST["number"];
            $day = $_POST["day"];
            $color = $_POST["color"];

            echo "<h2> Name: $firstname $lastname </h2>";
            echo "<h2> Address: $address, $city, $state, $zipcode </h2>";
            echo "<h2> Favorite Number: $number </h2>";
            echo "<h2> Favorite Day of the Week: $day </h2>";
            echo "<h2> Favorite Color: $color </h2>";

        } else {
            echo "<h2> No data submitted! </h2>";
        }
    ?>

</body>
</html>