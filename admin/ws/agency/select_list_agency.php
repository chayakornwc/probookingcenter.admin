<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST
$word_search      = $_REQUEST['word_search'];
$status_1         = $_REQUEST['status_1'];
$status_2         = $_REQUEST['status_2'];
$status_0         = $_REQUEST['status_0'];

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
    if($status_1 == 1){
        $sql_status[] = 1;
    }
    if($status_2 == 2){
        $sql_status[] = 2;
    }
    if($status_0 == 0){
        $sql_status[] = 0;
    }
    if($status_1 == 1 || $status_2 == 2 || $status_0 == 0){
    $sql_status = ' AND a.`status` IN ('.implode(",",$sql_status).')';
    }
    else {
      $sql_status = '1 != 1';  
    }

    $sql = "SELECT 
                    a.`agen_id`,
                    a.`agen_user_name`,
                    a.`agen_password`,
                    a.`agen_fname`,
                    a.`agen_lname`,
                    a.`agen_position`,
                    a.`agen_email`, 
                    a.`agen_tel`, 
                    a.`agen_line_id`, 
                    a.`agen_skype`, 
                    a.`remark`,
                    b.`agen_com_name`,
                    a.`status`
                FROM `agency` a
                LEFT OUTER JOIN `agency_company` b
                ON a.`agency_company_id` = b.`agen_com_id`
                WHERE (a.`status` != '9' $sql_status )
                And (  a.`agen_user_name`  LIKE  '%$word_search%'
                    OR a.`agen_fname` LIKE  '%$word_search%'
                    OR a.`agen_lname` LIKE  '%$word_search%'
                    OR a.`agen_email` LIKE  '%$word_search%'
                    OR a.`agen_tel`   LIKE  '%$word_search%'
                    OR a.`remark`   LIKE  '%$word_search%'
                    OR b.`agen_com_name`   LIKE  '%$word_search%'
                )
                ORDER BY a.`agen_id` DESC
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
