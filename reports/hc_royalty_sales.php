<?php 
include_once($_SERVER["DOCUMENT_ROOT"]."/cvbtech/authenticate.php");

include_once($_SERVER['DOCUMENT_ROOT'].'/assets/functions.ac2.php');

include_once($_SERVER["DOCUMENT_ROOT"].'/assets/functions.all.php');
//include("includes/header_left.php");


?>
	


<html lang="en" class="">
    <head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Package | Salem Author Center</title>
	<meta name="description" content="">
	<meta name="author" content="Salem Author Services">

	<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<link rel="stylesheet" type="text/css" href="/authorcenter2/css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="/authorcenter2/css/bootstrap-responsive.min.css" />
		<link rel="stylesheet" type="text/css" href="/authorcenter2/css/default.css" />
		<link rel="stylesheet" type="text/css" href="/authorcenter2/css/xulonauthors.css" />

	<script language=javascript src='/authorcenter2/js/bootstrap.min.js'></script>
	<script language=javascript src='/authorcenter2/js/jquery-1.7.1.min.js'></script>
	<script language=javascript src='/authorcenter2/js/bootstrap-dropdown.js'></script>
	<script language=javascript src='/authorcenter2/js/bootstrap-collapse.js'></script>
	<script language=javascript src='/authorcenter2/js/bootstrap-transition.js'></script>
	<script language=javascript src='/authorcenter2/js/bootstrap-alert.js'></script>
	<script language=javascript src='/authorcenter2/js/jquery-ui-1.10.4.min.js'></script>

	<!-- fav and touch icons -->
	<link rel="shortcut icon" href="/authorcenter2/images/favicon.ico">
	<link rel="apple-touch-icon" href="/authorcenter2/images/apple-touch-icon.png">
	<link rel="apple-touch-icon" sizes="72x72" href="/authorcenter2/images/apple-touch-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="114x114" href="/authorcenter2/images/apple-touch-icon-114x114.png">

	</head>
<body>


<!--NEW-->
<div class="widget-box">
	    		<!-- Terms -->
		<div class="widget-header brown">
		    <h4 class="span8"><i class="icon-dollar"></i> Royalty Sales</h4>
		</div>
		<div>
		    <table class="table table-bordered">
			<tbody>
			    <tr>
				<td class="span8">
<?

if ($_SESSION['SESSCONTACTID']=='120351') { 
	$hide_sales=1;
}

//$hide_sales=1;

if (
	$_COOKIE['xulon_name']=='Susan Williams' || 
	$_COOKIE['xulon_name']=='Chad Nykamp' || 
	$_COOKIE['xulon_name']=='Terry Haines' || 
	$_COOKIE['xulon_name']=='Tom McCrary'
	) { 
	unset($hide_sales);
} 










if ($hide_sales==1) { 
	echo "<p>We are currently updating your royalties information. This information will be restored within the near future.</p>";
} else { 

	//hide until royalties fixed
	echo ac2_display_book_ids_royalty_royalties3(); 
?>
	<br>
	<? if ($_REQUEST['PID']) { ?>
	<iframe src="hc_royalty_sales_frame.php?PID=<?=$_REQUEST['PID']?>" width="100%" height="500" frameborder="0" scrolling="yes"></iframe>
	<? } 
	
}?>


                                                                    </td>
			
			    </tr>
			    			</tbody>
		    </table>
		</div>

</div>
<!--Middle Body End Here-->
		<div class="footer">
			<div class="container">
				<p class="muted credit" align='center'>Powered by Salem Author Services &copy; 2001-2017</p>
			</div>
		</div>
		<!--Footer End Here-->
	</body>
</html>