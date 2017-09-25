<?php


// //www.xulonauthors.com/sasfinance/temp_sums.php

//$royalty_month = '2017-06';
$sortorder=$_GET["sort"];
$thisfield=$_REQUEST["thisfield"];

if (!$sortorder && isset($sortorder)) { $sortorder = 'asc'; }
if ($sortorder == 'asc') {
    $sortorder = 'desc';
}
else
{
    $sortorder = 'asc';
}

if (!isset($thisfield)) { $thisfield="file_source"; }

?>

<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Count of temp tables</title>
<link href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css" />
<link href='/xuloncontrolpanel/einstein/css/database.css' rel='stylesheet' type='text/css' /><style-- type='text/css'>
<script src="/assets/js/jquery-1.9.1.min.js"></script>
</head>

<body>

<div id='page-container'>
<div id='infobar'>
    <div class='tealText'>Royalties -- count of temp tables</div>
</div>
<div id='categorybar'>Generate Report</div>
    <div id='accounttable'>
        <table width="775" border="0">
            <tr>
                <td>
                    <label>
                        <input class="btn1" type='button' id='refresh' value='Refresh Data' onclick='refresh()'>
                    </label>
                </td>
            </tr>

        </table>

        <DIV ID='testdiv1' STYLE='position:absolute;visibility:hidden;background-color:white;layer-background-color:white;'></DIV>
    </div>
 <?php
    $writetotext = "file_source\tcounts\ttimestamp\r\n";
 ?>

    <div id='reporttable'>
        <table cellpadding="0px" cellspacing="0px" style="width:100%">
            <tr>
                <th width='80'><div align="center">File Source <a href='?thisfield=file_source&sort=<?=$sortorder?>'><img src='/sasfinance/images/sort_down.jpg' border=0></a></div>
                <th width='80'><div align="center">Count <a href='?thisfield=count&sort=<?=$sortorder?>'><img src='/sasfinance/images/sort_down.jpg' border=0></a></div>
                <th width='80'><div align="center">Timestamp <a href='?thisfield=timestamp&sort=<?=$sortorder?>'><img src='/sasfinance/images/sort_down.jpg' border=0></a></div>
            </tr>

            <?php

                $count=0;

                $db = mysql_connect($dbserver, $dbuser, $dbpassword);
                $database="xulonpress";
                mysql_select_db("$database",$db);

                $Use_Query="
                    SELECT
                        *
                    FROM
                        temp.sasroyalties_counts
                    ORDER BY
                        $thisfield $sortorder
                ";

                display_query_xa($Use_Query);

                $query_getReport = mysql_query($Use_Query, $db);
                if ($myrow = mysql_fetch_array($query_getReport)) {
                    do {
                        $file_source=$myrow["file_source"];
                        $counts=$myrow["count"];
                        $timestamp=$myrow["timestamp"];

                        ## ALTERNATE TABLE ROW COLORS
                        if ($rowcount % 2) {
                            $rowcolor="bgcolor='#F0F0F0'";
                        } else {
                            $rowcolor="bgcolor='white'";
                            unset ($rowcount);
                        }
                        $rowcount++;

                        echo "
                            <tr $rowcolor>
                                <td align='center'>$file_source</td>
                                <td align='center'>".number_format($counts,0)."</td>
                                <td align='center'>$timestamp</td>
                            </tr>
                        ";

                        $total_counts = $total_counts + $counts;
                        $writetotext .= "$file_source\t$counts\t$timestamp\r\n";
                        $count++;
                    } while ($myrow = mysql_fetch_array($query_getReport)); }
                    mysql_close ($db);
                ?>
        </table>
    </div>
    <div id='reporttable2'>
        <table width="775" cellspacing="0px" style="width:100%">
            <tr>
                <th width="119"><div align="left"></div>
                <th width="128"><div align="left"></div>
                <th width="300"><div align="left"></div>
                <th width="119"><div align="left"></div>
                <th width="128"><div align="left"></div>
                <th width="522">
            </tr>
            <?php $writetotext .= "Total Records: $count\r\n"; ?>
            <tr>
                <td width="119"><div align="left"><strong>Total File Sources</strong></div></td>
                <td width="128"><div align="left"><?php echo $count; ?></div></td>
                <td width="300"><div align="left"></div></td>
                <td width="119"><div align="left"><strong>Total Count</strong></div></td>
                <td width="128"><div align="left"><?php echo $total_counts; ?></div></td>
                <td width="522"><form method='POST' action='/xulonreports/csv/<?php echo $_SESSION["xulonname"]; ?>_metadata_report.xls' target='_blank'><input type="submit" name="button2" id="button2" value="Export CSV File" style='width:150px;'></form></td>
            </tr>
        </table>
    <p></div>
</div>
<?php
#### Excel Export

    $filePointer = fopen($_SERVER["DOCUMENT_ROOT"]."/xulonreports/csv/".$_SESSION["xulonname"]."_sasroyalties_temp_sums.xls", "w");
    fputs ($filePointer, "$writetotext", 2000000);
    fclose($filePointer);
?>
?>
<script>
    function refresh() {
        document.getElementById("refresh").disabled = true;
    }

    $(".btn1").click(function(){
        $("html").fadeTo("slow", 0.4);

        $.ajax({
            type: "GET",
            url: '/sasfinance/sasroyalties/temp_sums_refresh.php',
            data: { "refresh":1 },
            success: function (data) {
                document.getElementById("refresh").disabled = false;
                $("html").fadeTo("slow", 1);
                alert('Refresh complete.');
                window.location.reload();
            },
            error: function(data){
                document.getElementById("refresh").disabled = false;
                $("html").fadeTo("slow", 1);
                alert('Error during Refresh.');
            }
        });
    });
</script>
