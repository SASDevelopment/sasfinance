<? include_once($_SERVER['DOCUMENT_ROOT'].'/sasfinance/assets/functions.royalties3.php'); ?>
<html>
<head>
	<title> Royalties 3.0 </title>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="/sasfinance/assets/css/materialize/js/init.js"></script>

	<meta name="theme-color" content="#EE6E73">

	<link rel="stylesheet" type="text/css" href="/sasfinance/assets/css/materialize/css/materialize.css">
	<script src="/sasfinance/assets/css/materialize/js/materialize.js"></script>
    <link href="//fonts.googleapis.com/css?family=Inconsolata" rel="stylesheet" type="text/css">
    <link href="//fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

	<script type="text/javascript">
	  $(document).ready(function(){
		// the "href" attribute of the modal trigger must specify the modal ID that wants to be triggered
		$('.modal').modal();
	  }); 

	  $(document).ready(function(){
		// the "href" attribute of .modal-trigger must specify the modal ID that wants to be triggered
		$('.modal-trigger').leanModal();
	  });

	</script>

	<?=royalties3_new_datepicker()?>


</head>
<body>
  <nav>
    <div class="nav-wrapper">
      <a href="#" class="brand-logo">Royalty Processing</a>
      <ul id="nav-mobile" class="right hide-on-med-and-down">
        <li><a href="/xuloncontrolpanel/einstein">Back to Einstein</a></li>
      </ul>
    </div>
  </nav>
       
<h5 class="header">Step 1</h5>
<p class="caption">Set start dates, end dates, royalty period, statement period and carryover amount.</p>

<div class="card-panel grey lighten-3" style="width: 50%">
<table class="bordered highlight">
<th><th>Xulon<th>Hillcrest
<tr>
<td>Current Reporting Start Date</td><td><?=royalties3_royalty_field_form(1, 'current_reporting_balance_start_date', 'current_reporting_end_date', 10, 'date')?></td>
<td><?=royalties3_royalty_field_form(27, 'current_reporting_balance_start_date', 'current_reporting_end_date', 10, 'date')?></td>
</tr>
<tr>
<td>Current Reporting End Date</td><td><?=royalties3_royalty_field_form(1, 'current_reporting_end_date', 'current_reporting_end_date', 10, 'date')?></td>
<td><?=royalties3_royalty_field_form(27, 'current_reporting_end_date', 'current_reporting_end_date', 10, 'date')?></td>
</tr>
<tr><td>Royalty Period</td>
<td><?=royalties3_royalty_field_form(1, 'Royalty_Period', 'royalty_period', 20, NULL)?></td>
<td><?=royalties3_royalty_field_form(27, 'Royalty_Period', 'royalty_period', 20, NULL)?></td></tr>
<tr><td>Statement Period</td>
<td><?=royalties3_royalty_field_form(1, 'Statement_Date', 'royalty_period', 10, NULL)?></td>
<td><?=royalties3_royalty_field_form(27, 'Statement_Date', 'royalty_period', 10, NULL)?></td></tr>
<tr><td>Carryover Amount</td>
<td><?=royalties3_royalty_field_form(1, 'Carryover', 'royalty_period', 5, 'currency')?></td>
<td><?=royalties3_royalty_field_form(27, 'Carryover', 'royalty_period', 5, 'currency')?></td></tr>
</table>
</div>
<p></p>




<h5 class="header">Step 2</h5>

<div class="card-panel grey lighten-3" style="width: 50%">
<h5><i class="small material-icons">delete</i><a class="modal-trigger" href="#modal1">Clear temp tables</a></h5>
</div>
<!-- Modal Structure -->
	<div id="modal1" class="modal">
	  <div class="modal-content">
		<h5>Clear Temp Tables</h5>
		<p>Clicking "AGREE" will delete all of the data currently stored in the temp tables in Navicat so that you can begin processing royalties.</p>
	  </div>
	  <div class="modal-footer">
		<a href="#!" class="modal-action modal-close waves-effect waves-red btn-flat ">Disagree</a>
		<a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">Agree</a>
	  </div>
	</div>




<h5 class="header">Step 3</h5>

