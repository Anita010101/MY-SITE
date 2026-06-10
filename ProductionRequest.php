<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'connection.php';
$con = $conn;

$_SESSION["FRM"] = "GIV";
require_once 'CHK_PRIV.php';

$errortext = "";
$i2 = "00";
$disabled = " disabled";
$maxPRNo = 1;
$balanceqty = 0;
$totalcount = 0;

if (isset($_POST["CHK_CLOSE_YN"]) && $_POST["CHK_CLOSE_YN"] == "on") {
    $M_CLOSE_YN = "Y";
} else {
    $M_CLOSE_YN = "N";
}

try {
    $strSQL25 = "SELECT NVL(MAX(prno),0) AS total FROM productionrequest";
    $objRec25 = $con->prepare($strSQL25);
    $objRec25->execute();
    $row25 = $objRec25->fetch(PDO::FETCH_ASSOC);
    if ($row25) {
        $maxPRNo = (int)$row25["TOTAL"] + 1;
    }
} catch (PDOException $e) {
    $errortext .= "Max PR No Error: " . $e->getMessage() . "<br>";
}

if (isset($_POST["hdnCmd"]) && $_POST["hdnCmd"] == "CSave" && isset($_SESSION["back2"]) && $_SESSION["back2"] == "11") {

    $disabled = " disabled";

    try {
        $strSQL = "SELECT count(*) as count FROM Reorderlevelcontractno WHERE contractno = :contractno";
        $objRec = $con->prepare($strSQL);
        $objRec->bindValue(":contractno", $_POST["hdncontract0"]);
        $objRec->execute();
        $rowCount = $objRec->fetch(PDO::FETCH_ASSOC);
        $totalcount = isset($rowCount["COUNT"]) ? (int)$rowCount["COUNT"] : 0;

        $givno = isset($_POST["TXT_GIV_NO"]) ? $_POST["TXT_GIV_NO"] : "";
        $remarks = isset($_POST["TXT_REMARKS"]) ? $_POST["TXT_REMARKS"] : "";

        for ($i = 0; $i <= $totalcount; $i++) {
            $MATERIALNAME = isset($_POST["TXT_materialname" . $i]) ? $_POST["TXT_materialname" . $i] : "";
            $CONTRACTNO = isset($_POST["TXT_contractno" . $i]) ? $_POST["TXT_contractno" . $i] : "";
            $REQUESTEDQTY = isset($_POST["TXT_REQ_NO" . $i]) ? $_POST["TXT_REQ_NO" . $i] : "";
            $BALANCEQTY = isset($_POST["TXT_balance_qty" . $i]) ? $_POST["TXT_balance_qty" . $i] : "";

            if ($REQUESTEDQTY != "") {
                if ((int)$REQUESTEDQTY <= (int)$BALANCEQTY) {
                    $strSQL6 = "INSERT INTO productionrequest
                        (ID, MATERIALNAME, CONTRACTNO, REQUESTEDQTY, BALANCEQTY, DATEENTER, status, month, year, prno, REMARKS)
                        VALUES
                        (PurchaseRequest_id_seq.NEXTVAL, :mat, :cont, :req, :bal, sysdate, 'Pending',
                         EXTRACT(month FROM sysdate), EXTRACT(year FROM sysdate), :prno, :rem)";

                    $objExec = $con->prepare($strSQL6);
                    $objExec->bindValue(":mat", $MATERIALNAME);
                    $objExec->bindValue(":cont", $CONTRACTNO);
                    $objExec->bindValue(":req", $REQUESTEDQTY);
                    $objExec->bindValue(":bal", $BALANCEQTY);
                    $objExec->bindValue(":prno", $givno);
                    $objExec->bindValue(":rem", $remarks);
                    $objExec->execute();
                } else {
                    $errortext .= "Requested Qty is greter than balance Qty : " . $MATERIALNAME . "<br>";
                }
            }
        }

        $_SESSION["back2"] = "00";
        $errortext .= "<a href='productionrequestlist.php'>Go back to list</a><br>";
    } catch (PDOException $e) {
        $errortext .= "Save Error: " . $e->getMessage() . "<br>";
    }
} elseif (isset($_POST["hdnCmd"]) && $_POST["hdnCmd"] == "CSave") {
    $errortext .= "Cannot Save Multiple Times. Search by contract NUmber <br>";
}

