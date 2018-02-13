<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST

$book_id      = $_REQUEST['book_id'];
$method           = strtoupper($_REQUEST['method']);

#set var
$set_data = array();


if($method != 'GET'){
    $set_data = return_object(400);
}
else{

    #SEARCH 

   
    

    $sql = "   SELECT 
                a.`book_id`
                ,a.`book_code`
                ,a.`invoice_code`
                ,a.`agen_id`
                ,CONCAT(b.`agen_fname`,' ',b.`agen_lname`) AS 'agen_name'
                ,b.`agen_tel`
                ,b.`agen_email`
                ,c.`agen_com_name`
                ,a.`book_date`
                ,a.`status`
                ,a.`inv_rev_no`
                ,d.`bus_qty` - (SELECT  COALESCE(SUM(e.`book_list_qty`),0) FROM `booking_list` e 
                LEFT OUTER JOIN `booking` f on e.`book_code` = f.`book_code`
                WHERE f.`per_id` =  a.`per_id` 
                AND f.`bus_no` = d.`bus_no` AND f.`status` != '40'
                AND e.`book_list_code`  IN ('1','2','3','4','5') )  
                AS 'qty_receipt' 
                FROM `booking` a
                LEFT OUTER JOIN `agency` b
                ON a.`agen_id` = b.`agen_id`
                LEFT OUTER JOIN `agency_company` c
                ON b.`agency_company_id` = c.`agen_com_id`
                LEFT OUTER JOIN `bus_list` d
                on a.`per_id` = d.`per_id`
                AND a.`bus_no` = d.`bus_no`
                WHERE a.`book_id` = $book_id  ";
               

    $sql_all_row = $sql;
    
    $result = mysqli_query($CON,$sql);

    $set_data['sql'] = $sql;

    if(mysqli_num_rows($result)>0){
        
        
        while ($data = mysqli_fetch_assoc($result)) {
            $set_data['result'][] = $data;
        }

        
        $set_data = return_object(200,$set_data['result']);
        
        
        #ALL ROW
        
        $set_data['all_row'] = mysqli_num_rows(mysqli_query($CON,$sql_all_row));
    }
    else{
        $set_data = return_object(204);
    }

}

/*echo '<pre>';
print_r($set_data);
return;*/
echo json_encode($set_data);

disconect_db();
