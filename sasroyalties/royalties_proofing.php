<?php error_reporting(E_ERROR);  ?>
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
function sources() {
    include($_SERVER['DOCUMENT_ROOT'] . '/assets/db/db.connect.php');
    $display_table = 0;
    $writetotext = "File Source\tSource ID\tProject ID\tISBN\tTitle\tAuthor\tRoyalty Books Sold\tTemp Sold\tRoyalty Books Returned\tTemp Returned\tTemp Total\tRoyalty Amount\tClass\r\n";

    //$query = "SELECT DISTINCT(ORG_FILE_SOURCE) AS file_source FROM sasroyalties.royalty_processing WHERE ORG_FILE_SOURCE = 'temp_itasca';";
    $query = "SELECT DISTINCT(ORG_FILE_SOURCE) AS file_source FROM sasroyalties.royalty_processing;";
    //echo $query;
    $file_sources_select = mysql_query($query, $db);

    if ($file_sources = mysql_fetch_array($file_sources_select)) {
        do {
            #echo "<br>File Source: ".$file_sources['file_source'];

            $fields = '';

            // eBook Amazon
            if ($file_sources['file_source'] == 'temp_ebook_amazon') {
                $fields = "
                    sasroyalties.royalty_processing.ORG_TITLE AS Title,
                    sasroyalties_dev." . $file_sources['file_source'] . ".`Units Sold` AS sold,
                    sasroyalties_dev." . $file_sources['file_source'] . ".`Units Refunded` AS returned,
                    sasroyalties_dev." . $file_sources['file_source'] . ".`Royalty` AS temp_total,
                ";
            }

            // Barnes and Noble
            if ($file_sources['file_source'] == 'temp_ebook_bn') {
                $fields = "
                    sasroyalties.royalty_processing.ORG_TITLE AS Title,
                    sasroyalties_dev." . $file_sources['file_source'] . ".`Units Sold` AS sold,
                    sasroyalties_dev." . $file_sources['file_source'] . ".`Units Returned` AS returned,
                    sasroyalties_dev." . $file_sources['file_source'] . ".`Total Royalty` AS temp_total,
                ";
            }

            // eBook Corp
            if ($file_sources['file_source'] == 'temp_ebook_corp') {
                $fields = "
                    sasroyalties.royalty_processing.ORG_TITLE AS Title,
                    sasroyalties_dev." . $file_sources['file_source'] . ".`Quantity` AS sold,
                    #sasroyalties_dev." . $file_sources['file_source'] . ".`Units Returned` AS returned,
                    sasroyalties_dev." . $file_sources['file_source'] . ".`PubDue` AS temp_total,
                ";
            }

            // eBook createspace
            if ($file_sources['file_source'] == 'temp_ebook_createspace') {
                $fields = "
                    sasroyalties.royalty_processing.ORG_TITLE AS Title,
                    sasroyalties_dev." . $file_sources['file_source'] . ".`Quantity` AS sold,
                    #sasroyalties_dev." . $file_sources['file_source'] . ".`Units Returned` AS returned,
                    sasroyalties_dev." . $file_sources['file_source'] . ".`Royalty` AS temp_total,
                ";
            }

            // eBook ebsco
            if ($file_sources['file_source'] == 'temp_ebook_ebsco') {
                $fields = "
                    sasroyalties.royalty_processing.ORG_TITLE AS Title,
                    sasroyalties_dev." . $file_sources['file_source'] . ".`QTY` AS sold,
                    #sasroyalties_dev." . $file_sources['file_source'] . ".`Units Returned` AS returned,
                    sasroyalties_dev." . $file_sources['file_source'] . ".`ROYALTY DUE` AS temp_total,
                ";
            }

            // eBook gardners
            if ($file_sources['file_source'] == 'temp_ebook_gardners') {
                $fields = "
                    sasroyalties.royalty_processing.ORG_TITLE AS Title,
                    sasroyalties_dev." . $file_sources['file_source'] . ".`UNITS` AS sold,
                    #sasroyalties_dev." . $file_sources['file_source'] . ".`Units Returned` AS returned,
                    #sasroyalties_dev." . $file_sources['file_source'] . ".`TOTAL-NET-LINE-VALUE` AS temp_total,
                ";
            }

            // eBook itunes
            if ($file_sources['file_source'] == 'temp_ebook_itunes') {
                $fields = "
                    CASE WHEN sasroyalties_dev.temp_ebook_itunes.`Sales or Return` = 'S' THEN
                        ABS(sasroyalties_dev.temp_ebook_itunes.`Quantity`)
                    ELSE
					    0
                    END AS sold,
                    CASE WHEN sasroyalties_dev.temp_ebook_itunes.`Sales or Return` = 'R' THEN
                        ABS(sasroyalties_dev.temp_ebook_itunes.`Quantity`)
			        ELSE
					    0
                    END AS returned,
                    #sasroyalties_dev.temp_ebook_itunes.`ROYALTY DUE` AS temp_total,
                    sasroyalties_dev." . $file_sources['file_source'] . ".`Extended Partner Share` AS temp_total,
                    sasroyalties_dev." . $file_sources['file_source'] . ".`Artist/Show/Developer/Author` AS Author,
                ";
            }

            // eBook kobo
            if ($file_sources['file_source'] == 'temp_ebook_kobo') {
                $fields = "
                    sasroyalties.royalty_processing.ORG_TITLE AS Title,
                    CASE WHEN sasroyalties_dev.temp_ebook_kobo.`Total Qty` > 0 THEN
                        ABS(sasroyalties_dev.temp_ebook_kobo.`Total Qty`)
                    ELSE
                        0
                    END AS sold,
                    CASE WHEN sasroyalties_dev.temp_ebook_kobo.`Total Qty` < 0 THEN
                        ABS(sasroyalties_dev.temp_ebook_kobo.`Total Qty`)
                    ELSE
                        0
                    END AS returned,
                    #sasroyalties_dev.temp_ebook_kobo.`COGS (Payable Currency)` AS temp_total,
                    sasroyalties_dev.temp_ebook_kobo.`Net Due (Payable Currency)` AS temp_total,
                ";
            }

            // eBook overdrive
            if ($file_sources['file_source'] == 'temp_ebook_overdrive') {
                $fields = "
                    sasroyalties.royalty_processing.ORG_TITLE AS Title,
                    NULL AS Sold, #sasroyalties_dev." . $file_sources['file_source'] . ".`UNITS` AS sold,
                    NULL AS returned, #sasroyalties_dev." . $file_sources['file_source'] . ".`Units Returned` AS returned,
                    sasroyalties_dev." . $file_sources['file_source'] . ".`Amt owed USD` AS temp_total,
                ";
            }

            // eBook Proquest
            if ($file_sources['file_source'] == 'temp_ebook_proquest') {
                $fields = "
                    sasroyalties.royalty_processing.ORG_TITLE AS Title,
                    sasroyalties_dev." . $file_sources['file_source'] . ".`Quantity` AS sold,
                    0 AS returned, #sasroyalties_dev." . $file_sources['file_source'] . ".`MTD_return_quantity` AS returned,
                    sasroyalties_dev." . $file_sources['file_source'] . ".`Publisher Revenue` AS temp_total,
                ";
            }

            // eBook scribd
            if ($file_sources['file_source'] == 'temp_ebook_scribd') {
                $fields = "
                    sasroyalties.royalty_processing.ORG_TITLE AS Title,
                    1 AS sold,
                    NULL AS returned,
                    sasroyalties_dev." . $file_sources['file_source'] . ".`Amount owed for this interaction` AS temp_total,
                ";
            }

            // Itasca
            if ($file_sources['file_source'] == 'temp_itasca') {
                $fields = "
                    CASE WHEN sasroyalties_dev." . $file_sources['file_source'] . ".`Qty` > 0 THEN
                        ABS(sasroyalties_dev." . $file_sources['file_source'] . ".`Qty`)
			        ELSE
					    0
                    END AS sold,
                    CASE WHEN sasroyalties_dev." . $file_sources['file_source'] . ".`Qty` < 0 THEN
                        ABS(sasroyalties_dev." . $file_sources['file_source'] . ".`Qty`)
			        ELSE
					    0
                    END AS returned,
                    sasroyalties_dev." . $file_sources['file_source'] . ".Total1 AS temp_total,
                ";
            }

            // lsi
            if ($file_sources['file_source'] == 'temp_lsi') {
                $fields = "
                    sasroyalties.royalty_processing.ORG_TITLE AS Title,
                    sasroyalties_dev." . $file_sources['file_source'] . ".`PTD_Quantity` AS sold,
                    ABS(sasroyalties_dev." . $file_sources['file_source'] . ".`PTD_return_quantity`) AS returned,
                    ABS(sasroyalties_dev." . $file_sources['file_source'] . ".`PTD_pub_comp`) AS temp_total,
                ";
            }

            // lsi aud
            if ($file_sources['file_source'] == 'temp_lsi_aud') {
                $fields = "
                    sasroyalties.royalty_processing.ORG_TITLE AS Title,
                    sasroyalties_dev." . $file_sources['file_source'] . ".`MTD_Quantity` AS sold,
                    sasroyalties_dev." . $file_sources['file_source'] . ".`MTD_return_quantity` AS returned,
                    sasroyalties_dev." . $file_sources['file_source'] . ".`MTD_pub_comp` * sasroyalties_dev.temp_lsi_aud.`period_exchange_rate` AS temp_total,
                ";
            }

            // lsi ul
            if ($file_sources['file_source'] == 'temp_lsi_uk') {
                $fields = "
                    sasroyalties.royalty_processing.ORG_TITLE AS Title,
                    sasroyalties_dev." . $file_sources['file_source'] . ".`PTD_Quantity` AS sold,
                    0 AS returned, #sasroyalties_dev." . $file_sources['file_source'] . ".`MTD_return_quantity` AS returned,
                    sasroyalties_dev." . $file_sources['file_source'] . ".`PTD_pub_comp` * sasroyalties_dev." . $file_sources['file_source'] . ".`period_exchange_rate` AS temp_total,
                ";
            }

            if ($fields) {
                $result = detail($file_sources['file_source'],$fields,$writetotext);
                $tablerows .= $result[0];
                $num_record = $result[1];
                $writetotext = $result[2];
                $count += $result[3];
                if($num_record > 0) { $display_table = 1; }
            }
        } while ($file_sources = mysql_fetch_array($file_sources_select));
    }
    if ($display_table == 1) {
        $mailcontent .= "
            <table>
                <th>File Source
                <th>Source ID
                <th>Project ID
                <th>ISBN
                <th>Title
                <th>Author
                <th>Royalty Books Sold
                <th>Temp Sold
                <th>Royalty Books Returned
                <th>Temp Returned
                <th>Royalty Amount
                <th>Temp Total
                <th>Class" . $tablerows . "
            </table>
        ";
        echo $mailcontent;
        ?>
        <br>
        <table id="excel">
            <tr id="excel">
                <td id="excel" width="100"><div align="left"><strong>Total Records:</strong></div></td>
                <td id="excel" width="30"><div align="left"><?php echo $count; ?></div></td>
                <td id="excel" width="155"><form method='POST' action='/xulonreports/csv/<?php echo $_SESSION["xulonname"]; ?>_royalties_proofing_report.xls' target='_blank'><input type="submit" name="button2" id="button2" value="Export CSV File" style='width:150px;'></form></td>
            </tr>
        </table>
    <?php
        $filePointer = fopen($_SERVER["DOCUMENT_ROOT"]."/xulonreports/csv/".$_SESSION["xulonname"]."_royalties_proofing_report.xls", "w");
        fputs ($filePointer, "$writetotext", 2000000);
        fclose($filePointer);
    }
    else
    {
        echo "<p>No records found</p>";
    }
}

