<?php

include($_SERVER['DOCUMENT_ROOT'].'/assets/db/db.connect.php');

if ($_GET['refresh'] == 1) {
    $query = "DELETE FROM temp.sasroyalties_counts;";
    $query_delete = mysql_query($query, $db);

    $timestamp = date("Y-m-d H:i:s");
    $query = "SELECT CONCAT(table_schema, '.', table_name) AS table_names FROM information_schema.tables WHERE table_schema = 'sasroyalties_dev' AND table_name LIKE 'temp%';";
    $query_select = mysql_query($query, $db);
    if ($myrow = mysql_fetch_array($query_select)) {
        do {
            $report = 0;
            if ($myrow['table_names'] == 'sasroyalties_dev.temp_ebook_amazon') {
                $table = substr($myrow['table_names'], strpos($myrow['table_names'], ".") + 1);
                /*
                $royalty_amount = "REPLACE(FORMAT(SUM(`Royalty`), 2), ',', '') AS royalty_amount,";
                $royalty_period = "DATE_FORMAT(STR_TO_DATE(`Date`, '%m/%d/%Y'), '%Y-%m-%d') AS `royalty_month`";
                $where = "DATE_FORMAT(STR_TO_DATE(`Date`, '%m/%d/%Y'), '%Y-%m') = '" . $royalty_month . "'";
                */
                $royalty_amount = "COUNT(*) AS `count`";
                $where = "1";
                $report = 1;
            }

            if ($myrow['table_names'] == 'sasroyalties_dev.temp_ebook_bn') {
                $table = substr($myrow['table_names'], strpos($myrow['table_names'], ".") + 1);
                /*
                $royalty_amount = "REPLACE(FORMAT(SUM(`Total Royalty`), 2), ',', '') AS royalty_amount,";
                $royalty_period = "DATE_FORMAT(STR_TO_DATE(`Date of Sale`, '%m/%d/%Y'), '%Y-%m-%d') AS `royalty_month`";";
                $where = "DATE_FORMAT(STR_TO_DATE(" . $royalty_period . ", '%m/%d/%Y'), '%Y-%m') = '" . $royalty_month . "'";
                */
                $royalty_amount = "COUNT(*) AS `count`";
                $where = "1";
                $report = 1;
            }

            if ($myrow['table_names'] == 'sasroyalties_dev.temp_ebook_corp') {
                $table = substr($myrow['table_names'], strpos($myrow['table_names'], ".") + 1);
                /*
                $royalty_amount = ""; // "REPLACE(FORMAT(SUM(`Total Royalty`), 2), ',', '') AS royalty_amount,";
                $royalty_period = "DATE_FORMAT(STR_TO_DATE(`PubDue`, '%m/%d/%Y'), '%Y-%m-%d') AS royalty_amount,";
                $where = "DATE_FORMAT(STR_TO_DATE(`Date of Sale`, '%m/%d/%Y'), '%Y-%m') = '" . $royalty_month . "'";
                $report = 0;
                */
                $royalty_amount = "COUNT(*) AS `count`";
                $where = "1";
                $report = 1;
            }

            if ($myrow['table_names'] == 'sasroyalties_dev.temp_ebook_createspace') {
                $table = substr($myrow['table_names'], strpos($myrow['table_names'], ".") + 1);
                /*
                $royalty_amount = "REPLACE(FORMAT(SUM(`Royalty`), 2), ',', '') AS royalty_amount,";
                $royalty_period = "DATE_FORMAT(STR_TO_DATE(`Sale Date`, '%m/%d/%Y'), '%Y-%m-%d') AS royalty_amount,";
                $where = "DATE_FORMAT(STR_TO_DATE(`Sale Date`, '%m/%d/%Y'), '%Y-%m') = '" . $royalty_month . "'";
                */
                $royalty_amount = "COUNT(*) AS `count`";
                $where = "1";
                $report = 1;
            }

            if ($myrow['table_names'] == 'sasroyalties_dev.temp_ebook_ebsco') {
                $table = substr($myrow['table_names'], strpos($myrow['table_names'], ".") + 1);
                /*
                $royalty_amount = "REPLACE(FORMAT(SUM(`PUBDUE`), 2), ',', '') AS royalty_amount,";
                $royalty_period = "DATE_FORMAT(STR_TO_DATE(`Sale Date`, '%m/%d/%Y'), '%Y-%m-%d') AS royalty_amount,";
                $where = "DATE_FORMAT(STR_TO_DATE(`Sale Date`, '%m/%d/%Y'), '%Y-%m') = '" . $royalty_month . "'";
                */
                $royalty_amount = "COUNT(*) AS `count`";
                $where = "1";
                $report = 1;
            }

            if ($myrow['table_names'] == 'sasroyalties_dev.temp_ebook_gardners') {
                $table = substr($myrow['table_names'], strpos($myrow['table_names'], ".") + 1);
                /*
                $royalty_amount = "REPLACE(FORMAT(SUM(`TOTAL-NET-LINE-VALUE`), 2), ',', '') AS royalty_amount,";
                $royalty_period = "
                        CASE WHEN SUBSTRING(`SALE-DATE`,3,1) = '/' THEN
                            DATE_FORMAT(STR_TO_DATE(`SALE-DATE`,'%d/%m/%Y'), '%Y-%m-%d')
                        ELSE
                            DATE_FORMAT(`SALE-DATE`, '%Y-%m-%d')
                        END AS royalty_amount,
                ";
                $where = "DATE_FORMAT(STR_TO_DATE(`SALE-DATE`,'%d/%m/%Y'), '%Y-%m') = '" . $royalty_month . "'";
                */
                $royalty_amount = "COUNT(*) AS `count`";
                $where = "1";
                $report = 1;
            }

            if ($myrow['table_names'] == 'sasroyalties_dev.temp_ebook_itunes') {
                $table = substr($myrow['table_names'], strpos($myrow['table_names'], ".") + 1);
                /*
                $royalty_amount = "REPLACE(FORMAT(SUM(`Extended Partner Share`), 2), ',', '') AS royalty_amount,";
                $royalty_period = "DATE_FORMAT(`Start Date`, '%Y-%m-%d') AS royalty_amount,";
                $where = "DATE_FORMAT(`Start Date`, '%Y-%m') = '" . $royalty_month . "'";
                */
                $royalty_amount = "COUNT(*) AS `count`";
                $where = "1";
                $report = 1;
            }

            if ($myrow['table_names'] == 'sasroyalties_dev.temp_ebook_kobo') {
                $table = substr($myrow['table_names'], strpos($myrow['table_names'], ".") + 1);
                /*
                $royalty_amount = "REPLACE(FORMAT(SUM(`Net Due (Payable Currency)`), 2), ',', '') AS royalty_amount,";
                $royalty_period = "DATE_FORMAT(`Date`, '%Y-%m-%d') AS royalty_amount AS royalty_amount,";,
                $where = "DATE_FORMAT(`Date`, '%Y-%m') = '" . $royalty_month . "'";
                */
                $royalty_amount = "COUNT(*) AS `count`";
                $where = "1";
                $report = 1;
            }

            if ($myrow['table_names'] == 'sasroyalties_dev.temp_ebook_overdrive') {
                $table = substr($myrow['table_names'], strpos($myrow['table_names'], ".") + 1);
                /*
                $royalty_amount = "REPLACE(FORMAT(SUM(`Amt owed USD`), 2), ',', '') AS royalty_amount,";
                $royalty_period = "DATE_FORMAT(`Date`, '%Y-%m-%d') AS royalty_amount,";
                $where = "DATE_FORMAT(" . $royalty_period . ", '%Y-%m') = '" . $royalty_month . "'";
                */
                $royalty_amount = "COUNT(*) AS `count`";
                $where = "1";
                $report = 1;
            }

            if ($myrow['table_names'] == 'sasroyalties_dev.temp_ebook_proquest') {
                $table = substr($myrow['table_names'], strpos($myrow['table_names'], ".") + 1);
                /*
                $royalty_amount = "REPLACE(FORMAT(SUM(`Publisher Revenue`), 2), ',', '') AS royalty_amount,";
                $royalty_period = "DATE_FORMAT(`Transaction Date`, '%Y-%m-%d')";
                $where = "DATE_FORMAT(`Transaction Date`, '%Y-%m') = '" . $royalty_month . "'";
                */
                $royalty_amount = "COUNT(*) AS `count`";
                $where = "1";
                $report = 1;
            }

            if ($myrow['table_names'] == 'sasroyalties_dev.temp_ebook_scribd') {
                $table = substr($myrow['table_names'], strpos($myrow['table_names'], ".") + 1);
                /*
                $royalty_amount = "REPLACE(FORMAT(SUM(`Amount owed for this interaction`), 2), ',', '') AS royalty_amount,";
                $royalty_period = "DATE_FORMAT(`Transaction Date`, '%Y-%m-%d')";
                $where = "DATE_FORMAT(`Transaction Date`, '%Y-%m') = '" . $royalty_month . "'";
                */
                $royalty_amount = "COUNT(*) AS `count`";
                $where = "1";
                $report = 1;
            }

            if ($myrow['table_names'] == 'sasroyalties_dev.temp_itasca') {
                $table = substr($myrow['table_names'], strpos($myrow['table_names'], ".") + 1);
                /*
                $royalty_amount = "REPLACE(FORMAT(SUM(`Total1`), 2), ',', '') AS royalty_amount,";
                $royalty_period = "DATE_FORMAT(`Transaction Date`, '%Y-%m-%d')";
                $where = "DATE_FORMAT(`Transaction Date`, '%Y-%m') = '" . $royalty_month . "'";
                */
                $royalty_amount = "COUNT(*) AS `count`";
                $where = "1";
                $report = 1;
            }

            if ($myrow['table_names'] == 'sasroyalties_dev.temp_lsi') {
                $table = substr($myrow['table_names'], strpos($myrow['table_names'], ".") + 1);
                /*
                $royalty_amount = "REPLACE(FORMAT(SUM(`PTD_pub_comp`), 2), ',', '') AS royalty_amount,";
                $royalty_period = "DATE_FORMAT(`Transaction Date`, '%Y-%m-%d')";
                $where = "DATE_FORMAT(`Transaction Date`, '%Y-%m') = '" . $royalty_month . "'";
                */
                $royalty_amount = "COUNT(*) AS `count`";
                $where = "1";
                $report = 1;
            }

            if ($myrow['table_names'] == 'sasroyalties_dev.temp_lsi_aud') {
                $table = substr($myrow['table_names'], strpos($myrow['table_names'], ".") + 1);
                /*
                $royalty_amount = "REPLACE(FORMAT(SUM(`MTD_pub_comp`), 2), ',', '') AS royalty_amount,";
                $royalty_period = "DATE_FORMAT(`Transaction Date`, '%Y-%m-%d')";
                $where = "DATE_FORMAT(`Transaction Date`, '%Y-%m') = '" . $royalty_month . "'";
                */
                $royalty_amount = "COUNT(*) AS `count`";
                $where = "1";
                $report = 1;
            }

            if ($myrow['table_names'] == 'sasroyalties_dev.temp_lsi_uk') {
                $table = substr($myrow['table_names'], strpos($myrow['table_names'], ".") + 1);
                /*
                $royalty_amount = "REPLACE(FORMAT(SUM(`PTD_pub_comp`), 2), ',', '') AS royalty_amount,";
                $royalty_period = "DATE_FORMAT(`Transaction Date`, '%Y-%m-%d')";
                $where = "DATE_FORMAT(`Transaction Date`, '%Y-%m') = '" . $royalty_month . "'";
                */
                $royalty_amount = "COUNT(*) AS `count`";
                $where = "1";
                $report = 1;
            }

            if ($report == 1) {
                $query1 = "
                    INSERT INTO temp.sasroyalties_counts
                    SELECT
                        '',
                        '" . $table . "' AS file_source,
                        " . $royalty_amount . ",
                        '" . $timestamp . "'
                    FROM
                        " . $myrow['table_names'] . "
                    WHERE
                        " . $where . ";
                ";
                //echo $query1;
                $query_royalty = mysql_query($query1, $db);
            }
        } while ($myrow = mysql_fetch_array($query_select));
    }
    echo 0;
}
else
{
    echo 1;
}
?>