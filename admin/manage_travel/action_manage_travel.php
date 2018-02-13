<?php
#include
include_once('../unity/main_core.php');
include_once('../unity/post_to_ws/post_to_ws.php');
include_once('../unity/php_script.php');

#REQUEST

#SET DATA RETURN

$set_data = array();


#METHOD

if($_REQUEST['method'] == 1){//select list

    #WS
    $wsserver   = URL_WS;
    $wsfolder	= '/series'; //กำหนด Folder
    $wsfile		= '/select_list_series.php'; //กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(    

                                'word_search'   => $_REQUEST['word_search'],
                                'country_id'    => $_REQUEST['country_id'],
                                'offset'        => $_REQUEST['offset'],
                                'limit'         => LIMIT,
                                'method'        => 'GET'
                            );

 
    $data_return =	json_decode( post_to_ws($url,$data),true );
    
    $set_data['all_row'] = intval($data_return['all_row']);
    $set_data['paginate_list'] = paginate_list( intval($data_return['all_row']) , $_REQUEST['offset'] , LIMIT ); // set page


    $thead = '<thead>
				<tr>
                    <th width="5%"> #</th>
                    <th width="20%">ชื่อซี่รีย์</th>
                    <th width="15%">Code</th>
                    <th width="10%">ประเทศ</th>
                    <th width="10%">สายการบิน</th>
                    <th width="10%">File</th>
                    <th width="10%">รูปภาพ</th>
                    <th width="10%"></th>
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

            $url_word = 'disabled';
            if(is_file($value['ser_url_word'])){
                $url_word = 'href="'.$value['ser_url_word'].'"';
            }

            $file_word = '<a '.$url_word.' type="button" class="mb-sm btn btn-primary btn-xs" target="_blank">ไฟล์ Word</a>';

            $url_pdf = 'disabled';
            if(is_file($value['ser_url_pdf'])){
                $url_pdf = 'href="'.$value['ser_url_pdf'].'"';
            }
            $file_pdf = '<a '.$url_pdf.' type="button" class="mb-sm btn btn-warning btn-xs" target="_blank">ไฟล์ PDF</a>';

            $url_img_1 = 'disabled';
            if(is_file($value['ser_url_img_1'])){
                $url_img_1 = 'href="'.$value['ser_url_img_1'].'"';
            }
            $img_1 = '<a '.$url_img_1.' type="button" class="mb-sm btn btn-info btn-xs" target="_blank">รูปภาพ 1</a>';

            $url_img_2 = 'disabled';
            if(is_file($value['ser_url_img_2'])){
                $url_img_2 = 'href="'.$value['ser_url_img_2'].'"';
            }
            $img_2 = '<a '.$url_img_2.' type="button" class="mb-sm btn btn-purple btn-xs" target="_blank">รูปภาพ 2</a>';

            $td = '<td class="text-center">'.number_format($count++).'</td>';
          //  $td .= '<td class="text-center">'.$status.'</td>';
            $td .= '<td class="text-center">'.$value['ser_name'].'</td>';
            $td .= '<td class="text-center">'.$value['ser_code'].'</td>';
            $td .= '<td class="text-center">'.$value['country_name'].'</td>';
            $td .= '<td class="text-center">'.$value['air_name'].'</td>';
            $td .= '<td class="text-center">'.$file_word.' '.$file_pdf.'</td>';
            $td .= '<td class="text-center">'.$img_1.' '.$img_2.'</td>';
            $td .= '<td class="text-center">
                        <button type="button" class="mb-sm btn btn-primary btn-xs" onclick="location.href=\'manage_travel.php?series_id='.$value['ser_id'].'\'">
                            <em class="icon-note"></em>
                            แก้ไข
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
else if($_REQUEST['method'] == 2){ // GET VALUE 

    #WS
    $wsserver   = URL_WS;
    $wsfolder	= '/series'; //กำหนด Folder
    $wsfile		= '/select_list_series_by_id.php'; //กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(    
                                'ser_id'            => $_REQUEST['ser_id'],
                                'method'            => 'GET'
                            );

 
    $data_return =	json_decode( post_to_ws($url,$data),true );

    if($data_return['status'] == 200){

        $result                 = $data_return['results'][0];
        
        $set_data['method']     = 4;
        $set_data['ser_id']     = $result['ser_id'];
        $set_data['ser_name']   = $result['ser_name'];
        $set_data['ser_code']   = $result['ser_code'];
        $set_data['series_description']   = $result['remark'];

        $set_data['ser_go_flight_code']   = $result['ser_go_flight_code'];
        $set_data['ser_go_route']   = $result['ser_go_route'];
        $set_data['ser_go_time']   = $result['ser_go_time'];
        $set_data['ser_return_flight_code']   = $result['ser_return_flight_code'];
        $set_data['ser_return_route']   = $result['ser_return_route'];
        $set_data['ser_return_time']   = $result['ser_return_time'];
        
        $set_data['country_id'] = $result['country_id'];
        $set_data['country_name'] = $result['country_name'];
        $set_data['air_id']         = $result['air_id'];
        $set_data['ser_deposit']    = intval($result['ser_deposit']);

        $set_data['ser_route']  = $result['ser_route'];
        $set_data['ser_show']  = $result['ser_show'];
        $set_data['status']  = $result['status'];

        $set_data['ser_href_img_1']  = 'javascript:;';
        $set_data['ser_href_img_2']  = 'javascript:;';
        $set_data['ser_href_word']   = 'javascript:;';
        $set_data['ser_href_pdf']    = 'javascript:;';

        $set_data['ser_url_img_1'] = '';
        $set_data['ser_url_img_2'] = '';
        $set_data['ser_url_word']  = '';
        $set_data['ser_url_pdf']   = '';

        $set_data['ser_is_promote'] = $result['ser_is_promote'];
        $set_data['ser_is_recommend'] = $result['ser_is_recommend'];

        if(is_file($result['ser_url_img_1'])){
            $set_data['ser_href_img_1'] = $result['ser_url_img_1'];
            $set_data['ser_url_img_1']  = $result['ser_url_img_1'];
        }
        if(is_file($result['ser_url_img_2'])){
            $set_data['ser_href_img_2'] = $result['ser_url_img_2'];
            $set_data['ser_url_img_2']  = $result['ser_url_img_2'];
        }
        if(is_file($result['ser_url_word'])){
            $set_data['ser_href_word']  = $result['ser_url_word'];
            $set_data['ser_url_word']   = $result['ser_url_word'];
        }
        if(is_file($result['ser_url_pdf'])){
            
            $set_data['ser_href_pdf']   = $result['ser_url_pdf'];
            $set_data['ser_url_pdf']    = $result['ser_url_pdf'];
        }


    }
    else{
        $set_data['method']     = 3;
    }

    echo json_encode($set_data);
    

}
else if($_REQUEST['method'] == 3){ // insert 


    #WS
    $wsserver   = URL_WS;
    $wsfolder	= '/series'; //กำหนด Folder
    $wsfile		= '/insert_series.php'; //กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(    
                                'status'            => $_REQUEST['status'],
                                'ser_name'          => $_REQUEST['series_name'],
                                'ser_code'          => $_REQUEST['series_code'],
                                'country_id'        => $_REQUEST['country'],
                                'air_id'            => $_REQUEST['airline'],
                                'ser_show'          => $_REQUEST['ser_show'],
                                'ser_route'         => $_REQUEST['series_route'],
                                'ser_deposit'       => $_REQUEST['series_deposit'],
                                'ser_url_img_1'     => $_REQUEST['series_img_1'],
                                'ser_url_img_2'     => $_REQUEST['series_img_2'],
                                'ser_url_img_3'     => '',
                                'ser_url_img_4'     => '',
                                'ser_url_img_5'     => '',
                                'ser_url_word'      => $_REQUEST['series_file_word'],
                                'ser_url_pdf'       => $_REQUEST['series_file_pdf'],
                                'remark'            => $_REQUEST['series_description'],

                                'ser_go_flight_code'            => $_REQUEST['ser_go_flight_code'],
                                'ser_go_route'            => $_REQUEST['ser_go_route'],
                                'ser_go_time'            => $_REQUEST['ser_go_time'],
                                'ser_return_flight_code'            => $_REQUEST['ser_return_flight_code'],
                                'ser_return_route'            => $_REQUEST['ser_return_route'],
                                'ser_return_time'            => $_REQUEST['ser_return_time'],

                                'ser_is_promote'            => $_REQUEST['ser_is_promote'],
                                'ser_is_recommend'            => $_REQUEST['ser_is_recommend'],
                                
                                'create_user_id'    => $_SESSION['login']['user_id'],
                                'method'            => 'PUT'
                            );

 
    $data_return =	json_decode( post_to_ws($url,$data),true );

    $set_data['ser_id']         = $data_return['results'];
    $set_data['status']         = $data_return['status'];
    $set_data['type_alert']     = 1;

    echo json_encode($set_data);


}
else if($_REQUEST['method'] == 4){ // update
    

    #WS
    $wsserver   = URL_WS;
    $wsfolder	= '/series'; //กำหนด Folder
    $wsfile		= '/update_series.php'; //กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(        
                                'ser_id'            => $_REQUEST['series_id'],
                                'status'            => $_REQUEST['status'],
                                'ser_name'          => $_REQUEST['series_name'],
                                'ser_code'          => $_REQUEST['series_code'],
                                'country_id'        => $_REQUEST['country'],
                                'air_id'            => $_REQUEST['airline'],
                                'ser_show'          => $_REQUEST['ser_show'],
                                'ser_route'         => $_REQUEST['series_route'],
                                'ser_deposit'       => $_REQUEST['series_deposit'],
                                'ser_url_img_1'     => $_REQUEST['series_img_1'],
                                'ser_url_img_2'     => $_REQUEST['series_img_2'],
                                'ser_url_img_3'     => '',
                                'ser_url_img_4'     => '',
                                'ser_url_img_5'     => '',
                                'ser_url_word'      => $_REQUEST['series_file_word'],
                                'ser_url_pdf'       => $_REQUEST['series_file_pdf'],
                                'remark'            => $_REQUEST['series_description'],

                                'ser_go_flight_code'            => $_REQUEST['ser_go_flight_code'],
                                'ser_go_route'            => $_REQUEST['ser_go_route'],
                                'ser_go_time'            => $_REQUEST['ser_go_time'],
                                'ser_return_flight_code'            => $_REQUEST['ser_return_flight_code'],
                                'ser_return_route'            => $_REQUEST['ser_return_route'],
                                'ser_return_time'            => $_REQUEST['ser_return_time'],

                                'ser_is_promote'            => $_REQUEST['ser_is_promote'],
                                'ser_is_recommend'            => $_REQUEST['ser_is_recommend'],

                                'update_user_id'    => $_SESSION['login']['user_id'],
                                'method'            => 'PUT'
                            );

 
    $data_return =	json_decode( post_to_ws($url,$data),true );


    $set_data['results']         = $_REQUEST['series_id'];
    $set_data['status']         = $data_return['status'];
    $set_data['type_alert']     = 2;

    echo json_encode($set_data);

}
else if($_REQUEST['method'] == 5){ // upload file
    
    $set_data['series_file_word']   = $_REQUEST['series_file_word_old'];
    $set_data['status_word']        = 'TRUE';


    $set_data['series_file_pdf']    = $_REQUEST['series_file_pdf_old'];
    $set_data['status_pdf']         = 'TRUE';

    $set_data['series_img_1']       = $_REQUEST['series_img_1_old'];
    $set_data['status_img_1']         = 'TRUE';

    $set_data['series_img_2']       = $_REQUEST['series_img_2_old'];
    $set_data['status_img_2']         = 'TRUE';

    if(isset($_FILES['series_file_word'])){
        
        if($_FILES['series_file_word']['size'] != 0){

            $path = $_FILES['series_file_word']['name'];
            $ext = pathinfo($path, PATHINFO_EXTENSION);

            $file_name = '../upload/travel/word_'.date('Y_m_d_H_I_s').'.'.$ext;

            if(move_uploaded_file($_FILES['series_file_word']['tmp_name'],$file_name)){
                $set_data['series_file_word']    = $file_name;
                $set_data['status_word']        = 'TRUE';
            }
            else{
                $set_data['status_word'] = 'FALSE';
            }

        }

    }

    if(isset($_FILES['series_file_pdf'])){
        
        if($_FILES['series_file_pdf']['size'] != 0){

            $path = $_FILES['series_file_pdf']['name'];
            $ext = pathinfo($path, PATHINFO_EXTENSION);

            $file_name = '../upload/travel/pdf_'.date('Y_m_d_H_I_s').'.'.$ext;

            if(move_uploaded_file($_FILES['series_file_pdf']['tmp_name'],$file_name)){
                $set_data['series_file_pdf']    = $file_name;
                $set_data['status_pdf'] = 'TRUE';
            }
            else{
                $set_data['status_pdf'] = 'FALSE';
            }

        }

    }


    if(isset($_FILES['series_img_1'])){
        
        if($_FILES['series_img_1']['size'] != 0){

            $path = $_FILES['series_img_1']['name'];
            $ext = pathinfo($path, PATHINFO_EXTENSION);

            $file_name = '../upload/travel/img_1_'.date('Y_m_d_H_I_s').'.'.$ext;

            if(move_uploaded_file($_FILES['series_img_1']['tmp_name'],$file_name)){
                $set_data['series_img_1']    = $file_name;
                $set_data['status_img_1'] = 'TRUE';
            }
            else{
                $set_data['status_img_1'] = 'FALSE';
            }

        }

    }

    if(isset($_FILES['series_img_2'])){
        
        if($_FILES['series_img_2']['size'] != 0){

            $path = $_FILES['series_img_2']['name'];
            $ext = pathinfo($path, PATHINFO_EXTENSION);

            $file_name = '../upload/travel/img_2_'.date('Y_m_d_H_I_s').'.'.$ext;

            if(move_uploaded_file($_FILES['series_img_2']['tmp_name'],$file_name)){
                $set_data['series_img_2']    = $file_name;
                $set_data['status_img_2'] = 'TRUE';
            }
            else{
                $set_data['status_img_2'] = 'FALSE';
            }

        }

    }

    echo json_encode($set_data);
}


