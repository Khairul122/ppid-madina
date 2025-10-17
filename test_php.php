<?php
// Simple test file to check if PHP is working
echo "PHP is working!<br>";
echo "Current time: " . date('Y-m-d H:i:s') . "<br>";

// Test database connection
global $database;
if (isset($database)) {
    echo "Database connection available<br>";
    $conn = $database->getConnection();
    if ($conn) {
        echo "Database connected successfully<br>";
    } else {
        echo "Database connection failed<br>";
    }
} else {
    echo "Database not available<br>";
}

echo "Test completed.";
?>