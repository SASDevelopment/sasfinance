<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/cvbtech/authenticate.php");

$thisfield=$_REQUEST["thisfield"];
$sortorder=$_GET["sort"];

if (!$sortorder) { $sortorder = 'DESC'; }
if ($sortorder == 'DESC') {
    $sortorder = 'ASC';
}
else
{
    $sortorder = 'DESC';
}
$royalty_type = $_GET['royalty_type'];
$royalty_date = $_GET['royalty_date'];

$where_query = "WHERE royalty_type='".$royalty_type."'";

if (!empty($royalty_type) && empty($royalty_date)) {
    $query_royalty_date = mysql_query("
        SELECT
            `timestamp` AS royalty_date_query,
            DATE_FORMAT(`timestamp`, '%m/%d/%y %H:%i %p') AS royalty_date
        FROM
            sasroyalties.royalty_csv_archive
        WHERE
            royalty_type = '".$royalty_type."'
        GROUP BY
            DATE_FORMAT(`timestamp`, '%m/%d/%y %H:%i %p')
        ORDER BY 
            DATE_FORMAT(`timestamp`, '%m/%d/%y %H:%i:s') DESC
        LIMIT
            1
    ", $db);

    $myrow = mysql_fetch_array($query_royalty_date);
    $rd = $myrow['royalty_date_query'];
    header("Refresh:0; url=royalties_csv_report.php?royalty_type=".$royalty_type."&royalty_date=".$rd);
}
// date('Y-m-d H:i:s', strtotime($_GET['royalty_date']))
if (empty($royalty_date)) { $where_query=$where_query." AND `timestamp` = '".$rd."'"; } else { $where_query=$where_query." AND `timestamp` = '".$royalty_date."'"; }
if (!isset($thisfield)) { $thisfield="projectid"; }
?>

<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
<title>Royalty Archive Report</title>

<script type="text/javascript" src="https://code.jquery.com/jquery-1.7.2.min.js"></script>
<script-- src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js">
<link rel="stylesheet" href="/assets/css/jquery-ui.css">
<link href='/xuloncontrolpanel/einstein/css/database.css' rel='stylesheet' type='text/css' /><style type='text/css'>
<style>
    body,td,th {
        font-size: 12px;
        color:#333333
    }
    body {
        margin: 0px;
    }
    .style5 {font-size: 12px}
</style>
<style>

    #divLoading
    {
        display : block;
        position : fixed;
        z-index: 100;
        background-image : url('/images/loadingpleasewait.gif');
        background-color: #ffffff;
        #opacity : 0.7;
        background-repeat : no-repeat;
        background-position : center;
        left : 0;
        bottom : 0;
        right : 0;
        top : 0;
    }
    #myBtn {
        display: none; /* Hidden by default */
        position: fixed; /* Fixed/sticky position */
        bottom: 20px; /* Place the button at the bottom of the page */
        right: 30px; /* Place the button 30px from the right */
        z-index: 99; /* Make sure it does not overlap */
        border: none; /* Remove borders */
        outline: none; /* Remove outline */
        background-color: red; /* Set a background color */
        color: white; /* Text color */
        cursor: pointer; /* Add a mouse pointer on hover */
        padding: 15px; /* Some padding */
        border-radius: 10px; /* Rounded corners */
    }
    #myBtn:hover {
        background-color: #555; /* Add a dark-grey background on hover */
    }
