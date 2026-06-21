<!DOCTYPE html>
<html>

<head>
    <title> Summer Lunch Program Login Page </title>
        <link rel="stylesheet" type="text/css" href="CSS/Style.css">

</head>

<body>
    <div class="background-image">
        <!-- <img src="photos/logo.png" alt="background"> -->
    </div>

    <div class="logo-header">
        <div class="logo">
            <!-- <img src="photos/logo.png" alt="Logo"> -->
        </div>
        <h1>Summer Lunch <br> Program</h1>
    </div>

    <div class="auth-card">
        
        <div class= 'login-section'>
            <h2>Login </h2>
                <form action="login.php" method="post">
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
                <a href="register.php" class="btn-register">
                    Sign Up with Email
                </a>
            </div>

        </div>


    </div>

    
</body>

</html>