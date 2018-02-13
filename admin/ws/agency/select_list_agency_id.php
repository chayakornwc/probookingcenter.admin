<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST

 $agen_id   = $_REQUEST['agen_id'];
$method         = strtoupper($_REQUEST['method']);

#set var
$set_data = array();


if($method != 'GET'){
    $set_data = return_object(400);
}
else{

    #SEARCH 
  
    

    $sql = "SELECT 
                    `agen_id`,
                    `agen_user_name`,
                    `agen_password`,
                    `agen_fname`,
                    `agen_lname`,
                    `agen_position`,
                    `agen_email`, 
                    `agen_tel`, 
                    `agen_line_id`, 
                    `agen_skype`, 
                    `remark`,
                    `agen_nickname`,
                    `agency_company_id`,
                    `agen_show`,
                    `status` 
                FROM `agency`
                WHERE `agen_id` = '$agen_id'
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
