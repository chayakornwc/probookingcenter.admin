<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST


$group_id   = $_REQUEST['group_id'];
$menu_1     = $_REQUEST['menu_1'];
$menu_2     = $_REQUEST['menu_2'];
$menu_3     = $_REQUEST['menu_3'];
$menu_4     = $_REQUEST['menu_4'];
$menu_5     = $_REQUEST['menu_5'];
$menu_6     = $_REQUEST['menu_6'];
$menu_7     = $_REQUEST['menu_7'];
$menu_8     = $_REQUEST['menu_8'];
$menu_9     = $_REQUEST['menu_9'];
$menu_10     = $_REQUEST['menu_10'];
$menu_11     = $_REQUEST['menu_11'];
$menu_12     = $_REQUEST['menu_12'];
$menu_13     = $_REQUEST['menu_13'];
$menu_14     = $_REQUEST['menu_14'];
$menu_15     = $_REQUEST['menu_15'];
$menu_17     = $_REQUEST['menu_17'];
$menu_18     = $_REQUEST['menu_18'];
//$menu_16     = $_REQUEST['menu_16'];



$method         = strtoupper($_REQUEST['method']);

#set var
$set_data = array();


if($method != 'PUT'){
    $set_data = return_object(400);
}
else{

  
   // $sql_status = '`user_group_id` IN ('.implode(",",$sql_type).')';
   $sql_status = '';
    

    $sql = "UPDATE `group` SET
                                    `menu_1`        =   $menu_1,
                                    `menu_2`        =   $menu_2,
                                    `menu_3`        =   $menu_3,
                                    `menu_4`        =   $menu_4,
                                    `menu_5`        =   $menu_5,
                                    `menu_6`        =   $menu_6,
                                    `menu_7`        =   $menu_7,
                                    `menu_8`        =   $menu_8,
                                    `menu_9`        =   $menu_9,
                                    `menu_10`        =   $menu_10,
                                    `menu_11`        =   $menu_11,
                                    `menu_12`        =   $menu_12,
                                    `menu_13`        =   $menu_13,
                                    `menu_14`        =   $menu_14,
                                    `menu_15`        =   $menu_15,
                                    `menu_17`        =   $menu_17,
                                    `menu_18`        =   $menu_18


                                 WHERE `group_id` = '$group_id' 
                               ";
    $sql         = $sql.set_limit($offset , $limit);
    $result = mysqli_query($CON,$sql);
    
    $set_data['sql'] = $sql;
    
    if($result){
       // $sql = "SELECT LAST_INSERT_ID() FROM `user`";
        //$result = mysqli_query($CON,$sql);
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
