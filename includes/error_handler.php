<?php
function customErrorHandler($errno, $errstr, $errfile, $errline) {
    $errorMessage = "[" . date("Y-m-d H:i:s") . "] ";
    $errorMessage .= "Error: [$errno] $errstr - ";
    $errorMessage .= "File: $errfile, Line: $errline\n";

    // Log error to a file
    error_log($errorMessage, 3, __DIR__ . '/../logs/error.log');

    // Display a generic message to the user (optional)
    if (ini_get('display_errors')) {
        echo "<div class='error-message'>Something went wrong. Please try again later.</div>";
    }

    /* Don't execute PHP internal error handler */
    return true;
}

// Set the custom error handler
set_error_handler("customErrorHandler");
?>