<?php include_once($_SERVER["DOCUMENT_ROOT"]."/cvbtech/authenticate.php"); ?>
<? 
include_once('assets/functions.royalties3.php'); 


if (isset($_REQUEST['step'])) { $step=$_REQUEST['step']; } else { $step=''; }
if (isset($_REQUEST['stepContent'])) { $stepContent=$_REQUEST['stepContent']; } else { $stepContent=''; }
if (isset($_REQUEST['process'])) { $process=$_REQUEST['process']; } else { $process=''; }

if ($step) { 
	include('process_btn.php');
}
?>
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
		// the "href" attribute of the modal trigger must specify the modal ID that wants to be triggered
		$('.modal').modal();
	  }); 
	</script>

	<script type="text/javascript">
	$(document).ready(function() {
		$("form").submit(function() {
			// Getting the form ID
			var  formID = $(this).attr('id');
			var formDetails = $('#'+formID);
			$.ajax({
				type: "POST",
				url: 'process.php',
				data: formDetails.serialize(),
				success: function (data) {	
					// Inserting html into the result div
					$('#results'+formID).html(data);
				},
				error: function(jqXHR, text, error){
				// Displaying if there are any errors
					$('#results'+formID).html(error);           
			}
		});
			return false;
		});
	});
	</script>

	<script type="text/javascript">
	  $(document).ready(function() {
		$('select').material_select();
	  });
     </script>

	<script type="text/javascript">
	  $(document).ready(function(){
		$('.collapsible').collapsible();
	  });
     </script>

	<script type="text/javascript">
	function clearBox(elementID)
	{
		document.getElementById(elementID).innerHTML = "<div class='preloader-wrapper small active' style='width:18px;height:18px;'><div class='spinner-layer spinner-blue-only'><div class='circle-clipper left'><div class='circle'></div></div><div class='gap-patch'><div class='circle'></div></div><div class='circle-clipper right'><div class='circle'></div></div></div></div>";
	}
     </script>

	<script type="text/javascript">
	  $(document).ready(function(){
		$('.tooltipped').tooltip({delay: 50});
	  });		   
     </script>

	<style>
	.borderleft {
    border-left:  1px solid #D0D0D0;
	}
	</style>
</head>
<body onload="displayCustomHTMLToast();">
  <nav>
    <div class="nav-wrapper">
	<div class="container">
      <a href="#" class="brand-logo">Royalty Processing - Salem Author Services</a>
      <ul id="nav-mobile" class="right hide-on-med-and-down">
        <li><a href="/xuloncontrolpanel/einstein">Back to Einstein</a></li>
      </ul>
	  </div>
    </div>
  </nav>

<?
for ($i=1; $i<=10; $i++){
	/*if ($step>=$i) { 
		$panel[$i]='darken-2';
	}*/
	if (!$panel[$i]) { 
		$panel[$i]='lighten-3';
	}
}
?>

<div class="container">       
<h5 class="header">Step 1</h5>
<p class="caption">Set start dates, end dates, royalty periods, statement periods and carryover amounts.</p>

<div class="card-panel grey <?=$panel[1]?>" style="width: 100%;;">
<table class="bordered highlight">
<th><th width="350" class="borderleft">Xulon<th width="350" class="borderleft">Hillcrest
<tr>
<td>Current Reporting Start Date</td><td class="borderleft"><?=royalties3_royalty_field_form(1, 'current_reporting_balance_start_date', 'current_reporting_end_date', 10, 'date')?></td>
<td class="borderleft"></td>
</tr>
<tr>
<td>Current Reporting End Date</td><td class="borderleft"><?=royalties3_royalty_field_form(1, 'current_reporting_end_date', 'current_reporting_end_date', 10, 'date')?></td>
<td class="borderleft"></td>
</tr>
<tr><td>Royalty Period</td>
<td class="borderleft"><?=royalties3_royalty_field_form(1, 'Royalty_Period', 'royalty_period', 20, NULL)?></td>
<td class="borderleft"></td></tr>
<tr><td>Statement Period</td>
<td class="borderleft"><?=royalties3_royalty_field_form(1, 'Statement_Date', 'royalty_period', 10, 'date')?></td>
<td class="borderleft"></td></tr>
<tr><td>Carryover Amount</td>
<td class="borderleft"><?=royalties3_royalty_field_form(1, 'Carryover', 'royalty_period', 5, 'currency')?></td>
<td class="borderleft"><?=royalties3_royalty_field_form(27, 'Carryover', 'royalty_period', 5, 'currency')?></td></tr>
</table>
</div>
<p></p>




<?
if ($step>=2 && $step<3) { 
	$collapsible['step2']='collapsible popout active';
} else { 
	$collapsible['step2']='collapsible-body';
}
?>
<a name="step2">
<h5 class="header">Step 2</h5>

