<?php
#include
include_once('../unity/main_core.php');
include_once('../unity/post_to_ws/post_to_ws.php');
include_once('../unity/php_script.php');

include_once('../unity/php_send_email.php');
#REQUEST

#SET DATA RETURN

$set_data = array();


#METHOD

if($_REQUEST['method'] == 1){//select list

  #WS
  $wsserver   = URL_WS;
  $wsfolder	= '/manage_payment'; //กำหนด Folder
  $wsfile		= '/select_list_payment.php'; //กำหนด File
  $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
  $data 		= array(    
    'word_search'   => $_REQUEST['word_search'],
    'status_20'      => $_REQUEST['status_20'],
    'status_25'      => $_REQUEST['status_25'],
    'status_30'      => $_REQUEST['status_30'],
    'status_35'      => $_REQUEST['status_35'],
    'status_40'      => $_REQUEST['status_40'],

    'status_payment_0'      => $_REQUEST['status_payment_0'],
    'status_payment_1'      => $_REQUEST['status_payment_1'],
    'status_payment_9'      => $_REQUEST['status_payment_9'],
    
    'country_id'      => $_REQUEST['country_id'],
    
    'date_start'    => Y_m_d($_REQUEST['date_start']),
    'date_end'      => Y_m_d($_REQUEST['date_end']),


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
                              <th>Invoice no</th>
                              <th>รหัสซี่รี่ย์</th>
                              <th>Period</th>
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
          if(is_file($value['pay_url_file'])){
              $url_img_1 = 'href="'.$value['pay_url_file'].'"';
          }
          $img_1 = '<a '.$url_img_1.'  target="_blank">FILE</a>';

          $btnapprove = '';
          $disabled_edit = '';

          
          if($_SESSION['login']['menu_11'] == 1){

          
              if ($value['status'] == 1){
                  $disabled_edit = 'disabled';
                  $btnapprove = '         <td class="text-center">
                                              
                                                  <button type="button" class="mb-sm btn btn-danger btn-xs" onclick="cancel_approve_payment('.$value['pay_id'].','.$value['book_id'].','.$value['user_id'].')">
                                                      <em class="icon-close"></em>
                                                      ไม่อนุมัติ
                                                  </button>
                                              </td> ';
              }else{
                      $btnapprove = '         <td class="text-center">
                                                  <button type="button" class="mb-sm btn btn-success btn-xs" onclick="approve_payment('.$value['pay_id'].','.$value['book_status'].','.$value['book_id'].','.$value['user_id'].')">
                                                      <em class="icon-check"></em>
                                                      อนุมัติ
                                                  </button>
                                                  <button type="button" class="mb-sm btn btn-danger btn-xs" onclick="cancel_approve_payment('.$value['pay_id'].','.$value['book_id'].','.$value['user_id'].')">
                                                      <em class="icon-close"></em>
                                                      ไม่อนุมัติ
                                                  </button>
                                              </td> ';
              }
          
          }else {
               if ($value['status'] == 1){
                      $disabled_edit = 'disabled';
               }
               if ($_REQUEST['source'] == 'detail'){
                  $disabled_edit = 'disabled';
                  
               }
          }
          
          $td = '<td class="text-center">'.number_format($count++).'</td>';
          $td .= '<td class="text-center">'.html_status_payment($value['status']).'</td>';
          $td .= '<td class="text-center">'.$img_1.'</td>';
          $td .= '<td class="text-center">'.$value['invoice_code'].'</td>';
          $td .= '<td class="text-center">'.$value['ser_code'].'</td>';
          $td .= '<td class="text-center">'.thai_date_short(strtotime($value['per_date_start'])).' - '.thai_date_short(strtotime($value['per_date_end'])).'</td>';
          $td .= '<td class="text-center">'.$value['bankbook_code'].'</td>';
          $td .= '<td class="text-right bg-success-dark">'.number_format(intval($value['pay_received'])).'</td>';
          $td .= '<td class="text-center">'.d_m_y($value['pay_date']).'</td>';
          $td .= '<td class="text-center">'.$value['pay_time'].'</td>';
          $td .= '<td class="text-center">'.$value['action_name'].'</td>';
          $td .= '<td class="text-center">'.d_m_y($value['create_date']).'</td>';
          $td .= '<td class="text-center">'.html_status_book($value['book_status']).'</td>';

          $td .= ' 
                                          '.$btnapprove.'
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
                            'detail'                 => 'อนุมัติการชำระเงิน BN : ',
                            'source'                 => '102app_payment',
                            'pay_id'                 => $_REQUEST['pay_id'],
                            'user_id'                 => $_REQUEST['user_id'],
                            'method'            => 'PUT'
                            );
      $data_return =	json_decode( post_to_ws($url,$data),true );            


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
                                'remark_cancel'             => $_REQUEST['remark_cancel'],

                                
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
                            'detail'                 => 'ไม่อนุมัติการชำระเงิน BN : ',
                            'source'                 => '103cxl_payment',
                            'pay_id'                 => $_REQUEST['pay_id'],
                            'user_id'                 => $_REQUEST['user_id'],
                            'remark'                 => '',
                            'method'            => 'PUT'
                            );
      $data_return =	json_decode( post_to_ws($url,$data),true );   



    send_email_payment_cancel($_REQUEST['book_id'],$_REQUEST['pay_id']);
    
    echo json_encode($set_data);

}
else if($_REQUEST['method'] == 22){ // model cancel apporove
    

    $set_data['modal'] = '
    <div id="modal-manage-cancel-pay" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" class="modal fade">
     <div class="modal-dialog">
         <div class="modal-content">
             <div class="modal-header">
             <button type="button" data-dismiss="modal" aria-label="Close" class="close">
                 <span aria-hidden="true">&times;</span>
             </button>
             
             <h4 id="myModalLabel" class="modal-title text-center">ยืนยันการไม่อนุมัติ</h4>
             </div>
             <div class="modal-body">
                   <input type="hidden" value="'.$_REQUEST['pay_id'].'" name="pay_id" id="pay_id">
                 <div class="form-horizontal">

                    <div class="form-group">
                        <font class="col-lg-3 text-right" color="red" style="font-size:15px" >ไม่ผ่านการอนุมัติ* :</font>
                        <div class="col-lg-7">
                            <textarea rows="5" cols="" class="form-control" id ="txtremark_payment_cancel" name = "txtremark_payment_cancel" required ></textarea>
                        </div>
                    </div>
                   
                 </div>


             </div>
             <div class="modal-footer">
             
                 <button type="button" data-dismiss="modal" class="btn btn-default">ปิด</button>
                 <button type="button" class="btn btn-primary" onclick="check_cancel_approve('.$_REQUEST['pay_id'].','.$_REQUEST['book_id'].','.$_REQUEST['user_id'].')">
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
?>