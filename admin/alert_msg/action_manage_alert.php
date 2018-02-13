<?php
#include
include_once('../unity/main_core.php');
include_once('../unity/post_to_ws/post_to_ws.php');
include_once('../unity/php_script.php');

include_once('../unity/php_send_email.php');
#REQUEST

#SET DATA RETURN

$set_data = array();
if($_REQUEST['method'] == 1){//select list
     $wsserver   = URL_WS;
    $wsfolder	= '/alert_msg'; //กำหนด Folder
    $wsfile		= '/select_sale.php'; //กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(    
                                'user_id'        =>  $_SESSION['login']['user_id'],
                                'Limit'        =>  5,
                                'method'        => 'GET'
                            );

 
    $data_return =	json_decode( post_to_ws($url,$data),true );

    $set_data['alert_list'] ='';
    $set_data['alert_count'] ='';
    $detail = '';
    if($data_return['status'] == 200){

        $result = $data_return['results'];
        $set_data['alert_count'] =$result[0]['count'];
        
        foreach($result as $key => $value){
            $book_code = $value['book_code'];
            $href = '../booking/manage_booking.php?book_id='.$value['book_id'].'&book_code='.$value['book_code'];
            if ($value['source'] == '100booking'){
                $detail = $value['qty'].' ที่นั่ง; AB : '.$value['agenname'].' ('.$value['agen_com_name'].')';
            }else if ($value['source'] == '104file_cost'){ // แนบไฟล์ Cost
                $detail =  $value['ser_code'].' ,P : '.thai_date_short(strtotime($value['per_date_start'])). ' - '.thai_date_short(strtotime($value['per_date_end']));
                $book_code = '';
                $href = '../manage_travel/manage_travel.php?series_id='.$value['ser_id'];
            }
            else if ($value['source'] == '150booking'){
                $detail = '<span style="color:red;">ที่นั่งไม่พอสำหรับการจอง</span>';
            }else{
                $detail = html_status_book($value['book_status']).' ยอดเงิน : ' .number_format(intval($value['pay_received']));
            }
           
           $set_data['alert_list'] .= ' <a href='.$href.' class="list-group-item">
                                <div class="media-box">
                                <div class="pull-left">
                                   
                                </div>
                                <div class="media-box-body clearfix">
                                    <p class="m0">'.$value['detail'].$book_code.'</p>
                                    <p class="m0 text-muted">
                                        <small>
                                            '.$detail.'
                                        </small>
                                    </p>
                                </div>
                                </div>
                            </a>
           ';
           
                      



        }

    }

    echo json_encode($set_data);




}else if ($_REQUEST['method'] == 2){ // แสดง 20 รายการ , ทั้งหมด
 $wsserver   = URL_WS;
    $wsfolder	= '/alert_msg'; //กำหนด Folder
    $wsfile		= '/select_sale.php'; //กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(    
                                'user_id'        =>  $_SESSION['login']['user_id'],
                                'Limit'        =>  $_REQUEST['limit'],
                                'method'        => 'GET'
                            );

 
    $data_return =	json_decode( post_to_ws($url,$data),true );

    $set_data['alert_noti'] ='';
    $set_data['count_all'] ='';
    $detail = '';
    if($data_return['status'] == 200){

        $result = $data_return['results'];
        $set_data['count_all'] = $result[0]['count_all'];


        foreach($result as $key => $value){
            $book_code = $value['book_code'];
            $href = '\'../booking/manage_booking.php?book_id='.$value['book_id'].'&book_code='.$value['book_code'].'\'';
            if ($value['source'] == '100booking'){ // จอง
                $detail = $value['qty'].' ที่นั่ง; AB : '.$value['agenname'].' ('.$value['agen_com_name'].')';
            }else if ($value['source'] == '101payment'){ // ชำระเงิน
                $detail = html_status_book($value['book_status']).' ยอดเงิน : ' .number_format(intval($value['pay_received'])). ' ,ผู้ทำรายการ : ' .$value['action_name']  ;
            }else if ($value['source'] == '102app_payment' || $value['source'] == '103cxl_payment'){ // อนุมัติ , ไม่อนุมัติ
                $detail = html_status_book($value['book_status']).' ยอดเงิน : ' .number_format(intval($value['pay_received']));
            }else if ($value['source'] == '104file_cost'){ // แนบไฟล์ Cost
                $detail = $value['ser_code'].' ,Period : '.thai_date_short(strtotime($value['per_date_start'])). ' - '.thai_date_short(strtotime($value['per_date_end']));
                $book_code = ' '.$value['ser_code'];
                $href = '\'../manage_travel/manage_travel.php?series_id='.$value['ser_id'].'\'';
            }
           $set_data['alert_noti'] .= ' <li class="list-group-item clearfix">
                                            <div class="pull-left mr">
                                                Date '.d_m_y($value['log_date']).'
                                            </div>
                                            <div class="pull-right">
                                                <button type="button" class="btn btn-primary btn-default" onclick="location.href='.$href.'">
                                                    <strong>ดูรายละเอียด</strong>
                                                </button>
                                            </div>
                                            <p class="text-bold mb0">
                                               '.$value['detail'].$book_code.'
                                            </p>
                                            <small>
                                            <p class="text-bold mb0">
                                               '.$detail.'
                                            </p>
                                            
                                            </small>
                                        </li>
           ';


        }

    }

    echo json_encode($set_data);


}else if ($_REQUEST['method'] == 4){ // update read date
 $wsserver   = URL_WS;
    $wsfolder	= '/alert_msg'; //กำหนด Folder
    $wsfile		= '/update_read_date.php'; //กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(    
                                'user_id'        =>  $_SESSION['login']['user_id'],
                                'method'        => 'PUT'
                            );

 
    $data_return =	json_decode( post_to_ws($url,$data),true );
    $set_data['status']         = $data_return['status'];

    echo json_encode($set_data);


}else if($_REQUEST['method'] == 5){//alert today
     $wsserver   = URL_WS;
    $wsfolder	= '/alert_msg'; //กำหนด Folder
    $wsfile		= '/select_today.php'; //กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(    
                                'user_id'        =>  $_SESSION['login']['user_id'],
                                'group_id'        =>  $_SESSION['login']['group_id'],
                                'Limit'        =>  100,
                                'method'        => 'GET'
                            );

 
    $data_return =	json_decode( post_to_ws($url,$data),true );

    $set_data['alert_list_today'] ='';
    $set_data['alert_count_today'] ='';
    $sum = 0;
    $detail = '';
    if($data_return['status'] == 200){

        $result = $data_return['results'];
        $i = 0;
        foreach($result as $key => $value){
                if ($value['source'] == 'Cost' || $value['source'] == 'Cost2'   ){
                    $detail = $value['ser_code'].' ,Period ' .thai_date_short(strtotime($value['book_date'])). ' - '.thai_date_short(strtotime($value['s_date']));
                }else if($value['source'] == 'room_list'){
                    
                    $detail = 'Period ' .date('d',strtotime($value['book_date'])). ' - '.thai_date_short(strtotime($value['s_date']));
                }
                
                
                else{
                    $detail = 'Booking code : ' .$value['book_code'];
                }
           
           if ($i < 5){
            if ($value['source'] == 'Cost' || $value['source'] == 'Cost2'){
                $set_data['alert_list_today'] .= ' <a href="../manage_travel/manage_travel.php?series_id='.$value['book_code'].'" class="list-group-item">
                <div class="media-box">
                <div class="pull-left">
                   
                </div>
                <div class="media-box-body clearfix">
                    <p class="m0">'.$value['detail'].'</p>
                    <p class="m0 text-muted">
                        <small>
                            '.$detail.'
                        </small>
                    </p>
                </div>
                </div>
                </a>
                ';
            }else if ($value['source'] == 'room_list'){
                $set_data['alert_list_today'] .= ' <a href="../manage_period/manage_period.php?per_id='.$value['book_id'].'&bus_no='.$value['agen_id'].'" class="list-group-item">
                <div class="media-box">
                <div class="pull-left">
                   
                </div>
                <div class="media-box-body clearfix">
                    <p class="m0">'.$value['detail'].' '.$value['ser_code'].'</p>
                    <p class="m0 text-muted">
                        <small>
                            '.$detail.' ,Bus no: '.$value['agen_id'].'
                        </small>
                    </p>
                </div>
                </div>
                </a>
                ';
            }else{
                $set_data['alert_list_today'] .= ' <a href="../booking/manage_booking.php?book_id='.$value['book_id'].'&book_code='.$value['book_code'].'" class="list-group-item">
                                <div class="media-box">
                                <div class="pull-left">
                                   
                                </div>
                                <div class="media-box-body clearfix">
                                    <p class="m0">'.$value['detail'].'</p>
                                    <p class="m0 text-muted">
                                        <small>
                                            '.$detail.'
                                        </small>
                                    </p>
                                </div>
                                </div>
                            </a>
                ';
            }
           $i = $i + 1;
           }
                      


           $sum = $sum + 1;
        }
        $set_data['alert_count_today'] = $sum;
        
    }

    echo json_encode($set_data);




}else if ($_REQUEST['method'] == 6){ // noti today all
 $wsserver   = URL_WS;
    $wsfolder	= '/alert_msg'; //กำหนด Folder
    $wsfile		= '/select_today.php'; //กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(    
                                'user_id'        =>  $_SESSION['login']['user_id'],
                                'group_id'        =>  $_SESSION['login']['group_id'],
                                'Limit'        =>  1000,
                                'method'        => 'GET'
                            );

 
    $data_return =	json_decode( post_to_ws($url,$data),true );

    $set_data['alert_noti'] ='';
    $set_data['count_all'] = 0;
    
    $detail = '';
    if($data_return['status'] == 200){
        $sum = 0;
        $result = $data_return['results'];
        foreach($result as $key => $value){
            if ($value['source'] == 'Cost' || $value['source'] == 'Cost2'  ){
                $detail =  $value['ser_code'].' ,Period ' .thai_date_short(strtotime($value['book_date'])). ' - '.thai_date_short(strtotime($value['s_date']));
                $set_data['alert_noti'] .= ' <li class="list-group-item clearfix">
               
                <div class="pull-right">
                    <button type="button" class="btn btn-primary btn-default" onclick="location.href=\'../manage_travel/manage_travel.php?series_id='.$value['book_code'].'\'">
                        <strong>ดูรายละเอียด</strong>
                    </button>
                </div>
                <p class="text-bold mb0">
                   '.$value['detail'].'
                </p>
                <small>
                <p class="text-bold mb0">
                   '.$detail.'
                </p>
                </small>
                </li>
                ';
            }else if ($value['source'] == 'room_list'){
                $detail =  'Period ' .thai_date_short(strtotime($value['book_date'])). ' - '.thai_date_short(strtotime($value['s_date']));
                $set_data['alert_noti'] .= ' <li class="list-group-item clearfix">
               
                <div class="pull-right">
                    <button type="button" class="btn btn-primary btn-default" onclick="location.href=\'../manage_period/manage_period.php?per_id='.$value['book_id'].'&bus_no='.$value['agen_id'].'\'">
                        <strong>ดูรายละเอียด</strong>
                    </button>
                </div>
                <p class="text-bold mb0">
                   '.$value['detail'].' '.$value['ser_code'].'
                </p>
                <small>
                <p class="text-bold mb0">
                   '.$detail.' Bus no : '.$value['agen_id'].'
                </p>
                </small>
                </li>
                ';
            }else{
                $detail = 'Booking code : ' .$value['book_code']. ' ,วันที่ครบกำหนดชำระ : '.d_m_y($value['s_date']). ' ,ยอดเงินที่ค้างชำระ : ' .number_format(intval($value['total']));
                $set_data['alert_noti'] .= ' <li class="list-group-item clearfix">
                <div class="pull-left mr">
                    Book Date : '.d_m_y($value['book_date']).'
                </div>
                <div class="pull-right">
                    <button type="button" class="btn btn-primary btn-default" onclick="location.href=\'../booking/manage_booking.php?book_id='.$value['book_id'].'&book_code='.$value['book_code'].'\'">
                        <strong>ดูรายละเอียด</strong>
                    </button>
                </div>
                <p class="text-bold mb0">
                   '.$value['detail'].'
                </p>
                <small>
                <p class="text-bold mb0">
                '.$detail.'
             </p>
                </small>
                </li>
                ';

            }  

            $sum = $sum + 1;
        }

    }
    $set_data['count_all'] = $sum;
    
    echo json_encode($set_data);


}








?>