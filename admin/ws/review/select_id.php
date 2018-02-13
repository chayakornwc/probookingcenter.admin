<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST
 $review_id   = $_REQUEST['review_id'];

$method         = strtoupper($_REQUEST['method']);

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
    $sql_status = '`user_status` IN ('.implode(",",$sql_status).')';

    if($type_10 == 1){
        $sql_type[] = 10;
    }  
    if($type_20 == 1){
        $sql_type[] = 20;
    }
    if($type_30 == 1){
        $sql_type[] = 30;
    }

   // $sql_status = '`user_group_id` IN ('.implode(",",$sql_type).')';
   $sql_status = '';
    

    $sql = "SELECT 
                    `review_id`,
                    `country_id`,
                    `review_detail`,
                    `review_url_img_1`,
                    `review_url_img_2`,
                    `review_url_img_3`,
                    `review_url_img_4`, 
                    `review_url_img_5`, 
                    `remark`,
                    `status`
                FROM `review`
                WHERE `review_id` = '$review_id'
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
