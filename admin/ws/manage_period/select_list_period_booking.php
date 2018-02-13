<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST
$per_id      = $_REQUEST['per_id'];



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
  
    

    $sql = "SELECT  
                a.`book_id`
                ,a.`book_date`
                ,c.`ser_code`
                ,a.`book_code`
                ,(SELECT  SUM(b.`book_list_qty`) FROM `booking_list` b  WHERE b.`book_id` =  a.`book_id` AND b.`book_list_code` IN ('1','2','3','4','5') ) + '/' + c.`per_qty_seats` AS 'QTY'
                ,e.`agen_fname` + ' ' + e.`agen_lname` AS 'agen_name'
                ,f.`user_fname` + ' ' + f.`user_lname` AS 'user_name'
                ,a.`book_total`
                ,a.`book_receipt`
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
                WHERE  c.`per_id` = '$per_id' 
              
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
