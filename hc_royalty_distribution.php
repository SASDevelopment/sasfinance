<?php include_once($_SERVER["DOCUMENT_ROOT"]."/cvbtech/authenticate.php"); ?>
<?
$use_db='sasroyalties';
//$use_db='xulonroyalties';

$GETCID=$_REQUEST['GETCID'];

if (!$_REQUEST['GETCID']) { 
	echo 'An error occurred.';
	die;
}

$current_distribution_balance=distrep_get_distribution_balance();
?>
<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head><title>


</title>
<meta charset="utf-8" />
<meta content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0" name="viewport" />
<meta content="yes" name="apple-mobile-web-app-capable" />
<meta content="black" name="apple-mobile-web-app-status-bar-style" />
<link href="https://fonts.googleapis.com/css?family=Raleway:400,300,500,600,700,200,100,800" type="text/css" rel="stylesheet" />
<link href="/assets/hillcrest/bootstrap.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" />
<link href="/assets/hillcrest/styles-responsive.css" rel="stylesheet" />
<link href="/assets/hillcrest/plugins.css" rel="stylesheet" />
<link media="print" type="text/css" href="/assets/hillcrest/print.css" rel="stylesheet" />
<link href="/assets/hillcrest/jquery-ui-1.8.4.custom.css" rel="stylesheet" type="text/css" />
<link href="/assets/hillcrest/ajax_table.css" rel="stylesheet" type="text/css" />


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>

	<style type="text/css">
	   table.ex1 {border-spacing: 0}
	   table.ex1 td, th {padding: 0 0.2em}
	   table.ex1 tr:nth-child(odd) {color: #000; background: #FFF}
	   table.ex1 tr:nth-child(even) {color: #000; background: #F5F5F5}
	</style>
  
    <style type="text/css">
        .display td {
            font-size: 11px;
			border-bottom:1pt solid #D8D8D8;
        }

		.display tr { 
			height: 50px;
		}

		.display th { 
			line-height: 20px;
		}

		/*.display tr:nth-child(odd) {color: #000; background: #FFF; line-height: 50px;}
		.display tr:nth-child(even) {color: #000; background: #F5F5F5; line-height: 50px;}*/


    </style>

    <link href="/assets/hillcrest/ajax_table.css" rel="stylesheet" type="text/css" />

</head>
<body>

        <div>
            


    <h2 class="pageSect">Distribution Historical Data</h2>

    <div class="row" style="padding: 7px 10px 15px 10px; font-weight: bold; font-size: 15px;">
        &emsp;Current Distribution Balance: <?=distrep_convert_neg($current_distribution_balance)?>
    </div>

    <div id="transcation_container" style="width: 99%; border: 1px solid #d9d9d9; padding: 8px 4px 24px 4px;">
        <table width="90%" cellpadding="0" cellspacing="1" border="0" class="display" id="tranlist">
            <thead>
                    <th width="17%">Transaction Date</th>
                    <th width="17%">Date Posted</th>
                    <th width="60%">Description</th>
                    <th>Transaction Amount</th>
                    <th>Distribution Balance</th>
            </thead>

<?
$beginning_of_time=distrep_get_first_royalty_month();
$start = $month = strtotime($beginning_of_time);
//$start = $month = strtotime('2016-01-01');
$end = strtotime(date('Y-m-t'));
while($month < $end)
{
     //$beginmonth=date('Y-m-16', $month);
     $beginmonth=date('Y-m-01', $month);
	 //$reportdate=$beginmonth=date('Y-m-16', $month);
	 $reportdate=date('Y-m-t', $month);
     $friendlymonth=date('M Y', $month);
     $endmonth=date('Y-m-t', $month);
     $month = strtotime("+1 month", $month);

	 echo distrep_get_distribution_balance_by_month($friendlymonth, $reportdate, $beginmonth, $endmonth);

	 echo "<tr style='background: #F5F5F5; '>
	 <td style='font-weight:bold;'>$friendlymonth Balance</td>
	 <td></td>
	 <td></td>
	 <td>".distrep_convert_neg(distrep_get_transaction_total_by_date_range($beginmonth, $endmonth))."</td>
	 <td>".distrep_convert_neg(distrep_get_distribution_balance_by_date_range($beginmonth,$endmonth))."</td>
	 </tr>";


}
?>


            <tfoot>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>

   
	



    <div class="row" style="padding: 7px 10px 15px 10px; font-weight: bold; font-size: 15px;">
        &emsp;Current Distribution Balance: <?=distrep_convert_neg($current_distribution_balance)?>
    </div>

        </div>
</body>
</html>

<?php

function distrep_get_distribution_balance() { 

	if (!$_REQUEST['GETCID']) { return; }

	global $use_db, $use_xp_db;

	include($_SERVER['DOCUMENT_ROOT'].'/assets/db/db.config.php');
	$db = new mysqli($dbserver, $dbuser, $dbpassword, $database);

	$Contact_ID=mysqli_real_escape_string($db, $_REQUEST['GETCID']);

	$query_anr = $db->query("select sum(Royalty_Amount) as distribution_balance from $use_db.authors_net_royalties where Contact_ID='$Contact_ID' and Check_Batch_Timestamp IS NULL and imprint_brand_id=27;");
	//$num_results = mysqli_affected_rows($db);
	while($myrow = $query_anr->fetch_assoc()) {
		$distribution_balance=$myrow['distribution_balance'];
	}
	$query_anr->free();
	$db->close();

	return $distribution_balance;


}
/*
function distrep_get_distribution_balance_by_date_range($endmonth) { 
	global $beginning_of_time;

	global $use_db, $use_xp_db;

	if (!$_REQUEST['GETCID']) { return; }

	include($_SERVER['DOCUMENT_ROOT'].'/assets/db/db.config.php');
	$db = new mysqli($dbserver, $dbuser, $dbpassword, $database);

	$Contact_ID=mysqli_real_escape_string($db, $_REQUEST['GETCID']);

	$query_anr = $db->query("select sum(Royalty_Amount) as distribution_balance from $use_db.authors_net_royalties where Contact_ID='$Contact_ID' and Check_Batch_Timestamp IS NULL and imprint_brand_id=27 and Royalty_Month between '$beginning_of_time' and '$endmonth';");
	//$num_results = mysqli_affected_rows($db);
	while($myrow = $query_anr->fetch_assoc()) {
		$distribution_balance=$myrow['distribution_balance'];
	}
	$query_anr->free();
	$db->close();

	return $distribution_balance;


}
*/

function distrep_get_distribution_balance_by_date_range($beginmonth, $endmonth) { 
	global $beginning_of_time;
	global $use_db, $use_xp_db;

	if (!$_REQUEST['GETCID']) { return; }

	include($_SERVER['DOCUMENT_ROOT'].'/assets/db/db.config.php');
	$db = new mysqli($dbserver, $dbuser, $dbpassword, $database);

	$Contact_ID=mysqli_real_escape_string($db, $_REQUEST['GETCID']);

	$query_anr = $db->query("select sum(Royalty_Amount) as distribution_balance from $use_db.authors_net_royalties where Contact_ID='$Contact_ID' and Check_Batch_Timestamp IS NULL and imprint_brand_id=27 and date_posted between '$beginmonth' and '$endmonth';");
	//$num_results = mysqli_affected_rows($db);
	while($myrow = $query_anr->fetch_assoc()) {
		$distribution_balance=$myrow['distribution_balance'];
	}
	$query_anr->free();
	$db->close();

	return $distribution_balance;


}

function distrep_get_transaction_total_by_date_range($beginmonth, $endmonth) { 

	if (!$_REQUEST['GETCID']) { return; }

	global $use_db, $use_xp_db;

	include($_SERVER['DOCUMENT_ROOT'].'/assets/db/db.config.php');
	$db = new mysqli($dbserver, $dbuser, $dbpassword, $database);

	$Contact_ID=mysqli_real_escape_string($db, $_REQUEST['GETCID']);

	$query_anr = $db->query("select sum(Royalty_Amount) as transaction_total from $use_db.authors_net_royalties where Contact_ID='$Contact_ID' and imprint_brand_id=27 and Royalty_Month between '$beginmonth' and '$endmonth';");
	//$num_results = mysqli_affected_rows($db);
	while($myrow = $query_anr->fetch_assoc()) {
		$transaction_total=$myrow['transaction_total'];
	}
	$query_anr->free();
	$db->close();

	return $transaction_total;


}

function distrep_get_distribution_balance_by_month($friendlymonth, $reportdate, $beginmonth, $endmonth) { 
	//return "<tr><td>distrep_get_distribution_balance_by_month($friendlymonth, $reportdate, $beginmonth, $endmonth)</td></tr>";

	if (!$_REQUEST['GETCID'] || !$beginmonth || !$endmonth) { return; }

	global $use_db, $use_xp_db;

	$row='';

	include($_SERVER['DOCUMENT_ROOT'].'/assets/db/db.config.php');
	$db = new mysqli($dbserver, $dbuser, $dbpassword, $database);

	$Contact_ID=mysqli_real_escape_string($db, $_REQUEST['GETCID']);

	$sql="select Royalty_Month, date_posted, case WHEN Royalty_Source like '%Distribution Balance%' then Royalty_Source ELSE concat(ORG_TITLE, ' $friendlymonth Monthly Sales Total') END as ORG_TITLE, sum(Royalty_Amount) as transaction_amount from $use_db.authors_net_royalties where Contact_ID='$Contact_ID' and Royalty_Month between '$beginmonth' and '$endmonth' and imprint_brand_id=27 group by Royalty_ISBN";

	//echo '<li>'.$sql;

	$query_anr = $db->query($sql);
	//$num_results = mysqli_affected_rows($db);
	while($myrow = $query_anr->fetch_assoc()) {
		$Royalty_Month=stripslashes($myrow['Royalty_Month']);
		$date_posted=stripslashes($myrow['date_posted']);
		$ORG_TITLE=stripslashes($myrow['ORG_TITLE']);
		$transaction_amount=$myrow['transaction_amount'];

		$row .= "<tr>
		<td>".date('m/d/Y', strtotime($reportdate))."</td>
		<td>".date('m/d/Y', strtotime($date_posted))."</td>
		<td>$ORG_TITLE</td>
		<td>".distrep_convert_neg($transaction_amount)."</td>
		<td>".distrep_convert_neg(distrep_get_distribution_balance_by_date_range($beginmonth,$endmonth))."</td>
		</tr>";

	} 
	$query_anr->free();
	$db->close();

	return $row;


}

function distrep_convert_neg($val) {
	if (!$val) { return '-'; }

	if ($val<0) { return '($'.number_format(abs($val), 2, '.', ',').')'; }

	if ($val>=0) { return '$'.number_format($val, 2, '.', ','); }
}

function distrep_get_first_royalty_month() { 

	if (!$_REQUEST['GETCID']) { return; }

	global $use_db, $use_xp_db;

	include($_SERVER['DOCUMENT_ROOT'].'/assets/db/db.config.php');
	$db = new mysqli($dbserver, $dbuser, $dbpassword, $database);

	$Contact_ID=mysqli_real_escape_string($db, $_REQUEST['GETCID']);

	$query_select_authors_net_royalties = $db->query("select min(Royalty_Month) as first_royalty_month from $use_db.authors_net_royalties where Contact_ID='$Contact_ID' and imprint_brand_id=27;");
	//$num_results = mysqli_affected_rows($db);
	while($myrow = $query_select_authors_net_royalties->fetch_assoc()) {
		$first_royalty_month=$myrow['first_royalty_month'];
	}
	$query_select_authors_net_royalties->free();
	$db->close();

	return (substr($first_royalty_month,0,7)).'-01';


}

