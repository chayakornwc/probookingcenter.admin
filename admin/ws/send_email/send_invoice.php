<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST
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
                a.`book_code`
                ,a.`book_id`
                ,a.`invoice_code`
                ,c.`ser_name`
                ,b.`per_date_start`
                ,b.`per_date_end`
                ,d.`air_name`
                ,(SELECT  SUM(c.`book_list_qty`) FROM `booking_list` c  WHERE c.`book_code` =  a.`book_code` AND c.`book_list_code` IN ('1','2','3','4','5') )  AS 'QTY'
                ,a.`status`
                ,e.`agen_email`
                FROM `booking` a
                LEFT OUTER JOIN `period` b
                on a.`per_id` = b.`per_id`
                LEFT OUTER JOIN `series` c
                on b.`ser_id` = c.`ser_id`
                LEFT OUTER JOIN `airline` d
                on c.`air_id` = d.`air_id`
                LEFT OUTER JOIN `agency` e
                on a.`agen_id` = e.`agen_id`
                WHERE  a.`book_code` = '$book_code'
                
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
