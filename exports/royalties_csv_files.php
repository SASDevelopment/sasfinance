<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/cvbtech/authenticate.php");

$file_date = $_REQUEST['filedate'];

?>

<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
<title>Royalty Archive Report</title>

<script type="text/javascript" src="https://code.jquery.com/jquery-1.7.2.min.js"></script>
<script-- src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js">
<link rel="stylesheet" href="/assets/css/jquery-ui.css">
<link href='/xuloncontrolpanel/einstein/css/database.css' rel='stylesheet' type='text/css' /><style type='text/css'>
<script type="text/javascript">
    hs.graphicsDir = '/assets/js/highslide/highslide/graphics/';
    hs.outlineType = 'rounded-white';
    hs.wrapperClassName = 'draggable-header';
    hs.showCredits = false;
</script>
<style>
    <!--
    body,td,th {
        font-size: 12px;
        color:#333333
    }
    body {
        margin: 0px;
    }
    .style5 {font-size: 12px}
    -->

    #divLoading
    {
        display : block;
        position : fixed;
        z-index: 100;
        background-image : url('/images/loadingpleasewait.gif');
        background-color: #ffffff;
        #opacity : 0.7;
        background-repeat : no-repeat;
        background-position : center;
        left : 0;
        bottom : 0;
        right : 0;
        top : 0;
    }
    #myBtn {
        display: none; /* Hidden by default */
        position: fixed; /* Fixed/sticky position */
        bottom: 20px; /* Place the button at the bottom of the page */
        right: 30px; /* Place the button 30px from the right */
        z-index: 99; /* Make sure it does not overlap */
        border: none; /* Remove borders */
        outline: none; /* Remove outline */
        background-color: red; /* Set a background color */
        color: white; /* Text color */
        cursor: pointer; /* Add a mouse pointer on hover */
        padding: 15px; /* Some padding */
        border-radius: 10px; /* Rounded corners */
    }
    #myBtn:hover {
        background-color: #555; /* Add a dark-grey background on hover */
    }
</style>
<<script>
    function createcsv(csvfiles) {
        var arrayLength = csvfiles.length;
        for (var i = 0; i < arrayLength; i++) {
            var str = csvfiles[i];
            var file = str.trim();
            downloadAll(file);
        }
    }

    function downloadAll(file) {
        var urls = '/xuloncontrolpanel/einstein/Protected_Royalty/accounting/'+file;
        var link = document.createElement('a');

        link.setAttribute('download', file);
        link.style.display = 'none';

        document.body.appendChild(link);

        link.setAttribute('href', urls);
        link.click();

        document.body.removeChild(link);
    }
</script>
</head>
<body>

<!--div id='divLoading'></div-->
<!--button onclick="topFunction()" id="myBtn" title="Go to top">Top</button-->

<div id='page-container'>

<div id='infobar'>

<div class='tealText'>Royalty Archive Report</div>

</div>
<div id='categorybar'>Generate Report</div>
    <div id='accounttable'>
        <form id='report' name='report' method='GET' action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>'>
            <table width="775" border="0">
                <br>
                <tr>
                    <td width="200"><strong>File Date:</strong></td>
                </tr>
                <tr>
                    <td>
                        <label>
                            <?=royalty_date();?>
                        </label>
                    </td>
                </tr>
            </table>
        </form>
    </div>
<?php
$writetotext .= "Vendor #\tName\tAddress (Line 1)\tAddress (Line 2)\tCity\tState\tZip\tCountry\tTimestamp showing author made changes\r\n";
?>
<div id='reporttable'>
<table cellpadding="0px" cellspacing="0px" style="width:100%">
    <tr>
        <th align="left" width="50">Date</th>
        <th align="left" width="500">Name</th>
        <th align="left" width="200">Size</th>
    </tr>
