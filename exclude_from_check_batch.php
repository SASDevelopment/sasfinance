<?php 
include_once($_SERVER["DOCUMENT_ROOT"]."/cvbtech/authenticate.php"); 
include_once('assets/functions.royalties3.php'); 
?>
<style>
body table, tr, td, ul, li, p, a {
	font-family: Arial, Helvetica, sans-serif;
	font-size: small;
}

h2 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: medium;
	background-color: #6A98C7;
	color:white;
	padding:5;
}
</style>


<style>
/*excel*/
td, th {
  border: solid 1px gray;
}
tr {
  border-bottom: solid 1px gray;
}
table {
  border-collapse: collapse;
}

a:link {
text-decoration:none;
}
a:hover {
text-decoration:underline;
}


table.bb {
  border-collapse: collapse;
}
table.bb td th {
  border-bottom: 1px solid black; 
}
table.bb tr:first-child td {
  border-top: 0;
}
table.bb tr td:first-child {
  border-left: 0;
}
table.bb tr:last-child td {
  border-bottom: 0;
}
table.bb tr td:last-child {
  border-right: 0;
}
</style>

<?
$THISENDDATE='2018-03-31';

echo "<!--h1>Xulon Authors End Date: ".$THISENDDATE."</h1--><table border=1>
<th>Contact ID
<th>Account Name
<th>Total Royalty Amount
<th>Project ID Detail
<th>Incorrect W9
<th>Bad Address
<th>Ok to Pay
<th>Don't Pay

";

include($_SERVER['DOCUMENT_ROOT'].'/assets/db/db.connect.php');

$query_call_sp = mysql_query("CALL xulonroyalties.account_flags()"); 

