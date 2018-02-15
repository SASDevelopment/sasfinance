<?php include_once($_SERVER["DOCUMENT_ROOT"]."/cvbtech/authenticate.php"); ?>
<frameset cols="50%,50%">
  <frame src="/_test/sasfinance/hc_royalty_sales.php?GETPID=<?=$_REQUEST['GETPID']?>&HC_ENTITY_ID=<?=$_REQUEST['HC_ENTITY_ID']?>&HC_BOOK_ID=<?=$_REQUEST['HC_BOOK_ID']?>&GETCID=<?=$_REQUEST['GETCID']?>">
  <frame src="https://admin.hillcrestmedia.com/books/<?=$_REQUEST['HC_ENTITY_ID']?>/reports">
</frameset>