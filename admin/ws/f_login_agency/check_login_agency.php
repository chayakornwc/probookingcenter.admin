<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST
$username              = $_REQUEST['username'];
$password          = $_REQUEST['password'];


$method             = strtoupper($_REQUEST['method']);

#set var
$set_data = array();


if($method != 'GET'){
    $set_data = return_object(400);
}
else{

    #SEARCH 
  

    $sql = "SELECT 
                    a.`agen_id`,
                    a.`agen_user_name`,
                    a.`agen_fname`,
                    a.`agen_lname`,
                    a.`agen_password`,
                    a.`agen_email`,
                    a.`agen_tel`, 
                    a.`agency_company_id`,
                    b.`agen_com_name`,
                    a.`remark`
                FROM `agency` a
                LEFT OUTER JOIN `agency_company` b
                ON a.`agency_company_id` = b.`agen_com_id`
                WHERE a.`agen_user_name` = '$username'
                AND a.`agen_password` = '$password' 
                AND a.`status` != 9 ";

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
