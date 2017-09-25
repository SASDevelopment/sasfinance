<?php  ?>
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
    include($_SERVER['DOCUMENT_ROOT'].'/assets/db/db.connect.php');

    $query_select = "
        SELECT
            ProductDistributionHistory.Description,
            CASE WHEN LEFT(SUBSTRING_INDEX(ProductDistributionHistory.Description,' ',1), 1) = 'Q' THEN
                SUBSTRING_INDEX(ProductDistributionHistory.Description,' ',1)
            END AS quarter,
            CASE WHEN LEFT(SUBSTRING_INDEX(ProductDistributionHistory.Description,' ',1), 1) != 'Q' THEN
                SUBSTRING_INDEX(ProductDistributionHistory.Description,' ',1)
            END AS `month`,
            SUBSTRING_INDEX(SUBSTRING(ProductDistributionHistory.Description, instr(ProductDistributionHistory.Description, ' ') + 1),' ',1) AS `year`,
            ProductDistributionHistory.Name,
            COUNT(*) AS count
        FROM Paul.ProductDistributionHistory
        WHERE ProductDistributionHistory.Amount != 0
        AND ProductDistributionHistory.Description LIKE '%MONTHLY%'
        GROUP BY ProductDistributionHistory.Description, ProductDistributionHistory.Name
        HAVING count > 1
        ORDER BY ProductDistributionHistory.Name, SUBSTRING_INDEX(SUBSTRING(ProductDistributionHistory.Description, instr(ProductDistributionHistory.Description, ' ') + 1),' ',1), MONTH(STR_TO_DATE(SUBSTRING_INDEX(ProductDistributionHistory.Description,' ',1),'%b'))
    ";
    //echo $source_select;
    $query_select = mysql_query($query_select,$db);
    $num_records = mysql_num_rows($query_select);

#    echo "<br>";
#    echo "Records found:".$num_records;
#    echo "<br>";

    if($num_records) {
        $writetotext = "Description\tMonth\tYear\tName\tDuplicates\r\n";

        $count = 1;
        if ($myrow = mysql_fetch_array($query_select)) {
            do {
                $description = $myrow['Description'];
                $quarter = $myrow['quarter'];
                $month = $myrow['month'];
                $year = $myrow['year'];
                $name = $myrow['Name'];
                $duplicate_count = $myrow['count'];

                $tablerows .= "
                    <tr bgcolor=$bgcolor>
                        <td width='200'>" . $description . "</td>
                        <td width='50'>" . $month . "</td>
                        <td width='50'>" . $year . "</td>
                        <td width='250'>" . $name . "</td>
                        <td>" . $duplicate_count . "</td>
                    </tr>
                ";
                $writetotext .= $description . "\t" . $month . "\t" . $year . "\t" . $name . "\t" . $duplicate_count . "\r\n";
                $count++;
            } while ($myrow = mysql_fetch_array($query_select));
        }
            else
        {
            echo "<p>No records found</p>";
        }

        $mailcontent .= "
            <table border=1>
                <th>Description
                <th>Month
                <th>Year
                <th>Name
                <th>Duplicates" . $tablerows . "
            </table>
        ";
        echo $mailcontent;
    }

    $filePointer = fopen($_SERVER["DOCUMENT_ROOT"]."/xulonreports/csv/".$_SESSION["xulonname"]."_royalties_proofing_report.xls", "w");
    fputs ($filePointer, "$writetotext", 2000000);
    fclose($filePointer);
?>
<br>
<table id="excel">
    <tr id="excel">
        <td id="excel" width="100"><div align="left"><strong>Total Records:</strong></div></td>
        <td id="excel" width="30"><div align="left"><?php echo $count; ?></div></td>
        <td id="excel" width="155"><form method='POST' action='/xulonreports/csv/<?php echo $_SESSION["xulonname"]; ?>_royalties_hillcrest_duplicates_report.xls' target='_blank'><input type="submit" name="button2" id="button2" value="Export CSV File" style='width:150px;'></form></td>
    </tr>
</table>

