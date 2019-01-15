<?php
    include_once($_SERVER["DOCUMENT_ROOT"]."/cvbtech/authenticate.php");
    include($_SERVER['DOCUMENT_ROOT'] . '/assets/db/db.connect.php');

    $filename = $_GET['filename'];
    $filename_path = $_GET['filename_path'];
    $royalty_type = $_GET['royalty_type'];
    $query = $_GET['query'];
    $scientific_notation = $_GET['sci'];

    insert_csv_archive($filename, $royalty_type, $query);

    $result = mysql_query($query);

    $columnHeaders = array();
    $nfields = mysql_num_fields($result);
    for ($i = 0; $i < $nfields; $i++) {
        $field = mysql_fetch_field($result, $i);
        $columnHeaders[] = $field->name;
    }

    $fp = fopen('php://output', 'w');
    //$fp = fopen($filename,"w");

    $status = fputcsv($fp, $columnHeaders);

    $nrows = 0;
    $rows = array();
    while ($myrow = mysql_fetch_row($result)) {
        for ($i = 0; $i < $nfields; $i++) {
            if ($i == $scientific_notation) {
                $rows[$nrows][$i] = '="' . $myrow[$i] . '"';
            } else {
                $rows[$nrows][$i] = $myrow[$i];
            }
        }
        fputcsv($fp, $rows[$nrows]);
        $nrows++;
    }
    fclose($fp);
    return;

    function insert_csv_archive($filename, $royalty_type, $query) {
        //echo "<pre>";
        //$royalty_type = substr($filename, 0, strpos($filename, "."));
        //$filename = "$filename".date('YmdHis');
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

?>