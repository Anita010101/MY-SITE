<?php
session_start();
require_once 'connection.php';

function h($value) {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

$action      = $_REQUEST['Action'] ?? 'All';
$fromDate    = trim($_REQUEST['from_date'] ?? '');
$uptoDate    = trim($_REQUEST['upto_date'] ?? '');
$givNo       = trim($_REQUEST['giv_no'] ?? '');
$contractNo  = trim($_REQUEST['contract_no'] ?? '');
$deliveryNo  = trim($_REQUEST['delivery_no'] ?? '');
$export      = isset($_REQUEST['export']) ? 1 : 0;
$page        = max(1, (int)($_REQUEST['page'] ?? 1));
$limit       = 50;
$offset      = ($page - 1) * $limit;

$addNewPage = 'GOODS_ISSUE_FORM.php';
$logoPath   = 'images/ecf_header.jpg';

// ========== COUNT QUERY (fast, no data loading) ==========
$countSql = "
    SELECT COUNT(*) AS total
    FROM ECF_GOODS_ISSUE
    WHERE 1 = 1
";
$countParams = [];

if ($fromDate !== '') {
    $countSql .= " AND TRUNC(GIV_DATE) >= TO_DATE(:from_date, 'DD/MM/YYYY')";
    $countParams[':from_date'] = $fromDate;
}
if ($uptoDate !== '') {
    $countSql .= " AND TRUNC(GIV_DATE) <= TO_DATE(:upto_date, 'DD/MM/YYYY')";
    $countParams[':upto_date'] = $uptoDate;
}
if ($givNo !== '') {
    $countSql .= " AND UPPER(GIV_NO) LIKE UPPER(:giv_no)";
    $countParams[':giv_no'] = '%' . $givNo . '%';
}
if ($contractNo !== '') {
    $countSql .= " AND UPPER(CONTRACT_NO) LIKE UPPER(:contract_no)";
    $countParams[':contract_no'] = '%' . $contractNo . '%';
}
if ($deliveryNo !== '') {
    $countSql .= " AND UPPER(GIV_DELV_NO) LIKE UPPER(:delivery_no)";
    $countParams[':delivery_no'] = '%' . $deliveryNo . '%';
}

$countStmt = $conn->prepare($countSql);
$countStmt->execute($countParams);
$totalRows = (int)$countStmt->fetchColumn();
$totalPages = max(1, ceil($totalRows / $limit));

// ========== DATA QUERY (only 50 rows) ==========
$sql = "
    SELECT
        GIV_NO,
        NVL(CONTRACT_NO, '') AS CONTRACT_NO,
        NVL(GIV_DELV_NO, '') AS GIV_DELV_NO,
        NVL(GIV_ISS_QTY, 0) AS GIV_ISS_QTY,
        NVL(GIV_ITEM_CODE, '') AS GIV_ITEM_CODE,
        NVL(GIV_ITEM_DESC, '') AS GIV_ITEM_DESC,
        TO_CHAR(GIV_DATE, 'DD/MM/YYYY') AS GIV_DATE
    FROM ECF_GOODS_ISSUE
    WHERE 1 = 1
";
$params = [];

if ($fromDate !== '') {
    $sql .= " AND TRUNC(GIV_DATE) >= TO_DATE(:from_date, 'DD/MM/YYYY')";
    $params[':from_date'] = $fromDate;
}
if ($uptoDate !== '') {
    $sql .= " AND TRUNC(GIV_DATE) <= TO_DATE(:upto_date, 'DD/MM/YYYY')";
    $params[':upto_date'] = $uptoDate;
}
if ($givNo !== '') {
    $sql .= " AND UPPER(GIV_NO) LIKE UPPER(:giv_no)";
    $params[':giv_no'] = '%' . $givNo . '%';
}
if ($contractNo !== '') {
    $sql .= " AND UPPER(CONTRACT_NO) LIKE UPPER(:contract_no)";
    $params[':contract_no'] = '%' . $contractNo . '%';
}
if ($deliveryNo !== '') {
    $sql .= " AND UPPER(GIV_DELV_NO) LIKE UPPER(:delivery_no)";
    $params[':delivery_no'] = '%' . $deliveryNo . '%';
}

$sql .= " ORDER BY TO_NUMBER(GIV_NO) DESC NULLS LAST";

// Add pagination using Oracle ROW_NUMBER or OFFSET/FETCH
$sql = "SELECT * FROM (
            SELECT a.*, ROWNUM rnum FROM (
                $sql
            ) a WHERE ROWNUM <= :max_row
        ) WHERE rnum > :min_row";
