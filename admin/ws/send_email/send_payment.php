<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST
$book_id      = $_REQUEST['book_id'];
$pay_id      = $_REQUEST['pay_id'];



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
                ,a.`book_date`
                ,a.`book_due_date_deposit`
                ,c.`ser_id`
                ,c.`per_date_start`
                ,c.`per_date_end`
                ,c.`per_com_agency`
                ,c.`per_com_company_agency`
                ,d.`ser_code`
                ,d.`ser_name`
                ,a.`book_code`
                ,(SELECT  SUM(b.`book_list_qty`) FROM `booking_list` b  WHERE b.`book_code` =  a.`book_code` AND b.`book_list_code` IN ('1','2','3','4','5') )  AS 'QTY'
                ,a.`book_total`
                ,a.`book_discount`
                ,a.`book_amountgrandtotal`
                ,a.`book_receipt`
                ,CONCAT(e.`agen_fname`,' ',e.`agen_lname`) AS 'agen_name'
                ,CONCAT(f.`user_fname`,' ',f.`user_lname`) AS 'user_name'
                ,e.`agen_tel`
                ,e.`agen_line_id`
                ,e.`agen_email`
                ,pm.`status`
                ,ac.`agen_com_name`
                ,a.`book_comment`
                ,pm.`book_status`
                ,pm.`remark`
                ,pm.`remark_cancel`
                ,pm.`pay_date`
                ,pm.`pay_time`
                ,pm.`pay_received`
                ,COALESCE(CONCAT(us.`user_fname`,' ',us.`user_lname`),CONCAT(ag.`agen_fname`,' ',ag.`agen_lname`)) AS 'action_name'
                FROM `booking` a
                LEFT OUTER JOIN `period` c
                on a.`per_id` = c.`per_id`
                LEFT OUTER JOIN `series` d
                on c.`ser_id` = d.`ser_id`
                LEFT OUTER JOIN `agency` e
                on a.`agen_id` = e.`agen_id`
                LEFT OUTER JOIN `user` f
                on a.`user_id` = f.`user_id`
                LEFT OUTER JOIN `agency_company` ac
                on e.`agency_company_id` = ac.`agen_com_id`
                LEFT OUTER JOIN `payment` pm
                on a.`book_id` = pm.`book_id`
                And pm.`pay_id` = '$pay_id'
                LEFT OUTER JOIN `user` us
                on pm.`user_action` = us.`user_name`
                LEFT OUTER JOIN `agency` ag
                on pm.`user_action` = ag.`agen_user_name`
                WHERE  a.`book_id` = '$book_id' 
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
