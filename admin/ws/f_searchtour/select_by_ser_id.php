<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST
$ser_id      = $_REQUEST['ser_id'];



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
                    a.`ser_url_img_1`,
                    a.`ser_url_img_2`,
                    a.`ser_url_pdf`,
                    a.`ser_url_word`,
                    a.`remark`,
                    c.`country_name`,
                    d.`air_name`,
                    d.`air_url_img`
                    
                FROM `series` a
                LEFT OUTER JOIN `country` c
                ON a.`country_id` = c.`country_id`
                LEFT OUTER JOIN `airline` d
                ON a.`air_id` = d.`air_id`
                WHERE (a.`status` = '1' )
                AND a.`ser_id` = '$ser_id'
                ";
  
    $result = mysqli_query($CON,$sql);


   if(mysqli_num_rows($result) == 0 ){
        $set_data = return_object(204);
   }
   else {
        if (mysqli_num_rows($result) > 0) {
              while ($data = mysqli_fetch_assoc($result)) {
                        $ser_id = $data['ser_id'];
                        $sql3 = "SELECT 
                                        a.`per_id`,
                                        a.`per_date_start`,
                                        a.`per_date_end`,
                                        a.`per_price_1`,
                                        a.`per_price_2`,
                                        a.`per_price_3`,
                                        a.`per_price_1` + a.`single_charge` AS 'price_alone',
                                        a.`per_url_word`,
                                        a.`per_url_pdf`,
                                        (SELECT  COALESCE(SUM(b.`book_list_qty`),0) FROM `booking_list` b LEFT OUTER JOIN `booking` bo on b.`book_code` = bo.`book_code` 
                                        WHERE bo.`per_id` =  a.`per_id` 
                                        AND b.`book_list_code` IN ('1','2','3','4','5') AND bo.`status` != 40) AS 'qty_book',
                                        a.`per_qty_seats`,
                                        a.`status`
                                    FROM `period` a
                                    WHERE a.`ser_id` = $ser_id
                                    AND a.`status` NOT IN ('3','9')
                                   
                                 ";
                        $result3 = mysqli_query($CON,$sql3);

                        while ($data2 = mysqli_fetch_assoc($result3)) {
                            $data['date_period'][] = $data2;
                        }
                            
                        $set_data['result']['ser'][] = $data;
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
