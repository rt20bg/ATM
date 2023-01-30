<?php
if (!isset($_SESSION)) {
    session_start();
  }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">

    <title>Document</title>
    <h1>Online ATM</h1>
</head>

<body>

    <?php include 'test_values.php';


    $_SESSION["attempts"] = 0; //After 3 failed attempts you are locked out for 30 seconds    
    $_SESSION["withdrawals"] = 0; //For withdrawals limits
    $_SESSION["login"] = false; //For PIN login
    $_SESSION["wmsg"] = ""; //Withdraw messages    
    ?>

</body>
</html>