$params[':max_row'] = $offset + $limit;
$params[':min_row'] = $offset;

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ========== EXPORT (all rows, no pagination) ==========
if ($export) {
    // Re-run query without pagination for export
    $exportSql = "
        SELECT
            GIV_NO,
            NVL(CONTRACT_NO, '') AS CONTRACT_NO,
            NVL(GIV_DELV_NO, '') AS GIV_DELV_NO,
            NVL(GIV_ISS_QTY, 0) AS GIV_ISS_QTY,
            NVL(GIV_ITEM_CODE, '') AS GIV_ITEM_CODE,
            NVL(GIV_ITEM_DESC, '') AS GIV_ITEM_DESC,
            TO_CHAR(GIV_DATE, 'DD/MM/YYYY') AS GIV_DATE
        FROM ECF_GOODS_ISSUE
        WHERE 1 = 1
    ";
    $exportParams = [];
    if ($fromDate !== '') { $exportSql .= " AND TRUNC(GIV_DATE) >= TO_DATE(:from_date, 'DD/MM/YYYY')"; $exportParams[':from_date'] = $fromDate; }
    if ($uptoDate !== '') { $exportSql .= " AND TRUNC(GIV_DATE) <= TO_DATE(:upto_date, 'DD/MM/YYYY')"; $exportParams[':upto_date'] = $uptoDate; }
    if ($givNo !== '') { $exportSql .= " AND UPPER(GIV_NO) LIKE UPPER(:giv_no)"; $exportParams[':giv_no'] = '%' . $givNo . '%'; }
    if ($contractNo !== '') { $exportSql .= " AND UPPER(CONTRACT_NO) LIKE UPPER(:contract_no)"; $exportParams[':contract_no'] = '%' . $contractNo . '%'; }
    if ($deliveryNo !== '') { $exportSql .= " AND UPPER(GIV_DELV_NO) LIKE UPPER(:delivery_no)"; $exportParams[':delivery_no'] = '%' . $deliveryNo . '%'; }
    $exportSql .= " ORDER BY TO_NUMBER(GIV_NO) DESC NULLS LAST";

    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=goods_issue_export.xls");
    header("Pragma: no-cache");
    header("Expires: 0");
    ?>
    <table border="1">
        <tr>
            <th>GIV NO.</th>
            <th>Contract NO.</th>
            <th>Delv NO.</th>
            <th>Issue Qty.</th>
            <th>Item Code</th>
            <th>Item Desc</th>
            <th>Date</th>
            <th>Location</th>
        </tr>
        <?php
        $exportStmt = $conn->prepare($exportSql);
        $exportStmt->execute($exportParams);
        while ($r = $exportStmt->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td><?php echo h($r['GIV_NO']); ?></td>
                <td><?php echo h($r['CONTRACT_NO']); ?></td>
                <td><?php echo h($r['GIV_DELV_NO']); ?></td>
                <td><?php echo h($r['GIV_ISS_QTY']); ?></td>
                <td><?php echo h($r['GIV_ITEM_CODE']); ?></td>
                <td><?php echo h($r['GIV_ITEM_DESC']); ?></td>
                <td><?php echo h($r['GIV_DATE']); ?></td>
                <td>OLD STORE</td>
            </tr>
        <?php endwhile; ?>
    </table>
    <?php
    exit;
}

// Build query string for pagination links (preserve filters)
$queryParts = [];
foreach (['from_date','upto_date','giv_no','contract_no','delivery_no'] as $k) {
    if (!empty($_REQUEST[$k])) {
        $queryParts[] = $k . '=' . urlencode($_REQUEST[$k]);
    }
}
$queryString = implode('&', $queryParts);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>GOODS ISSUE</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body{
            background:#ffffff;
            font-family:"Times New Roman", Times, serif;
            color:#000;
            padding: 10px;
        }
        .header-box{
            text-align:center;
            margin-bottom:15px;
        }
        .header-box img{
            max-width:820px;
            width:90%;
            height:auto;
        }
        .title-bar{
            width:100%;
            background:#9a9a9a;
            color:#000;
            text-align:center;
            font-weight:bold;
            font-size:22px;
            line-height:34px;
            height:34px;
            margin-bottom:10px;
        }
        .search-area{
            width:100%;
            text-align:center;
            font-size:20px;
            margin-bottom:10px;
        }
        .search-line{
            margin:4px 0;
            display:flex;
            flex-wrap:wrap;
            justify-content:center;
            align-items:center;
            gap:5px;
        }
        .search-area label{
            font-size:20px;
        }
        .search-area input[type="text"]{
            width:170px;
            height:28px;
            font-size:18px;
            font-family:"Times New Roman", Times, serif;
            border:1px solid #888;
            padding:2px 6px;
        }
        .btn-small, .link-btn{
            height:30px;
            font-size:15px;
            border:1px solid #777;
            background:#efefef;
            cursor:pointer;
            padding:0 10px;
            text-decoration:none;
            color:#000;
            display:inline-flex;
            align-items:center;
        }
        .add-link{
            color:#5a2d82;
            font-size:17px;
            text-decoration:underline;
            margin:0 4px;
        }
        .table-wrap{
            width:100%;
            overflow-x:auto;
        }
        table.grid{
            width:100%;
            border-collapse:collapse;
            table-layout:auto;
        }
        table.grid th{
            border:1px solid #bdbdbd;
            background:#ffffff;
            color:#000;
            font-size:16px;
            padding:4px 8px;
            text-align:center;
            white-space:nowrap;
        }
        table.grid td{
            border:1px solid #d0d0d0;
            font-size:16px;
            padding:3px 8px;
            white-space:nowrap;
        }
        /* Auto-fit: let columns shrink/expand naturally */
        table.grid th:first-child, table.grid td:first-child { min-width:80px; }
        table.grid th:nth-child(2), table.grid td:nth-child(2) { min-width:80px; }
        table.grid th:nth-child(3), table.grid td:nth-child(3) { min-width:70px; }
        table.grid th:nth-child(4), table.grid td:nth-child(4) { min-width:60px; }
        table.grid th:nth-child(5), table.grid td:nth-child(5) { min-width:90px; }
        table.grid th:nth-child(6), table.grid td:nth-child(6) { min-width:150px; }
        table.grid th:nth-child(7), table.grid td:nth-child(7) { min-width:80px; }
        table.grid th:nth-child(8), table.grid td:nth-child(8) { min-width:80px; }
        .giv-link{ color:#0000cc; text-decoration:underline; }
        .center{text-align:center;}
        .right{text-align:right;}

        /* Pagination */
        .pagination{
            text-align:center;
            margin:15px 0;
            font-size:16px;
        }
        .pagination a, .pagination span{
            display:inline-block;
            padding:5px 12px;
            margin:0 2px;
            border:1px solid #999;
            text-decoration:none;
            color:#000;
            background:#f0f0f0;
        }
        .pagination .active{
            background:#999;
            color:#fff;
            font-weight:bold;
        }
        .pagination .disabled{
            color:#aaa;
            border-color:#ccc;
            background:#eee;
        }
        .page-info{
            text-align:center;
            font-size:14px;
            margin-bottom:5px;
            color:#555;
        }
    </style>
</head>
<body>

<div class="header-box">
    <img src="<?php echo h($logoPath); ?>"
         alt="ENGINEERING CARPENTRY FACTORY"
         onerror="this.style.display='none';document.getElementById('headerFallback').style.display='block';">
    <div id="headerFallback" style="display:none;font-size:36px;font-weight:bold;">ENGINEERING CARPENTRY FACTORY</div>
</div>

<div class="title-bar">GOODS ISSUE</div>

<form method="get" action="GOODS_ISSUE.php">
    <input type="hidden" name="Action" value="All">

    <div class="search-area">
        <div class="search-line">
            <label>From Date</label>
            <input type="text" name="from_date" value="<?php echo h($fromDate); ?>">
            <label>Upto Date</label>
            <input type="text" name="upto_date" value="<?php echo h($uptoDate); ?>">
            <button type="submit" class="btn-small">Search</button>
            <a class="add-link" href="<?php echo h($addNewPage); ?>">Add New</a>
            <a class="link-btn" href="GOODS_ISSUE.php?Action=All&from_date=<?php echo urlencode($fromDate); ?>&upto_date=<?php echo urlencode($uptoDate); ?>&giv_no=<?php echo urlencode($givNo); ?>&contract_no=<?php echo urlencode($contractNo); ?>&delivery_no=<?php echo urlencode($deliveryNo); ?>&export=1">Export to Excel</a>
        </div>
        <div class="search-line">
            <label>Giv No</label>
            <input type="text" name="giv_no" value="<?php echo h($givNo); ?>">
            <button type="submit" class="btn-small">Search</button>
            <label>Contract No</label>
            <input type="text" name="contract_no" value="<?php echo h($contractNo); ?>">
            <button type="submit" class="btn-small">Search</button>
        </div>
        <div class="search-line">
            <label>Delivery No</label>
            <input type="text" name="delivery_no" value="<?php echo h($deliveryNo); ?>" style="width:170px;">
            <button type="submit" class="btn-small">Search</button>
        </div>
    </div>
</form>

<div class="page-info">
    Showing page <?php echo $page; ?> of <?php echo $totalPages; ?> | Total records: <?php echo $totalRows; ?>
</div>

<div class="table-wrap">
    <table class="grid">
        <tr>
            <th>GIV NO.</th>
            <th>Contract NO.</th>
            <th>Delv NO.</th>
            <th>Issue Qty.</th>
            <th>Item Code</th>
            <th>Item Desc</th>
            <th>Date</th>
            <th>Location</th>
        </tr>
        <?php if (count($rows) > 0): ?>
            <?php foreach ($rows as $r): ?>
                <tr>
                    <td>
                        <a class="giv-link" href="<?php echo h($addNewPage); ?>?GIVNO=<?php echo urlencode($r['GIV_NO']); ?>">
                            <?php echo h($r['GIV_NO']); ?>
                        </a>
                    </td>
                    <td><?php echo h($r['CONTRACT_NO']); ?></td>
                    <td><?php echo h($r['GIV_DELV_NO']); ?></td>
                    <td class="right"><?php echo h($r['GIV_ISS_QTY']); ?></td>
                    <td><?php echo h($r['GIV_ITEM_CODE']); ?></td>
                    <td><?php echo h($r['GIV_ITEM_DESC']); ?></td>
                    <td class="center"><?php echo h($r['GIV_DATE']); ?></td>
                    <td>OLD STORE</td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="8" class="center">No records found</td></tr>
        <?php endif; ?>
    </table>
</div>

<!-- Pagination -->
<?php if ($totalPages > 1): ?>
<div class="pagination">
    <?php
    // Build base URL with filters
    $baseUrl = 'GOODS_ISSUE.php?Action=All' . ($queryString ? '&' . $queryString : '');

    // Previous button
    if ($page > 1) {
        echo '<a href="' . $baseUrl . '&page=' . ($page - 1) . '">&laquo; Prev</a>';
    } else {
        echo '<span class="disabled">&laquo; Prev</span>';
    }

    // Page numbers
    $startPage = max(1, $page - 4);
    $endPage = min($totalPages, $page + 4);
    if ($startPage > 1) {
        echo '<a href="' . $baseUrl . '&page=1">1</a>';
        if ($startPage > 2) echo '<span>...</span>';
    }
    for ($i = $startPage; $i <= $endPage; $i++) {
        if ($i == $page) {
            echo '<span class="active">' . $i . '</span>';
        } else {
            echo '<a href="' . $baseUrl . '&page=' . $i . '">' . $i . '</a>';
        }
    }
    if ($endPage < $totalPages) {
        if ($endPage < $totalPages - 1) echo '<span>...</span>';
        echo '<a href="' . $baseUrl . '&page=' . $totalPages . '">' . $totalPages . '</a>';
    }

    // Next button
    if ($page < $totalPages) {
        echo '<a href="' . $baseUrl . '&page=' . ($page + 1) . '">Next &raquo;</a>';
    } else {
        echo '<span class="disabled">Next &raquo;</span>';
    }
    ?>
</div>
<?php endif; ?>

</body>
</html>
