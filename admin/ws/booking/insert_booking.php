<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST

 $book_code                 = $_REQUEST['book_code'];
 $invoice_code              = $_REQUEST['invoice_code'];
 $agen_id                   = $_REQUEST['agen_id'];
 $user_id                   = $_REQUEST['user_id'];
 $per_id                    = $_REQUEST['per_id'];
 $bus_no                    = $_REQUEST['bus_no'];
 $book_total                = $_REQUEST['book_total'];
 $book_discount             = $_REQUEST['book_discount'];
 $book_amountgrandtotal     = $_REQUEST['book_amountgrandtotal'];
 $book_comment              = $_REQUEST['book_comment'];
 $book_master_deposit       = $_REQUEST['book_master_deposit'];
 $book_due_date_deposit     = $_REQUEST['book_due_date_deposit'];
 $book_master_full_payment          = $_REQUEST['book_master_full_payment'];
 $book_due_date_full_payment        = $_REQUEST['book_due_date_full_payment'];
 $book_com_agency_company           = $_REQUEST['book_com_agency_company'];
 $book_com_agency                   = $_REQUEST['book_com_agency'];
 $remark                            = $_REQUEST['remark'];
 $book_room_twin                    = $_REQUEST['book_room_twin'];
 $book_room_double                  = $_REQUEST['book_room_double'];
 $book_room_triple                  = $_REQUEST['book_room_triple'];
 $book_room_single                  = $_REQUEST['book_room_single'];
 $create_user_id        = $_REQUEST['create_user_id'];
 $status                = $_REQUEST['status'];
                                   

// list
 $per_price_1                   = $_REQUEST['per_price_1'];
 $per_qty_1                     = $_REQUEST['per_qty_1'];
 $per_total_1                   = $_REQUEST['per_total_1'];
 $per_price_2                   = $_REQUEST['per_price_2'];
 $per_qty_2                     = $_REQUEST['per_qty_2'];
 $per_total_2                   = $_REQUEST['per_total_2'];
 $per_price_3                   = $_REQUEST['per_price_3'];
 $per_qty_3                     = $_REQUEST['per_qty_3'];
 $per_total_3                   = $_REQUEST['per_total_3'];
 $per_price_4                   = $_REQUEST['per_price_4'];
 $per_qty_4                     = $_REQUEST['per_qty_4'];
 $per_total_4                   = $_REQUEST['per_total_4'];
 $per_price_5                   = $_REQUEST['per_price_5'];
 $per_qty_5                     = $_REQUEST['per_qty_5'];
 $per_total_5                   = $_REQUEST['per_total_5'];
 
 $ex_name_arr   = $_REQUEST['ex_name_arr'];
 $ex_price_arr   = $_REQUEST['ex_price_arr'];
 $ex_qty_arr   = $_REQUEST['ex_qty_arr'];
 $ex_total_arr   = $_REQUEST['ex_total_arr'];
 


$method         = strtoupper($_REQUEST['method']);

#set var
$set_data = array();