else if($_REQUEST['method'] == 6){ // select list period

 
    #WS 
    $wsserver   = URL_WS;
    $wsfolder	= '/series'; //กำหนด Folder
    $wsfile		= '/select_list_period_by_ser_id.php'; //กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(        
                                'ser_id'            => $_REQUEST['series_id'],
                                
                                'method'            => 'GET'
                            );


    $data_return =	json_decode( post_to_ws($url,$data),true );


    $thead = '<thead>
				 <tr>
                    <th width="5%">#</th>
                    <th width="5%">Status</th>
                    <th width="15%">เดินทาง</th>
                    <th width="10%">จำนวนที่นั่ง</th>
                    <th width="8%" class="bg-info-light">ราคาผู้ใหญ่</th>
                    <th width="8%">เด็กมีเตียง</th>
                    <th width="8%">เด็กไม่มีเตียง</th>
                    <th width="8%">พักเดี่ยว</th>
                    <th width="8%">Infant</th>
                    <th width="8%">จอยแลนด์</th>
                    <th width="5%">ไฟไหม้</th>
                    <th width="5%"></th>
                </tr>
			</thead>';

    $set_data['btn_add_period'] = '<button type="button" class="mb-sm btn btn-green" onclick="manage_period('.$_REQUEST['series_id'].',\'\',\'add\')">
                                        <em class="icon-plus"></em>
                                        เพิ่มพีเรียด
                                    </button>';
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
       

        if($_REQUEST['series_id']==''){
            $set_data['btn_add_period'] = '';
            $tbody = '<tbody><tr>
                        <td colspan="20" style="text-align:center">
                           <div class="alert alert-info" role="alert">
                            <font style="font-size:25px">กรุณาเพิ่มข้อมูลซี่รีย์</font>
                            </div>
                        </td>
                    </tr>
                </tbody>';
        }
        else{
             $tbody = '<tbody><tr>
                        <td colspan="20" style="text-align:center">
                            <div class="alert alert-warning" role="alert">
                            <font style="font-size:25px">'.$non_txt.'</font>
                            </div>
                        </td>
                    </tr>
                </tbody>';
        }
    }
    else{
        
        $num_row = 1;
        $result = $data_return['results'];

        $tr = '';
        foreach($result as $key => $value){

            $no_fire = '<i class="fa fa-minus"></i>';
            if( !empty($value['per_on_fire']) ){
                $no_fire = '<i class="fa fa-check" style="color:red;"></i>';
            }

            $td = '<td class="text-center">'.number_format($num_row).'</td>';
            $td .= '<td class="text-center">'.html_status_period($value['status']).'</td>';
            $td .= '<td class="text-center">'.thai_date_short(strtotime($value['per_date_start'])).' - '.thai_date_short(strtotime($value['per_date_end'])).'</td>';
            $td .= '<td class="text-center">'.$value['per_qty_seats'].'</td>';
            $td .= '<td class="text-center bg-info-light">'.number_format($value['per_price_1']).'</td>';
            $td .= '<td class="text-center">'.number_format($value['per_price_2']).'</td>';
            $td .= '<td class="text-center">'.number_format($value['per_price_3']).'</td>';
            $td .= '<td class="text-center">'.number_format($value['single_charge']).'</td>';
            $td .= '<td class="text-center">'.number_format($value['per_price_4']).'</td>';
            $td .= '<td class="text-center">'.number_format($value['per_price_5']).'</td>';
            $td .= '<td class="text-center">'.$no_fire.'</td>';
            $td .= '<td class="text-right">
                        <button type="button" class="mb-sm btn btn-primary btn-xs" onclick="manage_period('.$_REQUEST['series_id'].','.$value['per_id'].',\'update\')">
                            <em class="icon-note"></em>
                            แก้ไข
                        </button>
                    </td>';



            $tr .= '<tr>'.$td.'</tr>';
            $num_row++;
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
else if($_REQUEST['method'] == 7){ // modal manage period

    $action_method = $_REQUEST['action_method'];


    if($action_method=='add'){
        $method = 8;
        $title_modal = 'เพิ่ม';
        $div_bus = '';
        $status_1 = 'checked';
        $status_3 = '';
        $status_9 = '';
        $status_10 = '';
        $per_on_fire = '';
        $visible_cost_ex = 'style="display: none;"';
    }
    else{
        $method = 9;
        $title_modal = 'แก้ไข';
        $div_bus = '';
        $visible_cost_ex = 'style="display: none;"';
        #WS 
        $wsserver   = URL_WS;
        $wsfolder	= '/series'; //กำหนด Folder
        $wsfile		= '/select_period_by_id.php'; //กำหนด File
        $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
        $data 		= array(        
                                    'per_id'            => $_REQUEST['period_id'],
                                    
                                    'method'            => 'GET'
                                );


        $data_return =	json_decode( post_to_ws($url,$data),true );


        if($data_return['status'] == 200){

            $result = $data_return['results'][0];

            $per_date_start         = d_m_y($result['per_date_start']);
            $per_date_end           = d_m_y($result['per_date_end']);
            $per_price_1            = intval($result['per_price_1']);
            $per_price_2            = intval($result['per_price_2']);
            $per_price_3            = intval($result['per_price_3']);
            $per_price_4            = intval($result['per_price_4']);
            $per_price_5            = intval($result['per_price_5']);
            $single_charge          = intval($result['single_charge']);
            $per_qty_seats          = intval($result['per_qty_seats']);
            $per_cost               = intval($result['per_cost']);
            $per_expenses           = intval($result['per_expenses']);
            $per_com_agency         = intval($result['per_com_agency']);
            $per_com_company_agency = intval($result['per_com_company_agency']);

            $per_href_word = '';
            $per_url_word = '';

            $per_href_pdf = '';
            $per_url_pdf = '';

            $per_href_cost = '';
            $per_url_cost = '';


            $status_1 = '';
            $status_3 = '';
            $status_9 = '';
            $status_10 = '';

             if($result['status'] == 1){
                $status_1 = 'checked';
                $status_3 = '';
                $status_9 = '';
                $status_10 = '';
                

            }else if($result['status'] == 3){
                $status_1 = '';
                $status_3 = 'checked';
                $status_9 = '';
                $status_10 = '';
                

            }else if($result['status'] == 9){
                $status_1 = '';
                $status_3 = '';
                $status_9 = 'checked';
                $status_10 = '';
                
            }else if($result['status'] == 10){
                $status_1 = '';
                $status_3 = '';
                $status_9 = '';
                $status_10 = 'checked';
                
            }else {
                $status_1 = 'checked';
                $status_3 = '';
                $status_9 = '';
                $status_10 = '';

            }

            $per_on_fire = !empty($result['per_on_fire']) ? 'checked="1"' : '';

            $bus_1_qty = $result['bus'][0]['bus_qty'];
            
            for($i = 1 ; $i< count($result['bus']);$i++){

                $div_bus .= '<div class="form-group">
                                    <label class="col-lg-3 control-label bus_order">Bus '.($i+1).' :</label>
                                    <div class="col-lg-9">
                                        <div class="input-group">
                                            <input type="text" id="bus_no1" name="bus_no[]" class="form-control input_num period_requried" value="'.$result['bus'][$i]['bus_qty'].'">
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-danger del_bus" >
                                                    <em class="icon-minus"></em> 
                                                </button>
                                            </span>
                                            
                                        </div>
                                    </div>
                                </div>';

            }


            $btn_attr_1 = 'href="javascript:;" disabled';
            $btn_attr_2 = 'href="javascript:;" disabled';
            $btn_cost_attr = 'href="javascript:;"';
            if(is_file($result['per_url_word'])){
                $per_href_word  = $result['per_url_word'];
                $per_url_word   = $result['per_url_word'];
                $btn_attr_1     = 'url="'.$per_url_word.'" href="'.$per_href_word.'"';
            }
            if(is_file($result['per_url_pdf'])){
                
                $per_href_pdf   = $result['per_url_pdf'];
                $per_url_pdf    = $result['per_url_pdf'];
                $btn_attr_2     = 'url="'.$per_href_pdf.'" href="'.$per_url_pdf.'"';
            }
            if(is_file($result['per_cost_file'])){
                
                $per_href_cost   = $result['per_cost_file'];
                $per_url_cost    = $result['per_cost_file'];
                $btn_cost_attr     = 'url="'.$per_href_cost.'" href="'.$per_url_cost.'"';
            }
            if ($_SESSION['login']['menu_17'] == 1){
                $visible_cost_ex = '';
            }

        }
    }

    $set_data['modal'] = '<div id="modal-manage-period" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" class="modal fade">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <button type="button" data-dismiss="modal" aria-label="Close" class="close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <h4 id="myModalLabel" class="modal-title">'.$title_modal.' พีเรียด</h4>
                                    </div>
                                    <div class="modal-body">

                                        <input type="hidden" value="'.$_REQUEST['period_id'].'" name="per_id" id="per_id">
                                        <form class="form-horizontal">
                                            <div class="form-group">
                                                <label class="col-lg-4 control-label">วันที่เดินทางไป :</label>
                                                <div class="col-lg-8">
                                                    <input type="text" id="period_date_start" name="period_date_start" class="form-control date period_requried" value="'.$per_date_start.'">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-lg-4 control-label">วันที่เดินทางกลับ :</label>
                                                <div class="col-lg-8">
                                                    <input type="text" id="period_date_end" name="period_date_end" class="form-control date period_requried" value="'.$per_date_end.'">
                                                </div>
                                            </div>

                                            <hr>
                                            <div class="form-group">
                                                
                                                <label class="col-lg-4 control-label">จำนวนที่นั่ง :</label>

                                                <div class="col-lg-8">
                                                    <div class="input-group">
                                                        <span class="input-group-btn">
                                                            <button type="button" class="btn btn-default btn-number" disabled="disabled" data-type="minus" data-field="per_qty_seats">
                                                                <span class="glyphicon glyphicon-minus"></span>
                                                            </button>
                                                        </span>
                                                        <input type="text" name="per_qty_seats" id="per_qty_seats" class="form-control input-number period_requried" value="'.$per_qty_seats.'" min="1" max="1000">
                                                        <span class="input-group-btn">
                                                            <button type="button" class="btn btn-default btn-number" data-type="plus" data-field="per_qty_seats">
                                                                <span class="glyphicon glyphicon-plus"></span>
                                                            </button>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                              <div class="form-group">
                                                <label class="col-lg-4 control-label bus_order">Bus 1 :</label>
                                                <div class="col-lg-8">
                                                    <input type="text" id="bus_no" name="bus_no[]" class="form-control input_num period_requried" value="'.$bus_1_qty.'">
                                                </div>
                                            </div>

                                            <div class="" id="contant_bus">

                                               '.$div_bus.'

                                            </div>
                                            
                                            <div class="form-group">
                                                <div class="col-lg-offset-4 col-lg-8">
                                                    <button type="button" class="btn btn-green btn-xs " id="btn_add_bus" onclick="add_bus()">
                                                        <em class="icon-plus"></em> 
                                                    </button>
                                                </div>
                                            </div>
                                            <hr>

                                            <div class="form-group">
                                                <label class="col-lg-4 control-label">ผู้ใหญ่เดียว :</label>
                                                <div class="col-lg-8">
                                                    <input type="text" id="period_price_1" name="period_price_1" class="form-control input_num period_requried" value="'.$per_price_1.'">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-lg-4 control-label">เด็กมีเตียง :</label>
                                                <div class="col-lg-8">
                                                    <input type="text" id="period_price_2" name="period_price_2" class="form-control input_num period_requried" value="'.$per_price_2.'">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-lg-4 control-label">เด็กไม่มีเตียง :</label>
                                                <div class="col-lg-8">
                                                    <input type="text" id="period_price_3" name="period_price_3" class="form-control input_num period_requried" value="'.$per_price_3.'">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-lg-4 control-label">Infant :</label>
                                                <div class="col-lg-8">
                                                    <input type="text" id="period_price_4" name="period_price_4" class="form-control input_num period_requried" value="'.$per_price_4.'">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-lg-4 control-label">จอยแลนด์ :</label>
                                                <div class="col-lg-8">
                                                    <input type="text" id="period_price_5" name="period_price_5" class="form-control input_num period_requried" value="'.$per_price_5.'">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-lg-4 control-label">Single charge :</label>
                                                <div class="col-lg-8">
                                                    <input type="text" id="single_charge" name="single_charge" class="form-control input_num period_requried" value="'.$single_charge.'">
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="form-group">
                                                <label class="col-lg-4 control-label">Agency Com :</label>
                                                <div class="col-lg-8">
                                                    <input type="text" id="period_commission_company_agency" name="period_commission_company_agency" class="form-control input_num period_requried" value="'.$per_com_company_agency.'">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-lg-4 control-label">Sales Com :</label>
                                                <div class="col-lg-8">
                                                    <input type="text" id="period_commission_agency" name="period_commission_agency" class="form-control input_num period_requried" value="'.$per_com_agency.'">
                                                </div>
                                            </div>
                                            <div class="form-group" '.$visible_cost_ex.' >
                                            <label class="col-lg-4 control-label">ราคาต้นทุน :</label>
                                            <div class="col-lg-8">
                                                <input type="text" id="period_cost" name="period_cost" class="form-control input_num " value="'.$per_cost.'">
                                            </div>
                                            </div>
                                            <div class="form-group" '.$visible_cost_ex.'>
                                            <label class="col-lg-4 control-label">ค่าใช้จ่ายอื่น ๆ  :</label>
                                            <div class="col-lg-8">
                                                <input type="text" id="period_expenses" name="period_expenses" class="form-control input_num " value="'.$per_expenses.'">
                                            </div>
                                            </div>
                                           
                                            <hr>
                                           <div class="form-group">
                                                <label class="col-lg-4 control-label">Status  :</label>
                                                   <div class="col-sm-8">
                                            <div class="radio c-radio">
                                            <label>
                                         <input type="radio" name="travel_status" class="status_1" value="1" '.$status_1.'>
                                            <span class="fa fa-circle"></span>
                                                ใช้งาน(เปิดจอง)
                                            </label>
                                            </div>
                                            <div class="radio c-radio">
                                                <label>
                                                    <input type="radio" name="travel_status" class="status_3" value="3" '.$status_3.'>
                                                        <span class="fa fa-circle"></span>
                                                        ปิดทัวร์
                                                    </label>
                                            </div>
                                             
                                            <div class="radio c-radio">
                                                <label>
                                                    <input type="radio" name="travel_status" class="status_9" value="9" '.$status_9.'>
                                                        <span class="fa fa-circle"></span>
                                                        ระงับการใช้งาน
                                                    </label>
                                            </div>
                                             <div class="radio c-radio">
                                                <label>
                                                    <input type="radio" name="travel_status" class="status_10" value="10" '.$status_10.'>
                                                        <span class="fa fa-circle"></span>
                                                        ตัดตั๋ว
                                                    </label>
                                            </div>
                                        </div>
                                        </div>
                                            <hr>
                                            <div class="form-group">
                                                <label class="col-lg-4 control-label">โปรโมชั่น : </label>
                                                <div class="col-lg-8">
                                                    <div class="checkbox">
                                                        <label><input type="checkbox" id="per_on_fire" name="per_on_fire" value="1" '.$per_on_fire.'> โปรไฟไหม้</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <hr>
                                            <div class="form-group">
                                            <label class="col-lg-4 control-label">แนบไฟล์ใบต้นทุน(Cost) :</label>
                                            <div class="col-lg-8">
                                            <div class="input-group">
                                            <span class="input-group-btn">
                                            <a href="javascript:manage_upload('.$_REQUEST['period_id'].');" type="button" class="btn btn-default btn-success " target="_blank">
                                            อัพโหลดไฟล์
                                             </a>
                                            <a '.$btn_cost_attr.' id="per_cost_file" class="btn btn-default btn-primary" target="_blank">ดาวน์โหลด</a>
                                            
                                             </span>
                                             
                                            <span class="input-group-btn">
                                            </span>
                                            </div>
                                            </div>
                                        </div>

                                            <hr>
                                            <div class="form-group">
                                            <label class="col-lg-4 control-label">ไฟล์เตรียมตัวเดินทาง Word  :</label>
                                                <div class="col-lg-8">

                                                    <div class="input-group">
                                                        <input type="file" data-classbutton="btn btn-default" id="per_url_word" name="per_url_word" data-classinput="form-control inline" class="form-control filestyle" accept=".doc, .docx">
                                                        <span class="input-group-btn">
                                                            <a '.$btn_attr_1.' id="per_url_word_old" class="btn btn-default btn-primary"  target="_blank">ดาวน์โหลด</a>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-lg-4 control-label">ไฟล์เตรียมตัวเดินทาง PDF :</label>
                                                
                                                <div class="col-lg-8">
                                                    <div class="input-group">
                                                        <input type="file" data-classbutton="btn btn-default" id="per_url_pdf" name="per_url_pdf" data-classinput="form-control inline" class="form-control filestyle" accept="application/pdf">
                                                        <span class="input-group-btn">
                                                            <a '.$btn_attr_2.' id="per_url_pdf_old" class="btn btn-default btn-primary" target="_blank">ดาวน์โหลด</a>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                        </form>


                                    </div>


                                    <div class="modal-footer">
                                        <button type="button" data-dismiss="modal" class="btn btn-default">ปิด</button>
                                        <button type="button" class="btn btn-primary" onclick="check_submit_period('.$_REQUEST['series_id'].','.$method.')">
                                            <em class="icon-cursor"></em>
                                            บันทีก
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>';

    echo json_encode($set_data);

}
else if($_REQUEST['method'] == 8){ // inser period


    #WS 
    $wsserver   = URL_WS;
    $wsfolder	= '/series'; //กำหนด Folder
    $wsfile		= '/insert_period.php'; //กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(        
                                'per_date_start'                => Y_m_d($_REQUEST['period_date_start']),
                                'per_date_end'                  => Y_m_d($_REQUEST['period_date_end']),
                                'per_price_1'                   => $_REQUEST['period_price_1'],
                                'per_price_2'                   => $_REQUEST['period_price_2'],
                                'per_price_3'                   => $_REQUEST['period_price_3'],
                                'per_price_4'                   => $_REQUEST['period_price_4'],
                                'per_price_5'                   => $_REQUEST['period_price_5'],
                                'single_charge'                   => $_REQUEST['single_charge'],
                                'per_qty_seats'                 => $_REQUEST['per_qty_seats'],
                                'per_cost'                      => $_REQUEST['period_cost'],
                                'per_expenses'                  => $_REQUEST['period_expenses'],
                                'status'                        => $_REQUEST['status'],
                                'ser_id'                        => $_REQUEST['series_id'],
                                'remark'                        => '',
                                'per_url_word'                  => $_REQUEST['per_url_word'],
                                'per_url_pdf'                   => $_REQUEST['per_url_pdf'],

                                'bus_order_arr'                 => $_REQUEST['bus_order_arr'],
                                'bus_qty_arr'                   => $_REQUEST['bus_qty_arr'],

                                'per_com_company_agency'        => $_REQUEST['period_commission_company_agency'],
                                'per_com_agency'                => $_REQUEST['period_commission_agency'],
                                'create_user_id'                => $_SESSION['login']['user_id'],

                                'per_on_fire'                   => $_REQUEST['per_on_fire'],
                                'method'                        => 'PUT'
                            );

    $data_return =	json_decode( post_to_ws($url,$data),true );

    $set_data['status']         = $data_return['status'];
    $set_data['type_alert']     = 1;

    echo json_encode($set_data);

}
else if($_REQUEST['method'] == 9){//update period

    #WS 
    $wsserver   = URL_WS;
    $wsfolder	= '/series'; //กำหนด Folder
    $wsfile		= '/update_period.php'; //กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(        
                                'per_id'                        => $_REQUEST['period_id'],
                                'per_date_start'                => Y_m_d($_REQUEST['period_date_start']),
                                'per_date_end'                  => Y_m_d($_REQUEST['period_date_end']),
                                'per_price_1'                   => $_REQUEST['period_price_1'],
                                'per_price_2'                   => $_REQUEST['period_price_2'],
                                'per_price_3'                   => $_REQUEST['period_price_3'],
                                'per_price_4'                   => $_REQUEST['period_price_4'],
                                'per_price_5'                   => $_REQUEST['period_price_5'],
                                'per_qty_seats'                 => $_REQUEST['per_qty_seats'],
                                'per_cost'                      => $_REQUEST['period_cost'],
                                'per_expenses'                  => $_REQUEST['period_expenses'],
                                'single_charge'                 => $_REQUEST['single_charge'],
                                'status'                        => $_REQUEST['status'],
                                'ser_id'                        => $_REQUEST['series_id'],
                                'remark'                        => '',
                                'per_url_word'                  => $_REQUEST['per_url_word'],
                                'per_url_pdf'                   => $_REQUEST['per_url_pdf'],

                                'bus_order_arr'                 => $_REQUEST['bus_order_arr'],
                                'bus_qty_arr'                   => $_REQUEST['bus_qty_arr'],

                                'per_com_company_agency'        => $_REQUEST['period_commission_company_agency'],
                                'per_com_agency'                => $_REQUEST['period_commission_agency'],
                                'create_user_id'                => $_SESSION['login']['user_id'],

                                'per_on_fire'                   => $_REQUEST['per_on_fire'],
                                'method'                        => 'PUT'
                            );
        
    $data_return =	json_decode( post_to_ws($url,$data),true );

    $set_data['status']         = $data_return['status'];
    $set_data['type_alert']     = 2;

    echo json_encode($set_data);

}