if (!isset($_POST["hdnCmd"]) || $_POST["hdnCmd"] != "CSave") {
    $errortext = "";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <script type="text/javascript" src="filterlist.js"></script>
    <link rel="stylesheet" type="text/css" href="style.css" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Production Request</title>
</head>

<body>
<form id="frm_GOOD_ISS" name="frm_GOOD_ISS" method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
    <input type="hidden" name="hdnCmd" value="">
    <input type="hidden" name="hdncount" value="">

    <table width="875" border="1" align="center" bgcolor="#FFFFFF">
        <tr>
            <th scope="col">
                <?php include 'header.php'; ?>

                <table width="200" border="1" align="center" bgcolor="#CCCCCC">
                    <tr>
                        <td>
                            <a href="menu.php">HOME</a> &nbsp;&nbsp;&nbsp;
                            <a href="productionrequestlist.php">Back TO list</a>
                        </td>
                    </tr>
                </table>

                <p>
                <tr bgcolor="#999999">
                    <th align="center" colspan="4"> Production Request</th>
                </tr>
                <th colspan="2" align="left"> </th>

                <tr>
                    <td align="left">REQ. No.</td>
                    <td align="left">
                        <input type="text" name="TXT_GIV_NO" size="40" id="TXT_GIV_NO" value="<?php echo $maxPRNo; ?>">
                    </td>
                    <td align="left">Date</td>
                    <td align="left">
                        <input type="text" name="TXT_DATE" size="40" id="TXT_DATE" />
                    </td>
                </tr>

                <tr>
                    <td align="left">CONTRACT nO</td>
                    <td align="left">
                        <input name="TXT_DELV_NO" type="text" id="TXT_DELV_NO" size="40" />
                        <input type="submit"
                               class="btn btn-sm btn-primary login-submit-cs"
                               value="Search"
                               onclick="document.frm_GOOD_ISS.action='<?php echo $_SERVER['SCRIPT_NAME']; ?>?Act=Find'; frm_GOOD_ISS.submit();" />
                    </td>

                    <td align="left">Sale type</td>
                    <td align="left">
                        <select name="LST_SALE_TYPE" id="LST_SALE_TYPE">
                            <option>Customer</option>
                            <option>Sales</option>
                            <option>User Asset</option>
                            <option>Infrastructure</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td align="left">Remarks</td>
                    <td align="left" colspan="3">
                        <input name="TXT_REMARKS" type="text" id="TXT_REMARKS" size="40" />
                    </td>
                </tr>

                <tr>
                    <td colspan="4"><font color="#FF0000"><?php echo $errortext; ?></font></td>
                </tr>
            </th>
        </tr>
    </table>

    <table width="100%" border="1" align="center" bgcolor="#CCCCCC">
        <tr>
            <th scope="col">Sr.No.</th>
            <th scope="col" width="30px">Contract No.</th>
            <th scope="col" width="100px">Search Term</th>
            <th scope="col" width="200px">Item Name</th>
            <th scope="col">Allocated Qty</th>
            <th scope="col">Balance Qty</th>
            <th scope="col">Req. Qty</th>
        </tr>

        <?php
        if (isset($_GET["Act"]) && $_GET["Act"] == "Find") {
            $_SESSION["back2"] = "11";

            $searchContract = isset($_POST["TXT_DELV_NO"]) ? $_POST["TXT_DELV_NO"] : "";
            $strSQL = "SELECT * FROM Reorderlevelcontractno WHERE contractno = :contractno";
            $objRec = $con->prepare($strSQL);
            $objRec->bindValue(":contractno", $searchContract);
            $objRec->execute();

            $i = 0;

            while ($row = $objRec->fetch(PDO::FETCH_ASSOC)) {

                $disabled = "";

               $strSQL33 = "SELECT MATERIALNAME FROM Reorderlevel WHERE TRIM(UPPER(SEARHTERM)) = TRIM(UPPER(:searchterm)) ORDER BY id DESC";
$objRec33 = $con->prepare($strSQL33);
$objRec33->bindValue(":searchterm", $row["MATERIALNAME"]);
$objRec33->execute();
$row33 = $objRec33->fetch(PDO::FETCH_ASSOC);


                $strSQL1 = "SELECT NVL(SUM(allocateqty),0) as total FROM reorderlevelcontractno WHERE contractno = :contractno AND materialname = :materialname";
                $objRec1 = $con->prepare($strSQL1);
                $objRec1->bindValue(":contractno", $row["CONTRACTNO"]);
                $objRec1->bindValue(":materialname", $row["MATERIALNAME"]);
                $objRec1->execute();
                $row1 = $objRec1->fetch(PDO::FETCH_ASSOC);

                $strSQL12 = "SELECT NVL(SUM(REQUESTEDQTY),0) as total FROM PRODUCTIONREQUEST WHERE contractno = :contractno AND materialname = :materialname";
                $objRec12 = $con->prepare($strSQL12);
                $objRec12->bindValue(":contractno", $row["CONTRACTNO"]);
                $objRec12->bindValue(":materialname", $row["MATERIALNAME"]);
                $objRec12->execute();
                $row12 = $objRec12->fetch(PDO::FETCH_ASSOC);

                $allocated = isset($row1["TOTAL"]) ? (int)$row1["TOTAL"] : 0;
                $requested = isset($row12["TOTAL"]) ? (int)$row12["TOTAL"] : 0;
                $balanceqty = $allocated - $requested;
                ?>
                <tr>
                    <td>
                        <div align="center"><?php echo $row["ID"]; ?></div>
                    </td>
                    <td>
                        <input name="TXT_contractno<?php echo $i; ?>" type="text" id="TXT_contractno<?php echo $i; ?>" size="20"
                               value="<?php echo $row["CONTRACTNO"]; ?>" readonly="readonly" />
                    </td>
                    <td>
                        <input name="TXT_materialname<?php echo $i; ?>" type="text" id="TXT_materialname<?php echo $i; ?>" size="20"
                               value="<?php echo $row["MATERIALNAME"]; ?>" readonly="readonly" />
                    </td>
                    <td width="200px">
                        <div align="center">
                            <?php
                            if ($row33) {
                                echo $row33["MATERIALNAME"];
                            }
                            ?>
                        </div>
                    </td>
                    <td>
                        <div align="center"><?php echo $row["ALLOCATEQTY"]; ?></div>
                    </td>
                    <td>
                        <input name="TXT_balance_qty<?php echo $i; ?>" type="text" id="TXT_balance_qty<?php echo $i; ?>" size="6"
                               value="<?php echo $balanceqty; ?>"
                               style="background-color:#CCCCCC;border:none; color:#0000FF;font-weight:bold" readonly="readonly" />
                    </td>
                    <td align="LEFT">
                        <input name="TXT_REQ_NO<?php echo $i; ?>" type="text" id="TXT_REQ_NO<?php echo $i; ?>" size="20" autocomplete="off" />
                        <input type="hidden" name="hdncontract<?php echo $i; ?>" value="<?php echo $row["CONTRACTNO"]; ?>">
                    </td>
                </tr>
                <?php
                $i++;
            }
        }
        ?>

        <tr>
            <td colspan="6">
                <input type="Button" name="btn_add" id="btn_add" value="add"
                       OnClick="frm_GOOD_ISS.hdnCmd.value='CSave';frm_GOOD_ISS.submit();" <?php echo $disabled; ?>/>
            </td>
        </tr>
    </table>
</form>
</body>
</html>
