<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST


 $status                = $_REQUEST['status'];
 $ser_name              = $_REQUEST['ser_name'];
 $ser_code              = $_REQUEST['ser_code'];
 $country_id            = $_REQUEST['country_id'];
 $air_id                = $_REQUEST['air_id'];
 $ser_show              = $_REQUEST['ser_show'];
 $ser_route             = $_REQUEST['ser_route'];

 $ser_deposit           = $_REQUEST['ser_deposit'];
 $ser_url_img_1         = $_REQUEST['ser_url_img_1'];
 $ser_url_img_2         = $_REQUEST['ser_url_img_2'];
 $ser_url_img_3         = $_REQUEST['ser_url_img_3'];
 $ser_url_img_4         = $_REQUEST['ser_url_img_4'];
 $ser_url_img_5         = $_REQUEST['ser_url_img_5'];
 $ser_url_word          = $_REQUEST['ser_url_word'];
 $ser_url_pdf           = $_REQUEST['ser_url_pdf'];
 $remark                = $_REQUEST['remark'];

 $ser_go_flight_code                         = $_REQUEST['ser_go_flight_code'];
$ser_go_route                         = $_REQUEST['ser_go_route'];
$ser_go_time                         = $_REQUEST['ser_go_time'];
$ser_return_flight_code                         = $_REQUEST['ser_return_flight_code'];
$ser_return_route                         = $_REQUEST['ser_return_route'];
$ser_return_time                         = $_REQUEST['ser_return_time'];

$ser_is_promote                         = $_REQUEST['ser_is_promote'];
$ser_is_recommend                       = $_REQUEST['ser_is_recommend'];


 $create_user_id        = $_REQUEST['create_user_id'];
                                   

$method         = strtoupper($_REQUEST['method']);

#set var
$set_data = array();


if($method != 'PUT'){
    $set_data = return_object(400);
}
else{

  
   // $sql_status = '`user_group_id` IN ('.implode(",",$sql_type).')';
   $sql_status = '';
    

    $sql = "INSERT INTO `series`
                                (
                                `status`,
                                `ser_name`,
                                 `ser_code`,
                                 `country_id`,
                                 `air_id`,
                                 `ser_show`,
                                 `ser_route`,
                                 `ser_deposit`,
                                 `ser_url_img_1`,
                                 `ser_url_img_2`,
                                 `ser_url_img_3`,
                                 `ser_url_img_4`,
                                 `ser_url_img_5`,
                                 `ser_url_word`,
                                 `ser_url_pdf`,
                                 `remark`,
                                 `ser_go_flight_code`,
                                 `ser_go_route`,
                                 `ser_go_time`,
                                 `ser_return_flight_code`,
                                 `ser_return_route`,
                                 `ser_return_time`,
                                 `create_user_id`,
                                 `create_date`,
                                 `ser_is_promote`,
                                 `ser_is_recommend`
                                 ) VALUES (
                                     '$status',
                                     '$ser_name',
                                     '$ser_code',
                                     '$country_id',
                                     '$air_id',
                                     '$ser_show',
                                     '$ser_route',
                                     '$ser_deposit',
                                     '$ser_url_img_1',
                                     '$ser_url_img_2',
                                     '$ser_url_img_3',
                                     '$ser_url_img_4',
                                     '$ser_url_img_5',
                                     '$ser_url_word',
                                     '$ser_url_pdf',
                                     '$remark',
                                     '$ser_go_flight_code',
                                     '$ser_go_route',
                                     '$ser_go_time',
                                     '$ser_return_flight_code',
                                     '$ser_return_route',
                                     '$ser_return_time',
                                     '$create_user_id',
                                     NOW(),
                                     '$ser_is_promote',
                                     '$ser_is_recommend'
                                  )";
    $result = mysqli_query($CON,$sql);

    $set_data['sql'] = $sql;

    if($result){
        $sql = "SELECT LAST_INSERT_ID() FROM `series`";
        $result = mysqli_insert_id($CON);
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
