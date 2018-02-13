<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST
$user_name              = $_REQUEST['user_name'];
$user_password          = $_REQUEST['user_password'];


$method             = strtoupper($_REQUEST['method']);

#set var
$set_data = array();


if($method != 'GET'){
    $set_data = return_object(400);
}
else{

    #SEARCH 
  

    $sql = "SELECT 
                    a.`user_id`,
                    a.`user_name`,
                    a.`group_id`,
                    b.`group_name`,
                    a.`user_fname`,
                    a.`user_lname`,
                    a.`user_password`,
                    a.`user_email`,
                    a.`user_tel`, 
                    a.`remark`,
                    a.`user_lastlogin`,
                    b.`menu_1`,
                    b.`menu_2`,
                    b.`menu_3`,
                    b.`menu_4`,
                    b.`menu_5`,
                    b.`menu_6`,
                    b.`menu_7`,
                    b.`menu_8`,
                    b.`menu_9`,
                    b.`menu_10`,
                    b.`menu_11`,
                    b.`menu_12`,
                    b.`menu_13`,
                    b.`menu_14`,
                    b.`menu_15`,
                    b.`menu_16`,
                    b.`menu_17`,

                    a.`status`
                FROM `user` a
                LEFT OUTER JOIN `group` b
                ON a.`group_id` = b.`group_id`
                WHERE a.`user_name` = '$user_name'
                AND a.`user_password` = '$user_password' 
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

        #UPDATE lastlogin
        $sql = " UPDATE `user` SET 
                `user_lastlogin` = NOW()
                WHERE `user_id` = '$user_id'
                 ";
        $result = mysqli_query($CON,$sql);


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
