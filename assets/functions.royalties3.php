<?php

function royalties3_currencyConverter($currency_from,$currency_to,$currency_input){
    $yql_base_url = "http://query.yahooapis.com/v1/public/yql";
    $yql_query = 'select * from yahoo.finance.xchange where pair in ("'.$currency_from.$currency_to.'")';
    $yql_query_url = $yql_base_url . "?q=" . urlencode($yql_query);
    $yql_query_url .= "&format=json&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys";
    $yql_session = curl_init($yql_query_url);
    curl_setopt($yql_session, CURLOPT_RETURNTRANSFER,true);
    $yqlexec = curl_exec($yql_session);
    $yql_json =  json_decode($yqlexec,true);
    $currency_output = (float) $currency_input*$yql_json['query']['results']['rate']['Rate'];

    return $currency_output;
}

function royalties3_load_royalty_currency_data() {
	$currency_input = 1;
	$currency_to = "USD";

	include($_SERVER['DOCUMENT_ROOT'].'/assets/db/db.config.php');
	$db = new mysqli($dbserver, $dbuser, $dbpassword, $database);

	$arr = array('GBP', 'EUR', 'JPY', 'CAD', 'INR', 'AUD', 'BRL', 'MXN', 'HKD', 'USD', 'NOK', 'SEK', 'DKK', 'CHF', 'NZD', 'SEK', 'NOK');
	foreach ($arr as &$currency_from) {
		$query_insert_royalty_currency_data = "insert ignore into sasroyalties.royalty_currency_data (currency_from, currency_to, currency_output, record_timestamp, last_updated_timestamp, record_date) values ('$currency_from', '$currency_to', ".royalties3_currencyConverter($currency_from,$currency_to,$currency_input).", NOW(), NOW(), CURDATE())";
		$resultinsert_royalty_currency_data=$db->query($query_insert_royalty_currency_data);

		$query_insert_royalty_currency_data = "insert ignore into sasroyalties.royalty_currency_data (currency_from, currency_to, currency_output, record_timestamp, last_updated_timestamp, record_date) values ('$currency_to', '$currency_from', ".royalties3_currencyConverter($currency_to,$currency_from,$currency_input).", NOW(), NOW(), CURDATE())";
		$resultinsert_royalty_currency_data=$db->query($query_insert_royalty_currency_data);
	}
	$db->close();
}

function royalties3_import_data_from_temp_tables() {
	include($_SERVER['DOCUMENT_ROOT'].'/assets/db/db.config.php');
	$db = new mysqli($dbserver, $dbuser, $dbpassword, $database);

	$query_sp = "CALL sasroyalties.royalty_processing()";
	$query_call_sp=$db->query($query_sp);

	$db->close();
}

function royalties3_new_datepicker() {
?>
	<link rel="stylesheet" href="/assets/css/jquery-ui.css">
	<script src="/assets/js/jquery-1.10.2.js"></script>
	<script src="/assets/js/jquery-ui.js"></script>
	<link rel="stylesheet" href="/assets/css/style.css">
	<script>
		$(function() {
			$( ".datepicker" ).datepicker({ dateFormat: "yy-mm-dd" });
		});
	</script>
<?php
}


