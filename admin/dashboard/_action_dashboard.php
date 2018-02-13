<?php
#include
include_once('../unity/main_core.php');
include_once('../unity/post_to_ws/post_to_ws.php');
include_once('../unity/php_script.php');

#REQUEST

#SET DATA RETURN

$set_data = array();


#METHOD

if($_REQUEST['method'] == 1){//select ยอดขาย agency 10 อันดับ

    #WS
   
    $wsserver   = URL_WS;
    $wsfolder	= '/dashboard'; //กำหนด Folder
    $wsfile		= '/select_list_sale.php'; //กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(    

                               
                                'method'        => 'GET'
                            );

 
      $data_return =	json_decode( post_to_ws($url,$data),true );
      $result     = $data_return['results'];

    //--------------------- tmp ws
    $data_return2 = array();
    $data_return2['result2'] = array();
    foreach($result as $key => $value){
        $data_return2['result2'][] = array(
                                        $value['iName'],
                                        intval($value['Total'])
                                        );
    }

    //--------------------- tmp ws

    $set_data['obj_sale'] = array(
            'id'                => 'chart_top_sale',
            'title'             => 'กราฟแสดงยอดขาย Sale 10 อันดับ',
            'tooltip_format'    => '<b> จำนวน {point.y} บาท</b>',
            'color'             => '#fad732',
            'data'              => $data_return2['result2']

    );


    #WS
   
    $wsserver   = URL_WS;
    $wsfolder	= '/dashboard'; //กำหนด Folder
    $wsfile		= '/select_list_agent.php'; //กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(    

                               
                                'method'        => 'GET'
                            );

 
      $data_return =	json_decode( post_to_ws($url,$data),true );
      $result     = $data_return['results'];

    //--------------------- tmp ws
    $data_return2 = array();
    $data_return2['result2'] = array();
    foreach($result as $key => $value){
        $data_return2['result2'][] = array(
                                        $value['iName'],
                                        intval($value['Total'])
                                        );
    }

    //--------------------- tmp ws

      $set_data['obj_agency'] = array(
            'id'                => 'chart_top_agency',
            'title'             => 'กราฟแสดงยอดขาย Agency 10 อันดับ',
            'tooltip_format'    => '<b> จำนวน {point.y} บาท</b>',
            'color'             => '#f763eb',
            'data'              => $data_return2['result2']

    );

    #WS
   
    $wsserver   = URL_WS;
    $wsfolder	= '/dashboard'; //กำหนด Folder
    $wsfile		= '/select_list_ser.php'; //กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(    

                               
                                'method'        => 'GET'
                            );

 
      $data_return =	json_decode( post_to_ws($url,$data),true );
      $result     = $data_return['results'];

    //--------------------- tmp ws
    $data_return2 = array();
    $data_return2['result2'] = array();
    foreach($result as $key => $value){
        $data_return2['result2'][] = array(
                                        $value['iName'],
                                        intval($value['Total'])
                                        );
    }

    //--------------------- tmp ws

      $set_data['obj_series'] = array(
            'id'                => 'chart_top_series',
            'title'             => 'กราฟแสดงยอดขาย Series  10 อันดับ',
            'tooltip_format'    => '<b> จำนวน {point.y} บาท</b>',
            'color'             => '#58ceb1',
            'data'              => $data_return2['result2']

    );



    #WS
   
    $wsserver   = URL_WS;
    $wsfolder	= '/dashboard'; //กำหนด Folder
    $wsfile		= '/select_list_12mon.php'; //กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(    

                               
                                'method'        => 'GET'
                            );

 
      $data_return =	json_decode( post_to_ws($url,$data),true );
      $result     = $data_return['results'];

    //--------------------- tmp ws
    $data_return2 = array();
    $data_return2['result2'] = array();
    foreach($result as $key => $value){
        $data_return2['result2'][] = array(
                                        intval($value['total'])
                                        );
    }


    #line all year 
    $set_data['sale_all_year'] = array(
            'id'                => 'chart_sale_all_year',
            'title'             => 'กราฟแสดงยอดทั้ง ปี '.date("Y"),
            'categories'        => array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'),
            'data'              => array(   
                                            array(
                                                'name' => 'ยอดขาย',
                                                'data' => $data_return2['result2']
                                            )
                                        )
    );
 

    echo json_encode($set_data);




}else if ($_REQUEST['method'] == 2){ //getdata
  #WS
  $wsserver   = URL_WS;
  $wsfolder	= '/dashboard'; //กำหนด Folder
  $wsfile		= '/select_list_pro.php'; //กำหนด File
  $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
  $data 		= array(    
                                'offset'        => $_REQUEST['offset'],
                                'limit'         => LIMIT,
                              'method'        => 'GET'
                          );


  $data_return =	json_decode( post_to_ws($url,$data),true );
  $set_data['all_row'] = intval($data_return['all_row']);
  $set_data['paginate_list'] = paginate_list( intval($data_return['all_row']) , $_REQUEST['offset'] , LIMIT ); // set page




  $thead = '<thead>
               <tr>
                  <th width="5%">#</th>
                  <th width="5%" class="bg-danger-light">รหัสซีรีย์</th>
                  <th width="15%">เดินทาง</th>
                  <th width="5%">BUS</th>
                  <th width="8%" class="bg-info-light">ราคาผู้ใหญ่</th>
                  <th width="8%">เด็กมีเตียง</th>
                  <th width="8%">เด็กไม่มีเตียง</th>
                  <th width="8%">พักเดี่ยว</th>
                  <th width="8%">Infant</th>
                  <th width="8%">จอยแลนด์</th>
                  <th width="8%">ที่นั่ง</th>
                  <th width="5%">จอง</th>
                  <th width="5%" class="bg-success-light">รับได้</th>
                  <th width="8%" style="min-width:170px"></th>
              </tr>
          </thead>';

  if($data_return['status'] != 200){
      if($data_return['status']==503){
          $non_txt = 'เกิดข้อผิดพลาดระหว่างการเข้าถึง เซิร์ฟเวอร์';
      }
      else if($data_return['status'] == 204){
          $non_txt = 'ไม่พบข้อมูล';
      }
      else{
          $non_txt = 'การเชื่อมต่อเซิฟเวอร์ผิดพลาด';
      }
      $div_ser = ' <div class="col-lg-12">
                      <div class="alert alert-warning text-center" role="alert">
                          <font style="font-size:25px">'.$non_txt.'</font>
                      </div>
                  </div>';
  }
  else{

      $count		= ($_REQUEST['offset']*LIMIT)+1;
      $result     = $data_return['results'];
      $tr         = '';
    
          foreach($result as $key => $value){
              $btnaction = '';

              if (intval($value['qty_receipt']) != 0) {
                
                  $btnaction = ' <td class="text-center">
                              <button type="button" class="mb-sm btn btn-green btn-xs" onclick="location.href=\'../booking/manage_booking.php?per_id='.$value['per_id'].'&bus_no='.$value['bus_no'].'\'">
                                  <em class="icon-plus"></em>
                                  จอง
                              </button>
                              <button type="button" class="mb-sm btn btn-primary btn-xs" onclick="location.href=\'../manage_period/manage_period.php?per_id='.$value['per_id'].'&bus_no='.$value['bus_no'].'\'">
                                  <em class="icon-note"></em>
                                  ดูรายละเอียด
                              </button>
                              
                          </td>';
      

                   $td = '';
  

                  $td .= '<td class="text-center">'.number_format($count++).'</td>';
                  $td .= '<td class="text-center  bg-danger-light">'.$value['ser_code'].'</td>';
                  $td .= '<td class="text-center">'.thai_date_short(strtotime($value['per_date_start'])).' - '.thai_date_short(strtotime($value['per_date_end'])).'</td>';
                  $td .= '<td class="text-center">'.number_format($value['bus_no']).'</td>';
                  $td .= '<td class="text-center bg-info-light">'.number_format(intval($value['per_price_1'])).'</td>';
                  $td .= '<td class="text-center ">'.number_format(intval($value['per_price_2'])).'</td>';
                  $td .= '<td class="text-center ">'.number_format(intval($value['per_price_3'])).'</td>';
                  $td .= '<td class="text-center ">'.number_format(intval($value['single_charge'])).'</td>';
                  $td .= '<td class="text-center ">'.number_format(intval($value['per_price_4'])).'</td>';
                  $td .= '<td class="text-center ">'.number_format(intval($value['per_price_5'])).'</td>';
                  $td .= '<td class="text-center">'.number_format(intval($value['per_qty_seats'])).'</td>';
                  $td .= '<td class="text-center">'.number_format(intval($value['qty_book'])).'</td>';
                  $td .= '<td class="text-center bg-success-light">'.number_format(intval($value['qty_receipt'])).'</td>';
                  $td .= $btnaction;
          

                   $tr .= '<tr>'.$td.'</tr>';
                  
          }
        }
        
           $tbody = '<tbody>'.$tr.'</tbody>';



          $div_ser .= '
                      <div class="col-lg-12">
                          <div id="panelDemoRefresh2" class="panel panel-default">
                              <div class="panel-heading">
                                  <em class="icon-pin"></em>
                                  <font class="pink" size="6"><strong> โปรดันขาย </strong></font>
                                  
                              </div>  
                              <div class="panel-body">
                                  <div class="table-responsive">
                                      <table id="" class="table table-bordered table-hover table-devgun">
                                      '.$thead.'
                                      '.$tbody.'
                                      </table>
                                  </div>
                              </div>
                          </div>
                      </div>';



  }




  $set_data['interface_table'] = ' <div class="row">'.$div_ser.'</div>';
  echo json_encode($set_data);








}else if($_REQUEST['method'] == 99){ //get total
  #REQUEST

     #WS
    $wsserver   = URL_WS;
    $wsfolder	= '/dashboard'; //กำหนด Folder
    $wsfile		= '/select_sum.php'; //กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(    
                                'method'        => 'GET'
                            );

 
    $data_return =	json_decode( post_to_ws($url,$data),true );


                                                  
  if($data_return['status'] == 200){

        $result = $data_return['results'][0];
        $set_data['sumtotal']             = number_format(intval($result['sumtotal']));
        $set_data['sumqty']             = number_format(intval($result['sumqty']));
        $set_data['sumperiod']           = number_format(intval($result['sumperiod']));

    }
    echo json_encode($set_data);

}














?>