else if($_REQUEST['method'] == 10){ // upload file period
    
   
    $set_data['per_url_word']   = $_REQUEST['per_url_word_old'];
    $set_data['status_word']        = 'TRUE';


    $set_data['per_url_pdf']    = $_REQUEST['per_url_pdf_old'];
    $set_data['status_pdf']         = 'TRUE';



    if(isset($_FILES['per_url_word'])){
        
        if($_FILES['per_url_word']['size'] != 0){

            $path = $_FILES['per_url_word']['name'];
            $ext = pathinfo($path, PATHINFO_EXTENSION);

            $file_name = '../upload/travel/word_per_'.date('Y_m_d_H_I_s').'.'.$ext;

            if(move_uploaded_file($_FILES['per_url_word']['tmp_name'],$file_name)){
                $set_data['per_url_word']       = $file_name;
                $set_data['status_word']        = 'TRUE';
            }
            else{
                $set_data['status_word'] = 'FALSE';
            }

        }

    }

    if(isset($_FILES['per_url_pdf'])){
        
        if($_FILES['per_url_pdf']['size'] != 0){

            $path = $_FILES['per_url_pdf']['name'];
            $ext = pathinfo($path, PATHINFO_EXTENSION);

            $file_name = '../upload/travel/pdf_per_'.date('Y_m_d_H_I_s').'.'.$ext;

            if(move_uploaded_file($_FILES['per_url_pdf']['tmp_name'],$file_name)){
                $set_data['per_url_pdf']    = $file_name;
                $set_data['status_pdf'] = 'TRUE';
            }
            else{
                $set_data['status_pdf'] = 'FALSE';
            }

        }

    }



    echo json_encode($set_data);
}

