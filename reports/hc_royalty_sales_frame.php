<?
include_once($_SERVER["DOCUMENT_ROOT"]."/cvbtech/authenticate.php");

include_once($_SERVER['DOCUMENT_ROOT'].'/assets/functions.ac2.php');

include_once($_SERVER["DOCUMENT_ROOT"].'/assets/functions.all.php');

$use_db='sasroyalties';
//$use_db='xulonroyalties';


if (!$_REQUEST['GETCID']) { 
	echo 'An error occurred.';
	die;
}

$_SESSION['SESSCONTACTID']=$_REQUEST['GETCID'];

if ($_REQUEST['PID']) { 
	$GETPID=$_REQUEST['PID'];
} 

$start_month_val=$_REQUEST['start_month_val'];
$end_month_val=$_REQUEST['end_month_val'];

$start_year_val=$_REQUEST['start_year_val'];
$end_year_val=$_REQUEST['end_year_val'];

if ($start_month_val && $start_year_val) { 
	$GETSTARTDATE=$start_year_val.'-'.$start_month_val.'-01';
}

if ($end_month_val && $end_year_val) { 
	$GETENDDATE=date ('Y-m-d', mktime(0,0,0,$end_month_val + 1,0,$end_year_val));
}

if (!$GETSTARTDATE || !$GETENDDATE) { 
	$GETSTARTDATE=date('Y').'-01-01';
	$GETENDDATE=date('Y-m-t');
}

$GETCID=$_SESSION['SESSCONTACTID'];

?>
<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head><title>

</title><meta charset="utf-8" /><meta content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0" name="viewport" /><meta content="yes" name="apple-mobile-web-app-capable" /><meta content="black" name="apple-mobile-web-app-status-bar-style" />
<link href="https://fonts.googleapis.com/css?family=Raleway:400,300,500,600,700,200,100,800" type="text/css" rel="stylesheet" />
<link href="/assets/hillcrest/bootstrap.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" />
<link href="/assets/hillcrest/perfect-scrollbar.css" rel="stylesheet" />
<link href="/assets/hillcrest/animate.min.css" rel="stylesheet" />
<link href="/assets/hillcrest/owl.carousel.css" rel="stylesheet" />
<link href="/assets/hillcrest/owl.theme.css" rel="stylesheet" />
<link href="/assets/hillcrest/owl.transitions.css" rel="stylesheet" />
<link href="/assets/hillcrest/summernote.css" rel="stylesheet" />
<link href="/assets/hillcrest/fullcalendar.css" rel="stylesheet" />
<link href="/assets/hillcrest/toastr.min.css" rel="stylesheet" />
<link href="/assets/hillcrest/bootstrap-select.min.css" rel="stylesheet" />
<link href="/assets/hillcrest/bootstrap-switch.min.css" rel="stylesheet" />
<link href="/assets/hillcrest/bootstrap-fileupload.min.css" rel="stylesheet" />


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>

