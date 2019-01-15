<? 
include_once($_SERVER["DOCUMENT_ROOT"]."/cvbtech/authenticate.php");
include_once('assets/functions.royalties3.php'); 

if (royalties3_royalty_field_form_action()) {
	echo "<span style='color:red;'><i class='tiny material-icons' style='vertical-align:middle'>check</i> Saved</span>";
} else {
	echo "<span style='color:red;'><i class='tiny material-icons' style='vertical-align:middle'>warning</i> An error occurred</span>";
}
?>