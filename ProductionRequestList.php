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

header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

function nz($val, $defaultVal = '') {
    if ($val === null || $val === false || (is_string($val) && trim($val) === '')) {
        return $defaultVal;
    }
    return $val;
}

$actionStr  = strtolower(trim($_REQUEST['Action'] ?? ''));
$fm_dt      = trim($_REQUEST['txt_fm_dt'] ?? '');
$to_dt      = trim($_REQUEST['txt_to_dt'] ?? '');
$contractno = trim($_REQUEST['txtcontractno'] ?? '');
$reqid      = trim($_REQUEST['txtid'] ?? '');
$reqname    = trim($_REQUEST['txtname'] ?? '');

$pageSize = 50;
$currentPage = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
if ($currentPage < 1) $currentPage = 1;

// Check privileges
$stmtPriv = $con->prepare("SELECT PRIV_VIEW, PRIV_ADD, PRIV_MOD FROM ECF_PRIVILEGES WHERE PRIV_USER_ID = ? AND PRIV_FORM_NAME = 'SPR'");
$stmtPriv->execute([$_SESSION['UID']]);
$privRow = $stmtPriv->fetch(PDO::FETCH_ASSOC);

if (!$privRow || nz($privRow['PRIV_VIEW'], 'N') !== 'Y') {
    header('Location: menu.php');
    exit;
}

// Build WHERE + bind values
$whereParts = ["1=1"];
$bindValues = [];

if ($actionStr === 'search' && $fm_dt !== '' && $to_dt !== '') {
    $whereParts[] = "TRUNC(p.dateenter) BETWEEN TO_DATE(?, 'dd/mm/yyyy') AND TO_DATE(?, 'dd/mm/yyyy')";
    $bindValues[] = $fm_dt;
    $bindValues[] = $to_dt;
} elseif ($actionStr === 'searchcontractno' && $contractno !== '') {
    $whereParts[] = "UPPER(p.contractno) = UPPER(?)";
    $bindValues[] = $contractno;
} elseif ($actionStr === 'searchreqno' && $reqid !== '') {
    $whereParts[] = "UPPER(p.prno) = UPPER(?)";
    $bindValues[] = $reqid;
} elseif ($actionStr === 'searchname' && $reqname !== '') {
    $whereParts[] = "UPPER(p.materialname) = UPPER(?)";
    $bindValues[] = $reqname;
}

$whereClause = " WHERE " . implode(" AND ", $whereParts);

// Pagination
$startRow = (($currentPage - 1) * $pageSize) + 1;
$endRow   = $currentPage * $pageSize;

// Main query (ROWNUM method - safe for all Oracle versions)
$dataSQL = "SELECT * FROM (
    SELECT a.*, ROWNUM rnum FROM (
        SELECT 
            p.id, p.prno, p.materialname, p.contractno, NVL(p.requestedqty,0) as requestedqty, 
            p.dateenter, p.status, p.month, p.year, p.remarks,
            NVL(al.total_alloc,0) as ALLOCATED_QTY,
            (NVL(al.total_alloc,0) - NVL(p.requestedqty,0)) as BALANCE_QTY
        FROM productionrequest p
        LEFT JOIN (
            SELECT contractno, materialname, SUM(NVL(allocateqty,0)) as total_alloc
            FROM reorderlevelcontractno
            GROUP BY contractno, materialname
        ) al ON al.contractno = p.contractno AND al.materialname = p.materialname
        $whereClause
        ORDER BY p.id DESC
    ) a WHERE ROWNUM <= ?
) WHERE rnum >= ?";

$bindValues[] = $endRow;
$bindValues[] = $startRow;

$stmt = $con->prepare($dataSQL);
$stmt->execute($bindValues);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$hasMore = (count($rows) >= $pageSize);

$qs = 'Action=' . urlencode($actionStr) . 
      '&txt_fm_dt=' . urlencode($fm_dt) . 
      '&txt_to_dt=' . urlencode($to_dt) . 
      '&txtcontractno=' . urlencode($contractno) . 
      '&txtid=' . urlencode($reqid) . 
      '&txtname=' . urlencode($reqname);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Production Request Listing</title>
<script>
function testmsg(tabla){
    var t = document.getElementById(tabla);
    t.border = 1;
    window.open('data:application/vnd.ms-excel,' + encodeURIComponent(t.outerHTML));
}
window.history.forward();
function noBack() { window.history.forward(); }
</script>
</head>
<body onload="noBack();" onpageshow="if (event.persisted) noBack();">

<?php include 'header.php'; ?>