function detail($file_sources,$fields,$writetotext) {
    if(!$fields) { return; }
    include($_SERVER['DOCUMENT_ROOT'].'/assets/db/db.connect.php');
    $source_select = "
        SELECT
            sasroyalties.royalty_processing.`ORG_FILE_SOURCE_RECORD_ID` AS Source_ID,
            sasroyalties.royalty_processing.`Royalty_ISBN`,
            sasroyalties.royalty_processing.`projectid`,
            CASE WHEN sasroyalties.royalty_processing.`ORG_AUTHOR` = '' THEN
                NULL
            ELSE
                sasroyalties.royalty_processing.`ORG_AUTHOR`
            END AS Author,
            CASE WHEN sasroyalties.royalty_processing.ORG_TITLE = '' THEN
                NULL
            ELSE
                sasroyalties.royalty_processing.ORG_TITLE
            END AS Title,
                # sasroyalties.royalty_processing.`id`,
                # sasroyalties.royalty_processing.`ORG_FILE_SOURCE_RECORD_ID` AS file_source_id,
                # sasroyalties_dev.".$file_sources.".id AS temp_id`,
            sasroyalties.royalty_processing.`Royalty_Books_Sold`,
            sasroyalties.royalty_processing.`Royalty_Books_Returned`,
            sasroyalties.royalty_processing.`royalty_Amount`,
            ".$fields."
            sasroyalties.royalty_processing.`royalty_classfication` AS Class
        FROM
            sasroyalties.royalty_processing
        INNER JOIN sasroyalties_dev.".$file_sources." ON sasroyalties.royalty_processing.`ORG_FILE_SOURCE_RECORD_ID` = sasroyalties_dev.".$file_sources.".`id`
        WHERE 
            sasroyalties.royalty_processing.ORG_FILE_SOURCE = '".$file_sources."'
        HAVING 
            (
                sasroyalties.royalty_processing.`Royalty_Books_Sold` <> sold
                OR sasroyalties.royalty_processing.`Royalty_Books_Returned` <> returned
                OR sasroyalties.royalty_processing.`royalty_Amount` <> temp_total
            )
        ORDER BY sasroyalties.royalty_processing.Royalty_ISBN
    ";
    //echo $source_select;
    $source_select = mysql_query($source_select,$db);
    $num_records = mysql_num_rows($source_select);

#    echo "<br>";
#    echo "Records found:".$num_records;
#    echo "<br>";

    if($num_records) {
        if ($source = mysql_fetch_array($source_select)) {
            do {
                $Source_ID = $source['Source_ID'];
                $projectid = $source['projectid'];
                $Royalty_ISBN = $source['Royalty_ISBN'];
                $Title = $source['Title'];
                $Author = $source['Author'];
                $Royalty_Books_Sold = $source['Royalty_Books_Sold'];
                $sold = $source['sold'];
                $Royalty_Books_Returned = $source['Royalty_Books_Returned'];
                $returned = $source['returned'];
                $temp_total = number_format(str_replace("$", "", $source['temp_total']), 2);
                $royalty_Amount = number_format(str_replace("$", "", $source['royalty_Amount']), 2);
                $Class = $source['Class'];

                ## ALTERNATE TABLE ROW COLORS
                if ($rowcount % 2) {
                    $rowcolor="bgcolor='#F0F0F0'";
                } else {
                    $rowcolor="bgcolor='white'";
                    unset ($rowcount);
                }
                $rowcount++;

                $tablerows .= "
                    <tr $rowcolor>
                        <td>" . $file_sources . "</td>
                        <td><a href='royalties_proofing_popup.php?file=".$file_sources."&id=".$Source_ID."' onclick=\"return hs.htmlExpand(this, { objectType: 'iframe', height: '600', width: '900', headingText: '$file_sources ($Source_ID)' } )\" title=\"Edit\">$Source_ID</a></td>
                        <td>" . $projectid . "</td>
                        <td>" . $Royalty_ISBN . "</td>
                        <td>" . $Title . "</td>
                        <td>" . $Author . "</td>
                        <td>" . $Royalty_Books_Sold . "</td>
                        <td>" . $sold . "</td>
                        <td>" . $Royalty_Books_Returned . "</td>
                        <td>" . $returned . "</td>
                        <td>$" . $royalty_Amount . "</td>
                        <td>$" . $temp_total . "</td>
                        <td>" . $Class . "</td>
                    </tr>
                ";
                $count++;
                $writetotext .= $file_sources . "\t" . $Source_ID . "\t" . $projectid . "\t" . $Royalty_ISBN . "\t" . $Title . "\t" . $Author . "\t" . $Royalty_Books_Sold . "\t" . $sold . "\t" . $Royalty_Books_Returned . "\t" . $returned . "\t" . $royalty_Amount . "\t". $temp_total . "\t" . $Class . "\r\n";
            } while ($source = mysql_fetch_array($source_select));
        }
    }
    return array($tablerows,$num_records,$writetotext,$count);
}

sources();
?>
<script type="text/javascript" src="/assets/js/highslide/highslide/highslide-with-html.js"></script>
    <link rel="stylesheet" type="text/css" href="/assets/js/highslide/highslide/highslide.css" />
    <script type="text/javascript">
    hs.graphicsDir = '/assets/js/highslide/highslide/graphics/';
    hs.outlineType = 'rounded-white';
    hs.wrapperClassName = 'draggable-header';
    hs.showCredits = false;
</script>