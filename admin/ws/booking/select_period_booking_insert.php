<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST
$per_id      = $_REQUEST['per_id'];
$bus_no      = $_REQUEST['bus_no'];


$method           = strtoupper($_REQUEST['method']);

#set var
$set_data = array();


if($method != 'GET'){
    $set_data = return_object(400);
}
else{

    #SEARCH 

   
    

    $sql = "SELECT  
                a.`ser_id`
                ,b.`ser_name`
                ,b.`ser_code`
                ,b.`country_id`
                ,a.`per_date_start`
                ,a.`per_date_end`
                ,a.`per_price_1`
                ,a.`per_price_2`
                ,a.`per_price_3`
                ,a.`per_price_4`
                ,a.`per_price_5`
                ,a.`single_charge`
                ,a.`per_com_agency`
                ,a.`per_com_company_agency`
                ,c.`air_name`
                ,b.`ser_deposit`
                ,d.`bus_qty` - (SELECT  COALESCE(SUM(e.`book_list_qty`),0) FROM `booking_list` e 
                LEFT OUTER JOIN `booking` f on e.`book_code` = f.`book_code` WHERE f.`per_id` =  a.`per_id` 
                AND f.`bus_no` = d.`bus_no`
               AND e.`book_list_code` IN ('1','2','3','4','5') )  
                AS 'qty_receipt'
                FROM `period` a
                LEFT OUTER JOIN `series` b
                on a.`ser_id` = b.`ser_id`
                LEFT OUTER JOIN `airline` c
                on b.`air_id` = c.`air_id`
                LEFT OUTER JOIN `bus_list` d
                on a.`per_id` = d.`per_id`
                WHERE  a.`per_id` = '$per_id'
                AND    d.`bus_no` = '$bus_no'
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
        $set_data = return_object(204);
    }

}

/*echo '<pre>';
print_r($set_data);
return;*/
echo json_encode($set_data);

disconect_db();
