<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="CSS/myStyle.css">
</head>

<body>
    <h1> User Information </h1>
    <div class="navigation">
        <a href="index.php"> Home </a>
        <a href="videos.html"> Videos </a>
        <a href="photos.html"> Photos </a>
        <a href="form.php"> My Form </a>
        <a href="pages/page5.html"> Folder Page </a>
    </div>

    <!-- form id -->
    <form id="user-registration-form" action="MyFirstHandler.php" method="post"> // form action to send data to MyFirstHandler.php and method post to send data with the keys
        <div class="left-side">
            <div class="user-info">
                <p> Please give your first name. </p>
                    <input type="text" name="firstname" placeholder="Enter your first name">
                <p> Please give your last name. </p>
                    <input type="text" name="lastname" placeholder="Enter your last name">
                <p> Please give your address. </p>
                    <input type="text" name="address" placeholder="Enter your address">
                <p> Please give your city. </p>
                    <input type="text" name="city" placeholder="Enter your city">
                <p> Please give your state. </p>    
                    <input type="text" name="state" placeholder="Enter your state">
                <p> Please give your zip code. </p>
                    <input type="text" name="zipcode" placeholder="Enter your zip code">
                <p> What is your favorite number? </p>
                <input type="number" name="number" placeholder="Enter favorite number">
            </div>
            
            <div class="bottom-row">
                <div class="day-of-week">
                    <p> What is you favorite day of the week? </p>
                        <label><input type="radio" name="day" value="Monday"> Monday</label>
                        <label><input type="radio" name="day" value="Tuesday"> Tuesday</label>
                        <label><input type="radio" name="day" value="Wednesday"> Wednesday</label>
                        <label><input type="radio" name="day" value="Thursday"> Thursday</label>
                        <label><input type="radio" name="day" value="Friday"> Friday</label>
                        <label><input type="radio" name="day" value="Saturday"> Saturday</label>
                        <label><input type="radio" name="day" value="Sunday"> Sunday</label>
                </div>

                <div class="fav-color">
                    <p> What is your favorite color? </p>
                    <table>
                        <tr>
                            <td style="background-color:#FF0000">___</td>
                            <td >Red</td>
                        </tr>
                        <tr>
                            <td style="background-color:#FFA500"> </td>
                            <td >Orange</td>
                        </tr>
                        <tr>
                            <td style="background-color:#FFFF00"> </td>
                            <td >Yellow</td>
                        </tr>
                        <tr>
                            <td style="background-color:#008000"> </td>
                            <td >Green</td>
                        </tr>
                        <tr>
                            <td style="background-color:#0000FF"> </td>
                            <td >Blue</td>
                        </tr>
                        <tr>
                            <td style="background-color:#350283"> </td>
                            <td >Indigo</td>
                        </tr>
                        <tr>
                            <td style="background-color:#6600ff"> </td>
                            <td >Violet</td>
                        </tr>
                        
                    </table>

                    <select name="color">
                        <option value="default">Choose one</option>
                        <option value="red">Red</option>
                        <option value="orange">Orange</option>
                        <option value="yellow">Yellow</option>
                        <option value="green">Green</option>
                        <option value="blue">Blue</option>
                        <option value="indigo">Indigo</option>
                        <option value="violet">Violet</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="right-side">
            <div class="my-face">
                <img src="photos/me.jpg" alt="My Face">

            </div>

            <input type="submit" id="submit-btn" value="Submit" style="background-color: #c06ac5; border: none; color: white; padding: 12px 30px;">
        </div>
    
    </form>
</body>

</html>