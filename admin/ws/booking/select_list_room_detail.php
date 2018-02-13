<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST
$book_code      = $_REQUEST['book_code'];

$method           = strtoupper($_REQUEST['method']);

#set var
$set_data = array();


if($method != 'GET'){
    $set_data = return_object(400);
}
else{

    #SEARCH 
  
    

    $sql = "SELECT  `room_id`,
                    `room_prename`, 
                    `room_fname`, 
                    `room_lname`,
                    `room_name_thai`,
                    `room_sex`,
                    `room_country`,
                    `room_nationality`,
                    `room_address`, 
                    `room_birthday`, 
                    `room_passportno`, 
                    `room_expire`, 
                    `room_file`, 
                    `room_remark`,
                    `room_career`, 
                    `room_placeofbirth`, 
                    `room_place_pp`, 
                    `room_date_pp`
                    
                    FROM `room_detail` 
                    WHERE `book_code` = $book_code
                    ORDER BY `room_no`
                    ";

    
    $sql         = $sql;
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
        $set_data = return_object(204,$sql);
    }

}

/*echo '<pre>';
print_r($set_data);
return;*/
echo json_encode($set_data);

disconect_db();
