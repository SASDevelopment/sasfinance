<?php
    include_once($_SERVER["DOCUMENT_ROOT"]."/cvbtech/authenticate.php");
    include($_SERVER['DOCUMENT_ROOT'] . '/assets/db/db.connect.php');

    function insert_csv_archive($filename, $query) {
        //echo "<pre>";
        $royalty_type = substr($filename, 0, strpos($filename, "."));
        $filename = date('YmdHis')."_$filename";
        $timestamp = date('Y-m-d H:i:s');

        $result = mysql_query($query);

        $columnHeaders = array();
        $nfields = mysql_num_fields($result);

        $header = "INSERT INTO sasroyalties.royalty_csv_archive (";

        for ($i = 0; $i < $nfields; $i++) {
            $field = mysql_fetch_field($result, $i);
            $columnHeaders[] = $field->name;
            $header .= "`".$field->name."`,";
        }
        $header .= "`authors_net_royalties_id`,";
        $header .= "`royalty_type`,";
        $header .= "`csv_filename`,";
        $header .= "`timestamp`";
        $header .= ") VALUES (";

        $nrows = 0;
        $rows = array();
        while ($myrow = mysql_fetch_row($result)) {
            $query_insert = $header;
            for ($i = 0; $i < $nfields; $i++) {
                if ($i == 0) { $query_insert .= "'',"; } else { $query_insert .= "'".mysql_real_escape_string($myrow[$i])."',"; }
            }
            $nrows++;
            $query_insert .= "'$myrow[0]',";
            $query_insert .= "'$royalty_type',";
            $query_insert .= "'$filename',";
            $query_insert .= "'$timestamp'";
            $query_insert .= ");";

            //echo $query_insert;
            //echo "<br>";
            $status = mysql_query($query_insert);
        }
        return;
    }

    insert_csv_archive(
        "xulon_less_than_0.csv",
        "select id, First_Name, Last_Name, W_9_payee_name, Vendor_ID, Royalty_ISBN, projectid, imprint_brand_id, sum(Royalty_Amount) as TOTALROYALTYAMOUNT from xulonroyalties.authors_net_royalties where Check_Batch_Timestamp is null and imprint_brand_id=1  group by Contact_ID HAVING TOTALROYALTYAMOUNT<0 ORDER BY id ASC;"
    );

    insert_csv_archive(
        "hillcrest_less_than_0.csv",
        "select id, First_Name, Last_Name, W_9_payee_name, Vendor_ID, Royalty_ISBN, projectid, imprint_brand_id, sum(Royalty_Amount) as TOTALROYALTYAMOUNT from xulonroyalties.authors_net_royalties where Check_Batch_Timestamp is null and imprint_brand_id=27 group by Contact_ID  HAVING TOTALROYALTYAMOUNT<0;"
    );

    insert_csv_archive(
        "unknown_less_than_0.csv",
        "select id, First_Name, Last_Name, W_9_payee_name, Vendor_ID, Royalty_ISBN, projectid, imprint_brand_id, sum(Royalty_Amount) as TOTALROYALTYAMOUNT from xulonroyalties.authors_net_royalties where Check_Batch_Timestamp is null and imprint_brand_id is null group by Contact_ID HAVING TOTALROYALTYAMOUNT<0;"
    );

    insert_csv_archive(
        "xulon_greater_than_0.csv",
        "select id, First_Name, Last_Name, W_9_payee_name, Vendor_ID, Royalty_ISBN, projectid, imprint_brand_id, sum(Royalty_Amount) as TOTALROYALTYAMOUNT from xulonroyalties.authors_net_royalties where Check_Batch_Timestamp is null and imprint_brand_id=1 group by Contact_ID HAVING TOTALROYALTYAMOUNT>0;"
    );

    insert_csv_archive(
        "hillcrest_greater_than_0.csv",
        "select id, First_Name, Last_Name, W_9_payee_name, Vendor_ID, Royalty_ISBN, projectid, imprint_brand_id, sum(Royalty_Amount) as TOTALROYALTYAMOUNT from xulonroyalties.authors_net_royalties where Check_Batch_Timestamp is null and imprint_brand_id=27 group by Contact_ID HAVING TOTALROYALTYAMOUNT>0;");

    insert_csv_archive(
        "unknown_greater_than_0.csv",
        "select id, First_Name, Last_Name, W_9_payee_name, Vendor_ID, Royalty_ISBN, projectid, imprint_brand_id, sum(Royalty_Amount) as TOTALROYALTYAMOUNT from xulonroyalties.authors_net_royalties where Check_Batch_Timestamp is null and imprint_brand_id is null group by Contact_ID HAVING TOTALROYALTYAMOUNT>0;"
    );
?>