else if($_REQUEST['method'] == 11){ // add bus

    $set_data['bus_input'] = ' <div class="form-group">
                                    <label class="col-lg-3 control-label bus_order">Bus '.$_REQUEST['order'].' :</label>
                                    <div class="col-lg-9">
                                        <div class="input-group">
                                            <input type="text" id="bus_no1" name="bus_no[]" class="form-control input_num period_requried" value="">
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-danger del_bus" >
                                                    <em class="icon-minus"></em> 
                                                </button>
                                            </span>
                                            
                                        </div>
                                    </div>
                                </div>';

    echo json_encode($set_data);

}
else if($_REQUEST['method'] == 20){// get model upload file
    
        $per_id = $_REQUEST['per_id'];
    
        $set_data['modal'] = '<div id="modal-manage-uploadfile" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" class="modal fade">
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
                                                            <input type="file" class="form-control" id="file_cost" name="file_cost" value="">
                                                        </div>
                                                    </div>
                                                    
                                                </div>
    
    
                                            </div>
    
                                           
    
                                            <div class="modal-footer">
                                                <button type="button" data-dismiss="modal" class="btn btn-default">ปิด</button>
                                                <button type="button" class="btn btn-primary" onclick="check_upload_file_cost()">
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
        
            if($_FILES['file_cost']['size'] > 0){
        
                $path = $_FILES['file_cost']['name'];
                $ext = pathinfo($path, PATHINFO_EXTENSION);
        
                $file_name = '../upload/travel/cost_file_'.date('Y_m_d_H_I_s').'.'.$ext;
        
                if(move_uploaded_file($_FILES['file_cost']['tmp_name'],$file_name)){
                    $set_data['file_cost']    = $file_name;
                    $set_data['status']        = 'TRUE';
        
        
                    #WS
                    $wsserver   = URL_WS;
                    $wsfolder	= '/series'; //กำหนด Folder
                    $wsfile		= '/update_cost_file.php'; //กำหนด File
                    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
                    $data 		= array(    
        
                                                'per_id'       => $_REQUEST['per_id'],
                                                'per_cost_file'     => $set_data['file_cost'],
                                                'method'        => 'PUT'
                                            );
        


                
                    $data_return =	json_decode( post_to_ws($url,$data),true );

                    

                    $set_data['status']         = $data_return['status'];
                    $set_data['type_alert']     = 2;

                      // insert_alert
             // source  100booking = insert booking
             //         101payment = insert payment
             //         102app_payment = approve payment
             //         103cxl_payment = not approve payment
             //         104file_cost = att file cost

                $wsserver   = URL_WS;                   # ../unity/post_to_ws/config.php
                $wsfolder	= '/alert_msg';      # กำหนด Folder
                $wsfile		= '/insert_alert.php';     # กำหนด File
                $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
                $data 		= array(
                            'book_id'                 => $_REQUEST['per_id'], // period id
                            'detail'                 => 'แนบไฟล์ใบต้นทุน(Cost)',
                            'source'                 => '104file_cost',
                            'pay_id'                 => $_SESSION['login']['user_id'], // ผู้ทำรายการ
                            'user_id'                 => $_SESSION['login']['user_id'],
                            'method'            => 'PUT'
                            );
                $data_return =	json_decode( post_to_ws($url,$data),true );                       
                }
                else{
                    
                }
        
        
            }
        
            echo json_encode($set_data);
            
        
}
else if ($_REQUEST['method'] == 22){ // select cost file
    $wsserver   = URL_WS;
    $wsfolder	= '/series'; //กำหนด Folder
    $wsfile		= '/select_cost_file.php'; //กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(    

                                'per_id'       => $_REQUEST['per_id'],
                                'method'        => 'GET'
                            );


    $data_return =	json_decode( post_to_ws($url,$data),true );
    if($data_return['status'] == 200){
        $result = $data_return['results'][0];
        if(is_file($result['per_cost_file'])){
            $set_data['per_cost_file']   = $result['per_cost_file'];
        }else{
            $set_data['per_cost_file']   = 'disabled';
            
        }
    }

    echo json_encode($set_data);
    
}
else{

}











?>