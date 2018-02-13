<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST
$review_id     = $_REQUEST['review_id'];

$method           = strtoupper($_REQUEST['method']);

#set var
$set_data = array();


if($method != 'GET'){
    $set_data = return_object(400);
}
else{
    
    $sql = "SELECT 
                    a.`review_id`,
                    a.`country_id`,
                    a.`review_detail`,
                    b.`country_name`,
                    a.`review_url_img_1`,
                    a.`review_url_img_2`,
                    a.`review_url_img_3`,
                    a.`review_url_img_4`,
                    a.`review_url_img_5`, 
                    a.`remark`
                FROM `review` a
                LEFT OUTER JOIN `country` b
                ON a.`country_id` = b.`country_id`
                WHERE (a.`review_id` = '$review_id' )
               
                ";

    $sql_all_row = $sql;
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
