<?php
// No session_start() here — already started in GOODS_ISSUE.php

if (!isset($_SESSION['UID'])) {
    header("Location: index.php");
    exit;
}

try {
    // Use positional placeholder (?) instead of named (:uid)
    $sql = "SELECT * FROM ECF_USER WHERE USR_USER_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$_SESSION['UID']]);
    $Rsobj = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$Rsobj) {
        header("Location: index.php");
        exit;
    }

    // Default privileges
    $Rsobj = array_merge($Rsobj, [
        'PRIV_ADD' => $Rsobj['PRIV_ADD'] ?? 'Y',
        'PRIV_EDIT' => $Rsobj['PRIV_EDIT'] ?? 'Y',
        'PRIV_DEL' => $Rsobj['PRIV_DEL'] ?? 'Y',
        'PRIV_VIEW' => $Rsobj['PRIV_VIEW'] ?? 'Y',
        'PRIV_PRINT' => $Rsobj['PRIV_PRINT'] ?? 'Y'
    ]);

} catch (PDOException $e) {
    die("Privilege Check Error: " . $e->getMessage());
}
?>