</style>
<script type="text/javascript">
    function clear_fields(id) {
        document.getElementById('royalty_date').selectedIndex = 'All';
    }

    // When the user scrolls down 20px from the top of the document, show the button
    window.onscroll = function() {scrollFunction()};

    function scrollFunction() {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            document.getElementById("myBtn").style.display = "block";
        } else {
            document.getElementById("myBtn").style.display = "none";
        }
    }

    // When the user clicks on the button, scroll to the top of the document
    function topFunction() {
        document.body.scrollTop = 0; // For Safari
        document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
    }

    $(document).ready(function () {
        $("#archive_data").click(function () {
            $("#createcsv").attr("disabled", "disabled");
            $("#archive_data").attr("disabled", "disabled");
                $.ajax({
                url: "royalties_csv_archiving.php",
                type: "POST",
                success: function (data) {
                    console.log(data);
                    location.reload();
                },
                error: function (errMsg) {
                    console.log(errMsg);
                }
            });
        });

        $("#createcsv").click(function () {
            $("#createcsv").attr("disabled", "disabled");
            $("#archive_data").attr("disabled", "disabled");
            $.ajax({
                url: "royalties_csv_exports.php",
                type: "POST",
                success: function (data) {
                    console.log(data);
                    location.reload();
                },
                error: function (errMsg) {
                    console.log(errMsg);
                }
            });
        });

    });
</script>
</head>
<body>

<!--div id='divLoading'></div-->
<button onclick="topFunction()" id="myBtn" title="Go to top">Top</button>

<div id='page-container'>

<div id='infobar'>

<div class='tealText'>Royalty Archive Report</div>

</div>
<div id='categorybar'>Generate Report</div>
    <div id='accounttable'>
        <form id='report' name='report' method='GET' action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>'>
            <table width="775" border="0">
                <br>
                <tr>
                    <td width="200"><strong>Royalty Type:</strong></td>
                    <td width="200"><strong>Royalty Date:</strong></td>
                </tr>
                <tr>
                    <td>
                        <label>
                            <?=royalty_type();?>
                        </label>
                    </td>
                    <td>
                        <label>
                            <?=royalty_date();?>
                        </label>
                    </td>
                    <td>
                        <label>
                            <input type="submit" name="button" id="button" value="Run Report" style='width:100px;'>
                        </label>
                    </td>
                    <td width="100">
                    </td>
                    <!--td>
                        <label>
                            <input type="button" name="archive_data" id="archive_data" value="Archive Data" style='width:100px;'>
                            <input type="button" name="createcsv" id="createcsv" value="Create CVS files" style='width:100px;'>
                        </label>
                    </td-->
                </tr>
            </table>
        </form>
    </div>
<?php
$writetotext .= "Vendor #\tName\tAddress (Line 1)\tAddress (Line 2)\tCity\tState\tZip\tCountry\tTimestamp showing author made changes\r\n";
?>

<div id='reporttable'>
<table cellpadding="0px" cellspacing="0px" style="width:100%">
    <tr>
        <th width='75'><div align="left"><a href='?thisfield=projectid&sort=<?= $sortorder ?>&royalty_type=<?= $royalty_type ?>&royalty_date=<?= $royalty_date ?>' style='color:white; text-decoration: none'>Project ID <?=$sort_image_projectid?></a></div>
        <th width="150"><div align="center"><a href='?thisfield=Vendor_ID&sort=<?= $sortorder ?>&royalty_type=<?= $royalty_type ?>&royalty_date=<?= $royalty_date ?>' style='color:white; text-decoration: none'>Vendor ID</div></th>
        <th width="100"><div align="center"><a href='?thisfield=Royalty_ISBN&sort=<?= $sortorder ?>&royalty_type=<?= $royalty_type ?>&royalty_date=<?= $royalty_date ?>' style='color:white; text-decoration: none'>Royalty ISBN</div></th>
        <th width="75"><div align="center"><a href='?thisfield=imprint_brand_id&sort=<?= $sortorder ?>&royalty_type=<?= $royalty_type ?>&royalty_date=<?= $royalty_date ?>' style='color:white; text-decoration: none'>Imprint Brand</div></th>
        <th width="275"><div align="left"><a href='?thisfield=First_Name&sort=<?= $sortorder ?>&royalty_type=<?= $royalty_type ?>&royalty_date=<?= $royalty_date ?>' style='color:white; text-decoration: none'>First Name</div></th>
        <th width="275"><div align="left"><a href='?thisfield=Last_Name&sort=<?= $sortorder ?>&royalty_type=<?= $royalty_type ?>&royalty_date=<?= $royalty_date ?>' style='color:white; text-decoration: none'>Last Name</div></th>
        <th width="100"><div align="left"><a href='?thisfield=W_9_payee_name&sort=<?= $sortorder ?>&royalty_type=<?= $royalty_type ?>&royalty_date=<?= $royalty_date ?>' style='color:white; text-decoration: none'>W9 Payee Name</div></th>
        <th width="150"><div align="center"><a href='?thisfield=TOTALROYALTYAMOUNT&sort=<?= $sortorder ?>&royalty_type=<?= $royalty_type ?>&royalty_date=<?= $royalty_date ?>' style='color:white; text-decoration: none'>Total Royalty Amount</div></th>
        <th width="150"><div align="center">Royalty Date</th>
    </tr>

