<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST

 $per_id   = $_REQUEST['per_id'];
 $per_date_start   = $_REQUEST['per_date_start'];
 $per_date_end    = $_REQUEST['per_date_end'];
 $per_price_1   = $_REQUEST['per_price_1'];
 $per_price_2   = $_REQUEST['per_price_2'];
 $per_price_3   = $_REQUEST['per_price_3'];
 $per_price_4   = $_REQUEST['per_price_4'];
 $per_price_5   = $_REQUEST['per_price_5'];
 $single_charge   = $_REQUEST['single_charge'];
 $per_qty_seats   = $_REQUEST['per_qty_seats'];
 $per_cost   = $_REQUEST['per_cost'];
 $per_expenses   = $_REQUEST['per_expenses'];
 $status   = $_REQUEST['status'];
 $ser_id   = $_REQUEST['ser_id'];
 $remark   = $_REQUEST['remark'];
  $per_url_word   = $_REQUEST['per_url_word'];
 $per_url_pdf   = $_REQUEST['per_url_pdf'];
 $bus_no1   = $_REQUEST['bus_no1'];
 $per_com_company_agency   = $_REQUEST['per_com_company_agency'];
 $per_com_agency   = $_REQUEST['per_com_agency'];
 $update_user_id      = $_REQUEST['update_user_id'];
 $bus_order_arr   = $_REQUEST['bus_order_arr'];
 $bus_qty_arr   = $_REQUEST['bus_qty_arr'];

$offset         = $_REQUEST['offset'];
$limit         = $_REQUEST['limit'];

$per_on_fire = $_REQUEST["per_on_fire"];

$method           = strtoupper($_REQUEST['method']);

#set var
$set_data = array();


if($method != 'PUT'){
    $set_data = return_object(400);
}
else{

    #SEARCH 
  
    

    $sql = "UPDATE `period` SET 
                    `per_date_start` = '$per_date_start',
                    `per_date_end` = '$per_date_end',
                    `per_price_1` = '$per_price_1',
                    `per_price_2` = '$per_price_2',
                    `per_price_3` = '$per_price_3',
                    `per_price_4` = '$per_price_4',
                    `per_price_5` = '$per_price_5',
                    `single_charge` = '$single_charge',
                    `per_qty_seats` = '$per_qty_seats',
                    `per_cost` = '$per_cost',
                    `per_expenses` = '$per_expenses',
                    `status` = '$status',
                    `ser_id` = '$ser_id',
                    `per_url_word` = '$per_url_word',
                    `per_url_pdf` = '$per_url_pdf',
                    `per_com_agency` = '$per_com_agency',
                    `per_com_company_agency` = '$per_com_company_agency',
                    `update_user_id` = '$update_user_id',
                    `update_date` = NOW(),
                    `remark` = '$remark',
                    `per_on_fire` = '$per_on_fire'
                WHERE `per_id` = '$per_id'
                ";

    $sql         = $sql.set_limit($offset , $limit);
    $result = mysqli_query($CON,$sql);

    $set_data['sql'] = $sql;

    if($result){
        $sql = "DELETE FROM `bus_list` WHERE `per_id` = $per_id";
        $result2 = mysqli_query($CON,$sql);

        for ( $i = 0;$i < count($bus_order_arr);$i++ ){
           $sql = "INSERT INTO `bus_list`(
                                `per_id`,
                                `bus_no`,
                                `bus_qty`
                                )  VALUES (
                                '$per_id',
                                '$bus_order_arr[$i]',
                                '$bus_qty_arr[$i]'
                                )";  
           $result3 = mysqli_query($CON,$sql);
        }

       // $sql = "SELECT LAST_INSERT_ID() FROM `user`";
        //$result = mysqli_query($CON,$sql);
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