function royalties3_royalty_field_form($imprint_brand_id, $field_name, $table_name, $size, $field_type) {

	if (($table_name != 'royalty_period' && $table_name !='current_reporting_end_date') || !$imprint_brand_id || !$field_name || !$table_name) { 
		return; 
	}

	if ($field_type=='date') { 
		$datepicker="class='datepicker browser-default'";
	} else {
		$datepicker="class='browser-default'";
	}

	if ($field_type=='currency') { 
		$currency='$';
	} else { 
		$currency='';
	}

	$size="size='$size'";

	include($_SERVER['DOCUMENT_ROOT'].'/assets/db/db.config.php');
	$db = new mysqli($dbserver, $dbuser, $dbpassword, $database);

	$field_name=mysqli_real_escape_string($db, $field_name);
	$table_name=mysqli_real_escape_string($db, $table_name);

	$sql="SELECT id, $field_name FROM sasroyalties.$table_name where imprint_brand_id=$imprint_brand_id";

	$query_select_royalty_period = $db->query($sql);
	while($myrow = $query_select_royalty_period->fetch_assoc()) {
		$id=$myrow['id'];
		$field_value=$myrow[$field_name];
		$FORMNAME=$field_name.$imprint_brand_id;
		$FORMNAME=royalties3_repl($FORMNAME);

		echo "<form method='post' name='$FORMNAME' id='$FORMNAME' action='process.php'>";

		if ($field_name=='Royalty_Period') { 
			echo royalties3_get_quarter_options($field_name, $field_value);
		} else { 
			echo "$currency<input $datepicker type='text' name='$field_name' value='$field_value' $size>";
		}

		echo "
		<input type='hidden' name='table_name' value='$table_name'>
		<input type='hidden' name='_field_name' value='$field_name'>
		<input type='hidden' name='id' value='$id'>
		<button onclick=\"clearBox('results$FORMNAME')\" type='submit' style='border:none; background-color: Transparent;'>
		<i class=\"blue-text text-darken-2 material-icons\" style=\"vertical-align:middle\">save</i>
		</button>
		<span id='results$FORMNAME'></span>
		</form>
		
		";
	}

	$query_select_royalty_period->free();
	$db->close();

}

function royalties3_repl($str) { 
	return str_replace('_', '', $str);
}


