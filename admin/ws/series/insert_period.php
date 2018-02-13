<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST


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
 
 $bus_order_arr   = $_REQUEST['bus_order_arr'];
 $bus_qty_arr   = $_REQUEST['bus_qty_arr'];
 $per_com_company_agency   = $_REQUEST['per_com_company_agency'];
 $per_com_agency   = $_REQUEST['per_com_agency'];
 $create_user_id   = $_REQUEST['create_user_id'];
                            
$per_on_fire = $_REQUEST["per_on_fire"];

$method         = strtoupper($_REQUEST['method']);

#set var
$set_data = array();


if($method != 'PUT'){
    $set_data = return_object(400);
}
else{

  
   // $sql_status = '`user_group_id` IN ('.implode(",",$sql_type).')';
   $sql_status = '';
    

    $sql = "INSERT INTO `period`
                                (
                                `per_date_start`,
                                `per_date_end`,
                                 `per_price_1`,
                                 `per_price_2`,
                                 `per_price_3`,
                                 `per_price_4`,
                                 `per_price_5`,
                                 `single_charge`,
                                 `per_qty_seats`,
                                 `per_cost`,
                                 `per_expenses`,
                                 `status`,
                                 `ser_id`,
                                 `remark`,
                                 `per_url_word`,
                                 `per_url_pdf`,
                                 `per_com_company_agency`,
                                 `per_com_agency`,
                                 `create_user_id`,
                                 `create_date`,
                                 `per_on_fire`
                                 ) VALUES (
                                     '$per_date_start',
                                     '$per_date_end',
                                     '$per_price_1',
                                     '$per_price_2',
                                     '$per_price_3',
                                     '$per_price_4',
                                     '$per_price_5',
                                     '$single_charge',
                                     '$per_qty_seats',
                                     '$per_cost',
                                     '$per_expenses',
                                     '$status',
                                     '$ser_id',
                                     '$remark',
                                     '$per_url_word',
                                     '$per_url_pdf',
                                     '$per_com_company_agency',
                                     '$per_com_agency',
                                     '$create_user_id',
                                     NOW(),
                                     '$per_on_fire'
                                  )";
    $sql         = $sql.set_limit($offset , $limit);
    $result = mysqli_query($CON,$sql);

    $set_data['sql'] = $sql;

    if($result){
        $sql = "SELECT LAST_INSERT_ID() FROM `period`";
        $result = mysqli_insert_id($CON); 
        $set_data = return_object(200,$result);

        for ( $i = 0;$i < count($bus_order_arr);$i++ ){
           $sql = "INSERT INTO `bus_list`(
                                `per_id`,
                                `bus_no`,
                                `bus_qty`
                                )  VALUES (
                                '$result',
                                '$bus_order_arr[$i]',
                                '$bus_qty_arr[$i]'
                                )";  
           $result2 = mysqli_query($CON,$sql);
        }
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
