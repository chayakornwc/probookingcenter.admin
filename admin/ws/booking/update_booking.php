<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST


 $book_id               = $_REQUEST['book_id'];
 $book_code               = $_REQUEST['book_code'];
 $invoice_code               = $_REQUEST['invoice_code'];
 $inv_rev_no               = $_REQUEST['inv_rev_no'];

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
 $update_user_id        = $_REQUEST['update_user_id'];
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
   if ($inv_rev_no != 0){
    $inv = substr($invoice_code, 0, -3);
   }else{
    $inv = $invoice_code;
   }
    $inv_rev_no = $inv_rev_no + 1;
    $inv_rev =  $inv. '('.$inv_rev_no.')';
    $sql = "UPDATE `booking` SET
                                 `agen_id` = '$agen_id',
                                 `invoice_code` = '$inv_rev',
                                 `invoice_date` = NOW(),
                                 `inv_rev_no` = '$inv_rev_no',
                                 `user_id` = '$user_id',
                                 `per_id` = '$per_id',
                                 `bus_no` = '$bus_no',
                                 `book_total` = '$book_total',
                                 `book_discount` = '$book_discount',
                                 `book_amountgrandtotal` = '$book_amountgrandtotal',
                                 `book_comment` = '$book_comment',
                                 `book_master_deposit` = '$book_master_deposit',
                                 `book_due_date_deposit` = '$book_due_date_deposit',
                                 `book_master_full_payment` = '$book_master_full_payment',
                                 `book_due_date_full_payment` = '$book_due_date_full_payment',
                                 `book_com_agency_company` = '$book_com_agency_company',
                                 `book_com_agency` = '$book_com_agency',
                                 `remark` = '$remark',
                                 `book_room_twin` = '$book_room_twin',
                                 `book_room_double` = '$book_room_double',
                                 `book_room_triple` = '$book_room_triple',
                                 `book_room_single` = '$book_room_single',
                                 `update_user_id` = '$update_user_id',
                                 `update_date` = NOW()
                                 WHERE   `book_id` = $book_id
                                ";
    
    $result = mysqli_query($CON,$sql);
   

    $set_data['sql'] = $sql;

    if($result){
      

        // list 1
        $sql = "UPDATE `booking_list` SET             
                                 `book_list_price` = '$per_price_1',
                                 `book_list_qty` = '$per_qty_1',
                                 `book_list_total` = '$per_total_1',
                                 `update_user_id` = '$update_user_id',
                                 `update_date` = NOW()
                                 WHERE `book_code` = $book_code
                                 AND `book_list_code` = '1'
                                 ";
        $result = mysqli_query($CON,$sql);
        // list 2
       $sql = "UPDATE `booking_list` SET             
                                 `book_list_price` = '$per_price_2',
                                 `book_list_qty` = '$per_qty_2',
                                 `book_list_total` = '$per_total_2',
                                 `update_user_id` = '$update_user_id',
                                 `update_date` = NOW()
                                 WHERE `book_code` = $book_code
                                 AND `book_list_code` = '2'
                                 ";
        $result = mysqli_query($CON,$sql);
           // list 3
       $sql = "UPDATE `booking_list` SET             
                                 `book_list_price` = '$per_price_3',
                                 `book_list_qty` = '$per_qty_3',
                                 `book_list_total` = '$per_total_3',
                                 `update_user_id` = '$update_user_id',
                                 `update_date` = NOW()
                                 WHERE `book_code` = $book_code
                                 AND `book_list_code` = '3'
                                 ";
         $result = mysqli_query($CON,$sql);
           // list 4
       $sql = "UPDATE `booking_list` SET             
                                 `book_list_price` = '$per_price_4',
                                 `book_list_qty` = '$per_qty_4',
                                 `book_list_total` = '$per_total_4',
                                 `update_user_id` = '$update_user_id',
                                 `update_date` = NOW()
                                 WHERE `book_code` = $book_code
                                 AND `book_list_code` = '4'
                                 ";
         $result = mysqli_query($CON,$sql);
           // list 5
      $sql = "UPDATE `booking_list` SET             
                                 `book_list_price` = '$per_price_5',
                                 `book_list_qty` = '$per_qty_5',
                                 `book_list_total` = '$per_total_5',
                                 `update_user_id` = '$update_user_id',
                                 `update_date` = NOW()
                                 WHERE `book_code` = $book_code
                                 AND `book_list_code` = '5'
                                 ";
        $result = mysqli_query($CON,$sql);

        $sql = "DELETE FROM `booking_list` WHERE `book_list_code` NOT IN ('1','2','3','4','5')";

        

        $result = mysqli_query($CON,$sql);
          $x = 6;
         for ( $i = 0;$i < count($ex_price_arr);$i++ ){
           $sql = "INSERT INTO `booking_list`              
                                (
                                 `book_list_code`,
                                 `book_list_name`,
                                 `book_list_price`,
                                 `book_list_qty`,
                                 `book_list_total`,
                                 `book_code`,
                                 `create_user_id`,
                                 `update_user_id`,
                                 `create_date`,
                                 `update_date`
                                 ) VALUES (
                                 '$x',
                                 '$ex_name_arr[$i]',
                                 '$ex_price_arr[$i]',
                                 '$ex_qty_arr[$i]',
                                 '$ex_total_arr[$i]',
                                 $book_code,
                                 '$update_user_id',
                                 '$update_user_id',
                                 NOW(), 
                                 NOW() 
                                 )";
           $result = mysqli_query($CON,$sql);
           $x = $x + 1;

         }


         if($result){

            $set_data = return_object(200,$result);


         }else{
              $set_data = return_object(204);
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
