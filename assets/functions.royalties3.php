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

	$myecho = "<table class=\"blue-text text-darken-2 bordered\"><th>Table Name<th># Records";

	$query_select_temp_ebook_bn = $db->query("
	SELECT TABLE_NAME, TABLE_ROWS
		 FROM INFORMATION_SCHEMA.TABLES 
		 WHERE TABLE_SCHEMA = 'sasroyalties_dev';");
	while($myrow = $query_select_temp_ebook_bn->fetch_assoc()) {
		$TABLE_NAME=$myrow['TABLE_NAME'];
		$TABLE_ROWS=$myrow['TABLE_ROWS'];

		if ($TABLE_NAME != 'Settings') { 
			$myecho .= "<tr><td>$TABLE_NAME</td><td>".number_format(royalties3_table_count($TABLE_NAME), 0, '.', ',')."</td></tr>";
		}
	}
	$query_select_temp_ebook_bn->free();
	$db->close();

	$myecho .= "</table>";

	return $myecho;

}

function royalties3_imported_royalty_processing_data() {

	include($_SERVER['DOCUMENT_ROOT'].'/assets/db/db.config.php');
	$db = new mysqli($dbserver, $dbuser, $dbpassword, $database);

	$myecho = "<table class=\"blue-text text-darken-2 bordered\"><th>Temp Table<th># Temp records<th># Records imported";

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
		$count=$myrow['count'];

		$myecho .= "<tr><td>$ORG_FILE_SOURCE</td><td>".number_format(royalties3_table_count($ORG_FILE_SOURCE), 0, '.', ',')."</td><td>".number_format($count, 0, '.', ',')."</td></tr>";
	}
	$query_select_temp_ebook_bn->free();
	$db->close();

	$myecho .= "</table>";

	return $myecho;

}

function royalties3_table_count($TEMP_TABLE) {
	if (!$TEMP_TABLE || !stristr($TEMP_TABLE,'temp')) { return; }

	//return "SELECT count(id) as count FROM sasroyalties_dev.$TEMP_TABLE";

	include($_SERVER['DOCUMENT_ROOT'].'/assets/db/db.config.php');
	$db = new mysqli($dbserver, $dbuser, $dbpassword, $database);

	$query_select_royalty_processing = $db->query("SELECT count(id) as count FROM sasroyalties_dev.$TEMP_TABLE");
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


?>