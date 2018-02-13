<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST
$book_id      = $_REQUEST['book_id'];


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
                a.`pay_id`
                ,a.`status`
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
                WHERE  a.`book_id` = $book_id
                ORDER BY  a.`pay_id`
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
