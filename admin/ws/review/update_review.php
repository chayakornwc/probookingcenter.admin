<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST


 $review_id   = $_REQUEST['review_id'];
 $country_id   = $_REQUEST['country_id'];
 $review_detail   = $_REQUEST['review_detail'];
 $review_url_img_1   = $_REQUEST['review_url_img_1'];
 $review_url_img_2   = $_REQUEST['review_url_img_2'];
 $review_url_img_3   = $_REQUEST['review_url_img_3'];
 $review_url_img_4   = $_REQUEST['review_url_img_4'];
 $review_url_img_5   = $_REQUEST['review_url_img_5'];
 $update_user_id   = $_REQUEST['update_user_id'];
 $remark   = $_REQUEST['remark'];
 $status   = $_REQUEST['status'];
                                   

$method         = strtoupper($_REQUEST['method']);

#set var
$set_data = array();


if($method != 'PUT'){
    $set_data = return_object(400);
}
else{

  
   // $sql_status = '`user_group_id` IN ('.implode(",",$sql_type).')';
   $sql_status = '';
    

    $sql = "UPDATE `review` SET
                                `country_id` = '$country_id',
                                `review_detail` = '$review_detail',
                                 `review_url_img_1` = '$review_url_img_1',
                                 `review_url_img_2` = '$review_url_img_2',
                                 `review_url_img_3` = '$review_url_img_3',
                                 `review_url_img_4` = '$review_url_img_4',
                                 `review_url_img_5` = '$review_url_img_5',
                                 `update_user_id` = '$update_user_id',
                                 `update_date` = NOW(),
                                 `remark` = '$remark',
                                 `status` = '$status'
                                 WHERE `review_id` = '$review_id' 
                               ";
    $sql         = $sql.set_limit($offset , $limit);
    $result = mysqli_query($CON,$sql);

    $set_data['sql'] = $sql;

    if($result){
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