<div class="card-panel grey <?=$panel[2]?>" style="width: 100%;">
<h5><i class="small material-icons" style="vertical-align:middle">delete</i> <a class="modal-trigger" href="#modal2" onclick="closeToast();">Clear temp tables</a></h5>
<? //if ($step>=2) { ?>
<ul class="collapsible" data-collapsible="accordion">
    <li>
      <div class="collapsible-header"><i class="material-icons">dehaze</i>View temp table counts</div>
      <div class="<?=$collapsible['step2']?>"><span><?=royalties3_show_tables_in_sasroyalties_dev()?></span></div>
	  <!--collapsible popout-->
    </li>
</ul>
<? //} ?>
</div>
<!-- Modal Structure -->
	<div id="modal2" class="modal">
	  <div class="modal-content">
		<h5><i class="small material-icons" style="vertical-align:middle">delete</i> Clear Temp Tables</h5>
		<p>Clicking "Delete All Records" will delete all of the data currently stored in the temp tables in Navicat so that royalty processing can begin.</p>
	  </div>
	  <div class="modal-footer">
		<a href="#!" class="modal-action modal-close waves-effect waves-light btn"><i class="material-icons left">clear</i>Do Not Delete</a>
		<a href="index.php?step=2&stepContent=Clear Temp Tables&process=1#step2" class="modal-action modal-close waves-effect waves-light btn"><i class="material-icons left">delete</i>Delete All Records</a>
	  </div>
	</div>





<a name="step3">
<h5 class="header">Step 3</h5>

<div class="card-panel grey <?=$panel[3]?>" style="width: 100%;;">
<h5><img src="images/navicat_trans_30x30.png" style="vertical-align:middle"> <a class="modal-trigger" href="#modal3" onclick="closeToast();">Open Navicat and import vendor data into temp tables</a></h5>
</div>

<!-- Modal Structure -->
	<div id="modal3" class="modal">
	  <div class="modal-content">
		<h5><img src="images/navicat_trans_sm.png" style="vertical-align:middle"> Open Navicat and import vendor data into temp tables</h5>
		<p>Open Navicat now and begin your import.  The database in Navicat is named "sasroyalties_dev".  Click "I've completed the import" when done.</p>
	  </div>
	  <div class="modal-footer">
		<a href="#!" class="modal-action modal-close waves-effect waves-light btn"><i class="material-icons left">clear</i>Cancel</a>
		<a href="index.php?step=3&stepContent=Open Navicat and import vendor data&process=1#step4" class="modal-action modal-close waves-effect waves-light btn"><i class="material-icons left">thumb_up</i>I've completed the import</a>
	  </div>
	</div>



<?
if ($step>=4) { 
	$collapsible['step4']='collapsible popout active';
} else { 
	$collapsible['step4']='collapsible-body';
}
?>

<a name="step4">
<h5 class="header">Step 4</h5>
<p><em>This step assumes that you have already imported the latest data into the temp tables using Navicat in Step 3.</em></p>

<div class="card-panel grey <?=$panel[4]?>" style="width: 100%;">
<h5><i class="small material-icons" style="vertical-align:middle">cached</i> <a class="modal-trigger" href="#modal4" onclick="closeToast();">Process Royalties</a></h5>
<? if ($step>=4) { ?>
<ul class="collapsible" data-collapsible="accordion">
    <li>
      <div class="collapsible-header"><i class="material-icons">dehaze</i>View import counts</div>
      <div class="<?=$collapsible['step4']?>"><span><?=royalties3_imported_royalty_processing_data()?></span></div>
	  <!--collapsible popout-->
    </li>
</ul>
<? } ?>
</div>

<!-- Modal Structure -->
	<div id="modal4" class="modal">
	  <div class="modal-content">
		<h5><i class="small material-icons" style="vertical-align:middle">cached</i> <a class="modal-trigger" href="#modal4">Process Royalties</a></h5>
		<p>Ready to process royalties?  Click the button below...</p>
	  </div>
	  <div class="modal-footer">
		<a href="#!" class="modal-action modal-close waves-effect waves-light btn"><i class="material-icons left">clear</i>Cancel</a>
		<a href="index.php?step=4&stepContent=Process Royalties&process=1#step4" class="modal-action modal-close waves-effect waves-light btn"><i class="material-icons left">thumb_up</i>Process royalties</a>
	  </div>
	</div>





<a name="step5">
<h5 class="header">Step 5</h5>

<div class="card-panel grey <?=$panel[5]?>" style="width: 100%;;">
<h5><i class="small material-icons" style="vertical-align:middle">face</i> <a class="modal-trigger" href="#modal5" onclick="closeToast();">Review & Repair Exceptions</a></h5>
</div>