<?php
$count=0;

include($_SERVER["DOCUMENT_ROOT"]."/assets/db/db.connect.php");

$xulon_name = $_COOKIE['xulon_name'];
$Use_Query="/* ".$xulon_name." royalty archive report */
    SELECT 
        *
    FROM
        sasroyalties.royalty_csv_archive
    $where_query
    ORDER BY
        $thisfield $sortorder
";

display_query_xa($Use_Query);

$query_getReport = mysql_query($Use_Query, $db);

if ($myrow = mysql_fetch_array($query_getReport)) {
do {
    $id=$myrow["id"];
	$First_Name=$myrow["First_Name"];
	$Last_Name=$myrow["Last_Name"];
	$W_9_payee_name=$myrow["W_9_payee_name"];
	$Vendor_ID=$myrow["Vendor_ID"];
	$Royalty_ISBN=$myrow["Royalty_ISBN"];
	$projectid=$myrow["projectid"];
	$imprint_brand_id=$myrow["imprint_brand_id"];
	$TOTALROYALTYAMOUNT=$myrow["TOTALROYALTYAMOUNT"];
	$timestamp=$myrow["timestamp"];

	unset ($rowcolor);

	## ALTERNATE TABLE ROW COLORS
    if ($rowcount % 2) {
        $rowcolor="bgcolor='#F0F0F0'";
    } else {
        $rowcolor="bgcolor='white'";
        unset ($rowcount);
    }
    $rowcount++;

    $reviewed_by_checked = !empty($reviewed_by) ? "CHECKED" : "";

    echo "
        <tr $rowcolor>
            <td align='center'>".$projectid."</td>
            <td align='center'>".$Vendor_ID."</td>
            <td align='center'>".$Royalty_ISBN."</td>
            <td align='center'>".$imprint_brand_id."</td>
            <td>".$First_Name."</td>
            <td>".$Last_Name."</td>
            <td>".$W_9_payee_name."</td>
            <td align='center'>$".$TOTALROYALTYAMOUNT."</td>
            <td align='center'>".date('m/d/Y g:i A', strtotime($timestamp))."</td>
            <!--td>".date('m/d/Y g:i A', strtotime($timestamp)) ."</td-->
        </tr>
    </form>
    ";
	$writetotext .= "$vendor_id\t$Account_Name\t$Mailing_Address_Line_1\t$Mailing_Address_Line_2\t$Mailing_City\t$Mailing_State_Province\t$Mailing_Zip_Postal_Code\t$Mailing_Country\t$Account_Name $Last_Modified_Date\r\n";
	$count++;
    #if($count == 1) { break; }
} while ($myrow = mysql_fetch_array($query_getReport)); }

mysql_close ($db);

?>

</table>
</div>
 <div id='reporttable2'>
<table width="775" cellspacing="0px" style="width:100%">

    <tr>
        <th width="119"><div align="left"></div>
        <th width="128"><div align="left"></div>
        <th width="522"><div align="left"></div>
        <!--th width="522"-->
    </tr>
    <?php $writetotext .= "Total Records: $count\r\n"; ?>

    <tr>
        <td width="119"><div align="left"><strong>Total Records</strong></div></td>
        <td width="128"><div align="left"><?php echo $count; ?></div></td>
        <td width="128"><form method='POST' action='/xulonreports/csv/<?php echo $_SESSION["xulonname"]; ?>_address_change_report.xls' target='_blank'><input type="submit" name="button2" id="button2" value="Export CSV File" style='width:150px;'></form></td>
        <!--td width="128"><input type="button" name="pdf_button" id="pdf_button" value="Export PDF File" style='width:150px;'></form></td-->
       <!--td width="128">
            <form method='POST' action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>' target='_blank'><input type="submit" name="button3" id="button3" value="Export PDF File" style='width:150px;'>
                <input type='hidden' name='pdf' value='<?php echo $writetotext; ?>'>
                <input type='hidden' name='text' value='pdf'>
            </form>
        </td-->
    </tr>
