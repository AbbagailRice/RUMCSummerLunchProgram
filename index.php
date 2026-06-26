<!DOCTYPE html>
<html>

<head>
    <title> Summer Lunch Program Login Page </title>
        <link rel="stylesheet" type="text/css" href="CSS/Style.css">

</head>

<body>
    <?php include 'includes/header.php'; ?>

    <div class="auth-card">
        
        <div class= 'login-section'>
            <form action="services/login.php" method="post">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required><br><br>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required><br><br>

                <input type="submit" value="Login">
            </form>

        </div>

        <div class= 'middle-divider'>
            or
        </div>
        
        <div class= 'register-section'>
            <a href="pages/registrationPage.php" class="btn-register">
                Request an Account.
            </a>
        </div>

    </div>

   

</body>

</html>