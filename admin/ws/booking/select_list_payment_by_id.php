<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST
$pay_id           = $_REQUEST['pay_id'];


$method           = strtoupper($_REQUEST['method']);

#set var
$set_data = array();


if($method != 'GET'){
    $set_data = return_object(400);
}
else{

    #SEARCH 

   
    

    $sql = "     SELECT  
                a.`pay_id`
                ,a.`status`
                ,a.`pay_date`
                ,a.`pay_time`
                ,a.`pay_url_file`
                ,a.`pay_received`
                ,a.`book_id`
                ,a.`book_status`
                ,a.`bankbook_id`
                ,b.`bank_name`
                ,b.`bankbook_code`
                ,b.`bankbook_name`
                ,b.`bankbook_branch`
                ,a.`remark`
                ,a.`remark_cancel`
                FROM `payment` a
                LEFT OUTER JOIN `bankbook` b
                ON a.`bankbook_id` = b.`bankbook_id`
                WHERE  a.`pay_id` = $pay_id
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
