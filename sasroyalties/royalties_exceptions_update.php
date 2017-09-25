<?php

include($_SERVER['DOCUMENT_ROOT'].'/assets/db/db.connect.php');

$file = $_GET["file"];
$id = $_GET["id"];
$isbn = $_POST["Royalty_ISBN"] ? $_POST["Royalty_ISBN"] : '';
$books_sold = $_POST["Royalty_Books_Sold"] ? $_POST["Royalty_Books_Sold"] : '';
$books_returned = $_POST["Royalty_Books_Returned"] ? $_POST["Royalty_Books_Returned"] : '';
$royalty_amount = $_POST["Royalty_Amount"] ? $_POST["Royalty_Amount"] : '';
$class = $_POST["royalty_classfication"] ? $_POST["royalty_classfication"] : '';

if(!$file && !$id) { echo "Missing parameters."; die(); }

$fields = '';

// eBook Amazon
if ($file == 'temp_ebook_amazon') {
    unset($fields);
    if($isbn <> '') { $fields .= "sasroyalties_dev." . $file . ".`ASIN` = '" . $isbn . "',"; }
    if($books_sold <> '') { $fields .= "sasroyalties_dev." . $file . ".`Units Sold` = '" . $books_sold . "',"; }
    if($books_returned <> '') { $fields .= "sasroyalties_dev." . $file . ".`Units Refunded` = '" . $books_returned . "',"; }
    if($royalty_amount <> '') { $fields .= "sasroyalties_dev." . $file . ".`Royalty` = '" . $royalty_amount . "',"; }
    if($class <> '') { $fields .= "sasroyalties_dev." . $file . ".`royalty_classifaction` = '" . $class . "',"; }
    $fields = rtrim($fields,",");
}

// Barnes and Noble
if ($file == 'temp_ebook_bn') {
    unset($fields);
    if($isbn <> '') { $fields .= "sasroyalties_dev." . $file . ".`Publisher's ISBN` = '" . $isbn . "',"; }
    if($books_sold <> '') { $fields .= "sasroyalties_dev." . $file . ".`Units Sold` = '" . $books_sold . "',"; }
    if($books_returned <> '') { $fields .= "sasroyalties_dev." . $file . ".`Units Returned` = '" . $books_returned . "',"; }
    if($royalty_amount <> '') { $fields .= "sasroyalties_dev." . $file . ".`Total Royalty` = '" . $royalty_amount . "',"; }
    if($class <> '') { $fields .= "sasroyalties_dev." . $file . ".`royalty_classifaction` = '" . $class . "',"; }
    $fields = rtrim($fields,",");
}

// eBook Corp
if ($file == 'temp_ebook_corp') {
    unset($fields);
    if($isbn <> '') { $fields .= "sasroyalties_dev." . $file . ".`EbookISBN13` = '" . $isbn . "',"; }
    if($books_sold <> '') { $fields .= "sasroyalties_dev." . $file . ".`Quantity` = '" . $books_sold . "',"; }
    #if($books_returned <> '') { $fields .= "sasroyalties_dev." . $file . ".`Units Returned` = '" . $books_returned . "',"; }
    if($royalty_amount <> '') { $fields .= "sasroyalties_dev." . $file . ".`PubDue` = '" . $royalty_amount . "',"; }
    if($class <> '') { $fields .= "sasroyalties_dev." . $file . ".`royalty_classifaction` = '" . $class . "',"; }
    $fields = rtrim($fields,",");
}

// eBook createspace
if ($file == 'temp_ebook_createspace') {
    unset($fields);
    if($isbn <> '') { $fields .= "sasroyalties_dev." . $file . ".`ISBN` = '" . $isbn . "',"; }
    if($books_sold <> '') { $fields .= "sasroyalties_dev." . $file . ".`Quantity` = '" . $books_sold . "',"; }
    #if($books_returned <> '') { $fields .= "sasroyalties_dev." . $file . ".`Units Returned` = '" . $books_returned . "',"; }
    if($royalty_amount <> '') { $fields .= "sasroyalties_dev." . $file . ".`Royalty` = '" . $royalty_amount . "',"; }
    if($class <> '') { $fields .= "sasroyalties_dev." . $file . ".`royalty_classifaction` = '" . $class . "',"; }
    $fields = rtrim($fields,",");
}

// eBook ebsco
if ($file == 'temp_ebook_ebsco') {
    unset($fields);
    if($isbn <> '') { $fields .= "sasroyalties_dev." . $file . ".`ISBN` = '" . $isbn . "',"; }
    if($books_sold <> '') { $fields .= "sasroyalties_dev." . $file . ".`QTY` = '" . $books_sold . "',"; }
    #if($books_returned <> '') { $fields .= "sasroyalties_dev." . $file . ".`Units Returned` = '" . $books_returned . "',"; }
    if($royalty_amount <> '') { $fields .= "sasroyalties_dev." . $file . ".`PUBDUE` = '" . $royalty_amount . "',"; }
    if($class <> '') { $fields .= "sasroyalties_dev." . $file . ".`royalty_classifaction` = '" . $class . "',"; }
    $fields = rtrim($fields,",");
}

