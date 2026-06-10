<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

// ============================================
// Oracle Database Connection (using local IP)
// ============================================
$host     = '192.168.1.200';
$port     = '1521';
$service  = 'ECFINV';
$username = 'ECFINV';
$password = 'ECFINV'; 

try {
    $tns = "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=$host)(PORT=$port))(CONNECT_DATA=(SERVICE_NAME=$service)))";
    $conn = new PDO("oci:dbname=" . $tns . ";charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // ADD THIS LINE BELOW to match your page code
    $con = $conn; 

} catch (PDOException $e) {
    die("Connection Error: " . $e->getMessage());
}
?>
