<!DOCTYPE HTML>
<html>
    <head>
        <title> Summer Lunch Program Register Page </title>
        <link rel="stylesheet" type="text/css" href="../CSS/Style.css">
    </head>

    <body>

    <div class="background-image">
        <!-- <img src="../photos/logo.png" alt="background"> -->
    </div>

    <div class="logo-header">
        <div class="logo">
            <!-- <img src="../photos/logo.png" alt="Logo"> -->
        </div>
        <h1>Summer Lunch <br> Program</h1>
    </div>

    <div class="register-container">
        <h2>Request a Volunteer Account</h2>
        <p> Please fill out the form below to request a volunteer account. <br>
            Once submitted, your request will be reviewed.
        </p>
        
        <form action="../services/register.php" method="post">
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" required>

            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" required>

            <label for="contact">Contact:</label>
            <input type="email" id="contact" name="contact" required>
            
            <label for="has_keys"> Do you have a set of keys to the building?:</label>
            <input type="checkbox" id="has_keys" name="has_keys" value="1"> // 1 for true 0 is default set on register.php if it not 1.

            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Submit Account Request</button>
        </form>
        
        <div class="login-link">
            <p>Already have an account? <a href="../index.php">Login here</a>.</p>
        </div>

    </div>  

    </body>
</html>