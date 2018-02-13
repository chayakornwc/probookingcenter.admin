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
    $wsfolder	= '/noti_booking'; //กำหนด Folder
    $wsfile		= '/select_list_noti_booking.php'; //กำหนด File
    $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		= array(    

                                'word_search'       => $_REQUEST['word_search'],
                                'status_0'          => $_REQUEST['status_0'],
                                'status_5'          => $_REQUEST['status_5'],
                                'date_start'      => Y_m_d($_REQUEST['date_start']) . ' 00:00:00',
                                'date_end'      => Y_m_d($_REQUEST['date_end']) . ' 23:59:59',




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
                                <th>Booking By</th>
                                <th>เบอร์โทรศัพท์</th>
                                <th>Line ID</th>
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
            $td .= '<td class="text-center">'.$value['agen_name'].'</td>';
            $td .= '<td class="text-center">'.$value['agen_tel'].'</td>';
            $td .= '<td class="text-center">'.$value['agen_line_id'].'</td>';
            $td .= ' <td class="text-center">
                                    <button type="button" class="mb-sm btn btn-primary btn-xs" onclick="location.href=\'../booking/manage_booking.php?book_id='.$value['book_id'].'&book_code='.$value['book_code'].'\'">
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
else if($_REQUEST['method'] == 2){ // modal manage 

    #REQUEST

    $action_method = $_REQUEST['action_method'];

    if($action_method == 'add'){

        $title_modal        = 'เพิ่ม';
        $user_group_id      = '';
        $user_group_name    = '';
        $method             = 3;

        $air_status_1      = 'checked';
        $air_status_2      = '';

    }
    else{
        
        $title_modal        = 'แก้ไข';
        $user_group_id      = '';
        $user_group_name    = '';
        $method             = 4;

        #WS
        $wsserver   = URL_WS;                   # ../unity/post_to_ws/config.php
        $wsfolder	= '/manage_airline';      # กำหนด Folder
        $wsfile		= '/select_by_id_airline.php';     # กำหนด File
        $url 		= $wsserver.$wsfolder.$wsfile; // กำหนด URL
        $data 		= array(
                                'air_id'        => $_REQUEST['air_id'],
                                'method'          => 'GET'
                                );

        $data_return =	json_decode( post_to_ws($url,$data),true );

        
        if($data_return['status'] == 200){

            $result = $data_return['results'][0];

            $air_id      = $result['air_id'];
            $air_name    = $result['air_name'];
            $air_img     = $result['air_url_img'];

            
            if($result['status'] == 1){
                $air_status_1      = 'checked';
                $air_status_2      = '';
            }
            else if($result['status'] == 2){
                $air_status_1      = '';
                $air_status_2      = 'checked';
            }


        }

        
       

    }
    


    $set_data['modal'] = ' <div id="modal-manage-air" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" class="modal fade">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <button type="button" data-dismiss="modal" aria-label="Close" class="close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <h4 id="myModalLabel" class="modal-title">'.$title_modal.'สายการบิน</h4>
                                    </div>
                                    <div class="modal-body">

                                        <input type="hidden" value="'.$air_id.'" id="air_id" name="air_id">

                                        <input type="hidden" value="'.$air_name.'" id="air_name_old" name="air_name_old">
                                        <input type="hidden" value="'.$air_img.'" id="air_img_old" name="air_img_old">

                                        <input type="hidden" value="" id="air_url_img" name="air_url_img">

                                        <div class="form-horizontal">

                                            <div class="form-group">
                                                <label class="col-lg-2 control-label">ชื่อสายการบิน :</label>
                                                <div class="col-lg-8">
                                                    <input type="text" class="form-control" id="air_name" name="air_name" value="'.$air_name.'">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-lg-2 control-label">รูปสายการบิน :</label>
                                                <div class="col-lg-10">
                                                    <input type="file" data-classbutton="btn btn-default" accept="image/*" data-classinput="form-control inline" class="form-control filestyle" id="air_img" name="air_img">
                                                </div>
                                            </div>
                        
                                            <div class="form-group">
                                            <label class="col-sm-2 control-label">Status : </label>
                                            <div class="col-sm-10">
                                                <div class="radio c-radio">
                                                    <label>
                                                        <input type="radio" name="air_status" class="status_1" value="1" '.$air_status_1.'>
                                                        <span class="fa fa-circle"></span>
                                                            ใช้งาน
                                                        </label>
                                                </div>
                                                <div class="radio c-radio">
                                                    <label>
                                                        <input type="radio" name="air_status" class="status_2" value="2" '.$air_status_2.'>
                                                        <span class="fa fa-circle"></span>
                                                            ระงับการใช้งาน
                                                        </label>
                                                </div>
                                            </div>
                                            </div>

                                            
                                        </div>


                                    </div>


                                    <div class="modal-footer">
                                        <button type="button" data-dismiss="modal" class="btn btn-default">ปิด</button>
                                        <button type="button" class="btn btn-primary" onclick="check_submit_air('.$method.')">
                                            <em class="icon-cursor"></em>
                                            บันทีก
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>';


    echo json_encode($set_data);

}
else if($_REQUEST['method'] == 3){ // insert 


    #WS
    $wsserver   =   URL_WS;                   # ../unity/post_to_ws/config.php
    $wsfolder	=   '/manage_airline';      # กำหนด Folder
    $wsfile		=	'/insert_airline.php';     # กำหนด File
    $url 		=	$wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		=	array(  
                            'air_name'          => $_REQUEST['air_name'],
                            'air_url_img'       => $_REQUEST['air_url_img'],
                            'create_user_id'    => $_SESSION['login']['user_id'],
                            'status'            => $_REQUEST['status'],
                            'remark'            => '',
                            'method'            => 'PUT'
                            );

    $data_return =	json_decode( post_to_ws($url,$data),true );

    $set_data['data_return']    = $data_return;
    $set_data['status']         = $data_return['status'];
    $set_data['type_alert']     = 1;

    echo json_encode($set_data);

}
else if($_REQUEST['method'] == 4){ // update
    
    

    #WS
    $wsserver   =   URL_WS;                   # ../unity/post_to_ws/config.php
    $wsfolder	=   '/manage_airline';      # กำหนด Folder
    $wsfile		=	'/update_airline.php';     # กำหนด File
    $url 		=	$wsserver.$wsfolder.$wsfile; // กำหนด URL
    $data 		=	array(  

                            'air_id'            => $_REQUEST['air_id'],
                            'air_name'          => $_REQUEST['air_name'],
                            'air_url_img'       => $_REQUEST['air_url_img'],
                            'status'            => $_REQUEST['status'],
                            'update_user_id'    => $_SESSION['login']['user_id'],
                            
                            'remark'            => '',
                            'method'            => 'PUT'
                            );

    $data_return =	json_decode( post_to_ws($url,$data),true );

    $set_data['data_return']    = $data_return;
    $set_data['status']         = $data_return['status'];
    $set_data['type_alert']     = 2;

    echo json_encode($set_data);


}
else if($_REQUEST['method'] == 5){ //check group 



    if($_REQUEST['air_name'] != $_REQUEST['air_name_old']){ #check name


        #WS
        $wsserver   =   URL_WS;                   # ../unity/post_to_ws/config.php
        $wsfolder	=   '/manage_airline';      # กำหนด Folder
        $wsfile		=	'/check_airname.php';     # กำหนด File
        $url 		=	$wsserver.$wsfolder.$wsfile; // กำหนด URL
        $data 		=	array(  
                                'air_name'      => $_REQUEST['air_name'],
                                'method'          => 'GET'
                                );

        $data_return =	json_decode( post_to_ws($url,$data),true );

        $set_data['status_air_name'] = $data_return['results'];

    }
    else{

        $set_data['status_air_name'] = 'TRUE';

    }


    

    $set_data['url_img']    = $_REQUEST['air_img_old'];
    $set_data['status_img'] = 'TRUE';

    if($_FILES['air_img']['size'] != 0){

        $path = $_FILES['air_img']['name'];
        $ext = pathinfo($path, PATHINFO_EXTENSION);

        $file_name = '../upload/img_air/air_'.date('Y_m_d_H_I_s').'.'.$ext;

        if(move_uploaded_file($_FILES['air_img']['tmp_name'],$file_name)){
            $set_data['url_img']    = $file_name;
            $set_data['status_img'] = 'TRUE';
        }
        else{
            $set_data['status_img'] = 'FALSE';
        }

    }

 
    echo json_encode($set_data);




}
else{

}
















?>