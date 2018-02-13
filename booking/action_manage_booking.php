<?php
#include
include_once('../includes/main_core.php');
include_once('../admin/unity/post_to_ws/post_to_ws.php');
include_once('../admin/unity/php_script.php');

include_once('../admin/unity/php_send_email_agen.php');
#REQUEST

#SET DATA RETURN

$set_data = array();


#METHOD

if($_REQUEST['method'] == 1){//select list

    #WS
    $wsserver   = URL_WS;
    $wsfolder	= '/f_agen'; //กำหนด Folder
    $wsfile		= '/select_list_booking.php'; //กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(    

                                'word_search'   => $_REQUEST['word_search'],
                                'status_0'      => $_REQUEST['status_0'],
                                'status_10'      => $_REQUEST['status_10'],
                                'status_20'      => $_REQUEST['status_20'],
                                'status_25'      => $_REQUEST['status_25'],
                                'status_30'      => $_REQUEST['status_30'],
                                'status_35'      => $_REQUEST['status_35'],
                                'status_40'      => $_REQUEST['status_40'],
                                'date_start'    => Y_m_d($_REQUEST['date_start']),
                                'date_end'      => Y_m_d($_REQUEST['date_end']),

                                'agen_id'       => $_SESSION['login']['agen_id'],

                                'offset'        => $_REQUEST['offset'],
                                'limit'         => LIMIT,
                                'method'        => 'GET'
                            );

    
 
    $data_return =	json_decode( post_to_ws($url,$data),true );
    
    $set_data['all_row'] = intval($data_return['all_row']);
    $set_data['paginate_list'] = paginate_list( intval($data_return['all_row']) , $_REQUEST['offset'] , LIMIT ); // set page


    $thead = '<thead>
				<tr>
                                <th  width="5%">
                                    #
                                </th>
                                <th>สถานะ</th>
                                <th>Booking No.</th>
                                <th>รหัส ซีรีย์</th>
                                <th>Period</th>
                                <th>จำนวนคน</th>
                                <th>ยอดรวม</th>
                                <th class="bg-info-light">ส่วนลด</th>
                                <th class="bg-success-light">ยอดสุทธิ</th>
                                <th>วันที่จอง</th>
                                <th class="bg-danger-light">วันหมดอายุ</th>
                                <th>Sales contact</th>
                                <th></th>
                            </tr>
			</thead>';

    if($data_return['status'] != 200){
        if($data_return['status']==503){
            $non_txt = 'เกิดข้อผิดพลาดระหว่างการเข้าถึง เซิร์ฟเวอร์';
        }
        else if($data_return['status'] == 204){
            $non_txt = 'ไม่พบข้อมูลที่ค้นหา';
        }
        else{
            $non_txt = 'การเชื่อมต่อเซิฟเวอร์ผิดพลาด';
        }
        $tbody = '<tbody><tr>
                        <td colspan="20" style="text-align:center">
                            <div class="alert alert-warning" role="alert">
                            <font style="font-size:25px">'.$non_txt.'</font>
                            </div>
                        </td>
                    </tr>
                </tbody>';
    }
    else{

        $count		= ($_REQUEST['offset']*LIMIT)+1;
        $result     = $data_return['results'];
        $tr         = '';
        
        foreach($result as $key => $value){

        

            $td = '<td class="text-center">'.number_format($count++).'</td>';
            $td .= '<td class="text-center">'.html_status_book($value['status']).'</td>';
            $td .= '<td class="text-center">'.$value['book_code'].'</td>';
            $td .= '<td class="text-center">'.$value['ser_code'].'</td>';
            $td .= '<td class="text-center">'.thai_date_short(strtotime($value['per_date_start'])).' - '.thai_date_short(strtotime($value['per_date_end'])).'</td>';
            $td .= '<td class="text-right">'.number_format(intval($value['QTY'])).'</td>';
            $td .= '<td class="text-right">'.number_format(intval($value['book_total'])).'</td>';
            $td .= '<td class="text-right bg-info-light">'.number_format(intval($value['book_discount'])).'</td>';
            $td .= '<td class="text-right bg-success-light">'.number_format(intval($value['book_amountgrandtotal'])).'</td>';
            $td .= '<td class="text-center">'.thai_date_short(strtotime($value['book_date'])).'</td>';
            $td .= '<td class="text-center bg-danger-light">'.thai_date_short(strtotime($value['book_due_date_deposit'])).'</td>';
            $td .= '<td class="text-center">'.$value['user_name'].'</td>';
            $td .= ' <td class="text-center">
                                    <button type="button" class="mb-sm btn btn-primary btn-xs" onclick="location.href=\'../booking/booking_detail.php?book_id='.$value['book_id'].'&book_code='.$value['book_code'].'\'">
                                        <em class="icon-note"></em>
                                        จัดการ
                                    </button>
                                </td>';
            

            $tr .= '<tr>'.$td.'</tr>';
        }
        $tbody = '<tbody>'.$tr.'</tbody>';

    }


    $set_data['interface_table'] = '<table id="" class="table table-bordered table-hover table-devgun">
                '.$thead.'
                '.$tbody.'
                </table>
            ';


    echo json_encode($set_data);




}
else if($_REQUEST['method'] == 2){ // select period by bus no

    #REQUEST

     #WS
    $wsserver   = URL_WS;
    $wsfolder	= '/booking'; //กำหนด Folder
    $wsfile		= '/select_qty_receipt_by_bus.php'; //กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(    
                                'per_id' => $_REQUEST['per_id'],
                                'bus_no' => $_REQUEST['bus_no'],
                                'method'        => 'GET'
                            );

 
    $data_return =	json_decode( post_to_ws($url,$data),true );

    $qty_receipt = 0;
    if($data_return['status'] == 200){
        $qty_receipt = intval($data_return['results'][0]['qty_receipt']);
    }

    $set_data['qty_receipt'] = $qty_receipt;

    echo json_encode($set_data);

}
else if($_REQUEST['method'] == 3){ // insert booking

    $wsserver   = URL_WS;
    $wsfolder	= '/booking'; //กำหนด Folder
    $wsfile		= '/get_prefixnumber.php';
     //กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(    
                                'method'        => 'GET'
                            );
    $data_return =	json_decode( post_to_ws($url,$data),true );

    $pre_booking = 0;
    $pre_invoice = 0;
    $pre_month = '';
    $pre_year = '';
    if($data_return['status'] == 200){
        $pre_booking = intval($data_return['results'][0]['pre_booking']);
        $pre_invoice = intval($data_return['results'][0]['pre_invoice']);
        $pre_year = $data_return['results'][0]['pre_year'];
        $pre_month = $data_return['results'][0]['pre_month'];
    }
    
    $running_booking = sprintf("%04s", $pre_booking);
    $running_invoice = sprintf("%04s", $pre_invoice);

    $book_code = 'B'.$pre_year.'/'.$pre_month.$running_booking;
    $invoice_code = 'I'.$pre_year.'/'.$pre_month.$running_invoice;    


    $book_due_date_full_payment = '';
    $book_due_date_deposit = '';
    if($_REQUEST['book_due_date_full_payment'] == ''){
            $book_due_date_full_payment = 'null';
    }else{
            $book_due_date_full_payment = Y_m_d($_REQUEST['book_due_date_full_payment']);
    }
   if($_REQUEST['book_due_date_deposit'] == ''){
            $book_due_date_deposit = 'null';
    }else{
            $book_due_date_deposit = Y_m_d($_REQUEST['book_due_date_deposit']);
    }
    $wsserver   = URL_WS;
    $wsfolder	= '/booking'; //กำหนด Folder
    $wsfile		= '/insert_booking.php'; //กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(    

                                'book_code'                 => $book_code,
                                'invoice_code'              => $invoice_code,
                                'agen_id'                   => $_SESSION['login']['agen_id'],
                                'user_id'                   => $_REQUEST['user_id'],
                                'per_id'                    => $_REQUEST['per_id'],
                                'bus_no'                    => $_REQUEST['bus_no'],
                                'book_total'                => $_REQUEST['book_total'],
                                'book_discount'             => $_REQUEST['book_discount'],
                                'book_amountgrandtotal'     => $_REQUEST['book_amountgrandtotal'],
                                'book_comment'              => $_REQUEST['book_comment'],
                                'book_master_deposit'       => $_REQUEST['book_master_deposit'],
                                'book_due_date_deposit'     => $book_due_date_deposit,
                                'book_master_full_payment'          => $_REQUEST['book_master_full_payment'],
                                'book_due_date_full_payment'        => $book_due_date_full_payment,
                                'book_com_agency_company'           => $_REQUEST['book_com_agency_company'],
                                'book_com_agency'           => $_REQUEST['book_com_agency'],
                                'remark'                    => $_REQUEST['remark'],
                                'book_room_twin'            => $_REQUEST['book_room_twin'],
                                'book_room_double'          => $_REQUEST['book_room_double'],
                                'book_room_triple'          => $_REQUEST['book_room_triple'],
                                'book_room_single'          => $_REQUEST['book_room_single'],


                                'per_price_1'           => $_REQUEST['per_price_1'],
                                'per_qty_1'             => $_REQUEST['per_qty_1'],
                                'per_total_1'           => $_REQUEST['per_total_1'],
                                'per_price_2'           => $_REQUEST['per_price_2'],
                                'per_qty_2'             => $_REQUEST['per_qty_2'],
                                'per_total_2'           => $_REQUEST['per_total_2'],
                                'per_price_3'           => $_REQUEST['per_price_3'],
                                'per_qty_3'             => $_REQUEST['per_qty_3'],
                                'per_total_3'           => $_REQUEST['per_total_3'],
                                'per_price_4'           => $_REQUEST['per_price_4'],
                                'per_qty_4'             => $_REQUEST['per_qty_4'],
                                'per_total_4'           => $_REQUEST['per_total_4'],
                                'per_price_5'           => $_REQUEST['per_price_5'],
                                'per_qty_5'             => $_REQUEST['per_qty_5'],
                                'per_total_5'           => $_REQUEST['per_total_5'],

                                'ex_name_arr'           => $_REQUEST['ex_name_arr'],
                                'ex_price_arr'          => $_REQUEST['ex_price_arr'],
                                'ex_qty_arr'            => $_REQUEST['ex_qty_arr'],
                                'ex_total_arr'          => $_REQUEST['ex_total_arr'],
                                

                                'create_user_id'        => $_SESSION['login']['agen_id'],
                              
                                'status'                    => 0,
                                'method'                    => 'PUT'
                            );
   
    $data_return =	json_decode( post_to_ws($url,$data),true );
    $set_data['book_id']         = $data_return['results'];
    $set_data['status']          = $data_return['status'];
    $set_data['type_alert']      = 1;
  

    if($data_return['status'] == 200){
         // update prefix
    $wsserver   = URL_WS;                   # ../unity/post_to_ws/config.php
    $wsfolder	= '/perfixnumber';      # กำหนด Folder
    $wsfile		= '/update_prefixnumber.php';     # กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(
                            'source'                 => 'insert',
                            'method'            => 'PUT'
                            );

    $data_return =	json_decode( post_to_ws($url,$data),true );  
 
             // insert_alert
             // source  100booking = insert booking
             //         101payment = insert payment
             //         102app_payment = approve payment
             //         103cxl_payment = not approve payment

    $wsserver   = URL_WS;                   # ../unity/post_to_ws/config.php
    $wsfolder	= '/alert_msg';      # กำหนด Folder
    $wsfile		= '/insert_alert.php';     # กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(
                            'book_id'                 => $set_data['book_id'],
                            'detail'                 => 'จองทัวร์ BN : ',
                            'source'                 => '100booking',
                            'pay_id'                 => 0,
                            'user_id'                 => $_REQUEST['user_id'],
                            'method'            => 'PUT'
                            );
      $data_return =	json_decode( post_to_ws($url,$data),true );                       

        $wsserver   = URL_WS;
        $wsfolder	= '/booking'; //กำหนด Folder
        $wsfile		= '/select_book_by_id.php'; //กำหนด File
        $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
        $data 		= array(    

                                    'book_id'                 => $set_data['book_id'],
                                    'method'                  => 'GET'
                                );
    
        $data_return =	json_decode( post_to_ws($url,$data),true );
        
        if($data_return['status'] == 200){
            
    
            $result     = $data_return['results'][0];
            $book_code  =  $result['book_code'];

          send_email_booking($book_code);

        }

    }


   echo json_encode($set_data);


}    
else if($_REQUEST['method'] == 4){ // update booking

    $book_due_date_full_payment = '';
    if($_REQUEST['book_due_date_full_payment'] == ''){
            $book_due_date_full_payment = 'null';
    }else{
            $book_due_date_full_payment = Y_m_d($_REQUEST['book_due_date_full_payment']);
    }

    $wsserver   = URL_WS;
    $wsfolder	= '/booking'; //กำหนด Folder
    $wsfile		= '/update_booking.php'; //กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(    

                                'book_id'                 => $_REQUEST['book_id'],
                                'book_code'                 => $_REQUEST['book_code'],
                                'invoice_code'              => $_REQUEST['invoice_code'],
                                'inv_rev_no'              => $_REQUEST['inv_rev_no'],
                                'agen_id'                   => $_SESSION['login']['agen_id'],
                                'user_id'                   => $_REQUEST['user_id'],
                                'per_id'                    => $_REQUEST['per_id'],
                                'bus_no'                    => $_REQUEST['bus_no'],
                                'book_total'                => $_REQUEST['book_total'],
                                'book_discount'             => $_REQUEST['book_discount'],
                                'book_amountgrandtotal'     => $_REQUEST['book_amountgrandtotal'],
                                'book_comment'              => $_REQUEST['book_comment'],
                                'book_master_deposit'       => $_REQUEST['book_master_deposit'],
                                'book_due_date_deposit'     => Y_m_d($_REQUEST['book_due_date_deposit']),
                                'book_master_full_payment'          => $_REQUEST['book_master_full_payment'],
                                'book_due_date_full_payment'        => $book_due_date_full_payment,
                                'book_com_agency_company'           => $_REQUEST['book_com_agency_company'],
                                'book_com_agency'           => $_REQUEST['book_com_agency'],
                                'remark'                    => $_REQUEST['remark'],
                                'book_room_twin'            => $_REQUEST['book_room_twin'],
                                'book_room_double'          => $_REQUEST['book_room_double'],
                                'book_room_triple'          => $_REQUEST['book_room_triple'],
                                'book_room_single'          => $_REQUEST['book_room_single'],


                                'per_price_1'           => $_REQUEST['per_price_1'],
                                'per_qty_1'             => $_REQUEST['per_qty_1'],
                                'per_total_1'           => $_REQUEST['per_total_1'],
                                'per_price_2'           => $_REQUEST['per_price_2'],
                                'per_qty_2'             => $_REQUEST['per_qty_2'],
                                'per_total_2'           => $_REQUEST['per_total_2'],
                                'per_price_3'           => $_REQUEST['per_price_3'],
                                'per_qty_3'             => $_REQUEST['per_qty_3'],
                                'per_total_3'           => $_REQUEST['per_total_3'],
                                'per_price_4'           => $_REQUEST['per_price_4'],
                                'per_qty_4'             => $_REQUEST['per_qty_4'],
                                'per_total_4'           => $_REQUEST['per_total_4'],
                                'per_price_5'           => $_REQUEST['per_price_5'],
                                'per_qty_5'             => $_REQUEST['per_qty_5'],
                                'per_total_5'           => $_REQUEST['per_total_5'],

                                'ex_name_arr'           => $_REQUEST['ex_name_arr'],
                                'ex_price_arr'          => $_REQUEST['ex_price_arr'],
                                'ex_qty_arr'            => $_REQUEST['ex_qty_arr'],
                                'ex_total_arr'          => $_REQUEST['ex_total_arr'],
                                
                                'update_user_id'        => $_SESSION['login']['agen_id'],
                              
                                //'status'                    => 0,
                                'method'                    => 'PUT'
                            );
   
   $data_return =	json_decode( post_to_ws($url,$data),true );
  
   $set_data['data_return']    = $data_return;
   $set_data['status']         = $data_return['status'];
   $set_data['type_alert']     = 2;

   echo json_encode($set_data);



}  
else if($_REQUEST['method'] == 5){
         #WS
    $wsserver   = URL_WS;
    $wsfolder	= '/booking'; //กำหนด Folder
    $wsfile		= '/select_list_payment.php'; //กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(    

                                'book_id'   => $_REQUEST['book_id'],

                                'offset'        => $_REQUEST['offset'],
                                'limit'         => LIMIT,
                                'method'        => 'GET'
                            );

 
    $data_return =	json_decode( post_to_ws($url,$data),true );
    
    $set_data['all_row'] = intval($data_return['all_row']);
    $set_data['paginate_list'] = paginate_list( intval($data_return['all_row']) , $_REQUEST['offset'] , LIMIT ); // set page


    $thead = '<thead>
				<tr>
                                <th  width="5%">
                                    #
                                </th>
                                <th>สถานะ</th>
                                <th>ไฟล์อ้างอิง</th>
                                <th>ธนาคาร</th>
                                <th>สาขา</th>
                                <th>ชื่อบัญชี</th>
                                <th>เลขที่บัญชี</th>
                                <th class="bg-success-dark">จำนวนเงิน</th>
                                <th>วันที่โอน</th>
                                <th>เวลาที่โอน</th>
                                <th>ผู้ทำรายการ</th>
                                <th>วันที่ทำรายการ</th>
                                <th>สถานะการชำระเงิน</th>
                                <th></th>
                            </tr>
			</thead>';

    if($data_return['status'] != 200){
        if($data_return['status']==503){
            $non_txt = 'เกิดข้อผิดพลาดระหว่างการเข้าถึง เซิร์ฟเวอร์';
        }
        else if($data_return['status'] == 204){
            $non_txt = 'ไม่พบข้อมูลที่ค้นหา';
        }
        else{
            $non_txt = 'การเชื่อมต่อเซิฟเวอร์ผิดพลาด';
        }
        $tbody = '<tbody><tr>
                        <td colspan="20" style="text-align:center">
                            <div class="alert alert-warning" role="alert">
                            <font style="font-size:25px">'.$non_txt.'</font>
                            </div>
                        </td>
                    </tr>
                </tbody>';
    }
    else{

        $count		= ($_REQUEST['offset']*LIMIT)+1;
        $result     = $data_return['results'];
        $tr         = '';
        
        foreach($result as $key => $value){

            $url_img_1 = 'disabled';
            $str = str_replace('../','',$value['pay_url_file']);
            $pay_url_file = '../admin/'.$str;
            if(is_file($pay_url_file)){
                $url_img_1 = 'href="'.$pay_url_file.'"';
            }
            $img_1 = '<a '.$url_img_1.'  target="_blank">FILE</a>';

            $btnapprove = '';
            
                if ($value['status'] == 1){
                    $disabled_edit = 'disabled';
                
                }
            
            $td = '<td class="text-center">'.number_format($count++).'</td>';
            $td .= '<td class="text-center">'.html_status_payment($value['status']).'</td>';
            $td .= '<td class="text-center">'.$img_1.'</td>';
            $td .= '<td class="text-center">'.$value['bank_name'].'</td>';
            $td .= '<td class="text-center">'.$value['bankbook_branch'].'</td>';
            $td .= '<td class="text-center">'.$value['bankbook_name'].'</td>';
            $td .= '<td class="text-center">'.$value['bankbook_code'].'</td>';
            $td .= '<td class="text-right bg-success-dark">'.number_format(intval($value['pay_received'])).'</td>';
            $td .= '<td class="text-center">'.d_m_y($value['pay_date']).'</td>';
            $td .= '<td class="text-center">'.$value['pay_time'].'</td>';
            $td .= '<td class="text-center">'.$value['action_name'].'</td>';
            $td .= '<td class="text-center">'.d_m_y($value['create_date']).'</td>';
            $td .= '<td class="text-center">'.html_status_book($value['book_status']).'</td>';
  
            $td .= ' <td class="text-center">
                                                <button type="button" class="mb-sm btn btn-primary btn-xs" onclick="manage_transactions_pay(\'update\','.$value['pay_id'].')" '.$disabled_edit.' >
                                                    <em class="icon-note"></em>
                                                    แก้ไข
                                                </button>
                                                
                                            </td>
                                          
                                    ';
            

            $tr .= '<tr>'.$td.'</tr>';
        }
        $tbody = '<tbody>'.$tr.'</tbody>';

    }


    $set_data['interface_table'] = '<table id="" class="table table-bordered table-hover table-devgun">
                '.$thead.'
                '.$tbody.'
                </table>
            ';


    echo json_encode($set_data);
   


}
else if($_REQUEST['method'] == 6){
  #REQUEST

    $action_method = $_REQUEST['action_method'];

    if($action_method == 'add'){

        $title_modal            = 'เพิ่ม';
        $bankbook_id            = '';
        $pay_date               = '';
        $pay_time               = '';
        $pay_received           = '';
        $pay_url_file           = 'url="" disabled';
        $status                 = '';
        $book_status            = '';
        $bank_name            = '';
        $bankbook_code            = '';
        $bankbook_name            = '';
        $bankbook_branch            = '';

        $method                 = 7;

        $status_1 = '';
        $status_2 = '';
        $status_3 = '';
        $status_4 = '';
        $btncancel = '';
    }
    else{
        
          
        $title_modal        = 'แก้ไข';
        $bankbook_id            = '';
        $pay_date               = '';
        $pay_time               = '';
        $pay_received           = '';
        $pay_url_file           = '';
        $status                 = '';
        $book_status            = '';
        $bank_name            = '';
        $bankbook_code            = '';
        $bankbook_name            = '';
        $bankbook_branch            = '';

        $method             = 8;

     
        


        #WS
        $wsserver   = URL_WS;
        $wsfolder	= '/booking'; //กำหนด Folder
        $wsfile		= '/select_list_payment_by_id.php'; //กำหนด File
        $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
        $data 		= array(    

                                    'pay_id'       => $_REQUEST['pay_id'],
                                    'method'        => 'GET'
                                );

        $data_return =	json_decode( post_to_ws($url,$data),true );

        $div_remark_cancel = '';                       
        
        if($data_return['status'] == 200){

            $result = $data_return['results'][0];
            $bankbook_id  = $result['bankbook_id'];
            $pay_date  = d_m_y($result['pay_date']);
            $pay_time = $result['pay_time'];
            $pay_received = intval($result['pay_received']);
            $status = $result['status'];
            $book_status   = $result['book_status'];
            $bank_name   = $result['bank_name'];
            $bankbook_code   = $result['bankbook_code'];
            $bankbook_name   = $result['bankbook_name'];
            $bankbook_branch   = $result['bankbook_branch'];
            $remark   = $result['remark'];
            $remark_cancel   = $result['remark_cancel'];
            $status_1 = '';
            $status_2 = '';
            $status_3 = '';
            $status_4 = '';

            if($result['book_status'] == 20){

                $status_1 = 'checked';

            }
            else if($result['book_status'] == 25){

                $status_2 = 'checked';

            }else if($result['book_status'] == 30){

                $status_3 = 'checked';

            }else if($result['book_status'] == 35){

                $status_4 = 'checked';

            }
            if(is_file($result['pay_url_file'])){
                $pay_url_file = 'href="'.$result['pay_url_file'].'" url="'.$result['pay_url_file'].'"';
            }
            else{
                $pay_url_file = 'href="javascript:;" url="" disabled';
            }

            if ($status == 9){
                $div_remark_cancel = ' <div class="form-group">
                                            <font class="col-lg-3 text-right" color="red" style="font-size:15px" >ไม่ผ่านการอนุมัติ :</font>
                                            <div class="col-lg-7">
                                                <textarea rows="5" cols="" class="form-control" id ="txtremark_payment_cancel" name = "txtremark_payment_cancel" readonly >'.$remark_cancel.'</textarea>
                                            </div>
                                        </div>
                                    ';
            }
  

        }
        $btncancel = ' 
                     <button type="button" class="pull-left btn btn-danger btn-sm" onclick="manage_cancel_payment('.$_REQUEST['pay_id'].')">
                                    ยกเลิกรายการ
                        </button>';

    }
   
       #get bankbook
    $wsserver   = URL_WS;
    $wsfolder	= '/booking'; //กำหนด Folder
    $wsfile		= '/get_bankbook.php'; //กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(    
                                'method'        => 'GET'
                            );

 
    $data_return =	json_decode( post_to_ws($url,$data),true );


    if($data_return['status'] == 200){

        $result = $data_return['results'];
        $option_bankbook = '';

        foreach($result as $key => $value){

            $selected_bankbook = '';
            if($bankbook_id == $value['bankbook_id']){
                $selected_bankbook = 'selected';
            }

            $option_bankbook .= '<option value="'.$value['bankbook_id'].'" '.$selected_bankbook.'>'.$value['bank_name'].'</option>';
        }

    }
    else{
        $option_bankbook = '<option value="">ไม่พบข้อมูล</option>';
    }



    $set_data['modal'] = '
       <div id="modal-manage-transactions-pay" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <button type="button" data-dismiss="modal" aria-label="Close" class="close">
                    <span aria-hidden="true">&times;</span>
                </button>
                
                <h4 id="myModalLabel" class="modal-title">'.$title_modal.' รายการ</h4>
                </div>
                <div class="modal-body">
                      <input type="hidden" value="'.$_REQUEST['pay_id'].'" name="pay_id" id="pay_id">
                    <div class="form-horizontal">

                        <div class="form-group">
                            <label class="col-lg-3  control-label">ธนาคาร* :</label>
                            <div class="col-lg-7 ">
                                <select name="select_bankbook" id="select_bankbook" class="chosen-select form-control" onchange="get_bankbook($(this).val())">
                                                        '.$option_bankbook.'
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3  control-label">สาขา :</label>
                            <div class="col-lg-7 ">
                                <input type="text" class="form-control" id="bankbook_branch" name="bankbook_branch"  value="'.$bankbook_branch.'" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3  control-label">ชื่อบัญชี :</label>
                            <div class="col-lg-7 ">
                                <input type="text" class="form-control" id="bankbook_name" name="bankbook_name" value="'.$bankbook_name.'" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3  control-label">เลขที่บัญชี :</label>
                            <div class="col-lg-7 ">
                                <input type="text" class="form-control" id="bankbook_code" name="bankbook_code" value="'.$bankbook_code.'" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3  control-label">วันที่ชำระเงิน :</label>
                            <div class="col-lg-7 ">
                                <input type="text" class="form-control  date period_requried" id="pay_date" name="pay_date" value="'.$pay_date.'">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3  control-label">เวลา :</label>
                            <div class="col-lg-7 ">
                                <input type="text" class="form-control" id="pay_time" name="pay_time" value="'.$pay_time.'">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">จำนวนเงินที่ได้รับ :</label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control input_num text-right" id="pay_received" name="pay_received" value="'.$pay_received.'">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">ไฟล์อ้างอิง :</label>
                            <div class="col-lg-7">
                                                    <div class="input-group">

                                                        <input type="file" data-classbutton="btn btn-default" accept="image/*" id="pay_url_file" name="pay_url_file" data-classinput="form-control inline" class="form-control filestyle">
                                                        <span class="input-group-btn">
                                                            <a id="pay_url_file_old" class="btn btn-default btn-primary" target="_blank" '.$pay_url_file.'>ดาวน์โหลด</a>
                                                        </span>
                                                    </div>
                            </div>
                        </div>
                        <div class="form-group">
                        <label class="col-lg-3 control-label">หมายเหตุ :</label>
                        <div class="col-lg-7">
                            <textarea rows="5" cols="" class="form-control" id ="txtremark_payment" name = "txtremark_payment" >'.$remark.'</textarea>
                        </div>
                    </div>
                    '.$div_remark_cancel.'
                    <hr>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">สถานะ Booking </label>
                            <div class="col-lg-7">
                                <div class="radio c-radio">
                                    <label>
                                        <input type="radio" name="status_booking" value="20" '.$status_1.'>
                                        <span class="fa fa-circle"></span>ชำระ มัดจำ(Deposite)บางส่วน</label>
                                </div>
                                <div class="radio c-radio">
                                    <label>
                                        <input type="radio" name="status_booking" value="25" '.$status_2.'>
                                        <span class="fa fa-circle"></span>ชำระ มัดจำ(Deposite)เต็มจำนวน</label>
                                </div>
                                <div class="radio c-radio">
                                    <label>
                                        <input type="radio" name="status_booking" value="30" '.$status_3.'>
                                        <span class="fa fa-circle"></span>ชำระ เต็มจำนวน(Full payment)บางส่วน</label>
                                </div>
                                <div class="radio c-radio">
                                    <label>
                                        <input type="radio" name="status_booking" value="35" '.$status_4.'>
                                        <span class="fa fa-circle"></span>ชำระ เต็มจำนวน(Full payment)เต็มจำนวน</label>
                                </div>
                                
                            </div>
                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                '.$btncancel.'
               
                    <button type="button" data-dismiss="modal" class="btn btn-default">ปิด</button>
                    <button type="button" class="btn btn-primary" onclick="check_submit_payment('.$method.')">
                        <em class="icon-cursor"></em>
                        บันทีก
                    </button>
                </div>

            </div>
        </div>
    </div>
    
    
    ';


    echo json_encode($set_data);
}
else if($_REQUEST['method'] == 7){ // insert payment
    #WS
    $wsserver   =   URL_WS;                   # ../unity/post_to_ws/config.php
    $wsfolder	=   '/booking';      # กำหนด Folder
    $wsfile		=	'/insert_payment.php';     # กำหนด File
    $url 		=	$wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		=	array(  
                                'pay_date'                  => Y_m_d($_REQUEST['pay_date']),
                                'pay_time'                  => $_REQUEST['pay_time'],
                                'pay_url_file'              => $_REQUEST['pay_url_file'],
                                'pay_received'              =>  $_REQUEST['pay_received'],
                                'book_id'                   => $_REQUEST['book_id'],
                                'book_status'               => $_REQUEST['book_status'],
                                'bankbook_id'               => $_REQUEST['bankbook_id'],
                                'create_user_id'            => $_SESSION['login']['agen_id'],
                                'user_action'               => $_SESSION['login']['agen_user_name'],
                                'remark'              =>  $_REQUEST['remark'],
                                'status'                    => 0,
                                'method'                    => 'PUT'
                            );
                            $data_return =	json_decode( post_to_ws($url,$data),true );
                            $set_data['pay_id']         = $data_return['results'];
                            $set_data['data_return']    = $data_return;
                            $set_data['status']         = $data_return['status'];
                            $set_data['type_alert']     = 1;
                              // insert_alert
                                     // source  100booking = insert booking
                                     //         101payment = insert payment
                                     //         102app_payment = approve payment
                                     //         103cxl_payment = not approve payment
                        
                         $wsserver   = URL_WS;                   # ../unity/post_to_ws/config.php
                            $wsfolder	= '/alert_msg';      # กำหนด Folder
                            $wsfile		= '/insert_alert.php';     # กำหนด File
                            $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
                            $data 		= array(
                                                    'book_id'                 => $_REQUEST['book_id'],
                                                    'detail'                 => 'แจ้งชำระเงิน BN : ',
                                                    'source'                 => '101payment',
                                                    'pay_id'                 =>  $set_data['pay_id'],
                                                    'user_id'                 =>  $_REQUEST['user_id'],
                                                    'method'            => 'PUT'
                                                    );
                              $data_return =	json_decode( post_to_ws($url,$data),true );                  
                        
                            echo json_encode($set_data);
                            
}
else if($_REQUEST['method'] == 8){ // update payment
 #WS
    $wsserver   =   URL_WS;                   # ../unity/post_to_ws/config.php
    $wsfolder	=   '/booking';      # กำหนด Folder
    $wsfile		=	'/update_payment.php';     # กำหนด File
    $url 		=	$wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		=	array(  
                                'pay_id'                    => $_REQUEST['pay_id'],
                                'pay_date'                  => Y_m_d($_REQUEST['pay_date']),
                                'pay_time'                  => $_REQUEST['pay_time'],
                                'pay_url_file'              => $_REQUEST['pay_url_file'],
                                'pay_received'              =>  $_REQUEST['pay_received'],
                                'book_id'                   => $_REQUEST['book_id'],
                                'book_status'               => $_REQUEST['book_status'],
                                'bankbook_id'               => $_REQUEST['bankbook_id'],
                                'update_user_id'            => $_SESSION['login']['agen_id'],
                                'user_action'               => $_SESSION['login']['agen_user_name'],
                                'remark'              =>  $_REQUEST['remark'],
                                
                                'status'                    => 0,
                                'method'                    => 'PUT'
                            );

                            $data_return =	json_decode( post_to_ws($url,$data),true );
                            
                                $set_data['data_return']    = $data_return;
                                $set_data['status']         = $data_return['status'];
                                $set_data['type_alert']     = 2;
                            
                               $wsserver   = URL_WS;                   # ../unity/post_to_ws/config.php
                                $wsfolder	= '/alert_msg';      # กำหนด Folder
                                $wsfile		= '/insert_alert.php';     # กำหนด File
                                $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
                                $data 		= array(
                                                        'book_id'                 => $_REQUEST['book_id'],
                                                        'detail'                 => '*แก้ไข แจ้งชำระเงิน BN : ',
                                                        'source'                 => '101payment',
                                                        'pay_id'                 =>  $_REQUEST['pay_id'],
                                                        'user_id'                 =>  $_REQUEST['user_id'],
                                                        'method'            => 'PUT'
                                                        );
                                  $data_return =	json_decode( post_to_ws($url,$data),true );        
                            
                            
                                echo json_encode($set_data);
}
else if($_REQUEST['method'] == 9){ //get bankbook onchange
  #REQUEST

     #WS
    $wsserver   = URL_WS;
    $wsfolder	= '/booking'; //กำหนด Folder
    $wsfile		= '/select_bankbook.php'; //กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(    
                                'bankbook_id' => $_REQUEST['bankbook_id'],
                                'method'        => 'GET'
                            );

 
    $data_return =	json_decode( post_to_ws($url,$data),true );


                                                  
    if($data_return['status'] == 200){

        $result = $data_return['results'][0];
        $set_data['bankbook_code']             = $result['bankbook_code'];
        $set_data['bankbook_name']           = $result['bankbook_name'];
        $set_data['bankbook_branch']           = $result['bankbook_branch'];



    }
    echo json_encode($set_data);

}
else if($_REQUEST['method'] == 10){  // picture payment
    
        
        if(isset($_FILES['pay_url_file'])){


            if($_FILES['pay_url_file']['size'] != 0){

                $path = $_FILES['pay_url_file']['name'];
                $ext = pathinfo($path, PATHINFO_EXTENSION);

                $file_name = '../admin/upload/payment/img_'.$i.'_'.date('Y_m_d_H_I_s').'.'.$ext;

                if(move_uploaded_file($_FILES['pay_url_file']['tmp_name'],$file_name)){
                    $set_data['pay_url_file']    = $file_name;
                    $set_data['status_img'] = 'TRUE';
                }
                else{
                    $set_data['status_img'] = 'FALSE';
                }

            }


        }
        else{
            $set_data['pay_url_file']     = $_REQUEST['pay_url_file_old'];
        }


 
    echo json_encode($set_data);
}
else if($_REQUEST['method'] == 11){ //get bookid
  #REQUEST

     #WS
    $wsserver   = URL_WS;
    $wsfolder	= '/booking'; //กำหนด Folder
    $wsfile		= '/get_booking_insert.php'; //กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(    
                                'book_id' => $_REQUEST['book_id'],
                                'method'        => 'GET'
                            );

 
    $data_return =	json_decode( post_to_ws($url,$data),true );


                                                  
  if($data_return['status'] == 200){

        $result = $data_return['results'][0];
        $set_data['book_id']             = $result['book_id'];
        $set_data['book_code']             = $result['book_code'];
        $set_data['invoice_code']           = $result['invoice_code'];
        $set_data['inv_rev_no']           = intval($result['inv_rev_no']);
        $set_data['agen_name']           = $result['agen_name'];
        $set_data['agen_tel']           = $result['agen_tel'];
        $set_data['agen_email']           = $result['agen_email'];
        $set_data['agen_com_name']           = $result['agen_com_name'];
        $set_data['book_date']           = d_m_y($result['book_date']);
        $set_data['qty_receipt']           = number_format(intval($result['qty_receipt']));
        

    }
    echo json_encode($set_data);

}
else if($_REQUEST['method'] == 12){ //get payment detail
  #REQUEST

     #WS
    $wsserver   = URL_WS;
    $wsfolder	= '/booking'; //กำหนด Folder
    $wsfile		= '/select_period_booking_update.php'; //กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(    
                                'book_id' => $_REQUEST['book_id'],
                                'method'        => 'GET'
                            );

 
    $data_return =	json_decode( post_to_ws($url,$data),true );


                                                  
  if($data_return['status'] == 200){

        $result = $data_return['results'][0];
        $set_data['book_amountgrandtotal']             = number_format(intval($result['book_amountgrandtotal']));
        $set_data['book_receipt']             = number_format(intval($result['book_receipt']));
        $set_data['book_master_deposit']           = number_format(intval($result['book_master_deposit']));
        $set_data['book_due_date_deposit']           = d_m_y($result['book_due_date_deposit']);
        $set_data['book_master_full_payment']           = number_format(intval($result['book_master_full_payment']));
        if($result['book_due_date_full_payment'] == '0000-00-00 00:00:00') {
            $set_data['book_due_date_full_payment'] = '';
        }else{
            $set_data['book_due_date_full_payment']       = d_m_y($result['book_due_date_full_payment']);
            }
        $set_data['book_balance']           = number_format(intval($result['book_amountgrandtotal']) - intval($result['book_receipt']));


    }
    echo json_encode($set_data);

}
else if($_REQUEST['method'] == 13){ //อนุมัติ ชำระเงิน
    #WS
    $wsserver   =   URL_WS;                   # ../unity/post_to_ws/config.php
    $wsfolder	=   '/booking';      # กำหนด Folder
    $wsfile		=	'/update_approve.php';     # กำหนด File
    $url 		=	$wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		=	array(  
                                'pay_id'                    => $_REQUEST['pay_id'],
                                'book_id'                   => $_REQUEST['book_id'],
                                'book_status'               => $_REQUEST['book_status'],
                                'update_user_id'            => $_SESSION['login']['user_id'],
                                'status'                    => 1,
                                'method'                    => 'PUT'
                            );

    $data_return =	json_decode( post_to_ws($url,$data),true );

    $set_data['data_return']    = $data_return;
    $set_data['status']         = $data_return['status'];
    $set_data['type_alert']     = 3;
    

    send_email_payment($_REQUEST['book_id'],$_REQUEST['pay_id']);

    echo json_encode($set_data);

}
else if($_REQUEST['method'] == 14){ // ไม่อนุมัติ ชำระเงิน
    #WS
    $wsserver   =   URL_WS;                   # ../unity/post_to_ws/config.php
    $wsfolder	=   '/booking';      # กำหนด Folder
    $wsfile		=	'/update_cancel_approve.php';     # กำหนด File
    $url 		=	$wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		=	array(  
                                'pay_id'                    => $_REQUEST['pay_id'],
                                'book_id'                   => $_REQUEST['book_id'],
                                'update_user_id'            => $_SESSION['login']['user_id'],
                                'status'                    => 9,
                                'method'                    => 'PUT'
                            );

    $data_return =	json_decode( post_to_ws($url,$data),true );
    $result = $data_return['results'][1];
    $b_status = '';
    if ($result['book_status'] == null){
        $b_status = 0;
    }else{
        $b_status = $result['book_status'];
    }
    $set_data['book_status']    = $b_status;
    $set_data['status']         = $data_return['status'];
    $set_data['type_alert']     = 2;

    send_email_payment($_REQUEST['book_id'],$_REQUEST['pay_id']);
    
    echo json_encode($set_data);

}
else if($_REQUEST['method'] == 15){ //ยกเลิกรายการ ชำระเงิน
    #WS
    $wsserver   =   URL_WS;                   # ../unity/post_to_ws/config.php
    $wsfolder	=   '/booking';      # กำหนด Folder
    $wsfile		=	'/cancel_payment.php';     # กำหนด File
    $url 		=	$wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		=	array(  
                                'pay_id'                    => $_REQUEST['pay_id'],
                                'method'                    => 'PUT'
                            );

    $data_return =	json_decode( post_to_ws($url,$data),true );

    $set_data['status']         = $data_return['status'];
    $set_data['type_alert']     = 4;

    echo json_encode($set_data);

}
else if($_REQUEST['method'] == 16){ // submit room
    $birthday_arr =  array();
     for($i = 0 ; $i< count($_REQUEST['birthday_arr']);$i++){
        $birthday_arr[$i] = Y_m_d($_REQUEST['birthday_arr'][$i]);
     }
       $expire_arr =  array();
     for($i = 0 ; $i< count($_REQUEST['expire_arr']);$i++){
        $expire_arr[$i] = Y_m_d($_REQUEST['expire_arr'][$i]);
     }
    #WS
    $wsserver   =   URL_WS;                   # ../unity/post_to_ws/config.php
    $wsfolder	=   '/booking';      # กำหนด Folder
    $wsfile		=	'/insert_room_detail.php';     # กำหนด File
    $url 		=	$wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		=	array(  
                                'book_code'                     => $_REQUEST['book_code'],
                                'room_fname'                    => $_REQUEST['firstname_arr'],
                                'room_lname'                    => $_REQUEST['lastname_arr'],
                                'room_sex'                      => $_REQUEST['sex_arr'],
                                'room_country'                  => $_REQUEST['country_arr'],
                                'room_nationality'              => $_REQUEST['national_arr'],
                                'room_address'                  => $_REQUEST['address_arr'],
                                'room_birthday'                 => $birthday_arr,
                                'room_passportno'               => $_REQUEST['passportno_arr'],
                                'room_expire'                   => $expire_arr,
                                'room_remark'                   => $_REQUEST['remark_arr'],
                                'room_file'                   => $_REQUEST['room_file_arr'],
                                'method'                        => 'PUT'
                            );

    $data_return =	json_decode( post_to_ws($url,$data),true );

    $set_data['status']         = $data_return['status'];
    $set_data['type_alert']     = 1;

    echo json_encode($set_data);

}
else if($_REQUEST['method'] == 17){ // get ex
    $option_ex = '';
            
            for($i = 0; $i <= 100; $i++ ){
                $selected = '';
             //   if($i == $book_list_qty_5){
              //      $selected = 'selected'; 
              //  }
                 $option_ex .= '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
            }
// onchange="calculatetotal('.$_REQUEST['ex_position'].',\'Y\')"
 $set_data['exname_input'] = '    <div> 
                                                 <div class="form-group">
                                                <label class="col-lg-5  control-label" id= "ex_name" name = "ex_name[]">'.$_REQUEST['ex_name'].' :</label>
                                                <div class="col-lg-7 ">
                                                 <div class="input-group">
                                                    <select class="seat onchange_select" id="ex_select" name="ex_select[]"  index="'.$_REQUEST['index'].'">
                                                     '.$option_ex.'
                                                    </select>
                                                     <span class="input-group-btn">
                                                        <button type="button" class="btn btn-danger del_ex " list="'.$_REQUEST['index'].'" >
                                                        <em class="icon-minus"></em> 
                                                        </button>
                                                    </span>
                                            </div>
                                            </div>
                                </div>
                                <hr>
                                </div>
                                  ';

 $set_data['exprice_input'] = '    <div id="list_'.$_REQUEST['index'].'">  <hr>
                                    <div class="form-group">
                                    <label class="col-lg-4  control-label red">'.$_REQUEST['ex_name'].' :</label>
                                    <label class="col-lg-8  control-label"><font style="font-weight:normal"><font name ="ex_price[]" id = "ex_price_'.$_REQUEST['index'].'">'.$_REQUEST['ex_price'].'</font> 
                                    x <font  name= "ex_qty[]" id = "ex_qty_'.$_REQUEST['index'].'">0</font> = </font> <b> <font name = "total_ex[]" id = "ex_total_'.$_REQUEST['index'].'">0</font></b></label>
                                    </div>
                                    </div>
                            ';

    echo json_encode($set_data);



}
else if($_REQUEST['method'] == 18){ // update receipt number
    $wsserver   = URL_WS;
    $wsfolder	= '/booking'; //กำหนด Folder
    $wsfile		= '/get_prefixnumber.php';
     //กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(    
                                'method'        => 'GET'
                            );
    $data_return =	json_decode( post_to_ws($url,$data),true );

    $pre_receipt = 0;
    $pre_month = '';
    $pre_year = '';
    if($data_return['status'] == 200){
        $pre_receipt = intval($data_return['results'][0]['pre_receipt']);
        $pre_year = $data_return['results'][0]['pre_year'];
        $pre_month = $data_return['results'][0]['pre_month'];
    }
    
    $running_receipt = sprintf("%04s", $pre_receipt);

    $receipt_code = 'R'.$pre_year.'/'.$pre_month.$running_receipt;


     #WS
    $wsserver   = URL_WS;
    $wsfolder	= '/booking'; //กำหนด Folder
    $wsfile		= '/update_receipt_code.php'; //กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(    
                                'book_id' => $_REQUEST['book_id'],
                                'receipt_code' => $receipt_code,
                                'method'        => 'PUT'
                            );

    $data_return =	json_decode( post_to_ws($url,$data),true );

             // update prefix
    $wsserver   = URL_WS;                   # ../unity/post_to_ws/config.php
    $wsfolder	= '/perfixnumber';      # กำหนด Folder
    $wsfile		= '/update_prefixnumber.php';     # กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(
                            'source'                 => 'receipt',
                            'method'            => 'PUT'
                            );

    $data_return =	json_decode( post_to_ws($url,$data),true );

   
   $set_data['data_return']    = $data_return;
   $set_data['status']         = $data_return['status'];
   $set_data['type_alert']     = 2;

    echo json_encode($set_data);

}
else if($_REQUEST['method'] == 19){ // update cancel booking
#WS
    $wsserver   =   URL_WS;                   # ../unity/post_to_ws/config.php
    $wsfolder	=   '/booking';      # กำหนด Folder
    $wsfile		=	'/update_status_cancel.php';     # กำหนด File
    $url 		=	$wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		=	array(  
                                'book_id'                       => $_REQUEST['book_id'],
                                'status_cancel'                 => $_REQUEST['status_cancel'],
                                'book_receipt'                  => $_REQUEST['book_receipt'],
                                'book_cancel'                   => $_REQUEST['book_cancel'],
                                'remark_cancel'                   => $_REQUEST['remark_cancel'],
                                'method'                        => 'PUT'
                            );

    $data_return =	json_decode( post_to_ws($url,$data),true );

    $set_data['status']         = $data_return['status'];
    $set_data['type_alert']     = 2;

    echo json_encode($set_data);

}