<form id="frm_lst_issue" name="frm_lst_issue" method="post" action="ProductionRequestList.php">
<table width="90%" border="0" align="center">
    <tr bgcolor="#999999"><th colspan="4"><font color="white"><b>Production Request</b></font></th></tr>
    <tr>
        <td align="center" colspan="4" style="padding:10px;">
            <b>From Date</b> <input type="text" name="txt_fm_dt" value="<?php echo htmlspecialchars($fm_dt); ?>" size="12" />
            <b>Upto Date</b> <input type="text" name="txt_to_dt" value="<?php echo htmlspecialchars($to_dt); ?>" size="12" />
            <input type="button" value="Search" onclick="this.form.action='ProductionRequestList.php?Action=search';this.form.submit();" />
            <?php if (nz($privRow['PRIV_ADD'], 'N') === 'Y'): ?>
                <a href="ProductionRequest.php?Act=Insert" target="_blank"><b>Add New</b></a>
            <?php endif; ?>
            <input type="button" onclick="testmsg('tabla');" value="Export to Excel!" />
        </td>
    </tr>
    <tr>
        <td align="center" colspan="4" style="padding-bottom:10px;">
            <b>Contract No</b> <input type="text" name="txtcontractno" value="<?php echo htmlspecialchars($contractno); ?>" size="15" />
            <input type="button" value="Search" onclick="this.form.action='ProductionRequestList.php?Action=searchcontractno';this.form.submit();" />
            <b>Req No</b> <input type="text" name="txtid" value="<?php echo htmlspecialchars($reqid); ?>" size="12" />
            <input type="button" value="Search" onclick="this.form.action='ProductionRequestList.php?Action=searchreqno';this.form.submit();" />
            <b>Item Name</b> <input type="text" name="txtname" value="<?php echo htmlspecialchars($reqname); ?>" size="18" />
            <input type="button" value="Search" onclick="this.form.action='ProductionRequestList.php?Action=searchname';this.form.submit();" />
            <input type="button" value="Refresh" onclick="window.location.href='ProductionRequestList.php';" />
        </td>
    </tr>
</table>

<table width="100%" border="1" align="center" id="tabla" style="border-collapse:collapse; margin-top:20px;">
    <tr bgcolor="#E0E0E0">
        <th>PRINT</th><th>REQ NO.</th><th>MATERIAL NAME</th><th>CONTRACT NO</th><th>ALLOCATED QTY</th><th>BALANCE QTY</th><th>REQUESTED QTY</th><th>DATE</th><th>STATUS</th><th>MONTH-YEAR</th><th>REMARKS</th><th>ACTION</th>
        <?php if ($_SESSION['UID'] === 'AFROZ'): ?>
            <th>DELETE</th><th>APPROVE</th><th>DECLINE</th>
        <?php endif; ?>
    </tr>
    <?php if (count($rows) > 0): foreach ($rows as $row): 
        $dcolor = ($row['STATUS'] == 'Approved') ? 'green' : (($row['STATUS'] == 'Declined') ? 'red' : 'black');
    ?>
    <tr>
        <td align="center"><a target="_blank" href="ProductionRequestprint.php?ID=<?php echo $row['PRNO']; ?>">PRINT</a></td>
        <td align="center"><?php echo $row['PRNO']; ?></td>
        <td><?php echo $row['MATERIALNAME']; ?></td>
        <td align="center"><?php echo $row['CONTRACTNO']; ?></td>
        <td align="center"><b><?php echo $row['ALLOCATED_QTY']; ?></b></td>
        <td align="center"><b><?php echo $row['BALANCE_QTY']; ?></b></td>
        <td align="center"><?php echo $row['REQUESTEDQTY']; ?></td>
        <td align="center"><?php echo $row['DATEENTER']; ?></td>
        <td align="center" style="color:<?php echo $dcolor; ?>; font-weight:bold;"><?php echo $row['STATUS']; ?></td>
        <td align="center"><?php echo $row['MONTH'].'-'.$row['YEAR']; ?></td>
        <td><?php echo $row['REMARKS']; ?></td>
        <td align="center">
            <?php if (nz($privRow['PRIV_MOD'], 'N') === 'Y'): ?>
                <a href="productionrequestedit.php?id=<?php echo $row['ID']; ?>&contractno=<?php echo urlencode($row['CONTRACTNO']); ?>&name=<?php echo urlencode($row['MATERIALNAME']); ?>&qty=<?php echo $row['REQUESTEDQTY']; ?>&balance=<?php echo $row['BALANCE_QTY']; ?>">Edit</a>
            <?php endif; ?>
        </td>
        <?php if ($_SESSION['UID'] === 'AFROZ'): ?>
            <td align="center"><a href="productionrequestdelete.php?id=<?php echo $row['ID']; ?>" onclick="return confirm('Delete?');">Delete</a></td>
            <td align="center"><a href="productionrequestapprove.php?id=<?php echo $row['ID']; ?>&Action=Approve">Approve</a></td>
            <td align="center"><a href="productionrequestapprove.php?id=<?php echo $row['ID']; ?>&Action=Decline&qty=<?php echo $row['REQUESTEDQTY']; ?>">Decline</a></td>
        <?php endif; ?>
    </tr>
    <?php endforeach; else: ?>
    <tr><td colspan="15" align="center">No record found.</td></tr>
    <?php endif; ?>
</table>

<div align="center" style="margin:20px;">
    <?php if ($currentPage > 1): ?>
        <a href="ProductionRequestList.php?<?php echo $qs; ?>&page=<?php echo $currentPage-1; ?>"><b><< Previous</b></a>&nbsp;&nbsp;
    <?php endif; ?>
    <?php if ($hasMore): ?>
        <a href="ProductionRequestList.php?<?php echo $qs; ?>&page=<?php echo $currentPage+1; ?>"><b>Next >></b></a>
    <?php endif; ?>
</div>
</form>
</body>
</html>