<div class="card-panel grey lighten-3" style="width: 50%">
<h5><img src="/sasfinance/images/navicat_trans_sm.png"><a class="modal-trigger" href="#modal3">Open Navicat and import vendor data into temp tables</a></h5>
</div>

<!-- Modal Structure -->
	<div id="modal3" class="modal">
	  <div class="modal-content">
		<h5>Open Navicat</h5>
		<p><img src="/sasfinance/images/navicat_trans.png">Open Navicat now and begin your import.  The database in Navicat is named "sasroyalties_dev".  Click the "IMPORT COMPLETE" button below when done.</p>
	  </div>
	  <div class="modal-footer">
		<a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">Import Complete</a>
	  </div>
	</div>






<h5 class="header">Step 4</h5>
<p><em>This step assumes that you have already imported the latest data into the temp tables using Navicat in Step 3.</em></p>

<div class="card-panel grey lighten-3" style="width: 50%">
<h5><i class="small material-icons">cached</i> <a>Process Royalties</a></h5>
</div>






<h5 class="header">Step 5</h5>

<div class="card-panel grey lighten-3" style="width: 50%">
<h5><i class="small material-icons">face</i> <a>Review Exceptions</a></h5>
</div>





<h5 class="header">Step 6</h5>
<p>This step will copy data from authors_net_royalties to check_batch_master.</p>

<div class="card-panel grey lighten-3" style="width: 50%">
<h5><i class="small material-icons">attach_money</i> <a>Process Check Batch for Existing Authors</a></h5>
<h5><i class="small material-icons">attach_money</i> <a>Process Check Batch for New Authors</a></h5>
</div>





<h5 class="header">Step 7</h5>

<div class="card-panel grey lighten-3" style="width: 50%">
<h5><i class="small material-icons">file_download</i> <a>Check Batch Export</a></h5>
</div>







<table width="700" border="0" cellspacing="0" cellpadding="0">

  <tr>
    <td valign="top"><p><strong>Step 3</strong></p>
    <p><em>Update records in authors_net_royalties.</em></p></td>
    <td><ol>

      <li><a href="/xuloncontrolpanel/royalties/flags/flag_Author_Code_Unknown.php">Update Unknown Authors</a> (Currently: </=countStatusCodes_roy('U')?> records) <a href='help/help-flag_Author_Code_Unknown.php?iframe=true&width=700&height=400' rel='prettyPhoto[iframes]' title='Update Unknown Authors'><img src='/images/help_16.png' border='0'></a></li>
      <li><a href="/xuloncontrolpanel/royalties/updates/Load_Book_Type.php">Load Book Type</a></li>
      <li><a href="/xuloncontrolpanel/royalties/updates/Load_Reporting_ISBN.php">Update Reporting ISBN</a></li>
      <li><a href="/xuloncontrolpanel/royalties/updates/repair_book_numbers.php">Load Book Numbers</a></li>
	  <li><a href='/xuloncontrolpanel/royalties/updates/Update_Unknown_Authors_PB.php'>Fix Unknown PB Authors</a>  <a href='help/help-Update_Unknown_Authors_PB.php?iframe=true&width=700&height=400' rel='prettyPhoto[iframes]' title='Fix Unknown PB Authors'><img src='/images/help_16.png' border='0'></a>