else if($_REQUEST['method'] == 20){// get model upload file

    $room_id = $_REQUEST['room_id'];

    $set_data['modal'] = '<div id="modal-manage-room" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" class="modal fade">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" data-dismiss="modal" aria-label="Close" class="close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            <h4 id="myModalLabel" class="modal-title">Upload</h4>
                                        </div>
                                        <div class="modal-body">

                                            <div class="form-horizontal">

                                                <div class="form-group">
                                                    <label class="col-lg-3  control-label">File :</label>
                                                    <div class="col-lg-7 ">
                                                        <input type="file" class="form-control" id="file_room" name="file_room" value="">
                                                    </div>
                                                </div>
                                                
                                            </div>


                                        </div>

                                       

                                        <div class="modal-footer">
                                            <button type="button" data-dismiss="modal" class="btn btn-default">ปิด</button>
                                            <button type="button" class="btn btn-primary" onclick="upload_file_room('.$room_id.')">
                                                <em class="icon-cursor"></em>
                                                บันทีก
                                            </button>
                                        </div>

                                    </div>
                                </div>
                            </div>';

    echo json_encode($set_data);

}
else if($_REQUEST['method'] == 21){ // upload file return url

    if($_FILES['file_room']['size'] > 0){

        $path = $_FILES['file_room']['name'];
        $ext = pathinfo($path, PATHINFO_EXTENSION);

        $file_name = '../admin/upload/room/room_file_'.date('Y_m_d_H_I_s').'.'.$ext;

        if(move_uploaded_file($_FILES['file_room']['tmp_name'],$file_name)){
            $set_data['file_room']    = str_replace('../admin/','../',$file_name);
            $set_data['status']        = 'TRUE';


            #WS
            $wsserver   = URL_WS;
            $wsfolder	= '/booking'; //กำหนด Folder
            $wsfile		= '/update_room_file.php'; //กำหนด File
            $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
            $data 		= array(    

                                        'room_id'       => $_REQUEST['room_id'],
                                        'room_file'     => $set_data['file_room'],
                                        'method'        => 'PUT'
                                    );

        
            $data_return =	json_decode( post_to_ws($url,$data),true );

            print_r($data_return);
        }
        else{
            
        }


    }

    print_r($set_data);
   

}

