<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST
$word_search      = $_REQUEST['word_search'];
$status_20         = $_REQUEST['status_20'];
$status_25         = $_REQUEST['status_25'];
$status_30         = $_REQUEST['status_30'];
$status_35         = $_REQUEST['status_35'];
$status_40         = $_REQUEST['status_40'];
$status_payment_0         = $_REQUEST['status_payment_0'];
$status_payment_1         = $_REQUEST['status_payment_1'];
$status_payment_9         = $_REQUEST['status_payment_9'];

$country_id         = $_REQUEST['country_id'];
$date_start         = $_REQUEST['date_start'];
$date_end         = $_REQUEST['date_end'];
$menu_12         = $_REQUEST['menu_12'];
$user_id         = $_REQUEST['user_id'];


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
    
   
     if($status_20 == 20){
        $sql_status[] = 20;
    }
     if($status_25 == 25){
        $sql_status[] = 25;
    }
     if($status_30 == 30){
        $sql_status[] = 30;
    }
     if($status_35 == 35){
        $sql_status[] = 35;
    }
     if($status_40 == 40){
        $sql_status[] = 40;
    }
    if($status_20 == 20 || $status_25 == 25 || $status_30 == 30 || $status_35 == 35 || $status_40 == 40){
    $sql_status = ' AND a.`book_status` IN ('.implode(",",$sql_status).')';
    }
    else {
      $sql_status = '1 != 1';  
    }

    if($status_payment_0 == 0){
        $sql_status_payment[] = 0;
    }
    if($status_payment_1 == 1){
        $sql_status_payment[] = 1;
    }
    if($status_payment_9 == 9){
        $sql_status_payment[] = 9;
    }
    if($status_payment_0 == 0 || $status_payment_1 == 1 || $status_payment_9 == 9 ){
        $sql_status_payment = ' AND a.`status` IN ('.implode(",",$sql_status_payment).')';
    }
    else {
          $sql_status_payment = '1 != 1';  
    }

    if($country_id!=''){
        $sql_country = 'ct.`country_id` = '.$country_id;
    }
    else{
        $sql_country = '1=1';
    }
    

    $sql = "SELECT  
    a.`pay_id`
    ,a.`status`
    ,bk.`book_id`
    ,bk.`invoice_code`
    ,bk.`user_id`
    ,s.`ser_code`
    ,pd.`per_date_start`
    ,pd.`per_date_end`
    ,a.`pay_url_file`
    ,b.`bank_name`
    ,b.`bankbook_branch`
    ,b.`bankbook_name`
    ,b.`bankbook_code`
    ,a.`pay_received`
    ,a.`pay_date`
    ,a.`pay_time`
    ,COALESCE(CONCAT(c.`user_fname`,' ',c.`user_lname`),CONCAT(d.`agen_fname`,' ',d.`agen_lname`)) AS 'action_name'
    ,a.`create_date`
    ,a.`book_status`
    FROM `payment` a
    LEFT OUTER JOIN `bankbook` b
    on a.`bankbook_id` = b.`bankbook_id`
    LEFT OUTER JOIN `user` c
    on a.`user_action` = c.`user_name`
    LEFT OUTER JOIN `agency` d
    on a.`user_action` = d.`agen_user_name`
    LEFT OUTER JOIN `booking` bk
    on a.`book_id` = bk.`book_id`
    LEFT OUTER JOIN `period` pd
    on bk.`per_id` = pd.`per_id`
    LEFT OUTER JOIN `series` s
    on pd.`ser_id` = s.`ser_id`
    LEFT OUTER JOIN `country` ct
    on s.`country_id` = ct.`country_id`
    WHERE  a.`pay_date` BETWEEN '$date_start' AND '$date_end'
    AND a.`book_status`NOT IN ('0','5','10')
    AND bk.`invoice_code` <> ''
     $sql_status
     $sql_status_payment
    AND $sql_country
    And (  s.`ser_code`  LIKE  '%$word_search%'
                    OR bk.`invoice_code` LIKE  '%$word_search%'
                    OR b.`bank_name` LIKE  '%$word_search%'
                    OR b.`bankbook_name` LIKE  '%$word_search%'
                    OR b.`bankbook_code` LIKE  '%$word_search%'
                    OR b.`bankbook_branch` LIKE  '%$word_search%'
                )
    ORDER BY  a.`pay_date` DESC
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
