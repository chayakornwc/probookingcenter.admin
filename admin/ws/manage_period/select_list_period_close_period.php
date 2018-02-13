<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST
$word_search     = $_REQUEST['word_search'];
$country_id      = $_REQUEST['country_id'];

$date_start       = $_REQUEST['date_start'];
$date_end         = $_REQUEST['date_end'];


$offset         = $_REQUEST['offset'];
$limit          = $_REQUEST['limit'];


$method           = strtoupper($_REQUEST['method']);

#set var
$set_data = array();


if($method != 'GET'){
    $set_data = return_object(400);
}
else{

    #SEARCH 
      if($country_id!=''){
        $sql_country = 'd.`country_id` = '.$country_id;
    }
    else{
        $sql_country = '1=1';
    }

    if($date_start!=''){
        $sql_date_start = "(a.`per_date_start` >= '$date_start')";
    }
    else{
        $sql_date_start = '1=1';
    }
    
    if($date_end!=''){
        $sql_date_end = "(a.`per_date_end` <= '$date_end')";
    }
    else{
        $sql_date_end = "1=1";
    }
    

    $sql = "SELECT  
                a.`per_id`
                ,c.`ser_id`
                ,c.`ser_name`
                ,c.`ser_code`
                ,d.`country_name`
                ,a.`per_date_start`
                ,a.`per_date_end`
                ,a.`per_price_1`
                ,a.`per_price_2`
                ,a.`per_price_3`
                ,a.`per_price_4`
                ,a.`per_price_5`
                ,a.`single_charge`
                ,(SELECT  COALESCE(SUM(b.`book_list_qty`),0) FROM `booking_list` b LEFT OUTER JOIN `booking` e on b.`book_code` = e.`book_code` WHERE e.`per_id` =  a.`per_id` 
                AND e.`bus_no` = bl.`bus_no` 
                AND b.`book_list_code` IN ('1','2','3','4','5')
                AND e.`status` != 40 )  AS 'qty_book'
                ,bl.`bus_qty` - (SELECT  COALESCE(SUM(b.`book_list_qty`),0) FROM `booking_list` b LEFT OUTER JOIN `booking` e on b.`book_code` = e.`book_code` 
                WHERE e.`per_id` =  a.`per_id` AND e.`bus_no` = bl.`bus_no` 
                AND b.`book_list_code` IN ('1','2','3','4','5')
                AND e.`status` != 40)  AS 'qty_receipt'
                ,a.`status`
                ,bl.`bus_no`
                ,bl.`bus_qty` AS 'per_qty_seats'
                ,e.`air_name`
                FROM `period` a
                LEFT OUTER JOIN `series` c
                on a.`ser_id` = c.`ser_id`
                LEFT OUTER JOIN `bus_list` bl
                on a.`per_id` = bl.`per_id`
                LEFT OUTER JOIN `country` d
                on c.`country_id` = d.`country_id`
                 LEFT OUTER JOIN `airline` e
                on c.`air_id` = e.`air_id`
                WHERE  a.`status` = '3' 
                AND $sql_country
                AND $sql_date_start AND $sql_date_end
                AND (  c.`ser_name`  LIKE  '%$word_search%'
                    OR c.`ser_code` LIKE  '%$word_search%'
                    OR d.`country_name` LIKE  '%$word_search%'
                )
                ORDER BY c.`ser_id`,a.`per_date_start` , bl.`bus_no`
                ";

    $sql_all_row = $sql;
    $sql         = $sql.set_limit($offset , $limit);
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
        $set_data = return_object(204,$sql);
    }

}

/*echo '<pre>';
print_r($set_data);
return;*/
echo json_encode($set_data);

disconect_db();
