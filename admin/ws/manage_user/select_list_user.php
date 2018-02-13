<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST
$word_search      = $_REQUEST['word_search'];
$status_1         = $_REQUEST['status_1'];
$status_2         = $_REQUEST['status_2'];

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
    if($status_1 == 1){
        $sql_status[] = 1;
    }
    if($status_2 == 2){
        $sql_status[] = 2;
    }
    if($status_1 == 1 || $status_2 == 2){
    $sql_status = ' AND a.`status` IN ('.implode(",",$sql_status).')';
    }
    else {
      $sql_status = '1 != 1';  
    }
   
    

    $sql = "SELECT 
                    a.`user_id`,
                    a.`user_name`,
                    a.`group_id`,
                    b.`group_name`,
                    a.`user_fname`,
                    a.`user_lname`,
                    a.`user_nickname`,
                    a.`user_password`,
                    a.`user_email`,
                    a.`user_tel`, 
                    a.`remark`,
                    a.`user_lastlogin`,
                     a.`status`
                FROM `user` a
                LEFT OUTER JOIN `group` b
                ON a.`group_id` = b.`group_id`
                WHERE (a.`status` != '9' $sql_status )
                And (  a.`user_name`  LIKE  '%$word_search%'
                    OR a.`user_fname` LIKE  '%$word_search%'
                    OR a.`user_lname` LIKE  '%$word_search%'
                    OR a.`user_email` LIKE  '%$word_search%'
                    OR a.`user_tel`   LIKE  '%$word_search%'
                    OR b.`group_name`   LIKE  '%$word_search%'
                )
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