// eBook gardners
if ($file == 'temp_ebook_gardners') {
    unset($fields);
    if($isbn <> '') { $fields .= "sasroyalties_dev." . $file . ".`ISBN13` = '" . $isbn . "',"; }
    if($books_sold <> '') { $fields .= "sasroyalties_dev." . $file . ".`UNITS` = '" . $books_sold . "',"; }
    #if($books_returned <> '') { $fields .= "sasroyalties_dev." . $file . ".`Units Returned` = '" . $books_returned . "',"; }
    if($royalty_amount <> '') { $fields .= "sasroyalties_dev." . $file . ".`TOTAL-NET-LINE-VALUE` = '" . $royalty_amount . "',"; }
    if($class <> '') { $fields .= "sasroyalties_dev." . $file . ".`royalty_classifaction` = '" . $class . "',"; }
    $fields = rtrim($fields,",");
}

// eBook itunes
if ($file == 'temp_ebook_itunes') {
    unset($fields);
    if($isbn <> '') { $fields .= "sasroyalties_dev." . $file . ".`ISRC/ISBN` = '" . $isbn . "',"; }
    if($books_sold > 0) {
        $fields .= "sasroyalties_dev." . $file . ".`Sales or Return` = 'S',";
        $fields .= "sasroyalties_dev." . $file . ".`Quantity` = '" . $books_sold . "',";
    }
    if($books_sold < 0) {
        $fields .= "sasroyalties_dev." . $file . ".`Sales or Return` = 'S',";
        $fields .= "sasroyalties_dev." . $file . ".`Quantity` = '" . $books_returned . "',";
    }
    if($books_sold == 0) {
        $fields .= "sasroyalties_dev." . $file . ".`Sales or Return` = NULL,";
        $fields .= "sasroyalties_dev." . $file . ".`Quantity` = '0',";
    }
    if($royalty_amount <> '') { $fields .= "sasroyalties_dev." . $file . ".`Extended Partner Share` = '" . $royalty_amount . "',"; }
    if($class <> '') { $fields .= "sasroyalties_dev." . $file . ".`royalty_classifaction` = '" . $class . "',"; }
    $fields = rtrim($fields,",");
}

// eBook kobo
if ($file == 'temp_ebook_kobo') {
    unset($fields);
    if($isbn <> '') { $fields .= "sasroyalties_dev." . $file . ".`eISBN` = '" . $isbn . "',"; }
    if($books_sold <> '') { $fields .= "sasroyalties_dev." . $file . ".`Total Qty` = '" . $books_sold . "',"; }
    #if($books_returned <> '') { $fields .= "sasroyalties_dev." . $file . ".`Units Returned` = '" . $books_returned . "',"; }
    if($royalty_amount <> '') { $fields .= "sasroyalties_dev." . $file . ".`Net Due (Payable Currency)` = '" . $royalty_amount . "',"; }
    if($class <> '') { $fields .= "sasroyalties_dev." . $file . ".`royalty_classifaction` = '" . $class . "',"; }
    $fields = rtrim($fields,",");
}

// eBook overdrive
if ($file == 'temp_ebook_overdrive') {
    unset($fields);
    if($isbn <> '') { $fields .= "sasroyalties_dev." . $file . ".`isbn_13` = '" . $isbn . "',"; }
    if($books_sold <> '') { $fields .= "sasroyalties_dev." . $file . ".`UNITS` = '" . $books_sold . "',"; }
    if($books_returned <> '') { $fields .= "sasroyalties_dev." . $file . ".`Units Returned` = '" . $books_returned . "',"; }
    if($royalty_amount <> '') { $fields .= "sasroyalties_dev." . $file . ".`Amt owed USD` = '" . $royalty_amount . "',"; }
    if($class <> '') { $fields .= "sasroyalties_dev." . $file . ".`royalty_classifaction` = '" . $class . "',"; }
    $fields = rtrim($fields,",");
}

// eBook Proquest
if ($file == 'temp_ebook_proquest') {
    unset($fields);
    if($isbn <> '') { $fields .= "sasroyalties_dev." . $file . ".`isbn_13` = '" . $isbn . "',"; }
    if($books_sold <> '') { $fields .= "sasroyalties_dev." . $file . ".`Quantity` = '" . $books_sold . "',"; }
    if($royalty_amount <> '') { $fields .= "sasroyalties_dev." . $file . ".`Publisher Revenue` = '" . $royalty_amount . "',"; }
    if($class <> '') { $fields .= "sasroyalties_dev." . $file . ".`royalty_classifaction` = '" . $class . "',"; }
    $fields = rtrim($fields,",");
}

