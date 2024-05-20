<?php
$connection = new mysqli("localhost", "root", "", "assignment02");

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}
?>