<!-- Modal Structure -->
<div id="modal5" class="modal">
  <div class="modal-content">
    <h5><i class="small material-icons" style="vertical-align:middle">face</i> Review Exceptions</h5>
    <p>
      <div class="content">
      <iframe width="100%" height="75%" src="sasroyalties/royalties_exceptions.php" frameborder="0" allowfullscreen></iframe></div>
    </p>
  </div>
  <div class="modal-footer">
		<a href="index.php?step=5&stepContent=Review and Repair Exceptions&process=1#step6" class="modal-action modal-close waves-effect waves-light btn"><i class="material-icons left">clear</i>Close</a>
  </div>
</div>





<a name="step6">
<h5 class="header">Step 6</h5>
<p>This step will copy imported sales data to a temporary version of authors_net_royalties, which will be viewable in this admin, but not to authors.</p>

<div class="card-panel grey <?=$panel[6]?>" style="width: 100%;;">
<h5><i class="small material-icons" style="vertical-align:middle">backup</i> <a class="modal-trigger" href="#modal6" onclick="closeToast();">Load Sales Data to Test Server</a></h5>
</div>

<!-- Modal Structure -->
	<div id="modal6" class="modal">
	  <div class="modal-content">
		<h5><i class="small material-icons" style="vertical-align:middle">backup</i> <a class="modal-trigger" href="#modal6">Load Sales Data to Test Server</a></h5>
		<p>Ready to test load the data?  Click the button below...</p>
	  </div>
	  <div class="modal-footer">
		<a href="#!" class="modal-action modal-close waves-effect waves-light btn"><i class="material-icons left">clear</i>Cancel</a>
		<a href="index.php?step=6&stepContent=Load Sales Data to Test Server&process=1#step6" class="modal-action modal-close waves-effect waves-light btn"><i class="material-icons left">thumb_up</i>Load Sales Data to Test Server</a>
	  </div>
	</div>



<a name="step7">
<h5 class="header">Step 7</h5>
<p>View royalty reports before loading to Author Center.</p>

<div class="card-panel grey <?=$panel[7]?>" style="width: 100%;;">
<h5><i class="small material-icons" style="vertical-align:middle">visibility</i> <a class="modal-trigger" href="#modal7" onclick="closeToast();">View Royalty Test Reports</a></h5>
</div>

<!-- Modal Structure -->
	<div id="modal7" class="modal">
	  <div class="modal-content">
		<h5><i class="small material-icons" style="vertical-align:middle">visibility</i> <a class="modal-trigger" href="#modal7">View Royalty Test Reports</a></h5>
		<p style="font-weight:bold;">Xulon</p>
		<li><a href="reports.php?type=xp_home" target="_blank">Home</a>
		<li><a href="reports.php?type=xp_home" target="_blank">Summary</a>
		<li><a href="reports.php?type=xp_home" target="_blank">Transaction Details</a>
		<li><a href="reports.php?type=xp_home" target="_blank">Statements</a>
		<p style="font-weight:bold;">Hillcrest</p>
		<li><a href="reports.php?type=hc_sales" target="_blank">Sales Report</a>
		<li><a href="reports.php?type=hc_dist" target="_blank">Distribution Report</a>
	  </div>
	  <div class="modal-footer">
		<a href="index.php?step=7&stepContent=View Royalty Test Reports&process=1#step7" class="modal-action modal-close waves-effect waves-light btn"><i class="material-icons left">clear</i>Close</a>
	  </div>
	</div>




<a name="step8">
<h5 class="header">Step 8</h5>
<p>This step will copy imported sales data to authors_net_royalties, which will display in Author Center.</p>

<div class="card-panel grey <?=$panel[8]?>" style="width: 100%;;">
<h5><i class="small material-icons" style="vertical-align:middle">verified_user</i> <a class="modal-trigger" href="#modal8" onclick="closeToast();">Load Sales Data to Live Server (Author Center)</a></h5>
</div>

<!-- Modal Structure -->
	<div id="modal8" class="modal">
	  <div class="modal-content">
		<h5><i class="small material-icons" style="vertical-align:middle">verified_user</i> <a class="modal-trigger" href="#modal8">Load Sales Data to Live Server (Author Center)</a></h5>
		<p>Ready to display sales data to authors?  Click the button below...</p>
	  </div>
	  <div class="modal-footer">
		<a href="#!" class="modal-action modal-close waves-effect waves-light btn"><i class="material-icons left">clear</i>Cancel</a>
		<a href="index.php?step=8&stepContent=Load Sales Data to Live Server (Author Center)&process=1#step8" class="modal-action modal-close waves-effect waves-light btn"><i class="material-icons left">thumb_up</i>Load Sales Data to Live Server (Author Center)</a>
	  </div>
	</div>



<a name="step9">
<h5 class="header">Step 9</h5>
<p>This step will copy data from authors_net_royalties to check_batch_master.</p>