// eBook scribd
if ($file == 'temp_ebook_scribd') {
    unset($fields);
    if($isbn <> '') { $fields .= "sasroyalties_dev." . $file . ".`ISBN` = '" . $isbn . "',"; }
    if($royalty_amount <> '') { $fields .= "sasroyalties_dev." . $file . ".`Amount owed for this interaction` = '" . $royalty_amount . "',"; }
    if($class <> '') { $fields .= "sasroyalties_dev." . $file . ".`royalty_classifaction` = '" . $class . "',"; }
    $fields = rtrim($fields,",");
}

// Itasca
if ($file == 'temp_itasca') {
    unset($fields);
    if($isbn <> '') { $fields .= "sasroyalties_dev." . $file . ".`isbn_13` = '" . $isbn . "',"; }
    if($books_sold <> '') { $fields .= "sasroyalties_dev." . $file . ".`Qty` = '" . $books_sold . "',"; }
    #if($books_returned <> '') { $fields .= "sasroyalties_dev." . $file . ".`Qty` = '" . $books_returned . "',"; }
    if($royalty_amount <> '') { $fields .= "sasroyalties_dev." . $file . ".`Total1` = '" . $royalty_amount . "',"; }
    if($class <> '') { $fields .= "sasroyalties_dev." . $file . ".`royalty_classifaction` = '" . $class . "',"; }
    $fields = rtrim($fields,",");
}

// lsi
if ($file == 'temp_lsi') {
    unset($fields);
    if($isbn <> '') { $fields .= "sasroyalties_dev." . $file . ".`isbn_13` = '" . $isbn . "',"; }
    if($books_sold <> '') { $fields .= "sasroyalties_dev." . $file . ".`PTD_Quantity` = '" . $books_sold . "',"; }
    if($books_returned <> '') { $fields .= "sasroyalties_dev." . $file . ".`PTD_return_quantity` = '" . $books_returned . "',"; }
    if($royalty_amount <> '') { $fields .= "sasroyalties_dev." . $file . ".`PTD_pub_comp` = '" . $royalty_amount . "',"; }
    if($class <> '') { $fields .= "sasroyalties_dev." . $file . ".`royalty_classifaction` = '" . $class . "',"; }
    $fields = rtrim($fields,",");
}

// lsi aud
if ($file == 'temp_lsi_aud') {
    unset($fields);
    if($isbn <> '') { $fields .= "sasroyalties_dev." . $file . ".`isbn` = '" . $isbn . "',"; }
    if($books_sold <> '') { $fields .= "sasroyalties_dev." . $file . ".`MTD_Quantity` = '" . $books_sold . "',"; }
    if($books_returned <> '') { $fields .= "sasroyalties_dev." . $file . ".`MTD_return_quantity` = '" . $books_returned . "',"; }
    if($royalty_amount <> '') { $fields .= "sasroyalties_dev." . $file . ".`MTD_pub_comp` = '" . $royalty_amount . "',"; }
    if($class <> '') { $fields .= "sasroyalties_dev." . $file . ".`royalty_classifaction` = '" . $class . "',"; }
    $fields = rtrim($fields,",");
}

// lsi uk
if ($file == 'temp_lsi_uk') {
    unset($fields);
    if($isbn <> '') { $fields .= "sasroyalties_dev." . $file . ".`isbn` = '" . $isbn . "',"; }
    if($books_sold <> '') { $fields .= "sasroyalties_dev." . $file . ".`PTD_Quantity` = '" . $books_sold . "',"; }
    if($books_returned <> '') { $fields .= "sasroyalties_dev." . $file . ".`MTD_return_quantity` = '" . $books_returned . "',"; }
    if($royalty_amount <> '') { $fields .= "sasroyalties_dev." . $file . ".`PTD_pub_comp` = '" . $royalty_amount . "',"; }
    if($class <> '') { $fields .= "sasroyalties_dev." . $file . ".`royalty_classifaction` = '" . $class . "',"; }
    $fields = rtrim($fields,",");
}

$timestamp = date("Y-m-d H:i:s");
$record_timestamp = ",sasroyalties_dev." . $file . ".record_timestamp = '".$timestamp."'";

$update_query = "
    UPDATE sasroyalties_dev." . $file . "
    SET " . $fields . $record_timestamp . "
    WHERE
        id = $id
";


//echo "<pre>";
mail('pclark@christianpublishing.com', 'Royalties Exceptions Update', $record_timestamp, "From: support@xulonauthors.com\r\nReply-to: pclark@christianpublishing.com\r\nContent-type: text/html; charset=iso-8859-1\r\n\n");

// Update sasroyalties_dev temp tables with revised data
$query_update_result = mysql_query($update_query);

mysql_close();

header('location: royalties_exceptions.php');
?>