else if($_REQUEST['method'] == 30){// send email invoice

    send_email_invoice($_REQUEST['book_code']);

}
else if($_REQUEST['method'] == 98){
    #REQUEST

     #WS
    $wsserver   = URL_WS;
    $wsfolder	= '/booking'; //กำหนด Folder
    $wsfile		= '/select_agency_by_com_id.php'; //กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(    
                                'com_agency_id' => $_REQUEST['com_agency_id'],
                                'method'        => 'GET'
                            );

 
    $data_return =	json_decode( post_to_ws($url,$data),true );


     $set_data['select_agency'] = ' <select class="" id="agency_booking" name = "agency_booking" >';


    
                                                  
  if($data_return['status'] == 200){

                $result = $data_return['results'];


                foreach($result as $key => $value){
                    $selected = '';
                     if($value['agen_id'] == $_REQUEST['agency_id']){
                        $selected = 'selected';
                    }
                   
                    $set_data['select_agency'] .= '<option value="'.$value['agen_id'].'" '.$selected.'>'.$value['name'].'</option>';
                }

            }
            else{
                
                 $set_data['select_agency'] .= '<option value="0">ไม่พบข้อมูล</option>';
            }

    $set_data['select_agency'] .= ' </select>';

    echo json_encode($set_data);


}

