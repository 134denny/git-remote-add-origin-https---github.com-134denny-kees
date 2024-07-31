<?php
function shutdownFunction() {
    $error = error_get_last();
    if ($error !== NULL) {
        $errorMessage = "[" . date("Y-m-d H:i:s") . "] ";
        $errorMessage .= "Shutdown Error: " . $error['message'] . " - ";
        $errorMessage .= "File: " . $error['file'] . ", Line: " . $error['line'] . "\n";

        // Log error to a file
        error_log($errorMessage, 3, __DIR__ . '/../logs/error.log');
        
        // Display a generic message to the user (optional)
        if (ini_get('display_errors')) {
            echo "<div class='error-message'>A fatal error occurred. Please try again later.</div>";
        }
    }
}

// Register the shutdown function
register_shutdown_function("shutdownFunction");
?>