function royalties3_show_tables_in_sasroyalties_dev() {

	include($_SERVER['DOCUMENT_ROOT'].'/assets/db/db.config.php');
	$db = new mysqli($dbserver, $dbuser, $dbpassword, $database);

	$myecho = "<table class=\"blue-text text-darken-2 bordered\"><th>Table Name<th># Records<th>Clear";
	$mymodal= '';

	$query_select_temp_ebook_bn = $db->query("
	SELECT TABLE_NAME, TABLE_ROWS
		 FROM INFORMATION_SCHEMA.TABLES 
		 WHERE TABLE_SCHEMA = 'sasroyalties_dev';");
	while($myrow = $query_select_temp_ebook_bn->fetch_assoc()) {
		$TABLE_NAME=$myrow['TABLE_NAME'];
		$TABLE_ROWS=$myrow['TABLE_ROWS'];

		$count++;

		if ($TABLE_NAME != 'Settings') { 
			$myecho .= "<tr><td>$TABLE_NAME</td><td>".number_format(royalties3_table_count($TABLE_NAME), 0, '.', ',')."</td><td><a class='modal-trigger' href='#modal".$TABLE_NAME."' onclick='closeToast();'><i class='small material-icons' style='vertical-align:middle'>delete</i></a></td></tr>";

			$mymodal .= "<!-- Modal Structure -->
			<div id='modal".$TABLE_NAME."' class='modal'>
			  <div class='modal-content'>
				<h5><i class='small material-icons' style='vertical-align:middle'>delete</i> Clear ".$TABLE_NAME."</h5>
				<p>Clicking 'Delete Records From ".$TABLE_NAME."' will delete all of the data currently stored in ".$TABLE_NAME." in Navicat so that you can re-import.</p>
			  </div>
			  <div class='modal-footer'>
				<a href='#!' class='modal-action modal-close waves-effect waves-light btn'><i class='material-icons left'>clear</i>Do Not Delete</a>
				<a href='index.php?step=2.".$count."&stepContent=Clear ".$TABLE_NAME."&process=1&clear_table=".$TABLE_NAME."#step2' class='modal-action modal-close waves-effect waves-light btn'><i class='material-icons left'>delete</i>Delete All Records From ".$TABLE_NAME."</a>
			  </div>
			</div>";
		}
	}
	$query_select_temp_ebook_bn->free();
	$db->close();

	$myecho .= "</table>";

	return $myecho.$mymodal;

}

function royalties3_imported_royalty_processing_data() {

	include($_SERVER['DOCUMENT_ROOT'].'/assets/db/db.config.php');
	$db = new mysqli($dbserver, $dbuser, $dbpassword, $database);

	$view_table='';

	$myecho = "<table class=\"blue-text text-darken-2 bordered\"><th>Temp Table<th># Temp records<th># Records imported<th># Records skipped";

	$query_select_temp_ebook_bn = $db->query("SELECT
		ORG_FILE_SOURCE,
		count(id) AS count
	FROM
		sasroyalties.royalty_processing
	WHERE
		ORG_FILE_SOURCE like 'temp%'
	GROUP BY
		ORG_FILE_SOURCE;");
	while($myrow = $query_select_temp_ebook_bn->fetch_assoc()) {
		$ORG_FILE_SOURCE=$myrow['ORG_FILE_SOURCE'];
		$count_org=royalties3_table_count($ORG_FILE_SOURCE);
		$count=$myrow['count'];
		$count_skipped=$count_org-$count;

		if ($count_skipped) { 
			$skipped=number_format($count_skipped, 0, '.', ',');
		} else { 
			$skipped='';
		}

		$myecho .= "<tr><td>$ORG_FILE_SOURCE</td><td>".number_format($count_org, 0, '.', ',')."</td><td><a class='modal-trigger' href='#modal".$ORG_FILE_SOURCE."view' onclick='closeToast();'>".number_format($count, 0, '.', ',')."</a></td><td><a class='modal-trigger' href='#modal".$ORG_FILE_SOURCE."skipped' onclick='closeToast();'>".$skipped."</a></td></tr>";

		if ($skipped) { 
			$mymodal .= "<!-- Modal Structure -->
			<div id='modal".$ORG_FILE_SOURCE."skipped' class='modal'>
			  <div class='modal-content'>
				<h5><i class='small material-icons' style='vertical-align:middle'>do_not_disturb</i> ".$ORG_FILE_SOURCE." skipped records</h5>
				<p>".royalties3_display_import_exceptions($ORG_FILE_SOURCE)."</p>
			  </div>
			  <div class='modal-footer'>
				<a href='#!' class='modal-action modal-close waves-effect waves-light btn'><i class='material-icons left'>clear</i>Close</a>
			  </div>
			</div>
			";
		}

		$view_table .= "<!-- Modal Structure -->
		<div id='modal".$ORG_FILE_SOURCE."view' class='modal'>
		  <div class='modal-content'>
			<h5><i class='small material-icons' style='vertical-align:middle'>face</i> View records imported from $ORG_FILE_SOURCE</h5>
			<p>
			  <div class='content'>
			  <iframe width='100%' height='75%' src='view_table.php?ORG_FILE_SOURCE=$ORG_FILE_SOURCE' frameborder='0' allowfullscreen></iframe></div>
			</p>
		  </div>
		  <div class='modal-footer'>
				<a href='index.php?step=6&stepContent=Viewed $ORG_FILE_SOURCE&process=1#step5' class='modal-action modal-close waves-effect waves-light btn'><i class='material-icons left'>clear</i>Close</a>
		  </div>
		</div>";

	}
	$query_select_temp_ebook_bn->free();
	$db->close();

	$myecho .= "</table>";

	return $myecho.$mymodal.$view_table;

}



function royalties3_view_zero_exceptions() {

	include($_SERVER['DOCUMENT_ROOT'].'/assets/db/db.config.php');
	$db = new mysqli($dbserver, $dbuser, $dbpassword, $database);

	$view_table='';
	$writetotext = "Royalty_Month\tRoyalty Source\tPID\tAccount Name\tBook Title\tISBN\tVendor ID\tBooks Sold\tBooks Returned\tRoyalty Amount\tORG Royalty Amount\tStatus\r\n";

	$myecho = "
	<table class=\"blue-text text-darken-2 bordered\"><th>Royalty Month<th>Royalty Source<th>PID<th>Account Name<th>Book Title<th>ISBN<th>Vendor ID<th>Books Sold<th>Books Returned<th>Royalty Amount<th>ORG Royalty Amount<th>Status";

	$query_select_temp_charged = $db->query("
	SELECT
		Royalty_Month, 
		Royalty_Source,
		projectid,
		Account_Name,
		Account_Site,
		Royalty_ISBN,
		Vendor_ID,
		Royalty_Books_Sold,
		Royalty_Books_Returned,
		Royalty_Amount,
		ORG_Royalty_Amount,
		Payment_Problem_Status
	FROM
		sasroyalties.authors_net_royalties
	WHERE
		ORG_Royalty_Amount > 0
	AND Royalty_Amount = 0
	AND Royalty_Source NOT LIKE 'distr%forward'
	AND Royalty_Source NOT LIKE 'adj%'

		");
	while($myrow = $query_select_temp_charged->fetch_assoc()) {
		$id=stripslashes($myrow['id']);
		$Account_ID=stripslashes($myrow['Account_ID']);
		$Contact_ID=stripslashes($myrow['Contact_ID']);
		$projectid=stripslashes($myrow['projectid']);
		$Account_Name=stripslashes($myrow['Account_Name']);
		$Account_Site=stripslashes($myrow['Account_Site']);
		$Payment_Problem_Status=stripslashes($myrow['Payment_Problem_Status']);
		$Royalty_Month=stripslashes($myrow['Royalty_Month']);
		$Royalty_Source=stripslashes($myrow['Royalty_Source']);
		$Royalty_ISBN=stripslashes($myrow['Royalty_ISBN']);
		$Vendor_ID=stripslashes($myrow['Vendor_ID']);
		$Royalty_Books_Sold=stripslashes($myrow['Royalty_Books_Sold']);
		$Royalty_Books_Returned=stripslashes($myrow['Royalty_Books_Returned']);
		$Royalty_Amount=stripslashes($myrow['Royalty_Amount']);
		$ORG_Royalty_Amount=stripslashes($myrow['ORG_Royalty_Amount']);

		if ($rowcount % 2) {
			$rowcolor="bgcolor='#F0F0F0'";
		} else {
			$rowcolor="bgcolor='white'";
			unset ($rowcount);
		}

		$countajax++;

		$rowcount++;
		$myecho .= "<tr $rowcolor>
		<td align=''>$Royalty_Month</td>
		<td align=''>$Royalty_Source</td>
		<td align='center'>$projectid</td>
		<td><a href='/xuloncontrolpanel/einstein/index.php?Account_ID=$Account_ID' target='_blank'>$Account_Name</a></td>
		<td align='left'>$Account_Site</td>
		<td align='center'>$Royalty_ISBN</td>
		<td align='center'>$Vendor_ID</td>
		<td align='right'>$Royalty_Books_Sold</td>
		<td align='right'>$Royalty_Books_Returned</td>
		<td align='right'>$".number_format($Royalty_Amount, 2, '.', ',')."</td>
		<td align='right'>$".number_format($ORG_Royalty_Amount, 2, '.', ',')."</td>
		<td>$Payment_Problem_Status</td>
		</tr>";

		$writetotext .= "$Royalty_Month\t$Royalty_Source\t$projectid\t$Account_Name\t$Account_Site\t$Royalty_ISBN\t$Vendor_ID\t$Royalty_Books_Sold\t$Royalty_Books_Returned\t$Royalty_Amount\t$ORG_Royalty_Amount\t$Payment_Problem_Status\r\n";

		$count++;

	}

	$query_select_temp_charged->free();
	$db->close();

	$myecho .= "</table>
	";

	$filePointer = fopen($_SERVER["DOCUMENT_ROOT"]."/xulonreports/csv/".$_SESSION["xulonname"]."_zero_exceptions.xls", "w");
	fputs ($filePointer, "$writetotext", 2000000);
	fclose($filePointer);
	
	$myecho .= "<p><form method='POST' action='/xulonreports/csv/".$_SESSION['xulonname']."_zero_exceptions.xls' target='_blank'><input type='submit' name='button2' id='button2' value='Export CSV File' style='width:150px;' onclick='this.form.submit();'></form></p>";


	return $myecho;

}



function royalties3_view_charged_royalties() {



	include($_SERVER['DOCUMENT_ROOT'].'/assets/db/db.config.php');
	$db = new mysqli($dbserver, $dbuser, $dbpassword, $database);


	$view_table='';
	$writetotext = "Charge/Do Not Charge\tPID\tAccount Name\tLast Renewal Due Date\tRenewal Fee Qty\tRenewal Fee Qty Paid\tRenewal Fee Amount\tCurrent Distribution Balance\tQty to Charge\tTotal to Charge\tDistribution Balance Remaining After Charging Royalties\tPayment Problem Status\r\n";

	$myecho = "<!--form action='#'-->
	<table class=\"blue-text text-darken-2 bordered\"><th>PID<th>Account Name<th>Last Renewal Due Date<th>Renewal Fee Qty<th>Renewal Fee Qty Paid<th>Renewal Fee Amount<th>Current Distribution Balance<th>Qty to Charge<th>Total to Charge<th>Distribution Balance Remaining<br>After Charging Royalties<th>Payment Problem Status";

	$query_select_temp_charged = $db->query("/*SELECT
			charge_overdue_renewals_to_royalties_sas.id,
			charge_overdue_renewals_to_royalties_sas.Contact_ID,
			charge_overdue_renewals_to_royalties_sas.projectid,
			charge_overdue_renewals_to_royalties_sas.Account_Name,
			charge_overdue_renewals_to_royalties_sas.Account_Site,
			charge_overdue_renewals_to_royalties_sas.Temp_Renewal_Due_Date,
			charge_overdue_renewals_to_royalties_sas.Renewal_Fee_Qty,
			charge_overdue_renewals_to_royalties_sas.Renewal_Fee_Qty_Paid,
			charge_overdue_renewals_to_royalties_sas.Renewal_Fee_Amount,
			charge_overdue_renewals_to_royalties_sas.Renewal_Fee_Amount_Due_Late,
			charge_overdue_renewals_to_royalties_sas.TOTAL_TRA,
			charge_overdue_renewals_to_royalties_sas.TRA_AFTER_PAYMENT,
			charge_overdue_renewals_to_royalties_sas.imprint_brand_id,
			charge_overdue_renewals_to_royalties_sas.Payment_Problem_Status,
			charge_overdue_renewals_to_royalties_sas.eorders_ordernumber
		FROM
			xulonroyalties.charge_overdue_renewals_to_royalties_sas*/
			
			SELECT
		Contact_ID,
		accounts.projectid, 
		accounts.projectid as PID,
		Account_Name,
		Account_Site,
		Account_ID, 
		Temp_Renewal_Due_Date,
		Renewal_Fee_Qty,
		(
			Renewal_Fee_Qty_Paid + Renewal_Fee_Qty_Charged
		) AS Renewal_Fee_Qty_Paid,
		Renewal_Fee_Product_Price as Renewal_Fee_Amount,
		Renewal_Fee_Amount_Due_Late,
		@TRA := xulonroyalties.get_sas_tra (Contact_ID) AS TOTAL_TRA,
		@TRA - Renewal_Fee_Product_Price AS TRA_AFTER_PAYMENT,
		imprint_brand_id, 
		Payment_Problem_Status, 
		(SELECT Last_Modified_Date from xulonpress.es_change_log where projectid=PID and (Update_Query like '%CANCEL%' or Update_Query like '%TERMINATE%') limit 1) as Cancel_Date 
	FROM
		sasroyalties.accounts
	inner join xulonroyalties.charge_royalties_projectid on accounts.projectid=charge_royalties_projectid.projectid
	WHERE
		Renewal_Fee_Amount_Due_Late > 0
	AND Renewal_Fee_Times_Late > 0
	AND (Non_Renewal IS NULL or Non_Renewal=0)
	AND CURDATE() > Temp_Renewal_Due_Date
	AND Program_Order_Date IS NOT NULL
	AND Program_Order_Date != '0000-00-00'
	AND accounts.Payment_Problem_Status not like 'TEST%'
	/*AND accounts.Payment_Problem_Status not like 'CANCEL%'
	AND accounts.Payment_Problem_Status not like 'TERMINATE%'*/
	HAVING 
	  (Temp_Renewal_Due_Date<Cancel_Date or Cancel_Date is null) AND TOTAL_TRA>Renewal_Fee_Product_Price
	ORDER BY
		Temp_Renewal_Due_Date;

		");
	while($myrow = $query_select_temp_charged->fetch_assoc()) {
		$id=stripslashes($myrow['id']);
		$Account_ID=stripslashes($myrow['Account_ID']);
		$Contact_ID=stripslashes($myrow['Contact_ID']);
		$projectid=stripslashes($myrow['projectid']);
		$Account_Name=stripslashes($myrow['Account_Name']);
		$Account_Site=stripslashes($myrow['Account_Site']);
		$Temp_Renewal_Due_Date=stripslashes($myrow['Temp_Renewal_Due_Date']);
		$Renewal_Fee_Qty=stripslashes($myrow['Renewal_Fee_Qty']);
		$Renewal_Fee_Qty_Paid=stripslashes($myrow['Renewal_Fee_Qty_Paid']);
		$Renewal_Fee_Amount=stripslashes($myrow['Renewal_Fee_Amount']);
		$Renewal_Fee_Amount_Due_Late=stripslashes($myrow['Renewal_Fee_Amount_Due_Late']);
		$TOTAL_TRA=stripslashes($myrow['TOTAL_TRA']);
		$TRA_AFTER_PAYMENT=stripslashes($myrow['TRA_AFTER_PAYMENT']);
		$imprint_brand_id=stripslashes($myrow['imprint_brand_id']);
		$Payment_Problem_Status=stripslashes($myrow['Payment_Problem_Status']);
		$eorders_ordernumber=stripslashes($myrow['eorders_ordernumber']);

		
		if (!$CHARGED_AMOUNT[$Contact_ID]) { 
			$CHARGED_AMOUNT[$Contact_ID]=$TOTAL_TRA;
		}

		if ($rowcount % 2) {
			$rowcolor="bgcolor='#F0F0F0'";
		} else {
			$rowcolor="bgcolor='white'";
			unset ($rowcount);
		}

		$Renewals_To_Pay=$Renewal_Fee_Qty-$Renewal_Fee_Qty_Paid;

		$Ability_To_Pay=floor($CHARGED_AMOUNT[$Contact_ID]/$Renewal_Fee_Amount);

		if ($Ability_To_Pay>$Renewals_To_Pay) { $Ability_To_Pay=$Renewals_To_Pay; }

		$Total_Renewal_Fee_Amount=$Ability_To_Pay * $Renewal_Fee_Amount;
		
		$countajax++;

		if ($CHARGED_AMOUNT[$Contact_ID] > $Total_Renewal_Fee_Amount && $CHARGED_AMOUNT[$Contact_ID]>$Renewal_Fee_Amount) { 
			$rowcount++;
			$myecho .= "<tr $rowcolor>
			<td align='center'>$projectid</td>
			<td><a href='/xuloncontrolpanel/einstein/index.php?Account_ID=$Account_ID' target='_blank'>$Account_Name</a></td>
			<td align='center'>$Temp_Renewal_Due_Date</td>
			<td align='center'>$Renewal_Fee_Qty</td>
			<td align='center'>$Renewal_Fee_Qty_Paid</td>
			<td align='right'>$".number_format($Renewal_Fee_Amount, 2, '.', ',')."</td>";

			$charge1=$CHARGED_AMOUNT[$Contact_ID];

			$myecho .= "
			<td align='right'>$".number_format($CHARGED_AMOUNT[$Contact_ID], 2, '.', ',')."</td>
			<td align='center'>$Ability_To_Pay</td>
			<td align='right'>$".number_format($Total_Renewal_Fee_Amount, 2, '.', ',')."</td>
			";

			$CHARGED_AMOUNT[$Contact_ID]=$CHARGED_AMOUNT[$Contact_ID]-$Total_Renewal_Fee_Amount;
			$charge2=$CHARGED_AMOUNT[$Contact_ID];

			$myecho .= "
			<td align='right'>$".number_format($CHARGED_AMOUNT[$Contact_ID], 2, '.', ',')."</td>
			<td>$Payment_Problem_Status</td>
			</tr>";

			$charge_dont_charge='CHARGE';
			

			$writetotext .= "$charge_dont_charge\t$projectid\t$Account_Name\t$Temp_Renewal_Due_Date\t$Renewal_Fee_Qty\t$Renewal_Fee_Qty_Paid\t$Renewal_Fee_Amount\t".$charge1."\t$Ability_to_Pay\t$Total_Renewal_Fee_Amount\t$charge2\t$Payment_Problem_Status\r\n";

			$count++;
		}


	

	}
	$query_select_temp_charged->free();
	$db->close();

	$myecho .= "</table>
	<!--p><input type='submit' name='submitchecklist' value='Save Changes'></p>
	</form-->";

	$filePointer = fopen($_SERVER["DOCUMENT_ROOT"]."/xulonreports/csv/".$_SESSION["xulonname"]."_charge_royalties_report.xls", "w");
	fputs ($filePointer, "$writetotext", 2000000);
	fclose($filePointer);
	
	$myecho .= "<p><form method='POST' action='/xulonreports/csv/".$_SESSION['xulonname']."_charge_royalties_report.xls' target='_blank'><input type='submit' name='button2' id='button2' value='Export CSV File' style='width:150px;' onclick='this.form.submit();'></form></p>";


	return $myecho;

}

function royalties3_table_count($TEMP_TABLE) {
	if (!$TEMP_TABLE || !stristr($TEMP_TABLE,'temp')) { return; }

	//return "SELECT count(id) as count FROM sasroyalties_dev.$TEMP_TABLE";

	include($_SERVER['DOCUMENT_ROOT'].'/assets/db/db.config.php');
	$db = new mysqli($dbserver, $dbuser, $dbpassword, $database);

	$query_select_royalty_processing = $db->query("SELECT count(*) as count FROM sasroyalties_dev.$TEMP_TABLE");
	while($myrow = $query_select_royalty_processing->fetch_assoc()) {
		$count=$myrow['count'];
	}
	$query_select_royalty_processing->free();
	$db->close();

	return $count;


}


function royalties3_royalty_field_form_action() {

	$id=$_REQUEST['id'];
	$field_name=$_REQUEST['_field_name'];
	$field_value=$_REQUEST[$field_name];
	$table_name=$_REQUEST['table_name'];

	if (($table_name != 'royalty_period' && $table_name !='current_reporting_end_date') || !$id || !$field_name || !$field_value || !$table_name) { 
		return; 
	}

	include($_SERVER['DOCUMENT_ROOT'].'/assets/db/db.config.php');
	$db = new mysqli($dbserver, $dbuser, $dbpassword, $database);

	$id=mysqli_real_escape_string($db, $id);
	$field_name=mysqli_real_escape_string($db, $field_name);
	$field_value=mysqli_real_escape_string($db, $field_value);
	$table_name=mysqli_real_escape_string($db, $table_name);
	
	if (isset($_COOKIE['xulon_name'])) { 
		$xulon_name=mysqli_real_escape_string($db, $_COOKIE['xulon_name']);
	} else {
		$xulon_name=$_SERVER['REMOTE_ADDR'];
	}

	$query_update_current_reporting_end_date = "update sasroyalties.$table_name set $field_name = '$field_value', last_updated_user='".$xulon_name."', last_updated_date=NOW() where id=$id";
	$resultupdate=$db->query($query_update_current_reporting_end_date);

	$query_insert_current_reporting_end_date = "insert into sasroyalties.current_reporting_end_date_log (current_reporting_end_date, current_reporting_balance_start_date, imprint_brand_id, last_updated_date, last_updated_user) SELECT current_reporting_end_date, current_reporting_balance_start_date, imprint_brand_id, last_updated_date, last_updated_user from sasroyalties.current_reporting_end_date where id=$id";
	$resultinsert_current_reporting_end_date=$db->query($query_insert_current_reporting_end_date);

	$db->close();	

	if ($resultupdate) { 
		return 1;
	}

}



function royalties3_get_quarter_options($field_name, $field_value) {

	$options="<select name='$field_name' style='width:225px;'>
	<option value='$field_value' SELECTED>$field_value</option>";

	$quarters=array("1st", "2nd", "3rd", "4th");
	$years=array(date('Y')-1, date('Y'), date('Y')+1);

	$count_quarters=count($quarters);
	$count_years=count($years);

	for ($counter_years = 0; $counter_years < $count_years; $counter_years++)
	{
		$this_year=$years[$counter_years];

		for ($counter = 0; $counter < $count_quarters; $counter++)
		{
			$this_quarter=$quarters[$counter];

			$option_value=$this_year.' '.$this_quarter.' Qtr Royalties';

			if ($this_year==date('Y')-1 && $this_quarter != '4th') { 
				//do nothing
			} else {
				$options .= "<option value='$option_value'>$option_value</option>";
			}
		}	
	}

	$options .= "</select>";

	return $options;
}

function royalties3_display_import_exceptions($table_name) {

	include($_SERVER['DOCUMENT_ROOT'].'/assets/db/db.config.php');
	$db = new mysqli($dbserver, $dbuser, $dbpassword, $database);
	
	$sql="SELECT
		sasroyalties_dev.$table_name.*
	FROM
		sasroyalties_dev.$table_name
	LEFT JOIN sasroyalties.royalty_processing ON sasroyalties_dev.$table_name.id = sasroyalties.royalty_processing.ORG_FILE_SOURCE_RECORD_ID 
	AND royalty_processing.ORG_FILE_SOURCE='$table_name'
	WHERE
		sasroyalties.royalty_processing.id IS NULL
		;";

	//return $sql;

	$query_select_temp = $db->query($sql);

    $header='';
    $myrows='';

    while($myrow = $query_select_temp->fetch_assoc()) {
        if($header==''){
            $header.='<tr>'; 
            $myrows.='<tr>'; 
            foreach($myrow as $key => $value){ 
                $header.='<th>'.$key.'</th>'; 
                $myrows.='<td>'.$value.'</td>'; 
            } 
            $header.='</tr>'; 
            $myrows.='</tr>'; 
        }else{
            $myrows.='<tr>'; 
            foreach($myrow as $value){ 
                $myrows .= "<td>".$value."</td>"; 
            } 
            $myrows.='</tr>'; 
        }
    } 

	$query_select_temp->free();
	$db->close();

	return '<table class="bordered striped">'.$header.$myrows.'</table>';

}


function royalties3_display_imported_records($table_name) {

	include($_SERVER['DOCUMENT_ROOT'].'/assets/db/db.config.php');
	$db = new mysqli($dbserver, $dbuser, $dbpassword, $database);
	
	$sql="SELECT
		*
	FROM
		sasroyalties.royalty_processing
	WHERE royalty_processing.ORG_FILE_SOURCE='$table_name'
		;";

	//return $sql;

	$query_select_temp = $db->query($sql);

    $header='';
    $myrows='';

    while($myrow = $query_select_temp->fetch_assoc()) {
        if($header==''){
            $header.='<tr>'; 
            $myrows.='<tr>'; 
            foreach($myrow as $key => $value){ 
                $header.='<th>'.$key.'</th>'; 
                $myrows.='<td>'.$value.'</td>'; 
            } 
            $header.='</tr>'; 
            $myrows.='</tr>'; 
        }else{
            $myrows.='<tr>'; 
            foreach($myrow as $value){ 
                $myrows .= "<td>".$value."</td>"; 
            } 
            $myrows.='</tr>'; 
        }
    } 

	$query_select_temp->free();
	$db->close();

	return '<table class="bordered striped">'.$header.$myrows.'</table>';

}


function royalties3_display_temp_table($table_name) {

	include($_SERVER['DOCUMENT_ROOT'].'/assets/db/db.config.php');
	$db = new mysqli($dbserver, $dbuser, $dbpassword, $database);
	
	$sql="SELECT
		sasroyalties_dev.$table_name.*
	FROM
		sasroyalties_dev.$table_name
		;";

	//return $sql;

	$query_select_temp = $db->query($sql);

    $header='';
    $myrows='';

    while($myrow = $query_select_temp->fetch_assoc()) {
        if($header==''){
            $header.='<tr>'; 
            $myrows.='<tr>'; 
            foreach($myrow as $key => $value){ 
                $header.='<th>'.$key.'</th>'; 
                $myrows.='<td>'.$value.'</td>'; 
            } 
            $header.='</tr>'; 
            $myrows.='</tr>'; 
        }else{
            $myrows.='<tr>'; 
            foreach($myrow as $value){ 
                $myrows .= "<td>".$value."</td>"; 
            } 
            $myrows.='</tr>'; 
        }
    } 

	$query_select_temp->free();
	$db->close();

	return '<table class="bordered striped">'.$header.$myrows.'</table>';

}

function royalties3_anr_exclude_from_check_batch($projectid) { 
	if (!$projectid) { return; }

	include($_SERVER['DOCUMENT_ROOT'].'/assets/db/db.config.php');
	$db = new mysqli($dbserver, $dbuser, $dbpassword, $database);

	$query_update_authors_net_royalties = "update xulonroyalties.authors_net_royalties set exclude_from_check_batch=CURDATE() where projectid=$projectid and Check_Batch_Timestamp IS NULL";
	$resultupdate=$db->query($query_update_authors_net_royalties);

	$db->close();
}
?>