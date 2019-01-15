<?php include_once($_SERVER["DOCUMENT_ROOT"]."/cvbtech/authenticate.php"); ?>
<!DOCTYPE html>
<html>
<head>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<script>
$(document).ready(function(){

    function createcsv(filename, query, scientific_notation) {
        var timestamp="<?=date('YmdHis');?>";
        var royalty_type = filename;
        var filename = filename + "_" + timestamp + '.csv';
        var filename_path = '/var/www/xulonauthors.com/httpsdocs/xuloncontrolpanel/einstein/Protected_Royalty/accounting/' + filename;
        //var query = "'select id, First_Name, Last_Name, W_9_payee_name, Vendor_ID, Royalty_ISBN, projectid, imprint_brand_id, sum(Royalty_Amount) as TOTALROYALTYAMOUNT from xulonroyalties.authors_net_royalties where Check_Batch_Timestamp is null and imprint_brand_id=1  group by Contact_ID HAVING TOTALROYALTYAMOUNT<0;'";
        $.ajax({
            url: "royalties_csv_exports_builder.php",
            type: "GET",
            dataType: "text",
            data: {'filename': filename, 'filename_path': filename_path, 'royalty_type': royalty_type, 'query': query, 'sci': scientific_notation},

            success: function (csv) {
                var pom = document.createElement('a');
                var csvContent = csv; //here we load our csv data
                var blob = new Blob([csvContent], {type: 'text/csv;charset=utf-8;'});
                var url = URL.createObjectURL(blob);
                pom.href = url;
                pom.setAttribute('download', filename);
                pom.click();
            }
        });
    }

    /*
    createcsv(
        "file name",
        "query",
        Data field number that shows up as scientific_notation in Excel.  Field number starts at id=0, etc.  ISBN is 5 as in "select id, First_Name, Last_Name, W_9_payee_name, Vendor_ID, Royalty_ISBN, ..."
    );
    */

    createcsv(
        "xulon_less_than_0",
        "select id, First_Name, Last_Name, W_9_payee_name, Vendor_ID, Royalty_ISBN, projectid, imprint_brand_id, sum(Royalty_Amount) as TOTALROYALTYAMOUNT from xulonroyalties.authors_net_royalties where Check_Batch_Timestamp is null and imprint_brand_id=1  group by Contact_ID HAVING TOTALROYALTYAMOUNT<0;",
        5
    );

    createcsv(
        "hillcrest_less_than_0",
        "select id, First_Name, Last_Name, W_9_payee_name, Vendor_ID, Royalty_ISBN, projectid, imprint_brand_id, sum(Royalty_Amount) as TOTALROYALTYAMOUNT from xulonroyalties.authors_net_royalties where Check_Batch_Timestamp is null and imprint_brand_id=27 group by Contact_ID  HAVING TOTALROYALTYAMOUNT<0;",
        5
    );

    createcsv(
        "unknown_less_than_0",
        "select id, First_Name, Last_Name, W_9_payee_name, Vendor_ID, Royalty_ISBN, projectid, imprint_brand_id, sum(Royalty_Amount) as TOTALROYALTYAMOUNT from xulonroyalties.authors_net_royalties where Check_Batch_Timestamp is null and imprint_brand_id is null group by Contact_ID HAVING TOTALROYALTYAMOUNT<0;",
        5
    );

    createcsv(
        "xulon_greater_than_0",
        "select id, First_Name, Last_Name, W_9_payee_name, Vendor_ID, Royalty_ISBN, projectid, imprint_brand_id, sum(Royalty_Amount) as TOTALROYALTYAMOUNT from xulonroyalties.authors_net_royalties where Check_Batch_Timestamp is null and imprint_brand_id=1 group by Contact_ID HAVING TOTALROYALTYAMOUNT>0;",
        5
    );

    createcsv(
        "hillcrest_greater_than_0",
        "select id, First_Name, Last_Name, W_9_payee_name, Vendor_ID, Royalty_ISBN, projectid, imprint_brand_id, sum(Royalty_Amount) as TOTALROYALTYAMOUNT from xulonroyalties.authors_net_royalties where Check_Batch_Timestamp is null and imprint_brand_id=27 group by Contact_ID HAVING TOTALROYALTYAMOUNT>0;",
        5
    );

    createcsv(
        "unknown_greater_than_0",
        "select id, First_Name, Last_Name, W_9_payee_name, Vendor_ID, Royalty_ISBN, projectid, imprint_brand_id, sum(Royalty_Amount) as TOTALROYALTYAMOUNT from xulonroyalties.authors_net_royalties where Check_Batch_Timestamp is null and imprint_brand_id is null group by Contact_ID HAVING TOTALROYALTYAMOUNT>0;",
        5
    );
});
</script>
</head>
<body>
</body>
</html>