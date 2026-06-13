<!DOCTYPE html>
<html>

<head>
    <title> My Website Demo </title>
        <link rel="stylesheet" type="text/css" href="CSS/myStyle.css">

</head>

<body>
    <h1> Home!</h1>
    <div class="navigation">
        <a href="index.php"> Home </a>
        <a href="videos.html"> Videos </a>
        <a href="photos.html"> Photos </a>
        <a href="form.php"> My Form </a>
        <a href="pages/page5.html"> Folder Page </a>
    </div>

    <h2> names starting with J: </h2>
    <?php
        $names = array("John", "Jane", "Jack", "Jill", "James");
        foreach ($names as $name) {
            echo "$name<br>";
        }
    ?>

    <h2> Simple Calculator </h2>
    <form action="index.php" method="post">
        <input type="number" name="num1" placeholder="Number 1" required>

        <label><input type="radio" name="operation" value="add" checked> + </label>
        <label><input type="radio" name="operation" value="subtract"> - </label>
        <label><input type="radio" name="operation" value="multiply"> * </label>

        <input type="number" name="num2" placeholder="Number 2" required>
        <input type="submit" value="Calculate">
    </form>
    <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $num1 = $_POST["num1"];
            $num2 = $_POST["num2"];
            $operation = $_POST["operation"];
            $result = 0;

            switch ($operation) {
                case "add":
                    $result = $num1 + $num2;
                    break;
                case "subtract":
                    $result = $num1 - $num2;
                    break;
                case "multiply":
                    $result = $num1 * $num2;
                    break;
            }

            echo "<h3> Result: $result </h3>";
        }
        ?>

    <h2> Submit to processing </h2>
    <form action="processing.php" method="post">
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="submit" value="Submit">
    </form>
    
</body>

</html>