<li><a href='/xuloncontrolpanel/royalties/updates/Update_Unknown_Authors_HC.php'>Fix Unknown Hardcover Authors</a>  <a href='help/help-Update_Unknown_Authors_HC.php?iframe=true&width=700&height=400' rel='prettyPhoto[iframes]' title='Fix Unknown Hardcover Authors'><img src='/images/help_16.png' border='0'></a>

      <li><a href="/xuloncontrolpanel/royalties/flags/flag_Author_Code_Existing.php">Update Existing Authors</a> (Currently: </=countStatusCodes_roy('E')?> records) <a href='help/help-flag_Author_Code_Existing.php?iframe=true&width=600&height=400' rel='prettyPhoto[iframes]' title='Update Existing Authors'><img src='/images/help_16.png' border='0'></a></li>
      <li><a href="/xuloncontrolpanel/royalties/flags/flag_Author_Code_New.php">Update New Authors</a> (Currently: </=countStatusCodes_roy('N')?> records) <a href='help/help-flag_Author_Code_New.php?iframe=true&width=600&height=400' rel='prettyPhoto[iframes]' title='Update New Authors'><img src='/images/help_16.png' border='0'></a></li><br />
    </ol>
    </td>
  </tr>
  <tr>
    <td colspan="2" valign="top"><hr />
        <br /></td>
  </tr>
  <tr>
    <td valign="top"><p><strong>Step 4</strong></p>
    <p><em>Will add a Royalty_Period to the records in authors_net_royalties that do not have a Check_Batch_Timestamp.  Will also allow the addition of an ending date range (i.e. 7/31/2010).</em></p></td>
    <td><ol>
      <li><a href="/xuloncontrolpanel/royalties/flags/Total_Royalty_Amount.php">Update Total Royalty Amounts</a></li><br />
      
      <li><a href="/xuloncontrolpanel/royalties/flags/flag_All.php">Flags Update</a><br />(Carryover, Program, and Zero flags)</li><br />
      <li><a href="/xuloncontrolpanel/royalties/flags/flag_W9.php">Missing W9 Flag Update</a><br /></li><br />
      
      <li><a href='flags/Royalty_Period_Processing.php'>Process Statements for </=$Current_Royalty_Period?></a></li>
    </ol></td>
  </tr>
  <tr>
    <td colspan="2" valign="top"><hr />
        <br /></td>
  </tr>
  <tr>
    <td valign="top"><p><strong>Step 5</strong></p>
    <p><em>This step will copy data from authors_net_royalties to check_batch_master.</em></p></td>
    <td><ol>
      <?php
	  
	  $Existing_Check_Count="confirmCheckBatchRun_roy('count','E')";
	  $New_Check_Count="confirmCheckBatchRun_roy('count', 'N')";
	  $Existing_Check_Timestamp=' '."confirmCheckBatchRun_roy('timestamp','E')";
	  $New_Check_Timestamp=' '."confirmCheckBatchRun_roy('timestamp', 'N')";
	  
	  if (substr($Existing_Check_Timestamp, 0, 4)<'2009') { unset ($Existing_Check_Timestamp); }
	  if (substr($New_Check_Timestamp, 0, 4)<'2009') { unset ($New_Check_Timestamp); }
	  
	  if ($Existing_Check_Count>0) { $hide_existing='!--'; }
	  if ($New_Check_Count>0) { $hide_new='!--'; }
	  
	  echo "
      <li><".$hide_existing."a href='/xuloncontrolpanel/royalties/file_export_step3_form.php?Batch_Type=existing'>Process Check Batch for Existing Authors</a".$hide_existing."> ($Existing_Check_Count existing authors processed$Existing_Check_Timestamp)</li>";
	  
	  echo "
      <li><".$hide_new."a href='/xuloncontrolpanel/royalties/file_export_step3_form.php?Batch_Type=new'>Process Check Batch for New Authors</a".$hide_new."> ($New_Check_Count new authors processed$New_Check_Timestamp)</li>";
	  

	  ?>
    </ol>
	
	</td>
  </tr>
  <tr>
    <td colspan="2" valign="top"><hr />
        <br /></td>
  </tr>  
  <tr>
    <td valign="top"><p><strong>Step 6</strong></p>
    <p><em>This step will export check batch data to Excel files.</em></p></td>
    <td><ol>
      <li><a href='/xuloncontrolpanel/royalties/_check_batch_export.php'>Check Batch Export</a><br />New_US, New_Intl, Existing_US, Existing_Intl</li>
    </ol>
	
	</td>
  </tr>  
  
</table>








<div class="row section">
  <div class="col">
    <!-- Modal Trigger -->
    <a class="waves-effect waves-light btn modal-trigger" href="#modal2">Modal</a>
    <p>You have to include jQuery and Materialize JS + CSS for the modal to work. You can include it from <a href="http://materializecss.com/getting-started.html">CDN (getting started)</a>.
  </div>
</div>
<!-- Modal Structure -->
<div id="modal2" class="modal">
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