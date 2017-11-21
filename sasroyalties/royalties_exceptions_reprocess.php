<?php


if ($_GET['reprocess'] == 1) {
    
	include($_SERVER['DOCUMENT_ROOT'].'/assets/db/db.config.php');
	$db = new mysqli($dbserver, $dbuser, $dbpassword, $database);

	$query_sp = "CALL sasroyalties_dev.step00_execute_all_steps()";
	$query_call_sp=$db->query($query_sp);


	$db->close();

    echo 0;
}
else
{
    echo 1;
}
?>