<div class="card-panel grey <?=$panel[9]?>" style="width: 100%;;">
<h5><i class="blue-text text-darken-2 small material-icons" style="vertical-align:middle">local_atm</i> <a class="modal-trigger" href="#modal9"" onclick="closeToast();">Process Check Batch</a></h5>
</div>

<!-- Modal Structure -->
	<div id="modal9" class="modal">
	  <div class="modal-content">
		<h5><i class="small material-icons" style="vertical-align:middle">local_atm</i> <a class="modal-trigger" href="#modal9">Process Check Batch</a></h5>
		<p style="font-weight:bold;">Process</p>
		<li><a href="process.php?type=E" target="_blank">Process Check Batch for Existing Authors</a>
		<li><a href="process.php?type=N" target="_blank">Process Check Batch for New Authors</a>
	  </div>
	  <div class="modal-footer">
		<a href="#!" class="modal-action modal-close waves-effect waves-light btn"><i class="material-icons left">clear</i>Cancel</a>
		<a href="index.php?step=9&stepContent=Process Check Batch&process=1#step9" class="modal-action modal-close waves-effect waves-light btn"><i class="material-icons left">thumb_up</i>Processing Completed</a>
	  </div>
	</div>




<a name="step10">
<h5 class="header">Step 10</h5>

<div class="card-panel grey <?=$panel[10]?>" style="width: 100%;;">
<h5><i class="small material-icons" style="vertical-align:middle">file_download</i> <a class="modal-trigger" href="#modal10" onclick="closeToast()">Check Batch Export</a></h5>


</div>
<!-- Modal Structure -->
	<div id="modal10" class="modal">
	  <div class="modal-content">
		<h5><i class="small material-icons" style="vertical-align:middle">file_download</i> <a class="modal-trigger" href="#modal10">Xulon Check Batch Export</a></h5>
		<p style="font-weight:bold;">Export Xulon</p>
		<li><a href="check_batch_export.php?type=New_US" target="_blank">New_US</a>
		<li><a href="check_batch_export.php?type=New_Intl" target="_blank">New_Intl</a>
		<li><a href="check_batch_export.php?type=Existing_US" target="_blank">Existing_US</a>
		<li><a href="check_batch_export.php?type=Existing_Intl" target="_blank">Existing_Intl</a>
		<p style="font-weight:bold;">Export Hillcrest</p>
		<li><a href="check_batch_export.php?type=New_US" target="_blank">New_US</a>
		<li><a href="check_batch_export.php?type=New_Intl" target="_blank">New_Intl</a>
		<li><a href="check_batch_export.php?type=Existing_US" target="_blank">Existing_US</a>
		<li><a href="check_batch_export.php?type=Existing_Intl" target="_blank">Existing_Intl</a>
	  </div>
	  <div class="modal-footer">
		<a href="index.php?step=10&stepContent=Check Batch Export&process=1#step10" class="modal-action modal-close waves-effect waves-light btn"><i class="material-icons left">clear</i>Close</a>
	  </div>
	</div>

<?
/*
<div class="row section">
  <div class="col">
    <!-- Modal Trigger -->
    <a class="waves-effect waves-light btn modal-trigger" href="#modal12">Modal</a>
    <p>You have to include jQuery and Materialize JS + CSS for the modal to work. You can include it from <a href="http://materializecss.com/getting-started.html">CDN (getting started)</a>.
  </div>
</div>
<!-- Modal Structure -->
<div id="modal12" class="modal">
  <div class="modal-content">
    <h5>Modal Header</h5>
    <p>
      <div class="video-container">
      <iframe width="560" height="315" src="https://www.youtube.com/embed/DMilXF7ENps?rel=0" frameborder="0" allowfullscreen></iframe></div>
    </p>
  </div>
  <div class="modal-footer">
    <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Agree</a>
  </div>
</div>
*/
?>

</div>



 <footer class="page-footer">
      <div class="container">
        <div class="row">
          <div class="col l4 s12">
            <h5 class="white-text">Royalties 3.0</h5>
            <p class="grey-text text-lighten-4">Contact the IT Department if you encounter any issues. <a href="mailto:itadmins@salemauthorservices.com">itadmins@salemauthorservices.com</a></p>


          </div>
          <div class="col l4 s12">
            <h5 class="white-text"></h5>
            <p class="grey-text text-lighten-4"></p>
          </div>
          <div class="col l4 s12" style="overflow: hidden;">
            <h5 class="white-text"></h5>
            <br>
            <!--a href="https://twitter.com/MaterializeCSS" class="twitter-follow-button" data-show-count="true" data-size="large" data-dnt="true">Follow @MaterializeCSS</a-->
            <br>
          </div>
        </div>
      </div>
      <div class="footer-copyright">
        <div class="container">
        &copy; <?=date('Y')?> Salem Author Services, All rights reserved.
        
        </div>
      </div>
    </footer>


</body>
</html>