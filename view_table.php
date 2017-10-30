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

</head>
<body>
<? 
include_once('assets/functions.royalties3.php'); 

$ORG_FILE_SOURCE=$_REQUEST['ORG_FILE_SOURCE'];

echo royalties3_display_imported_records($ORG_FILE_SOURCE);
?>
</body>
</html>