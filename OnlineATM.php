<?php
if (!isset($_SESSION)) {
    session_start();
  }
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1>Online ATM - no JS!</h1>

    <?php
    include 'functions.php';
    ?>

    <?php
    include "reset.php"; //the reset div for the reset form and logic. And the info for the test values.       
    
    //Initializing some session variables
    if (!isset($_SESSION["stored"])) //stores the tuped from the keypad numbers
        $_SESSION["stored"] = "";

    if (!isset($_SESSION["login"])) //stores login status
        $_SESSION["login"] = false;

    if (!isset($_SESSION["locked"]))
        $_SESSION["locked"] = 0; //stores locked out time
    
    if (!isset($_SESSION['attempts'])) //counts the login attelmts
        $_SESSION['attempts'] = 0;
    ?>

    <div id="text_box">
        <?php
        //First text box logic
        
        //First PIN, Login and Locked
        if ($_SESSION["login"] == false) {
            if ($_SESSION["attempts"] >= 3) {
                echo ("Every 3 failed attempts you cannot enter new PINs for 30 seconds!");
                $_SESSION["locked"] = time() + 30;
                $_SESSION["attempts"] = 0;
            } else {
                if (!($_SESSION["login"] == true) && isLocked()) {
                    echo ("Please enter your PIN number");
                } else {
                    echo ("Every 3 failed attempts you cannot enter new PINs for 30 seconds!");
                }
            }
        }

        //Then the withdraw limits
        if ($_SESSION["login"] == true) {
            echo ("Please enter an withdraw amount or press history" . "<br>");
            if (isset($_SESSION["limit"])) {
                echo ($_SESSION["limit"]);
                unset($_SESSION["limit"]);
                $_SESSION["stored"] = "";
            }
        }
        ?>
    </div>

    <div id="ATM_text_box">
        <?php echo $_SESSION["stored"]; //Second text box ?>
    </div>

    <!-- ATM_Keypad -->
    <div id="ATM_container">
        <div id="keypad">
            <form method="GET">
                <input class="keypad" type="submit" name="keypad" value="1">
                <input class="keypad" type="submit" name="keypad" value="2">
                <input class="keypad" type="submit" name="keypad" value="3">
                <input class="keypad" type="submit" name="keypad" value="4">
                <input class="keypad" type="submit" name="keypad" value="5">
                <input class="keypad" type="submit" name="keypad" value="6">
                <input class="keypad" type="submit" name="keypad" value="7">
                <input class="keypad" type="submit" name="keypad" value="8">
                <input class="keypad" type="submit" name="keypad" value="9">
                <input class="keypad" type="submit" name="keypad" value="0">
                <input class="keypad" type="submit" name="clear" value="C">
                <input class="keypad" type="submit" name="enter" value="PIN↵">
                <input class="large_key" type="submit" name="withdraw" value="Withdraw">
                <input class="large_key" type="submit" name="history" value="History">
            </form>
        </div>
    </div>
    <?php

    //ATM buttons functionality
    if (($_SERVER['REQUEST_METHOD'] === 'GET') && isLocked()) { //Cheking if the keybord is locked
        if (isset($_GET['clear'])) { //The clear button
            $_SESSION["stored"] = "";
            refresh();
        } else if (isset($_GET['keypad'])) { //The keypad number buttons
            $_SESSION["stored"] .= $_GET['keypad'];
            refresh();
        } else if (isset($_GET['enter'])) { //After we enter the pin
    
            if ($_SESSION["stored"] == $_SESSION["PIN"]) { //PIN number matches
                $_SESSION["login"] = true;
                $_SESSION["stored"] = "";
                $_SESSION["attempts"] = 0;
                refresh();
            }

            if (($_SESSION["stored"] != $_SESSION["PIN"])) { //Pin doesn't match
                if ($_SESSION["login"] == true) { //Pin doesn't match but we are already logged
                    $_SESSION["stored"] = "";
                    refresh();
                }
                if (!$_SESSION["login"] == true) { //Pin doesn't match and we had not logged before
                    $_SESSION["attempts"] += 1;
                }

                $_SESSION["stored"] = "";
                refresh();
            }


        } else if (isset($_GET['withdraw'])) { //The withdraw button
    
            if ($_SESSION['login'] == true) {

                //Unless limits, withdraw.
                if ($_SESSION['stored'] > 400 || $_SESSION['stored'] < 10) {
                    $_SESSION["limit"] = "<br>The withdraw sum must be a number berween 10 BGN and 400 BGN";
                    $_SESSION['stored'] = "";
                    refresh();
                } elseif (($_SESSION['balance'] - $_SESSION['stored']) < 0) {
                    $_SESSION["limit"] = "<br>Insufficient funds!";
                    $_SESSION['stored'] = "";
                    refresh();
                } else {
                    echo (withdraw($_SESSION['stored'], $_SESSION["balance"]));
                }
            }
            $_SESSION['stored'] = "";

            if ($_SESSION['login'] == false) {
                refresh();
            }

        } else if (isset($_GET['history'])) { //The history button
            if (isset($_SESSION["history"])) {
                print_r($_SESSION["history"]);
                $_SESSION['stored'] = "";
            } else {
                $_SESSION['stored'] = "";
                refresh();
            }
        }
    }
    ?>

</body>

</html>