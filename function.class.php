<?php
if (!class_exists('Functions')) {
    class Functions {
        // Redirect method with headers check and logging
        public function redirect($address) {
            if (!headers_sent()) {
                header("Location: " . $address);
                exit;
            } else {
                error_log("Headers already sent. Cannot redirect to: " . $address . "\n", 3, "../logs/error.log");
            }
        }
    }
}

// Create an instance of the Functions class
$fn = new Functions();
?>
