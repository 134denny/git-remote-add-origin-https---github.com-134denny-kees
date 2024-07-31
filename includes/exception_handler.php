<?php
function customExceptionHandler($exception) {
    $errorMessage = "[" . date("Y-m-d H:i:s") . "] ";
    $errorMessage .= "Uncaught Exception: " . $exception->getMessage() . " - ";
    $errorMessage .= "File: " . $exception->getFile() . ", Line: " . $exception->getLine() . "\n";
    
    // Log error to a file
    error_log($errorMessage, 3, __DIR__ . '/../logs/error.log');
    
    // Display a generic message to the user (optional)
    if (ini_get('display_errors')) {
        echo "<div class='error-message'>Something went wrong. Please try again later.</div>";
    }
}

// Set the custom exception handler
set_exception_handler("customExceptionHandler");
?>
