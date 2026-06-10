<?php
require_once "connection.php";
try {
    $stmt = $conn->query("SELECT * FROM ECF_USER WHERE ROWNUM <= 1");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    print_r($result);
    echo "Connection OK!";
} catch (PDOException $e) {
    die("Connection Error: " . $e->getMessage());
}
