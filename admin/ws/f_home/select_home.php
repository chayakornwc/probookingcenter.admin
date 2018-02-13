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
                ";

    $sql2 = "SELECT 
                    a.`review_id`,
                    a.`country_id`,
                    a.`review_detail`,
                    a.`review_url_img_1`,
                    a.`review_url_img_2`,
                    a.`review_url_img_3`,
                    a.`review_url_img_4`, 
                    a.`review_url_img_5`,
                    a.`remark`,
                    c.`country_name`
                FROM `review` a
                LEFT OUTER JOIN `country` c
                ON a.`country_id` = c.`country_id`
                WHERE (a.`status` = '1' )
                ORDER BY a.`review_id` DESC
                ";


  
    $result = mysqli_query($CON,$sql);

    
    $result2 = mysqli_query($CON,$sql2);



   if(mysqli_num_rows($result) == 0 && mysqli_num_rows($result2) == 0){
        $set_data = return_object(204);
   }
   else {
        if (mysqli_num_rows($result) > 0) {
              while ($data = mysqli_fetch_assoc($result)) {
                        $ser_id = $data['ser_id'];
                        $sql3 = "SELECT 
                                        a.`per_date_start`,
                                        a.`per_date_end`,
                                        a.`per_price_1`,
                                        a.`per_price_2`,
                                        a.`per_price_3`,
                                        a.`per_price_4`,
                                        a.`per_price_5`,
                                        a.`per_qty_seats`
                                    FROM `period` a
                                    WHERE a.`ser_id` = $ser_id
                                    AND a.`status` NOT IN ('3','9','10')
                                    ORDER BY a.`per_id` DESC LIMIT 3
                                 ";
                        $result3 = mysqli_query($CON,$sql3);

                        while ($data2 = mysqli_fetch_assoc($result3)) {
                            $data['date_period'][] = $data2;
                        }
                            
                        $set_data['result']['ser'][] = $data;
                }
        }
        if (mysqli_num_rows($result2) > 0) {
              while ($data = mysqli_fetch_assoc($result2)) {
            $set_data['result']['rev'][] = $data;
            }
        }

        $set_data = return_object(200,$set_data['result']);
   }
}
/*echo '<pre>';
print_r($set_data);
return;*/
echo json_encode($set_data);

disconect_db();
