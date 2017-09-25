<?php include_once($_SERVER["DOCUMENT_ROOT"]."/cvbtech/authenticate.php"); ?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!--script src="//code.jquery.com/jquery.ui.position.js"></script-->
<link href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css" />


<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>


<?php
error_reporting(E_ERROR);
$edit = $_GET["edit"];
$add = $_GET["add"];
?>
<html>
<head>
    <style>
        body table, tr, td, ul, li, p, a {
            font-family: Arial, Helvetica, sans-serif;
            font-size: small;
        }

        h2 {
            font-family: Arial, Helvetica, sans-serif;
            font-size: medium;
            background-color: #6A98C7;
            color:white;
            padding:5;
        }

        #divLoading
        {
            display : none;
        }
        #divLoading.show
        {
            display : block;
            position : fixed;
            z-index: 100;
            background-image : url('spinner1.gif');
            background-color:#000000;
            opacity : 0.7;
            background-repeat : no-repeat;
            background-position : center;
            left : 0;
            bottom : 0;
            right : 0;
            top : 0;
        }
        #loadinggif.show
        {
            left : 50%;
            top : 50%;
            position : absolute;
            z-index : 101;
            width : 32px;
            height : 32px;
            margin-left : -16px;
            margin-top : -16px;
        }
        div.content {
            width : 1000px;
            height : 1000px;
        }
    </style>
    <style>
        /*excel*/
        td, th {
            border: solid 1px gray;
        }
        table {
            border-collapse: collapse;
        }
        table#excel, td#excel, tr#excel {
            border: hidden 1px gray;
            border-collapse: collapse;
            vertical-align: 50%;
        }
    </style>
</head>
<body>

<?php

$writetotext = "id\tORG_FILE_SOURCE_RECORD_ID\tORG_FILE_SOURCE\tprojectid\tContact_ID\tRoyalty_ISBN\tORG_TITLE\tORG_AUTHOR\tRoyalty_Month\tRoyalty_Source\tRoyalty_Books_Sold\tRoyalty_Books_Returned\tRoyalty_Amount\tlast_modified_timestamp\trecord_timestamp\timprint_brand_id\troyalty_classfication\tperiod_exchange_rate\r\n";

include($_SERVER['DOCUMENT_ROOT'].'/assets/db/db.connect.php');
$query = "
    SELECT
    sasroyalties.royalty_processing.id,
    sasroyalties.royalty_processing.ORG_FILE_SOURCE_RECORD_ID,
    sasroyalties.royalty_processing.Royalty_Month,
    sasroyalties.royalty_processing.Royalty_Source,
    sasroyalties.royalty_processing.Royalty_ISBN,
    sasroyalties.royalty_processing.Royalty_Books_Sold,
    sasroyalties.royalty_processing.Royalty_Books_Returned,
    sasroyalties.royalty_processing.Royalty_Amount,
    sasroyalties.royalty_processing.projectid,
    sasroyalties.royalty_processing.Contact_ID,
    sasroyalties.royalty_processing.last_modified_timestamp,
    sasroyalties.royalty_processing.record_timestamp,
    sasroyalties.royalty_processing.imprint_brand_id,
    sasroyalties.royalty_processing.ORG_FILE_SOURCE,
    sasroyalties.royalty_processing.ORG_TITLE,
    sasroyalties.royalty_processing.ORG_AUTHOR,
    sasroyalties.royalty_processing.royalty_classfication,
    sasroyalties.royalty_processing.period_exchange_rate,
    sasroyalties.royalty_processing.is_exception,
    sasroyalties.royalty_processing.exclude
    FROM
    sasroyalties.royalty_processing
    WHERE
    sasroyalties.royalty_processing.projectid IS NULL
    AND exclude IS NULL
	AND ORG_FILE_SOURCE != 'eorders_mbo'
    GROUP BY
    Royalty_ISBN
    ORDER BY
    sasroyalties.royalty_processing.ORG_FILE_SOURCE ASC