$query_select_authors_net_royalties = mysql_query("SELECT
	authors_net_royalties.Contact_ID AS CID,
	authors_net_royalties.Account_Name AS Account_Name,
	(SELECT incorrect_w9 from xulonpress.contacts where Contact_ID=CID limit 1) as incorrect_w9,
	(SELECT bad_address from xulonpress.contacts where Contact_ID=CID limit 1) as bad_address,
	Sum(
		authors_net_royalties.Royalty_Amount
	) AS SUM_ROYALTY_AMOUNT,
	account_flags.count_flags
FROM
	xulonroyalties.authors_net_royalties
INNER JOIN xulonroyalties.account_flags ON authors_net_royalties.Contact_ID = account_flags.Contact_ID
WHERE
	Check_Batch_Timestamp IS NULL/* AND Royalty_Month<='$THISENDDATE' and imprint_brand_id=1*/
GROUP BY
	authors_net_royalties.Contact_ID
HAVING
	SUM_ROYALTY_AMOUNT >= 25", $db);
//$num_results = mysql_num_rows($query_select_authors_net_royalties);
if ($myrow = mysql_fetch_array($query_select_authors_net_royalties)) {

do {
	$Account_Name=$myrow['Account_Name'];
	$SUM_ROYALTY_AMOUNT=$myrow['SUM_ROYALTY_AMOUNT'];
	$Contact_ID=$myrow['CID'];
	$incorrect_w9=$myrow['incorrect_w9'];
	$bad_address=$myrow['bad_address'];


	if ($incorrect_w9) { 
		$incorrect_w9='Incorrect W9';
	} else { 
		unset ($incorrect_w9);
	}
	if ($bad_address) { 
		$bad_address='Bad Address';
	} else { 
		unset ($bad_address);
	}	

	echo "<tr>
	<td>$Contact_ID</td>
	<td>$Account_Name</td>
	<td align='center'>$SUM_ROYALTY_AMOUNT</td>
	<td>
	
	<table width=100% class='bb'>
	<tr style='font-weight:bold; font-size:x-small; text-align:center;'><td>PID</td><td>Royalty Amount</td><td>Status</td></tr>";

	$query_select_anr_check_batch_all = mysql_query("SELECT
	accounts.Contact_ID as CID, 
	accounts.projectid AS PID, 
	xulonroyalties.get_tra_end_date_cid(accounts.Contact_ID, '$THISENDDATE') as CID_ROYALTY_AMOUNT,
	xulonroyalties.get_tra_end_date_pid(accounts.Contact_ID, accounts.projectid, '$THISENDDATE') as PID_ROYALTY_AMOUNT,
	xulonpress.accounts.Payment_Problem_Status, 
	(SELECT date_terminated from xulonpress.accounts_terminated_log where projectid=PID) as date_terminated, 
	DATE_SUB(CURDATE(), INTERVAL 6 month) as max_terminated_date_ok_to_pay
	FROM
	xulonpress.accounts
	WHERE
	accounts.Contact_ID = '$Contact_ID';
	", $db);
	//$num_results = mysql_num_rows($query_select_anr_check_batch_all);
	if ($myrow = mysql_fetch_array($query_select_anr_check_batch_all)) {

	do {
		$projectid=$myrow['PID'];
		$date_terminated=$myrow['date_terminated'];
		$CID_ROYALTY_AMOUNT=$myrow['CID_ROYALTY_AMOUNT'];
		$PID_ROYALTY_AMOUNT=$myrow['PID_ROYALTY_AMOUNT'];
		$Payment_Problem_Status=$myrow['Payment_Problem_Status'];
		$max_terminated_date_ok_to_pay=$myrow['max_terminated_date_ok_to_pay'];

		if ($date_terminated<$max_terminated_date_ok_to_pay && ((stristr($Payment_Problem_Status,'TERMINATE') && !stristr($Payment_Problem_Status,'RENEWAL FEE')) || stristr($Payment_Problem_Status,'CANCEL'))) { 
			$OK_TO_PAY_OVERRIDE=1;
		} else { 
			unset ($OK_TO_PAY_OVERRIDE);
		}


		if (($OK_TO_PAY_OVERRIDE || 
			((stristr($Payment_Problem_Status,'PAYMENT PLAN') && $PID_ROYALTY_AMOUNT<0) || 
			(stristr($Payment_Problem_Status,'ON HOLD') && $PID_ROYALTY_AMOUNT<0) || 
			(stristr($Payment_Problem_Status,'TERMINATE') && $PID_ROYALTY_AMOUNT<0) || 
			(stristr($Payment_Problem_Status,'CANCEL') && $PID_ROYALTY_AMOUNT<0)) || 
			
			(!stristr($Payment_Problem_Status,'PAYMENT PLAN') && 
			!stristr($Payment_Problem_Status,'ON HOLD') && 
			!stristr($Payment_Problem_Status,'TERMINATE') && 
			!stristr($Payment_Problem_Status,'CANCEL')  
			))
				&& !$bad_address && !$incorrect_w9


		)  {
			$OK_TO_PAY = $OK_TO_PAY + $PID_ROYALTY_AMOUNT;
			$color='style="color:green;"';
		} else { 
			$DONT_PAY = $DONT_PAY + $PID_ROYALTY_AMOUNT;
			$color='style="color:gray;"';
			royalties3_anr_exclude_from_check_batch($projectid);
		}

		if ($date_terminated) { 
			$display_date_terminated='('.$date_terminated.')';
		} else { 
			unset ($display_date_terminated);
		}



		echo "<tr style='bb'>
		<td width=100>$projectid</td>
		<td width=100 align='center' $color>$PID_ROYALTY_AMOUNT</td>
		<td>$Payment_Problem_Status $display_date_terminated</td>

		</tr>";



	} while ($myrow = mysql_fetch_array($query_select_anr_check_batch_all)); }


	echo "</table></td>
	<td align='center'>$incorrect_w9</td>
	<td align='center'>$bad_address</td>
	<td align='center' style='color:green;'>$OK_TO_PAY</td>
	<td align='center' style='color:gray;'>$DONT_PAY</td>
	</tr>";

	unset ($OK_TO_PAY, $DONT_PAY);

	$count++;

} while ($myrow = mysql_fetch_array($query_select_authors_net_royalties)); }
mysql_close ($db);

echo "</table>";

echo "$count records";




?>