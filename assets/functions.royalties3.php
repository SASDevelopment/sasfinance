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

	$arr = array('GBP', 'EUR', 'JPY', 'CAD', 'INR', 'AUD', 'BRL', 'MXN', 'HKD', 'USD', 'NOK', 'SEK', 'DKK', 'CHF');
	foreach ($arr as &$currency_from) {
		$query_insert_royalty_currency_data = "insert ignore into sasroyalties.royalty_currency_data (currency_from, currency_to, currency_output, record_timestamp, last_updated_timestamp, record_date) values ('$currency_from', '$currency_to', ".royalties3_currencyConverter($currency_from,$currency_to,$currency_input).", NOW(), NOW(), CURDATE())";
		$resultinsert_royalty_currency_data=$db->query($query_insert_royalty_currency_data);

		$query_insert_royalty_currency_data = "insert ignore into sasroyalties.royalty_currency_data (currency_from, currency_to, currency_output, record_timestamp, last_updated_timestamp, record_date) values ('$currency_to', '$currency_from', ".royalties3_currencyConverter($currency_to,$currency_from,$currency_input).", NOW(), NOW(), CURDATE())";
		$resultinsert_royalty_currency_data=$db->query($query_insert_royalty_currency_data);
	}
	$db->close();
}


function royalties3_insert_royalty_period() {

	include($_SERVER['DOCUMENT_ROOT'].'/assets/db/db.config.php');
	$db = new mysqli($dbserver, $dbuser, $dbpassword, $database);

	$royalty_period=mysql_real_escape_string($_REQUEST['royalty_period']);
	$royalty_period_start_date=mysql_real_escape_string($_REQUEST['royalty_period_start_date']);
	$royalty_period_end_date=mysql_real_escape_string($_REQUEST['royalty_period_end_date']);
	$imprint_brand=mysql_real_escape_string($_REQUEST['imprint_brand']);
	$imprint_brand_id=mysql_real_escape_string($_REQUEST['imprint_brand_id']);
	$record_timestamp=mysql_real_escape_string($_REQUEST['record_timestamp']);
	$last_updated_timestamp=mysql_real_escape_string($_REQUEST['last_updated_timestamp']);
	$carryover_amount=mysql_real_escape_string($_REQUEST['carryover_amount']);

	$query_insert_royalty_period = "insert into sasroyalties.royalty_period (royalty_period, royalty_period_start_date, royalty_period_end_date, imprint_brand, imprint_brand_id, record_timestamp, last_updated_timestamp, carryover_amount) values ('$royalty_period', '$royalty_period_start_date', '$royalty_period_end_date', '$imprint_brand', $imprint_brand_id, $carryover_amount)";
	$resultinsert_royalty_period=$db->query($query_insert_royalty_period);

	$db->close();

}


?>