";
$query_select = mysql_query($query,$db);
$num_records = mysql_num_rows($query_select);
$count = 1;
if($num_records) {
    if ($myrows = mysql_fetch_array($query_select)) {
        do {
            $id = $myrows['id'];
            $ORG_FILE_SOURCE_RECORD_ID = $myrows['ORG_FILE_SOURCE_RECORD_ID'];
            $ORG_FILE_SOURCE = $myrows['ORG_FILE_SOURCE'];
            $projectid = $myrows['projectid'];
            $Contact_ID = $myrows['Contact_ID'];
            $Royalty_ISBN = $myrows['Royalty_ISBN'];
            $ORG_TITLE = $myrows['ORG_TITLE'];
            $ORG_AUTHOR = $myrows['ORG_AUTHOR'];
            $Royalty_Month = $myrows['Royalty_Month'];
            $Royalty_Source = $myrows['Royalty_Source'];
            $Royalty_Books_Sold = $myrows['Royalty_Books_Sold'];
            $Royalty_Books_Returned= $myrows['Royalty_Books_Returned'];
            $Royalty_Amount = number_format(str_replace("$", "", $myrows['Royalty_Amount']), 2);
            $last_modified_timestamp = $myrows['last_modified_timestamp'];
            $record_timestamp = $myrows['record_timestamp'];
            $imprint_brand_id = $myrows['imprint_brand_id'];
            $royalty_classfication = $myrows['royalty_classfication'];
            $period_exchange_rate = $myrows['period_exchange_rate'];

            $isbn_edit = "alert(" . $Royalty_ISBN . ")";
            $roymonth_edit = "alert(" . $Royalty_ISBN . ")";
            $roybooksold_edit = "alert(" . $Royalty_ISBN . ")";
            $roybookreturned_edit = "alert(" . $Royalty_ISBN . ")";
            $royamount_edit = "alert(" . $Royalty_ISBN . ")";
            $class_edit = "alert(" . $Royalty_ISBN . ")";

            $returns = 0;
            $sold = 0;
            if($ORG_FILE_SOURCE == 'temp_ebook_corp') { $returns = 1; }
            if($ORG_FILE_SOURCE == 'temp_itasca') { $returns = 1; }
            if($ORG_FILE_SOURCE == 'temp_ebook_createspace') { $returns = 1; }
            if($ORG_FILE_SOURCE == 'temp_ebook_ebsco') { $returns = 1; }
            if($ORG_FILE_SOURCE == 'temp_ebook_gardners') { $returns = 1; }
            if($ORG_FILE_SOURCE == 'temp_ebook_kobo') { $returns = 1; }
            if($ORG_FILE_SOURCE == 'temp_ebook_scribd') { $returns = 1; }
            if($ORG_FILE_SOURCE == 'temp_ebook_scribd') { $sold = 1; }

            if($myrows["id"] == $edit) {
                $tablerows .= "
                <form name='current' method='post' action='royalties_exceptions_update.php?file=".$ORG_FILE_SOURCE."&id=".$ORG_FILE_SOURCE_RECORD_ID."'>
                    <tr id='edit' $rowcolor>
                        <!--td>" . $id . "</td>
                        <td>" . $ORG_FILE_SOURCE . "</td-->";
                        if($id == $edit) {
                            $tablerows .= " <td><a href='royalties_exceptions_popup.php?file=".$ORG_FILE_SOURCE."&id=".$ORG_FILE_SOURCE_RECORD_ID."' onclick=\"return hs.htmlExpand(this, { objectType: 'iframe', height: '600', width: '900', headingText: '$ORG_FILE_SOURCE ($ORG_FILE_SOURCE_RECORD_ID)' } )\" title=\"Edit\">$ORG_FILE_SOURCE_RECORD_ID</a></td>";
                        }
                        $tablerows .= "
                        <td>" . $projectid . "</td>
                        <td>" . $Contact_ID . "</td>
                        <td><input size='13' autofocus id='Royalty_ISBN_" . $count . "' type='text' style='border:1px solid #ff0000' name='Royalty_ISBN' value='".$myrows["Royalty_ISBN"]."'></td>
                        <td>" . $ORG_TITLE . "<br>" . $ORG_AUTHOR . "</td>
                        <td>" . $Royalty_Month . "</td>
                        <td>" . $Royalty_Source . "</td>
                        ";
                        if($sold == 1) { $tablerows .= "<td>" . $Royalty_Books_Returned . "</td>"; } else { $tablerows .= "<td width='75'><input size='5' id='Royalty_Books_Sold_" . $count . "' type='text' style='border:1px solid #ff0000' name='Royalty_Books_Sold' value='".$myrows["Royalty_Books_Sold"]."'></td>"; }
                        if($returns == 1) { $tablerows .= "<td>" . $Royalty_Books_Returned . "</td>"; } else { $tablerows .= "<td width='75'><input size='5' id='Royalty_Books_Returned_" . $count . "' type='text' style='border:1px solid #ff0000' name='Royalty_Books_Returned' value='".$myrows["Royalty_Books_Returned"]."'></td>"; }
                        $tablerows .= "        
                        <td width='75'><input size='5' id='Royalty_Amount_" . $count . "' type='text' style='border:1px solid #ff0000' name='Royalty_Amount' value='".$myrows["Royalty_Amount"]."'></td>
                        <!--td>" . $last_modified_timestamp . "</td>
                        <td>" . $record_timestamp . "</td>
                        <td>" . $imprint_brand_id . "</td>
                        <td><input type='text' id='royalty_classfication_" . $count . "' style='border:1px solid #ff0000' name='royalty_classfication' value='".$myrows["royalty_classfication"]."'></td>
                        <td>$period_exchange_rate</td-->
                        <td>
                            <input type='image' name='Submit' id='Submit' value='Save' src='../images/save.png' style='vertical-align:middle; height:18; width:18;'/>
                            &nbsp;
                            <a href='".$_SERVER['PHP_SELF']."' title='Cancel' onclick='javascript:urlredirect(management.referral_codes.php);'><img valign='middle' src='../images/cancel.png' height='18' width='18'></img></a>
                        </td>
                    </tr>
                </form>
                ";
            }
            else
            {

                ## ALTERNATE TABLE ROW COLORS
                if ($rowcount % 2) {
                    $rowcolor="bgcolor='#F0F0F0'";
                } else {
                    $rowcolor="bgcolor='white'";
                    unset ($rowcount);
                }
                $rowcount++;

                if(isset($edit)) {
                    if($id <> $edit) { $fgcolor="<font color='#A6ACAF'>"; $rowcolor = "bgcolor=white"; }
                }

                $tablerows .= "
                    <tr id='tr_" . $count . "' $rowcolor>
                        <!--td>$fgcolor$id</font></td>
                        <td>$fgcolor$ORG_FILE_SOURCE</td-->
                        <td><a href='royalties_exceptions_popup.php?file=".$ORG_FILE_SOURCE."&id=".$ORG_FILE_SOURCE_RECORD_ID."' onclick=\"return hs.htmlExpand(this, { objectType: 'iframe', height: '600', width: '900', headingText: '$ORG_FILE_SOURCE ($ORG_FILE_SOURCE_RECORD_ID)' } )\" title=\"Edit\">$ORG_FILE_SOURCE_RECORD_ID</a></td>
                        <td>$fgcolor$projectid</td>
                        <td>$fgcolor$Contact_ID</td>
                        <td>$fgcolor$Royalty_ISBN</td>
                        <td>$fgcolor$ORG_TITLE<br>$ORG_AUTHOR</td>
                        <td>$fgcolor$Royalty_Month</td>
                        <td>$fgcolor$Royalty_Source</td>
                        <td>$fgcolor$Royalty_Books_Sold</td>
                        <td>$fgcolor$Royalty_Books_Returned</td>
                        <td>$fgcolor" . number_format(str_replace("$", "", $Royalty_Amount), 2) . "</td>
                        <!--td>$fgcolor$last_modified_timestamp</td>
                        <td>$fgcolor$record_timestamp</td>
                        <td>$fgcolor$imprint_brand_id</td>
                        <td>$fgcolor$royalty_classfication</td>
                        <td>$fgcolor$period_exchange_rate</td-->
                        <td>
                    ";
                        if(isset($add) == '' && isset($edit) == 0) {
                            $delete_values = "id=".$myrows['id'];
                            $tablerows .= "
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <a href='".$_SERVER['PHP_SELF']."?edit=".$myrows['id']."' value=".$myrows['id']." title='Edit' name=".$myrows["$Royalty_ISBN"]." id=edit_".$myrows['id']." onclick='javascript:popup(this.id)'><img valign='middle' src='../images/pencil.png' height='14' width='14'></img></a>
                                <!--a href='".$_SERVER['PHP_SELF']."?delete=".$myrows['id']."' value=".$delete_values." title='Delete' name=".$myrows['$Royalty_ISBN']." id='Delete' class='confirmDelete'><img valign='top' src='../images/delete.gif'></img></a-->
                            ";
                        }

                    $tablerows .= "
                        </td>
                    </tr>
                ";
            }
            $count++;
            $writetotext .= $myrows['id'] . "\t" . $myrows['ORG_FILE_SOURCE_RECORD_ID'] . "\t" . $myrows['ORG_FILE_SOURCE'] . "\t" . $myrows['projectid'] . "\t" . $myrows['Contact_ID'] . "\t" . $myrows['Royalty_ISBN'] . "\t" . $myrows['ORG_TITLE'] . "\t" . $myrows['ORG_AUTHOR'] . "\t" . $myrows['Royalty_Month'] . "\t" . $myrows['Royalty_Source'] . "\t" . $myrows['Royalty_Books_Sold'] . "\t" . $myrows['Royalty_Books_Returned'] . "\t" . $myrows['Royalty_Amount'] . "\t" . $myrows['last_modified_timestamp'] . "\t" . $myrows['record_timestamp'] . "\t" . $myrows['imprint_brand_id'] . "\t" . $myrows['royalty_classfication'] . "\t" . $myrows['period_exchange_rate'] . "\r\n";
        } while ($myrows = mysql_fetch_array($query_select));
    }
    $writetotext .= "Total Records: $count\r\n";
}
#echo "<br>Records found:".$num_records;
$mailcontent .= "
        <table border=1 width='100%'>
            <!--th>Record ID
            <th>File Source-->
            <th>Original ID
            <th>Project ID
            <th>Contact ID
            <th>ISBN
            <th>Title/Author
            <th>Royalty Month
            <th>Source
            <th>Books Sold
            <th>Books Returned
            <th>Royalty Amount
            <!--th>Last Modified Timestamp
            <th>Record Timestamp
            <th>Imprint
            <th>Class
            <th>Exchange Rate-->
            <th>Options" . $tablerows . "
        </table>
    ";
