<?php 
$id = $_GET['id'];
$file = $_GET['file'];
?>

<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
    <title>Royalty Exceptions</title>


    <link href='/xuloncontrolpanel/einstein/css/database.css' rel='stylesheet' type='text/css' />
    <style type='text/css'>
        <!--
        body,td,th {
            font-size: 12px;
            color:#333333;
            border: solid 1px gray;
        }
        body {
            margin: 0px;

        }
        -->
    </style></head>
<body>
<div id='page-container'>
    <div id='categorybar'>
        <a href=''><img src='/xuloncontrolpanel/einstein/sasfinance/images/triangle_side.gif' alt='Triangle' border='0' width='16' height='14' align='absmiddle' /></a>Royalty Exceptions</div>
            <div id='accounttable'>
                <table border='0'>
<?php
$query = "SELECT * FROM sasroyalties_dev." . $file . " WHERE id = '".$id."' LIMIT 1";
$query_select = mysql_query($query,$db);
$num_records = mysql_num_rows($query_select);
if(!$num_records) { echo "<br>Record not found or Table empty<br><br>"; } else {
    ?>
    <tr>
        <td width='100'>
            <div class='bold'>Temp File</div>
        </td>
        <td width='700' align=left><?php echo $file; ?></td>
    </tr>
    <?php
}
if ($myrows = mysql_fetch_array($query_select)) {

    foreach($myrows as $key=>$val) {
        if (!is_numeric($key)) {
            ${ $key_name } = $key." = ".$val;
?>
                    <tr>
                        <td width='100'><div class='bold'><?php echo $key; ?></div></td>
                        <td width='700' align=left><?php echo $val; ?></td>
                    </tr>
<?php
        }
    }
}
?>
        </table>
    </div>
<?php

?>
</body>
</html>
