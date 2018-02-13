<?php
header('Content-Type: text/html; charset=utf-8');
require('../function.php');
# REQUEST


 $book_id          = $_REQUEST['book_id'];
 $status_cancel          = $_REQUEST['status_cancel'];
 $book_receipt          = $_REQUEST['book_receipt'];
 $book_cancel          = $_REQUEST['book_cancel'];
 $remark_cancel          = $_REQUEST['remark_cancel'];

                                   

$method         = strtoupper($_REQUEST['method']);

#set var
$set_data = array();


if($method != 'PUT'){
    $set_data = return_object(400);
}
else{

  
   // $sql_status = '`user_group_id` IN ('.implode(",",$sql_type).')';
   $sql_status = '';
    

    $sql = "UPDATE `booking` SET
                                 `status` = '40',
                                 `status_cancel` = '$status_cancel',
                                 `book_receipt` = '$book_receipt',
                                 `book_cancel` = '$book_cancel',
                                 `remark_cancel` = '$remark_cancel'
                                 WHERE `book_id` = $book_id
                                ";
    
    $result = mysqli_query($CON,$sql);

    /* AUTO UPDATE WATIING LIST */
   ////////////----------------------------------------------///////////////
   /* GET PERIOD ID */
    $_sql = "SELECT per_id FROM booking WHERE `book_id`={$book_id}";
    $_query = mysqli_query($CON,$_sql);
    $_rs = mysqli_fetch_assoc($_query);

    /* GET Waiting List */
    $w_sql = "SELECT book_id,user_id,COALESCE(SUM(booking_list.book_list_qty)) AS qty FROM booking LEFT JOIN booking_list ON booking.book_code=booking_list.book_code WHERE per_id={$_rs["per_id"]} AND status=5 ORDER BY booking.create_date ASC";
    $w_query = mysqli_query($CON, $w_sql);
    $w_numRow = mysqli_num_rows($w_query);
    if( $w_numRow > 0 ){
        /* จำนวนที่นั่งทั้งหมด */
        $p_sql = "SELECT per_qty_seats FROM period WHERE per_id={$_rs["per_id"]} LIMIT 1";
        $p_query = mysqli_query($CON, $p_sql);
        $p_rs = mysqli_fetch_assoc($p_query);

        /* จำนวนคนจองทั้งหมด (ตัด Waiting กับ ยกเลิกแล้ว) */
        $s_sql = "SELECT COALESCE(SUM(booking_list.book_list_qty),0) as qty FROM booking_list
                    LEFT JOIN booking ON booking_list.book_code=booking.book_code
                  WHERE booking.per_id={$_rs["per_id"]} AND booking.status!=5 AND booking.status!=40";
        $s_query = mysqli_query($CON, $s_sql);
        $s_rs = mysqli_fetch_assoc($s_query);

        $BalanceSeats = $p_rs["per_qty_seats"] - $s_rs["qty"]; // จำนวนคงเหลือ
        if( $BalanceSeats > 0 ){
            while($w_rs = mysqli_fetch_assoc($w_query)){
                if( !empty($BalanceSeats) ){
                    if( $w_rs["qty"] <= $BalanceSeats ){
                        $up_book = "UPDATE booking SET status='00' WHERE book_id={$w_rs["book_id"]}";
                        mysqli_query($CON, $up_book);
                        $BalanceSeats -= $w_rs["qty"];
                    }
                    else{
                        if( $BalanceSeats > 0 ){
                            mysqli_query($CON,"UPDATE booking SET status='50' WHERE book_id={$w_rs["book_id"]}");
                            
                            $alert = "INSERT INTO alert_msg 
                                                (user_id,book_id,detail,source,log_date) 
                                      VALUE ('{$w_rs["user_id"]}','{$w_rs["book_id"]}','ที่นั่งไม่เพียงพอ','150booking',NOW())";
                            mysqli_query($CON,$alert);

                            $BalanceSeats = 0;
                        }
                    }
                }
            }
        }
    }
    ////////////----------------------------------------------///////////////

    $set_data['sql'] = $sql;

    if($result){
      
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
