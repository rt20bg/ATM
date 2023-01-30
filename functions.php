<?php

/**
 * Handles the calculation, formatting and logging of the transactions and balance
 * Withdraw function takes two parameters: $amount and $balance.
 * It calculates the banknotes needed for the withdrawal and applies a 2% tax.
 * It also logs the date, banknotes details, withdrawal amount, tax, total sum, and new balance in the session.
 *
 * @param float $amount The amount to withdraw
 * @param float $balance The current balance
 * @return string A message containing the details of the withdrawal
 */
function withdraw($amount, $balance)
{
    $balance_before_transaction = $balance;
    // Get the banknotes needed to make the specified amount
    $banknotes = getChange($amount);
    // Calculate the sum of the banknotes
    $sum = calculateArrayContent($banknotes);
    // Get the current date and time
    $date = date("d-m-Y H:i:s");
    // Calculate the tax of 2% on the sum of banknotes
    $tax = 0.02 * $sum;
    // Calculate the total amount to be withdrawn, including the tax
    $total = $sum * 1.02;
    // Update the balance by subtracting the total amount to be withdrawn
    $balance = $balance - $total;

    // Create a string containing the banknotes and their respective counts
    $notes = "";
    foreach ($banknotes as $key => $value) {
        if ($value > 0)
            $notes .= "$key BGN = $value <br> ";
    }

    // Create a message containing details of the transaction such as date, banknotes, withdrawal amount, tax, total amount and the new balance
    $msg = "Date: $date <br> Balance: $balance_before_transaction <br> Banknotes: <br>
    $notes Withdrawal: $sum BGN <br> Tax: $tax BGN<br> Total sum: $total BGN <br>
    New blance: $balance BGN<br>";

    $msg1 = "Date: $date; Balance: $balance_before_transaction; Withdrawal: $total; Tax: $tax; New Blance: $balance BGN";

    // Save the message to the session data
    if (isset($_SESSION['data']))
        $_SESSION['data'] .= "\n" . $msg;
    else
        $_SESSION['data'] = $msg;

    // Save the transactions to the session data, and to a general log file.
    save_to_session($msg1);

    $_SESSION['balance'] -= $total;
    return $msg;
}

/**
 * Get the banknotes needed to make the specified amount
 * @param int $amount
 * @return array<int>
 */
function getChange($amount)
{
    $result = array("100" => 0, "50" => 0, "20" => 0, "10" => 0);
    $sum = $amount;
    while ($sum >= 10) {
        if ($sum - 100 >= 0) {
            $result["100"]++;
            $sum -= 100;
            continue;
        }
        if ($sum - 50 >= 0) {
            $result["50"]++;
            $sum -= 50;
            continue;
        }
        if ($sum - 20 >= 0) {
            $result["20"]++;
            $sum -= 20;
            continue;
        }
        if ($sum - 10 >= 0) {
            $result["10"]++;
            $sum -= 10;
            continue;
        }
    }
    return $result;
}

/**
 * Calculates the content of an array of banknotes.
 */
function calculateArrayContent($array)
{
    $sum = 0;
    foreach ($array as $key => $value) {
        $sum += $key * $value;
    }
    return $sum;
}

/**
 * Saves the transaction infromation to $_SESSION['data'] and also to log.txt file.
 */
function save_to_session($string)
{
    $file = "log.txt";
    file_put_contents($file, $string . "\n", FILE_APPEND);
    // Start the session if it hasn't been started already
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    // Append the string to the current session data
    if (isset($_SESSION['history']))
        $_SESSION['history'] .= "<br>" . $string;
    else
        $_SESSION['history'] = $string;
}


function isLocked()
{
    if (time() < $_SESSION["locked"]) {
        // Allow user to perform locked functionality
        return false;
    } else {
        return true;
    }
}

function refresh()
{
    echo ('<meta http-equiv="refresh" content="0; URL=OnlineATM.php">');
}



?>