if($method != 'PUT'){
    $set_data = return_object(400);
}
else{

  
   // $sql_status = '`user_group_id` IN ('.implode(",",$sql_type).')';
   $sql_status = '';
    

    $sql = "INSERT INTO `booking`
                                (
                                 `book_code`,
                                 `book_date`,
                                 `invoice_code`,
                                 `invoice_date`,
                                 `agen_id`,
                                 `user_id`,
                                 `per_id`,
                                 `bus_no`,
                                 `book_total`,
                                 `book_discount`,
                                 `book_amountgrandtotal`,
                                 `book_comment`,
                                 `book_master_deposit`,
                                 `book_due_date_deposit`,
                                 `book_master_full_payment`,
                                 `book_due_date_full_payment`,
                                 `book_com_agency_company`,
                                 `book_com_agency`,
                                 `remark`,
                                 `book_room_twin`,
                                 `book_room_double`,
                                 `book_room_triple`,
                                 `book_room_single`,
                                 `create_user_id`,
                                 `create_date`,
                                 `status`
                                 ) VALUES (
                                 '$book_code',
                                 NOW() ,
                                 '$invoice_code',
                                 NOW() ,
                                 '$agen_id',
                                 '$user_id',
                                 '$per_id',
                                 '$bus_no',
                                 '$book_total',
                                 '$book_discount',
                                 '$book_amountgrandtotal',
                                 '$book_comment',
                                 '$book_master_deposit',
                                 '$book_due_date_deposit',
                                 '$book_master_full_payment',
                                 '$book_due_date_full_payment',
                                 '$book_com_agency_company',
                                 '$book_com_agency',
                                 '$remark',
                                 '$book_room_twin',
                                 '$book_room_double',
                                 '$book_room_triple',
                                 '$book_room_single',
                                 '$create_user_id',
                                 NOW() ,
                                 '$status'
                                 )";
    
    $result = mysqli_query($CON,$sql);
   

    $set_data['sql'] = $sql;

    if($result){
       $sql = "SELECT LAST_INSERT_ID() FROM `booking`";
        $result = mysqli_insert_id($CON); 

        // list 1
        $sql = "INSERT INTO `booking_list`              
                                (
                                 `book_list_code`,
                                 `book_list_name`,
                                 `book_list_price`,
                                 `book_list_qty`,
                                 `book_list_total`,
                                 `book_code`,
                                 `create_user_id`,
                                 `create_date`
                                 ) VALUES (
                                 '1',
                                 'Adult',
                                 '$per_price_1',
                                 '$per_qty_1',
                                 '$per_total_1',
                                 '$book_code',
                                 '$create_user_id',
                                 NOW() 
                                 )";
        $result2 = mysqli_query($CON,$sql);
        // list 2
        $sql = " INSERT INTO `booking_list`              
                                (
                                 `book_list_code`,
                                 `book_list_name`,
                                 `book_list_price`,
                                 `book_list_qty`,
                                 `book_list_total`,
                                 `book_code`,
                                 `create_user_id`,
                                 `create_date`
                                 ) VALUES (
                                 '2',
                                 'Child',
                                 '$per_price_2',
                                 '$per_qty_2',
                                 '$per_total_2',
                                 '$book_code',
                                 '$create_user_id',
                                 NOW() 
                                 )";
        $result2 = mysqli_query($CON,$sql);
           // list 3
        $sql = " INSERT INTO `booking_list`              
                                (
                                 `book_list_code`,
                                 `book_list_name`,
                                 `book_list_price`,
                                 `book_list_qty`,
                                 `book_list_total`,
                                 `book_code`,
                                 `create_user_id`,
                                 `create_date`
                                 ) VALUES (
                                 '3',
                                 'Child No bed',
                                 '$per_price_3',
                                 '$per_qty_3',
                                 '$per_total_3',
                                 '$book_code',
                                 '$create_user_id',
                                 NOW() 
                                 )";   
         $result2 = mysqli_query($CON,$sql);
           // list 4
        $sql = " INSERT INTO `booking_list`              
                                (
                                 `book_list_code`,
                                 `book_list_name`,
                                 `book_list_price`,
                                 `book_list_qty`,
                                 `book_list_total`,
                                 `book_code`,
                                 `create_user_id`,
                                 `create_date`
                                 ) VALUES (
                                 '4',
                                 'Infant',
                                 '$per_price_4',
                                 '$per_qty_4',
                                 '$per_total_4',
                                 '$book_code',
                                 '$create_user_id',
                                 NOW() 
                                 )"; 
         $result2 = mysqli_query($CON,$sql);
           // list 5
        $sql = " INSERT INTO `booking_list`              
                                (
                                 `book_list_code`,
                                 `book_list_name`,
                                 `book_list_price`,
                                 `book_list_qty`,
                                 `book_list_total`,
                                 `book_code`,
                                 `create_user_id`,
                                 `create_date`
                                 ) VALUES (
                                 '5',
                                 'Joinland',
                                 '$per_price_5',
                                 '$per_qty_5',
                                 '$per_total_5',
                                 '$book_code',
                                 '$create_user_id',
                                 NOW() 
                                 )"; 
        $result2 = mysqli_query($CON,$sql);
        if( !empty($ex_name_arr) ){
            $x = 6;
            for ( $i = 0;$i < count($ex_name_arr);$i++ ){
            $sql = "INSERT INTO `booking_list`              
                                (
                                 `book_list_code`,
                                 `book_list_name`,
                                 `book_list_price`,
                                 `book_list_qty`,
                                 `book_list_total`,
                                 `book_code`,
                                 `create_user_id`,
                                 `create_date`
                                 ) VALUES (
                                 '$x',
                                 '$ex_name_arr[$i]',
                                 '$ex_price_arr[$i]',
                                 '$ex_qty_arr[$i]',
                                 '$ex_total_arr[$i]',
                                 '$book_code',
                                 '$create_user_id',
                                 NOW() 
                                 )";
            $result2 = mysqli_query($CON,$sql);
            $x = $x + 1;

            }
        }

         if($result2){

            $set_data = return_object(200,$result);


         }else{
              $set_data = return_object(204,$sql);
         }
       
     
    }
    else{
        $set_data = return_object(204,$sql);
    }
}

$set_data['hello'] = 1;

/*echo '<pre>';
print_r($set_data);
return;*/
echo json_encode($set_data);

disconect_db();
