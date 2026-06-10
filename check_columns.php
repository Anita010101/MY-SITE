<?php
require_once "connection.php";

if (!isset($conn) && isset($con)) { $conn = $con; }

try {
    $sql = "SELECT COLUMN_NAME FROM ALL_TAB_COLUMNS 
            WHERE TABLE_NAME = 'ECF_USER' 
            ORDER BY COLUMN_ID";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    
    echo "<h3>Columns in ECF_USER table:</h3>";
    echo "<ul>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<li><b>" . $row['COLUMN_NAME'] . "</b></li>";
    }
    echo "</ul>";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
