
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

        .sidebar-navigation {
            position: fixed; /* layout rule  */
            top: 0;
            right: 0; /* set it to right fixed*/
            width: 100px;
            height: 100vh; /* top to bottom*/
            background-color: var(--color-primary-scarlet);
            
            /* alignments of the links */
            display: flex;
            flex-direction: column; /* stack vert */
            align-items: center; /* centered horiz */
            gap: 30px; /* space between each */
            box-sizing: border-box;
            z-index: 10; /* keep to top */
        }

        /* the link containers*/
        .nav-item a {
            display: block;
            text-align: center;
            text-decoration: none;  /* no weird line under when clicked */
            color: var(--color-cotton-rose);
            font-weight: bold;
            max-width: 100px; /* Safe padding buffer width text wrapping constraint */
            word-wrap: break-word; /*break word if it is to long for sidebar*/

            font-family: 'Arial', sans-serif;
        }

        /* icons */
        .nav-icon {
            display: inline-block;
            font-size: 2.4rem; /*sizing*/
            margin-bottom: 5px; /* spacing*/
            color: var(--color-cotton-rose);
        }

    </style>
</head>
<body>


<!-- This handles the fixed right-hand red navigation column-->
<nav class="sidebar-navigation">
    <div class="nav-item">
        <a href="../pages/dashboard.php">
            <span class="nav-icon">🏠︎</span><br>
            Home
        </a>
    </div>

    <div class="nav-item">
        <a href="../pages/manage_recipients.php">
            <span class="nav-icon">ጸ</span><br>
            Recipient Management
        </a>
    </div>

    <div class="nav-item">
        <a href="../pages/attendance.php">
            <span class="nav-icon">🗒</span><br>
            Attendance
        </a>
    </div>
</nav>
</body>
</html>