<style type="text/css">
   table.ex1 {border-spacing: 0}
   table.ex1 td, th {padding: 0 0.2em}
   table.ex1 tr:nth-child(odd) {color: #000; background: #FFF}
   table.ex1 tr:nth-child(even) {color: #000; background: #ECECEC}
</style>
  
</head>
<body>
        <div>
            
    <style type="text/css">
        .uploadTable {
            width: 98%;
            margin: 8px;
        }

            .uploadTable td {
                vertical-align: middle;
                height: 20px;
                border: 0px #000 solid;
                font-size: 13px;
                padding: 5px 5px 5px 2px;

            }

            .uploadTable th {
                vertical-align: bottom;
                height: 20px;
                border: 0px #000 solid;
                font-size: 13px;
                font-weight: bold;
                padding: 10px 3px 0px 1px;
            }

            .uploadTable td a {
                color: #fff;
                font-size: 13px;
            }

			.uploadTable tr:nth-child(odd) {color: #000; background: #FFF}
			.uploadTable tr:nth-child(even) {color: #000; background: #ECECEC}

        .reportHdr {
            background-color: #0077b9;
            color: #ffffff;
            padding-left: 10px;
        }
    </style>
    <div id="section_container1" style="width: 100%; border: 1px solid #d9d9d9; margin-top: 15px; min-height: 300px;">

<?
//print_r($_REQUEST);
function display_acc_info($projectid) { 
	if (!$projectid) { return; }

	include($_SERVER['DOCUMENT_ROOT'].'/assets/db/db.config.php');
	$db = new mysqli($dbserver, $dbuser, $dbpassword, $database);

	$query_select_accounts = $db->query("SELECT PB_ISBN, Account_Name, Account_Site FROM xulonpress.accounts where projectid=$projectid");
	//$num_results = mysqli_affected_rows($db);
	while($myrow = $query_select_accounts->fetch_assoc()) {
		$PB_ISBN=$myrow['PB_ISBN'];
		$Account_Name=stripslashes($myrow['Account_Name']);
		$Account_Site=stripslashes($myrow['Account_Site']);
	}
	$query_select_accounts->free();
	$db->close();


	if (file_exists($_SERVER['DOCUMENT_ROOT']."/bookstore/booksimages/sm/".$PB_ISBN."_sm.jpg")) {
			echo "<img src='https://www.xulonauthors.com/bookstore/booksimages/sm/".$PB_ISBN."_sm.jpg' border=0 align=left hspace=10 vspace=10> ";
	}

	echo "<h2>$Account_Name <small>$Account_Site</small></h3>";
}

display_acc_info($_REQUEST['PID']);

function get_selected_start_month() {
	$start_month_val=$_REQUEST['start_month_val'];
	if($start_month_val) { 
		$SELECTED[$start_month_val]='SELECTED';
	} else {
		$SELECTED['01']='SELECTED';
	}
	return $SELECTED;
}

function get_selected_end_month() {
	$end_month_val=$_REQUEST['end_month_val'];
	if($end_month_val) { 
		$SELECTED[$end_month_val]='SELECTED';
	} else {
		$SELECTED[date('m')]='SELECTED';
	}
	return $SELECTED;
}

$SELECTEDSTART=get_selected_start_month();
$SELECTEDEND=get_selected_end_month();
?>
<form method='GET' action='<?=$_SERVER['PHP_SELF']?>'>
        <div id="DateSelector" style="padding: 10px; width: 650px; float: left;">
            Period:
        from
        <select id="start_month_val" name="start_month_val">
            <option value="01" <?=$SELECTEDSTART['01']?>>Jan</option>
            <option value="02" <?=$SELECTEDSTART['02']?>>Feb</option>
            <option value="03" <?=$SELECTEDSTART['03']?>>Mar</option>
            <option value="04" <?=$SELECTEDSTART['041']?>>Apr</option>
            <option value="05" <?=$SELECTEDSTART['05']?>>May</option>
            <option value="06" <?=$SELECTEDSTART['06']?>>Jun</option>
            <option value="07" <?=$SELECTEDSTART['07']?>>Jul</option>
            <option value="08" <?=$SELECTEDSTART['08']?>>Aug</option>
            <option value="09" <?=$SELECTEDSTART['09']?>>Sep</option>
            <option value="10" <?=$SELECTEDSTART['10']?>>Oct</option>
            <option value="11" <?=$SELECTEDSTART['11']?>>Nov</option>
            <option value="12" <?=$SELECTEDSTART['12']?>>Dec</option>
        </select>
            / 
        <select id="start_year_val" name="start_year_val">
            <option value="2010">2010</option><option value="2011">2011</option><option value="2012">2012</option><option value="2013">2013</option><option value="2014">2014</option><option value="2015">2015</option><option value="2016">2016</option><option value="2017" selected>2017</option>
        </select>
            through
        <select id="end_month_val" name="end_month_val">
              <option value="01" <?=$SELECTEDEND['01']?>>Jan</option>
            <option value="02" <?=$SELECTEDEND['02']?>>Feb</option>
            <option value="03" <?=$SELECTEDEND['03']?>>Mar</option>
            <option value="04" <?=$SELECTEDEND['041']?>>Apr</option>
            <option value="05" <?=$SELECTEDEND['05']?>>May</option>
            <option value="06" <?=$SELECTEDEND['06']?>>Jun</option>
            <option value="07" <?=$SELECTEDEND['07']?>>Jul</option>
            <option value="08" <?=$SELECTEDEND['08']?>>Aug</option>
            <option value="09" <?=$SELECTEDEND['09']?>>Sep</option>
            <option value="10" <?=$SELECTEDEND['10']?>>Oct</option>
            <option value="11" <?=$SELECTEDEND['11']?>>Nov</option>
            <option value="12" <?=$SELECTEDEND['12']?>>Dec</option>
        </select>
            / 
        <select id="end_year_val" name="end_year_val">
            <option value="2010">2010</option><option value="2011">2011</option><option value="2012">2012</option><option value="2013">2013</option><option value="2014">2014</option><option value="2015">2015</option><option value="2016">2016</option><option value="2017" selected>2017</option><option value="2018">2018</option>
        </select>
			<input type='hidden' name='PID' value='<?=$_REQUEST['PID']?>'>
            <input type="submit" value="View Sales" class="submitbutton1" id="salesdetails" />

</form>            
            <label id="message"></label>
            <div>Report range cannot exceed 12 months.</div>

        </div>

        <div id="HelpDoc" style="float: left; margin-top: 10px; text-align: center;">

            <!--<a href="https://www.salemauthorcenter.com/Files/SalesReportHelpDoc.pdf" target="_blank">Sales Report Help Document</a><br />
            (click link to open/download PDF)-->
        </div>

        
        <table id="PhysicalBookTable" class="uploadTable">
            <tr>
                <td colspan="11" class="reportHdr"><span style="margin-left: 5px;"><b>Physical Book Distribution</b></span></td>
            </tr>
                    <div id="salesInfo" style="width: 100%;">
					<? display_physical_book_distribution($GETPID, $GETSTARTDATE, $GETENDDATE);	?>					
					</div>
                    <br />
                    <br />

        </table>
        <table id="EBookTable" class="uploadTable">
            <tr>
                <td colspan="11" class="reportHdr"><span style="margin-left: 5px;"><b>E-Book Distribution</b></span></td>
            </tr>
                    <div id="salesInfoEBook" style="width: 100%;">
					<? display_ebook_distribution($GETPID, $GETSTARTDATE, $GETENDDATE); ?>
					</div>
        </table>
        
        <table id="WebsiteSalesTable" class="uploadTable">
            <tr>
                <td colspan="11" class="reportHdr"><span style="margin-left: 5px;"><b>MyBookOrders.com Sales</b></span></td>
            </tr>
                    <div id="WebsiteSalesInfo" style="width: 100%;">
					<? display_mbo_sales($GETPID, $GETSTARTDATE, $GETENDDATE); ?>
					</div>
        </table>
        
        <table id="StorageTable" class="uploadTable">
            <tr>
                <td colspan="11" class="reportHdr"><span style="margin-left: 5px;"><b>Storage</b></span></td>
            </tr>
                    <div id="StorageTableInfo" style="width: 100%;">
					<?
					display_storage_fees($GETPID, $GETSTARTDATE, $GETENDDATE);
					?>
					</div>
        </table>
        
        <!--table id="AdjustmentTable" class="uploadTable">
            <tr>
                <td colspan="11" class="reportHdr"><span style="margin-left: 5px;"><b>Adjustment</b></span></td>
            </tr>
                    <div id="AdjustmentTableInfo" style="width: 100%;">
					<? display_royalty_adjustment($GETPID, $GETSTARTDATE, $GETENDDATE); ?>
					</div>
        </table-->
        <br />
    </div>

</body>
</html>

<?
function display_physical_book_distribution($GETPID, $GETSTARTDATE, $GETENDDATE) {

	global $use_db;

	//echo "<table>";

	echo "
	<th>Transaction Date
	<th>Date Posted
	<th>Description
	<th>Transaction
	<th>Qty
	<th>Wholesale $
	<th>Distribution Fee
	<th>Freight Fee
	<th>Print Fee
	<th>Return Fee
	<th>SubTotal";

	include($_SERVER['DOCUMENT_ROOT'].'/assets/db/db.config.php');
	$db = new mysqli($dbserver, $dbuser, $dbpassword, $database);

	$query_select_royalty_processing = $db->query("
	SELECT
		Royalty_ISBN,
		DATE_FORMAT(Royalty_Month,'%m/%d/%Y') as Royalty_Month,
		CASE WHEN Royalty_Source like '%Amazon.com%' then 'Amazon.com' WHEN Royalty_Source like '%Baker & Taylor%' THEN 'Baker & Taylor' WHEN Royalty_Source like '%Ingram%' THEN 'Ingram' ELSE Royalty_Source END AS Description,
		CASE WHEN transaction_description like 'Order Bookstore order' then 'OrderOut-Bookstore' WHEN transaction_description like '%Vendor return%' THEN 'ReturnFee' WHEN transaction_description like '%Order Wholesale%' THEN 'OrderOut-Wholesaler' WHEN royalty_classification like 'Payment from Vendor' THEN 'Payment' ELSE transaction_description END as transaction_description,
		(
			CASE WHEN Royalty_Books_Sold IS NULL THEN 0 ELSE Royalty_Books_Sold END - CASE WHEN Royalty_Books_Returned IS NULL THEN 0 ELSE abs(Royalty_Books_Returned) END
		) AS Net_Sold,
		wholesale_pct,
		misc_fee,
		CASE WHEN Royalty_Source like '%]%' THEN 0.00 ELSE distribution_fee END AS distribution_fee,
		freight_fee,
		print_fee,
		return_fee,
		Royalty_Amount, 
		royalty_classification,
		authors_net_royalties.Check_Batch_Timestamp, 
		DATE_FORMAT(date_posted,'%m/%d/%Y') as date_posted
	FROM
		$use_db.authors_net_royalties
	INNER JOIN $use_db.contacts_isbns ON authors_net_royalties.projectid = contacts_isbns.projectid
	WHERE
		authors_net_royalties.projectid = $GETPID
	AND Royalty_Month BETWEEN '$GETSTARTDATE' and '$GETENDDATE'
	AND ((Royalty_Source NOT LIKE 'MyBookOrders' and Royalty_Source NOT LIKE '%Pallet Storage%' and (royalty_classification not like '%ebook%' or royalty_classification is null)) or Royalty_Source IS NULL)
	GROUP BY authors_net_royalties.id
	ORDER BY Royalty_Month ASC;");
	while($myrow = $query_select_royalty_processing->fetch_assoc()) {
		$Royalty_ISBN=$myrow['Royalty_ISBN'];
		$Royalty_Month=$myrow['Royalty_Month'];
		$Description=$myrow['Description'];
		$transaction_description=$myrow['transaction_description'];
		$Net_Sold=$myrow['Net_Sold'];
		$wholesale_pct=$myrow['wholesale_pct'];
		$misc_fee=$myrow['misc_fee'];
		$distribution_fee=$myrow['distribution_fee'];
		$freight_fee=$myrow['freight_fee'];
		$print_fee=$myrow['print_fee'];
		$Royalty_Amount=$myrow['Royalty_Amount'];
		$royalty_classification=$myrow['royalty_classification'];
		$Check_Batch_Timestamp=$myrow['Check_Batch_Timestamp'];
		$date_posted=$myrow['date_posted'];

		$Total_TRA=$Total_TRA+$Royalty_Amount;

		if ($Check_Batch_Timestamp) { 
			$rowcolor="style='color:#006400'";
		} else {
			unset($rowcolor);
		}

		//$rowcolor="style='background-color:#006400'";

		echo "<tr $rowcolor>
		<td>$Royalty_Month</td>
		<td>$date_posted</td>
		<td>$Description  <!--($Royalty_ISBN)--></td>
		<td>$transaction_description</td>
		<td>$Net_Sold</td>
		<td>".convert_neg($wholesale_pct)."</td>
		<td>".convert_neg($distribution_fee)."</td>
		<td>".convert_neg($freight_fee)."</td>
		<td>".convert_neg($print_fee)."</td>
		<td>".convert_neg($misc_fee)."</td>
		<td>".convert_neg($Royalty_Amount)."</td>
		</tr>";
	}
	$query_select_royalty_processing->free();
	$db->close();

	echo "<tr><td colspan=9></td><td align='right' style='font-weight:bold;'>Total: </td><td style='font-weight:bold;'>".convert_neg($Total_TRA)."</td></tr>";

	//echo "</table>";



}


function display_ebook_distribution($GETPID, $GETSTARTDATE, $GETENDDATE) {

	global $use_db;

	//echo "<table>";

	echo "
	<th>Transaction Date
	<th>Date Posted
	<th>Description
	<th>
	<th>Qty
	<th>Wholesale $
	<th>Retailer Fee
	<th>Distribution Fee
	<th>Return Fee
	<th>Transaction
	<th>SubTotal";

	include($_SERVER['DOCUMENT_ROOT'].'/assets/db/db.config.php');
	$db = new mysqli($dbserver, $dbuser, $dbpassword, $database);

	$query_select_royalty_processing = $db->query("
	SELECT
		Royalty_ISBN,
		DATE_FORMAT(Royalty_Month,'%m/%d/%Y') as Royalty_Month,
		CASE WHEN Royalty_Source like '%Amazon.com%' then 'Amazon.com' WHEN Royalty_Source like '%Amazon Kindle%' AND Royalty_Books_Returned=0 THEN 'Amazon Kindle Store' WHEN Royalty_Source like '%Amazon Kindle%' and Royalty_Books_Returned !=0 THEN concat('Amazon Kindle Store (',Royalty_Books_Sold,' Sold, ',Royalty_Books_Returned,' Returned)') WHEN Royalty_Source like '%iTunes%' THEN 'iBook Sale' ELSE Royalty_Source END AS Description,
		CASE WHEN transaction_description like 'Order Bookstore order' then 'OrderOut-Bookstore' ELSE transaction_description END as transaction_description,
		(
			CASE WHEN Royalty_Books_Sold IS NULL THEN 0 ELSE Royalty_Books_Sold END - CASE WHEN Royalty_Books_Returned IS NULL THEN 0 ELSE abs(Royalty_Books_Returned) END
		) AS Net_Sold,
		wholesale_pct,
		misc_fee,
		distribution_fee,
		freight_fee,
		print_fee,
		return_fee,
		Royalty_Amount, 
		royalty_classification,
		authors_net_royalties.Check_Batch_Timestamp, 
		DATE_FORMAT(date_posted,'%m/%d/%Y') as date_posted
	FROM
		$use_db.authors_net_royalties
	INNER JOIN $use_db.contacts_isbns ON authors_net_royalties.projectid = contacts_isbns.projectid
	WHERE
		authors_net_royalties.projectid = $GETPID
	AND Royalty_Month BETWEEN '$GETSTARTDATE' and '$GETENDDATE'
	AND (royalty_classification like '%ebook%')
	GROUP BY authors_net_royalties.id
	ORDER BY Royalty_Month ASC;");
	while($myrow = $query_select_royalty_processing->fetch_assoc()) {
		$Royalty_ISBN=$myrow['Royalty_ISBN'];
		$Royalty_Month=$myrow['Royalty_Month'];
		$Description=$myrow['Description'];
		$transaction_description=$myrow['transaction_description'];
		$Net_Sold=$myrow['Net_Sold'];
		$wholesale_pct=$myrow['wholesale_pct'];
		$misc_fee=$myrow['misc_fee'];
		$distribution_fee=$myrow['distribution_fee'];
		$freight_fee=$myrow['freight_fee'];
		$print_fee=$myrow['print_fee'];
		$Royalty_Amount=$myrow['Royalty_Amount'];
		$royalty_classification=$myrow['royalty_classification'];
		$Check_Batch_Timestamp=$myrow['Check_Batch_Timestamp'];
		$date_posted=$myrow['date_posted'];

		$Total_TRA=$Total_TRA+$Royalty_Amount;


		if ($Check_Batch_Timestamp) { 
			$rowcolor="style='color:#006400'";
		} else {
			unset($rowcolor);
		}

		echo "<tr $rowcolor>
		<td>$Royalty_Month</td>
		<td>$date_posted</td>
		<td>$Description <!--($Royalty_ISBN)--></td>
		<td>&nbsp;</td>
		<td>$Net_Sold</td>
		<td>".convert_neg($wholesale_pct)."</td>
		<td>".convert_neg($retailer_fee)."</td>
		<td>".convert_neg($distribution_fee)."</td>
		<td>".convert_neg($return_fee)."</td>
		<td>$transaction</td>
		<td>".convert_neg($Royalty_Amount)."</td>
		</tr>";
	}
	$query_select_royalty_processing->free();
	$db->close();

	echo "<tr><td colspan=9></td><td align='right' style='font-weight:bold;'>Total: </td><td style='font-weight:bold;'>".convert_neg($Total_TRA)."</td></tr>";

	//echo "</table>";



}

function display_storage_fees($GETPID, $GETSTARTDATE, $GETENDDATE) {

	global $use_db;


	//echo "<table>";

	echo "
	<th>Transaction Date
	<th>Date Posted
	<th>
	<th>
	<th>
	<th>
	<th>
	<th>
	<th>
	<th>
	<th>Storage Fee";

	include($_SERVER['DOCUMENT_ROOT'].'/assets/db/db.config.php');
	$db = new mysqli($dbserver, $dbuser, $dbpassword, $database);

	$query_select_royalty_processing = $db->query("
	SELECT
		DATE_FORMAT(Royalty_Month,'%m/%d/%Y') as Royalty_Month,
		Royalty_Amount,
		authors_net_royalties.Check_Batch_Timestamp, 
		DATE_FORMAT(date_posted,'%m/%d/%Y') as date_posted 
	FROM
		$use_db.authors_net_royalties
	INNER JOIN $use_db.contacts_isbns ON authors_net_royalties.projectid = contacts_isbns.projectid
	WHERE
		authors_net_royalties.projectid = $GETPID
	AND Royalty_Month BETWEEN '$GETSTARTDATE' and '$GETENDDATE'
	and royalty_classification like '%storage%'
	GROUP BY authors_net_royalties.id;");
	while($myrow = $query_select_royalty_processing->fetch_assoc()) {
		$Royalty_Month=$myrow['Royalty_Month'];
		$Royalty_Amount=$myrow['Royalty_Amount'];
		$royalty_classification=$myrow['royalty_classification'];
		$Check_Batch_Timestamp=$myrow['Check_Batch_Timestamp'];
		$date_posted=$myrow['date_posted'];

		$Total_TRA=$Total_TRA+$Royalty_Amount;


		if ($Check_Batch_Timestamp) { 
			$rowcolor="style='color:#006400'";
		} else {
			unset($rowcolor);
		}

		echo "<tr $rowcolor>
		<td width='10%'>$Royalty_Month</td>
		<td>$date_posted</td>
		<td width='10%'>&nbsp;</td>
		<td width='10%'>&nbsp;</td>
		<td width='10%'>&nbsp;</td>
		<td width='10%'>&nbsp;</td>
		<td width='10%'>&nbsp;</td>
		<td width='10%'>&nbsp;</td>
		<td width='10%'>&nbsp;</td>
		<td width='10%'>&nbsp;</td>
		<td width='10%'>".convert_neg($Royalty_Amount)."</td>
		</tr>";
	}
	$query_select_royalty_processing->free();
	$db->close();

	echo "<tr><td colspan=10 align='right' style='font-weight:bold;'>Total: </td><td style='font-weight:bold;'>".convert_neg($Total_TRA)."</td></tr>";

	//echo "</table>";


}

function display_mbo_sales($GETPID, $GETSTARTDATE, $GETENDDATE) {

	global $use_db;

	//echo "<table>";

	echo "
	<th width='10%'>Transaction Date
	<th width='10%'>Date Posted
	<th width='10%'>Format
	<th width='10%'>Qty
	<th width='10%'>Total Unit Price
	<th width='10%'>Handling Fee
	<th width='10%'>Promo Code
	<th width='10%'>&nbsp;
	<th width='10%'>&nbsp;
	<th width='10%'>Tracking
	<th width='10%'>Net Comp";

	include($_SERVER['DOCUMENT_ROOT'].'/assets/db/db.config.php');
	$db = new mysqli($dbserver, $dbuser, $dbpassword, $database);

	$query_select_royalty_processing = $db->query("
	SELECT
		DATE_FORMAT(Royalty_Month,'%m/%d/%Y') as Royalty_Month,
		format, 
		(
			CASE WHEN Royalty_Books_Sold IS NULL THEN 0 ELSE Royalty_Books_Sold END - CASE WHEN Royalty_Books_Returned IS NULL THEN 0 ELSE abs(Royalty_Books_Returned) END
		) AS Net_Sold,
		total_unit_price, 
		handling_fee, 
		promo_code, 
		tracking, 
		Royalty_Amount,
		authors_net_royalties.Check_Batch_Timestamp, 
		DATE_FORMAT(date_posted,'%m/%d/%Y') as date_posted  
	FROM
		$use_db.authors_net_royalties
	INNER JOIN $use_db.contacts_isbns ON authors_net_royalties.projectid = contacts_isbns.projectid
	WHERE
		authors_net_royalties.projectid = $GETPID
	AND Royalty_Month BETWEEN '$GETSTARTDATE' and '$GETENDDATE'
	and Royalty_Source like '%MyBookOrders%'
	GROUP BY authors_net_royalties.id;");
	while($myrow = $query_select_royalty_processing->fetch_assoc()) {
		$Royalty_Month=$myrow['Royalty_Month'];
		$format=$myrow['format'];
		$Net_Sold=$myrow['Net_Sold'];
		$total_unit_price=$myrow['total_unit_price'];
		$handling_fee=$myrow['handling_fee'];
		$promo_code=$myrow['promo_code'];
		//$tracking=$myrow['tracking'];
		$Royalty_Amount=$myrow['Royalty_Amount'];
		$royalty_classification=$myrow['royalty_classification'];
		$Check_Batch_Timestamp=$myrow['Check_Batch_Timestamp'];
		$date_posted=$myrow['date_posted'];

		$Total_TRA=$Total_TRA+$Royalty_Amount;


		if ($Check_Batch_Timestamp) { 
			$rowcolor="style='color:#006400'";
		} else {
			unset($rowcolor);
		}

		echo "<tr $rowcolor>
		<td width='10%'>$Royalty_Month</td>
		<td>$date_posted</td>
		<td width='10%'>$format</td>
		<td width='10%'>$Net_Sold</td>
		<td width='10%'>".convert_neg($total_unit_price)."</td>
		<td width='10%'>".convert_neg($handling_fee)."</td>
		<td width='10%'>$promo_code</td>
		<td width='10%'>&nbsp;</td>
		<td width='10%'>&nbsp;</td>
		<td width='10%'>$tracking</td>
		<td width='10%'>".convert_neg($Royalty_Amount)."</td>
		</tr>";
	}
	$query_select_royalty_processing->free();
	$db->close();

	echo "<tr><td colspan=9></td><td align='right' style='font-weight:bold;'>Total: </td><td style='font-weight:bold;'>".convert_neg($Total_TRA)."</td></tr>";

	//echo "</table>";

}


function display_royalty_adjustment($GETPID, $GETSTARTDATE, $GETENDDATE) {

	return;

	global $use_db;


	//echo "<table>";

	echo "
	<th>Date
	<th>
	<th>
	<th>
	<th>
	<th>
	<th>
	<th>
	<th>
	<th>Adjustment";

	include($_SERVER['DOCUMENT_ROOT'].'/assets/db/db.config.php');
	$db = new mysqli($dbserver, $dbuser, $dbpassword, $database);

	$query_select_royalty_processing = $db->query("
	SELECT
		DATE_FORMAT(Royalty_Month,'%m/%d/%Y') as Royalty_Month,
		Royalty_Amount 
	FROM
		$use_db.authors_net_royalties
	WHERE
		authors_net_royalties.projectid = $GETPID
	INNER JOIN $use_db.contacts_isbns ON authors_net_royalties.projectid = contacts_isbns.projectid
	AND Royalty_Month BETWEEN '$GETSTARTDATE' and '$GETENDDATE'
	and Royalty_Source like '%adjust%
	GROUP BY authors_net_royalties.id;");
	while($myrow = $query_select_royalty_processing->fetch_assoc()) {
		$Royalty_Month=$myrow['Royalty_Month'];
		$Royalty_Amount=$myrow['Royalty_Amount'];
		$royalty_classification=$myrow['royalty_classification'];

		$Total_TRA=$Total_TRA+$Royalty_Amount;


		echo "<tr>
		<td width='10%'>$Royalty_Month</td>
		<td width='10%'>&nbsp;</td>
		<td width='10%'>&nbsp;</td>
		<td width='10%'>&nbsp;</td>
		<td width='10%'>&nbsp;</td>
		<td width='10%'>&nbsp;</td>
		<td width='10%'>&nbsp;</td>
		<td width='10%'>&nbsp;</td>
		<td width='10%'>&nbsp;</td>
		<td width='10%'>".convert_neg($Royalty_Amount)."</td>
		</tr>";
	}
	$query_select_royalty_processing->free();
	$db->close();

	echo "<tr><td colspan='9' align='right' style='font-weight:bold;'>Total: </td><td style='font-weight:bold;'>".convert_neg($Total_TRA)."</td></tr>";

	//echo "</table>";


}


function convert_neg($val) {
	if (!$val) { return '-'; }

	if ($val<0) { return '($'.number_format(abs($val), 2, '.', ',').')'; }

	if ($val>=0) { return '$'.number_format($val, 2, '.', ','); }
}

?>