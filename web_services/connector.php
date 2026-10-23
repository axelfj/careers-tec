<?php
function request_connection() {
    $host = getenv('CAREERS_DB_HOST'); $user = getenv('CAREERS_DB_USER');
    $password = getenv('CAREERS_DB_PASSWORD'); $database = getenv('CAREERS_DB_NAME');
    if (!$host || !$user || !$password || !$database) throw new RuntimeException('Database environment configuration is required');
    $conn = new mysqli($host, $user, $password, $database);
    $conn->set_charset('utf8mb4');
    return $conn;
}
function close_connection($conn) { mysqli_close($conn); }
