<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST


 $book_code             = $_REQUEST['book_code'];
 $room_prename            = $_REQUEST['room_prename'];
 $room_fname            = $_REQUEST['room_fname'];
 $room_lname            = $_REQUEST['room_lname'];
 $room_name_thai            = $_REQUEST['room_name_thai'];
 $room_sex              = $_REQUEST['room_sex'];
 $room_country          = $_REQUEST['room_country'];
 $room_nationality      = $_REQUEST['room_nationality'];
 $room_address          = $_REQUEST['room_address'];
 $room_birthday         = $_REQUEST['room_birthday'];
 $room_passportno       = $_REQUEST['room_passportno'];
 $room_expire           = $_REQUEST['room_expire'];
 $room_file             = $_REQUEST['room_file'];
 $room_remark           = $_REQUEST['room_remark'];
 $room_career           = $_REQUEST['room_career'];
 $room_placeofbirth           = $_REQUEST['room_placeofbirth'];
 $room_place_pp           = $_REQUEST['room_place_pp'];
 $room_date_pp           = $_REQUEST['room_date_pp'];
                                   

$method         = strtoupper($_REQUEST['method']);

#set var
$set_data = array();


if($method != 'PUT'){
    $set_data = return_object(400);
}
else{


        $sql = "DELETE FROM `room_detail` WHERE `book_code` = '$book_code'";
        $result = mysqli_query($CON,$sql);

        $linenumber = 1;
        for ( $i = 0;$i < count($room_fname);$i++ ){
           $sql = "INSERT INTO `room_detail`(
                                `book_code`,
                                `room_no`,
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
                                )  VALUES (
                                '$book_code',
                                $linenumber,
                                '$room_prename[$i]',
                                '$room_fname[$i]',
                                '$room_lname[$i]',
                                '$room_name_thai[$i]',
                                '$room_sex[$i]',
                                '$room_country[$i]',
                                '$room_nationality[$i]',
                                '$room_address[$i]',
                                '$room_birthday[$i]',
                                '$room_passportno[$i]',
                                '$room_expire[$i]',
                                '$room_file[$i]',
                                '$room_remark[$i]',
                                '$room_career[$i]',
                                '$room_placeofbirth[$i]',
                                '$room_place_pp[$i]',
                                '$room_date_pp[$i]'
                                )";  
           $result2 = mysqli_query($CON,$sql);
           $linenumber = $linenumber +1;
        }



    $set_data['sql'] = $sql;

    if($result2){
        $sql = "SELECT LAST_INSERT_ID() FROM `room_detail`";
        $result = mysqli_query($CON,$sql);
        $set_data = return_object(200,$result);
     
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
