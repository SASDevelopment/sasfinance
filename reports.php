<?php include_once($_SERVER["DOCUMENT_ROOT"]."/cvbtech/authenticate.php"); ?>
<html>
<head>
	<title> Royalties 3.0 </title>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="assets/css/materialize/js/init.js"></script>

	<meta name="theme-color" content="#EE6E73">

	<link rel="stylesheet" type="text/css" href="assets/css/materialize/css/materialize.css">
	<script src="assets/css/materialize/js/materialize.js"></script>
    <link href="//fonts.googleapis.com/css?family=Inconsolata" rel="stylesheet" type="text/css">
    <link href="//fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

	<script type="text/javascript">
	  $(document).ready(function(){
		$('.collapsible').collapsible();
	  });
    </script>

</head>
<body>
  
        
<?php

$GETSTARTDATE='2017-09-01';
$GETENDDATE='2017-09-30';

if ($_GET['imprint_id']==27) { 
	$GETCID='HC56095';
	$GETISBN='9781635051599';
} else { 
	$GETCID='00300000005oKde';
	$GETISBN='9781602665163';
	$GETROYALTYPERIOD='2017 2nd Qtr Royalties';
}

$GETCID=$_REQUEST['GETCID'];
$GETISBN=$_REQUEST['GETISBN'];

echo "<h5>Test Royalties 3.0 Reports</h5>
<li><a href='?imprint_id=1&GETCID=00300000005oKde&GETISBN=9781634139892'>Kat Kerr (Xulon)</a>
<li><a href='?imprint_id=1&GETCID=49406&GETISBN='>Kevin Zadai (Xulon)</a>
<li><a href='?imprint_id=1&GETCID=21167&GETISBN='>James Durham (Xulon)</a>
<li><a href='?imprint_id=27&GETCID=HC8459&GETISBN=9781634139892'>Gregory Cagle (Hillcrest)</a>
<li><a href='?imprint_id=27&GETCID=HC18389&GETISBN=9781937600464'>Justin Rosales (Hillcrest)</a>
<li><a href='?imprint_id=27&GETCID=HC10799&GETISBN=9781936401567'>McCandless (Hillcrest)</a>
<li><a href='?imprint_id=27&GETCID=HC56095&GETISBN=9781635051599'>Mary Tichi (Hillcrest)</a>
<li><a href='?imprint_id=27&GETCID=HC13104&GETISBN='>Susan Finterty (Hillcrest)</a>
<li><a href='?imprint_id=27&GETCID=HC52410&GETISBN='>Julie Causton (Hillcrest)</a>
";
echo '<ul class="collapsible" data-collapsible="accordion">';
echo call_sp(0, 'Monthly Activity by Date Range', "CALL sasroyalties.A_roy_ac_monthly_activity_by_date('$GETCID','$GETSTARTDATE', '$GETENDDATE')");
echo call_sp(1, 'Monthly Activity by Royalty Period', "CALL sasroyalties.A_roy_ac_monthly_activity_by_period('$GETCID','$GETROYALTYPERIOD')");
echo call_sp(1, 'Current Reporting Balance by Royalty Period', "CALL sasroyalties.B_roy_ac_royalty_distribution_balance_by_period('$GETCID','$GETROYALTYPERIOD')");
echo call_sp(1, 'Chcck Batch by Royalty Period', "CALL sasroyalties.C_roy_ac_royalty_check_batch_by_period('$GETCID','$GETROYALTYPERIOD')");
echo call_sp(0, 'Check Batch by Date Range', "CALL sasroyalties.C_roy_ac_royalty_check_batch_by_date('$GETCID','$GETSTARTDATE', '$GETENDDATE')");
echo call_sp(0, 'Current Reporting Balance', "CALL sasroyalties.D_roy_ac_current_reporting_balance('$GETCID','$GETENDDATE')");
echo call_sp(0, 'Current Ending Balance', "CALL sasroyalties.E_roy_ac_royalty_distribution_balance('$GETCID')");
echo call_sp(0, 'Current Reporting Balance Total', "CALL sasroyalties.F_roy_ac_current_reporting_balance('$GETCID')");
echo call_sp(1, 'Transactions', "CALL sasroyalties.G_roy_ac_royalty_transactions_xulon('$GETCID', '', '', '2017','')");
echo call_sp(1, 'Xulon Transactions by Royalty Period', "CALL sasroyalties.G_roy_ac_royalty_transactions_xulon('$GETCID', '$GETROYALTYPERIOD', '', '2017','')");
echo call_sp(27, 'Hillcrest Transactions by Date Range', "CALL sasroyalties.G_roy_ac_royalty_transactions_hillcrest('$GETCID', '$GETSTARTDATE','$GETENDDATE')");
echo call_sp(27, 'MBO Transactions by Date Range', "CALL sasroyalties.G_roy_ac_royalty_transactions_hillcrest_mbo('$GETCID', '$GETSTARTDATE','$GETENDDATE')");
echo call_sp(27, 'Storage Fees by Date Range', "CALL sasroyalties.G_roy_ac_royalty_transactions_hillcrest_storage('$GETISBN', '$GETSTARTDATE','$GETENDDATE')");
echo call_sp(0, 'ISBN Picklist', "CALL sasroyalties.roy_ac_royalty_isbn_picklist('$GETCID')");
echo call_sp(1, 'Royalty Month Picklist', "CALL sasroyalties.roy_ac_royalty_month_picklist('$GETCID')");
echo call_sp(1, 'Current Statement Period', "CALL sasroyalties.roy_ac_royalty_statement_period_list('$GETCID')");
echo call_sp(1, 'Statement Period Picklist', "CALL sasroyalties.roy_ac_royalty_statement_period_picklist('$GETCID')");
echo call_sp(1, 'Royalty Year Picklist', "CALL sasroyalties.roy_ac_royalty_year_picklist('$GETCID')");

echo '</ul>';

function call_sp($report_imprint, $report_title, $sql) {
	if (!$sql) { return; }

	if ($report_imprint==1 && $_GET['imprint_id']==27) { return; }
	if ($report_imprint==27 && $_GET['imprint_id']==1) { return; }

	include($_SERVER['DOCUMENT_ROOT'].'/assets/db/db.config.php');
	$db = new mysqli($dbserver, $dbuser, $dbpassword, $database);
	
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


	return "<li>
      <div class='collapsible-header'><i class='material-icons'>dehaze</i><h5 style='font-weight:bold;'>$report_title</h5></div>
      <div class='collapsible-body'><span>".$sql.';<table class="bordered striped" style="width:1200px;">'.$header.$myrows."</table></span></div>
    </li>";


	/*return "<div class='row'>
        <div class='col s12 m6'>
          <div class='card blue-grey darken-1'>
            <div class='card-content white-text'>
              <!--span class='card-title'>Stored Procedure</span-->
              <p>$sql</p>
            </div>
            <!--div class='card-action'>
              <a href='#'>This is a link</a>
              <a href='#'>This is a link</a>
            </div-->
          </div>
        </div>
      </div>".'<table class="bordered striped" style="width:1200px;">'.$header.$myrows.'</table>';*/

}

?>
</body>
</html>