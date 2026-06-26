<!DOCTYPE HTML>
<html>

<head>
    <title> Header Styling </title>
    <style>
        .background-image {
            position: fixed;/* dont move*/
            top: 0;
            left: 0;
            width: 100vw;/*frame size */
            height: 100vh;
            z-index: -2; /* go to back*/
            background-image: 
            linear-gradient(rgba(255, 255, 255, 0.65), rgba(255, 255, 255, 0.65)), /* dim*/
            url('../photos/background.jpg');
            background-size: cover; /* scale to size*/
            background-position: center; /* keeps center even if cropped*/
            background-repeat: no-repeat;

        }

        .logo-header {
            position: absolute;
            top: 40px;
            left: 40px;
            display: flex;
            align-items: center; /* vertical align */
            gap: 15px; /* spacing */
        }

        .logo img {/* sizing for the image */
            height: 90px;
            width: auto;
            display: block;
        }

        .logo-header h1 {
            margin: 0;
            font-size: 2.2rem;
            font-weight: 800;
            line-height: 1.1;
            text-transform: uppercase; /* make uppercase */
            letter-spacing: 0.5px; /* even spacing */
        }

    </style>
</head>
<body>

    <!-- This handles logo and background-->
    <div class="background-image">
            <img src="../photos/background.jpg" alt="background">
    </div>

    <div class="logo-header">
        <div class="logo">
           <img src="../photos/logo.png" alt="Logo">
        </div>
        <h1>Summer Lunch <br> Program</h1>
    </div>
</div>
</body>
</html>