<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST

$book_id      = $_REQUEST['book_id'];
$book_code      = $_REQUEST['book_code'];


$method           = strtoupper($_REQUEST['method']);

#set var
$set_data = array();


if($method != 'GET'){
    $set_data = return_object(400);
}
else{

    #SEARCH 

   
    

    $sql = "SELECT  
                a.`book_id`
                ,a.`book_code`
                ,a.`invoice_code`
                ,a.`invoice_date`
                ,COALESCE(a.`inv_rev_no`,0) AS 'inv_rev_no'
                ,COALESCE(a.`receipt_code`,'') AS 'receipt_code'
                ,a.`receipt_date` 
                ,ag.`agency_company_id`
                ,ag.`agen_email`
                ,ac.`agen_com_name`
                ,ac.`agen_com_address1`
                ,ac.`agen_com_address2`
                ,a.`agen_id`
                ,CONCAT(ag.`agen_fname`,' ',ag.`agen_lname`) AS 'agenname'
                ,CONCAT(us.`user_fname`,' ',us.`user_lname`) AS 'username'
                ,ag.agen_tel
                ,a.`user_id`
                ,a.`bus_no`
                ,a.`book_total`
                ,a.`book_discount`
                ,a.`book_amountgrandtotal`
                ,a.`book_comment`
                ,a.`book_master_deposit`
                ,a.`book_due_date_deposit`
                ,a.`book_master_full_payment`
                ,a.`book_due_date_full_payment`
                ,a.`book_com_agency_company`
                ,a.`book_com_agency`
                ,a.`book_date`
                ,a.`book_receipt`
                ,a.`book_room_twin`
                ,a.`book_room_double`
                ,a.`book_room_triple`
                ,a.`book_room_single`
                ,a.`remark`
                ,p.`per_id`
                ,p.`ser_id`
                ,b.`ser_name`
                ,b.`ser_code`
                ,b.`ser_route`
                ,b.`country_id`
                ,p.`per_date_start`
                ,p.`per_date_end`
                ,p.`per_price_1`
                ,p.`per_price_2`
                ,p.`per_price_3`
                ,p.`per_price_4`
                ,p.`per_price_5`
                ,p.`single_charge`
                ,p.`per_com_agency`
                ,p.`per_com_company_agency`
                ,c.`air_name`
                ,b.`ser_deposit`
                ,a.`status`
                ,a.`remark_cancel`
                ,COALESCE(a.`status_cancel`,3) AS 'status_cancel'
                ,COALESCE(a.`book_cancel`,0) AS 'book_cancel'
                ,d.`bus_qty` - (SELECT  COALESCE(SUM(e.`book_list_qty`),0) FROM `booking_list` e 
                LEFT OUTER JOIN `booking` f on e.`book_code` = f.`book_code` WHERE f.`per_id` =  p.`per_id` AND f.`bus_no` = d.`bus_no` 
                AND e.`book_list_code` IN ('1','2','3','4','5') )  
                AS 'qty_receipt'
                ,((a.`book_amountgrandtotal` + a.`book_com_agency_company` + a.`book_com_agency` ) * 3) / 100 AS 'AmountTAX'
                FROM `booking` a
                LEFT OUTER JOIN `period` p
                on a.`per_id` = p.`per_id`
                LEFT OUTER JOIN `series` b
                on p.`ser_id` = b.`ser_id`
                LEFT OUTER JOIN `airline` c
                on b.`air_id` = c.`air_id`
                LEFT OUTER JOIN `bus_list` d
                on p.`per_id` = d.`per_id`
                AND a.`bus_no` = d.`bus_no`
                LEFT OUTER JOIN `agency` ag
                on a.`agen_id` = ag.`agen_id`
                 LEFT OUTER JOIN `user` us
                on a.`user_id` = us.`user_id`
                LEFT OUTER JOIN `agency_company` ac
                on ag.`agency_company_id` = ac.`agen_com_id`
                WHERE  a.`book_id` = $book_id
                ";

    $sql_all_row = $sql;
    $sql         = $sql.set_limit($offset , $limit);
    $result = mysqli_query($CON,$sql);

    $set_data['sql'] = $sql;

    if(mysqli_num_rows($result)>0){
        
    while ($data = mysqli_fetch_assoc($result)) {
                        
                        $sql3 = "SELECT 
                                        a.`book_list_id`,
                                        a.`book_list_code`,
                                        a.`book_list_name`,
                                        a.`book_list_price`,
                                        a.`book_list_qty`,
                                        a.`book_list_total`,
                                        a.`book_code`
                                    FROM `booking_list` a
                                    WHERE a.`book_code` = '$book_code'
                                    ORDER BY  a.`book_list_id`
                                 ";
                        $result3 = mysqli_query($CON,$sql3);

                        while ($data2 = mysqli_fetch_assoc($result3)) {
                            $data['booking_list'][] = $data2;
                        }
                            
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
$set_data['db'] = $db;

/*echo '<pre>';
print_r($set_data);
return;*/
echo json_encode($set_data);

disconect_db();
