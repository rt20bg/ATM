<div id="PIN">
  <form method="POST" action="index.php">
    <label for="PIN">1) Please enter a PIN number:</label>
    <input type="text" id="PIN" name="PIN">
    <input type="submit" value="Submit">
  </form>
</div>

<div id="balance">
  <form method="POST">
    <label id="balance_label" for="balance">2) Please enter the balance amount:</label>
    <input type="text" id="balance" name="balance">
    <input type="submit" value="Submit">
  </form>
</div>

<?php

//Adding a new pin and balance values to the seesion
if (isset($_POST["PIN"]))
  $_SESSION["PIN"] = $_POST["PIN"];
if (isset($_POST["balance"]))
  $_SESSION["balance"] = $_POST["balance"];

//Cheking and validating
$valid = true;

if (isset($_SESSION["PIN"])) {
  echo 'Your PIN is: ' . $_SESSION["PIN"] . '<br>';
  if (!(strlen($_SESSION["PIN"]) == 4 && is_numeric($_SESSION["PIN"]) && $_SESSION["PIN"] >= 0)) {
    echo 'This is not a valid PIN. Please entere a 4 digits number!' . "<br>";
    $valid = false;
  }
}

if (isset($_SESSION["balance"])) {
  echo ("Your balance is: " . $_SESSION["balance"] . " BGN" . "<br>");
  if (!is_numeric($_SESSION["balance"])) {
    echo ("The balance must be a number!");
    $valid = false;
  }
}

if (isset($_SESSION["PIN"]) && isset($_SESSION["balance"]) && $valid == true) {
  echo ('<meta http-equiv="refresh" content="0; URL=OnlineATM.php">');
}
?>