else if($_REQUEST['method'] == 99){ // select room type by id book 

    #WS
    $wsserver   =   URL_WS;                   # ../unity/post_to_ws/config.php
    $wsfolder	=   '/booking';      # กำหนด Folder
    $wsfile		=	'/get_room.php';     # กำหนด File
    $url 		=	$wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		=	array(  
                            'book_id'          => $_REQUEST['book_id'],
                            'method'            => 'GET'
                            );

    $data_return =	json_decode( post_to_ws($url,$data),true );
    $book_room_twin = 0;
    $book_room_double = 0;
    $book_room_triple = 0;
    $book_room_single = 0;
    
    if($data_return['status'] == 200){
        $result  = $data_return['results'][0];

        $book_room_twin = $result['book_room_twin'];
        $book_room_double = $result['book_room_double'];
        $book_room_triple = $result['book_room_triple'];
        $book_room_single = $result['book_room_single'];
    }
    
        $room_id                = array();
        $room_fname             = array();
        $room_lname             = array();
        $room_name_thai             = array();
        $room_sex_male          = array();
        $room_sex_female        = array();
        $room_country           = array();
        $room_nationality       = array();
        $room_address           = array();
        $room_birthday          = array();
        $room_passportno        = array();
        $room_expire            = array();
        $room_file              = array();
        $room_remark            = array();
        $room_career            = array();
        $room_placeofbirth        = array();
        $room_place_pp            = array();
        $room_date_pp            = array();
     
        
        $readonly           = 'readonly';


        #WS
        $wsserver   = URL_WS;
        $wsfolder	= '/booking'; //กำหนด Folder
        $wsfile		= '/select_list_room_detail.php'; //กำหนด File
        $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
        $data 		= array(    

                                    'book_code'       => $_REQUEST['book_code'],
                                    'method'        => 'GET'
                                );

        $data_return =	json_decode( post_to_ws($url,$data),true );


        if($data_return['status'] == 200){
            $result = $data_return['results'];
                for($i = 0 ; $i< count($result);$i++){
                    $room_fname[$i]         = $result[$i]['room_fname'];
                    $room_lname[$i]         = $result[$i]['room_lname'];
                    $room_name_thai[$i]         = $result[$i]['room_name_thai'];
                    
                if($result[$i]['room_sex'] == 1){
                    $room_sex_male[$i]      = 'selected';
                    $room_sex_female[$i]    = '';
                }else{
                    $room_sex_male[$i]      = '';
                    $room_sex_female[$i]    = 'selected';
                }
                    $room_id[$i]            = $result[$i]['room_id'];
                    $room_country[$i]       = $result[$i]['room_country'];
                    $room_nationality[$i]   = $result[$i]['room_nationality'];
                    $room_address[$i]       = $result[$i]['room_address'];
                    $room_birthday[$i]      = d_m_y($result[$i]['room_birthday']);
                    $room_passportno[$i]    = $result[$i]['room_passportno'];
                    $room_expire[$i]        = d_m_y($result[$i]['room_expire']);

                    $str = str_replace('../','',$result[$i]['room_file']);
                    $room_filee = '../admin/'.$str;
                    if(is_file($room_filee)){
                        $url_room_file = $room_filee;
                    }
                    else{
                        $url_room_file = 'javascript:;';
                    }

                    $room_file[$i]          = $url_room_file;
                    $room_remark[$i]        = $result[$i]['room_remark'];
                    $room_career[$i]        = $result[$i]['room_career'];
                    $room_placeofbirth[$i]        = $result[$i]['room_placeofbirth'];
                    $room_place_pp[$i]        = $result[$i]['room_place_pp'];
                    $room_date_pp[$i]        = d_m_y($result[$i]['room_date_pp']);
                }
        }else{
            for($i = 0 ; $i< 100;$i++){
             $room_file[$i] = 'javascript:;';
            }

        }

    $x = 0;
  /*  $set_data['div_room'] = '<div class="panel-heading">'.$room_fname.' </div>';
      echo json_encode($set_data);
    return; */
    for($i = 0; $i< $book_room_twin; $i++){
        $set_data['div_room'] .= '<div class="panel panel-primary" style="" >
                                    <div class="panel-heading">-Twin </div>
                                    <div class="panel-body">
                                        <div class="table-responsive" style="padding-bottom:20px;">
                                            <table id="" class="table table-bordered table-hover table-devgun" style="width: 2000px;" >
                                                <thead>
                                                    <tr>
                                                        <th width="5%">#</th>
                                                        <th>Firstname*</th>
                                                        <th>Lastname*</th>
                                                        <th>Fullname THAI*</th>
                                                        <th>Sex*</th>
                                                        <th>Country*</th>
                                                        <th>National*</th>
                                                        <th>Address in Thailand*</th>
                                                        <th>Birthday*</th>
                                                        <th>Passport No.*</th>
                                                        <th>Expire*</th>
                                                        <th>File</th>
                                                        <th>Remark</th>
                                                        <th>Upload</th>
                                                        <th>อาชีพ</th>
                                                        <th>จัดหวัดที่เกิด</th>
                                                        <th>สถานที่ออก pp</th>
                                                        <th>วันที่ออก pp</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="text-center">1</td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control " name ="firtname[]" value = "'.$room_fname[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control" name ="lastname[]" value = "'.$room_lname[$x].'" required>
                                                        </td>
                                                         <td class="text-center">
                                                            <input type="text" class="form-control" name ="room_name_thai[]" value = "'.$room_name_thai[$x].'" required>
                                                        </td>
                                                      <td class="text-center">
                                                            <select name="sex[]">
                                                                <option value="1" '.$room_sex_male[$x].'>M</option>
                                                                <option value="2" '.$room_sex_female[$x].'>F</option>
                                                            </select>
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control" name="country[]" value = "'.$room_country[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control" name="national[]" value = "'.$room_nationality[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control" name="address[]" value = "'.$room_address[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control date period_requried" name="birthday[]" value = "'.$room_birthday[$x].'" >
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control" name="passportno[]" value = "'.$room_passportno[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control date period_requried" name="expire[]" value = "'.$room_expire[$x].'">
                                                        </td>
                                                        <td class="text-center">
                                                            <a href="'.$room_file[$x].'" type="button" class="mb-sm btn btn-info btn-xs btn-room-file" target="_blank">
                                                                <em class="icon-cloud-download"></em>
                                                            </a>
                                                        </td>
                                                        <td style="display:none;">
                                                           <input type="text" class="form-control" name="room_file[]" value = "'.$room_file[$x].'">
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control " name="remark[]" value = "'.$room_remark[$x].'">
                                                        </td>
                                                        <td class="text-center">
                                                            <a href="javascript:manage_upload('.$room_id[$x].');" type="button" class="mb-sm btn btn-success btn-xs" target="_blank">
                                                                <em class="icon-cloud-upload"></em>
                                                            </a>
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control" name="career[]" value = "'.$room_career[$x].'" >
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control" name="placeofbirth[]" value = "'.$room_placeofbirth[$x].'" >
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control" name="place_pp[]" value = "'.$room_place_pp[$x].'" >
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control date" name="date_pp[]" value = "'.$room_date_pp[$x].'" >
                                                        </td>
                                                        
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">2</td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control"  name ="firtname[]" value = "'.$room_fname[($x = $x+1)].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control" name ="lastname[]" value = "'.$room_lname[$x].'" required>
                                                        </td>
                                                          <td class="text-center">
                                                            <input type="text" class="form-control" name ="room_name_thai[]" value = "'.$room_name_thai[$x].'" required>
                                                        </td>
                                                      <td class="text-center">
                                                            <select  name="sex[]"> 
                                                                <option value="1" '.$room_sex_male[$x].'>M</option>
                                                                <option value="2" '.$room_sex_female[$x].'>F</option>
                                                            </select>
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control" name="country[]" value = "'.$room_country[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control" name="national[]" value = "'.$room_nationality[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control" name="address[]" value = "'.$room_address[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control date period_requried" name="birthday[]" value = "'.$room_birthday[$x].'">
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control" name="passportno[]" value = "'.$room_passportno[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control date period_requried" name="expire[]" value = "'.$room_expire[$x].'">
                                                        </td>
                                                        <td class="text-center">
                                                            <a href="'.$room_file[$x].'" type="button" class="mb-sm btn btn-info btn-xs btn-room-file" target="_blank">
                                                                <em class="icon-cloud-download"></em>
                                                            </a>
                                                        </td>
                                                        <td style="display:none;">
                                                           <input type="text" class="form-control" name="room_file[]" value = "'.$room_file[$x].'">
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control " name="remark[]" value = "'.$room_remark[$x].'">
                                                        </td>
                                                        <td class="text-center">
                                                            <a href="javascript:manage_upload('.$room_id[$x].');" type="button" class="mb-sm btn btn-success btn-xs" target="_blank">
                                                                <em class="icon-cloud-upload"></em>
                                                            </a>
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control" name="career[]" value = "'.$room_career[$x].'" >
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control" name="placeofbirth[]" value = "'.$room_placeofbirth[$x].'" >
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control" name="place_pp[]" value = "'.$room_place_pp[$x].'" >
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control date" name="date_pp[]" value = "'.$room_date_pp[$x].'" >
                                                        </td>
                                                        
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    
                                </div>';
    $x = $x+1;
    }

    for($i = 0; $i< $book_room_double; $i++){
        $set_data['div_room'] .= '<div class="panel panel-success">
                                    <div class="panel-heading">-Double </div>
                                    <div class="panel-body">
                                        <div class="table-responsive" style="padding-bottom:20px">
                                            <table id="" class="table table-bordered table-hover table-devgun" style="width: 2000px;" >
                                                <thead>
                                                    <tr>
                                                        <th width="5%">#</th>
                                                        <th>Firstname*</th>
                                                        <th>Lastname*</th>
                                                        <th>Fullname THAI*</th>
                                                        <th>Sex*</th>
                                                        <th>Country*</th>
                                                        <th>National*</th>
                                                        <th>Address in Thailand*</th>
                                                        <th>Birthday*</th>
                                                        <th>Passport No.*</th>
                                                        <th>Expire*</th>
                                                        <th>File</th>
                                                        <th>Remark</th>
                                                        <th>Upload</th>
                                                        <th>อาชีพ</th>
                                                        <th>จัดหวัดที่เกิด</th>
                                                        <th>สถานที่ออก pp</th>
                                                        <th>วันที่ออก pp</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="text-center">1</td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control" name ="firtname[]" value = "'.$room_fname[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control" name ="lastname[]" value = "'.$room_lname[$x].'" required>
                                                        </td>
                                                         <td class="text-center">
                                                            <input type="text" class="form-control" name ="room_name_thai[]" value = "'.$room_name_thai[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <select  name="sex[]">
                                                                <option value="1" '.$room_sex_male[$x].'>M</option>
                                                                <option value="2" '.$room_sex_female[$x].'>F</option>
                                                            </select>
                                                        </td>
                                                        <td class="text-center">
                                                         <input type="text" class="form-control" name="country[]" value = "'.$room_country[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                         <input type="text" class="form-control" name="national[]" value = "'.$room_nationality[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control" name="address[]" value = "'.$room_address[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control date period_requried" name="birthday[]" value = "'.$room_birthday[$x].'">
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control" name="passportno[]" value = "'.$room_passportno[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control date period_requried" name="expire[]" value = "'.$room_expire[$x].'">
                                                        </td>
                                                        <td class="text-center">
                                                            <a href="'.$room_file[$x].'" type="button" class="mb-sm btn btn-info btn-xs btn-room-file" target="_blank" >
                                                                <em class="icon-cloud-download"></em>
                                                            </a>
                                                        </td>
                                                          <td style="display:none;">
                                                           <input type="text" class="form-control" name="room_file[]" value = "'.$room_file[$x].'">
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control " name="remark[]" value = "'.$room_remark[$x].'">
                                                        </td>
                                                        <td class="text-center">
                                                            <a href="javascript:manage_upload('.$room_id[$x].');" type="button" class="mb-sm btn btn-success btn-xs" target="_blank">
                                                                <em class="icon-cloud-upload"></em>
                                                            </a>
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control" name="career[]" value = "'.$room_career[$x].'" >
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control" name="placeofbirth[]" value = "'.$room_placeofbirth[$x].'" >
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control" name="place_pp[]" value = "'.$room_place_pp[$x].'" >
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control date " name="date_pp[]" value = "'.$room_date_pp[$x].'" >
                                                        </td>
                                                        
                                                    </tr>
                                                   <tr>
                                                        <td class="text-center">2</td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control" name ="firtname[]" value = "'.$room_fname[($x = $x+1)].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control" name ="lastname[]" value = "'.$room_lname[$x].'" required>
                                                        </td>
                                                           <td class="text-center">
                                                            <input type="text" class="form-control" name ="room_name_thai[]" value = "'.$room_name_thai[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <select  name="sex[]">
                                                                <option value="1" '.$room_sex_male[$x].'>M</option>
                                                                <option value="2" '.$room_sex_female[$x].'>F</option>
                                                            </select>
                                                        </td>
                                                        <td class="text-center">
                                                         <input type="text" class="form-control" name="country[]" value = "'.$room_country[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                         <input type="text" class="form-control" name="national[]" value = "'.$room_nationality[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control" name="address[]" value = "'.$room_address[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control date period_requried" name="birthday[]" value = "'.$room_birthday[$x].'">
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control" name="passportno[]" value = "'.$room_passportno[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control date period_requried" name="expire[]" value = "'.$room_expire[$x].'">
                                                        </td>
                                                        <td class="text-center">
                                                            <a href="'.$room_file[$x].'" type="button" class="mb-sm btn btn-info btn-xs btn-room-file" target="_blank" >
                                                                <em class="icon-cloud-download"></em>
                                                            </a>
                                                        </td>
                                                          <td style="display:none;">
                                                           <input type="text" class="form-control" name="room_file[]" value = "'.$room_file[$x].'">
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control " name="remark[]" value = "'.$room_remark[$x].'">
                                                        </td>
                                                        <td class="text-center">
                                                            <a href="javascript:manage_upload('.$room_id[$x].');" type="button" class="mb-sm btn btn-success btn-xs" target="_blank">
                                                                <em class="icon-cloud-upload"></em>
                                                            </a>
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control" name="career[]" value = "'.$room_career[$x].'" >
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control" name="placeofbirth[]" value = "'.$room_placeofbirth[$x].'" >
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control" name="place_pp[]" value = "'.$room_place_pp[$x].'" >
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control date " name="date_pp[]" value = "'.$room_date_pp[$x].'" >
                                                        </td>
                                                        
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    
                                </div>';
      $x = $x+1;
    }
    for($i = 0; $i< $book_room_triple; $i++){
        $set_data['div_room'] .= ' <div class="panel panel panel-info">
                                    <div class="panel-heading">-Triple  </div>
                                    <div class="panel-body">
                                        <div class="table-responsive" style="padding-bottom:20px">
                                            <table id="" class="table table-bordered table-hover table-devgun" style="width: 2000px;" >
                                                <thead>
                                                    <tr>
                                                        <th width="5%">#</th>
                                                        <th>Firstname*</th>
                                                        <th>Lastname*</th>
                                                        <th>Fullname THAI*</th>
                                                        <th>Sex*</th>
                                                        <th>Country*</th>
                                                        <th>National*</th>
                                                        <th>Address in Thailand*</th>
                                                        <th>Birthday*</th>
                                                        <th>Passport No.*</th>
                                                        <th>Expire*</th>
                                                        <th>File</th>
                                                        <th>Remark</th>
                                                        <th>Upload</th>
                                                        <th>อาชีพ</th>
                                                        <th>จัดหวัดที่เกิด</th>
                                                        <th>สถานที่ออก pp</th>
                                                        <th>วันที่ออก pp</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="text-center">1</td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control" name ="firtname[]" value = "'.$room_fname[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control" name ="lastname[]" value = "'.$room_lname[$x].'" required>
                                                        </td>
                                                         <td class="text-center">
                                                            <input type="text" class="form-control" name ="room_name_thai[]" value = "'.$room_name_thai[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <select  name="sex[]">
                                                                <option value="1" '.$room_sex_male[$x].'>M</option>
                                                                <option value="2" '.$room_sex_female[$x].'>F</option>
                                                            </select>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control" name="country[]" value = "'.$room_country[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control" name="national[]" value = "'.$room_nationality[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control" name="address[]" value = "'.$room_address[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control date period_requried" name="birthday[]" value = "'.$room_birthday[$x].'">
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control" name="passportno[]" value = "'.$room_passportno[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control date period_requried" name="expire[]" value = "'.$room_expire[$x].'">
                                                        </td>
                                                        <td class="text-center">
                                                            <a href="'.$room_file[$x].'" type="button" class="mb-sm btn btn-info btn-xs btn-room-file" target="_blank">
                                                                <em class="icon-cloud-download"></em>
                                                            </a>
                                                        </td>
                                                          <td style="display:none;">
                                                           <input type="text" class="form-control" name="room_file[]" value = "'.$room_file[$x].'">
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control " name="remark[]" value = "'.$room_remark[$x].'">
                                                        </td>
                                                        <td class="text-center">
                                                            <a href="javascript:manage_upload('.$room_id[$x].');" type="button" class="mb-sm btn btn-success btn-xs" target="_blank">
                                                                <em class="icon-cloud-upload"></em>
                                                            </a>
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control" name="career[]" value = "'.$room_career[$x].'" >
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control" name="placeofbirth[]" value = "'.$room_placeofbirth[$x].'" >
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control" name="place_pp[]" value = "'.$room_place_pp[$x].'" >
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control date " name="date_pp[]" value = "'.$room_date_pp[$x].'" >
                                                        </td>
                                                        
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">2</td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control" name ="firtname[]" value = "'.$room_fname[($x = $x+1)].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control" name ="lastname[]" value = "'.$room_lname[$x].'" required>
                                                        </td>
                                                         <td class="text-center">
                                                            <input type="text" class="form-control" name ="room_name_thai[]" value = "'.$room_name_thai[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <select  name="sex[]">
                                                                <option value="1" '.$room_sex_male[$x].'>M</option>
                                                                <option value="2" '.$room_sex_female[$x].'>F</option>
                                                            </select>
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control" name="country[]" value = "'.$room_country[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                              <input type="text" class="form-control" name="national[]" value = "'.$room_nationality[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control" name="address[]" value = "'.$room_address[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control date period_requried" name="birthday[]" value = "'.$room_birthday[$x].'">
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control" name="passportno[]" value = "'.$room_passportno[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control date period_requried" name="expire[]" value = "'.$room_expire[$x].'">
                                                        </td>
                                                        <td class="text-center">
                                                            <a href="'.$room_file[$x].'" type="button" class="mb-sm btn btn-info btn-xs btn-room-file" target="_blank" >
                                                                <em class="icon-cloud-download"></em>
                                                            </a>
                                                        </td>
                                                          <td style="display:none;">
                                                           <input type="text" class="form-control" name="room_file[]" value = "'.$room_file[$x].'">
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control " name="remark[]" value = "'.$room_remark[$x].'">
                                                        </td>
                                                        <td class="text-center">
                                                            <a href="javascript:manage_upload('.$room_id[$x].');" type="button" class="mb-sm btn btn-success btn-xs" target="_blank">
                                                                <em class="icon-cloud-upload"></em>
                                                            </a>
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control" name="career[]" value = "'.$room_career[$x].'" >
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control" name="placeofbirth[]" value = "'.$room_placeofbirth[$x].'" >
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control" name="place_pp[]" value = "'.$room_place_pp[$x].'" >
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control date " name="date_pp[]" value = "'.$room_date_pp[$x].'" >
                                                        </td>
                                                        
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">3</td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control" name ="firtname[]" value = "'.$room_fname[($x = $x+1)].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control" name ="lastname[]" value = "'.$room_lname[$x].'" required>
                                                        </td>
                                                         <td class="text-center">
                                                            <input type="text" class="form-control" name ="room_name_thai[]" value = "'.$room_name_thai[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <select  name="sex[]">
                                                                <option value="1" '.$room_sex_male[$x].'>M</option>
                                                                <option value="2" '.$room_sex_female[$x].'>F</option>
                                                            </select>
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control" name="country[]" value = "'.$room_country[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                              <input type="text" class="form-control" name="national[]" value = "'.$room_nationality[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control" name="address[]" value = "'.$room_address[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control date period_requried" name="birthday[]" value = "'.$room_birthday[$x].'">
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control" name="passportno[]" value = "'.$room_passportno[$x].'" required>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control date period_requried" name="expire[]" value = "'.$room_expire[$x].'">
                                                        </td>
                                                        <td class="text-center">
                                                            <a href="'.$room_file[$x].'" type="button" class="mb-sm btn btn-info btn-xs btn-room-file" target="_blank" >
                                                                <em class="icon-cloud-download"></em>
                                                            </a>
                                                        </td>
                                                          <td style="display:none;">
                                                           <input type="text" class="form-control" name="room_file[]" value = "'.$room_file[$x].'">
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="text" class="form-control " name="remark[]" value = "'.$room_remark[$x].'">
                                                        </td>
                                                        <td class="text-center">
                                                            <a href="javascript:manage_upload('.$room_id[$x].');" type="button" class="mb-sm btn btn-success btn-xs" target="_blank">
                                                                <em class="icon-cloud-upload"></em>
                                                            </a>
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control" name="career[]" value = "'.$room_career[$x].'" >
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control" name="placeofbirth[]" value = "'.$room_placeofbirth[$x].'" >
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control" name="place_pp[]" value = "'.$room_place_pp[$x].'" >
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control date " name="date_pp[]" value = "'.$room_date_pp[$x].'" >
                                                        </td>
                                                        
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    
                                </div>
                                    ';
        $x = $x+1;
    }
    for($i = 0; $i< $book_room_single; $i++){
            $set_data['div_room'] .= ' <div class="panel panel panel-warning">
                                        <div class="panel-heading">-Single  </div>
                                        <div class="panel-body">
                                            <div class="table-responsive" style="padding-bottom:20px">
                                                <table id="" class="table table-bordered table-hover table-devgun" style="width: 2000px;" >
                                                    <thead>
                                                        <tr>
                                                            <th width="5%">#</th>
                                                            <th>Firstname*</th>
                                                            <th>Lastname*</th>
                                                            <th>Fullname THAI*</th>
                                                            <th>Sex*</th>
                                                            <th>Country*</th>
                                                            <th>National*</th>
                                                            <th>Address in Thailand*</th>
                                                            <th>Birthday*</th>
                                                            <th>Passport No.*</th>
                                                            <th>Expire*</th>
                                                            <th>File</th>
                                                            <th>Remark</th>
                                                            <th>Upload</th>
                                                            <th>อาชีพ</th>
                                                        <th>จัดหวัดที่เกิด</th>
                                                        <th>สถานที่ออก pp</th>
                                                        <th>วันที่ออก pp</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="text-center">1</td>
                                                            <td class="text-center">
                                                                <input type="text" class="form-control" name ="firtname[]" value = "'.$room_fname[$x].'" required> 
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="text" class="form-control" name ="lastname[]" value = "'.$room_lname[$x].'" required>
                                                            </td>
                                                              <td class="text-center">
                                                            <input type="text" class="form-control" name ="room_name_thai[]" value = "'.$room_name_thai[$x].'" required>
                                                        </td>
                                                            <td class="text-center">
                                                                <select  name="sex[]">
                                                                    <option value="1" '.$room_sex_male[$x].'>M</option>
                                                                    <option value="2" '.$room_sex_female[$x].'>F</option>
                                                                </select>
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="text" class="form-control"  name="country[]" value = "'.$room_country[$x].'" required>
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="text" class="form-control" name="national[]" value = "'.$room_nationality[$x].'" required>
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="text" class="form-control" name="address[]" value = "'.$room_address[$x].'" required>
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="text" class="form-control date period_requried" name="birthday[]" value = "'.$room_birthday[$x].'">
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="text" class="form-control" name="passportno[]" value = "'.$room_passportno[$x].'" required>
                                                            </td>
                                                            <td class="text-center">
                                                                <input type="text" class="form-control date period_requried" name="expire[]" value = "'.$room_expire[$x].'">
                                                            </td>
                                                            <td class="text-center">
                                                                <a href="'.$room_file[$x].'" type="button" class="mb-sm btn btn-info btn-xs btn-room-file" target="_blank" >
                                                                    <em class="icon-cloud-download"></em>
                                                                </a>
                                                            </td>
                                                              <td style="display:none;">
                                                           <input type="text" class="form-control" name="room_file[]" value = "'.$room_file[$x].'">
                                                        </td>
                                                            <td class="text-center">
                                                                <input type="text" class="form-control" name="remark[]" value = "'.$room_remark[$x].'">
                                                            </td>
                                                            <td class="text-center">
                                                                <a href="javascript:manage_upload('.$room_id[$x].');" type="button" class="mb-sm btn btn-success btn-xs" target="_blank">
                                                                    <em class="icon-cloud-upload"></em>
                                                                </a>
                                                            </td>
                                                         <td class="text-center">
                                                             <input type="text" class="form-control" name="career[]" value = "'.$room_career[$x].'" >
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control" name="placeofbirth[]" value = "'.$room_placeofbirth[$x].'" >
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control" name="place_pp[]" value = "'.$room_place_pp[$x].'" >
                                                        </td>
                                                        <td class="text-center">
                                                             <input type="text" class="form-control date" name="date_pp[]" value = "'.$room_date_pp[$x].'" >
                                                        </td>
                                                            
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        
                                    </div>';
        $x= $x + 1;
    }
    echo json_encode($set_data);

}


else{

}








?>