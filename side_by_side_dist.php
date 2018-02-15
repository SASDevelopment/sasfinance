<?php include_once($_SERVER["DOCUMENT_ROOT"]."/cvbtech/authenticate.php"); ?>
<frameset cols="50%,50%">
  <frame src="/_test/sasfinance/hc_royalty_distribution.php?GETPID=<?=$_REQUEST['GETPID']?>&HC_BOOK_ID=<?=$_REQUEST['HC_BOOK_ID']?>&GETCID=<?=$_REQUEST['GETCID']?>">
  <frame src="https://admin.hillcrestmedia.com/person/<?=$_REQUEST['HC_ENTITY_ID']?>/distributionreport">
</frameset>