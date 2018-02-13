<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST
$word_search      = $_REQUEST['word_search'];
$status_0         = $_REQUEST['status_0'];
$status_5         = $_REQUEST['status_5'];
$date_start         = $_REQUEST['date_start'];
$date_end         = $_REQUEST['date_end'];

$offset         = $_REQUEST['offset'];
$limit         = $_REQUEST['limit'];


$method           = strtoupper($_REQUEST['method']);

#set var
$set_data = array();


if($method != 'GET'){
    $set_data = return_object(400);
}
else{

    #SEARCH 
    if($status_0 == 0){
        $sql_status[] = 0;
    }
    if($status_5 == 5){
        $sql_status[] = 5;
    }
    if($status_0 == 0 || $status_5 == 5){
    $sql_status = ' AND a.`status` IN ('.implode(",",$sql_status).')';
    }
    else {
      $sql_status = '1 != 1';  
    }
   
    
$sql = "SELECT  
                a.`book_id`
                ,a.`book_date`
                ,a.`book_due_date_deposit`
                ,c.`ser_id`
                ,c.`per_date_start`
                ,c.`per_date_end`
                ,d.`ser_code`
                ,d.`ser_name`
                ,a.`book_code`
                ,(SELECT  SUM(b.`book_list_qty`) FROM `booking_list` b  WHERE b.`book_code` =  a.`book_code` AND b.`book_list_code` IN ('1','2','3','4','5') ) AS 'QTY'
                ,a.`book_total`
                ,a.`book_discount`
                ,a.`book_amountgrandtotal`
                ,a.`book_receipt`
                ,CONCAT(e.`agen_fname`,' ',e.`agen_lname`) AS 'agen_name'
                ,CONCAT(f.`user_fname`,' ',f.`user_lname`) AS 'user_name'
                ,e.`agen_tel`
                ,e.`agen_line_id`
                ,a.`status`
                FROM `booking` a
                LEFT OUTER JOIN `period` c
                on a.`per_id` = c.`per_id`
                LEFT OUTER JOIN `series` d
                on c.`ser_id` = d.`ser_id`
                LEFT OUTER JOIN `agency` e
                on a.`agen_id` = e.`agen_id`
                LEFT OUTER JOIN `user` f
                on a.`user_id` = f.`user_id`
                WHERE   a.`user_id` = '0' $sql_status
                AND a.`book_date` BETWEEN '$date_start' AND '$date_end'
                  And (  d.`ser_name`  LIKE  '%$word_search%'
                    OR a.`book_code` LIKE  '%$word_search%'
                    OR e.`agen_fname` LIKE  '%$word_search%'
                    OR e.`agen_lname` LIKE  '%$word_search%'
                    OR e.`agen_tel`   LIKE  '%$word_search%'
                    OR e.`agen_line_id`   LIKE  '%$word_search%'
                )
                ORDER BY a.`book_date`DESC ,  a.`book_id` DESC
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
