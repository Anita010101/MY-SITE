<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'connection.php';
$con = $conn;

$_SESSION["FRM"] = "RV";
require_once 'CHK_PRIV.php';

// Get the PRNO from URL
$prNo = trim($_GET['ID'] ?? '');

if (empty($prNo)) {
    die('No Production Request selected.');
}

// Fetch the production request record
$stmtData = $con->prepare("
    SELECT 
        p.id, p.prno, p.materialname, p.contractno, p.requestedqty, 
        p.dateenter, p.status, p.month, p.year, p.remarks,
        NVL(a.total_alloc, 0) AS allocated_qty,
        (NVL(p.requestedqty, 0) - NVL(a.total_alloc, 0)) AS balance_qty
    FROM productionrequest p
    LEFT JOIN (
        SELECT contractno, materialname, SUM(NVL(allocateqty, 0)) AS total_alloc
        FROM reorderlevelcontractno
        GROUP BY contractno, materialname
    ) a ON a.contractno = p.contractno AND a.materialname = p.materialname
    WHERE UPPER(p.prno) = UPPER(?)
");
$stmtData->execute([$prNo]);
$rowData = $stmtData->fetch(PDO::FETCH_ASSOC);

if (!$rowData) {
    die('Production Request not found.');
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Production Request Print</title>
<style>
body { font-family: Arial, sans-serif; margin: 20px; }
table { width: 100%; border-collapse: collapse; }
td { border: 1px solid #000; padding: 8px; }
th { border: 1px solid #000; padding: 8px; background-color: #E0E0E0; font-weight: bold; }
.header { text-align: center; margin-bottom: 30px; }
.logo { text-align: center; margin-bottom: 10px; }
.info-table { width: 100%; margin-bottom: 20px; }
.info-table td { border: none; padding: 5px; }
.section-title { font-weight: bold; margin-top: 20px; margin-bottom: 10px; }
</style>
</head>
<body onload="window.print();">

<div class="logo">
    <img src="logo.png" alt="Company Logo" style="max-width: 200px;" />
</div>

<div class="header">
    <h2>ENGINEERING CARPENTRY FACTORY</h2>
    <h3>PRODUCTION REQUEST</h3>
</div>

<table class="info-table">
    <tr>
        <td width="50%"><b>Request No.:</b> <?php echo htmlspecialchars($rowData['PRNO']); ?></td>
        <td width="50%"><b>Date:</b> <?php echo htmlspecialchars($rowData['DATEENTER']); ?></td>
    </tr>
    <tr>
        <td><b>User:</b> <?php echo htmlspecialchars($_SESSION['UID']); ?></td>
        <td><b>Month-Year:</b> <?php echo htmlspecialchars($rowData['MONTH']).'-'.htmlspecialchars($rowData['YEAR']); ?></td>
    </tr>
</table>

<div class="section-title">Production Details</div>

<table>
    <tr>
        <th>Sr.No.</th>
        <th>Contract No.</th>
        <th>Material Name</th>
        <th>Allocated Qty.</th>
        <th>Balance Qty.</th>
        <th>Requested Qty.</th>
        <th>Status</th>
    </tr>
    <tr>
        <td align="center">1</td>
        <td align="center"><?php echo htmlspecialchars($rowData['CONTRACTNO']); ?></td>
        <td><?php echo htmlspecialchars($rowData['MATERIALNAME']); ?></td>
        <td align="center"><?php echo htmlspecialchars($rowData['ALLOCATED_QTY']); ?></td>
        <td align="center"><?php echo htmlspecialchars($rowData['BALANCE_QTY']); ?></td>
        <td align="center"><?php echo htmlspecialchars($rowData['REQUESTEDQTY']); ?></td>
        <td align="center"><?php echo htmlspecialchars($rowData['STATUS']); ?></td>
    </tr>
</table>

<div class="section-title">Remarks</div>

<table>
    <tr>
        <td><?php echo htmlspecialchars($rowData['REMARKS']); ?></td>
    </tr>
</table>

<table class="info-table" style="margin-top: 40px;">
    <tr>
        <td width="33%">
            <b>Received By:</b><br /><br />
            __________________
        </td>
        <td width="33%">
            <b>Verify By:</b><br /><br />
            __________________
        </td>
        <td width="33%">
            <b>Posted By:</b><br /><br />
            __________________
        </td>
    </tr>
</table>

</body>
</html>
