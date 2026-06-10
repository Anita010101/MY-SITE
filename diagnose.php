<?php
require_once "connection.php";

if (!isset($conn) && isset($con)) { $conn = $con; }

echo "<h2>🔍 Database Diagnostic</h2>";

// =====================
// STEP 1: List ALL tables
// =====================
echo "<h3>Step 1: All Tables You Have Access To</h3>";
try {
    $stmt = $conn->prepare("SELECT TABLE_NAME FROM USER_TABLES ORDER BY TABLE_NAME");
    $stmt->execute();
    $tables = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($tables) == 0) {
        echo "<p style='color:red;'>❌ No tables found!</p>";
    } else {
        echo "<ul>";
        foreach ($tables as $table) {
            echo "<li>" . $table['TABLE_NAME'] . "</li>";
        }
        echo "</ul>";
    }
} catch (PDOException $e) {
    echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
}

// =====================
// STEP 2: Columns of ECF_USER
// =====================
echo "<h3>Step 2: Columns in ECF_USER</h3>";
try {
    $stmt = $conn->prepare("SELECT COLUMN_NAME, DATA_TYPE 
                            FROM USER_TAB_COLUMNS 
                            WHERE TABLE_NAME = 'ECF_USER' 
                            ORDER BY COLUMN_ID");
    $stmt->execute();
    $cols = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($cols) == 0) {
        echo "<p style='color:red;'>❌ Table ECF_USER not found or has no columns!</p>";
    } else {
        echo "<table border='1' cellpadding='8' style='border-collapse:collapse;'>";
        echo "<tr style='background:#ddd;'>
                <th>Column Name</th>
                <th>Data Type</th>
              </tr>";
        foreach ($cols as $col) {
            echo "<tr>
                    <td><b>" . $col['COLUMN_NAME'] . "</b></td>
                    <td>" . $col['DATA_TYPE'] . "</td>
                  </tr>";
        }
        echo "</table>";
    }
} catch (PDOException $e) {
    echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
}

// =====================
// STEP 3: Show all data in ECF_USER
// =====================
echo "<h3>Step 3: All Data in ECF_USER</h3>";
try {
    $stmt = $conn->prepare("SELECT * FROM ECF_USER");
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($rows) == 0) {
        echo "<p style='color:orange;'>⚠️ Table exists but is EMPTY - No user records found!</p>";
    } else {
        echo "<table border='1' cellpadding='8' style='border-collapse:collapse;'>";
        // Print headers
        echo "<tr style='background:#ddd;'>";
        foreach (array_keys($rows[0]) as $col) {
            echo "<th>$col</th>";
        }
        echo "</tr>";
        // Print rows
        foreach ($rows as $row) {
            echo "<tr>";
            foreach ($row as $value) {
                echo "<td>$value</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    }
} catch (PDOException $e) {
    echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
}
?>
