<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST
$word_search        = $_REQUEST['word_search'];
$country         = $_REQUEST['country_id'];
$method             = strtoupper($_REQUEST['method']);

#set var
$set_data = array();


if($method != 'GET'){
    $set_data = return_object(400);
}
else{

    #SEARCH 
    if($country!=''){
        $sql_country = 'a.`country_id` = '.$country;
    }
    else{
        $sql_country = '1=1';
    }
    

    $sql = "SELECT 
                    a.`review_id`,
                    a.`country_id`,
                    b.`country_name`,
                    a.`review_detail`,
                    a.`review_url_img_1`,
                    a.`review_url_img_2`,
                    a.`review_url_img_3`,
                    a.`review_url_img_4`, 
                    a.`review_url_img_5`, 
                    a.`remark`,
                    a.`status`
                FROM `review` a
                LEFT OUTER JOIN `country` b
                ON a.`country_id` = b.`country_id`
                WHERE (a.`status` != '9' ) 
                    AND $sql_country
                    AND (  b.`country_name`  LIKE  '%$word_search%'
                    OR a.`review_detail` LIKE  '%$word_search%'
                )
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
