<?php
session_start();
if (!isset($_SESSION['UID'])) {
    header("Location: index.php");
    exit();
}
$username = $_SESSION['UID'];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>ECF - Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: Arial, Helvetica, sans-serif;
            background-color: #EFEFEF;
            color: #333;
        }

        /* Top Info Section */
        .top-section {
            background-color: #FFFFFF;
            padding: 12px 20px 8px 20px;
            font-size: 14px;
            border-bottom: 1px solid #CCCCCC;
        }

        .top-line {
            margin-bottom: 4px;
            font-size: 14px;
        }

        .logout-line {
            font-size: 14px;
        }

        .logout-line a {
            color: #0000FF;
            text-decoration: none;
            font-weight: bold;
        }

        .logout-line a:hover {
            text-decoration: underline;
        }

        /* Dashboard Header Bar */
        .header-bar {
            background: linear-gradient(to bottom, #1a337e, #1a2d6f);
            color: #FFFFFF;
            text-align: center;
            padding: 14px 0;
            font-size: 28px;
            font-weight: bold;
            letter-spacing: 3px;
            text-transform: uppercase;
        }

        /* Main Container */
        .main-container {
            padding: 20px 10px;
            display: flex;
            justify-content: center;
            gap: 10px;
            flex-wrap: wrap;
            background-color: #EFEFEF;
        }

        /* Column Wrapper */
        .menu-column {
            width: 270px;
            background-color: #FFFFFF;
        }

        /* Column Header */
        .col-header {
            text-align: center;
            color: #FF0000;
            font-weight: bold;
            font-size: 15px;
            padding: 10px 5px;
            border-top: 3px solid #00CCCC;
            border-bottom: 1px solid #CCCCCC;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Menu Table */
        .menu-table {
            width: 100%;
            border-collapse: collapse;
            border-left: 1px solid #CCCCCC;
            border-right: 1px solid #CCCCCC;
            border-bottom: 1px solid #CCCCCC;
        }

        .menu-table tr {
            height: 30px;
        }

        .menu-table td {
            border-right: 1px solid #CCCCCC;
            border-bottom: 1px solid #E5E5E5;
            padding: 0;
            vertical-align: middle;
        }

        .menu-table tr:last-child td {
            border-bottom: 1px solid #CCCCCC;
        }

        /* Number Box Cell */
        .num-box {
            background-color: #333333;
            color: #FFFFFF;
            width: 45px;
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            border-right: 1px solid #CCCCCC;
        }

        /* Link Cell */
        .link-cell {
            text-align: center;
            background-color: #FFFFFF;
            padding: 0 8px;
            font-size: 14px;
        }

        .link-cell a {
            color: #1a337e;
            text-decoration: none;
            font-weight: normal;
            display: block;
            padding: 6px 0;
        }

        .link-cell a:hover {
            color: #FF0000;
            text-decoration: underline;
        }

        /* Section Labels (REORDER LEVEL, PETTY CASH, etc.) */
        .section-label {
            color: #FF0000;
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            padding: 10px 5px 7px 5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 10px;
            border-top: 3px solid #00CCCC;
        }

        /* Footer */
        .footer {
            text-align: center;
            font-size: 13px;
            color: #666;
            padding: 30px 20px;
            background-color: #EFEFEF;
        }

    </style>
</head>
<body>

    <!-- Top Info Section -->
    <div class="top-section">
        <div class="top-line">Welcome To Engineering Carpentry Factory</div>
        <div class="logout-line">
            <a href="logout.php">Log Out</a> User:<?php echo htmlspecialchars($username); ?>
        </div>
    </div>

    <!-- Dashboard Header -->
    <div class="header-bar">DASHBOARD</div>

    <!-- Main Menu Container -->
    <div class="main-container">

        <!-- COLUMN 1: MASTERS -->
        <div class="menu-column">
            <div class="col-header">MASTERS</div>
            <table class="menu-table">
                <tr><td class="num-box">2</td><td class="link-cell"><a href="USER.php">USER</a></td></tr>
                <tr><td class="num-box">3</td><td class="link-cell"><a href="VENDOR.php">VENDOR</a></td></tr>
                <tr><td class="num-box">4</td><td class="link-cell"><a href="STOCK.php">STOCK</a></td></tr>
                <tr><td class="num-box">5</td><td class="link-cell"><a href="STOCK_UPDATE.php">STOCK UPDATE PROCESS</a></td></tr>
                <tr><td class="num-box">6</td><td class="link-cell"><a href="STOCK_MOVEMENT.php">STOCK MOVEMENT</a></td></tr>
            </table>
        </div>

        <!-- COLUMN 2: DOCUMENTS -->
        <div class="menu-column">
            <div class="col-header">DOCUMENTS</div>
            <table class="menu-table">
                <tr><td class="num-box">2</td><td class="link-cell"><a href="SERVICE_ORDER.php">Service Order</a></td></tr>
                <tr><td class="num-box">3</td><td class="link-cell"><a href="PURCHASE_REQUEST.php">Request For Purchase</a></td></tr>
                <tr><td class="num-box">4</td><td class="link-cell"><a href="OPEN_SHOP.php">Open new shop</a></td></tr>
                <tr><td class="num-box">5</td><td class="link-cell"><a href="PURCHASE_ORDER.php">Purchase Order</a></td></tr>
                <tr><td class="num-box">6</td><td class="link-cell"><a href="GOODS_RECEIPT.php">Goods Receipt</a></td></tr>
                <tr><td class="num-box">7</td><td class="link-cell"><a href="STORE_REQUEST.php">Store Request</a></td></tr>
                <tr><td class="num-box">8</td><td class="link-cell"><a href="GOODS_ISSUE.php">Goods Issue</a></td></tr>
                <tr><td class="num-box">9</td><td class="link-cell"><a href="GOODS_RETURN.php">Goods Return</a></td></tr>
                <tr><td class="num-box">10</td><td class="link-cell"><a href="INVOICE.php">Invoice</a></td></tr>
                <tr><td class="num-box">11</td><td class="link-cell"><a href="TRANSFER.php">Transfer</a></td></tr>
                <tr><td class="num-box">12</td><td class="link-cell"><a href="TOOLS_REQUEST.php">Tools Request</a></td></tr>
                <tr><td class="num-box">13</td><td class="link-cell"><a href="TOOLS_TO_ISSUE.php">Tools Request TO Issue</a></td></tr>
                <tr><td class="num-box">14</td><td class="link-cell"><a href="TOOLS_RETURN.php">Tools Issued TO Return</a></td></tr>
                <tr><td class="num-box">15</td><td class="link-cell"><a href="TOOLS_CIVIL.php">Tools Issued TO Civil ID</a></td></tr>
                <tr><td class="num-box">16</td><td class="link-cell"><a href="CLEAN_CHIT.php">Clean Chit</a></td></tr>
                <tr><td class="num-box">17</td><td class="link-cell"><a href="PRODUCTIONREQUESTLIST.php">Production Request</a></td></tr>

            </table>
        </div>

        <!-- COLUMN 3: REPORTS -->
        <div class="menu-column">
            <div class="col-header">REPORTS</div>
            <table class="menu-table">
                <tr><td class="num-box">2</td><td class="link-cell"><a href="REP_SALES_MOVE.php">Sales Movement Report</a></td></tr>
                <tr><td class="num-box">3</td><td class="link-cell"><a href="REP_STOCK_MOVE.php">Stock Movement Report</a></td></tr>
                <tr><td class="num-box">4</td><td class="link-cell"><a href="REP_PERIOD_MOVE.php">Periodic Movement Report</a></td></tr>
                <tr><td class="num-box">5</td><td class="link-cell"><a href="REP_STOCK_LIST.php">Stock Listing</a></td></tr>
                <tr><td class="num-box">6</td><td class="link-cell"><a href="REP_RESERVED.php">List for Reserved Stock Qty</a></td></tr>
                <tr><td class="num-box">7</td><td class="link-cell"><a href="REP_SALESMAN.php">SalesMan Report</a></td></tr>
                <tr><td class="num-box">8</td><td class="link-cell"><a href="REP_SALES_PART.php">SalesMan Report By Part No</a></td></tr>
                <tr><td class="num-box">9</td><td class="link-cell"><a href="REP_SALES_CUST.php">SalesMan Customer Report</a></td></tr>
                <tr><td class="num-box">10</td><td class="link-cell"><a href="REP_PENDING_INV.php">Pending Invoice Report</a></td></tr>
                <tr><td class="num-box">11</td><td class="link-cell"><a href="REP_ITEMS_COUNT.php">Total Items Count</a></td></tr>
                <tr><td class="num-box">12</td><td class="link-cell"><a href="REP_STORE_STOCK.php">Store Stock Report</a></td></tr>
                <tr><td class="num-box">13</td><td class="link-cell"><a href="REP_STORE_INV.php">Store Invoice Report</a></td></tr>
                <tr><td class="num-box">14</td><td class="link-cell"><a href="REP_GRV.php">GRV Report</a></td></tr>
                <tr><td class="num-box">15</td><td class="link-cell"><a href="REP_GRV_LIST.php">GRV Report List</a></td></tr>
                <tr><td class="num-box">16</td><td class="link-cell"><a href="REP_GRV_DATE.php">GRV Report List BY DATE</a></td></tr>
                <tr><td class="num-box">17</td><td class="link-cell"><a href="REP_GRV_LC.php">GRV Report LC</a></td></tr>
                <tr><td class="num-box">18</td><td class="link-cell"><a href="REP_GRV_BL.php">GRV Report BL</a></td></tr>
                <tr><td class="num-box">19</td><td class="link-cell"><a href="REP_GRV_NO.php">GRV Report By GRV NO</a></td></tr>
                <tr><td class="num-box">20</td><td class="link-cell"><a href="REP_CONTRACT.php">CONTRACT LISTING</a></td></tr>
                <tr><td class="num-box">21</td><td class="link-cell"><a href="REP_TRANSFER.php">Stock Transfer Report</a></td></tr>
            </table>
        </div>

        <!-- COLUMN 4: GENERIC REPORT -->
        <div class="menu-column">
            <div class="col-header">Generic Report</div>
            <table class="menu-table">
                <tr><td class="num-box">17</td><td class="link-cell"><a href="SEARCH_GIV.php">Search GIV</a></td></tr>
                <tr><td class="num-box">17</td><td class="link-cell"><a href="SEARCH_RETURN.php">Search Goods Return</a></td></tr>
                <tr><td class="num-box">18</td><td class="link-cell"><a href="SEARCH_GRV.php">Search Goods Receipt</a></td></tr>
                <tr><td class="num-box">19</td><td class="link-cell"><a href="SEARCH_PO.php">Search Purchase Order</a></td></tr>
                <tr><td class="num-box">20</td><td class="link-cell"><a href="SEARCH_INV.php">Search Invoice</a></td></tr>
            </table>

            <div class="section-label">REORDER LEVEL</div>
            <table class="menu-table">
                <tr><td class="num-box">23</td><td class="link-cell"><a href="REORDER.php">Reorder Level</a></td></tr>
            </table>

            <div class="section-label">PETTY CASH</div>
            <table class="menu-table">
                <tr><td class="num-box">24</td><td class="link-cell"><a href="PUR_LIST.php">Purchase List</a></td></tr>
                <tr><td class="num-box">25</td><td class="link-cell"><a href="INV_LIST.php">Invoice List</a></td></tr>
                <tr><td class="num-box">26</td><td class="link-cell"><a href="PERS_ACC_LIST.php">Personal Account List</a></td></tr>
            </table>

            <div class="section-label">FINANCIAL GRV</div>
            <table class="menu-table">
                <tr><td class="num-box">27</td><td class="link-cell"><a href="FIN_GRV.php">Financial Grv</a></td></tr>
            </table>

            <div class="section-label">CONTRACT CALCULATION</div>
            <div style="text-align: center; color: #FF0000; font-size: 12px; padding: 3px 5px; text-transform: uppercase;">CONTRACT CALCULATION - PVC</div>
            <table class="menu-table">
                <tr><td class="num-box">29</td><td class="link-cell"><a href="CALC_PVC.php">CONTRACT CALCULATION - PVC</a></td></tr>
            </table>

            <div style="text-align: center; color: #FF0000; font-size: 12px; padding: 3px 5px; text-transform: uppercase; margin-top: 5px;">CONTRACT CALCULATION - Cupboard</div>
            <table class="menu-table">
                <tr><td class="num-box">30</td><td class="link-cell"><a href="CALC_CUP.php">CONTRACT CALCULATION - Cupboard</a></td></tr>
            </table>
        </div>

    </div>

    <!-- Footer -->
    <div class="footer">
        Copyright 2020 ECF. Developed By Raju Tamang
    </div>

</body>
</html>
