<!DOCTYPE HTML>
<html>

<head>
    <title> Header Styling </title>
    <style>
        :root {
            /* base colors */
            --color-black: #000000;
            --color-dim-grey: #5d6769;
            --color-white: #ffffff;
            
            /* lights */
            --color-pale-sky: #bfd7ea;
            --color-dry-sage: #b6c69d;
            
            /* reds and pinks */
            --color-cotton-rose: #fdd1d3;
            --color-cotton-candy: #fda6a9;
            --color-vibrant-coral: #ff5a5f;
            --color-primary-scarlet: #e50027;
        }

        .background-image {
            position: fixed;/* dont move*/
            top: 0;
            left: 0;
            width: 100vw;/*frame size */
            height: 100vh;
            z-index: -2; /* go to back*/
            background-image: 
            linear-gradient(rgba(255, 255, 255, 0.3), rgba(255, 255, 255, 0.3)), 
                url('../photos/background2.jpg');
            background-size: cover; /* scale to size*/
            background-position: center; /* keeps center even if cropped*/
            background-repeat: no-repeat;

        }

        .logo-header {
            position: absolute;
            top: 20px;
            left: 20px;
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
            font-family: 'Arial', sans-serif;
        }

    </style>
</head>
<body>

    <!-- This handles logo and background-->
    <div class="background-image">
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