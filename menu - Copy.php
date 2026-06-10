<?php
require_once "connection.php";
?>
<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Dashboard | ECF</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/adminpro-custon-icon.css">
    <link rel="stylesheet" href="css/meanmenu.min.css">
    <link rel="stylesheet" href="css/jquery.mCustomScrollbar.min.css">
    <link rel="stylesheet" href="css/animate.css">
    <link rel="stylesheet" href="style1.css">
    <link rel="stylesheet" href="css/responsive.css">

    <style>
        body { background-color: #2b303b !important; }
        .welcome-wrapper { background: #fff !important; color: #333 !important; }
        .headingCenter { 
            text-align: center; 
            font-size: 14px; 
            font-weight: bold; 
            color: #ff5e5e !important; 
            display: block; 
            padding: 15px 0; 
            text-transform: uppercase;
            border-bottom: 2px solid #f0f0f0;
            margin-bottom: 10px;
        }
        .adminpro-message-list { background: #fff; padding: 15px; margin-bottom: 20px; }
        .adminpro-message-list ul { padding: 0; margin: 0; }
        .adminpro-message-list ul li {
            list-style: none;
            padding: 8px 5px;
            display: flex;
            align-items: center;
        }
        .message-info { flex: 1; }
        .message-info a { 
            color: #000 !important; 
            text-decoration: none !important; 
            font-size: 13px; 
        }
        .message-info a:hover { color: #00c5fb !important; }
        .message-serial { 
            display: inline-block; 
            width: 28px; 
            height: 28px; 
            line-height: 28px; 
            text-align: center; 
            border-radius: 50%; 
            margin-right: 10px; 
            font-size: 11px;
            font-weight: bold;
            color: #fff !important;
            flex-shrink: 0;
        }
        .message-cl-one  { background: #00c5fb; }
        .message-cl-two  { background: #f3c200; }
        .message-cl-three{ background: #fe5e5e; }
        .message-cl-four { background: #3fe259; }
        .message-cl-five { background: #2f323e; }
        .header-top-area { background: #0d148c; padding: 10px 0; position: fixed; width: 100%; top: 0; z-index: 999; }
        .footer-copyright-area { background: #fff; color: #333; padding: 15px; margin-top: 20px; text-align: center; }
        .sub-heading {
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            color: #ff5e5e !important;
            display: block;
            padding: 10px 0 5px 0;
            border-top: 2px solid #f0f0f0;
            margin-top: 5px;
        }
    </style>
</head>

<body class="materialdesign">
<div class="wrapper-pro">
    <div class="content-inner-all">

        <!-- Header -->
        <div class="header-top-area">
            <div class="container-fluid">
                <h3 style="color:white; margin:0; text-align:center;">DASHBOARD</h3>
            </div>
        </div>

        <div class="welcome-adminpro-area" style="margin-top:70px">
            <div class="container-fluid">

                <!-- Welcome Bar -->
                <div class="welcome-wrapper shadow-reset" style="padding:15px; margin-bottom:20px;">
                    <div class="row">
                        <div class="col-lg-8">
                            <h2 style="margin:0; font-size:18px;">Welcome To Engineering Carpentry Factory</h2>
                        </div>
                        <div class="col-lg-4" style="text-align:right;">
                            <strong>User:</strong> <?php echo htmlspecialchars($_SESSION['UID'] ?? 'Guest'); ?> |
                            <a href="index.php" style="color:red; font-weight:bold;">Log Out</a>
                        </div>
                    </div>
                </div>

                <div class="row">

                    <!-- ====== MASTERS ====== -->
                    <div class="col-lg-3">
                        <div class="adminpro-message-list">
                            <span class="headingCenter">MASTERS</span>
                            <ul>
                                <li><span class="message-serial message-cl-two">1</span><span class="message-info"><a href="user.php">USER</a></span></li>
                                <li><span class="message-serial message-cl-three">2</span><span class="message-info"><a href="vendor.php">VENDOR</a></span></li>
                                <li><span class="message-serial message-cl-four">3</span><span class="message-info"><a href="COPY_OF_stk_mast.php">STOCK</a></span></li>
                                <li><span class="message-serial message-cl-five">4</span><span class="message-info"><a href="EOD_PROCESS.php" target="_blank">STOCK UPDATE PROCESS</a></span></li>
                                <li><span class="message-serial message-cl-one">5</span><span class="message-info"><a href="STK_MVMT.php" target="_blank">STOCK MOVEMENT</a></span></li>
                            </ul>
                        </div>
                    </div>

                    <!-- ====== DOCUMENTS (Service Order & Open new shop removed) ====== -->
                    <div class="col-lg-3">
                        <div class="adminpro-message-list">
                            <span class="headingCenter">DOCUMENTS</span>
                            <ul>
                                <li><span class="message-serial message-cl-two">1</span><span class="message-info"><a href="pend_pur_req.php" target="_blank">Request For Purchase</a></span></li>
                                <li><span class="message-serial message-cl-three">2</span><span class="message-info"><a href="pend_pur_ord.php" target="_blank">Purchase Order</a></span></li>
                                <li><span class="message-serial message-cl-four">3</span><span class="message-info"><a href="list_grv.php" target="_blank">Goods Receipt</a></span></li>
                                <li><span class="message-serial message-cl-five">4</span><span class="message-info"><a href="pend_store_req.php" target="_blank">Store Request</a></span></li>
                                <li><span class="message-serial message-cl-one">5</span><span class="message-info"><a href="TEST.php?Action=All" target="_blank">Goods Issue</a></span></li>
                                <li><span class="message-serial message-cl-two">6</span><span class="message-info"><a href="list_rv.php" target="_blank">Goods Return</a></span></li>
                                <li><span class="message-serial message-cl-three">7</span><span class="message-info"><a href="list_inv.php" target="_blank">Invoice</a></span></li>
                                <li><span class="message-serial message-cl-four">8</span><span class="message-info"><a href="list_trans.php" target="_blank">Transfer</a></span></li>
                                <li><span class="message-serial message-cl-five">9</span><span class="message-info"><a href="list_tools_req.php" target="_blank">Tools Request</a></span></li>
                                <li><span class="message-serial message-cl-one">10</span><span class="message-info"><a href="LIST_TOOLS_REQ_TO_ISSUE.php" target="_blank">Tools Request TO Issue</a></span></li>
                                <li><span class="message-serial message-cl-two">11</span><span class="message-info"><a href="LIST_TOOLS_REQ_TO_RETURN.php" target="_blank">Tools Issued TO Return</a></span></li>
                                <li><span class="message-serial message-cl-three">12</span><span class="message-info"><a href="Tools_issued_To_CivilID.php" target="_blank">Tools Issued TO Civil ID</a></span></li>
                                <li><span class="message-serial message-cl-four">13</span><span class="message-info"><a href="cleanchit.php" target="_blank">Clean Chit</a></span></li>
                                <li><span class="message-serial message-cl-five">14</span><span class="message-info"><a href="ProductionRequestList.php" target="_blank">Production Request</a></span></li>
                            </ul>
                        </div>
                    </div>

                    <!-- ====== REPORTS (Sales Movement, SalesMan x3, Pending Invoice removed) ====== -->
                    <div class="col-lg-3">
                        <div class="adminpro-message-list">
                            <span class="headingCenter">REPORTS</span>
                            <ul>
                                <li><span class="message-serial message-cl-two">1</span><span class="message-info"><a href="stock_movement_report.php" target="_blank">Stock Movement Report</a></span></li>
                                <li><span class="message-serial message-cl-three">2</span><span class="message-info"><a href="MovementReport.php" target="_blank">Periodic Movement Report</a></span></li>
                                <li><span class="message-serial message-cl-four">3</span><span class="message-info"><a href="stk_list.php" target="_blank">Stock Listing</a></span></li>
                                <li><span class="message-serial message-cl-five">4</span><span class="message-info"><a href="STK_REP_RFP.php" target="_blank">List for Reserved Stock Qty</a></span></li>
                                <li><span class="message-serial message-cl-one">5</span><span class="message-info"><a href="item_by_salesman_report.php" target="_blank">Total Items Count</a></span></li>
                                <li><span class="message-serial message-cl-two">6</span><span class="message-info"><a href="stk_listnew.php" target="_blank">Store Stock Report</a></span></li>
                                <li><span class="message-serial message-cl-three">7</span><span class="message-info"><a href="Salesinvoicereport.php" target="_blank">Store Invoice Report</a></span></li>
                                <li><span class="message-serial message-cl-four">8</span><span class="message-info"><a href="grv2.php" target="_blank">GRV Report</a></span></li>
                                <li><span class="message-serial message-cl-five">9</span><span class="message-info"><a href="GRV_Made_list.php" target="_blank">GRV Report List</a></span></li>
                                <li><span class="message-serial message-cl-one">10</span><span class="message-info"><a href="GRV_Made_list_date.php" target="_blank">GRV Report List BY DATE</a></span></li>
                                <li><span class="message-serial message-cl-two">11</span><span class="message-info"><a href="GRV2searchLC.php?Action=Search" target="_blank">GRV Report LC</a></span></li>
                                <li><span class="message-serial message-cl-three">12</span><span class="message-info"><a href="GRV2searchBL.php?Action=Search" target="_blank">GRV Report BL</a></span></li>
                                <li><span class="message-serial message-cl-four">13</span><span class="message-info"><a href="GRV2searchGRV.php?Action=Search" target="_blank">GRV Report By GRV NO</a></span></li>
                                <li><span class="message-serial message-cl-five">14</span><span class="message-info"><a href="list_contract.php?Action=Search" target="_blank">CONTRACT LISTING</a></span></li>
                                <li><span class="message-serial message-cl-one">15</span><span class="message-info"><a href="Transferreport.php" target="_blank">Stock Transfer Report</a></span></li>
                            </ul>
                        </div>
                    </div>

                    <!-- ====== GENERIC REPORT ====== -->
                    <div class="col-lg-3">
                        <div class="adminpro-message-list">
                            <span class="headingCenter">Generic Report</span>
                            <ul>
                                <li><span class="message-serial message-cl-two">1</span><span class="message-info"><a href="searchgiv.php" target="_blank">Search GIV</a></span></li>
                                <li><span class="message-serial message-cl-three">2</span><span class="message-info"><a href="searchgrv.php" target="_blank">Search Goods Return</a></span></li>
                                <li><span class="message-serial message-cl-four">3</span><span class="message-info"><a href="searchgoodsreceipt.php" target="_blank">Search Goods Receipt</a></span></li>
                                <li><span class="message-serial message-cl-five">4</span><span class="message-info"><a href="SearchPurchaseOrder.php" target="_blank">Search Purchase Order</a></span></li>
                                <li><span class="message-serial message-cl-one">5</span><span class="message-info"><a href="SearchInvoice.php" target="_blank">Search Invoice</a></span></li>
                                <li><span class="message-serial message-cl-two">6</span><span class="message-info"><a href="searchgoodsreceiptByvendor.php" target="_blank">Search Goods Receipt By Vendor</a></span></li>
                                <li><span class="message-serial message-cl-three">7</span><span class="message-info"><a href="SearchPurchaseRequest.php?Action=Search" target="_blank">Search Purchase Request</a></span></li>

                                <!-- REORDER LEVEL -->
                                <li><span class="sub-heading" style="width:100%">REORDER LEVEL</span></li>
                                <li><span class="message-serial message-cl-four">8</span><span class="message-info"><a href="reorderlevelnew.php" target="_blank">Reorder Level</a></span></li>

                                <!-- PETTY CASH -->
                                <li><span class="sub-heading" style="width:100%">PETTY CASH</span></li>
                                <li><span class="message-serial message-cl-five">9</span><span class="message-info"><a href="PettyCashList.php" target="_blank">Purchase List</a></span></li>
                                <li><span class="message-serial message-cl-one">10</span><span class="message-info"><a href="INVOICELISTNEW.php" target="_blank">Invoice List</a></span></li>
                                <li><span class="message-serial message-cl-two">11</span><span class="message-info"><a href="PERSONALACCOUNT.php" target="_blank">Personal Account List</a></span></li>

                                <!-- FINANCIAL GRV -->
                                <li><span class="sub-heading" style="width:100%">FINANCIAL GRV</span></li>
                                <li><span class="message-serial message-cl-three">12</span><span class="message-info"><a href="financialgrv.php" target="_blank">Financial Grv</a></span></li>

                                <!-- CONTRACT CALCULATION -->
                                <li><span class="sub-heading" style="width:100%">CONTRACT CALCULATION</span></li>
                                <li><span class="message-serial message-cl-four">13</span><span class="message-info"><a href="contractcalculationlistpvc.php" target="_blank">CONTRACT CALCULATION - PVC</a></span></li>
                                <li><span class="message-serial message-cl-five">14</span><span class="message-info"><a href="contractcalculationlistcupbaord.php" target="_blank">CONTRACT CALCULATION - Cupboard</a></span></li>
                            </ul>
                        </div>
                    </div>

                </div><!-- end row -->
            </div><!-- end container -->
        </div><!-- end welcome area -->

        <!-- Footer -->
        <div class="footer-copyright-area">
            <p style="margin:0;">Copyright &copy; 2020 ECF. Developer RAJU TAMANG</p>
        </div>

    </div>
</div>
</body>
</html>