echo $mailcontent;
?>
<br>
<table id="excel">
    <tr id="excel">
        <td id="excel" width="100"><div align="left"><strong>Total Records:</strong></div></td>
        <td id="excel" width="30"><div align="left"><?php echo $count; ?></div></td>
        <td id="excel" width="155"><form method='POST' action='/xulonreports/csv/<?php echo $_SESSION["xulonname"]; ?>_royalties_exceptions_report.xls' target='_blank'><input type="submit" name="button2" id="button2" value="Export CSV File" style='width:150px;'></form></td>
        <td id="excel" width="155"></td>
        <td width="155"><input class="btn1" type='button' id='reprocess' value='Re-process Exceptions' onclick='reprocess()'></td>
    </tr>
</table>

<div id='divLoading'></div>

<div class="container">
    <!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Royalties Exceptions</h4>
                </div>
                <div class="modal-body">Processing Exceptions changes</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php

#### Excel Export

$filePointer = fopen($_SERVER["DOCUMENT_ROOT"]."/xulonreports/csv/".$_SESSION["xulonname"]."_royalties_exceptions_report.xls", "w");
fputs ($filePointer, "$writetotext", 2000000);
fclose($filePointer);
?>
<script type="text/javascript" src="/assets/js/highslide/highslide/highslide-with-html.js"></script>
    <link rel="stylesheet" type="text/css" href="/assets/js/highslide/highslide/highslide.css" />
    <script type="text/javascript">
    hs.graphicsDir = '/assets/js/highslide/highslide/graphics/';
    hs.outlineType = 'rounded-white';
    hs.wrapperClassName = 'draggable-header';
    hs.showCredits = false;
</script>
<script type="text/javascript">

// http://animizer.net/en/animate-static-image
// //stackoverflow.com/questions/24961121/how-to-display-loading-gif-at-the-center-of-the-screen-which-has-a-scrollbar

    function reprocess() {
        document.getElementById("reprocess").disabled = true;
    }

    $(".btn1").click(function(){
        document.getElementById("reprocess").disabled = true;
        //$("#myModal").modal("show");
        $("div#divLoading").addClass('show');

        //$("table").fadeTo("slow", 0.4);

        $.ajax({
            type: "GET",
            url: '../sasroyalties/royalties_exceptions_reprocess.php',
            data: { "reprocess":1 },
            success: function (data) {
                document.getElementById("reprocess").disabled = false;
                //$("table").fadeTo("slow", 1);
                location.reload();
                //$("#myModal").modal("hide");
                $('#logo').css('background', 'none')
                //alert('Re-process complete.');
            },
            error: function(data){
                document.getElementById("reprocess").disabled = false;
                $("table").fadeTo("slow", 1);
                alert('Error during Re-process.');
            }
        });
    });
</script>