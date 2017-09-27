<script type="text/javascript">
  function closeToast() {
	// Get toast DOM Element, get instance, then call remove function
  var toastElement = $('.toast').first()[0];
  var toastInstance = toastElement.M_Toast;
  toastInstance.remove();
}
</script>


<style>
.preloader-background {
	display: flex;
	align-items: center;
	justify-content: center;
	background-color: #eee;
	position: fixed;
	z-index: 999;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
  p {
	padding-top:120px;
	margin-left: -60px;
	opacity: 0.8;
  } 
}

// ADD BLINKING TEXT CLASS
.blinking {
	animation: blinker 0.5s linear infinite;
}
@keyframes blinker {  
  50% { opacity: 0; }
}
</style>


<script type="text/javascript">
document.addEventListener("DOMContentLoaded", function(){
	$('.preloader-background').delay(1700).fadeOut('slow');
	
	$('.preloader-wrapper')
		.delay(1700)
		.fadeOut();
});
</script>

<!-- Preloader and it's background. -->
<div class="preloader-background">
<div class="preloader-wrapper big active">
      <div class="spinner-layer spinner-blue">
        <div class="circle-clipper left">
          <div class="circle"></div>
        </div><div class="gap-patch">
          <div class="circle"></div>
        </div><div class="circle-clipper right">
          <div class="circle"></div>
        </div>
      </div>

      <div class="spinner-layer spinner-red">
        <div class="circle-clipper left">
          <div class="circle"></div>
        </div><div class="gap-patch">
          <div class="circle"></div>
        </div><div class="circle-clipper right">
          <div class="circle"></div>
        </div>
      </div>

      <div class="spinner-layer spinner-yellow">
        <div class="circle-clipper left">
          <div class="circle"></div>
        </div><div class="gap-patch">
          <div class="circle"></div>
        </div><div class="circle-clipper right">
          <div class="circle"></div>
        </div>
      </div>

      <div class="spinner-layer spinner-green">
        <div class="circle-clipper left">
          <div class="circle"></div>
        </div><div class="gap-patch">
          <div class="circle"></div>
        </div><div class="circle-clipper right">
          <div class="circle"></div>
        </div>
      </div>
    </div>&nbsp;
  <p class="blinking">Loading...</p>
</div>
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

	include($_SERVER['DOCUMENT_ROOT'].'/assets/db/db.config.php');
	$db = new mysqli($dbserver, $dbuser, $dbpassword, $database);

	$query_delete_temp_sibi = "DELETE FROM sasroyalties_dev.$clear_table";
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

	$db->close();
}

if ($step==6) { 
	include($_SERVER['DOCUMENT_ROOT'].'/assets/db/db.config.php');
	$db = new mysqli($dbserver, $dbuser, $dbpassword, $database);

	$query_sp = "CALL sasroyalties_dev.step09_load_transactions_to_dev_authors_net_royalties()";
	$query_call_sp=$db->query($query_sp);

	$db->close();
}


?>


