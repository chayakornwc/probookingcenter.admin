<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST


 $status   = $_REQUEST['status'];
 $bank_name   = $_REQUEST['bank_name'];
 $bankbook_code   = $_REQUEST['bankbook_code'];
 $bankbook_name   = $_REQUEST['bankbook_name'];
 $bankbook_branch   = $_REQUEST['bankbook_branch'];
 $create_user_id   = $_REQUEST['create_user_id'];
 $remark   = $_REQUEST['remark'];
                                   

$method         = strtoupper($_REQUEST['method']);

#set var
$set_data = array();


if($method != 'PUT'){
    $set_data = return_object(400);
}
else{

  
   // $sql_status = '`user_group_id` IN ('.implode(",",$sql_type).')';
   $sql_status = '';
    

    $sql = "INSERT INTO `bankbook`
                                (
                                `status`,
                                 `bank_name`,
                                 `bankbook_code`,
                                 `bankbook_name`,
                                 `bankbook_branch`,
                                 `create_user_id`,
                                 `create_date`,
                                 `remark`
                                 ) VALUES (
                                     '$status',
                                     '$bank_name',
                                     '$bankbook_code',
                                     '$bankbook_name',
                                     '$bankbook_branch',
                                     '$create_user_id',
                                     NOW(),
                                     '$remark'
                                 )";
    $sql         = $sql.set_limit($offset , $limit);
    $result = mysqli_query($CON,$sql);

    $set_data['sql'] = $sql;

    if($result){
        $sql = "SELECT LAST_INSERT_ID() FROM `bankbook`";
        $result = mysqli_query($CON,$sql);
        $set_data = return_object(200,$result);
     
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
