<?php

include($_SERVER['DOCUMENT_ROOT'].'/assets/db/db.connect.php');

if ($_GET['reprocess'] == 1) {
    // CALL sasroyalties_dev.step08_update_royalty_processing_data
    //$query_function="CALL sasroyalties_dev.step08_update_royalty_processing_data;";
    $query_function="CALL sasroyalties_dev.step00_execute_all_steps;";
    $query_function_result = mysql_query($query_function);

    /*
    //$query = "CALL sasroyalties_dev.step08_update_royalty_processing_data();";
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
        GROUP BY
        Royalty_ISBN
        ORDER BY
        sasroyalties.royalty_processing.ORG_FILE_SOURCE ASC
    ";

    echo "<pre>";
    $query_select = mysql_query($query, $db);
    if ($myrow = mysql_fetch_array($query_select)) {
        do {
            print_r($myrow);
        } while ($myrow = mysql_fetch_array($query_select));
    }
    */
    echo 0;
}
else
{
    echo 1;
}
?>