<?php
    $csvfiles = array();
    $dir="/var/www/xulonauthors.com/httpsdocs/xuloncontrolpanel/einstein/Protected_Royalty/accounting/";
    $files = scandir($dir);
    $file = 0;
    for($i=0; $i <count($files); $i++) {

        if (!is_dir($files[$i]) && !stristr($files[$i], '.htaccess')) {
            $filedatevalue = date('Y-m-d H:i', filemtime($dir . '/' . $files[$i]));
            $filedate = date("M d Y g:i a", filemtime($dir.'/'.$files[$i]));
            $filename = $files[$i];
            $filesize = formatSizeUnits(filesize($dir . '/' . $files[$i]));
            if (isset($_REQUEST['$file_date']) || $filedatevalue == $file_date) {
                $count++;
                $csvfiles[$file] = $filename;
                $csv_files = json_encode($csvfiles);
                rsort($csv_files);
?>
    <tr <?=$rowcolor?>>
        <td align="left"><?php echo $filedate; ?></td>
        <td align="left"><?php echo $filename; ?></td>
        <td align="left"><?php echo $filesize; ?></td>
    </tr>
<?php
                $file++;
            }
            if (isset($_REQUEST['$file_date']) || $file_date == 'All') {
                $count++;
                ## ALTERNATE TABLE ROW COLORS
                if ($rowcount % 2) {
                    $rowcolor="bgcolor='#F0F0F0'";
                } else {
                    $rowcolor="bgcolor='white'";
                    unset ($rowcount);
                }
                $rowcolor++;

?>
    <tr <?=$rowcolor?>>
        <td align="left"><?php echo $filedate; ?></td>
        <td align="left"><?php echo $filename; ?></td>
        <td align="left"><?php echo $filesize; ?></td>
    </tr>
<?php
            }
        }
    }
?>
</table>
</div>
    <div id='reporttable2'>
        <table width="775" cellspacing="0px" style="width:100%">
            <tr>
                <th width="119"><div align="left"></div></th>
                <th width="128"><div align="left"></div></th>
                <th width="522"><div align="left"></div></th>
                <!--th width="522"-->
            </tr>
            <?php $writetotext .= "Total Records: $count\r\n"; ?>

            <tr>
                <td width="119"><div align="left"><strong>Total Files</strong></div></td>
                <td width="128"><div align="left"><?php echo $count; ?></div></td>
                <? if ($_REQUEST['filedate'] <> 'All' && !empty($_REQUEST['filedate'])) { ?>
                    <td width="128">
                        <input type="submit" name="export_csv" id="export_csv" value="Export all <? echo date("M d Y g:i a", strtotime($_REQUEST['filedate'])); ?> files to CSV" style='width:250px;' onclick='createcsv(<?php echo $csv_files; ?>);'></input>
                    </td>
                <? } else { ?>
                    <td width="128"></td>
                <? } ?>
            </tr>
        </table>
    </div>
</div>

<?php
    function royalty_date() {
        $file_date = $_REQUEST['filedate'];
        $option = "<select id='filedate' name='filedate' onchange='this.form.submit();'>";
        $option .= "<option value='All' $ISSELECTED>All</option>";
        $dir="/var/www/xulonauthors.com/httpsdocs/xuloncontrolpanel/einstein/Protected_Royalty/accounting/";
        $files = scandir($dir);
        rsort($files);
        $temp = '';
        for($i=0; $i <count($files); $i++) {
            if (!is_dir($files[$i]) && !stristr($files[$i], '.htaccess')) {
                //$filedatevalue = date('Y-m-d h:i', filemtime($dir . '/' . $files[$i]));
                $filedatevalue = date('Y-m-d H:i', filemtime($dir . '/' . $files[$i]));
                $filedate = date("M d Y g:i a", filemtime($dir . '/' . $files[$i]));
                if ($filedatevalue == $file_date) { $ISSELECTED = "SELECTED"; } else { $ISSELECTED = ""; }
                if ($filedate <> $temp) {
                    $option .= "<option value='" . $filedatevalue . "' $ISSELECTED>$filedate</option>";
                }

                $temp = $filedate;
            }
        }
        $option .= "</select>";

        return $option;
    }

    function formatSizeUnits($bytes) {
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
    }
?>
