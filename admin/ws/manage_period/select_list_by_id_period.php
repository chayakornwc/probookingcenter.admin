<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST
$per_id             = $_REQUEST['per_id'];
$bus_no             = $_REQUEST['bus_no'];

$method           = strtoupper($_REQUEST['method']);

#set var
$set_data = array();


if($method != 'GET'){
    $set_data = return_object(400);
}
else{

    #SEARCH 


   
    

    $sql = "SELECT  
                a.`per_id`
                ,a.`per_qty_seats`
                ,c.`ser_code`
                ,c.`ser_name`
                ,d.`country_id`
                ,d.`country_name`
                ,a.`per_date_start`
                ,a.`per_date_end`
                ,(SELECT  COALESCE(SUM(b.`book_list_qty`),0) FROM `booking_list` b LEFT OUTER JOIN `booking` bo on b.`book_code` = bo.`book_code` 
                WHERE bo.`per_id` =  a.`per_id` 
                AND b.`book_list_code` IN ('1','2','3','4','5') AND bo.`status` != 40
                AND bo.`bus_no` = f.`bus_no` )  AS 'qty_book'
                ,f.`bus_qty`
                ,a.`status`
                ,e.`air_name`
                ,a.`per_price_1`
                ,a.per_com_company_agency
                ,a.per_com_agency
                ,a.per_hotel
                ,a.per_hotel_tel
                ,a.arrival_date
                ,(SELECT sum(bk.book_amountgrandtotal) from booking bk where bk.per_id = a.per_id and bk.status != 40) AS 'sum_total'
                ,(SELECT sum(bk.book_receipt) from booking bk where bk.per_id = a.per_id and bk.status != 40) AS 'sum_receipt'
                FROM `period` a
                LEFT OUTER JOIN `series` c
                on a.`ser_id` = c.`ser_id`
                LEFT OUTER JOIN `country` d
                on c.`country_id` = d.`country_id`
                LEFT OUTER JOIN `airline` e
                on c.`air_id` = e.`air_id`
                LEFT OUTER JOIN `bus_list` f
                on a.`per_id` = f.`per_id`
                WHERE  a.`per_id` = '$per_id'
                AND f.`bus_no` = '$bus_no'                
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
