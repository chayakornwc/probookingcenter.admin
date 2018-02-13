<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST

$method           = strtoupper($_REQUEST['method']);

#set var
$set_data = array();


if($method != 'GET'){
    $set_data = return_object(400);
}
else{

    #SEARCH 

    $sql = "SELECT 
                    a.`ser_id`,
                    a.`ser_name`,
                    a.`ser_code`,
                    a.`country_id`,
                    a.`ser_city`,
                    a.`air_id`,
                    a.`ser_route`,
                    a.`ser_deposit`, 
                    a.`ser_url_img_1`,
                    a.`ser_url_img_2`,
                    a.`ser_url_img_3`,
                    a.`ser_url_img_4`,
                    a.`ser_url_img_5`,
                    a.`ser_show`,
                    a.`remark`,
                    c.`country_name`,
                    d.`air_name`,
                    d.`air_url_img`
                FROM `series` a
                LEFT OUTER JOIN `country` c
                ON a.`country_id` = c.`country_id`
                LEFT OUTER JOIN `airline` d
                ON a.`air_id` = d.`air_id`
                WHERE (a.`status` = '1' ) AND a.`ser_show` != '0'
                ORDER BY a.`ser_id` DESC
                LIMIT 3
                ";

  
    $result = mysqli_query($CON,$sql);


   if(mysqli_num_rows($result) > 0 ){
         while ($data = mysqli_fetch_assoc($result)) {
            $set_data['result'][] = $data;
            }
              $set_data = return_object(200,$set_data['result']);
   }else {
              $set_data = return_object(204);
   }
}
/*echo '<pre>';
print_r($set_data);
return;*/
echo json_encode($set_data);

disconect_db();