</table>

<p></div>
</div>

<?php

#### Excel Export
unlink ($_SERVER["DOCUMENT_ROOT"]."/xulonreports/csv/".$_SESSION["xulonname"]."_address_change_report.xls");
$filePointer = fopen($_SERVER["DOCUMENT_ROOT"]."/xulonreports/csv/".$_SESSION["xulonname"]."_address_change_report.xls", "w");
fputs ($filePointer, "$writetotext", 2000000);
fclose($filePointer);

    function royalty_type() {
        include($_SERVER['DOCUMENT_ROOT'] . '/assets/db/db.connect.php');
        $rt = $_REQUEST['royalty_type'];

        $query_royalty_type = mysql_query("SELECT DISTINCT(royalty_type) AS royalty_type FROM sasroyalties.royalty_csv_archive", $db);

/*
        if ($rt == "All") {
            $ALLSELECTED = 'SELECTED';
        }
*/
        $option = "
            <select id='royalty_type' name='royalty_type' onchange='clear_fields(royalty_date); this.form.submit();'>
            <!--option value='' $ALLSELECTED>All</option-->
            <option value=''></option>
        ";

        if ($myrow = mysql_fetch_array($query_royalty_type)) {
            do {
                $royalty_type = $myrow['royalty_type'];

                if ($rt == $royalty_type) {
                    $ISSELECTED = 'SELECTED';
                } else {
                    $ISSELECTED = '';
                }

                $option .= "<option value='".$royalty_type."' $ISSELECTED>$royalty_type</option>";

            } while ($myrow = mysql_fetch_array($query_royalty_type));
        }
        $option .= "</select>";
        unset($_GET['royalty_date']);
        return $option;
    }

    function royalty_date() {
        include($_SERVER['DOCUMENT_ROOT'] . '/assets/db/db.connect.php');
        $rt = $_REQUEST['royalty_type'];
        $rd = $_REQUEST['royalty_date'];

        $query_royalty_date = mysql_query("
            SELECT
                `timestamp` AS royalty_date_query,
                DATE_FORMAT(`timestamp`, '%m/%d/%y %l:%i %p') AS royalty_date
            FROM
                sasroyalties.royalty_csv_archive
            WHERE
                royalty_type = '".$rt."'
            GROUP BY
                DATE_FORMAT(`timestamp`, '%m/%d/%y %H:%i %p')
            ORDER BY 
                DATE_FORMAT(`timestamp`, '%m/%d/%y %H:%i:s') DESC
        ", $db);

        if ($rd == "") {
            $ALLSELECTED = 'SELECTED';
        }

        $option .= "<select id='royalty_date' name='royalty_date' onchange='this.form.submit();'>";
        //if (!empty($rd1)) { $option .= "<option value='".$rd."' $ALLSELECTED>".$rd1."</option>"; }

        $option .= "<option value='' $ALLSELECTED></option>";

        if ($myrow = mysql_fetch_array($query_royalty_date)) {
            do {
                $royalty_date = $myrow['royalty_date'];
                $royalty_date_query = $myrow['royalty_date_query'];

                if ($rd == $royalty_date_query) {
                    $ISSELECTED = 'SELECTED';
                } else {
                    $ISSELECTED = '';
                }

                $option .= "<option value='".$royalty_date_query."' $ISSELECTED>$royalty_date</option>";

            } while ($myrow = mysql_fetch_array($query_royalty_date));
        }

        $option .= "</select>";

        return $option;
    }
?>

</body>
</html>
