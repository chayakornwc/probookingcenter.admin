<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST


$agen_com_id         = $_REQUEST['agen_com_id'];

$offset         = $_REQUEST['offset'];
$limit         = $_REQUEST['limit'];


$method         = strtoupper($_REQUEST['method']);

#set var
$set_data = array();


if($method != 'GET'){
    $set_data = return_object(400);
}
else{

    #SEARCH 
  

    $sql = "SELECT 
                    `agen_com_id`,
                    `agen_com_name`,
                    `agen_com_tel`,
                    `agen_com_fax`,
                    `agen_com_ttt_on`,
                    `agen_com_ttt_url_img`,
                    `agen_com_logo_img`,
                    `agen_com_address1`,
                    `agen_com_address2`,
                    `remark`,
                    `status`
                FROM `agency_company`
                WHERE `agen_com_id` = '$agen_com_id'
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
