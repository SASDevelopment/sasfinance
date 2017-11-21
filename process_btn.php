<script type="text/javascript">
  function closeToast() {
	// Get toast DOM Element, get instance, then call remove function
  var toastElement = $('.toast').first()[0];
  var toastInstance = toastElement.M_Toast;
  toastInstance.remove();
}
</script>
<?
if ($step && $process) { 
?>

<script type="text/javascript">
	var displayCustomHTMLToast = function () {
	  var stepContent = '<?=$step.' - '.$stepContent?>';
	  var $toastContent = $('<span>Completed Step '+stepContent+'</span>').add($('<a class="btn-flat toast-action" onclick="closeToast();">Close</a>'));
	  Materialize.toast($toastContent);
	}
</script>

<?
}

if ($step==2) { 
	include($_SERVER['DOCUMENT_ROOT'].'/assets/db/db.config.php');
	$db = new mysqli($dbserver, $dbuser, $dbpassword, $database);

	$query_sp = "CALL sasroyalties_dev.clear_temp()";
	$query_call_sp=$db->query($query_sp);

	$db->close();

}

if ($step>2 && $step<3) { 
	$clear_table=$_REQUEST['clear_table'];

	include($_SERVER['DOCUMENT_ROOT'].'/assets/db/db.config.php');
	$db = new mysqli($dbserver, $dbuser, $dbpassword, $database);

	$query_delete_temp_sibi = "DELETE FROM sasroyalties_dev.$clear_table";
	$resultdelete=$db->query($query_delete_temp_sibi);

	$query_delete_temp_sibi = "DELETE FROM sasroyalties.royalty_processing where ORG_FILE_SOURCE='$clear_table'";
	$resultdelete=$db->query($query_delete_temp_sibi);

	$db->close();


}

/*if ($step==3) { 
	include($_SERVER['DOCUMENT_ROOT'].'/assets/db/db.config.php');
	$db = new mysqli($dbserver, $dbuser, $dbpassword, $database);

	$query_sp = "CALL sasroyalties_dev.step0_execute_all_steps()";
	$query_call_sp=$db->query($query_sp);

	$db->close();
}*/

if ($step==4) { 
	include($_SERVER['DOCUMENT_ROOT'].'/assets/db/db.config.php');
	$db = new mysqli($dbserver, $dbuser, $dbpassword, $database);

	$query_sp = "CALL sasroyalties_dev.step00_execute_all_steps()";
	$query_call_sp=$db->query($query_sp);


/* alternate method */

/*
	$query_delete_royalty_processing = "delete from sasroyalties.royalty_processing;";
	$resultdelete=$db->query($query_delete_royalty_processing);

	$query_sp = "CALL sasroyalties_dev.copy_xulonpress_tables()";
	$query_call_sp=$db->query($query_sp);

	$query_sp = "CALL sasroyalties_dev.step01_data_repairs();";
	$query_call_sp=$db->query($query_sp);

	$query_sp = "CALL sasroyalties_dev.step02_process_itasca();";
	$query_call_sp=$db->query($query_sp);

	$query_sp = "CALL sasroyalties_dev.step03_process_ebooks();";
	$query_call_sp=$db->query($query_sp);

	$query_sp = "CALL sasroyalties_dev.step04_process_lsi();";
	$query_call_sp=$db->query($query_sp);

	$query_sp = "CALL sasroyalties_dev.step05_process_sibi();";
	$query_call_sp=$db->query($query_sp);

	$query_sp = "CALL sasroyalties_dev.step06_process_mbo();";
	$query_call_sp=$db->query($query_sp);

	$query_sp = "CALL sasroyalties_dev.step08_update_royalty_processing_data();";
	$query_call_sp=$db->query($query_sp);

*/



	$db->close();

	if ($query_call_sp) { 

	}
}

if ($step==6) { 
	include($_SERVER['DOCUMENT_ROOT'].'/assets/db/db.config.php');
	$db = new mysqli($dbserver, $dbuser, $dbpassword, $database);

	$query_sp = "CALL sasroyalties_dev.step09_load_transactions_to_dev_authors_net_royalties()";
	$query_call_sp=$db->query($query_sp);

	$db->close();
}
?>


