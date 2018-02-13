<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST


 $country_name          = $_REQUEST['country_name'];
 $country_deposit       = $_REQUEST['country_deposit'];
 $create_user_id        = $_REQUEST['create_user_id'];
 $remark                = $_REQUEST['remark'];
 $status                = $_REQUEST['status'];
                                  

$method         = strtoupper($_REQUEST['method']);

#set var
$set_data = array();


if($method != 'PUT'){
    $set_data = return_object(400);
}
else{

  
   // $sql_status = '`user_group_id` IN ('.implode(",",$sql_type).')';
   $sql_status = '';
    

    $sql = "INSERT INTO `country`
                                (
                                 `country_name`,
                                 `country_deposit`,
                                 `create_user_id`,
                                 `create_date`,
                                 `remark`,
                                 `status`
                                 ) VALUES (
                                     '$country_name',
                                     '$country_deposit',
                                     '$create_user_id',
                                     NOW(),
                                     '$remark',
                                     '$status'
                                 )";
    $sql         = $sql.set_limit($offset , $limit);
    $result = mysqli_query($CON,$sql);

    $set_data['sql'] = $sql;

    if($result){
        $sql = "SELECT LAST_INSERT_ID() FROM `country`";
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
