<div id="reset">
    <form method="post">
        <input type="hidden" name="action" value="logout">
        <input type="submit" value="RESET">
    </form>
    <?php
    //The test values
    if (isset($_SESSION["PIN"]) && isset($_SESSION["balance"])) {
        echo 'Test PIN is ' . $_SESSION["PIN"] . ' <br> Test balance was ' . $_SESSION["balance"] . ' BGN';
        echo '<br>Attempts = ' . $_SESSION["attempts"];
    }
    ?>
</div>

<?php

//The Logout logic
if (isset($_POST['action']) && $_POST['action'] == 'logout') {
    session_start();
    session_destroy();
    echo ('<meta http-equiv="refresh" content="0; URL=index.php">');
    exit();
}
?>