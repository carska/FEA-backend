<?php

include 'currencyHandler.php';
$handler = new currencyHandler();

print "\r\nWelcome."
        . "\r\nTo compare current date to 30 days prior, leave the input blank."
        . "\r\nTo compare another date to 30 days prior, enter a date formatted as YYYY/MM/DD. "
        . "\r\nAll dates from 2000/02/01 to current are accepted."
        . "\r\nTo exit: ctrl+C\r\n";

do {
    print "\r\nInput command\033[5m:\033[0m";
    $handle = fopen("php://stdin", "r");
    $line = fgets($handle);
    $input = trim($line);
    $handler->checkCommand($input);
} while ($line != 'q');
?>