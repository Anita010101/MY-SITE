<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "connection.php";

if (!isset($conn) && isset($con)) {
    $conn = $con;
}

$user_id = strtoupper(trim($_POST['txt_user_id'] ?? ''));
$passwd  = trim($_POST['txt_passwd'] ?? '');

if ($user_id === '' || $passwd === '') {
    header("Location: index.php?ERROR=Y");
    exit;
}

try {
    $sql = "SELECT USR_USER_ID, USR_NAME
            FROM ECF_USER
            WHERE UPPER(USR_USER_ID) = :user_id
              AND USR_PASSWD = :passwd";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);
    $stmt->bindParam(':passwd', $passwd, PDO::PARAM_STR);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $_SESSION['UID'] = $row['USR_USER_ID'];
        $_SESSION['UNAME'] = $row['USR_NAME'];

        header("Location: menu.php");
        exit;
    } else {
        header("Location: index.php?ERROR=Y");
        exit;
    }
} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}
?>
