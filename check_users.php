<?php
require_once "connection.php";

if (!isset($conn) && isset($con)) { $conn = $con; }

try {
    $sql = "SELECT * FROM ECF_USER";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h3>Users in ECF_USER table:</h3>";
    
    if (count($rows) == 0) {
        echo "<p style='color:red;'><b>❌ Table is EMPTY - No users exist!</b></p>";
    } else {
        echo "<table border='1' cellpadding='10'>";
        foreach ($rows as $row) {
            echo "<tr>";
            foreach ($row as $key => $value) {
                echo "<td><b>$key:</b> $value</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    }
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
