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

// Update Condition
if (isset($_POST["hdnCmd"]) && $_POST["hdnCmd"] == "CSave") {

    $id = isset($_POST["txt_req_no"]) ? $_POST["txt_req_no"] : "";
    $REQUESTEDQTY = isset($_POST["txt_req_qty"]) ? $_POST["txt_req_qty"] : "";
    $BALANCEQTY = isset($_POST["txt_Balance"]) ? $_POST["txt_Balance"] : "";

    if ($REQUESTEDQTY != "") {
        if ((int)$REQUESTEDQTY <= (int)$BALANCEQTY) {
            try {
                $strSQL6 = "UPDATE productionrequest SET REQUESTEDQTY = :req WHERE ID = :id";
                $objExec = $con->prepare($strSQL6);
                $objExec->bindValue(":req", $REQUESTEDQTY);
                $objExec->bindValue(":id", $id);
                $objExec->execute();

                header("Location: ProductionRequestList.php");
                exit();
            } catch (PDOException $e) {
                $errortext = "Update Error: " . $e->getMessage();
            }
        } else {
            $errortext = "Cannot add balance qty is less";
        }
    }
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
                            <a href="menu.php">HOME</a>
                        </td>
                    </tr>
                </table>

                <p>
                <tr bgcolor="#999999">
                    <th align="center" colspan="4">Production Request</th>
                </tr>
                <th colspan="2" align="left"> </th>

                <tr>
                    <td align="left">REQ. No.</td>
                    <td align="left">
                        <input type="text" name="txt_req_no" size="40" id="txt_req_no" readonly="readonly"
                               value="<?php echo isset($_GET['id']) ? $_GET['id'] : ''; ?>"
                               style="background-color:#CCCCCC">
                    </td>
                    <td align="left"> </td>
                    <td align="left"></td>
                </tr>

                <tr>
                    <td align="left">CONTRACT nO</td>
                    <td align="left">
                        <input name="txt_contract_no" type="text" id="txt_contract_no" size="40"
                               value="<?php echo isset($_GET['contractno']) ? $_GET['contractno'] : ''; ?>"
                               readonly="readonly" style="background-color:#CCCCCC" />
                    </td>
                    <td align="left">Material Name</td>
                    <td align="left">
                        <input name="txt_material_name" type="text" id="txt_material_name" size="40"
                               value="<?php echo isset($_GET['name']) ? $_GET['name'] : ''; ?>"
                               readonly="readonly" style="background-color:#CCCCCC" />
                    </td>
                </tr>

                <tr>
                    <td align="left">Balance Qty.</td>
                    <td align="left">
                        <input name="txt_Balance" type="text" id="txt_Balance" size="40"
                               value="<?php echo isset($_GET['balance']) ? $_GET['balance'] : ''; ?>"
                               readonly="readonly" style="background-color:#CCCCCC" />
                    </td>
                </tr>

                <tr>
                    <td align="left">Required Qty</td>
                    <td>
                        <input name="txt_req_qty" type="text" id="txt_req_qty" size="40"
                               value="<?php echo isset($_GET['qty']) ? $_GET['qty'] : ''; ?>" />
                    </td>
                </tr>

                <?php if ($errortext != ""): ?>
                <tr>
                    <td colspan="4"><font color="#FF0000"><?php echo $errortext; ?></font></td>
                </tr>
                <?php endif; ?>

                <tr>
                    <td colspan="4">
                        <input type="Button" name="btn_add" id="btn_add" value="add"
                               OnClick="frm_GOOD_ISS.hdnCmd.value='CSave';frm_GOOD_ISS.submit();" />
                    </td>
                </tr>
            </th>
        </tr>
    </table>
</form>

</body>
</html>
